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
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('start_location_id')->constrained('locations')->onDelete('cascade');
            $table->foreignId('end_location_id')->constrained('locations')->onDelete('cascade');
            $table->decimal('total_distance', 8, 2)->nullable(); // in kilometers
            $table->integer('estimated_duration')->nullable(); // in minutes
            $table->enum('status', ['draft', 'active', 'inactive', 'maintenance'])->default('draft');
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['status', 'is_active']);
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};
