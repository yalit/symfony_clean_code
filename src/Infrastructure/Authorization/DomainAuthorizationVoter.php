<?php

namespace App\Infrastructure\Authorization;

use App\Domain\Shared\Authorization\AuthorizationInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/** @template-extends Voter<string, mixed> */
class DomainAuthorizationVoter extends Voter
{
    /**
     * @var AuthorizationInterface[] $authorizations
     */
    private array $authorizations = [];

    protected function supports(string $attribute, mixed $subject): bool
    {
        foreach ($this->authorizations as $authorization) {
            if ($authorization->supports($attribute, $subject)) {
                return true;
            }
        }

        return false;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        foreach ($this->authorizations as $authorization) {
            if ($authorization->supports($attribute, $subject)) {
                return $authorization->allows($attribute, $subject);
            }
        }

        return false;
    }

    public function addDomainAuthorization(AuthorizationInterface $authorization): void
    {
        $this->authorizations[$authorization::class] = $authorization;
    }
}
