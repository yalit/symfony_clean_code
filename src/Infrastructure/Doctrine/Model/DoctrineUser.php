<?php

namespace App\Infrastructure\Doctrine\Model;

use App\Domain\User\Model\Enum\UserRole;
use App\Infrastructure\Doctrine\Generator\DoctrineUserIdGenerator;
use App\Infrastructure\Doctrine\Repository\DoctrineUserRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: DoctrineUserRepository::class)]
#[Table(name: 'app_user')]
class DoctrineUser
{
    #[Id]
    #[Column(type: 'string', length: 128)]
    #[GeneratedValue(strategy: 'CUSTOM')]
    #[CustomIdGenerator(class: DoctrineUserIdGenerator::class)]
    private string $id;

    #[Column(type: 'string', length: 128)]
    private string $name;

    #[Column(type: 'string', length: 128)]
    private string $email;

    #[Column(type: 'string', length: 128, enumType: UserRole::class)]
    private UserRole $role;

    #[Column(type: 'string', length: 128)]
    private ?string $password = null;

    private ?string $plainPassword = null;

    public function setId(string $id): void
    {
        $this->id = $id;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }
}
