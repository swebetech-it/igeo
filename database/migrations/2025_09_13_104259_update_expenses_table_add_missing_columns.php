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
        Schema::table('expenses', function (Blueprint $table) {
            // Add missing columns that the model expects
            $table->string('invoice_number')->nullable()->after('vendor');
            $table->string('receipt_file')->nullable()->after('invoice_number');
            $table->foreignId('approved_by')->nullable()->constrained('users')->after('notes');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn(['invoice_number', 'receipt_file', 'approved_by', 'approved_at']);
        });
    }
};
