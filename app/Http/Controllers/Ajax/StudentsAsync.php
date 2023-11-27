<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\CollegeStudent;
use App\Models\Courses;
use App\Models\ElementaryStudent;
use Illuminate\Support\Facades\DB;

class StudentsAsync extends Controller
{
    /**
     * This will be used to populate the <li> of datalist
     */
    public function getElemDatalist()
    {
        $lname  = ElementaryStudent::FIELD_LNAME;
        $fields = 
        [
            ElementaryStudent::FIELD_STUDENT_NUM,
            ElementaryStudent::FIELD_GRADE_LEVEL,
            ElementaryStudent::FIELD_FNAME,
            ElementaryStudent::FIELD_MNAME,
            $lname,
        ];

        $studentData = ElementaryStudent::select($fields)
                    ->orderBy($lname, 'asc')
                    ->get();

        $data = [];

        foreach ($studentData as $obj)
        {
            $data[$obj->student_no] = 
            [
                'name'  => implode(' ', [$obj->lastname . ",", $obj->middlename, $obj->firstname]),
                'grade' => $obj->grade_level
            ];
        }

        return response()->json($data);
    }

    public function getCollegeDatalist()
    {
        $table  = CollegeStudent::getTableName(); 
        $course = Courses::getTableName();
        $studNo = CollegeStudent::FIELD_STUDENT_NUM;
        $fields = 
        [
            $studNo,
            CollegeStudent::FIELD_YEAR,
            CollegeStudent::FIELD_FNAME,
            CollegeStudent::FIELD_MNAME,
            CollegeStudent::FIELD_LNAME,
            Courses::FIELD_COURSE,
        ];

        $studentData = DB::table("$table as s")
                    ->leftJoin("$course as c", "c.id", "s.course_id")
                    ->select($fields)
                    ->orderBy($studNo, 'asc')
                    ->get();

        $data = [];

        foreach ($studentData as $obj)
        {
            $data[$obj->student_no] = array
            ( 
                'name'   => implode(' ', [$obj->lastname . ",", $obj->middlename, $obj->firstname]),
                'course' => $obj->course,
                'year'   => $obj->year
            );
        }

        return response()->json($data);
    }
}