<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class banquet_event_order extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'banquet_event_order';
    protected $fillable = [
        'Banquet_ID',
        'Quotation_ID',
        'Company_ID',
        'event_date',
        'sales',
        'catering',
        'Operated_by',
        'image',
    ];
}
