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
        Schema::create('qa_questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('qa_form_id')->constrained('qa_forms')->cascadeOnUpdate()->cascadeOnDelete();
            $table->text('text');
            $table->text('description')->nullable();
            $table->unsignedInteger('max_points');
            $table->unsignedInteger('display_order')->default(1);
            $table->boolean('is_active')->default(true);
            $table->foreignUuid('author_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();

            $table->index('qa_form_id');
            $table->index('author_id');
            $table->index('is_active');
            $table->index('display_order');
            $table->unique(['qa_form_id', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qa_questions');
    }
};
