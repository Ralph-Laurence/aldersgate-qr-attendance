<?php

use App\Models\Attendance;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance', function (Blueprint $table) 
        {
            $currentTimestamp = DB::raw('CURRENT_TIMESTAMP');

            $table->bigIncrements('id');
            $table->integer(Attendance::FIELD_STUDENT_FK);
            $table->timestamp(Attendance::FIELD_TIME_IN)->default($currentTimestamp);
            $table->timestamp(Attendance::FIELD_TIME_OUT)->nullable();
            $table->string('attendance_date');
            $table->string(Attendance::FIELD_STATUS);
            $table->integer(Attendance::FIELD_WEEK_NO);
            $table->integer(Attendance::FIELD_UPDATED_BY)->nullable();
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
        Schema::dropIfExists('attendance');
    }
}
