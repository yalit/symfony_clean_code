<?php

namespace App\Domain\User\Rule;

use App\Domain\Shared\Validation\Rule\RuleInterface;

#[\Attribute]
class UserUniqueDataEmailRule implements RuleInterface
{
    public function getValidatorClass(): string
    {
        return UserUniqueDataEmailRuleValidator::class;
    }

    public function getErrorMessage(): string
    {
        return 'Email already exists';
    }
}
