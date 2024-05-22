<?php

namespace App\Domain\Score\Model;

class Identification
{
    public function __construct(
        private readonly string $id,
        private string $number,
        private Category $category,
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function setNumber(string $number): void
    {
        $this->number = $number;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }
}
