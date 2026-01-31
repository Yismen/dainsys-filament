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
        Schema::create('hires', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->dateTime('date');
            $table->foreignUuid('employee_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('site_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('project_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('position_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('supervisor_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hires');
    }
};
