<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigTable extends Migration
{
    public function up()
    {
        Schema::create('config', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            
            $table->mediumIncrements('id');
            $table->mediumInteger('default_commission');
            $table->mediumInteger('default_chips');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('config');
    }
}
