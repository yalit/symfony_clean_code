<?php

namespace App\Domain\Shared\Validation\Rule;

interface RuleValidatorInterface
{
    /**
     * @param mixed $object
     */
    public function isValid($object, RuleInterface $rule): bool;
}
