<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

final class OrganizationMiddleware
{
    public function handle(
        Request $request,
        Closure $next
    )
    {
        $organization = $request->header(
            'X-Organization'
        );

        if ($organization) {

            app()->instance(
                'organization_uuid',
                $organization
            );

        }

        return $next($request);
    }
}