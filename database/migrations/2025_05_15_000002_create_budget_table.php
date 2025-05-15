<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetTable extends Migration
{
    public function up()
    {
        Schema::create('budget', function (Blueprint $table) {
            $table->increments('budgetid');
            $table->integer('userid')->unsigned();
            $table->integer('categoryid')->unsigned();
            $table->decimal('amount', 10, 2);
            $table->date('fromdate');
            $table->date('todate')->nullable();
            $table->text('description')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('budget');
    }
}
