<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Adverse Event Confirmation - {{ $report->reference_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #1e293b;
            line-height: 1.5;
            margin: 0;
            padding: 15px;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #059669;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #0f172a;
            margin: 0;
        }
        .sub-header {
            font-size: 10px;
            color: #059669;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 3px;
        }
        .contact-info {
            font-size: 9px;
            color: #64748b;
            text-align: right;
            line-height: 1.4;
        }
        .ref-box {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 6px;
            padding: 12px;
            text-align: center;
            margin-bottom: 20px;
        }
        .ref-label {
            font-size: 10px;
            color: #059669;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .ref-number {
            font-size: 18px;
            font-family: monospace;
            font-weight: bold;
            color: #0f172a;
            margin-top: 4px;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #059669;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 4px;
            margin-top: 15px;
            margin-bottom: 10px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .data-table th, .data-table td {
            padding: 6px 8px;
            border: 1px solid #e2e8f0;
            text-align: left;
            vertical-align: top;
        }
        .data-table th {
            background-color: #f8fafc;
            font-size: 10px;
            color: #475569;
            font-weight: bold;
            width: 28%;
        }
        .badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-suspected {
            background-color: #fef3c7;
            color: #92400e;
        }
        .badge-serious {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .badge-non-serious {
            background-color: #f1f5f9;
            color: #475569;
        }
        .alert-box {
            background-color: #f8fafc;
            border-left: 3px solid #059669;
            padding: 10px;
            margin-top: 15px;
            font-size: 10px;
            color: #334155;
        }
        .footer {
            margin-top: 30px;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
            font-size: 9px;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td style="vertical-align: middle;">
                <div class="company-name">iGEO Pharmacy &amp; Consulting</div>
                <div class="sub-header">Drug Safety &amp; Pharmacovigilance Unit</div>
            </td>
            <td class="contact-info" style="vertical-align: middle;">
                Dar es Salaam, Tanzania<br>
                Email: info@igeo.co.tz<br>
                Web: https://igeo.co.tz
            </td>
        </tr>
    </table>

    <div class="ref-box">
        <div class="ref-label">Official Adverse Event Submission Receipt</div>
        <div class="ref-number">{{ $report->reference_number }}</div>
        <div style="font-size: 10px; color: #475569; margin-top: 3px;">
            Recorded on: {{ $report->created_at->format('F d, Y \a\t H:i:s T') }}
        </div>
    </div>

    <div class="section-title">1. Reported Medication / Product Details</div>
    <table class="data-table">
        <tr>
            <th>Primary Product Name</th>
            <td style="font-weight: bold; text-transform: uppercase;">{{ $report->primary_product_name }}</td>
        </tr>
        @if(!empty($report->products))
            <tr>
                <th>All Reported Products</th>
                <td>
                    @foreach($report->products as $idx => $prod)
                        <div style="margin-bottom: 4px;">
                            <strong>{{ $idx + 1 }}. {{ $prod['product_name'] ?? 'N/A' }}</strong>
                            <span class="badge {{ ($prod['relationship'] ?? '') === 'Suspected' ? 'badge-suspected' : 'badge-non-serious' }}">
                                {{ $prod['relationship'] ?? 'Suspected' }}
                            </span>
                            @if(!empty($prod['dose']))
                                &bull; {{ $prod['dose'] }} {{ $prod['dose_unit'] ?? '' }}
                            @endif
                            @if(!empty($prod['lot_batch_number']))
                                &bull; Lot: {{ $prod['lot_batch_number'] }}
                            @endif
                        </div>
                    @endforeach
                </td>
            </tr>
        @endif
    </table>

    <div class="section-title">2. Adverse Event / Side Effect Information</div>
    <table class="data-table">
        <tr>
            <th>Reaction Description</th>
            <td style="white-space: pre-line;">{{ $report->reaction_description }}</td>
        </tr>
        <tr>
            <th>Onset Date</th>
            <td>{{ $report->onset_date ?: 'Not specified' }}</td>
        </tr>
        <tr>
            <th>Outcome</th>
            <td><strong>{{ $report->outcome ?: 'Not specified' }}</strong></td>
        </tr>
        <tr>
            <th>Seriousness Classification</th>
            <td>
                @if($report->is_serious)
                    <span class="badge badge-serious">Serious Adverse Event</span>
                    @if(!empty($report->seriousness_criteria))
                        <div style="margin-top: 3px; font-size: 9px; color: #991b1b;">
                            Criteria: {{ implode(', ', $report->seriousness_criteria) }}
                        </div>
                    @endif
                @else
                    <span class="badge badge-non-serious">Non-Serious</span>
                @endif
            </td>
        </tr>
    </table>

    <div class="section-title">3. Reporter &amp; Patient Reference</div>
    <table class="data-table">
        <tr>
            <th>Reporter Type</th>
            <td>{{ $report->reporter_type }}</td>
        </tr>
        <tr>
            <th>Reporter Name</th>
            <td>{{ $report->reporter_name ?: 'Anonymous / Confidential' }}</td>
        </tr>
        <tr>
            <th>Patient Details</th>
            <td>
                Initials: {{ $report->patient_initials ?: 'N/A' }} |
                Gender: {{ $report->patient_gender ?: 'N/A' }} |
                Age: {{ $report->patient_age ? $report->patient_age . ' ' . ($report->patient_age_unit ?? 'Years') : 'N/A' }}
            </td>
        </tr>
    </table>

    <div class="alert-box">
        <strong>Important Medical Notice:</strong><br>
        Your report has been successfully recorded in our pharmacovigilance registry for evaluation by our Drug Safety Officer.
        If you or the patient experience worsening or severe symptoms, seek immediate medical attention from a qualified healthcare practitioner.
        <br><br>
        To submit additional medical notes, diagnostic records, or updates, please email <strong>info@igeo.co.tz</strong> quoting Case Reference <strong>{{ $report->reference_number }}</strong>.
    </div>

    <div class="footer">
        iGEO Pharmacy &amp; Consulting &bull; Pharmacovigilance &amp; Drug Safety Department &bull; Generated on {{ $generatedAt ?? now()->toDateTimeString() }}
    </div>
</body>
</html>
