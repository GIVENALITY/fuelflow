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
        // Update the role enum to include super_admin
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'admin', 'station_manager', 'station_attendant', 'treasury', 'client') DEFAULT 'client'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to the original enum without super_admin
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'station_manager', 'station_attendant', 'treasury', 'client') DEFAULT 'client'");
    }
};
