<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SeniorsAttendanceController extends Controller
{
    public function index()
    {
        return view('backoffice.attendance.seniors.index');
    }
}
