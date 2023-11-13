<?php

namespace App\Models;

use App\Models\Base\Student;

class SeniorStudent extends Student
{
    public static function getTableName()
    {
        return (new self)->getTable();
    }

    public function getGradeLevels()
    {
        return [
            "Grade 11" => 11,
            "Grade 12" => 12,
        ];
    }
}
