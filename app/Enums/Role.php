<?php

namespace App\Enums;

use App\Enums\Concerns\Enum;

enum Role: string
{
    use Enum;

    case SUPER = 'super';
    case USER = 'user';

    /**
     * Get the permissions assigned to this role.
     *
     * @return array<Permission>
     */
    public function permissions(): array
    {
        return match ($this) {
            self::SUPER => [
                Permission::ACCESS_ADMIN,
            ],
            self::USER => [
                //
            ],
        };
    }
}
