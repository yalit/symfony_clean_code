<?php

namespace App\Infrastructure\Voter;

use App\Domain\Shared\Specification\SpecificationVerifierInterface;
use Symfony\Bundle\SecurityBundle\Security;

class VoterSpecificationVerifier implements SpecificationVerifierInterface
{
    public function __construct(private readonly Security $security)
    {
    }

    /**
     * @inheritDoc
     */
    public function satisfies(array $specifications, $object): bool
    {
        foreach ($specifications as $specification) {
            if (!$this->security->isGranted($specification::class, $object)) {
                return false;
            }
        }

        return true;
    }
}
