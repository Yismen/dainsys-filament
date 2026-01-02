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
            $table->foreignUuid('employee_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('site_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('project_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('position_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('supervisor_id')->constrained()->onDelete('cascade');
            $table->string('punch')->nullable();
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
