<?php

use App\Models\TournamentPlayer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTournamentPlayersTable extends Migration
{
    public function up()
    {
        Schema::create(TournamentPlayer::table(), function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';

            $table->bigIncrements('id');
            $table->unsignedBigInteger('tournament_id');
            $table->unsignedSmallInteger('user_id');
            $table->mediumInteger('chips');
            $table->timestamps();

            $table
                ->foreign('tournament_id')
                ->references('id')
                ->on('tournaments');
            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->unique(['tournament_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists(TournamentPlayer::table());
    }
}
