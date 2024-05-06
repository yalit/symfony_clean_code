<?php

use App\Domain\Shared\Validation\ValidatorInterface;
use App\Domain\User\Rule\UserUniqueEmailRuleValidator;
use App\Infrastructure\Service\ContainerServiceFetcher;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ContainerServiceFetcherTest extends KernelTestCase
{
    public function testContainerServiceFetcher(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        $containerServiceFetcher = $container->get(ContainerServiceFetcher::class);
        $service = $containerServiceFetcher->fetch(UserUniqueEmailRuleValidator::class);
        self::assertInstanceOf(UserUniqueEmailRuleValidator::class, $service);
    }
}
