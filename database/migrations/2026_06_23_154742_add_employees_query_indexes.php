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
        Schema::table('employees', function (Blueprint $table) {
            $table->index('full_name', 'employees_full_name_idx');
            $table->index('status', 'employees_status_idx');
            $table->index('deleted_at', 'employees_deleted_at_idx');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropIndex('employees_full_name_idx');
            $table->dropIndex('employees_status_idx');
            $table->dropIndex('employees_deleted_at_idx');
        });
    }
};
