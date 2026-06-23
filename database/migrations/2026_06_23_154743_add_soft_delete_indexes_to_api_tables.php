<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productions', function (Blueprint $table) {
            $table->index('deleted_at', 'productions_deleted_at_idx');
        });

        Schema::table('campaigns', function (Blueprint $table) {
            $table->index('deleted_at', 'campaigns_deleted_at_idx');
        });

        Schema::table('login_names', function (Blueprint $table) {
            $table->index('deleted_at', 'login_names_deleted_at_idx');
        });

        Schema::table('dispositions', function (Blueprint $table) {
            $table->index('deleted_at', 'dispositions_deleted_at_idx');
        });

        Schema::table('holidays', function (Blueprint $table) {
            $table->index('deleted_at', 'holidays_deleted_at_idx');
        });
    }

    public function down(): void
    {
        Schema::table('productions', function (Blueprint $table) {
            $table->dropIndex('productions_deleted_at_idx');
        });

        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropIndex('campaigns_deleted_at_idx');
        });

        Schema::table('login_names', function (Blueprint $table) {
            $table->dropIndex('login_names_deleted_at_idx');
        });

        Schema::table('dispositions', function (Blueprint $table) {
            $table->dropIndex('dispositions_deleted_at_idx');
        });

        Schema::table('holidays', function (Blueprint $table) {
            $table->dropIndex('holidays_deleted_at_idx');
        });
    }
};
