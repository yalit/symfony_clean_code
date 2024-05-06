<?php

namespace App\Tests\Shared\Specification;

use App\Domain\Shared\Validation\Rule\RuleInterface;
use App\Domain\Shared\Validation\Rule\RuleValidatorInterface;

class TestEvenRuleValidator implements RuleValidatorInterface
{
    public function isValid($object, RuleInterface $rule): bool
    {
        if (!$rule instanceof TestEvenRule) {
            return false;
        }

        if (!is_int($object)) {
            return false;
        }

        return $object % 2 === 0;
    }
}
