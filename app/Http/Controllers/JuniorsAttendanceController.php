<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\AttendanceController;
use App\Http\Extensions\Routes;
use App\Models\Base\Attendance;
use App\Models\Base\Student;
use App\Models\JuniorsAttendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class JuniorsAttendanceController extends AttendanceController
{
    private $attendance     = null;
    private $studentType    = null;
    private $landingRoute   = null;
    private $tabRoutes      = [];

    public function __construct() 
    {
        $this->attendance   = new JuniorsAttendance();
        $this->studentType  = Student::STUDENT_LEVEL_JUNIORS;
        $this->landingRoute = route( Routes::ATTENDANCE['index'] );
    }

    public function index($sort = null)
    {
        $options    = ['sort' => $sort, 'mode' => Attendance::MODE_DAILY];
        $dataset    = $this->attendance->getAttendance($options, $this->studentType);

        return view('backoffice.attendance.juniors.index')
                ->with('attendanceDataset'  , $dataset)
                ->with('totalRecords'       , $dataset->count())
                ->with('backPage'           , $this->landingRoute)
                ->with('worksheetTabRoutes' , $this->getWorksheetTabRoutes());
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
        
        return view('backoffice.attendance.juniors.weekly')
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

        return view('backoffice.attendance.juniors.monthly')
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
                'today'   => route( Routes::ATTENDANCE_JUNIORS['index'] ),
                'weekly'  => route( Routes::ATTENDANCE_JUNIORS['weekly'] ),
                'monthly' => route( Routes::ATTENDANCE_JUNIORS['monthly']),
                'alltime' => ''
            ];
        }

        return $this->tabRoutes;
    }

    public function saveAttendance(Request $request, Attendance $model)
    {

    }
}
