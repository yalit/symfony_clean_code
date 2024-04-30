<?php

namespace App\Domain\Shared\Action;

use App\Domain\User\Model\User;

interface ActionInput
{
    public function getRequester(): ?User;
}
