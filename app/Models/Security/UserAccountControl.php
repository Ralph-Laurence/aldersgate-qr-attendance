<?php

namespace App\Models\Security;


class UserAccountControl
{
    /*
    -----------------------------------------------------------------
    |                       Permission Levels                       |
    -----------------------------------------------------------------
    | In the context of CRUD operations, the different permission   |
    | levels can be related to the different types of operations    |
    | that can be performed on data. Here's how the different       |
    | levels of permission could be related to CRUD operations:     |
    |                                                               |
    |                   Meaning of Access Levels                    |
    |                                                               |
    | X  -> NO ACCESS: Users with no access would not be able to    |
    |       perform any CRUD operations on data.                    |
    |                                                               |
    | R  -> READ-ONLY access: Users with read-only access would be  |
    |       able to read data, but not create, update, or delete it.|
    |                                                               |
    | W  -> WRITE ACCESS: Users with write access would be able to  |
    |       create and update data, but not delete it.              |
    |                                                               |
    | M -> (MODIFY) READ AND WRITE ACCESS: Users with read and      |
    |       write access would be able to read, create, update,     |
    |       and delete data.                                        |
    |                                                               |
    | F  -> FULL CONTROL: Full access to every feature              |
    |                                                               |
    |_______________________________________________________________|
    */
    public const PERM_READ           = 'r';
    public const PERM_WRITE          = 'w';
    public const PERM_MODIFY     = 'm';
    public const PERM_FULL_CONTROL   = 'f';
    public const PERM_DENIED         = 'x';

    public static function permToString(string $perm)
    {
        $permMap = 
        [
            self::PERM_READ           => 'Read',
            self::PERM_WRITE          => 'Write',
            self::PERM_MODIFY         => 'Modify',
            self::PERM_FULL_CONTROL   => 'Full',
            self::PERM_DENIED         => 'Denied',
        ];

        return $permMap[$perm];
    }

    //
    // User Roles
    //
    public const ROLE_LIBRARIAN      = 0;
    public const ROLE_MODERATOR      = 1;
    public const ROLE_MASTER         = 2;

    public static function roleToString(int $role)
    {
        $roleMap = 
        [
            self::ROLE_LIBRARIAN => 'Librarian',
            self::ROLE_MODERATOR => 'Moderator',
            self::ROLE_MASTER    => 'Master'
        ];

        return $roleMap[$role];
    }

    //
    // User Status
    //
    public const STATUS_DISABLED     = 0;
    public const STATUS_ACTIVE       = 1;

    public static function statusToString(int $status)
    {
        $statMap = 
        [
            self::STATUS_ACTIVE     => 'Active',
            self::STATUS_DISABLED   => 'Disabled',
        ];

        return $statMap[$status];
    }
}
