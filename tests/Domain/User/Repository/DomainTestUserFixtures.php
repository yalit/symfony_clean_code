<?php

namespace App\Tests\Domain\User\Repository;

use App\Domain\User\Model\Factory\UserFactory;
use App\Domain\User\Repository\UserRepositoryInterface;

class DomainTestUserFixtures
{
    public const ADMIN_EMAIL = 'admin@email.com';
    public const EDITOR_EMAIL = 'editor@email.com';
    public const AUTHOR_EMAIL = 'author@email.com';

    public const PASSWORD = 'Password123)';

    public function __construct(private readonly UserRepositoryInterface $userRepository) {}

    public function load(): void
    {
        $admin = UserFactory::createAdmin('Admin', self::ADMIN_EMAIL, self::PASSWORD);
        $this->userRepository->save($admin);

        $editor = UserFactory::createEditor('Editor', self::EDITOR_EMAIL, self::PASSWORD);
        $this->userRepository->save($editor);

        $author = UserFactory::createAuthor('Author', self::AUTHOR_EMAIL, self::PASSWORD);
        $this->userRepository->save($author);
    }
}
