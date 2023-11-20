<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use App\Http\Extensions\RegexPatterns;
use App\Http\Extensions\Routes;
use App\Http\Extensions\Utils;
use App\Http\Extensions\ValidationMessages;
use App\Models\Base\Attendance;
use App\Models\CollegeStudent;
use App\Models\ElementaryStudent;
use App\Models\JuniorStudent;
use App\Models\SeniorStudent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

abstract class AttendanceController extends Controller
{
    public const MODE_CREATE = 0;
    public const MODE_UPDATE = 1;
    
    public const MSG_SUCCESS_DELETE         = 'Student record and attendance history successfully deleted.';
    public const MSG_SUCCESS_ADDED          = 'Student has been successfully added to the records.';
    public const MSG_SUCCESS_UPDATED        = 'Student record has been successfully updated.';
    
    public const MSG_FAIL_INDEX_EMAIL       = 'Email is already in use.';
    public const MSG_FAIL_INDEX_USERNAME    = 'Username is already in use.';

    abstract protected function saveAttendance(Request $request, Attendance $model);

    public function update(Request $request)
    {
        return $this->saveModel($request, self::MODE_UPDATE);
    }
 
    public function store(Request $request)
    {
        return $this->saveModel($request, self::MODE_CREATE);
    }
}