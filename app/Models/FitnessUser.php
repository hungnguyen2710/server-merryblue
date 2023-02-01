<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FitnessUser extends Model
{
    use HasFactory;

    protected $table = 'fitness_users';

    protected $fillable = [
        'name' ,
        'email' ,
        'email_verified_at' ,
        'password' ,
    ];

    public function exercise(){
        return $this->hasMany(FitnessUserExercise::class,'fitness_user_id', 'id');
    }
}
