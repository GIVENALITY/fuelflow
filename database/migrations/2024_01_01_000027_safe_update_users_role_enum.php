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
        // First, let's see what roles currently exist and update them safely
        $existingRoles = DB::table('users')->distinct()->pluck('role')->toArray();
        
        // Update any problematic role values to valid ones
        $roleMappings = [
            'fuel_pumper' => 'station_attendant',
            'pumper' => 'station_attendant', 
            'attendant' => 'station_attendant',
            'manager' => 'station_manager',
            'superadmin' => 'super_admin',
            'super-admin' => 'super_admin',
        ];
        
        foreach ($roleMappings as $oldRole => $newRole) {
            if (in_array($oldRole, $existingRoles)) {
                DB::table('users')->where('role', $oldRole)->update(['role' => $newRole]);
            }
        }
        
        // Now safely update the enum
        try {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'admin', 'station_manager', 'station_attendant', 'treasury', 'client') DEFAULT 'client'");
        } catch (Exception $e) {
            // If that fails, try a more conservative approach
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'station_manager', 'station_attendant', 'treasury', 'client') DEFAULT 'client'");
        }
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
