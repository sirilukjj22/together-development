<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Harmony_tb_close_days extends Model
{
    use HasFactory;

    protected $connection = 'mysql_harmony';
    protected $table = 'tb_close_day';
    protected $fillable = [
        'date',
        'status',
        'created_at',
        'updated_at',
    ];
}
