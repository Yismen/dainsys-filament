<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_agents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 255);
            $table->uuid('project_id');
            $table->string('phone', 20)->nullable();
            $table->string('email', 200)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_agents');
    }
};
