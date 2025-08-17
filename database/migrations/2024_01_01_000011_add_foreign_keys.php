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
        Schema::table('fuel_requests', function (Blueprint $table) {
            $table->foreign('receipt_id')->references('id')->on('receipts')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fuel_requests', function (Blueprint $table) {
            $table->dropForeign(['receipt_id']);
        });
    }
};
