<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FakeMessageCategoryCelebrity extends Model
{
    use HasFactory;

    protected $table = 'fake_message_category_celebrity';

    protected $fillable = [
        'name',
        'status'
    ];
}
