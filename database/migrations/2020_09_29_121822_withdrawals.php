<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Withdrawals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('CREATE TABLE withdrawals (
            id INT AUTO_INCREMENT NOT NULL,
            user_id SMALLINT UNSIGNED DEFAULT NULL,
            btc_address VARCHAR(255) NOT NULL,
            amount INT NOT NULL,
            processed TINYINT(1) NOT NULL, INDEX IDX_1DD5572FA76ED395 (user_id), PRIMARY KEY(id)
         ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
        ');
        DB::statement('ALTER TABLE withdrawals ADD CONSTRAINT FK_1DD5572FA76ED395 FOREIGN KEY (user_id) REFERENCES users (id);');
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
