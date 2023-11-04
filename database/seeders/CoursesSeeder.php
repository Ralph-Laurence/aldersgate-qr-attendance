<?php

namespace Database\Seeders;

use App\Models\Courses;
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
        $fieldCourse = Courses::FIELD_COURSE;
        $fieldDesc   = Courses::FIELD_COURSE_DESC;

        $seed =
            [
                [$fieldCourse => 'AB PolSci',   $fieldDesc => 'Bachelor of Arts in Political Science'],
                [$fieldCourse => 'ABCOMM',      $fieldDesc => 'Bachelor of Arts in Mass Communication'],
                [$fieldCourse => 'BAE',         $fieldDesc => 'Bachelor of Arts in English'],
                [$fieldCourse => 'BEED',        $fieldDesc => 'Bachelor of Elementary Education'],
                [$fieldCourse => 'BSED',        $fieldDesc => 'Bachelor of Secondary Education'],
                [$fieldCourse => 'BSA',         $fieldDesc => 'Bachelor of Science in Accountancy'],
                [$fieldCourse => 'BSArch',      $fieldDesc => 'Bachelor of Science in Architecture'],
                [$fieldCourse => 'BSBA',        $fieldDesc => 'Bachelor of Science in Business Administration'],
                [$fieldCourse => 'BSCpE',       $fieldDesc => 'Bachelor of Science in Computer Engineering'],
                [$fieldCourse => 'BSCrim',      $fieldDesc => 'Bachelor of Science in Criminology'],
                [$fieldCourse => 'BSCS',        $fieldDesc => 'Bachelor of Science in Computer Science'],
                [$fieldCourse => 'BSCE',        $fieldDesc => 'Bachelor of Science in Civil Engineering'],
                [$fieldCourse => 'BSEE',        $fieldDesc => 'Bachelor of Science in Electrical Engineering'],
                [$fieldCourse => 'BSECE',       $fieldDesc => 'Bachelor of Science in Electronics and Communications Engineering'],
                [$fieldCourse => 'BSIT',        $fieldDesc => 'Bachelor of Science in Information Technology'],
                [$fieldCourse => 'BSME',        $fieldDesc => 'Bachelor of Science in Mechanical Engineering'],
                [$fieldCourse => 'BSMT',        $fieldDesc => 'Bachelor of Science in Medical Technology'],
                [$fieldCourse => 'BSN',         $fieldDesc => 'Bachelor of Science in Nursing'],
                [$fieldCourse => 'BSOT',        $fieldDesc => 'Bachelor of Science in Occupational Therapy'],
                [$fieldCourse => 'BSPsych',     $fieldDesc => 'Bachelor of Science in Psychology'],
                [$fieldCourse => 'BSPT',        $fieldDesc => 'Bachelor of Science in Physical Therapy'],
                [$fieldCourse => 'BPharm',      $fieldDesc => 'Bachelor of Science in Pharmacy'],
                [$fieldCourse => 'BSRadTech',   $fieldDesc => 'Bachelor of Science in Radiologic Technology'],
                [$fieldCourse => 'BSRT',        $fieldDesc => 'Bachelor of Science in Respiratory Therapy'],
                [$fieldCourse => 'BTVTEd',      $fieldDesc => 'Bachelor of Technical-Vocational Teacher Education']
            ];

        DB::table('courses')->insert($seed);
    }
}
