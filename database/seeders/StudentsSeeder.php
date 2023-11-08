<?php

namespace Database\Seeders;

use App\Models\Base\Student;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentsSeeder extends Seeder
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
            [
                Student::FIELD_STUDENT_NUM  => '00001',
                Student::FIELD_FNAME        => 'Elon',
                Student::FIELD_MNAME        => 'Reeves',
                Student::FIELD_LNAME        => 'Musk',
                Student::FIELD_COURSE_ID    => rand(1, 15),
                Student::FIELD_YEAR         => rand(1, 4),
                Student::FIELD_EMAIL        => 'elon_musk@gmail.com',
                Student::FIELD_CONTACT      => $this->generateRandomPhoneNumber(),
                Student::FIELD_BIRTHDAY     => $this->generateRandomBirthday(),
                Student::FIELD_PHOTO        => 'elon.png'
            ],
            [
                Student::FIELD_STUDENT_NUM  => '00002',
                Student::FIELD_FNAME        => 'Demi',
                Student::FIELD_MNAME        => 'Devonne',
                Student::FIELD_LNAME        => 'Lovato',
                Student::FIELD_COURSE_ID    => rand(1, 15),
                Student::FIELD_YEAR         => rand(1, 4),
                Student::FIELD_EMAIL        => 'demi@gmail.com',
                Student::FIELD_CONTACT      => $this->generateRandomPhoneNumber(),
                Student::FIELD_BIRTHDAY     => $this->generateRandomBirthday(),
                Student::FIELD_PHOTO        => 'demi.png'
            ],
            [
                Student::FIELD_STUDENT_NUM  => '00003',
                Student::FIELD_FNAME        => 'Anne-Marie',
                Student::FIELD_MNAME        => 'Rose',
                Student::FIELD_LNAME        => 'Nicholson',
                Student::FIELD_COURSE_ID    => rand(1, 15),
                Student::FIELD_YEAR         => rand(1, 4),
                Student::FIELD_EMAIL        => 'anne@gmail.com',
                Student::FIELD_CONTACT      => $this->generateRandomPhoneNumber(),
                Student::FIELD_BIRTHDAY     => $this->generateRandomBirthday(),
                Student::FIELD_PHOTO        => 'anne.png'
            ],
            [
                Student::FIELD_STUDENT_NUM  => '00004',
                Student::FIELD_FNAME        => 'Avril',
                Student::FIELD_MNAME        => 'Ramona',
                Student::FIELD_LNAME        => 'Lavigne',
                Student::FIELD_COURSE_ID    => rand(1, 15),
                Student::FIELD_YEAR         => rand(1, 4),
                Student::FIELD_EMAIL        => 'avril@gmail.com',
                Student::FIELD_CONTACT      => $this->generateRandomPhoneNumber(),
                Student::FIELD_BIRTHDAY     => $this->generateRandomBirthday(),
                Student::FIELD_PHOTO        => 'avril.png'
            ],
            [
                Student::FIELD_STUDENT_NUM  => '00005',
                Student::FIELD_FNAME        => 'Vladimir',
                Student::FIELD_MNAME        => 'Vladimirovich',
                Student::FIELD_LNAME        => 'Putin',
                Student::FIELD_COURSE_ID    => rand(1, 15),
                Student::FIELD_YEAR         => rand(1, 4),
                Student::FIELD_EMAIL        => 'putin@gmail.com',
                Student::FIELD_CONTACT      => $this->generateRandomPhoneNumber(),
                Student::FIELD_BIRTHDAY     => $this->generateRandomBirthday(),
                Student::FIELD_PHOTO        => 'putin.png'
            ],
            [
                Student::FIELD_STUDENT_NUM  => '00006',
                Student::FIELD_FNAME        => 'Angela',
                Student::FIELD_MNAME        => 'Dorothea',
                Student::FIELD_LNAME        => 'Merkel',
                Student::FIELD_COURSE_ID    => rand(1, 15),
                Student::FIELD_YEAR         => rand(1, 4),
                Student::FIELD_EMAIL        => 'angela@gmail.com',
                Student::FIELD_CONTACT      => $this->generateRandomPhoneNumber(),
                Student::FIELD_BIRTHDAY     => $this->generateRandomBirthday(),
                Student::FIELD_PHOTO        => 'angela.png'
            ],
            [
                Student::FIELD_STUDENT_NUM  => '00007',
                Student::FIELD_FNAME        => 'Donald',
                Student::FIELD_MNAME        => 'J',
                Student::FIELD_LNAME        => 'Trump',
                Student::FIELD_COURSE_ID    => rand(1, 15),
                Student::FIELD_YEAR         => rand(1, 4),
                Student::FIELD_EMAIL        => 'trump@gmail.com',
                Student::FIELD_CONTACT      => $this->generateRandomPhoneNumber(),
                Student::FIELD_BIRTHDAY     => $this->generateRandomBirthday(),
                Student::FIELD_PHOTO        => 'trump.png'
            ],
            [
                Student::FIELD_STUDENT_NUM  => '00008',
                Student::FIELD_FNAME        => 'Ed',
                Student::FIELD_MNAME        => 'Christopher',
                Student::FIELD_LNAME        => 'Sheeran',
                Student::FIELD_COURSE_ID    => rand(1, 15),
                Student::FIELD_YEAR         => rand(1, 4),
                Student::FIELD_EMAIL        => 'sheeran@gmail.com',
                Student::FIELD_CONTACT      => $this->generateRandomPhoneNumber(),
                Student::FIELD_BIRTHDAY     => $this->generateRandomBirthday(),
                Student::FIELD_PHOTO        => 'sheeran.png'
            ],
            [
                Student::FIELD_STUDENT_NUM  => '00009',
                Student::FIELD_FNAME        => 'Lisa',
                Student::FIELD_MNAME        => 'Pranpriya',
                Student::FIELD_LNAME        => 'Manobal',
                Student::FIELD_COURSE_ID    => rand(1, 15),
                Student::FIELD_YEAR         => rand(1, 4),
                Student::FIELD_EMAIL        => 'lisa@blackpink.com',
                Student::FIELD_CONTACT      => $this->generateRandomPhoneNumber(),
                Student::FIELD_BIRTHDAY     => $this->generateRandomBirthday(),
                Student::FIELD_PHOTO        => 'lisa.png'
            ],
            [
                Student::FIELD_STUDENT_NUM  => '00010',
                Student::FIELD_FNAME        => 'Charlie',
                Student::FIELD_MNAME        => 'Otto',
                Student::FIELD_LNAME        => 'Puth',
                Student::FIELD_COURSE_ID    => rand(1, 15),
                Student::FIELD_YEAR         => rand(1, 4),
                Student::FIELD_EMAIL        => 'charlie@gmail.com',
                Student::FIELD_CONTACT      => $this->generateRandomPhoneNumber(),
                Student::FIELD_BIRTHDAY     => $this->generateRandomBirthday(),
                Student::FIELD_PHOTO        => 'charlie.png'
            ],
            [
                Student::FIELD_STUDENT_NUM  => '00012',
                Student::FIELD_FNAME        => 'Tom',
                Student::FIELD_MNAME        => 'Cruise',
                Student::FIELD_LNAME        => 'Mapother',
                Student::FIELD_COURSE_ID    => rand(1, 15),
                Student::FIELD_YEAR         => rand(1, 4),
                Student::FIELD_EMAIL        => 'mission@impossible.com',
                Student::FIELD_CONTACT      => $this->generateRandomPhoneNumber(),
                Student::FIELD_BIRTHDAY     => $this->generateRandomBirthday(),
                Student::FIELD_PHOTO        => 'tom.png'
            ],
            [
                Student::FIELD_STUDENT_NUM  => '00013',
                Student::FIELD_FNAME        => 'Brackeys',
                Student::FIELD_MNAME        => 'Asbjorn',
                Student::FIELD_LNAME        => 'Thirslund',
                Student::FIELD_COURSE_ID    => rand(1, 15),
                Student::FIELD_YEAR         => rand(1, 4),
                Student::FIELD_EMAIL        => 'brackeys@unity3d.com',
                Student::FIELD_CONTACT      => $this->generateRandomPhoneNumber(),
                Student::FIELD_BIRTHDAY     => $this->generateRandomBirthday(),
                Student::FIELD_PHOTO        => 'brackeys.png'
            ],
            [
                Student::FIELD_STUDENT_NUM  => '00014',
                Student::FIELD_FNAME        => 'James',
                Student::FIELD_MNAME        => 'Arthur',
                Student::FIELD_LNAME        => 'Gosling',
                Student::FIELD_COURSE_ID    => rand(1, 15),
                Student::FIELD_YEAR         => rand(1, 4),
                Student::FIELD_EMAIL        => 'james@java.com',
                Student::FIELD_CONTACT      => $this->generateRandomPhoneNumber(),
                Student::FIELD_BIRTHDAY     => $this->generateRandomBirthday(),
                Student::FIELD_PHOTO        => 'james.png'
            ],
            [
                Student::FIELD_STUDENT_NUM  => '00015',
                Student::FIELD_FNAME        => 'Rasmus',
                Student::FIELD_MNAME        => 'Olsen',
                Student::FIELD_LNAME        => 'Lerdorf',
                Student::FIELD_COURSE_ID    => rand(1, 15),
                Student::FIELD_YEAR         => rand(1, 4),
                Student::FIELD_EMAIL        => 'rasmus@php.com',
                Student::FIELD_CONTACT      => $this->generateRandomPhoneNumber(),
                Student::FIELD_BIRTHDAY     => $this->generateRandomBirthday(),
                Student::FIELD_PHOTO        => 'rasmus.png'
            ],
            [
                Student::FIELD_STUDENT_NUM  => '00016',
                Student::FIELD_FNAME        => 'Guido',
                Student::FIELD_MNAME        => 'van',
                Student::FIELD_LNAME        => 'Rosum',
                Student::FIELD_COURSE_ID    => rand(1, 15),
                Student::FIELD_YEAR         => rand(1, 4),
                Student::FIELD_EMAIL        => 'guido@python.com',
                Student::FIELD_CONTACT      => $this->generateRandomPhoneNumber(),
                Student::FIELD_BIRTHDAY     => $this->generateRandomBirthday(),
                Student::FIELD_PHOTO        => 'guido.png'
            ],
            [
                Student::FIELD_STUDENT_NUM  => '00017',
                Student::FIELD_FNAME        => 'Brendan',
                Student::FIELD_MNAME        => 'Eich',
                Student::FIELD_LNAME        => 'Ecma',
                Student::FIELD_COURSE_ID    => rand(1, 15),
                Student::FIELD_YEAR         => rand(1, 4),
                Student::FIELD_EMAIL        => 'brendan@js.com',
                Student::FIELD_CONTACT      => $this->generateRandomPhoneNumber(),
                Student::FIELD_BIRTHDAY     => $this->generateRandomBirthday(),
                Student::FIELD_PHOTO        => 'brendan.png'
            ],
            [
                Student::FIELD_STUDENT_NUM  => '00018',
                Student::FIELD_FNAME        => 'Tim',
                Student::FIELD_MNAME        => 'Berners',
                Student::FIELD_LNAME        => 'Lee',
                Student::FIELD_COURSE_ID    => rand(1, 15),
                Student::FIELD_YEAR         => rand(1, 4),
                Student::FIELD_EMAIL        => 'tim@html.com',
                Student::FIELD_CONTACT      => $this->generateRandomPhoneNumber(),
                Student::FIELD_BIRTHDAY     => $this->generateRandomBirthday(),
                Student::FIELD_PHOTO        => 'tim.png'
            ],
            [
                Student::FIELD_STUDENT_NUM  => '00020',
                Student::FIELD_FNAME        => 'Markus',
                Student::FIELD_MNAME        => 'Notch',
                Student::FIELD_LNAME        => 'Persson',
                Student::FIELD_COURSE_ID    => rand(1, 15),
                Student::FIELD_YEAR         => rand(1, 4),
                Student::FIELD_EMAIL        => 'notch@minecraft.net',
                Student::FIELD_CONTACT      => $this->generateRandomPhoneNumber(),
                Student::FIELD_BIRTHDAY     => $this->generateRandomBirthday(),
                Student::FIELD_PHOTO        => 'notch.png'
            ],
            [
                Student::FIELD_STUDENT_NUM  => '00035',
                Student::FIELD_FNAME        => 'Bill',
                Student::FIELD_MNAME        => 'Henry',
                Student::FIELD_LNAME        => 'Gates',
                Student::FIELD_COURSE_ID    => rand(1, 15),
                Student::FIELD_YEAR         => rand(1, 4),
                Student::FIELD_EMAIL        => 'bill_gates@gmail.com',
                Student::FIELD_CONTACT      => $this->generateRandomPhoneNumber(),
                Student::FIELD_BIRTHDAY     => $this->generateRandomBirthday(),
                Student::FIELD_PHOTO        => 'bill.png'
            ],
            [
                Student::FIELD_STUDENT_NUM  => '00110',
                Student::FIELD_FNAME        => 'Jeff',
                Student::FIELD_MNAME        => 'Preston',
                Student::FIELD_LNAME        => 'Bezos',
                Student::FIELD_COURSE_ID    => rand(1, 15),
                Student::FIELD_YEAR         => rand(1, 4),
                Student::FIELD_EMAIL        => 'jeff_bezos@gmail.com',
                Student::FIELD_CONTACT      => $this->generateRandomPhoneNumber(),
                Student::FIELD_BIRTHDAY     => $this->generateRandomBirthday(),
                Student::FIELD_PHOTO        => 'jeff.png'
            ],
            [
                Student::FIELD_STUDENT_NUM  => '00142',
                Student::FIELD_FNAME        => 'Mark',
                Student::FIELD_MNAME        => 'Elliott',
                Student::FIELD_LNAME        => 'Zuckerberg',
                Student::FIELD_COURSE_ID    => rand(1, 15),
                Student::FIELD_YEAR         => rand(1, 4),
                Student::FIELD_EMAIL        => 'mark_zuckerberg@facebook.com',
                Student::FIELD_CONTACT      => $this->generateRandomPhoneNumber(),
                Student::FIELD_BIRTHDAY     => $this->generateRandomBirthday(),
                Student::FIELD_PHOTO        => 'mark.png'
            ],
            [
                Student::FIELD_STUDENT_NUM  => '00295',
                Student::FIELD_FNAME        => 'Steve',
                Student::FIELD_MNAME        => 'Paul',
                Student::FIELD_LNAME        => 'Jobs',
                Student::FIELD_COURSE_ID    => rand(1, 15),
                Student::FIELD_YEAR         => rand(1, 4),
                Student::FIELD_EMAIL        => 'steve_jobs@apple.com',
                Student::FIELD_CONTACT      => $this->generateRandomPhoneNumber(),
                Student::FIELD_BIRTHDAY     => $this->generateRandomBirthday(),
                Student::FIELD_PHOTO        => 'steve.png'
            ],
            [
                Student::FIELD_STUDENT_NUM  => '00301',
                Student::FIELD_FNAME        => 'Kim',
                Student::FIELD_MNAME        => 'Jong',
                Student::FIELD_LNAME        => 'Un',
                Student::FIELD_COURSE_ID    => rand(1, 15),
                Student::FIELD_YEAR         => rand(1, 4),
                Student::FIELD_EMAIL        => 'nuclear@facility.com',
                Student::FIELD_CONTACT      => $this->generateRandomPhoneNumber(),
                Student::FIELD_BIRTHDAY     => $this->generateRandomBirthday(),
                Student::FIELD_PHOTO        => 'kim.png'
            ],
            [
                Student::FIELD_STUDENT_NUM  => '00400',
                Student::FIELD_FNAME        => 'Barrack',
                Student::FIELD_MNAME        => 'Hussein',
                Student::FIELD_LNAME        => 'Obama',
                Student::FIELD_COURSE_ID    => rand(1, 15),
                Student::FIELD_YEAR         => rand(1, 4),
                Student::FIELD_EMAIL        => 'nuclear@facility.com',
                Student::FIELD_CONTACT      => $this->generateRandomPhoneNumber(),
                Student::FIELD_BIRTHDAY     => $this->generateRandomBirthday(),
                Student::FIELD_PHOTO        => 'obama.png'
            ],
            [
                Student::FIELD_STUDENT_NUM  => '00506',
                Student::FIELD_FNAME        => 'Volodymyr',
                Student::FIELD_MNAME        => 'Oleksandrovych',
                Student::FIELD_LNAME        => 'Zelenskyy',
                Student::FIELD_COURSE_ID    => rand(1, 15),
                Student::FIELD_YEAR         => rand(1, 4),
                Student::FIELD_EMAIL        => 'slava@ukrainia.com',
                Student::FIELD_CONTACT      => $this->generateRandomPhoneNumber(),
                Student::FIELD_BIRTHDAY     => $this->generateRandomBirthday(),
                Student::FIELD_PHOTO        => 'zelenskyy.png'
            ],
        ];

        DB::table('students')->insert($seed);
    }

    function generateRandomPhoneNumber()
    {
        $digits = '09';
        
        for ($i = 0; $i < 8; $i++)
            $digits .= rand(0, 9);
        
        return $digits;
    }

    function generateRandomBirthday()
    {
        $min = Carbon::create(2000, 1, 1)->timestamp;
        $max = Carbon::create(2004, 12, 31)->timestamp;

        $birthday = Carbon::createFromTimestamp(rand($min, $max));

        return $birthday->format('n/j/Y');
    }
}
