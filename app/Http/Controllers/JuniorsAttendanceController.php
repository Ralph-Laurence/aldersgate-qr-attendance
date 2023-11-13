<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JuniorsAttendanceController extends Controller
{
    public function index()
    {
        return view('backoffice.attendance.juniors.index');
    }
}
