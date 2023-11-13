<?php

use App\Models\SeniorsAttendance;
use App\Models\SeniorStudent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeniorsAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seniors_attendances', function (Blueprint $table) 
        {
            $table->id();

            $table->foreignId(SeniorsAttendance::FIELD_STUDENT_FK)
                ->constrained(SeniorStudent::getTableName())
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->timestamp(SeniorsAttendance::FIELD_TIME_IN)->default(now());
            $table->timestamp(SeniorsAttendance::FIELD_TIME_OUT)->nullable();
            $table->string(SeniorsAttendance::FIELD_STATUS);
            $table->integer(SeniorsAttendance::FIELD_WEEK_NO);
            $table->integer(SeniorsAttendance::FIELD_UPDATED_BY)->nullable();
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
        Schema::dropIfExists('seniors_attendances');
    }
}
