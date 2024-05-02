<?php

namespace App\Domain\User\Service\Factory;

use App\Domain\User\Model\Enum\UserRole;
use App\Domain\User\Model\User;
use App\Domain\User\Service\PasswordHasherInterface;

final class UserFactory
{
    public function __construct(private readonly PasswordHasherInterface $passwordHasher) {}

    public function createAdmin(string $name, string $email, string $password): User
    {
        $user = new User(UniqIDFactory::create("admin"), $name, $email, UserRole::ADMIN, $password);
        $user->setPassword($this->passwordHasher->hash($password, $user));

        return $user;
    }

    public function createEditor(string $name, string $email, string $password): User
    {
        $user = new User(UniqIDFactory::create("editor"), $name, $email, UserRole::EDITOR, $password);
        $user->setPassword($this->passwordHasher->hash($password, $user));

        return $user;
    }

    public function createAuthor(string $name, string $email, string $password): User
    {
        $user = new User(UniqIDFactory::create("author"), $name, $email, UserRole::AUTHOR, $password);
        $user->setPassword($this->passwordHasher->hash($password, $user));

        return $user;
    }
}
