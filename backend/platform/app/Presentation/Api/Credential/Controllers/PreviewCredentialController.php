<?php

declare(strict_types=1);

namespace App\Presentation\Api\Credential\Controllers;

use App\Core\Credential\Models\Credential;
use App\Core\Credential\Services\GenerateCredentialPdf;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

final class PreviewCredentialController extends Controller
{
    public function __construct(
        private readonly GenerateCredentialPdf $pdf,
    ) {
    }

    public function __invoke(string $uuid): Response
    {
        $credential = Credential::query()
            ->where('uuid', $uuid)
            ->firstOrFail();

        if (
            empty($credential->pdf_path) ||
            ! Storage::disk('public')->exists($credential->pdf_path)
        ) {
            $this->pdf->execute($credential);

            $credential->refresh();
        }

        return response()->file(
            storage_path(
                'app/public/' . $credential->pdf_path
            )
        );
    }
}