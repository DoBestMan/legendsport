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
            $table->string('name');
            $table->enum('type', ['Single', 'Multiple']);
            $table->enum('players_limit', [
                'Heads-Up',
                'Full',
                'Unlimited',
            ]);
            $table->smallInteger('buy_in');
            $table->mediumInteger('chips');
            $table->mediumInteger('commission');
            $table->boolean('late_register');
            $table->json('late_register_rule')->nullable();
            $table->json('prize_pool');
            $table->json('prizes');
            $table->enum('state', [
                'Announced',
                'Registering',
                'Late registering',
                'Running',
                'Complete',
                'Cancel'
            ]);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tournaments');
    }
}
