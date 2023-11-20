<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string(User::FIELD_FIRSTNAME,       32);
            $table->string(User::FIELD_MIDDLENAME,      32)->nullable();
            $table->string(User::FIELD_LASTNAME,        32);
            $table->string(User::FIELD_USERNAME,        32)->unique();
            $table->string(User::FIELD_EMAIL,           64)->unique();
            $table->string(User::FIELD_PASSWORD           );
            $table->integer(User::FIELD_PRIVILEGE         );
            $table->integer(User::FIELD_STATUS            );
            $table->integer(User::FIELD_PHOTO             )->nullable();
            $table->timestamp(User::FIELD_VERIFIED_AT     )->nullable();
            $table->timestamp(User::FIELD_LAST_LOGIN      )->nullable();
            $table->timestamp(User::FIELD_LAST_LOGOUT     )->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
