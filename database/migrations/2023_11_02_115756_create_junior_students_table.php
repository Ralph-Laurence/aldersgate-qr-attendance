<?php

use App\Models\JuniorStudent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJuniorStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('junior_students', function (Blueprint $table) 
        {
            $table->id();
            $table->string(JuniorStudent::FIELD_STUDENT_NUM,  32)->unique();
            $table->string(JuniorStudent::FIELD_FNAME,        32);
            $table->string(JuniorStudent::FIELD_MNAME,        32);
            $table->string(JuniorStudent::FIELD_LNAME,        32);
            $table->string(JuniorStudent::FIELD_CONTACT,      16)->nullable();
            $table->string(JuniorStudent::FIELD_EMAIL,        64)->unique();
            $table->string(JuniorStudent::FIELD_BIRTHDAY,     24)->nullable();
            $table->integer(JuniorStudent::FIELD_GRADE_LEVEL    );
            $table->string(JuniorStudent::FIELD_PHOTO,        64)->nullable();
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
        Schema::dropIfExists('junior_students');
    }
}
