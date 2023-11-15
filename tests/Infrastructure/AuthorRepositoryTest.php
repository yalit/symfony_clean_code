<?php

namespace App\Tests\Infrastructure;

use App\Domain\Model\Author;
use App\Domain\Model\Factory\AuthorFactory;
use App\Infrastructure\Doctrine\Repository\AuthorRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AuthorRepositoryTest extends RepositoryKernelTestCase
{
    /**
     * @var AuthorRepository $repository
     */
    protected ServiceEntityRepositoryInterface $repository;
    protected string $entityClass = AuthorRepository::class;

    public function testSave(): void
    {
        $allExistingAuthors = $this->repository->findAll();
        self::assertCount(0, $allExistingAuthors);

        $authorName = 'Author 1';
        $authorEmail = 'author1@email.com';
        $author = AuthorFactory::createAuthor($authorName, $authorEmail);
        $this->repository->save($author);

        $this->assertNotNull($author->getId());

        $allNewExistingAuthors = $this->repository->findAll();
        self::assertCount(1, $allNewExistingAuthors);

        /** @var Author $newAuthor */
        $newAuthor = $allNewExistingAuthors[0];
        self::assertEquals($author->getId(), $newAuthor->getId());
        self::assertEquals($authorName, $newAuthor->getName());
        self::assertEquals($authorEmail, $newAuthor->getEmail());
    }
}
