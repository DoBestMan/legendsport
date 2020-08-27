<?php
namespace App\Tournament\Exceptions;

use Exception;
use Throwable;

/**
 * @codeCoverageIgnore
 * @deprecated
 */
class CorrelatedParlayException extends Exception
{
    public function __construct($message = "Correlated Parlay", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
