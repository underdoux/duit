<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryTable extends Migration
{
    public function up()
    {
        Schema::create('category', function (Blueprint $table) {
            $table->increments('categoryid');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('type');
            $table->string('color', 10);
        });
    }

    public function down()
    {
        Schema::dropIfExists('category');
    }
}
