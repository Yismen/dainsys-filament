<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('number', 100)->nullable();
            $table->date('date')->nullable();
            $table->uuid('project_id')->nullable();
            $table->uuid('agent_id')->nullable();
            $table->uuid('campaign_id')->nullable();
            $table->json('items')->nullable();
            $table->decimal('subtotal_amount', 12, 2)->nullable();
            $table->decimal('tax_amount', 12, 2)->nullable();
            $table->decimal('total_amount', 12, 2)->nullable();
            $table->decimal('total_paid', 12, 2)->nullable();
            $table->decimal('balance_pending', 12, 2)->nullable();
            $table->string('status')->nullable();
            $table->date('due_date')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null');
            $table->foreign('agent_id')->references('id')->on('invoice_agents')->onDelete('set null');
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
