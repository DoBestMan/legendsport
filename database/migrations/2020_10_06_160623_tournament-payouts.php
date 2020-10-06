<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TournamentPayouts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('CREATE TABLE tournament_payouts (
            id INT AUTO_INCREMENT NOT NULL,
            t_id BIGINT UNSIGNED DEFAULT NULL,
            u_id SMALLINT UNSIGNED DEFAULT NULL,
            payout INT NOT NULL,
            paid_date DATETIME NOT NULL,
            is_bot TINYINT(1) NOT NULL,
            user_id INT NOT NULL,
            tournament_id INT NOT NULL,
            INDEX IDX_9E417B845C19F4F5 (t_id),
            INDEX IDX_9E417B84E4A59390 (u_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;');
        DB::statement('ALTER TABLE tournament_payouts ADD CONSTRAINT FK_9E417B845C19F4F5 FOREIGN KEY (t_id) REFERENCES tournaments (id);');
        DB::statement('ALTER TABLE tournament_payouts ADD CONSTRAINT FK_9E417B84E4A59390 FOREIGN KEY (u_id) REFERENCES users (id);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
