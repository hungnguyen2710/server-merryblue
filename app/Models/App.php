<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    use HasFactory;

    protected $table = 'apps';

    protected $fillable = [
        'name_project',
        'name_in_store',
        'app_id',
        'category_name',
        'company',
        'size',
        'icon',
        'description',
        'link_ios',
        'link_android',
        'time_update_store',
        'status',
    ];

    protected function thumbnail(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => config('app.storage_url') . $value ,
        );
    }

    protected function icon(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => config('app.storage_url') . $value ,
        );
    }
}
