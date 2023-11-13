<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CollegeAttendanceController extends Controller
{
    public function index()
    {
        return view('backoffice.attendance.college.index');
    }
}
