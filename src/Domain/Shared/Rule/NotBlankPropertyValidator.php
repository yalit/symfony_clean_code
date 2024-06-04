<?php

namespace App\Domain\Shared\Rule;

use App\Domain\Shared\Validation\Rule\RuleInterface;
use App\Domain\Shared\Validation\Rule\RuleValidatorInterface;

class NotBlankPropertyValidator implements RuleValidatorInterface
{
    public function isValid($object, RuleInterface $rule): bool
    {
        if (!($rule instanceof NotBlankProperty)) {
            return true;
        }

        $getter = 'get' . ucfirst($rule->getPropertyName());

        if (!method_exists($object, $getter)) {
            return true;
        }

        return $object->$getter() !== "";
    }
}
