<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prize extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'date', 'place', 'timeframe', 'points'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->using(PrizeUser::class)->withPivot('hash', 'expires')->withTimestamps();
    }

    use HasFactory;
}
