<?php

namespace App\Domain\Shared\Authorization;

use App\Domain\Shared\Authorization\AuthorizationInterface;
use App\Domain\User\Model\Enum\UserRole;
use App\Domain\User\Repository\UserRepositoryInterface;

abstract class AbstractAdminAuthorization implements AuthorizationInterface
{
    public function __construct(protected readonly UserRepositoryInterface $userRepository) {}

    /**
     * @inheritDoc
     */
    abstract public function supports(string $action, $resource): bool;

    /**
     * @inheritDoc
     */
    public function allows(string $action, $resource): bool
    {
        if (!$this->isAdmin()) {
            return $this->allowsNonAdmin($action, $resource);
        }

        return true;
    }

    /**
     * @param object $resource
     */
    abstract protected function allowsNonAdmin(string $action, $resource): bool;

    protected function isAdmin(): bool
    {
        $currentUser = $this->userRepository->getCurrentUser();

        if (!$currentUser) {
            return false;
        }

        return $currentUser->getRole() === UserRole::ADMIN;
    }
}
