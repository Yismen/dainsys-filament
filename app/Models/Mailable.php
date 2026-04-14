<?php

namespace App\Models;

use App\Traits\Models\InteractsWithModelCaching;
use Database\Factories\MailableFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'description',
])]
class Mailable extends Model
{
    /** @use HasFactory<MailableFactory> */
    use HasFactory;

    use HasUuids;
    use InteractsWithModelCaching;

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
