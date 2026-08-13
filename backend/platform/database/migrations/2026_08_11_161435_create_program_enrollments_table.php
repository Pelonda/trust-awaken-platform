<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'program_enrollments',
            function (Blueprint $table): void {
                $table->id();

                $table
                    ->uuid('uuid')
                    ->unique();

                /*
                |--------------------------------------------------------------------------
                | Tenant
                |--------------------------------------------------------------------------
                */

                $table
                    ->foreignId(
                        'organization_id'
                    )
                    ->constrained(
                        'organizations'
                    )
                    ->cascadeOnDelete();

                /*
                |--------------------------------------------------------------------------
                | Program
                |--------------------------------------------------------------------------
                */

                $table
                    ->foreignId(
                        'program_id'
                    )
                    ->constrained(
                        'programs'
                    )
                    ->cascadeOnDelete();

                /*
                |--------------------------------------------------------------------------
                | Participant
                |--------------------------------------------------------------------------
                */

                $table
                    ->foreignId(
                        'participant_id'
                    )
                    ->constrained(
                        'participants'
                    )
                    ->cascadeOnDelete();

                /*
                |--------------------------------------------------------------------------
                | Enrollment Status
                |--------------------------------------------------------------------------
                |
                | Initial expected values:
                |
                | enrolled
                | active
                | completed
                | withdrawn
                | cancelled
                |
                | We deliberately use a string rather than a database enum so
                | the workflow can evolve without replacing the column.
                |
                */

                $table
                    ->string(
                        'status',
                        50
                    )
                    ->default('enrolled');

                $table
                    ->timestamp(
                        'enrolled_at'
                    )
                    ->nullable();

                $table
                    ->timestamp(
                        'completed_at'
                    )
                    ->nullable();

                /*
                |--------------------------------------------------------------------------
                | Extensible Enrollment Metadata
                |--------------------------------------------------------------------------
                |
                | Future examples:
                |
                | attendance percentage
                | assessment result
                | import batch
                | external student ID
                | eligibility information
                |
                */

                $table
                    ->json('metadata')
                    ->nullable();

                $table->timestamps();

                /*
                |--------------------------------------------------------------------------
                | Prevent Duplicate Enrollment
                |--------------------------------------------------------------------------
                |
                | A participant may only have one enrollment record for a
                | particular program within an organization.
                |
                */

                $table->unique(
                    [
                        'organization_id',
                        'program_id',
                        'participant_id',
                    ],
                    'program_enrollments_unique'
                );

                /*
                |--------------------------------------------------------------------------
                | Operational Indexes
                |--------------------------------------------------------------------------
                */

                $table->index(
                    [
                        'organization_id',
                        'program_id',
                        'status',
                    ],
                    'program_enrollments_program_status_idx'
                );

                $table->index(
                    [
                        'organization_id',
                        'participant_id',
                    ],
                    'program_enrollments_participant_idx'
                );
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'program_enrollments'
        );
    }
};