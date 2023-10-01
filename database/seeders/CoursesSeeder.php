<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seed = 
        [
            ['course' => 'BSA'],
            ['course' => 'BSBA'],
            ['course' => 'BSCS'],
            ['course' => 'BSIT'],
            ['course' => 'BSN'],
            ['course' => 'BSP'],
            ['course' => 'BSRT'],
            ['course' => 'BSMT'],
            ['course' => 'BSPT'],
            ['course' => 'BSOT'],
            ['course' => 'BAC'],
            ['course' => 'BAE'],
            ['course' => 'BAPS'],
            ['course' => 'BEED'],
            ['course' => 'BTVTEd']
        ];

        DB::table('courses')->insert($seed);
    }
}

/*
['course' => 'Bachelor of Science in Accountancy (BSA)'],
            ['course' => 'Bachelor of Science in Business Administration (BSBA)'],
            ['course' => 'Bachelor of Science in Computer Science (BSCS)'],
            ['course' => 'Bachelor of Science in Information Technology (BSIT)'],
            ['course' => 'Bachelor of Science in Nursing (BSN)'],
            ['course' => 'Bachelor of Science in Pharmacy (BSP)'],
            ['course' => 'Bachelor of Science in Radiologic Technology (BSRT)'],
            ['course' => 'Bachelor of Science in Medical Technology (BSMT)'],
            ['course' => 'Bachelor of Science in Physical Therapy (BSPT)'],
            ['course' => 'Bachelor of Science in Occupational Therapy (BSOT)'],
            ['course' => 'Bachelor of Science in Respiratory Therapy (BSRT)'],
            ['course' => 'Bachelor of Science in Psychology (BSP)'],
            ['course' => 'Bachelor of Arts in Communication (BAC)'],
            ['course' => 'Bachelor of Arts in English (BAE)'],
            ['course' => 'Bachelor of Arts in Political Science (BAPS)'],
            ['course' => 'Bachelor of Elementary Education (BEED)'],
            ['course' => 'Bachelor of Secondary Education (BSED)'],
            ['course' => 'Bachelor of Technical-Vocational Teacher Education (BTVTEd)']
*/