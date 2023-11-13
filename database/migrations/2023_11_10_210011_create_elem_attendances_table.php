<?php

use App\Models\ElemAttendance;
use App\Models\ElementaryStudent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElemAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('elem_attendances', function (Blueprint $table) 
        {
            $table->id();
            
            $table->foreignId(ElemAttendance::FIELD_STUDENT_FK)
                  ->constrained(ElementaryStudent::getTableName())
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->timestamp(ElemAttendance::FIELD_TIME_IN     )->default( now() );
            $table->timestamp(ElemAttendance::FIELD_TIME_OUT    )->nullable();
            $table->string(ElemAttendance::FIELD_STATUS         );
            $table->integer(ElemAttendance::FIELD_WEEK_NO       );
            $table->integer(ElemAttendance::FIELD_UPDATED_BY    )->nullable();
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
        Schema::dropIfExists('elem_attendances');
    }
}
