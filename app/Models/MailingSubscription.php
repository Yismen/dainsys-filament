<?php

namespace App\Models;

use App\Models\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MailingSubscription extends Model
{
    use HasFactory;
    use SoftDeletes;
    use BelongsToUser;
    use HasUuids;

    protected $fillable = ['mailable', 'user_id'];
}
