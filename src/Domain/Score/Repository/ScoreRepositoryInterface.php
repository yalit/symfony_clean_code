<?php

namespace App\Domain\Score\Repository;

use App\Domain\Score\Model\Score;
use App\Domain\Shared\Repository\DomainRepositoryInterface;

/**
 * @template-extends DomainRepositoryInterface<Score>
 */
interface ScoreRepositoryInterface extends DomainRepositoryInterface {}
