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
        // Get current enum values and add missing ones
        $this->addEnumValue('users', 'role', 'super_admin');
        $this->addEnumValue('vehicles', 'vehicle_type', 'tractor');
        $this->addEnumValue('vehicles', 'vehicle_type', 'trailer');
        $this->addEnumValue('payments', 'status', 'verified');
        $this->addEnumValue('payments', 'status', 'rejected');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: MySQL doesn't support removing enum values easily
        // This would require recreating the table
    }
    
    private function addEnumValue($table, $column, $value)
    {
        try {
            // Get current enum values
            $result = DB::select("SHOW COLUMNS FROM {$table} LIKE '{$column}'");
            if (empty($result)) return;
            
            $enumString = $result[0]->Type;
            if (strpos($enumString, "'{$value}'") !== false) {
                // Value already exists
                return;
            }
            
            // Add the new value to the enum
            $newEnumString = str_replace(')', ", '{$value}')", $enumString);
            DB::statement("ALTER TABLE {$table} MODIFY COLUMN {$column} {$newEnumString}");
        } catch (Exception $e) {
            // Ignore errors for individual enum additions
        }
    }
};
