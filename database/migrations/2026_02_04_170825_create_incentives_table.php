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
        Schema::create('incentives', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('payable_date');
            $table->foreignUuid('employee_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('project_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->decimal('total_production_hours', 12, 2)->default(0);
            $table->decimal('total_sales', 12, 2)->default(0);
            $table->decimal('amount', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incentives');
    }
};
