<?php

namespace App\Infrastructure\Doctrine\Validation;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_CLASS)]
class DomainSpecificationConstraint extends Constraint
{
    /**
     * @param class-string $dtoClassName
     * @param class-string $mapperToDtoClassName
     */
    public function __construct(
        private readonly string $dtoClassName,
        private readonly string $mapperToDtoClassName,
        mixed $options = null, ?array $groups = null, mixed $payload = null)
    {
        parent::__construct($options, $groups, $payload);
    }

    public function getDtoClassName(): string
    {
        return $this->dtoClassName;
    }

    public function getMapperToDtoClassName(): string
    {
        return $this->mapperToDtoClassName;
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
