<?php
namespace App\Tournament\Exceptions;

use Exception;
use Throwable;

class NotEnoughBalanceException extends Exception
{
    public function __construct(
        $message = "Not enough balance",
        $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
