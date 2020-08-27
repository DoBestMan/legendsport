<?php
namespace App\Tournament\Exceptions;

use Exception;
use Throwable;

/**
 * @codeCoverageIgnore
 * @deprecated
 */
class NotEnoughChipsException extends Exception
{
    public function __construct(
        $message = "Not enough chips",
        $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
