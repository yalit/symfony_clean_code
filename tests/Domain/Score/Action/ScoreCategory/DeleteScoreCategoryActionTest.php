<?php

namespace App\Tests\Domain\Score\Action\ScoreCategory;

use App\Domain\Score\Action\ScoreCategory\DeleteScoreCategoryAction;
use App\Domain\Score\Action\ScoreCategory\DeleteScoreCategoryInput;
use App\Domain\Score\Action\ScoreCategory\UpdateScoreCategoryAction;
use App\Domain\Score\Action\ScoreCategory\UpdateScoreCategoryInput;
use App\Domain\Score\Authorization\ScoreCategory\DeleteScoreCategoryAuthorization;
use App\Domain\Score\Authorization\ScoreCategory\UpdateScoreCategoryAuthorization;
use App\Domain\Score\Model\Enum\ScoreCategoryType;
use App\Domain\Score\Repository\ScoreCategoryRepositoryInterface;
use App\Domain\Score\Rule\NotBlankNameValidator;
use App\Domain\Shared\Exception\InvalidRequester;
use App\Domain\Shared\Validation\Exception\ValidationException;
use App\Domain\Shared\Validation\Validator;
use App\Tests\Domain\Score\Fixtures\DomainTestCategoryFixtures;
use App\Tests\Domain\Score\Repository\InMemoryTestScoreCategoryRepository;
use App\Tests\Domain\Shared\Action\DomainActionTestCase;
use App\Tests\Domain\User\Fixtures\DomainTestUserFixtures;

class DeleteScoreCategoryActionTest extends DomainActionTestCase
{
    private ScoreCategoryRepositoryInterface $scoreCategoryRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->scoreCategoryRepository = new InMemoryTestScoreCategoryRepository();
        $this->authorizationChecker->addAuthorization(new DeleteScoreCategoryAuthorization($this->userRepository));

        (new DomainTestCategoryFixtures($this->scoreCategoryRepository))->load();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->scoreCategoryRepository);
    }

    /** @dataProvider deleteScoreCategoryDataProvider */
    public function testDeleteScoreCategory(string $targetName): void
    {
        $category = $this->scoreCategoryRepository->getOneByName($targetName);
        self::assertNotNull($category);

        $input = new DeleteScoreCategoryInput($category);
        $action = new DeleteScoreCategoryAction(
            $this->scoreCategoryRepository,
            new Validator($this->serviceFetcher),
            $this->authorizationChecker,
        );

        $this->setCurrentUser();
        $action($input);

        $allCategories = $this->scoreCategoryRepository->getAll();
        self::assertCount(1, $allCategories);

        self::assertNull($this->scoreCategoryRepository->getOneByName($targetName));
    }

    /**
     * @return array<string, array{targetName: string}>
     */
    public function deleteScoreCategoryDataProvider(): iterable
    {
        yield "simple" => ["targetName" => DomainTestCategoryFixtures::CATEGORY_SCORE_NAME];
        yield "identification" => ["targetName" => DomainTestCategoryFixtures::CATEGORY_SCORE_IDENTIFICATION];
    }

    public function testUpdateScoreCategoryWithInvalidUser(): void
    {
        $category = $this->scoreCategoryRepository->getOneByName(DomainTestCategoryFixtures::CATEGORY_SCORE_NAME);
        self::assertNotNull($category);
        $input = new DeleteScoreCategoryInput($category);
        $action = new DeleteScoreCategoryAction(
            $this->scoreCategoryRepository,
            new Validator($this->serviceFetcher),
            $this->authorizationChecker,
        );

        $this->setCurrentUser(DomainTestUserFixtures::EDITOR_EMAIL);
        $this->expectException(InvalidRequester::class);
        $action($input);
    }
}
