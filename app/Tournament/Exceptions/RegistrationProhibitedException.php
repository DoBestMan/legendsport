<?php
namespace App\Tournament\Exceptions;

use Exception;
use Throwable;

class RegistrationProhibitedException extends Exception
{
    public function __construct(
        $message = "Registration is prohibited",
        $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
