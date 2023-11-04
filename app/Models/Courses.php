<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hashids\Hashids;
use Illuminate\Support\Facades\DB;

class Courses extends Model
{
    use HasFactory;

    public const FIELD_ID           = 'id';
    public const FIELD_COURSE       = 'course';
    public const FIELD_COURSE_DESC  = 'course_desc';

    public static function getTableName()
    {
        return (new self)->getTable();
    }

    /**
     * Get all courses and return a key-value pair having
     * its Key as course name and Value as course id
     */
    public function getAll($keyAsValue = false)
    {
        $dataset = $this->select('id', 'course')->orderBy('course')->get();
        $data = [];

        // $hashIds = new Hashids();

        foreach ($dataset as $row)
        {
            $data[$row->course] = $keyAsValue ? $row->course : $row->id;
        }

        return $data;
    }

    /**
     * Find the ID by course name and return null if no matches 
     */
    public static function findCourseId($courseName) : int
    { 
        $id = DB::table( self::getTableName() )
            ->where(self::FIELD_COURSE, '=', $courseName)
            ->value(self::FIELD_ID);

        return $id;
    }
}
