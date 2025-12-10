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
        Schema::table('orders', function (Blueprint $table) {
            // Add customer token for session tracking
            $table->string('customer_token')->nullable()->index()->after('table_number');
            
            // Modify status enum to use pending/paid instead of unpaid/paid
            // Note: For existing data, you may need to update 'unpaid' to 'pending' manually
            $table->dropColumn('status');
        });
        
        // Re-add status column with new enum values
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', ['pending', 'paid', 'expired', 'cancelled'])->default('pending')->after('total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('customer_token');
            $table->dropColumn('status');
        });
        
        // Restore original status enum
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', ['unpaid', 'paid', 'expired', 'cancelled'])->default('unpaid');
        });
    }
};
