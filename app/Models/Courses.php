<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courses extends Model
{
    use HasFactory;

    public static function getTableName()
    {
        return (new self)->getTable();
    }

    /**
     * Get all courses and return a key-value pair having
     * its Key as course name and Value as course id
     */
    public function getAllAssoc()
    {
        $dataset = $this->select('id', 'course')->orderBy('course')->get();
        $data = [];

        foreach ($dataset as $row)
        {
            $data[$row->course] = $row->id;
        }

        return $data;
    }
}
