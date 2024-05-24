<?php

namespace App\Domain\Score\Repository;

use App\Domain\Score\Model\ScoreCategory;
use App\Domain\Shared\Repository\DomainRepositoryInterface;

/**
 * @template-extends DomainRepositoryInterface<ScoreCategory>
 */
interface ScoreCategoryRepositoryInterface extends DomainRepositoryInterface {}
