<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class log_dummy extends Model
{
    use HasFactory;
    protected $table = 'log_dummy';
    protected $fillable = [
        'Quotation_ID',
        'Approve_date',
        'Approve_time',
    ];
}
