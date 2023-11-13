<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\AttendanceController;
use App\Http\Extensions\Routes;
use App\Models\Base\Attendance;
use Illuminate\Http\Request;

class AttendanceTodayController extends AttendanceController
{
    private $attendanceModel = null;

    function __construct()
    {
        $this->attendanceModel = new Attendance();
    }

    public function index($sort = null) 
    {
        $options    = ['sort' => $sort];
        $privilege  = UAC::ROLE_MASTER;
        
        $dataset    = $this->attendanceModel->getUsers($options, $privilege);
         
        return view('backoffice.attendance.today.index')
            ->with('usersDataset'   , $dataset)
            ->with('totalRecords'   , $dataset->count())
            ->with('formActions', 
            [
                'storeAttendance'  => route( Routes::ATTENDANCE_TODAY['store'] ),
                'updateAttendance' => route( Routes::ATTENDANCE_TODAY['update']),
                'deleteAttendance' => route( Routes::ATTENDANCE_TODAY['destroy']),
            ])
            ->with('worksheetTabRoutes', $this->getWorksheetTabRoutesExcept('today'));
    }

    public function saveAttendance(Request $request, Attendance $model)
    {
        
    }
}
