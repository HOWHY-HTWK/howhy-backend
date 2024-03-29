<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VideoData extends Model
{
    use SoftDeletes;
    protected $fillable = ['videoId', 'creator', 'data'];

    protected $casts = [
        'data' => 'array',
    ];

    // protected $hidden = ['correctAnswerIndexes'];


    use HasFactory;
}
