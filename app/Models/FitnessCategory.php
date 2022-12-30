<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FitnessCategory extends Model
{
    use HasFactory;

    protected $table = 'fitness_category';

    protected $fillable = [
        'sort_order' ,
        'title' ,
        'icon' ,
        'thumbnail' ,
        'type' ,
        'status' ,
    ];

    protected function thumbnail(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => config('app.storage_url') . $value ,
        );
    }
}
