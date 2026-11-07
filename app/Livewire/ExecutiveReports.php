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

class ExecutiveReports extends Component
{
    public $dateFrom = '';
    public $dateTo = '';
    public $selectedPeriod = 'quarter';
    public $reportType = 'company_performance';
    public $projects = [];

    public function mount()
    {
        // Check if user has permission to view reports
        $user = Auth::user();
        if (!$user || !$user->hasRole([\App\Models\User::ROLE_ADMIN, \App\Models\User::ROLE_MANAGER])) {
            abort(403, 'Unauthorized access to reports');
        }

        $this->dateTo = now()->format('Y-m-d');
        $this->dateFrom = now()->startOfQuarter()->format('Y-m-d');
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
    }

    public function downloadPdf()
    {
        $reportData = [];
        $reportTitle = '';
        
        switch($this->reportType) {
            case 'company_performance':
                $reportData = $this->getCompanyPerformanceReport();
                $reportTitle = 'Company Performance Report';
                break;
            case 'project_profitability':
                $reportData = $this->getProjectProfitabilityReport();
                $reportTitle = 'Project Profitability Report';
                break;
            default:
                $reportData = $this->getCompanyPerformanceReport();
                $reportTitle = 'Executive Report';
        }

        $data = [
            'reportData' => $reportData,
            'reportTitle' => $reportTitle,
            'reportType' => $this->reportType,
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
            'projects' => $this->projects,
            'generatedAt' => now()->format('Y-m-d H:i:s')
        ];

        $filename = 'executive-report-' . now()->format('Y-m-d') . '.pdf';
        $pdf = Pdf::loadView('livewire.executive-reports-pdf', $data);

        return response()->streamDownload(
            function () use ($pdf) {
                echo $pdf->output();
            },
            $filename,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ],
            'attachment'
        );
    }

    public function getCompanyPerformanceReport()
    {
        // Overall financial metrics
        $totalProjects = Project::count();
        $activeProjects = Project::where('status', '!=', 'completed')->count();
        
        $incomeQuery = Income::query()
            ->when($this->dateFrom, function($q) {
                $q->whereDate('date', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function($q) {
                $q->whereDate('date', '<=', $this->dateTo);
            });

        $expenseQuery = Expense::query()
            ->when($this->dateFrom, function($q) {
                $q->whereDate('date', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function($q) {
                $q->whereDate('date', '<=', $this->dateTo);
            });

        $totalIncome = $incomeQuery->sum('received_amount');
        $totalExpenses = $expenseQuery->sum('amount');
        $netProfit = $totalIncome - $totalExpenses;
        $profitMargin = $totalIncome > 0 ? ($netProfit / $totalIncome) * 100 : 0;

        // Top performing projects
        $topProjects = Project::with(['expenses', 'incomes'])
            ->get()
            ->map(function($project) {
                return [
                    'id' => $project->id,
                    'name' => $project->name,
                    'budget' => $project->budget,
                    'total_income' => $project->total_income,
                    'total_expenses' => $project->total_expenses,
                    'profit_loss' => $project->profit_loss,
                    'profit_margin' => $project->profit_margin,
                    'status' => $project->status,
                ];
            })
            ->sortByDesc('profit_loss')
            ->take(5);

        // Income by type
        $incomesByType = $incomeQuery->select(
            'type',
            DB::raw('SUM(received_amount) as total'),
            DB::raw('COUNT(*) as count')
        )->groupBy('type')->get();

        // Expenses by category
        $expensesByCategory = $expenseQuery->select(
            'category',
            DB::raw('SUM(amount) as total'),
            DB::raw('COUNT(*) as count')
        )->groupBy('category')->get();

        return [
            'summary' => [
                'total_projects' => $totalProjects,
                'active_projects' => $activeProjects,
                'total_income' => $totalIncome,
                'total_expenses' => $totalExpenses,
                'net_profit' => $netProfit,
                'profit_margin' => $profitMargin,
            ],
            'top_projects' => $topProjects,
            'incomes_by_type' => $incomesByType,
            'expenses_by_category' => $expensesByCategory,
        ];
    }

    public function getProjectProfitabilityReport()
    {
        $projects = Project::with(['expenses', 'incomes'])
            ->get()
            ->map(function($project) {
                return [
                    'id' => $project->id,
                    'name' => $project->name,
                    'budget' => $project->budget ?? 0,
                    'total_income' => $project->total_income,
                    'total_expenses' => $project->total_expenses,
                    'profit_loss' => $project->profit_loss,
                    'profit_margin' => $project->profit_margin,
                    'completion_percentage' => $project->completion_percentage,
                    'status' => $project->status,
                ];
            })
            ->sortByDesc('profit_margin');

        return $projects;
    }

    public function render()
    {
        $reportData = [];
        
        switch($this->reportType) {
            case 'company_performance':
                $reportData = $this->getCompanyPerformanceReport();
                break;
            case 'project_profitability':
                $reportData = $this->getProjectProfitabilityReport();
                break;
            default:
                $reportData = $this->getCompanyPerformanceReport();
        }

        return view('livewire.executive-reports', [
            'reportData' => $reportData,
            'projects' => $this->projects
        ]);
    }
}