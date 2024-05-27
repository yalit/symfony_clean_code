<?php

namespace App\Infrastructure\Doctrine\Model\Score;

use App\Domain\Score\Model\Enum\ScoreCategoryType;
use App\Infrastructure\Doctrine\Repository\Score\DoctrineScoreCategoryRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: DoctrineScoreCategoryRepository::class)]
#[Table(name: 'score_category')]
class DoctrineScoreCategory
{
    #[Id]
    #[Column(type: 'string', length: 128, nullable: false)]
    private ?string $id = null;

    #[Column(type: 'string', length: 1028, nullable: false)]
    private string $name;

    #[Column(type: 'string', length: 25, nullable: false, enumType: ScoreCategoryType::class)]
    private ScoreCategoryType $type;

    #[Column(type: 'string', length: 1028, nullable: true)]
    private ?string $description;

    public function getId(): ?string
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

    public function getType(): ScoreCategoryType
    {
        return $this->type;
    }

    public function setType(ScoreCategoryType $type): void
    {
        $this->type = $type;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }
}
