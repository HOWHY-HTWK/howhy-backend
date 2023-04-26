<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Pivot;

class QuestionUser extends Pivot
{
    protected $table = 'question_user';

    protected $fillable = ['correct'];

    protected $casts = [
        'correct' => 'boolean',
    ];

    public function user(): HasOne{
        return $this->hasOne(User::class);
    }

    public function question(): HasOne{
        return $this->hasOne(Question::class);
    }
    use HasFactory;
}
