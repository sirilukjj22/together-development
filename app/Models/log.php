<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class log extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'log';
    protected $fillable = [
        'Quotation_ID',
        'QuotationType',
        'Approve_date',
        'Approve_time',
    ];
}
