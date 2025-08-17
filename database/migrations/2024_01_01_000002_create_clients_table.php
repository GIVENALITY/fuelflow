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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('company_name');
            $table->string('contact_person');
            $table->string('email');
            $table->string('phone');
            $table->text('address');
            $table->string('city');
            $table->string('state');
            $table->string('zip_code');
            $table->string('country')->default('US');
            $table->decimal('credit_limit', 15, 2)->default(0);
            $table->decimal('current_balance', 15, 2)->default(0);
            $table->integer('payment_terms')->default(30); // days
            $table->enum('status', ['active', 'suspended', 'credit_hold', 'payment_review', 'inactive'])->default('active');
            $table->foreignId('account_manager_id')->nullable()->constrained('users')->onDelete('set null');
            $table->json('preferred_stations')->nullable();
            $table->text('special_instructions')->nullable();
            $table->string('tax_id')->nullable();
            $table->string('business_license')->nullable();
            $table->date('contract_start_date')->nullable();
            $table->date('contract_end_date')->nullable();
            $table->timestamps();
            
            $table->index(['status', 'account_manager_id']);
            $table->index('email');
            $table->index('company_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
