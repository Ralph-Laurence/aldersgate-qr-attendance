<?php

namespace App\Http\Controllers;

use App\Http\Extensions\Utils;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QRScannerController extends Controller
{
    private $attendanceModel = null;

    public function __construct()
    {
        $this->attendanceModel = new Attendance();
    }

    public function index()
    {
        // Get today's attendance data
        $attendanceData = $this->attendanceModel->getTodaysAttendance();

        // Store here distinct student ids. We will use this for counting
        // how many students had created attendance. If a student has two
        // or more timein details, we will count him as one.
        $distinctTimeinIds = [];

        $totalRows = $attendanceData->count();

        // Process every row data
        for ($i = 0; $i < $totalRows;  $i++) 
        {
            // Reference to current iterated row
            $row = $attendanceData[$i];

            // Exclude empty rows
            if (empty((array)$row))
                continue;

            if (!in_array($row->student_no, $distinctTimeinIds))
                $distinctTimeinIds[] = $row->student_no;
 
            if ($row->time_out) 
            {
                // If there is a timeout field, we can assume that there is an
                // existing timein field then we can compute the stay duration
                $row->stay_duration = $this->attendanceModel->getStayDuration($row->time_in, $row->time_out);

                // Format the timeout field
                $row->time_out = Utils::formatTimestamp($row->time_out, 'g:i A');
            }
            
            // Format the timein field
            if ($row->time_in) 
                $row->time_in = Utils::formatTimestamp($row->time_in, 'g:i A');

            // Fix photo path
            if ($row->photo)
                $row->photo = Utils::getPhotoPath($row->photo);

            // Apply the updated data to original collection row
            $attendanceData[$i] = $row;
        }

        return view("qrscanner.index")->with('attendanceData', $attendanceData)
            ->with('totalStudents', count($distinctTimeinIds))
            ->with('totalRecords', $totalRows);
    }

    // 1. Select attendance of target user for this day
        // 2. begin Check if an attendance record exists
        // 3. If time out is null, the user is checking out
        // 4. if time out is NOT null, the user has already checked in and out today
    //    [SHOW CAPTCHA]
    // 5. end if NO attendance record exists, the user is checking in
    public function update(Request $request)
    {
        // Get the input from ajax then Validate it  
        $validator = Validator::make($request->all(), [
            'studentNo' => 'required|string|max:16'
        ]);

        // Return a failed message when validation fails
        if ($validator->fails())
            return Utils::makeFailResponse(Utils::STATUS_CODE_INVALID_QR_CONTENT);

        // Validated student ref number
        $studentReferenceNumber = $validator->validated()['studentNo'];
 
        // Make sure that the student exists from the records (i.e. record id != null) 
        $student_foreignKey = DB::table('students')
            ->where(Student::FIELD_STUDENT_NUM, $studentReferenceNumber)
            ->value('id');

        if (empty($student_foreignKey) || is_null($student_foreignKey)) {
            return Utils::makeFailResponse(Utils::STATUS_CODE_UNRECOGNIZED_STUDENT_NO);
        }

        // 1. Select attendance of target user for this day
        $recentAttendance = $this->attendanceModel->checkAttendanceExists($student_foreignKey);//$this->attendanceModel->findLatestAttendance($student_foreignKey);
  
        // 2. Check if an attendance record exists
        if (!is_null($recentAttendance))
        {
            error_log('time out => ' . $recentAttendance->time_out);

            // 3. If time out is null, the user is checking out
            if (is_null($recentAttendance->time_out) || empty($recentAttendance->time_out))
            {
                $timeout = $this->attendanceModel->setTimedOut($student_foreignKey);

                if (!$timeout)
                    return Utils::makeFailResponse(Utils::STATUS_CODE_TIMED_OUT_FAILED);
                  
                return Utils::makeResponse(
                    Utils::STATUS_CODE_TIMED_OUT, 
                    "Time out recorded", 
                    $this->returnModelAfterUpdate($student_foreignKey)
                ); 
            }

            // 4. if time out is NOT null, the user has already checked in and out today
            //    [SHOW CAPTCHA]
            else
            { 
                return json_encode([
                    'message' => 'Already timed in and out',
                    'status'  => '0x14'
                ]);
            }
        }

        // 5. end if NO attendance record exists, the user is checking in
        else
        {
            $create = $this->attendanceModel->createAttendanceData($student_foreignKey);

            // After creating his attendance data, return it as view data
            if ($create)
            {
                $statTimeIn = Utils::STATUS_CODE_TIMED_IN;

                $viewData = $this->attendanceModel->findAttendance($create->id, $create->student_fk_id);
                $viewData->photo = Utils::getPhotoPath($viewData->photo);

                return Utils::makeResponse(
                    $statTimeIn, 
                    Utils::getStatusMessage($statTimeIn), 
                    $viewData
                ); 
            }
            else
            {
                return Utils::makeFailResponse(Utils::STATUS_CODE_CREATE_ATTENDANCE_FAILED); 
            }
        }
    }

    private function returnModelAfterUpdate($student_foreignKey)
    {
        $updatedModel = DB::table('attendance as a')
                ->where('a.student_fk_id', $student_foreignKey)
                ->where('a.attendance_date', Utils::dateToday())
                ->leftJoin('students as s', 's.id', '=', 'a.student_fk_id')
                ->select('a.time_in', 'a.time_out', 's.student_no')
                ->orderByDesc('a.created_at')
                ->first();

        return $updatedModel;
    }
}
