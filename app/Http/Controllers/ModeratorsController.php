<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\UsersController;
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
        
        // $permBadges = 
        // [
        //     UAC::permToString(UAC::PERM_FULL_CONTROL)  => [ 'type' => 'badge-success', 'icon' => 'fa-crown'    ],
        //     UAC::permToString(UAC::PERM_MODIFY)        => [ 'type' => 'badge-warning', 'icon' => 'fa-gear'     ],
        //     UAC::permToString(UAC::PERM_READ)          => [ 'type' => 'badge-info',    'icon' => 'fa-bookmark' ],
        //     UAC::permToString(UAC::PERM_WRITE)         => [ 'type' => 'badge-warning', 'icon' => 'fa-pen'      ],
        //     UAC::permToString(UAC::PERM_DENIED)        => [ 'type' => 'badge-danger',  'icon' => 'fa-ban'      ]
        // ];

        

        $statusBadges =
        [
            UAC::statusToString(UAC::STATUS_ACTIVE)    => [ 'type' => 'badge-success', 'icon' => 'fa-check'  ],
            UAC::statusToString(UAC::STATUS_DISABLED)  => [ 'type' => 'badge-danger',  'icon' => 'fa-times'  ],
        ];

        return view('backoffice.moderators.index')
            ->with('usersDataset'   , $dataset)
            ->with('totalRecords'   , $dataset->count())
            ->with('statusBadges'   , $statusBadges)
            ->with('formActions', 
            [
                'storeUser'  => '',/*route(Routes::ADMINISTRATORS['store'] ),*/
                'updateUser' => '', /* route(Routes::ADMINISTRATORS['update'] ), */
                'deleteUser' => '',/* route(Routes::ADMINISTRATORS['destroy']), */
            ])
            ->with('worksheetTabRoutes', $this->getWorksheetTabRoutesExcept('moderators'));
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
