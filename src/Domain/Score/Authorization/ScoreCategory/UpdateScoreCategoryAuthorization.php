<?php

namespace App\Domain\Score\Authorization\ScoreCategory;

use App\Domain\Score\Action\ScoreCategory\UpdateScoreCategoryInput;
use App\Domain\Shared\Authorization\AbstractAdminAuthorization;
use App\Domain\User\Repository\UserRepositoryInterface;

class UpdateScoreCategoryAuthorization extends AbstractAdminAuthorization
{
    public const AUTHORIZATION_ACTION = 'domain_update_score_category';

    public function __construct(UserRepositoryInterface $userRepository)
    {
        parent::__construct($userRepository);
    }

    /**
     * @inheritDoc
     */
    public function supports(string $action, $resource): bool
    {
        return $action === self::AUTHORIZATION_ACTION && $resource instanceof UpdateScoreCategoryInput;
    }

    protected function allowsNonAdmin(string $action, $resource): bool
    {
        return false;
    }
}
