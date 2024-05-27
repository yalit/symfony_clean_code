<?php

namespace App\Tests\Infrastructure\Integration\Doctrine\Repository;

use App\Domain\User\Model\User;
use App\Domain\User\Service\Factory\UserFactory;
use App\Infrastructure\Doctrine\Model\User\DoctrineUser;
use App\Infrastructure\Doctrine\Repository\User\DoctrineUserRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;

class DoctrineUserRepositoryTest extends RepositoryKernelTestCase
{
    /**
     * @var DoctrineUserRepository $repository
     */
    protected ServiceEntityRepositoryInterface $repository;
    protected string $entityClass = DoctrineUser::class;

    public function testFindOneById(): void
    {
        $allExistingUsers = $this->repository->getAll();
        self::assertNotCount(0, $allExistingUsers);

        /** @var User $author */
        $author = $allExistingUsers[0];
        $authorId = $author->getId();
        $authorName = $author->getName();
        $authorEmail = $author->getEmail();

        $foundUser = $this->repository->getOneById($authorId);
        self::assertNotNull($foundUser);
        self::assertEquals($authorId, $foundUser->getId());
        self::assertEquals($authorName, $foundUser->getName());
        self::assertEquals($authorEmail, $foundUser->getEmail());
    }

    public function testGetOneByIdForUnknownIdReturnsNull(): void
    {
        $unknownId = 'unknown_id';
        $foundUser = $this->repository->getOneById($unknownId);
        self::assertNull($foundUser);
    }

    public function testSave(): void
    {
        $allExistingUsers = $this->repository->getAll();
        self::assertNotCount(0, $allExistingUsers);

        $authorName = 'User 1';
        $authorEmail = 'author1_test@email.com';
        $author = self::getContainer()->get(UserFactory::class)->createAuthor($authorName, $authorEmail, 'Password123)');
        $this->repository->save($author);

        $this->assertNotNull($author->getId());

        $allNewExistingUsers = $this->repository->getAll();
        self::assertCount(count($allExistingUsers) + 1, $allNewExistingUsers);

        $newUsers = array_filter($allNewExistingUsers, function (User $user) use ($author) {
            return $user->getId() === $author->getId();
        });
        /** @var User $newUser */
        $newUser = array_pop($newUsers);
        self::assertEquals($authorName, $newUser->getName());
        self::assertEquals($authorEmail, $newUser->getEmail());
    }

    public function testDelete(): void
    {
        $allExistingUsers = $this->repository->getAll();
        self::assertNotCount(0, $allExistingUsers);

        /** @var User $author */
        $author = $allExistingUsers[0];
        $authorId = $author->getId();

        $this->repository->delete($author->getId());

        $allNewExistingUsers = $this->repository->getAll();
        self::assertCount(count($allExistingUsers) - 1, $allNewExistingUsers);

        $foundUser = $this->repository->getOneById($authorId);
        self::assertNull($foundUser);
    }
}
