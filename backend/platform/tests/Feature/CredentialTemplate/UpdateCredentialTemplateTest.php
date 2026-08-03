<?php

declare(strict_types=1);

namespace Tests\Feature\CredentialTemplate;

use App\Core\CredentialTemplate\Models\CredentialTemplate;
use App\Core\Organization\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

final class UpdateCredentialTemplateTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_existing_credential_template(): void
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
            'name' => 'Certificate',
            'credential_type' => 'certificate',
            'paper_size' => 'A4',
            'orientation' => 'landscape',
            'is_default' => true,
        ]);

        $response = $this->putJson(
            "/api/v1/credential-templates/{$template->uuid}",
            [
                'name' => 'Certificate of Completion',
                'paper_size' => 'Letter',
                'orientation' => 'portrait',
                'is_default' => false,
            ]
        );

        $response->assertOk();

        $response->assertJsonFragment([
            'name' => 'Certificate of Completion',
        ]);

        $this->assertDatabaseHas('credential_templates', [
            'name' => 'Certificate of Completion',
            'paper_size' => 'Letter',
        ]);
    }

    public function test_update_unknown_credential_template_returns_404(): void
    {
        $response = $this->putJson(
            '/api/v1/credential-templates/00000000-0000-0000-0000-000000000000',
            [
                'name' => 'Test',
                'paper_size' => 'A4',
                'orientation' => 'landscape',
                'is_default' => true,
            ]
        );

        $response->assertNotFound();
    }
}