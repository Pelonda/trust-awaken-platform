<?php

declare(strict_types=1);

namespace App\Presentation\Api\Verification\Controllers;

use App\Core\Verification\Actions\VerifyCredential;
use App\Http\Controllers\Controller;
use App\Presentation\Api\Verification\Resources\VerificationResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

final class VerificationController extends Controller
{
    public function __construct(
        private readonly VerifyCredential $verifyCredential,
    ) {
    }

    public function verify(
        Request $request,
        string $verificationCode,
    ): JsonResponse {

        try {

            $credential = $this->verifyCredential->execute(
                verificationCode: $verificationCode,
                ipAddress: $request->ip(),
                userAgent: $request->userAgent(),
            );

        } catch (RuntimeException) {

            return response()->json([
                'valid' => false,
                'message' => 'Credential not found.',
            ], 404);

        }

        return (new VerificationResource($credential))
            ->response()
            ->setStatusCode(200);
    }
}