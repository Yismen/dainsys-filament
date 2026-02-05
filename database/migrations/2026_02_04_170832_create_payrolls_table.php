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
            $table->decimal('salary_rate', 12, 2)->default(0);
            $table->decimal('total_hours', 12, 2)->default(0);
            $table->decimal('salary_income', 12, 2)->default(0);
            $table->decimal('medical_licence', 12, 2)->default(0);
            $table->decimal('gross_income', 12, 2)->default(0);
            $table->decimal('deduction_ars', 12, 2)->default(0);
            $table->decimal('deduction_afp', 12, 2)->default(0);
            $table->decimal('deductions_other', 12, 2)->default(0);
            $table->decimal('total_deductions', 12, 2)->default(0);
            $table->decimal('nightly_incomes', 12, 2)->default(0);
            $table->decimal('overtime_incomes', 12, 2)->default(0);
            $table->decimal('holiday_incomes', 12, 2)->default(0);
            $table->decimal('additional_incentives_2', 12, 2)->default(0);
            $table->decimal('additional_incentives_1', 12, 2)->default(0);
            $table->decimal('net_payroll', 12, 2)->default(0);
            $table->decimal('total_payroll', 12, 2)->default(0);
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
