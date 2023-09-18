<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FakeMessageCelebrity extends Model
{
    use HasFactory;

    protected $table = 'fake_message_celebrity';

    protected $fillable = [
      'name',
      'name_profile',
      'fake_message_category_celebrity_id',
      'avatar',
      'video',
      'followers',
      'language_code',
      'count',
    ];

    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => config('app.storage_url') . $value ,
        );
    }

    protected function video(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => config('app.storage_url') . $value ,
        );
    }
}
