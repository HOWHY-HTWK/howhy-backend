<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoData extends Model
{
    protected $fillable = ['videoId', 'creator', 'data' ];

    protected $casts = [
        'data' => 'array'
    ];
    
    use HasFactory;
}
