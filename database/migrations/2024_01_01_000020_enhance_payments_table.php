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
        Schema::table('payments', function (Blueprint $table) {
            // Add new fields for enhanced payment system
            $table->string('bank_name')->nullable()->after('payment_method');
            $table->string('proof_of_payment_path')->nullable()->after('bank_name');
            $table->foreignId('submitted_by')->nullable()->constrained('users')->onDelete('set null')->after('proof_of_payment_path');
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null')->after('submitted_by');
            $table->timestamp('verified_at')->nullable()->after('verified_by');
            $table->text('verification_notes')->nullable()->after('verified_at');
            
            // Update status enum
            $table->enum('status', ['pending', 'verified', 'rejected', 'completed', 'failed', 'cancelled'])->default('pending')->change();
            
            // Add soft delete
            $table->softDeletes()->after('updated_at');
            
            $table->index(['status', 'submitted_by']);
            $table->index(['status', 'verified_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['submitted_by']);
            $table->dropForeign(['verified_by']);
            $table->dropIndex(['status', 'submitted_by']);
            $table->dropIndex(['status', 'verified_by']);
            
            $table->dropColumn([
                'bank_name',
                'proof_of_payment_path',
                'submitted_by',
                'verified_by',
                'verified_at',
                'verification_notes',
                'deleted_at'
            ]);
            
            // Revert status enum
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('pending')->change();
        });
    }
};
