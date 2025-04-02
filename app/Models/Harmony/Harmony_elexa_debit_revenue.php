<?php

namespace App\Models\Harmony;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Harmony_elexa_debit_revenue extends Model
{
    use HasFactory;

    protected $connection = 'mysql_harmony';
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
