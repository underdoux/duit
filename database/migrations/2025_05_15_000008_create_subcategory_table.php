<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubcategoryTable extends Migration
{
    public function up()
    {
        Schema::create('subcategory', function (Blueprint $table) {
            $table->increments('subcategoryid');
            $table->integer('categoryid')->unsigned();
            $table->string('name');
            $table->integer('type');
            $table->text('description')->nullable();
            $table->index('categoryid', 'deletesubquery');
        });
    }

    public function down()
    {
        Schema::dropIfExists('subcategory');
    }
}
