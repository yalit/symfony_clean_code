<?php

namespace App\Infrastructure\Voter;

use App\Domain\Shared\Specification\SpecificationInterface;
use App\Infrastructure\Voter\SpecificationManagerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * @template-extends Voter<string, SpecificationInterface>
 */
class SpecificationVoter extends Voter
{
    public function __construct(private readonly SpecificationManagerInterface $specificationManager) {}

    /**
     * @inheritDoc
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        $specification = $this->specificationManager->getSpecification($attribute);
        if ($specification === null) {
            return false;
        }

        return $specification instanceof SpecificationInterface;
    }

    /**
     * @inheritDoc
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var SpecificationInterface $specification */
        $specification = $this->specificationManager->getSpecification($attribute);

        return $specification->isSatisfiedBy($subject);
    }
}
