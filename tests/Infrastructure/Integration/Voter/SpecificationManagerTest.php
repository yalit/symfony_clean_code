<?php

namespace App\Tests\Infrastructure\Integration\Voter;

use App\Infrastructure\Voter\SpecificationManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SpecificationManagerTest extends KernelTestCase
{
    private readonly SpecificationManagerInterface $specificationManager;
    protected function setUp(): void
    {
        self::bootKernel();
        $this->specificationManager = self::getContainer()->get(SpecificationManagerInterface::class);
    }

    public function testKernelLoadingAllSpecificationsIntoManager(): void
    {
        $specifications = $this->specificationManager->getSpecifications();
        self::assertNotEmpty($specifications);
    }
}
