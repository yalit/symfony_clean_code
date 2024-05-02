<?php

namespace App\Domain\User\Action;

use App\Domain\Shared\Action\Action;
use App\Domain\Shared\Action\ActionOutput;
use App\Domain\Shared\Exception\InvalidRequester;
use App\Domain\Shared\Exception\InvalidSpecification;
use App\Domain\Shared\Specification\SpecificationVerifierInterface;
use App\Domain\User\Model\Enum\UserRole;
use App\Domain\User\Model\Factory\UserFactory;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\Specification\UserUniqueEmailSpecification;
use Exception;

class CreateUserAction implements Action
{
    public function __construct(
        private readonly UserRepositoryInterface        $userRepository,
        private readonly SpecificationVerifierInterface $specificationVerifier,
    ) {}

    /**
     * @throws Exception
     */
    public function __invoke(CreateUserInput $input): ?ActionOutput
    {
        if(!$this->isAllowed()) {
            throw new InvalidRequester(sprintf('%s is not allowed to create a user', $this->userRepository->getCurrentUser() ?? '"No user"'));
        }

        $user = match($input->getRole()) {
            UserRole::ADMIN => UserFactory::createAdmin($input->getName(), $input->getEmail(), $input->getPassword()),
            UserRole::EDITOR => UserFactory::createEditor($input->getName(), $input->getEmail(), $input->getPassword()),
            UserRole::AUTHOR => UserFactory::createAuthor($input->getName(), $input->getEmail(), $input->getPassword()),
        };

        if(!$this->specificationVerifier->satisfies([new UserUniqueEmailSpecification($this->userRepository)], $user)) {
            throw new InvalidSpecification(UserUniqueEmailSpecification::class, $user);
        }
        $this->userRepository->save($user);

        return null;
    }

    private function isAllowed(): bool
    {
        $user = $this->userRepository->getCurrentUser();

        if (!$user) {
            return count($this->userRepository->findAll()) === 0;
        }

        return match ($user->getRole()) {
            UserRole::ADMIN => true,
            default => false,
        };
    }
}
