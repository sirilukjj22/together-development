<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class banquet_asset extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'banquet_asset';
    protected $fillable = [
        'Banquet_ID',
        'item',
        'quantity',
        'remarks',
        'price',
    ];
}
