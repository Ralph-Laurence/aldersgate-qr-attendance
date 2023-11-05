<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\StudentController;
use App\Http\Extensions\Routes;
use App\Http\Extensions\Utils;
use App\Http\Extensions\ValidationMessages;
use App\Models\SeniorStudent;
use App\Models\Strand;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class SeniorStudentsController extends StudentController
{
    private $studentModel = null;
    private $strandsModel = null;

    function __construct()
    {
        $this->studentModel = new SeniorStudent();
        $this->strandsModel = new Strand();
    }

    public function index($sort = null) 
    {
        $options     = ['sort' => $sort];
        $student     = SeniorStudent::STUDENT_LEVEL_SENIORS;
        
        $dataset     = $this->studentModel->getStudents($options, $student);
        $gradeLevels = $this->studentModel->getGradeLevels();
        $strands     = $this->strandsModel->getAll(true);
        
        return view('backoffice.students.seniors.index')
            ->with('studentsDataset', $dataset)
            ->with('totalRecords'   , $dataset->count())
            ->with('gradeLevels'    , $gradeLevels)
            ->with('strandsList'    , $strands)
            ->with('formActions', 
            [
                'storeStudent'  => route(Routes::SENIOR_STUDENT['store']  ),
                'updateStudent' => route(Routes::SENIOR_STUDENT['update'] ),
                'deleteStudent' => route(Routes::SENIOR_STUDENT['destroy']),
            ])
            ->with('worksheetTabRoutes', $this->getWorksheetTabRoutesExcept('seniors'));
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

                SeniorStudent::FIELD_STUDENT_NUM  => $inputs['input-student-no'],
                SeniorStudent::FIELD_FNAME        => $inputs['input-fname'],
                SeniorStudent::FIELD_MNAME        => $inputs['input-mname'],
                SeniorStudent::FIELD_LNAME        => $inputs['input-lname'],
                SeniorStudent::FIELD_EMAIL        => $inputs['input-email'],
                SeniorStudent::FIELD_GRADE_LEVEL  => $inputs['input-grade-level-value'],
                SeniorStudent::FIELD_STRAND_ID    => $inputs['input-strand-value'],
                SeniorStudent::FIELD_CONTACT      => $inputs['input-contact'],
                SeniorStudent::FIELD_BIRTHDAY     => $inputs['input-birthday'],
            ];

            $_flashMsg = '';

            if ($mode === 0)
            {
                SeniorStudent::create($data);
                $_flashMsg = self::MSG_SUCCESS_ADDED;
            }
            else if ($mode === 1)
            {
                // There must be a student key present in the input request.
                if (empty($studentKey))
                    abort(500);

                $student = SeniorStudent::find( $studentKey );

                // If the student record is not found, do not perform an update
                if (!$student)
                    abort(500);

                $student->update($data);
                $_flashMsg = self::MSG_SUCCESS_UPDATED;
            }

            $flashMessage = Utils::makeFlashMessage($_flashMsg, Utils::FLASH_MESSAGE_SUCCESS, 'toast');

            return redirect()->route(Routes::SENIOR_STUDENT['index'], ['sort' => 'recent'])
                ->withInput( ['form-action' => $request->input('form-action')] )
                ->with('flash-message', $flashMessage);
        } 
        catch (QueryException $ex) 
        {
            if ($ex->errorInfo[1] == 1062)
            {
                $errMsg = [];

                if (Str::contains($ex->getMessage(), "for key 'senior_students_student_no_unique'"))
                    $errMsg['input-student-no'] = self::MSG_FAIL_INDEX_STUDENT_NO;

                if (Str::contains($ex->getMessage(), "for key 'senior_students_email_unique"))
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
            'input-strand-value'      => 'required',
            'input-grade-level-value' => 'required|integer|between:11,12',
        ], $recordId);
        // 
        // Get common validation rule messages defined in base class
        // then union / merge extra rules
        //
        $validationMessages = $this->commonValidationMessages([

            // Extra | Specific Rules
            'input-strand-value.required'      => ValidationMessages::option('strand'),

            'input-grade-level-value.integer'  => ValidationMessages::invalid('Grade level'),
            'input-grade-level-value.required' => ValidationMessages::option('grade level'),
            'input-grade-level-value.between'  => ValidationMessages::between('Grade level', 11, 12),
        ]);

        $validator = Validator::make($request->all(), $validationFields, $validationMessages);

        if ($validator->fails())
            return redirect()->back()->withErrors($validator)->withInput();
        
        // Successfully validated fields
        $inputs = $validator->validated();

        // Validate strand, find its id by its name
        $strandId = $this->strandsModel->findStrandId($request->input('input-strand-value'));

        if (is_null($strandId))
        {
            return redirect()->back()
                ->withErrors(['input-strand-value' => ValidationMessages::invalid('Strand')])
                ->withInput();
        }

        // Update the input field values, set it to the course id found
        $inputs['input-strand-value'] = $strandId;
 
        return $inputs;
    }
}
