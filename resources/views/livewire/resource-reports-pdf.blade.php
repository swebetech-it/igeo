<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $reportTitle }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }
        .report-info {
            margin-bottom: 20px;
            font-size: 11px;
        }
        .report-info div {
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            font-size: 10px;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>iGEO - Construction Company</h1>
        <h1>{{ $reportTitle }}</h1>
        <p>Generated on: {{ $generatedAt }}</p>
    </div>

    <div class="report-info">
        <div><strong>Report Period:</strong> 
            @if($dateFrom && $dateTo)
                {{ $dateFrom }} to {{ $dateTo }}
            @else
                All Time
            @endif
        </div>
        @if($selectedProject)
            @php
                $project = $projects->firstWhere('id', $selectedProject);
            @endphp
            <div><strong>Project:</strong> {{ $project ? $project->name : 'Unknown Project' }}</div>
        @else
            <div><strong>Project:</strong> All Projects</div>
        @endif
    </div>

    @if($reportType == 'material')
        <table>
            <thead>
                <tr>
                    <th>Project</th>
                    <th>Material Name</th>
                    <th>Total Cost</th>
                    <th>Usage Count</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportData as $material)
                    <tr>
                        <td>{{ $material['project_name'] }}</td>
                        <td>{{ $material['material_name'] }}</td>
                        <td class="text-right">${{ number_format($material['total_cost'], 2) }}</td>
                        <td class="text-right">{{ $material['count'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No material consumption data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @elseif($reportType == 'equipment')
        <table>
            <thead>
                <tr>
                    <th>Project</th>
                    <th>Equipment Name</th>
                    <th>Total Cost</th>
                    <th>Usage Count</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportData as $equipment)
                    <tr>
                        <td>{{ $equipment['project_name'] }}</td>
                        <td>{{ $equipment['equipment_name'] }}</td>
                        <td class="text-right">${{ number_format($equipment['total_cost'], 2) }}</td>
                        <td class="text-right">{{ $equipment['usage_count'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No equipment usage data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @elseif($reportType == 'labor')
        <table>
            <thead>
                <tr>
                    <th>Project</th>
                    <th>Labor Type</th>
                    <th>Total Cost</th>
                    <th>Usage Count</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportData as $labor)
                    <tr>
                        <td>{{ $labor['project_name'] }}</td>
                        <td>{{ $labor['labor_type'] }}</td>
                        <td class="text-right">${{ number_format($labor['total_cost'], 2) }}</td>
                        <td class="text-right">{{ $labor['count'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No labor data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif

    <div class="footer">
        <p>Confidential - Generated by iGEO Pharmacy & Consulting Management System</p>
        <p>This report contains proprietary information and should be handled accordingly.</p>
    </div>
</body>
</html>