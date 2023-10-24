<?php

namespace App\Http\Controllers;

use App\Http\Extensions\RegexPatterns;
use App\Http\Extensions\RouteNames;
use App\Http\Extensions\ValidationMessages;
use App\Models\Courses;
use App\Models\Student;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

// index, show, create, store, edit, update and delete
// https://packagist.org/packages/hashids/hashids
class StudentsController extends Controller
{     
    private $studentModel = null;
    private $courseModel  = null;
    
    function __construct()
    {
        $this->studentModel = new Student();
        $this->courseModel  = new Courses();
    }

    public function index($sort = null) 
    {  
        $dataset = $this->studentModel->getStudents([
            'sort' => $sort
        ]);

        $courses = $this->courseModel->getAllAssoc();
        $yearLevels = $this->studentModel->getYearLevels();
        
        return view('backoffice.students.index')
            ->with('studentsDataset', $dataset)
            ->with('totalRecords', $dataset->count())
            ->with('coursesList', $courses)
            ->with('yearLevels', $yearLevels)
            ->with('formActions', 
            [
                'storeStudent'  => route( RouteNames::ADD_STUDENT ),
                'deleteStudent' => route( RouteNames::DELETE_STUDENT )
            ]);
    }

    public function delete(Request $request)
    {
        if (empty($request->input('student-key')))
            abort(500);

        try 
        {
            $id = decrypt($request->input('student-key'));

            DB::table($this->studentModel->getTable())
                ->where(Student::FIELD_ID, '=', $id)
                ->delete();

            $flashMessage = json_encode([
                'status'   => '0', 
                'response' => 'Student record and attendance history successfully deleted.'
            ]);

            return redirect()->route(RouteNames::STUDENTS)->with('flash-message', $flashMessage);

        } catch (\Throwable $th) {
            //throw $th;
            abort(500);
        }
    }

    public function store(Request $request)
    {
        $validator = $this->validateFields($request);

        if ($validator->fails())
            return redirect()->back()->withErrors($validator)->withInput();

        $inputs = $validator->validated();

        $data = 
        [
            Student::FIELD_STUDENT_NUM  => $inputs['input-student-no'],
            Student::FIELD_FNAME        => $inputs['input-fname'],
            Student::FIELD_MNAME        => $inputs['input-mname'],
            Student::FIELD_LNAME        => $inputs['input-lname'],
            Student::FIELD_EMAIL        => $inputs['input-email'],
            Student::FIELD_COURSE_ID    => $inputs['input-course-value'],
            Student::FIELD_YEAR         => $inputs['input-year-level-value'],
            Student::FIELD_CONTACT      => $inputs['input-contact'],
            Student::FIELD_BIRTHDAY     => $inputs['input-birthday'],
        ];

        try 
        {
            Student::create($data);

            return redirect()->route(RouteNames::STUDENTS, ['sort' => 'recent']);
        } 
        catch (QueryException $ex) 
        {
            if ($ex->errorInfo[1] == 1062)
            {
                $errMsg = [];

                if (Str::contains($ex->getMessage(), "for key 'students_student_no_unique'"))
                    $errMsg['input-student-no'] = 'Student Number is already in use.';

                if (Str::contains($ex->getMessage(), "for key 'students_email_unique"))
                    $errMsg['input-email'] = 'Email is already in use.';

                return redirect()->back()->withErrors($errMsg)->withInput();
            }

            abort(500);
        }
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
            'input-birthday'     => 'nullable|date_format:n/j/Y',
            
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
}
