<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Core\Document\Models\DocumentTemplate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

final class ProfessionalDocumentTemplateSeeder extends Seeder
{
    private const ENGINE_VERSION = 7;

    public function run(): void
    {
        foreach ($this->templates() as $template) {
            DocumentTemplate::query()->updateOrCreate(
                [
                    'organization_id' => null,
                    'is_system' => true,
                    'name' => $template['name'],
                    'language' => $template['language'],
                    'document_type' => $template['document_type'],
                    'paper_size' => $template['paper_size'],
                    'orientation' => $template['orientation'],
                ],
                [
                    'uuid' => (string) Str::uuid(),

                    'type' => $template['document_type'],

                    'canvas' => $template['canvas'],

                    'default' => false,

                    'schema_version' => 2,

                    'paper_width' => $template['paper_width'],

                    'paper_height' => $template['paper_height'],

                    'paper_unit' => $template['paper_unit'],

                    'source_template_id' => null,

                    'settings' => [
                        'library' => 'professional',
                        'protected_master' => true,

                        'orientation' =>
                            $template['orientation'],

                        'page_count' => count(
                            $template['canvas']['pages']
                        ),

                        'has_back_side' => count(
                            $template['canvas']['pages']
                        ) > 1,
                    ],
                ]
            );
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function templates(): array
    {
        return [
            /*
            |--------------------------------------------------------------------------
            | Certificates — Landscape
            |--------------------------------------------------------------------------
            */

            $this->certificate(
                name: 'Professional Classic - English - Landscape',
                language: 'en',
                orientation: 'landscape',
                title: 'CERTIFICATE OF COMPLETION',
                presented: 'This certificate is proudly presented to',
                completion: 'for successfully completing',
                issueLabel: 'Issued',
                credentialLabel: 'Credential ID',
                signatureLabel: 'Authorized Signature',
            ),

            $this->certificate(
                name: "Professionnel Classique - Fran\u{00E7}ais - Paysage",
                language: 'fr',
                orientation: 'landscape',
                title: "CERTIFICAT DE R\u{00C9}USSITE",
                presented: "Ce certificat est fi\u{00E8}rement d\u{00E9}cern\u{00E9} \u{00E0}",
                completion: "pour avoir compl\u{00E9}t\u{00E9} avec succ\u{00E8}s",
                issueLabel: "D\u{00E9}livr\u{00E9} le",
                credentialLabel: 'Identifiant',
                signatureLabel: "Signature autoris\u{00E9}e",
            ),

            $this->certificate(
                name: 'Professional Bilingual - EN-FR - Landscape',
                language: 'en-fr',
                orientation: 'landscape',
                title: "CERTIFICATE OF COMPLETION / CERTIFICAT DE R\u{00C9}USSITE",
                presented: "Proudly presented to / Fi\u{00E8}rement d\u{00E9}cern\u{00E9} \u{00E0}",
                completion: "for successfully completing / pour avoir compl\u{00E9}t\u{00E9} avec succ\u{00E8}s",
                issueLabel: "Issued / D\u{00E9}livr\u{00E9}",
                credentialLabel: 'Credential ID / Identifiant',
                signatureLabel: "Authorized Signature / Signature autoris\u{00E9}e",
            ),

            /*
            |--------------------------------------------------------------------------
            | Certificates — Portrait
            |--------------------------------------------------------------------------
            */

            $this->certificate(
                name: 'Professional Classic - English - Portrait',
                language: 'en',
                orientation: 'portrait',
                title: 'CERTIFICATE OF COMPLETION',
                presented: 'This certificate is proudly presented to',
                completion: 'for successfully completing',
                issueLabel: 'Issued',
                credentialLabel: 'Credential ID',
                signatureLabel: 'Authorized Signature',
            ),

            $this->certificate(
                name: "Professionnel Classique - Fran\u{00E7}ais - Portrait",
                language: 'fr',
                orientation: 'portrait',
                title: "CERTIFICAT DE R\u{00C9}USSITE",
                presented: "Ce certificat est fi\u{00E8}rement d\u{00E9}cern\u{00E9} \u{00E0}",
                completion: "pour avoir compl\u{00E9}t\u{00E9} avec succ\u{00E8}s",
                issueLabel: "D\u{00E9}livr\u{00E9} le",
                credentialLabel: 'Identifiant',
                signatureLabel: "Signature autoris\u{00E9}e",
            ),

            $this->certificate(
                name: 'Professional Bilingual - EN-FR - Portrait',
                language: 'en-fr',
                orientation: 'portrait',
                title: "CERTIFICATE OF COMPLETION / CERTIFICAT DE R\u{00C9}USSITE",
                presented: "Proudly presented to / Fi\u{00E8}rement d\u{00E9}cern\u{00E9} \u{00E0}",
                completion: "for successfully completing / pour avoir compl\u{00E9}t\u{00E9} avec succ\u{00E8}s",
                issueLabel: "Issued / D\u{00E9}livr\u{00E9}",
                credentialLabel: 'Credential ID / Identifiant',
                signatureLabel: "Authorized Signature / Signature autoris\u{00E9}e",
            ),

            /*
            |--------------------------------------------------------------------------
            | Diplomas — Portrait
            |--------------------------------------------------------------------------
            */

            $this->diploma(
                name: 'Academic Diploma - English - Portrait',
                language: 'en',
                orientation: 'portrait',
                title: 'DIPLOMA',
                presented: 'This diploma certifies that',
                completion: 'has successfully completed the requirements of',
                issueLabel: 'Awarded',
                credentialLabel: 'Diploma ID',
                signatureLabel: 'Authorized Signature',
            ),

            $this->diploma(
                name: "Dipl\u{00F4}me Acad\u{00E9}mique - Fran\u{00E7}ais - Portrait",
                language: 'fr',
                orientation: 'portrait',
                title: "DIPL\u{00D4}ME",
                presented: "Le pr\u{00E9}sent dipl\u{00F4}me certifie que",
                completion: "a satisfait avec succ\u{00E8}s aux exigences de",
                issueLabel: "D\u{00E9}cern\u{00E9} le",
                credentialLabel: "Identifiant du dipl\u{00F4}me",
                signatureLabel: "Signature autoris\u{00E9}e",
            ),

            $this->diploma(
                name: 'Academic Diploma - EN-FR - Portrait',
                language: 'en-fr',
                orientation: 'portrait',
                title: "DIPLOMA / DIPL\u{00D4}ME",
                presented: "This diploma certifies that / Le pr\u{00E9}sent dipl\u{00F4}me certifie que",
                completion: "has successfully completed / a satisfait avec succ\u{00E8}s aux exigences de",
                issueLabel: "Awarded / D\u{00E9}cern\u{00E9}",
                credentialLabel: 'Diploma ID / Identifiant',
                signatureLabel: "Authorized Signature / Signature autoris\u{00E9}e",
            ),

            /*
            |--------------------------------------------------------------------------
            | Diplomas — Landscape
            |--------------------------------------------------------------------------
            */

            $this->diploma(
                name: 'Academic Diploma - English - Landscape',
                language: 'en',
                orientation: 'landscape',
                title: 'DIPLOMA',
                presented: 'This diploma certifies that',
                completion: 'has successfully completed the requirements of',
                issueLabel: 'Awarded',
                credentialLabel: 'Diploma ID',
                signatureLabel: 'Authorized Signature',
            ),

            $this->diploma(
                name: "Dipl\u{00F4}me Acad\u{00E9}mique - Fran\u{00E7}ais - Paysage",
                language: 'fr',
                orientation: 'landscape',
                title: "DIPL\u{00D4}ME",
                presented: "Le pr\u{00E9}sent dipl\u{00F4}me certifie que",
                completion: "a satisfait avec succ\u{00E8}s aux exigences de",
                issueLabel: "D\u{00E9}cern\u{00E9} le",
                credentialLabel: "Identifiant du dipl\u{00F4}me",
                signatureLabel: "Signature autoris\u{00E9}e",
            ),

            $this->diploma(
                name: 'Academic Diploma - EN-FR - Landscape',
                language: 'en-fr',
                orientation: 'landscape',
                title: "DIPLOMA / DIPL\u{00D4}ME",
                presented: "This diploma certifies that / Le pr\u{00E9}sent dipl\u{00F4}me certifie que",
                completion: "has successfully completed / a satisfait avec succ\u{00E8}s aux exigences de",
                issueLabel: "Awarded / D\u{00E9}cern\u{00E9}",
                credentialLabel: 'Diploma ID / Identifiant',
                signatureLabel: "Authorized Signature / Signature autoris\u{00E9}e",
            ),

            /*
            |--------------------------------------------------------------------------
            | ID Cards — CR80 Landscape / Front + Back
            |--------------------------------------------------------------------------
            */

            $this->idCard(
                name: 'Professional ID - English',
                language: 'en',
                heading: 'PROFESSIONAL ID',
                programLabel: 'Program',
                participantLabel: 'Participant ID',
                expiryLabel: 'Expires',
                verificationLabel: 'Scan to verify this credential',
            ),

            $this->idCard(
                name: "Carte Professionnelle - Fran\u{00E7}ais",
                language: 'fr',
                heading: 'CARTE PROFESSIONNELLE',
                programLabel: 'Programme',
                participantLabel: 'Identifiant',
                expiryLabel: 'Expiration',
                verificationLabel: "Scannez pour v\u{00E9}rifier ce titre",
            ),

            $this->idCard(
                name: 'Professional ID - EN-FR',
                language: 'en-fr',
                heading: 'PROFESSIONAL ID / CARTE PROFESSIONNELLE',
                programLabel: 'Program / Programme',
                participantLabel: 'Participant ID / Identifiant',
                expiryLabel: 'Expires / Expiration',
                verificationLabel: "Scan to verify / Scanner pour v\u{00E9}rifier",
            ),

            /*
            |--------------------------------------------------------------------------
            | Badges — Portrait
            |--------------------------------------------------------------------------
            */

            $this->badge(
                name: 'Achievement Badge - English - Portrait',
                language: 'en',
                orientation: 'portrait',
                heading: 'VERIFIED ACHIEVEMENT',
                subtitle: 'Awarded to',
                programLabel: 'Achievement',
                credentialLabel: 'Credential ID',
            ),

            $this->badge(
                name: "Badge de R\u{00E9}ussite - Fran\u{00E7}ais - Portrait",
                language: 'fr',
                orientation: 'portrait',
                heading: "R\u{00C9}USSITE V\u{00C9}RIFI\u{00C9}E",
                subtitle: "D\u{00E9}cern\u{00E9} \u{00E0}",
                programLabel: "R\u{00E9}ussite",
                credentialLabel: 'Identifiant',
            ),

            $this->badge(
                name: 'Achievement Badge - EN-FR - Portrait',
                language: 'en-fr',
                orientation: 'portrait',
                heading: "VERIFIED ACHIEVEMENT / R\u{00C9}USSITE V\u{00C9}RIFI\u{00C9}E",
                subtitle: "Awarded to / D\u{00E9}cern\u{00E9} \u{00E0}",
                programLabel: "Achievement / R\u{00E9}ussite",
                credentialLabel: 'Credential ID / Identifiant',
            ),

            /*
            |--------------------------------------------------------------------------
            | Badges — Landscape
            |--------------------------------------------------------------------------
            */

            $this->badge(
                name: 'Achievement Badge - English - Landscape',
                language: 'en',
                orientation: 'landscape',
                heading: 'VERIFIED ACHIEVEMENT',
                subtitle: 'Awarded to',
                programLabel: 'Achievement',
                credentialLabel: 'Credential ID',
            ),

            $this->badge(
                name: "Badge de R\u{00E9}ussite - Fran\u{00E7}ais - Paysage",
                language: 'fr',
                orientation: 'landscape',
                heading: "R\u{00C9}USSITE V\u{00C9}RIFI\u{00C9}E",
                subtitle: "D\u{00E9}cern\u{00E9} \u{00E0}",
                programLabel: "R\u{00E9}ussite",
                credentialLabel: 'Identifiant',
            ),

            $this->badge(
                name: 'Achievement Badge - EN-FR - Landscape',
                language: 'en-fr',
                orientation: 'landscape',
                heading: "VERIFIED ACHIEVEMENT / R\u{00C9}USSITE V\u{00C9}RIFI\u{00C9}E",
                subtitle: "Awarded to / D\u{00E9}cern\u{00E9} \u{00E0}",
                programLabel: "Achievement / R\u{00E9}ussite",
                credentialLabel: 'Credential ID / Identifiant',
            ),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function certificate(
        string $name,
        string $language,
        string $orientation,
        string $title,
        string $presented,
        string $completion,
        string $issueLabel,
        string $credentialLabel,
        string $signatureLabel,
    ): array {
        if ($orientation === 'portrait') {
            return $this->portraitFormalDocument(
                name: $name,
                documentType: 'certificate',
                language: $language,
                title: $title,
                presented: $presented,
                completion: $completion,
                issueLabel: $issueLabel,
                credentialLabel: $credentialLabel,
                signatureLabel: $signatureLabel,
            );
        }

        return $this->landscapeFormalDocument(
            name: $name,
            documentType: 'certificate',
            language: $language,
            title: $title,
            presented: $presented,
            completion: $completion,
            issueLabel: $issueLabel,
            credentialLabel: $credentialLabel,
            signatureLabel: $signatureLabel,
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function diploma(
        string $name,
        string $language,
        string $orientation,
        string $title,
        string $presented,
        string $completion,
        string $issueLabel,
        string $credentialLabel,
        string $signatureLabel,
    ): array {
        if ($orientation === 'landscape') {
            return $this->landscapeFormalDocument(
                name: $name,
                documentType: 'diploma',
                language: $language,
                title: $title,
                presented: $presented,
                completion: $completion,
                issueLabel: $issueLabel,
                credentialLabel: $credentialLabel,
                signatureLabel: $signatureLabel,
            );
        }

        return $this->portraitFormalDocument(
            name: $name,
            documentType: 'diploma',
            language: $language,
            title: $title,
            presented: $presented,
            completion: $completion,
            issueLabel: $issueLabel,
            credentialLabel: $credentialLabel,
            signatureLabel: $signatureLabel,
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function landscapeFormalDocument(
        string $name,
        string $documentType,
        string $language,
        string $title,
        string $presented,
        string $completion,
        string $issueLabel,
        string $credentialLabel,
        string $signatureLabel,
    ): array {
        $width = 1414;
        $height = 1000;

        $objects = [
            $this->rect(
                0,
                0,
                $width,
                $height,
                '#fffdf8'
            ),

            $this->rect(
                28,
                28,
                1358,
                944,
                'rgba(255,255,255,0)',
                '#0f172a',
                5
            ),

            $this->rect(
                48,
                48,
                1318,
                904,
                'rgba(255,255,255,0)',
                '#b68b2c',
                2
            ),

            $this->rect(
                0,
                0,
                $width,
                145,
                '#0f172a'
            ),

            $this->text(
                '{{organization.name}}',
                707,
                55,
                28,
                '#ffffff',
                'center',
                true,
                'organization.name'
            ),

            $this->text(
                $title,
                707,
                205,
                48,
                '#172554',
                'center',
                true
            ),

            $this->text(
                $presented,
                707,
                320,
                21,
                '#64748b',
                'center'
            ),

            $this->text(
                '{{participant.name}}',
                707,
                395,
                50,
                '#111827',
                'center',
                true,
                'participant.name'
            ),

            $this->rect(
                355,
                475,
                704,
                2,
                '#b68b2c'
            ),

            $this->text(
                $completion,
                707,
                525,
                20,
                '#475569',
                'center'
            ),

            $this->text(
                '{{program.title}}',
                707,
                590,
                32,
                '#1d4ed8',
                'center',
                true,
                'program.title'
            ),

            /*
             * Signature line.
             *
             * No unresolved trainer variable.
             */

            $this->rect(
                555,
                765,
                304,
                1,
                '#475569'
            ),

            $this->text(
                $signatureLabel,
                707,
                780,
                14,
                '#64748b',
                'center'
            ),

            $this->text(
                "{$issueLabel}: {{credential.issue_date}}",
                300,
                790,
                16,
                '#334155',
                'center',
                false,
                'credential.issue_date'
            ),

            $this->text(
                "{$credentialLabel}: {{credential.number}}",
                1070,
                790,
                15,
                '#334155',
                'center',
                false,
                'credential.number'
            ),

            $this->qr(
                1165,
                825,
                105
            ),
        ];

        return $this->template(
            name: $name,
            documentType: $documentType,
            language: $language,
            paperSize: 'a4',
            orientation: 'landscape',
            paperWidth: 297,
            paperHeight: 210,
            paperUnit: 'mm',
            canvasWidth: $width,
            canvasHeight: $height,
            pages: [
                $this->page(
                    'front',
                    $width,
                    $height,
                    $objects
                ),
            ],
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function portraitFormalDocument(
        string $name,
        string $documentType,
        string $language,
        string $title,
        string $presented,
        string $completion,
        string $issueLabel,
        string $credentialLabel,
        string $signatureLabel,
    ): array {
        $width = 1000;
        $height = 1414;

        $objects = [
            $this->rect(
                0,
                0,
                $width,
                $height,
                '#fffdf8'
            ),

            $this->rect(
                25,
                25,
                950,
                1364,
                'rgba(255,255,255,0)',
                '#172554',
                5
            ),

            $this->rect(
                45,
                45,
                910,
                1324,
                'rgba(255,255,255,0)',
                '#b68b2c',
                2
            ),

            $this->text(
                '{{organization.name}}',
                500,
                125,
                28,
                '#172554',
                'center',
                true,
                'organization.name'
            ),

            $this->text(
                $title,
                500,
                275,
                54,
                '#172554',
                'center',
                true
            ),

            $this->text(
                $presented,
                500,
                425,
                20,
                '#64748b',
                'center'
            ),

            $this->text(
                '{{participant.name}}',
                500,
                520,
                46,
                '#111827',
                'center',
                true,
                'participant.name'
            ),

            $this->rect(
                180,
                600,
                640,
                2,
                '#b68b2c'
            ),

            $this->text(
                $completion,
                500,
                665,
                19,
                '#475569',
                'center'
            ),

            $this->text(
                '{{program.title}}',
                500,
                755,
                31,
                '#1e3a8a',
                'center',
                true,
                'program.title'
            ),

            /*
             * Signature line.
             */

            $this->rect(
                350,
                1040,
                300,
                1,
                '#475569'
            ),

            $this->text(
                $signatureLabel,
                500,
                1055,
                14,
                '#64748b',
                'center'
            ),

            $this->text(
                "{$issueLabel}: {{credential.issue_date}}",
                250,
                1150,
                15,
                '#334155',
                'center',
                false,
                'credential.issue_date'
            ),

            $this->text(
                "{$credentialLabel}: {{credential.number}}",
                720,
                1150,
                14,
                '#334155',
                'center',
                false,
                'credential.number'
            ),

            $this->qr(
                785,
                1215,
                105
            ),
        ];

        return $this->template(
            name: $name,
            documentType: $documentType,
            language: $language,
            paperSize: 'a4',
            orientation: 'portrait',
            paperWidth: 210,
            paperHeight: 297,
            paperUnit: 'mm',
            canvasWidth: $width,
            canvasHeight: $height,
            pages: [
                $this->page(
                    'front',
                    $width,
                    $height,
                    $objects
                ),
            ],
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function idCard(
        string $name,
        string $language,
        string $heading,
        string $programLabel,
        string $participantLabel,
        string $expiryLabel,
        string $verificationLabel,
    ): array {
        $width = 1011;
        $height = 638;

        $front = [
            $this->rect(
                0,
                0,
                $width,
                $height,
                '#f8fafc'
            ),

            $this->rect(
                0,
                0,
                $width,
                135,
                '#0f172a'
            ),

            $this->text(
                '{{organization.name}}',
                50,
                48,
                24,
                '#ffffff',
                'left',
                true,
                'organization.name'
            ),

            $this->text(
                $heading,
                50,
                92,
                15,
                '#cbd5e1',
                'left'
            ),

            /*
             * Participant photo placeholder.
             */

            $this->rect(
                55,
                190,
                230,
                280,
                '#e2e8f0',
                '#94a3b8',
                2,
                12
            ),

            $this->text(
                'PHOTO',
                170,
                315,
                22,
                '#64748b',
                'center'
            ),

            $this->text(
                '{{participant.name}}',
                350,
                205,
                34,
                '#0f172a',
                'left',
                true,
                'participant.name'
            ),

            $this->text(
                "{$participantLabel}: {{participant.code}}",
                350,
                285,
                18,
                '#475569',
                'left',
                false,
                'participant.code'
            ),

            $this->text(
                "{$programLabel}:",
                350,
                355,
                16,
                '#64748b',
                'left'
            ),

            $this->text(
                '{{program.title}}',
                350,
                390,
                20,
                '#1d4ed8',
                'left',
                true,
                'program.title'
            ),

            $this->text(
                "{$expiryLabel}: {{credential.expiry_date}}",
                350,
                485,
                16,
                '#475569',
                'left',
                false,
                'credential.expiry_date'
            ),

            $this->text(
                '{{credential.number}}',
                350,
                535,
                15,
                '#64748b',
                'left',
                false,
                'credential.number'
            ),
        ];

        $back = [
            $this->rect(
                0,
                0,
                $width,
                $height,
                '#0f172a'
            ),

            $this->text(
                '{{organization.name}}',
                505,
                70,
                26,
                '#ffffff',
                'center',
                true,
                'organization.name'
            ),

            $this->qr(
                110,
                180,
                230
            ),

            $this->text(
                $verificationLabel,
                620,
                200,
                20,
                '#ffffff',
                'center'
            ),

            $this->text(
                '{{credential.number}}',
                620,
                280,
                20,
                '#f8fafc',
                'center',
                true,
                'credential.number'
            ),

            $this->text(
                '{{credential.verification_url}}',
                620,
                340,
                14,
                '#cbd5e1',
                'center',
                false,
                'credential.verification_url'
            ),

            $this->text(
                '{{program.title}}',
                620,
                415,
                18,
                '#93c5fd',
                'center',
                false,
                'program.title'
            ),

            $this->text(
                'Trust AWAKEN Verified Credential',
                505,
                565,
                13,
                '#94a3b8',
                'center'
            ),
        ];

        return $this->template(
            name: $name,
            documentType: 'id_card',
            language: $language,
            paperSize: 'cr80',
            orientation: 'landscape',
            paperWidth: 85.60,
            paperHeight: 53.98,
            paperUnit: 'mm',
            canvasWidth: $width,
            canvasHeight: $height,
            pages: [
                $this->page(
                    'front',
                    $width,
                    $height,
                    $front
                ),

                $this->page(
                    'back',
                    $width,
                    $height,
                    $back
                ),
            ],
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function badge(
        string $name,
        string $language,
        string $orientation,
        string $heading,
        string $subtitle,
        string $programLabel,
        string $credentialLabel,
    ): array {
        if ($orientation === 'landscape') {
            return $this->landscapeBadge(
                name: $name,
                language: $language,
                heading: $heading,
                subtitle: $subtitle,
                programLabel: $programLabel,
                credentialLabel: $credentialLabel,
            );
        }

        return $this->portraitBadge(
            name: $name,
            language: $language,
            heading: $heading,
            subtitle: $subtitle,
            programLabel: $programLabel,
            credentialLabel: $credentialLabel,
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function portraitBadge(
        string $name,
        string $language,
        string $heading,
        string $subtitle,
        string $programLabel,
        string $credentialLabel,
    ): array {
        $width = 800;
        $height = 1200;

        $objects = [
            $this->rect(
                0,
                0,
                $width,
                $height,
                '#f8fafc'
            ),

            $this->rect(
                50,
                50,
                700,
                1100,
                '#ffffff',
                '#1d4ed8',
                7,
                55
            ),

            $this->rect(
                50,
                50,
                700,
                155,
                '#1e3a8a',
                null,
                0,
                55
            ),

            $this->text(
                '{{organization.name}}',
                400,
                105,
                23,
                '#ffffff',
                'center',
                true,
                'organization.name'
            ),

            $this->text(
                $heading,
                400,
                285,
                30,
                '#1e3a8a',
                'center',
                true
            ),

            $this->text(
                $subtitle,
                400,
                365,
                17,
                '#64748b',
                'center'
            ),

            $this->text(
                '{{participant.name}}',
                400,
                435,
                36,
                '#0f172a',
                'center',
                true,
                'participant.name'
            ),

            $this->text(
                $programLabel,
                400,
                545,
                14,
                '#64748b',
                'center'
            ),

            $this->text(
                '{{program.title}}',
                400,
                590,
                23,
                '#1d4ed8',
                'center',
                true,
                'program.title'
            ),

            $this->qr(
                325,
                735,
                150
            ),

            $this->text(
                "{$credentialLabel}: {{credential.number}}",
                400,
                940,
                14,
                '#64748b',
                'center',
                false,
                'credential.number'
            ),
        ];

        return $this->template(
            name: $name,
            documentType: 'badge',
            language: $language,
            paperSize: 'badge-portrait',
            orientation: 'portrait',
            paperWidth: 800,
            paperHeight: 1200,
            paperUnit: 'px',
            canvasWidth: $width,
            canvasHeight: $height,
            pages: [
                $this->page(
                    'front',
                    $width,
                    $height,
                    $objects
                ),
            ],
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function landscapeBadge(
        string $name,
        string $language,
        string $heading,
        string $subtitle,
        string $programLabel,
        string $credentialLabel,
    ): array {
        $width = 1200;
        $height = 800;

        $objects = [
            $this->rect(
                0,
                0,
                $width,
                $height,
                '#f8fafc'
            ),

            $this->rect(
                45,
                45,
                1110,
                710,
                '#ffffff',
                '#1d4ed8',
                7,
                45
            ),

            $this->rect(
                45,
                45,
                1110,
                125,
                '#1e3a8a',
                null,
                0,
                45
            ),

            $this->text(
                '{{organization.name}}',
                600,
                82,
                22,
                '#ffffff',
                'center',
                true,
                'organization.name'
            ),

            $this->text(
                $heading,
                560,
                220,
                30,
                '#1e3a8a',
                'center',
                true
            ),

            $this->text(
                $subtitle,
                560,
                295,
                17,
                '#64748b',
                'center'
            ),

            $this->text(
                '{{participant.name}}',
                560,
                355,
                36,
                '#0f172a',
                'center',
                true,
                'participant.name'
            ),

            $this->text(
                $programLabel,
                560,
                455,
                14,
                '#64748b',
                'center'
            ),

            $this->text(
                '{{program.title}}',
                560,
                495,
                23,
                '#1d4ed8',
                'center',
                true,
                'program.title'
            ),

            $this->qr(
                930,
                470,
                145
            ),

            $this->text(
                "{$credentialLabel}: {{credential.number}}",
                560,
                650,
                14,
                '#64748b',
                'center',
                false,
                'credential.number'
            ),
        ];

        return $this->template(
            name: $name,
            documentType: 'badge',
            language: $language,
            paperSize: 'badge-landscape',
            orientation: 'landscape',
            paperWidth: 1200,
            paperHeight: 800,
            paperUnit: 'px',
            canvasWidth: $width,
            canvasHeight: $height,
            pages: [
                $this->page(
                    'front',
                    $width,
                    $height,
                    $objects
                ),
            ],
        );
    }

    /**
     * @param array<int, array<string, mixed>> $pages
     *
     * @return array<string, mixed>
     */
    private function template(
        string $name,
        string $documentType,
        string $language,
        string $paperSize,
        string $orientation,
        float $paperWidth,
        float $paperHeight,
        string $paperUnit,
        int $canvasWidth,
        int $canvasHeight,
        array $pages,
    ): array {
        return [
            'name' =>
                $name,

            'document_type' =>
                $documentType,

            'language' =>
                $language,

            'paper_size' =>
                $paperSize,

            'orientation' =>
                $orientation,

            'paper_width' =>
                $paperWidth,

            'paper_height' =>
                $paperHeight,

            'paper_unit' =>
                $paperUnit,

            'canvas' => [
                'schema_version' =>
                    2,

                'engine' =>
                    'fabric',

                'engine_version' =>
                    self::ENGINE_VERSION,

                'document_type' =>
                    $documentType,

                'language' =>
                    $language,

                'paper' => [
                    'preset' =>
                        $paperSize,

                    'width' =>
                        $paperWidth,

                    'height' =>
                        $paperHeight,

                    'unit' =>
                        $paperUnit,

                    'orientation' =>
                        $orientation,
                ],

                /*
                 * Active-page compatibility.
                 */

                'canvas_width' =>
                    $canvasWidth,

                'canvas_height' =>
                    $canvasHeight,

                'version' =>
                    '7.0.0',

                'background' =>
                    '#ffffff',

                'objects' =>
                    $pages[0]['objects'],

                /*
                 * V2 authoritative pages.
                 */

                'pages' =>
                    $pages,
            ],
        ];
    }

    /**
     * @param array<int, array<string, mixed>> $objects
     *
     * @return array<string, mixed>
     */
    private function page(
        string $key,
        int $width,
        int $height,
        array $objects,
    ): array {
        return [
            'key' =>
                $key,

            'canvas_width' =>
                $width,

            'canvas_height' =>
                $height,

            'version' =>
                '7.0.0',

            'background' =>
                '#ffffff',

            'objects' =>
                $objects,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function text(
        string $text,
        float $left,
        float $top,
        float $fontSize,
        string $fill,
        string $align = 'left',
        bool $bold = false,
        ?string $variable = null,
    ): array {
        $originX =
            $align === 'center'
                ? 'center'
                : 'left';

        return [
            'type' =>
                'IText',

            'version' =>
                '7.0.0',

            'originX' =>
                $originX,

            'originY' =>
                'top',

            'left' =>
                $left,

            'top' =>
                $top,

            'width' =>
                500,

            'height' =>
                $fontSize * 1.25,

            'fill' =>
                $fill,

            'stroke' =>
                null,

            'strokeWidth' =>
                1,

            'scaleX' =>
                1,

            'scaleY' =>
                1,

            'angle' =>
                0,

            'opacity' =>
                1,

            'visible' =>
                true,

            'fontFamily' =>
                'Arial',

            'fontWeight' =>
                $bold
                    ? 'bold'
                    : 'normal',

            'fontSize' =>
                $fontSize,

            'text' =>
                $text,

            'textAlign' =>
                $align,

            'lineHeight' =>
                1.16,

            'charSpacing' =>
                0,

            'styles' =>
                [],

            'awakenType' =>
                $variable !== null
                    ? 'variable'
                    : 'text',

            'awakenVariable' =>
                $variable,

            'awakenProtected' =>
                false,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function rect(
        float $left,
        float $top,
        float $width,
        float $height,
        string $fill,
        ?string $stroke = null,
        float $strokeWidth = 0,
        float $radius = 0,
    ): array {
        return [
            'type' =>
                'Rect',

            'version' =>
                '7.0.0',

            'originX' =>
                'left',

            'originY' =>
                'top',

            'left' =>
                $left,

            'top' =>
                $top,

            'width' =>
                $width,

            'height' =>
                $height,

            'fill' =>
                $fill,

            'stroke' =>
                $stroke,

            'strokeWidth' =>
                $strokeWidth,

            'scaleX' =>
                1,

            'scaleY' =>
                1,

            'angle' =>
                0,

            'opacity' =>
                1,

            'visible' =>
                true,

            'rx' =>
                $radius,

            'ry' =>
                $radius,

            'awakenType' =>
                'design',

            'awakenProtected' =>
                false,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function qr(
        float $left,
        float $top,
        float $size,
    ): array {
        /*
         * Editable verification QR placeholder.
         *
         * Credential issuance replaces this
         * object with the actual QR image.
         */

        return [
            'type' =>
                'Rect',

            'version' =>
                '7.0.0',

            'originX' =>
                'left',

            'originY' =>
                'top',

            'left' =>
                $left,

            'top' =>
                $top,

            'width' =>
                $size,

            'height' =>
                $size,

            'fill' =>
                '#ffffff',

            'stroke' =>
                '#0f172a',

            'strokeWidth' =>
                3,

            'scaleX' =>
                1,

            'scaleY' =>
                1,

            'angle' =>
                0,

            'opacity' =>
                1,

            'visible' =>
                true,

            'awakenType' =>
                'verification-qr',

            'awakenVariable' =>
                'credential.verification_url',

            'awakenProtected' =>
                true,
        ];
    }
}