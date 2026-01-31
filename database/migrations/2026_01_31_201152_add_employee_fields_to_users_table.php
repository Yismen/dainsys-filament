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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignUuid('employee_id')->nullable()->constrained()->cascadeOnDelete();
            $table->timestamp('password_set_at')->nullable();
            $table->boolean('force_password_change')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeignIdFor('Employee');
            $table->dropColumn(['employee_id', 'password_set_at', 'force_password_change']);
        });
    }
};
