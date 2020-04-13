<?php
namespace App\Tournament\Exceptions;

use Exception;
use Throwable;

class MatchAlreadyStartedException extends Exception
{
    public function __construct(
        $message = "Match has already started",
        $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
