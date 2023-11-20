<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\UsersController;
use App\Http\Extensions\Routes;
use App\Models\User;
use App\Models\Security\UserAccountControl as UAC;
use Illuminate\Http\Request;

class ModeratorsController extends UsersController
{
    private $usersModel = null;

    function __construct()
    {
        $this->usersModel = new User();
    }

    public function index($sort = null) 
    {
        $options    = ['sort' => $sort];
        $privilege  = UAC::ROLE_MODERATOR;
        
        $dataset    = $this->usersModel->getUsers($options, $privilege);

        return view('backoffice.users.moderators.index')
            ->with('usersDataset'   , $dataset)
            ->with('totalRecords'   , $dataset->count())
            ->with('formActions', 
            [
                'storeUser'  => route( Routes::MODERATORS['store'] ),
                'updateUser' => route( Routes::MODERATORS['update']),
                'deleteUser' => route( Routes::MODERATORS['destroy']),
            ])
            ->with('worksheetTabRoutes', $this->getWorksheetTabRoutesExcept('moderator'));
    }

    public function update(Request $request)
    {
        return $this->saveModel($request, parent::MODE_UPDATE, UAC::ROLE_MODERATOR);
    }
 
    public function store(Request $request)
    {
        return $this->saveModel($request, parent::MODE_CREATE, UAC::ROLE_MODERATOR);
    }

    public function destroy(Request $request)
    {
        return $this->deleteUser($request, $this->usersModel, UAC::ROLE_MODERATOR);
    }
}
