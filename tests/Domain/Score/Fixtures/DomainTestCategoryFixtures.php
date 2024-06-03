<?php

namespace App\Tests\Domain\Score\Fixtures;

use App\Domain\Score\Model\Enum\ScoreCategoryType;
use App\Domain\Score\Repository\ScoreCategoryRepositoryInterface;
use App\Domain\Score\Service\Factory\ScoreCategoryFactory;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\Service\Factory\UserFactory;

class DomainTestCategoryFixtures
{
    public const CATEGORY_SCORE_NAME = 'Score cat';
    public const CATEGORY_SCORE_IDENTIFICATION = 'Identification cat';

    public function __construct(
        private readonly ScoreCategoryRepositoryInterface $categoryRepository,
    ) {}

    public function load(): void
    {
        $category = ScoreCategoryFactory::create(self::CATEGORY_SCORE_NAME, ScoreCategoryType::SCORE);
        $this->categoryRepository->save($category);

        $category = ScoreCategoryFactory::create(self::CATEGORY_SCORE_IDENTIFICATION, ScoreCategoryType::IDENTIFICATION);
        $this->categoryRepository->save($category);
    }
}
