<?php

namespace App\Domain\Shared\Validation;

interface ValidatorInterface
{
    public function isValid($object): bool;
}
