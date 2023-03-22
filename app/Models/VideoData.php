<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoData extends Model
{
    protected $fillable = ['videoId', 'creator', 'data' , 'correctAnswerIndexes'];

    protected $casts = [
        'data' => 'array',
        'correctAnswerIndexes' => 'array'
    ];

    use HasFactory;
}
