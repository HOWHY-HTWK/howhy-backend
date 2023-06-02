<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{

    use SoftDeletes;

    protected $fillable = ['questionText', 'timecode', 'data', 'type', 'correctAnswers', 'answers'];

    protected $casts = [
        'data' => 'array',
        'correctAnswers' => 'array',
        'answers' => 'array'
    ];

    // protected $hidden = ['correctAnswerIndexes'];

    public function children()
    {
        return $this->hasMany(Question::class, 'parent_id');
    }

    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->using(QuestionUser::class)->withPivot('correct')->withTimestamps();
    }

    use HasFactory;
}
