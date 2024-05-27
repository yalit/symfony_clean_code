<?php

namespace App\Tests\Domain\Score\Action\ScoreCategory;

use App\Domain\Score\Action\ScoreCategory\CreateScoreCategoryAction;
use App\Domain\Score\Action\ScoreCategory\CreateScoreCategoryInput;
use App\Domain\Score\Authorization\CreateScoreCategoryAuthorization;
use App\Domain\Score\Model\Enum\ScoreCategoryType;
use App\Domain\Score\Repository\ScoreCategoryRepositoryInterface;
use App\Domain\Shared\Exception\InvalidRequester;
use App\Domain\Shared\Validation\Validator;
use App\Tests\Domain\Score\Repository\InMemoryTestScoreCategoryRepository;
use App\Tests\Domain\Shared\Action\DomainActionTestCase;
use App\Tests\Domain\User\Fixtures\DomainTestUserFixtures;

class CreateScoreCategoryActionTest extends DomainActionTestCase
{
    private ScoreCategoryRepositoryInterface $scoreCategoryRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->scoreCategoryRepository = new InMemoryTestScoreCategoryRepository();
        $this->authorizationChecker->addAuthorization(new CreateScoreCategoryAuthorization($this->userRepository));
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->scoreCategoryRepository);
    }

    /** @dataProvider createScoreCategoryDataProvider */
    public function testCreateScoreCategory(string $name, ScoreCategoryType $type, ?string $description = null): void
    {
        $input = new CreateScoreCategoryInput($name, $type, $description);
        $action = new CreateScoreCategoryAction(
            $this->scoreCategoryRepository,
            new Validator($this->serviceFetcher),
            $this->authorizationChecker,
        );

        $this->setCurrentUser();
        $action($input);

        $allCategories = $this->scoreCategoryRepository->getAll();
        self::assertCount(1, $allCategories);
        $category = array_values($allCategories)[0];
        self::assertNotNull($category->getId());
        self::assertEquals($name, $category->getName());
        self::assertEquals($type, $category->getType());
        self::assertEquals($description, $category->getDescription());
    }

    /**
     * @return array<string, array{name: string, type: ScoreCategoryType, description: string|null}>
     */
    public function createScoreCategoryDataProvider(): iterable
    {
        yield "simple" => ["name" => "test", "type" => ScoreCategoryType::SCORE, "description" => "simple description"];
        yield "null description" => ["name" => "test", "type" => ScoreCategoryType::SCORE, "description" => null];
        yield "identification category type" => ["name" => "test", "type" => ScoreCategoryType::IDENTIFICATION, "description" => "simple description"];
        yield "identification category type with null description" => ["name" => "test", "type" => ScoreCategoryType::IDENTIFICATION, "description" => null];
    }

    public function testCreateScoreCategoryWithInvalidUser(): void
    {
        $input = new CreateScoreCategoryInput("", ScoreCategoryType::SCORE, "description");
        $action = new CreateScoreCategoryAction(
            $this->scoreCategoryRepository,
            new Validator($this->serviceFetcher),
            $this->authorizationChecker,
        );

        $this->setCurrentUser(DomainTestUserFixtures::EDITOR_EMAIL);
        $this->expectException(InvalidRequester::class);
        $action($input);
    }
}
