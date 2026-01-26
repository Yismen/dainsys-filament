<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('h_r_activity_requests', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('supervisor_id')->constrained()->cascadeOnDelete();
            $table->string('activity_type');
            $table->text('description')->nullable();
            $table->timestamp('requested_at')->useCurrent();
            $table->string('status')->default('Requested');
            $table->text('completion_comment')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('h_r_activity_requests');
    }
};
