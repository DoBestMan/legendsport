<?php

namespace App\Console;

use App\Jobs\AddBotsToTournaments;
use App\Jobs\PlaceBotBets;
use App\Jobs\QueueUpdateApiData;
use App\Jobs\RefreshBet365Events;
use App\Jobs\SyncMatchesResults;
use App\Jobs\UpdateOdds;
use App\Jobs\UpdateTournamentStates;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //$schedule->job(SyncMatchesResults::class)->everyMinute();
        //$schedule->job(UpdateOdds::class)->everyMinute();
        $schedule->job(QueueUpdateApiData::class)->everyMinute();
        $schedule->job(UpdateTournamentStates::class)->everyMinute();
        $schedule->job(AddBotsToTournaments::class)->everyMinute();
        $schedule->job(PlaceBotBets::class)->everyMinute();
        //$schedule->job(RefreshBet365Events::class)->twiceDaily(0, 6);
        //$schedule->job(RefreshBet365Events::class)->twiceDaily(12, 18);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
