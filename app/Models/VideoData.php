<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoData extends Model
{
    protected $fillable = ['videoId', 'questions', 'creator', 'data' ];
    use HasFactory;
}
