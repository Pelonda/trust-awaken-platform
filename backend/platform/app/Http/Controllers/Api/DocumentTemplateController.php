<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Core\Document\Models\DocumentTemplate;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

final class DocumentTemplateController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Tenant Templates
    |--------------------------------------------------------------------------
    */

    public function index(
        Request $request
    ): JsonResponse {
        $organizationId =
            $this->organizationId(
                $request
            );

        $query =
            DocumentTemplate::query()
                ->where(
                    'organization_id',
                    $organizationId
                )
                ->where(
                    'is_system',
                    false
                )
                ->latest();

        $this->applyFilters(
            $request,
            $query
        );

        return response()->json([
            'data' =>
                $query->get(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Professional System Library
    |--------------------------------------------------------------------------
    |
    | System templates:
    |
    | organization_id = null
    | is_system = true
    |
    | Tenants can browse these templates but cannot edit or delete them.
    |
    */

    public function library(
        Request $request
    ): JsonResponse {
        /*
         * Resolve the organization even
         * though system templates are
         * global.
         *
         * This ensures only requests from
         * an active selected tenant can
         * access the library.
         */

        $this->organizationId(
            $request
        );

        $query =
            DocumentTemplate::query()
                ->whereNull(
                    'organization_id'
                )
                ->where(
                    'is_system',
                    true
                )
                ->orderBy(
                    'document_type'
                )
                ->orderBy(
                    'language'
                )
                ->orderBy(
                    'name'
                );

        $this->applyFilters(
            $request,
            $query
        );

        return response()->json([
            'data' =>
                $query->get(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Create Tenant Template
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request
    ): JsonResponse {
        $organizationId =
            $this->organizationId(
                $request
            );

        $validated =
            $request->validate(
                $this->rules(
                    creating: true
                )
            );

        $template =
            DB::transaction(
                function () use (
                    $validated,
                    $organizationId
                ): DocumentTemplate {
                    $makeDefault =
                        (bool) (
                            $validated[
                                'default'
                            ]
                            ?? false
                        );

                    $type =
                        $this->resolveType(
                            $validated
                        );

                    if ($makeDefault) {
                        $this->clearDefaults(
                            $organizationId,
                            $type
                        );
                    }

                    /*
                     * source_template_id is not accepted
                     * through normal tenant creation.
                     *
                     * System-template lineage is created
                     * exclusively through useTemplate().
                     */

                    unset(
                        $validated[
                            'source_template_id'
                        ]
                    );

                    return DocumentTemplate::create([
                        ...$validated,

                        'uuid' =>
                            (string)
                                Str::uuid(),

                        'organization_id' =>
                            $organizationId,

                        'type' =>
                            $type,

                        'document_type' =>
                            $type,

                        'schema_version' =>
                            (int) (
                                $validated[
                                    'schema_version'
                                ]
                                ?? 2
                            ),

                        'default' =>
                            $makeDefault,

                        'is_system' =>
                            false,

                        'source_template_id' =>
                            null,
                    ]);
                }
            );

        return response()->json([
            'data' =>
                $template,
        ], 201);
    }

    /*
    |--------------------------------------------------------------------------
    | Use Professional Template
    |--------------------------------------------------------------------------
    |
    | Copies a protected system template into the currently selected tenant.
    |
    | The copy becomes:
    |
    | organization_id    = tenant
    | is_system          = false
    | source_template_id = system template
    |
    */

    public function useTemplate(
        Request $request,
        int $id
    ): JsonResponse {
        $organizationId =
            $this->organizationId(
                $request
            );

        $source =
            DocumentTemplate::query()
                ->whereKey(
                    $id
                )
                ->whereNull(
                    'organization_id'
                )
                ->where(
                    'is_system',
                    true
                )
                ->first();

        abort_unless(
            $source,
            404,
            'System template not found.'
        );

        $validated =
            $request->validate([
                'name' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:255',
                ],

                'default' => [
                    'sometimes',
                    'boolean',
                ],
            ]);

        $copy =
            DB::transaction(
                function () use (
                    $source,
                    $validated,
                    $organizationId
                ): DocumentTemplate {
                    $makeDefault =
                        (bool) (
                            $validated[
                                'default'
                            ]
                            ?? false
                        );

                    $name =
                        trim(
                            (string) (
                                $validated[
                                    'name'
                                ]
                                ?? $source->name
                            )
                        );

                    if ($makeDefault) {
                        $this->clearDefaults(
                            $organizationId,
                            $source->document_type
                                ?? $source->type
                                ?? 'certificate'
                        );
                    }

                    return DocumentTemplate::create([
                        'uuid' =>
                            (string)
                                Str::uuid(),

                        'organization_id' =>
                            $organizationId,

                        'name' =>
                            $name,

                        'type' =>
                            $source->type,

                        'paper_size' =>
                            $source->paper_size,

                        'orientation' =>
                            $source->orientation,

                        /*
                         * Eloquent's array cast gives us
                         * a PHP array here. Assigning it
                         * to the new model produces an
                         * independent JSON snapshot.
                         */

                        'canvas' =>
                            $source->canvas,

                        'default' =>
                            $makeDefault,

                        'schema_version' =>
                            $source->schema_version,

                        'document_type' =>
                            $source->document_type,

                        'language' =>
                            $source->language,

                        'paper_width' =>
                            $source->paper_width,

                        'paper_height' =>
                            $source->paper_height,

                        'paper_unit' =>
                            $source->paper_unit,

                        'is_system' =>
                            false,

                        'source_template_id' =>
                            $source->id,

                        'settings' =>
                            $source->settings,
                    ]);
                }
            );

        return response()->json([
            'data' =>
                $copy,

            'message' =>
                'Template added to your organization.',
        ], 201);
    }

    /*
    |--------------------------------------------------------------------------
    | Show Tenant Template
    |--------------------------------------------------------------------------
    */

    public function show(
        Request $request,
        DocumentTemplate $documentTemplate
    ): JsonResponse {
        $this->authorizeOrganization(
            $request,
            $documentTemplate
        );

        return response()->json([
            'data' =>
                $documentTemplate,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Update Tenant Template
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        DocumentTemplate $documentTemplate
    ): JsonResponse {
        $organizationId =
            $this->organizationId(
                $request
            );

        $this->authorizeOrganization(
            $request,
            $documentTemplate
        );

        abort_if(
            $documentTemplate->is_system,
            403,
            'System templates cannot be modified.'
        );

        $validated =
            $request->validate(
                $this->rules(
                    creating: false
                )
            );

        $template =
            DB::transaction(
                function () use (
                    $validated,
                    $documentTemplate,
                    $organizationId
                ): DocumentTemplate {
                    $type =
                        $this->resolveType(
                            $validated,
                            $documentTemplate
                        );

                    if (
                        array_key_exists(
                            'default',
                            $validated
                        )
                        &&
                        $validated[
                            'default'
                        ]
                    ) {
                        $this->clearDefaults(
                            $organizationId,
                            $type,
                            $documentTemplate->id
                        );
                    }

                    if (
                        array_key_exists(
                            'document_type',
                            $validated
                        )
                        ||
                        array_key_exists(
                            'type',
                            $validated
                        )
                    ) {
                        $validated[
                            'type'
                        ] =
                            $type;

                        $validated[
                            'document_type'
                        ] =
                            $type;
                    }

                    /*
                     * Lineage and system ownership are
                     * immutable through normal editing.
                     */

                    unset(
                        $validated[
                            'is_system'
                        ],
                        $validated[
                            'source_template_id'
                        ]
                    );

                    $documentTemplate->update(
                        $validated
                    );

                    return $documentTemplate
                        ->fresh();
                }
            );

        return response()->json([
            'data' =>
                $template,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Tenant Template
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Request $request,
        DocumentTemplate $documentTemplate
    ): JsonResponse {
        $this->authorizeOrganization(
            $request,
            $documentTemplate
        );

        abort_if(
            $documentTemplate->is_system,
            403,
            'System templates cannot be deleted.'
        );

        $documentTemplate->delete();

        return response()->json([
            'message' =>
                'Template deleted successfully.',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    /**
     * @return array<string, mixed>
     */
    private function rules(
        bool $creating
    ): array {
        $required =
            $creating
                ? 'required'
                : 'sometimes';

        return [
            'name' => [
                $required,
                'string',
                'max:255',
            ],

            /*
             * Legacy classification.
             */

            'type' => [
                'sometimes',
                'string',
                Rule::in(
                    $this->documentTypes()
                ),
            ],

            /*
             * V2 classification.
             */

            'document_type' => [
                $required,
                'string',
                Rule::in(
                    $this->documentTypes()
                ),
            ],

            'language' => [
                $required,
                'string',
                Rule::in([
                    'en',
                    'fr',
                    'en-fr',
                ]),
            ],

            'paper_size' => [
                $required,
                'string',
                'max:50',
            ],

            'orientation' => [
                $required,
                'string',
                Rule::in([
                    'portrait',
                    'landscape',
                ]),
            ],

            'paper_width' => [
                $required,
                'numeric',
                'gt:0',
            ],

            'paper_height' => [
                $required,
                'numeric',
                'gt:0',
            ],

            'paper_unit' => [
                $required,
                'string',
                Rule::in([
                    'mm',
                    'cm',
                    'in',
                    'px',
                ]),
            ],

            'schema_version' => [
                'sometimes',
                'integer',
                'min:1',
                'max:2',
            ],

            'canvas' => [
                $required,
                'array',
            ],

            'settings' => [
                'nullable',
                'array',
            ],

            /*
             * Accepted for compatibility but removed
             * before normal create/update persistence.
             */

            'source_template_id' => [
                'nullable',
                'integer',
            ],

            'default' => [
                'sometimes',
                'boolean',
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Filters
    |--------------------------------------------------------------------------
    */

    private function applyFilters(
        Request $request,
        $query
    ): void {
        if (
            $request->filled(
                'type'
            )
        ) {
            $query->where(
                'type',
                $request
                    ->string('type')
                    ->toString()
            );
        }

        if (
            $request->filled(
                'document_type'
            )
        ) {
            $query->where(
                'document_type',
                $request
                    ->string(
                        'document_type'
                    )
                    ->toString()
            );
        }

        if (
            $request->filled(
                'language'
            )
        ) {
            $query->where(
                'language',
                $request
                    ->string(
                        'language'
                    )
                    ->toString()
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Resolve Document Type
    |--------------------------------------------------------------------------
    */

    private function resolveType(
        array $validated,
        ?DocumentTemplate $template = null
    ): string {
        return (string) (
            $validated[
                'document_type'
            ]
            ?? $validated[
                'type'
            ]
            ?? $template
                ?->document_type
            ?? $template
                ?->type
            ?? 'certificate'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Document Types
    |--------------------------------------------------------------------------
    */

    /**
     * @return array<int, string>
     */
    private function documentTypes():
        array {
        return [
            'certificate',
            'diploma',
            'badge',
            'id_card',
            'training_card',
            'custom',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Default Template
    |--------------------------------------------------------------------------
    */

    private function clearDefaults(
        int $organizationId,
        string $documentType,
        ?int $exceptId = null
    ): void {
        $query =
            DocumentTemplate::query()
                ->where(
                    'organization_id',
                    $organizationId
                )
                ->where(
                    'document_type',
                    $documentType
                )
                ->where(
                    'default',
                    true
                );

        if (
            $exceptId !== null
        ) {
            $query->where(
                'id',
                '!=',
                $exceptId
            );
        }

        $query->update([
            'default' =>
                false,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Tenant Authorization
    |--------------------------------------------------------------------------
    */

    private function authorizeOrganization(
        Request $request,
        DocumentTemplate $template
    ): void {
        $organizationId =
            $this->organizationId(
                $request
            );

        abort_unless(
            !$template->is_system
            &&
            (int)
                $template
                    ->organization_id
            ===
            $organizationId,
            404,
            'Template not found.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Selected Organization
    |--------------------------------------------------------------------------
    */

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
            $organization->status !==
                'active',
            403,
            'Organization is not active.'
        );

        return (int)
            $organization->id;
    }
}