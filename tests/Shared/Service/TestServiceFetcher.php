<?php

namespace App\Tests\Shared\Service;

use App\Domain\Shared\ServiceFetcherInterface;

class TestServiceFetcher implements ServiceFetcherInterface
{
    /** @var object[] $services */
    private array $services = [];

    public function fetch(string $service): ?object
    {
        if (array_key_exists($service, $this->services)) {
            return $this->services[$service];
        }
        return null;
    }

    public function addService(string $service, object $serviceInstance): void
    {
        $this->services[$service] = $serviceInstance;
    }
}
