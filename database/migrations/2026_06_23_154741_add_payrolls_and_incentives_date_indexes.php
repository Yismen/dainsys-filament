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
        Schema::table('payrolls', function (Blueprint $table) {
            $table->index('payable_date', 'payrolls_payable_date_idx');
            $table->index('deleted_at', 'payrolls_deleted_at_idx');
        });

        Schema::table('incentives', function (Blueprint $table) {
            $table->index('payable_date', 'incentives_payable_date_idx');
            $table->index('deleted_at', 'incentives_deleted_at_idx');
        });
    }

    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropIndex('payrolls_payable_date_idx');
            $table->dropIndex('payrolls_deleted_at_idx');
        });

        Schema::table('incentives', function (Blueprint $table) {
            $table->dropIndex('incentives_payable_date_idx');
            $table->dropIndex('incentives_deleted_at_idx');
        });
    }
};
