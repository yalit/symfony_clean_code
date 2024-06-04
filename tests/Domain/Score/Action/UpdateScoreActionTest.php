<?php

namespace App\Tests\Domain\Score\Action;

use App\Domain\Score\Action\CreateScoreAction;
use App\Domain\Score\Action\CreateScoreInput;
use App\Domain\Score\Action\UpdateScoreAction;
use App\Domain\Score\Action\UpdateScoreInput;
use App\Domain\Score\Authorization\CreateScoreAuthorization;
use App\Domain\Score\Authorization\UpdateScoreAuthorization;
use App\Domain\Score\Model\Composer;
use App\Domain\Score\Model\Enum\ScoreCategoryType;
use App\Domain\Score\Model\ScoreCategory;
use App\Domain\Score\Model\ScoreFile;
use App\Domain\Score\Model\ScoreIdentification;
use App\Domain\Score\Repository\ScoreRepositoryInterface;
use App\Domain\Score\Service\Factory\ComposerFactory;
use App\Domain\Score\Service\Factory\ScoreCategoryFactory;
use App\Domain\Score\Service\Factory\ScoreFileFactory;
use App\Domain\Score\Service\Factory\ScoreIdentificationFactory;
use App\Domain\Shared\Exception\InvalidRequester;
use App\Domain\Shared\Rule\NotBlankPropertyValidator;
use App\Domain\Shared\Validation\Exception\ValidationException;
use App\Domain\Shared\Validation\Validator;
use App\Tests\Domain\Score\Fixtures\DomainTestScoreFileFixtures;
use App\Tests\Domain\Score\Fixtures\DomainTestScoreFixtures;
use App\Tests\Domain\Score\Repository\InMemoryTestScoreRepository;
use App\Tests\Domain\Shared\Action\DomainActionTestCase;
use App\Tests\Domain\User\Fixtures\DomainTestUserFixtures;

class UpdateScoreActionTest extends DomainActionTestCase
{
    private ScoreRepositoryInterface $scoreRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->scoreRepository = new InMemoryTestScoreRepository();
        $this->authorizationChecker->addAuthorization(new UpdateScoreAuthorization($this->userRepository));
        $this->serviceFetcher->addService(NotBlankPropertyValidator::class, new NotBlankPropertyValidator());

        (new DomainTestScoreFixtures($this->scoreRepository))->load();
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
    public function testUpdateScore(
        ?string $title,
        ?string $description,
        array $categories = [],
        array $identifications = [],
        array $composers = [],
        array $files = []
    ): void
    {
        $score = array_values($this->scoreRepository->getAll())[0];
        self::assertNotNull($score);

        $originalTitle = $score->getTitle();
        $originalDescription = $score->getDescription();
        $originalCategories = $score->getCategories();
        $originalIdentifications = $score->getIdentifications();
        $originalComposers = $score->getComposers();
        $originalFiles = $score->getScoreFiles();
        $originalCreatedAt = $score->getCreatedAt();
        $originalUpdatedAt = $score->getUpdatedAt();

        $input = new UpdateScoreInput($score, $title, $description, $categories, $composers, $identifications, $files);
        $action = new UpdateScoreAction(
            $this->scoreRepository,
            new Validator($this->serviceFetcher),
            $this->authorizationChecker,
        );

        $this->setCurrentUser();
        $action($input);

        $updatedScore = $this->scoreRepository->getOneById($score->getId());
        self::assertNotNull($updatedScore);
        self::assertEquals($title ?? $originalTitle, $updatedScore->getTitle());
        self::assertEquals($description ?? $originalDescription, $updatedScore->getDescription());

        self::assertSame(count($categories) === 0 ? $originalCategories : $categories, $updatedScore->getCategories());
        self::assertSame(count($identifications) === 0 ? $originalIdentifications : $identifications, $updatedScore->getIdentifications());
        self::assertSame(count($composers) === 0 ? $originalComposers : $composers, $updatedScore->getComposers());
        self::assertSame(count($files) === 0 ? $originalFiles : $files, $updatedScore->getScoreFiles());

        self::assertSame($originalCreatedAt, $updatedScore->getCreatedAt());
        self::assertGreaterThan($originalUpdatedAt, $updatedScore->getUpdatedAt());
    }

    /**
     * @return array<string, array{title: string, description: ?string, categories: ScoreCategory[], identifications: ScoreIdentification[], composers: Composer[], files: ScoreFile[] }>
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

        yield 'null description' => [
            'title' => 'Score title',
            'description' => null,
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

        yield "with files" => [
            'title' => 'Score title',
            'description' => 'Score description',
            'categories' => [],
            'identifications' => [],
            'composers' => [],
            'files' => [ScoreFileFactory::create(DomainTestScoreFileFixtures::TEST_FILE_PATH)],
        ];
    }

    /** @dataProvider provideInvalidUser */
    public function testScoreCategoryWithInvalidUser(?string $email): void
    {
        $input = new CreateScoreInput("title", "description", [], [], [], []);
        $action = new CreateScoreAction(
            $this->scoreRepository,
            new Validator($this->serviceFetcher),
            $this->authorizationChecker,
        );

        $this->setCurrentUser($email);
        $this->expectException(InvalidRequester::class);
        $action($input);
    }

    /**
     * @return array<int, array<int, string>>
     */
    public function provideInvalidUser(): iterable
    {
        return [[DomainTestUserFixtures::AUTHOR_EMAIL], [null]];
    }
}
