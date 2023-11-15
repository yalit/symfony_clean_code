<?php

namespace App\Tests\Infrastructure;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RepositoryKernelTestCase extends KernelTestCase
{
    protected string $entityClass;
    protected ServiceEntityRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $this->repository = self::getContainer()->get($this->entityClass);
    }

    protected function flush(): void
    {
        $this->repository->getEntityManager()->flush();
    }
}
