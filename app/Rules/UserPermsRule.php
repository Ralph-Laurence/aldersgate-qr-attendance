<?php

namespace App\Rules;

use App\Http\Extensions\ValidationMessages;
use Illuminate\Contracts\Validation\Rule;
use App\Models\Security\UserAccountControl as UAC;

class UserPermsRule implements Rule
{
    //private $permission;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct() //($perm)
    {
        //$this->permission = $perm;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $perms = UAC::toArray('values');
        
        if (!in_array($value, $perms))
            return false;

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ValidationMessages::permission();
        //'The permission levels for the new user are either incomplete or not set. Please ensure that appropriate permissions are fully assigned before finalizing the user creation process.'
    }
}
