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
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('type'); // milestone_payment, initial_deposit, final_payment, change_order, bonus, other
            $table->decimal('amount', 15, 2);
            $table->date('date');
            $table->date('due_date')->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('payment_method')->nullable(); // cash, check, credit_card, bank_transfer, financing
            $table->string('status')->default('pending'); // pending, received, overdue, partial, cancelled
            $table->decimal('received_amount', 15, 2)->default(0);
            $table->date('received_date')->nullable();
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
