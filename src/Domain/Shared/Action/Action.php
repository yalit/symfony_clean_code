<?php

namespace App\Domain\Shared\Action;

interface Action
{
    public function execute(ActionInput $input): ?ActionOutput;
}
