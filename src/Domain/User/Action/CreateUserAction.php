<?php

namespace App\Domain\User\Action;

use App\Domain\Shared\Action\Action;
use App\Domain\Shared\Action\ActionOutput;
use App\Domain\Shared\Authorization\AuthorizationCheckerInterface;
use App\Domain\Shared\Exception\InvalidRequester;
use App\Domain\Shared\Exception\InvalidSpecification;
use App\Domain\Shared\Validation\ValidatorInterface;
use App\Domain\User\Authorization\CreateUserAuthorization;
use App\Domain\User\Model\Enum\UserRole;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\Service\Factory\UserFactory;
use InvalidArgumentException;

class CreateUserAction implements Action
{
    public function __construct(
        private readonly UserRepositoryInterface        $userRepository,
        private readonly ValidatorInterface             $validator,
        private readonly UserFactory                    $userFactory,
        private readonly AuthorizationCheckerInterface  $authorizationChecker,
    ) {}

    public function __invoke(CreateUserInput $input): ?ActionOutput
    {
        if (!$this->authorizationChecker->allows(CreateUserAuthorization::AUTHORIZATION_ACTION, $input)) {
            throw new InvalidRequester(sprintf('%s is not allowed to create a user', $this->userRepository->getCurrentUser() ?? '"No user"'));
        }

        if (!$this->validator->isValid($input)) {
            throw new InvalidArgumentException('Invalid input for CreateUser Action²');
        }

        $user = match ($input->getRole()) {
            UserRole::ADMIN => $this->userFactory->createAdmin($input->getName(), $input->getEmail(), $input->getPlainPassword()),
            UserRole::EDITOR => $this->userFactory->createEditor($input->getName(), $input->getEmail(), $input->getPlainPassword()),
            UserRole::AUTHOR => $this->userFactory->createAuthor($input->getName(), $input->getEmail(), $input->getPlainPassword()),
        };

        $this->userRepository->save($user);

        return null;
    }
}
