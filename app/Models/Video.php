<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
    use SoftDeletes;
    protected $fillable = ['videoId', 'creator'];

    // protected $casts = [
    //     'data' => 'array',
    // ];

    public function questions(): HasMany{
        return $this->hasMany(Question::class);
    }
    use HasFactory;
}
