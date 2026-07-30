<?php

namespace App\Core\Organization\Enums;

enum OrganizationStatus: string
{
    case Draft = 'draft';

    case Trial = 'trial';

    case Active = 'active';

    case Suspended = 'suspended';

    case Archived = 'archived';
}