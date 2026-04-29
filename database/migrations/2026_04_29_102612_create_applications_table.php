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
        Schema::create('applications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('applicant_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('job_opening_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('status')->default('Applied');
            $table->text('notes')->nullable();
            $table->date('applied_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
