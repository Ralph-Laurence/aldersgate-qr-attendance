<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\UsersController;
use App\Http\Extensions\Routes;
use App\Models\User;
use App\Models\Security\UserAccountControl as UAC;
use Illuminate\Http\Request;

class MasterUsersController extends UsersController
{
    private $usersModel = null;

    function __construct()
    {
        $this->usersModel = new User();
    }

    public function index($sort = null) 
    {
        $options    = ['sort' => $sort];
        $privilege  = UAC::ROLE_MASTER;
        
        $dataset    = $this->usersModel->getUsers($options, $privilege);
         
        return view('backoffice.master-users.index')
            ->with('usersDataset'   , $dataset)
            ->with('totalRecords'   , $dataset->count())
            ->with('formActions', 
            [
                'storeUser'  => route( Routes::MASTER_USERS['store'] ),
                'updateUser' => route( Routes::MASTER_USERS['update']),
                'deleteUser' => route( Routes::MASTER_USERS['destroy']),
            ])
            ->with('worksheetTabRoutes', $this->getWorksheetTabRoutesExcept('master'));
    }

    public function destroy(Request $request)
    {
        return $this->deleteUser($request, $this->usersModel);
    }

    public function saveModel(Request $request, $mode = 0)
    {
        dump($request);
    }
}
