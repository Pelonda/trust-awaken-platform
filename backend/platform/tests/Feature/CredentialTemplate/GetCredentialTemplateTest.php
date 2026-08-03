<?php

declare(strict_types=1);

namespace Tests\Feature\CredentialTemplate;

use App\Core\CredentialTemplate\Models\CredentialTemplate;
use App\Core\Organization\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

final class GetCredentialTemplateTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_existing_credential_template(): void
    {
        $owner = User::factory()->create();

        $organization = Organization::create([
            'uuid' => (string) Str::uuid(),
            'slug' => 'global-cybersafe',
            'display_name' => 'Global CyberSafe',
            'legal_name' => 'Global CyberSafe Foundation',
            'organization_type' => 'nonprofit',
            'status' => 'active',
            'owner_user_id' => $owner->id,
        ]);

        $template = CredentialTemplate::create([
            'uuid' => (string) Str::uuid(),
            'organization_id' => $organization->id,
            'template_code' => 'CERT-001',
            'name' => 'Certificate of Completion',
            'credential_type' => 'certificate',
            'paper_size' => 'A4',
            'orientation' => 'landscape',
            'is_default' => true,
        ]);

        $response = $this->getJson(
            "/api/v1/credential-templates/{$template->uuid}"
        );

        $response->assertOk();

        $response->assertJsonStructure([
            'data' => [
                'uuid',
                'template_code',
                'name',
                'credential_type',
                'paper_size',
                'orientation',
                'is_default',
                'created_at',
                'updated_at',
            ],
        ]);
    }

    public function test_get_unknown_credential_template_returns_404(): void
    {
        $response = $this->getJson(
            '/api/v1/credential-templates/00000000-0000-0000-0000-000000000000'
        );

        $response->assertNotFound();
    }
}