<?php

namespace App\Models;

use App\Models\Base\Student;

class JuniorStudent extends Student
{
    public static function getTableName()
    {
        return (new self)->getTable();
    }
     
    public function getGradeLevels() : array
    {
        for ($i = 7; $i <= 10; $i++)
        { 
            $gradeLevels["Grade $i"] = $i;
        }

        return $gradeLevels;
    }
}
