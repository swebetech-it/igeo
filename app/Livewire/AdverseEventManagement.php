<?php

namespace App\Livewire;

use App\Models\AdverseEventReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdverseEventManagement extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public string $seriousnessFilter = '';
    public int $perPage = 10;

    public ?AdverseEventReport $selectedReport = null;
    public bool $showDetailModal = false;
    public string $adminNotesInput = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingSeriousnessFilter(): void
    {
        $this->resetPage();
    }

    protected function buildFilteredQuery()
    {
        return AdverseEventReport::query()
            ->with('reviewer')
            ->when($this->search, function ($query) {
                $term = '%' . trim($this->search) . '%';
                $query->where(function ($q) use ($term) {
                    $q->where('reference_number', 'like', $term)
                        ->orWhere('primary_product_name', 'like', $term)
                        ->orWhere('reaction_description', 'like', $term)
                        ->orWhere('reporter_name', 'like', $term)
                        ->orWhere('reporter_email', 'like', $term)
                        ->orWhere('reporter_phone', 'like', $term);
                });
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->seriousnessFilter !== '', function ($query) {
                $query->where('is_serious', $this->seriousnessFilter === 'serious');
            })
            ->orderByDesc('created_at');
    }

    public function viewReport(int $id): void
    {
        $this->selectedReport = AdverseEventReport::with('reviewer')->findOrFail($id);
        $this->adminNotesInput = (string) ($this->selectedReport->admin_notes ?? '');
        $this->showDetailModal = true;
    }

    public function closeModal(): void
    {
        $this->showDetailModal = false;
        $this->selectedReport = null;
    }

    public function updateStatus(int $id, string $status): void
    {
        if (!array_key_exists($status, AdverseEventReport::STATUSES)) {
            return;
        }

        $report = AdverseEventReport::findOrFail($id);
        $report->update([
            'status' => $status,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        if ($this->selectedReport && $this->selectedReport->id === $id) {
            $this->selectedReport = $report->fresh(['reviewer']);
        }

        $this->dispatch('show-toast', [
            'message' => "Report {$report->reference_number} status updated.",
            'type' => 'success',
        ]);
    }

    public function saveAdminNotes(): void
    {
        if (!$this->selectedReport) {
            return;
        }

        $this->validate([
            'adminNotesInput' => 'nullable|string|max:5000',
        ]);

        $this->selectedReport->update([
            'admin_notes' => trim($this->adminNotesInput) ?: null,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        $this->selectedReport = $this->selectedReport->fresh(['reviewer']);

        $this->dispatch('show-toast', [
            'message' => 'Pharmacovigilance reviewer notes saved.',
            'type' => 'success',
        ]);
    }

    public function deleteReport(int $id): void
    {
        $report = AdverseEventReport::findOrFail($id);
        $ref = $report->reference_number;
        $report->delete();

        $this->showDetailModal = false;
        $this->selectedReport = null;

        $this->dispatch('show-toast', [
            'message' => "Report {$ref} deleted.",
            'type' => 'success',
        ]);
    }

    public function downloadPdf(?int $id = null)
    {
        $reportId = $id ?? $this->selectedReport?->id;
        if (!$reportId) {
            return null;
        }

        $report = AdverseEventReport::with('reviewer')->findOrFail($reportId);

        $pdf = Pdf::loadView('livewire.adverse-event-report-pdf', [
            'report' => $report,
            'generatedAt' => now()->format('Y-m-d H:i:s'),
        ]);

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'adverse-event-' . $report->reference_number . '.pdf'
        );
    }

    public function exportCsv(): StreamedResponse
    {
        $reports = $this->buildFilteredQuery()->get();
        $filename = 'igeo-adverse-events-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($reports) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Reference Number',
                'Submitted At',
                'Status',
                'Serious',
                'Seriousness Criteria',
                'Primary Suspected Product',
                'All Products Summary',
                'Side Effect Description',
                'Symptom Evolution',
                'Onset Date',
                'Outcome',
                'Treatment Needed',
                'HCP Name',
                'HCP Contact',
                'Patient Initials',
                'Patient Gender',
                'Patient Age',
                'Ongoing Conditions',
                'Other Medications',
                'Reporter Type',
                'Reporter Name',
                'Reporter Email',
                'Reporter Phone',
                'Location',
                'Follow-up Permitted',
                'Reviewer Notes',
            ]);

            foreach ($reports as $r) {
                $productsSummary = collect($r->products ?? [])->map(function ($p) {
                    $dose = trim(($p['dose'] ?? '') . ' ' . ($p['dose_unit'] ?? ''));
                    return sprintf(
                        '%s [%s | %s | %s | Lot: %s]',
                        $p['product_name'] ?? 'N/A',
                        $p['relationship'] ?? '-',
                        $dose ?: '-',
                        $p['frequency'] ?? '-',
                        $p['lot_batch_number'] ?: '-'
                    );
                })->implode(' ; ');

                fputcsv($handle, [
                    $r->reference_number,
                    $r->created_at?->format('Y-m-d H:i:s'),
                    $r->status_label,
                    $r->is_serious ? 'Yes' : 'No',
                    implode(', ', $r->seriousness_criteria ?? []),
                    $r->primary_product_name,
                    $productsSummary,
                    $r->reaction_description,
                    $r->symptom_evolution,
                    $r->onset_date,
                    $r->outcome,
                    $r->treatment_needed,
                    $r->hcp_name,
                    $r->hcp_contact,
                    $r->patient_initials,
                    $r->patient_gender,
                    $r->patient_age ? ($r->patient_age . ' ' . ($r->patient_age_unit ?? 'Years')) : '',
                    $r->ongoing_medical_conditions,
                    $r->concomitant_medications_notes,
                    $r->reporter_type,
                    $r->reporter_name,
                    $r->reporter_email,
                    $r->reporter_phone,
                    $r->reporter_location,
                    $r->consent_to_contact ? 'Yes' : 'No',
                    $r->admin_notes,
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function render()
    {
        $reports = $this->buildFilteredQuery()->paginate($this->perPage);

        $stats = [
            'total' => AdverseEventReport::count(),
            'new' => AdverseEventReport::where('status', 'new')->count(),
            'serious' => AdverseEventReport::where('is_serious', true)->count(),
            'closed' => AdverseEventReport::whereIn('status', ['reported_to_authority', 'closed'])->count(),
        ];

        return view('livewire.adverse-event-management', compact('reports', 'stats'));
    }
}
