<?php

namespace App\Domain\User\Authorization;

use App\Domain\Shared\Authorization\AbstractAdminAuthorization;
use App\Domain\Shared\Authorization\AuthorizationInterface;
use App\Domain\User\Action\CreateUserInput;
use App\Domain\User\Action\EditUserInput;
use App\Domain\User\Model\Enum\UserRole;
use App\Domain\User\Repository\UserRepositoryInterface;

class EditUserAuthorization extends AbstractAdminAuthorization
{
    public const AUTHORIZATION_ACTION = 'domain_edit_user';

    public function __construct(UserRepositoryInterface $userRepository)
    {
        parent::__construct($userRepository);
    }

    public function supports(string $action, $resource): bool
    {
        return $action === self::AUTHORIZATION_ACTION && $resource instanceof EditUserInput;
    }

    protected function allowsNonAdmin(string $action, $resource): bool
    {
        $requester = $this->userRepository->getCurrentUser();

        if ($requester === null) {
            return false;
        }

        return $requester->getId() === $resource->getUserId();
    }
}
