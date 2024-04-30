<?php

namespace App\Domain\Shared\Query;

interface Query
{
    public function execute(QueryInput $input): QueryOutput;
}
