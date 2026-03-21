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
        Schema::table('productions', function (Blueprint $table) {
            $table->index(['date'], 'productions_date_index');
            $table->index(['employee_id', 'date'], 'productions_employee_id_date_index');
            $table->index(['campaign_id', 'date'], 'productions_campaign_id_date_index');
            $table->index(['supervisor_id', 'date'], 'productions_supervisor_id_date_index');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->index('full_name', 'employees_full_name_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productions', function (Blueprint $table) {
            // Ensure foreign keys keep a supporting index when composite indexes are removed.
            if (! Schema::hasIndex('productions', ['employee_id'])) {
                $table->index('employee_id', 'productions_employee_id_rollback_index');
            }

            if (! Schema::hasIndex('productions', ['campaign_id'])) {
                $table->index('campaign_id', 'productions_campaign_id_rollback_index');
            }

            if (! Schema::hasIndex('productions', ['supervisor_id'])) {
                $table->index('supervisor_id', 'productions_supervisor_id_rollback_index');
            }

            if (Schema::hasIndex('productions', 'productions_date_index')) {
                $table->dropIndex('productions_date_index');
            }

            if (Schema::hasIndex('productions', 'productions_employee_id_date_index')) {
                $table->dropIndex('productions_employee_id_date_index');
            }

            if (Schema::hasIndex('productions', 'productions_campaign_id_date_index')) {
                $table->dropIndex('productions_campaign_id_date_index');
            }

            if (Schema::hasIndex('productions', 'productions_supervisor_id_date_index')) {
                $table->dropIndex('productions_supervisor_id_date_index');
            }
        });

        Schema::table('employees', function (Blueprint $table) {
            if (Schema::hasIndex('employees', 'employees_full_name_index')) {
                $table->dropIndex('employees_full_name_index');
            }
        });
    }
};
