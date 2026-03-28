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
        Schema::create('evaluation_question_scores', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('evaluation_id')->constrained('evaluations')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignUuid('qa_question_id')->constrained('qa_questions')->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedInteger('points_awarded')->default(0);
            $table->unsignedInteger('max_points_snapshot');
            $table->text('evaluator_note')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('evaluation_id');
            $table->index('qa_question_id');
            $table->unique(['evaluation_id', 'qa_question_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_question_scores');
    }
};
