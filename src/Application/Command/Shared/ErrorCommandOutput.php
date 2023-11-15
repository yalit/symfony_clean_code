<?php

namespace App\Application\Command\Shared;

use App\Application\Command\CommandOutputInterface;
use App\Application\Command\Enum\CommandOutputStatus;

class ErrorCommandOutput implements CommandOutputInterface
{
    public function getStatus(): CommandOutputStatus
    {
        return CommandOutputStatus::ERROR;
    }
}
