<?php

use App\Models\Employee;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('overnight_hours', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignIdFor(Employee::class);
            $table->double('hours', 15, 8);
            $table->unique(['date', 'employee_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('overnight_hours');
    }
};
