<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FitnessUserHistory extends Model
{
    use HasFactory;
    protected $table = 'fitness_user_history';

    protected $fillable = [
        'fitness_user_id' ,
        'fitness_exercise_id' ,
    ];
}
