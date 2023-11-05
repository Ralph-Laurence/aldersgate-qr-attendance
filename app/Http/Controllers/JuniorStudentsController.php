<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\StudentController;
use App\Http\Extensions\Routes;
use App\Http\Extensions\Utils;
use App\Http\Extensions\ValidationMessages;
use App\Models\JuniorStudent;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class JuniorStudentsController extends StudentController
{
    private $studentModel = null;

    function __construct()
    {
        $this->studentModel = new JuniorStudent();
    }

    public function index($sort = null) 
    {
        $options     = ['sort' => $sort];
        $student     = JuniorStudent::STUDENT_LEVEL_JUNIORS;
        
        $dataset     = $this->studentModel->getStudents($options, $student);
        $gradeLevels = $this->studentModel->getGradeLevels();
        
        return view('backoffice.students.juniors.index')
            ->with('studentsDataset', $dataset)
            ->with('totalRecords'   , $dataset->count())
            ->with('gradeLevels'    , $gradeLevels)
            ->with('formActions', 
            [
                'storeStudent'  => route(Routes::JUNIOR_STUDENT['store']  ),
                'updateStudent' => route(Routes::JUNIOR_STUDENT['update'] ),
                'deleteStudent' => route(Routes::JUNIOR_STUDENT['destroy']),
            ])
            ->with('worksheetTabRoutes', $this->getWorksheetTabRoutesExcept('juniors'));
    }

    public function destroy(Request $request)
    {
        return $this->deleteStudent($request, $this->studentModel);
    }

    public function saveModel(Request $request, $mode = 0)
    {
        $studentKey = $request->input('student-key');
        
        if (!empty($studentKey))
            $studentKey = decrypt($studentKey);

        $inputs = $this->validateFields($request, $studentKey);
        
        // Check if validation failed and a 'redirect' response was returned
        if ($inputs instanceof \Illuminate\Http\RedirectResponse)
            return $inputs;

        try 
        { 
            $data = 
            [
                // Table field names        => Input request names

                JuniorStudent::FIELD_STUDENT_NUM  => $inputs['input-student-no'],
                JuniorStudent::FIELD_FNAME        => $inputs['input-fname'],
                JuniorStudent::FIELD_MNAME        => $inputs['input-mname'],
                JuniorStudent::FIELD_LNAME        => $inputs['input-lname'],
                JuniorStudent::FIELD_EMAIL        => $inputs['input-email'],
                JuniorStudent::FIELD_GRADE_LEVEL  => $inputs['input-grade-level-value'],
                JuniorStudent::FIELD_CONTACT      => $inputs['input-contact'],
                JuniorStudent::FIELD_BIRTHDAY     => $inputs['input-birthday'],
            ];

            $_flashMsg = '';

            if ($mode === 0)
            {
                JuniorStudent::create($data);
                $_flashMsg = self::MSG_SUCCESS_ADDED;
            }
            else if ($mode === 1)
            {
                // There must be a student key present in the input request.
                if (empty($studentKey))
                    abort(500);

                $student   = JuniorStudent::find( $studentKey );

                // If the student record is not found, do not perform an update
                if (!$student)
                    abort(500);

                $student->update($data);
                $_flashMsg = self::MSG_SUCCESS_UPDATED;
            }

            $flashMessage = Utils::makeFlashMessage($_flashMsg, Utils::FLASH_MESSAGE_SUCCESS, 'toast');

            return redirect()->route(Routes::JUNIOR_STUDENT['index'], ['sort' => 'recent'])
                ->withInput( ['form-action' => $request->input('form-action')] )
                ->with('flash-message', $flashMessage);
        } 
        catch (QueryException $ex) 
        {
            if ($ex->errorInfo[1] == 1062)
            {
                $errMsg = [];

                if (Str::contains($ex->getMessage(), "for key 'junior_students_student_no_unique'"))
                    $errMsg['input-student-no'] = self::MSG_FAIL_INDEX_STUDENT_NO;

                if (Str::contains($ex->getMessage(), "for key 'junior_students_email_unique"))
                    $errMsg['input-email'] = self::MSG_FAIL_INDEX_EMAIL;

                return redirect()->back()->withErrors($errMsg)->withInput();
            }
            
            abort(500);
        }
    }

    private function validateFields(Request $request, $recordId = null)
    {
        // 
        // Get common validation rules defined in base class
        // then union / merge extra rules
        //
        $validationFields = $this->commonValidationFields([ 
            'input-grade-level-value' => 'required|integer|between:7,10',
        ], $recordId);
        // 
        // Get common validation rule messages defined in base class
        // then union / merge extra rules
        //
        $validationMessages = $this->commonValidationMessages([

            // Extra | Specific Rules
            'input-grade-level-value.integer'  => ValidationMessages::invalid('Grade level'),
            'input-grade-level-value.required' => ValidationMessages::option('grade level'),
            'input-grade-level-value.between'  => ValidationMessages::between('Grade level', 7, 10),
        ]);

        $validator = Validator::make($request->all(), $validationFields, $validationMessages);

        if ($validator->fails())
            return redirect()->back()->withErrors($validator)->withInput();

        return $validator->validated();
    }
}
