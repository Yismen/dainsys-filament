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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('payable_date');
            $table->foreignUuid('employee_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->decimal('gross_income', 12, 2)->default(0);
            $table->decimal('taxable_payroll', 12, 2)->default(0);
            $table->decimal('hourly_rate', 12, 2)->default(0);
            $table->decimal('regular_hours', 12, 2)->default(0);
            $table->decimal('overtime_hours', 12, 2)->default(0);
            $table->decimal('holiday_hours', 12, 2)->default(0);
            $table->decimal('night_shift_hours', 12, 2)->default(0);
            $table->decimal('additional_incentives_1', 12, 2)->default(0);
            $table->decimal('additional_incentives_2', 12, 2)->default(0);
            $table->decimal('deduction_afp', 12, 2)->default(0);
            $table->decimal('deduction_ars', 12, 2)->default(0);
            $table->decimal('other_deductions', 12, 2)->default(0);
            $table->decimal('net_payroll', 12, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
