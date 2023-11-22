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
        'weekly'       => 'backoffice.attendance.elem.weekly',
        'monthly'      => 'backoffice.attendance.elem.monthly',
    ];

    public const ATTENDANCE_JUNIORS = 
    [
        'index'     => 'backoffice.attendance.juniors.index',
        'weekly'    => 'backoffice.attendance.juniors.weekly',
        'monthly'   => 'backoffice.attendance.juniors.monthly',
    ];

    public const ATTENDANCE_SENIORS = 
    [
        'index'     => 'backoffice.attendance.seniors.index',
        'weekly'    => 'backoffice.attendance.seniors.weekly',
        'monthly'   => 'backoffice.attendance.seniors.monthly',
    ];

    public const ATTENDANCE_COLLEGE = 
    [
        'index'     => 'backoffice.attendance.college.index',
        'weekly'    => 'backoffice.attendance.college.weekly',
        'monthly'   => 'backoffice.attendance.college.monthly',
    ];

    public const LIBRARIANS =
    [
        'index'    => 'backoffice.users.librarians.index',
        'store'    => 'backoffice.users.librarians.store',
        'update'   => 'backoffice.users.librarians.update',
        'destroy'  => 'backoffice.users.librarians.destroy',
        'disable'  => 'backoffice.users.librarians.disable',
        'enable'   => 'backoffice.users.librarians.enable',
    ];

    public const MODERATORS =
    [
        'index'    => 'backoffice.users.moderators.index',
        'store'    => 'backoffice.users.moderators.store',
        'update'   => 'backoffice.users.moderators.update',
        'destroy'  => 'backoffice.users.moderators.destroy',
        'disable'  => 'backoffice.users.moderators.disable',
        'enable'   => 'backoffice.users.moderators.enable',
    ];

    public const MASTER_USERS =
    [
        'index'    => 'backoffice.users.master.index',
        'store'    => 'backoffice.users.master.store',
        'update'   => 'backoffice.users.master.update',
        'destroy'  => 'backoffice.users.master.destroy',
        'disable'  => 'backoffice.users.master.disable',
        'enable'   => 'backoffice.users.master.enable',
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