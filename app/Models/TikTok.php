<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TikTok extends Model
{
    use HasFactory;
    protected $table = 'tiktok';

    protected $fillable = [
        'url',
        'version',
        'api',
        'status',
    ];
}
