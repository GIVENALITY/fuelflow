<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update the vehicle_type enum to include tractor and trailer
        DB::statement("ALTER TABLE vehicles MODIFY COLUMN vehicle_type ENUM('truck', 'van', 'car', 'bus', 'motorcycle', 'tractor', 'trailer') DEFAULT 'car'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to the original enum without tractor and trailer
        DB::statement("ALTER TABLE vehicles MODIFY COLUMN vehicle_type ENUM('truck', 'van', 'car', 'bus', 'motorcycle') DEFAULT 'car'");
    }
};
