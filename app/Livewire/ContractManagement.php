<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Contract;
use App\Models\ContractSignatureEvent;
use App\Models\Project;
use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ContractManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $contract_id;
    public $title;
    public $description;
    public $project_id;
    public $client_id;
    public $value;
    public $start_date;
    public $end_date;
    public $type = 'service';
    public $terms_and_conditions;
    public $signatures_required = 1;
    public $required_signatories = [];
    public $document;
    public $status_filter = 'all';
    public $search = '';
    
    // Modal states
    public $showCreateForm = false;
    public $showEditForm = false;
    
    // Signature modal
    public $showSignatureModal = false;
    public $contract_to_sign;
    public $signature_data;
    public $signature_comments;

    protected $paginationTheme = 'bootstrap';

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'nullable|exists:projects,id',
            'client_id' => 'nullable|exists:clients,id',
            'value' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'type' => 'required|string',
            'terms_and_conditions' => 'nullable|string',
            'signatures_required' => 'required|integer|min:1',
            'document' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ];
    }

    public function render()
    {
        $query = Contract::with(['project', 'client', 'creator'])
            ->when($this->status_filter !== 'all', function ($q) {
                $q->where('status', $this->status_filter);
            })
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('contract_number', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->latest();

        return view('livewire.contract-management', [
            'contracts' => $query->paginate(10),
            'projects' => Project::all(),
            'clients' => Client::all(),
            'users' => User::all(),
        ])->layout('components.layouts.app');
    }

    public function create()
    {
        $this->resetForm();
        $this->showCreateForm = true;
    }

    public function store()
    {
        $validated = $this->validate();
        
        $documentPath = null;
        if ($this->document) {
            $documentPath = $this->document->store('contracts', 'public');
        }

        $contract = Contract::create([
            ...$validated,
            'document_path' => $documentPath,
            'created_by' => Auth::id(),
            'status' => 'draft',
            'required_signatories' => $this->required_signatories,
        ]);

        session()->flash('success', 'Contract created successfully!');
        $this->resetForm();
        $this->showCreateForm = false;
    }

    public function edit($id)
    {
        $contract = Contract::findOrFail($id);
        
        $this->contract_id = $contract->id;
        $this->title = $contract->title;
        $this->description = $contract->description;
        $this->project_id = $contract->project_id;
        $this->client_id = $contract->client_id;
        $this->value = $contract->value;
        $this->start_date = $contract->start_date?->format('Y-m-d');
        $this->end_date = $contract->end_date?->format('Y-m-d');
        $this->type = $contract->type;
        $this->terms_and_conditions = $contract->terms_and_conditions;
        $this->signatures_required = $contract->signatures_required;
        $this->required_signatories = $contract->required_signatories ?? [];

        $this->showEditForm = true;
    }

    public function update()
    {
        $validated = $this->validate();
        $contract = Contract::findOrFail($this->contract_id);

        // Only allow editing if contract is in draft or pending review
        if (!in_array($contract->status, ['draft', 'pending_review'])) {
            session()->flash('error', 'Cannot edit contract in current status.');
            return;
        }

        $documentPath = $contract->document_path;
        if ($this->document) {
            // Delete old document
            if ($contract->document_path) {
                Storage::disk('public')->delete($contract->document_path);
            }
            $documentPath = $this->document->store('contracts', 'public');
        }

        $contract->update([
            ...$validated,
            'document_path' => $documentPath,
            'required_signatories' => $this->required_signatories,
        ]);

        session()->flash('success', 'Contract updated successfully!');
        $this->resetForm();
        $this->showEditForm = false;
    }

    public function delete($id)
    {
        $contract = Contract::findOrFail($id);
        
        // Delete associated documents
        if ($contract->document_path) {
            Storage::disk('public')->delete($contract->document_path);
        }
        if ($contract->signed_document_path) {
            Storage::disk('public')->delete($contract->signed_document_path);
        }

        $contract->delete();
        session()->flash('success', 'Contract deleted successfully!');
    }

    public function sendForSignature($id)
    {
        $contract = Contract::findOrFail($id);
        
        if ($contract->sendForSignature()) {
            // Here you would send emails/notifications to required signatories
            session()->flash('success', 'Contract sent for signature!');
        } else {
            session()->flash('error', 'Contract cannot be sent for signature.');
        }
    }

    public function openSignatureModal($id)
    {
        $this->contract_to_sign = $id;
        $this->showSignatureModal = true;
    }

    public function closeSignatureModal()
    {
        $this->showSignatureModal = false;
        $this->contract_to_sign = null;
        $this->signature_data = null;
        $this->signature_comments = null;
    }

    public function closeCreateForm()
    {
        $this->showCreateForm = false;
        $this->resetForm();
    }

    public function closeEditForm()
    {
        $this->showEditForm = false;
        $this->resetForm();
    }

    public function signContract()
    {
        $this->validate([
            'signature_data' => 'required|string',
            'signature_comments' => 'nullable|string|max:500',
        ]);

        $contract = Contract::findOrFail($this->contract_to_sign);
        
        if (!$contract->canBeSigned()) {
            session()->flash('error', 'Contract cannot be signed in current status.');
            return;
        }

        if ($contract->hasUserSigned(Auth::id())) {
            session()->flash('error', 'You have already signed this contract.');
            return;
        }

        // Add signature
        $contract->addSignature(Auth::id(), $this->signature_data, $this->signature_comments);

        // Log signature event
        ContractSignatureEvent::create([
            'contract_id' => $contract->id,
            'user_id' => Auth::id(),
            'event_type' => 'signed',
            'signature_data' => $this->signature_data,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'signed_at' => now(),
            'comments' => $this->signature_comments,
        ]);

        session()->flash('success', 'Contract signed successfully!');
        $this->closeSignatureModal();
    }

    public function activateContract($id)
    {
        $contract = Contract::findOrFail($id);
        
        if ($contract->activate()) {
            session()->flash('success', 'Contract activated successfully!');
        } else {
            session()->flash('error', 'Contract must be fully signed before activation.');
        }
    }

    public function completeContract($id)
    {
        $contract = Contract::findOrFail($id);
        
        if ($contract->complete()) {
            session()->flash('success', 'Contract marked as completed!');
        } else {
            session()->flash('error', 'Cannot complete contract.');
        }
    }

    public function downloadContract($id)
    {
        $contract = Contract::with(['project', 'client', 'creator'])->findOrFail($id);
        
        $pdf = Pdf::loadView('contracts.pdf', compact('contract'));
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $contract->contract_number . '.pdf');
    }

    public function viewSignatureHistory($id)
    {
        $this->dispatch('show-signature-history', contractId: $id);
    }

    private function resetForm()
    {
        $this->contract_id = null;
        $this->title = '';
        $this->description = '';
        $this->project_id = null;
        $this->client_id = null;
        $this->value = null;
        $this->start_date = null;
        $this->end_date = null;
        $this->type = 'service';
        $this->terms_and_conditions = '';
        $this->signatures_required = 1;
        $this->required_signatories = [];
        $this->document = null;
        $this->resetValidation();
    }
}

