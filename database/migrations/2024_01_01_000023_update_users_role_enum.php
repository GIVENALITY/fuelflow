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
        // First, update any existing users with invalid role values
        DB::table('users')->where('role', 'fuel_pumper')->update(['role' => 'station_attendant']);
        DB::table('users')->where('role', 'pumper')->update(['role' => 'station_attendant']);
        DB::table('users')->where('role', 'attendant')->update(['role' => 'station_attendant']);
        DB::table('users')->where('role', 'manager')->update(['role' => 'station_manager']);
        DB::table('users')->where('role', 'superadmin')->update(['role' => 'super_admin']);
        DB::table('users')->where('role', 'super-admin')->update(['role' => 'super_admin']);
        
        // Now update the role enum to include super_admin
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
