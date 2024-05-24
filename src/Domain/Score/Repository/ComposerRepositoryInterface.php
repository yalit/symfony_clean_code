<?php

namespace App\Domain\Score\Repository;

use App\Domain\Score\Model\Composer;
use App\Domain\Shared\Repository\DomainRepositoryInterface;

/**
 * @template-extends DomainRepositoryInterface<Composer>
 */
interface ComposerRepositoryInterface extends DomainRepositoryInterface {}
