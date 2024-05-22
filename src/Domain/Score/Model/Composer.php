<?php

namespace App\Domain\Score\Model;

class Composer
{
    public function __construct(
        private readonly string $id,
        private string $name,
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
