<?php

namespace App\Domain\Model\Factory;

final class UniqIDFactory
{
    public static function create(string $prefix = ""): string
    {
        return uniqid($prefix);
    }
}
