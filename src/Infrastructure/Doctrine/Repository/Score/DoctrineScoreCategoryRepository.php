<?php

namespace App\Infrastructure\Doctrine\Repository\Score;

use App\Domain\Score\Model\ScoreCategory;
use App\Domain\Score\Repository\ScoreCategoryRepositoryInterface;
use App\Infrastructure\Doctrine\Mapper\Score\DoctrineScoreCategoryMappper;
use App\Infrastructure\Doctrine\Model\Score\DoctrineScoreCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DoctrineScoreCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method DoctrineScoreCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method DoctrineScoreCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @template-extends ServiceEntityRepository<DoctrineScoreCategory>
 */
class DoctrineScoreCategoryRepository extends ServiceEntityRepository implements ScoreCategoryRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly DoctrineScoreCategoryMappper $mappper,
    ) {
        parent::__construct($registry, DoctrineScoreCategory::class);
    }

    /**
     * @return ?ScoreCategory
     */
    public function getOneById(string $id): ?object
    {
        $find = $this->find($id);
        return $find ? $this->mappper->toDomainEntity($find) : null;
    }

    public function getAll(): array
    {
        return array_map(fn(DoctrineScoreCategory $doctrineScoreCategory) => $this->mappper->toDomainEntity($doctrineScoreCategory), $this->findAll());
    }

    public function save(object $entity): void
    {
        $this->getEntityManager()->persist($this->mappper->fromDomainEntity($entity, $this->find($entity->getId())));
        $this->getEntityManager()->flush();
    }

    public function delete(string $id): void
    {
        $found = $this->find($id);
        if (!$found) {
            return;
        }
        $this->getEntityManager()->remove($found);
        $this->getEntityManager()->flush();
    }
}
