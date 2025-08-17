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
        Schema::create('stations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('address');
            $table->string('city');
            $table->string('state');
            $table->string('zip_code');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->foreignId('manager_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['active', 'inactive', 'maintenance', 'closed'])->default('active');
            $table->decimal('capacity_diesel', 10, 2)->default(0);
            $table->decimal('capacity_petrol', 10, 2)->default(0);
            $table->decimal('current_diesel_level', 10, 2)->default(0);
            $table->decimal('current_petrol_level', 10, 2)->default(0);
            $table->json('operating_hours')->nullable();
            $table->string('timezone')->default('UTC');
            $table->timestamps();
            
            $table->index(['status', 'manager_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stations');
    }
};
