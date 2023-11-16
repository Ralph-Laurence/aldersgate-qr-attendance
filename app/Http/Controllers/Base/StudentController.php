<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use App\Http\Extensions\RegexPatterns;
use App\Http\Extensions\Routes;
use App\Http\Extensions\Utils;
use App\Http\Extensions\ValidationMessages;
use App\Models\Base\Student;
use App\Models\ElementaryStudent;
use App\Models\JuniorStudent;
use App\Models\SeniorStudent;
use App\Models\CollegeStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

abstract class StudentController extends Controller
{
    public const MODE_CREATE = 0;
    public const MODE_UPDATE = 1;
    
    public const MSG_SUCCESS_DELETE         = 'Student record and attendance history successfully deleted.';
    public const MSG_SUCCESS_ADDED          = 'Student has been successfully added to the records.';
    public const MSG_SUCCESS_UPDATED        = 'Student record has been successfully updated.';
    
    public const MSG_FAIL_INDEX_EMAIL       = 'Email is already in use.';
    public const MSG_FAIL_INDEX_STUDENT_NO  = 'Student Number is already in use.';

    private $COMMON_TABLES = array();
    
    abstract protected function saveModel(Request $request, $mode = 0);

    public function update(Request $request)
    {
        return $this->saveModel($request, self::MODE_UPDATE);
    }
 
    public function store(Request $request)
    {
        return $this->saveModel($request, self::MODE_CREATE);
    }

    public function deleteStudent(Request $request, Student $model)
    {
        if (empty($request->input('student-key')))
            abort(500);

        try 
        {
            $id = decrypt($request->input('student-key'));

            DB::table($model->getTable())
                ->where(Student::FIELD_ID, '=', $id)
                ->delete();
 
            $flashMessage = Utils::makeFlashMessage(self::MSG_SUCCESS_DELETE, Utils::FLASH_MESSAGE_SUCCESS, 'toast');

            // return redirect()->route( Routes::COLLEGE_STUDENT['index'] )->with('flash-message', $flashMessage);
            return redirect()->back()->with('flash-message', $flashMessage);

        } 
        catch (\Throwable $th) 
        {
            //throw $th;
            abort(500);
        }
    }

    public function getWorksheetTabRoutesExcept($except)
    {
        $routes = 
        [
            'elementary' => route(Routes::ELEM_STUDENT['index']),
            'juniors'    => route(Routes::JUNIOR_STUDENT['index']),
            'seniors'    => route(Routes::SENIOR_STUDENT['index']),
            'college'    => route(Routes::COLLEGE_STUDENT['index']),
        ];

        // Exclude a route
        if (array_key_exists($except, $routes))
            unset($routes[$except]);

        return $routes;
    }

    public function commonValidationFields(array $extraFields, $updateRecordKey = null) : array
    {
        $studentNumRule = $this->uniqueRuleAcrossStudents('student_no', $updateRecordKey, ['max:32','regex:' . RegexPatterns::NUMERIC_DASH]);
        $emailRule = $this->uniqueRuleAcrossStudents('email', $updateRecordKey, ['max:50','email']);

        //dump($studentNumRule); exit;

        $fields = array(
            'input-student-no'   => $studentNumRule,
            'input-fname'        => 'required|max:32|regex:' . RegexPatterns::ALPHA_DASH_DOT_SPACE,
            'input-mname'        => 'required|max:32|regex:' . RegexPatterns::ALPHA_DASH_DOT_SPACE,
            'input-lname'        => 'required|max:32|regex:' . RegexPatterns::ALPHA_DASH_DOT_SPACE,
            'input-contact'      => 'nullable|regex:'        . RegexPatterns::MOBILE_NO,
            'input-email'        => $emailRule, //'required|max:50|email',
            'input-birthday'     => 'nullable|date_format:n/j/Y',
        );

        return array_merge($fields, $extraFields); //$fields + $extraFields;
    }

    private function uniqueRuleAcrossStudents($field, $updateRecordKey = null, $extraRules = [])
    {
        $tables = [
            ElementaryStudent::getTableName(),
            JuniorStudent::getTableName(),
            SeniorStudent::getTableName(),
            CollegeStudent::getTableName()
        ];

        $rule = ['required'];

        foreach ($tables as $table) 
        {
            $uniqueRule = Rule::unique($table, $field);

            if ($updateRecordKey !== null)
                $uniqueRule->ignore($updateRecordKey);

            $rule[] = $uniqueRule;
        }

        return array_merge($rule, $extraRules); //$rule + $extraRules;
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
            'input-student-no.unique'    => ValidationMessages::unique('Student Number'),
            
            'input-email.unique'         => ValidationMessages::unique('Email'),
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