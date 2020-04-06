<?php
namespace App\Tournament;

use Exception;
use Throwable;

class AlreadyRegisteredException extends Exception
{
    public function __construct(
        $message = "Already registered",
        $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
