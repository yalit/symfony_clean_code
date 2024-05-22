<?php

namespace App\Infrastructure\Security\Model;

use App\Domain\User\Model\Enum\UserRole;
use App\Domain\User\Model\User;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class SecurityUser extends User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public function getRoles(): array
    {
        return [$this->getSecurityRole($this->getRole()), 'ROLE_USER'];
    }

    public function eraseCredentials(): void
    {
        $this->setPassword('');
    }

    public function getUserIdentifier(): string
    {
        return $this->getEmail();
    }

    private function getSecurityRole(UserRole $role): string
    {
        return match ($role) {
            UserRole::ADMIN => 'ROLE_ADMIN',
            UserRole::EDITOR => 'ROLE_EDITOR',
            UserRole::AUTHOR => 'ROLE_AUTHOR'
        };
    }
}
