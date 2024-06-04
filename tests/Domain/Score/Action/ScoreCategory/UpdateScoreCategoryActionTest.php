<?php

namespace App\Tests\Domain\Score\Action\ScoreCategory;

use App\Domain\Score\Action\ScoreCategory\UpdateScoreCategoryAction;
use App\Domain\Score\Action\ScoreCategory\UpdateScoreCategoryInput;
use App\Domain\Score\Authorization\ScoreCategory\UpdateScoreCategoryAuthorization;
use App\Domain\Score\Model\Enum\ScoreCategoryType;
use App\Domain\Score\Repository\ScoreCategoryRepositoryInterface;
use App\Domain\Shared\Exception\InvalidRequester;
use App\Domain\Shared\Rule\NotBlankPropertyValidator;
use App\Domain\Shared\Validation\Exception\ValidationException;
use App\Domain\Shared\Validation\Validator;
use App\Tests\Domain\Score\Fixtures\DomainTestCategoryFixtures;
use App\Tests\Domain\Score\Repository\InMemoryTestScoreCategoryRepository;
use App\Tests\Domain\Shared\Action\DomainActionTestCase;
use App\Tests\Domain\User\Fixtures\DomainTestUserFixtures;

class UpdateScoreCategoryActionTest extends DomainActionTestCase
{
    private ScoreCategoryRepositoryInterface $scoreCategoryRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->scoreCategoryRepository = new InMemoryTestScoreCategoryRepository();
        $this->authorizationChecker->addAuthorization(new UpdateScoreCategoryAuthorization($this->userRepository));

        $fixturesLoader = new DomainTestCategoryFixtures($this->scoreCategoryRepository);
        $fixturesLoader->load();

        $this->setCurrentUser();
        $this->serviceFetcher->addService(NotBlankPropertyValidator::class, new NotBlankPropertyValidator());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->scoreCategoryRepository);
    }

    /** @dataProvider updateScoreCategoryDataProvider */
    public function testUpdateScoreCategory(string $targetName, ?string $name = null, ?ScoreCategoryType $type = null, ?string $description = null): void
    {
        $category = $this->scoreCategoryRepository->getOneByName($targetName);
        self::assertNotNull($category);

        $input = new UpdateScoreCategoryInput($category, $name, $type, $description);
        $action = new UpdateScoreCategoryAction(
            $this->scoreCategoryRepository,
            new Validator($this->serviceFetcher),
            $this->authorizationChecker,
        );

        $action($input);

        $allCategories = $this->scoreCategoryRepository->getAll();
        self::assertCount(2, $allCategories);

        $updatedCategory = $this->scoreCategoryRepository->getOneById($category->getId());
        self::assertNotNull($updatedCategory);
        if ($name) {
            self::assertEquals($name, $updatedCategory->getName());
        } else {
            self::assertEquals($category->getName(), $updatedCategory->getName());
        }
        if ($type) {
            self::assertEquals($type, $updatedCategory->getType());
        } else {
            self::assertEquals($category->getType(), $updatedCategory->getType());
        }
        if ($description) {
            self::assertEquals($description, $updatedCategory->getDescription());
        } else {
            self::assertEquals($category->getDescription(), $updatedCategory->getDescription());
        }
    }

    /**
     * @return array<string, array{targetName: string, name: string|null, type: ScoreCategoryType|null, description: string|null}>
     */
    public function updateScoreCategoryDataProvider(): iterable
    {
        yield "full simple" => ["targetName" => DomainTestCategoryFixtures::CATEGORY_SCORE_NAME, "name" => "New Name", "type" => ScoreCategoryType::IDENTIFICATION, "description" => "simple description"];
        yield "full identification" => ["targetName" => DomainTestCategoryFixtures::CATEGORY_SCORE_IDENTIFICATION, "name" => "New Name", "type" => ScoreCategoryType::SCORE, "description" => "identification description"];
        yield "no description" => ["targetName" => DomainTestCategoryFixtures::CATEGORY_SCORE_NAME, "name" => "New Name", "type" => ScoreCategoryType::IDENTIFICATION, 'description' => null];
        yield "no type" => ["targetName" => DomainTestCategoryFixtures::CATEGORY_SCORE_NAME, "name" => "New Name", "type" => null, "description" => "simple description"];
        yield "no name" => ["targetName" => DomainTestCategoryFixtures::CATEGORY_SCORE_NAME, "name" => "New Name", "type" => ScoreCategoryType::IDENTIFICATION, "description" => "simple description"];
        yield "no name and type" => ["targetName" => DomainTestCategoryFixtures::CATEGORY_SCORE_NAME, "name" => "New Name", "type" => null, "description" => "simple description"];
        yield "no name and description" => ["targetName" => DomainTestCategoryFixtures::CATEGORY_SCORE_NAME, "name" => "New Name", "type" => ScoreCategoryType::SCORE, "description" => null];
        yield "no type and description" => ["targetName" => DomainTestCategoryFixtures::CATEGORY_SCORE_NAME, "name" => "New name", "type" => null, "description" => null];
        yield "no data" => ["targetName" => DomainTestCategoryFixtures::CATEGORY_SCORE_NAME, "name" => null, "type" => null, "description" => null];
    }

    public function testUpdateScoreCategoryWithInvalidUser(): void
    {
        $category = $this->scoreCategoryRepository->getOneByName(DomainTestCategoryFixtures::CATEGORY_SCORE_NAME);
        self::assertNotNull($category);
        $input = new UpdateScoreCategoryInput($category, "new name");
        $action = new UpdateScoreCategoryAction(
            $this->scoreCategoryRepository,
            new Validator($this->serviceFetcher),
            $this->authorizationChecker,
        );

        $this->setCurrentUser(DomainTestUserFixtures::EDITOR_EMAIL);
        $this->expectException(InvalidRequester::class);
        $action($input);
    }

    public function testUpdateScoreCategoryWithABlankName(): void
    {
        $category = $this->scoreCategoryRepository->getOneByName(DomainTestCategoryFixtures::CATEGORY_SCORE_NAME);
        self::assertNotNull($category);
        $input = new UpdateScoreCategoryInput($category, "");
        $action = new UpdateScoreCategoryAction(
            $this->scoreCategoryRepository,
            new Validator($this->serviceFetcher),
            $this->authorizationChecker,
        );

        $this->expectException(ValidationException::class);
        $action($input);
    }

}
