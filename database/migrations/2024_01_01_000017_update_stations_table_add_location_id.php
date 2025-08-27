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
        Schema::table('stations', function (Blueprint $table) {
            // Add location_id foreign key
            $table->foreignId('location_id')->nullable()->constrained('locations')->onDelete('set null');
            
            // Drop the old address columns
            $table->dropColumn(['address', 'city', 'state', 'zip_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stations', function (Blueprint $table) {
            // Drop location_id foreign key
            $table->dropForeign(['location_id']);
            $table->dropColumn('location_id');
            
            // Add back the old address columns
            $table->text('address');
            $table->string('city');
            $table->string('state');
            $table->string('zip_code');
        });
    }
};
