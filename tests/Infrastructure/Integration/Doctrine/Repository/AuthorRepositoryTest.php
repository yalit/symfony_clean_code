<?php

namespace App\Tests\Infrastructure\Integration\Doctrine\Repository;

use App\Domain\Model\Author;
use App\Domain\Model\Factory\AuthorFactory;
use App\Infrastructure\Doctrine\Repository\AuthorRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;

class AuthorRepositoryTest extends RepositoryKernelTestCase
{
    /**
     * @var AuthorRepository $repository
     */
    protected ServiceEntityRepositoryInterface $repository;
    protected string $entityClass = AuthorRepository::class;

    public function testFindOneById(): void
    {
        $allExistingAuthors = $this->repository->findAll();
        self::assertNotCount(0, $allExistingAuthors);

        /** @var Author $author */
        $author = $allExistingAuthors[0];
        $authorId = $author->getId();
        $authorName = $author->getName();
        $authorEmail = $author->getEmail();

        $foundAuthor = $this->repository->getOneById($authorId);
        self::assertNotNull($foundAuthor);
        self::assertEquals($authorId, $foundAuthor->getId());
        self::assertEquals($authorName, $foundAuthor->getName());
        self::assertEquals($authorEmail, $foundAuthor->getEmail());
    }

    public function testGetOneByIdForUnknownIdReturnsNull(): void
    {
        $unknownId = 'unknown_id';
        $foundAuthor = $this->repository->getOneById($unknownId);
        self::assertNull($foundAuthor);
    }

    public function testSave(): void
    {
        $allExistingAuthors = $this->repository->findAll();
        self::assertNotCount(0, $allExistingAuthors);

        $authorName = 'Author 1';
        $authorEmail = 'author1_test@email.com';
        $author = AuthorFactory::createAuthor($authorName, $authorEmail);
        $this->repository->save($author);

        $this->assertNotNull($author->getId());

        $allNewExistingAuthors = $this->repository->findAll();
        self::assertCount(count($allExistingAuthors) + 1, $allNewExistingAuthors);

        /** @var Author $newAuthor */
        $newAuthor = $allNewExistingAuthors[count($allNewExistingAuthors) - 1];
        self::assertEquals($author->getId(), $newAuthor->getId());
        self::assertEquals($authorName, $newAuthor->getName());
        self::assertEquals($authorEmail, $newAuthor->getEmail());
    }

    public function testDelete(): void
    {
        $allExistingAuthors = $this->repository->findAll();
        self::assertNotCount(0, $allExistingAuthors);

        /** @var Author $author */
        $author = $allExistingAuthors[0];
        $authorId = $author->getId();

        $this->repository->delete($author->getId());

        $allNewExistingAuthors = $this->repository->findAll();
        self::assertCount(count($allExistingAuthors) - 1, $allNewExistingAuthors);

        $foundAuthor = $this->repository->getOneById($authorId);
        self::assertNull($foundAuthor);
    }
}
