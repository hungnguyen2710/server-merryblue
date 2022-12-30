<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FitnessUserInfo extends Model
{
    use HasFactory;

    protected $table = 'fitness_user_info';

    protected $fillable = [
        'fitness_user_id' ,
        'gender' ,
        'weight' ,
        'height' ,
    ];
}
