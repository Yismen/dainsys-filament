<?php

use App\Models\User;
use App\Enums\TicketStatuses;
use App\Enums\TicketPriorities;
use App\Models\TicketDepartment;
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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'owner_id')->constrained('users')->cascadeOnDelete();
            $table->foreignIdFor(TicketDepartment::class, 'department_id')->constrained('ticket_departments')->cascadeOnDelete();
            $table->string('subject', 300);
            $table->text('description');
            $table->string('status')->default(TicketStatuses::Pending->value);
            $table->foreignIdFor(User::class, 'assigned_to')->nullable()->constrained('users')->cascadeOnDelete();
            $table->dateTime('assigned_at')->nullable();
            $table->text('images')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->string('priority')->default(TicketPriorities::Normal->value);
            $table->dateTime('expected_at')->nullable();
            $table->string('reference', 50)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
