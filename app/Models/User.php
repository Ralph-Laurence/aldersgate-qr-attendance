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

    // public const PermBadges =
    // [
    //     UAC::PERM_FULL_CONTROL  => [ 'type' => 'badge-success', 'icon' => 'fa-crown'    ],
    //     UAC::PERM_MODIFY        => [ 'type' => 'badge-warning', 'icon' => 'fa-gear'     ],
    //     UAC::PERM_READ          => [ 'type' => 'badge-info',    'icon' => 'fa-bookmark' ],
    //     UAC::PERM_WRITE         => [ 'type' => 'badge-warning', 'icon' => 'fa-pen'      ],
    //     UAC::PERM_DENIED        => [ 'type' => 'badge-danger',  'icon' => 'fa-ban'      ]
    // ];

    private $UacPermsArray; // = UAC::permsToArray();

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

            // Permissions and Badges
            $row->permBadge  = $this->makeAccessBadge($row->permission);
            // $row->permission = UAC::permToString($row->permission);

            $row->statusBadge = $this->makeStatusBadge($row->status);
            //$row->status = UAC::statusToString($row->status);

            // Fix the name as one fullname
            $row->name = implode(" ", [$row->lastname . ",", $row->firstname, $row->middlename]);

            $dataset[$i] = $row;                            // Update the current row
        }

        return $dataset;
    }

    private function makeAccessBadge($perm)
    {
        $perms = $this->getUACPerms();

        if (!in_array($perm, $perms))
        {
            return [ 
                'type'  => 'badge-warning', 
                'icon'  => 'fa-triangle-exclamation',
                'label' => 'Unknown'
            ];
        }

        if ($perm == UAC::PERM_FULL_CONTROL)
            return self::AccessBadges['full'] + ['label' => 'Full'];

        if ($perm == UAC::PERM_DENIED)
            return self::AccessBadges['denied'] + ['label' => 'Denied'];

        return self::AccessBadges['moderate'] + ['label' => 'Moderate'];
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

    private function getUACPerms()
    {
        if (empty($this->UacPermsArray))
            $this->UacPermsArray = UAC::toArray();

        return $this->UacPermsArray;
    }
}
