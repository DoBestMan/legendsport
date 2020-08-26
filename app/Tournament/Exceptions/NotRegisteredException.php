<?php
namespace App\Tournament\Exceptions;

use Exception;
use Throwable;

/**
 * @codeCoverageIgnore
 * @deprecated
 */
class NotRegisteredException extends Exception
{
    public function __construct($message = "Not registered", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
