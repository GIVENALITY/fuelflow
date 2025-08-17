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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'station_manager', 'fuel_pumper', 'treasury', 'client'])->default('client')->after('email');
            $table->foreignId('station_id')->nullable()->constrained('stations')->onDelete('set null')->after('role');
            $table->string('phone')->nullable()->after('station_id');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->after('phone');
            $table->text('two_factor_secret')->nullable()->after('status');
            $table->text('two_factor_recovery_codes')->nullable()->after('two_factor_secret');
            $table->timestamp('two_factor_confirmed_at')->nullable()->after('two_factor_recovery_codes');
            
            $table->index(['role', 'status']);
            $table->index('station_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['station_id']);
            $table->dropIndex(['role', 'status']);
            $table->dropIndex(['station_id']);
            
            $table->dropColumn([
                'role',
                'station_id',
                'phone',
                'status',
                'two_factor_secret',
                'two_factor_recovery_codes',
                'two_factor_confirmed_at'
            ]);
        });
    }
};
