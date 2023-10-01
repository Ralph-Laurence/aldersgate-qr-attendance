<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
