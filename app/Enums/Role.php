<?php

namespace App\Enums;

enum Role: string
{
    case Admin = 'Admin';
    case Author = 'Author';
    case User = 'User';
}
