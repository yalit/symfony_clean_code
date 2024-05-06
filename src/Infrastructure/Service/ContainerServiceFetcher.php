<?php

namespace App\Infrastructure\Service;

use App\Domain\Shared\ServiceFetcherInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ContainerServiceFetcher implements ServiceFetcherInterface
{
    public function __construct(private readonly ContainerInterface $container) {}

    public function fetch(string $service): ?object
    {
        return $this->container->get($service);
    }
}
