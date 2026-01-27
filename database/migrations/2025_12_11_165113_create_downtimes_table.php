<?php

use App\Enums\DowntimeStatuses;
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
            $table->string('unique_id', 250)->unique()->nullable();
            $table->foreignUuid('employee_id')->constrained('employees')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('campaign_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('downtime_reason_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->float('total_time');
            $table->string('status')->default(DowntimeStatuses::Pending->value);
            $table->foreignUuid('requester_id')->nullable()->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('aprover_id')->nullable()->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->dateTime('converted_to_payroll_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['date', 'campaign_id', 'employee_id']);
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
