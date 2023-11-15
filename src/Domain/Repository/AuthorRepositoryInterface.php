<?php

namespace App\Domain\Repository;

use App\Domain\Model\Author;

interface AuthorRepositoryInterface
{
    public function save(Author $author): void;

    public function getOneById(string $id): ?Author;

    /**
     * @return array<Author>
     */
    public function getAll(): array;

    public function delete(string $id): void;
}
