<?php

namespace App\Application\Command\User;

use App\Application\Command\CommandInterface;
use App\Domain\Model\Enum\UserRole;
use App\Infrastructure\Validation\StringEnumValue;
use Symfony\Component\Validator\Constraints\AtLeastOneOf;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\ExpressionSyntax;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Component\Validator\Constraints\NotNull;

class CreateUser implements CommandInterface
{
    public function __construct(
        #[NotBlank]
        private readonly string $name,
        #[NotBlank]
        #[Email]
        private readonly string $email,
        #[NotBlank]
        #[NotCompromisedPassword]
        private readonly string $password,
        #[StringEnumValue(UserRole::class)]
        private readonly string $role
    ) {
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
