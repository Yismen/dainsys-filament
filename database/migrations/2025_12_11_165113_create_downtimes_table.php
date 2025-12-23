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
        Schema::create('downtimes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('date');
            $table->foreignUuid('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignUuid('campaign_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('downtime_reason_id')->constrained()->onDelete('cascade');
            $table->float('time');
            $table->foreignUuid('requester_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignUuid('aprover_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->dateTime('converted_to_payroll_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('downtimes');
    }
};
