<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'number_of_reps' ,
        'rest_time' ,
        'tips' ,
        'calories' ,
        'thumbnail' ,
        'image_action' ,
        'description' ,
        'status' ,
        'language_code' ,
    ];

    protected function thumbnail(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => config('app.storage_url') . $value ,
        );
    }

    protected function imageAction(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => config('app.storage_url') . $value ,
        );
    }
}
