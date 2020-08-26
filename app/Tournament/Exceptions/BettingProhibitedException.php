<?php
namespace App\Tournament\Exceptions;

use Exception;
use Throwable;

/**
 * @codeCoverageIgnore
 * @deprecated
 */
class BettingProhibitedException extends Exception
{
    public function __construct(
        $message = "Betting is prohibited",
        $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
