<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Core\CredentialTemplate\Models\CredentialTemplate;
use App\Core\Organization\Models\Organization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

final class CredentialTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $organization = Organization::first();

        if (! $organization) {
            return;
        }

        for ($i = 1; $i <= 5; $i++) {

            CredentialTemplate::updateOrCreate(
                [
                    'template_code' => "CERT-00{$i}",
                ],
                [
                    'uuid' => (string) Str::uuid(),
                    'organization_id' => $organization->id,
                    'template_code' => "CERT-00{$i}",
                    'name' => "Certificate Template {$i}",
                    'credential_type' => 'certificate',
                    'paper_size' => 'A4',
                    'orientation' => 'landscape',
                    'is_default' => $i === 1,
                ]
            );

        }
    }
}