<?php

namespace App\Models;

use App\Http\Extensions\RecordUtils;
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
  
    const FIELD_ID              = 'id';
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
   
    protected $guarded = [
        self::FIELD_ID
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];

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

    private $UacPermsArray;

    public const AccessBadges =
    [
        'full'      => [ 'type' => 'badge-success', 'icon' => 'fa-star'                 ],
        'moderate'  => [ 'type' => 'badge-warning', 'icon' => 'fa-star-half-stroke'     ],
        'denied'    => [ 'type' => 'badge-danger',  'icon' => 'fa-ban' ]
    ];

    public const StatusBadges =
    [
        UAC::STATUS_ACTIVE   => [ 'type' => 'status-badge-success', 'icon' => 'fa-check'  ],
        UAC::STATUS_DISABLED => [ 'type' => 'status-badge-danger',  'icon' => 'fa-times'  ],
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
        $users = self::getTableName();
        $chmod = Chmod::getTableName();

        $query = DB::table( "$users as u" )
            ->leftJoin("$chmod as c", 'c.' . Chmod::FIELD_USER_FK, '=', 'u.' . User::FIELD_ID)
            ->where(self::FIELD_PRIVILEGE, '=', $level);

        $fields =  
        [
            'u.id',    'u.firstname',  'u.middlename', 'u.lastname', 'u.username',
            'u.email', 'u.permission', 'u.status',     'u.photo', 

            'c.' . Chmod::FIELD_ACCESS_ADVANCED   . ' as perm_advanced',
            'c.' . Chmod::FIELD_ACCESS_ATTENDANCE . ' as perm_attendance',
            'c.' . Chmod::FIELD_ACCESS_STUDENTS   . ' as perm_students',
            'c.' . Chmod::FIELD_ACCESS_USERS      . ' as perm_users'
        ];

        return $query->select($fields);
    }

    public function getUsers($options = array(), $userType)
    {   
        $query = $this->getUsersBase($userType);            // Build the base query
        
        if ( array_key_exists('sort', $options) )
        {  
            switch ($options['sort'])
            {
                case RecordUtils::SORT_MODE_NEWLY_ADDED:
                    $query->orderBy('u.created_at', 'desc');    // Query for the last added row
                    break;

                case RecordUtils::SORT_MODE_LAST_UPDATED:
                    $query->orderBy('u.updated_at', 'desc');    // Query for the last added row
                    break;

                default:
                    $query->orderBy('u.lastname', 'asc');       // Default query, sort by lastname
                    break;
            }
        } 
        else
        {
            $query->orderBy('u.lastname', 'asc');           // Default fallback query
        }
          
        return $this->beautifyDataset( $query->get(), $userType );
    }

    private function beautifyDataset($dataset)
    {
        foreach ($dataset as $row) 
        {
            $row->id = encrypt($row->id);                   // Encrypt user id
            $photo   = $row->photo ? $row->photo : '';        // Fix photo path

            $row->photo = Utils::getPhotoPath($photo);

            $uacPerms = [
                'perm_advanced'     => $row->perm_advanced,
                'perm_attendance'   => $row->perm_attendance,
                'perm_students'     => $row->perm_students,
                'perm_users'        => $row->perm_users
            ];

            // Permissions badge
            $row->permBadge    = $this->makePermsBadge( array_values($uacPerms) );

            // Status badge
            $row->statusBadge = $this->makeStatusBadge($row->status);

            // Extra data
            $row->rowData     = $this->bindExtraData($row, $uacPerms);

            // Fix the name as one fullname
            $row->name = implode(" ", [$row->lastname . ",", $row->firstname, $row->middlename]);
        }

        return $dataset;
    }

    private function makePermsBadge(array $perms)
    {
        if ( empty($perms) || in_array(null, $perms, true) )
        {
            return [ 
                'type'  => 'badge-warning', 
                'icon'  => 'fa-triangle-exclamation',
                'label' => 'Unknown'
            ];
        }

        $unique_values  = array_unique($perms);
        $accessModerate = self::AccessBadges['moderate'] + ['label' => 'Moderate'];

        if (count($unique_values) == 1) 
        {
            $value = reset($unique_values);     // move internal array pointer to first element

            if ($value === UAC::PERM_FULL_CONTROL) 
                return self::AccessBadges['full'] + ['label' => 'Full'];

            elseif ($value === UAC::PERM_DENIED) 
                return self::AccessBadges['denied'] + ['label' => 'Denied'];

            else 
                return $accessModerate;
        } 
        else {
            return $accessModerate;
        }
    }

    private function makeStatusBadge($status)
    {
        if (!array_key_exists($status, self::StatusBadges))
        {
            return [ 
                'type'  => 'badge-warning', 
                'icon'  => 'fa-triangle-exclamation',
                'label' => 'Unknown'
            ];
        }
        
        return self::StatusBadges[$status] + ['label' => UAC::statusToString($status)];
    }

    private function bindExtraData(&$row, $uacPerms) : string
    {
        $rowData = [
            'firstname'     => $row->firstname,
            'middlename'    => $row->middlename,
            'lastname'      => $row->lastname,
            'username'      => $row->username,
            'email'         => $row->email,

            'perm_advanced'     => UAC::permToString($uacPerms['perm_advanced'  ]),
            'perm_attendance'   => UAC::permToString($uacPerms['perm_attendance']),
            'perm_students'     => UAC::permToString($uacPerms['perm_students'  ]),
            'perm_users'        => UAC::permToString($uacPerms['perm_users'      ]),
        ];

        return json_encode($rowData);
    }
}
