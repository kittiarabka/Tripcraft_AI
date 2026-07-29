<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable(['destination', 'days', 'budget', 'interests', 'status'])]
class Trip extends Model
{
    protected static function booted(): void
    {
        static::creating(function (Trip $trip) {
            $trip->share_token ??= (string) Str::uuid();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tripDays(): HasMany
    {
        return $this->hasMany(TripDay::class)->orderBy('day_number');
    }
}
