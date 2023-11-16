<?php

use App\Models\CollegeStudent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollegeStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('college_students', function (Blueprint $table) 
        {
            $table->id();
            $table->string(CollegeStudent::FIELD_STUDENT_NUM,  32)->unique();
            $table->string(CollegeStudent::FIELD_FNAME,        32);
            $table->string(CollegeStudent::FIELD_MNAME,        32);
            $table->string(CollegeStudent::FIELD_LNAME,        32);
            $table->string(CollegeStudent::FIELD_CONTACT,      16)->nullable();
            $table->string(CollegeStudent::FIELD_EMAIL,        64)->unique();
            $table->string(CollegeStudent::FIELD_BIRTHDAY,     24)->nullable();
            $table->integer(CollegeStudent::FIELD_COURSE_ID      );
            $table->integer(CollegeStudent::FIELD_YEAR           );
            $table->string(CollegeStudent::FIELD_PHOTO,        64)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('college_students');
    }
}
