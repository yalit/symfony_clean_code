<?php

namespace App\Infrastructure\Voter;

use App\Domain\Shared\Specification\SpecificationInterface;
use App\Infrastructure\Voter\SpecificationManagerInterface;

class SpecificationManager implements SpecificationManagerInterface
{
    /** @var SpecificationInterface[] $specifications */
    private array $specifications = [];

    public function addSpecification(SpecificationInterface $specification): void
    {
        $this->specifications[$specification::class] = $specification;
    }

    public function getSpecification(string $specificationName): ?SpecificationInterface
    {
        if (!$this->hasSpecification($specificationName)) {
            return null;
        }

        return $this->specifications[$specificationName];
    }

    public function hasSpecification(string $specificationName): bool
    {
        return array_key_exists($specificationName, $this->specifications);
    }

    public function getSpecifications(): array
    {
        return $this->specifications;
    }
}
