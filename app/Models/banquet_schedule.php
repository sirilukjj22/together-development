<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class banquet_schedule extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'banquet_schedule';
    protected $fillable = [
        'Banquet_ID',
        'date',
        'first_time',
        'last_time',
        'room',
        'function',
        'setup',
        'agr_schedule',
        'gtd_schedule',
        'set_schedule',
    ];
}
