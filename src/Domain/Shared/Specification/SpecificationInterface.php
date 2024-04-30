<?php

namespace App\Domain\Shared\Specification;

interface SpecificationInterface
{
    public function isSatisfiedBy($object): bool;
}
