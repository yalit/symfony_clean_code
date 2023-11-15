<?php

namespace App\Application\Command;

use App\Application\Command\Enum\CommandOutputStatus;

interface CommandOutputInterface
{
    public function getStatus(): CommandOutputStatus;
}
