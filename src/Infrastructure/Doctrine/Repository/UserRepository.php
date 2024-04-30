<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\User\Model\User;
use App\Domain\User\Repository\UserRepositoryInterface;
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

    public function getOneByEmail(string $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }

    /**
     * @return User[]
     */
    public function findAll(): array
    {
        return parent::findAll();
    }

    public function delete(string $id): void
    {
        $this->getEntityManager()->remove($this->getOneById($id));
        $this->getEntityManager()->flush();
    }
}
