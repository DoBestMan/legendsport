<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TournamentWin extends Mailable
{
    use Queueable, SerializesModels;

    private string $name;
    private string $tournamentName;
    private int $rank;
    private int $amount;

    public function __construct(string $name, string $tournamentName, int $rank, int $amount)
    {
        $this->name = $name;
        $this->tournamentName = $tournamentName;
        $this->rank = $rank;
        $this->amount = $amount;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.tournament-win')
            ->with('name', $this->name)
            ->with('tournamentName', $this->tournamentName)
            ->with('rank', $this->rank)
            ->with('amount', $this->amount);
    }
}
