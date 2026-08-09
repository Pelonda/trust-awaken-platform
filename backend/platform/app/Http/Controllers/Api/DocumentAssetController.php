<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Core\Document\Models\DocumentAsset;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class DocumentAssetController extends Controller
{
    public function index(
        Request $request
    ): JsonResponse {
        $organizationId =
            $this->organizationId(
                $request
            );

        $assets =
            DocumentAsset::query()
                ->where(
                    'organization_id',
                    $organizationId
                )
                ->latest()
                ->get()
                ->map(
                    fn (
                        DocumentAsset $asset
                    ): array =>
                        $this->transform(
                            $asset
                        )
                );

        return response()->json([
            'data' => $assets,
        ]);
    }

    public function store(
        Request $request
    ): JsonResponse {
        $organizationId =
            $this->organizationId(
                $request
            );

        $validated =
            $request->validate([
                'file' => [
                    'required',
                    'file',
                    'mimes:jpg,jpeg,png,webp,svg',
                    'max:10240',
                ],

                'asset_type' => [
                    'required',
                    'string',
                    'in:image,logo,background,watermark,signature,seal',
                ],

                'name' => [
                    'nullable',
                    'string',
                    'max:255',
                ],
            ]);

        $file =
            $request->file(
                'file'
            );

        abort_if(
            !$file,
            422,
            'Asset file is required.'
        );

        $uuid =
            (string) Str::uuid();

        $extension =
            strtolower(
                $file
                    ->getClientOriginalExtension()
            );

        /*
         * Do not rely entirely on the
         * client filename extension.
         */
        if ($extension === '') {
            $extension =
                match (
                    $file->getMimeType()
                ) {
                    'image/jpeg' =>
                        'jpg',

                    'image/png' =>
                        'png',

                    'image/webp' =>
                        'webp',

                    'image/svg+xml' =>
                        'svg',

                    default =>
                        'bin',
                };
        }

        $filename =
            $uuid .
            '.' .
            $extension;

        $directory =
            'document-assets/' .
            $organizationId;

        $disk =
            'public';

        $path =
            $file->storeAs(
                $directory,
                $filename,
                $disk
            );

        abort_if(
            !$path,
            500,
            'Unable to store asset.'
        );

        [
            $width,
            $height,
        ] =
            $this->imageDimensions(
                $file->getRealPath(),
                $file->getMimeType()
            );

        $asset =
            DocumentAsset::create([
                'uuid' =>
                    $uuid,

                'organization_id' =>
                    $organizationId,

                'name' =>
                    $validated['name']
                    ??
                    $file
                        ->getClientOriginalName(),

                'asset_type' =>
                    $validated[
                        'asset_type'
                    ],

                'disk' =>
                    $disk,

                'path' =>
                    $path,

                'mime_type' =>
                    $file->getMimeType(),

                'width' =>
                    $width,

                'height' =>
                    $height,

                'metadata' => [
                    'original_name' =>
                        $file
                            ->getClientOriginalName(),

                    'size' =>
                        $file->getSize(),
                ],
            ]);

        return response()->json(
            [
                'data' =>
                    $this->transform(
                        $asset
                    ),
            ],
            201
        );
    }

    public function destroy(
        Request $request,
        string $uuid
    ): JsonResponse {
        $organizationId =
            $this->organizationId(
                $request
            );

        $asset =
            DocumentAsset::query()
                ->where(
                    'organization_id',
                    $organizationId
                )
                ->where(
                    'uuid',
                    $uuid
                )
                ->firstOrFail();

        $disk =
            $asset->disk
            ?: 'public';

        if (
            $asset->path &&
            Storage::disk(
                $disk
            )->exists(
                $asset->path
            )
        ) {
            Storage::disk(
                $disk
            )->delete(
                $asset->path
            );
        }

        $asset->delete();

        return response()->json([
            'message' =>
                'Asset deleted successfully.',
        ]);
    }

    private function transform(
        DocumentAsset $asset
    ): array {
        return [
            'id' =>
                $asset->uuid,

            'uuid' =>
                $asset->uuid,

            'organization_id' =>
                $asset->organization_id,

            'name' =>
                $asset->name,

            'type' =>
                $asset->asset_type,

            'asset_type' =>
                $asset->asset_type,

            'disk' =>
                $asset->disk,

            'path' =>
                $asset->path,

            /*
             * Always return an absolute
             * Laravel asset URL.
             *
             * Example:
             *
             * http://127.0.0.1:8000/
             * storage/document-assets/1/...
             */
            'url' =>
                $this->assetUrl(
                    $asset
                ),

            'mime_type' =>
                $asset->mime_type,

            'width' =>
                $asset->width,

            'height' =>
                $asset->height,

            'metadata' =>
                $asset->metadata,

            'createdAt' =>
                optional(
                    $asset->created_at
                )->toISOString(),

            'created_at' =>
                optional(
                    $asset->created_at
                )->toISOString(),

            'updated_at' =>
                optional(
                    $asset->updated_at
                )->toISOString(),
        ];
    }

    private function assetUrl(
        DocumentAsset $asset
    ): string {
        $disk =
            $asset->disk
            ?: 'public';

        /*
         * We intentionally build this
         * from APP_URL instead of trusting
         * Storage::url() to provide the
         * correct host.
         */
        if ($disk === 'public') {
            return
                rtrim(
                    (string)
                    config(
                        'app.url'
                    ),
                    '/'
                )
                .
                '/storage/'
                .
                ltrim(
                    $asset->path,
                    '/'
                );
        }

        $url =
            Storage::disk(
                $disk
            )->url(
                $asset->path
            );

        if (
            str_starts_with(
                $url,
                'http://'
            ) ||
            str_starts_with(
                $url,
                'https://'
            )
        ) {
            return $url;
        }

        return
            rtrim(
                (string)
                config(
                    'app.url'
                ),
                '/'
            )
            .
            '/'
            .
            ltrim(
                $url,
                '/'
            );
    }

    private function imageDimensions(
        string $path,
        ?string $mimeType
    ): array {
        /*
         * SVG dimensions are not
         * reliably available through
         * getimagesize().
         */
        if (
            $mimeType ===
            'image/svg+xml'
        ) {
            return [
                null,
                null,
            ];
        }

        $size =
            @getimagesize(
                $path
            );

        if (!$size) {
            return [
                null,
                null,
            ];
        }

        return [
            $size[0]
                ?? null,

            $size[1]
                ?? null,
        ];
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

        $organization =
            DB::table(
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
            $organization->status
                !== 'active',
            403,
            'Organization is not active.'
        );

        return (int)
            $organization->id;
    }
}