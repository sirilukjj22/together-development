<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Elexa_debit_revenue extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'elexa_debit_revenue';
    protected $fillable = [
        'document_elexa',
        'date',
        'status_type',
        'amount',
        'remark',
        'created_by',
        'created_at',
    ];
}
