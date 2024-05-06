<?php

namespace App\Domain\User\Authorization;

use App\Domain\Shared\Authorization\AuthorizationInterface;
use App\Domain\User\Action\CreateUserInput;
use App\Domain\User\Model\Enum\UserRole;
use App\Domain\User\Repository\UserRepositoryInterface;

class CreateUserAuthorization implements AuthorizationInterface
{
    public const AUTHORIZATION_ACTION = 'domain_create_user';

    public function __construct(private readonly UserRepositoryInterface $userRepository) {}

    public function supports(string $action, $resource): bool
    {
        return $action === self::AUTHORIZATION_ACTION && $resource instanceof CreateUserInput;
    }

    /**
     * @param CreateUserInput $resource
     */
    public function allows(string $action, $resource): bool
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
