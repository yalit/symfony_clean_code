<?php

namespace App\Infrastructure\Security\Service;

use App\Domain\User\Model\User;
use App\Domain\User\Service\PasswordHasherInterface;
use App\Infrastructure\Security\Service\Model\Factory\SecurityUserFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SymfonyPasswordHasher implements PasswordHasherInterface
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher) {}

    public function hash(string $plainPassword, User $user): string
    {
        $securityUser = SecurityUserFactory::createFromUser($user);
        return $this->passwordHasher->hashPassword($securityUser, $plainPassword);
    }

    public function isPasswordValid(string $plainPassword, User $user): bool
    {
        $securityUser = SecurityUserFactory::createFromUser($user);
        return $this->passwordHasher->isPasswordValid($securityUser, $plainPassword);
    }
}
