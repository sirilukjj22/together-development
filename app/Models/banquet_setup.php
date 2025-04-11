<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class banquet_setup extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'banquet_setup';
    protected $fillable = [
        'Banquet_ID',
        'date',
        'first_time',
        'last_time',
        'room',
        'details',
        'image',
        'Image_ID',
    ];
}
