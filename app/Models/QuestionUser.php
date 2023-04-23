<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class QuestionUser extends Model
{
    protected $table = 'question_user';

    protected $fillable = ['data'];

    public function user(): HasOne{
        return $this->hasOne(User::class);
    }

    public function question(): HasOne{
        return $this->hasOne(Question::class);
    }
    use HasFactory;
}
