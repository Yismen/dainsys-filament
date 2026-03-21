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
            $table->index(['date']);
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
            $table->dropIndex(['date']);
            $table->dropIndex('productions_employee_id_date_index');
            $table->dropIndex('productions_campaign_id_date_index');
            $table->dropIndex('productions_supervisor_id_date_index');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropIndex('employees_full_name_index');
        });
    }
};
