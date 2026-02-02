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
        $tableName = config('activitylog.table_name', 'activity_log');

        Schema::connection(config('activitylog.database_connection'))->table($tableName, function (Blueprint $table) {
            $table->uuid('subject_id')->nullable()->change();
            $table->string('subject_type')->nullable()->change();

            $table->uuid('causer_id')->nullable()->change();
            $table->string('causer_type')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableName = config('activitylog.table_name', 'activity_log');

        Schema::connection(config('activitylog.database_connection'))->table($tableName, function (Blueprint $table) {
            $table->nullableMorphs('subject', 'subject');
            $table->nullableMorphs('causer', 'causer');
        });
    }
};
