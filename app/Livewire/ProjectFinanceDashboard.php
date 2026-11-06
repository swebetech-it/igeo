<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use App\Models\Expense;
use App\Models\Income;
use Illuminate\Support\Carbon;

class ProjectFinanceDashboard extends Component
{
    public $project;
    public $showExpenseModal = false;
    public $showIncomeModal = false;
    public $editingExpense = null;
    public $editingIncome = null;
    public $expenseForm = [
        'name' => '', 'amount' => '', 'date' => '', 'status' => 'pending', 'category' => 'materials'
    ];
    public $incomeForm = [
        'name' => '', 'amount' => '', 'date' => '', 'status' => 'pending', 'type' => 'milestone_payment'
    ];

    protected $listeners = [
        'refreshFinance' => '$refresh',
    ];

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    // Expense CRUD
    public function openExpenseModal() { $this->resetExpenseForm(); $this->showExpenseModal = true; }
    public function editExpense($id) {
        $expense = Expense::findOrFail($id);
        $this->editingExpense = $expense;
        $this->expenseForm = $expense->only(['name','amount','date','status','category']);
        $this->showExpenseModal = true;
    }
    public function saveExpense() {
        $data = $this->expenseForm;
        $data['project_id'] = $this->project->id;
        $data['date'] = $data['date'] ?: now();
        $data['category'] = $data['category'] ?? 'materials';
        if ($this->editingExpense) {
            $this->editingExpense->update($data);
        } else {
            Expense::create($data);
        }
        $this->showExpenseModal = false;
        $this->editingExpense = null;
        $this->dispatch('refreshFinance');
    }
    public function confirmDeleteExpense($id) { $this->editingExpense = Expense::findOrFail($id); }
    public function deleteExpense() {
    if ($this->editingExpense) { $this->editingExpense->delete(); }
    $this->editingExpense = null;
    $this->dispatch('refreshFinance');
    }
    public function resetExpenseForm() {
    $this->expenseForm = ['name'=>'','amount'=>'','date'=>'','status'=>'pending','category'=>'materials'];
    $this->editingExpense = null;
    }

    // Income CRUD
    public function openIncomeModal() { $this->resetIncomeForm(); $this->showIncomeModal = true; }
    public function editIncome($id) {
    $income = Income::findOrFail($id);
    $this->editingIncome = $income;
    $this->incomeForm = $income->only(['name','amount','date','status','type']);
    $this->showIncomeModal = true;
    }
    public function saveIncome() {
        $data = $this->incomeForm;
        $data['project_id'] = $this->project->id;
        $data['date'] = $data['date'] ?: now();
        $data['type'] = $data['type'] ?? 'milestone_payment';
        if ($this->editingIncome) {
            $this->editingIncome->update($data);
        } else {
            Income::create($data);
        }
        $this->showIncomeModal = false;
        $this->editingIncome = null;
        $this->dispatch('refreshFinance');
    }
    public function confirmDeleteIncome($id) { $this->editingIncome = Income::findOrFail($id); }
    public function deleteIncome() {
    if ($this->editingIncome) { $this->editingIncome->delete(); }
    $this->editingIncome = null;
    $this->dispatch('refreshFinance');
    }
    public function resetIncomeForm() {
    $this->incomeForm = ['name'=>'','amount'=>'','date'=>'','status'=>'pending','type'=>'milestone_payment'];
    $this->editingIncome = null;
    }

    public function render()
    {
        $project = $this->project->fresh(['expenses', 'incomes', 'client']);
        return view('livewire.project-finance-dashboard', compact('project'));
    }
}
