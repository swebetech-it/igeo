<?php

namespace App\Livewire;

use App\Models\Lead;
use Livewire\Component;
use Livewire\WithPagination;

class LeadManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $perPage = 10;
    
    // UI State
    public $selectedLead = null;
    public $showDetailModal = false;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function viewLead($id)
    {
        $this->selectedLead = Lead::findOrFail($id);
        $this->showDetailModal = true;
    }

    public function updateStatus($id, $status)
    {
        $lead = Lead::findOrFail($id);
        $lead->update(['status' => $status]);
        $this->dispatch('show-toast', ['message' => 'Lead status updated!', 'type' => 'success']);
        
        if ($this->selectedLead && $this->selectedLead->id == $id) {
            $this->selectedLead->refresh();
        }
    }

    public function deleteLead($id)
    {
        Lead::findOrFail($id)->delete();
        $this->dispatch('show-toast', ['message' => 'Lead deleted!', 'type' => 'success']);
        $this->showDetailModal = false;
    }

    public function render()
    {
        $leads = Lead::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('company', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.lead-management', compact('leads'));
    }
}
