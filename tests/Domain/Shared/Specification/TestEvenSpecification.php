<?php

namespace App\Tests\Domain\Shared\Specification;

use App\Domain\Shared\Specification\SpecificationInterface;

class TestEvenSpecification implements SpecificationInterface
{

    public function isSatisfiedBy($object): bool
    {
        if (!is_int($object)) {
            return false;
        }

        return $object % 2 === 0;
    }
}
