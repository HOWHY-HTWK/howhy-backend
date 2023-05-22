<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllowedEmail extends Model
{
    protected $fillable = ['email'];

    protected $casts = [
        'email' => 'string',
    ];

    use HasFactory;
}
