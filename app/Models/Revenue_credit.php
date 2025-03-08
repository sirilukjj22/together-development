<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revenue_credit extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
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
        'ev_fee',
        'ev_vat',
        'ev_revenue',
        'date_receive',
        'receive_payment',
        'sms_revenue',
        'remark',
        'status',
    ];

    public static function getAgodaReceiveDate($smsID) 
    {
        $query = Revenue_credit::where('sms_revenue', $smsID)->where('status', 5)->where('receive_payment', 1)->select('id', 'date_receive')->first();

        $result = !empty($query) ? $query->date_receive : 0;

        return $result;
    }

    public static function getElexaReceiveDate($smsID) 
    {
        $query = Revenue_credit::where('sms_revenue', $smsID)->where('status', 8)->where('receive_payment', 1)->select('id', 'date_receive')->first();

        $result = !empty($query) ? $query->date_receive : 0;

        return $result;
    }
}
