<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'google_id',
        'name',
        'date',
        'time',
        'user_id',

    ];
    use HasFactory;
}
