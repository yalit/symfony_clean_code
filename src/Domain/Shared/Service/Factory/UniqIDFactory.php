<?php

namespace App\Domain\Shared\Service\Factory;

final class UniqIDFactory
{
    public static function create(string $prefix = ""): string
    {
        return uniqid($prefix);
    }
}
