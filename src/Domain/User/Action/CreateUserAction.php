<?php

namespace App\Domain\User\Action;

use App\Domain\Shared\Action\Action;
use App\Domain\Shared\Action\ActionInput;
use App\Domain\Shared\Action\ActionOutput;
use App\Domain\Shared\Specification\InvalidSpecification;
use App\Domain\Shared\Specification\SpecificationVerifierInterface;
use App\Domain\User\Model\Enum\UserRole;
use App\Domain\User\Model\Factory\UserFactory;
use App\Domain\User\Model\Specification\UserUniqueEmailSpecification;
use App\Domain\User\Repository\UserRepositoryInterface;
use Exception;

class CreateUserAction implements Action
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly SpecificationVerifierInterface $specificationVerifier
    ) {
    }

    /**
     * @param CreateUserInput $input
     * @throws Exception
     */
    public function execute(ActionInput $input): ?ActionOutput
    {

        $user = match($input->getRole()) {
            UserRole::ADMIN => UserFactory::createAdmin($input->getName(), $input->getEmail(), $input->getPassword()),
            UserRole::EDITOR => UserFactory::createEditor($input->getName(), $input->getEmail(), $input->getPassword()),
            UserRole::AUTHOR => UserFactory::createAuthor($input->getName(), $input->getEmail(), $input->getPassword()),
            default => throw new \Exception('Unexpected user role'),
        };

        if(!$this->specificationVerifier->satisfies([UserUniqueEmailSpecification::class], $user)) {
            throw new InvalidSpecification(UserUniqueEmailSpecification::class, $user);
        }
        $this->userRepository->save($user);

        return null;
    }
}
