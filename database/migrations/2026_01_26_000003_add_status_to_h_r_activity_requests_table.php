<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('h_r_activity_requests', function (Blueprint $table): void {
            $table->string('status')->default('Requested')->after('activity_type');
            $table->text('completion_comment')->nullable()->after('description');
            $table->timestamp('completed_at')->nullable()->after('requested_at');
        });
    }

    public function down(): void
    {
        Schema::table('h_r_activity_requests', function (Blueprint $table): void {
            $table->dropColumn(['status', 'completion_comment', 'completed_at']);
        });
    }
};
