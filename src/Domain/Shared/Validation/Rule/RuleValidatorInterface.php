<?php

namespace App\Domain\Shared\Validation\Rule;

interface RuleValidatorInterface
{
    public function isValid($object, RuleInterface $rule): bool;
}
