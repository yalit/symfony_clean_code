<?php

namespace App\Domain\User\Rule;

use App\Domain\Shared\Validation\Rule\RuleInterface;
use App\Domain\Shared\Validation\Rule\RuleValidatorInterface;
use App\Domain\User\Repository\UserRepositoryInterface;

class UserUniqueDataEmailRuleValidator implements RuleValidatorInterface
{
    public function __construct(private readonly UserRepositoryInterface $userRepository) {}

    public function isValid($object, RuleInterface $rule): bool
    {
        if (!method_exists($object, 'getData')) {
            return true;
        }

        if (!array_key_exists('email', $object->getData())) {
            return true;
        }

        $targetEntity = $this->userRepository->getOneByEmail($object->getData()['email']);
        if ($targetEntity === null) {
            return true;
        }

        if (!method_exists($object, 'getUser')) {
            return true;
        }

        return $object->getUser()->getId() === $targetEntity->getId();
    }
}
