<?php

namespace Tests\Feature\Livewire;

use App\Livewire\AdverseEventManagement;
use App\Livewire\AdverseEventReporting;
use App\Mail\AdverseEventSubmittedMail;
use App\Models\AdverseEventReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class AdverseEventModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_adverse_event_page_renders_with_header_and_footer_links(): void
    {
        $response = $this->get(route('public.adverse-event'));

        $response->assertStatus(200);
        $response->assertSee('Report an Adverse Event');
        $response->assertSee('info@igeo.co.tz');
        $response->assertSee('About this product');
    }

    public function test_step_one_requires_product_name_and_at_least_one_suspected_product(): void
    {
        Livewire::test(AdverseEventReporting::class)
            ->call('saveAndContinue')
            ->assertHasErrors(['products.0.product_name'])
            ->assertSet('currentStep', 1);

        Livewire::test(AdverseEventReporting::class)
            ->set('products.0.product_name', 'Paracetamol 500mg')
            ->set('products.0.relationship', 'Concomitant product')
            ->call('saveAndContinue')
            ->assertHasErrors(['suspected_product'])
            ->assertSet('currentStep', 1);
    }

    public function test_full_five_step_submission_saves_to_backend_module_and_dispatches_email(): void
    {
        Mail::fake();

        $component = Livewire::test(AdverseEventReporting::class)
            // Step 1: Product
            ->set('products.0.product_name', 'BACLOFEN INTRATHECAL')
            ->set('products.0.relationship', 'Suspected')
            ->set('products.0.start_day', '4')
            ->set('products.0.start_month', 'March')
            ->set('products.0.start_year', '2025')
            ->set('products.0.end_day', '4')
            ->set('products.0.end_month', 'March')
            ->set('products.0.end_year', '2026')
            ->set('products.0.indication', 'Severe spasticity')
            ->set('products.0.formulation', 'Intrathecal Solution')
            ->set('products.0.route', 'Intrathecal')
            ->set('products.0.dose', '50')
            ->set('products.0.dose_unit', 'mcg (µg)')
            ->set('products.0.frequency', 'Continuous infusion')
            ->set('products.0.action_taken', 'Dose reduced')
            ->set('products.0.action_impact', 'Symptoms improved')
            ->set('products.0.lot_batch_number', 'LOT-2026-TZ')
            ->call('saveAndContinue')
            ->assertHasNoErrors()
            ->assertSet('currentStep', 2)

            // Step 2: Side Effect
            ->set('reaction_description', 'Patient experienced transient dizziness and mild muscle hypotonia.')
            ->set('symptom_evolution', 'Started 6 hours after dose adjustment and improved within 48 hours.')
            ->set('outcome', 'Recovering / Resolving')
            ->set('seriousness_criteria', ['Hospitalization (initial or prolonged)'])
            ->set('treatment_needed', 'Yes')
            ->set('treatment_description', 'Observation and IV hydration')
            ->set('hcp_name', 'Dr. J. Mwakasege')
            ->set('hcp_contact', '+255 713 000 111')
            ->call('saveAndContinue')
            ->assertHasNoErrors()
            ->assertSet('currentStep', 3)

            // Step 3: Patient Details
            ->set('patient_initials', 'A.K.')
            ->set('patient_gender', 'Female')
            ->set('patient_age', '42')
            ->set('patient_age_unit', 'Years')
            ->set('ongoing_medical_conditions', 'Hypertension')
            ->set('concomitant_medications_notes', 'Amlodipine 5mg once daily')
            ->call('saveAndContinue')
            ->assertHasNoErrors()
            ->assertSet('currentStep', 4)

            // Step 4: Contact Details
            ->set('reporter_type', 'Pharmacist')
            ->set('reporter_name', 'Neema Joseph')
            ->set('reporter_email', 'neema@example.co.tz')
            ->set('reporter_phone', '+255755123456')
            ->set('reporter_location', 'Dar es Salaam, Tanzania')
            ->set('consent_to_contact', 'yes')
            ->call('saveAndContinue')
            ->assertHasNoErrors()
            ->assertSet('currentStep', 5)

            // Step 5: Summary & Submit
            ->set('confirm_good_faith', true)
            ->call('submitReport')
            ->assertHasNoErrors()
            ->assertSet('submitted', true);

        $this->assertDatabaseCount('adverse_event_reports', 1);

        $report = AdverseEventReport::first();
        $this->assertNotNull($report);
        $this->assertStringStartsWith('IGEO-PV-', $report->reference_number);
        $this->assertSame('BACLOFEN INTRATHECAL', $report->primary_product_name);
        $this->assertTrue($report->is_serious);
        $this->assertSame('new', $report->status);
        $this->assertSame('Pharmacist', $report->reporter_type);
        $this->assertTrue($report->consent_to_contact);

        $component->assertSet('submittedReference', $report->reference_number);

        Mail::assertSent(AdverseEventSubmittedMail::class, function (AdverseEventSubmittedMail $mail) use ($report) {
            return $mail->report->id === $report->id;
        });
    }

    public function test_admin_module_lists_reviews_updates_and_exports_reports(): void
    {
        $admin = User::factory()->create();

        $report = AdverseEventReport::create([
            'reference_number' => 'IGEO-PV-2026-TEST01',
            'primary_product_name' => 'AMOXICILLIN 500MG',
            'products' => [[
                'product_name' => 'AMOXICILLIN 500MG',
                'relationship' => 'Suspected',
                'dose' => '500',
                'dose_unit' => 'mg',
                'frequency' => 'Three times daily',
                'lot_batch_number' => 'AMX-99',
            ]],
            'reaction_description' => 'Mild urticarial skin rash on forearms.',
            'outcome' => 'Recovered / Resolved',
            'is_serious' => false,
            'reporter_type' => 'Patient / Consumer',
            'reporter_name' => 'John Doe',
            'consent_to_contact' => false,
            'status' => 'new',
        ]);

        // Guest redirected
        $this->get(route('admin.adverse-events'))->assertRedirect(route('login'));

        // Authenticated admin access
        $this->actingAs($admin)
            ->get(route('admin.adverse-events'))
            ->assertStatus(200)
            ->assertSee('IGEO-PV-2026-TEST01')
            ->assertSee('AMOXICILLIN 500MG');

        Livewire::actingAs($admin)
            ->test(AdverseEventManagement::class)
            ->call('viewReport', $report->id)
            ->assertSet('showDetailModal', true)
            ->set('adminNotesInput', 'Reviewed by Drug Safety officer; non-serious rash resolved.')
            ->call('saveAdminNotes')
            ->call('updateStatus', $report->id, 'under_review')
            ->call('exportCsv')
            ->assertFileDownloaded();

        $report->refresh();
        $this->assertSame('under_review', $report->status);
        $this->assertSame('Reviewed by Drug Safety officer; non-serious rash resolved.', $report->admin_notes);
        $this->assertSame($admin->id, $report->reviewed_by);
    }
}
