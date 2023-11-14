<?php

namespace App\Domain\Model\Enum;

enum AuthorRole: string
{
    case ADMIN = 'admin';
    case EDITOR = 'editor';
    case AUTHOR = 'author';
}
