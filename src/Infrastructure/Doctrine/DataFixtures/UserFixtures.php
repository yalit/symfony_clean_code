<?php

namespace App\Infrastructure\Doctrine\DataFixtures;

use App\Domain\User\Service\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public const ADMIN_NAME = 'admin';
    public const EDITOR_NAME = 'editor';
    public const AUTHOR_NAME = 'author';

    public const USER_EMAIL = '%s@testemail.com';
    public const USER_REFERENCE = 'user_%s';

    public const PASSWORD = 'Password123)';

    public function __construct(
        private readonly UserFactory $userFactory,
    ) {}

    public function load(ObjectManager $manager): void
    {

        $admin = $this->userFactory->createAdmin(self::ADMIN_NAME, sprintf(self::USER_EMAIL, self::ADMIN_NAME), self::PASSWORD);
        $manager->persist($admin);
        $this->addReference(sprintf(self::USER_REFERENCE, self::ADMIN_NAME), $admin);

        $editor = $this->userFactory->createEditor(self::EDITOR_NAME, sprintf(self::USER_EMAIL, self::EDITOR_NAME), self::PASSWORD);
        $manager->persist($editor);
        $this->addReference(sprintf(self::USER_REFERENCE, self::EDITOR_NAME), $editor);

        $author = $this->userFactory->createAuthor(self::AUTHOR_NAME, sprintf(self::USER_EMAIL, self::AUTHOR_NAME), self::PASSWORD);
        $manager->persist($author);
        $this->addReference(sprintf(self::USER_REFERENCE, self::AUTHOR_NAME), $author);

        $manager->flush();
    }
}
