<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PrizeUser extends Model
{
    protected $table = 'question_user';

    protected $fillable = ['hash', 'expires', 'redeemed'];

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function question(): HasOne
    {
        return $this->hasOne(Prize::class);
    }
    use HasFactory;
}
