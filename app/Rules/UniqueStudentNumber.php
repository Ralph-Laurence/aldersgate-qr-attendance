<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class UniqueStudentNumber implements Rule
{
    private $student;

    public function __construct($student)
    {
        $this->student = $student;
    }

    public function passes($attribute, $value)
    {
        return Unique::query('elem')->where('student_no', $value)->where('id', '<>', $this->student->id)->count() === 0
            && Unique::query('juniors')->where('student_no', $value)->where('id', '<>', $this->student->id)->count() === 0
            && Unique::query('seniors')->where('student_no', $value)->where('id', '<>', $this->student->id)->count() === 0
            && Unique::query('college')->where('student_no', $value)->where('id', '<>', $this->student->id)->count() === 0;
    }

    public function message()
    {
        return 'The student number already exists.';
    }
}