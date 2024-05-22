<?php

namespace App\Infrastructure\Security\Provider;

use App\Domain\User\Repository\UserRepositoryInterface;
use App\Infrastructure\Security\Factory\SecurityUserFactory;
use App\Infrastructure\Security\Model\SecurityUser;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @implements UserProviderInterface<SecurityUser>
 */
class SecurityUserProvider implements UserProviderInterface, PasswordUpgraderInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof SecurityUser) {
            throw new UserNotFoundException();
        }

        return $this->loadUserByIdentifier($user->getEmail());
    }

    public function supportsClass(string $class): bool
    {
        return $class === SecurityUser::class;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $user = $this->userRepository->getOneByEmail($identifier);

        if ($user === null) {
            throw new UserNotFoundException();
        }

        return SecurityUserFactory::createFromUser($user);
    }

    /**
     * @param SecurityUser&PasswordAuthenticatedUserInterface $user
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        $domainUser = $this->userRepository->getOneByEmail($user->getUserIdentifier());
        $domainUser->setPassword($newHashedPassword);
        $this->userRepository->save($domainUser);

        $user->setPassword($newHashedPassword);
    }
}
