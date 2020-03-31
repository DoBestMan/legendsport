<?php
namespace App\Exceptions;

use Exception;
use Throwable;

class LimitExceededException extends Exception
{
    public function __construct($message = "Limit exceeded", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
