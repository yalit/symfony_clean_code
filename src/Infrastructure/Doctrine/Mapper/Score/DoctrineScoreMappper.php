<?php

namespace App\Infrastructure\Doctrine\Mapper\Score;

use App\Domain\Score\Model\Score;
use App\Infrastructure\Doctrine\Mapper\DoctrineMapperInterface;
use App\Infrastructure\Doctrine\Model\Score\DoctrineScore;

class DoctrineScoreMappper implements DoctrineMapperInterface
{

    public function supports(string $entityClassName): bool
    {
        return $entityClassName === DoctrineScore::class;
    }

    /**
     * @param Score $domainEntity
     * @param ?DoctrineScore $targetEntity
     * @return DoctrineScore
     */
    public function fromDomainEntity($domainEntity, $targetEntity = null)
    {
        // TODO: Implement fromDomainEntity() method.
    }

    /**
     * @param DoctrineScore $entity
     * @return Score
     */
    public function toDomainEntity($entity)
    {
        // TODO: Implement toDomainEntity() method.
    }

    /**
     * @param DoctrineScore $entity
     * @param class-string $dtoClassName
     */
    public function toDomainDto($entity, string $dtoClassName)
    {
        // TODO: Implement toDomainDto() method.
    }
}
