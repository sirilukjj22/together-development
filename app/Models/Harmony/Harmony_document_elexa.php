<?php

namespace App\Models\Harmony;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Harmony_document_elexa extends Model
{
    use HasFactory;

    protected $connection = 'mysql_harmony';
    protected $table = 'document_elexa';
    protected $fillable = [
        'doc_no',
        'issue_date',
        'sms_id',
        'debit_amount',
        'status_lock',
        'status_paid',
        'created_by',
        'updated_by',
    ];
}
