<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
