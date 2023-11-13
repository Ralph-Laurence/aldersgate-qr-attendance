<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ElemStudentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = base_path().'/database/seeders/sql/elementary_students.sql';
        $sql = file_get_contents($path);
        DB::unprepared($sql);

        $path = base_path().'/database/seeders/sql/elem_attendance.sql';
        $sql = file_get_contents($path);
        DB::unprepared($sql);
    }
}
