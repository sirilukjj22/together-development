<?php

namespace App\Models\Harmony;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Harmony_tb_outstanding_balance extends Model
{
    use HasFactory;

    protected $connection = 'mysql_harmony';
    protected $table = 'table_outstanding_balance';
    protected $fillable = [
        'year',
        'agoda_balance',
        'elexa_balance'
    ];
}
