<?php

namespace App\Models\Base;

use App\Http\Extensions\Utils;
use App\Models\Courses;
use App\Models\ElementaryStudent;
use App\Models\JuniorStudent;
use App\Models\Strand;
use App\Models\TertiaryStudent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Student extends Model
{
    use HasFactory;

    const FIELD_ID           = 'id';
    const FIELD_STUDENT_NUM  = 'student_no';
    const FIELD_FNAME        = 'firstname';
    const FIELD_MNAME        = 'middlename';
    const FIELD_LNAME        = 'lastname';
    const FIELD_EMAIL        = 'email';
    const FIELD_CONTACT      = 'contact';
    const FIELD_BIRTHDAY     = 'birthday';
    const FIELD_GRADE_LEVEL  = 'grade_level';       // For elementary - highschool students (G1-G10)
    const FIELD_STRAND_ID    = 'strand_id';         // For senior highschool students (k11-k12)
    const FIELD_COURSE_ID    = 'course_id';         // For college students (BS, AB, etc)
    const FIELD_YEAR         = 'year';              // For college students (1st ~ 5th)
    const FIELD_PHOTO        = 'photo'; 

    protected $guarded = 
    [
        self::FIELD_ID
    ];

    const STUDENT_LEVEL_ELEMENTARY  = 'elementary';
    const STUDENT_LEVEL_JUNIORS     = 'juniors';
    const STUDENT_LEVEL_SENIORS     = 'seniors';
    const STUDENT_LEVEL_COLLEGE     = 'college';

    const COMMON_FIELDS = 
    [
        's.id', 's.student_no', 's.firstname', 's.middlename', 
        's.lastname', 's.email', 's.birthday', 's.contact', 's.photo'
    ];
    
    public $QUERY_MAP = [];
  
    public function getStudents($options = array(), $studentLevel)
    {   
        $query = $this->getStudentsBase($studentLevel);     // Build the base query
        
        if ( array_key_exists('sort', $options) )
        {  
            if ($options['sort'] == 'recent') 
                $query->orderBy('s.created_at', 'desc');    // Query for the last added row
            else
                $query->orderBy('s.lastname', 'asc');       // Default query, sort by lastname
        } 
        else
        {
            $query->orderBy('s.lastname', 'asc');           // Default fallback query
        }
          
        return $this->beautifyDataset( $query->get(), $studentLevel );
    }

    private function beautifyDataset($dataset, $studentLevel)
    {
        for ($i = 0; $i < $dataset->count(); $i++) 
        {
            $row = $dataset[$i];

            $row->id = encrypt($row->id);                   // Encrypt student id

            $photo = $row->photo ? $row->photo : '';        // Fix photo path

            $row->photo = Utils::getPhotoPath($photo);

            // Fix the name as one fullname
            $row->name = implode(" ", [$row->lastname . ",", $row->firstname, $row->middlename]);

            $this->bindExtraData($row, $studentLevel);      // Add extra data to the row

            $dataset[$i] = $row;                            // Update the current row
        }

        return $dataset;
    }

    /**
     * Base query builder for selecting students in database
     */
    public function getStudentsBase($level)
    { 
        $fields = self::COMMON_FIELDS;                      // These fields are always selected

        switch ($level)
        {
            case self::STUDENT_LEVEL_COLLEGE: 
                $fields[] = 's.year';
                $fields[] = 'c.course';
                break;
    
            case self::STUDENT_LEVEL_ELEMENTARY: 
            case self::STUDENT_LEVEL_JUNIORS:
                $fields[] = 's.grade_level';
                break;
        }
    
        $query = $this->getQueryMap()[$level];
 
        return $query->select($fields);
    }

    private function makeQuery_ElemStudents()
    {
        $table = ElementaryStudent::getTableName();
        $query = DB::table( "$table as s" )->whereBetween('s.grade_level', [1, 6]);

        return $query;
    }

    private function makeQuery_HighschoolStudents()
    {
        $table = JuniorStudent::getTableName();
        $query = DB::table( "$table as s" )->whereBetween('s.grade_level', [7, 10]);

        return $query;
    }

    private function makeQuery_SeniorHighStudents()
    {
        $table   = JuniorStudent::getTableName();
        $strands = Strand::getTableName();

        $query   = DB::table( "$table as s" )
                    ->leftJoin("$strands as t", 't.id', '=', 's.strand_id');

        return $query;
    }

    private function makeQuery_CollegeStudents()
    {
        $table   = TertiaryStudent::getTableName();   
        $courses = Courses::getTableName();

        $query   = DB::table( "$table as s" )->leftJoin("$courses as c", 'c.id', '=', 's.course_id');

        return $query;
    }

    private function getQueryMap()
    {
        if (empty($this->QUERY_MAP)) 
        {
            $this->QUERY_MAP =
            [
                self::STUDENT_LEVEL_ELEMENTARY  => $this->makeQuery_ElemStudents(),
                self::STUDENT_LEVEL_JUNIORS     => $this->makeQuery_HighschoolStudents(),
                self::STUDENT_LEVEL_SENIORS     => $this->makeQuery_SeniorHighStudents(),
                self::STUDENT_LEVEL_COLLEGE     => $this->makeQuery_CollegeStudents()
            ];
        }

        return $this->QUERY_MAP;
    }

    private function bindExtraData($row, $studentLevel) : void
    {
        // Create a JSON object that will be used later to
        // autofill the form during Edit mode
        $initialRowData = [
            'studentNo'     => $row->student_no,
            'firstname'     => $row->firstname,
            'middlename'    => $row->middlename,
            'lastname'      => $row->lastname,
            'email'         => $row->email,
            'contact'       => $row->contact,
            'birthday'      => $row->birthday,
        ];

        $rowData = [];

        switch ($studentLevel)
        {
            case self::STUDENT_LEVEL_COLLEGE:
                // Convert the year levels to their ordinal equivalent
                // but only return the ordinal suffix
                $row->year_ordinal = Utils::toOrdinal($row->year, true);

                $rowData = $initialRowData + [
                    'course'    => $row->course,
                    'yearlevel' => $row->year
                ];
                
                break;

            case self::STUDENT_LEVEL_ELEMENTARY:
            case self::STUDENT_LEVEL_JUNIORS:
                
                $rowData = $initialRowData + [ 'gradeLevel' => $row->grade_level ];

                break;
        }
        
        $row->rowData = json_encode($rowData);
    }
}