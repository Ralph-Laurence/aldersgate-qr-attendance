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
            $table->string(Student::FIELD_STUDENT_NUM)->unique();
            $table->string(Student::FIELD_FNAME);
            $table->string(Student::FIELD_MNAME);
            $table->string(Student::FIELD_LNAME);
            $table->string(Student::FIELD_CONTACT);
            $table->string(Student::FIELD_EMAIL);
            $table->date(Student::FIELD_BIRTHDAY);
            $table->integer(Student::FIELD_COURSE_ID);
            $table->integer(Student::FIELD_YEAR);
            $table->string(Student::FIELD_PHOTO)->nullable();
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
