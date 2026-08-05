<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

final class PermissionMiddleware
{
    public function handle(
        Request $request,
        Closure $next,
        string $permission
    ): Response {

        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        $allowed = DB::table('role_user')
            ->join(
                'permission_role',
                'permission_role.role_id',
                '=',
                'role_user.role_id'
            )
            ->join(
                'permissions',
                'permissions.id',
                '=',
                'permission_role.permission_id'
            )
            ->where(
                'role_user.user_id',
                $user->id
            )
            ->where(
                'permissions.name',
                $permission
            )
            ->exists();

        if (! $allowed) {
            abort(403);
        }

        return $next($request);
    }
}