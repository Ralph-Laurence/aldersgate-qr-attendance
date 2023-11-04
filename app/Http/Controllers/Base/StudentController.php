<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use App\Http\Extensions\RegexPatterns;
use App\Http\Extensions\ValidationMessages;
use Illuminate\Http\Request;

abstract class StudentController extends Controller
{
    public const MODE_CREATE = 0;
    public const MODE_UPDATE = 1;
    
    public const MSG_SUCCESS_DELETE  = 'Student record and attendance history successfully deleted.';
    public const MSG_SUCCESS_ADDED   = 'Student has been successfully added to the records.';
    public const MSG_SUCCESS_UPDATED = 'Student record has been successfully updated.';
    
    public const MSG_FAIL_INDEX_EMAIL       = 'Email is already in use.';
    public const MSG_FAIL_INDEX_STUDENT_NO  = 'Student Number is already in use.';

    public function update(Request $request)
    {
        return $this->saveModel($request, self::MODE_UPDATE);
    }
 
    public function store(Request $request)
    {
        return $this->saveModel($request, self::MODE_CREATE);
    }

    abstract protected function saveModel(Request $request, $mode = 0);

    public function getCommonValidationFields(array $extraFields) : array
    {
        $fields = array(
            'input-fname'        => 'required|max:32|regex:' . RegexPatterns::ALPHA_DASH_DOT_SPACE,
            'input-mname'        => 'required|max:32|regex:' . RegexPatterns::ALPHA_DASH_DOT_SPACE,
            'input-lname'        => 'required|max:32|regex:' . RegexPatterns::ALPHA_DASH_DOT_SPACE,
            'input-student-no'   => 'required|max:32|regex:' . RegexPatterns::NUMERIC_DASH,
            'input-contact'      => 'nullable|regex:'        . RegexPatterns::MOBILE_NO,
            'input-email'        => 'required|max:50|email',
            'input-birthday'     => 'nullable|date_format:n/j/Y',
        );

        return $fields + $extraFields;
    }

    public function commonValidationMessages(array $extraRules)
    {
        $validationMessage = 
        [
            'input-fname.required'       => ValidationMessages::required('Firstname'),
            'input-fname.max'            => ValidationMessages::maxLength(32, 'Firstname'),
            'input-fname.regex'          => ValidationMessages::alphaDashDotSpace('Firstname'),

            'input-mname.required'       => ValidationMessages::required('Middlename'),
            'input-mname.max'            => ValidationMessages::maxLength(32, 'Middlename'),
            'input-mname.regex'          => ValidationMessages::alphaDashDotSpace('Middlename'),

            'input-lname.required'       => ValidationMessages::required('Lastname'),
            'input-lname.max'            => ValidationMessages::maxLength(32, 'Lastname'),
            'input-lname.regex'          => ValidationMessages::alphaDashDotSpace('Lastname'),

            'input-student-no.required'  => ValidationMessages::required('Student number'),
            'input-student-no.max'       => ValidationMessages::maxLength(32, 'Student number'),
            'input-student-no.regex'     => ValidationMessages::numericDash('Student number'),

            'input-email.required'       => ValidationMessages::required('Email'),
            'input-email.email'          => ValidationMessages::invalid('Email'),
            'input-email.max'            => ValidationMessages::maxLength(50, 'Email'),

            'input-birthday.required'    => ValidationMessages::required('Birthdate'),
            'input-birthday.date_format' => ValidationMessages::invalid('Birthdate'),

            'input-contact.regex'        => ValidationMessages::mobile('Contact number'),
        ];
        
        return $validationMessage + $extraRules;
    }
}