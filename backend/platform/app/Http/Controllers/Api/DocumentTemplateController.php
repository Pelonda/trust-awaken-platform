<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DocumentTemplate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocumentTemplateController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $organizationId =
            $this->organizationId($request);

        $query = DocumentTemplate::query()
            ->where(
                'organization_id',
                $organizationId
            )
            ->latest();

        if ($request->filled('type')) {
            $query->where(
                'type',
                $request->string('type')->toString()
            );
        }

        return response()->json([
            'data' => $query->get(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $organizationId =
            $this->organizationId($request);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'type' => [
                'required',
                'string',
                'max:50',
            ],

            'paper_size' => [
                'required',
                'string',
                'max:50',
            ],

            'orientation' => [
                'required',
                'string',
                'in:portrait,landscape',
            ],

            'canvas' => [
                'required',
                'array',
            ],

            'default' => [
                'sometimes',
                'boolean',
            ],
        ]);

        $template = DB::transaction(
            function () use (
                $validated,
                $organizationId
            ) {
                $makeDefault =
                    (bool) (
                        $validated['default']
                        ?? false
                    );

                if ($makeDefault) {
                    $this->clearDefaults(
                        $organizationId,
                        $validated['type']
                    );
                }

                return DocumentTemplate::create([
                    ...$validated,

                    'organization_id' =>
                        $organizationId,

                    'default' =>
                        $makeDefault,
                ]);
            }
        );

        return response()->json([
            'data' => $template,
        ], 201);
    }

    public function show(
        Request $request,
        DocumentTemplate $documentTemplate
    ): JsonResponse {
        $this->authorizeOrganization(
            $request,
            $documentTemplate
        );

        return response()->json([
            'data' => $documentTemplate,
        ]);
    }

    public function update(
        Request $request,
        DocumentTemplate $documentTemplate
    ): JsonResponse {
        $organizationId =
            $this->organizationId($request);

        $this->authorizeOrganization(
            $request,
            $documentTemplate
        );

        $validated = $request->validate([
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],

            'type' => [
                'sometimes',
                'required',
                'string',
                'max:50',
            ],

            'paper_size' => [
                'sometimes',
                'required',
                'string',
                'max:50',
            ],

            'orientation' => [
                'sometimes',
                'required',
                'string',
                'in:portrait,landscape',
            ],

            'canvas' => [
                'sometimes',
                'required',
                'array',
            ],

            'default' => [
                'sometimes',
                'boolean',
            ],
        ]);

        $template = DB::transaction(
            function () use (
                $validated,
                $documentTemplate,
                $organizationId
            ) {
                $type =
                    $validated['type']
                    ?? $documentTemplate->type;

                if (
                    array_key_exists(
                        'default',
                        $validated
                    )
                    && $validated['default']
                ) {
                    $this->clearDefaults(
                        $organizationId,
                        $type,
                        $documentTemplate->id
                    );
                }

                $documentTemplate->update(
                    $validated
                );

                return $documentTemplate->fresh();
            }
        );

        return response()->json([
            'data' => $template,
        ]);
    }

    public function destroy(
        Request $request,
        DocumentTemplate $documentTemplate
    ): JsonResponse {
        $this->authorizeOrganization(
            $request,
            $documentTemplate
        );

        $documentTemplate->delete();

        return response()->json([
            'message' =>
                'Template deleted successfully.',
        ]);
    }

    private function clearDefaults(
        int $organizationId,
        string $type,
        ?int $exceptId = null
    ): void {
        $query = DocumentTemplate::query()
            ->where(
                'organization_id',
                $organizationId
            )
            ->where(
                'type',
                $type
            )
            ->where(
                'default',
                true
            );

        if ($exceptId !== null) {
            $query->where(
                'id',
                '!=',
                $exceptId
            );
        }

        $query->update([
            'default' => false,
        ]);
    }

    private function authorizeOrganization(
        Request $request,
        DocumentTemplate $template
    ): void {
        $organizationId =
            $this->organizationId($request);

        abort_unless(
            (int) $template->organization_id ===
            $organizationId,
            404,
            'Template not found.'
        );
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