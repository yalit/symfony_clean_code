<?php

namespace App\Domain\Score\Repository;

use App\Domain\Score\Model\ScoreIdentification;
use App\Domain\Shared\Repository\DomainRepositoryInterface;

/**
 * @template-extends DomainRepositoryInterface<ScoreIdentification>
 */
interface ScoreIdentificationRepositoryInterface extends DomainRepositoryInterface {}
