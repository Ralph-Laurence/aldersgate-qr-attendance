<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Strand extends Model
{
    use HasFactory;

    public const FIELD_ID           = 'id';
    public const FIELD_STRAND       = 'strand';
    public const FIELD_STRAND_DESC  = 'strand_desc';

    public static function getTableName()
    {
        return (new self)->getTable();
    }

    /**
     * Get all strands and return a key-value pair having
     * its Key as strand name and Value as strand id
     */
    public function getAll($keyAsValue = false)
    {
        $dataset = $this->select(self::FIELD_ID, self::FIELD_STRAND)->orderBy(self::FIELD_STRAND)->get();
        $data = [];
 
        foreach ($dataset as $row)
        {
            $data[$row->strand] = $keyAsValue ? $row->strand : $row->id;
        }

        return $data;
    }

     /**
     * Find the ID by strand name and return null if no matches 
     */
    public static function findStrandId($strandName) : int
    { 
        $id = DB::table( self::getTableName() )
            ->where(self::FIELD_STRAND, '=', $strandName)
            ->value(self::FIELD_ID);

        return $id;
    }
}
