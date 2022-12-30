<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FitnessExercise extends Model
{
    use HasFactory;

    protected $table = 'fitness_exercise';

    protected $fillable = [
        'fitness_category_id' ,
        'title' ,
        'time' ,
        'calories' ,
        'thumbnail' ,
        'image_action' ,
        'description' ,
        'status' ,
    ];
}
