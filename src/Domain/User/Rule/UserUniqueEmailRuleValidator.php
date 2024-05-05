<?php

namespace App\Domain\User\Rule;

use App\Domain\Shared\Validation\Rule\RuleInterface;
use App\Domain\Shared\Validation\Rule\RuleValidatorInterface;
use App\Domain\User\Repository\UserRepositoryInterface;

class UserUniqueEmailRuleValidator implements RuleValidatorInterface
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function isValid($object, RuleInterface $rule): bool
    {
        return $this->userRepository->findOneByEmail($object->getEmail()) === null;
    }
}
