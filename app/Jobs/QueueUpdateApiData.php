<?php

namespace App\Jobs;

use App\Jobs\Tournaments\UpdateApiDataJob;
use Illuminate\Bus\Dispatcher;

class QueueUpdateApiData
{
    public function handle(Dispatcher $dispatcher, int $updateSpeed = 5): void
    {
        $speedMap = [
            1,
            2,
            3,
            4,
            6,
            12,
            15,
            20,
            30,
            60
        ];

        $numberOfUpdates = $speedMap[$updateSpeed];
        $offsetJump = 60/$numberOfUpdates;

        for ($i=0; $i < $numberOfUpdates; $i++) {
            $dispatcher->dispatch(new UpdateApiDataJob($i * $offsetJump));
        }
    }
}
