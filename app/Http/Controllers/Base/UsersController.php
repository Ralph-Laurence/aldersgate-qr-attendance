<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use App\Http\Extensions\Routes;

class UsersController extends Controller
{
    public function getWorksheetTabRoutesExcept($except)
    {
        $routes = 
        [
            'librarians'    => route(Routes::LIBRARIANS['index']),
            'moderators'    => route(Routes::MODERATORS['index']),
            'master'        => route(Routes::MASTER_USERS['index']),
        ];

        // Exclude a route
        if (array_key_exists($except, $routes))
            unset($routes[$except]);

        return $routes;
    }
}
