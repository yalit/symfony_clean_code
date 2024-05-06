<?php

namespace App\Domain\User\Action;

use App\Domain\Shared\Action\Action;
use App\Domain\Shared\Action\ActionInput;
use App\Domain\Shared\Action\ActionOutput;
use App\Domain\Shared\Exception\InvalidRequester;
use App\Domain\User\Model\Enum\UserRole;
use App\Domain\User\Model\User;
use App\Domain\User\Repository\UserRepositoryInterface;

class DeleteUserAction implements Action
{
    public function __construct(private readonly UserRepositoryInterface $userRepository) {}

    /**
     * @throws InvalidRequester
     */
    public function __invoke(DeleteUserInput $input): ?ActionOutput
    {
        if (!$this->isAllowed($input->getUser())) {
            throw new InvalidRequester();
        }

        $this->userRepository->delete($input->getUser()->getId());

        return null;
    }

    private function isAllowed(User $user): bool
    {
        $requester = $this->userRepository->getCurrentUser();

        if ($requester === null) {
            return false;
        }

        if ($requester->getId() === $user->getId()) {
            return false;
        }

        if ($requester->getRole() === UserRole::ADMIN) {
            return true;
        }

        return false;
    }
}
