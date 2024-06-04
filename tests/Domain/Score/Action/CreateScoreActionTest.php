<?php

namespace App\Tests\Domain\Score\Action;

use App\Domain\Score\Action\CreateScoreAction;
use App\Domain\Score\Action\CreateScoreInput;
use App\Domain\Score\Action\ScoreCategory\CreateScoreCategoryAction;
use App\Domain\Score\Action\ScoreCategory\CreateScoreCategoryInput;
use App\Domain\Score\Authorization\CreateScoreAuthorization;
use App\Domain\Score\Authorization\ScoreCategory\CreateScoreCategoryAuthorization;
use App\Domain\Score\Model\Composer;
use App\Domain\Score\Model\Enum\ScoreCategoryType;
use App\Domain\Score\Model\ScoreCategory;
use App\Domain\Score\Model\ScoreFile;
use App\Domain\Score\Model\ScoreIdentification;
use App\Domain\Score\Repository\ScoreCategoryRepositoryInterface;
use App\Domain\Score\Repository\ScoreRepositoryInterface;
use App\Domain\Score\Service\Factory\ComposerFactory;
use App\Domain\Score\Service\Factory\ScoreCategoryFactory;
use App\Domain\Score\Service\Factory\ScoreFileFactory;
use App\Domain\Score\Service\Factory\ScoreIdentificationFactory;
use App\Domain\Shared\Exception\InvalidRequester;
use App\Domain\Shared\Validation\Exception\ValidationException;
use App\Domain\Shared\Validation\Validator;
use App\Tests\Domain\Score\Repository\InMemoryTestScoreCategoryRepository;
use App\Tests\Domain\Score\Repository\InMemoryTestScoreRepository;
use App\Tests\Domain\Shared\Action\DomainActionTestCase;
use App\Tests\Domain\User\Fixtures\DomainTestUserFixtures;

class CreateScoreActionTest extends DomainActionTestCase
{
    private ScoreRepositoryInterface $scoreRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->scoreRepository = new InMemoryTestScoreRepository();
        $this->authorizationChecker->addAuthorization(new CreateScoreAuthorization($this->userRepository));
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->scoreRepository);
    }

    /**
     * @dataProvider createScoreDataProvider
     * @param ScoreCategory[] $categories
     * @param ScoreIdentification[] $identifications
     * @param Composer[] $composers
     * @param ScoreFile[] $files
     * @throws InvalidRequester
     * @throws ValidationException
     */
    public function testCreateScore(
        string $title,
        string $description,
        array $categories,
        array $identifications,
        array $composers,
        array $files
    ): void
    {
        $input = new CreateScoreInput($title, $description, $categories, $composers, $identifications, $files);
        $action = new CreateScoreAction(
            $this->scoreRepository,
            new Validator($this->serviceFetcher),
            $this->authorizationChecker,
        );

        $this->setCurrentUser();
        $action($input);

        $allScores = $this->scoreRepository->getAll();
        self::assertCount(1, $allScores);
        $score = array_values($allScores)[0];
        self::assertNotNull($score->getId());
        self::assertEquals($title, $score->getTitle());
        self::assertEquals($description, $score->getDescription());

        self::assertSame($categories, $score->getCategories());
        self::assertSame($identifications, $score->getIdentifications());
        self::assertSame($composers, $score->getComposers());
        self::assertSame($files, $score->getScoreFiles());
    }

    /**
     * @return array<string, array{title: string, description: string, categories: ScoreCategory[], identifications: ScoreIdentification[], composers: Composer[], files: ScoreFile[] }>
     */
    public function createScoreDataProvider(): iterable
    {
        yield "only title and description" => [
            'title' => 'Score title',
            'description' => 'Score description',
            'categories' => [],
            'identifications' => [],
            'composers' => [],
            'files' => [],
        ];

        yield "with categories" => [
            'title' => 'Score title',
            'description' => 'Score description',
            'categories' => [
                ScoreCategoryFactory::create("Category 1", ScoreCategoryType::SCORE, "Category 1 description"),
                ScoreCategoryFactory::create("Category 2", ScoreCategoryType::SCORE, "Category 2 description"),
            ],
            'identifications' => [],
            'composers' => [],
            'files' => [],
        ];

        yield "with identifications" => [
            'title' => 'Score title',
            'description' => 'Score description',
            'categories' => [],
            'identifications' => [
                ScoreIdentificationFactory::create("Identification 1", ScoreCategoryFactory::create("category Id 1", ScoreCategoryType::IDENTIFICATION)),
                ScoreIdentificationFactory::create("Identification 2")
            ],
            'composers' => [],
            'files' => [],
        ];

        yield "with composers" => [
            'title' => 'Score title',
            'description' => 'Score description',
            'categories' => [],
            'identifications' => [],
            'composers' => [
                ComposerFactory::create("Composer 1"),
                ComposerFactory::create("Composer 2"),
            ],
            'files' => [],
        ];

    }

    public function testCreateScoreCategoryWithInvalidUser(): void
    {
        $input = new CreateScoreInput("title", "description", [], [], [], []);
        $action = new CreateScoreAction(
            $this->scoreRepository,
            new Validator($this->serviceFetcher),
            $this->authorizationChecker,
        );

        $this->setCurrentUser(null);
        $this->expectException(InvalidRequester::class);
        $action($input);
    }
}
