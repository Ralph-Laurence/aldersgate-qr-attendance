<?php

namespace App\Http\Controllers;

use App\Http\Extensions\Utils;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{ 
    private $attendanceModel = null;

    private const FILTER_COURSE_FREQUENT = 1;
    private const FILTER_COURSE_LEAST = 0;

    function __construct()
    {
        $this->attendanceModel = new Attendance();
    }

    /**
     * By default, all records shown are from the 1st day
     * of current month upto the last day.
     */
    public function index() 
    {  
        $indicatorData = [];

        $monthly = $this->getMonthly();
        $monthly = $this->beautifyDataset($monthly, $indicatorData);
        
        return view('backoffice.attendance.index')
            ->with('monthlyAttendance', $monthly)
            ->with('indicatorData', $indicatorData);
    }
    /**
     * Base query builder for getting attendance data without condition
     */
    private function getAttendanceBase()
    {
        $queryBuilder = DB::table('attendance as a')
            ->leftJoin('students as s', 's.id', '=', 'a.student_fk_id')
            ->leftJoin('courses as c',  'c.id', '=', 's.course_id')
            ->select
            (
                's.student_no', 's.firstname', 's.middlename', 's.lastname', 's.year', 's.photo',
                'c.course', 'a.time_in', 'a.time_out', 'a.attendance_date', 'a.status'
            );

        return $queryBuilder;
    }
    
    private function getMonthly()
    {
        $from = Carbon::now()->startOfMonth();
        $upto = Carbon::now()->endOfMonth();

        $dataset = $this->getAttendanceBase()
            ->whereBetween('a.created_at', [$from, $upto] )
            ->get();

        return $dataset;
    }

    private function beautifyDataset($dataset, &$indicatorData)
    { 
        $distinctStudentsMonthly = array();
        $distinctStudentsDaily = array();
        $totalTimedInToday = 0;
        $totalDailyAttendance = 0;
 
        for ($i = 0; $i < count($dataset); $i++) 
        {
            $row = $dataset[$i];

            // Exclude empty rows
            if (empty((array)$row))
                continue;

            // Count disctinct students per month
            if (!in_array($row->student_no, $distinctStudentsMonthly))
                $distinctStudentsMonthly[] = $row->student_no;

            if ($row->time_out) 
            {
                // If there is a timeout field, we can assume that there is an
                // existing timein field ; then we can compute the stay duration
                $row->stay_duration = $this->attendanceModel->getStayDuration($row->time_in, $row->time_out);

                // Format the timeout field
                $row->time_out = Utils::formatTimestamp($row->time_out, 'g:i A');
            }
             
            // Format the timein field
            $row->time_in = Utils::formatTimestamp($row->time_in, 'g:i A');

            // Process daily attendance
            if ($row->attendance_date == Utils::dateToday())
            {
                $totalDailyAttendance++;

                // Count the timed in students
                if ($row->status == Attendance::STATUS_VAL_TIMED_IN)
                    $totalTimedInToday++;

                // Count disctinct students
                if (!in_array($row->student_no, $distinctStudentsDaily))
                    $distinctStudentsDaily[] = $row->student_no;
            }

            // Fix photo path
            if ($row->photo)
                $row->photo = Utils::getPhotoPath($row->photo);

            // Set time in / timeout badge
            if ($row->status)
            { 
                $statusBadge = 'status-in';             // default <span> class

                if ($row->status == Attendance::STATUS_VAL_TIMED_OUT)
                    $statusBadge = 'status-out';

                $row->statusBadge = $statusBadge;       // add <span> class to collection

                $row->status = ucfirst($row->status);   // Capitalize the time in status text
            }

            // Fix the name as one fullname
            $row->name = implode(" ", [ $row->lastname . ",", $row->firstname, $row->middlename ]);

            // Update the current row
            $dataset[$i] = $row;
        }

        // Find the frequent courses from this day
        $frequentCourses = $this->findCourseFrequency($dataset, 0, self::FILTER_COURSE_FREQUENT);

        // Out parameters
        $indicatorData = [
            'totalStudentsMonthly'  => count($distinctStudentsMonthly),
            'totalStudentsDaily'    => count($distinctStudentsDaily),
            'totalRecordsMonthly'   => count($dataset),
            'totalRecordsDaily'     => $totalDailyAttendance,
            'totalTimedInToday'     => $totalTimedInToday,
            'frequentCourses'       => $frequentCourses
        ];

        return $dataset;
    }

    /**
     * Finds the most frequent or least frequent course according
     * to the mode applied (0 = Least, 1 = Most) and the date 
     * filter mode applied (0 = Daily, 1 = Monthly)
     */
    private function findCourseFrequency($dataset, $dateFilterMode = 1, $mode = 1)
    {
        // Date Filtering
        if ($dateFilterMode == 0) 
        {
            $dataset = $dataset->where('attendance_date', Utils::dateToday());
        } 
        else if ($dateFilterMode == 1) 
        {
            $from = Carbon::now()->startOfMonth();
            $to = Carbon::now()->endOfMonth();

            $dataset = $dataset->whereBetween('attendance_date', [$from, $to]);
        }

        // Frequency Filtering
        $frequentCourses = $dataset->pluck('course')
            ->countBy()
            ->{ $mode == 0 ? 'sort' : 'sortDesc' }()
            ->take(10);

        return $frequentCourses;
    }
}
