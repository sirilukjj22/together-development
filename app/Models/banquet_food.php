<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class banquet_food extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'banquet_food';
    protected $fillable = [
        'Banquet_ID',
        'date',
        'first_time',
        'last_time',
        'room',
        'special',
        'number_guest',
        'food',
        'food_type',
        'drink',
    ];
}
