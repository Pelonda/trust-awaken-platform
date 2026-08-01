<?php

declare(strict_types=1);

namespace App\Core\Identity\Enums;

enum UserType: string
{
    case Platform = 'platform';

    case Organization = 'organization';

    case Recipient = 'recipient';
}