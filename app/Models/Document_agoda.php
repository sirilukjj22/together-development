<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document_agoda extends Model
{
    use HasFactory;

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
