<?php

namespace App\Domain\Repository;

use App\Domain\Model\Author;

interface AuthorRepositoryInterface
{
    public function save(Author $author): void;

    public function findById(string $id): ?Author;

    /**
     * @return array<Author>
     */
    public function findAll(): array;

    public function delete(string $id): void;
}
