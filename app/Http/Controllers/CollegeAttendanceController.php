<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\AttendanceController;
use App\Http\Extensions\Routes;
use App\Models\Base\Attendance;
use App\Models\Base\Student;
use App\Models\CollegeAttendance;
use App\Models\CollegeStudent;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CollegeAttendanceController extends AttendanceController
{
    private $attendance     = null;
    private $studentType    = null;
    private $landingRoute   = null;
    private $tabRoutes      = [];

    public function __construct() 
    {
        $this->attendance   = new CollegeAttendance();
        $this->studentType  = Student::STUDENT_LEVEL_COLLEGE;
        $this->landingRoute = route( Routes::ATTENDANCE['index'] );
    }

    public function index($sort = null)
    {
        $options    = ['sort' => $sort, 'mode' => Attendance::MODE_DAILY];
        $dataset    = $this->attendance->getAttendance($options, $this->studentType);

        return view('backoffice.attendance.college.index')
                ->with('attendanceDataset'  , $dataset)
                ->with('totalRecords'       , $dataset->count())
                ->with('backPage'           , $this->landingRoute)
                ->with('worksheetTabRoutes' , $this->getWorksheetTabRoutes())
                ->with('formActions', 
                [
                    'storeAttendance'  => route(Routes::ATTENDANCE_COLLEGE['store']  ),
                    // 'updateAttendance' => route(Routes::SENIOR_STUDENT['update'] ),
                    // 'deleteAttendance' => route(Routes::SENIOR_STUDENT['destroy']),
                ])
                ->with('datalistAsyncRoute' , route(Routes::ASYNC['college_datalist']));
    }

    
    public function showWeekly($sort = null)
    {
        $options    = ['sort' => $sort, 'mode' => Attendance::MODE_WEEKLY];
        $dataset    = $this->attendance->getAttendance($options, $this->studentType);
        
        $week       = Carbon::now();
        $weekHint   = [
            'weekNo'   => $week->weekOfYear,
            'weekFrom' => $week->startOfWeek()->format('F d'),
            'weekTo'   => $week->endOfWeek()->format('F d')
        ];
        
        return view('backoffice.attendance.college.weekly')
               ->with('attendanceDataset'  , $dataset)
               ->with('totalRecords'       , $dataset->count())
               ->with('backPage'           , $this->landingRoute)
               ->with('weekHint'           , $weekHint)
               ->with('worksheetTabRoutes' , $this->getWorksheetTabRoutes());
    }
    
    public function showMonthly($sort = null)
    {
        $options    = ['sort' => $sort, 'mode' => Attendance::MODE_MONTHLY];
        $dataset    = $this->attendance->getAttendance($options, $this->studentType);
        
        $month      = Carbon::now();
        $monthHint  = [
            'monthStart' => $month->startOfMonth()->format('F d'),
            'monthEnd'   => $month->endOfMonth()->format('F d')
        ];

        return view('backoffice.attendance.college.monthly')
               ->with('attendanceDataset'  , $dataset)
               ->with('totalRecords'       , $dataset->count())
               ->with('backPage'           , $this->landingRoute)
               ->with('monthHint'          , $monthHint)
               ->with('worksheetTabRoutes' , $this->getWorksheetTabRoutes());
    }
    
    public function getWorksheetTabRoutes()
    {
        if ( empty($this->tabRoutes) )
        {
            $this->tabRoutes = 
            [
                'today'   => route( Routes::ATTENDANCE_COLLEGE['index'] ),
                'weekly'  => route( Routes::ATTENDANCE_COLLEGE['weekly'] ),
                'monthly' => route( Routes::ATTENDANCE_COLLEGE['monthly']),
                'alltime' => ''
            ];
        }

        return $this->tabRoutes;
    }

    public function saveAttendance(Request $request, Attendance $model)
    {

    }

    public function store(Request $request)
    {
        $inputs = $this->validateFields($request);

        // Check if validation failed and a 'redirect' response was returned
        if ($inputs instanceof \Illuminate\Http\RedirectResponse)
            return $inputs;

        try
        {
            $studentNo = $inputs['input-student-no'];

            // Find the student using his student number
            $student = CollegeStudent::where(CollegeStudent::FIELD_STUDENT_NUM, '=', $studentNo)->first();

            // Make sure that the student really exists
            if (!$student)
                abort(500);

            $data = 
            [
                CollegeAttendance::FIELD_STUDENT_FK => $student->id,
                CollegeAttendance::FIELD_TIME_IN    => $inputs['input-time-in']
            ];

            CollegeAttendance::create($data);
        }
        catch (QueryException $ex)
        {

        }

        // if (empty($studentNo) || empty($timeIn))

        //return $this->saveModel($request, self::MODE_CREATE);
    }

    // public function index()
    // {
    //     return view('backoffice.attendance.college.index');
    // }
}
