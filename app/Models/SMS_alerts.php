<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SMS_alerts extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'sms_alert';
    protected $fillable = [
        'sort',
        'split_ref_id',
        'date',
        'date_into',
        'transfer_from',
        'transfer_form_account',
        'into_account',
        'amount',
        'amount_before_split',
        'into_qr',
        'booking_id',
        'sequence',
        'transfer_status',
        'agoda_status',
        'split_status',
        'status',
        'status_receive_agoda',
        'status_receive_elexa',
        'remark',
        'date_remark',
        'transfer_remark',
        'other_remark',
        'close_day',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    public function fullAmount()
    {
        return $this->hasOne(SMS_alerts::class, 'id', 'split_ref_id');
    }

    public static function check_bank($datakey){
        
        $query = Masters::where('category', 'bank')->whereNull('deleted_at')->select('id', 'name_en')->get();

        $bank_name = '';
        if (strtoupper($datakey) == "TMB") {
            $check = Masters::where('name_en', 'TTB')->select('id')->first();
            $bank_name = $check->id;

        } else {
            foreach ($query as $key => $value) {
                if (strtoupper($value->name_en) == strtoupper($datakey)) {
                    $bank_name = $value->id;
                    break;
                }
            }
        }

        if (empty($bank_name)) {
            $check = Masters::where('category', 'bank')->whereNull('deleted_at')->where('name_en', "SCB")->select('id', 'name_en')->first();
            $bank_name = $check->id ?? 0;
        }

        return $bank_name;
    }

    public static function check_account($datakey) {

        $account = "";
        if ($datakey == "x267913") {
            $account = "708-2-26791-3";
        } elseif ($datakey == "x267921") {
            $account = "708-2-26792-1";
        } elseif ($datakey == "x273574") {
            $account = "708-2-27357-4";
        }

        return $account;
    }

    public function transfer_bank()
    {
        return $this->hasOne(Masters::class, 'id', 'transfer_from');
    }

    // เช็คเลขที่เอกสารของ Agoda
    public function DocumentNoAgoda()
    {
        return $this->hasOne(Document_agoda::class, 'sms_id', 'id');
    }

    // เช็คเลขที่เอกสารของ Elexa
    public function DocumentNoElexa()
    {
        return $this->hasOne(Document_elexa::class, 'sms_id', 'id');
    }

    // เช็คสถานะ Lock/Unlock ของ Agoda
    public function statusLockAgoda()
    {
        return $this->hasOne(Document_agoda::class, 'sms_id', 'id');
    }

    // เช็คสถานะ Lock/Unlock ของ Agoda
    public function statusLockElexa()
    {
        return $this->hasOne(Document_elexa::class, 'sms_id', 'id');
    }

    ## Check Close Day
    public static function checkCloseDay($date) {

        $adate = date('Y-m-d', strtotime($date));

        $check_data = TB_close_days::where('date', $adate)->first();

        if (!empty($check_data)) {
            return 1;
        } else {
            return 0;
        }
    }
}
