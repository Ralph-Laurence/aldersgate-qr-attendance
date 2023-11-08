<?php

namespace App\Models;

use App\Http\Extensions\Utils;
use App\Models\Base\CommonUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Security\UserAccountControl as UAC;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
  
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

    public static function getTableName()
    {
        return (new self)->getTable();
    }

    /**
     * Base query builder for selecting students in database
     */
    public function getUsersBase($level)
    { 
        $table = self::getTableName();
        $query = DB::table( "$table as u" )->where(self::FIELD_PRIVILEGE, '=', $level);

        $fields =  [
            'u.id',    'u.firstname',  'u.middlename', 'u.lastname', 'u.username',
            'u.email', 'u.permission', 'u.status', 'u.photo'
        ];

        return $query->select($fields);
    }

    public function getUsers($options = array(), $userType)
    {   
        $query = $this->getUsersBase($userType);            // Build the base query
        
        if ( array_key_exists('sort', $options) )
        {  
            if ($options['sort'] == 'recent') 
                $query->orderBy('u.created_at', 'desc');    // Query for the last added row
            else
                $query->orderBy('u.lastname', 'asc');       // Default query, sort by lastname
        } 
        else
        {
            $query->orderBy('u.lastname', 'asc');           // Default fallback query
        }
          
        return $this->beautifyDataset( $query->get(), $userType );
    }

    private function beautifyDataset($dataset)
    {
        for ($i = 0; $i < $dataset->count(); $i++) 
        {
            $row = $dataset[$i];

            $row->id = encrypt($row->id);                   // Encrypt user id

            $photo = $row->photo ? $row->photo : '';        // Fix photo path

            $row->photo = Utils::getPhotoPath($photo);

            $row->permission = UAC::permToString($row->permission);

            $row->status = UAC::statusToString($row->status);

            // Fix the name as one fullname
            $row->name = implode(" ", [$row->lastname . ",", $row->firstname, $row->middlename]);

            $dataset[$i] = $row;                            // Update the current row
        }

        return $dataset;
    }
}
