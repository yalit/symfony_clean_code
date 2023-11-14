<?php

namespace App\Domain\Model\Factory;

use App\Domain\Model\Author;
use App\Domain\Model\Enum\AuthorRole;

final class AuthorFactory
{
    public static function createAdmin(string $name, string $email): Author
    {
        return new Author(UniqIDFactory::create("author"), $name, $email, AuthorRole::ADMIN);
    }

    public static function createEditor(string $name, string $email): Author
    {
        return new Author(UniqIDFactory::create("author"), $name, $email, AuthorRole::EDITOR);
    }

    public static function createAuthor(string $name, string $email): Author
    {
        return new Author(UniqIDFactory::create("author"), $name, $email, AuthorRole::AUTHOR);
    }
}
