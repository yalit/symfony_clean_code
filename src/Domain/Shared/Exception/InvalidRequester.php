<?php

namespace App\Domain\Shared\Exception;

use Exception;
use Throwable;

class InvalidRequester extends Exception
{
    public function __construct(?string $message = null, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message ?? "The requester is invalid", $code, $previous);
    }
}
