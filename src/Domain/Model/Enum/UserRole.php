<?php

namespace App\Domain\Model\Enum;

enum UserRole: string
{
    case ADMIN = 'admin';
    case EDITOR = 'editor';
    case AUTHOR = 'author';
}
