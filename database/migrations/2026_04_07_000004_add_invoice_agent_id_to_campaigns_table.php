<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->uuid('invoice_agent_id')->nullable()->after('description');
            $table->foreign('invoice_agent_id')->references('id')->on('invoice_agents')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropForeign(['invoice_agent_id']);
            $table->dropColumn(['invoice_agent_id']);
        });
    }
};
