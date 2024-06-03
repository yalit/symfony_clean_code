<?php

namespace App\Domain\Score\Rule;

use App\Domain\Shared\Validation\Rule\RuleInterface;
use App\Domain\Shared\Validation\Rule\RuleValidatorInterface;

class NotBlankNameValidator implements RuleValidatorInterface
{
    public function isValid($object, RuleInterface $rule): bool
    {
        if (!method_exists($object, 'getName')) {
            return true;
        }

        return $object->getName() !== "";
    }
}
