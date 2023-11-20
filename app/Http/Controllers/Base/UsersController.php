<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use App\Http\Extensions\RegexPatterns;
use App\Http\Extensions\Routes;
use App\Http\Extensions\Utils;
use App\Http\Extensions\ValidationMessages;
use App\Models\Chmod;
use App\Models\CollegeStudent;
use App\Models\ElementaryStudent;
use App\Models\JuniorStudent;
use App\Models\SeniorStudent;
use App\Models\User;
use App\Models\Security\UserAccountControl as UAC;
use App\Rules\UserPermsRule;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

abstract class UsersController extends Controller
{
    public const MODE_CREATE = 0;
    public const MODE_UPDATE = 1;
    
    public const MSG_SUCCESS_DELETE         = 'A user has been successfully removed from the system.';
    public const MSG_SUCCESS_ADDED          = 'User successfully added.';
    public const MSG_SUCCESS_UPDATED        = 'User record updated successfully.';
    
    public const MSG_FAIL_INDEX_EMAIL       = 'Email is already in use.';
    public const MSG_FAIL_INDEX_USERNAME    = 'Username is taken.';

    // These are the routes to return back to, 
    // after a successful operation such as create, update etc.
    private const GoBackRoutes = 
    [
        UAC::ROLE_MASTER    => Routes::MASTER_USERS['index'],
        UAC::ROLE_MODERATOR => Routes::MODERATORS['index'],
        UAC::ROLE_LIBRARIAN => Routes::LIBRARIANS['index']
    ];

