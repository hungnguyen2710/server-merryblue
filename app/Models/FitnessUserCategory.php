<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FitnessUserCategory extends Model
{
    use HasFactory;

    protected $table = 'fitness_user_category';

    protected $fillable = [
        'fitness_user_id' ,
        'fitness_category_id' ,
        'language_code' ,
    ];
}
