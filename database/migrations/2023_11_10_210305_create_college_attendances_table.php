<?php

use App\Models\CollegeAttendance;
use App\Models\CollegeStudent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollegeAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('college_attendances', function (Blueprint $table) 
        {
            $table->id();

            $table->foreignId(CollegeAttendance::FIELD_STUDENT_FK)
                ->constrained(CollegeStudent::getTableName())
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->timestamp(CollegeAttendance::FIELD_TIME_IN     )->default(now());
            $table->timestamp(CollegeAttendance::FIELD_TIME_OUT    )->nullable();
            $table->string(CollegeAttendance::FIELD_STATUS         );
            $table->integer(CollegeAttendance::FIELD_WEEK_NO       );
            $table->integer(CollegeAttendance::FIELD_UPDATED_BY    )->nullable();
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
        Schema::dropIfExists('college_attendances');
    }
}
