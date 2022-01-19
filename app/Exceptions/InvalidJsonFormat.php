<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class InvalidJsonFormat extends Exception
{
    public function __construct(string $message = null, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message ?? 'Invalid Json format', $code, $previous);
    }
}
