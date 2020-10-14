<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Withdrawal extends Mailable
{
    use Queueable, SerializesModels;

    private string $type;
    private array $data;
    private string $name;

    public function __construct(string $type, string $name, array $data)
    {
        $this->type = $type;
        $this->data = $data;
        $this->name = $name;
    }

    public function build()
    {
        return $this->view('emails.withdrawal')
                    ->with('data', $this->data)
                    ->with('name', $this->name);
    }
}
