<?php

namespace App\Core\Organization\Enums;

enum OrganizationType: string
{
    case Company = 'company';

    case School = 'school';

    case University = 'university';

    case Nonprofit = 'nonprofit';

    case Government = 'government';

    case Church = 'church';

    case Association = 'association';

    case Healthcare = 'healthcare';

    case TrainingCenter = 'training_center';

    case CertificationBody = 'certification_body';

    case Other = 'other';
}