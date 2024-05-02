<?php

namespace App\Tests\Domain\Shared\Specification;

use App\Domain\Shared\Specification\SpecificationInterface;
use App\Domain\Shared\Specification\SpecificationVerifierInterface;

class TestSpecificationVerifier implements SpecificationVerifierInterface
{
    public function satisfies(array $specifications, $object): bool
    {
        foreach ($specifications as $specification) {
            if (!$specification->isSatisfiedBy($object)) {
                return false;
            }
        }

        return true;
    }
}
