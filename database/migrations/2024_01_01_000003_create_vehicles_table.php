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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->string('plate_number');
            $table->enum('vehicle_type', ['truck', 'van', 'car', 'bus', 'motorcycle'])->default('car');
            $table->string('make');
            $table->string('model');
            $table->year('year');
            $table->enum('fuel_type', ['diesel', 'petrol', 'hybrid', 'electric'])->default('petrol');
            $table->decimal('tank_capacity', 8, 2)->nullable(); // in liters
            $table->integer('current_mileage')->default(0);
            $table->string('driver_name')->nullable();
            $table->string('driver_phone')->nullable();
            $table->enum('status', ['active', 'inactive', 'maintenance', 'retired'])->default('active');
            $table->date('registration_expiry')->nullable();
            $table->date('insurance_expiry')->nullable();
            $table->date('last_service_date')->nullable();
            $table->date('next_service_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['client_id', 'plate_number']);
            $table->index(['client_id', 'status']);
            $table->index('fuel_type');
            $table->index('registration_expiry');
            $table->index('insurance_expiry');
            $table->index('next_service_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
