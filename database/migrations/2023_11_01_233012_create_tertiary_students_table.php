<?php

use App\Models\TertiaryStudent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTertiaryStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tertiary_students', function (Blueprint $table) 
        {
            $table->id();
            $table->string(TertiaryStudent::FIELD_STUDENT_NUM,  32)->unique();
            $table->string(TertiaryStudent::FIELD_FNAME,        32);
            $table->string(TertiaryStudent::FIELD_MNAME,        32);
            $table->string(TertiaryStudent::FIELD_LNAME,        32);
            $table->string(TertiaryStudent::FIELD_CONTACT,      16)->nullable();
            $table->string(TertiaryStudent::FIELD_EMAIL,        64)->unique();
            $table->string(TertiaryStudent::FIELD_BIRTHDAY,     24)->nullable();
            $table->integer(TertiaryStudent::FIELD_COURSE_ID      );
            $table->integer(TertiaryStudent::FIELD_YEAR           );
            $table->string(TertiaryStudent::FIELD_PHOTO,        64)->nullable();
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
        Schema::dropIfExists('tertiary_students');
    }
}
