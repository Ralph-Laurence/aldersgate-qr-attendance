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
    private $privilege  = null;

    function __construct()
    {
        $this->usersModel = new User();
        $this->privilege  = UAC::ROLE_MODERATOR;
    }

    public function index($sort = null) 
    {
        $options    = ['sort' => $sort];
        $dataset    = $this->usersModel->getUsers($options, $this->privilege);

        return view('backoffice.users.moderators.index')
            ->with('usersDataset'   , $dataset)
            ->with('totalRecords'   , $dataset->count())
            ->with('formActions', 
            [
                'storeUser'   => route( Routes::MODERATORS['store'] ),
                'updateUser'  => route( Routes::MODERATORS['update']),
                'deleteUser'  => route( Routes::MODERATORS['destroy']),
                'disableUser' => route( Routes::MODERATORS['disable']),
                'enableUser'  => route( Routes::MODERATORS['enable']),
            ])
            ->with('worksheetTabRoutes', $this->getWorksheetTabRoutesExcept('moderator'));
    }

    public function update(Request $request)
    {
        return $this->saveModel($request, $this->usersModel, parent::MODE_UPDATE, $this->privilege);
    }
 
    public function store(Request $request)
    {
        return $this->saveModel($request, $this->usersModel, parent::MODE_CREATE, $this->privilege);
    }

    public function destroy(Request $request)
    {
        return $this->deleteUser($request, $this->usersModel, $this->privilege);
    }

    public function disable(Request $request)
    {
        return $this->disableUser($request, $this->privilege);
    }

    public function enable(Request $request)
    {
        return $this->enableUser($request, $this->privilege);
    }
}
