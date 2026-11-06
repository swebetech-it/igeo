<div class="px-4 sm:px-6 lg:px-8 py-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-semibold mb-2">
                <i class="fas fa-shield-virus"></i>
                <span>Pharmacovigilance &amp; Drug Safety</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Adverse Event Reports</h1>
            <p class="mt-1 text-sm text-gray-600">
                Review, assess, and manage side effect &amp; adverse event submissions collected from the public website.
            </p>
        </div>
        <div class="mt-4 sm:mt-0 flex flex-wrap items-center gap-3">
            <a href="{{ route('public.adverse-event') }}" target="_blank"
               class="inline-flex items-center px-3.5 py-2 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 shadow-sm">
                <i class="fas fa-external-link-alt mr-2 text-gray-400"></i>
                Public Form
            </a>
            <button type="button" wire:click="exportCsv"
                    class="inline-flex items-center px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-sm font-semibold text-white shadow-sm transition">
                <i class="fas fa-file-csv mr-2"></i>
                Export CSV
            </button>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total Reports</p>
                    <p class="mt-1 text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600">
                    <i class="fas fa-clipboard-list text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-amber-200 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-amber-700">New / Pending Review</p>
                    <p class="mt-1 text-2xl font-bold text-amber-900">{{ $stats['new'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center text-amber-600">
                    <i class="fas fa-inbox text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-red-200 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-red-700">Serious Events</p>
                    <p class="mt-1 text-2xl font-bold text-red-900">{{ $stats['serious'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center text-red-600">
                    <i class="fas fa-exclamation-triangle text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-emerald-200 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-emerald-700">Submitted / Closed</p>
                    <p class="mt-1 text-2xl font-bold text-emerald-900">{{ $stats['closed'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-600">
                    <i class="fas fa-check-double text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="mt-6 bg-white rounded-xl border border-gray-200 p-4 shadow-sm flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400 text-sm"></i>
                <input wire:model.live.debounce.300ms="search"
                       type="text"
                       placeholder="Search reference #, product name, symptom, or reporter..."
                       class="pl-9 block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
            </div>
        </div>
        <div class="w-full md:w-52">
            <select wire:model.live="statusFilter" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                <option value="">All Statuses</option>
                @foreach(\App\Models\AdverseEventReport::STATUSES as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-full md:w-48">
            <select wire:model.live="seriousnessFilter" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                <option value="">All Severities</option>
                <option value="serious">Serious Only</option>
                <option value="non_serious">Non-Serious Only</option>
            </select>
        </div>
    </div>

    <!-- Reports Table -->
    <div class="mt-6 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Reference &amp; Date</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Suspected Product</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Side Effect &amp; Outcome</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Severity</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Reporter</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Workflow Status</th>
                        <th class="px-4 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-gray-600">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($reports as $report)
                        <tr class="hover:bg-gray-50/70 transition">
                            <td class="whitespace-nowrap px-4 py-4 text-sm">
                                <div class="font-mono font-bold text-gray-900">{{ $report->reference_number }}</div>
                                <div class="text-xs text-gray-500">{{ $report->created_at->format('M d, Y H:i') }}</div>
                            </td>
                            <td class="px-4 py-4 text-sm">
                                <div class="font-semibold text-gray-900 uppercase">{{ $report->primary_product_name }}</div>
                                @php $prodCount = count($report->products ?? []); @endphp
                                @if($prodCount > 1)
                                    <span class="inline-flex items-center mt-0.5 text-xs text-indigo-600 font-medium">
                                        +{{ $prodCount - 1 }} other medication{{ $prodCount - 1 > 1 ? 's' : '' }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-sm max-w-xs">
                                <p class="text-gray-800 truncate" title="{{ $report->reaction_description }}">
                                    {{ \Illuminate\Support\Str::limit($report->reaction_description, 65) }}
                                </p>
                                <span class="text-xs text-gray-500">Outcome: {{ $report->outcome }}</span>
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-sm">
                                @if($report->is_serious)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                        <i class="fas fa-exclamation-circle"></i> Serious
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                        Non-Serious
                                    </span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-sm">
                                <div class="font-medium text-gray-900">{{ $report->reporter_name ?: 'Anonymous' }}</div>
                                <div class="text-xs text-gray-500">{{ $report->reporter_type }}</div>
                                @if($report->consent_to_contact)
                                    <span class="inline-block mt-0.5 text-[11px] font-semibold text-emerald-700">Follow-up OK</span>
                                @else
                                    <span class="inline-block mt-0.5 text-[11px] font-semibold text-amber-700">Do Not Contact</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-sm">
                                <select wire:change="updateStatus({{ $report->id }}, $event.target.value)"
                                        class="text-xs rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 py-1.5">
                                    @foreach(\App\Models\AdverseEventReport::STATUSES as $sKey => $sLabel)
                                        <option value="{{ $sKey }}" @selected($report->status === $sKey)>{{ $sLabel }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-right text-sm font-medium">
                                <button type="button"
                                        wire:click="downloadPdf({{ $report->id }})"
                                        title="Download Case PDF"
                                        class="inline-flex items-center px-2.5 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs transition mr-1">
                                    <i class="fas fa-file-pdf text-red-600"></i>
                                </button>
                                <button type="button"
                                        wire:click="viewReport({{ $report->id }})"
                                        class="inline-flex items-center px-3 py-1.5 rounded-lg bg-indigo-50 text-indigo-700 hover:bg-indigo-100 font-semibold text-xs transition">
                                    <i class="fas fa-eye mr-1.5"></i> View Case
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-notes-medical text-3xl text-gray-300 mb-3 block"></i>
                                <p class="font-medium text-gray-700">No adverse event reports found</p>
                                <p class="text-xs text-gray-500 mt-1">Submissions from the public Pharmacovigilance form will appear here automatically.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reports->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                {{ $reports->links() }}
            </div>
        @endif
    </div>

    <!-- Case Detail Modal -->
    @if($showDetailModal && $selectedReport)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" wire:click="closeModal"></div>

                <div class="relative z-10 w-full max-w-4xl rounded-2xl bg-white shadow-2xl border border-gray-200 overflow-hidden">
                    <!-- Modal Header -->
                    <div class="bg-slate-900 text-white px-6 py-4 flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2.5">
                                <span class="font-mono text-lg font-bold text-emerald-400">{{ $selectedReport->reference_number }}</span>
                                @if($selectedReport->is_serious)
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-500 text-white">SERIOUS EVENT</span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-300 mt-0.5">
                                Submitted {{ $selectedReport->created_at->format('F j, Y \a\t H:i') }}
                                @if($selectedReport->ip_address) &bull; IP: {{ $selectedReport->ip_address }} @endif
                            </p>
                        </div>
                        <button type="button" wire:click="closeModal" class="text-slate-400 hover:text-white p-1">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-6 max-h-[75vh] overflow-y-auto space-y-6 text-sm">
                        <!-- 1. Products -->
                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-700 mb-3">1. Reported Product(s)</h3>
                            <div class="space-y-3">
                                @foreach($selectedReport->products ?? [] as $idx => $p)
                                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                        <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                                            <span class="font-bold text-gray-900 uppercase">#{{ $idx + 1 }} — {{ $p['product_name'] ?? 'N/A' }}</span>
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ ($p['relationship'] ?? '') === 'Suspected' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-200 text-gray-700' }}">
                                                {{ $p['relationship'] ?? '-' }}
                                            </span>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs text-gray-700">
                                            <div><span class="text-gray-500">Start Date:</span> <strong>{{ $p['start_date'] ?? '-' }}</strong></div>
                                            <div><span class="text-gray-500">End Date:</span> <strong>{{ $p['end_date'] ?? '-' }}</strong></div>
                                            <div><span class="text-gray-500">Lot / Batch:</span> <strong>{{ !empty($p['lot_batch_number']) ? $p['lot_batch_number'] : '-' }}</strong></div>
                                            <div><span class="text-gray-500">Formulation:</span> <strong>{{ !empty($p['formulation']) ? $p['formulation'] : '-' }}</strong></div>
                                            <div><span class="text-gray-500">Route:</span> <strong>{{ !empty($p['route']) ? $p['route'] : '-' }}</strong></div>
                                            <div>
                                                <span class="text-gray-500">Dose &amp; Freq:</span>
                                                <strong>{{ trim(($p['dose'] ?? '') . ' ' . ($p['dose_unit'] ?? '')) ?: '-' }} ({{ !empty($p['frequency']) ? $p['frequency'] : '-' }})</strong>
                                            </div>
                                            @if(!empty($p['other_dosage']))
                                                <div class="sm:col-span-3"><span class="text-gray-500">Other Dosage:</span> <strong>{{ $p['other_dosage'] }}</strong></div>
                                            @endif
                                            <div class="sm:col-span-2"><span class="text-gray-500">Indication:</span> <strong>{{ !empty($p['indication']) ? $p['indication'] : '-' }}</strong></div>
                                            <div>
                                                <span class="text-gray-500">Action Taken:</span>
                                                <strong>{{ !empty($p['action_taken']) ? $p['action_taken'] : '-' }}</strong>
                                                @if(!empty($p['action_impact'])) ({{ $p['action_impact'] }}) @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- 2. Side Effect -->
                        <div class="border-t border-gray-200 pt-5">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-700 mb-3">2. Side Effect / Adverse Event</h3>
                            <div class="rounded-xl border border-gray-200 p-4 space-y-3">
                                <div>
                                    <span class="text-xs text-gray-500 block">Description</span>
                                    <p class="text-gray-900 whitespace-pre-line font-medium">{{ $selectedReport->reaction_description }}</p>
                                </div>
                                @if($selectedReport->symptom_evolution)
                                    <div>
                                        <span class="text-xs text-gray-500 block">Symptom Evolution Over Time</span>
                                        <p class="text-gray-800 whitespace-pre-line">{{ $selectedReport->symptom_evolution }}</p>
                                    </div>
                                @endif
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs pt-2 border-t border-gray-100">
                                    <div><span class="text-gray-500">Onset Date:</span> <strong>{{ $selectedReport->onset_date ?: '-' }}</strong></div>
                                    <div><span class="text-gray-500">Resolution Date:</span> <strong>{{ $selectedReport->resolution_date ?: '-' }}</strong></div>
                                    <div><span class="text-gray-500">Outcome:</span> <strong>{{ $selectedReport->outcome }}</strong></div>
                                    <div><span class="text-gray-500">Treatment Needed:</span> <strong>{{ $selectedReport->treatment_needed ?: '-' }}</strong></div>
                                    <div><span class="text-gray-500">Healthcare Provider:</span> <strong>{{ $selectedReport->hcp_name ?: '-' }}</strong></div>
                                    <div><span class="text-gray-500">HCP Contact:</span> <strong>{{ $selectedReport->hcp_contact ?: '-' }}</strong></div>
                                </div>
                                @if($selectedReport->treatment_description)
                                    <div class="text-xs">
                                        <span class="text-gray-500">Treatment Details:</span>
                                        <p class="text-gray-800 mt-0.5">{{ $selectedReport->treatment_description }}</p>
                                    </div>
                                @endif
                                @if(!empty($selectedReport->seriousness_criteria))
                                    <div class="rounded-lg bg-red-50 border border-red-200 p-3 text-xs text-red-800">
                                        <strong>Seriousness Criteria Reported:</strong> {{ implode(', ', $selectedReport->seriousness_criteria) }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- 3 & 4. Patient & Reporter -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-gray-200 pt-5">
                            <div>
                                <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-700 mb-3">3. Patient Details</h3>
                                <div class="rounded-xl border border-gray-200 p-4 space-y-2 text-xs">
                                    <div><span class="text-gray-500">Initials:</span> <strong>{{ $selectedReport->patient_initials ?: 'Not provided' }}</strong></div>
                                    <div><span class="text-gray-500">Gender:</span> <strong>{{ $selectedReport->patient_gender ?: 'Not provided' }}</strong></div>
                                    <div><span class="text-gray-500">Age:</span> <strong>{{ $selectedReport->patient_age ? $selectedReport->patient_age . ' ' . ($selectedReport->patient_age_unit ?? 'Years') : 'Not provided' }}</strong></div>
                                    <div>
                                        <span class="text-gray-500 block">Ongoing Medical Conditions:</span>
                                        <p class="text-gray-800 mt-0.5">{{ $selectedReport->ongoing_medical_conditions ?: 'None reported' }}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 block">Other Medications Currently Taken:</span>
                                        <p class="text-gray-800 mt-0.5">{{ $selectedReport->concomitant_medications_notes ?: 'None listed here' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-700 mb-3">4. Reporter &amp; Consent</h3>
                                <div class="rounded-xl border border-gray-200 p-4 space-y-2 text-xs">
                                    <div><span class="text-gray-500">Role:</span> <strong>{{ $selectedReport->reporter_type }}</strong></div>
                                    <div><span class="text-gray-500">Name:</span> <strong>{{ $selectedReport->reporter_name ?: 'Anonymous' }}</strong></div>
                                    <div><span class="text-gray-500">Email:</span> <strong>{{ $selectedReport->reporter_email ?: '-' }}</strong></div>
                                    <div><span class="text-gray-500">Phone:</span> <strong>{{ $selectedReport->reporter_phone ?: '-' }}</strong></div>
                                    <div><span class="text-gray-500">Location:</span> <strong>{{ $selectedReport->reporter_location ?: '-' }}</strong></div>
                                    <div class="pt-1">
                                        @if($selectedReport->consent_to_contact)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 font-semibold">
                                                <i class="fas fa-check-circle mr-1"></i> Consented to Drug Safety follow-up
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 font-semibold">
                                                <i class="fas fa-ban mr-1"></i> Requested NO follow-up contact
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 5. Drug Safety Reviewer Notes -->
                        <div class="border-t border-gray-200 pt-5">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-700">5. Internal Pharmacovigilance Assessment &amp; Notes</h3>
                                @if($selectedReport->reviewed_at)
                                    <span class="text-xs text-gray-500">
                                        Last updated {{ $selectedReport->reviewed_at->diffForHumans() }}
                                        @if($selectedReport->reviewer) by {{ $selectedReport->reviewer->name }} @endif
                                    </span>
                                @endif
                            </div>
                            <textarea rows="3"
                                      wire:model="adminNotesInput"
                                      placeholder="Record follow-up calls, medical causality assessment, or TMDA reporting reference..."
                                      class="w-full rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                            <div class="mt-2 flex justify-end">
                                <button type="button"
                                        wire:click="saveAdminNotes"
                                        class="inline-flex items-center px-4 py-2 rounded-lg bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold transition">
                                    <i class="fas fa-save mr-1.5"></i> Save Reviewer Notes
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex flex-wrap items-center justify-between gap-3">
                        <button type="button"
                                wire:click="deleteReport({{ $selectedReport->id }})"
                                wire:confirm="Are you sure you want to permanently delete adverse event report {{ $selectedReport->reference_number }}?"
                                class="inline-flex items-center px-3.5 py-2 rounded-lg bg-red-50 text-red-700 hover:bg-red-100 text-xs font-bold transition">
                            <i class="fas fa-trash-alt mr-1.5"></i> Delete Report
                        </button>

                        <div class="flex items-center gap-3">
                            <button type="button" wire:click="downloadPdf({{ $selectedReport->id }})"
                                    class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 text-xs font-semibold shadow-sm">
                                <i class="fas fa-file-pdf mr-1.5 text-red-600"></i> Download PDF
                            </button>
                            <button type="button" wire:click="closeModal"
                                    class="inline-flex items-center px-5 py-2 rounded-lg bg-gray-800 text-white hover:bg-gray-900 text-xs font-semibold">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
