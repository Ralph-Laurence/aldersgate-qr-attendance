<?php

namespace App\Models;

use App\Http\Extensions\Utils;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Student extends Model
{
    use HasFactory;

    const FIELD_STUDENT_NUM = 'student_no';
    const FIELD_FNAME = 'firstname';
    const FIELD_MNAME = 'middlename';
    const FIELD_LNAME = 'lastname';
    const FIELD_EMAIL = 'email';
    const FIELD_CONTACT = 'contact';
    const FIELD_BIRTHDAY = 'birthday';
    const FIELD_COURSE_ID = 'course_id';
    const FIELD_YEAR = 'year';
    const FIELD_PHOTO = 'photo'; 

    /**
     * Base query builder for selecting students in database
     */
    public function getStudentsBase()
    {
        $table   = $this->getTable();
        $courses = Courses::getTableName();

        $dataset = DB::table("$table as s")
            ->leftJoin("$courses as c", 'c.id', '=', 's.course_id')
            ->select(
                's.student_no', 's.firstname', 's.middlename', 's.lastname', 's.contact',
                's.email', 's.birthday', 's.year', 's.photo', 'c.course'
            )
            ->orderBy('s.lastname')
            ->get();

        if (!is_null($dataset))
        {
            for ($i = 0; $i < count($dataset); $i++) 
            {
                $row = $dataset[$i];
    
                // Exclude empty rows
                if (empty((array)$row))
                    continue;
            }
        }

        return $dataset;
    }

    public function getStudents()
    {
        $dataset = $this->getStudentsBase();

        for ($i = 0; $i < $dataset->count(); $i++)
        {
            $row = $dataset[$i];

            // Fix photo path
            if ($row->photo)
                $row->photo = Utils::getPhotoPath($row->photo);

            // Convert the year levels to their ordinal equivalent
            $row->year_ordinal = Utils::toOrdinal($row->year, true);

                // Fix the name as one fullname
            $row->name = implode(" ", [ $row->lastname . ",", $row->firstname, $row->middlename ]);

            // Update the current row
            $dataset[$i] = $row;
        }

        return $dataset;
    }
}
