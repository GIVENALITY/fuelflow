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
        Schema::create('fuel_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->foreignId('station_id')->constrained('stations')->onDelete('cascade');
            $table->enum('fuel_type', ['diesel', 'petrol'])->default('petrol');
            $table->decimal('quantity_requested', 8, 2);
            $table->decimal('quantity_dispensed', 8, 2)->nullable();
            $table->decimal('unit_price', 8, 2);
            $table->decimal('total_amount', 10, 2);
            $table->timestamp('request_date');
            $table->date('preferred_date');
            $table->date('due_date');
            $table->enum('status', ['pending', 'approved', 'rejected', 'in_progress', 'dispensed', 'completed', 'cancelled'])->default('pending');
            $table->enum('urgency_level', ['standard', 'priority', 'emergency'])->default('standard');
            $table->text('special_instructions')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('assigned_pumper_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('dispensed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('dispensed_at')->nullable();
            $table->unsignedBigInteger('receipt_id')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['client_id', 'status']);
            $table->index(['station_id', 'status']);
            $table->index(['assigned_pumper_id', 'status']);
            $table->index('request_date');
            $table->index('preferred_date');
            $table->index('due_date');
            $table->index('urgency_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fuel_requests');
    }
};
