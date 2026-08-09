<?php

declare(strict_types=1);

namespace App\Core\Credential\Services;

use App\Core\Credential\Models\Credential;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class FabricVariableResolver
{
    /**
     * Resolve all supported Trust AWAKEN
     * Fabric variables for an issued credential.
     *
     * @return array<string, string>
     */
    public function resolve(
        Credential $credential
    ): array {
        $participant =
            DB::table('participants')
                ->where(
                    'id',
                    $credential->participant_id
                )
                ->first();

        if (!$participant) {
            throw new RuntimeException(
                'Credential participant not found.'
            );
        }

        $program =
            DB::table('programs')
                ->where(
                    'id',
                    $credential->program_id
                )
                ->first();

        if (!$program) {
            throw new RuntimeException(
                'Credential program not found.'
            );
        }

        $organization =
            DB::table('organizations')
                ->where(
                    'id',
                    $credential->organization_id
                )
                ->first();

        if (!$organization) {
            throw new RuntimeException(
                'Credential organization not found.'
            );
        }

        $session =
            DB::table('program_sessions')
                ->where(
                    'id',
                    $credential->session_id
                )
                ->first();

        if (!$session) {
            throw new RuntimeException(
                'Credential session not found.'
            );
        }

        /*
         * Organization isolation check.
         *
         * Participants and programs must belong
         * to the same organization as the
         * credential.
         */
        if (
            (int) $participant->organization_id
            !==
            (int) $credential->organization_id
        ) {
            throw new RuntimeException(
                'Participant does not belong to the credential organization.'
            );
        }

        if (
            (int) $program->organization_id
            !==
            (int) $credential->organization_id
        ) {
            throw new RuntimeException(
                'Program does not belong to the credential organization.'
            );
        }

        /*
         * Session belongs to Program rather
         * than directly to Organization.
         */
        if (
            (int) $session->program_id
            !==
            (int) $credential->program_id
        ) {
            throw new RuntimeException(
                'Session does not belong to the credential program.'
            );
        }

        $participantName =
            trim(
                implode(
                    ' ',
                    array_filter([
                        $participant->first_name
                            ?? null,

                        $participant->last_name
                            ?? null,
                    ])
                )
            );

        return [
            /*
             * Participant
             */
            '{{participant.name}}' =>
                $participantName,

            '{{participant.email}}' =>
                (string) (
                    $participant->email
                    ?? ''
                ),

            /*
             * Program
             */
            '{{program.title}}' =>
                (string) (
                    $program->title
                    ?? ''
                ),

            '{{program.code}}' =>
                (string) (
                    $program->program_code
                    ?? ''
                ),

            /*
             * Session
             *
             * These aren't exposed in the
             * Fabric Variables panel yet,
             * but supporting them now makes
             * the resolver reusable.
             */
            '{{session.title}}' =>
                (string) (
                    $session->title
                    ?? ''
                ),

            '{{session.code}}' =>
                (string) (
                    $session->session_code
                    ?? ''
                ),

            '{{session.venue}}' =>
                (string) (
                    $session->venue
                    ?? ''
                ),

            /*
             * Organization
             */
            '{{organization.name}}' =>
                (string) (
                    $organization->display_name
                    ?? ''
                ),

            /*
             * Credential
             *
             * credential.number is protected
             * in Fabric and generated only
             * by the backend.
             */
            '{{credential.number}}' =>
                (string)
                $credential->credential_number,

            '{{credential.issue_date}}' =>
                $this->formatDate(
                    $credential->issued_at
                ),

            '{{credential.expiry_date}}' =>
                $this->formatDate(
                    $credential->expires_at
                ),

            '{{credential.verification_url}}' =>
                (string) (
                    $credential->verification_url
                    ?? ''
                ),

            /*
             * Do NOT resolve the QR to text.
             *
             * The Fabric renderer will identify
             * objects whose awakenType is:
             *
             * verification-qr
             *
             * and replace the placeholder image
             * with the generated QR asset.
             */
            '{{credential.verification_qr}}' =>
                (string) (
                    $credential->qr_code_path
                    ?? ''
                ),

            /*
             * Trainer intentionally remains
             * unresolved.
             *
             * There is currently no trainer
             * relationship on program_sessions.
             */
            '{{trainer.name}}' =>
                '{{trainer.name}}',
        ];
    }

    /**
     * Replace supported variables inside
     * an arbitrary string.
     */
    public function replace(
        string $value,
        Credential $credential
    ): string {
        return strtr(
            $value,
            $this->resolve(
                $credential
            )
        );
    }

    /**
     * Recursively resolve variables inside
     * a decoded Fabric canvas structure.
     */
    public function resolveCanvas(
        array $canvas,
        Credential $credential
    ): array {
        $variables =
            $this->resolve(
                $credential
            );

        return $this->walk(
            $canvas,
            $variables
        );
    }

    /**
     * @param array<string, string> $variables
     */
    private function walk(
        mixed $value,
        array $variables
    ): mixed {
        if (is_string($value)) {
            return strtr(
                $value,
                $variables
            );
        }

        if (!is_array($value)) {
            return $value;
        }

        foreach (
            $value as
            $key => $child
        ) {
            $value[$key] =
                $this->walk(
                    $child,
                    $variables
                );
        }

        return $value;
    }

    private function formatDate(
        mixed $date
    ): string {
        if ($date === null) {
            return '';
        }

        /*
         * Eloquent casts issued_at and
         * expires_at to Carbon instances.
         */
        if (
            is_object($date) &&
            method_exists(
                $date,
                'format'
            )
        ) {
            return $date->format(
                'F j, Y'
            );
        }

        try {
            return \Carbon\Carbon::parse(
                $date
            )->format(
                'F j, Y'
            );
        } catch (\Throwable) {
            return (string) $date;
        }
    }
}