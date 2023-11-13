<?php

namespace App\Http\Controllers;

use App\Http\Extensions\Routes;
use App\Models\Base\Attendance;
use App\Models\ElemAttendance;
use App\Models\JuniorsAttendance;
use App\Models\SeniorsAttendance;
use App\Models\TertiaryAttendance;
use Illuminate\Http\Request;

class AttendanceHomeController extends Controller
{
    public function __construct() 
    {
    
    }

    public function index()
    {
        $props =
        [
            'elem'      => [ 'link' => route(Routes::ATTENDANCE_ELEM['index']) , 'count' => ElemAttendance::count() ],
            'juniors'   => [ 'link' => route(Routes::ATTENDANCE_JUNIORS['index']) , 'count' => JuniorsAttendance::count()  ],
            'seniors'   => [ 'link' => route(Routes::ATTENDANCE_SENIORS['index']) , 'count' => SeniorsAttendance::count()  ],
            'college'   => [ 'link' => route(Routes::ATTENDANCE_COLLEGE['index']) , 'count' => TertiaryAttendance::count() ],
        ];

        return view('backoffice.attendance.index')
            ->with('props', $props);
    }
}
