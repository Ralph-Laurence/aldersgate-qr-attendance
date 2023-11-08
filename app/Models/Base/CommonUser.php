<?php

namespace App\Models\Base;

use App\Http\Extensions\Utils;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CommonUser extends Model
{
    use HasFactory;

    const FIELD_FIRSTNAME       = 'firstname';
    const FIELD_MIDDLENAME      = 'middlename';
    const FIELD_LASTNAME        = 'lastname';
    const FIELD_USERNAME        = 'username';
    const FIELD_EMAIL           = 'email';
    const FIELD_PASSWORD        = 'password';
    const FIELD_PRIVILEGE       = 'privilege';         // (User Types) -> Librarians | Admin | Master
    const FIELD_PERMS           = 'permission';        // x -> No Access, r -> Read Only, w -> Write Only, rw -> Read + Write f -> Full Access
    const FIELD_STATUS          = 'status';            // 1 -> Active || 0 -> Disabled
    const FIELD_LAST_LOGIN      = 'last_login';
    const FIELD_LAST_LOGOUT     = 'last_logout';
    const FIELD_VERIFIED_AT     = 'email_verified_at';
    const FIELD_PHOTO           = 'photo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}