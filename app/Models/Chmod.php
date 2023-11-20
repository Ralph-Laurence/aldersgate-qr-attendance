<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chmod extends Model
{
    use HasFactory;

    public const FIELD_ID       = 'id';
    public const FIELD_USER_FK  = 'user_fk_id';

    public const FIELD_ACCESS_ADVANCED     = 'access_advanced';
    public const FIELD_ACCESS_ATTENDANCE   = 'access_attendance';
    public const FIELD_ACCESS_STUDENTS     = 'access_students';
    public const FIELD_ACCESS_USERS        = 'access_users';

    protected $guarded = 
    [
        self::FIELD_ID
    ];

    public static function getTableName()
    {
        return (new self)->getTable();
    }
}
