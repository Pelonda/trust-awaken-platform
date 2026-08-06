<?php

declare(strict_types=1);

namespace App\Core\Credential\Services;

use App\Core\Credential\Models\Credential;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;

final class GenerateCredentialPdf
{
    public function execute(
        Credential $credential,
    ): string {

        $directory = storage_path(
            'app/public/certificates'
        );

        if (! File::exists($directory)) {

            File::makeDirectory(
                $directory,
                0755,
                true
            );

        }

        $filename =
            $credential->credential_number . '.pdf';

        $filepath =
            $directory . DIRECTORY_SEPARATOR . $filename;

        Pdf::loadView(
            'pdf.certificate',
            [
                'credential' => $credential,
            ]
        )->save($filepath);

        $credential->update([
            'pdf_path' => 'certificates/' . $filename,
        ]);

        return $filepath;
    }
}