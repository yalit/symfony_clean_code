<?php

namespace App\Domain\User\Authorization;

use App\Domain\Shared\Authorization\AuthorizationInterface;
use App\Domain\User\Action\DeleteUserInput;
use App\Domain\User\Action\EditUserInput;
use App\Domain\User\Model\Enum\UserRole;
use App\Domain\User\Repository\UserRepositoryInterface;

class DeleteUserAuthorization implements AuthorizationInterface
{
    public const AUTHORIZATION_ACTION = 'domain_delete_user';

    public function __construct(private readonly UserRepositoryInterface $userRepository) {}

    public function supports(string $action, $resource): bool
    {
        return $action === self::AUTHORIZATION_ACTION && $resource instanceof DeleteUserInput;
    }

    /**
     * @param DeleteUserInput $resource
     */
    public function allows(string $action, $resource): bool
    {
        $requester = $this->userRepository->getCurrentUser();

        if ($requester === null) {
            return false;
        }

        if ($requester->getId() === $resource->getUserId()) {
            return false;
        }

        if ($requester->getRole() === UserRole::ADMIN) {
            return true;
        }

        return false;
    }
}
