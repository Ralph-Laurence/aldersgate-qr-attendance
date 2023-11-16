<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\StudentController;
use App\Http\Extensions\Routes;
use App\Http\Extensions\Utils;
use App\Http\Extensions\ValidationMessages;
use App\Models\Courses;
use App\Models\CollegeStudent;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CollegeStudentsController extends StudentController
{
    private $studentModel = null;
    private $courseModel  = null;
    
    function __construct()
    {
        $this->studentModel = new CollegeStudent();
        $this->courseModel  = new Courses();
    }
    //
    //=================================================
    //:::::::::::::: CONTROLLER METHODS :::::::::::::::
    //=================================================
    //
    public function index($sort = null) 
    {
        $options    = ['sort' => $sort];
        $student    = CollegeStudent::STUDENT_LEVEL_COLLEGE;
            
        $dataset    = $this->studentModel->getStudents($options, $student);
        $courses    = $this->courseModel->getAll(true);
        $yearLevels = $this->studentModel->getYearLevels();
        
        return view('backoffice.students.college.index')
            ->with('studentsDataset', $dataset)
            ->with('totalRecords', $dataset->count())
            ->with('coursesList', $courses)
            ->with('yearLevels', $yearLevels)
            ->with('formActions', 
            [
                'storeStudent'  => route( Routes::COLLEGE_STUDENT['store']   ),
                'updateStudent' => route( Routes::COLLEGE_STUDENT['update']  ),
                'deleteStudent' => route( Routes::COLLEGE_STUDENT['destroy'] )
            ])
            ->with('worksheetTabRoutes', $this->getWorksheetTabRoutesExcept('college'));
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

                CollegeStudent::FIELD_STUDENT_NUM  => $inputs['input-student-no'],
                CollegeStudent::FIELD_FNAME        => $inputs['input-fname'],
                CollegeStudent::FIELD_MNAME        => $inputs['input-mname'],
                CollegeStudent::FIELD_LNAME        => $inputs['input-lname'],
                CollegeStudent::FIELD_EMAIL        => $inputs['input-email'],
                CollegeStudent::FIELD_COURSE_ID    => $inputs['input-course-value'],
                CollegeStudent::FIELD_YEAR         => $inputs['input-year-level-value'],
                CollegeStudent::FIELD_CONTACT      => $inputs['input-contact'],
                CollegeStudent::FIELD_BIRTHDAY     => $inputs['input-birthday'],
            ];

            $_flashMsg = '';

            if ($mode === 0)
            {
                CollegeStudent::create($data);
                $_flashMsg = self::MSG_SUCCESS_ADDED;
            }
            else if ($mode === 1)
            {
                // There must be a student key present in the input request.
                if (empty($studentKey))
                    abort(500);

                $student = CollegeStudent::find( $studentKey );

                // If the student record is not found, do not perform an update
                if (!$student)
                    abort(500);

                $student->update($data);
                $_flashMsg = self::MSG_SUCCESS_UPDATED;
            }

            $flashMessage = Utils::makeFlashMessage($_flashMsg, Utils::FLASH_MESSAGE_SUCCESS, 'toast');

            return redirect()->route(Routes::COLLEGE_STUDENT['index'], ['sort' => 'recent'])
                ->withInput( ['form-action' => $request->input('form-action')] )
                ->with('flash-message', $flashMessage);
        } 
        catch (QueryException $ex) 
        {
            if ($ex->errorInfo[1] == 1062) 
            {
                $errMsg = [];

                if (Str::contains($ex->getMessage(), "for key 'college_students_student_no_unique'"))
                    $errMsg['input-student-no'] = self::MSG_FAIL_INDEX_STUDENT_NO;

                if (Str::contains($ex->getMessage(), "for key 'college_students_email_unique"))
                    $errMsg['input-email'] = self::MSG_FAIL_INDEX_EMAIL;
                    
                return redirect()->back()->withErrors($errMsg)->withInput();
            }

            abort(500);
        }
    }

    private function validateFields(Request $request, $recordId = null)
    { 
        // 
        // Get common validation rules defined in base class then union / merge extra rules
        //
        $validationFields = $this->commonValidationFields([ 
            // Extra | Specific Fields
            'input-course-value'     => 'required',
            'input-year-level-value' => 'required|integer|between:1,5',
        ], $recordId);

        // 
        // Get common validation rule messages defined in base class then union / merge extra rules
        //
        $validationMessages = $this->commonValidationMessages([
            
            // Extra | Specific Rules
            'input-course-value.integer'      => ValidationMessages::invalid('Course'),
            'input-course-value.required'     => ValidationMessages::option('course'),
            
            'input-year-level-value.integer'  => ValidationMessages::invalid('Year level'),
            'input-year-level-value.required' => ValidationMessages::option('year level'),
            'input-year-level-value.between'  => ValidationMessages::between('Year level', 1, 5),
        ]);
  
        $validator = Validator::make($request->all(), $validationFields, $validationMessages);

        if ($validator->fails())
            return redirect()->back()->withErrors($validator)->withInput();
        
        // Successfully validated fields
        $inputs = $validator->validated();

        // Validate course, find its id by its name
        $courseId = $this->courseModel->findCourseId($request->input('input-course-value'));

        if (is_null($courseId))
        {
            return redirect()->back()
                ->withErrors(['input-course-value' => ValidationMessages::invalid('Course')])
                ->withInput();
        }

        // Update the input field values, set it to the course id found
        $inputs['input-course-value'] = $courseId;

        return $inputs;
    }

    
}
