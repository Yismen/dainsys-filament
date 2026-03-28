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
        Schema::table('downtimes', function (Blueprint $table) {
            $table->dropUnique(['date', 'campaign_id', 'employee_id']);
            $table->unique(['date', 'campaign_id', 'employee_id', 'downtime_reason_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('downtimes', function (Blueprint $table) {
            $table->dropUnique(['date', 'campaign_id', 'employee_id', 'downtime_reason_id']);
            $table->unique(['date', 'campaign_id', 'employee_id']);
        });
    }
};
