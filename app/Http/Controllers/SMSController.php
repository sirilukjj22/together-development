<?php

namespace App\Http\Controllers;

use App\Models\Masters;
use App\Models\Revenues;
use App\Models\SMS_alerts;
use App\Models\SMS_forwards;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Cast\Double;

class SMSController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd(explode(" ", "เช็คเข้าบ/ช x267913 ยอด THB95,890.00 ผ่าน TELL"));
        $data_forward = SMS_forwards::where('is_status', 0)->get();

        foreach ($data_forward as $key => $value) {
            if (count($data_forward) > 0) {
                $exp_form = explode(" ", $value->messages);
                if (count($exp_form) == 11) {
                    SMS_alerts::create([
                        'date' => $value->created_at,
                        'transfer_from' => SMS_alerts::check_bank($exp_form[1]),
                        'into_account' => SMS_alerts::check_account($exp_form[6]),
                        'amount' => str_replace(",", "", substr($exp_form[4], 3)),
                        'remark' => "Auto",
                        'status' => 0
                    ]);

                    SMS_forwards::where('id', $value->id)->update([
                        'is_status' => 1
                    ]);
                } elseif (count($exp_form) == 12) {
                    SMS_alerts::create([
                        'date' => $value->created_at,
                        'transfer_from' => SMS_alerts::check_bank($exp_form[1]),
                        'into_account' => SMS_alerts::check_account($exp_form[7]),
                        'amount' => str_replace(",", "", substr($exp_form[5], 3)),
                        'remark' => "Auto",
                        'status' => 0
                    ]);

                    SMS_forwards::where('id', $value->id)->update([
                        'is_status' => 1
                    ]);
                } elseif (count($exp_form) == 10) {
                    SMS_alerts::create([
                        'date' => $value->created_at,
                        'transfer_from' => SMS_alerts::check_bank($exp_form[1]),
                        'into_account' => SMS_alerts::check_account($exp_form[5]),
                        'amount' => str_replace(",", "", substr($exp_form[3], 3)),
                        'remark' => "Auto",
                        'status' => 0
                    ]);

                    SMS_forwards::where('id', $value->id)->update([
                        'is_status' => 1
                    ]);
                } elseif (count($exp_form) == 6) {
                    if ($exp_form[0] == "เงินเข้าบ/ช") {
                        SMS_alerts::create([
                            'date' => $value->created_at,
                            'transfer_from' => SMS_alerts::check_bank($exp_form[5]),
                            'into_account' => SMS_alerts::check_account($exp_form[1]),
                            'amount' => str_replace(",", "", substr($exp_form[3], 3)),
                            'remark' => "Auto",
                            'status' => 0
                        ]);

                        SMS_forwards::where('id', $value->id)->update([
                            'is_status' => 1
                        ]);
                    } if ($exp_form[0] == "เช็คเข้าบ/ช") {
                        SMS_alerts::create([
                            'date' => $value->created_at,
                            'transfer_from' => 21, // เช็คธนาคาร
                            'into_account' => SMS_alerts::check_account($exp_form[1]),
                            'amount' => str_replace(",", "", substr($exp_form[3], 3)),
                            'remark' => "Auto",
                            'status' => 0
                        ]);

                        SMS_forwards::where('id', $value->id)->update([
                            'is_status' => 1
                        ]);
                    } else {
                        $data_qr = mb_substr($exp_form[4], 4);
                        $into = "none";

                        switch ($data_qr) {
                            case '076355900016901':
                                $into = "708-227357-4";
                                break;
                            case '076355900016902':
                                $into = "708-226791-3";
                                break;
                            case '076355900016911':
                                $into = "708-226792-1";
                                break;
                        }

                        SMS_alerts::create([
                            'date' => $value->created_at,
                            'transfer_from' => SMS_alerts::check_bank(mb_substr($exp_form[1], 5)),
                            'into_account' => $into,
                            'into_qr' => $data_qr,
                            'amount' => str_replace(",", "", $exp_form[0]),
                            'remark' => "Auto",
                            'status' => $into == "708-227357-4" ? 3 : 0
                        ]);

                        SMS_forwards::where('id', $value->id)->update([
                            'is_status' => 1
                        ]);
                    }
                } elseif (count($exp_form) == 8) {
                    SMS_alerts::create([
                        'date' => $value->created_at,
                        'transfer_from' => SMS_alerts::check_bank($exp_form[5]),
                        'into_account' => SMS_alerts::check_account($exp_form[1]),
                        'amount' => str_replace(",", "", substr($exp_form[3], 3)),
                        'remark' => "Auto",
                        'status' => 0
                    ]);

                    SMS_forwards::where('id', $value->id)->update([
                        'is_status' => 1
                    ]);
                } elseif (count($exp_form) == 9) {
                    SMS_alerts::create([
                        'date' => Carbon::parse($value->created_at)->format('Y-m-d H:i:s'),
                        'transfer_from' => SMS_alerts::check_bank("Credit"),
                        'into_account' => SMS_alerts::check_account($exp_form[3]),
                        'amount' => str_replace(",", "", substr($exp_form[1], 3)),
                        'remark' => "Auto",
                        'date_into' => Carbon::parse($value->created_at)->subDays(1)->format('Y-m-d H:i:s'),
                        'transfer_remark' => "ยอดเครดิต",
                        'status' => 4
                    ]);

                    SMS_forwards::where('id', $value->id)->update([
                        'is_status' => 1
                    ]);
                }
            }
        }

        $adate = date('Y-m-d 21:00:00');
        $from = date("Y-m-d 21:00:00", strtotime("-1 day", strtotime($adate)));
        $to = date('Y-m-d 21:00:00');

        $fromLast7 = date("Y-m-d 21:00:00", strtotime("-8 day", strtotime($adate)));

        $data_sms = SMS_alerts::whereBetween('date', [$from, $to])->whereNull('date_into')->orderBy('date', 'asc')->get();
        $data_sms_transfer = SMS_alerts::whereDate('date_into', date('Y-m-d'))->where('transfer_status', 1)
            ->orWhereDate('date', date('Y-m-d'))->where('transfer_status', 1)
            ->orWhere('status', 4)->where('split_status', 0)->whereDate('date_into', date('Y-m-d'))
            ->orWhereDate('date', date('Y-m-d'))->where('status', 4)->where('split_status', 0)
            ->orderBy('date', 'asc')->get();
        $data_sms_split = SMS_alerts::whereDate('date_into', date('Y-m-d'))->where('split_status', 1)->orderBy('date', 'asc')->get();
        $total_day = SMS_alerts::whereBetween('date', [$from, $to])->WhereNull('transfer_remark')->where('split_status', 0)->orWhereDate('date_into', date('Y-m-d'))->sum('amount');
        $total_room = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 1)->whereNull('date_into')
            ->orWhereDate('date_into', date('Y-m-d'))->where('status', 1)
            ->sum('amount');
        $total_fb = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 2)->whereNull('date_into')
            ->orWhereDate('date_into', date('Y-m-d'))->where('status', 2)
            ->sum('amount');
        $total_wp = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 3)->whereNull('date_into')
            ->orWhereDate('date_into', date('Y-m-d'))->where('status', 3)
            ->sum('amount');
        $total_credit = SMS_alerts::whereDate('date_into', [$from, $to])->where('into_account', "708-226792-1")
            ->where('status', 4)->sum('amount');
        $total_agoda = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 5)->whereNull('date_into')
            ->orWhereDate('date_into', date('Y-m-d'))->where('status', 5)
            ->sum('amount');
        $total_front = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 6)->whereNull('date_into')
            ->orWhereDate('date_into', date('Y-m-d'))->where('status', 6)
            ->sum('amount');
        $total_wp_credit = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 7)->whereNull('date_into')
            ->orWhereDate('date_into', date('Y-m-d'))->where('status', 7)
            ->sum('amount');
        $total_ev = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 8)->whereNull('date_into')
            ->orWhereDate('date_into', date('Y-m-d'))->where('status', 8)
            ->sum('amount');
        $total_credit = SMS_alerts::whereDate('date_into', [$from, $to])->where('into_account', "708-226792-1")->where('status', 4)->sum('amount');
        $total_credit_transaction = SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', "708-226792-1")->where('status', 4)->count();
        $total_transfer = SMS_alerts::where('date_into', [$from, $to])->where('transfer_status', 1)->sum('amount');
        $total_transfer2 = SMS_alerts::whereBetween('date', [$from, $to])->where('transfer_status', 1)->count();
        $total_split = SMS_alerts::whereDate('date_into', date('Y-m-d'))->where('split_status', 1)->sum('amount');
        $total_split_transaction = SMS_alerts::whereBetween('date', [$from, $to])->where('split_status', 1)->select(DB::raw("SUM(amount) as amount, COUNT(id) as transfer_transaction"))->first();
        $total_not_type_revenue = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 0)->whereNull('date_into')->sum('amount');
        $total_not_type = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 0)->whereNull('date_into')->count();
        $total_transaction = SMS_alerts::whereBetween('date', [$from, $to])->orWhereDate('date_into', date('Y-m-d'))->get();

        $data_bank = Masters::where('category', "bank")->where('status', 1)->select('id', 'name_th', 'name_en')->get();

        // dd($total_split);
        return view(
            'sms-forward.index',
            compact(
                'data_sms',
                'data_sms_transfer',
                'data_sms_split',
                'total_day',
                'total_room',
                'total_fb',
                'total_wp',
                'total_ev',
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
                    $sum_amount = SMS_alerts::whereBetween('date', [$from_start, $to_end])->where('status', $type)->where('into_account', $account)->WhereNull('transfer_remark')->where('split_status', 0)
                        ->orWhereDate('date_into', $adate2)->where('status', $type)->where('into_account', $account)->orderBy('date', 'asc')->sum('amount');
                } else {
                    $sum_amount = SMS_alerts::whereBetween('date', [$from_start, $to_end])->where('status', $type)->WhereNull('transfer_remark')->where('split_status', 0)
                        ->orWhereDate('date_into', $adate2)->where('status', $type)->orderBy('date', 'asc')->sum('amount');
                }
            } else {
                if (!empty($account)) {
                    $sum_amount = SMS_alerts::whereBetween('date', [$from_start, $to_end])->where('into_account', $account)->WhereNull('transfer_remark')->where('split_status', 0)
                        ->orWhereDate('date_into', $adate2)->where('into_account', $account)->orderBy('date', 'asc')->sum('amount');
                } else {
                    $sum_amount = SMS_alerts::whereBetween('date', [$from_start, $to_end])->WhereNull('transfer_remark')->where('split_status', 0)
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

    public function graphToday($date)
    {

        $adate = $date;
        $to_date = date_create($adate);
        $to_fm = date_format($to_date, "Y-m-d");

        ## 21:00:00 - 23:59:59
        $from_1 = date("Y-m-d 21:00:00", strtotime("-1 day", strtotime($adate)));
        $to_1 = date('Y-m-d 23:59:59', strtotime("-1 day", strtotime($adate)));
        $data_1 = SMS_alerts::whereBetween('date', [$from_1, $to_1])->WhereNull('transfer_remark')->where('split_status', 0)
            ->orWhereDate('date_into', $to_fm)->orderBy('date', 'asc')->sum('amount');

        ## 00:00:00 - 02:59:59
        $from_2 = date("Y-m-d 00:00:00");
        $to_2 = date('Y-m-d 02:59:59');
        $data_2 = SMS_alerts::whereBetween('date', [$from_2, $to_2])->WhereNull('transfer_remark')->where('split_status', 0)
            ->orWhereDate('date_into', $to_fm)->orderBy('date', 'asc')->sum('amount');

        ## 03:00:00 - 05:59:59
        $from_3 = date("Y-m-d 03:00:00");
        $to_3 = date('Y-m-d 05:59:59');
        $data_3 = SMS_alerts::whereBetween('date', [$from_3, $to_3])->WhereNull('transfer_remark')->where('split_status', 0)
            ->orWhereDate('date_into', $to_fm)->orderBy('date', 'asc')->sum('amount');

        ## 06:00:00 - 08:59:59
        $from_4 = date("Y-m-d 06:00:00");
        $to_4 = date('Y-m-d 08:59:59');
        $data_4 = SMS_alerts::whereBetween('date', [$from_4, $to_4])->WhereNull('transfer_remark')->where('split_status', 0)
            ->orWhereDate('date_into', $to_fm)->orderBy('date', 'asc')->sum('amount');

        ## 09:00:00 - 11:59:59
        $from_5 = date("Y-m-d 09:00:00");
        $to_5 = date('Y-m-d 11:59:59');
        $data_5 = SMS_alerts::whereBetween('date', [$from_5, $to_5])->WhereNull('transfer_remark')->where('split_status', 0)
            ->orWhereDate('date_into', $to_fm)->orderBy('date', 'asc')->sum('amount');

        ## 12:00:00 - 14:59:59
        $from_6 = date("Y-m-d 12:00:00");
        $to_6 = date('Y-m-d 14:59:59');
        $data_6 = SMS_alerts::whereBetween('date', [$from_6, $to_6])->WhereNull('transfer_remark')->where('split_status', 0)
            ->orWhereDate('date_into', $to_fm)->orderBy('date', 'asc')->sum('amount');

        ## 15:00:00 - 17:59:59
        $from_7 = date("Y-m-d 15:00:00");
        $to_7 = date('Y-m-d 17:59:59');
        $data_7 = SMS_alerts::whereBetween('date', [$from_7, $to_7])->WhereNull('transfer_remark')->where('split_status', 0)
            ->orWhereDate('date_into', $to_fm)->orderBy('date', 'asc')->sum('amount');

        ## 18:00:00 - 20:59:59
        $from_8 = date("Y-m-d 18:00:00");
        $to_8 = date('Y-m-d 20:59:59');
        $data_8 = SMS_alerts::whereBetween('date', [$from_8, $to_8])->WhereNull('transfer_remark')->where('split_status', 0)
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

        $yesterday = SMS_alerts::whereBetween('date', [$from_1, $to_1])->WhereNull('transfer_remark')->where('split_status', 0)
            ->orWhereDate('date_into', $to_1_fm)->orderBy('date', 'asc')->sum('amount');

        ## Today
        $from_2 = date("Y-m-d 21:00:00", strtotime("-1 day", strtotime($adate)));
        $to_2 = date('Y-m-d 21:00:00', strtotime($adate));

        $to_2_date = date_create($to_2);
        $to_2_fm = date_format($to_2_date, "Y-m-d");

        $today = SMS_alerts::whereBetween('date', [$from_2, $to_2])->WhereNull('transfer_remark')->where('split_status', 0)
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

        $to_3_date = date_create($to_3);
        $to_3_fm = date_format($to_3_date, "Y-m-d");

        $total_month = SMS_alerts::whereBetween('date', [$from_3, $to_3])->WhereNull('transfer_remark')->where('split_status', 0)
            ->orWhereDate('date_into', $to_3_fm)->orderBy('date', 'asc')->sum('amount');
        $forcast = $total_month / date('j');

        return response()->json([
            'yesterday' => number_format($yesterday, 2, '.', ''),
            'today' => number_format($today, 2, '.', ''),
            'forcast' => number_format($forcast, 2, '.', ''),
        ]);
    }

    // public function index_refresh($day, $month, $year)
    // {
    //     $data_forward = SMS_forwards::where('is_status', 0)->get();

    //         foreach ($data_forward as $key => $value) {
    //             if (count($data_forward) > 0) {
    //                 $exp_form = explode(" ", $value->messages);
    //                 if (count($exp_form) == 11) {
    //                     SMS_alerts::create([
    //                         'date' => $value->created_at,
    //                         'transfer_from' => SMS_alerts::check_bank($exp_form[1]),
    //                         'into_account' => SMS_alerts::check_account($exp_form[6]),
    //                         'amount' => str_replace(",", "", substr($exp_form[4], 3)),
    //                         'remark' => "Auto",
    //                     ]);

    //                     SMS_forwards::where('id', $value->id)->update([
    //                         'is_status' => 1
    //                     ]);
    //                 } elseif (count($exp_form) == 10) {
    //                     SMS_alerts::create([
    //                         'date' => $value->created_at,
    //                         'transfer_from' => SMS_alerts::check_bank($exp_form[1]),
    //                         'into_account' => SMS_alerts::check_account($exp_form[5]),
    //                         'amount' => str_replace(",", "", substr($exp_form[3], 3)),
    //                         'remark' => "Auto",
    //                     ]);

    //                     SMS_forwards::where('id', $value->id)->update([
    //                         'is_status' => 1
    //                     ]);

    //                 } elseif (count($exp_form) == 6) {
    //                     $into = mb_substr($exp_form[4], 4);
    //                     SMS_alerts::create([
    //                         'date' => $value->created_at,
    //                         'transfer_from' => SMS_alerts::check_bank(mb_substr($exp_form[1], 5)),
    //                         'into_account' => $into == "076355900016902" ? "708-226791-3" : SMS_alerts::check_account($into),
    //                         'into_qr' => $into,
    //                         'amount' => str_replace(",", "", $exp_form[0]),
    //                         'remark' => "Auto",
    //                     ]);

    //                     SMS_forwards::where('id', $value->id)->update([
    //                         'is_status' => 1
    //                     ]);
    //                 } elseif (count($exp_form) == 9) {
    //                     SMS_alerts::create([
    //                         'date' => $value->created_at,
    //                         'transfer_from' => SMS_alerts::check_bank("Credit"),
    //                         'into_account' => SMS_alerts::check_account($exp_form[3]),
    //                         'amount' => str_replace(",", "", substr($exp_form[1], 3)),
    //                         'remark' => "Auto",
    //                     ]);

    //                     SMS_forwards::where('id', $value->id)->update([
    //                         'is_status' => 1
    //                     ]);
    //                 }
    //             }
    //         }

    //     $adate = date($year."-".$month."-".$day);
    //     $from = date('Y-m-d 21:00:00', strtotime("-1 day",strtotime($adate)));
    //     $to = date($year."-".$month."-".$day.' 21:00:00');

    //     $data_sms = SMS_alerts::whereBetween('date', [$from, $to])->orWhere('date_into', date($year."-".$month."-".$day))->get();
    //     $total_day = SMS_alerts::whereBetween('date', [$from, $to])->orWhere('date_into', date($year."-".$month."-".$day))->sum('amount');
    //     $total_room = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 1)->sum('amount');
    //     $total_fb = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 2)->sum('amount');
    //     $total_wp = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 3)->sum('amount');
    //     $total_credit = SMS_alerts::where('date_into', date($year."-".$month."-".$day))->where('into_account', "708-226792-1")->where('status', 4)->sum('amount');
    //     $total_not_type = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 0)->count();

    //     return response()->json([
    //         'data_sms' => $data_sms,
    //         'total_day' => $total_day,
    //         'total_room' => $total_room,
    //         'total_fb' => $total_fb,
    //         'total_wp' => $total_wp,
    //         'total_credit' => $total_credit,
    //         'total_not_type' => $total_not_type
    //     ]);
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     $data_bank = Masters::where('category', "bank")->where('status', 1)->select('id', 'name_th', 'name_en')->get();

    //     return view('sms-forward.create',
    //         compact(
    //             'data_bank'
    //         )
    //     );
    // }

    public function forward()
    {
        return view('sms-forward.sms-example');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (isset($request->id)) {

            SMS_alerts::where('id', $request->id)->update([
                'date' => $request->date . " " . $request->time  ?? null,
                'transfer_from' => $request->transfer_from ?? 0,
                'into_account' => $request->into_account == "076355900016902" ? "708-226791-3" : $request->into_account,
                'amount' => $request->amount ?? null,
                'into_qr' => $request->into_account == "076355900016902" ? "708-226791-3" : null,
                'status' => $request->status == 0 ? 0 : $request->status,
                'transfer_status' => 0,
                'split_status' => 0,
                'remark' => Auth::user()->name,
                'updated_by' => Auth::user()->id
            ]);

            // return back()->with('success', 'ระบบได้ทำการแก้ไขรายการในระบบเรียบร้อยแล้ว');
            return response()->json([
                'status' => 200,
            ]);
        } else {

            SMS_alerts::create([
                'date' => $request->date . " " . $request->time  ?? null,
                'transfer_from' => $request->transfer_from ?? 0,
                'into_account' => $request->into_account == "076355900016902" ? "708-226791-3" : $request->into_account,
                'amount' => $request->amount ?? null,
                'into_qr' => $request->into_account == "076355900016902" ? "708-226791-3" : null,
                'booking_id' => $request->status == 5 ? $request->booking_id : NULL,
                'status' => $request->status == 0 ? 0 : $request->status,
                'transfer_status' => 0,
                'split_status' => 0,
                'remark' => Auth::user()->name,
                'created_by' => Auth::user()->id
            ]);

            $url = url()->previous();

            // return redirect($url)->with('success', 'ระบบได้ทำการบันทึกรายการในระบบเรียบร้อยแล้ว');
            return response()->json([
                'status' => 200,
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function change_status($id, $status)
    {
        if ($status == "No Category") {
            SMS_alerts::where('id', $id)->update([
                'status' => 0,
            ]);
        } elseif ($status == "Guest Deposit Revenue") {
            SMS_alerts::where('id', $id)->update([
                'status' => 1,
            ]);
        } elseif ($status == "All Outlet Revenue") {
            SMS_alerts::where('id', $id)->update([
                'status' => 2,
            ]);
        } elseif ($status == "Water Park Revenue") {
            SMS_alerts::where('id', $id)->update([
                'status' => 3,
            ]);
        } elseif ($status == "Credit Card Revenue") {
            SMS_alerts::where('id', $id)->update([
                'status' => 4,
            ]);
        } elseif ($status == "Credit Agoda Revenue") {
            SMS_alerts::where('id', $id)->update([
                'status' => 5,
            ]);
        } elseif ($status == "Front Desk Revenue") {
            SMS_alerts::where('id', $id)->update([
                'status' => 6,
            ]);
        } elseif ($status == "Credit Water Park Revenue") {
            SMS_alerts::where('id', $id)->update([
                'status' => 7,
            ]);
        } elseif ($status == "Elexa EGAT Revenue") {
            SMS_alerts::where('id', $id)->update([
                'status' => 8,
            ]);
        }

        return redirect(route('sms-alert'));
    }

    public function update_time($id, $time)
    {
        $check_data = SMS_alerts::find($id);
        SMS_alerts::where('id', $id)->update([
            'date' => Carbon::parse($check_data->date)->format('Y-m-d ' . $time),
        ]);

        return response()->json([
            'status' => 200,
        ]);
    }

    public function update_split(Request $request)
    {
        // dd($request);
        $status = 200;
        $data = SMS_alerts::find($request->splitID);
        $time = Carbon::parse($data->date)->format('H:i:s');
        foreach ($request->date_split as $key => $value) {
            SMS_alerts::create([
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

            SMS_alerts::where('id', $request->splitID)->update([
                'amount' => 0,
                'amount_before_split' => $data->amount,
                'sequence' => 1,
                'split_status' => 3
            ]);
        }

        return response()->json([
            'status' => $status,
        ]);

        // return back();
    }

    public function receive_payment($id)
    {
        SMS_alerts::where('id', $id)->update([
            'agoda_status' => 1,
        ]);

        return back();
    }

    public function get_other_revenue($id)
    {
        $data = SMS_alerts::where('status', 9)->where('id', $id)->select('other_remark')->first();

        return response()->json([
            'data' => $data,
        ]);
    }

    public function other_revenue(Request $request)
    {
        try {
            SMS_alerts::where('id', $request->dataID)->update([
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
    }

    public function transfer(Request $request)
    {
        // $check_data = SMS_alerts::find($request->dataID);
        SMS_alerts::where('id', $request->dataID)->update([
            'date_into' => date($request->date_transfer . ' 21:59:59'),
            'transfer_remark' => $request->transfer_remark,
            'transfer_status' => 1
        ]);

        return redirect(route('sms-alert'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = SMS_alerts::find($id);

        return response()->json([
            'data' => $data,
        ]);
    }

    public function search_calendar(Request $request)
    {
        if ($request->time != 0) {
            // dd($request->time);
            return $this->search_calendar_time($request);
        } else {
        }
    }


    public function search_calendar_time(Request $request)
    {

        if ($request->day == 0 && $request->month != 0) {

            $from = date("Y-m-d 21:00:00", strtotime("-1 day", strtotime(date('Y-' . $request->month . '-01'))));
            $to = date("Y-m-d" . " " . $request->time, strtotime("last day of this month", strtotime(date('Y-m-d'))));
            $adate = date("Y-m-d", strtotime($from));
            $adate2 = date("Y-m-d", strtotime($to));
            // dd([$adate, $adate2]);
            if ($request->into_account == "") {
                if ($request->status == "") {
                    $data_sms = SMS_alerts::whereBetween('date', [$from, $to])->whereNull('date_into')->orderBy('date', 'asc')->get();
                    $data_sms_transfer = SMS_alerts::whereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))->where('transfer_status', 1)
                        ->orWhereDate('date', $adate2)->where('transfer_status', 1)
                        ->orWhere('status', 4)->where('split_status', 0)->whereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))
                        ->orWhereDate('date', $adate2)->where('status', 4)->where('split_status', 0)
                        ->orderBy('date', 'asc')->get();
                    $data_sms_split = SMS_alerts::whereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))->where('split_status', 1)
                        ->orderBy('date', 'asc')->get();
                    $total_day = SMS_alerts::whereBetween('date', [$from, $to])->whereNull('date_into')
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))
                        ->sum('amount');
                    $total_front = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 6)->whereNull('date_into')
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))->where('status', 6)
                        ->sum('amount');
                    $total_room = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 1)->whereNull('date_into')
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))->where('status', 1)
                        ->sum('amount');
                    $total_fb = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 2)->whereNull('date_into')
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))->where('status', 2)
                        ->sum('amount');
                    $total_credit = SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', "708-226792-1")->whereNull('date_into')->where('status', 4)
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))->where('status', 4)
                        ->sum('amount');
                    $total_agoda = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 5)->whereNull('date_into')
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))->where('status', 5)
                        ->sum('amount');
                    $total_wp = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 3)->whereNull('date_into')
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))->where('status', 3)
                        ->sum('amount');
                    $total_wp_credit = SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', "708-226792-1")->where('status', 7)->whereNull('date_into')
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))
                        ->where('into_account', "708-226792-1")->where('status', 7)->sum('amount');
                    $total_ev = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 8)->whereNull('date_into')
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))
                        ->where('status', 8)->sum('amount');
                    $total_transfer = SMS_alerts::whereBetween('date', [$from, $to])->where('transfer_status', 1)->whereNull('date_into')
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))->where('transfer_status', 1)
                        ->sum('amount');

                    $total_credit_transaction = SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', "708-226792-1")->where('status', 4)->count();
                    $total_transfer2 = SMS_alerts::whereBetween('date', [$from, $to])->where('transfer_status', 1)->count();
                    $total_split = SMS_alerts::whereMonth('date_into', $request->month)->whereYear('date_into', $request->year)->where('split_status', 1)->sum('amount');
                    $total_split_transaction = SMS_alerts::whereBetween('date', [$from, $to])->where('split_status', 1)->select(DB::raw("SUM(amount) as amount, COUNT(id) as transfer_transaction"))->first();
                    $total_not_type = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 0)->count();
                    $total_not_type_revenue = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 0)->sum('amount');
                    $total_transaction = SMS_alerts::whereBetween('date', [$from, $to])
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))->get();
                } else {
                    $data_sms = SMS_alerts::whereBetween('date', [$from, $to])->whereNull('date_into')->where('status', $request->status)->orderBy('date', 'asc')->get();
                    $data_sms_transfer = SMS_alerts::whereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))->where('transfer_status', 1)->where('status', $request->status)
                        ->orWhereDate('date', $adate2)->where('transfer_status', 1)->where('status', $request->status)
                        ->orWhere('status', $request->status == '4' ? 4 : "-")->where('split_status', 0)->whereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))
                        ->orWhereDate('date', $adate2)->where('status', 4)->where('split_status', 0)
                        ->orderBy('date', 'asc')->get();
                    $data_sms_split = SMS_alerts::whereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))->where('split_status', 1)->where('status', $request->status)->orderBy('date', 'asc')->get();
                    $total_day = SMS_alerts::whereBetween('date', [$from, $to])->where('status', $request->status)
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))->where('status', $request->status)
                        ->sum('amount');
                    $total_front = $request->status == 6 ? SMS_alerts::whereBetween('date', [$from, $to])->where('status', 6)
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))->where('status', 6)
                        ->sum('amount') : 0;
                    $total_room = $request->status == 1 ? SMS_alerts::whereBetween('date', [$from, $to])->where('status', 1)
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))->where('status', 1)
                        ->sum('amount') : 0;
                    $total_fb = $request->status == 2 ? SMS_alerts::whereBetween('date', [$from, $to])->where('status', 2)
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))->where('status', 2)
                        ->sum('amount') : 0;
                    $total_agoda = $request->status == 5 ? SMS_alerts::whereBetween('date', [$from, $to])->where('status', 5)
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))->where('status', 5)
                        ->sum('amount') : 0;
                    $total_wp = $request->status == 3 ? SMS_alerts::whereBetween('date', [$from, $to])->where('status', 3)
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))->where('status', 3)
                        ->sum('amount') : 0;
                    $total_credit = $request->status == 4 ? SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', "708-226792-1")->whereNull('date_into')->where('status', 4)
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))
                        ->where('into_account', "708-226792-1")->where('status', 4)->sum('amount') : 0;
                    $total_wp_credit = $request->status == 7 ? SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', "708-226792-1")->where('status', 7)
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))
                        ->where('into_account', "708-226792-1")->where('status', 7)->sum('amount') : 0;
                    $total_ev = $request->status == 8 ? SMS_alerts::whereBetween('date', [$from, $to])->where('status', 8)
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))
                        ->where('status', 8)->sum('amount') : 0;
                    $total_transfer = SMS_alerts::whereBetween('date', [$from, $to])->where('transfer_status', 1)->where('status', $request->status)
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))
                        ->where('transfer_status', 1)->where('status', $request->status)->sum('amount');

                    $total_credit_transaction = $request->status == 4 ? SMS_alerts::whereMonth('date_into', $request->month)->whereYear('date_into', $request->year)->where('into_account', "708-226792-1")->where('status', 4)->count() : 0;
                    $total_transfer2 = SMS_alerts::whereBetween('date', [$from, $to])->where('transfer_status', 1)->where('status', $request->status)->count();
                    $total_split = SMS_alerts::whereMonth('date_into', $request->month)->whereYear('date_into', $request->year)->where('split_status', 1)->where('status', $request->status)->sum('amount');
                    $total_split_transaction = SMS_alerts::whereBetween('date', [$from, $to])->where('split_status', 1)->where('status', $request->status)->select(DB::raw("SUM(amount) as amount, COUNT(id) as transfer_transaction"))->first();
                    $total_not_type = 0;
                    $total_not_type_revenue = 0;
                    $total_transaction = SMS_alerts::whereBetween('date', [$from, $to])->where('status', $request->status)
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))
                        ->where('status', $request->status)->get();
                }
            } else {
                if ($request->status == "") {

                    $data_sms = SMS_alerts::whereBetween('date', [$from, $to])->whereNull('date_into')->where('into_account', $request->into_account)->orderBy('date', 'asc')->get();
                    $data_sms_transfer = SMS_alerts::whereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))->where('transfer_status', 1)->where('into_account', $request->into_account)
                        ->orWhereDate('date', $adate2)->where('transfer_status', 1)->where('into_account', $request->into_account)
                        ->orWhere('status', 4)->where('split_status', 0)->whereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))->where('into_account', $request->into_account)
                        ->orWhereDate('date', $adate2)->where('status', 4)->where('split_status', 0)
                        ->orderBy('date', 'asc')->get();
                    $data_sms_split = SMS_alerts::whereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))->where('split_status', 1)->where('into_account', $request->into_account)->orderBy('date', 'asc')->get();
                    $total_day = SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', $request->into_account)
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))
                        ->where('into_account', $request->into_account)->sum('amount');
                    $total_front = SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', $request->into_account)->where('status', 6)
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))
                        ->where('into_account', $request->into_account)->where('status', 6)->sum('amount');
                    $total_room = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 1)->where('into_account', $request->into_account)
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))
                        ->where('into_account', $request->into_account)->where('status', 1)->sum('amount');
                    $total_fb = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 2)->where('into_account', $request->into_account)
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))
                        ->where('into_account', $request->into_account)->where('status', 2)->sum('amount');
                    $total_credit = $request->into_account == "708-226792-1" ? SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', "708-226792-1")->whereNull('date_into')->where('status', 4)
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))
                        ->where('into_account', "708-226792-1")->where('status', 4)->sum('amount') : 0;
                    $total_agoda = SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', $request->into_account)->where('status', 5)
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))
                        ->where('into_account', $request->into_account)->where('status', 5)->sum('amount');
                    $total_wp = $request->into_account == "708-227357-4" ? SMS_alerts::whereBetween('date', [$from, $to])->where('status', 3)
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))
                        ->where('status', 3)->sum('amount') : 0;
                    $total_wp_credit = $request->into_account == "708-227357-4" ? SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', "708-226792-1")->where('status', 7)
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))
                        ->where('into_account', "708-226792-1")->where('status', 7)->sum('amount') : 0;
                    $total_ev = SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', $request->into_account)->where('status', 8)
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))->where('into_account', $request->into_account)
                        ->where('status', 8)->sum('amount');
                    $total_transfer = SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', $request->into_account)->where('transfer_status', 1)
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))
                        ->where('into_account', $request->into_account)->where('transfer_status', 1)->sum('amount');

                    $total_credit_transaction = $request->into_account == "708-226792-1" ? SMS_alerts::whereMonth('date_into', $request->month)->whereYear('date_into', $request->year)->where('into_account', "708-226792-1")->where('status', 4)->count() : 0;
                    $total_transfer2 = SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', $request->into_account)->where('transfer_status', 1)->count();
                    $total_split = SMS_alerts::whereMonth('date_into', $request->month)->whereYear('date_into', $request->year)->where('split_status', 1)->where('into_account', $request->into_account)->sum('amount');
                    $total_split_transaction = SMS_alerts::whereBetween('date', [$from, $to])->where('split_status', 1)->where('into_account', $request->into_account)->select(DB::raw("SUM(amount) as amount, COUNT(id) as transfer_transaction"))->first();
                    $total_not_type = SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', $request->into_account)->where('status', 0)->count();
                    $total_not_type_revenue = SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', $request->into_account)->where('status', 0)->sum('amount');
                    $total_transaction = SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', $request->into_account)
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))
                        ->where('into_account', $request->into_account)->get();
                } else {
                    $data_sms = SMS_alerts::whereBetween('date', [$from, $to])->whereNull('date_into')->where('status', $request->status)->where('into_account', $request->into_account)->orderBy('id', 'asc')->get();
                    $data_sms_transfer = SMS_alerts::whereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))->where('transfer_status', 1)->where('status', $request->status)->where('into_account', $request->into_account)
                        ->orWhereDate('date', $adate2)->where('transfer_status', 1)->where('status', $request->status)->where('into_account', $request->into_account)
                        ->orWhere('status', $request->status == '4' ? 4 : "-")->where('split_status', 0)->whereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))->where('into_account', $request->into_account)
                        ->orWhereDate('date', $adate2)->where('status', 4)->where('split_status', 0)
                        ->orderBy('date', 'asc')->get();
                    $data_sms_split = SMS_alerts::whereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))->where('split_status', 1)->where('status', $request->status)->where('into_account', $request->into_account)
                        ->orderBy('date', 'asc')->get();
                    $total_day = SMS_alerts::whereBetween('date', [$from, $to])->where('status', $request->status)->where('into_account', $request->into_account)
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))
                        ->where('status', $request->status)->where('into_account', $request->into_account)->sum('amount');
                    $total_room = $request->status == 1 ? SMS_alerts::whereBetween('date', [$from, $to])->where('status', 1)->where('into_account', $request->into_account)
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))->where('into_account', $request->into_account)
                        ->where('status', 1)->sum('amount') : 0;
                    $total_front = $request->status == 6 ? SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', $request->into_account)->where('status', 6)
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))
                        ->where('into_account', $request->into_account)->where('status', 6)->sum('amount') : 0;
                    $total_fb = $request->status == 2 ? SMS_alerts::whereBetween('date', [$from, $to])->where('status', 2)->where('into_account', $request->into_account)
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))
                        ->where('into_account', $request->into_account)->where('status', 2)->sum('amount') : 0;
                    $total_agoda = $request->status == 5 ? SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', $request->into_account)->where('status', 5)
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))
                        ->where('into_account', $request->into_account)->where('status', 5)->sum('amount') : 0;
                    $total_credit = $request->status == 4 && $request->into_account == "708-226792-1" ? SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', "708-226792-1")->whereNull('date_into')->where('status', 4)
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))
                        ->where('into_account', "708-226792-1")->where('status', 4)->sum('amount') : 0;
                    $total_wp = $request->status == 3 && $request->into_account == "708-227357-4" ? SMS_alerts::whereBetween('date', [$from, $to])->where('status', 3)
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))
                        ->where('status', 3)->sum('amount') : 0;
                    $total_wp_credit = $request->status == 7 && $request->into_account == "708-227357-4" ? SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', "708-226792-1")->where('status', 7)
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))->where('into_account', "708-226792-1")
                        ->where('status', 7)->sum('amount') : 0;
                    $total_ev = SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', $request->into_account)->where('status', 8)
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))->where('into_account', $request->into_account)
                        ->where('status', 8)->sum('amount');
                    $total_transfer = SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', $request->into_account)->where('transfer_status', 1)->where('status', $request->status)
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))->where('into_account', $request->into_account)
                        ->where('transfer_status', 1)->where('status', $request->status)->sum('amount');

                    $total_credit_transaction = $request->status == 4 && $request->into_account == "708-226792-1" ? SMS_alerts::whereMonth('date_into', $request->month)->whereYear('date_into', $request->year)->where('into_account', "708-226792-1")->where('status', 4)->count() : 0;
                    $total_transfer2 = SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', $request->into_account)->where('transfer_status', 1)->where('status', $request->status)->count();
                    $total_split = SMS_alerts::whereMonth('date_into', $request->month)->whereYear('date_into', $request->year)->where('split_status', 1)->where('into_account', $request->into_account)->where('status', $request->status)->sum('amount');
                    $total_split_transaction = SMS_alerts::whereBetween('date', [$from, $to])->where('split_status', 1)->where('into_account', $request->into_account)->where('status', $request->status)->select(DB::raw("SUM(amount) as amount, COUNT(id) as transfer_transaction"))->first();
                    $total_not_type = 0;
                    $total_not_type_revenue = 0;
                    $total_transaction = SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', $request->into_account)->where('status', $request->status)
                        ->orwhereDate('date_into', '>', date($from))->whereDate('date_into', '<=', date($to))
                        ->where('into_account', $request->into_account)->where('status', $request->status)->get();
                }
            }
        } elseif ($request->day != 0 && $request->month != 0) {

            $adate = date('Y-' . $request->month . '-' . $request->day);
            $from = date('Y-m-d' . ' 21:00:00', strtotime('-1 day', strtotime(date($adate))));
            $to = date($adate . ' ' . $request->time);

            if ($request->into_account == "") {
                if ($request->status == "") {
                    $data_sms = SMS_alerts::whereBetween('date', [$from, $to])->whereNull('date_into')->orderBy('date', 'asc')->get();
                    $data_sms_transfer = SMS_alerts::whereDate('date_into', $adate)->where('transfer_status', 1)
                        ->orWhereDate('date', $adate)->where('transfer_status', 1)
                        ->orWhere('status', 4)->where('split_status', 0)->whereDate('date_into', $adate)
                        ->orWhereDate('date', $adate)->where('status', 4)->where('split_status', 0)
                        ->orderBy('date', 'asc')->get();
                    $data_sms_split = SMS_alerts::whereDate('date_into', $adate)->where('split_status', 1)->orderBy('date', 'asc')->get();
                    $total_day = SMS_alerts::whereBetween('date', [$from, $to])->whereNull('date_into')->orWhereDate('date_into', $adate)->sum('amount');
                    $total_front = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 6)->whereNull('date_into')
                        ->orWhereDate('date_into', $adate)->where('status', 6)
                        ->sum('amount');
                    $total_room = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 1)->whereNull('date_into')
                        ->orWhereDate('date_into', $adate)->where('status', 1)
                        ->sum('amount');
                    $total_fb = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 2)->whereNull('date_into')
                        ->orWhereDate('date_into', $adate)->where('status', 2)
                        ->sum('amount');
                    $total_credit = SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', "708-226792-1")->whereNull('date_into')->where('status', 4)
                        ->orWhereDate('date_into', $adate)->where('into_account', "708-226792-1")
                        ->where('status', 4)->sum('amount');
                    $total_agoda = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 5)->whereNull('date_into')
                        ->orWhereDate('date_into', $adate)->where('status', 5)
                        ->sum('amount');
                    $total_wp = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 3)->whereNull('date_into')
                        ->orWhereDate('date_into', $adate)->where('status', 3)
                        ->sum('amount');
                    $total_wp_credit = SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', "708-226792-1")->where('status', 7)->whereNull('date_into')
                        ->orWhereDate('date_into', $adate)->where('into_account', "708-226792-1")
                        ->where('status', 7)->sum('amount');
                    $total_ev = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 8)->whereNull('date_into')
                        ->orWhereDate('date_into', $adate)->where('status', 8)
                        ->sum('amount');
                    $total_transfer = SMS_alerts::whereBetween('date', [$from, $to])->where('transfer_status', 1)->whereNull('date_into')
                        ->orWhereDate('date_into', $adate)->where('transfer_status', 1)
                        ->sum('amount');

                    $total_credit_transaction = SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', "708-226792-1")->where('status', 4)->count();
                    $total_transfer2 = SMS_alerts::whereBetween('date', [$from, $to])->where('transfer_status', 1)->count();
                    $total_split = SMS_alerts::whereDate('date_into', $adate)->where('split_status', 1)->sum('amount');
                    $total_split_transaction = SMS_alerts::whereBetween('date', [$from, $to])->where('split_status', 1)->select(DB::raw("SUM(amount) as amount, COUNT(id) as transfer_transaction"))->first();
                    $total_not_type = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 0)->count();
                    $total_not_type_revenue = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 0)->sum('amount');
                    $total_transaction = SMS_alerts::whereBetween('date', [$from, $to])->orWhereDate('date_into', $adate)->get();
                } else {
                    $data_sms = SMS_alerts::whereBetween('date', [$from, $to])->whereNull('date_into')->where('status', $request->status)->orderBy('date', 'asc')->get();
                    $data_sms_transfer = SMS_alerts::whereDate('date_into', $adate)->where('transfer_status', 1)->where('status', $request->status)
                        ->orWhereDate('date', $adate)->where('transfer_status', 1)->where('status', $request->status)
                        ->orWhere('status', $request->status == '4' ? 4 : "-")->where('split_status', 0)->whereDate('date_into', $adate)
                        ->orWhereDate('date', $adate)->where('status', 4)->where('split_status', 0)
                        ->orderBy('date', 'asc')->get();
                    $data_sms_split = SMS_alerts::whereDate('date_into', $adate)->where('split_status', 1)->where('status', $request->status)
                        ->orderBy('date', 'asc')->get();
                    $total_day = SMS_alerts::whereBetween('date', [$from, $to])->where('status', $request->status)->whereNull('date_into')
                        ->orwhereDate('date_into', $adate)
                        ->where('status', $request->status)->sum('amount');
                    $total_front = $request->status == 6 ? SMS_alerts::whereBetween('date', [$from, $to])->where('status', 6)->whereNull('date_into')
                        ->orwhereDate('date_into', $adate)
                        ->where('status', 6)->sum('amount') : 0;
                    $total_room = $request->status == 1 ? SMS_alerts::whereBetween('date', [$from, $to])->where('status', 1)->whereNull('date_into')
                        ->orwhereDate('date_into', $adate)
                        ->where('status', 1)->sum('amount') : 0;
                    $total_fb = $request->status == 2 ? SMS_alerts::whereBetween('date', [$from, $to])->where('status', 2)->whereNull('date_into')
                        ->orwhereDate('date_into', $adate)
                        ->where('status', 2)->sum('amount') : 0;
                    $total_agoda = $request->status == 5 ? SMS_alerts::whereBetween('date', [$from, $to])->where('status', 5)->whereNull('date_into')
                        ->orwhereDate('date_into', $adate)
                        ->where('status', 5)->sum('amount') : 0;
                    $total_wp = $request->status == 3 ? SMS_alerts::whereBetween('date', [$from, $to])->where('status', 3)->whereNull('date_into')
                        ->orwhereDate('date_into', $adate)
                        ->where('status', 3)->sum('amount') : 0;
                    $total_credit = $request->status == 4 ? SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', "708-226792-1")->whereNull('date_into')->where('status', 4)
                        ->orwhereDate('date_into', $adate)
                        ->where('into_account', "708-226792-1")->where('status', 4)->sum('amount') : 0;
                    $total_wp_credit = $request->status == 7 ? SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', "708-226792-1")->where('status', 7)->whereNull('date_into')
                        ->orwhereDate('date_into', $adate)
                        ->where('into_account', "708-226792-1")->where('status', 7)->sum('amount') : 0;
                    $total_ev = $request->status == 8 ? SMS_alerts::whereBetween('date', [$from, $to])->where('status', 8)->whereNull('date_into')
                        ->orwhereDate('date_into', $adate)
                        ->where('status', 8)->sum('amount') : 0;
                    $total_transfer = SMS_alerts::whereBetween('date', [$from, $to])->where('transfer_status', 1)->where('status', $request->status)->whereNull('date_into')
                        ->orwhereDate('date_into', $adate)
                        ->where('transfer_status', 1)->where('status', $request->status)->sum('amount');

                    $total_credit_transaction = $request->status == 4 ? SMS_alerts::whereDate('date_into', '<=', $adate)->where('into_account', "708-226792-1")->where('status', 4)->count() : 0;
                    $total_transfer2 = SMS_alerts::whereBetween('date', [$from, $to])->where('transfer_status', 1)->where('status', $request->status)->count();
                    $total_split = SMS_alerts::whereDate('date_into', $adate)->where('split_status', 1)->where('status', $request->status)->sum('amount');
                    $total_split_transaction = SMS_alerts::whereBetween('date', [$from, $to])->where('split_status', 1)->where('status', $request->status)->select(DB::raw("SUM(amount) as amount, COUNT(id) as transfer_transaction"))->first();
                    $total_not_type = 0;
                    $total_not_type_revenue = 0;
                    $total_transaction = SMS_alerts::whereBetween('date', [$from, $to])->where('status', $request->status)
                        ->orwhereDate('date_into', $adate)
                        ->where('status', $request->status)->get();
                }
            } else {
                if ($request->status == "") {
                    $data_sms = SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', $request->into_account)->whereNull('date_into')->orderBy('date', 'asc')->get();
                    $data_sms_transfer = SMS_alerts::whereDate('date_into', $adate)->where('transfer_status', 1)->where('into_account', $request->into_account)
                        ->orWhereDate('date', $adate)->where('transfer_status', 1)->where('into_account', $request->into_account)
                        ->orWhere('status', 4)->where('split_status', 0)->whereDate('date_into', $adate)->where('into_account', $request->into_account)
                        ->orWhereDate('date', $adate)->where('status', 4)->where('split_status', 0)
                        ->orderBy('date', 'asc')->get();
                    $data_sms_split = SMS_alerts::whereDate('date_into', $adate)->where('split_status', 1)->where('into_account', $request->into_account)->orderBy('date', 'asc')->get();
                    $total_day = SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', $request->into_account)->whereNull('date_into')
                        ->orWhereDate('date_into', $adate)->where('into_account', $request->into_account)
                        ->sum('amount');
                    $total_front = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 6)->where('into_account', $request->into_account)->whereNull('date_into')
                        ->orWhereDate('date_into', $adate)->where('status', 6)->where('into_account', $request->into_account)
                        ->sum('amount');
                    $total_room = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 1)->where('into_account', $request->into_account)->whereNull('date_into')
                        ->orWhereDate('date_into', $adate)->where('status', 1)->where('into_account', $request->into_account)
                        ->sum('amount');
                    $total_fb = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 2)->where('into_account', $request->into_account)->whereNull('date_into')
                        ->orWhereDate('date_into', $adate)->where('status', 2)->where('into_account', $request->into_account)
                        ->sum('amount');
                    $total_credit = SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', "708-226792-1")->whereNull('date_into')->where('status', 4)
                        ->orWhereDate('date_into', $adate)->where('into_account', "708-226792-1")
                        ->where('status', 4)->sum('amount');
                    $total_agoda = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 5)->where('into_account', $request->into_account)->whereNull('date_into')
                        ->orWhereDate('date_into', $adate)->where('status', 5)->where('into_account', $request->into_account)
                        ->sum('amount');
                    $total_wp = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 3)->where('into_account', $request->into_account)->whereNull('date_into')
                        ->orWhereDate('date_into', $adate)->where('status', 3)->where('into_account', $request->into_account)
                        ->sum('amount');
                    $total_wp_credit = SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', "708-226792-1")->where('status', 7)->whereNull('date_into')
                        ->orWhereDate('date_into', $adate)->where('into_account', "708-226792-1")
                        ->where('status', 7)->sum('amount');
                    $total_ev = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 8)->where('into_account', $request->into_account)->whereNull('date_into')
                        ->orWhereDate('date_into', $adate)->where('status', 8)->where('into_account', $request->into_account)
                        ->sum('amount');
                    $total_transfer = SMS_alerts::whereBetween('date', [$from, $to])->where('transfer_status', 1)->where('into_account', $request->into_account)->whereNull('date_into')
                        ->orWhereDate('date_into', $adate)->where('transfer_status', 1)->where('into_account', $request->into_account)
                        ->sum('amount');

                    $total_credit_transaction = SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', "708-226792-1")->where('status', 4)->count();
                    $total_transfer2 = SMS_alerts::whereBetween('date', [$from, $to])->where('transfer_status', 1)->where('into_account', $request->into_account)->count();
                    $total_split = SMS_alerts::whereDate('date_into', $adate)->where('split_status', 1)->where('into_account', $request->into_account)->sum('amount');
                    $total_split_transaction = SMS_alerts::whereBetween('date', [$from, $to])->where('split_status', 1)->where('into_account', $request->into_account)
                        ->select(DB::raw("SUM(amount) as amount, COUNT(id) as transfer_transaction"))->first();
                    $total_not_type = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 0)->where('into_account', $request->into_account)->count();
                    $total_not_type_revenue = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 0)->where('into_account', $request->into_account)->sum('amount');
                    $total_transaction = SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', $request->into_account)
                        ->orWhereDate('date_into', $adate)->where('into_account', $request->into_account)->get();
                } else {
                    $data_sms = SMS_alerts::whereBetween('date', [$from, $to])->whereNull('date_into')->where('status', $request->status)->where('into_account', $request->into_account)
                        ->orderBy('date', 'asc')->get();
                    $data_sms_transfer = SMS_alerts::whereDate('date_into', $adate)->where('transfer_status', 1)->where('status', $request->status)->where('into_account', $request->into_account)
                        ->orWhereDate('date', $adate)->where('transfer_status', 1)->where('status', $request->status)->where('into_account', $request->into_account)
                        ->orWhere('status', $request->status == '4' ? 4 : "-")->where('split_status', 0)->whereDate('date_into', $adate)->where('into_account', $request->into_account)
                        ->orWhereDate('date', $adate)->where('status', 4)->where('split_status', 0)
                        ->orderBy('date', 'asc')->get();
                    $data_sms_split = SMS_alerts::whereDate('date_into', $adate)->where('split_status', 1)->where('status', $request->status)->where('into_account', $request->into_account)
                        ->orderBy('date', 'asc')->get();
                    $total_day = SMS_alerts::whereBetween('date', [$from, $to])->where('status', $request->status)->where('into_account', $request->into_account)->whereNull('date_into')
                        ->orwhereDate('date_into', $adate)->where('into_account', $request->into_account)
                        ->where('status', $request->status)->sum('amount');
                    $total_front = $request->status == 6 ? SMS_alerts::whereBetween('date', [$from, $to])->where('status', 6)->where('into_account', $request->into_account)->whereNull('date_into')
                        ->orwhereDate('date_into', $adate)->where('into_account', $request->into_account)
                        ->where('status', 6)->sum('amount') : 0;
                    $total_room = $request->status == 1 ? SMS_alerts::whereBetween('date', [$from, $to])->where('status', 1)->where('into_account', $request->into_account)->whereNull('date_into')
                        ->orwhereDate('date_into', $adate)->where('into_account', $request->into_account)
                        ->where('status', 1)->sum('amount') : 0;
                    $total_fb = $request->status == 2 ? SMS_alerts::whereBetween('date', [$from, $to])->where('status', 2)->where('into_account', $request->into_account)->whereNull('date_into')
                        ->orwhereDate('date_into', $adate)->where('into_account', $request->into_account)
                        ->where('status', 2)->sum('amount') : 0;
                    $total_agoda = $request->status == 5 ? SMS_alerts::whereBetween('date', [$from, $to])->where('status', 5)->where('into_account', $request->into_account)->whereNull('date_into')
                        ->orwhereDate('date_into', $adate)->where('into_account', $request->into_account)
                        ->where('status', 5)->sum('amount') : 0;
                    $total_wp = $request->status == 3 ? SMS_alerts::whereBetween('date', [$from, $to])->where('status', 3)->where('into_account', $request->into_account)->whereNull('date_into')
                        ->orwhereDate('date_into', $adate)->where('into_account', $request->into_account)
                        ->where('status', 3)->sum('amount') : 0;
                    $total_credit = $request->status == 4 ? SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', "708-226792-1")->whereNull('date_into')->where('status', 4)
                        ->orwhereDate('date_into', $adate)
                        ->where('into_account', "708-226792-1")->where('status', 4)->sum('amount') : 0;
                    $total_wp_credit = $request->status == 7 ? SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', "708-226792-1")->where('status', 7)->whereNull('date_into')
                        ->orwhereDate('date_into', $adate)
                        ->where('into_account', "708-226792-1")->where('status', 7)->sum('amount') : 0;
                    $total_ev = $request->status == 8 ? SMS_alerts::whereBetween('date', [$from, $to])->where('status', 8)->where('into_account', $request->into_account)->whereNull('date_into')
                        ->orwhereDate('date_into', $adate)->where('into_account', $request->into_account)
                        ->where('status', 8)->sum('amount') : 0;
                    $total_transfer = SMS_alerts::whereBetween('date', [$from, $to])->where('transfer_status', 1)->where('status', $request->status)->where('into_account', $request->into_account)->whereNull('date_into')
                        ->orwhereDate('date_into', $adate)->where('into_account', $request->into_account)
                        ->where('transfer_status', 1)->where('status', $request->status)->sum('amount');

                    $total_credit_transaction = $request->status == 4 ? SMS_alerts::whereDate('date_into', '<=', $adate)->where('into_account', "708-226792-1")->where('status', 4)->count() : 0;
                    $total_transfer2 = SMS_alerts::whereBetween('date', [$from, $to])->where('transfer_status', 1)->where('status', $request->status)->where('into_account', $request->into_account)->count();
                    $total_split = SMS_alerts::whereDate('date_into', $adate)->where('split_status', 1)->where('status', $request->status)->where('into_account', $request->into_account)->sum('amount');
                    $total_split_transaction = SMS_alerts::whereBetween('date', [$from, $to])->where('split_status', 1)->where('status', $request->status)
                        ->where('into_account', $request->into_account)
                        ->select(DB::raw("SUM(amount) as amount, COUNT(id) as transfer_transaction"))->first();
                    $total_not_type = 0;
                    $total_not_type_revenue = 0;
                    $total_transaction = SMS_alerts::whereBetween('date', [$from, $to])->where('status', $request->status)->where('into_account', $request->into_account)
                        ->orwhereDate('date_into', $adate)->where('into_account', $request->into_account)
                        ->where('status', $request->status)->get();
                }
            }
        }

        $data_bank = Masters::where('category', "bank")->where('status', 1)->select('id', 'name_th', 'name_en')->get();

        // dd($total_transfer2->transfer_transaction);

        $day = $request->day;
        $month = $request->month;
        $year = $request->year;

        $day2 = $request->day2;
        $month2 = $request->month2;
        $year2 = $request->year2;

        $time = $request->time;
        $time2 = $request->time2;

        $status = $request->status;
        $into_account = $request->into_account;
        $note1 = $request->note1;
        $note2 = $request->note2;
        $note3 = $request->note3;

        // dd($day);

        return view('sms-forward.index', compact(
            'data_sms',
            'data_sms_transfer',
            'data_sms_split',
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
            'total_ev',
            'day',
            'month',
            'year',
            'day2',
            'month2',
            'year2',
            'status',
            'into_account',
            'time',
            'time2',
            'note1',
            'note2',
            'note3',
            'data_bank'
        ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        SMS_alerts::where('id', $id)->delete();

        return redirect(route('sms-alert'));
    }

    public function detail($topic, $date)
    {
        $adate = Carbon::parse($date)->format('Y-m-d');
        $from = date('Y-m-d' . ' 21:00:00', strtotime('-1 day', strtotime(date($date))));
        $to = $date;
        $title = "";

        $data_bank = Masters::where('category', "bank")->where('status', 1)->select('id', 'name_th', 'name_en')->get();

        if ($topic == "front") {
            $data_sms = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 6)
                ->orWhereDate('date_into', $adate)
                ->where('status', 6)->paginate(10);
            $title = "Front Desk Bank Transfer Revenue";

        } elseif ($topic == "room") {
            $data_sms = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 1)
                ->whereNull('date_into')->orWhereDate('date_into', $adate)
                ->where('status', 1)->paginate(1);
            $title = "Guest Deposit Bank Transfer Revenue";

        } elseif ($topic == "all_outlet") {
            $data_sms = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 2)
                ->whereNull('date_into')->orWhereDate('date_into', $adate)
                ->where('status', 2)->get();
            $title = "All Outlet Revenue";

        } elseif ($topic == "credit") {
            $data_sms = SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', "708-226792-1")->where('status', 4)
                ->orWhereDate('date_into', $adate)->where('into_account', "708-226792-1")
                ->where('status', 4)->get();
            $title = "Credit Card Hotel Revenue";

        } elseif ($topic == "credit_water") {
            $data_sms = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 7)
                ->whereNull('date_into')->orWhereDate('date_into', $adate)
                ->where('status', 7)->get();
            $title = "Credit Card Water Park Revenue";

        } elseif ($topic == "water") {
            $data_sms = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 3)
                ->whereNull('date_into')->orWhereDate('date_into', $adate)
                ->where('status', 3)->get();
            $title = "Water Park Bank Transfer Revenue";

        } elseif ($topic == "elexa_revenue") {
            $data_sms = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 8)
                ->whereNull('date_into')->orWhereDate('date_into', $adate)
                ->where('status', 8)->get();
            $title = "Elexa EGAT Revenue";

        } elseif ($topic == "transfer_revenue") {
            $data_sms = SMS_alerts::whereDate('date_into', $adate)->where('transfer_status', 1)->get();
            $title = "Transfer Revenue";

        } elseif ($topic == "split_revenue") {
            $data_sms = SMS_alerts::whereDate('date_into', $adate)->where('split_status', 1)->get();
            $title = "Split Credit Card Hotel Revenue";

        } elseif ($topic == "transfer_transaction") {
            $data_sms = SMS_alerts::whereBetween('date', [$from, $to])->where('transfer_status', 1)->get();
            $title = "Transfer Transaction";

        } elseif ($topic == "credit_transaction") {
            $data_sms = SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', "708-226792-1")->where('status', 4)->get();
            $title = "Credit Card Hotel Transfer Transaction";

        } elseif ($topic == "split_transaction") {
            $data_sms = SMS_alerts::whereBetween('date', [$from, $to])->where('split_status', 1)->get();
            $title = "Split Credit Card Hotel Transaction";

        } elseif ($topic == "total_transaction") {
            $data_sms = SMS_alerts::whereBetween('date', [$from, $to])
                ->orWhereDate('date_into', $adate)->get();
            $title = "Total Transaction";

        } elseif ($topic == "status") {
            $data_sms = SMS_alerts::whereDate('date', [$from, $to])->where('status', 0)->whereNull('date_into')
                ->orWhereDate('date_into', $adate)
                ->where('status', 0)->get();
            $title = "No Income Type";

        } elseif ($topic == "no_income_revenue") {
            $data_sms = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 0)->whereNull('date_into')
                    ->orWhereDate('date_into', $adate)
                    ->where('status', 0)->get();
            $title = "No Income Revenue";

        }

        return view('sms-forward.detail', compact('data_sms', 'data_bank', 'title'));
    }

    public function agoda_detail($date)
    {
        // $adate= date('Y-m-d 21:00:00');
        // $from = date("Y-m-d 21:00:00", strtotime("-1 day",strtotime($adate)));
        // $to = date('Y-m-d 21:00:00');

        $adate = Carbon::parse($date)->format('Y-m-d');
        $from = date('Y-m-d' . ' 21:00:00', strtotime('-1 day', strtotime(date($date))));
        $to = $date;

        $sum_revenue = Revenues::rightjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')
            ->where('revenue_credit.status', 5)->where('revenue_credit.revenue_type', 5)
            ->select('revenue_credit.agoda_charge', 'revenue_credit.agoda_outstanding', 'revenue.date')->get();

        $data_sms = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 5)
            ->whereNull('date_into')->orWhereDate('date_into', $adate)
            ->where('status', 5)->get();

        return view('sms-forward.agoda_detail', compact('sum_revenue', 'data_sms'));
    }
}
