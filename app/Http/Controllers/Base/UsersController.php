<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use App\Http\Extensions\Routes;
use App\Http\Extensions\Utils;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

abstract class UsersController extends Controller
{
    public const MODE_CREATE = 0;
    public const MODE_UPDATE = 1;
    
    public const MSG_SUCCESS_DELETE         = 'A user has been successfully removed from the system.';
    public const MSG_SUCCESS_ADDED          = 'User successfully added.';
    public const MSG_SUCCESS_UPDATED        = 'User record updated successfully.';
    
    public const MSG_FAIL_INDEX_EMAIL       = 'Email is already in use.';
    public const MSG_FAIL_INDEX_USERNAME    = 'Username is taken.';

    abstract protected function saveModel(Request $request, $mode = 0);

    public function update(Request $request)
    {
        return $this->saveModel($request, self::MODE_UPDATE);
    }
 
    public function store(Request $request)
    {
        return $this->saveModel($request, self::MODE_CREATE);
    }

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

    public function deleteUser(Request $request, User $model)
    {
        if (empty($request->input('user-key')))
            abort(500);

        try 
        {
            $id = decrypt($request->input('user-key'));

            DB::table($model->getTable())
                ->where(User::FIELD_ID, '=', $id)
                ->delete();
 
            $flashMessage = Utils::makeFlashMessage(self::MSG_SUCCESS_DELETE, Utils::FLASH_MESSAGE_SUCCESS, 'toast');

            return redirect()->back()->with('flash-message', $flashMessage);

        } 
        catch (\Throwable $th) 
        {
            //throw $th;
            abort(500);
        }
    }
}
