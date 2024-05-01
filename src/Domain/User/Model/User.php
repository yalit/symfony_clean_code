<?php

namespace App\Domain\User\Model;

use App\Domain\User\Model\Enum\UserRole;

class User
{
    /**
     * has the following properties:
     * - id as string
     * - name as string
     * - email as string
     * - role as Enum UserRole
     * - password as string
     */
    public function __construct(
        private readonly string $id,
        private string $name,
        private string $email,
        private UserRole $role,
        private string $password,
    ) {}

    public function __toString(): string
    {
        return $this->name . "/" . $this->email;
    }

    public function getId(): string
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

    public function getRole(): UserRole
    {
        return $this->role;
    }

    public function setRole(UserRole $role): void
    {
        $this->role = $role;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}
