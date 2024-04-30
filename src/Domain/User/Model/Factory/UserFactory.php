<?php

namespace App\Domain\User\Model\Factory;

use App\Domain\User\Model\Enum\UserRole;
use App\Domain\User\Model\User;

final class UserFactory
{
    public static function createAdmin(string $name, string $email, string $password): User
    {
        return new User(UniqIDFactory::create("admin"), $name, $email, UserRole::ADMIN, $password);
    }

    public static function createEditor(string $name, string $email, string $password): User
    {
        return new User(UniqIDFactory::create("editor"), $name, $email, UserRole::EDITOR, $password);
    }

    public static function createAuthor(string $name, string $email, string $password): User
    {
        return new User(UniqIDFactory::create("author"), $name, $email, UserRole::AUTHOR, $password);
    }
}
