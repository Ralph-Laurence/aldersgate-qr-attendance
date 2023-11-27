<?php

use App\Models\JuniorsAttendance;
use App\Models\JuniorStudent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJuniorsAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('juniors_attendances', function (Blueprint $table) 
        {
            $table->id();
            
            $table->foreignId(JuniorsAttendance::FIELD_STUDENT_FK)
                  ->constrained(JuniorStudent::getTableName())
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->timestamp(JuniorsAttendance::FIELD_TIME_IN     )->default( now() );
            $table->timestamp(JuniorsAttendance::FIELD_TIME_OUT    )->nullable();
            $table->string(JuniorsAttendance::FIELD_STATUS         );
            $table->integer(JuniorsAttendance::FIELD_WEEK_NO       );
            $table->integer(JuniorsAttendance::FIELD_UPDATED_BY    )->nullable();
            $table->timestamps();
            $table->timestamp(JuniorsAttendance::FIELD_RECORDED_AT )->default( now() );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('juniors_attendances');
    }
}
