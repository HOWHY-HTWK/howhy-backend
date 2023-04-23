<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{

    use SoftDeletes;

    protected $fillable = ['creator', 'data', 'correctAnswers'];

    protected $casts = [
        'data' => 'json',
        'correctAnswers' => 'array',
    ];

    // protected $hidden = ['correctAnswerIndexes'];

    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }

    public function users(){
        return $this->belongsToMany(User::class);
    }

    use HasFactory;
}
