<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketDepartment extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'ticket_prefix', 'description'];

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'department_id');
    }

    protected static function booted()
    {
        parent::booted();

        static::saved(function ($model) {
            $model->updateQuietly(['ticket_prefix' => $model->getTicketPrefix()]);
        });
    }

    public function getTicketsCompletedAttribute(): int
    {
        return $this->tickets()->completed()->count();
    }

    public function getTicketsIncompletedAttribute(): int
    {
        return $this->tickets()->incompleted()->count();
    }

    public function getTicketsCompliantsAttribute(): int
    {
        return $this->tickets()->compliant()->count();
    }

    public function getCompletionRateAttribute()
    {
        $total_tickets = $this->tickets()->count();

        return $total_tickets > 0
            ? $this->tickets_completed / $total_tickets
            : 0;
    }

    public function getComplianceRateAttribute()
    {
        $total_tickets = $this->tickets()->completed()->count();

        return $total_tickets > 0
            ? $this->tickets_compliants / $total_tickets
            : 0;
    }

    protected function getTicketPrefix(): string
    {
        $words = explode(' ', preg_replace('/\s+/', ' ', trim($this->attributes['name'])), 2);
        $count = count($words);

        $parsed = $count === 1
            ? str($words[0])->limit(4, '')->upper()
            : join('', [
                str($words[0])->limit(2, '')->upper(),
                str($words[1] ?? '')->limit(2, '')->upper()
            ]);

        $parsed = str($parsed)->finish('-');
        $exists = self::where('ticket_prefix', $parsed)->where('id', '!=', $this->id)->first();

        if ($exists) {
            $rand = str()->random(1);
            return str($exists->ticket_prefix)->substrReplace(str($rand)->upper(), 4, 0);
        }

        return $parsed;
    }
}
