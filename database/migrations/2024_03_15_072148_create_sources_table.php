<?php

use App\Models\Source;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sources', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 500)->unique();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        if(app()->environment() !== 'testing') {
            $this->seedSourcesTable();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sources');
    }

    protected function seedSourcesTable()
    {
        Source::create([
            'name' => 'Data Entry',
        ]);

        Source::create([
            'name' => 'Chat',
        ]);

        Source::create([
            'name' => 'Email',
        ]);

        Source::create([
            'name' => 'Escalation',
        ]);

        Source::create([
            'name' => 'QA Review',
        ]);

        Source::create([
            'name' => 'Resubmission',
        ]);

        Source::create([
            'name' => 'Downtime',
        ]);

        Source::create([
            'name' => 'Training',
        ]);
    }
};
