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
            $table->string(User::FIELD_FIRSTNAME);
            $table->string(User::FIELD_MIDDLENAME);
            $table->string(User::FIELD_LASTNAME);
            $table->string(User::FIELD_USERNAME);
            $table->string(User::FIELD_EMAIL)->unique();
            $table->timestamp(User::FIELD_VERIFIED_AT)->nullable();
            $table->string(User::FIELD_PASSWORD);
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
