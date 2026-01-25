<?php

namespace App\Models;

use App\Traits\Models\InteractsWithModelCaching;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mailable extends Model
{
    /** @use HasFactory<\Database\Factories\MailableFactory> */
    use HasFactory;

    use HasUuids;
    use InteractsWithModelCaching;

    protected $fillable = [
        'name',
        'description',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
