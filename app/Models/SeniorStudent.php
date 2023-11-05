<?php

namespace App\Models;

use App\Models\Base\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SeniorStudent extends Student
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
    const FIELD_GRADE_LEVEL  = 'grade_level';
    const FIELD_STRAND_ID    = 'strand_id';
    const FIELD_PHOTO        = 'photo'; 

    protected $guarded = 
    [
        self::FIELD_ID
    ];

    public static function getTableName()
    {
        return (new self)->getTable();
    }

    public function getGradeLevels()
    {
        return [
            "Grade 11" => 11,
            "Grade 12" => 12,
        ];
    }
}
