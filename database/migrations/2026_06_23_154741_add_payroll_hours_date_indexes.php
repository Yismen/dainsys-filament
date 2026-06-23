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
        Schema::table('payroll_hours', function (Blueprint $table) {
            $table->index('date', 'payroll_hours_date_idx');
            $table->index('week_ending_at', 'payroll_hours_week_ending_at_idx');
            $table->index('payroll_ending_at', 'payroll_hours_payroll_ending_at_idx');
            $table->index('deleted_at', 'payroll_hours_deleted_at_idx');
        });
    }

    public function down(): void
    {
        Schema::table('payroll_hours', function (Blueprint $table) {
            $table->dropIndex('payroll_hours_date_idx');
            $table->dropIndex('payroll_hours_week_ending_at_idx');
            $table->dropIndex('payroll_hours_payroll_ending_at_idx');
            $table->dropIndex('payroll_hours_deleted_at_idx');
        });
    }
};
