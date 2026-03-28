<?php

use App\Enums\EvaluationStatuses;
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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('evaluation_date');
            $table->foreignUuid('employee_id')->constrained('employees')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignUuid('supervisor_id')->nullable()->constrained('supervisors')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('evaluator_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignUuid('qa_form_id')->constrained('qa_forms')->cascadeOnUpdate()->cascadeOnDelete();
            $table->decimal('threshold_percentage', 5, 2);
            $table->unsignedInteger('points_possible')->default(0);
            $table->unsignedInteger('points_achieved')->default(0);
            $table->decimal('success_percentage', 6, 3)->default(0);
            $table->string('status')->default(EvaluationStatuses::Draft->value);
            $table->text('comments')->nullable();
            $table->text('employee_decision_comment')->nullable();
            $table->text('manager_resolution_comment')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('disputed_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('status');
            $table->index('evaluation_date');
            $table->index('employee_id');
            $table->index('evaluator_id');
            $table->index('qa_form_id');
            $table->index('supervisor_id');
            $table->index(['evaluator_id', 'status', 'evaluation_date']);
            $table->index(['employee_id', 'status', 'evaluation_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
