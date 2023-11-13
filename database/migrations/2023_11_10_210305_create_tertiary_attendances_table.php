<?php

use App\Models\TertiaryAttendance;
use App\Models\TertiaryStudent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTertiaryAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tertiary_attendances', function (Blueprint $table) 
        {
            $table->id();

            $table->foreignId(TertiaryAttendance::FIELD_STUDENT_FK)
                ->constrained(TertiaryStudent::getTableName())
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->timestamp(TertiaryAttendance::FIELD_TIME_IN     )->default(now());
            $table->timestamp(TertiaryAttendance::FIELD_TIME_OUT    )->nullable();
            $table->string(TertiaryAttendance::FIELD_STATUS         );
            $table->integer(TertiaryAttendance::FIELD_WEEK_NO       );
            $table->integer(TertiaryAttendance::FIELD_UPDATED_BY    )->nullable();
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
        Schema::dropIfExists('tertiary_attendances');
    }
}
