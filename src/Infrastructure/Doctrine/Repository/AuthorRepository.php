<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Model\Author;
use App\Domain\Repository\AuthorRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AuthorRepository extends ServiceEntityRepository implements AuthorRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    public function save(Author $author): void
    {
        $this->getEntityManager()->persist($author);
        $this->getEntityManager()->flush();
    }

    public function getOneById(string $id): ?Author
    {
        $this->find($id);
    }

    /**
     * @return Author[]
     */
    public function getAll(): array
    {
        return $this->findAll();
    }

    public function delete(string $id): void
    {
        $this->getEntityManager()->remove($this->getOneById($id));
    }
}
