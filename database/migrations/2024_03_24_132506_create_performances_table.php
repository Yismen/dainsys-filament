<?php

use App\Models\Campaign;
use App\Models\Employee;
use App\Models\Supervisor;
use App\Models\DowntimeReason;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('performances', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id')->unique();
            $table->string('file')->index();
            $table->date('date');
            $table->foreignIdFor(Employee::class);
            $table->foreignIdFor(Campaign::class);
            $table->double('campaign_goal', 15, 8)->unsigned()->default(0);
            $table->double('login_time', 15, 8)->unsigned()->default(0);
            $table->double('production_time', 15, 8)->unsigned()->default(0);
            $table->double('talk_time', 15, 8)->unsigned()->default(0);
            $table->double('billable_time', 15, 8)->unsigned()->default(0);
            $table->integer('attempts')->unsigned()->default(0);
            $table->integer('contacts')->unsigned()->default(0);
            $table->integer('successes')->unsigned()->default(0);
            $table->integer('upsales')->unsigned()->default(0);
            $table->double('revenue', 15, 8)->unsigned()->default(0);
            $table->foreignIdFor(DowntimeReason::class)->nullable();
            $table->foreignIdFor(Supervisor::class, 'reporter_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performances');
    }
};
