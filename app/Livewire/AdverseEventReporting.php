<?php

namespace App\Livewire;

use App\Mail\AdverseEventSubmittedMail;
use App\Models\AdverseEventReport;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class AdverseEventReporting extends Component
{
    public int $currentStep = 1;
    public int $maxStepReached = 1;
    public bool $showEmailInstructions = false;
    public bool $submitted = false;
    public ?string $submittedReference = null;

    // Step 1: Products
    public array $products = [];
    public array $productSuggestions = [];

    // Step 2: Side Effect
    public string $reaction_description = '';
    public string $symptom_evolution = '';
    public string $onset_day = '';
    public string $onset_month = '';
    public string $onset_year = '';
    public string $resolution_day = '';
    public string $resolution_month = '';
    public string $resolution_year = '';
    public string $outcome = '';
    public array $seriousness_criteria = [];
    public string $treatment_needed = 'No';
    public string $treatment_description = '';
    public string $hcp_name = '';
    public string $hcp_contact = '';

    // Step 3: Patient Details
    public string $patient_initials = '';
    public string $patient_gender = '';
    public string $patient_age = '';
    public string $patient_age_unit = 'Years';
    public string $ongoing_medical_conditions = '';
    public string $concomitant_medications_notes = '';

    // Step 4: Contact Details
    public string $reporter_type = 'Patient / Consumer';
    public string $reporter_name = '';
    public string $reporter_email = '';
    public string $reporter_phone = '';
    public string $reporter_location = 'Tanzania';
    public string $consent_to_contact = 'yes';

    // Step 5: Confirmation
    public bool $confirm_good_faith = false;

    public const MONTHS = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December',
    ];

    public const FORMULATIONS = [
        'Tablet',
        'Capsule',
        'Film-coated Tablet',
        'Solution for Injection / Infusion',
        'Intrathecal Solution',
        'Oral Liquid / Syrup / Suspension',
        'Powder for Injection / Suspension',
        'Cream / Ointment / Gel',
        'Eye / Ear / Nasal Drops',
        'Inhaler / Nasal Spray',
        'Transdermal Patch',
        'Suppository',
        'Implant',
        'Other',
    ];

    public const ROUTES = [
        'Oral',
        'Intravenous (IV)',
        'Intramuscular (IM)',
        'Subcutaneous (SC)',
        'Intrathecal',
        'Topical / Cutaneous',
        'Inhalation / Respiratory',
        'Ophthalmic (Eye)',
        'Otic (Ear)',
        'Nasal',
        'Sublingual / Buccal',
        'Rectal / Vaginal',
        'Transdermal',
        'Other / Unknown',
    ];

    public const DOSE_UNITS = [
        'mg', 'mcg (µg)', 'g', 'mL', 'L', 'IU', '%', 'mg/mL', 'drops', 'puffs', 'tablets', 'capsules', 'units', 'other',
    ];

    public const FREQUENCIES = [
        'Once daily',
        'Twice daily',
        'Three times daily',
        'Four times daily',
        'Every 8 hours',
        'Every 12 hours',
        'Every other day',
        'Once weekly',
        'Once monthly',
        'Continuous infusion',
        'Single dose',
        'As needed (PRN)',
        'Other / Unknown',
    ];

    public const ACTIONS_TAKEN = [
        'Dose not changed',
        'Dose reduced',
        'Dose increased',
        'Drug withdrawn / Discontinued',
        'Drug temporarily interrupted',
        'Not applicable',
        'Unknown',
    ];

    public const ACTION_IMPACTS = [
        'Symptoms resolved completely',
        'Symptoms improved',
        'No change in symptoms',
        'Symptoms worsened',
        'Not applicable / Unknown',
    ];

    public const OUTCOMES = [
        'Recovered / Resolved',
        'Recovering / Resolving',
        'Not recovered / Ongoing',
        'Recovered with lasting effects (sequelae)',
        'Fatal',
        'Unknown',
    ];

    public const SERIOUSNESS_OPTIONS = [
        'Hospitalization (initial or prolonged)',
        'Life-threatening condition',
        'Persistent or significant disability / incapacity',
        'Congenital anomaly / birth defect',
        'Medically significant / important event',
        'Resulted in death',
    ];

    public function mount(): void
    {
        $this->products = [$this->makeBlankProduct('Suspected')];

        try {
            $catalogNames = Product::query()->pluck('name')->toArray();
        } catch (\Throwable $e) {
            $catalogNames = [];
        }

        $this->productSuggestions = array_values(array_unique(array_merge($catalogNames, [
            'Amoxicillin Capsules',
            'Artemether / Lumefantrine Tablets',
            'Azithromycin Tablets',
            'Baclofen Intrathecal Solution',
            'Ceftriaxone Injection',
            'Ciprofloxacin Tablets',
            'Diclofenac Sodium',
            'Losartan Potassium Tablets',
            'Metformin Hydrochloride Tablets',
            'Omeprazole Capsules',
            'Paracetamol Infusion / Tablets',
        ])));
    }

    protected function makeBlankProduct(string $defaultRelationship = 'Concomitant product'): array
    {
        return [
            'expanded' => true,
            'product_name' => '',
            'relationship' => $defaultRelationship,
            'start_day' => '',
            'start_month' => '',
            'start_year' => '',
            'is_ongoing' => false,
            'end_day' => '',
            'end_month' => '',
            'end_year' => '',
            'indication' => '',
            'formulation' => '',
            'route' => '',
            'dose' => '',
            'dose_unit' => '',
            'other_dosage' => '',
            'frequency' => '',
            'action_taken' => '',
            'action_impact' => '',
            'lot_batch_number' => '',
        ];
    }

    public function addProduct(): void
    {
        foreach ($this->products as $i => $prod) {
            $this->products[$i]['expanded'] = false;
        }
        $this->products[] = $this->makeBlankProduct('Concomitant product');
    }

    public function removeProduct(int $index): void
    {
        if (count($this->products) <= 1) {
            return;
        }

        unset($this->products[$index]);
        $this->products = array_values($this->products);

        // Ensure at least one remains expanded if all are collapsed
        $anyExpanded = collect($this->products)->contains(fn ($p) => !empty($p['expanded']));
        if (!$anyExpanded && isset($this->products[0])) {
            $this->products[0]['expanded'] = true;
        }
    }

    public function toggleProduct(int $index): void
    {
        if (isset($this->products[$index])) {
            $this->products[$index]['expanded'] = empty($this->products[$index]['expanded']);
        }
    }

    public function goToStep(int $step): void
    {
        if ($step < 1 || $step > 5) {
            return;
        }

        if ($step <= $this->currentStep || $step <= $this->maxStepReached) {
            $this->currentStep = $step;
        }
    }

    public function previousStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function saveAndContinue(): void
    {
        $this->resetErrorBag();

        if (!$this->validateStep($this->currentStep)) {
            return;
        }

        if ($this->currentStep < 5) {
            $this->currentStep++;
            $this->maxStepReached = max($this->maxStepReached, $this->currentStep);
        }
    }

    protected function validateStep(int $step): bool
    {
        if ($step === 1) {
            $this->validate([
                'products' => 'required|array|min:1',
                'products.*.product_name' => 'required|string|max:70',
                'products.*.relationship' => 'required|in:Suspected,Concomitant product,Used for treatment of the side effect',
                'products.*.indication' => 'nullable|string|max:250',
                'products.*.dose' => 'nullable|string|max:8',
                'products.*.other_dosage' => 'nullable|string|max:50',
                'products.*.lot_batch_number' => 'nullable|string|max:35',
            ], [
                'products.*.product_name.required' => 'Please enter the name of the product you want to report.',
                'products.*.product_name.max' => "Maximum length of the 'Product name' field is 70 characters.",
                'products.*.relationship.required' => 'Please select how this product is related to the side effect.',
                'products.*.indication.max' => "Maximum length of the 'Indication' field is 250 characters.",
                'products.*.dose.max' => "Maximum length of the 'Dose' field is 8 characters.",
                'products.*.other_dosage.max' => "Maximum length of the 'Other Dosage' field is 50 characters.",
                'products.*.lot_batch_number.max' => "Maximum length of the 'Lot or Batch number' field is 35 characters.",
            ]);

            $hasSuspected = collect($this->products)->contains(
                fn ($p) => ($p['relationship'] ?? '') === 'Suspected'
            );

            if (!$hasSuspected) {
                $this->addError('suspected_product', 'Please ensure that at least one product is checked as “Suspected”.');
                return false;
            }

            return true;
        }

        if ($step === 2) {
            $this->validate([
                'reaction_description' => 'required|string|min:5|max:2000',
                'symptom_evolution' => 'nullable|string|max:2000',
                'outcome' => 'required|string|max:80',
                'treatment_needed' => 'required|in:No,Yes,Unknown',
                'treatment_description' => 'nullable|string|max:1000',
                'hcp_name' => 'nullable|string|max:255',
                'hcp_contact' => 'nullable|string|max:255',
            ], [
                'reaction_description.required' => 'Please describe the side effect or adverse event experienced.',
                'outcome.required' => 'Please select the current outcome of the side effect.',
            ]);

            return true;
        }

        if ($step === 3) {
            $this->validate([
                'patient_initials' => 'nullable|string|max:15',
                'patient_gender' => 'nullable|string|max:40',
                'patient_age' => 'nullable|numeric|min:0|max:130',
                'patient_age_unit' => 'nullable|in:Years,Months,Weeks,Days',
                'ongoing_medical_conditions' => 'nullable|string|max:2000',
                'concomitant_medications_notes' => 'nullable|string|max:2000',
            ]);

            return true;
        }

        if ($step === 4) {
            $rules = [
                'reporter_type' => 'required|string|max:80',
                'reporter_name' => 'nullable|string|max:255',
                'reporter_email' => 'nullable|email|max:255',
                'reporter_phone' => 'nullable|string|max:50',
                'reporter_location' => 'nullable|string|max:120',
                'consent_to_contact' => 'required|in:yes,no',
            ];

            $this->validate($rules);

            if ($this->consent_to_contact === 'yes' && empty(trim($this->reporter_email)) && empty(trim($this->reporter_phone))) {
                $this->addError('reporter_contact', 'Please provide an email address or phone number so our Drug Safety team can follow up, or choose "Do not contact me".');
                return false;
            }

            return true;
        }

        return true;
    }

    protected function formatPartialDate(string $day, string $month, string $year): ?string
    {
        $parts = array_filter([$day, $month, $year], fn ($v) => trim((string) $v) !== '');
        return empty($parts) ? null : implode(' ', $parts);
    }

    public function submitReport(): void
    {
        $this->resetErrorBag();

        for ($s = 1; $s <= 4; $s++) {
            if (!$this->validateStep($s)) {
                $this->currentStep = $s;
                return;
            }
        }

        $this->validate([
            'confirm_good_faith' => 'accepted',
        ], [
            'confirm_good_faith.accepted' => 'Please confirm that the information provided is accurate to the best of your knowledge.',
        ]);

        $cleanProducts = collect($this->products)->map(function (array $p) {
            return [
                'product_name' => trim($p['product_name'] ?? ''),
                'relationship' => $p['relationship'] ?? 'Suspected',
                'start_date' => $this->formatPartialDate($p['start_day'] ?? '', $p['start_month'] ?? '', $p['start_year'] ?? ''),
                'start_day' => $p['start_day'] ?? '',
                'start_month' => $p['start_month'] ?? '',
                'start_year' => $p['start_year'] ?? '',
                'is_ongoing' => !empty($p['is_ongoing']),
                'end_date' => !empty($p['is_ongoing'])
                    ? 'Ongoing'
                    : $this->formatPartialDate($p['end_day'] ?? '', $p['end_month'] ?? '', $p['end_year'] ?? ''),
                'end_day' => $p['end_day'] ?? '',
                'end_month' => $p['end_month'] ?? '',
                'end_year' => $p['end_year'] ?? '',
                'indication' => trim($p['indication'] ?? ''),
                'formulation' => $p['formulation'] ?? '',
                'route' => $p['route'] ?? '',
                'dose' => trim($p['dose'] ?? ''),
                'dose_unit' => $p['dose_unit'] ?? '',
                'other_dosage' => trim($p['other_dosage'] ?? ''),
                'frequency' => $p['frequency'] ?? '',
                'action_taken' => $p['action_taken'] ?? '',
                'action_impact' => $p['action_impact'] ?? '',
                'lot_batch_number' => trim($p['lot_batch_number'] ?? ''),
            ];
        })->values()->all();

        $suspected = collect($cleanProducts)->firstWhere('relationship', 'Suspected');
        $primaryProductName = $suspected['product_name'] ?? ($cleanProducts[0]['product_name'] ?? 'Unspecified Product');

        $report = AdverseEventReport::create([
            'reference_number' => AdverseEventReport::generateReferenceNumber(),
            'primary_product_name' => $primaryProductName,
            'products' => $cleanProducts,
            'reaction_description' => trim($this->reaction_description),
            'symptom_evolution' => trim($this->symptom_evolution) ?: null,
            'onset_date' => $this->formatPartialDate($this->onset_day, $this->onset_month, $this->onset_year),
            'resolution_date' => $this->formatPartialDate($this->resolution_day, $this->resolution_month, $this->resolution_year),
            'outcome' => $this->outcome,
            'is_serious' => !empty($this->seriousness_criteria),
            'seriousness_criteria' => array_values($this->seriousness_criteria),
            'treatment_needed' => $this->treatment_needed,
            'treatment_description' => trim($this->treatment_description) ?: null,
            'hcp_name' => trim($this->hcp_name) ?: null,
            'hcp_contact' => trim($this->hcp_contact) ?: null,
            'patient_initials' => trim($this->patient_initials) ?: null,
            'patient_gender' => $this->patient_gender ?: null,
            'patient_age' => trim($this->patient_age) !== '' ? trim($this->patient_age) : null,
            'patient_age_unit' => trim($this->patient_age) !== '' ? $this->patient_age_unit : null,
            'ongoing_medical_conditions' => trim($this->ongoing_medical_conditions) ?: null,
            'concomitant_medications_notes' => trim($this->concomitant_medications_notes) ?: null,
            'reporter_type' => $this->reporter_type,
            'reporter_name' => trim($this->reporter_name) ?: null,
            'reporter_email' => trim($this->reporter_email) ?: null,
            'reporter_phone' => trim($this->reporter_phone) ?: null,
            'reporter_location' => trim($this->reporter_location) ?: 'Tanzania',
            'consent_to_contact' => $this->consent_to_contact === 'yes',
            'status' => 'new',
            'ip_address' => request()->ip(),
        ]);

        try {
            $recipient = env('DRUG_SAFETY_EMAIL', env('CONTACT_EMAIL', 'info@igeo.co.tz'));
            if ($recipient) {
                Mail::to($recipient)->send(new AdverseEventSubmittedMail($report));
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to dispatch AdverseEventSubmittedMail: ' . $e->getMessage());
        }

        $this->submitted = true;
        $this->submittedReference = $report->reference_number;
    }

    public function downloadPdf()
    {
        if (!$this->submittedReference) {
            return;
        }

        $report = AdverseEventReport::where('reference_number', $this->submittedReference)->firstOrFail();

        $pdf = Pdf::loadView('livewire.adverse-event-confirmation-pdf', [
            'report' => $report,
            'generatedAt' => now()->format('Y-m-d H:i:s'),
        ]);

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'confirmation-' . $report->reference_number . '.pdf'
        );
    }

    public function startNewReport(): void
    {
        $this->reset([
            'currentStep', 'maxStepReached', 'submitted', 'submittedReference',
            'reaction_description', 'symptom_evolution',
            'onset_day', 'onset_month', 'onset_year',
            'resolution_day', 'resolution_month', 'resolution_year',
            'outcome', 'seriousness_criteria', 'treatment_needed', 'treatment_description',
            'hcp_name', 'hcp_contact',
            'patient_initials', 'patient_gender', 'patient_age', 'patient_age_unit',
            'ongoing_medical_conditions', 'concomitant_medications_notes',
            'reporter_type', 'reporter_name', 'reporter_email', 'reporter_phone',
            'reporter_location', 'consent_to_contact', 'confirm_good_faith',
        ]);
        $this->products = [$this->makeBlankProduct('Suspected')];
    }

    public function render()
    {
        return view('livewire.adverse-event-reporting')
            ->layout('layouts.web');
    }
}
