<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class receive_payment extends Model
{
    use HasFactory;
    protected $table = 'receive_payment';
    protected $fillable = [
        'Invoice_ID',
        'Receipt_ID',
        'Quotation_ID',
        'payment_date',
        'company',
        'Amount',
        'Remark',
        'Bank',
        'sequence_re',
        'total',
        'Cheque',
        'Credit',
        'Expire',
        'document_status',
        'balance',
    ];
}
