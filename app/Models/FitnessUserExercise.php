<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FitnessUserExercise extends Model
{
    use HasFactory;

    protected $table = 'fitness_user_exercise';

    protected $fillable = [
      'fitness_user_id',
      'fitness_exercise_id',
    ];
}
