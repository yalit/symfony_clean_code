<?php

namespace App\Domain\User\Action;

use App\Domain\Shared\Action\Action;
use App\Domain\Shared\Action\ActionInput;
use App\Domain\Shared\Action\ActionOutput;
use App\Domain\Shared\Authorization\AuthorizationCheckerInterface;
use App\Domain\Shared\Exception\InvalidRequester;
use App\Domain\User\Authorization\DeleteUserAuthorization;
use App\Domain\User\Model\Enum\UserRole;
use App\Domain\User\Model\User;
use App\Domain\User\Repository\UserRepositoryInterface;

class DeleteUserAction implements Action
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
    ) {}

    /**
     * @throws InvalidRequester
     */
    public function __invoke(DeleteUserInput $input): ?ActionOutput
    {
        if (!$this->authorizationChecker->allows(DeleteUserAuthorization::AUTHORIZATION_ACTION, $input)) {
            throw new InvalidRequester();
        }

        $this->userRepository->delete($input->getUser()->getId());

        return null;
    }
}
