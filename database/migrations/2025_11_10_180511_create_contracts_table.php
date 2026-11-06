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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('client_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            
            // Contract Details
            $table->decimal('value', 15, 2)->nullable();
            $table->string('contract_number')->unique();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('type')->default('service'); // service, construction, maintenance, etc.
            
            // Document Storage
            $table->string('document_path')->nullable();
            $table->string('signed_document_path')->nullable();
            
            // Status and Workflow
            $table->enum('status', [
                'draft', 
                'pending_review', 
                'awaiting_signatures', 
                'partially_signed', 
                'fully_signed', 
                'active', 
                'completed', 
                'terminated', 
                'expired'
            ])->default('draft');
            
            // Signature Tracking
            $table->json('required_signatories')->nullable(); // Array of users who need to sign
            $table->json('signatures')->nullable(); // Array of signature data
            $table->integer('signatures_required')->default(1);
            $table->integer('signatures_received')->default(0);
            
            // Approval Workflow
            $table->json('approval_workflow')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            
            // Timestamps
            $table->timestamp('sent_for_signature_at')->nullable();
            $table->timestamp('fully_signed_at')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            // Additional metadata
            $table->text('terms_and_conditions')->nullable();
            $table->json('metadata')->nullable();
            $table->boolean('is_template')->default(false);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('status');
            $table->index('contract_number');
            $table->index(['project_id', 'status']);
        });
        
        // Signature events log table
        Schema::create('contract_signature_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('event_type'); // signature_requested, signed, declined, viewed, etc.
            $table->string('signature_data')->nullable(); // Base64 signature image or signature hash
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->text('comments')->nullable();
            $table->timestamps();
            
            $table->index(['contract_id', 'event_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
