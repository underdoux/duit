<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleaccessTable extends Migration
{
    public function up()
    {
        Schema::create('roleaccess', function (Blueprint $table) {
            $table->increments('roleaccessid');
            $table->integer('roleid')->unsigned();
            $table->integer('userid')->unsigned();
            $table->index('userid', 'deleteroleaccess');
        });
    }

    public function down()
    {
        Schema::dropIfExists('roleaccess');
    }
}
