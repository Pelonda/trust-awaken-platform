<?php

declare(strict_types=1);

namespace App\Core\Credential\Actions;

use App\Core\Credential\DTOs\CreateCredentialData;
use App\Core\Credential\Models\Credential;
use App\Core\Credential\Services\FabricVariableResolver;
use App\Core\Credential\Services\GenerateCredentialQrCode;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

final readonly class IssueFabricCredential
{
    public function __construct(
        private CreateCredential $createCredential,
        private GenerateCredentialQrCode $generateQrCode,
        private FabricVariableResolver $variableResolver,
    ) {
    }

    public function execute(
        CreateCredentialData $data
    ): Credential {
        /*
         * Fabric issuance must use the new
         * document_templates system only.
         */
        if (
            $data->documentTemplateId === null
        ) {
            throw new RuntimeException(
                'A Fabric document template is required.'
            );
        }

        if (
            $data->templateId !== null
        ) {
            throw new RuntimeException(
                'A Fabric credential cannot also use a legacy credential template.'
            );
        }

        return DB::transaction(
            function () use ($data): Credential {

                /*
                |--------------------------------------------------------------------------
                | Organization
                |--------------------------------------------------------------------------
                */

                $organization =
                    DB::table(
                        'organizations'
                    )
                        ->where(
                            'id',
                            $data->organizationId
                        )
                        ->where(
                            'status',
                            'active'
                        )
                        ->whereNull(
                            'deleted_at'
                        )
                        ->first();

                if (!$organization) {
                    throw new RuntimeException(
                        'Organization is not active or does not exist.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Participant
                |--------------------------------------------------------------------------
                |
                | Participant must belong to the
                | credential organization.
                |
                */

                $participant =
                    DB::table(
                        'participants'
                    )
                        ->where(
                            'id',
                            $data->participantId
                        )
                        ->where(
                            'organization_id',
                            $data->organizationId
                        )
                        ->whereNull(
                            'deleted_at'
                        )
                        ->first();

                if (!$participant) {
                    throw new RuntimeException(
                        'Participant does not belong to this organization.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Program
                |--------------------------------------------------------------------------
                |
                | Program must belong to the
                | credential organization.
                |
                */

                $program =
                    DB::table(
                        'programs'
                    )
                        ->where(
                            'id',
                            $data->programId
                        )
                        ->where(
                            'organization_id',
                            $data->organizationId
                        )
                        ->whereNull(
                            'deleted_at'
                        )
                        ->first();

                if (!$program) {
                    throw new RuntimeException(
                        'Program does not belong to this organization.'
                    );
                }

                /*
                 * A program that does not allow
                 * credentials cannot issue one.
                 */
                if (
                    !((bool)
                        $program
                            ->credential_enabled)
                ) {
                    throw new RuntimeException(
                        'Credential issuance is disabled for this program.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Session
                |--------------------------------------------------------------------------
                |
                | program_sessions does not contain
                | organization_id.
                |
                | Tenant isolation is therefore:
                |
                | Organization
                |      ↓
                | Program
                |      ↓
                | Session
                |
                */

                $session =
                    DB::table(
                        'program_sessions'
                    )
                        ->where(
                            'id',
                            $data->sessionId
                        )
                        ->where(
                            'program_id',
                            $data->programId
                        )
                        ->whereNull(
                            'deleted_at'
                        )
                        ->first();

                if (!$session) {
                    throw new RuntimeException(
                        'Session does not belong to the selected program.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Fabric Document Template
                |--------------------------------------------------------------------------
                |
                | Template must belong to the same
                | organization.
                |
                */

                $template =
                    DB::table(
                        'document_templates'
                    )
                        ->where(
                            'id',
                            $data->documentTemplateId
                        )
                        ->where(
                            'organization_id',
                            $data->organizationId
                        )
                        ->whereNull(
                            'deleted_at'
                        )
                        ->first();

                if (!$template) {
                    throw new RuntimeException(
                        'Fabric document template not found for this organization.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Validate Canvas Before Issuance
                |--------------------------------------------------------------------------
                |
                | Decode before creating a credential
                | or QR file.
                |
                */

                $canvas =
                    $this->decodeCanvas(
                        $template->canvas
                    );


                /*
                |--------------------------------------------------------------------------
                | Create Credential
                |--------------------------------------------------------------------------
                |
                | Repository generates:
                |
                | uuid
                | credential_number
                | verification_code
                | status
                | issued_at
                |
                */

                $credential =
                    $this->createCredential
                        ->execute(
                            $data
                        );

                try {

                    /*
                    |--------------------------------------------------------------------------
                    | Generate Verification QR
                    |--------------------------------------------------------------------------
                    |
                    | This also persists:
                    |
                    | verification_url
                    | qr_code_path
                    |
                    */

                    $this->generateQrCode
                        ->execute(
                            $credential
                        );

                    $credential->refresh();


                    /*
                    |--------------------------------------------------------------------------
                    | Resolve Dynamic Variables
                    |--------------------------------------------------------------------------
                    */

                    $resolvedCanvas =
                        $this->variableResolver
                            ->resolveCanvas(
                                $canvas,
                                $credential
                            );


                    /*
                    |--------------------------------------------------------------------------
                    | Resolve Verification QR Object
                    |--------------------------------------------------------------------------
                    |
                    | Preserve designer geometry.
                    | Replace only issuance data.
                    |
                    */

                    $resolvedCanvas =
                        $this->resolveQrObjects(
                            $resolvedCanvas,
                            $credential
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | Immutable Issuance Snapshot
                    |--------------------------------------------------------------------------
                    |
                    | Never render an issued credential
                    | directly from the live template.
                    |
                    | If the organization later edits
                    | or deletes the source template,
                    | the issued credential retains its
                    | original resolved design.
                    |
                    */

                    $metadata =
                        is_array(
                            $credential->metadata
                        )
                            ? $credential->metadata
                            : [];

                    $metadata[
                        'fabric'
                    ] = [
                        'engine' =>
                            'fabric',

                        'version' =>
                            1,

                        'document_template_id' =>
                            (int)
                            $template->id,

                        'document_template_uuid' =>
                            (string)
                            $template->uuid,

                        'document_template_name' =>
                            (string)
                            $template->name,

                        'paper_size' =>
                            (string)
                            $template->paper_size,

                        'orientation' =>
                            (string)
                            $template->orientation,

                        /*
                         * Immutable resolved Fabric
                         * canvas.
                         */
                        'canvas' =>
                            $resolvedCanvas,

                        /*
                         * Issuance references.
                         */
                        'credential_uuid' =>
                            (string)
                            $credential->uuid,

                        'credential_number' =>
                            (string)
                            $credential
                                ->credential_number,

                        'verification_code' =>
                            (string)
                            $credential
                                ->verification_code,

                        'verification_url' =>
                            $credential
                                ->verification_url,

                        'qr_code_path' =>
                            $credential
                                ->qr_code_path,

                        'issued_at' =>
                            optional(
                                $credential
                                    ->issued_at
                            )->toISOString(),

                        /*
                         * Source IDs are retained for
                         * auditing.
                         */
                        'organization_id' =>
                            (int)
                            $data->organizationId,

                        'participant_id' =>
                            (int)
                            $data->participantId,

                        'program_id' =>
                            (int)
                            $data->programId,

                        'session_id' =>
                            (int)
                            $data->sessionId,
                    ];

                    $credential->update([
                        'metadata' =>
                            $metadata,
                    ]);

                    return $credential
                        ->refresh();

                } catch (Throwable $exception) {

                    /*
                     * Database changes are rolled
                     * back automatically.
                     *
                     * QR generation creates a physical
                     * file outside the database, so
                     * remove it manually if issuance
                     * fails afterward.
                     */

                    $this->cleanupQr(
                        $credential
                    );

                    throw $exception;
                }
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Decode Fabric Canvas
    |--------------------------------------------------------------------------
    */

    private function decodeCanvas(
        mixed $canvas
    ): array {
        if (
            is_array(
                $canvas
            )
        ) {
            return $canvas;
        }

        if (
            !is_string(
                $canvas
            )
        ) {
            throw new RuntimeException(
                'Fabric template canvas is invalid.'
            );
        }

        try {
            $decoded =
                json_decode(
                    $canvas,
                    true,
                    512,
                    JSON_THROW_ON_ERROR
                );
        } catch (
            Throwable $exception
        ) {
            throw new RuntimeException(
                'Unable to decode Fabric template canvas.',
                previous:
                    $exception
            );
        }

        if (
            !is_array(
                $decoded
            )
        ) {
            throw new RuntimeException(
                'Fabric template canvas is invalid.'
            );
        }

        return $decoded;
    }


    /*
    |--------------------------------------------------------------------------
    | Resolve QR Objects
    |--------------------------------------------------------------------------
    */

    private function resolveQrObjects(
        array $canvas,
        Credential $credential
    ): array {
        if (
            !isset(
                $canvas['objects']
            ) ||
            !is_array(
                $canvas['objects']
            )
        ) {
            return $canvas;
        }

        foreach (
            $canvas['objects']
            as $index => $object
        ) {
            if (
                !is_array(
                    $object
                )
            ) {
                continue;
            }

            if (
                (
                    $object[
                        'awakenType'
                    ]
                    ?? null
                )
                !==
                'verification-qr'
            ) {
                continue;
            }

            /*
             * Preserve position, dimensions,
             * scale, rotation and designer
             * styling.
             */

            $object[
                'awakenType'
            ] =
                'verification-qr';

            $object[
                'awakenVariable'
            ] =
                '{{credential.verification_qr}}';

            $object[
                'awakenProtected'
            ] =
                true;

            $object[
                'awakenQrCodePath'
            ] =
                $credential
                    ->qr_code_path;

            $object[
                'awakenVerificationUrl'
            ] =
                $credential
                    ->verification_url;

            $object[
                'awakenVerificationCode'
            ] =
                $credential
                    ->verification_code;

            $canvas[
                'objects'
            ][
                $index
            ] =
                $object;
        }

        return $canvas;
    }


    /*
    |--------------------------------------------------------------------------
    | QR File Cleanup
    |--------------------------------------------------------------------------
    */

    private function cleanupQr(
        Credential $credential
    ): void {
        $path =
            $credential
                ->qr_code_path;

        if (!$path) {
            return;
        }

        $absolutePath =
            storage_path(
                'app/public/'
                .
                ltrim(
                    $path,
                    '/'
                )
            );

        if (
            is_file(
                $absolutePath
            )
        ) {
            @unlink(
                $absolutePath
            );
        }
    }
}