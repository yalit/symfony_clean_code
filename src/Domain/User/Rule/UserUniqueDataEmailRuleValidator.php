<?php

namespace App\Domain\User\Rule;

use App\Domain\Shared\Validation\Rule\RuleInterface;
use App\Domain\Shared\Validation\Rule\RuleValidatorInterface;
use App\Domain\User\Repository\UserRepositoryInterface;

//TODO : implement a test
class UserUniqueDataEmailRuleValidator implements RuleValidatorInterface
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function isValid($object, RuleInterface $rule): bool
    {
        if (!method_exists($object, 'getData')) {
            return true;
        }

        if (!array_key_exists('email', $object->getData())) {
            return true;
        }

        return $this->userRepository->findOneByEmail($object->getData()['email']) === null;
    }
}
