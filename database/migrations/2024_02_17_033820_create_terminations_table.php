<?php

use App\Models\Employee;
use App\Models\TerminationType;
use App\Models\TerminationReason;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terminations', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignIdFor(Employee::class)->constrained('employees')->cascadeOnDelete();
            $table->foreignIdFor(TerminationType::class)->constrained('termination_types')->cascadeOnDelete();
            $table->foreignIdFor(TerminationReason::class)->constrained('termination_reasons')->cascadeOnDelete();
            $table->boolean('rehireable')->default(true);
            $table->text('comments')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('terminations');
    }
};
