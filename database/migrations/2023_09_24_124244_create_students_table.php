<?php

use App\Models\Student;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) 
        {
            $table->bigIncrements('id');
            $table->string(Student::FIELD_STUDENT_NUM,  32)->unique();
            $table->string(Student::FIELD_FNAME,        32);
            $table->string(Student::FIELD_MNAME,        32);
            $table->string(Student::FIELD_LNAME,        32);
            $table->string(Student::FIELD_CONTACT,      16)->nullable();
            $table->string(Student::FIELD_EMAIL,        64)->unique();
            $table->date(Student::FIELD_BIRTHDAY,       24)->nullable();
            $table->integer(Student::FIELD_COURSE_ID,   10);
            $table->integer(Student::FIELD_YEAR,        10);
            $table->string(Student::FIELD_PHOTO,        64)->nullable();
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
        Schema::dropIfExists('students');
    }
}
