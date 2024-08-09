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
        'Quotation_ID',
        'payment_date',
        'Amount',
        'Remark',
        'Bank',
        'Cheque',
        'Credit',
        'Expire',
    ];
}
