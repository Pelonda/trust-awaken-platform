<?php

declare(strict_types=1);

namespace App\Presentation\Api\Credential\Controllers;

use App\Core\Credential\Models\Credential;
use App\Core\Credential\Services\GenerateCredentialPdf;
use App\Core\Credential\Services\GenerateFabricCredentialPdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class PreviewCredentialController extends Controller
{
    public function __construct(
        private readonly GenerateCredentialPdf $legacyPdf,
        private readonly GenerateFabricCredentialPdf $fabricPdf,
    ) {
    }

    public function __invoke(
        string $uuid,
    ): BinaryFileResponse {
        $credential =
            Credential::query()
                ->where(
                    'uuid',
                    $uuid
                )
                ->firstOrFail();

        $isFabric =
            $credential->document_template_id
                !== null;

        if (
            empty(
                $credential->pdf_path
            )
            ||
            !Storage::disk(
                'public'
            )->exists(
                $credential->pdf_path
            )
        ) {
            if ($isFabric) {
                $this->fabricPdf->execute(
                    $credential
                );
            } else {
                $this->legacyPdf->execute(
                    $credential
                );
            }

            $credential->refresh();
        }

        $path =
            $credential->pdf_path;

        abort_if(
            !$path ||
            !Storage::disk(
                'public'
            )->exists(
                $path
            ),
            404,
            'Credential PDF was not generated.'
        );

        return response()->file(
            Storage::disk(
                'public'
            )->path(
                $path
            ),
            [
                'Content-Type' =>
                    'application/pdf',

                'Content-Disposition' =>
                    'inline; filename="'
                    . $credential
                        ->credential_number
                    . '.pdf"',
            ]
        );
    }
}