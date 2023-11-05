<?php

namespace App\Models;
 
use App\Models\Base\Student;

class ElementaryStudent extends Student
{
    public static function getTableName()
    {
        return (new self)->getTable();
    }

    public function getGradeLevels() : array
    {
        for ($i = 1; $i <= 6; $i++)
        { 
            $gradeLevels["Grade $i"] = $i;
        }

        return $gradeLevels;
    }
}
