<?php

namespace App\Models;

use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FitnessLanguage extends Model
{
    use HasFactory;

    protected $table = 'fitness_language';

    protected $fillable = [
      'name' ,
      'code' ,
      'flag' ,
      'status' ,
    ];

    protected function flag(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => config('app.storage_url') . $value ,
        );
    }

}
