<?php

namespace App\Providers;

use App\Models\ElementaryStudent;
use App\Models\JuniorStudent;
use App\Models\SeniorStudent;
use App\Models\TertiaryStudent;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('unique_student_no_across', function ($attribute, $value, $parameters, $validator) 
        {
            // List of your tables
            $tables = 
            [
                ElementaryStudent::getTableName(), 
                JuniorStudent::getTableName(), 
                SeniorStudent::getTableName(), 
                TertiaryStudent::getTableName()
            ];
        
            foreach ($tables as $table) 
            {
                $query = DB::table($table)->where($attribute, $value);
        
                // If we're updating a record, exclude it from the uniqueness check
                if (isset($parameters[0]) && !is_null($parameters[0])) {
                    $query->where('id', '<>', $parameters[0]);
                }
        
                if ($query->exists()) {
                    return false;
                }
            }
        
            return true;
        });        
    }
}
