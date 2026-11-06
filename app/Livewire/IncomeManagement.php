<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Income;
use App\Models\Project;

class IncomeManagement extends Component
{
    use WithPagination;

    public $showCreateForm = false;
    public $showEditForm = false;
    public $showDeleteConfirm = false;
    public $editingIncome = null;
    public $deleteIncomeId = null;
    public $isLoading = false;
    public $isEdit = false;

    // Form fields
    public $project_id;
    public $name;
    public $description;
    public $type = 'project_payment';
    public $amount;
    public $date;
    public $due_date;
    public $invoice_number;
    public $payment_method;
    public $status = 'pending';
    public $received_amount = 0;
    public $received_date;
    public $reference_number;
    public $notes;

    // Search and filters
    public $search = '';
    public $typeFilter = '';
    public $statusFilter = '';
    public $projectFilter = '';
    public $sortBy = 'date';
    public $sortDirection = 'desc';
    public $perPage = 10;

    protected $rules = [
        'project_id' => 'required|exists:projects,id',
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'type' => 'required|string',
        'amount' => 'required|numeric|min:0',
        'date' => 'required|date',
        'due_date' => 'nullable|date|after_or_equal:date',
        'invoice_number' => 'nullable|string|max:255',
        'payment_method' => 'nullable|string',
        'status' => 'required|in:pending,invoiced,partial,received,overdue,cancelled',
        'received_amount' => 'nullable|numeric|min:0|lte:amount',
        'received_date' => 'nullable|date',
        'reference_number' => 'nullable|string|max:255',
        'notes' => 'nullable|string'
    ];

    public function openCreateForm()
    {
        $this->resetForm();
        $this->showCreateForm = true;
    }

    public function closeCreateForm()
    {
        $this->showCreateForm = false;
        $this->isEdit = false;
        $this->resetForm();
    }

    public function openEditForm($incomeId)
    {
        $this->editingIncome = Income::findOrFail($incomeId);
        $this->project_id = $this->editingIncome->project_id;
        $this->name = $this->editingIncome->name;
        $this->description = $this->editingIncome->description;
        $this->type = $this->editingIncome->type;
        $this->amount = $this->editingIncome->amount;
        $this->date = $this->editingIncome->date->format('Y-m-d');
        $this->due_date = $this->editingIncome->due_date ? $this->editingIncome->due_date->format('Y-m-d') : '';
        $this->invoice_number = $this->editingIncome->invoice_number;
        $this->payment_method = $this->editingIncome->payment_method;
        $this->status = $this->editingIncome->status;
        $this->received_amount = $this->editingIncome->received_amount;
        $this->received_date = $this->editingIncome->received_date ? $this->editingIncome->received_date->format('Y-m-d') : '';
        $this->reference_number = $this->editingIncome->reference_number;
        $this->notes = $this->editingIncome->notes;
        $this->showEditForm = true;
        $this->isEdit = true;
    }

    public function closeEditForm()
    {
        $this->showEditForm = false;
        $this->editingIncome = null;
        $this->isEdit = false;
        $this->resetForm();
    }

    public function createIncome()
    {
        $this->isLoading = true;

        try {
            $this->validate();

            Income::create([
                'project_id' => $this->project_id,
                'name' => $this->name,
                'description' => $this->description,
                'type' => $this->type,
                'amount' => $this->amount,
                'date' => $this->date,
                'due_date' => $this->due_date,
                'invoice_number' => $this->invoice_number,
                'payment_method' => $this->payment_method,
                'status' => $this->status,
                'received_amount' => $this->received_amount ?? 0,
                'received_date' => $this->received_date,
                'reference_number' => $this->reference_number,
                'notes' => $this->notes
            ]);

            $this->dispatch('show-toast', ['message' => 'Income record created successfully!', 'type' => 'success']);
            $this->closeCreateForm();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', ['message' => 'Error creating income record. Please try again.', 'type' => 'error']);
        } finally {
            $this->isLoading = false;
        }
    }

