<?php

namespace App\Models\Harmony;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use function Symfony\Component\String\b;

class Harmony_log_agoda extends Model
{
    use HasFactory;

    protected $connection = 'mysql_harmony';
    protected $table = 'log_agoda';
    protected $fillable = [
        'document_id',
        'type',
        'original_attributes',
        'changed_attributes',
        'created_by',
    ];

    public function  userCreatedBy()
    {
        return $this->hasOne(\App\Models\User::class, 'id','created_by');
    }

    public static function SaveLog($type, $product, $request)
    {
        if ($type == "add") {
            $changes = $request->all();
        } else {
            // ดึงข้อมูลเดิมจากโมเดล
            $original = $product->getOriginal();

            // ดึงข้อมูลที่เปลี่ยนแปลงจาก request
            $changes = array_diff_assoc($request->all(), $original);
        }

        $details = [];

        if (count($changes) > 0) {
            foreach ($changes as $column => $value) {
                if (in_array($column, ['_token', 'id', 'created_by', 'created_at', 'updated_by', 'updated_at']))
                    continue;

                ### Rename
                if ($column == 'doc_no')
                    $column = 'Document No';
                if ($column == 'issue_date')
                    $column = 'Issue Date';
                if ($column == 'sms_id')
                    $column = 'Hotel Bank Transfer';
                if ($column == 'receive_id')
                    $column = 'Debit Agoda Revenue';

                    if ($column == 'Issue Date') {
                        $value = Carbon::parse($value)->format('d/m/Y');
                    }

                    if ($column == 'Hotel Bank Transfer') {
                        $Famount = Harmony_SMS_alerts::where('id', $value)->where('status', 5)->sum('amount');
                        $value =  number_format($Famount, 2);
                    }

                    if ($column != 'Debit Agoda Revenue') {
                        $details[] = '<b>'. $column . '</b> : ' . $value;
                    } else {
                        $details[] = '</br><b>'. $column . '</b>';
                        if ($type == 'edit') {
                            foreach ($value as $key => $item) {
                                if (!in_array($item, $product['receive_id'])) {
                                    $check_detail = Harmony_revenue_credit::where('id', $item)
                                        ->select('batch', 'agoda_check_in', 'agoda_check_out', 'agoda_outstanding')->first();
                                    $details[] = '<b>Booking Number : </b>' . $check_detail->batch .' <b>Check In : </b>'. Carbon::parse($check_detail->agoda_check_in)->format('d/m/Y') .' <b>Check Out : </b>'. Carbon::parse($check_detail->agoda_check_out)->format('d/m/Y') .' <b>Amount : </b>'. number_format($check_detail->agoda_outstanding, 2);
                                }
                            }
                        } else {
                            foreach ($value as $key => $item) {
                                $check_detail = Harmony_revenue_credit::where('id', $item)
                                    ->select('batch', 'agoda_check_in', 'agoda_check_out', 'agoda_outstanding')->first();
                                    $details[] = '<b>Booking Number : </b>' . $check_detail->batch .' <b>Check In : </b>'. Carbon::parse($check_detail->agoda_check_in)->format('d/m/Y') .' <b>Check Out : </b>'. Carbon::parse($check_detail->agoda_check_out)->format('d/m/Y') .' <b>Amount : </b>'. number_format($check_detail->agoda_outstanding, 2);
                            }
                        }
                    }
                }
            }

            if ($type == 'edit') {
                Harmony_log_agoda::create([
                    'document_id' => $product->id,
                    'type' => 'Edit',
                    'changed_attributes' => implode('<br/>', $details), // บันทึกเฉพาะฟิลด์ที่มีการเปลี่ยนแปลง
                    'created_by' => optional(Auth::user())->id, // ตรวจสอบการล็อกอินของผู้ใช้
                    'updated_by' => optional(Auth::user())->id, // ตรวจสอบการล็อกอินของผู้ใช้
                ]);

            } else {
                Harmony_log_agoda::create([
                    'document_id' => $request->id,
                    'type' => 'Add',
                    'original_attributes' => implode('<br/>', $details), // บันทึกเฉพาะฟิลด์ที่มีการเปลี่ยนแปลง
                    'created_by' => optional(Auth::user())->id, // ตรวจสอบการล็อกอินของผู้ใช้
                ]);
            }

        return true;
    }
}
