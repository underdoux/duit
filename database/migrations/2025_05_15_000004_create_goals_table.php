<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoalsTable extends Migration
{
    public function up()
    {
        Schema::create('goals', function (Blueprint $table) {
            $table->increments('goalsid');
            $table->integer('userid')->unsigned();
            $table->integer('accountid')->unsigned()->nullable();
            $table->string('name');
            $table->decimal('balance', 10, 2);
            $table->decimal('amount', 10, 2);
            $table->decimal('deposit', 10, 2);
            $table->date('deadline');
        });
    }

    public function down()
    {
        Schema::dropIfExists('goals');
    }
}
