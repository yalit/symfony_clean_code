<?php

namespace App\Domain\Shared\Specification;

interface SpecificationInterface
{
    /**
     * @param mixed $object
     */
    public function isSatisfiedBy($object): bool;
}