    public function updateIncome()
    {
        $this->isLoading = true;

        try {
            $this->validate();

            $this->editingIncome->update([
                'project_id' => $this->project_id,
                'name' => $this->name,
                'description' => $this->description,
                'type' => $this->type,
                'amount' => $this->amount,
                'date' => $this->date,
                'due_date' => $this->due_date,
                'invoice_number' => $this->invoice_number,
                'payment_method' => $this->payment_method,
                'status' => $this->status,
                'received_amount' => $this->received_amount ?? 0,
                'received_date' => $this->received_date,
                'reference_number' => $this->reference_number,
                'notes' => $this->notes
            ]);

            $this->dispatch('show-toast', ['message' => 'Income record updated successfully!', 'type' => 'success']);
            $this->closeEditForm();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', ['message' => 'Error updating income record. Please try again.', 'type' => 'error']);
        } finally {
            $this->isLoading = false;
        }
    }

    public function confirmDelete($id)
    {
        $this->deleteIncomeId = $id;
        $this->dispatch('show-delete-modal');
    }

    public function cancelDelete()
    {
        $this->deleteIncomeId = null;
        $this->showDeleteConfirm = false;
    }

    public function deleteIncome()
    {
        if (!$this->deleteIncomeId) return;

        try {
            Income::findOrFail($this->deleteIncomeId)->delete();
            $this->dispatch('show-toast', ['message' => 'Income record deleted successfully!', 'type' => 'success']);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', ['message' => 'Error deleting income record. It may have associated data.', 'type' => 'error']);
        } finally {
            $this->deleteIncomeId = null;
            $this->dispatch('hide-delete-modal');
        }
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function markAsReceived($incomeId)
    {
        $income = Income::findOrFail($incomeId);
        $income->update([
            'status' => 'received',
            'received_amount' => $income->amount,
            'received_date' => now()
        ]);
        session()->flash('message', 'Income marked as received!');
    }

    public function resetForm()
    {
        $this->project_id = '';
        $this->name = '';
        $this->description = '';
        $this->type = '';
        $this->amount = '';
        $this->date = '';
        $this->due_date = '';
        $this->invoice_number = '';
        $this->payment_method = '';
        $this->status = 'pending';
        $this->received_amount = 0;
        $this->received_date = '';
        $this->reference_number = '';
        $this->notes = '';
        $this->resetErrorBag();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingProjectFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Build query with relationships
        $query = Income::with(['project.client']);

        // Apply filters
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('invoice_number', 'like', '%' . $this->search . '%')
                  ->orWhere('reference_number', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->typeFilter) {
            $query->where('type', $this->typeFilter);
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->projectFilter) {
            $query->where('project_id', $this->projectFilter);
        }

        // Apply sorting
        $query->orderBy($this->sortBy, $this->sortDirection);

        // Paginate results
        $incomes = $query->paginate($this->perPage);

        // Get statistics
        $stats = [
            'total' => Income::count(),
            'pending' => Income::where('status', 'pending')->count(),
            'received' => Income::where('status', 'received')->count(),
            'overdue' => Income::where('due_date', '<', now())
                             ->whereNotIn('status', ['received', 'cancelled'])
                             ->count(),
            'total_amount' => Income::sum('amount'),
            'received_amount' => Income::sum('received_amount'),
            'pending_amount' => Income::where('status', '!=', 'received')->sum('amount') - Income::where('status', '!=', 'received')->sum('received_amount')
        ];

        // Get data for dropdowns
        $projects = Project::with('client')->orderBy('name')->get();

        $typeOptions = [
            // Core types used across the app
            'project_payment'    => 'Project Payment',
            'milestone_payment'  => 'Milestone Payment',
            'progress_payment'   => 'Progress Payment',
            'final_payment'      => 'Final Payment',
            'initial_deposit'    => 'Initial Deposit',
            'advance_payment'    => 'Advance Payment',
            'retention_release'  => 'Retention Release',
            'change_order'       => 'Change Order',
            'bonus'              => 'Bonus',
            'other'              => 'Other',
        ];

        $paymentMethods = [
            'bank_transfer' => 'Bank Transfer',
            'check' => 'Check',
            'cash' => 'Cash',
            'credit_card' => 'Credit Card',
            'online_payment' => 'Online Payment',
            'other' => 'Other'
        ];

        $statusOptions = [
            'pending' => 'Pending',
            'invoiced' => 'Invoiced',
            'partial' => 'Partially Received',
            'received' => 'Fully Received',
            'overdue' => 'Overdue',
            'cancelled' => 'Cancelled'
        ];

        return view('livewire.income-management', [
            'incomes' => $incomes,
            'projects' => $projects,
            'types' => $typeOptions,
            'paymentMethods' => $paymentMethods,
            'statuses' => $statusOptions,
            'stats' => $stats
        ]);
    }
}
