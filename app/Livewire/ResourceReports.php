<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ResourceReports extends Component
{
    public $selectedProject = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $selectedPeriod = 'month';
    public $reportType = 'material';
    public $projects = [];

    public function mount()
    {
        // Check if user has permission to view reports
        $user = Auth::user();
        if (!$user || !$user->hasRole([\App\Models\User::ROLE_ADMIN, \App\Models\User::ROLE_MANAGER])) {
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
        $reportTitle = '';
        
        switch($this->reportType) {
            case 'material':
                $reportData = $this->getMaterialConsumptionReport();
                $reportTitle = 'Material Consumption Report';
                break;
            case 'equipment':
                $reportData = $this->getEquipmentUsageReport();
                $reportTitle = 'Equipment Usage Report';
                break;
            case 'labor':
                $reportData = $this->getLaborReport();
                $reportTitle = 'Labor Report';
                break;
            default:
                $reportData = $this->getMaterialConsumptionReport();
                $reportTitle = 'Resource Report';
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

        $filename = 'resource-report-' . now()->format('Y-m-d') . '.pdf';
        $pdf = Pdf::loadView('livewire.resource-reports-pdf', $data);

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

    public function getMaterialConsumptionReport()
    {
        $expenseQuery = Expense::query()
            ->where('category', 'materials')
            ->when($this->selectedProject, function($q) {
                $q->where('project_id', $this->selectedProject);
            })
            ->when($this->dateFrom, function($q) {
                $q->whereDate('date', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function($q) {
                $q->whereDate('date', '<=', $this->dateTo);
            });

        // Group by material type (name) and project
        $materialsByProject = $expenseQuery->select(
            'project_id',
            'name as material_name',
            DB::raw('SUM(amount) as total_cost'),
            DB::raw('COUNT(*) as count')
        )->groupBy('project_id', 'name')->get();

        $projectIds = $materialsByProject->pluck('project_id')->unique();
        $projects = Project::whereIn('id', $projectIds)->get()->keyBy('id');

        return $materialsByProject->map(function($item) use ($projects) {
            $project = $projects->get($item->project_id);
            return [
                'project_id' => $item->project_id,
                'project_name' => $project ? $project->name : 'Unknown Project',
                'material_name' => $item->material_name,
                'total_cost' => $item->total_cost,
                'count' => $item->count,
            ];
        });
    }

    public function getEquipmentUsageReport()
    {
        $expenseQuery = Expense::query()
            ->where('category', 'equipment')
            ->when($this->selectedProject, function($q) {
                $q->where('project_id', $this->selectedProject);
            })
            ->when($this->dateFrom, function($q) {
                $q->whereDate('date', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function($q) {
                $q->whereDate('date', '<=', $this->dateTo);
            });

        // Group by equipment type (name) and project
        $equipmentByProject = $expenseQuery->select(
            'project_id',
            'name as equipment_name',
            DB::raw('SUM(amount) as total_cost'),
            DB::raw('COUNT(*) as usage_count')
        )->groupBy('project_id', 'name')->get();

        $projectIds = $equipmentByProject->pluck('project_id')->unique();
        $projects = Project::whereIn('id', $projectIds)->get()->keyBy('id');

        return $equipmentByProject->map(function($item) use ($projects) {
            $project = $projects->get($item->project_id);
            return [
                'project_id' => $item->project_id,
                'project_name' => $project ? $project->name : 'Unknown Project',
                'equipment_name' => $item->equipment_name,
                'total_cost' => $item->total_cost,
                'usage_count' => $item->usage_count,
            ];
        });
    }

    public function getLaborReport()
    {
        $expenseQuery = Expense::query()
            ->where('category', 'labor')
            ->when($this->selectedProject, function($q) {
                $q->where('project_id', $this->selectedProject);
            })
            ->when($this->dateFrom, function($q) {
                $q->whereDate('date', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function($q) {
                $q->whereDate('date', '<=', $this->dateTo);
            });

        // Group by labor type and project
        $laborByProject = $expenseQuery->select(
            'project_id',
            'name as labor_type',
            DB::raw('SUM(amount) as total_cost'),
            DB::raw('COUNT(*) as count')
        )->groupBy('project_id', 'name')->get();

        $projectIds = $laborByProject->pluck('project_id')->unique();
        $projects = Project::whereIn('id', $projectIds)->get()->keyBy('id');

        return $laborByProject->map(function($item) use ($projects) {
            $project = $projects->get($item->project_id);
            return [
                'project_id' => $item->project_id,
                'project_name' => $project ? $project->name : 'Unknown Project',
                'labor_type' => $item->labor_type,
                'total_cost' => $item->total_cost,
                'count' => $item->count,
            ];
        });
    }

    public function render()
    {
        $reportData = [];
        
        switch($this->reportType) {
            case 'material':
                $reportData = $this->getMaterialConsumptionReport();
                break;
            case 'equipment':
                $reportData = $this->getEquipmentUsageReport();
                break;
            case 'labor':
                $reportData = $this->getLaborReport();
                break;
            default:
                $reportData = $this->getMaterialConsumptionReport();
        }

        return view('livewire.resource-reports', [
            'reportData' => $reportData,
            'projects' => $this->projects
        ]);
    }
}