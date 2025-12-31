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
        Schema::create('payroll_hours', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('date');
            $table->foreignUuid('employee_id')->constrained()->onDelete('cascade');
            $table->date('week_ending_at')->nullable();
            $table->date('payroll_ending_at')->nullable();
            $table->float('total_hours')->default(0);
            $table->float('regular_hours')->default(0);
            $table->float('nightly_hours')->default(0);
            $table->float('overtime_hours')->default(0);
            $table->float('holiday_hours')->default(0);
            $table->float('day_off_hours')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_hours');
    }
};
