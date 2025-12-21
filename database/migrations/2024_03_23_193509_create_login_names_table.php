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
        Schema::create('login_names', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('employee_id')->constrained()->cascadeOnDelete();
            $table->string('login_name', 1000);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_names');
    }
};
