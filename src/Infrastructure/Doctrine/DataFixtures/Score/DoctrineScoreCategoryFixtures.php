<?php

namespace App\Infrastructure\Doctrine\DataFixtures\Score;

use App\Domain\Score\Model\Enum\ScoreCategoryType;
use App\Domain\Score\Service\Factory\ScoreCategoryFactory;
use App\Infrastructure\Doctrine\Mapper\Score\DoctrineScoreCategoryMappper;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DoctrineScoreCategoryFixtures extends Fixture
{
    public const SCORE_CATEGORY_NAME_1 = 'Score Category 1';
    public const SCORE_CATEGORY_NAME_2 = 'Score Category 2';
    public const SCORE_CATEGORY_NAME_3 = 'Score Category 3';
    public const SCORE_CATEGORY_NAME_4 = 'Score Category 4';
    public const IDENTIFICATION_CATEGORY_NAME_1 = 'Identification 1';
    public const IDENTIFICATION_CATEGORY_NAME_2 = 'Identification 2';
    public const IDENTIFICATION_CATEGORY_NAME_3 = 'Identification 3';

    public const SCORE_CATEGORY_REFERENCE = 'score_category_%s';

    public function __construct(
        private readonly DoctrineScoreCategoryMappper $doctrineMapper,
    ) {}

    public function load(ObjectManager $manager): void
    {

        $scoreCategory1 = $this->doctrineMapper->fromDomainEntity(ScoreCategoryFactory::create(self::SCORE_CATEGORY_NAME_1, ScoreCategoryType::SCORE));
        $manager->persist($scoreCategory1);
        $this->addReference(sprintf(self::SCORE_CATEGORY_REFERENCE, self::SCORE_CATEGORY_NAME_1), $scoreCategory1);

        $scoreCategory2 = $this->doctrineMapper->fromDomainEntity(ScoreCategoryFactory::create(self::SCORE_CATEGORY_NAME_2, ScoreCategoryType::SCORE));
        $manager->persist($scoreCategory2);
        $this->addReference(sprintf(self::SCORE_CATEGORY_REFERENCE, self::SCORE_CATEGORY_NAME_2), $scoreCategory2);

        $scoreCategory3 = $this->doctrineMapper->fromDomainEntity(ScoreCategoryFactory::create(self::SCORE_CATEGORY_NAME_3, ScoreCategoryType::SCORE));
        $manager->persist($scoreCategory3);
        $this->addReference(sprintf(self::SCORE_CATEGORY_REFERENCE, self::SCORE_CATEGORY_NAME_3), $scoreCategory3);

        $scoreCategory4 = $this->doctrineMapper->fromDomainEntity(ScoreCategoryFactory::create(self::SCORE_CATEGORY_NAME_4, ScoreCategoryType::SCORE));
        $manager->persist($scoreCategory4);
        $this->addReference(sprintf(self::SCORE_CATEGORY_REFERENCE, self::SCORE_CATEGORY_NAME_4), $scoreCategory4);

        $identificationCategory1 = $this->doctrineMapper->fromDomainEntity(ScoreCategoryFactory::create(self::IDENTIFICATION_CATEGORY_NAME_1, ScoreCategoryType::IDENTIFICATION));
        $manager->persist($identificationCategory1);
        $this->addReference(sprintf(self::SCORE_CATEGORY_REFERENCE, self::IDENTIFICATION_CATEGORY_NAME_1), $identificationCategory1);

        $identificationCategory2 = $this->doctrineMapper->fromDomainEntity(ScoreCategoryFactory::create(self::IDENTIFICATION_CATEGORY_NAME_2, ScoreCategoryType::IDENTIFICATION));
        $manager->persist($identificationCategory2);
        $this->addReference(sprintf(self::SCORE_CATEGORY_REFERENCE, self::IDENTIFICATION_CATEGORY_NAME_2), $identificationCategory2);

        $identificationCategory3 = $this->doctrineMapper->fromDomainEntity(ScoreCategoryFactory::create(self::IDENTIFICATION_CATEGORY_NAME_3, ScoreCategoryType::IDENTIFICATION));
        $manager->persist($identificationCategory3);
        $this->addReference(sprintf(self::SCORE_CATEGORY_REFERENCE, self::IDENTIFICATION_CATEGORY_NAME_3), $identificationCategory3);

        $manager->flush();
    }
}
