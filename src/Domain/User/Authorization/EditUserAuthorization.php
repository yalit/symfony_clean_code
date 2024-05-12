<?php

namespace App\Domain\User\Authorization;

use App\Domain\Shared\Authorization\AuthorizationInterface;
use App\Domain\User\Action\CreateUserInput;
use App\Domain\User\Action\EditUserInput;
use App\Domain\User\Model\Enum\UserRole;
use App\Domain\User\Repository\UserRepositoryInterface;

class EditUserAuthorization implements AuthorizationInterface
{
    public const AUTHORIZATION_ACTION = 'domain_edit_user';

    public function __construct(private readonly UserRepositoryInterface $userRepository) {}

    public function supports(string $action, $resource): bool
    {
        return $action === self::AUTHORIZATION_ACTION && $resource instanceof EditUserInput;
    }

    /**
     * @param EditUserInput $resource
     */
    public function allows(string $action, $resource): bool
    {
        $requester = $this->userRepository->getCurrentUser();

        if ($requester === null) {
            return false;
        }

        if ($requester->getRole() === UserRole::ADMIN || $requester->getId() === $resource->getUserId()) {
            return true;
        }

        return false;
    }
}
