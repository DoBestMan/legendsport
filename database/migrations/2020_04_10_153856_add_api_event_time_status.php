<?php

use App\Betting\TimeStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddApiEventTimeStatus extends Migration
{
    public function up()
    {
        Schema::table("api_events", function (Blueprint $table) {
            $table->unsignedInteger("sport_id")->nullable();
            $table->string("time_status");
            $table->timestamp('starts_at')->nullable();
            $table->string("team_away");
            $table->string("team_home");
            $table->unsignedInteger("score_away")->nullable();
            $table->unsignedInteger("score_home")->nullable();
            $table->string("provider");
        });

        DB::table("api_events")
            ->get()
            ->each(function ($apiEvent) {
                $data = json_decode($apiEvent->api_data, true);

                DB::table("api_events")
                    ->where("id", $apiEvent->id)
                    ->update([
                        "sport_id" => $data["Sport"],
                        "starts_at" => $data["MatchTime"],
                        "team_away" => $data["AwayTeam"],
                        "team_home" => $data["HomeTeam"],
                        "provider" => "jsonodd",
                        "time_status" => TimeStatus::ENDED(),
                    ]);
            });

        Schema::table("api_events", function (Blueprint $table) {
            $table->dropColumn("api_data");
        });
    }

    public function down()
    {
        Schema::table("api_events", function (Blueprint $table) {
            $table->dropColumn([
                "provider",
                "score_away",
                "score_home",
                "sport_id",
                "starts_at",
                "team_away",
                "team_home",
                "time_status",
            ]);
        });
    }
}
