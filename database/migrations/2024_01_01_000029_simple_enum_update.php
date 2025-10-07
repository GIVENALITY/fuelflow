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
        // Simply add the missing enum values without updating existing data
        try {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'station_manager', 'station_attendant', 'treasury', 'client', 'super_admin') DEFAULT 'client'");
        } catch (Exception $e) {
            // If that fails, just add super_admin to the existing enum
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'station_manager', 'station_attendant', 'treasury', 'client', 'super_admin') DEFAULT 'client'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to the original enum
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'station_manager', 'station_attendant', 'treasury', 'client') DEFAULT 'client'");
    }
};
