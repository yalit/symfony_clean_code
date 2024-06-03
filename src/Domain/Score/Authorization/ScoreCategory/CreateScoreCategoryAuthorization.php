<?php

namespace App\Domain\Score\Authorization\ScoreCategory;

use App\Domain\Score\Action\ScoreCategory\CreateScoreCategoryInput;
use App\Domain\Shared\Authorization\AuthorizationInterface;
use App\Domain\User\Model\Enum\UserRole;
use App\Domain\User\Repository\UserRepositoryInterface;

class CreateScoreCategoryAuthorization implements AuthorizationInterface
{
    public const AUTHORIZATION_ACTION = 'domain_create_score_category';

    public function __construct(private readonly UserRepositoryInterface $userRepository) {}

    /**
     * @inheritDoc
     */
    public function supports(string $action, $resource): bool
    {
        return $action === self::AUTHORIZATION_ACTION && $resource instanceof CreateScoreCategoryInput;
    }

    /**
     * @inheritDoc
     */
    public function allows(string $action, $resource): bool
    {
        $currentUser = $this->userRepository->getCurrentUser();

        if (!$currentUser) {
            return false;
        }

        return $currentUser->getRole() === UserRole::ADMIN;
    }
}
