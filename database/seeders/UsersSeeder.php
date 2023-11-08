<?php

namespace Database\Seeders;

use App\Models\Security\UserAccountControl as UAC;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // randomize birthdays 
        
        $seed = 
        [
            //
            // MASTER USER | SUPER ADMIN | HIGHEST PRIVILEGE
            //
            [
                User::FIELD_FIRSTNAME   => 'Darth',
                User::FIELD_MIDDLENAME  => 'Vader',
                User::FIELD_LASTNAME    => 'Skywalker',
                User::FIELD_USERNAME    => 'starwars',
                User::FIELD_EMAIL       => 'star@wars.com',
                User::FIELD_PASSWORD    => Hash::make('1234'),
                User::FIELD_PRIVILEGE   => UAC::ROLE_MASTER,
                User::FIELD_PERMS       => UAC::PERM_FULL_CONTROL,
                User::FIELD_STATUS      => UAC::STATUS_ACTIVE,
                'created_at'            => Carbon::now(),
                'updated_at'            => Carbon::now(),
            ],
            [
                User::FIELD_FIRSTNAME   => 'Mark',
                User::FIELD_MIDDLENAME  => 'Cortes',
                User::FIELD_LASTNAME    => 'Aldersgate',
                User::FIELD_USERNAME    => 'mark',
                User::FIELD_EMAIL       => 'mark@aldersgate.edu',
                User::FIELD_PASSWORD    => Hash::make('1234'),
                User::FIELD_PRIVILEGE   => UAC::ROLE_MASTER,
                User::FIELD_PERMS       => UAC::PERM_FULL_CONTROL,
                User::FIELD_STATUS      => UAC::STATUS_ACTIVE,
                'created_at'            => Carbon::now(),
                'updated_at'            => Carbon::now(),
            ],
            [
                User::FIELD_FIRSTNAME   => 'Paule',
                User::FIELD_MIDDLENAME  => 'Jann',
                User::FIELD_LASTNAME    => 'Maglente',
                User::FIELD_USERNAME    => 'jann',
                User::FIELD_EMAIL       => 'bigbikes@suzuki.com',
                User::FIELD_PASSWORD    => Hash::make('1234'),
                User::FIELD_PRIVILEGE   => UAC::ROLE_MASTER,
                User::FIELD_PERMS       => UAC::PERM_FULL_CONTROL,
                User::FIELD_STATUS      => UAC::STATUS_ACTIVE,
                'created_at'            => Carbon::now(),
                'updated_at'            => Carbon::now(),
            ],
            [
                User::FIELD_FIRSTNAME   => 'Cardo',
                User::FIELD_MIDDLENAME  => 'Tanggol',
                User::FIELD_LASTNAME    => 'Dalisay',
                User::FIELD_USERNAME    => 'cardo',
                User::FIELD_EMAIL       => 'fpj@probinsyano.com',
                User::FIELD_PASSWORD    => Hash::make('1234'),
                User::FIELD_PRIVILEGE   => UAC::ROLE_MASTER,
                User::FIELD_PERMS       => UAC::PERM_FULL_CONTROL,
                User::FIELD_STATUS      => UAC::STATUS_ACTIVE,
                'created_at'            => Carbon::now(),
                'updated_at'            => Carbon::now(),
            ],
            [
                User::FIELD_FIRSTNAME   => 'Captain',
                User::FIELD_MIDDLENAME  => 'Jack',
                User::FIELD_LASTNAME    => 'Sparrow',
                User::FIELD_USERNAME    => 'pirates',
                User::FIELD_EMAIL       => 'pirates@carribean.com',
                User::FIELD_PASSWORD    => Hash::make('1234'),
                User::FIELD_PRIVILEGE   => UAC::ROLE_MASTER,
                User::FIELD_PERMS       => UAC::PERM_FULL_CONTROL,
                User::FIELD_STATUS      => UAC::STATUS_DISABLED,
                'created_at'            => Carbon::now(),
                'updated_at'            => Carbon::now(),
            ],
            //
            // MODERATOR | ADMIN | MODERATE PRIVILEGE
            // 
            [
                User::FIELD_FIRSTNAME   => 'James',
                User::FIELD_MIDDLENAME  => 'Charles',
                User::FIELD_LASTNAME    => 'Bond',
                User::FIELD_USERNAME    => 'bond007',
                User::FIELD_EMAIL       => 'mi6@spy.com',
                User::FIELD_PASSWORD    => Hash::make('1234'),
                User::FIELD_PRIVILEGE   => UAC::ROLE_MODERATOR,
                User::FIELD_PERMS       => UAC::PERM_MODIFY,
                User::FIELD_STATUS      => UAC::STATUS_ACTIVE,
                'created_at'            => Carbon::now(),
                'updated_at'            => Carbon::now(),
            ],
            [
                User::FIELD_FIRSTNAME   => 'Russell',
                User::FIELD_MIDDLENAME  => 'Maximus',
                User::FIELD_LASTNAME    => 'Crowe',
                User::FIELD_USERNAME    => 'gladiator',
                User::FIELD_EMAIL       => 'emperor@rome.com',
                User::FIELD_PASSWORD    => Hash::make('1234'),
                User::FIELD_PRIVILEGE   => UAC::ROLE_MODERATOR,
                User::FIELD_PERMS       => UAC::PERM_MODIFY,
                User::FIELD_STATUS      => UAC::STATUS_ACTIVE,
                'created_at'            => Carbon::now(),
                'updated_at'            => Carbon::now(),
            ],
            //
            // LIBRARIANS | LOWEST PRIVILEGE
            // 
            [
                User::FIELD_FIRSTNAME   => 'Wednesday',
                User::FIELD_MIDDLENAME  => 'Charles',
                User::FIELD_LASTNAME    => 'Addams',
                User::FIELD_USERNAME    => 'addams',
                User::FIELD_EMAIL       => 'addams@family.com',
                User::FIELD_PASSWORD    => Hash::make('1234'),
                User::FIELD_PRIVILEGE   => UAC::ROLE_LIBRARIAN,
                User::FIELD_PERMS       => UAC::PERM_MODIFY,
                User::FIELD_STATUS      => UAC::STATUS_ACTIVE,
                'created_at'            => Carbon::now(),
                'updated_at'            => Carbon::now(),
            ],
            [
                User::FIELD_FIRSTNAME   => 'Gromit',
                User::FIELD_MIDDLENAME  => '',
                User::FIELD_LASTNAME    => 'Wallace',
                User::FIELD_USERNAME    => 'topbun',
                User::FIELD_EMAIL       => 'canine@unit.com',
                User::FIELD_PASSWORD    => Hash::make('1234'),
                User::FIELD_PRIVILEGE   => UAC::ROLE_LIBRARIAN,
                User::FIELD_PERMS       => UAC::PERM_WRITE,
                User::FIELD_STATUS      => UAC::STATUS_ACTIVE,
                'created_at'            => Carbon::now(),
                'updated_at'            => Carbon::now(),
            ],
            [
                User::FIELD_FIRSTNAME   => 'Glenn',
                User::FIELD_MIDDLENAME  => '',
                User::FIELD_LASTNAME    => 'Quagmire',
                User::FIELD_USERNAME    => 'glenn',
                User::FIELD_EMAIL       => 'giggity@giggity.com',
                User::FIELD_PASSWORD    => Hash::make('1234'),
                User::FIELD_PRIVILEGE   => UAC::ROLE_LIBRARIAN,
                User::FIELD_PERMS       => UAC::PERM_WRITE,
                User::FIELD_STATUS      => UAC::STATUS_ACTIVE,
                'created_at'            => Carbon::now(),
                'updated_at'            => Carbon::now(),
            ],
        ];

        DB::table(User::getTableName())->insert($seed);
        //
    }
}
