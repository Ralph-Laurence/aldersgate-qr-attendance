<?php

use App\Models\SeniorStudent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeniorStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('senior_students', function (Blueprint $table) 
        {
            $table->id();
            $table->string(SeniorStudent::FIELD_STUDENT_NUM,  32)->unique();
            $table->string(SeniorStudent::FIELD_FNAME,        32);
            $table->string(SeniorStudent::FIELD_MNAME,        32);
            $table->string(SeniorStudent::FIELD_LNAME,        32);
            $table->string(SeniorStudent::FIELD_CONTACT,      16)->nullable();
            $table->string(SeniorStudent::FIELD_EMAIL,        64)->unique();
            $table->string(SeniorStudent::FIELD_BIRTHDAY,     24)->nullable();
            $table->integer(SeniorStudent::FIELD_GRADE_LEVEL    );
            $table->integer(SeniorStudent::FIELD_STRAND_ID      );
            $table->string(SeniorStudent::FIELD_PHOTO,        64)->nullable();
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
        Schema::dropIfExists('senior_students');
    }
}
