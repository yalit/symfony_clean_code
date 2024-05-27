<?php

namespace App\Infrastructure\Doctrine\Mapper\Score;

use App\Domain\Score\Model\ScoreCategory;
use App\Infrastructure\Doctrine\Mapper\DoctrineMapperInterface;
use App\Infrastructure\Doctrine\Model\Score\DoctrineScoreCategory;

class DoctrineScoreCategoryMappper implements DoctrineMapperInterface
{
    /**
     * @inheritDoc
     */
    public function supports(string $entityClassName): bool
    {
        return $entityClassName === ScoreCategory::class;
    }

    /**
     * @inheritDoc
     */
    public function fromDomainEntity($domainEntity, $targetEntity = null)
    {
        $targetEntity = $targetEntity ?? new DoctrineScoreCategory();
        $targetEntity->setId($domainEntity->getId());
        $targetEntity->setName($domainEntity->getName());
        $targetEntity->setType($domainEntity->getType());
        $targetEntity->setDescription($domainEntity->getDescription());

        return $targetEntity;
    }

    /**
     * @inheritDoc
     */
    public function toDomainEntity($entity)
    {
        return new ScoreCategory(
            $entity->getId(),
            $entity->getName(),
            $entity->getType(),
            $entity->getDescription(),
        );
    }

    /**
     * @inheritDoc
     */
    public function toDomainDto($entity, string $dtoClassName)
    {
        throw new \Exception('Not implemented');
    }
}
