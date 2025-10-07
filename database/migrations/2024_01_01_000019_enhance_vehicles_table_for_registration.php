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
        Schema::table('vehicles', function (Blueprint $table) {
            // Add document upload fields
            $table->string('head_card_path')->nullable()->after('driver_phone');
            $table->string('trailer_card_path')->nullable()->after('head_card_path');
            
            // Update vehicle type to include tractor and trailer
            $table->enum('vehicle_type', ['truck', 'van', 'car', 'bus', 'motorcycle', 'tractor', 'trailer'])->default('car')->change();
            
            // Add soft delete
            $table->softDeletes()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn([
                'head_card_path',
                'trailer_card_path',
                'deleted_at'
            ]);
            
            // Revert vehicle type
            $table->enum('vehicle_type', ['truck', 'van', 'car', 'bus', 'motorcycle'])->default('car')->change();
        });
    }
};
