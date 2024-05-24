<?php

namespace App\Domain\Score\Service\Factory;

use App\Domain\Score\Model\Composer;
use App\Domain\Shared\Service\Factory\UniqIDFactory;

class ComposerFactory
{
    public static function create(string $name): Composer
    {
        return new Composer(
            id: UniqIDFactory::create("composer_"),
            name: $name,
        );
    }
}
