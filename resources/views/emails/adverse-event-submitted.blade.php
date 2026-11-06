<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Adverse Event Report {{ $report->reference_number }}</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; color: #1e293b; background-color: #f8fafc; margin: 0; padding: 24px;">
    <div style="max-width: 680px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0;">
        <div style="background: #102210; color: #ffffff; padding: 20px 24px;">
            <p style="margin: 0; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; color: #13ec13; font-weight: bold;">
                iGEO Pharmacy &amp; Consulting &bull; Pharmacovigilance
            </p>
            <h2 style="margin: 6px 0 0; font-size: 20px;">
                Adverse Event Report: {{ $report->reference_number }}
            </h2>
        </div>

        <div style="padding: 24px; font-size: 14px; line-height: 1.6;">
            @if($report->is_serious)
                <div style="background: #fef2f2; border-left: 4px solid #dc2626; padding: 12px 16px; margin-bottom: 20px; color: #991b1b;">
                    <strong>Seriousness Alert:</strong> This report includes one or more serious event criteria
                    ({{ implode(', ', $report->seriousness_criteria ?? []) }}).
                </div>
            @endif

            <h3 style="font-size: 15px; border-bottom: 1px solid #e2e8f0; padding-bottom: 6px; margin-top: 0;">1. Product Information</h3>
            @foreach($report->products ?? [] as $index => $prod)
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; margin-bottom: 12px;">
                    <strong>#{{ $index + 1 }}: {{ $prod['product_name'] ?? 'N/A' }}</strong>
                    ({{ $prod['relationship'] ?? 'Suspected' }})<br>
                    <span><strong>Indication:</strong> {{ $prod['indication'] ?: 'Not specified' }}</span><br>
                    <span><strong>Formulation / Route:</strong> {{ $prod['formulation'] ?: '-' }} / {{ $prod['route'] ?: '-' }}</span><br>
                    <span><strong>Dose / Frequency:</strong> {{ trim(($prod['dose'] ?? '') . ' ' . ($prod['dose_unit'] ?? '')) ?: '-' }} {{ !empty($prod['other_dosage']) ? '(' . $prod['other_dosage'] . ')' : '' }} &bull; {{ $prod['frequency'] ?: '-' }}</span><br>
                    <span><strong>Action Taken:</strong> {{ $prod['action_taken'] ?: '-' }} {{ !empty($prod['action_impact']) ? '(' . $prod['action_impact'] . ')' : '' }}</span><br>
                    <span><strong>Lot / Batch:</strong> {{ $prod['lot_batch_number'] ?: '-' }}</span>
                </div>
            @endforeach

            <h3 style="font-size: 15px; border-bottom: 1px solid #e2e8f0; padding-bottom: 6px;">2. Side Effect / Adverse Event</h3>
            <p><strong>Description:</strong><br>{{ $report->reaction_description }}</p>
            @if($report->symptom_evolution)
                <p><strong>Symptom Evolution:</strong><br>{{ $report->symptom_evolution }}</p>
            @endif
            <p>
                <strong>Onset:</strong> {{ $report->onset_date ?: 'Not specified' }} &bull;
                <strong>Outcome:</strong> {{ $report->outcome }}<br>
                <strong>Treatment Needed:</strong> {{ $report->treatment_needed ?: 'Not specified' }}
                @if($report->treatment_description) — {{ $report->treatment_description }} @endif
            </p>
            @if($report->hcp_name || $report->hcp_contact)
                <p><strong>Healthcare Provider:</strong> {{ $report->hcp_name ?: '-' }} ({{ $report->hcp_contact ?: 'No contact provided' }})</p>
            @endif

            <h3 style="font-size: 15px; border-bottom: 1px solid #e2e8f0; padding-bottom: 6px;">3. Patient Details</h3>
            <p>
                <strong>Initials:</strong> {{ $report->patient_initials ?: 'Not provided' }} &bull;
                <strong>Gender:</strong> {{ $report->patient_gender ?: 'Not provided' }} &bull;
                <strong>Age:</strong> {{ $report->patient_age ? $report->patient_age . ' ' . ($report->patient_age_unit ?? 'Years') : 'Not provided' }}<br>
                <strong>Ongoing Conditions:</strong> {{ $report->ongoing_medical_conditions ?: 'None reported' }}<br>
                <strong>Other Medications:</strong> {{ $report->concomitant_medications_notes ?: 'None reported' }}
            </p>

            <h3 style="font-size: 15px; border-bottom: 1px solid #e2e8f0; padding-bottom: 6px;">4. Reporter &amp; Consent</h3>
            <p>
                <strong>Reporter Role:</strong> {{ $report->reporter_type }}<br>
                <strong>Name:</strong> {{ $report->reporter_name ?: 'Anonymous' }}<br>
                <strong>Email / Phone:</strong> {{ $report->reporter_email ?: '-' }} / {{ $report->reporter_phone ?: '-' }}<br>
                <strong>Location:</strong> {{ $report->reporter_location ?: '-' }}<br>
                <strong>Follow-up Contact Permitted:</strong> {{ $report->consent_to_contact ? 'Yes' : 'No (Do NOT contact)' }}
            </p>

            <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid #e2e8f0; text-align: center;">
                <a href="{{ url('/admin/adverse-events') }}" style="display: inline-block; background: #13ec13; color: #102210; font-weight: bold; text-decoration: none; padding: 10px 22px; border-radius: 8px;">
                    Review in iGEO Pharmacovigilance Module
                </a>
            </div>
        </div>
    </div>
</body>
</html>
