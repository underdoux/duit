<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('userid');
            $table->string('email', 60);
            $table->string('name');
            $table->string('password');
            $table->string('role', 20)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('status', 20);
            $table->string('remember_token', 100)->nullable();
            $table->dateTime('updated_at');
            $table->dateTime('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
