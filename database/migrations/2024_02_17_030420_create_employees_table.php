<?php

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
        Schema::create('employees', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('first_name');
            $table->string('second_first_name')->nullable();
            $table->string('last_name');
            $table->string('second_last_name')->nullable();
            $table->string('full_name')->nullable();
            $table->string('personal_id_type');
            $table->string('personal_id', 11)->unique();
            $table->date('date_of_birth');
            $table->string('cellphone', 20)->unique();
            $table->string('secondary_phone', 20)->nullable();
            $table->string('email', 200)->nullable()->unique();
            $table->text('address');
            $table->string('status')->nullable();
            // $table->string('marriage')->default(MaritalStatus::Single);
            $table->string('gender');
            $table->boolean('has_kids')->default(false);
            $table->foreignUuid('citizenship_id')->constrained('citizenships')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('site_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('project_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('position_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('supervisor_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->dateTime('hired_at')->nullable();
            $table->string('internal_id')->nullable()->unique();
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
        Schema::dropIfExists('employees');
    }
};