    public function saveModel(Request $request, $mode = 0, $privilege)
    {
        $userKey = $request->input('user-key');
        
        if (!empty($userKey))
            $userKey = decrypt($userKey);

        $inputs = $this->validateFields($request, $userKey);
        
        // Check if validation failed and a 'redirect' response was returned
        if ($inputs instanceof \Illuminate\Http\RedirectResponse)
            return $inputs;

        $_flashMsg = '';
 
        try 
        {
            $modelData = $this->createModelData($inputs, $privilege);

            $transactions =
            [
                self::MODE_CREATE => function () use ($modelData, &$_flashMsg) {
                    $user  = User::create($modelData['user_data']);
                    $chmod = $modelData['perm_data'] + [Chmod::FIELD_USER_FK => $user->id];

                    Chmod::create($chmod);

                    $_flashMsg = self::MSG_SUCCESS_ADDED;
                },

                self::MODE_UPDATE => function () use ($modelData, &$_flashMsg, $userKey) {
                    // There must be a user key present in the input request.
                    if (empty($userKey))
                        abort(500);

                    $user = User::find($userKey);

                    // If the student record is not found, do not perform an update
                    if (!$user)
                        abort(500);

                    $user->update($modelData);
                    $_flashMsg = self::MSG_SUCCESS_UPDATED;
                }
            ];

            // Make user creation atomic. Which means, when the user was successfully created,
            // then follows the creation of chmod. If atleast one of them fails, laravel will
            // revert the changes to the database
            DB::transaction(function() use ($transactions, $mode) 
            {
                $transactions[$mode]();
            });
             
            $flashMessage = Utils::makeFlashMessage($_flashMsg, Utils::FLASH_MESSAGE_SUCCESS, 'toast');

            return redirect()->route(self::GoBackRoutes[$privilege], ['sort' => 'recent'])
                ->withInput( ['form-action' => $request->input('form-action')] )
                ->with('flash-message', $flashMessage);
        }
        catch (QueryException $ex) 
        {
            if ($ex->errorInfo[1] == 1062)
            {
                $errMsg = [];

                if (Str::contains($ex->getMessage(), "for key 'users_username_unique'"))
                    $errMsg['input-uname'] = self::MSG_FAIL_INDEX_USERNAME;

                if (Str::contains($ex->getMessage(), "for key 'users_email_unique"))
                    $errMsg['input-email'] = self::MSG_FAIL_INDEX_EMAIL;

                return redirect()->route(self::GoBackRoutes[$privilege])
                    ->withErrors($errMsg)
                    ->withInput();
            }
            
            dump($ex->getMessage()); exit;
            abort(500);
        }
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

    public function deleteUser(Request $request, User $model, $privilege)
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

            return redirect()->route(self::GoBackRoutes[$privilege])
                ->with('flash-message', $flashMessage);

        } 
        catch (\Throwable $th) 
        {
            //throw $th;
            abort(500);
        }
    }

    public function commonValidationFields(array $extraFields, $updateRecordKey = null) : array
    {
        $emailRule  = $this->uniqueRuleAcrossTables(User::FIELD_EMAIL,    $updateRecordKey, ['max:50','email']);
        $unameRule  = $this->uniqueRuleAcrossTables(User::FIELD_USERNAME, $updateRecordKey, [
            'min:5', 
            'max:32',
            'regex:' . RegexPatterns::USERNAME,
        ]);

        $fields = array(
            'input-fname'   => 'required|max:32|regex:' . RegexPatterns::ALPHA_DASH_DOT_SPACE,
            'input-mname'   => 'nullable|max:32|regex:' . RegexPatterns::ALPHA_DASH_DOT_SPACE,
            'input-lname'   => 'required|max:32|:' . RegexPatterns::ALPHA_DASH_DOT_SPACE,

            'input-uname'   => $unameRule, 
            'input-email'   => $emailRule,
            
            'option-perm-students'      => ['required', new UserPermsRule()],
            'option-perm-attendance'    => ['required', new UserPermsRule()],
            'option-perm-users'         => ['required', new UserPermsRule()],
            'option-perm-advanced'      => ['required', new UserPermsRule()],
        );

        return array_merge($fields, $extraFields); //$fields + $extraFields;
    }

    private function uniqueRuleAcrossTables($field, $updateRecordKey = null, $extraRules = [])
    {
        $tables = [
            User::getTableName()
        ];

        if ($field == 'email')
        {
            $tables[] = ElementaryStudent::getTableName();
            $tables[] = JuniorStudent::getTableName();
            $tables[] = SeniorStudent::getTableName();
            $tables[] = CollegeStudent::getTableName();
        }

        $rule = ['required'];

        foreach ($tables as $table) 
        {
            $uniqueRule = Rule::unique($table, $field);

            if ($updateRecordKey !== null)
                $uniqueRule->ignore($updateRecordKey);

            $rule[] = $uniqueRule;
        }

        return array_merge($rule, $extraRules); //$rule + $extraRules;
    }

    public function commonValidationMessages(array $extraRules)
    {
        $validationMessage = 
        [
            'input-fname.required'       => ValidationMessages::required('Firstname'),
            'input-fname.max'            => ValidationMessages::maxLength(32, 'Firstname'),
            'input-fname.regex'          => ValidationMessages::alphaDashDotSpace('Firstname'),

            'input-mname.max'            => ValidationMessages::maxLength(32, 'Middlename'),
            'input-mname.regex'          => ValidationMessages::alphaDashDotSpace('Middlename'),

            'input-lname.required'       => ValidationMessages::required('Lastname'),
            'input-lname.max'            => ValidationMessages::maxLength(32, 'Lastname'),
            'input-lname.regex'          => ValidationMessages::alphaDashDotSpace('Lastname'),

            'input-uname.required'       => ValidationMessages::required('Username'),
            'input-uname.max'            => ValidationMessages::maxLength(32, 'Username'),
            'input-uname.min'            => ValidationMessages::minLength(5, 'Username'),
            'input-uname.regex'          => ValidationMessages::alphaNumUnderscore('Username'),
            'input-uname.unique'         => ValidationMessages::unique('Username'),

            'input-email.unique'         => ValidationMessages::unique('Email'),
            'input-email.required'       => ValidationMessages::required('Email'),
            'input-email.email'          => ValidationMessages::invalid('Email'),
            'input-email.max'            => ValidationMessages::maxLength(50, 'Email'),

            'option-perm-students.required'     => ValidationMessages::permission(),
            'option-perm-attendance.required'   => ValidationMessages::permission(),
            'option-perm-users.required'        => ValidationMessages::permission(),
            'option-perm-advanced.required'     => ValidationMessages::permission(),
        ];
        
        return $validationMessage + $extraRules;
    }

    private function createModelData(array $inputs, $privilege)
    {
        $data = 
        [
            'user_data' => [
                User::FIELD_FIRSTNAME   => $inputs['input-fname'],
                User::FIELD_MIDDLENAME  => $inputs['input-mname'],
                User::FIELD_LASTNAME    => $inputs['input-lname'],
                User::FIELD_USERNAME    => $inputs['input-uname'],
                User::FIELD_EMAIL       => $inputs['input-email'],
                User::FIELD_PASSWORD    => Hash::make('1234'),
                User::FIELD_STATUS      => UAC::STATUS_ACTIVE,
                User::FIELD_PRIVILEGE   => $privilege
            ],
            
            'perm_data' => $this->createPermData($privilege, 
            [
                Chmod::FIELD_ACCESS_STUDENTS    => $inputs['option-perm-students'],  
                Chmod::FIELD_ACCESS_ATTENDANCE  => $inputs['option-perm-attendance'],
                Chmod::FIELD_ACCESS_USERS       => $inputs['option-perm-users'],     
                Chmod::FIELD_ACCESS_ADVANCED    => $inputs['option-perm-advanced'],  
            ])
        ];

        return $data;
    }
    
    private function validateFields(Request $request, $recordId = null)
    {
        // Get common validation rules defined in base class
        $validationFields = $this->commonValidationFields([], $recordId);
        // 
        // Get common validation rule messages defined in base class
        $validator = Validator::make($request->all(), $validationFields, $this->commonValidationMessages([]));

        if ($validator->fails())
            return redirect()->back()->withErrors($validator)->withInput();

        $inputs = $validator->validated();

        return $inputs;
    }

    /**
    * In this code, array_flip swaps the keys and values of the $uacPerms array. 
    * Then, array_map applies a function to each value of $chmod_data array. 
    * The function takes the value of the $chmod_data array item and uses it 
    * as a key to get the corresponding value from the $uacPerms array. 
    * The use keyword is used to import the $uacPerms array into the scope of the function. 
    * The result is a new array where the values of $chmod_data have been replaced with the 
    * corresponding values from $uacPerms. This new array is then assigned back to $chmod_data. 
    * Now, $chmod_data should have the values we want to insert into the database.
    */
    public function createPermData($privilege, $perm_data) : array
    {
        $uacPerms = UAC::toArray('flip');

        $perm_data = array_map(function($value) use($uacPerms, &$privilege)
        {
            if ($privilege != UAC::ROLE_MASTER && $value == UAC::permToString(UAC::PERM_FULL_CONTROL))
                return $uacPerms[UAC::permToString(UAC::PERM_MODIFY)];

            return $uacPerms[$value];
        }, 
        $perm_data);

        return $perm_data;
    }
}
