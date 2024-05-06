<?php

namespace App\Infrastructure\Doctrine\Generator;

use App\Domain\User\Service\Factory\UniqIDFactory;
use App\Infrastructure\Doctrine\Model\DoctrineUser;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AbstractIdGenerator;

class DoctrineUserIdGenerator extends AbstractIdGenerator
{
    /**
     * @param EntityManagerInterface $em
     * @param DoctrineUser $entity
     * @return string
     */
    public function generateId(EntityManagerInterface $em, $entity): string
    {
        if ($entity->getId() !== null && str_starts_with($entity->getId(), $entity->getRole()->value)) {
            return $entity->getId();
        }

        return UniqIDFactory::create($entity->getRole()->value);
    }
}
