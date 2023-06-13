<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PrizeUser extends Pivot
{
    protected $table = 'prize_user';

    protected $fillable = ['hash', 'expires', 'redeemed'];

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function prize(): HasOne
    {
        return $this->hasOne(Prize::class);
    }
    use HasFactory;
}
