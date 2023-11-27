<?php

namespace App\Models;

use App\Models\Base\Attendance;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CollegeAttendance extends Attendance
{
    use HasFactory;

    public static function getTableName()
    {
        return (new self)->getTable();
    }

    public function createAttendance($studentId, $timeIn, $timeOut = null)
    {
        
    }
}