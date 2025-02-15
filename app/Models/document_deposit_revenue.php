<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class document_deposit_revenue extends Model
{
    use HasFactory;
    protected $table = 'document_deposit_revenue';
    protected $fillable = [
        'Deposit_ID',
        'PaymentType',
        'bank',
        'CardNumber',
        'Cheque_Number',
        'Expiry',
        'paymentDate',
        'Amount',
        'detail'
    ];
}
