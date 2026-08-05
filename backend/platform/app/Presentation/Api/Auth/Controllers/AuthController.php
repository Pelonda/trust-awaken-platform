<?php

declare(strict_types=1);

namespace App\Presentation\Api\Auth\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

final class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials.',
            ], 401);
        }

        $user = $request->user();

        $token = $user
            ->createToken('awaken')
            ->plainTextToken;

        $roles = DB::table('role_user')
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
            ->pluck('roles.name')
            ->values();

        $permissions = DB::table('role_user')
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
            ->pluck('permissions.name')
            ->unique()
            ->values();

        return response()->json([

            'token' => $token,

            'user' => [

                'id' => $user->id,

                'name' => $user->name,

                'email' => $user->email,

                'roles' => $roles,

                'permissions' => $permissions,

            ],

        ]);
    }

    public function logout(Request $request)
    {
        $request
            ->user()
            ->currentAccessToken()
            ?->delete();

        return response()->json([
            'message' => 'Logged out.',
        ]);
    }

    public function me(Request $request)
    {
        return response()->json(
            $request->user()
        );
    }
}