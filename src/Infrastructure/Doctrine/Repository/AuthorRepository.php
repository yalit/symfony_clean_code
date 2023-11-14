<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Model\Author;
use App\Domain\Repository\AuthorRepositoryInterface;

class AuthorRepository implements AuthorRepositoryInterface
{

    public function save(Author $author): void
    {
        // TODO: Implement save() method.
    }

    public function findById(string $id): ?Author
    {
        // TODO: Implement findById() method.
    }

    /**
     * @inheritDoc
     */
    public function findAll(): array
    {
        // TODO: Implement findAll() method.
    }

    public function delete(string $id): void
    {
        // TODO: Implement delete() method.
    }
}
