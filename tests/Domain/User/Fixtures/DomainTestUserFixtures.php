<?php

namespace App\Tests\Domain\User\Fixtures;

use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\Service\Factory\UserFactory;

class DomainTestUserFixtures
{
    public const ADMIN_EMAIL = 'admin@email.com';
    public const EDITOR_EMAIL = 'editor@email.com';
    public const AUTHOR_EMAIL = 'author@email.com';

    public const PASSWORD = 'Password123)';

    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserFactory $userFactory,
    ) {}

    public function load(): void
    {
        $admin = $this->userFactory->createAdmin('Admin', self::ADMIN_EMAIL, self::PASSWORD);
        $this->userRepository->save($admin);

        $editor = $this->userFactory->createEditor('Editor', self::EDITOR_EMAIL, self::PASSWORD);
        $this->userRepository->save($editor);

        $author = $this->userFactory->createAuthor('Author', self::AUTHOR_EMAIL, self::PASSWORD);
        $this->userRepository->save($author);
    }
}
