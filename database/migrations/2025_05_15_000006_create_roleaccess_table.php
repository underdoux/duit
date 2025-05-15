<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
