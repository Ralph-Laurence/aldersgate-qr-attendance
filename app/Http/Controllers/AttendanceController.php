<?php

namespace App\Http\Controllers;

use App\Http\Extensions\Utils;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{ 
    /**
     * By default, all records shown are from the 1st day
     * of current month upto the last day.
     */
    public function index() 
    {  
        return view('backoffice.attendance.index')
            ->with('monthlyAttendance', $this->getMonthly())
            ->with('dailyAttendance', $this->getToday());
    }
    /**
     * Base query builder for getting attendance data without condition
     */
    private function getAttendanceBase()
    {
        $queryBuilder = DB::table('attendance as a')
            ->leftJoin('students as s', 's.id', '=', 'a.student_fk_id')
            ->leftJoin('courses as c',  'c.id', '=', 's.course_id')
            ->select
            (
                's.student_no', 's.firstname', 's.middlename', 's.lastname', 's.year', 's.photo',
                'c.course', 'a.time_in', 'a.time_out', 'a.attendance_date', 'a.status'
            );

        return $queryBuilder;
    }
    
    private function getToday()
    {
        $dataset = $this->getAttendanceBase()
            ->whereDate('a.created_at', Carbon::today())
            ->get();

        $this->fixPhotoPath($dataset);

        return $dataset;
    }

    private function getMonthly()
    {
        $from = Carbon::now()->startOfMonth();
        $upto = Carbon::now()->endOfMonth();

        $dataset = $this->getAttendanceBase()
            ->whereBetween('a.created_at', [$from, $upto] )
            ->get();

        $this->fixPhotoPath($dataset);

        return $dataset;
    }

    private function fixPhotoPath($dataset) : void
    {
        for ($i = 0; $i < count($dataset); $i++) 
        {
            $row = $dataset[$i];

            if ($row->photo)
                $row->photo = Utils::getPhotoPath($row->photo);

            $dataset[$i] = $row;
        }
    }
}
