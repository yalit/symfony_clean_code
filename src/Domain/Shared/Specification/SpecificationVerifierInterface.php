<?php

namespace App\Domain\Shared\Specification;

interface SpecificationVerifierInterface
{
    /**
     * @param SpecificationInterface[] $specifications
     * @param mixed $object
     */
    public function satisfies(array $specifications, $object): bool;
}
