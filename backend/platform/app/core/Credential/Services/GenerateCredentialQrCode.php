<?php

declare(strict_types=1);

namespace App\Core\Credential\Services;

use App\Core\Credential\Models\Credential;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\File;

final class GenerateCredentialQrCode
{
    public function execute(
        Credential $credential,
    ): string {

        $directory = storage_path(
            'app/public/qrcodes'
        );

        if (! File::exists($directory)) {

            File::makeDirectory(
                $directory,
                0755,
                true
            );

        }

        $filename =
            $credential->verification_code . '.svg';

        $filepath =
            $directory . DIRECTORY_SEPARATOR . $filename;

        $verificationUrl =
            config('app.url')
            . '/verify/'
            . $credential->verification_code;

        $renderer = new ImageRenderer(
            new RendererStyle(300),
            new SvgImageBackEnd()
        );

        $writer = new Writer($renderer);

        $writer->writeFile(
            $verificationUrl,
            $filepath
        );

        $credential->update([
            'verification_url' => $verificationUrl,
            'qr_code_path' => 'qrcodes/' . $filename,
        ]);

        return $filepath;
    }
}