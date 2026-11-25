<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\Service;
use App\Support\RegulatoryServicesCatalog;
use Database\Seeders\ServiceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegulatoryServicesWebsiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_displays_updated_logo_decluttered_nav_and_eight_service_lines(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Logo-emblem.png?v=20261125');
        $response->assertSee('Plus+Jakarta+Sans', false);
        $response->assertSee('Services');
        $response->assertSee('Media &amp; Project Gallery', false);

        foreach (RegulatoryServicesCatalog::all() as $service) {
            $response->assertSee($service['name']);
        }
    }

    public function test_services_hub_renders_positioning_all_eight_lines_and_case_study(): void
    {
        $response = $this->get(route('public.services'));

        $response->assertStatus(200);
        $response->assertSee('Regulatory Affairs &amp; Pharmaceutical Compliance Services', false);
        $response->assertSee('Caffeine Citrate Injection');
        $response->assertSee('Morphine Oral Solution');
        $response->assertSee('REQUEST CONSULTATION');

        foreach (RegulatoryServicesCatalog::all() as $service) {
            $response->assertSee($service['name']);
            foreach ($service['features'] as $feature) {
                $response->assertSee($feature);
            }
        }
    }

    public function test_each_service_detail_page_renders_full_deliverables_and_consultation_option(): void
    {
        foreach (RegulatoryServicesCatalog::all() as $service) {
            $response = $this->get(route('public.service.detail', ['slug' => $service['slug']]));

            $response->assertStatus(200);
            $response->assertSee($service['name']);
            $response->assertSee('REQUEST CONSULTATION');
            $response->assertSee('SUBMIT CONSULTATION REQUEST');

            foreach ($service['features'] as $feature) {
                $response->assertSee($feature);
            }
        }
    }

    public function test_submitting_consultation_request_stores_lead_and_contact_submission(): void
    {
        $service = RegulatoryServicesCatalog::all()[0];

        $response = $this->post(route('consultation.submit'), [
            'service_name' => $service['name'],
            'service_slug' => $service['slug'],
            'name' => 'Dr. Asha Mwakasege',
            'company' => 'Pan-African Pharma Ltd',
            'email' => 'asha@panafricanpharma.com',
            'phone' => '+255712345678',
            'product_category' => 'Controlled / Neonatal / Critical-Care Medicines',
            'message' => 'We wish to assess the TMDA CTD registration pathway for caffeine citrate injection.',
        ]);

        $response->assertRedirect(route('public.service.detail', ['slug' => $service['slug']]) . '#consultation');
        $response->assertSessionHas('consultation_success');

        $this->assertDatabaseHas('leads', [
            'name' => 'Dr. Asha Mwakasege',
            'email' => 'asha@panafricanpharma.com',
            'company' => 'Pan-African Pharma Ltd',
            'status' => 'new',
        ]);

        $this->assertDatabaseHas('contact_submissions', [
            'name' => 'Dr. Asha Mwakasege',
            'email' => 'asha@panafricanpharma.com',
            'project_type' => 'Consultation: ' . $service['name'],
        ]);

        $lead = Lead::first();
        $this->assertStringContainsString($service['name'], $lead->message);
    }

    public function test_service_seeder_populates_eight_regulatory_services(): void
    {
        $this->seed(ServiceSeeder::class);

        $this->assertEquals(8, Service::count());
        $this->assertDatabaseHas('services', [
            'name' => 'Product Registration and Marketing Authorization',
            'is_active' => true,
        ]);
    }
}
