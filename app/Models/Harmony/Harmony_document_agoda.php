<?php

namespace App\Models\Harmony;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Harmony_document_agoda extends Model
{
    use HasFactory;

    protected $connection = 'mysql_harmony';
    protected $table = 'document_agoda';
    protected $fillable = [
        'doc_no',
        'issue_date',
        'sms_id',
        'status_lock',
        'status_paid',
        'created_by',
        'updated_by',
    ];
}
