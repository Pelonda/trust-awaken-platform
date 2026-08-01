<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Trust AWAKEN API
|--------------------------------------------------------------------------
|
| This file only bootstraps the module route files.
| Each module owns its own routes.
|
*/

require __DIR__ . '/api/public.php';
require __DIR__ . '/api/identity.php';
require __DIR__ . '/api/organization.php';

// Future Modules
// require __DIR__ . '/api/billing.php';
// require __DIR__ . '/api/document.php';
// require __DIR__ . '/api/template.php';
// require __DIR__ . '/api/workshop.php';
// require __DIR__ . '/api/participant.php';
// require __DIR__ . '/api/certificate.php';
// require __DIR__ . '/api/verification.php';
// require __DIR__ . '/api/reporting.php';
// require __DIR__ . '/api/marketplace.php';