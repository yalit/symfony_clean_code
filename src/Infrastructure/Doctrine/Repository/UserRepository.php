<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Model\User;
use App\Domain\Repository\UserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $user): void
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function getOneById(string $id): ?User
    {
        return $this->find($id);
    }

    /**
     * @return User[]
     */
    public function getAll(): array
    {
        return $this->findAll();
    }

    public function delete(string $id): void
    {
        $this->getEntityManager()->remove($this->getOneById($id));
        $this->getEntityManager()->flush();
    }
}
