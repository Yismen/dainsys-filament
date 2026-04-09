<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 255);
            $table->uuid('campaign_id');
            $table->decimal('price', 16, 13);
            $table->text('description')->nullable();
            // $table->string('image')->nullable();
            // $table->string('category')->nullable();
            // $table->string('brand')->nullable();
            // $table->string('sku')->nullable();
            // $table->string('barcode')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
