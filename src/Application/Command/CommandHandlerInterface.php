<?php

namespace App\Application\Command;

interface CommandHandlerInterface
{
    public function __invoke(CommandInterface $command): CommandOutputInterface;
}
