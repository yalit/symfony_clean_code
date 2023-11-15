<?php

namespace App\Domain\Model\Factory;

use App\Domain\Model\User;
use App\Domain\Model\Enum\UserRole;

final class UserFactory
{
    public static function createAdmin(string $name, string $email, string $password): User
    {
        return new User(UniqIDFactory::create("author"), $name, $email, UserRole::ADMIN, $password);
    }

    public static function createEditor(string $name, string $email, string $password): User
    {
        return new User(UniqIDFactory::create("author"), $name, $email, UserRole::EDITOR, $password);
    }

    public static function createAuthor(string $name, string $email, string $password): User
    {
        return new User(UniqIDFactory::create("author"), $name, $email, UserRole::AUTHOR, $password);
    }
}
