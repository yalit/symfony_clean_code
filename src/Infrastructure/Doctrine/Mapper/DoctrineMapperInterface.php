<?php

namespace App\Infrastructure\Doctrine\Mapper;

interface DoctrineMapperInterface
{
    /** @param class-string $entityClassName */
    public function supports(string $entityClassName): bool;

    /**
     * @param mixed $domainEntity
     * @param mixed|null $targetEntity
     * @return mixed
     */
    public function fromDomainEntity($domainEntity, $targetEntity = null) ;

    /**
     * @param mixed $entity
     * @return mixed
     */
    public function toDomainEntity($entity) ;

    /** */
    /**
     * @param mixed $entity
     * @param class-string $dtoClassName
     * @return mixed
     */
    public function toDomainDto($entity, string $dtoClassName) ;
}
