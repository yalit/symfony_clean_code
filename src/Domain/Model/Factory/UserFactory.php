<?php

namespace App\Domain\Model\Factory;

use App\Domain\Model\User;
use App\Domain\Model\Enum\UserRole;

final class UserFactory
{
    public static function createAdmin(string $name, string $email): User
    {
        return new User(UniqIDFactory::create("author"), $name, $email, UserRole::ADMIN);
    }

    public static function createEditor(string $name, string $email): User
    {
        return new User(UniqIDFactory::create("author"), $name, $email, UserRole::EDITOR);
    }

    public static function createAuthor(string $name, string $email): User
    {
        return new User(UniqIDFactory::create("author"), $name, $email, UserRole::AUTHOR);
    }
}
