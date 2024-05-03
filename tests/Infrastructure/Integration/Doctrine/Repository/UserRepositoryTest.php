<?php

namespace App\Tests\Infrastructure\Integration\Doctrine\Repository;

use App\Domain\User\Model\User;
use App\Domain\User\Service\Factory\UserFactory;
use App\Infrastructure\Doctrine\Repository\DoctrineUserRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;

class UserRepositoryTest extends RepositoryKernelTestCase
{
    /**
     * @var DoctrineUserRepository $repository
     */
    protected ServiceEntityRepositoryInterface $repository;
    protected string $entityClass = User::class;

    public function testFindOneById(): void
    {
        $allExistingUsers = $this->repository->findAll();
        self::assertNotCount(0, $allExistingUsers);

        /** @var User $author */
        $author = $allExistingUsers[0];
        $authorId = $author->getId();
        $authorName = $author->getName();
        $authorEmail = $author->getEmail();

        $foundUser = $this->repository->findOneById($authorId);
        self::assertNotNull($foundUser);
        self::assertEquals($authorId, $foundUser->getId());
        self::assertEquals($authorName, $foundUser->getName());
        self::assertEquals($authorEmail, $foundUser->getEmail());
    }

    public function testGetOneByIdForUnknownIdReturnsNull(): void
    {
        $unknownId = 'unknown_id';
        $foundUser = $this->repository->findOneById($unknownId);
        self::assertNull($foundUser);
    }

    public function testSave(): void
    {
        $allExistingUsers = $this->repository->findAll();
        self::assertNotCount(0, $allExistingUsers);

        $authorName = 'User 1';
        $authorEmail = 'author1_test@email.com';
        $author = self::getContainer()->get(UserFactory::class)->createAuthor($authorName, $authorEmail, 'Password123)');
        $this->repository->save($author);

        $this->assertNotNull($author->getId());

        $allNewExistingUsers = $this->repository->findAll();
        self::assertCount(count($allExistingUsers) + 1, $allNewExistingUsers);

        /** @var User $newUser */
        $newUser = $allNewExistingUsers[count($allNewExistingUsers) - 1];
        self::assertEquals($author->getId(), $newUser->getId());
        self::assertEquals($authorName, $newUser->getName());
        self::assertEquals($authorEmail, $newUser->getEmail());
    }

    public function testDelete(): void
    {
        $allExistingUsers = $this->repository->findAll();
        self::assertNotCount(0, $allExistingUsers);

        /** @var User $author */
        $author = $allExistingUsers[0];
        $authorId = $author->getId();

        $this->repository->delete($author->getId());

        $allNewExistingUsers = $this->repository->findAll();
        self::assertCount(count($allExistingUsers) - 1, $allNewExistingUsers);

        $foundUser = $this->repository->findOneById($authorId);
        self::assertNull($foundUser);
    }
}
