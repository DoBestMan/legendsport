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
            $table->boolean('avatar')->default(false);
            $table->string('name')->nullable();
            $table->enum('type', ['Single', 'Multiple'])->nullable();
            $table->tinyInteger('players_limit')->nullable();
            $table->smallInteger('buy_in')->nullable();
            $table->mediumInteger('chips')->nullable();
            $table->mediumInteger('commission')->nullable();
            $table->boolean('late_register')->nullable();
            $table->json('late_register_rule')->nullable();
            $table->json('prize_pool')->nullable();
            $table->json('prizes')->nullable();
            $table->enum('state', [
                'Announced',
                'Registering',
                'Late registering',
                'Running',
                'Complete',
                'Cancel'
            ])->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tournaments');
    }
}
