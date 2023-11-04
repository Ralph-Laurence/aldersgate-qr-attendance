<?php

namespace App\Http\Extensions;

class Routes
{
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