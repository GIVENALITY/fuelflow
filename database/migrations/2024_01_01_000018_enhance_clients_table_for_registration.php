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
        Schema::table('clients', function (Blueprint $table) {
            // Add registration status
            $table->enum('registration_status', ['pending', 'approved', 'rejected', 'active'])->default('pending')->after('status');
            
            // Add document upload fields
            $table->string('tin_document_path')->nullable()->after('tax_id');
            $table->string('brela_certificate_path')->nullable()->after('tin_document_path');
            $table->string('business_license_path')->nullable()->after('brela_certificate_path');
            $table->string('director_id_path')->nullable()->after('business_license_path');
            
            // Add contract management fields
            $table->boolean('contract_sent')->default(false)->after('director_id_path');
            $table->timestamp('contract_sent_at')->nullable()->after('contract_sent');
            $table->string('signed_contract_path')->nullable()->after('contract_sent_at');
            $table->timestamp('contract_signed_at')->nullable()->after('signed_contract_path');
            
            // Add approval fields
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->after('contract_signed_at');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->text('approval_notes')->nullable()->after('approved_at');
            
            // Add soft delete
            $table->softDeletes()->after('updated_at');
            
            $table->index(['registration_status', 'approved_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropIndex(['registration_status', 'approved_by']);
            
            $table->dropColumn([
                'registration_status',
                'tin_document_path',
                'brela_certificate_path',
                'business_license_path',
                'director_id_path',
                'contract_sent',
                'contract_sent_at',
                'signed_contract_path',
                'contract_signed_at',
                'approved_by',
                'approved_at',
                'approval_notes',
                'deleted_at'
            ]);
        });
    }
};
