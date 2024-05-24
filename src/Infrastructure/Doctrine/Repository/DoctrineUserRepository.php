<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\User\Model\User;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Infrastructure\Doctrine\Mapper\DoctrineUserMapper;
use App\Infrastructure\Doctrine\Model\DoctrineUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @method DoctrineUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method DoctrineUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method DoctrineUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @template-extends ServiceEntityRepository<DoctrineUser>
 */
class DoctrineUserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly Security $security,
        private readonly DoctrineUserMapper $doctrineUserMapper,
    ) {
        parent::__construct($registry, DoctrineUser::class);
    }

    /**
     * @param User $entity
     */
    public function save(object $entity): void
    {
        $this->getEntityManager()->persist($this->doctrineUserMapper->fromDomainEntity($entity, $this->find($entity->getId())));
        $this->getEntityManager()->flush();
    }

    public function getOneById(string $id): ?User
    {
        return  $this->getDomainUser($this->find($id));
    }

    public function getOneByEmail(string $email): ?User
    {
        return $this->getDomainUser($this->findOneBy(['email' => $email]));
    }

    /**
     * @return User[]
     */
    public function getAll(): array
    {
        return array_map(fn(?DoctrineUser $doctrineUser) => $this->getDomainUser($doctrineUser), $this->findAll());
    }

    public function delete(string $id): void
    {
        $this->getEntityManager()->remove($this->find($id));
        $this->getEntityManager()->flush();
    }

    public function getCurrentUser(): ?User
    {
        /** @var ?User $currentUser */
        $currentUser = $this->security->getUser();
        return $currentUser;
    }

    private function getDomainUser(?DoctrineUser $doctrineUser): ?User
    {
        if ($doctrineUser === null) {
            return null;
        }

        return $this->doctrineUserMapper->toDomainEntity($doctrineUser);
    }
}
