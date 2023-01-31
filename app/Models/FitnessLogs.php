<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FitnessLogs extends Model
{
    use HasFactory;

    protected $table = 'fitness_logs';

    protected $fillable = [
      'fitness_user_id',
      'day_count',
    ];
}
