<?php

namespace App\Domain\Repository;

use App\Domain\Model\User;

interface UserRepositoryInterface
{
    public function save(User $author): void;

    public function getOneById(string $id): ?User;

    /**
     * @return array<User>
     */
    public function getAll(): array;

    public function delete(string $id): void;
}
