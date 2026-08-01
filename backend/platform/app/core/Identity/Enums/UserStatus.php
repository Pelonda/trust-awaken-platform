<?php

declare(strict_types=1);

namespace App\Core\Identity\Enums;

enum UserStatus: string
{
    case Active = 'active';

    case Pending = 'pending';

    case Suspended = 'suspended';

    case Archived = 'archived';
}