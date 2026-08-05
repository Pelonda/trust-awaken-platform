<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

final class RoleMiddleware
{
    public function handle(
        Request $request,
        Closure $next,
        string $role
    ): Response {

        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        $hasRole = DB::table('role_user')
            ->join(
                'roles',
                'roles.id',
                '=',
                'role_user.role_id'
            )
            ->where(
                'role_user.user_id',
                $user->id
            )
            ->where(
                'roles.name',
                $role
            )
            ->exists();

        if (! $hasRole) {
            abort(403);
        }

        return $next($request);
    }
}