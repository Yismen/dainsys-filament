<?php

use App\Models\Afp;
use App\Models\Ars;
use App\Models\Bank;
use App\Models\Site;
use App\Models\Employee;
use App\Models\Supervisor;
use App\Models\Information;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


test('information model interacts with db table', function () {
    $data = Information::factory()->create();

    $this->assertDatabaseHas('informations', $data->only([
        'phone', 'email', 'photo_url', 'address', 'company_id', 'informationable_id', 'informationable_type'
    ]));
});

test('information model uses soft delete', function () {
    $information = Information::factory()->create();

    $information->delete();

    $this->assertSoftDeleted(Information::class, $information->only(['id']));
});

test('information model morph to informationable', function () {
    $information = Information::factory()->create();

    expect($information->informationable())->toBeInstanceOf(MorphTo::class);
});

test('information model morph employee', function () {
    Mail::fake();
    $employee = Employee::factory()->create();
    $data = [
        'phone' => 'phone',
        'email' => 'email',
        'photo_url' => 'photo',
        'address' => 'address',
        'company_id' => 'asdfasdf',
    ];

    $employee->information()->create($data);

    $this->assertDatabaseHas('informations', $data);
    expect($employee->information)->not->toBeNull();
    expect($employee->information())->toBeInstanceOf(MorphOne::class);
    expect((new Information())->employee())->toBeInstanceOf(BelongsTo::class);
});

test('information model morph site', function () {
    $site = Site::factory()->create();
    $data = [
        'phone' => 'phone',
        'email' => 'email',
        'photo_url' => 'photo',
        'address' => 'address',
        'company_id' => 'asdfasdf',
    ];

    $site->information()->create($data);

    $this->assertDatabaseHas('informations', $data);
    expect($site->information)->not->toBeNull();
    expect($site->information())->toBeInstanceOf(MorphOne::class);
    expect((new Information())->site())->toBeInstanceOf(BelongsTo::class);
});

test('information model morph bank', function () {
    $bank = Bank::factory()->create();
    $data = [
        'phone' => 'phone',
        'email' => 'email',
        'photo_url' => 'photo',
        'address' => 'address',
        'company_id' => 'asdfasdf',
    ];

    $bank->information()->create($data);

    $this->assertDatabaseHas('informations', $data);
    expect($bank->information)->not->toBeNull();
    expect($bank->information())->toBeInstanceOf(MorphOne::class);
    expect((new Information())->bank())->toBeInstanceOf(BelongsTo::class);
});

test('information model morph ars', function () {
    $ars = Ars::factory()->create();
    $data = [
        'phone' => 'phone',
        'email' => 'email',
        'photo_url' => 'photo',
        'address' => 'address',
        'company_id' => 'asdfasdf',
    ];

    $ars->information()->create($data);

    $this->assertDatabaseHas('informations', $data);
    expect($ars->information)->not->toBeNull();
    expect($ars->information())->toBeInstanceOf(MorphOne::class);
    expect((new Information())->ars())->toBeInstanceOf(BelongsTo::class);
});

test('information model morph afp', function () {
    $afp = Afp::factory()->create();
    $data = [
        'phone' => 'phone',
        'email' => 'email',
        'photo_url' => 'photo',
        'address' => 'address',
        'company_id' => 'asdfasdf',
    ];

    $afp->information()->create($data);

    $this->assertDatabaseHas('informations', $data);
    expect($afp->information)->not->toBeNull();
    expect($afp->information())->toBeInstanceOf(MorphOne::class);
    expect((new Information())->afp())->toBeInstanceOf(BelongsTo::class);
});

test('information model morph project', function () {
    $project = Afp::factory()->create();
    $data = [
        'phone' => 'phone',
        'email' => 'email',
        'photo_url' => 'photo',
        'address' => 'address',
        'company_id' => 'asdfasdf',
    ];

    $project->information()->create($data);

    $this->assertDatabaseHas('informations', $data);
    expect($project->information)->not->toBeNull();
    expect($project->information())->toBeInstanceOf(MorphOne::class);
    expect((new Information())->project())->toBeInstanceOf(BelongsTo::class);
});
