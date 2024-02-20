<?php

use App\Models\Department;
use App\Models\PaymentType;
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
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 500)->unique();
            $table->foreignIdFor(Department::class)->constrained('departments')->cascadeOnDelete();
            $table->foreignIdFor(PaymentType::class)->constrained('payment_types')->cascadeOnDelete();
            $table->integer('salary')->unsigned();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('positions');
    }
};
