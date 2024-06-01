<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revenue_credit extends Model
{
    use HasFactory;

    protected $table = 'revenue_credit';
    protected $fillable = [
        'revenue_id',
        'batch',
        'revenue_type',
        'credit_amount',
        'agoda_check_in',
        'agoda_check_out',
        'agoda_date_deposit',
        'agoda_charge',
        'agoda_outstanding',
        'ev_charge',
        'ev_outstanding',
        'date_receive',
        'receive_payment',
        'sms_revenue',
        'remark',
        'status',
    ];
}
