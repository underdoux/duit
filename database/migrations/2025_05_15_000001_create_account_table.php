<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountTable extends Migration
{
    public function up()
    {
        Schema::create('account', function (Blueprint $table) {
            $table->increments('accountid');
            $table->string('name');
            $table->decimal('balance', 10, 2);
            $table->string('accountnumber')->nullable();
            $table->text('description')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('account');
    }
}
