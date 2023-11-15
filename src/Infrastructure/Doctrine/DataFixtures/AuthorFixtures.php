<?php

namespace App\Infrastructure\Doctrine\DataFixtures;

use App\Domain\Model\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AuthorFixtures extends Fixture
{
    public const ADMIN_NAME = 'administrator';
    public const EDITOR_NAME = 'editor';
    public const AUTHOR_NAME = 'author';

    public const AUTHOR_EMAIL = '%s@testemail.com';
    public const AUTHOR_REFERENCE = 'author_%s';

    public function load(ObjectManager $manager): void
    {
        $admin = UserFactory::createAdmin(self::ADMIN_NAME, sprintf(self::AUTHOR_EMAIL, self::ADMIN_NAME));
        $manager->persist($admin);
        $this->addReference(sprintf(self::AUTHOR_REFERENCE, self::ADMIN_NAME), $admin);

        $editor = UserFactory::createEditor(self::EDITOR_NAME, sprintf(self::AUTHOR_EMAIL, self::EDITOR_NAME));
        $manager->persist($editor);
        $this->addReference(sprintf(self::AUTHOR_REFERENCE, self::EDITOR_NAME), $editor);

        $author = UserFactory::createAuthor(self::AUTHOR_NAME, sprintf(self::AUTHOR_EMAIL, self::AUTHOR_NAME));
        $manager->persist($author);
        $this->addReference(sprintf(self::AUTHOR_REFERENCE, self::AUTHOR_NAME), $author);

        $manager->flush();
    }
}
