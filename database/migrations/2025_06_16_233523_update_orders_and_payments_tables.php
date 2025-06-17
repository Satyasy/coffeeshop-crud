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
        // Update payments table to include payment method details
        Schema::table('payments', function (Blueprint $table) {
            $table->string('payment_method', 50)->change(); // Keep existing column
            $table->string('bank_name', 50)->nullable()->after('payment_method');
            $table->string('ewallet_type', 50)->nullable()->after('bank_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('bank_name');
            $table->dropColumn('ewallet_type');
        });
    }
};