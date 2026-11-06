<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use App\Models\Income;
use App\Models\Expense;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsDashboard extends Component
{
    public $dateRange = '30'; // Default 30 days
    public $selectedProject = 'all';
    
    public function render()
    {
        return view('livewire.analytics-dashboard', [
            'revenueData' => $this->getRevenueData(),
            'revenueForecast' => $this->getRevenueForecast(),
            'profitabilityData' => $this->getProfitabilityData(),
            'resourceUtilization' => $this->getResourceUtilization(),
            'projectMetrics' => $this->getProjectMetrics(),
            'cashFlowData' => $this->getCashFlowData(),
            'expenseBreakdown' => $this->getExpenseBreakdown(),
            'projects' => Project::all(),
        ])->layout('components.layouts.app');
    }

    /**
     * Get revenue data for the selected period
     */
    protected function getRevenueData()
    {
        $startDate = Carbon::now()->subDays($this->dateRange);
        
        $query = Income::where('date', '>=', $startDate);
        
        if ($this->selectedProject !== 'all') {
            $query->where('project_id', $this->selectedProject);
        }
        
        $revenueByDay = $query
            ->select(
                DB::raw('DATE(date) as day'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('day')
            ->orderBy('day')
            ->get();
        
        return [
            'labels' => $revenueByDay->pluck('day')->map(fn($d) => Carbon::parse($d)->format('M d'))->toArray(),
            'data' => $revenueByDay->pluck('total')->toArray(),
            'total' => $revenueByDay->sum('total'),
        ];
    }

    /**
     * Revenue forecasting using linear regression
     */
    protected function getRevenueForecast()
    {
        $historical = Income::where('date', '>=', Carbon::now()->subDays(90))
            ->select(
                DB::raw('DATE(date) as day'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        if ($historical->count() < 7) {
            return [
                'labels' => [],
                'actual' => [],
                'forecast' => [],
                'trend' => 'flat',
                'growthRate' => 0,
            ];
        }

        // Simple linear regression for forecasting
        $n = $historical->count();
        $sumX = 0;
        $sumY = 0;
        $sumXY = 0;
        $sumX2 = 0;

        foreach ($historical as $index => $record) {
            $x = $index;
            $y = (float) $record->total;
            $sumX += $x;
            $sumY += $y;
            $sumXY += ($x * $y);
            $sumX2 += ($x * $x);
        }

        $slope = ($n * $sumXY - $sumX * $sumY) / ($n * $sumX2 - $sumX * $sumX);
        $intercept = ($sumY - $slope * $sumX) / $n;

        // Generate forecast for next 30 days
        $forecastDays = 30;
        $forecastLabels = [];
        $forecastData = [];

        for ($i = 0; $i < $forecastDays; $i++) {
            $futureDate = Carbon::now()->addDays($i);
            $forecastLabels[] = $futureDate->format('M d');
            $forecastData[] = max(0, round($slope * ($n + $i) + $intercept, 2));
        }

        return [
            'labels' => $forecastLabels,
            'actual' => $historical->pluck('total')->toArray(),
            'forecast' => $forecastData,
            'trend' => $slope > 0 ? 'up' : 'down',
            'growthRate' => round(($slope / ($sumY / $n)) * 100, 2),
        ];
    }

    /**
     * Project profitability heat map data
     */
    protected function getProfitabilityData()
    {
        $projects = Project::with(['incomes', 'expenses'])->get();
        
        $profitabilityData = $projects->map(function ($project) {
            $totalIncome = $project->incomes->sum('amount');
            $totalExpenses = $project->expenses->sum('amount');
            $profit = $totalIncome - $totalExpenses;
            $margin = $totalIncome > 0 ? ($profit / $totalIncome) * 100 : 0;

            return [
                'project' => $project->name,
                'income' => $totalIncome,
                'expenses' => $totalExpenses,
                'profit' => $profit,
                'margin' => round($margin, 2),
                'status' => $project->status,
                'budget' => $project->budget ?? 0,
            ];
        })->sortByDesc('margin')->values();

        return [
            'projects' => $profitabilityData,
            'avgMargin' => round($profitabilityData->avg('margin'), 2),
            'totalProfit' => $profitabilityData->sum('profit'),
        ];
    }

    /**
     * Resource utilization metrics
     */
    protected function getResourceUtilization()
    {
        // Employee utilization
        $totalEmployees = Employee::where('status', 'active')->count();
        // Count active employees that have at least one project assignment (safe if relationship missing in some envs)
        $assignedEmployees = 0;
        if (method_exists(Employee::class, 'projects')) {
            $assignedEmployees = Employee::where('status', 'active')
                ->whereHas('projects')
                ->count();
        }

        // Project resource allocation
    $projects = Project::with(method_exists(Project::class,'employees') ? 'employees' : [])->get();
        
        $resourceData = [
            'employeeUtilization' => $totalEmployees > 0 
                ? round(($assignedEmployees / $totalEmployees) * 100, 2) 
                : 0,
            'totalEmployees' => $totalEmployees,
            'assignedEmployees' => $assignedEmployees,
            'availableEmployees' => $totalEmployees - $assignedEmployees,
            'projectAllocation' => $projects->map(fn($p) => [
                'project' => $p->name,
                'employees' => $p->employees->count(),
                'status' => $p->status,
            ])->toArray(),
        ];

        // Budget utilization
        $budgetUtilization = Project::whereNotNull('budget')
            ->get()
            ->map(function ($project) {
                $spent = $project->expenses->sum('amount');
                $budget = $project->budget;
                return [
                    'project' => $project->name,
                    'budget' => $budget,
                    'spent' => $spent,
                    'remaining' => $budget - $spent,
                    'utilization' => $budget > 0 ? round(($spent / $budget) * 100, 2) : 0,
                ];
            });

        $resourceData['budgetUtilization'] = $budgetUtilization;

        return $resourceData;
    }

    /**
     * Overall project metrics
     */
    protected function getProjectMetrics()
    {
        $projects = Project::all();
        
        return [
            'total' => $projects->count(),
            'active' => $projects->where('status', 'active')->count(),
            'completed' => $projects->where('status', 'completed')->count(),
            'onHold' => $projects->where('status', 'on_hold')->count(),
            'delayed' => $projects->where('status', 'delayed')->count(),
            'statusDistribution' => [
                'labels' => ['Active', 'Completed', 'On Hold', 'Delayed'],
                'data' => [
                    $projects->where('status', 'active')->count(),
                    $projects->where('status', 'completed')->count(),
                    $projects->where('status', 'on_hold')->count(),
                    $projects->where('status', 'delayed')->count(),
                ],
            ],
        ];
    }

    /**
     * Cash flow analysis
     */
    protected function getCashFlowData()
    {
        $startDate = Carbon::now()->subDays($this->dateRange);
        
        $incomeByDay = Income::where('date', '>=', $startDate)
            ->select(
                DB::raw('DATE(date) as day'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->keyBy('day');
        
        $expenseByDay = Expense::where('date', '>=', $startDate)
            ->select(
                DB::raw('DATE(date) as day'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->keyBy('day');
        
        // Merge and calculate net cash flow
        $allDays = $incomeByDay->keys()->merge($expenseByDay->keys())->unique()->sort();
        
        $cashFlowData = $allDays->map(function ($day) use ($incomeByDay, $expenseByDay) {
            $income = $incomeByDay->get($day)?->total ?? 0;
            $expense = $expenseByDay->get($day)?->total ?? 0;
            
            return [
                'day' => Carbon::parse($day)->format('M d'),
                'income' => $income,
                'expense' => $expense,
                'net' => $income - $expense,
            ];
        })->values();
        
        return [
            'labels' => $cashFlowData->pluck('day')->toArray(),
            'income' => $cashFlowData->pluck('income')->toArray(),
            'expense' => $cashFlowData->pluck('expense')->toArray(),
            'net' => $cashFlowData->pluck('net')->toArray(),
        ];
    }

    /**
     * Expense breakdown by category
     */
    protected function getExpenseBreakdown()
    {
        $startDate = Carbon::now()->subDays($this->dateRange);
        
        $expenses = Expense::where('date', '>=', $startDate)
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();
        
        return [
            'labels' => $expenses->pluck('category')->toArray(),
            'data' => $expenses->pluck('total')->toArray(),
            'total' => $expenses->sum('total'),
        ];
    }

    public function updatedDateRange()
    {
        // Trigger re-render when date range changes
    }

    public function updatedSelectedProject()
    {
        // Trigger re-render when project selection changes
    }
}
