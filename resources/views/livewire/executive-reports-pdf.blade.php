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
        .summary-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }
        .summary-card {
            flex: 1;
            min-width: 120px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 10px;
            text-align: center;
        }
        .summary-card h3 {
            margin: 0 0 5px 0;
            font-size: 12px;
            color: #495057;
        }
        .summary-card p {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
            color: #212529;
        }
        .profit-positive {
            color: #28a745;
        }
        .profit-negative {
            color: #dc3545;
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
    </div>

    @if($reportType == 'company_performance')
        <div class="summary-cards">
            <div class="summary-card">
                <h3>Total Projects</h3>
                <p>{{ $reportData['summary']['total_projects'] }}</p>
            </div>
            <div class="summary-card">
                <h3>Active Projects</h3>
                <p>{{ $reportData['summary']['active_projects'] }}</p>
            </div>
            <div class="summary-card">
                <h3>Total Income</h3>
                <p>${{ number_format($reportData['summary']['total_income'], 2) }}</p>
            </div>
            <div class="summary-card">
                <h3>Total Expenses</h3>
                <p>${{ number_format($reportData['summary']['total_expenses'], 2) }}</p>
            </div>
            <div class="summary-card">
                <h3>Net Profit</h3>
                <p class="@if($reportData['summary']['net_profit'] >= 0) profit-positive @else profit-negative @endif">
                    ${{ number_format($reportData['summary']['net_profit'], 2) }}
                </p>
            </div>
            <div class="summary-card">
                <h3>Profit Margin</h3>
                <p>{{ number_format($reportData['summary']['profit_margin'], 2) }}%</p>
            </div>
        </div>

        <h3>Top Performing Projects</h3>
        <table>
            <thead>
                <tr>
                    <th>Project</th>
                    <th>Budget</th>
                    <th>Income</th>
                    <th>Expenses</th>
                    <th>Profit/Loss</th>
                    <th>Profit Margin</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportData['top_projects'] as $project)
                    <tr>
                        <td>{{ $project['name'] }}</td>
                        <td class="text-right">${{ number_format($project['budget'], 2) }}</td>
                        <td class="text-right">${{ number_format($project['total_income'], 2) }}</td>
                        <td class="text-right">${{ number_format($project['total_expenses'], 2) }}</td>
                        <td class="text-right">
                            <span class="@if($project['profit_loss'] >= 0) profit-positive @else profit-negative @endif">
                                ${{ number_format($project['profit_loss'], 2) }}
                            </span>
                        </td>
                        <td class="text-right">{{ number_format($project['profit_margin'], 2) }}%</td>
                        <td class="text-center">{{ ucfirst($project['status']) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No project data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <h3>Income by Type</h3>
        <table>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Total</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportData['incomes_by_type'] as $income)
                    <tr>
                        <td>{{ ucfirst(str_replace('_', ' ', $income->type)) }}</td>
                        <td class="text-right">${{ number_format($income->total, 2) }}</td>
                        <td class="text-right">{{ $income->count }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">No income data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <h3>Expenses by Category</h3>
        <table>
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Total</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportData['expenses_by_category'] as $expense)
                    <tr>
                        <td>{{ ucfirst(str_replace('_', ' ', $expense->category)) }}</td>
                        <td class="text-right">${{ number_format($expense->total, 2) }}</td>
                        <td class="text-right">{{ $expense->count }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">No expense data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @elseif($reportType == 'project_profitability')
        <table>
            <thead>
                <tr>
                    <th>Project</th>
                    <th>Budget</th>
                    <th>Income</th>
                    <th>Expenses</th>
                    <th>Profit/Loss</th>
                    <th>Profit Margin</th>
                    <th>Completion</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportData as $project)
                    <tr>
                        <td>{{ $project['name'] }}</td>
                        <td class="text-right">${{ number_format($project['budget'], 2) }}</td>
                        <td class="text-right">${{ number_format($project['total_income'], 2) }}</td>
                        <td class="text-right">${{ number_format($project['total_expenses'], 2) }}</td>
                        <td class="text-right">
                            <span class="@if($project['profit_loss'] >= 0) profit-positive @else profit-negative @endif">
                                ${{ number_format($project['profit_loss'], 2) }}
                            </span>
                        </td>
                        <td class="text-right">{{ number_format($project['profit_margin'], 2) }}%</td>
                        <td class="text-center">{{ number_format($project['completion_percentage'], 2) }}%</td>
                        <td class="text-center">{{ ucfirst($project['status']) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No project profitability data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif

    <div class="footer">
        <p>Confidential - Generated by iGEO Management System</p>
        <p>This report contains proprietary iGEO Pharmacy & Consulting should be handled accordingly.</p>
    </div>
</body>
</html>