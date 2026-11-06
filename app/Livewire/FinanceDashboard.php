<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use App\Models\Expense;
use App\Models\Income;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinanceDashboard extends Component
{
    public $selectedProject = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $selectedPeriod = 'all';

    public function mount()
    {
        $this->dateTo = now()->format('Y-m-d');
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
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

    public function getFinancialMetrics()
    {
        $query = collect();

        // Base queries with date and project filters
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

        $projectQuery = Project::query()
            ->when($this->selectedProject, function($q) {
                $q->where('id', $this->selectedProject);
            });

        // Calculate metrics
        $totalIncome = $incomeQuery->sum('received_amount');
        $pendingIncome = $incomeQuery->where('status', '!=', 'received')->sum('amount') - $incomeQuery->where('status', '!=', 'received')->sum('received_amount');
        $totalExpenses = $expenseQuery->sum('amount');
        $pendingExpenses = $expenseQuery->where('status', 'pending')->sum('amount');
        $profit = $totalIncome - $totalExpenses;
        $profitMargin = $totalIncome > 0 ? ($profit / $totalIncome) * 100 : 0;

        // Get expense breakdown by category
        $expensesByCategory = $expenseQuery->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->get()
            ->pluck('total', 'category');

        // Get income breakdown by type
        $incomesByType = $incomeQuery->select('type', DB::raw('SUM(received_amount) as total'))
            ->groupBy('type')
            ->get()
            ->pluck('total', 'type');

        // Recent transactions
        $recentIncome = Income::with('project')
            ->when($this->selectedProject, function($q) {
                $q->where('project_id', $this->selectedProject);
            })
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();

        $recentExpenses = Expense::with('project')
            ->when($this->selectedProject, function($q) {
                $q->where('project_id', $this->selectedProject);
            })
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();

        // Project performance
        $projects = $projectQuery->with(['expenses', 'incomes'])->get()->map(function($project) {
            return [
                'id' => $project->id,
                'name' => $project->name,
                'budget' => $project->budget,
                'total_income' => $project->total_income,
                'total_expenses' => $project->total_expenses,
                'profit_loss' => $project->profit_loss,
                'profit_margin' => $project->profit_margin,
                'completion_percentage' => $project->completion_percentage,
                'status' => $project->status
            ];
        });

        return compact(
            'totalIncome',
            'pendingIncome',
            'totalExpenses',
            'pendingExpenses',
            'profit',
            'profitMargin',
            'expensesByCategory',
            'incomesByType',
            'recentIncome',
            'recentExpenses',
            'projects'
        );
    }

    public function render()
    {
        $metrics = $this->getFinancialMetrics();
        $projects = Project::orderBy('name')->get();

        return view('livewire.finance-dashboard', array_merge($metrics, ['projectsList' => $projects]));
    }
}
