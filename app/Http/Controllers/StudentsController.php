<?php

namespace App\Http\Controllers;

use App\Http\Extensions\Utils;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentsController extends Controller
{    
    private $studentModel = null;

    function __construct()
    {
        $this->studentModel = new Student();
    }

    public function index() 
    {  
        return view('backoffice.students.index')
            ->with('studentsDataset', $this->getStudents());
    }

    private function getStudents()
    {
        $dataset = $this->studentModel->getStudentsBase();

        for ($i = 0; $i < $dataset->count(); $i++)
        {
            $row = $dataset[$i];

            // Fix photo path
            if ($row->photo)
                $row->photo = Utils::getPhotoPath($row->photo);

            // Convert the year levels to their ordinal equivalent
            $row->year_ordinal = Utils::toOrdinal($row->year, true);

                // Fix the name as one fullname
            $row->name = implode(" ", [ $row->lastname . ",", $row->firstname, $row->middlename ]);

            // Update the current row
            $dataset[$i] = $row;
        }

        return $dataset;
    }
}
