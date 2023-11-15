<?php

namespace App\Application\Command\Enum;

enum CommandOutputStatus: string
{
    case SUCCESS = 'success';
    case ERROR = 'error';
    case NO_ACTION = 'no_action';
}
