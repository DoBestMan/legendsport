<?php
namespace App\Tournament\Exceptions;

use Exception;
use Throwable;

/**
 * @codeCoverageIgnore
 * @deprecated
 */
class DuplicatedOddException extends Exception
{
    public function __construct($message = "Duplicated odd", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
