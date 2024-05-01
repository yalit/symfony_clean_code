<?php

namespace App\Tests\Infrastructure\Integration\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RepositoryKernelTestCase extends KernelTestCase
{
    protected string $entityClass;
    protected ServiceEntityRepositoryInterface $repository;
    protected EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $this->repository = $this->entityManager->getRepository($this->entityClass);
    }

    protected function flush(): void
    {
        $this->entityManager->flush();
    }
}
