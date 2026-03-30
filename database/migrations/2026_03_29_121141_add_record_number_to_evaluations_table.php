<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->string('record_number')->nullable()->after('id');
        });

        DB::table('evaluations')->orderBy('created_at')->each(function (object $evaluation) {
            DB::table('evaluations')
                ->where('id', $evaluation->id)
                ->update(['record_number' => 'EVAL-'.strtoupper(Str::random(8))]);
        });

        Schema::table('evaluations', function (Blueprint $table) {
            $table->string('record_number')->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->dropUnique(['record_number']);
            $table->dropColumn('record_number');
        });
    }
};
