<?php

use App\Models\Afp;
use App\Models\Ars;
use App\Models\Site;
use App\Enums\Gender;
use App\Models\Project;
use App\Models\Position;
use App\Models\Supervisor;
use App\Models\Citizenship;
use App\Enums\MaritalStatus;
use App\Enums\EmployeeStatus;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('second_first_name')->nullable();
            $table->string('last_name');
            $table->string('second_last_name')->nullable();
            $table->string('full_name')->nullable();
            $table->string('personal_id', 11)->unique();
            $table->dateTime('hired_at');
            $table->date('date_of_birth');
            $table->string('cellphone', 20)->unique();
            $table->enum('status', EmployeeStatus::all());
            $table->enum('marriage', MaritalStatus::all());
            $table->enum('gender', Gender::all());
            $table->boolean('kids')->default(false);
            $table->foreignIdFor(Site::class)->constrained('sites');
            $table->foreignIdFor(Project::class)->constrained('projects');
            $table->foreignIdFor(Position::class)->constrained('positions');
            $table->foreignIdFor(Citizenship::class)->constrained('citizenships');
            $table->foreignIdFor(Supervisor::class)->nullable()->constrained('supervisors');
            $table->foreignIdFor(Afp::class)->nullable()->constrained('afps');
            $table->foreignIdFor(Ars::class)->nullable()->constrained('arss');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
