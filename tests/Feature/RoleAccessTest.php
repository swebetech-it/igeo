<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_access_all_reports()
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN
        ]);

        $response = $this->actingAs($admin)->get(route('admin.reports.project'));
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get(route('admin.reports.financial'));
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get(route('admin.reports.resource'));
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get(route('admin.reports.executive'));
        $response->assertStatus(200);
    }

    /** @test */
    public function manager_can_access_all_reports()
    {
        $manager = User::factory()->create([
            'role' => User::ROLE_MANAGER
        ]);

        $response = $this->actingAs($manager)->get(route('admin.reports.project'));
        $response->assertStatus(200);

        $response = $this->actingAs($manager)->get(route('admin.reports.financial'));
        $response->assertStatus(200);

        $response = $this->actingAs($manager)->get(route('admin.reports.resource'));
        $response->assertStatus(200);

        $response = $this->actingAs($manager)->get(route('admin.reports.executive'));
        $response->assertStatus(200);
    }

    /** @test */
    public function finance_user_can_access_project_and_financial_reports_only()
    {
        $finance = User::factory()->create([
            'role' => User::ROLE_FINANCE
        ]);

        $response = $this->actingAs($finance)->get(route('admin.reports.project'));
        $response->assertStatus(200);

        $response = $this->actingAs($finance)->get(route('admin.reports.financial'));
        $response->assertStatus(200);

        $response = $this->actingAs($finance)->get(route('admin.reports.resource'));
        $response->assertStatus(403);

        $response = $this->actingAs($finance)->get(route('admin.reports.executive'));
        $response->assertStatus(403);
    }

    /** @test */
    public function regular_user_cannot_access_any_reports()
    {
        $user = User::factory()->create([
            'role' => User::ROLE_USER
        ]);

        $response = $this->actingAs($user)->get(route('admin.reports.project'));
        $response->assertStatus(403);

        $response = $this->actingAs($user)->get(route('admin.reports.financial'));
        $response->assertStatus(403);

        $response = $this->actingAs($user)->get(route('admin.reports.resource'));
        $response->assertStatus(403);

        $response = $this->actingAs($user)->get(route('admin.reports.executive'));
        $response->assertStatus(403);
    }

    /** @test */
    public function guest_cannot_access_reports()
    {
        $response = $this->get(route('admin.reports.project'));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('admin.reports.financial'));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('admin.reports.resource'));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('admin.reports.executive'));
        $response->assertRedirect(route('login'));
    }
}