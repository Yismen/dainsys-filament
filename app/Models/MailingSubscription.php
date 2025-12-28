<?php

namespace App\Models;

use App\Models\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MailingSubscription extends \App\Models\BaseModels\AppModel
{
    use BelongsToUser;
    use SoftDeletes;

    protected $fillable = ['mailable', 'user_id'];
}
