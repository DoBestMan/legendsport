<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Welcome extends Mailable
{
    use Queueable, SerializesModels;

    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function build()
    {
        return $this->view('emails.welcome')
            ->with('name', $this->name);
    }
}
