<?php

namespace App\Domain\Shared\Specification;

interface SpecificationVerifierInterface
{
    /**
     * @param class-string[] $specifications
     * @param mixed $object
     */
    public function satisfies(array $specifications, $object): bool;


    public function addSpecification(SpecificationInterface $specification): void;
}
