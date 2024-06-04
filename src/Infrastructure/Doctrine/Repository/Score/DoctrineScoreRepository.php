<?php

namespace App\Infrastructure\Doctrine\Repository\Score;

use App\Domain\Score\Model\Score;
use App\Domain\Score\Model\ScoreCategory;
use App\Domain\Score\Repository\ScoreRepositoryInterface;
use App\Infrastructure\Doctrine\Mapper\Score\DoctrineScoreMappper;
use App\Infrastructure\Doctrine\Model\Score\DoctrineScore;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DoctrineScore|null find($id, $lockMode = null, $lockVersion = null)
 * @method DoctrineScore|null findOneBy(array $criteria, array $orderBy = null)
 * @method DoctrineScore[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @template-extends ServiceEntityRepository<DoctrineScore>
 */
class DoctrineScoreRepository extends ServiceEntityRepository implements ScoreRepositoryInterface
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly DoctrineScoreMappper $mappper,
    ) {
        parent::__construct($registry, DoctrineScore::class);
    }

    /**
     * @return ?Score
     */
    public function getOneById(string $id): ?object
    {
        $find = $this->find($id);
        return $find ? $this->mappper->toDomainEntity($find) : null;
    }

    public function getAll(): array
    {
        return array_map(fn(DoctrineScore $doctrineScoreCategory) => $this->mappper->toDomainEntity($doctrineScoreCategory), $this->findAll());
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
