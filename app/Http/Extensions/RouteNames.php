<?php

namespace App\Http\Extensions;

class RouteNames
{
    //
    // GET ROUTES
    //
    public const DASHBOARD      = 'backoffice.dashboard';
    public const ATTENDANCE     = 'backoffice.attendance';
    public const STUDENTS       = 'backoffice.students';
    public const USERS          = 'backoffice.users';
    public const SETTINGS       = 'backoffice.prefs';

    public const MY_PROFILE     = 'common.myprofile';
    public const SUPPORT        = 'common.support';
    public const ABOUT          = 'common.about';

    //
    // POST ROUTES
    //
    public const ADD_STUDENT    = 'backoffice.add-student';
    public const EDIT_STUDENT   = 'backoffice.edit-student';
    public const DELETE_STUDENT = 'backoffice.delete-student';
}