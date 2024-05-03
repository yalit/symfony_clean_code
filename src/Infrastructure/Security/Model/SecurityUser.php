<?php

namespace App\Infrastructure\Security\Model;

use App\Domain\User\Model\User;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class SecurityUser extends User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public function getRoles(): array
    {
        return [$this->getRole()->value, 'ROLE_USER'];
    }

    public function eraseCredentials(): void
    {
        $this->setPassword('');
    }

    public function getUserIdentifier(): string
    {
        return $this->getEmail();
    }
}
