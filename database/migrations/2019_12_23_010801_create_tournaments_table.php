<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTournamentsTable extends Migration
{
    public function up()
    {
        Schema::create("tournaments", function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->boolean("avatar")->default(false);
            $table->string("name");
            $table->enum("players_limit", ["Heads-Up", "Single table", "Unlimited"]);
            $table->unsignedInteger("buy_in");
            $table->unsignedInteger("chips");
            $table->unsignedInteger("commission");
            $table->boolean("late_register")->nullable();
            $table->json("late_register_rule")->nullable();
            $table->json("prize_pool");
            $table->enum("state", [
                "Announced",
                "Registering",
                "Late registering",
                "Running",
                "Complete",
                "Cancel",
            ]);
            $table->enum("time_frame", ["Daily", "Weekly", "Monthly", "Season long"]);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists("tournaments");
    }
}
