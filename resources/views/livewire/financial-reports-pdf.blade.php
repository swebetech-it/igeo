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
        .profit-positive {
            color: #28a745;
        }
        .profit-negative {
            color: #dc3545;
        }
        .variance-positive {
            color: #28a745;
        }
        .variance-negative {
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

    @if($reportType == 'budget_vs_actual')
        <table>
            <thead>
                <tr>
                    <th>Project</th>
                    <th>Budget</th>
                    <th>Actual Cost</th>
                    <th>Income</th>
                    <th>Budget Variance</th>
                    <th>Profit/Loss</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportData as $project)
                    <tr>
                        <td>{{ $project['name'] }}</td>
                        <td class="text-right">${{ number_format($project['budget'], 2) }}</td>
                        <td class="text-right">${{ number_format($project['actual_cost'], 2) }}</td>
                        <td class="text-right">${{ number_format($project['income'], 2) }}</td>
                        <td class="text-right">
                            <span class="@if($project['budget_variance'] >= 0) variance-positive @else variance-negative @endif">
                                ${{ number_format($project['budget_variance'], 2) }}
                                ({{ number_format($project['budget_variance_percentage'], 2) }}%)
                            </span>
                        </td>
                        <td class="text-right">
                            <span class="@if($project['profit_loss'] >= 0) profit-positive @else profit-negative @endif">
                                ${{ number_format($project['profit_loss'], 2) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No budget vs actual data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @elseif($reportType == 'cash_flow')
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Income</th>
                    <th>Expenses</th>
                    <th>Net Cash Flow</th>
                    <th>Running Balance</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportData as $data)
                    <tr>
                        <td>{{ $data['date'] }}</td>
                        <td class="text-right">${{ number_format($data['income'], 2) }}</td>
                        <td class="text-right">${{ number_format($data['expenses'], 2) }}</td>
                        <td class="text-right">
                            <span class="@if($data['net_cash_flow'] >= 0) profit-positive @else profit-negative @endif">
                                ${{ number_format($data['net_cash_flow'], 2) }}
                            </span>
                        </td>
                        <td class="text-right">${{ number_format($data['running_balance'], 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No cash flow data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @elseif($reportType == 'expense_breakdown')
        <h3>Expense Breakdown by Category</h3>
        <table>
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Total Amount</th>
                    <th>Transaction Count</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportData['by_category'] as $category)
                    <tr>
                        <td>{{ ucfirst($category->category) }}</td>
                        <td class="text-right">${{ number_format($category->total_amount, 2) }}</td>
                        <td class="text-right">{{ $category->count }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">No expense data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <h3>Expense Breakdown by Project</h3>
        <table>
            <thead>
                <tr>
                    <th>Project</th>
                    <th>Total Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportData['by_project'] as $project)
                    <tr>
                        <td>{{ $project['project_name'] }}</td>
                        <td class="text-right">${{ number_format($project['total_amount'], 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center">No project expense data available</td>
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