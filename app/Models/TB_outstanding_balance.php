<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TB_outstanding_balance extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'table_outstanding_balance';
    protected $fillable = [
        'year',
        'agoda_balance',
        'elexa_balance'
    ];
}
