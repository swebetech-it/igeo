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
        .progress-bar {
            width: 100px;
            height: 10px;
            background-color: #e0e0e0;
            border-radius: 5px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background-color: #4285f4;
        }
        .status-badge {
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }
        .status-active {
            background-color: #cce5ff;
            color: #004085;
        }
        .status-delayed {
            background-color: #f8d7da;
            color: #721c24;
        }
        .profit-positive {
            color: #28a745;
        }
        .profit-negative {
            color: #dc3545;
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

    @if($reportType == 'progress')
        <table>
            <thead>
                <tr>
                    <th>Project</th>
                    <th>Budget</th>
                    <th>Actual Cost</th>
                    <th>Income</th>
                    <th>Progress</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportData as $project)
                    <tr>
                        <td>{{ $project['name'] }}</td>
                        <td class="text-right">${{ number_format($project['budget'], 2) }}</td>
                        <td class="text-right">${{ number_format($project['actual_cost'], 2) }}</td>
                        <td class="text-right">${{ number_format($project['income'], 2) }}</td>
                        <td class="text-center">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ $project['progress_percentage'] }}%"></div>
                            </div>
                            <div>{{ $project['progress_percentage'] }}%</div>
                        </td>
                        <td class="text-center">
                            <span class="status-badge 
                                @if($project['status'] == 'completed') status-completed
                                @elseif($project['status'] == 'active') status-active
                                @elseif($project['status'] == 'delayed') status-delayed
                                @endif">
                                {{ ucfirst($project['status']) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No project data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @elseif($reportType == 'financial')
        <table>
            <thead>
                <tr>
                    <th>Project</th>
                    <th>Total Expenses</th>
                    <th>Total Income</th>
                    <th>Received</th>
                    <th>Profit/Loss</th>
                    <th>Transactions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportData as $data)
                    <tr>
                        <td>{{ $data['project_name'] }}</td>
                        <td class="text-right">${{ number_format($data['total_expenses'], 2) }}</td>
                        <td class="text-right">${{ number_format($data['total_income'], 2) }}</td>
                        <td class="text-right">${{ number_format($data['total_received'], 2) }}</td>
                        <td class="text-right">
                            <span class="@if($data['profit_loss'] >= 0) profit-positive @else profit-negative @endif">
                                ${{ number_format($data['profit_loss'], 2) }}
                            </span>
                        </td>
                        <td class="text-center">
                            {{ $data['expense_count'] }} expenses, {{ $data['income_count'] }} incomes
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No financial data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif

    <div class="footer">
        <p>Confidential - Generated by iGEO Management System</p>
        <p>This report contains proprietary information and should be handled accordingly.</p>
    </div>
</body>
</html>