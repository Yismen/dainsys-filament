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
        Schema::create('productions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('date');
            $table->foreignUuid('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('campaign_id')->constrained()->cascadeOnDelete();
            $table->string('revenue_type')->nullable();
            $table->foreignUuid('supervisor_id')->nullable()->constrained()->cascadeOnDelete();
            $table->float('revenue_rate', 10)->unsigned()->default(0);
            $table->float('sph_goal', 10)->unsigned()->default(0);
            $table->float('conversions', 10)->unsigned()->default(0);
            $table->float('total_time', 10)->unsigned()->default(0);
            $table->float('production_time', 10)->unsigned()->default(0);
            $table->float('talk_time', 10)->unsigned()->default(0);
            $table->float('billable_time', 10)->unsigned()->default(0);
            $table->float('revenue', 10)->unsigned()->default(0);
            $table->dateTime('converted_to_payroll_at')->nullable();

            $table->unique(['date', 'campaign_id', 'employee_id']);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productions');
    }
};
