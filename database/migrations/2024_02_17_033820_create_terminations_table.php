<?php

use App\Enums\TerminationTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->uuid('id')->primary();
            $table->date('date');
            $table->foreignUuid('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->text('termination_type')->default(TerminationTypes::Resignation);
            $table->boolean('is_rehireable')->default(true);
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
