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
        Schema::create('qa_forms', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 150);
            $table->foreignUuid('project_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->decimal('passing_threshold_percentage', 5, 2);
            $table->text('description')->nullable();
            $table->foreignUuid('created_by')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();

            $table->index('project_id');
            $table->index('created_by');
            $table->unique(['project_id', 'name', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qa_forms');
    }
};
