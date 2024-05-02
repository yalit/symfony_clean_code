<?php

namespace App\Infrastructure\Security\Service\Model;

use App\Domain\User\Model\User;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class SecurityUser extends User implements PasswordAuthenticatedUserInterface {}
