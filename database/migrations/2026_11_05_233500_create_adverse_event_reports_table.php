<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('adverse_event_reports', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number', 40)->unique();

            // Step 1: Product(s)
            $table->string('primary_product_name', 100)->index();
            $table->json('products');

            // Step 2: Side Effect / Adverse Event
            $table->text('reaction_description');
            $table->text('symptom_evolution')->nullable();
            $table->string('onset_date', 50)->nullable();
            $table->string('resolution_date', 50)->nullable();
            $table->string('outcome', 80)->default('Unknown');
            $table->boolean('is_serious')->default(false)->index();
            $table->json('seriousness_criteria')->nullable();
            $table->string('treatment_needed', 30)->nullable();
            $table->text('treatment_description')->nullable();
            $table->string('hcp_name')->nullable();
            $table->string('hcp_contact')->nullable();

            // Step 3: Patient Details
            $table->string('patient_initials', 20)->nullable();
            $table->string('patient_gender', 40)->nullable();
            $table->string('patient_age', 20)->nullable();
            $table->string('patient_age_unit', 20)->nullable();
            $table->text('ongoing_medical_conditions')->nullable();
            $table->text('concomitant_medications_notes')->nullable();

            // Step 4: Contact / Reporter Details
            $table->string('reporter_type', 80)->default('Patient / Consumer');
            $table->string('reporter_name')->nullable();
            $table->string('reporter_email')->nullable();
            $table->string('reporter_phone', 50)->nullable();
            $table->string('reporter_location', 120)->nullable();
            $table->boolean('consent_to_contact')->default(true);

            // Admin / Drug Safety Review Workflow
            $table->string('status', 40)->default('new')->index();
            $table->text('admin_notes')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->string('ip_address', 45)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adverse_event_reports');
    }
};
