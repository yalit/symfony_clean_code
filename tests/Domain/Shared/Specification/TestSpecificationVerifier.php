<?php

namespace App\Tests\Domain\Shared\Specification;

use App\Domain\Shared\Specification\SpecificationInterface;
use App\Domain\Shared\Specification\SpecificationVerifierInterface;

class TestSpecificationVerifier implements SpecificationVerifierInterface
{
    /**
     * @var SpecificationInterface[] $specifications
     */
    private array $specifications = [];

    /**
     * @inheritDoc
     */
    public function satisfies(array $specifications, $object): bool
    {
        foreach ($specifications as $specification) {
            if (!array_key_exists($specification, $this->specifications)) {
                return false;
            }
            if (!$this->specifications[$specification]->isSatisfiedBy($object)) {
                return false;
            }
        }

        return true;
    }

    public function addSpecification(SpecificationInterface $specification): void
    {
        $this->specifications[$specification::class] = $specification;
    }
}
