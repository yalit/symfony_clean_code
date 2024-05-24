<?php

namespace App\Tests\Domain\User\Repository;

use App\Domain\User\Model\User;
use App\Domain\User\Repository\UserRepositoryInterface;

class InMemoryTestUserRepository implements UserRepositoryInterface
{
    /**
     * @var User[]
     */
    private array $users = [];

    private ?User $currentUser = null;

    public function save(object $entity): void
    {
        $this->users[$entity->getId()] = $entity;
    }

    public function getOneById(string $id): ?User
    {
        return $this->users[$id] ?? null;
    }

    public function getOneByEmail(string $email): ?User
    {
        foreach ($this->users as $user) {
            if ($user->getEmail() === $email) {
                return $user;
            }
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function getAll(): array
    {
        return $this->users;
    }

    public function delete(string $id): void
    {
        unset($this->users[$id]);
    }

    public function setCurrentUser(?User $user): void
    {
        $this->currentUser = $user;
    }

    public function getCurrentUser(): ?User
    {
        return $this->currentUser;
    }
}
