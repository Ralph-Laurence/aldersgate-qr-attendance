<?php

namespace App\Http\Extensions;

class RouteNames
{
    //
    // GET ROUTES
    //
    public const DASHBOARD      = 'backoffice.dashboard';
    public const ATTENDANCE     = 'backoffice.attendance';
    //public const STUDENTS       = 'backoffice.students';
    public const USERS          = 'backoffice.users';
    public const SETTINGS       = 'backoffice.prefs';

    public const MY_PROFILE     = 'common.myprofile';
    public const SUPPORT        = 'common.support';
    public const ABOUT          = 'common.about';

    // New
    public const ELEM_STUDENTS          = 'backoffice.students.elementary';
    public const JUNIOR_STUDENTS        = 'backoffice.students.juniors';
    public const SENIOR_STUDENTS        = 'backoffice.students.seniors';
    public const COLLEGE_STUDENTS      = 'backoffice.students.college';
    public const SECONDARY_STUDENTS     = 'backoffice.students.secondary';

    //
    // POST ROUTES
    //
    // public const ADD_STUDENT    = 'backoffice.add-student';
    // public const EDIT_STUDENT   = 'backoffice.edit-student';
    // public const DELETE_STUDENT = 'backoffice.delete-student';

    public const ADD_COLLEGE_STUDENT    = 'backoffice.add-college-student';
    public const EDIT_COLLEGE_STUDENT   = 'backoffice.edit-college-student';
    public const DELETE_COLLEGE_STUDENT = 'backoffice.delete-college-student';
 
    public static function toLabel( $routeName ) : string
    {
        $labels = 
        [
            self::DASHBOARD         => 'Dashboard',
            self::ATTENDANCE        => 'Attendance',
            //self::STUDENTS      => 'Students',
            self::COLLEGE_STUDENTS => 'College Students',
            self::SENIOR_STUDENTS   => 'Senior Students',
            self::JUNIOR_STUDENTS   => 'Junior Students',
            self::ELEM_STUDENTS     => 'Elementary Students',
            self::USERS             => 'Users',
            self::SETTINGS          => 'Settings',
            self::MY_PROFILE        => 'My Profile',
            self::SUPPORT           => 'Support',
            self::ABOUT             => 'About'
        ];

        return $labels[$routeName];
    }
}