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
        // Add soft deletes to users table
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');
        });

        // Add soft deletes to stations table
        Schema::table('stations', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');
        });

        // Add soft deletes to fuel_requests table
        Schema::table('fuel_requests', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');
        });

        // Add soft deletes to receipts table
        Schema::table('receipts', function (Blueprint $table) {
            $table->softDeletes()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('stations', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('fuel_requests', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('receipts', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
