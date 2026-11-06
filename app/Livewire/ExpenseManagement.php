<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Expense;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ExpenseManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $showCreateForm = false;
    public $showEditForm = false;
    public $showDeleteConfirm = false;
    public $editingExpense = null;
    public $deleteExpenseId = null;
    public $isLoading = false;
    public $isEdit = false;

    // Form fields
    public $project_id;
    public $name;
    public $description;
    public $category = 'materials';
    public $amount;
    public $date;
    public $vendor;
    public $invoice_number;
    public $receipt_file;
    public $payment_method;
    public $status = 'pending';
    public $notes;

    // Search and filters
    public $search = '';
    public $categoryFilter = '';
    public $statusFilter = '';
    public $projectFilter = '';
    public $sortBy = 'date';
    public $sortDirection = 'desc';
    public $perPage = 10;

    protected $rules = [
        'project_id' => 'required|exists:projects,id',
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'category' => 'required|string',
        'amount' => 'required|numeric|min:0',
        'date' => 'required|date',
        'vendor' => 'nullable|string|max:255',
        'invoice_number' => 'nullable|string|max:255',
        'receipt_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        'payment_method' => 'nullable|string',
        'status' => 'required|in:pending,approved,paid,rejected',
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

    public function openEditForm($expenseId)
    {
        $this->editingExpense = Expense::findOrFail($expenseId);
        $this->project_id = $this->editingExpense->project_id;
        $this->name = $this->editingExpense->name;
        $this->description = $this->editingExpense->description;
        $this->category = $this->normalizeCategory($this->editingExpense->category);
        $this->amount = $this->editingExpense->amount;
        $this->date = $this->editingExpense->date->format('Y-m-d');
        $this->vendor = $this->editingExpense->vendor;
        $this->invoice_number = $this->editingExpense->invoice_number;
        $this->payment_method = $this->editingExpense->payment_method;
        $this->status = $this->editingExpense->status;
        $this->notes = $this->editingExpense->notes;
        $this->showEditForm = true;
        $this->isEdit = true;
    }

    public function closeEditForm()
    {
        $this->showEditForm = false;
        $this->editingExpense = null;
        $this->isEdit = false;
        $this->resetForm();
    }

    public function createExpense()
    {
        $this->isLoading = true;

        try {
            $this->validate();

            $receiptPath = null;
            if ($this->receipt_file) {
                $receiptPath = $this->receipt_file->store('receipts', 'public');
            }

            Expense::create([
                'project_id' => $this->project_id,
                'name' => $this->name,
                'description' => $this->description,
                'category' => $this->normalizeCategory($this->category),
                'amount' => $this->amount,
                'date' => $this->date,
                'vendor' => $this->vendor,
                'invoice_number' => $this->invoice_number,
                'receipt_file' => $receiptPath,
                'payment_method' => $this->payment_method,
                'status' => $this->status,
                'notes' => $this->notes
            ]);

            $this->dispatch('show-toast', ['message' => 'Expense created successfully!', 'type' => 'success']);
            $this->closeCreateForm();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', ['message' => 'Error creating expense. Please try again.', 'type' => 'error']);
        } finally {
            $this->isLoading = false;
        }
    }

    public function updateExpense()
    {
        $this->isLoading = true;

        try {
            $this->validate();

            $receiptPath = $this->editingExpense->receipt_file;
            if ($this->receipt_file) {
                // Delete old file if exists
                if ($receiptPath) {
                    Storage::disk('public')->delete($receiptPath);
                }
                $receiptPath = $this->receipt_file->store('receipts', 'public');
            }

            $this->editingExpense->update([
                'project_id' => $this->project_id,
                'name' => $this->name,
                'description' => $this->description,
                'category' => $this->normalizeCategory($this->category),
                'amount' => $this->amount,
                'date' => $this->date,
                'vendor' => $this->vendor,
                'invoice_number' => $this->invoice_number,
                'receipt_file' => $receiptPath,
                'payment_method' => $this->payment_method,
                'status' => $this->status,
                'notes' => $this->notes
            ]);

            $this->dispatch('show-toast', ['message' => 'Expense updated successfully!', 'type' => 'success']);
            $this->closeEditForm();
        } catch (\Exception $e) {
            $this->dispatch('show-toast', ['message' => 'Error updating expense. Please try again.', 'type' => 'error']);
        } finally {
            $this->isLoading = false;
        }
    }

    public function confirmDelete($id)
    {
        $this->deleteExpenseId = $id;
        $this->dispatch('show-delete-modal');
    }

    public function cancelDelete()
    {
        $this->deleteExpenseId = null;
        $this->showDeleteConfirm = false;
    }

    public function deleteExpense()
    {
        if (!$this->deleteExpenseId) return;

        try {
            $expense = Expense::findOrFail($this->deleteExpenseId);

            // Delete associated file if exists
            if ($expense->receipt_file) {
                Storage::disk('public')->delete($expense->receipt_file);
            }

            $expense->delete();
            $this->dispatch('show-toast', ['message' => 'Expense deleted successfully!', 'type' => 'success']);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', ['message' => 'Error deleting expense. Please try again.', 'type' => 'error']);
        } finally {
            $this->deleteExpenseId = null;
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

    public function approveExpense($expenseId)
    {
        $expense = Expense::findOrFail($expenseId);
        $expense->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now()
        ]);
        session()->flash('message', 'Expense approved successfully!');
    }

    public function resetForm()
    {
        $this->project_id = '';
        $this->name = '';
        $this->description = '';
        $this->category = '';
        $this->amount = '';
        $this->date = '';
        $this->vendor = '';
        $this->invoice_number = '';
        $this->receipt_file = null;
        $this->payment_method = '';
        $this->status = 'pending';
        $this->notes = '';
        $this->resetErrorBag();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
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

    private function normalizeCategory($value)
    {
        $v = strtolower(($value ?? ''));
        return match($v) {
            'transport', 'transportation' => 'transportation',
            'material', 'materials' => 'materials',
            'labour', 'labor' => 'labor',
            'equip', 'equipment' => 'equipment',
            default => $v
        };
    }

    public function render()
    {
        // Build query with relationships
        $query = Expense::with(['project.client']);

        // Apply filters
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('vendor', 'like', '%' . $this->search . '%')
                  ->orWhere('invoice_number', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->categoryFilter) {
            $query->where('category', $this->categoryFilter);
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
        $expenses = $query->paginate($this->perPage);

        // Get statistics
        $stats = [
            'total' => Expense::count(),
            'pending' => Expense::where('status', 'pending')->count(),
            'approved' => Expense::where('status', 'approved')->count(),
            'paid' => Expense::where('status', 'paid')->count(),
            'total_amount' => Expense::sum('amount') ?: 0,
            'pending_amount' => Expense::where('status', 'pending')->sum('amount') ?: 0,
        ];

        // Get data for dropdowns
        $projects = Project::with('client')->orderBy('name')->get();

        $categoryOptions = [
            'materials' => 'Materials',
            'labor' => 'Labor',
            'equipment' => 'Equipment',
            'transportation' => 'Transportation',
            'permits' => 'Permits & Licenses',
            'utilities' => 'Utilities',
            'insurance' => 'Insurance',
            'office' => 'Office Expenses',
            'other' => 'Other'
        ];

        $paymentMethods = [
            'cash' => 'Cash',
            'check' => 'Check',
            'bank_transfer' => 'Bank Transfer',
            'credit_card' => 'Credit Card',
            'online_payment' => 'Online Payment'
        ];

        $statusOptions = [
            'pending' => 'Pending',
            'approved' => 'Approved',
            'paid' => 'Paid',
            'rejected' => 'Rejected'
        ];

        return view('livewire.expense-management', [
            'expenses' => $expenses,
            'projects' => $projects,
            'categories' => $categoryOptions,
            'paymentMethods' => $paymentMethods,
            'statuses' => $statusOptions,
            'stats' => $stats
        ]);
    }
}
