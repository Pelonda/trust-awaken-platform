<?php

declare(strict_types=1);

namespace Tests\Feature\CredentialTemplate;

use App\Core\CredentialTemplate\Models\CredentialTemplate;
use App\Core\Organization\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

final class ListCredentialTemplateTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_credential_templates(): void
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

        CredentialTemplate::create([
            'uuid' => (string) Str::uuid(),
            'organization_id' => $organization->id,
            'template_code' => 'CERT-001',
            'name' => 'Certificate of Completion',
            'credential_type' => 'certificate',
            'paper_size' => 'A4',
            'orientation' => 'landscape',
            'is_default' => true,
        ]);

        CredentialTemplate::create([
            'uuid' => (string) Str::uuid(),
            'organization_id' => $organization->id,
            'template_code' => 'CERT-002',
            'name' => 'Certificate of Excellence',
            'credential_type' => 'certificate',
            'paper_size' => 'A4',
            'orientation' => 'portrait',
            'is_default' => false,
        ]);

        $response = $this->getJson('/api/v1/credential-templates');

        $response->assertOk();

        $response->assertJsonCount(2, 'data');
    }
}