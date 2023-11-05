<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\StudentController;
use App\Http\Extensions\Routes;
use App\Http\Extensions\Utils;
use App\Http\Extensions\ValidationMessages;
use App\Models\ElementaryStudent;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ElemStudentsController extends StudentController
{
    private $studentModel = null;

    function __construct()
    {
        $this->studentModel = new ElementaryStudent();
    }

    //
    public function index($sort = null) 
    {
        $options     = ['sort' => $sort];
        $student     = ElementaryStudent::STUDENT_LEVEL_ELEMENTARY;

        $dataset     = $this->studentModel->getStudents($options, $student);
        $gradeLevels = $this->studentModel->getGradeLevels();
        
        return view('backoffice.students.elementary.index')
            ->with('studentsDataset', $dataset)
            ->with('totalRecords'   , $dataset->count())
            ->with('gradeLevels'    , $gradeLevels)
            ->with('formActions', 
            [
                'storeStudent'  => route(Routes::ELEM_STUDENT['store']  ),
                'updateStudent' => route(Routes::ELEM_STUDENT['update'] ),
                'deleteStudent' => route(Routes::ELEM_STUDENT['destroy']),
            ])
            ->with('worksheetTabRoutes', $this->getWorksheetTabRoutesExcept('elementary'));
    }

    public function destroy(Request $request)
    {
        return $this->deleteStudent($request, $this->studentModel);
    }
    //
    //=================================================
    //::::::::::: BUSINESS LOGIC METHODS ::::::::::::::
    //=================================================
    //
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

                ElementaryStudent::FIELD_STUDENT_NUM  => $inputs['input-student-no'],
                ElementaryStudent::FIELD_FNAME        => $inputs['input-fname'],
                ElementaryStudent::FIELD_MNAME        => $inputs['input-mname'],
                ElementaryStudent::FIELD_LNAME        => $inputs['input-lname'],
                ElementaryStudent::FIELD_EMAIL        => $inputs['input-email'],
                ElementaryStudent::FIELD_GRADE_LEVEL  => $inputs['input-grade-level-value'],
                ElementaryStudent::FIELD_CONTACT      => $inputs['input-contact'],
                ElementaryStudent::FIELD_BIRTHDAY     => $inputs['input-birthday'],
            ];

            $_flashMsg = '';

            if ($mode === 0)
            {
                ElementaryStudent::create($data);
                $_flashMsg = self::MSG_SUCCESS_ADDED;
            }
            else if ($mode === 1)
            {
                // There must be a student key present in the input request.
                if (empty($studentKey))
                    abort(500);

                $student = ElementaryStudent::find( $studentKey );

                // If the student record is not found, do not perform an update
                if (!$student)
                    abort(500);

                $student->update($data);
                $_flashMsg = self::MSG_SUCCESS_UPDATED;
            }

            $flashMessage = Utils::makeFlashMessage($_flashMsg, Utils::FLASH_MESSAGE_SUCCESS, 'toast');

            return redirect()->route(Routes::ELEM_STUDENT['index'], ['sort' => 'recent'])
                ->withInput( ['form-action' => $request->input('form-action')] )
                ->with('flash-message', $flashMessage);
        } 
        catch (QueryException $ex) 
        {
            if ($ex->errorInfo[1] == 1062)
            {
                $errMsg = [];

                if (Str::contains($ex->getMessage(), "for key 'elementary_students_student_no_unique'"))
                    $errMsg['input-student-no'] = self::MSG_FAIL_INDEX_STUDENT_NO;

                if (Str::contains($ex->getMessage(), "for key 'elementary_students_email_unique"))
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
            'input-grade-level-value' => 'required|integer|between:1,6',
        ], $recordId);
        // 
        // Get common validation rule messages defined in base class
        // then union / merge extra rules
        //
        $validationMessages = $this->commonValidationMessages([

            // Extra | Specific Rules
            'input-grade-level-value.integer'  => ValidationMessages::invalid('Grade level'),
            'input-grade-level-value.required' => ValidationMessages::option('grade level'),
            'input-grade-level-value.between'  => ValidationMessages::between('Grade level', 1, 6),
        ]);

        $validator = Validator::make($request->all(), $validationFields, $validationMessages);

        if ($validator->fails())
            return redirect()->back()->withErrors($validator)->withInput();
 
        return $validator->validated();
    }
}
