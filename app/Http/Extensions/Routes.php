<?php

namespace App\Http\Extensions;

class Routes
{
    public const DASHBOARD =
    [
        'index'    => 'backoffice.dashboard.index'
    ];

    public const ATTENDANCE =
    [
        'index'        => 'backoffice.attendance.index',
    ];

    public const ATTENDANCE_ELEM = 
    [
        'index'        => 'backoffice.attendance.elem.index',
        'weekly'       => 'backoffice.attendance.elem.index.weekly',
        'monthly'      => 'backoffice.attendance.elem.index.monthly',
        //'index'        => 'backoffice.attendance.elem.index',
    ];

    public const ATTENDANCE_JUNIORS = 
    [
        'index'     => 'backoffice.attendance.juniors.index',
    ];

    public const ATTENDANCE_SENIORS = 
    [
        'index'     => 'backoffice.attendance.seniors.index',
    ];

    public const ATTENDANCE_COLLEGE = 
    [
        'index'     => 'backoffice.attendance.college.index',
    ];

    // public const ATTENDANCE_TODAY =
    // [
    //     'elem_index'    => 'backoffice.attendance.elem.today.index',
    //     'elem_store'    => 'backoffice.attendance.elem.today.store',
    //     'elem_update'   => 'backoffice.attendance.elem.today.update',
    //     'elem_destroy'  => 'backoffice.attendance.elem.today.destroy',

    //     'college_index'    => 'backoffice.attendance.college.today.index',
    //     'college_store'    => 'backoffice.attendance.college.today.store',
    //     'college_update'   => 'backoffice.attendance.college.today.update',
    //     'college_destroy'  => 'backoffice.attendance.college.today.destroy',
    // ];
    
    // public const ATTENDANCE_MONTHLY =
    // [
    //     'index'    => 'backoffice.attendance.monthly.index',
    // ];

    // public const ATTENDANCE_YEARLY =
    // [
    //     'index'    => 'backoffice.attendance.yearly.index',
    // ];

    public const LIBRARIANS =
    [
        'index'    => 'backoffice.users.librarians.index',
        'store'    => 'backoffice.users.librarians.store',
        'update'   => 'backoffice.users.librarians.update',
        'destroy'  => 'backoffice.users.librarians.destroy',
    ];

    public const MODERATORS =
    [
        'index'    => 'backoffice.users.moderators.index',
        'store'    => 'backoffice.users.moderators.store',
        'update'   => 'backoffice.users.moderators.update',
        'destroy'  => 'backoffice.users.moderators.destroy',
    ];

    public const MASTER_USERS =
    [
        'index'    => 'backoffice.users.master.index',
        'store'    => 'backoffice.users.master.store',
        'update'   => 'backoffice.users.master.update',
        'destroy'  => 'backoffice.users.master.destroy',
    ];

    public const ELEM_STUDENT =
    [
        'index'     => 'backoffice.students.elementary.index',
        'store'     => 'backoffice.students.elementary.store',
        'update'    => 'backoffice.students.elementary.update',
        'destroy'   => 'backoffice.students.elementary.destroy',
    ];

    public const JUNIOR_STUDENT =
    [
        'index'     => 'backoffice.students.junior.index',
        'store'     => 'backoffice.students.junior.store',
        'update'    => 'backoffice.students.junior.update',
        'destroy'   => 'backoffice.students.junior.destroy',
    ];

    public const SENIOR_STUDENT =
    [
        'index'     => 'backoffice.students.senior.index',
        'store'     => 'backoffice.students.senior.store',
        'update'    => 'backoffice.students.senior.update',
        'destroy'   => 'backoffice.students.senior.destroy',
    ];

    public const COLLEGE_STUDENT =
    [
        'index'     => 'backoffice.students.college.index',
        'store'     => 'backoffice.students.college.store',
        'update'    => 'backoffice.students.college.update',
        'destroy'   => 'backoffice.students.college.destroy',
    ];
}