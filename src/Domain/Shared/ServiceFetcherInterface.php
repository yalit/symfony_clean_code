<?php

namespace App\Domain\Shared;

interface ServiceFetcherInterface
{
    public function fetch(string $service): ?object;
}
