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
        Schema::create('login_names', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Employee::class)->constrained()->cascadeOnDelete();
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
