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

class ProjectReports extends Component
{
    public $selectedProject = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $selectedPeriod = 'all';
    public $projects = [];
    public $reportType = 'progress';

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
    }

    public function downloadPdf()
    {
        $reportData = [];
        
        switch($this->reportType) {
            case 'progress':
                $reportData = $this->getProjectProgressReport();
                $reportTitle = 'Project Progress Report';
                break;
            case 'financial':
                $reportData = $this->getProjectFinancialReport();
                $reportTitle = 'Project Financial Report';
                break;
            default:
                $reportData = $this->getProjectProgressReport();
                $reportTitle = 'Project Report';
        }

        $data = [
            'reportData' => $reportData,
            'reportTitle' => $reportTitle,
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
            'selectedProject' => $this->selectedProject,
            'projects' => $this->projects,
            'generatedAt' => now()->format('Y-m-d H:i:s')
        ];

        $filename = 'project-report-' . now()->format('Y-m-d') . '.pdf';
        $pdf = Pdf::loadView('livewire.project-reports-pdf', $data);

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

    public function getProjectProgressReport()
    {
        $query = Project::query()
            ->with(['expenses', 'incomes'])
            ->when($this->selectedProject, function($q) {
                $q->where('id', $this->selectedProject);
            });

        $projects = $query->get()->map(function($project) {
            return [
                'id' => $project->id,
                'name' => $project->name,
                'budget' => $project->budget,
                'actual_cost' => $project->total_expenses,
                'income' => $project->total_income,
                'progress_percentage' => $project->progress_percentage,
                'status' => $project->status,
                'start_date' => $project->start_date,
                'end_date' => $project->end_date,
                'completion_date' => $project->completion_date,
                'project_manager' => $project->project_manager,
            ];
        });

        return $projects;
    }

    public function getProjectFinancialReport()
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

        $incomeQuery = Income::query()
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

        // Group by project
        $expensesByProject = $expenseQuery->select(
            'project_id',
            DB::raw('SUM(amount) as total_expenses'),
            DB::raw('COUNT(*) as expense_count')
        )->groupBy('project_id')->get();

        $incomesByProject = $incomeQuery->select(
            'project_id',
            DB::raw('SUM(amount) as total_income'),
            DB::raw('SUM(received_amount) as total_received'),
            DB::raw('COUNT(*) as income_count')
        )->groupBy('project_id')->get();

        // Combine data
        $projectIds = collect([$expensesByProject->pluck('project_id'), $incomesByProject->pluck('project_id')])->flatten()->unique();
        $projects = Project::whereIn('id', $projectIds)->get()->keyBy('id');

        $financialData = [];
        foreach ($projectIds as $projectId) {
            $project = $projects->get($projectId);
            $expenseData = $expensesByProject->firstWhere('project_id', $projectId);
            $incomeData = $incomesByProject->firstWhere('project_id', $projectId);

            $financialData[] = [
                'project_id' => $projectId,
                'project_name' => $project ? $project->name : 'Unknown Project',
                'total_expenses' => $expenseData ? $expenseData->total_expenses : 0,
                'total_income' => $incomeData ? $incomeData->total_income : 0,
                'total_received' => $incomeData ? $incomeData->total_received : 0,
                'profit_loss' => ($incomeData ? $incomeData->total_received : 0) - ($expenseData ? $expenseData->total_expenses : 0),
                'expense_count' => $expenseData ? $expenseData->expense_count : 0,
                'income_count' => $incomeData ? $incomeData->income_count : 0,
            ];
        }

        return $financialData;
    }

    public function render()
    {
        $reportData = [];
        
        switch($this->reportType) {
            case 'progress':
                $reportData = $this->getProjectProgressReport();
                break;
            case 'financial':
                $reportData = $this->getProjectFinancialReport();
                break;
            default:
                $reportData = $this->getProjectProgressReport();
        }

        return view('livewire.project-reports', [
            'reportData' => $reportData,
            'projects' => $this->projects
        ]);
    }
}