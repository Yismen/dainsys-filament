<?php

namespace App\Models;

use App\Models\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MailingSubscription extends Model
{
    use HasFactory, BelongsToUser;
    protected $fillable = ['mailable', 'user_id'];
}
