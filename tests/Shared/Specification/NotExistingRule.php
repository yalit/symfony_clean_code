<?php

namespace App\Tests\Shared\Specification;

use App\Domain\Shared\Validation\Rule\RuleInterface;

class NotExistingRule implements RuleInterface
{
    public function getValidatorClass(): string
    {
        return NotExistingRuleValidator::class;
    }

    public function getErrorMessage(): string
    {
        return "this rule is a dummy rule for testing and should not be used in production code";
    }
}
