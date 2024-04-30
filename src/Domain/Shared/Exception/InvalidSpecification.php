<?php

namespace App\Domain\Shared\Exception;

use Exception;
use Throwable;

class InvalidSpecification extends Exception
{
    public function __construct(string $specificationClass, $object, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(sprintf("The following specification %s is not satisfied by %s", $specificationClass, (string)$object), $code, $previous);
    }
}
