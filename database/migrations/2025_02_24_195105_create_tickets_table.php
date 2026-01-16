<?php

use App\Enums\TicketPriorities;
use App\Enums\TicketRoles;
use App\Enums\TicketStatuses;
use App\Models\Role;
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
        Schema::create('tickets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('owner_id')->constrained('users')->cascadeOnDelete();
            $table->string('subject', 300);
            $table->text('description');
            $table->string('status')->default(TicketStatuses::Pending->value);
            $table->foreignUuid('assigned_to')->nullable()->constrained('users')->cascadeOnDelete();
            $table->dateTime('assigned_at')->nullable();
            $table->text('images')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->string('priority')->default(TicketPriorities::Normal->value);
            $table->dateTime('expected_at')->nullable();
            $table->string('reference', 50)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Role::create([
            'name' => TicketRoles::Admin,
        ]);

        Role::create([
            'name' => TicketRoles::Operator,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
