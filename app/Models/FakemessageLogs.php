<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FakemessageLogs extends Model
{
    use HasFactory;
    protected $table = 'fakemessage_logs';

    protected $fillable = [
        'celebrity_id',
        'celebrity_name',
    ];
}
