<?php

namespace App\Tests\Shared\Specification;

use App\Domain\Shared\Validation\Rule\RuleInterface;
use App\Domain\Shared\Validation\Rule\RuleValidatorInterface;

class NotExistingRuleValidator implements RuleValidatorInterface
{

    public function isValid($object, RuleInterface $rule): bool
    {
        return false;
    }
}
