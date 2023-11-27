<?php

use App\Models\ElementaryStudent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElementaryStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('elementary_students', function (Blueprint $table) {
            $table->id();
            $table->string(ElementaryStudent::FIELD_STUDENT_NUM,  32)->unique();
            $table->string(ElementaryStudent::FIELD_FNAME,        32);
            $table->string(ElementaryStudent::FIELD_MNAME,        32);
            $table->string(ElementaryStudent::FIELD_LNAME,        32);
            $table->string(ElementaryStudent::FIELD_CONTACT,      16)->nullable();
            $table->string(ElementaryStudent::FIELD_EMAIL,        64)->unique();
            //$table->string(ElementaryStudent::FIELD_SECTION,       8);
            $table->string(ElementaryStudent::FIELD_BIRTHDAY,     24)->nullable();
            $table->integer(ElementaryStudent::FIELD_GRADE_LEVEL    );
            $table->string(ElementaryStudent::FIELD_PHOTO,        64)->nullable();
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
        Schema::dropIfExists('elementary_students');
    }
}
