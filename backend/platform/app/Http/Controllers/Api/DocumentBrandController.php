<?php

namespace App\Http\Controllers\Api;

use App\Core\Document\Models\DocumentBrand;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DocumentBrandController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $organizationId =
            $this->organizationId($request);

        $brand = DocumentBrand::query()
            ->where(
                'organization_id',
                $organizationId
            )
            ->first();

        return response()->json([
            'data' => $brand,
        ]);
    }

    public function save(Request $request): JsonResponse
    {
        $organizationId =
            $this->organizationId($request);

        $validated = $request->validate([
            'brand_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'logo_path' => [
                'nullable',
                'string',
                'max:2048',
            ],

            'favicon_path' => [
                'nullable',
                'string',
                'max:2048',
            ],

            'background_path' => [
                'nullable',
                'string',
                'max:2048',
            ],

            'watermark_path' => [
                'nullable',
                'string',
                'max:2048',
            ],

            'signature_path' => [
                'nullable',
                'string',
                'max:2048',
            ],

            'seal_path' => [
                'nullable',
                'string',
                'max:2048',
            ],

            'primary_color' => [
                'nullable',
                'string',
                'max:50',
            ],

            'secondary_color' => [
                'nullable',
                'string',
                'max:50',
            ],

            'accent_color' => [
                'nullable',
                'string',
                'max:50',
            ],

            'font_family' => [
                'nullable',
                'string',
                'max:255',
            ],

            'settings' => [
                'nullable',
                'array',
            ],
        ]);

        $brand = DocumentBrand::query()
            ->where(
                'organization_id',
                $organizationId
            )
            ->first();

        if (!$brand) {
            $brand =
                new DocumentBrand();

            $brand->uuid =
                (string) Str::uuid();

            $brand->organization_id =
                $organizationId;
        }

        $brand->fill(
            $validated
        );

        $brand->save();

        return response()->json([
            'data' =>
                $brand->fresh(),
        ]);
    }

    private function organizationId(
        Request $request
    ): int {
        $organizationUuid =
            $request->header(
                'X-Organization'
            );

        abort_if(
            !$organizationUuid,
            422,
            'Organization is required.'
        );

        $organization = DB::table(
            'organizations'
        )
            ->where(
                'uuid',
                $organizationUuid
            )
            ->whereNull(
                'deleted_at'
            )
            ->first([
                'id',
                'status',
            ]);

        abort_if(
            !$organization,
            404,
            'Organization not found.'
        );

        abort_if(
            $organization->status !== 'active',
            403,
            'Organization is not active.'
        );

        return (int) $organization->id;
    }
}