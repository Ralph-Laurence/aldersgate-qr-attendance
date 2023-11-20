<?php

use App\Models\Chmod;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChmodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chmods', function (Blueprint $table) {
            $table->id();

            $table->foreignId(Chmod::FIELD_USER_FK)
                  ->constrained(User::getTableName())
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->string(Chmod::FIELD_ACCESS_ADVANCED,    4);
            $table->string(Chmod::FIELD_ACCESS_ATTENDANCE,  4);
            $table->string(Chmod::FIELD_ACCESS_STUDENTS,    4);
            $table->string(Chmod::FIELD_ACCESS_USERS,       4);

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
        Schema::dropIfExists('chmods');
    }
}
