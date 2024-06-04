<?php

namespace App\Domain\User\Authorization;

use App\Domain\Shared\Authorization\AbstractAdminAuthorization;
use App\Domain\Shared\Authorization\AuthorizationInterface;
use App\Domain\User\Action\CreateUserInput;
use App\Domain\User\Model\Enum\UserRole;
use App\Domain\User\Repository\UserRepositoryInterface;

class CreateUserAuthorization extends AbstractAdminAuthorization
{
    public const AUTHORIZATION_ACTION = 'domain_create_user';

    public function __construct(UserRepositoryInterface $userRepository)
    {
        parent::__construct($userRepository);
    }

    public function supports(string $action, $resource): bool
    {
        return $action === self::AUTHORIZATION_ACTION && $resource instanceof CreateUserInput;
    }

    protected function allowsNonAdmin(string $action, $resource): bool
    {
        $user = $this->userRepository->getCurrentUser();

        if (!$user) {
            return count($this->userRepository->getAll()) === 0;
        }

        return false;
    }
}
