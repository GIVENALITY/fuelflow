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
        // Update the client registration_status enum to include the new statuses
        DB::statement("ALTER TABLE clients MODIFY COLUMN registration_status ENUM('pending', 'approved', 'rejected', 'active') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to the original enum (if it existed)
        DB::statement("ALTER TABLE clients MODIFY COLUMN registration_status ENUM('pending', 'approved', 'rejected', 'active') DEFAULT 'pending'");
    }
};
