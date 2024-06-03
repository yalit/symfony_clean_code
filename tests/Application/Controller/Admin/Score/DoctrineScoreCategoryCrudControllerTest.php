<?php

namespace App\Tests\Application\Controller\Admin\Score;

use App\Application\Controller\Admin\Score\DoctrineScoreCategoryCrudController;
use App\Application\Controller\Admin\User\DoctrineUserCrudController;
use App\Domain\Score\Model\Enum\ScoreCategoryType;
use App\Domain\Score\Repository\ScoreCategoryRepositoryInterface;
use App\Domain\User\Model\Enum\UserRole;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\Service\PasswordHasherInterface;
use App\Infrastructure\Doctrine\DataFixtures\Score\DoctrineScoreCategoryFixtures;
use App\Infrastructure\Doctrine\DataFixtures\User\DoctrineUserFixtures;
use App\Infrastructure\Doctrine\Model\Score\DoctrineScoreCategory;
use App\Infrastructure\Doctrine\Model\User\DoctrineUser;
use App\Tests\Application\Shared\Traits\WebSecurityTrait;
use App\Tests\Domain\Score\Fixtures\DomainTestCategoryFixtures;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use function PHPUnit\Framework\assertNotNull;

class DoctrineScoreCategoryCrudControllerTest extends WebTestCase
{
    use WebSecurityTrait;

    private AdminUrlGenerator $adminUrlGenerator;
    private KernelBrowser $client;
    private ScoreCategoryRepositoryInterface $scoreCategoryRepository;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->adminUrlGenerator = self::getContainer()->get(AdminUrlGenerator::class);
        $this->scoreCategoryRepository = self::getContainer()->get(ScoreCategoryRepositoryInterface::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->client, $this->adminUrlGenerator, $this->userRepository);
    }

    public function testDisplayCrudList(): void
    {
        $this->client->loginUser($this->createUser(DoctrineUserFixtures::ADMIN_NAME));
        $this->client->request('GET', $this->getCrudUrl(Action::INDEX));

        $this->assertResponseIsSuccessful();
    }

    public function testDisplayCrudCreate(): void
    {
        $this->client->loginUser($this->createUser(DoctrineUserFixtures::ADMIN_NAME));
        $this->client->request('GET', $this->getCrudUrl(Action::NEW));

        $this->assertResponseIsSuccessful();
    }

    /** @dataProvider createScoreCategoryDataProvider */
    public function testCrudCreateNewScoreCategory(string $name, ScoreCategoryType $type, ?string $description): void
    {
        $this->client->loginUser($this->createUser(DoctrineUserFixtures::ADMIN_NAME));
        $this->client->request('GET', $this->getCrudUrl(Action::NEW));

        $this->client->submitForm('Create', [
            'DoctrineScoreCategory[name]' => $name,
            'DoctrineScoreCategory[type]' => $type->value,
            'DoctrineScoreCategory[description]' => $description ?? '',
        ]);

        self::assertResponseRedirects();
        $this->client->followRedirect();
        self::assertResponseIsSuccessful();

        $scoreCategory = $this->scoreCategoryRepository->getOneByName($name);
        self::assertNotNull($scoreCategory);
        self::assertEquals($name, $scoreCategory->getName());
        self::assertEquals($type, $scoreCategory->getType());
        self::assertEquals($description ?? '', $scoreCategory->getDescription());
    }

    /**
     * @return array<string, array{string, ScoreCategoryType, string|null}>
     */
    public function createScoreCategoryDataProvider(): iterable
    {
        yield 'Score' => ['Score Category', ScoreCategoryType::SCORE, 'small description'];
        yield 'Identification' => ['Identification category', ScoreCategoryType::IDENTIFICATION, null];

    }

    public function testCrudEditExistingUser(): void
    {
        $scoreCategory = $this->scoreCategoryRepository->getOneByName(DoctrineScoreCategoryFixtures::SCORE_CATEGORY_NAME_1);
        self::assertNotNull($scoreCategory);

        $this->client->loginUser($this->createUser(DoctrineUserFixtures::ADMIN_NAME));
        $this->client->request('GET', $this->getCrudUrl(Action::EDIT, $scoreCategory->getId()));

        self::assertResponseIsSuccessful();

        $newName = 'new Name';
        $newDescription = 'a New description';
        $newType = ScoreCategoryType::IDENTIFICATION;

        $this->client->submitForm('Save changes', [
            'DoctrineScoreCategory[name]' => $newName,
            'DoctrineScoreCategory[type]' => $newType->value,
            'DoctrineScoreCategory[description]' => $newDescription,
        ]);

        self::assertResponseRedirects();
        $this->client->followRedirect();
        self::assertResponseIsSuccessful();

        $updatedScoreCategory = $this->scoreCategoryRepository->getOneById($scoreCategory->getId());
        self::assertNotNull($updatedScoreCategory);
        self::assertEquals($newName, $updatedScoreCategory->getName());
        self::assertEquals($newType, $updatedScoreCategory->getType());
        self::assertEquals($newDescription, $updatedScoreCategory->getDescription());

        $updatedDoctrineScoreCategory = self::getContainer()->get(EntityManagerInterface::class)->getRepository(DoctrineScoreCategory::class)->find($updatedScoreCategory->getId());
        self::assertNotNull($updatedDoctrineScoreCategory);
        self::assertEquals($updatedScoreCategory->getName(), $updatedDoctrineScoreCategory->getName());
        self::assertEquals($updatedScoreCategory->getType(), $updatedDoctrineScoreCategory->getType());
        self::assertEquals($updatedScoreCategory->getDescription(), $updatedDoctrineScoreCategory->getDescription());
    }

    private function getCrudUrl(string $crudAction, string $entityId = null): string
    {
        $this->adminUrlGenerator->unsetAll();

        $generator = $this->adminUrlGenerator->setController(DoctrineScoreCategoryCrudController::class)->setAction($crudAction);

        if ($entityId !== null) {
            $generator->setEntityId($entityId);
        }

        return $generator->generateUrl();
    }

}
