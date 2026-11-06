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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category'); // materials, labor, equipment, permits, subcontractor, utilities, other
            $table->decimal('amount', 15, 2);
            $table->date('date');
            $table->string('vendor')->nullable();
            $table->string('payment_method')->nullable(); // cash, check, credit_card, bank_transfer
            $table->string('status')->default('pending'); // pending, paid, overdue, cancelled
            $table->string('receipt_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
