<?php

declare(strict_types=1);

namespace Tests\Feature\CredentialTemplate;

use App\Core\Organization\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

final class CreateCredentialTemplateTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_credential_template_successfully(): void
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

        $response = $this->postJson(
            '/api/v1/credential-templates',
            [
                'organization_id' => $organization->id,
                'template_code' => 'CERT-001',
                'name' => 'Certificate of Completion',
                'credential_type' => 'certificate',
                'paper_size' => 'A4',
                'orientation' => 'landscape',
                'background_image' => null,
                'elements' => [],
                'is_default' => true,
            ]
        );

        $response->assertCreated();

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

        $this->assertDatabaseHas('credential_templates', [
            'template_code' => 'CERT-001',
            'name' => 'Certificate of Completion',
        ]);
    }
}