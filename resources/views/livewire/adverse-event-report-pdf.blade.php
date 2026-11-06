<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pharmacovigilance Case Safety Report - {{ $report->reference_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, Helvetica, sans-serif;
            font-size: 10px;
            color: #1e293b;
            line-height: 1.4;
            margin: 0;
            padding: 15px;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #059669;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .company-name {
            font-size: 16px;
            font-weight: bold;
            color: #0f172a;
            margin: 0;
        }
        .sub-header {
            font-size: 9px;
            color: #059669;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 2px;
        }
        .contact-info {
            font-size: 9px;
            color: #64748b;
            text-align: right;
            line-height: 1.3;
        }
        .meta-bar {
            width: 100%;
            background-color: #0f172a;
            color: #ffffff;
            padding: 8px 12px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .meta-ref {
            font-size: 13px;
            font-family: monospace;
            font-weight: bold;
            color: #34d399;
        }
        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #059669;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1.5px solid #059669;
            padding-bottom: 3px;
            margin-top: 14px;
            margin-bottom: 8px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .data-table th, .data-table td {
            padding: 5px 7px;
            border: 1px solid #cbd5e1;
            text-align: left;
            vertical-align: top;
            font-size: 9.5px;
        }
        .data-table th {
            background-color: #f1f5f9;
            color: #334155;
            font-weight: bold;
            width: 26%;
        }
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .products-table th, .products-table td {
            padding: 5px 6px;
            border: 1px solid #cbd5e1;
            font-size: 9px;
            text-align: left;
            vertical-align: middle;
        }
        .products-table th {
            background-color: #f1f5f9;
            color: #334155;
            font-weight: bold;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8.5px;
            font-weight: bold;
        }
        .badge-suspected {
            background-color: #fef3c7;
            color: #92400e;
        }
        .badge-concomitant {
            background-color: #e2e8f0;
            color: #334155;
        }
        .badge-serious {
            background-color: #ef4444;
            color: #ffffff;
        }
        .badge-non-serious {
            background-color: #e2e8f0;
            color: #475569;
        }
        .badge-status {
            background-color: #059669;
            color: #ffffff;
        }
        .two-col-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .two-col-table td {
            width: 50%;
            vertical-align: top;
            padding: 0 5px;
        }
        .two-col-table td:first-child {
            padding-left: 0;
        }
        .two-col-table td:last-child {
            padding-right: 0;
        }
        .notes-box {
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-left: 3px solid #0f172a;
            padding: 8px 10px;
            font-size: 9.5px;
            margin-bottom: 10px;
        }
        .footer {
            margin-top: 25px;
            border-top: 1px solid #cbd5e1;
            padding-top: 8px;
            font-size: 8.5px;
            color: #64748b;
            text-align: center;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td style="vertical-align: middle;">
                <div class="company-name">iGEO Pharmacy &amp; Consulting</div>
                <div class="sub-header">Individual Case Safety Report (ICSR) &bull; Pharmacovigilance</div>
            </td>
            <td class="contact-info" style="vertical-align: middle;">
                Dar es Salaam, Tanzania<br>
                Email: info@igeo.co.tz &bull; Web: https://igeo.co.tz<br>
                Printed: {{ $generatedAt ?? now()->toDateTimeString() }}
            </td>
        </tr>
    </table>

    <table class="meta-bar">
        <tr>
            <td>
                Case Reference: <span class="meta-ref">{{ $report->reference_number }}</span>
            </td>
            <td style="text-align: center;">
                Status: <span class="badge badge-status">{{ $report->status_label }}</span>
            </td>
            <td style="text-align: right;">
                @if($report->is_serious)
                    <span class="badge badge-serious">SERIOUS ADVERSE EVENT</span>
                @else
                    <span class="badge badge-non-serious">Non-Serious</span>
                @endif
                &nbsp;&bull; Submitted: {{ $report->created_at->format('Y-m-d H:i') }}
            </td>
        </tr>
    </table>

    <!-- 1. Products Table -->
    <div class="section-title">1. Reported Medication(s) &amp; Suspected Product(s)</div>
    @if(!empty($report->products))
        <table class="products-table">
            <thead>
                <tr>
                    <th style="width: 25%;">Product Name</th>
                    <th style="width: 14%;">Role</th>
                    <th style="width: 16%;">Dose &amp; Frequency</th>
                    <th style="width: 15%;">Route / Form</th>
                    <th style="width: 16%;">Start / End Date</th>
                    <th style="width: 14%;">Batch / Lot</th>
                </tr>
            </thead>
            <tbody>
                @foreach($report->products as $p)
                    <tr>
                        <td><strong>{{ $p['product_name'] ?? 'N/A' }}</strong></td>
                        <td>
                            <span class="badge {{ ($p['relationship'] ?? '') === 'Suspected' ? 'badge-suspected' : 'badge-concomitant' }}">
                                {{ $p['relationship'] ?? 'Suspected' }}
                            </span>
                        </td>
                        <td>
                            {{ trim(($p['dose'] ?? '') . ' ' . ($p['dose_unit'] ?? '')) ?: '-' }}
                            @if(!empty($p['frequency']))<br><span style="color: #64748b;">({{ $p['frequency'] }})</span>@endif
                        </td>
                        <td>
                            {{ $p['route'] ?? '-' }}<br>
                            <span style="color: #64748b;">{{ $p['formulation'] ?? '' }}</span>
                        </td>
                        <td>
                            From: {{ $p['start_date'] ?? '-' }}<br>
                            To: {{ $p['end_date'] ?? '-' }}
                        </td>
                        <td>{{ $p['lot_batch_number'] ?? '-' }}</td>
                    </tr>
                    @if(!empty($p['indication']) || !empty($p['action_taken']))
                        <tr>
                            <td colspan="6" style="background-color: #f8fafc; font-size: 8.5px; color: #475569;">
                                @if(!empty($p['indication']))
                                    <strong>Indication:</strong> {{ $p['indication'] }} &nbsp;&bull;&nbsp;
                                @endif
                                @if(!empty($p['action_taken']))
                                    <strong>Action Taken:</strong> {{ $p['action_taken'] }}
                                    @if(!empty($p['action_impact'])) ({{ $p['action_impact'] }}) @endif
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @else
        <table class="data-table">
            <tr>
                <th>Primary Product</th>
                <td>{{ $report->primary_product_name }}</td>
            </tr>
        </table>
    @endif

    <!-- 2. Adverse Reaction -->
    <div class="section-title">2. Adverse Reaction &amp; Clinical Course</div>
    <table class="data-table">
        <tr>
            <th>Reaction Description</th>
            <td style="white-space: pre-line;"><strong>{{ $report->reaction_description }}</strong></td>
        </tr>
        @if($report->symptom_evolution)
            <tr>
                <th>Symptom Evolution</th>
                <td style="white-space: pre-line;">{{ $report->symptom_evolution }}</td>
            </tr>
        @endif
        <tr>
            <th>Dates &amp; Outcome</th>
            <td>
                Onset: <strong>{{ $report->onset_date ?: 'Unspecified' }}</strong> &nbsp;|&nbsp;
                Resolution: <strong>{{ $report->resolution_date ?: 'Unspecified' }}</strong> &nbsp;|&nbsp;
                Outcome: <strong>{{ $report->outcome ?: 'Unspecified' }}</strong>
            </td>
        </tr>
        <tr>
            <th>Seriousness Criteria</th>
            <td>
                @if($report->is_serious)
                    <span class="badge badge-serious">Serious</span> &mdash;
                    {{ !empty($report->seriousness_criteria) ? implode('; ', $report->seriousness_criteria) : 'Yes' }}
                @else
                    <span class="badge badge-non-serious">Non-Serious</span>
                @endif
            </td>
        </tr>
        @if($report->treatment_needed === 'Yes' || $report->treatment_description || $report->hcp_name)
            <tr>
                <th>Medical Treatment &amp; HCP</th>
                <td>
                    Treatment Required: <strong>{{ $report->treatment_needed ?: 'No' }}</strong><br>
                    @if($report->treatment_description)
                        Details: {{ $report->treatment_description }}<br>
                    @endif
                    @if($report->hcp_name)
                        HCP Name: {{ $report->hcp_name }} &bull; Contact: {{ $report->hcp_contact ?: 'N/A' }}
                    @endif
                </td>
            </tr>
        @endif
    </table>

    <!-- 3. Patient & Reporter Details (Two-column layout) -->
    <table class="two-col-table">
        <tr>
            <td>
                <div class="section-title">3. Patient Information</div>
                <table class="data-table">
                    <tr>
                        <th style="width: 38%;">Patient Initials</th>
                        <td>{{ $report->patient_initials ?: 'Not provided' }}</td>
                    </tr>
                    <tr>
                        <th>Gender / Age</th>
                        <td>
                            {{ $report->patient_gender ?: 'Not provided' }} /
                            {{ $report->patient_age ? $report->patient_age . ' ' . ($report->patient_age_unit ?? 'Years') : 'Age not provided' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Medical History</th>
                        <td>{{ $report->ongoing_medical_conditions ?: 'None reported' }}</td>
                    </tr>
                    <tr>
                        <th>Concomitant Meds</th>
                        <td>{{ $report->concomitant_medications_notes ?: 'None listed' }}</td>
                    </tr>
                </table>
            </td>
            <td>
                <div class="section-title">4. Reporter Information</div>
                <table class="data-table">
                    <tr>
                        <th style="width: 38%;">Reporter Role</th>
                        <td><strong>{{ $report->reporter_type }}</strong></td>
                    </tr>
                    <tr>
                        <th>Reporter Name</th>
                        <td>{{ $report->reporter_name ?: 'Anonymous' }}</td>
                    </tr>
                    <tr>
                        <th>Contact Email</th>
                        <td>{{ $report->reporter_email ?: '-' }}</td>
                    </tr>
                    <tr>
                        <th>Contact Phone</th>
                        <td>{{ $report->reporter_phone ?: '-' }}</td>
                    </tr>
                    <tr>
                        <th>Location</th>
                        <td>{{ $report->reporter_location ?: 'Tanzania' }}</td>
                    </tr>
                    <tr>
                        <th>Follow-up Consent</th>
                        <td>
                            @if($report->consent_to_contact)
                                <span style="color: #059669; font-weight: bold;">Consented to Drug Safety Follow-up</span>
                            @else
                                <span style="color: #d97706; font-weight: bold;">Declined Follow-up Contact</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- 5. Internal Drug Safety Review -->
    <div class="section-title">5. Internal Pharmacovigilance Review &amp; Assessment</div>
    <div class="notes-box">
        <div style="font-size: 9px; color: #64748b; margin-bottom: 5px;">
            <strong>Reviewer:</strong> {{ $report->reviewer?->name ?? 'Unassigned' }} &nbsp;&bull;&nbsp;
            <strong>Reviewed At:</strong> {{ $report->reviewed_at ? $report->reviewed_at->format('Y-m-d H:i') : 'Pending Review' }} &nbsp;&bull;&nbsp;
            <strong>Origin IP:</strong> {{ $report->ip_address ?: '-' }}
        </div>
        <strong>Reviewer Notes / Causality Evaluation:</strong><br>
        <div style="margin-top: 3px; white-space: pre-line; color: #0f172a;">
            {{ $report->admin_notes ?: 'No reviewer notes recorded yet.' }}
        </div>
    </div>

    <div class="footer">
        CONFIDENTIAL MEDICAL DOCUMENT &bull; iGEO Pharmacy &amp; Consulting Pharmacovigilance &bull; Regulated under TMDA Guidelines &bull; Reference: {{ $report->reference_number }}
    </div>
</body>
</html>
