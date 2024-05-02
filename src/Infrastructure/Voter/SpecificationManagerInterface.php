<?php

namespace App\Infrastructure\Voter;

use App\Domain\Shared\Specification\SpecificationInterface;

interface SpecificationManagerInterface
{
    public function addSpecification(SpecificationInterface $specification): void;

    public function getSpecification(string $specificationName): ?SpecificationInterface;

    public function hasSpecification(string $specificationName): bool;

    /**
     * @return array<SpecificationInterface>
     */
    public function getSpecifications(): array;
}
