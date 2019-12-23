<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTournamentsTable extends Migration
{
    public function up()
    {
        Schema::create('tournaments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('avatar');
            $table->string('name');
            $table->enum('type', ['Single','Multiple']);
            $table->integer('prize_pool');
            $table->tinyInteger('players_limit');
            $table->smallInteger('buy_in');
            $table->mediumInteger('chips');
            $table->mediumInteger('commission');
            $table->boolean('late_register');
            $table->json('late_register_rule');
            $table->enum('state', ['Announced', 'Registering', 'Late registering',
                    'Running', 'Complete', 'Cancel']);
            $table->json('prize');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tournaments');
    }
}
