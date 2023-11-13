<?php

namespace App\Models;

use App\Models\Base\Attendance;
use App\Models\Base\Student;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ElemAttendance extends Attendance
{
    use HasFactory;

    public static function getTableName()
    {
        return (new self)->getTable();
    }
}
