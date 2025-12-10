<?php



/*
|
| Arhitectural tests
|
*/

arch('all models should use soft deletes')
    ->expect('App\Models')
    ->toUseTraits([
        \Illuminate\Database\Eloquent\SoftDeletes::class,
    ])
    ->ignoring([
        \App\Models\Comment::class,
        'App\Models\LiveVox',
        'App\Models\Services',
        'App\Models\Traits',
    ]);

arch('all models should use ulids and factory')
    ->expect('App\Models')
    ->toUseTraits([
        \Illuminate\Database\Eloquent\Factories\HasFactory::class,
        \Illuminate\Database\Eloquent\Concerns\HasUuids::class,
    ])
    ->ignoring([
        'App\Models\LiveVox',
        'App\Models\Services',
        'App\Models\Traits',
    ]);
