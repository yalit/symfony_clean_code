<?php

namespace App\Tests\Shared\Specification;

use App\Domain\Shared\Specification\SpecificationInterface;

class NotExistingSpecification implements SpecificationInterface
{
    public function isSatisfiedBy($object): bool
    {
        return false;
    }
}
