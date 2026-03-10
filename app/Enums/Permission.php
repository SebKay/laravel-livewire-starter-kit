<?php

namespace App\Enums;

use App\Enums\Concerns\Enum;

enum Permission: string
{
    use Enum;

    case ACCESS_ADMIN = 'access-filament';
}
