<?php

namespace App\Domain\User\Model\Specification;

use App\Domain\Shared\Specification\SpecificationInterface;
use App\Domain\User\Model\User;
use App\Domain\User\Repository\UserRepositoryInterface;

class UserUniqueEmailSpecification implements SpecificationInterface
{
    public function __construct(private readonly UserRepositoryInterface $userRepository) {}

    /**
     * @param User $object
     */
    public function isSatisfiedBy($object): bool
    {
        return $this->userRepository->getOneByEmail($object->getEmail()) === null;
    }
}
