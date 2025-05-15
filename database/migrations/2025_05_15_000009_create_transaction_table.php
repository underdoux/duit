<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionTable extends Migration
{
    public function up()
    {
        Schema::create('transaction', function (Blueprint $table) {
            $table->increments('transactionid');
            $table->integer('userid')->unsigned();
            $table->integer('categoryid')->unsigned();
            $table->integer('accountid')->unsigned();
            $table->string('name');
            $table->decimal('amount', 10, 2);
            $table->string('reference')->nullable();
            $table->date('transactiondate');
            $table->integer('type');
            $table->text('description')->nullable();
            $table->text('file')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaction');
    }
}
