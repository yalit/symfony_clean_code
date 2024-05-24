<?php

namespace App\Domain\Score\Repository;

use App\Domain\Score\Model\ScoreFile;
use App\Domain\Shared\Repository\DomainRepositoryInterface;

/**
 * @template-extends DomainRepositoryInterface<ScoreFile>
 */
interface ScoreFileRepositoryInterface extends DomainRepositoryInterface {}
