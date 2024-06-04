<?php

namespace App\Domain\Score\Authorization\ScoreCategory;

use App\Domain\Score\Action\ScoreCategory\CreateScoreCategoryInput;
use App\Domain\Shared\Authorization\AbstractAdminAuthorization;
use App\Domain\User\Repository\UserRepositoryInterface;

class CreateScoreCategoryAuthorization extends AbstractAdminAuthorization
{
    public const AUTHORIZATION_ACTION = 'domain_create_score_category';

    public function __construct(UserRepositoryInterface $userRepository)
    {
        parent::__construct($userRepository);
    }

    /**
     * @inheritDoc
     */
    public function supports(string $action, $resource): bool
    {
        return $action === self::AUTHORIZATION_ACTION && $resource instanceof CreateScoreCategoryInput;
    }

    protected function allowsNonAdmin(string $action, $resource): bool
    {
        return false;
    }
}
