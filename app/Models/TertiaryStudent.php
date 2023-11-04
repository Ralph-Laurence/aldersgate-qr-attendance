<?php

namespace App\Models;

use App\Http\Extensions\Utils;
use App\Models\Base\Student;

class TertiaryStudent extends Student
{ 
    public static function getTableName()
    {
        return (new self)->getTable();
    }

    public function getYearLevels() : array
    {
        for ($i = 1; $i <= 5; $i++)
        {
            // Suffix year levels with "st, nd, rd" i.e 1st 2nd etc..
            $yearLevels[Utils::toOrdinal($i)] = $i;
        }

        return $yearLevels;
    }
}
