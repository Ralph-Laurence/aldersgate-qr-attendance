<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeniorHighStudentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = base_path().'/database/seeders/sql/senior_students.sql';
        $sql = file_get_contents($path);
        DB::unprepared($sql);

        $path = base_path().'/database/seeders/sql/seniors_attendance.sql';
        $sql = file_get_contents($path);
        //DB::unprepared($sql);
    }
}
