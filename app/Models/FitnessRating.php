<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FitnessRating extends Model
{
    use HasFactory;

    protected $table = 'fitness_rating';
    protected $fillable = [
      'fitness_user_id',
      'star',
      'comment',
    ];
}
