<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Contract PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .section { margin-bottom: 15px; }
        .section h3 { border-bottom: 1px solid #ccc; padding-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; }
        td, th { padding: 6px; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $contract->title }}</h2>
        <p>Contract Number: {{ $contract->contract_number }}</p>
    </div>

    <div class="section">
        <h3>Overview</h3>
        <p>{{ $contract->description }}</p>
        <p><strong>Type:</strong> {{ ucfirst($contract->type) }}</p>
        <p><strong>Value:</strong> ${{ number_format($contract->value, 2) }}</p>
        <p><strong>Start Date:</strong> {{ $contract->start_date?->format('M d, Y') }} | <strong>End Date:</strong> {{ $contract->end_date?->format('M d, Y') }}</p>
    </div>

    <div class="section">
        <h3>Parties</h3>
        <p><strong>Project:</strong> {{ $contract->project?->name ?? '-' }}</p>
        <p><strong>Client:</strong> {{ $contract->client?->name ?? '-' }}</p>
        <p><strong>Created By:</strong> {{ $contract->creator?->name ?? '-' }}</p>
    </div>

    <div class="section">
        <h3>Terms and Conditions</h3>
        <div>{!! nl2br(e($contract->terms_and_conditions)) !!}</div>
    </div>

    <div class="section">
        <h3>Signatures</h3>
        <p>Signatures Received: {{ $contract->signatures_received }} / {{ $contract->signatures_required }}</p>
        @if($contract->signatures)
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Signed At</th>
                        <th>Signature</th>
                        <th>Comments</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contract->signatures as $sig)
                        <tr>
                            <td>{{ $sig['user_id'] }}</td>
                            <td>{{ $sig['signed_at'] ?? '-' }}</td>
                            <td>
                                @if(isset($sig['signature_data']))
                                    <img src="{{ $sig['signature_data'] }}" alt="Signature" style="height:50px;" />
                                @endif
                            </td>
                            <td>{{ $sig['comments'] ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</body>
</html>
