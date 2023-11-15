<?php

namespace App\Domain\Model;

use App\Domain\Model\Enum\UserRole;

class User
{
    /**
     * has the following properties:
     * - id as int
     * - name as string
     * - email as string
     * - role as Enum AuthorRole
     */
    public function __construct(
        private readonly string $id,
        private string $name,
        private string $email,
        private UserRole $role
    ) {}

    public function getId():string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getRole():UserRole
    {
        return $this->role;
    }

    public function setRole(UserRole $role): void
    {
        $this->role = $role;
    }
}
