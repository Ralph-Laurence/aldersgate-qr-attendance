<?php

namespace App\Models;

use App\Http\Extensions\Utils;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Student extends Model
{
    use HasFactory;

    const FIELD_ID          = 'id';
    const FIELD_STUDENT_NUM = 'student_no';
    const FIELD_FNAME       = 'firstname';
    const FIELD_MNAME       = 'middlename';
    const FIELD_LNAME       = 'lastname';
    const FIELD_EMAIL       = 'email';
    const FIELD_CONTACT     = 'contact';
    const FIELD_BIRTHDAY    = 'birthday';
    const FIELD_COURSE_ID   = 'course_id';
    const FIELD_YEAR        = 'year';
    const FIELD_PHOTO       = 'photo'; 

    protected $guarded = 
    [
        self::FIELD_ID
    ];

    /**
     * Base query builder for selecting students in database
     */
    public function getStudentsBase()
    {
        $table   = $this->getTable();
        $courses = Courses::getTableName();

        $query = DB::table("$table as s")
            ->leftJoin("$courses as c", 'c.id', '=', 's.course_id')
            ->select(
                's.id', 's.student_no', 's.firstname', 's.middlename', 's.lastname',
                's.email', 's.birthday', 's.year', 's.photo', 's.contact', 'c.course');

        return $query;
    }

    public function getStudents($options = array())
    {
        // Default | Fallback query
        $dataset  = $this->getStudentsBase()->orderBy('s.lastname', 'asc');

        if ( array_key_exists('sort', $options) )
        { 
            // Build a query to get the last added row
            if ($options['sort'] == 'recent')
                $dataset = $this->getStudentsBase()->orderBy('s.created_at', 'desc');
        }

        $dataset = $dataset->get(); // Execute the query

        // Beautify the dataset
        for ($i = 0; $i < $dataset->count(); $i++)
        {
            $row = $dataset[$i];

            // Encrypt student id
            $row->id = encrypt($row->id);

            // Fix photo path
            $photo = $row->photo ? $row->photo : '';
            
            $row->photo = Utils::getPhotoPath($photo);

            // Convert the year levels to their ordinal equivalent
            $row->year_ordinal = Utils::toOrdinal($row->year, true);

                // Fix the name as one fullname
            $row->name = implode(" ", [ $row->lastname . ",", $row->firstname, $row->middlename ]);

            // Update the current row
            $dataset[$i] = $row;
        }

        return $dataset;
    }

    public function getYearLevels() : array
    {
        for ($i = 1; $i <= 5; $i++)
        {
            // Suffix year levels with "st, nd, rd" i.e 1st 2nd etc..
            $yearLevels[Utils::toOrdinal($i)] = $i;
        }

        return $yearLevels;
    }
}
