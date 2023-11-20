<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\UsersController;
use App\Http\Extensions\Routes;
use App\Models\User;
use App\Models\Security\UserAccountControl as UAC;
use Illuminate\Http\Request;

class LibrariansController extends UsersController
{
    private $usersModel = null;

    function __construct()
    {
        $this->usersModel = new User();
    }

    public function index($sort = null) 
    {
        $options    = ['sort' => $sort];
        
        $dataset    = $this->usersModel->getUsers($options, UAC::ROLE_LIBRARIAN);

        return view('backoffice.users.librarians.index')
            ->with('usersDataset'   , $dataset)
            ->with('totalRecords'   , $dataset->count())
            ->with('formActions', 
            [
                'storeUser'  => route( Routes::LIBRARIANS['store'] ),
                'updateUser' => route( Routes::LIBRARIANS['update']),
                'deleteUser' => route( Routes::LIBRARIANS['destroy']),
            ])
            ->with('worksheetTabRoutes', $this->getWorksheetTabRoutesExcept('librarian'));
    }

    public function update(Request $request)
    {
        return $this->saveModel($request, parent::MODE_UPDATE, UAC::ROLE_LIBRARIAN);
    }
 
    public function store(Request $request)
    {
        return $this->saveModel($request, parent::MODE_CREATE, UAC::ROLE_LIBRARIAN);
    }

    public function destroy(Request $request)
    {
        return $this->deleteUser($request, $this->usersModel, UAC::ROLE_LIBRARIAN);
    }
}
