<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Credential Certificate</title>
    <style>
        @page {
            margin: 28px;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #0f172a;
            margin: 0;
            padding: 0;
            background: #ffffff;
        }

        .border {
            border: 8px solid #0f4c81;
            padding: 24px;
            min-height: 740px;
        }

        .inner {
            border: 2px solid #cbd5e1;
            min-height: 690px;
            padding: 28px;
            position: relative;
        }

        .topbar {
            display: table;
            width: 100%;
            margin-bottom: 24px;
        }

        .brand {
            display: table-cell;
            vertical-align: middle;
            width: 70%;
        }

        .brand-title {
            font-size: 28px;
            font-weight: bold;
            color: #0f4c81;
            margin: 0;
            line-height: 1.1;
        }

        .brand-subtitle {
            font-size: 12px;
            color: #475569;
            margin-top: 4px;
        }

        .logo {
            display: table-cell;
            vertical-align: top;
            text-align: right;
            width: 30%;
        }

        .logo img {
            max-height: 72px;
            max-width: 180px;
        }

        .eyebrow {
            text-align: center;
            font-size: 12px;
            letter-spacing: 2px;
            color: #64748b;
            text-transform: uppercase;
            margin-top: 18px;
        }

        .title {
            text-align: center;
            font-size: 34px;
            font-weight: bold;
            color: #0f172a;
            margin: 10px 0 8px;
        }

        .line {
            width: 180px;
            height: 2px;
            background: #0f4c81;
            margin: 0 auto 18px;
        }

        .body-copy {
            text-align: center;
            font-size: 16px;
            color: #334155;
            line-height: 1.7;
            margin: 0 auto;
            max-width: 620px;
        }

        .recipient {
            text-align: center;
            font-size: 30px;
            font-weight: bold;
            color: #111827;
            margin: 24px 0 8px;
        }

        .credential-number {
            text-align: center;
            font-size: 13px;
            color: #475569;
            margin-bottom: 18px;
        }

        .details {
            width: 100%;
            margin-top: 22px;
            border-collapse: collapse;
        }

        .details td {
            vertical-align: top;
        }

        .detail-card {
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 14px 16px;
            margin-bottom: 12px;
            background: #f8fafc;
        }

        .label {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }

        .value {
            font-size: 15px;
            font-weight: bold;
            color: #0f172a;
        }

        .footer {
            width: 100%;
            margin-top: 36px;
            display: table;
        }

        .signature {
            display: table-cell;
            width: 45%;
            vertical-align: bottom;
        }

        .signature-line {
            border-top: 1px solid #94a3b8;
            width: 240px;
            margin-bottom: 6px;
        }

        .signature-name {
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
        }

        .signature-role {
            font-size: 11px;
            color: #64748b;
        }

        .qr {
            display: table-cell;
            width: 20%;
            text-align: center;
            vertical-align: bottom;
        }

        .qr img {
            width: 120px;
            height: 120px;
        }

        .verify {
            display: table-cell;
            width: 35%;
            vertical-align: bottom;
            text-align: right;
        }

        .verify-box {
            display: inline-block;
            border: 1px solid #cbd5e1;
            padding: 12px 14px;
            border-radius: 10px;
            background: #f8fafc;
            text-align: left;
            min-width: 230px;
        }

        .verify-title {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 6px;
        }

        .verify-code {
            font-size: 12px;
            color: #0f172a;
            word-break: break-all;
            line-height: 1.4;
        }

        .issued {
            text-align: center;
            font-size: 11px;
            color: #64748b;
            margin-top: 18px;
        }
    </style>
</head>
<body>
    @php
        $logoPath = public_path('images/logo.png');
        $qrPath = $credential->qr_code_path
            ? public_path('storage/' . $credential->qr_code_path)
            : null;
    @endphp

    <div class="border">
        <div class="inner">
            <div class="topbar">
                <div class="brand">
                    <p class="brand-title">Global CyberSafe</p>
                    <div class="brand-subtitle">Trust AWAKEN Credential Certificate</div>
                </div>

                <div class="logo">
                    @if (file_exists($logoPath))
                        <img src="{{ $logoPath }}" alt="Logo">
                    @endif
                </div>
            </div>

            <div class="eyebrow">Certificate of Achievement</div>

            <div class="title">Credential Certificate</div>
            <div class="line"></div>

            <p class="body-copy">
                This certifies that
            </p>

            <div class="recipient">
                {{ $credential->participant->first_name ?? '' }}
                {{ $credential->participant->last_name ?? '' }}
            </div>

            <p class="body-copy">
                has successfully completed the required training program and has been awarded the following credential.
            </p>

            <div class="credential-number">
                Credential Number: <strong>{{ $credential->credential_number }}</strong>
            </div>

            <table class="details">
                <tr>
                    <td style="width: 50%; padding-right: 10px;">
                        <div class="detail-card">
                            <div class="label">Program</div>
                            <div class="value">
                                {{ $credential->program->title ?? 'N/A' }}
                            </div>
                        </div>

                        <div class="detail-card">
                            <div class="label">Credential Type</div>
                            <div class="value">
                                {{ $credential->credential_type }}
                            </div>
                        </div>

                        <div class="detail-card">
                            <div class="label">Status</div>
                            <div class="value">
                                {{ ucfirst($credential->status) }}
                            </div>
                        </div>
                    </td>

                    <td style="width: 50%; padding-left: 10px;">
                        <div class="detail-card">
                            <div class="label">Issued At</div>
                            <div class="value">
                                {{ optional($credential->issued_at)->format('F d, Y') ?? 'Not issued' }}
                            </div>
                        </div>

                        <div class="detail-card">
                            <div class="label">Expires At</div>
                            <div class="value">
                                {{ optional($credential->expires_at)->format('F d, Y') ?? 'No expiry' }}
                            </div>
                        </div>

                        <div class="detail-card">
                            <div class="label">Verification URL</div>
                            <div class="value" style="font-size: 11px; word-break: break-all;">
                                {{ $credential->verification_url ?? url('/verify/' . $credential->verification_code) }}
                            </div>
                        </div>
                    </td>
                </tr>
            </table>

            <div class="footer">
                <div class="signature">
                    <div class="signature-line"></div>
                    <div class="signature-name">Authorized Signatory</div>
                    <div class="signature-role">Global CyberSafe / Trust AWAKEN</div>
                </div>

                <div class="qr">
                    @if ($qrPath && file_exists($qrPath))
                        <img src="{{ $qrPath }}" alt="QR Code">
                    @endif
                </div>

                <div class="verify">
                    <div class="verify-box">
                        <div class="verify-title">Verification Code</div>
                        <div class="verify-code">{{ $credential->verification_code }}</div>
                    </div>
                </div>
            </div>

            <div class="issued">
                Issued by Trust AWAKEN • Global CyberSafe
            </div>
        </div>
    </div>
</body>
</html>