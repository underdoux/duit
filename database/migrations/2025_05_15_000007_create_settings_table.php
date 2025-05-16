<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('settingsid');
            $table->string('company');
            $table->string('city', 200);
            $table->text('address');
            $table->string('website')->nullable();
            $table->string('phone', 200)->nullable();
            $table->text('logo');
            $table->string('currency', 5);
            $table->string('languages', 10);
            $table->string('dateformat', 20);
        });
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
