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
        Schema::create('informations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('phone', 50);
            $table->string('email', 200)->nullable();
            $table->string('photos', 2000)->nullable();
            $table->text('address')->nullable();
            $table->string('company_id', 200)->comment('Internal company id, like a punch id. Can also be the company tax id or any other unique identifier')->nullable();
            $table->string('informationable_id');
            $table->string('informationable_type', 200);
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
        Schema::dropIfExists('informations');
    }
};
