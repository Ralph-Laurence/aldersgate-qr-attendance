<?php

namespace App\Http\Controllers;

use App\Http\Extensions\InputTypes;
use App\Http\Extensions\RegexPatterns;
use App\Http\Extensions\RouteNames;
use App\Http\Extensions\Utils;
use App\Http\Extensions\ValidationMessages;
use App\Models\Courses;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentsController extends Controller
{     
    private $studentModel = null;
    private $courseModel  = null;

    function __construct()
    {
        $this->studentModel = new Student();
        $this->courseModel  = new Courses();
    }

    public function index() 
    {  
        $dataset = $this->studentModel->getStudents();
        $courses = $this->courseModel->getAllAssoc();

        for ($i = 1; $i <= 5; $i++)
        {
            // Suffix year levels with "st, nd, rd" i.e 1st 2nd etc..
            $yearLevels[Utils::toOrdinal($i)] = $i;
        }

        $inputs = array
        (
            'frame1' => [
                $this->buildInput('input-fname', InputTypes::TEXT, 'Firstname'),
                $this->buildInput('input-mname', InputTypes::TEXT, 'Middlename'),
                $this->buildInput('input-lname', InputTypes::TEXT, 'Lastname'),

                $this->buildInput('input-student-no', InputTypes::TEXT, 'Student No.'),

                $this->buildInput('input-email', InputTypes::TEXT, 'Email'),
            ],
            'frame2' => [
                $this->buildInput('input-contact',  InputTypes::TEXT, 'Contact #', false),
            ]
        );

        return view('backoffice.students.index')
            ->with('studentsDataset', $dataset)
            ->with('totalRecords', $dataset->count())
            ->with('coursesList', $courses)
            ->with('yearLevels', $yearLevels)
            ->with('storeStudentRoute', route( RouteNames::ADD_STUDENT ))
            ->with('fields', $inputs);
    }

    public function store(Request $request)
    {
        $validator = $this->validateFields($request);

        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        dump($validator->validated());
    }

    private function validateFields(Request $request)
    {
        $validator = Validator::make($request->all(), 
        [
            'input-fname'        => 'required|max:32|regex:' . RegexPatterns::ALPHA_DASH_DOT_SPACE,
            'input-mname'        => 'required|max:32|regex:' . RegexPatterns::ALPHA_DASH_DOT_SPACE,
            'input-lname'        => 'required|max:32|regex:' . RegexPatterns::ALPHA_DASH_DOT_SPACE,
            'input-student-no'   => 'required|max:32|regex:' . RegexPatterns::NUMERIC_DASH,
            'input-contact'      => 'nullable|regex:'        . RegexPatterns::MOBILE_NO,
            'input-email'        => 'required|max:50|email',
            'input-birthday'     => 'nullable|date_format:m/d/Y',
            
            'input-course-value'     => 'required|integer',
            'input-year-level-value' => 'required|integer',

        ], $this->createValidationMessages());

        return $validator;
    }

    private function createValidationMessages()
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
            
            'input-course-value.integer'  => ValidationMessages::invalid('Course'),
            'input-course-value.required' => ValidationMessages::option('course'),
            
            'input-year-level-value.integer'  => ValidationMessages::invalid('Year level'),
            'input-year-level-value.required' => ValidationMessages::option('year level'),
        ];
        
        return $validationMessage;
    }

    private function buildInput($name, $type, $label, $required = true) : array
    {
        return [
            'type'      => $type, 
            'name'      => $name, 
            'label'     => $label,  
            'required'  => $required 
        ];
    }
}
