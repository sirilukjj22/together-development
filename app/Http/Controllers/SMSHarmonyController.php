<?php

namespace App\Http\Controllers;

use App\Models\Masters;
use App\Models\Role_permission_revenue;
use App\Models\Harmony\Harmony_SMS_alerts;
use App\Models\Harmony\Harmony_SMS_forwards;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SMSHarmonyController extends Controller
{

    public function index()
    {
        $data_forward = Harmony_SMS_forwards::where('is_status', 0)->get();

        foreach ($data_forward as $key => $value) {
            if (count($data_forward) > 0) {
                $exp_form = explode(" ", $value->messages);
                if ($value->sender == "027777777" && count($exp_form) == 11 || $value->sender == "SCBQRAlert" && count($exp_form) == 11) {

                    if (isset($exp_form[1])) {
                        $fromAccount = $exp_form[1].' '.$exp_form[2];
                    }

                    $check_transfer_from = Harmony_SMS_alerts::check_bank($exp_form[1]);
                    $from_account = $check_transfer_from;

                    if ($check_transfer_from == '' && $fromAccount != '') {
                        $from_account = 5;
                    }

                    Harmony_SMS_alerts::create([
                        'date' => $value->created_at,
                        'transfer_from' => $from_account,
                        'transfer_form_account' => $fromAccount,
                        'into_account' => Harmony_SMS_alerts::check_account($exp_form[6]),
                        'amount' => str_replace(",", "", substr($exp_form[4], 3)),
                        'remark' => "Auto",
                        'status' => 0
                    ]);

                    Harmony_SMS_forwards::where('id', $value->id)->update([
                        'is_status' => 1
                    ]);
                } elseif ($value->sender == "027777777" && count($exp_form) == 12 || $value->sender == "SCBQRAlert" && count($exp_form) == 12) {
                    Harmony_SMS_alerts::create([
                        'date' => $value->created_at,
                        'transfer_from' => Harmony_SMS_alerts::check_bank($exp_form[1]),
                        'into_account' => Harmony_SMS_alerts::check_account($exp_form[7]),
                        'amount' => str_replace(",", "", substr($exp_form[5], 3)),
                        'remark' => "Auto",
                        'status' => 0
                    ]);

                    Harmony_SMS_forwards::where('id', $value->id)->update([
                        'is_status' => 1
                    ]);
                } elseif ($value->sender == "027777777" && count($exp_form) == 13 && isset($exp_form[8]) && $exp_form[7] == "เข้าบ/ช" || $value->sender == "SCBQRAlert" && count($exp_form) == 13 && isset($exp_form[8]) && $exp_form[7] == "เข้าบ/ช") {
                    if (isset($exp_form[1])) {
                        $fromAccount = $exp_form[1].' '.$exp_form[2].' '.$exp_form[3];
                    }

                    Harmony_SMS_alerts::create([
                        'date' => $value->created_at,
                        'transfer_from' => Harmony_SMS_alerts::check_bank($exp_form[1]),
                        'transfer_form_account' => $fromAccount,
                        'into_account' => Harmony_SMS_alerts::check_account($exp_form[8]),
                        'amount' => str_replace(",", "", substr($exp_form[6], 3)),
                        'remark' => "Auto",
                        'status' => 0
                    ]);

                    Harmony_SMS_forwards::where('id', $value->id)->update([
                        'is_status' => 1
                    ]);
                } elseif ($value->sender == "027777777" && count($exp_form) == 10 || $value->sender == "SCBQRAlert" && count($exp_form) == 10) {
                    Harmony_SMS_alerts::create([
                        'date' => $value->created_at,
                        'transfer_from' => Harmony_SMS_alerts::check_bank($exp_form[1]),
                        'into_account' => Harmony_SMS_alerts::check_account($exp_form[5]),
                        'amount' => str_replace(",", "", substr($exp_form[3], 3)),
                        'remark' => "Auto",
                        'status' => 0
                    ]);

                    Harmony_SMS_forwards::where('id', $value->id)->update([
                        'is_status' => 1
                    ]);
                } elseif ($value->sender == "027777777" && count($exp_form) == 5 || $value->sender == "SCBQRAlert" && count($exp_form) == 5) {
                    if ($exp_form[1] == "บ.จากxBorder") {

                        $data_qr = mb_substr($exp_form[3], 4);
                        $into = "none";

                        switch ($data_qr) {
                            case '076355400050101':
                                $into = "156-2-77492-1";
                                break;
                        }

                        Harmony_SMS_alerts::create([
                            'date' => $value->created_at,
                            'transfer_from' => 22,
                            'into_account' => $into,
                            'into_qr' => $data_qr,
                            'amount' => str_replace(",", "", $exp_form[0]),
                            'remark' => "Auto",
                            'status' => 0
                        ]);

                        Harmony_SMS_forwards::where('id', $value->id)->update([
                            'is_status' => 1
                        ]);
                    }
                } elseif ($value->sender == "027777777" && count($exp_form) == 6 || $value->sender == "SCBQRAlert" && count($exp_form) == 6) {
                    if ($exp_form[1] == "บ.จากxBorder") {

                        $data_qr = mb_substr($exp_form[4], 4);
                        $into = "none";

                        switch ($data_qr) {
                            case '076355400050101':
                                $into = "156-2-77492-1";
                                break;
                        }

                        Harmony_SMS_alerts::create([
                            'date' => $value->created_at,
                            'transfer_from' => 22,
                            'into_account' => $into,
                            'into_qr' => $data_qr,
                            'amount' => str_replace(",", "", $exp_form[0]),
                            'remark' => "Auto",
                            'status' => 2
                        ]);

                        Harmony_SMS_forwards::where('id', $value->id)->update([
                            'is_status' => 1
                        ]);

                    } elseif ($exp_form[0] == "เงินเข้าบ/ช") {
                        Harmony_SMS_alerts::create([
                            'date' => $value->created_at,
                            'transfer_from' => Harmony_SMS_alerts::check_bank($exp_form[5]),
                            'into_account' => Harmony_SMS_alerts::check_account($exp_form[1]),
                            'amount' => str_replace(",", "", substr($exp_form[3], 3)),
                            'remark' => "Auto",
                            'status' => 0
                        ]);

                        Harmony_SMS_forwards::where('id', $value->id)->update([
                            'is_status' => 1
                        ]);

                    } if ($exp_form[0] == "เช็คเข้าบ/ช") {
                        Harmony_SMS_alerts::create([
                            'date' => $value->created_at,
                            'transfer_from' => 21, // เช็คธนาคาร
                            'into_account' => Harmony_SMS_alerts::check_account($exp_form[1]),
                            'amount' => str_replace(",", "", substr($exp_form[3], 3)),
                            'remark' => "Auto",
                            'status' => 0
                        ]);

                        Harmony_SMS_forwards::where('id', $value->id)->update([
                            'is_status' => 1
                        ]);

                    } elseif (isset($exp_form[3]) && $exp_form[3] == "ชำระผ่านQR") { // SCBQRAlert
                        $data_qr = mb_substr($exp_form[4], 4);
                        $into = "none";

                        switch ($data_qr) {
                            case '076355400050101':
                                $into = "156-2-77492-1";
                                break;
                        }

                        if (isset($exp_form[5]) && strpos($exp_form[5], '@') !== false) {
                            $getTime = substr($exp_form[5], strpos($exp_form[5], '@') + 1);
                        } else {
                            $getTime = Carbon::parse($value->created_at)->format('H:i');
                        }

                        if (isset($exp_form[2]) && strpos($exp_form[2], 'X') !== false) {
                            if ($exp_form[1] == "บ.จากGSBA") {
                                $formAccount = "xxx-" . strtolower($exp_form[2][0]) . "-xx" . $exp_form[2][1] . "-" . substr($exp_form[2], 2, 4) . "-" . $exp_form[2][-1];
                            } else {
                                $formAccount = "xxx-{$exp_form[2][1]}-" . substr($exp_form[2], 2, 4) . "-{$exp_form[2][-1]}";
                            }
                        } else {
                            $formAccount = '';
                        }
                        
                        Harmony_SMS_alerts::create([
                            'date' => Carbon::parse($value->created_at)->format('Y-m-d').' '.Carbon::createFromFormat('H:i', $getTime)->format('H:i:s'),
                            'transfer_from' => Harmony_SMS_alerts::check_bank(mb_substr($exp_form[1], 5)),
                            'transfer_form_account' => $formAccount ?? '',
                            'into_account' => $into,
                            'into_qr' => $data_qr,
                            'amount' => str_replace(",", "", $exp_form[0]),
                            'remark' => "Auto",
                            'status' => $into == "156-2-77492-1" ? 2 : 0
                        ]);

                        Harmony_SMS_forwards::where('id', $value->id)->update([
                            'is_status' => 1
                        ]);
                    }
                } elseif ($value->sender == "027777777" && count($exp_form) == 8 || $value->sender == "SCBQRAlert" && count($exp_form) == 8) {
                    Harmony_SMS_alerts::create([
                        'date' => $value->created_at,
                        'transfer_from' => Harmony_SMS_alerts::check_bank($exp_form[5]),
                        'into_account' => Harmony_SMS_alerts::check_account($exp_form[1]),
                        'amount' => str_replace(",", "", substr($exp_form[3], 3)),
                        'remark' => "Auto",
                        'status' => 0
                    ]);

                    Harmony_SMS_forwards::where('id', $value->id)->update([
                        'is_status' => 1
                    ]); 

                } elseif ($value->sender == "027777777" && count($exp_form) == 9 && isset($exp_form[3]) && $exp_form[3] == "x774921" || $value->sender == "SCBQRAlert" && count($exp_form) == 9 && isset($exp_form[3]) && $exp_form[3] == "x774921") {
                    Harmony_SMS_alerts::create([
                        'date' => Carbon::parse($value->created_at)->format('Y-m-d H:i:s'),
                        'transfer_from' => Harmony_SMS_alerts::check_bank("Credit"),
                        'into_account' => Harmony_SMS_alerts::check_account($exp_form[3]),
                        'amount' => str_replace(",", "", substr($exp_form[1], 3)),
                        'remark' => "Auto",
                        'date_into' => Carbon::parse($value->created_at)->subDays(1)->format('Y-m-d H:i:s'),
                        'transfer_remark' => "ยอดเครดิต",
                        'status' => 4
                    ]);

                    Harmony_SMS_forwards::where('id', $value->id)->update([
                        'is_status' => 1
                    ]);

                } elseif ($value->sender == "027777777" && count($exp_form) == 4 && isset($exp_form[2]) && $exp_form[2] == "เข้าบ/ชx774921") {
                    $parts = preg_split('/[@]/', $exp_form[3]);

                    $date = $parts[0]; // "16/03"
                    $time = $parts[1]; // "02:48"

                    $year = now()->year; // ดึงปีปัจจุบัน (2025)
                    $formattedDate = Carbon::createFromFormat('d/m/Y', "$date/$year")->format('Y-m-d');

                    Harmony_SMS_alerts::create([
                        'date' => $formattedDate." ".$time.":00",
                        'transfer_from' => Harmony_SMS_alerts::check_bank("Credit"),
                        'into_account' => Harmony_SMS_alerts::check_account($exp_form[2]),
                        'amount' => preg_replace('/[^\d.]/', '', $exp_form[1]),
                        'remark' => "Auto",
                        'date_into' => Carbon::parse($formattedDate)->subDays(1)->format('Y-m-d H:i:s'),
                        'transfer_remark' => "ยอดเครดิต",
                        'status' => 4
                    ]);

                    Harmony_SMS_forwards::where('id', $value->id)->update([
                        'is_status' => 1
                    ]);

                } elseif ($value->sender == "BANGKOKBANK") {
                    if (count($exp_form) == 4 && $exp_form[0] == "เงินโอนเข้าบ/ชX1199ผ่านระบบ") { // Credit Card
                        $parts = preg_split('/[@]/', $exp_form[3]);
                        $time = $parts[1]; // "02:48"
                        $formattedDate = Carbon::parse($value->created_at)->format('Y-m-d');

                        Harmony_SMS_alerts::create([
                            'date' => $formattedDate." ".$time.":00",
                            'transfer_from' => Harmony_SMS_alerts::check_bank("Credit"),
                            'into_account' => Harmony_SMS_alerts::check_account($exp_form[0]),
                            'amount' => preg_replace('/[^\d.]/', '', $exp_form[1]),
                            'remark' => "Auto",
                            'date_into' => Carbon::parse($formattedDate)->subDays(1)->format('Y-m-d H:i:s'),
                            'transfer_remark' => "ยอดเครดิต",
                            'status' => 4
                        ]);
    
                        Harmony_SMS_forwards::where('id', $value->id)->update([
                            'is_status' => 1
                        ]);
                        
                    } elseif (count($exp_form) == 4) {
                        Harmony_SMS_alerts::create([
                            'date' => $value->created_at,
                            'transfer_from' => Harmony_SMS_alerts::check_bank($exp_form[0]),
                            'into_account' => Harmony_SMS_alerts::check_account($exp_form[0]),
                            'amount' => str_replace(",", "", $exp_form[1]),
                            'remark' => "Auto",
                            'status' => 0
                        ]);
    
                        Harmony_SMS_forwards::where('id', $value->id)->update([
                            'is_status' => 1
                        ]);
                    }

                } elseif ($value->sender == "KBank") {
                    $date = DateTime::createFromFormat('d/m/y', $exp_form[0]);

                    if ($date) {
                        $year = '25'.$date->format('y');

                        $yearInAD  = $year - 543;

                        $date->setDate($yearInAD, $date->format('m'), $date->format('d'));
                    }

                    if (isset($exp_form[4]) && $exp_form[4] == "รับโอนจาก") {
                        $letter = substr($exp_form[5], 0, 1);  // ตัวอักษร (ในที่นี้คือ "X")
                        $numbers = substr($exp_form[5], 2);    // ตัวเลขหลังเครื่องหมายขีด (ในที่นี้คือ "6163")
                        $formattedString = sprintf('xxx-x-x%s-%s', substr($numbers, 0, 3), substr($numbers, 3));

                        Harmony_SMS_alerts::create([
                            'date' => $date->format('Y-m-d').' '.Carbon::parse($exp_form[1])->format('H:i:s'),
                            'transfer_from' => '-',
                            'transfer_form_account' => $formattedString,
                            'into_account' => Harmony_SMS_alerts::check_account($exp_form[3]),
                            'amount' => str_replace(",", "", $exp_form[6]),
                            'remark' => "Auto",
                            'status' => 0
                        ]);
                    } else {
                        Harmony_SMS_alerts::create([
                            'date' => $date->format('Y-m-d').' '.Carbon::parse($exp_form[1])->format('H:i:s'),
                            'transfer_from' => '-',
                            'into_account' => Harmony_SMS_alerts::check_account($exp_form[3]),
                            'amount' => str_replace(",", "", $exp_form[5]),
                            'remark' => "Auto",
                            'status' => 0
                        ]);
                    }

                    Harmony_SMS_forwards::where('id', $value->id)->update([
                        'is_status' => 1
                    ]);
                }
            }
        }

        $adate = date('Y-m-d 21:00:00');
        $from = date("Y-m-d 21:00:00", strtotime("-1 day", strtotime($adate)));
        $to = date('Y-m-d 21:00:00');
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;

        $fromLast7 = date("Y-m-d 21:00:00", strtotime("-8 day", strtotime($adate)));

        // ตาราง 1
        $data_sms = Harmony_SMS_alerts::whereBetween('date', [$from, $to])->whereNull('date_into')->orderBy('date', 'asc')->paginate($perPage);
        $total_sms_amount = Harmony_SMS_alerts::whereBetween('date', [$from, $to])->whereNull('date_into')
            ->select(DB::raw("SUM(amount) as amount, COUNT(id) as total_sms"))->first();

        // ตารางที่ 2
        $data_sms_transfer = Harmony_SMS_alerts::whereDate('date_into', date('Y-m-d'))->where('transfer_status', 1)
            ->orWhereDate('date', date('Y-m-d'))->where('transfer_status', 1)
            ->orWhere('status', 4)->where('split_status', 0)->whereDate('date_into', date('Y-m-d'))
            ->orWhereDate('date', date('Y-m-d'))->where('status', 4)->where('split_status', 0)
            ->orderBy('date', 'asc')->paginate($perPage);
            
        $total_transfer_amount = Harmony_SMS_alerts::whereDate('date_into', date('Y-m-d'))->where('transfer_status', 1)
            ->orWhereDate('date', date('Y-m-d'))->where('transfer_status', 1)
            ->orWhere('status', 4)->where('split_status', 0)->whereDate('date_into', date('Y-m-d'))
            ->orWhereDate('date', date('Y-m-d'))->where('status', 4)->where('split_status', 0)
            ->select(DB::raw("SUM(amount) as amount, COUNT(id) as total_transfer"))->first();

        // ตารางที่ 3
        $data_sms_split = Harmony_SMS_alerts::whereDate('date_into', date('Y-m-d'))->where('split_status', 1)->orderBy('date', 'asc')->paginate($perPage);
        $total_split_amount = Harmony_SMS_alerts::whereDate('date_into', date('Y-m-d'))->where('split_status', 1)
            ->select(DB::raw("SUM(amount) as amount, COUNT(id) as total_split"))->first();

        $total_day = Harmony_SMS_alerts::whereBetween('date', [$from, $to])->WhereNull('transfer_remark')->where('split_status', 0)->orWhereDate('date_into', date('Y-m-d'))->sum('amount');
        $total_room = Harmony_SMS_alerts::whereBetween('date', [$from, $to])->where('status', 1)->whereNull('date_into')
            ->orWhereDate('date_into', date('Y-m-d'))->where('status', 1)
            ->sum('amount');
        $total_fb = Harmony_SMS_alerts::whereBetween('date', [$from, $to])->where('status', 2)->whereNull('date_into')
            ->orWhereDate('date_into', date('Y-m-d'))->where('status', 2)
            ->sum('amount');
        $total_wp = Harmony_SMS_alerts::whereBetween('date', [$from, $to])->where('status', 3)->whereNull('date_into')
            ->orWhereDate('date_into', date('Y-m-d'))->where('status', 3)
            ->sum('amount');
        $total_credit = Harmony_SMS_alerts::whereDate('date_into', [$from, $to])->where('into_account', "156-2-77492-1")
            ->where('status', 4)->sum('amount');
        $total_agoda = Harmony_SMS_alerts::whereBetween('date', [$from, $to])->where('status', 5)->whereNull('date_into')
            ->orWhereDate('date_into', date('Y-m-d'))->where('status', 5)
            ->sum('amount');
        $total_front = Harmony_SMS_alerts::whereBetween('date', [$from, $to])->where('status', 6)->whereNull('date_into')
            ->orWhereDate('date_into', date('Y-m-d'))->where('status', 6)
            ->sum('amount');
        $total_wp_credit = Harmony_SMS_alerts::whereBetween('date', [$from, $to])->where('status', 7)->whereNull('date_into')
            ->orWhereDate('date_into', date('Y-m-d'))->where('status', 7)
            ->sum('amount');
        $total_other = Harmony_SMS_alerts::whereBetween('date', [$from, $to])->where('status', 9)->whereNull('date_into')
            ->orWhereDate('date_into', date('Y-m-d'))->where('status', 9)
            ->sum('amount');
        $total_ev = Harmony_SMS_alerts::whereBetween('date', [$from, $to])->where('status', 8)->whereNull('date_into')
            ->orWhereDate('date_into', date('Y-m-d'))->where('status', 8)
            ->sum('amount');
        $total_credit = Harmony_SMS_alerts::whereDate('date_into', date('Y-m-d'))->where('into_account', "156-2-77492-1")->where('status', 4)->sum('amount');
        $total_credit_transaction = Harmony_SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', "156-2-77492-1")->where('status', 4)->count();
        $total_transfer = Harmony_SMS_alerts::whereDate('date_into', date('Y-m-d'))->where('transfer_status', 1)->sum('amount'); // ยอดที่ Transfer เข้าของวันนี้
        $total_transfer2 = Harmony_SMS_alerts::whereBetween('date', [$from, $to])->where('transfer_status', 1)->count(); // จำนวนที่ Transfer ไปวันอื่น
        $total_split = Harmony_SMS_alerts::whereDate('date_into', date('Y-m-d'))->where('split_status', 1)->sum('amount');
        $total_split_transaction = Harmony_SMS_alerts::whereBetween('date', [$from, $to])->where('split_status', 1)->select(DB::raw("SUM(amount) as amount, COUNT(id) as transfer_transaction"))->first();
        $total_not_type_revenue = Harmony_SMS_alerts::where(function ($query) use ($from, $to) {
                    $query->whereBetween('date', [$from, $to])->whereNull('date_into')->where('status', 0)
                    ->orWhereDate('date_into', date('Y-m-d'))->where('status', 0);
                })->sum('amount');
        $total_not_type = Harmony_SMS_alerts::where(function ($query) use ($from, $to) {
                    $query->whereBetween('date', [$from, $to])->whereNull('date_into')->where('status', 0)
                    ->orWhereDate('date_into', date('Y-m-d'))->where('status', 0);
                })->count();
        $total_transaction = Harmony_SMS_alerts::whereBetween('date', [$from, $to])->orWhereDate('date_into', date('Y-m-d'))->get();

        $data_bank = Masters::where('category', "bank")->where('status', 1)->select('id', 'name_th', 'name_en')->get();
        $filter_by = "date";

        return view('sms-forward-harmony.index',
            compact(
                'filter_by',
                // ตารางที่ 1
                'data_sms',
                'total_sms_amount',

                // ตารางที่ 2
                'data_sms_transfer',
                'total_transfer_amount',

                // ตารางที่ 3
                'data_sms_split',
                'total_split_amount',

                'total_day',
                'total_room',
                'total_fb',
                'total_wp',
                'total_ev',
                'total_other',
                'total_credit',
                'total_credit_transaction',
                'total_transfer',
                'total_not_type_revenue',
                'total_not_type',
                'total_transaction',
                'total_transfer2',
                'total_split',
                'total_split_transaction',
                'total_agoda',
                'total_front',
                'total_wp_credit',
                'data_bank'
            )
        );
    }

    public function search_table(Request $request)
    {
        $role_revenue = Role_permission_revenue::where('user_id', Auth::user()->id)->first();

        if ($request->filter_by != "yesterday" && $request->filter_by != "tomorrow" && $request->filter_by != "week" && $request->filter_by != "thisMonth" && $request->filter_by != "thisYear") {
            $checkDateRange = $this->checkDateRange($request);
        } else {
            $checkDateRange = $request->filter_by;
        }

        if ($checkDateRange == "date") {
            $exp_date = array_map('trim', explode('-', $request->date));
            $FormatDate = Carbon::createFromFormat('d/m/Y', $exp_date[0]);
            $FormatDate2 = Carbon::createFromFormat('d/m/Y', $exp_date[1]);

            // Format Y-m-d
            $FromFormatDate = $FormatDate->format('Y-m-d');
            $ToFormatDate = $FormatDate2->format('Y-m-d');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime(date($FromFormatDate))));
            $smsToDate = date('Y-m-d 20:59:59', strtotime($ToFormatDate));

            $filter_by = "date";

        } elseif ($checkDateRange == "yesterday") {
            $FormatDate = Carbon::now()->subDays(1);
            $FormatDate2 = Carbon::now()->subDays(1);

            // Format Y-m-d
            $FromFormatDate = $FormatDate->format('Y-m-d');
            $ToFormatDate = $FormatDate2->format('Y-m-d');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime(date($FromFormatDate))));
            $smsToDate = date('Y-m-d 20:59:59', strtotime(date($ToFormatDate)));

            $filter_by = "yesterday";

        } elseif ($checkDateRange == "tomorrow") {
            $FormatDate = Carbon::now()->addDay(1);
            $FormatDate2 = Carbon::now()->addDay(1);

            // Format Y-m-d
            $FromFormatDate = $FormatDate->format('Y-m-d');
            $ToFormatDate = $FormatDate2->format('Y-m-d');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime(date($FromFormatDate)));
            $smsToDate = date('Y-m-d 20:59:59', strtotime('+1 day', strtotime(date($ToFormatDate))));

            $filter_by = "tomorrow";

        } elseif ($checkDateRange == "month") {
            if (strpos($request->date, ' - ') !== false) { // กรณีเป็นช่วงเดือน เช่น "March - May 2024"
                $exp_date = array_map('trim', explode('-', $request->date));
                $startMonth = $exp_date[0];
                [$endMonth, $year] = explode(' ', $exp_date[1]); // แยกปีจาก endMonthYear

            } else { // กรณีเป็นเดือนเดียว เช่น "May 2024"
                [$month, $year] = explode(' ', $request->date);
                $startMonth = $month;
                $endMonth = $month;
            }

            // แปลงชื่อเดือนเป็นหมายเลขเดือน
            $startMonthNumber = Carbon::parse($startMonth . ' 1')->format('m'); // "03" สำหรับ March
            $endMonthNumber = Carbon::parse($endMonth . ' 1')->format('m'); // "05" สำหรับ May

            $FormatDate = Carbon::createFromFormat('Y-m', $year . '-' . $startMonthNumber); // 2024-03
            $FormatDate2 = Carbon::createFromFormat('Y-m', $year . '-' . $endMonthNumber); // 2024-05

            $FromFormatDate = $FormatDate->startOfMonth()->format('Y-m-d');
            $ToFormatDate = $FormatDate2->endOfMonth()->format('Y-m-d');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime(date($FromFormatDate))));
            $smsToDate = date('Y-m-d 20:59:59', strtotime($ToFormatDate));

            $filter_by = "month";

        } elseif ($checkDateRange == "year") {
            $FormatDate = Carbon::createFromFormat('Y', $request->date);
            $FormatDate2 = Carbon::createFromFormat('Y', $request->date);

            // Format Y-m-d
            $FromFormatDate = $FormatDate->format('Y-01-01');
            $ToFormatDate = $FormatDate2->format('Y-12-31');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime(date($FromFormatDate))));
            $smsToDate = date('Y-m-d 20:59:59', strtotime($ToFormatDate));

            $filter_by = "year";

        } elseif ($request->filter_by == "week") {
            $FormatDate = Carbon::parse(date('Y-m-d', strtotime('last sunday', strtotime('next sunday'))));
            $FormatDate2 = Carbon::parse(date('Y-m-d', strtotime('+6 day', strtotime(date($FormatDate)))));

            $FromFormatDate = $FormatDate->format('Y-m-d');
            $ToFormatDate = $FormatDate2->format('Y-m-d');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime(date($FromFormatDate))));
            $smsToDate = date('Y-m-d 20:59:59', strtotime($ToFormatDate));

            $filter_by = "week";

        } elseif ($request->filter_by == "thisMonth") {
            $FormatDate = Carbon::now();
            $FormatDate2 = Carbon::now();

            // Format Y-m-d
            $FromFormatDate = $FormatDate->startOfMonth()->format('Y-m-d');
            $ToFormatDate = $FormatDate2->endOfMonth()->format('Y-m-d');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime(date($FromFormatDate))));
            $smsToDate = date('Y-m-d 20:59:59', strtotime($ToFormatDate));

            $filter_by = "thisMonth";

        } elseif ($request->filter_by == "thisYear") {
            $FormatDate = Carbon::now();
            $FormatDate2 = Carbon::now();

            // Format Y-m-d
            $FromFormatDate = $FormatDate->format('Y-01-01');
            $ToFormatDate = $FormatDate2->format('Y-12-31');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime(date($FromFormatDate))));
            $smsToDate = date('Y-m-d 20:59:59', strtotime($ToFormatDate));

            $filter_by = "thisYear";
        }

        $data = [];

        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $search = $request->search_value;
        $status_type = $request->status == "no_income_revenue" ? 0 : $request->status;

        if ($request->table_name == "smsTable") {
            if (!empty($request->search_value)) {
                $data_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')
                    ->where(function($query) use ($search) {
                        $query->where('date', 'LIKE', '%'.$search.'%')
                        ->orWhere('amount', 'LIKE', '%'.$search.'%');
                    })
                    ->orderBy('date', 'asc')->paginate($perPage);
            } else {
                $data_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->orderBy('date', 'asc')->paginate($perPage);
            }

        } elseif ($request->table_name == "smsDetailTable") {
            if (!empty($request->search_value)) {
                if ($request->status == "total_transaction") {
                    $data_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])
                        ->where('date', 'LIKE', '%'.$search.'%')
                        ->orWhere('amount', 'LIKE', '%'.$search.'%')->whereBetween('date', [$smsFromDate, $smsToDate])
                        ->orderBy('date', 'asc')->paginate($perPage);

                } elseif ($request->status == "credit_card_hotel_transfer_transaction") { 
                    $data_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])
                        ->where('status', 4)->where('date', 'LIKE', '%'.$search.'%')
                        ->orWhere('amount', 'LIKE', '%'.$search.'%')->whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 4)
                        ->orderBy('date', 'asc')->paginate($perPage);

                } elseif ($request->status == "transfer_transaction") { 
                    $data_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])
                        ->where('transfer_status', 1)->where('date', 'LIKE', '%'.$search.'%')
                        ->orWhere('amount', 'LIKE', '%'.$search.'%')->whereBetween('date', [$smsFromDate, $smsToDate])->where('transfer_status', 1)
                        ->orderBy('date', 'asc')->paginate($perPage);

                } elseif ($request->status == "split_revenue") {
                    $data_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])
                        ->where('split_status', 1)->where('date', 'LIKE', '%'.$search.'%')
                        ->orWhere('amount', 'LIKE', '%'.$search.'%')->whereBetween('date', [$smsFromDate, $smsToDate])->where('split_status', 1)
                        ->orWhere('amount', 'LIKE', '%'.$search.'%')->whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('split_status', 1)
                        ->orderBy('date', 'asc')->paginate($perPage);

                }  elseif ($request->status == "split_credit_card_hotel_transaction") {
                    $data_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])
                        ->where('split_status', 1)->where('date', 'LIKE', '%'.$search.'%')
                        ->orWhere('amount', 'LIKE', '%'.$search.'%')->whereBetween('date', [$smsFromDate, $smsToDate])->where('split_status', 1)
                        ->orWhere('amount', 'LIKE', '%'.$search.'%')->whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('split_status', 1)
                        ->orderBy('date', 'asc')->paginate($perPage);

                } elseif ($request->status == "transfer_revenue") {
                    $data_query = Harmony_SMS_alerts::where('amount', 'LIKE', '%'.$search.'%')->whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])
                        ->where('transfer_status', 1)
                        ->orderBy('date', 'asc')->paginate($perPage);

                }else {
                    $data_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')
                        ->where('status', $status_type)->where('date', 'LIKE', '%'.$search.'%')
                        ->orWhere('amount', 'LIKE', '%'.$search.'%')->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('status', $status_type)
                        ->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', $status_type)
                        ->where('amount', 'LIKE', '%'.$search.'%')->where('status', $status_type)
                        ->orderBy('date', 'asc')->paginate($perPage);
                }

            } else {
                if ($request->status == "total_transaction") {
                    $data_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->orderBy('date', 'asc')->paginate($perPage);
                } elseif ($request->status == "credit_card_hotel_transfer_transaction") { 
                    $data_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 4)->orderBy('date', 'asc')->paginate($perPage);
                } elseif ($request->status == "transfer_transaction") { 
                    $data_query = Harmony_SMS_alerts::whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('transfer_status', 1)->orderBy('date', 'asc')->paginate($perPage);
                } elseif ($request->status == "split_revenue") {
                    $data_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('split_status', 1)
                        ->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('split_status', 1)
                        ->orderBy('date', 'asc')->paginate($perPage);
                } elseif ($request->status == "split_credit_card_hotel_transaction") {
                    $data_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('split_status', 1)
                        ->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('split_status', 1)
                        ->orderBy('date', 'asc')->paginate($perPage);
                }  elseif ($request->status == "transfer_revenue") {
                    $data_query = Harmony_SMS_alerts::whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('transfer_status', 1)
                        ->orderBy('date', 'asc')->paginate($perPage);
                } else {
                    $data_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('status', $status_type)
                        ->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', $status_type)
                        ->orderBy('date', 'asc')->paginate($perPage);
                }
            }

        } elseif ($request->table_name == "transferTable") {
            if (!empty($request->search_value)) {
                $data_query = Harmony_SMS_alerts::where('amount', 'LIKE', '%'.$request->search_value.'%')->whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('transfer_status', 1)
                    ->orWhere('amount', 'LIKE', '%'.$request->search_value.'%')->whereBetween(DB::raw('DATE(date)'), [$FromFormatDate, $ToFormatDate])->where('transfer_status', 1)
                    ->orderBy('date', 'asc')->paginate($perPage);
            } else {
                $data_query = Harmony_SMS_alerts::whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('transfer_status', 1)
                    ->orWhereBetween(DB::raw('DATE(date)'), [$FromFormatDate, $ToFormatDate])->where('transfer_status', 1)
                    ->orWhere('status', 4)->where('split_status', 0)->whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])
                    ->orWhereBetween(DB::raw('DATE(date)'), [$FromFormatDate, $ToFormatDate])->where('status', 4)->where('split_status', 0)
                    ->orderBy('date', 'asc')->paginate($perPage);
            }
            

        } elseif ($request->table_name == "splitTable") {
            if (!empty($request->search_value)) {
                $data_query = Harmony_SMS_alerts::whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('split_status', 1)
                    ->where('date', 'LIKE', '%'.$request->search_value.'%')
                    ->orWhere('amount', 'LIKE', '%'.$request->search_value.'%')->whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('split_status', 1)
                    ->orderBy('date', 'asc')->paginate($perPage);
            } else {
                $data_query = Harmony_SMS_alerts::whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('split_status', 1)->orderBy('date', 'asc')->paginate($perPage);
            }
            

        } elseif ($request->table_name == "smsAgodaTable") {
            if (!empty($request->search_value)) {
                $query_agoda = Harmony_SMS_alerts::query();

                if ($request->into_account != '') { 
                    if ($request->status != '') { 
                        $query_agoda->whereBetween('date', [$smsFromDate, $smsToDate])->where('amount', 'LIKE', '%'.$request->search_value.'%')->whereNull('date_into')->where('into_account', $request->into_account)->where('status', $request->status)->where('status', 5);
                        $query_agoda->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('amount', 'LIKE', '%'.$request->search_value.'%')->where('into_account', $request->into_account)->where('status', $request->status)->where('status', 5);
                    } else {
                        $query_agoda->whereBetween('date', [$smsFromDate, $smsToDate])->where('amount', 'LIKE', '%'.$request->search_value.'%')->whereNull('date_into')->where('into_account', $request->into_account)->where('status', 5);
                        $query_agoda->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('amount', 'LIKE', '%'.$request->search_value.'%')->where('into_account', $request->into_account)->where('status', 5);
                    }
                } else {
                    if ($request->status != '') { 
                        $query_agoda->whereBetween('date', [$smsFromDate, $smsToDate])->where('amount', 'LIKE', '%'.$request->search_value.'%')->whereNull('date_into')->where('status', $request->status)->where('status', 5);
                        $query_agoda->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('amount', 'LIKE', '%'.$request->search_value.'%')->where('status', $request->status)->where('status', 5);
                    } else {
                        $query_agoda->whereBetween('date', [$smsFromDate, $smsToDate])->where('amount', 'LIKE', '%'.$request->search_value.'%')->whereNull('date_into')->where('status', 5);
                        $query_agoda->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('amount', 'LIKE', '%'.$request->search_value.'%')->where('status', 5);
                    }
                }

                $data_query = $query_agoda->paginate($perPage);

            } else {
                $data_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 5)->whereNull('date_into')
                    ->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 5)
                    ->paginate($perPage);
            }
        }

        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {

                ## Check Close Day
                if ($value->date_into == '') {
                    $f_date = $value->date;
                } else {
                    $f_date = $value->date_into;
                }
                
                $close_day = Harmony_SMS_alerts::checkCloseDay($f_date);
                // $close_day = 0;
                ## End Check Close Day

                $img_bank = '';
                $transfer_bank = '';
                $revenue_name = '';
                $split_from = '';
                $btn_action = '';

                // โอนจากธนาคาร
                $filename = base_path() . '/public/image/bank/' . @$value->transfer_bank->name_en . '.jpg';
                $filename2 = base_path() . '/public/image/bank/' . @$value->transfer_bank->name_en . '.png';
            
                if (file_exists($filename)) {
                    $img_bank = '<img class="img-bank" src="../image/bank/'.@$value->transfer_bank->name_en.'.jpg">';
                } elseif (file_exists($filename2)) {
                    $img_bank = '<img class="img-bank" src="../image/bank/'.@$value->transfer_bank->name_en.'.png">';
                } else {
                    $img_bank = '<img class="img-bank" src="../assets/images/harmony/bank_transfer.png">';
                       if ($value->transfer_form_account == '' || $value->transfer_form_account == '-') {
                           $img_bank .= 'Bank Transfer';
                       }
                }

                $transfer_bank = '<div>'.$img_bank.''.@$value->transfer_bank->name_en.' '.@$value->transfer_form_account.'</div>';

                // เข้าบัญชี
                if ($value->into_account == "978-2-18099-9") {
                    $into_account = '<div class="flex-jc p-left-4 center"><img class="img-bank" src="../image/bank/KBNK.jpg">KBNK '.$value->into_account.'</div>';
                } elseif ($value->into_account == "436-0-75511-1" || $value->into_account == "156-2-77492-1") {
                    $into_account = '<div class="flex-jc p-left-4 center"><img class="img-bank" src="../image/bank/SCB.jpg">SCB '.$value->into_account.'</div>';
                } elseif ($value->into_account == "871-0-11991-1") {
                    $into_account = '<div class="flex-jc p-left-4 center"><img class="img-bank" src="../image/bank/BBL.png">BBL '.$value->into_account.'</div>';
                }

                // ประเภทรายได้
                if ($value->status == 0) { $revenue_name = '-'; } 
                if ($value->status == 1) { $revenue_name = 'Guest Deposit Revenue'; } 
                if($value->status == 2) { $revenue_name = 'All Outlet Revenue'; } 
                if($value->status == 3) { $revenue_name = 'Water Park Revenue'; } 
                if($value->status == 4) { $revenue_name = 'Credit Card Revenue'; } 
                if($value->status == 5) { $revenue_name = 'Agoda Bank Transfer Revenue'; } 
                if($value->status == 6) { $revenue_name = 'Front Desk Revenue'; } 
                if($value->status == 7) { $revenue_name = 'Credit Card Water Park Revenue'; } 
                if($value->status == 8) { $revenue_name = 'Elexa EGAT Revenue'; } 
                if($value->status == 9) { $revenue_name = 'Other Revenue Bank Transfer'; }

                if ($value->split_status == 1)
                {
                    $split_from = '<br>
                    <span class="text-danger">(Split Credit Card From '.number_format($value->fullAmount->amount_before_split, 2).')</span>';
                }

                if ($value->close_day == 0 || Auth::user()->edit_close_day == 1) {
                    $btn_action .='<div class="dropdown">';
                                $btn_action .='<button class="btn" type="button" style="background-color: #2C7F7A; color:white;" data-toggle="dropdown" data-toggle="dropdown">
                                    Select <span class="caret"></span>
                                </button>';
                                $btn_action .='<ul class="dropdown-menu">';
                                    if (@$role_revenue->front_desk == 1) {
                                        $btn_action .='<li class="button-li" onclick="change_status('.$value->id.', '."'Front Desk Revenue'".')">
                                                            Front Desk Bank <br>Transfer Revenue 
                                                        </li>';
                                    }
                                    if (@$role_revenue->guest_deposit == 1) {
                                        $btn_action .='<li class="button-li" onclick="change_status('.$value->id.', '."'Guest Deposit Revenue'".')">
                                                            Guest Deposit Bank <br> Transfer Revenue 
                                                        </li>';
                                    }
                                    if (@$role_revenue->all_outlet == 1) {
                                        $btn_action .='<li class="button-li" onclick="change_status('.$value->id.', '."'All Outlet Revenue'".')">
                                                            All Outlet Bank <br> Transfer Revenue 
                                                        </li>';
                                    }
                                    if (@$role_revenue->agoda == 1) {
                                        $btn_action .='<li class="button-li" onclick="change_status('.$value->id.', '."'Credit Agoda Revenue'".')">
                                                            Agoda Bank <br>Transfer Revenue 
                                                        </li>';
                                    }
                                    if (@$role_revenue->credit_card_hotel == 1) {
                                        $btn_action .='<li class="button-li" onclick="change_status('.$value->id.', '."'Credit Card Revenue'".')">
                                                            Credit Card Hotel <br> Revenue 
                                                        </li>';
                                    }
                                    if (@$role_revenue->elexa == 1) {
                                        $btn_action .='<li class="button-li" onclick="change_status('.$value->id.', '."'Elexa EGAT Revenue'".')">
                                                            Elexa EGAT Bank Transfer <br> Transfer Revenue
                                                        </li>';
                                    }
                                    if (@$role_revenue->no_category == 1) {
                                        $btn_action .='<li class="button-li" onclick="change_status('.$value->id.', '."'No Category'".')">
                                                            No Category
                                                        </li>';
                                    }
                                    if (@$role_revenue->water_park == 1) {
                                        $btn_action .='<li class="button-li" onclick="change_status('.$value->id.', '."'Water Park Revenue'".')">
                                                            Water Park Bank <br> Transfer Revenue 
                                                        </li>';
                                    }
                                    if (@$role_revenue->credit_water_park == 1) {
                                        $btn_action .='<li class="button-li" onclick="change_status('.$value->id.', '."'Credit Water Park Revenue'".')">
                                                            Credit Card Water <br>Park Revenue 
                                                        </li>';
                                    }
                                    if (@$role_revenue->other_revenue == 1) {
                                        $btn_action .='<li class="button-li" onclick="other_revenue_data('.$value->id.')">
                                                            Other Revenue <br> Bank Transfer
                                                        </li>';
                                    }
                                    if (@$role_revenue->transfer == 1) {
                                        $btn_action .='<li class="button-li" onclick="transfer_data('.$value->id.')">
                                                            Transfer
                                                        </li>';
                                    }
                                    if (@$role_revenue->time == 1) {
                                        $btn_action .='<li class="button-li" onclick="update_time_data('.$value->id.')">
                                                            Update Time
                                                        </li>';
                                    }
                                    if (@$role_revenue->split == 1) {
                                        $btn_action .='<li class="button-li" onclick="split_data('.$value->id.', {{ $item->amount }})">
                                                            Split Revenue
                                                        </li>';
                                    }
                                    if (@$role_revenue->edit == 1) {
                                        $btn_action .='<li class="button-li" onclick="edit('.$value->id.')">Edit</li>
                                                       <li class="button-li" onclick="deleted('.$value->id.')">Delete</li>';
                                    }
                                $btn_action .='</ul>';
                    $btn_action .='</div>';
                }

                $data[] = [
                    'id' => $key + 1,
                    'date' => Carbon::parse($value->date)->format('d/m/Y'),
                    'time' => Carbon::parse($value->date)->format('H:i:s'),
                    'transfer_bank' => $transfer_bank,
                    'into_account' => $into_account,
                    'amount' => number_format($value->amount, 2),
                    'remark' => $value->remark ?? 'Auto',
                    'revenue_name' => $revenue_name.$split_from,
                    'date_into' => !empty($value->date_into) ? Carbon::parse($value->date_into)->format('d/m/Y') : '-',
                    'btn_action' => $btn_action,
                ];
            }
        }

        return response()->json([
            'data' => $data,
            ]);
    }

    public function paginate_table(Request $request)
    {
        $role_revenue = Role_permission_revenue::where('user_id', Auth::user()->id)->first();

        if ($request->filter_by != "yesterday" && $request->filter_by != "tomorrow" && $request->filter_by != "week" && $request->filter_by != "thisMonth" && $request->filter_by != "thisYear") {
            $checkDateRange = $this->checkDateRange($request);
        } else {
            $checkDateRange = $request->filter_by;
        }

        if ($checkDateRange == "date") {
            $exp_date = array_map('trim', explode('-', $request->date));
            $FormatDate = Carbon::createFromFormat('d/m/Y', $exp_date[0]);
            $FormatDate2 = Carbon::createFromFormat('d/m/Y', $exp_date[1]);

            // Format Y-m-d
            $FromFormatDate = $FormatDate->format('Y-m-d');
            $ToFormatDate = $FormatDate2->format('Y-m-d');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime(date($FromFormatDate))));
            $smsToDate = date('Y-m-d 20:59:59', strtotime($ToFormatDate));

            $filter_by = "date";

        } elseif ($checkDateRange == "yesterday") {
            $FormatDate = Carbon::now()->subDays(1);
            $FormatDate2 = Carbon::now()->subDays(1);

            // Format Y-m-d
            $FromFormatDate = $FormatDate->format('Y-m-d');
            $ToFormatDate = $FormatDate2->format('Y-m-d');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime(date($FromFormatDate))));
            $smsToDate = date('Y-m-d 20:59:59', strtotime(date($ToFormatDate)));

            $filter_by = "yesterday";

        } elseif ($checkDateRange == "tomorrow") {
            $FormatDate = Carbon::now()->addDay(1);
            $FormatDate2 = Carbon::now()->addDay(1);

            // Format Y-m-d
            $FromFormatDate = $FormatDate->format('Y-m-d');
            $ToFormatDate = $FormatDate2->format('Y-m-d');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime(date($FromFormatDate)));
            $smsToDate = date('Y-m-d 20:59:59', strtotime('+1 day', strtotime(date($ToFormatDate))));

            $filter_by = "tomorrow";

        } elseif ($checkDateRange == "month") {
            if (strpos($request->date, ' - ') !== false) { // กรณีเป็นช่วงเดือน เช่น "March - May 2024"
                $exp_date = array_map('trim', explode('-', $request->date));
                $startMonth = $exp_date[0];
                [$endMonth, $year] = explode(' ', $exp_date[1]); // แยกปีจาก endMonthYear

            } else { // กรณีเป็นเดือนเดียว เช่น "May 2024"
                [$month, $year] = explode(' ', $request->date);
                $startMonth = $month;
                $endMonth = $month;
            }

            // แปลงชื่อเดือนเป็นหมายเลขเดือน
            $startMonthNumber = Carbon::parse($startMonth . ' 1')->format('m'); // "03" สำหรับ March
            $endMonthNumber = Carbon::parse($endMonth . ' 1')->format('m'); // "05" สำหรับ May

            $FormatDate = Carbon::createFromFormat('Y-m', $year . '-' . $startMonthNumber); // 2024-03
            $FormatDate2 = Carbon::createFromFormat('Y-m', $year . '-' . $endMonthNumber); // 2024-05

            $FromFormatDate = $FormatDate->startOfMonth()->format('Y-m-d');
            $ToFormatDate = $FormatDate2->endOfMonth()->format('Y-m-d');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime(date($FromFormatDate))));
            $smsToDate = date('Y-m-d 20:59:59', strtotime($ToFormatDate));

            $filter_by = "month";

        } elseif ($checkDateRange == "year") {
            $FormatDate = Carbon::createFromFormat('Y', $request->date);
            $FormatDate2 = Carbon::createFromFormat('Y', $request->date);

            // Format Y-m-d
            $FromFormatDate = $FormatDate->format('Y-01-01');
            $ToFormatDate = $FormatDate2->format('Y-12-31');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime(date($FromFormatDate))));
            $smsToDate = date('Y-m-d 20:59:59', strtotime($ToFormatDate));

            $filter_by = "year";

        } elseif ($request->filter_by == "week") {
            $FormatDate = Carbon::parse(date('Y-m-d', strtotime('last sunday', strtotime('next sunday'))));
            $FormatDate2 = Carbon::parse(date('Y-m-d', strtotime('+6 day', strtotime(date($FormatDate)))));

            $FromFormatDate = $FormatDate->format('Y-m-d');
            $ToFormatDate = $FormatDate2->format('Y-m-d');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime(date($FromFormatDate))));
            $smsToDate = date('Y-m-d 20:59:59', strtotime($ToFormatDate));

            $filter_by = "week";

        } elseif ($request->filter_by == "thisMonth") {
            $FormatDate = Carbon::now();
            $FormatDate2 = Carbon::now();

            // Format Y-m-d
            $FromFormatDate = $FormatDate->startOfMonth()->format('Y-m-d');
            $ToFormatDate = $FormatDate2->endOfMonth()->format('Y-m-d');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime(date($FromFormatDate))));
            $smsToDate = date('Y-m-d 20:59:59', strtotime($ToFormatDate));

            $filter_by = "thisMonth";

        } elseif ($request->filter_by == "thisYear") {
            $FormatDate = Carbon::now();
            $FormatDate2 = Carbon::now();

            // Format Y-m-d
            $FromFormatDate = $FormatDate->format('Y-01-01');
            $ToFormatDate = $FormatDate2->format('Y-12-31');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime(date($FromFormatDate))));
            $smsToDate = date('Y-m-d 20:59:59', strtotime($ToFormatDate));

            $filter_by = "thisYear";
        }

        $perPage = (int)$request->perPage;

        if ($request->table_name == "smsTable") {
            $query_sms = Harmony_SMS_alerts::query()->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into');

                if ($request->into_account != '') { 
                    $query_sms->where('into_account', $request->into_account);
                }
                if ($request->status != 0 && is_int($request->status)) { 
                    $query_sms->where('status', $request->status); 
                }

            $query_sms->orderBy('date', 'asc');

            if ($perPage == 10) {
                $data_query = $query_sms->limit($request->page.'0')->get();
            } else {
                $data_query = $query_sms->paginate($perPage);
            }

        } elseif ($request->table_name == "smsDetailTable") {
            $query_sms = Harmony_SMS_alerts::query();

            if ($request->status == "transfer_revenue") {
                $query_sms->whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('transfer_status', 1);
            } else {
                if ($request->status == "credit_card_hotel_transfer_transaction") {
                    $query_sms->whereBetween('date', [$smsFromDate, $smsToDate])->where('into_account', "156-2-77492-1")->where('status', 4);
                } elseif ($request->status == "total_transaction") {
                    $query_sms->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate]);
                } elseif ($request->status == "transfer_transaction") {
                    $query_sms->whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('transfer_status', 1);
                } elseif ($request->status == "split_revenue" || $request->status == "split_credit_card_hotel_transaction") {
                    $query_sms->whereBetween('date', [$smsFromDate, $smsToDate])->where('split_status', 1);
                    $query_sms->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('split_status', 1);
                } else {
                    $query_sms->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('status', $request->status);
                    $query_sms->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', $request->status);
                }
            }

            if ($request->into_account != '') { 
                $query_sms->where('into_account', $request->into_account);
            }

            $query_sms->orderBy('date', 'asc');

            if ($perPage == 10) {
                $data_query = $query_sms->limit($request->page.'0')->get(); 
            } else {
                $data_query = $query_sms->paginate($perPage);
            }

        } elseif ($request->table_name == "transferTable") {
            $query_transfer = Harmony_SMS_alerts::query();

                if ($request->into_account != '') { 
                    if ($request->status != 0 && $request->status != '') { 
                        $query_transfer->whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('transfer_status', 1)->where('into_account', $request->into_account)->where('status', $request->status);
                        $query_transfer->orWhere('status', 4)->where('split_status', 0)->whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('into_account', $request->into_account)->where('status', $request->status);
                    } else {
                        $query_transfer->whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('transfer_status', 1)->where('into_account', $request->into_account);
                        $query_transfer->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('transfer_status', 1)->where('into_account', $request->into_account);
                        $query_transfer->orWhere('status', 4)->where('split_status', 0)->whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('into_account', $request->into_account);
                    }
                } else {
                    if ($request->status != 0 && $request->status != '') { 
                        $query_transfer->whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('transfer_status', 1)->where('status', $request->status);
                        $query_transfer->orWhere('status', 4)->where('split_status', 0)->whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', $request->status);
                    } else {
                        $query_transfer->whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('transfer_status', 1);
                        $query_transfer->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('transfer_status', 1);
                        $query_transfer->orWhere('status', 4)->where('split_status', 0)->whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate]);
                    }
                }

            // $query_transfer->orWhereDate('date', '>=', $adate)->whereDate('date', '<=', $adate2)->where('status', 4)->where('split_status', 0);
            $query_transfer->orderBy('date', 'asc');

            if ($perPage == 10) {
                $data_query = $query_transfer->limit($request->page.'0')->get();
            } else {
                $data_query = $query_transfer->paginate($perPage);
            }

        } elseif ($request->table_name == "splitTable") {
            $query_split = Harmony_SMS_alerts::query()->whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('split_status', 1);

                if ($request->into_account != '') { 
                    $query_split->where('into_account', $request->into_account);
                }
                if ($request->status != 0 && is_int($request->status)) { 
                    $query_split->where('status', $request->status); 
                }

            $query_split->orderBy('date', 'asc');

            if ($perPage == 10) {
                $data_query = $query_split->limit($request->page.'0')->get();
            } else {
                $data_query = $query_split->paginate($perPage);
            }
        } elseif ($request->table_name == "smsAgodaTable") {
                $query_sms = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 5)->whereNull('date_into')
                    ->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 5);

                if ($perPage == 10) {
                    $data_query = $query_sms->limit($request->page.'0')->get();
                } else {
                    $data_query = $query_sms->paginate($perPage);
                }
        }

        $data = [];

        $page_1 = $request->page == 1 ? 1 : ($request->page - 1).'1';
        $page_2 = $request->page.'0';

        $perPage2 = $request->perPage > 10 ? $request->perPage : 10;

        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                if (($key + 1) >= (int)$page_1 && ($key + 1) <= (int)$page_2 || (int)$perPage > 10 && $key < (int)$perPage2) {

                    $img_bank = '';
                    $transfer_bank = '';
                    $revenue_name = '';
                    $split_from = '';
                    $btn_action = '';
    
                    // โอนจากธนาคาร
                    $filename = base_path() . '/public/image/bank/' . @$value->transfer_bank->name_en . '.jpg';
                    $filename2 = base_path() . '/public/image/bank/' . @$value->transfer_bank->name_en . '.png';
                
                    if (file_exists($filename)) {
                        $img_bank = '<img class="img-bank" src="../image/bank/'.@$value->transfer_bank->name_en.'.jpg">';
                    } elseif (file_exists($filename2)) {
                        $img_bank = '<img class="img-bank" src="../image/bank/'.@$value->transfer_bank->name_en.'.png">';
                    } else {
                         $img_bank = '<img class="img-bank" src="../assets/images/harmony/bank_transfer.png">';
                            if ($value->transfer_form_account == '' || $value->transfer_form_account == '-') {
                                $img_bank .= 'Bank Transfer';
                            }
                    }
    
                    $transfer_bank = '<div>'.$img_bank.''.@$value->transfer_bank->name_en.' '.@$value->transfer_form_account.'</div>';
    
                    // เข้าบัญชี
                    if ($value->into_account == "978-2-18099-9") {
                        $into_account = '<div class="flex-jc p-left-4 center"><img class="img-bank" src="../image/bank/KBNK.jpg">KBNK '.$value->into_account.'</div>';
                    } elseif ($value->into_account == "436-0-75511-1" || $value->into_account == "156-2-77492-1") {
                        $into_account = '<div class="flex-jc p-left-4 center"><img class="img-bank" src="../image/bank/SCB.jpg">SCB '.$value->into_account.'</div>';
                    } elseif ($value->into_account == "871-0-11991-1") {
                        $into_account = '<div class="flex-jc p-left-4 center"><img class="img-bank" src="../image/bank/BBL.png">BBL '.$value->into_account.'</div>';
                    }
    
                    // ประเภทรายได้
                    if ($value->status == 0) { $revenue_name = '-'; } 
                    if ($value->status == 1) { $revenue_name = 'Guest Deposit Revenue'; } 
                    if($value->status == 2) { $revenue_name = 'All Outlet Revenue'; } 
                    if($value->status == 3) { $revenue_name = 'Water Park Revenue'; } 
                    if($value->status == 4) { $revenue_name = 'Credit Card Revenue'; } 
                    if($value->status == 5) { $revenue_name = 'Agoda Bank Transfer Revenue'; } 
                    if($value->status == 6) { $revenue_name = 'Front Desk Revenue'; } 
                    if($value->status == 7) { $revenue_name = 'Credit Card Water Park Revenue'; } 
                    if($value->status == 8) { $revenue_name = 'Elexa EGAT Revenue'; } 
                    if($value->status == 9) { $revenue_name = 'Other Revenue Bank Transfer'; }

                    if ($value->split_status == 1)
                    {
                        $split_from = '<br>
                        <span class="text-danger">(Split Credit Card From '.number_format($value->fullAmount->amount_before_split, 2).')</span>';
                    }
    
                    if ($value->close_day == 0 || Auth::user()->edit_close_day == 1) {
                        $btn_action .='<div class="dropdown">';
                            $btn_action .='<button class="btn" type="button" style="background-color: #2C7F7A; color:white;" data-toggle="dropdown" data-toggle="dropdown">
                                Select <span class="caret"></span>
                            </button>';
                            $btn_action .='<ul class="dropdown-menu">';
                                if (@$role_revenue->front_desk == 1) {
                                    $btn_action .='<li class="button-li" onclick="change_status('.$value->id.', '."'Front Desk Revenue'".')">
                                                        Front Desk Bank <br>Transfer Revenue 
                                                    </li>';
                                }
                                if (@$role_revenue->guest_deposit == 1) {
                                    $btn_action .='<li class="button-li" onclick="change_status('.$value->id.', '."'Guest Deposit Revenue'".')">
                                                        Guest Deposit Bank <br> Transfer Revenue 
                                                    </li>';
                                }
                                if (@$role_revenue->all_outlet == 1) {
                                    $btn_action .='<li class="button-li" onclick="change_status('.$value->id.', '."'All Outlet Revenue'".')">
                                                        All Outlet Bank <br> Transfer Revenue 
                                                    </li>';
                                }
                                if (@$role_revenue->agoda == 1) {
                                    $btn_action .='<li class="button-li" onclick="change_status('.$value->id.', '."'Credit Agoda Revenue'".')">
                                                        Agoda Bank <br>Transfer Revenue 
                                                    </li>';
                                }
                                if (@$role_revenue->credit_card_hotel == 1) {
                                    $btn_action .='<li class="button-li" onclick="change_status('.$value->id.', '."'Credit Card Revenue'".')">
                                                        Credit Card Hotel <br> Revenue 
                                                    </li>';
                                }
                                if (@$role_revenue->elexa == 1) {
                                    $btn_action .='<li class="button-li" onclick="change_status('.$value->id.', '."'Elexa EGAT Revenue'".')">
                                                        Elexa EGAT Bank Transfer <br> Transfer Revenue
                                                    </li>';
                                }
                                if (@$role_revenue->no_category == 1) {
                                    $btn_action .='<li class="button-li" onclick="change_status('.$value->id.', '."'No Category'".')">
                                                        No Category
                                                    </li>';
                                }
                                if (@$role_revenue->water_park == 1) {
                                    $btn_action .='<li class="button-li" onclick="change_status('.$value->id.', '."'Water Park Revenue'".')">
                                                        Water Park Bank <br> Transfer Revenue 
                                                    </li>';
                                }
                                if (@$role_revenue->credit_water_park == 1) {
                                    $btn_action .='<li class="button-li" onclick="change_status('.$value->id.', '."'Credit Water Park Revenue'".')">
                                                        Credit Card Water <br>Park Revenue 
                                                    </li>';
                                }
                                if (@$role_revenue->other_revenue == 1) {
                                    $btn_action .='<li class="button-li" onclick="other_revenue_data('.$value->id.')">
                                                        Other Revenue <br> Bank Transfer
                                                    </li>';
                                }
                                if (@$role_revenue->transfer == 1) {
                                    $btn_action .='<li class="button-li" onclick="transfer_data('.$value->id.')">
                                                        Transfer
                                                    </li>';
                                }
                                if (@$role_revenue->time == 1) {
                                    $btn_action .='<li class="button-li" onclick="update_time_data('.$value->id.')">
                                                        Update Time
                                                    </li>';
                                }
                                if (@$role_revenue->split == 1) {
                                    $btn_action .='<li class="button-li" onclick="split_data('.$value->id.', '.$value->amount.')">
                                                        Split Revenue
                                                    </li>';
                                }
                                if (@$role_revenue->edit == 1) {
                                    $btn_action .='<li class="button-li" onclick="edit('.$value->id.')">Edit</li>
                                                    <li class="button-li" onclick="deleted('.$value->id.')">Delete</li>';
                                }
                            $btn_action .='</ul>';
                        $btn_action .='</div>';
                    }
    
                    $data[] = [
                        'number' => $key + 1,
                        'date' => Carbon::parse($value->date)->format('d/m/Y'),
                        'time' => Carbon::parse($value->date)->format('H:i:s'),
                        'transfer_bank' => $transfer_bank,
                        'into_account' => $into_account,
                        'amount' => number_format($value->amount, 2),
                        'remark' => $value->remark ?? 'Auto',
                        'revenue_name' => $revenue_name.$split_from,
                        'date_into' => !empty($value->date_into) ? Carbon::parse($value->date_into)->format('d/m/Y') : '-',
                        'btn_action' => $btn_action,
                    ];
                }
            }
        }

        return response()->json([
                'data' => $data,
            ]);
    }

    public function graph30days($to_date, $type, $account)
    {

        $days = date('d', strtotime('last day of this month', strtotime(date($to_date))));

        if ($days == "31") {
            $start = date("Y-m-d 21:00:00", strtotime("-30 day", strtotime($to_date)));
        } elseif ($days == "30") {
            $start = date("Y-m-d 21:00:00", strtotime("-29 day", strtotime($to_date)));
        } elseif ($days == "29") {
            $start = date("Y-m-d 21:00:00", strtotime("-28 day", strtotime($to_date)));
        } elseif ($days == "28") {
            $start = date("Y-m-d 21:00:00", strtotime("-27 day", strtotime($to_date)));
        }

        $amount = [];
        $date = [];

        for ($i = 1; $i <= $days; $i++) {
            $from_start = date("Y-m-d 21:00:00", strtotime("-1 day", strtotime($start)));
            $to_end = date("Y-m-d 21:00:00", strtotime("+1 day", strtotime($from_start)));
            $adate2 = Carbon::parse($to_end)->format('Y-m-d');

            if (!empty($type)) {
                if (!empty($account)) {
                    $sum_amount = Harmony_SMS_alerts::whereBetween('date', [$from_start, $to_end])->where('status', $type)->where('into_account', $account)->WhereNull('transfer_remark')->where('split_status', 0)
                        ->orWhereDate('date_into', $adate2)->where('status', $type)->where('into_account', $account)->orderBy('date', 'asc')->sum('amount');
                } else {
                    $sum_amount = Harmony_SMS_alerts::whereBetween('date', [$from_start, $to_end])->where('status', $type)->WhereNull('transfer_remark')->where('split_status', 0)
                        ->orWhereDate('date_into', $adate2)->where('status', $type)->orderBy('date', 'asc')->sum('amount');
                }
            } else {
                if (!empty($account)) {
                    $sum_amount = Harmony_SMS_alerts::whereBetween('date', [$from_start, $to_end])->where('into_account', $account)->WhereNull('transfer_remark')->where('split_status', 0)
                        ->orWhereDate('date_into', $adate2)->where('into_account', $account)->orderBy('date', 'asc')->sum('amount');
                } else {
                    $sum_amount = Harmony_SMS_alerts::whereBetween('date', [$from_start, $to_end])->WhereNull('transfer_remark')->where('split_status', 0)
                        ->orWhereDate('date_into', $adate2)->orderBy('date', 'asc')->sum('amount');
                }
            }

            $amount[] = number_format($sum_amount, 2, '.', '');
            $date[] = Carbon::parse($adate2)->format('d/m');

            $start = date("Y-m-d 21:00:00", strtotime("+2 day", strtotime($from_start)));
        }

        return response()->json([
            'amount' => $amount,
            'date' => $date,
        ]);
    }

    public function graphThisWeek($to_date, $type, $account)
    {
        $days_value = [];

        $sundayOfWeek = date('Y-m-d', strtotime('last sunday', strtotime('next sunday', strtotime(date($to_date)))));

        // dd($sundayOfWeek);

        $weeks = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

        $amount = [];
        $date = [];

        for ($i=0; $i < 7; $i++) {

            $from_start = date("Y-m-d 21:00:00", strtotime("-1 day", strtotime($sundayOfWeek)));
            $to_end = date($sundayOfWeek." 20:59:59");
            $adate2 = Carbon::parse($to_end)->format('Y-m-d');

            if (!empty($type)) {
                if (!empty($account)) {
                    $sum_amount = Harmony_SMS_alerts::whereBetween('date', [$from_start, $to_end])->where('status', $type)->where('into_account', $account)->WhereNull('transfer_remark')->where('split_status', 0)
                        ->orWhereDate('date_into', $adate2)->where('status', $type)->where('into_account', $account)->orderBy('date', 'asc')->sum('amount');
                } else {
                    $sum_amount = Harmony_SMS_alerts::whereBetween('date', [$from_start, $to_end])->where('status', $type)->WhereNull('transfer_remark')->where('split_status', 0)
                        ->orWhereDate('date_into', $adate2)->where('status', $type)->orderBy('date', 'asc')->sum('amount');
                }
            } else {
                if (!empty($account)) {
                    $sum_amount = Harmony_SMS_alerts::whereBetween('date', [$from_start, $to_end])->where('into_account', $account)->WhereNull('transfer_remark')->where('split_status', 0)
                        ->orWhereDate('date_into', $adate2)->where('into_account', $account)->orderBy('date', 'asc')->sum('amount');
                } else {
                    $sum_amount = Harmony_SMS_alerts::whereBetween('date', [$from_start, $to_end])->WhereNull('transfer_remark')->where('split_status', 0)
                        ->orWhereDate('date_into', $adate2)->orderBy('date', 'asc')->sum('amount');
                }
            }

            $sundayOfWeek = date("Y-m-d", strtotime("+1 day", strtotime($sundayOfWeek)));

            // $average = $sum_amount / count($days_value['sunday']);

            $amount[] = number_format($sum_amount, 2, '.', '');
        }

        return response()->json([
            'amount' => $amount,
            // 'date' => $date,
        ]);
    }

    public function graphThisMonth($to_date, $type, $account)
    {
        $day = date('d', strtotime($to_date));
        $month = date('m', strtotime($to_date));
        $year = date('Y', strtotime($to_date));

        $lastday = date('d', strtotime('last day of this month', strtotime(date($to_date))));

        $amount = [];
        $date = [];

        for ($i = 1; $i <= $lastday; $i++) {
            $from_start = date("Y-m-d 21:00:00", strtotime("-1 day", strtotime($year."-".$month."-".$i)));
            $to_end = date("Y-m-".($i == $lastday || $i == 1 ? $i : $i)." 20:59:59");
            $adate2 = Carbon::parse($to_end)->format('Y-m-d');

            if (!empty($type)) {
                if (!empty($account)) {
                    $sum_amount = Harmony_SMS_alerts::whereBetween('date', [$from_start, $to_end])->where('status', $type)->where('into_account', $account)->WhereNull('transfer_remark')->where('split_status', 0)
                        ->orWhereDate('date_into', $adate2)->where('status', $type)->where('into_account', $account)->orderBy('date', 'asc')->sum('amount');
                } else {
                    $sum_amount = Harmony_SMS_alerts::whereBetween('date', [$from_start, $to_end])->where('status', $type)->WhereNull('transfer_remark')->where('split_status', 0)
                        ->orWhereDate('date_into', $adate2)->where('status', $type)->orderBy('date', 'asc')->sum('amount');
                }
            } else {
                if (!empty($account)) {
                    $sum_amount = Harmony_SMS_alerts::whereBetween('date', [$from_start, $to_end])->where('into_account', $account)->WhereNull('transfer_remark')->where('split_status', 0)
                        ->orWhereDate('date_into', $adate2)->where('into_account', $account)->orderBy('date', 'asc')->sum('amount');
                } else {
                    $sum_amount = Harmony_SMS_alerts::whereBetween('date', [$from_start, $to_end])->WhereNull('transfer_remark')->where('split_status', 0)
                        ->orWhereDate('date_into', $adate2)->orderBy('date', 'asc')->sum('amount');
                }
            }

            $amount[] = number_format($sum_amount, 2, '.', '');
            $date[] = Carbon::parse($to_end)->format('d/m');

            $start = date("Y-m-d 21:00:00", strtotime("+2 day", strtotime($from_start)));
        }

        return response()->json([
            'amount' => $amount,
            'date' => $date,
        ]);
    }

    public function graphDateRang($startdate, $enddate, $type, $account)
    {

        $amount = [];
        $date = [];

        for ($i = $startdate; $i <= $enddate; $i = date('Y-m-d', strtotime("+1 day", strtotime($i)))) {
            $from_start = date("Y-m-d 21:00:00", strtotime("-1 day", strtotime($i)));
            $to_end = date($i." 20:59:59");
            $adate2 = Carbon::parse($to_end)->format('Y-m-d');

            if (!empty($type)) {
                if (!empty($account)) {
                    $sum_amount = Harmony_SMS_alerts::whereBetween('date', [$from_start, $to_end])->where('status', $type)->where('into_account', $account)->WhereNull('transfer_remark')->where('split_status', 0)
                        ->orWhereDate('date_into', $adate2)->where('status', $type)->where('into_account', $account)->orderBy('date', 'asc')->sum('amount');
                } else {
                    $sum_amount = Harmony_SMS_alerts::whereBetween('date', [$from_start, $to_end])->where('status', $type)->WhereNull('transfer_remark')->where('split_status', 0)
                        ->orWhereDate('date_into', $adate2)->where('status', $type)->orderBy('date', 'asc')->sum('amount');
                }
            } else {
                if (!empty($account)) {
                    $sum_amount = Harmony_SMS_alerts::whereBetween('date', [$from_start, $to_end])->where('into_account', $account)->WhereNull('transfer_remark')->where('split_status', 0)
                        ->orWhereDate('date_into', $adate2)->where('into_account', $account)->orderBy('date', 'asc')->sum('amount');
                } else {
                    $sum_amount = Harmony_SMS_alerts::whereBetween('date', [$from_start, $to_end])->WhereNull('transfer_remark')->where('split_status', 0)
                        ->orWhereDate('date_into', $adate2)->orderBy('date', 'asc')->sum('amount');
                }
            }

            $amount[] = number_format($sum_amount, 2, '.', '');
            $date[] = Carbon::parse($to_end)->format('d/m');
        }

        return response()->json([
            'amount' => $amount,
            'date' => $date,
        ]);
    }

    public function graphThisMonthByDay($to_date, $type, $account)
    {
        $day = date('d', strtotime($to_date));
        $month = date('m', strtotime($to_date));
        $year = date('Y', strtotime($to_date));

        $thisMonth = $month;
        $days_value = [];

        $weeks = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];

        foreach ($weeks as $key => $value) {
            $week_day = "date_".$value;

            $week_day = date('Y-m-d', strtotime('first '.$value.' of this month', strtotime(date($to_date))));
            while (date('m', strtotime($week_day)) === $thisMonth) {
                $days_value[$value][] = date('Y-m-d', strtotime($week_day));
                $week_day = date('Y-m-d', strtotime('next '.$value, strtotime(date($week_day))));
            }
        }

        $amount = [];
        $date = [];

        if (!empty($days_value)) {
            foreach ($weeks as $key => $item) {
                $sum = 0;
                
                // $count_key = count($item);
                foreach ($days_value[$item] as $key => $date_value) {
                    $from_start = date("Y-m-d 21:00:00", strtotime("-1 day", strtotime($date_value)));
                    $to_end = date($date_value." 20:59:59");
                    $adate2 = Carbon::parse($to_end)->format('Y-m-d');

                    if (!empty($type)) {
                        if (!empty($account)) {
                            $sum_amount = Harmony_SMS_alerts::whereBetween('date', [$from_start, $to_end])->where('status', $type)->where('into_account', $account)->WhereNull('transfer_remark')->where('split_status', 0)
                                ->orWhereDate('date_into', $adate2)->where('status', $type)->where('into_account', $account)->orderBy('date', 'asc')->sum('amount');
                        } else {
                            $sum_amount = Harmony_SMS_alerts::whereBetween('date', [$from_start, $to_end])->where('status', $type)->WhereNull('transfer_remark')->where('split_status', 0)
                                ->orWhereDate('date_into', $adate2)->where('status', $type)->orderBy('date', 'asc')->sum('amount');
                        }
                    } else {
                        if (!empty($account)) {
                            $sum_amount = Harmony_SMS_alerts::whereBetween('date', [$from_start, $to_end])->where('into_account', $account)->WhereNull('transfer_remark')->where('split_status', 0)
                                ->orWhereDate('date_into', $adate2)->where('into_account', $account)->orderBy('date', 'asc')->sum('amount');
                        } else {
                            $sum_amount = Harmony_SMS_alerts::whereBetween('date', [$from_start, $to_end])->WhereNull('transfer_remark')->where('split_status', 0)
                                ->orWhereDate('date_into', $adate2)->orderBy('date', 'asc')->sum('amount');
                        }
                    }

                    $sum +=  $sum_amount;
                }
                $average = $sum / count($days_value[$item]);
        
                $amount[] = number_format($average, 2, '.', '');
            }
        }

        return response()->json([
            'amount' => $amount,
            // 'date' => $date,
        ]);
    }

    public function graphYearRange($year, $type, $account)
    {
        $thisYear = $year;
        $months = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];

        $amount = [];
        $date = [];

        foreach ($months as $key => $item) {

            $date_from = date($year.'-'.$item.'-01');
            $lastday = date('d', strtotime('last day of this month', strtotime(date($date_from))));

            $from_start = date("Y-m-d 21:00:00", strtotime("-1 day", strtotime($thisYear.'-'.$item.'-01')));
            $to_end = date($thisYear.'-'.$item.'-'.$lastday.' 20:59:59');
            $adate2 = Carbon::parse($to_end)->format('Y-m-d');

            if (!empty($type)) {
                if (!empty($account)) {
                    $sum_amount = Harmony_SMS_alerts::whereBetween('date', [$from_start, $to_end])->where('status', $type)->where('into_account', $account)->WhereNull('transfer_remark')->where('split_status', 0)
                        ->orWhereDate('date_into', $adate2)->where('status', $type)->where('into_account', $account)->orderBy('date', 'asc')->sum('amount');
                } else {
                    $sum_amount = Harmony_SMS_alerts::whereBetween('date', [$from_start, $to_end])->where('status', $type)->WhereNull('transfer_remark')->where('split_status', 0)
                        ->orWhereDate('date_into', $adate2)->where('status', $type)->orderBy('date', 'asc')->sum('amount');
                }
            } else {
                if (!empty($account)) {
                    $sum_amount = Harmony_SMS_alerts::whereBetween('date', [$from_start, $to_end])->where('into_account', $account)->WhereNull('transfer_remark')->where('split_status', 0)
                        ->orWhereDate('date_into', $adate2)->where('into_account', $account)->orderBy('date', 'asc')->sum('amount');
                } else {
                    $sum_amount = Harmony_SMS_alerts::whereBetween('date', [$from_start, $to_end])->WhereNull('transfer_remark')->where('split_status', 0)
                        ->orWhereDate('date_into', $adate2)->orderBy('date', 'asc')->sum('amount');
                }
            }

            $amount[] = number_format($sum_amount, 2, '.', '');
        }

        return response()->json([
            'amount' => $amount,
        ]);
    }

    public function graphMonthRange($month, $to_month, $year, $type, $account)
    {
        $months = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
        $month = date('m', strtotime(date($year.'-'.$month.'-d')));
        $to_month = date('m', strtotime(date($year.'-'.$to_month.'-d')));

        $amount = [];
        $date = [];

        foreach ($months as $key => $item) {

            if (($key + 1) >= (int)$month && ($key + 1) <= (int)$to_month) {
                $date_from = date($year.'-'.$item.'-01');
                $lastday = date('d', strtotime('last day of this month', strtotime(date($date_from))));

                $from_start = date("Y-m-d 21:00:00", strtotime("-1 day", strtotime($year.'-'.$item.'-01')));
                $to_end = date($year.'-'.$item.'-'.$lastday.' 20:59:59');
                $adate2 = Carbon::parse($to_end)->format('Y-m-d');

                if (!empty($type)) {
                    if (!empty($account)) {
                        $sum_amount = Harmony_SMS_alerts::whereBetween('date', [$from_start, $to_end])->where('status', $type)->where('into_account', $account)->whereNull('date_into')
                            ->orWhereDate('date_into', '>=', $date_from)->whereDate('date_into', '<=', $adate2)->where('status', $type)->where('into_account', $account)->orderBy('date', 'asc')->sum('amount');
                    } else {
                        $sum_amount = Harmony_SMS_alerts::whereBetween('date', [$from_start, $to_end])->where('status', $type)->whereNull('date_into')
                            ->orWhereDate('date_into', '>=', $date_from)->whereDate('date_into', '<=', $adate2)->where('status', $type)->orderBy('date', 'asc')->sum('amount');
                    }
                } else {
                    if (!empty($account)) {
                        $sum_amount = Harmony_SMS_alerts::whereBetween('date', [$from_start, $to_end])->where('into_account', $account)->whereNull('date_into')
                            ->orWhereDate('date_into', '>=', $date_from)->whereDate('date_into', '<=', $adate2)->where('into_account', $account)->orderBy('date', 'asc')->sum('amount');
                    } else {
                        $sum_amount = Harmony_SMS_alerts::whereBetween('date', [$from_start, $to_end])->whereNull('date_into')
                            ->orWhereDate('date_into', '>=', $date_from)->whereDate('date_into', '<=', $adate2)->orderBy('date', 'asc')->sum('amount');
                    }
                }
                $amount[] = number_format($sum_amount, 2, '.', '');
            } else {
                $amount[] = number_format(0, 2, '.', '');
            }
        }

        return response()->json([
            'amount' => $amount,
        ]);
    }

    public function graphToday($date)
    {

        $adate = $date;
        $to_date = date_create($adate);
        $to_fm = date_format($to_date, "Y-m-d");

        ## 21:00:00 - 23:59:59
        $from_1 = date("Y-m-d 21:00:00", strtotime("-1 day", strtotime($adate)));
        $to_1 = date('Y-m-d 23:59:59', strtotime("-1 day", strtotime($adate)));
        $data_1 = Harmony_SMS_alerts::whereBetween('date', [$from_1, $to_1])->WhereNull('transfer_remark')->where('split_status', 0)
            ->orWhereDate('date_into', $to_fm)->orderBy('date', 'asc')->sum('amount');

        ## 00:00:00 - 02:59:59
        $from_2 = date("Y-m-d 00:00:00");
        $to_2 = date('Y-m-d 02:59:59');
        $data_2 = Harmony_SMS_alerts::whereBetween('date', [$from_2, $to_2])->WhereNull('transfer_remark')->where('split_status', 0)
            ->orWhereDate('date_into', $to_fm)->orderBy('date', 'asc')->sum('amount');

        ## 03:00:00 - 05:59:59
        $from_3 = date("Y-m-d 03:00:00");
        $to_3 = date('Y-m-d 05:59:59');
        $data_3 = Harmony_SMS_alerts::whereBetween('date', [$from_3, $to_3])->WhereNull('transfer_remark')->where('split_status', 0)
            ->orWhereDate('date_into', $to_fm)->orderBy('date', 'asc')->sum('amount');

        ## 06:00:00 - 08:59:59
        $from_4 = date("Y-m-d 06:00:00");
        $to_4 = date('Y-m-d 08:59:59');
        $data_4 = Harmony_SMS_alerts::whereBetween('date', [$from_4, $to_4])->WhereNull('transfer_remark')->where('split_status', 0)
            ->orWhereDate('date_into', $to_fm)->orderBy('date', 'asc')->sum('amount');

        ## 09:00:00 - 11:59:59
        $from_5 = date("Y-m-d 09:00:00");
        $to_5 = date('Y-m-d 11:59:59');
        $data_5 = Harmony_SMS_alerts::whereBetween('date', [$from_5, $to_5])->WhereNull('transfer_remark')->where('split_status', 0)
            ->orWhereDate('date_into', $to_fm)->orderBy('date', 'asc')->sum('amount');

        ## 12:00:00 - 14:59:59
        $from_6 = date("Y-m-d 12:00:00");
        $to_6 = date('Y-m-d 14:59:59');
        $data_6 = Harmony_SMS_alerts::whereBetween('date', [$from_6, $to_6])->WhereNull('transfer_remark')->where('split_status', 0)
            ->orWhereDate('date_into', $to_fm)->orderBy('date', 'asc')->sum('amount');

        ## 15:00:00 - 17:59:59
        $from_7 = date("Y-m-d 15:00:00");
        $to_7 = date('Y-m-d 17:59:59');
        $data_7 = Harmony_SMS_alerts::whereBetween('date', [$from_7, $to_7])->WhereNull('transfer_remark')->where('split_status', 0)
            ->orWhereDate('date_into', $to_fm)->orderBy('date', 'asc')->sum('amount');

        ## 18:00:00 - 20:59:59
        $from_8 = date("Y-m-d 18:00:00");
        $to_8 = date('Y-m-d 20:59:59');
        $data_8 = Harmony_SMS_alerts::whereBetween('date', [$from_8, $to_8])->WhereNull('transfer_remark')->where('split_status', 0)
            ->orWhereDate('date_into', $to_fm)->orderBy('date', 'asc')->sum('amount');

        return response()->json([
            'data_1' => $data_1 > 0 ? number_format($data_1, 2, '.', '') : 0,
            'data_2' => $data_2 > 0 ? number_format($data_2, 2, '.', '') : 0,
            'data_3' => $data_3 > 0 ? number_format($data_3, 2, '.', '') : 0,
            'data_4' => $data_4 > 0 ? number_format($data_4, 2, '.', '') : 0,
            'data_5' => $data_5 > 0 ? number_format($data_5, 2, '.', '') : 0,
            'data_6' => $data_6 > 0 ? number_format($data_6, 2, '.', '') : 0,
            'data_7' => $data_7 > 0 ? number_format($data_7, 2, '.', '') : 0,
            'data_8' => $data_8 > 0 ? number_format($data_8, 2, '.', '') : 0,
        ]);
    }

    public function graphForcast($to_date)
    {

        $adate = date($to_date);

        ## Yesterday
        $from_1 = date("Y-m-d 21:00:00", strtotime("-2 day", strtotime($adate)));
        $to_1 = date('Y-m-d 21:00:00', strtotime("-1 day", strtotime($adate)));

        $to_1_date = date_create($to_1);
        $to_1_fm = date_format($to_1_date, "Y-m-d");

        $yesterday = Harmony_SMS_alerts::whereBetween('date', [$from_1, $to_1])->WhereNull('transfer_remark')->where('split_status', 0)
            ->orWhereDate('date_into', $to_1_fm)->orderBy('date', 'asc')->sum('amount');

        ## Today
        $from_2 = date("Y-m-d 21:00:00", strtotime("-1 day", strtotime($adate)));
        $to_2 = date('Y-m-d 21:00:00', strtotime($adate));

        $to_2_date = date_create($to_2);
        $to_2_fm = date_format($to_2_date, "Y-m-d");

        $today = Harmony_SMS_alerts::whereBetween('date', [$from_2, $to_2])->WhereNull('transfer_remark')->where('split_status', 0)
            ->orWhereDate('date_into', $to_2_fm)->orderBy('date', 'asc')->sum('amount');

        ## Forcast
        $day = date('d');
        if ($day == '01') {
            $from_3 = date('Y-m-d 21:00:00', strtotime("-1 day", strtotime($adate)));
            $to_3 = date('Y-m-01 21:00:00');
        } else {
            $date = date('Y-m-01 21:00:00');
            $from_3 = date('Y-m-d 21:00:00', strtotime("-1 day", strtotime($date)));
            $to_3 = date('Y-m-' . $day . ' 21:00:00');
        }

        // $to_3_date = date_create($to_3);
        // $to_3_fm = date_format($to_3_date, "Y-m-d");

        $total_month = Harmony_SMS_alerts::whereBetween('date', [$from_3, $to_3])->WhereNull('transfer_remark')->where('split_status', 0)
            ->orWhereBetween(DB::raw('DATE(date)'), [date('Y-m-d', strtotime($date)), date('Y-m-d', strtotime($to_3))])->orderBy('date', 'asc')->sum('amount');
        $forcast = $total_month / date('j');

        return response()->json([
            'yesterday' => number_format($yesterday, 2, '.', ''),
            'today' => number_format($today, 2, '.', ''),
            'forcast' => number_format($forcast, 2, '.', ''),
        ]);
    }

    public function graphDateRangDetail($startdate, $enddate, $type, $account)
    {
        return view('sms-forward-harmony.graph_daterang_detail', compact('startdate', 'enddate', 'type', 'account'));
    }

    public function forward()
    {
        return view('sms-forward-harmony.sms-example');
    }

    public function store(Request $request)
    {
        ## Check Close Day
        $close_day = Harmony_SMS_alerts::checkCloseDay($request->date);
        // $close_day = 0;

        if ($close_day == 0 || Auth::user()->edit_close_day == 1) {
            if (isset($request->id)) {

                Harmony_SMS_alerts::where('id', $request->id)->update([
                    'date' => $request->date . " " . $request->time  ?? null,
                    'date_into' => $request->date_transfer ?? null,
                    'transfer_from' => $request->transfer_from ?? 0,
                    'into_account' => $request->into_account == "076355900016902" ? "708-2-26791-3" : $request->into_account,
                    'amount' => $request->amount ?? null,
                    'into_qr' => $request->into_account == "076355900016902" ? "708-2-26791-3" : null,
                    'status' => $request->status == 0 ? 0 : $request->status,
                    'transfer_status' => !empty($request->date_transfer) ? 1 : 0,
                    'split_status' => 0,
                    'remark' => Auth::user()->name,
                    'updated_by' => Auth::user()->id
                ]);

                if (isset($request->transfer_account)) {
                    Harmony_SMS_alerts::where('id', $request->id)->update([
                        'transfer_form_account' => $request->transfer_from == 14 ? "xxx-x-xxx-" . $request->transfer_account . "-x" : "xxx-x-x" . $request->transfer_account. "-x",
                    ]);
                }
    
                return response()->json([
                    'status' => 200,
                ]);
            } else {
    
                Harmony_SMS_alerts::create([
                    'date' => $request->date . " " . $request->time  ?? null,
                    'date_into' => $request->date_transfer ?? null,
                    'transfer_from' => $request->transfer_from ?? 0,
                    'transfer_form_account' => $request->transfer_from == 14 ? "xxx-x-xxx-" . $request->transfer_account . "-x" : "xxx-x-x" . $request->transfer_account. "-x",
                    'into_account' => $request->into_account == "076355900016902" ? "708-2-26791-3" : $request->into_account,
                    'amount' => $request->amount ?? null,
                    'into_qr' => $request->into_account == "076355900016902" ? "708-2-26791-3" : null,
                    'booking_id' => $request->status == 5 ? $request->booking_id : NULL,
                    'status' => $request->status == 0 ? 0 : $request->status,
                    'transfer_status' => !empty($request->date_transfer) ? 1 : 0,
                    'split_status' => 0,
                    'remark' => Auth::user()->name,
                    'created_by' => Auth::user()->id
                ]);
    
                return response()->json([
                    'status' => 200,
                ]);
            }
        } else {
            return response()->json([
                'status' => 403,
            ]);
        }
    }

    public function change_status($id, $status)
    {
        ## Check Close Day
        $check_date = Harmony_SMS_alerts::where('id', $id)->select('date', 'date_into')->first();

        if ($check_date->date_into == '') {
            $f_date = $check_date->date;
        } else {
            $f_date = $check_date->date_into;
        }

        // $close_day = Harmony_SMS_alerts::checkCloseDay($f_date);
        // ## End Check Close Day

        if ($check_date->close_day == 0 || Auth::user()->edit_close_day == 1) {
            if ($status == "No Category") {
                Harmony_SMS_alerts::where('id', $id)->update([
                    'status' => 0,
                ]);
            } elseif ($status == "Guest Deposit Revenue") {
                Harmony_SMS_alerts::where('id', $id)->update([
                    'status' => 1,
                ]);
            } elseif ($status == "All Outlet Revenue") {
                Harmony_SMS_alerts::where('id', $id)->update([
                    'status' => 2,
                ]);
            } elseif ($status == "Water Park Revenue") {
                Harmony_SMS_alerts::where('id', $id)->update([
                    'status' => 3,
                ]);
            } elseif ($status == "Credit Card Revenue") {
                Harmony_SMS_alerts::where('id', $id)->update([
                    'status' => 4,
                ]);
            } elseif ($status == "Credit Agoda Revenue") {
                Harmony_SMS_alerts::where('id', $id)->update([
                    'status' => 5,
                ]);
            } elseif ($status == "Front Desk Revenue") {
                Harmony_SMS_alerts::where('id', $id)->update([
                    'status' => 6,
                ]);
            } elseif ($status == "Credit Water Park Revenue") {
                Harmony_SMS_alerts::where('id', $id)->update([
                    'status' => 7,
                ]);
            } elseif ($status == "Elexa EGAT Revenue") {
                Harmony_SMS_alerts::where('id', $id)->update([
                    'status' => 8,
                ]);
            }
    
            return response()->json([
                'status' => 200,
            ]);

        } else {
            return response()->json([
                'status' => 403,
                'message' => date('d/m/Y', strtotime(date($f_date))),
            ]);
        }
    }

    public function update_time($id, $time)
    {
        ## Check Close Day
        $check_date = Harmony_SMS_alerts::where('id', $id)->select('id', 'date', 'date_into')->first();

        if ($check_date->date_into == '') {
            $f_date = $check_date->date;
        } else {
            $f_date = $check_date->date_into;
        }

        // $close_day = Harmony_SMS_alerts::checkCloseDay($f_date);
        ## End Check Close Day

        if ($check_date->close_day == 0 || Auth::user()->edit_close_day == 1) {
            Harmony_SMS_alerts::where('id', $id)->update([
                'date' => Carbon::parse($check_date->date)->format('Y-m-d').' '.Carbon::parse($time)->format('H:i:s'),
            ]);

            return response()->json([
                'status' => 200,
            ]);

        } else {
            return response()->json([
                'status' => 403,
                'message' => date('d/m/Y', strtotime(date($f_date))),
            ]);
        }
    }

    public function update_split(Request $request)
    {
        ## Check Close Day
        $check_date = Harmony_SMS_alerts::where('id', $request->splitID)->select('id', 'date', 'date_into')->first();

        if ($check_date->date_into == '') {
            $f_date = $check_date->date;
        } else {
            $f_date = $check_date->date_into;
        }

        // $close_day = Harmony_SMS_alerts::checkCloseDay($f_date);
        ## End Check Close Day

        if ($check_date->close_day == 0 || Auth::user()->edit_close_day == 1) {
            $data = Harmony_SMS_alerts::find($request->splitID);
            $time = Carbon::parse($data->date)->format('H:i:s');
            foreach ($request->date_split as $key => $value) {
                Harmony_SMS_alerts::create([
                    'split_ref_id' => $data->id,
                    'date' => $data->date,
                    'date_into' => date($value . ' ' . $time),
                    'transfer_from' => $data->transfer_from,
                    'into_account' => $data->into_account,
                    'amount' => $request->amount_split[$key],
                    'sequence' => $key + 2,
                    'split_status' => 1,
                    'remark' => Auth::user()->name,
                    'status' => $data->status
                ]);

                Harmony_SMS_alerts::where('id', $request->splitID)->update([
                    'amount' => 0,
                    'amount_before_split' => $data->amount,
                    'sequence' => 1,
                    'split_status' => 3
                ]);
            }

            return response()->json([
                'status' => 200,
            ]);

        } else {
            return response()->json([
                'status' => 403,
                'message' => date('d/m/Y', strtotime(date($f_date))),
            ]);
        }
    }

    public function receive_payment($id)
    {
        Harmony_SMS_alerts::where('id', $id)->update([
            'agoda_status' => 1,
        ]);

        return back();
    }

    public function get_other_revenue($id)
    {
        $data = Harmony_SMS_alerts::where('status', 9)->where('id', $id)->select('other_remark')->first();

        return response()->json([
            'data' => $data,
        ]);
    }

    public function other_revenue(Request $request)
    {
        ## Check Close Day
        $check_date = Harmony_SMS_alerts::where('id', $request->dataID)->select('date', 'date_into')->first();

        if ($check_date->date_into == '') {
            $f_date = $check_date->date;
        } else {
            $f_date = $check_date->date_into;
        }

        // $close_day = Harmony_SMS_alerts::checkCloseDay($f_date);
        ## End Check Close Day

        if ($check_date->close_day == 0 || Auth::user()->edit_close_day == 1) {
            try {
                Harmony_SMS_alerts::where('id', $request->dataID)->update([
                    'other_remark' => $request->other_revenue_remark,
                    'status' => 9
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => 500,
                ]);
            }
    
            return response()->json([
                'status' => 200,
            ]);

        } else {
            return response()->json([
                'status' => 403,
                'message' => date('d/m/Y', strtotime(date($f_date))),
            ]);
        }
    }

    public function transfer(Request $request)
    {
        ## Check Close Day
        $check_date = Harmony_SMS_alerts::where('id', $request->dataID)->select('date', 'date_into')->first();

        if (!empty($check_date) && $check_date->date_into == '') {
            $f_date = $check_date->date;
        } else {
            $f_date = $check_date->date_into;
        }

        // $close_day = Harmony_SMS_alerts::checkCloseDay($f_date);
        ## End Check Close Day

        if ($check_date->close_day == 0 || Auth::user()->edit_close_day == 1) {
            Harmony_SMS_alerts::where('id', $request->dataID)->update([
                'date_into' => date($request->date_transfer . ' 21:59:59'),
                'transfer_remark' => $request->transfer_remark,
                'transfer_status' => 1
            ]);

            return response()->json([
                'status' => 200,
            ]);

        } else {
            return response()->json([
                'status' => 403,
                'message' => date('d/m/Y', strtotime(date($f_date))),
            ]);
        }
    }

    public function edit($id)
    {
        $data = Harmony_SMS_alerts::find($id);

        return response()->json([
            'data' => $data,
        ]);
    }

    public function checkDateRange(Request $request)
    {
        // รับค่าจากฟอร์มหรือ API
        $input = $request->date; // เช่น "March - May 2024", "2024-03-01 - 2024-03-31", หรือ "2024"

        // Regular Expressions
        $monthRegex = '/(January|February|March|April|May|June|July|August|September|October|November|December)/i';
        $dateRegex = '/\d{2}[-\/]\d{2}[-\/]\d{4}(\s*-\s*\d{2}[-\/]\d{2}[-\/]\d{4})?/'; // รองรับ DD/MM/YYYY หรือ YYYY-MM-DD และช่วงวันที่
        $yearRegex = '/\b\d{4}\b/'; // ตรวจจับปี เช่น "2024"

        // ตรวจสอบรูปแบบ
        if (preg_match($dateRegex, $input)) {
            // หากมีช่วงวันที่ เช่น "08/10/2024 - 10/10/2024"
            if (strpos($input, ' - ') !== false) {
                return 'date';
            }
            return 'date';
        } elseif (preg_match_all($monthRegex, $input, $matches)) {
            if (preg_match($yearRegex, $input)) {
                return 'month'; // จับคู่เดือนและปี
            }
        } elseif (preg_match($yearRegex, $input)) {
            // หากมีแค่ปี เช่น "2024"
            return 'year';
        } else {
            return 'ไม่สามารถระบุรูปแบบได้';
        }
    }

    public function search_calendar(Request $request)
    {
        // dd($request);
        if ($request->revenue_type == '') {
            return $this->search_filter_date($request);
        } else {
            if ($request->revenue_type == "agoda_detail") {
                return $this->agoda_detail($request);
            } else {
                return $this->detail($request);
            }
        }
    }

    public function search_filter_date(Request $request)
    {
        if ($request->filter_by != "yesterday" && $request->filter_by != "tomorrow" && $request->filter_by != "week" && $request->filter_by != "thisMonth" && $request->filter_by != "thisYear") {
            $checkDateRange = $this->checkDateRange($request);
        } else {
            $checkDateRange = $request->filter_by;
        }

        if ($checkDateRange == "date") {
            $exp_date = array_map('trim', explode('-', $request->date));
            $FormatDate = Carbon::createFromFormat('d/m/Y', $exp_date[0]);
            $FormatDate2 = Carbon::createFromFormat('d/m/Y', $exp_date[1]);

            // Format Y-m-d
            $FromFormatDate = $FormatDate->format('Y-m-d');
            $ToFormatDate = $FormatDate2->format('Y-m-d');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime(date($FromFormatDate))));
            $smsToDate = date('Y-m-d 20:59:59', strtotime($ToFormatDate));

            // เช็ค Month, Year ถ้าเป็นเดือนเดียวกันให้สร้าง Format
            if ($FormatDate->format('m') == $FormatDate2->format('m')) {
                $FromMonth = $FormatDate->startOfMonth()->format('Y-m-d');
                $ToMonth = $FormatDate2->format('Y-m-d');

                $FromYear = $FormatDate->format('Y-01-01');
                $ToYear = $FormatDate2->format('Y-m-d');
            } else {
                $FromMonth = null;
                $ToMonth = null;

                $FromYear = null;
                $ToYear = null;
            }

            $filter_by = "date";

        } elseif ($checkDateRange == "yesterday") {
            $FormatDate = Carbon::now()->subDays(1);
            $FormatDate2 = Carbon::now()->subDays(1);

            // Format Y-m-d
            $FromFormatDate = $FormatDate->format('Y-m-d');
            $ToFormatDate = $FormatDate2->format('Y-m-d');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime(date($FromFormatDate))));
            $smsToDate = date('Y-m-d 20:59:59', strtotime(date($ToFormatDate)));

            // เช็ค Month, Year ถ้าเป็นเดือนเดียวกันให้สร้าง Format
            if ($FormatDate->format('m') == $FormatDate2->format('m')) {
                $FromMonth = $FormatDate->startOfMonth()->format('Y-m-d');
                $ToMonth = $FormatDate2->format('Y-m-d');

                $FromYear = $FormatDate->format('Y-01-01');
                $ToYear = $FormatDate2->format('Y-m-d');
            } else {
                $FromMonth = null;
                $ToMonth = null;

                $FromYear = null;
                $ToYear = null;
            }

            $filter_by = "yesterday";

        } elseif ($checkDateRange == "tomorrow") {
            $FormatDate = Carbon::now();
            $FormatDate2 = Carbon::now()->addDay(1);

            // Format Y-m-d
            $FromFormatDate = $FormatDate->format('Y-m-d');
            $ToFormatDate = $FormatDate2->format('Y-m-d');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime(date($FromFormatDate)));
            $smsToDate = date('Y-m-d 20:59:59', strtotime('+1 day', strtotime(date($ToFormatDate))));

            // เช็ค Month, Year ถ้าเป็นเดือนเดียวกันให้สร้าง Format
            if ($FormatDate->format('m') == $FormatDate2->format('m')) {
                $FromMonth = $FormatDate->startOfMonth()->format('Y-m-d');
                $ToMonth = $FormatDate2->format('Y-m-d');

                $FromYear = $FormatDate->format('Y-01-01');
                $ToYear = $FormatDate2->format('Y-m-d');
            } else {
                $FromMonth = null;
                $ToMonth = null;

                $FromYear = null;
                $ToYear = null;
            }

            $filter_by = "tomorrow";

        } elseif ($checkDateRange == "month") {
            if (strpos($request->date, ' - ') !== false) { // กรณีเป็นช่วงเดือน เช่น "March - May 2024"
                $exp_date = array_map('trim', explode('-', $request->date));
                $startMonth = $exp_date[0];
                [$endMonth, $year] = explode(' ', $exp_date[1]); // แยกปีจาก endMonthYear

            } else { // กรณีเป็นเดือนเดียว เช่น "May 2024"
                [$month, $year] = explode(' ', $request->date);
                $startMonth = $month;
                $endMonth = $month;
            }

            // แปลงชื่อเดือนเป็นหมายเลขเดือน
            $startMonthNumber = Carbon::parse($startMonth . ' 1')->format('m'); // "03" สำหรับ March
            $endMonthNumber = Carbon::parse($endMonth . ' 1')->format('m'); // "05" สำหรับ May

            $FormatDate = Carbon::createFromFormat('Y-m', $year . '-' . $startMonthNumber); // 2024-03
            $FormatDate2 = Carbon::createFromFormat('Y-m', $year . '-' . $endMonthNumber); // 2024-05

            $FromFormatDate = $FormatDate->startOfMonth()->format('Y-m-d');
            $ToFormatDate = $FormatDate2->endOfMonth()->format('Y-m-d');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime(date($FromFormatDate))));
            $smsToDate = date('Y-m-d 20:59:59', strtotime($ToFormatDate));

            $FromMonth = $FormatDate->startOfMonth()->format('Y-m-d');
            $ToMonth = $FormatDate2->endOfMonth()->format('Y-m-d');

            $FromYear = $FormatDate->format('Y-01-01');
            $ToYear = $FormatDate2->endOfMonth()->format('Y-m-d');

            $filter_by = "month";

        } elseif ($checkDateRange == "year") {
            $FormatDate = Carbon::createFromFormat('Y', $request->date);
            $FormatDate2 = Carbon::createFromFormat('Y', $request->date);

            // Format Y-m-d
            $FromFormatDate = $FormatDate->format('Y-01-01');
            $ToFormatDate = $FormatDate2->format('Y-12-31');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime(date($FromFormatDate))));
            $smsToDate = date('Y-m-d 20:59:59', strtotime($ToFormatDate));

            $FromMonth = $FormatDate->format('Y-01-01');
            $ToMonth = $FormatDate2->format('Y-12-31');

            $FromYear = $FormatDate->format('Y-01-01');
            $ToYear = $FormatDate2->endOfMonth()->format('Y-12-31');

            $filter_by = "year";

        } elseif ($request->filter_by == "week") {
            $FormatDate = Carbon::parse(date('Y-m-d', strtotime('last sunday', strtotime('next sunday'))));
            $FormatDate2 = Carbon::parse(date('Y-m-d', strtotime('+6 day', strtotime(date($FormatDate)))));

            $FromFormatDate = $FormatDate->format('Y-m-d');
            $ToFormatDate = $FormatDate2->format('Y-m-d');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime(date($FromFormatDate))));
            $smsToDate = date('Y-m-d 20:59:59', strtotime($ToFormatDate));

            $FromMonth = $FormatDate->format('Y-m-d');
            $ToMonth = $FormatDate2->format('Y-m-d');

            $FromYear = date('Y-01-01', strtotime($FormatDate));
            $ToYear = $FormatDate2->format('Y-m-d');

            $filter_by = "week";

        } elseif ($request->filter_by == "thisMonth") {
            $FormatDate = Carbon::now();
            $FormatDate2 = Carbon::now();

            // Format Y-m-d
            $FromFormatDate = $FormatDate->startOfMonth()->format('Y-m-d');
            $ToFormatDate = $FormatDate2->endOfMonth()->format('Y-m-d');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime(date($FromFormatDate))));
            $smsToDate = date('Y-m-d 20:59:59', strtotime($ToFormatDate));

            $FromMonth = $FormatDate->startOfMonth()->format('Y-m-d');
            $ToMonth = $FormatDate2->endOfMonth()->format('Y-m-d');

            $FromYear = $FormatDate->format('Y-01-01');
            $ToYear = $FormatDate2->endOfMonth()->format('Y-m-d');

            $filter_by = "thisMonth";

        } elseif ($request->filter_by == "thisYear") {
            $FormatDate = Carbon::now();
            $FormatDate2 = Carbon::now();

            // Format Y-m-d
            $FromFormatDate = $FormatDate->format('Y-01-01');
            $ToFormatDate = $FormatDate2->format('Y-12-31');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime(date($FromFormatDate))));
            $smsToDate = date('Y-m-d 20:59:59', strtotime($ToFormatDate));

            $FromMonth = $FormatDate->format('Y-01-01');
            $ToMonth = $FormatDate2->format('Y-12-31');

            $FromYear = $FormatDate->format('Y-01-01');
            $ToYear = $FormatDate2->endOfMonth()->format('Y-12-31');

            $filter_by = "thisYear";
        }

        // ตาราง 1
        $query_sms = Harmony_SMS_alerts::query()->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into');

            if ($request->into_account != '') { 
                $query_sms->where('into_account', $request->into_account);
            }
            if ($request->status != '') { 
                $query_sms->where('status', $request->status); 
            }

        $query_sms->orderBy('date', 'asc');

        $query_sms_amount = $query_sms;
        
        $data_sms = $query_sms->paginate(10);
        $query_sms_amount->select(DB::raw("SUM(amount) as amount, COUNT(id) as total_sms"));
        $total_sms_amount = $query_sms_amount->first();

        // ตาราง 2
        $query_transfer = Harmony_SMS_alerts::query();

            if ($request->into_account != '') { 
                if ($request->status != '') { 
                    $query_transfer->whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('transfer_status', 1)->where('into_account', $request->into_account)->where('status', $request->status);
                    $query_transfer->orWhere('status', 4)->where('split_status', 0)->whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('into_account', $request->into_account)->where('status', $request->status);
                } else {
                    $query_transfer->whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('transfer_status', 1)->where('into_account', $request->into_account);
                    $query_transfer->orWhere('status', 4)->where('split_status', 0)->whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('into_account', $request->into_account);
                }
            } else {
                if ($request->status != '') { 
                    $query_transfer->whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('transfer_status', 1)->where('status', $request->status);
                    $query_transfer->orWhere('status', 4)->where('split_status', 0)->whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', $request->status);
                } else {
                    $query_transfer->whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('transfer_status', 1);
                    $query_transfer->orWhere('status', 4)->where('split_status', 0)->whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate]);
                }
            }

        // $query_transfer->orWhereDate('date', '>=', $adate)->whereDate('date', '<=', $adate2)->where('status', 4)->where('split_status', 0);
        $query_transfer->orderBy('date', 'asc');

        $query_transfer_amount = $query_transfer;

        $data_sms_transfer = $query_transfer->paginate(10);
        $query_transfer_amount->select(DB::raw("SUM(amount) as amount, COUNT(id) as total_transfer"));
        $total_transfer_amount = $query_transfer_amount->first();

        // ตาราง 3
        $query_split = Harmony_SMS_alerts::query()->whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('split_status', 1);

            if ($request->into_account != '') { 
                $query_split->where('into_account', $request->into_account);
            }
            if ($request->status != '') { 
                $query_split->where('status', $request->status); 
            }

        $query_split->orderBy('date', 'asc');

        $query_split_amount = $query_split;
        
        $data_sms_split = $query_split->paginate(10);
        $query_split_amount->select(DB::raw("SUM(amount) as amount, COUNT(id) as total_split"));
        $total_split_amount = $query_split_amount->first();

        // Dashboard
        ## Total Today
        $query_day = Harmony_SMS_alerts::query();

            if ($request->into_account != '') { 
                if ($request->status != '') { 
                    $query_day->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('into_account', $request->into_account)->where('status', $request->status);
                    $query_day->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('into_account', $request->into_account)->where('status', $request->status);
                } else {
                    $query_day->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('into_account', $request->into_account);
                    $query_day->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('into_account', $request->into_account);
                }
            } else {
                if ($request->status != '') { 
                    $query_day->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('status', $request->status);
                    $query_day->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', $request->status);
                } else {
                    $query_day->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into');
                    $query_day->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate]);
                }
            }

        $total_day = $query_day->sum('amount');

        ## Total Front
        $query_front = Harmony_SMS_alerts::query();

            if ($request->into_account != '') { 
                if ($request->status != '') { 
                    $query_front->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('into_account', $request->into_account)->where('status', $request->status)->where('status', 6);
                    $query_front->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('into_account', $request->into_account)->where('status', $request->status)->where('status', 6);
                } else {
                    $query_front->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('into_account', $request->into_account)->where('status', 6);
                    $query_front->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('into_account', $request->into_account)->where('status', 6);
                }
            } else {
                if ($request->status != '') { 
                    $query_front->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('status', $request->status)->where('status', 6);
                    $query_front->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', $request->status)->where('status', 6);
                } else {
                    $query_front->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('status', 6);
                    $query_front->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 6);
                }
            }

        $total_front = $query_front->sum('amount');

        ## Total Room
        $query_room = Harmony_SMS_alerts::query();

            if ($request->into_account != '') { 
                if ($request->status != '') { 
                    $query_room->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('into_account', $request->into_account)->where('status', $request->status)->where('status', 1);
                    $query_room->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('into_account', $request->into_account)->where('status', $request->status)->where('status', 1);
                } else {
                    $query_room->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('into_account', $request->into_account)->where('status', 1);
                    $query_room->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('into_account', $request->into_account)->where('status', 1);
                }
            } else {
                if ($request->status != '') { 
                    $query_room->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('status', $request->status)->where('status', 1);
                    $query_room->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', $request->status)->where('status', 1);
                } else {
                    $query_room->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('status', 1);
                    $query_room->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 1);
                }
            }

        $total_room = $query_room->sum('amount');

        ## Total All Outlet
        $query_fb = Harmony_SMS_alerts::query();

            if ($request->into_account != '') { 
                if ($request->status != '') { 
                    $query_fb->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('into_account', $request->into_account)->where('status', $request->status)->where('status', 2);
                    $query_fb->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('into_account', $request->into_account)->where('status', $request->status)->where('status', 2);
                } else {
                    $query_fb->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('into_account', $request->into_account)->where('status', 2);
                    $query_fb->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('into_account', $request->into_account)->where('status', 2);
                }
            } else {
                if ($request->status != '') { 
                    $query_fb->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('status', $request->status)->where('status', 2);
                    $query_fb->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', $request->status)->where('status', 2);
                } else {
                    $query_fb->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('status', 2);
                    $query_fb->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 2);
                }
            }

        $total_fb = $query_fb->sum('amount');

        ## Total Credit Card
        $query_credit = Harmony_SMS_alerts::query();

            if ($request->status != '') { 
                $query_credit->whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $FromFormatDate])->where('into_account', "156-2-77492-1")->where('status', $request->status)->where('transfer_status', 1);
                $query_credit->orWhere('status', 4)->where('split_status', 0)->whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate]);
            } else {
                $query_credit->whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $FromFormatDate])->where('into_account', "156-2-77492-1")->where('transfer_status', 1)->where('status', 4);
                $query_credit->orWhere('status', 4)->where('split_status', 0)->whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate]);
            }

        $total_credit = $query_credit->sum('amount');
        
        ## Agoda
        $query_agoda = Harmony_SMS_alerts::query();

            if ($request->into_account != '') { 
                if ($request->status != '') { 
                    $query_agoda->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('into_account', $request->into_account)->where('status', $request->status)->where('status', 5);
                    $query_agoda->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('into_account', $request->into_account)->where('status', $request->status)->where('status', 5);
                } else {
                    $query_agoda->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('into_account', $request->into_account)->where('status', 5);
                    $query_agoda->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('into_account', $request->into_account)->where('status', 5);
                }
            } else {
                if ($request->status != '') { 
                    $query_agoda->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('status', $request->status)->where('status', 5);
                    $query_agoda->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', $request->status)->where('status', 5);
                } else {
                    $query_agoda->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('status', 5);
                    $query_agoda->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 5);
                }
            }

        $total_agoda = $query_agoda->sum('amount');

        ## Water Park
        $query_wp = Harmony_SMS_alerts::query();

            if ($request->into_account != '') { 
                if ($request->status != '') { 
                    $query_wp->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('into_account', $request->into_account)->where('status', $request->status)->where('status', 3);
                    $query_wp->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('into_account', $request->into_account)->where('status', $request->status)->where('status', 3);
                } else {
                    $query_wp->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('into_account', $request->into_account)->where('status', 3);
                    $query_wp->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('into_account', $request->into_account)->where('status', 3);
                }
            } else {
                if ($request->status != '') { 
                    $query_wp->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('status', $request->status)->where('status', 3);
                    $query_wp->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', $request->status)->where('status', 3);
                } else {
                    $query_wp->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('status', 3);
                    $query_wp->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 3);
                }
            }

        $total_wp = $query_wp->sum('amount');

        ## Credit Card Water Park
        $query_wp_credit = Harmony_SMS_alerts::query();

            if ($request->status != '') { 
                $query_wp_credit->whereBetween('date', [$smsFromDate, $smsToDate])->where('into_account', "156-2-77492-1")->whereNull('date_into')->where('status', $request->status)->where('status', 7);
                $query_wp_credit->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('into_account', "156-2-77492-1")->where('status', $request->status)->where('status', 7);
            } else {
                $query_wp_credit->whereBetween('date', [$smsFromDate, $smsToDate])->where('into_account', "156-2-77492-1")->whereNull('date_into')->where('status', 7);
                $query_wp_credit->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('into_account', "156-2-77492-1")->where('status', 7);
            }

        $total_wp_credit = $query_wp_credit->sum('amount');

        ## Other Revenue
        $query_other = Harmony_SMS_alerts::query();

            if ($request->into_account != '') { 
                if ($request->status != '') { 
                    $query_other->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('into_account', $request->into_account)->where('status', $request->status)->where('status', 9);
                    $query_other->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('into_account', $request->into_account)->where('status', $request->status)->where('status', 9);
                } else {
                    $query_other->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('into_account', $request->into_account)->where('status', 9);
                    $query_other->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('into_account', $request->into_account)->where('status', 9);
                }
            } else {
                if ($request->status != '') { 
                    $query_other->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('status', $request->status)->where('status', 9);
                    $query_other->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', $request->status)->where('status', 9);
                } else {
                    $query_other->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('status', 9);
                    $query_other->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 9);
                }
            }

        $total_other = $query_other->sum('amount');

        ## Elexa EGAT
        $query_ev = Harmony_SMS_alerts::query();

            if ($request->into_account != '') { 
                if ($request->status != '') { 
                    $query_ev->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('into_account', $request->into_account)->where('status', $request->status)->where('status', 8);
                    $query_ev->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('into_account', $request->into_account)->where('status', $request->status)->where('status', 8);
                } else {
                    $query_ev->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('into_account', $request->into_account)->where('status', 8);
                    $query_ev->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('into_account', $request->into_account)->where('status', 8);
                }
            } else {
                if ($request->status != '') { 
                    $query_ev->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('status', $request->status)->where('status', 8);
                    $query_ev->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', $request->status)->where('status', 8);
                } else {
                    $query_ev->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('status', 8);
                    $query_ev->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 8);
                }
            }

        $total_ev = $query_ev->sum('amount');

        ## Transfer Revenue
        $query_transfer_revenue = Harmony_SMS_alerts::query();

            if ($request->into_account != '') { 
                if ($request->status != '') { 
                    $query_transfer_revenue->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('into_account', $request->into_account)->where('status', $request->status)->where('transfer_status', 1);
                    $query_transfer_revenue->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('into_account', $request->into_account)->where('status', $request->status)->where('transfer_status', 1);
                } else {
                    $query_transfer_revenue->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('into_account', $request->into_account)->where('transfer_status', 1);
                    $query_transfer_revenue->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('into_account', $request->into_account)->where('transfer_status', 1);
                }
            } else {
                if ($request->status != '') { 
                    $query_transfer_revenue->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('status', $request->status)->where('transfer_status', 1);
                    $query_transfer_revenue->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', $request->status)->where('transfer_status', 1);
                } else {
                    $query_transfer_revenue->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('transfer_status', 1);
                    $query_transfer_revenue->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('transfer_status', 1);
                }
            }

        $total_transfer = $query_transfer_revenue->sum('amount');

        ## Credit Transaction
        $query_credit_transaction = Harmony_SMS_alerts::query()->whereBetween('date', [$smsFromDate, $smsToDate])->where('into_account', "156-2-77492-1")->where('status', 4);
        $total_credit_transaction = $query_credit_transaction->count();

        ## Transfer Revenue2
        $query_transfer_revenue2 = Harmony_SMS_alerts::query()->whereBetween('date', [$smsFromDate, $smsToDate]);

            if ($request->into_account != '') { 
                if ($request->status != '') { 
                    $query_transfer_revenue2->where('transfer_status', 1)->where('into_account', $request->into_account)->where('status', $request->status);
                } else {
                    $query_transfer_revenue2->where('transfer_status', 1)->where('into_account', $request->into_account);
                }
            } else {
                if ($request->status != '') { 
                    $query_transfer_revenue2->where('transfer_status', 1)->where('status', $request->status);
                } else {
                    $query_transfer_revenue2->where('transfer_status', 1);
                }
            }
        $total_transfer2 = $query_transfer_revenue2->count();

        ## Split Revenue
        $query_split_revenue = Harmony_SMS_alerts::query()->WhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('split_status', 1);

            if ($request->into_account != '') { 
                if ($request->status != '') { 
                    $query_split_revenue->where('into_account', $request->into_account)->where('status', $request->status);
                } else {
                    $query_split_revenue->where('into_account', $request->into_account);
                }
            } else {
                if ($request->status != '') { 
                    $query_split_revenue->where('status', $request->status);
                } 
            }

        $total_split = $query_split_revenue->sum('amount');

        ## Split Transaction
        $query_split_transaction = Harmony_SMS_alerts::query()->whereBetween('date', [$smsFromDate, $smsToDate])->where('split_status', 1);

            if ($request->into_account != '') { 
                $query_split_transaction->where('into_account', $request->into_account);
            }
            if ($request->status != '') { 
                $query_split_transaction->where('status', $request->status); 
            }

        $query_split_transaction->select(DB::raw("SUM(amount) as amount, COUNT(id) as transfer_transaction"));
        $total_split_transaction = $query_split_transaction->first();

        // dd([$FromFormatDate, $ToFormatDate]);

        ## No Income Type
        $query_not_type = Harmony_SMS_alerts::query();

        $query_not_type->where(function ($query) use ($smsFromDate, $smsToDate, $FromFormatDate, $ToFormatDate) {
                $query->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('status', 0)
                ->orWhereDate('date_into', [$FromFormatDate, $ToFormatDate])->where('status', 0);
            });

            if ($request->into_account != '') { 
                $query_not_type->where('into_account', $request->into_account);
            }
            if ($request->status != '') { 
                $query_not_type->where('status', $request->status); 
            }

        $total_not_type = $query_not_type->count();

        ## No Income Type Revenue
        $query_not_type_revenue = Harmony_SMS_alerts::query();

        $query_not_type_revenue->where(function ($query) use ($smsFromDate, $smsToDate, $FromFormatDate, $ToFormatDate) {
                $query->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('status', 0)
                ->orWhereDate('date_into', [$FromFormatDate, $ToFormatDate])->where('status', 0);
            });

            if ($request->into_account != '') { 
                if ($request->status != '') { 
                    $query_not_type_revenue->where('into_account', $request->into_account)->where('status', $request->status);
                } else {
                    $query_not_type_revenue->where('into_account', $request->into_account);
                }
            } else {
                if ($request->status != '') { 
                    $query_not_type_revenue->where('status', $request->status);
                } 
            }

        $total_not_type_revenue = $query_not_type_revenue->sum('amount');

        ## Total Transaction
        $query_transaction = Harmony_SMS_alerts::query();

            if ($request->into_account != '') { 
                if ($request->status != '') { 
                    $query_transaction->whereBetween('date', [$smsFromDate, $smsToDate])->where('into_account', $request->into_account)->where('status', $request->status)->whereNull('date_into');
                    $query_transaction->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('into_account', $request->into_account)->where('status', $request->status);
                } else {
                    $query_transaction->whereBetween('date', [$smsFromDate, $smsToDate])->where('into_account', $request->into_account)->whereNull('date_into');
                    $query_transaction->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('into_account', $request->into_account);
                }
            } else {
                if ($request->status != '') { 
                    $query_transaction->whereBetween('date', [$smsFromDate, $smsToDate])->where('status', $request->status)->whereNull('date_into');
                    $query_transaction->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', $request->status);
                } else {
                    $query_transaction->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into');
                    $query_transaction->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate]);
                }
            }
        
        $total_transaction = $query_transaction->get();
        // End Dashboard

        $data_bank = Masters::where('category', "bank")->where('status', 1)->select('id', 'name_th', 'name_en')->get();

        $search_date = $request->date;

        $status = $request->status;
        $into_account = $request->into_account;
        $bank_note = $request->bank_note;

        return view('sms-forward-harmony.index', compact(
            'data_sms',
            'total_sms_amount',
            'data_sms_transfer',
            'total_transfer_amount',
            'data_sms_split',
            'total_split_amount',
            'total_day',
            'total_room',
            'total_fb',
            'total_wp',
            'total_credit',
            'total_credit_transaction',
            'total_transfer',
            'total_not_type',
            'total_not_type_revenue',
            'total_transaction',
            'total_transfer2',
            'total_agoda',
            'total_front',
            'total_wp_credit',
            'total_split',
            'total_split_transaction',
            'total_other',
            'total_ev',
            'filter_by',
            'search_date',
            'bank_note',
            'data_bank', 'status'
        ));
    }

    public function delete($id)
    {
        ## Check Close Day
        $check_date = Harmony_SMS_alerts::where('id', $id)->select('date', 'date_into')->first();

        if ($check_date->date_into == '') {
            $f_date = $check_date->date;
        } else {
            $f_date = $check_date->date_into;
        }

        // $close_day = Harmony_SMS_alerts::checkCloseDay($f_date);
        ## End Check Close Day

        if ($check_date->close_day == 0 || Auth::user()->edit_close_day == 1) {
            Harmony_SMS_alerts::where('id', $id)->delete();

            return response()->json([
                'status' => 200,
            ]);

        } else {
            return response()->json([
                'status' => 403,
                'message' => date('d/m/Y', strtotime(date($f_date))),
            ]);
        }

        return redirect(route('sms-alert'));
    }

    public function detail(Request $request)
    {
        if ($request->filter_by != "yesterday" && $request->filter_by != "tomorrow" && $request->filter_by != "week" && $request->filter_by != "thisMonth" && $request->filter_by != "thisYear") {
            $checkDateRange = $this->checkDateRange($request);
        } else {
            $checkDateRange = $request->filter_by;
        }

        if ($checkDateRange == "date") {
            $exp_date = array_map('trim', explode('-', $request->date));
            $FormatDate = Carbon::createFromFormat('d/m/Y', $exp_date[0]);
            $FormatDate2 = Carbon::createFromFormat('d/m/Y', $exp_date[1]);

            // Format Y-m-d
            $FromFormatDate = $FormatDate->format('Y-m-d');
            $ToFormatDate = $FormatDate2->format('Y-m-d');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime(date($FromFormatDate))));
            $smsToDate = date('Y-m-d 20:59:59', strtotime($ToFormatDate));

            $filter_by = "date";

        } elseif ($checkDateRange == "yesterday") {
            $FormatDate = Carbon::now()->subDays(1);
            $FormatDate2 = Carbon::now()->subDays(1);

            // Format Y-m-d
            $FromFormatDate = $FormatDate->format('Y-m-d');
            $ToFormatDate = $FormatDate2->format('Y-m-d');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime(date($FromFormatDate))));
            $smsToDate = date('Y-m-d 20:59:59', strtotime(date($ToFormatDate)));

            $filter_by = "yesterday";

        } elseif ($checkDateRange == "tomorrow") {
            $FormatDate = Carbon::now()->addDay(1);
            $FormatDate2 = Carbon::now()->addDay(1);

            // Format Y-m-d
            $FromFormatDate = $FormatDate->format('Y-m-d');
            $ToFormatDate = $FormatDate2->format('Y-m-d');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime(date($FromFormatDate)));
            $smsToDate = date('Y-m-d 20:59:59', strtotime('+1 day', strtotime(date($ToFormatDate))));

            $filter_by = "tomorrow";

        } elseif ($checkDateRange == "month") {
            if (strpos($request->date, ' - ') !== false) { // กรณีเป็นช่วงเดือน เช่น "March - May 2024"
                $exp_date = array_map('trim', explode('-', $request->date));
                $startMonth = $exp_date[0];
                [$endMonth, $year] = explode(' ', $exp_date[1]); // แยกปีจาก endMonthYear

            } else { // กรณีเป็นเดือนเดียว เช่น "May 2024"
                [$month, $year] = explode(' ', $request->date);
                $startMonth = $month;
                $endMonth = $month;
            }

            // แปลงชื่อเดือนเป็นหมายเลขเดือน
            $startMonthNumber = Carbon::parse($startMonth . ' 1')->format('m'); // "03" สำหรับ March
            $endMonthNumber = Carbon::parse($endMonth . ' 1')->format('m'); // "05" สำหรับ May

            $FormatDate = Carbon::createFromFormat('Y-m', $year . '-' . $startMonthNumber); // 2024-03
            $FormatDate2 = Carbon::createFromFormat('Y-m', $year . '-' . $endMonthNumber); // 2024-05

            $FromFormatDate = $FormatDate->startOfMonth()->format('Y-m-d');
            $ToFormatDate = $FormatDate2->endOfMonth()->format('Y-m-d');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime(date($FromFormatDate))));
            $smsToDate = date('Y-m-d 20:59:59', strtotime($ToFormatDate));

            $filter_by = "month";

        } elseif ($checkDateRange == "year") {
            $FormatDate = Carbon::createFromFormat('Y', $request->date);
            $FormatDate2 = Carbon::createFromFormat('Y', $request->date);

            // Format Y-m-d
            $FromFormatDate = $FormatDate->format('Y-01-01');
            $ToFormatDate = $FormatDate2->format('Y-12-31');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime(date($FromFormatDate))));
            $smsToDate = date('Y-m-d 20:59:59', strtotime($ToFormatDate));

            $filter_by = "year";

        } elseif ($request->filter_by == "week") {
            $FormatDate = Carbon::parse(date('Y-m-d', strtotime('last sunday', strtotime('next sunday'))));
            $FormatDate2 = Carbon::parse(date('Y-m-d', strtotime('+6 day', strtotime(date($FormatDate)))));

            $FromFormatDate = $FormatDate->format('Y-m-d');
            $ToFormatDate = $FormatDate2->format('Y-m-d');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime(date($FromFormatDate))));
            $smsToDate = date('Y-m-d 20:59:59', strtotime($ToFormatDate));

            $filter_by = "week";

        } elseif ($request->filter_by == "thisMonth") {
            $FormatDate = Carbon::now();
            $FormatDate2 = Carbon::now();

            // Format Y-m-d
            $FromFormatDate = $FormatDate->startOfMonth()->format('Y-m-d');
            $ToFormatDate = $FormatDate2->endOfMonth()->format('Y-m-d');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime(date($FromFormatDate))));
            $smsToDate = date('Y-m-d 20:59:59', strtotime($ToFormatDate));

            $filter_by = "thisMonth";

        } elseif ($request->filter_by == "thisYear") {
            $FormatDate = Carbon::now();
            $FormatDate2 = Carbon::now();

            // Format Y-m-d
            $FromFormatDate = $FormatDate->format('Y-01-01');
            $ToFormatDate = $FormatDate2->format('Y-12-31');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime(date($FromFormatDate))));
            $smsToDate = date('Y-m-d 20:59:59', strtotime($ToFormatDate));

            $filter_by = "thisYear";
        }

        $title = "";

        $data_bank = Masters::where('category', "bank")->where('status', 1)->select('id', 'name_th', 'name_en')->get();

        if ($request->revenue_type == "front") {
            $data_sms = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 6)->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 6)->paginate(10);
            $total_sms = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 6)->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 6)->sum('amount');
            $title = "Front Desk Bank Transfer Revenue";
            $status = 6;

        } elseif ($request->revenue_type == "room") {
            $data_sms = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 1)->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 1)->paginate(10);
            $total_sms = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 1)->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 1)->sum('amount');
            $title = "Guest Deposit Bank Transfer Revenue";
            $status = 1;

        } elseif ($request->revenue_type == "all_outlet") {
            $data_sms = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 2)->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 2)->paginate(10);
            $total_sms = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 2)->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 2)->sum('amount');
            $title = "All Outlet Revenue";
            $status = 2;

        } elseif ($request->revenue_type == "credit") {
            $data_sms = Harmony_SMS_alerts::whereBetween(DB::raw('DATE(date_into)'), [$smsFromDate, $smsToDate])->where('into_account', "156-2-77492-1")->where('transfer_status', 1)->where('status', 4)
                ->orWhere('status', 4)->where('split_status', 0)->whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->orderBy('date', 'asc')->paginate(10);
            $total_sms = Harmony_SMS_alerts::whereBetween(DB::raw('DATE(date_into)'), [$smsFromDate, $smsToDate])->where('into_account', "156-2-77492-1")->where('transfer_status', 1)->where('status', 4)
                ->orWhere('status', 4)->where('split_status', 0)->whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->sum('amount');
            $title = "Credit Card Hotel Revenue";
            $status = 4;

        } elseif ($request->revenue_type == "credit_water") {
            $data_sms = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 7)->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 7)->paginate(10);
            $total_sms = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 7)->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 7)->sum('amount');
            $title = "Credit Card Water Park Revenue";
            $status = 7;

        } elseif ($request->revenue_type == "water") {
            $data_sms = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 3)->whereNull('date_into')
                ->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 3)->paginate(10);
            $total_sms = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 3)->whereNull('date_into')
                ->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 3)->sum('amount');
            $title = "Water Park Bank Transfer Revenue";
            $status = 3;

        } elseif ($request->revenue_type == "elexa_revenue") {
            $data_sms = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 8)->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 8)->paginate(10);
            $total_sms = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 8)->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 8)->sum('amount');
            $title = "Elexa EGAT Revenue";
            $status = 8;

        } elseif ($request->revenue_type == "other_revenue") {
            $data_sms = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 9)->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 9)->paginate(10);
            $total_sms = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 9)->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 9)->sum('amount');
            $title = "Other Bank Transfer Revenue";
            $status = 9;

        } elseif ($request->revenue_type == "transfer_revenue") {
            $data_sms = Harmony_SMS_alerts::whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('transfer_status', 1)->paginate(10);
            $total_sms = Harmony_SMS_alerts::whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('transfer_status', 1)->sum('amount');
            $title = "Transfer Revenue";
            $status = 'transfer_revenue';

        } elseif ($request->revenue_type == "split_revenue") {
            $data_sms = Harmony_SMS_alerts::whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('split_status', 1)->paginate(10);
            $total_sms = Harmony_SMS_alerts::whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('split_status', 1)->sum('amount');
            $title = "Split Credit Card Hotel Revenue";
            $status = 'split_revenue';

        } elseif ($request->revenue_type == "transfer_transaction") {
            $data_sms = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('transfer_status', 1)->orderBy('date', 'asc')->paginate(10);
            $total_sms = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('transfer_status', 1)->sum('amount');
            $title = "Transfer Transaction";
            $status = 'transfer_transaction';

        } elseif ($request->revenue_type == "credit_transaction") {
            $data_sms = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('into_account', "156-2-77492-1")->where('status', 4)->paginate(10);
            $total_sms = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('into_account', "156-2-77492-1")->where('status', 4)->sum('amount');
            $title = "Credit Card Hotel Transfer Transaction";
            $status = 'credit_card_hotel_transfer_transaction';

        } elseif ($request->revenue_type == "split_transaction") {
            $data_sms = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('split_status', 1)->paginate(10);
            $total_sms = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('split_status', 1)->sum('amount');
            $title = "Split Credit Card Hotel Transaction";
            $status = 'split_credit_card_hotel_transaction';

        } elseif ($request->revenue_type == "total_transaction") {
            $data_sms = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->orderBy('date', 'asc')->paginate(10);
            $total_sms = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->sum('amount');
            $title = "Total Transaction";
            $status = 'total_transaction';

        } elseif ($request->revenue_type == "status") {
            $data_sms = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 0)->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 0)->paginate(10);
            $total_sms = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 0)->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 0)->sum('amount');
            $title = "No Income Type";
            $status = '0';

        } elseif ($request->revenue_type == "no_income_revenue") {
            $data_sms = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 0)->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 0)->paginate(10);
            $total_sms = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 0)->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 0)->sum('amount');
            $title = "No Income Revenue";
            $status = 'no_income_revenue';
        }

        $search_date = $request->date;
        $into_account = $request->into_account;

        return view('sms-forward-harmony.detail', compact('data_sms', 'total_sms', 'data_bank', 'title', 'filter_by', 'search_date', 'status', 'into_account'));
    }

    public function agoda_detail(Request $request)
    {
        if ($request->filter_by != "yesterday" && $request->filter_by != "tomorrow" && $request->filter_by != "week" && $request->filter_by != "thisMonth" && $request->filter_by != "thisYear") {
            $checkDateRange = $this->checkDateRange($request);
        } else {
            $checkDateRange = $request->filter_by;
        }

        if ($checkDateRange == "date") {
            $exp_date = array_map('trim', explode('-', $request->date));
            $FormatDate = Carbon::createFromFormat('d/m/Y', $exp_date[0]);
            $FormatDate2 = Carbon::createFromFormat('d/m/Y', $exp_date[1]);

            // Format Y-m-d
            $FromFormatDate = $FormatDate->format('Y-m-d');
            $ToFormatDate = $FormatDate2->format('Y-m-d');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime(date($FromFormatDate))));
            $smsToDate = date('Y-m-d 20:59:59', strtotime($ToFormatDate));

            $filter_by = "date";

        } elseif ($checkDateRange == "yesterday") {
            $FormatDate = Carbon::now()->subDays(1);
            $FormatDate2 = Carbon::now()->subDays(1);

            // Format Y-m-d
            $FromFormatDate = $FormatDate->format('Y-m-d');
            $ToFormatDate = $FormatDate2->format('Y-m-d');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime(date($FromFormatDate))));
            $smsToDate = date('Y-m-d 20:59:59', strtotime(date($ToFormatDate)));

            $filter_by = "yesterday";

        } elseif ($checkDateRange == "tomorrow") {
            $FormatDate = Carbon::now()->addDay(1);
            $FormatDate2 = Carbon::now()->addDay(1);

            // Format Y-m-d
            $FromFormatDate = $FormatDate->format('Y-m-d');
            $ToFormatDate = $FormatDate2->format('Y-m-d');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime(date($FromFormatDate)));
            $smsToDate = date('Y-m-d 20:59:59', strtotime('+1 day', strtotime(date($ToFormatDate))));

            $filter_by = "tomorrow";

        } elseif ($checkDateRange == "month") {
            if (strpos($request->date, ' - ') !== false) { // กรณีเป็นช่วงเดือน เช่น "March - May 2024"
                $exp_date = array_map('trim', explode('-', $request->date));
                $startMonth = $exp_date[0];
                [$endMonth, $year] = explode(' ', $exp_date[1]); // แยกปีจาก endMonthYear

            } else { // กรณีเป็นเดือนเดียว เช่น "May 2024"
                [$month, $year] = explode(' ', $request->date);
                $startMonth = $month;
                $endMonth = $month;
            }

            // แปลงชื่อเดือนเป็นหมายเลขเดือน
            $startMonthNumber = Carbon::parse($startMonth . ' 1')->format('m'); // "03" สำหรับ March
            $endMonthNumber = Carbon::parse($endMonth . ' 1')->format('m'); // "05" สำหรับ May

            $FormatDate = Carbon::createFromFormat('Y-m', $year . '-' . $startMonthNumber); // 2024-03
            $FormatDate2 = Carbon::createFromFormat('Y-m', $year . '-' . $endMonthNumber); // 2024-05

            $FromFormatDate = $FormatDate->startOfMonth()->format('Y-m-d');
            $ToFormatDate = $FormatDate2->endOfMonth()->format('Y-m-d');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime(date($FromFormatDate))));
            $smsToDate = date('Y-m-d 20:59:59', strtotime($ToFormatDate));

            $filter_by = "month";

        } elseif ($checkDateRange == "year") {
            $FormatDate = Carbon::createFromFormat('Y', $request->date);
            $FormatDate2 = Carbon::createFromFormat('Y', $request->date);

            // Format Y-m-d
            $FromFormatDate = $FormatDate->format('Y-01-01');
            $ToFormatDate = $FormatDate2->format('Y-12-31');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime(date($FromFormatDate))));
            $smsToDate = date('Y-m-d 20:59:59', strtotime($ToFormatDate));

            $filter_by = "year";

        } elseif ($request->filter_by == "week") {
            $FormatDate = Carbon::parse(date('Y-m-d', strtotime('last sunday', strtotime('next sunday'))));
            $FormatDate2 = Carbon::parse(date('Y-m-d', strtotime('+6 day', strtotime(date($FormatDate)))));

            $FromFormatDate = $FormatDate->format('Y-m-d');
            $ToFormatDate = $FormatDate2->format('Y-m-d');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime(date($FromFormatDate))));
            $smsToDate = date('Y-m-d 20:59:59', strtotime($ToFormatDate));

            $filter_by = "week";

        } elseif ($request->filter_by == "thisMonth") {
            $FormatDate = Carbon::now();
            $FormatDate2 = Carbon::now();

            // Format Y-m-d
            $FromFormatDate = $FormatDate->startOfMonth()->format('Y-m-d');
            $ToFormatDate = $FormatDate2->endOfMonth()->format('Y-m-d');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime(date($FromFormatDate))));
            $smsToDate = date('Y-m-d 20:59:59', strtotime($ToFormatDate));

            $filter_by = "thisMonth";

        } elseif ($request->filter_by == "thisYear") {
            $FormatDate = Carbon::now();
            $FormatDate2 = Carbon::now();

            // Format Y-m-d
            $FromFormatDate = $FormatDate->format('Y-01-01');
            $ToFormatDate = $FormatDate2->format('Y-12-31');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime(date($FromFormatDate))));
            $smsToDate = date('Y-m-d 20:59:59', strtotime($ToFormatDate));

            $filter_by = "thisYear";
        }

        $data_sms = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 5)
            ->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])
            ->where('status', 5)->paginate(10);

        $data_bank = Masters::where('category', "bank")->where('status', 1)->select('id', 'name_th', 'name_en')->get();
        
        $title = "Agoda bank Transfer Revenue";

        $filter_by = $request->filter_by;
        $search_date = $request->date;
        $status = $request->status;
        $into_account = $request->into_account;

        return view('sms-forward-harmony.agoda_detail', compact('data_sms', 'title', 'filter_by', 'search_date', 'status', 'into_account', 'data_bank'));
    }
}
