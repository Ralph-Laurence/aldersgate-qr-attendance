<?php

namespace App\Models;

use App\Http\Extensions\Utils;
use App\Models\Base\Student;

class JuniorStudent extends Student
{
    public static function getTableName()
    {
        return (new self)->getTable();
    }
     
    // public function getStudents($options = array())
    // {
    //     // Default | Fallback query
    //     $query = $this->getStudentsBase();
 
    //     if ( array_key_exists('sort', $options) )
    //     {  
    //         // Build a query to get the last added row
    //         if ($options['sort'] == 'recent') 
    //             $query->orderBy('s.created_at', 'desc');
    //         else
    //             $query->orderBy('s.lastname', 'asc');
    //     } 
    //     else
    //         $query->orderBy('s.lastname', 'asc');

    //     $dataset = $query->get(); // Execute the query
        
    //     // Beautify the dataset
    //     for ($i = 0; $i < $dataset->count(); $i++)
    //     {
    //         $row = $dataset[$i];

    //         // Encrypt student id
    //         $row->id = encrypt($row->id);

    //         // Fix photo path
    //         $photo = $row->photo ? $row->photo : '';
            
    //         $row->photo = Utils::getPhotoPath($photo);

    //         // Convert the grade levels to their ordinal equivalent
    //         // but only return the ordinal suffix
    //         $row->grade_ordinal = Utils::toOrdinal($row->grade_level, true);

    //         // Fix the name as one fullname
    //         $row->name = implode(" ", [ $row->lastname . ",", $row->firstname, $row->middlename ]);

    //         // Create a JSON object that will be used later to
    //         // autofill the form during Edit mode
    //         $row->rowData = json_encode([
    //             'studentNo'     => $row->student_no,
    //             'firstname'     => $row->firstname,
    //             'middlename'    => $row->middlename,
    //             'lastname'      => $row->lastname,
    //             'email'         => $row->email,
    //             'contact'       => $row->contact,
    //             'birthday'      => $row->birthday,
    //             'gradeLevel'    => $row->grade_level
    //         ]);

    //         // Update the current row
    //         $dataset[$i] = $row;
    //     }

    //     return $dataset;
    // }

    public function getGradeLevels() : array
    {
        for ($i = 1; $i <= 10; $i++)
        { 
            $gradeLevels[$i] = $i;
        }

        return $gradeLevels;
    }
}
