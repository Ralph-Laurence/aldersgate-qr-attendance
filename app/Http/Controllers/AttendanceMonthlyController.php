<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\AttendanceController;
use App\Models\Base\Attendance;
use Illuminate\Http\Request;

class AttendanceMonthlyController extends AttendanceController
{
    public function index()
    {
        return view('backoffice.attendance.monthly.index');
    }

    public function saveAttendance(Request $request, Attendance $model)
    {
        
    }
}
