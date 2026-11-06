<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use App\Models\Expense;
use App\Models\Income;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class FinancialReports extends Component
{
    public $selectedProject = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $selectedPeriod = 'month';
    public $reportType = 'budget_vs_actual';
    public $projects = [];

    public function mount()
    {
        // Check if user has permission to view reports
        $user = Auth::user();
        if (!$user || !$user->hasRole([\App\Models\User::ROLE_ADMIN, \App\Models\User::ROLE_MANAGER, \App\Models\User::ROLE_FINANCE])) {
            abort(403, 'Unauthorized access to reports');
        }

        $this->dateTo = now()->format('Y-m-d');
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->projects = Project::orderBy('name')->get();
    }

    public function updatedSelectedPeriod()
    {
        switch($this->selectedPeriod) {
            case 'today':
                $this->dateFrom = now()->format('Y-m-d');
                $this->dateTo = now()->format('Y-m-d');
                break;
            case 'week':
                $this->dateFrom = now()->startOfWeek()->format('Y-m-d');
                $this->dateTo = now()->endOfWeek()->format('Y-m-d');
                break;
            case 'month':
                $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
                $this->dateTo = now()->endOfMonth()->format('Y-m-d');
                break;
            case 'quarter':
                $this->dateFrom = now()->startOfQuarter()->format('Y-m-d');
                $this->dateTo = now()->endOfQuarter()->format('Y-m-d');
                break;
            case 'year':
                $this->dateFrom = now()->startOfYear()->format('Y-m-d');
                $this->dateTo = now()->endOfYear()->format('Y-m-d');
                break;
        }
        
        $this->dispatch('refreshReport');
    }

    public function downloadPdf()
    {
        $reportData = [];
        $reportTitle = '';
        
        switch($this->reportType) {
            case 'budget_vs_actual':
                $reportData = $this->getBudgetVsActualReport();
                $reportTitle = 'Budget vs Actual Report';
                break;
            case 'cash_flow':
                $reportData = $this->getCashFlowReport();
                $reportTitle = 'Cash Flow Report';
                break;
            case 'expense_breakdown':
                $reportData = $this->getExpenseBreakdownReport();
                $reportTitle = 'Expense Breakdown Report';
                break;
            default:
                $reportData = $this->getBudgetVsActualReport();
                $reportTitle = 'Financial Report';
        }

        $data = [
            'reportData' => $reportData,
            'reportTitle' => $reportTitle,
            'reportType' => $this->reportType,
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
            'selectedProject' => $this->selectedProject,
            'projects' => $this->projects,
            'generatedAt' => now()->format('Y-m-d H:i:s')
        ];

        $pdf = Pdf::loadView('livewire.financial-reports-pdf', $data);
        return response()->streamDownload(
            fn() => print($pdf->output()),
            'financial-report-' . now()->format('Y-m-d') . '.pdf'
        );
    }

    public function getBudgetVsActualReport()
    {
        $query = Project::query()
            ->with(['expenses', 'incomes'])
            ->when($this->selectedProject, function($q) {
                $q->where('id', $this->selectedProject);
            });

        $projects = $query->get()->map(function($project) {
            $budget = $project->budget ?? 0;
            $actualCost = $project->total_expenses;
            $income = $project->total_income;
            
            return [
                'id' => $project->id,
                'name' => $project->name,
                'budget' => $budget,
                'actual_cost' => $actualCost,
                'income' => $income,
                'budget_variance' => $budget - $actualCost,
                'budget_variance_percentage' => $budget > 0 ? (($budget - $actualCost) / $budget) * 100 : 0,
                'profit_loss' => $income - $actualCost,
            ];
        });

        return $projects;
    }

    public function getCashFlowReport()
    {
        $expenseQuery = Expense::query()
            ->when($this->selectedProject, function($q) {
                $q->where('project_id', $this->selectedProject);
            })
            ->when($this->dateFrom, function($q) {
                $q->whereDate('date', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function($q) {
                $q->whereDate('date', '<=', $this->dateTo);
            });

        $incomeQuery = Income::query()
            ->when($this->selectedProject, function($q) {
                $q->where('project_id', $this->selectedProject);
            })
            ->when($this->dateFrom, function($q) {
                $q->whereDate('date', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function($q) {
                $q->whereDate('date', '<=', $this->dateTo);
            });

        // Group by date for cash flow
        $expensesByDate = $expenseQuery->select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(amount) as total_expenses')
        )->groupBy(DB::raw('DATE(date)'))->orderBy('date')->get();

        $incomesByDate = $incomeQuery->select(
            DB::raw('DATE(date) as date'),
            DB::raw('SUM(received_amount) as total_income')
        )->groupBy(DB::raw('DATE(date)'))->orderBy('date')->get();

        // Combine data by date
        $dates = collect([$expensesByDate->pluck('date'), $incomesByDate->pluck('date')])->flatten()->unique()->sort();

        $cashFlowData = [];
        $runningBalance = 0;

        foreach ($dates as $date) {
            $expense = $expensesByDate->firstWhere('date', $date);
            $income = $incomesByDate->firstWhere('date', $date);
            
            $dailyExpense = $expense ? $expense->total_expenses : 0;
            $dailyIncome = $income ? $income->total_income : 0;
            
            $runningBalance += ($dailyIncome - $dailyExpense);
            
            $cashFlowData[] = [
                'date' => $date,
                'income' => $dailyIncome,
                'expenses' => $dailyExpense,
                'net_cash_flow' => $dailyIncome - $dailyExpense,
                'running_balance' => $runningBalance,
            ];
        }

        return $cashFlowData;
    }

    public function getExpenseBreakdownReport()
    {
        $expenseQuery = Expense::query()
            ->with('project')
            ->when($this->selectedProject, function($q) {
                $q->where('project_id', $this->selectedProject);
            })
            ->when($this->dateFrom, function($q) {
                $q->whereDate('date', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function($q) {
                $q->whereDate('date', '<=', $this->dateTo);
            });

        // Group by category
        $expensesByCategory = $expenseQuery->select(
            'category',
            DB::raw('SUM(amount) as total_amount'),
            DB::raw('COUNT(*) as count')
        )->groupBy('category')->get();

        // Group by project
        $expensesByProject = $expenseQuery->select(
            'project_id',
            DB::raw('SUM(amount) as total_amount')
        )->groupBy('project_id')->get();

        $projects = Project::whereIn('id', $expensesByProject->pluck('project_id'))->get()->keyBy('id');

        return [
            'by_category' => $expensesByCategory,
            'by_project' => $expensesByProject->map(function($item) use ($projects) {
                $project = $projects->get($item->project_id);
                return [
                    'project_id' => $item->project_id,
                    'project_name' => $project ? $project->name : 'Unknown Project',
                    'total_amount' => $item->total_amount,
                ];
            }),
        ];
    }

    public function render()
    {
        $reportData = [];
        
        switch($this->reportType) {
            case 'budget_vs_actual':
                $reportData = $this->getBudgetVsActualReport();
                break;
            case 'cash_flow':
                $reportData = $this->getCashFlowReport();
                break;
            case 'expense_breakdown':
                $reportData = $this->getExpenseBreakdownReport();
                break;
            default:
                $reportData = $this->getBudgetVsActualReport();
        }

        return view('livewire.financial-reports', [
            'reportData' => $reportData,
            'projects' => $this->projects
        ]);
    }
}