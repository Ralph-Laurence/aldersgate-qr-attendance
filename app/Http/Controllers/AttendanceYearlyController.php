<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\AttendanceController;
use App\Models\Base\Attendance;
use Illuminate\Http\Request;

class AttendanceYearlyController extends AttendanceController
{
    public function index()
    {
        return view('backoffice.attendance.yearly.index');
    }

    public function saveAttendance(Request $request, Attendance $model)
    {
        
    }
}
