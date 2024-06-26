<?php

namespace App\Infrastructure\Doctrine\Mapper;

use App\Domain\User\Action\CreateUserInput;
use App\Domain\User\Action\DeleteUserInput;
use App\Domain\User\Action\EditUserInput;
use App\Domain\User\Model\User;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Infrastructure\Doctrine\Mapper\DoctrineMapperInterface;
use App\Infrastructure\Doctrine\Model\DoctrineUser;
use App\Infrastructure\Doctrine\Repository\DoctrineUserRepository;

class DoctrineUserMapper implements DoctrineMapperInterface
{
    public function supports(string $entityClassName): bool
    {
        return $entityClassName === User::class;
    }

    /**
     * @param DoctrineUser $entity
     * @return User
     */
    public function toDomainEntity($entity): object
    {
        return new User(
            $entity->getId(),
            $entity->getName(),
            $entity->getEmail(),
            $entity->getRole(),
            $entity->getPassword(),
        );
    }

    /**
     * @param User $domainEntity
     * @param ?DoctrineUser $targetEntity
     * @return DoctrineUser
     */
    public function fromDomainEntity($domainEntity, $targetEntity = null): object
    {

        $doctrineUser = $targetEntity ?? new DoctrineUser();

        $doctrineUser->setId($domainEntity->getId());
        $doctrineUser->setName($domainEntity->getName());
        $doctrineUser->setEmail($domainEntity->getEmail());
        $doctrineUser->setRole($domainEntity->getRole());
        $doctrineUser->setPassword($domainEntity->getPassword());

        return $doctrineUser;
    }

    /**
     * @param DoctrineUser $entity
     * @param class-string $dtoClassName
     */
    public function toDomainDto($entity, string $dtoClassName): object
    {
        return match ($dtoClassName) {
            CreateUserInput::class => $this->toCreateUserInput($entity),
            EditUserInput::class => $this->toEditUserInput($entity),
            DeleteUserInput::class => $this->toDeleteUserInput($entity),
            default => throw new \InvalidArgumentException("Unsupported DTO class: $dtoClassName"),
        };
    }

    private function toCreateUserInput(DoctrineUser $entity): CreateUserInput
    {
        return new CreateUserInput(
            $entity->getName(),
            $entity->getEmail(),
            $entity->getPlainPassword(),
            $entity->getRole(),
        );
    }

    private function toEditUserInput(DoctrineUser $entity): EditUserInput
    {
        return new EditUserInput(
            $entity->getId(),
            ['name' => $entity->getName(), 'email' => $entity->getEmail(), 'role' => $entity->getRole()],
            $entity->getPlainPassword(),
        );
    }

    private function toDeleteUserInput(DoctrineUser $entity): DeleteUserInput
    {
        return new DeleteUserInput($entity->getId());
    }
}
