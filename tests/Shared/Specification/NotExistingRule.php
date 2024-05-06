<?php

namespace App\Tests\Shared\Specification;

use App\Domain\Shared\Validation\Rule\RuleInterface;

class NotExistingRule implements RuleInterface
{
    public function getValidatorClass(): string
    {
        return NotExistingRuleValidator::class;
    }
}
