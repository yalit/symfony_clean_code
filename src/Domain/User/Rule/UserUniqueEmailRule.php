<?php

namespace App\Domain\User\Rule;

use App\Domain\Shared\Validation\Rule\RuleInterface;
use App\Domain\User\Model\User;
use App\Domain\User\Repository\UserRepositoryInterface;
use Attribute;

#[\Attribute]
class UserUniqueEmailRule implements RuleInterface
{
    public function getValidatorClass(): string
    {
        return UserUniqueEmailRuleValidator::class;
    }

    public function getErrorMessage(): string
    {
        return 'Email already exists';
    }
}
