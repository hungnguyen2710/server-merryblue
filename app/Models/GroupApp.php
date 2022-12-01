<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupApp extends Model
{
    use HasFactory;

    protected $table = 'group_app';

    protected $fillable = [
        'group_id',
        'app_id',
    ];
}
