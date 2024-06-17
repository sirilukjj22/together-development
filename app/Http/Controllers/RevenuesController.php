<?php

namespace App\Http\Controllers;

use App\Models\Revenue_credit;
use App\Models\Revenues;
use App\Models\SMS_alerts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use PHPUnit\Framework\Constraint\Count;

class RevenuesController extends Controller
{

    public function index()
    {
        $adate= date('Y-m 21:00:00');
        $from = date("Y-m-d 21:00:00", strtotime("-1 day"));
        $to = date('Y-m-d 21:00:00');

        // dd($to);

        $check_data = Revenues::whereMonth('date', date('m'))->whereYear('date', date('Y'))->first();

        if (empty($check_data)) {
            $days = date('t');

            for ($i=1; $i <= $days; $i++) { 
                Revenues::create([
                    'date' => date('Y').date('m').str_pad($i, 2, '0', STR_PAD_LEFT),
                    'status' => 0
                ]);
            }
        }

        // dd(date("Y-m-01 21:00:00"));

        ## Update จำนวนเงินของแต่ละวัน
        $room_array = [];
        $fb_array = [];
        $wp_array = [];
        $credit_array = [];
        $agoda_array = [];
        $front_array = [];
        $credit_wp_array = [];
        $ev_array = [];
        $no_type_array = [];
        $transaction_array = [];
        
        for ($i=1; $i <= 31; $i++) { 
            if ($i == 1) {
                $check_sms = SMS_alerts::whereBetween('date', [date("Y-m-d 21:00:00", strtotime("last day of previous month")), date("Y-m-01 21:00:00")])->whereNull('date_into')
                ->orWhereDate('date_into', date("Y-m-01"))
                ->select('sms_alert.*', DB::raw("COUNT(id) as transaction_bill, DATE(date) as date_fm, SUM(amount) as total_amount"))->groupBy('status')->get();

                $sum_bill = 0;
                foreach ($check_sms as $key => $value) {
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 1) {
                        $room_array[$i] = [
                            'total_room' => $check_sms[$key]['total_amount'],
                        ];
                    } 
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 2) {
                        $fb_array[$i] = [
                            'total_fb' => $check_sms[$key]['total_amount'],
                        ];
                    } 
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 3) {
                        $wp_array[$i] = [
                            'total_wp' => $check_sms[$key]['total_amount'],
                        ];
                    } if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 4) {
                        $credit_array[$i] = [
                            'total_credit' => $check_sms[$key]['amount'],
                        ];
                    } if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 5) {
                        $agoda_array[$i] = [
                            'total_agoda' => $check_sms[$key]['total_amount'],
                        ];
                    } if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 6) {
                        $front_array[$i] = [
                            'total_front' => $check_sms[$key]['total_amount'],
                        ];
                    } if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 7) {
                        $credit_wp_array[$i] = [
                            'total_credit_wp' => $check_sms[$key]['total_amount'],
                        ];
                    } if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 8) {
                        $ev_array[$i] = [
                            'total_ev' => $check_sms[$key]['total_amount'],
                        ];
                    } if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 0) {
                        $no_type_array[$i] = ['no_type' => $check_sms[$key]['transaction_bill']];
                    }

                    $sum_bill += $check_sms[$key]['transaction_bill'];
                }

                $transaction_array[$i] = ['bill' => $sum_bill];
                
            } else {
                $check_sms = SMS_alerts::whereBetween('date', [date("Y-m-".str_pad($i - 1, 2, '0', STR_PAD_LEFT).' 21:00:00'), date('Y-m-'.str_pad($i, 2, '0', STR_PAD_LEFT).' 21:00:00')])->whereNull('date_into')
                ->orWhereDate('date_into', date('Y-m-'.str_pad($i, 2, '0', STR_PAD_LEFT)))
                ->select('sms_alert.*', DB::raw("COUNT(id) as transaction_bill, DATE(date) as date_fm, SUM(amount) as total_amount"))->groupBy('status')->get();

                // dd($check_sms);
                $sum_bill = 0;
                foreach ($check_sms as $key => $value) {
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 1) {
                        $room_array[$i] = [
                            'total_room' => $check_sms[$key]['total_amount'],
                        ];
                    } 
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 2) {
                        $fb_array[$i] = [
                            'total_fb' => $check_sms[$key]['total_amount'],
                        ];
                    } 
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 3) {
                        $wp_array[$i] = [
                            'total_wp' => $check_sms[$key]['total_amount'],
                        ];
                    } if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 4 && $check_sms[$key]['into_account'] == "708-226792-1") {
                        $credit_array[$i] = [
                            'total_credit' => $check_sms[$key]['amount'],
                        ];
                    } if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 5) {
                        $agoda_array[$i] = [
                            'total_agoda' => $check_sms[$key]['total_amount'],
                        ];
                    } if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 6) {
                        $front_array[$i] = [
                            'total_front' => $check_sms[$key]['total_amount'],
                        ];
                    } if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 7) {
                        $credit_wp_array[$i] = [
                            'total_credit_wp' => $check_sms[$key]['total_amount'],
                        ];
                    } if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 8) {
                        $ev_array[$i] = [
                            'total_ev' => $check_sms[$key]['total_amount'],
                        ];
                    } if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 0) {
                        $no_type_array[$i] = ['no_type' => $check_sms[$key]['transaction_bill']];
                    }

                    $sum_bill += $check_sms[$key]['transaction_bill'];
                    
                }
                $transaction_array[$i] = ['bill' => $sum_bill];
            }
            
        }

        // $check_sms = SMS_alerts::whereBetween('date', [date("Y-m-".str_pad(5 - 1, 2, '0', STR_PAD_LEFT).' 21:00:00'), date('Y-m-'.str_pad(5, 2, '0', STR_PAD_LEFT).' 21:00:00')])
        //         ->select('sms_alert.*', DB::raw("COUNT(id) as transaction_bill, DATE(date) as date_fm, SUM(amount) as total_amount"))->groupBy('status')->get();

        $room_transfer = 0;
        $fb_transfer = 0;
        $wp_transfer = 0;
        $room_credit = 0;

        if (isset($room_array)) {
            foreach ($room_array as $key => $value) {
                Revenues::where('date', date('Y-m-'.$key))->update([
                    'room_transfer' => $value['total_room']
                ]);
            }
        }

        if (isset($fb_array)) {
            foreach ($fb_array as $key => $value) {
                Revenues::where('date', date('Y-m-'.$key))->update([
                    'fb_transfer' => $value['total_fb']
                ]);
            }
        }

        if (isset($wp_array)) {
            foreach ($wp_array as $key => $value) {
                Revenues::where('date', date('Y-m-'.$key))->update([
                    'wp_transfer' => $value['total_wp']
                ]);
            }
        }

        if (isset($credit_array)) {
            foreach ($credit_array as $key => $value) {
                Revenues::where('date', date('Y-m-'.$key))->update([
                    'total_credit' => $value['total_credit']
                ]);
            }
        }

        if (isset($front_array)) {
            foreach ($front_array as $key => $value) {
                Revenues::where('date', date('Y-m-'.$key))->update([
                    'front_transfer' => $value['total_front']
                ]);
            }
        }

        if (isset($agoda_array)) {
            foreach ($agoda_array as $key => $value) {
                Revenues::where('date', date('Y-m-'.$key))->update([
                    'total_credit_agoda' => $value['total_agoda']
                ]);
            }
        }

        if (isset($ev_array)) {
            foreach ($ev_array as $key => $value) {
                Revenues::where('date', date('Y-m-'.$key))->update([
                    'total_elexa' => $value['total_ev']
                ]);
            }
        }

        if (isset($transaction_array)) {
            foreach ($transaction_array as $key => $value) {
                Revenues::where('date', date('Y-m-'.$key))->update([
                    'total_transaction' => $value['bill']
                ]);
            }
        }

        if (isset($no_type_array)) {
            foreach ($no_type_array as $key => $value) {
                Revenues::where('date', date('Y-m-'.$key))->update([
                    'total_no_type' => $value['no_type']
                ]);
            }
        }

        $daily_revenue = Revenues::whereMonth('date', date('m'))->whereYear('date', date('Y'))->select(
            DB::raw("SUM(front_cash) + SUM(front_transfer) + SUM(front_credit) as front_amount, 
            SUM(room_cash) + SUM(room_transfer) + SUM(room_credit) as room_amount, 
            SUM(fb_cash) + SUM(fb_transfer) + SUM(fb_credit) as fb_amount,
            SUM(wp_cash) + SUM(wp_transfer) + SUM(wp_credit) as wp_amount,
            SUM(room_credit) + SUM(fb_credit) + SUM(wp_credit) as credit_amount"), 'total_credit')->first();

        $total_daily_revenue = $daily_revenue->front_amount + $daily_revenue->room_amount + $daily_revenue->fb_amount + $daily_revenue->wp_amount + $daily_revenue->credit_amount + $daily_revenue->total_credit;

        $total_verified = Revenues::whereMonth('date', date('m'))->whereYear('date', date('Y'))->where('status', 1)->count();
        $total_unverified = Revenues::whereMonth('date', date('m'))->whereYear('date', date('Y'))->where('status', 0)->count();
        // dd($total_daily_revenue);
        $total_revenue_today = Revenues::whereDate('date', date('Y-m-d'))->select(
            DB::raw("
            front_cash + front_transfer + front_credit as front_amount, 
            room_cash + room_transfer + room_credit as room_amount, 
            fb_cash + fb_transfer + fb_credit as fb_amount,
            wp_cash + wp_transfer + wp_credit as wp_amount,
            room_credit + fb_credit + wp_credit as credit_amount
            "), 'total_credit_agoda', 'total_transaction', 'total_no_type', 'status')->first();
        $total_transfer = SMS_alerts::whereBetween('date', [$from, $to])->where('transfer_status', 1)->sum('amount');
        $total_transfer2 = SMS_alerts::whereBetween('date', [$from, $to])->where('transfer_status', 1)->count();
        $total_split = SMS_alerts::where('date_into', date('Y-m-d'))->where('split_status', 1)->sum('amount');
        $total_split_transaction = SMS_alerts::whereBetween('date', [$from, $to])->where('split_status', 1)->count();
        $total_not_type = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 0)->whereNull('date_into')->count();
        $total_not_type_revenue = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 0)->whereNull('date_into')->sum('amount');

        $total_agoda_outstanding = Revenues::getManualTotalAgoda();
        $total_ev_outstanding = Revenues::getManualTotalEv();

        $total_day = $total_revenue_today->front_amount + $total_revenue_today->room_amount + $total_revenue_today->fb_amount + $total_revenue_today->wp_amount
         + $total_revenue_today->credit_amount + $total_revenue_today->total_credit_agoda;
        // dd($total_guest_deposit);

        // dd($total_revenue_today->room_amount);

        ## ข้อมูลในตาราง
        $date = date('d');
        $symbol = $date == "01" ? "=" : "<=";

        $credit_revenue = Revenues::whereDate('date', date('Y-m-d'))->select('total_credit')->first();
        $credit_revenue_month = Revenues::whereDay('date', $symbol, date('d'))->whereMonth('date', date('m'))->whereYear('date', date('Y'))->select(DB::raw("SUM(total_credit) as total_credit"))->first();
        $credit_revenue_year = Revenues::whereDate('date', '<=', date('Y-m-d'))->select(DB::raw("SUM(total_credit) as total_credit"))->first();

        $total_front_revenue = Revenues::whereDate('date', date('Y-m-d'))->select('front_cash', 'front_transfer', 'front_credit')->first();
        $total_front_month = Revenues::whereDay('date', $symbol, date('d'))->whereMonth('date', date('m'))->whereYear('date', date('Y'))->select(DB::raw("SUM(front_cash) as front_cash, SUM(front_transfer) as front_transfer, SUM(front_credit) as front_credit"))->first();
        $total_front_year = Revenues::whereDate('date', '<=', date('Y-m-d'))->select(DB::raw("SUM(front_cash) as front_cash, SUM(front_transfer) as front_transfer, SUM(front_credit) as front_credit"))->first();
        $front_charge = Revenues::getManualCharge(date('Y-m-d'), date('m'), date('Y'), 6, 6);

        $total_guest_deposit = Revenues::whereDate('date', date('Y-m-d'))->select('room_cash', 'room_transfer', 'room_credit')->first();
        $total_guest_deposit_month = Revenues::whereDate('date', '>=', date('Y-m-01'))->whereDate('date', $symbol, date('Y-m-d'))
            ->select(DB::raw("SUM(room_cash) as room_cash, SUM(room_transfer) as room_transfer, SUM(room_credit) as room_credit"))->first();
        $total_guest_deposit_year = Revenues::whereDate('date', '<=', date('Y-m-d'))
            ->select(DB::raw("SUM(room_cash) as room_cash, SUM(room_transfer) as room_transfer, SUM(room_credit) as room_credit"))->first();
        $guest_deposit_charge = Revenues::getManualCharge(date('Y-m-d'), date('m'), date('Y'), 1, 1);
        // dd($total_guest_deposit_month);

        $total_fb_revenue = Revenues::whereDate('date', date('Y-m-d'))->select('fb_cash', 'fb_transfer', 'fb_credit')->first();
        $total_fb_month = Revenues::whereDay('date', $symbol, date('d'))->whereMonth('date', date('m'))->whereYear('date', date('Y'))->select(DB::raw("SUM(fb_cash) as fb_cash, SUM(fb_transfer) as fb_transfer, SUM(fb_credit) as fb_credit"))->first();
        $total_fb_year = Revenues::whereDate('date', '<=', date('Y-m-d'))->select(DB::raw("SUM(fb_cash) as fb_cash, SUM(fb_transfer) as fb_transfer, SUM(fb_credit) as fb_credit"))->first();
        $fb_charge = Revenues::getManualCharge(date('Y-m-d'), date('m'), date('Y'), 2, 2);

        $total_agoda_revenue = Revenues::whereDate('date', date('Y-m-d'))->sum('total_credit_agoda');
        $total_agoda_month = Revenues::whereDay('date', $symbol, date('d'))->whereMonth('date', date('m'))->whereYear('date', date('Y'))->sum('total_credit_agoda');
        $total_agoda_year = Revenues::whereDate('date', '<=', date('Y-m-d'))->sum('total_credit_agoda');
        $agoda_charge = Revenues::getManualAgodaCharge(date('Y-m-d'), date('m'), date('Y'), 1, 5);

        $total_wp_revenue = Revenues::whereDate('date', date('Y-m-d'))->select('wp_cash', 'wp_transfer', 'wp_credit')->first();
        $total_wp_month = Revenues::whereDay('date', $symbol, date('d'))->whereMonth('date', date('m'))->whereYear('date', date('Y'))->select(DB::raw("SUM(wp_cash) as wp_cash, SUM(wp_transfer) as wp_transfer, SUM(wp_credit) as wp_credit"))->first();
        $total_wp_year = Revenues::whereDate('date', '<=', date('Y-m-d'))->select(DB::raw("SUM(wp_cash) as wp_cash, SUM(wp_transfer) as wp_transfer, SUM(wp_credit) as wp_credit"))->first();
        $wp_charge = Revenues::getManualCharge(date('Y-m-d'), date('m'), date('Y'), 3, 3);

        $total_ev_revenue = Revenues::whereDate('date', date('Y-m-d'))->select('total_elexa')->sum('total_elexa');
        $total_ev_month = Revenues::whereDay('date', $symbol, date('d'))->whereMonth('date', date('m'))->whereYear('date', date('Y'))->select('total_elexa')->sum('total_elexa');
        $total_ev_year = Revenues::whereDate('date', '<=', date('Y-m-d'))->select('total_elexa')->sum('total_elexa');
        $ev_charge = Revenues::getManualEvCharge(date('Y-m-d'), date('m'), date('Y'), 8, 8);

        $total_credit_transaction = SMS_alerts::whereDate('date_into', date('Y-m-d'))->where('into_account', "708-226792-1")->where('status', 4)->count();
        $total_credit_transaction_month = SMS_alerts::whereMonth('date_into', date('m'))->whereYear('date_into', date('Y'))->where('into_account', "708-226792-1")->where('status', 4)->count();
        $total_credit_transaction_year = SMS_alerts::whereYear('date_into', date('Y'))->where('into_account', "708-226792-1")->where('status', 4)->count();

        $total_transfer_month = SMS_alerts::whereDay('date_into', $symbol, date('d'))->whereMonth('date_into', $symbol, date('m'))->whereYear('date_into', date('Y'))->where('transfer_status', 1)->sum('amount');
        $total_transfer_year = SMS_alerts::whereDate('date_into', '<=', date('Y-m-d'))->where('transfer_status', 1)->sum('amount');

        $total_transfer2_month = SMS_alerts::whereDay('date_into', $symbol, date('d'))->whereMonth('date_into', $symbol, date('m'))->whereYear('date_into', date('Y'))->where('transfer_status', 1)->count();
        $total_transfer2_year = SMS_alerts::whereDate('date_into', '<=', date('Y-m-d'))->where('transfer_status', 1)->count();

        $total_split_transaction_month = SMS_alerts::whereDay('date_into', $symbol, date('d'))->whereMonth('date_into', $symbol, date('m'))->whereYear('date_into', date('Y'))->where('split_status', 1)->count();
        $total_split_transaction_year = SMS_alerts::whereDate('date_into', '<=', date('Y-m-d'))->where('split_status', 1)->count();

        $total_split_month = SMS_alerts::whereDay('date_into', $symbol, date('d'))->whereMonth('date_into', $symbol, date('m'))->whereYear('date_into', date('Y'))->where('split_status', 1)->sum('amount');
        $total_split_year = SMS_alerts::whereDate('date_into', '<=', date('Y-m-d'))->where('split_status', 1)->sum('amount');

        $total_transfer_transaction = SMS_alerts::whereDate('date_into', date('Y-m-d'))->where('transfer_status', 1)->select(DB::raw("COUNT(id) as transfer_amount"))->first();
        $total_transfer_transaction_month = SMS_alerts::whereDay('date_into', $symbol, date('d'))->whereMonth('date_into', $symbol, date('m'))->whereYear('date_into', date('Y'))->where('transfer_status', 1)->select(DB::raw("COUNT(id) as transfer_amount"))->first();
        $total_transfer_transaction_year = SMS_alerts::whereDate('date_into', '<=', date('Y-m-d'))->where('transfer_status', 1)->select(DB::raw("COUNT(id) as transfer_amount"))->first();

        $total_transaction = Revenues::whereDate('date', date('Y-m-d'))->select('total_transaction')->first();
        $total_transaction_month = Revenues::whereDay('date', $symbol, date('d'))->whereMonth('date', date('m'))->whereYear('date', date('Y'))->select(DB::raw("SUM(total_transaction) as total_transaction"))->first();
        $total_transaction_year = Revenues::whereDate('date', '<=', date('Y-m-d'))->select(DB::raw("SUM(total_transaction) as total_transaction"))->first();

        $total_not_type_revenue_month = SMS_alerts::whereDay('date', $symbol, date('d'))->whereMonth('date', date('m'))->whereYear('date', date('Y'))->where('status', 0)->whereNull('date_into')->sum('amount');
        $total_not_type_revenue_year = SMS_alerts::whereDate('date', '<=', date('Y-m-d'))->where('status', 0)->whereNull('date_into')->sum('amount');

        $total_no_type_month = Revenues::whereDay('date', $symbol, date('d'))->whereMonth('date', date('m'))->whereYear('date', date('Y'))->select(DB::raw("SUM(total_no_type) as total_no_type"))->first();
        $total_no_type_year = Revenues::whereDate('date', '<=', date('Y-m-d'))->select(DB::raw("SUM(total_no_type) as total_no_type"))->first();

        // dd($ev_charge);

        $by_page = 'index';

        if (isset($_GET['byPage']) && @$_GET['byPage'] == 'department') {
            $by_page = 'index_department';
        } else {
            $by_page = 'index';
        }

        if (isset($_GET['dailyPage']) && @$_GET['dailyPage'] != 'daily') {
            $by_page = 'index_'.@$_GET['dailyPage'];
        }
        
        return view('revenue.'.$by_page, compact(
            // 'data_revenue',
            // 'data_bill',
            'total_revenue_today', 
            'total_daily_revenue',
            'total_day', 
            'total_verified',
            'total_unverified',
            'total_agoda_outstanding',
            'total_ev_outstanding',
            // 'total_room', 
            // 'total_fb', 
            // 'total_wp', 
            // 'total_credit', 
            'total_transfer', 

            'total_transfer2',
            'total_transfer2_month',
            'total_transfer2_year',

            'total_split',
            'total_split_month',
            'total_split_year',

            'total_split_transaction',
            'total_split_transaction_month',
            'total_split_transaction_year',

            'credit_revenue',
            'credit_revenue_month',
            'credit_revenue_year',

            'total_front_revenue',
            'total_front_month',
            'total_front_year',
            'front_charge',

            'total_guest_deposit',
            'total_guest_deposit_month',
            'total_guest_deposit_year',
            'guest_deposit_charge',

            'total_fb_revenue',
            'total_fb_month',
            'total_fb_year',
            'fb_charge',

            'total_agoda_revenue',
            'total_agoda_month',
            'total_agoda_year',
            'agoda_charge',

            'total_credit_transaction',
            'total_credit_transaction_month',
            'total_credit_transaction_year',

            'total_transfer_month',
            'total_transfer_year',

            'total_transfer_transaction',
            'total_transfer_transaction_month',
            'total_transfer_transaction_year',

            'total_wp_revenue',
            'total_wp_month',
            'total_wp_year',
            'wp_charge',

            'total_ev_revenue',
            'total_ev_month',
            'total_ev_year',
            'ev_charge',

            'total_not_type',

            'total_transaction',
            'total_transaction_month',
            'total_transaction_year',

            'total_no_type_month',
            'total_no_type_year',

            'total_not_type_revenue',
            'total_not_type_revenue_month',
            'total_not_type_revenue_year'
        ));
    }

    function EOM($month, $year){

        if($month=='01' || $month=='03' || $month=='05' || $month=='07' || $month=='08' || $month=='10' || $month=='12')
        {
            $EOM='31';
        } elseif($month=='02') {
            if($year%4==0) {
                $EOM='29';
            } else {
                $EOM='28';
            }
        } else {
            $EOM='30';
        }
    
        return $EOM;
    }

    function input_month($month) {
        $days = date("t", strtotime("last day of previous month"));

        $check_data = Revenues::whereMonth('date', $month)->whereYear('date', date('Y'))->first();

        if (empty($check_data)) {
            for ($i=1; $i <= 31; $i++) { 
                Revenues::create([
                    'date' => date('Y').$month.str_pad($i, 2, '0', STR_PAD_LEFT),
                    'status' => 0
                ]);
            }
        }

        // return $from = date("Y-m-d 21:00:00", strtotime("-1 day", strtotime($adate)));

        ## Update จำนวนเงินของแต่ละวัน
        $room_array = [];
        $fb_array = [];
        $wp_array = [];
        $credit_array = [];
        $agoda_array = [];
        $front_array = [];
        $credit_wp_array = [];
        $no_type_array = [];
        $transaction_array = [];
        
        for ($i=1; $i <= 31; $i++) { 
    
            if ($i == 1) {
                $adate = date('Y-'.$month.'-'.$i.' 21:00:00');
                $from = date("Y-m-d 21:00:00", strtotime("-1 day", strtotime($adate)));
                $to = date("Y-".$month."-".$i." 21:00:00");

                $check_sms = SMS_alerts::whereBetween('date', [$from, $to])->whereNull('date_into')
                ->orWhereDate('date_into', date('Y-'.$month.'-'.$i))
                ->select('sms_alert.*', DB::raw("COUNT(id) as transaction_bill, DATE(date) as date_fm, SUM(amount) as total_amount"))->groupBy('status')->get();

                $sum_bill = 0;
                foreach ($check_sms as $key => $value) {
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 1) {
                        $room_array[$i] = [
                            'total_room' => $check_sms[$key]['total_amount'],
                        ];
                    } 
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 2) {
                        $fb_array[$i] = [
                            'total_fb' => $check_sms[$key]['total_amount'],
                        ];
                    } 
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 3) {
                        $wp_array[$i] = [
                            'total_wp' => $check_sms[$key]['total_amount'],
                        ];
                    } if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 4 && $check_sms[$key]['into_account'] == "708-226792-1") {
                        $credit_array[$i] = [
                            'total_credit' => $check_sms[$key]['amount'],
                        ];
                    } if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 5) {
                        $agoda_array[$i] = [
                            'total_agoda' => $check_sms[$key]['total_amount'],
                        ];
                    } if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 6) {
                        $front_array[$i] = [
                            'total_front' => $check_sms[$key]['total_amount'],
                        ];
                    } if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 7) {
                        $credit_wp_array[$i] = [
                            'total_credit_wp' => $check_sms[$key]['total_amount'],
                        ];
                    } if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 8) {
                        $ev_array[$i] = [
                            'total_ev' => $check_sms[$key]['total_amount'],
                        ];
                    } if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 0) {
                        $no_type_array[$i] = ['no_type' => $check_sms[$key]['transaction_bill']];
                    }

                    $sum_bill += $check_sms[$key]['transaction_bill'];
                }

                $transaction_array[$i] = ['bill' => $sum_bill];
                
            } else {
                $check_sms = SMS_alerts::whereBetween('date', [date("Y-".$month."-".str_pad($i - 1, 2, '0', STR_PAD_LEFT).' 21:00:00'), date('Y-'.$month.'-'.str_pad($i, 2, '0', STR_PAD_LEFT).' 21:00:00')])->whereNull('date_into')
                ->orWhereDate('date_into', date('Y-'.$month.'-'.str_pad($i, 2, '0', STR_PAD_LEFT)))
                ->select('sms_alert.*', DB::raw("COUNT(id) as transaction_bill, DATE(date) as date_fm, SUM(amount) as total_amount"))->groupBy('status')->get();

                // dd($check_sms);
                $sum_bill = 0;
                foreach ($check_sms as $key => $value) {
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 1) {
                        $room_array[$i] = [
                            'total_room' => $check_sms[$key]['total_amount'],
                        ];
                    } 
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 2) {
                        $fb_array[$i] = [
                            'total_fb' => $check_sms[$key]['total_amount'],
                        ];
                    } 
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 3) {
                        $wp_array[$i] = [
                            'total_wp' => $check_sms[$key]['total_amount'],
                        ];
                    } if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 4) {
                        $credit_array[$i] = [
                            'total_credit' => $check_sms[$key]['amount'],
                        ];
                    } if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 5) {
                        $agoda_array[$i] = [
                            'total_agoda' => $check_sms[$key]['total_amount'],
                        ];
                    } if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 6) {
                        $front_array[$i] = [
                            'total_front' => $check_sms[$key]['total_amount'],
                        ];
                    } if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 7) {
                        $credit_wp_array[$i] = [
                            'total_credit_wp' => $check_sms[$key]['total_amount'],
                        ];
                    } if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 8) {
                        $ev_array[$i] = [
                            'total_ev' => $check_sms[$key]['total_amount'],
                        ];
                    } if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 0) {
                        $no_type_array[$i] = ['no_type' => $check_sms[$key]['transaction_bill']];
                    }

                    $sum_bill += $check_sms[$key]['transaction_bill'];
                    
                }
                $transaction_array[$i] = ['bill' => $sum_bill];
            }
            
        }

        // $check_sms = SMS_alerts::whereBetween('date', [date("Y-m-".str_pad(5 - 1, 2, '0', STR_PAD_LEFT).' 21:00:00'), date('Y-m-'.str_pad(5, 2, '0', STR_PAD_LEFT).' 21:00:00')])
        //         ->select('sms_alert.*', DB::raw("COUNT(id) as transaction_bill, DATE(date) as date_fm, SUM(amount) as total_amount"))->groupBy('status')->get();

        // dd($room_array);

        $room_transfer = 0;
        $fb_transfer = 0;
        $wp_transfer = 0;
        $room_credit = 0;

        if (isset($room_array)) {
            foreach ($room_array as $key => $value) {
                Revenues::where('date', date('Y-'.$month.'-'.$key))->update([
                    'room_transfer' => $value['total_room']
                ]);
            }
        }

        if (isset($fb_array)) {
            foreach ($fb_array as $key => $value) {
                Revenues::where('date', date('Y-'.$month.'-'.$key))->update([
                    'fb_transfer' => $value['total_fb']
                ]);
            }
        }

        if (isset($wp_array)) {
            foreach ($wp_array as $key => $value) {
                Revenues::where('date', date('Y-'.$month.'-'.$key))->update([
                    'wp_transfer' => $value['total_wp']
                ]);
            }
        }

        if (isset($credit_array)) {
            foreach ($credit_array as $key => $value) {
                Revenues::where('date', date('Y-'.$month.'-'.$key))->update([
                    'total_credit' => $value['total_credit']
                ]);
            }
        }

        if (isset($front_array)) {
            foreach ($front_array as $key => $value) {
                Revenues::where('date', date('Y-'.$month.'-'.$key))->update([
                    'front_transfer' => $value['total_front']
                ]);
            }
        }

        if (isset($agoda_array)) {
            foreach ($agoda_array as $key => $value) {
                Revenues::where('date', date('Y-'.$month.'-'.$key))->update([
                    'total_credit_agoda' => $value['total_agoda']
                ]);
            }
        }

        if (isset($ev_array)) {
            foreach ($ev_array as $key => $value) {
                Revenues::where('date', date('Y-m-'.$key))->update([
                    'total_elexa' => $value['total_ev']
                ]);
            }
        }

        if (isset($transaction_array)) {
            foreach ($transaction_array as $key => $value) {
                Revenues::where('date', date('Y-'.$month.'-'.$key))->update([
                    'total_transaction' => $value['bill']
                ]);
            }
        }

        if (isset($no_type_array)) {
            foreach ($no_type_array as $key => $value) {
                Revenues::where('date', date('Y-'.$month.'-'.$key))->update([
                    'total_no_type' => $value['no_type']
                ]);
            }
        }

        return back();
    }

    public function search_calendar(Request $request)
    {
        if ($request->day == 0) {
            return $this->search_calendar_all($request);
        } else {
        $adate= date("Y-".$request->month."-".$request->day." 21:00:00");
        $from = date("Y-m-d 21:00:00", strtotime("-1 day",strtotime($adate)));
        $to = date("Y-".$request->month."-".$request->day." 21:00:00");

        $datetime = date("Y-".$request->month."-d");
        $last_day = $this->EOM($request->month, $request->year);
        $last_day2 = $this->EOM(date("m", strtotime("-1 months", strtotime($datetime))), $request->year);

        $check_data = Revenues::whereMonth('date', $request->month)->whereYear('date', $request->year)->first();

        if (empty($check_data)) {
            $days = $last_day;

            for ($i=1; $i <= $days; $i++) { 
                Revenues::create([
                    'date' => $request->year.$request->month.str_pad($i, 2, '0', STR_PAD_LEFT),
                    'status' => 0
                ]);
            }
        }

        ## Update จำนวนเงินของแต่ละวัน
        $room_array = [];
        $fb_array = [];
        $wp_array = [];
        $credit_array = [];
        $agoda_array = [];
        $front_array = [];
        $credit_wp_array = [];
        $no_type_array = [];
        $transaction_array = [];
        
        for ($i=1; $i <= 31; $i++) { 
            if ($i == 1) {
                // dd(date("Y-m-".$last_day2, strtotime("-1 months", strtotime($datetime))));
                $check_sms = SMS_alerts::whereBetween('date', [date("Y-m-".$last_day2, strtotime("-1 months", strtotime($datetime))).' 21:00:00', date('Y-'.$request->month.'-01 21:00:00')])->whereNull('date_into')
                ->orWhereDate('date_into', date("Y-".$request->month."-01"))
                ->select('sms_alert.*', DB::raw("COUNT(id) as transaction_bill, DATE(date) as date_fm, SUM(amount) as total_amount"))->groupBy('status')->get();

                $sum_bill = 0;
                foreach ($check_sms as $key => $value) {
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 1) {
                        $room_array[$i] = [
                            'total_room' => $check_sms[$key]['total_amount'],
                        ];
                    } 
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 2) {
                        $fb_array[$i] = [
                            'total_fb' => $check_sms[$key]['total_amount'],
                        ];
                    } 
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 3) {
                        $wp_array[$i] = [
                            'total_wp' => $check_sms[$key]['total_amount'],
                        ];
                    } if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 4 && $check_sms[$key]['into_account'] == "708-226792-1") {
                        $credit_array[$i] = [
                            'total_credit' => $check_sms[$key]['amount'],
                        ];
                    } if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 5) {
                        $agoda_array[$i] = [
                            'total_agoda' => $check_sms[$key]['total_amount'],
                        ];
                    } if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 6) {
                        $front_array[$i] = [
                            'total_front' => $check_sms[$key]['total_amount'],
                        ];
                    } if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 7) {
                        $credit_wp_array[$i] = [
                            'total_credit_wp' => $check_sms[$key]['total_amount'],
                        ];
                    } if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 8) {
                        $ev_array[$i] = [
                            'total_ev' => $check_sms[$key]['total_amount'],
                        ];
                    } if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 0) {
                        $no_type_array[$i] = ['no_type' => $check_sms[$key]['transaction_bill']];
                    }

                    $sum_bill += $check_sms[$key]['transaction_bill'];
                }

                $transaction_array[$i] = ['bill' => $sum_bill];
                
            } else {
                $check_sms = SMS_alerts::whereBetween('date', [date("Y-".$request->month."-".str_pad($i - 1, 2, '0', STR_PAD_LEFT).' 21:00:00'), date('Y-'.$request->month.'-'.str_pad($i, 2, '0', STR_PAD_LEFT).' 21:00:00')])->whereNull('date_into')
                ->orWhereDate('date_into', date('Y-'.$request->month.'-'.str_pad($i, 2, '0', STR_PAD_LEFT)))
                ->select('sms_alert.*', DB::raw("COUNT(id) as transaction_bill, DATE(date) as date_fm, SUM(amount) as total_amount"))->groupBy('status')->get();

                $sum_bill = 0;
                foreach ($check_sms as $key => $value) {
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 1) {
                        $room_array[$i] = [
                            'total_room' => $check_sms[$key]['total_amount'],
                        ];
                    } 
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 2) {
                        $fb_array[$i] = [
                            'total_fb' => $check_sms[$key]['total_amount'],
                        ];
                    } 
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 3) {
                        $wp_array[$i] = [
                            'total_wp' => $check_sms[$key]['total_amount'],
                        ];
                    } if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 4) {
                        $credit_array[$i] = [
                            'total_credit' => $check_sms[$key]['amount'],
                        ];
                    } if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 5) {
                        $agoda_array[$i] = [
                            'total_agoda' => $check_sms[$key]['total_amount'],
                        ];
                    } if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 6) {
                        $front_array[$i] = [
                            'total_front' => $check_sms[$key]['total_amount'],
                        ];
                    } if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 7) {
                        $credit_wp_array[$i] = [
                            'total_credit_wp' => $check_sms[$key]['total_amount'],
                        ];
                    } if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 8) {
                        $ev_array[$i] = [
                            'total_ev' => $check_sms[$key]['total_amount'],
                        ];
                    } if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 0) {
                        $no_type_array[$i] = ['no_type' => $check_sms[$key]['transaction_bill']];
                    }

                    $sum_bill += $check_sms[$key]['transaction_bill'];
                    
                }
                $transaction_array[$i] = ['bill' => $sum_bill];
            }
            
        }

        // dd($front_array);
        // dd($agoda_array);


        $room_transfer = 0;
        $fb_transfer = 0;
        $wp_transfer = 0;
        $room_credit = 0;

        if (isset($room_array)) {
            foreach ($room_array as $key => $value) {
                Revenues::where('date', date('Y-'.$request->month.'-'.$key))->update([
                    'room_transfer' => $value['total_room']
                ]);
            }
        }

        if (isset($fb_array)) {
            foreach ($fb_array as $key => $value) {
                Revenues::where('date', date('Y-'.$request->month.'-'.$key))->update([
                    'fb_transfer' => $value['total_fb']
                ]);
            }
        }

        if (isset($wp_array)) {
            foreach ($wp_array as $key => $value) {
                Revenues::where('date', date('Y-'.$request->month.'-'.$key))->update([
                    'wp_transfer' => $value['total_wp']
                ]);
            }
        }

        if (isset($credit_array)) {
            foreach ($credit_array as $key => $value) {
                Revenues::where('date', date('Y-'.$request->month.'-'.$key))->update([
                    'total_credit' => $value['total_credit']
                ]);
            }
        }

        if (isset($front_array)) {
            foreach ($front_array as $key => $value) {
                Revenues::where('date', date('Y-'.$request->month.'-'.$key))->update([
                    'front_transfer' => $value['total_front']
                ]);
            }
        }

        if (isset($agoda_array)) {
            foreach ($agoda_array as $key => $value) {
                // dd($value['total_agoda']);
                Revenues::where('date', date('Y-'.$request->month.'-'.$key))->update([
                    'total_credit_agoda' => $value['total_agoda']
                ]);
            }
        }

        if (isset($ev_array)) {
            foreach ($ev_array as $key => $value) {
                Revenues::where('date', date('Y-'.$request->month.'-'.$key))->update([
                    'total_elexa' => $value['total_ev']
                ]);
            }
        }

        if (isset($transaction_array)) {
            foreach ($transaction_array as $key => $value) {
                Revenues::where('date', date('Y-'.$request->month.'-'.$key))->update([
                    'total_transaction' => $value['bill']
                ]);
            }
        }

        if (isset($no_type_array)) {
            foreach ($no_type_array as $key => $value) {
                Revenues::where('date', date('Y-'.$request->month.'-'.$key))->update([
                    'total_no_type' => $value['no_type']
                ]);
            }
        }

        $day_now = $request->day;
        $symbol = $day_now == "01" ? "=" : "<=";

        $daily_revenue = Revenues::whereMonth('date', $request->month)->whereYear('date', $request->year)->select(
            DB::raw("SUM(front_cash) + SUM(front_transfer) + SUM(front_credit) as front_amount, 
            SUM(room_cash) + SUM(room_transfer) + SUM(room_credit) as room_amount, 
            SUM(fb_cash) + SUM(fb_transfer) + SUM(fb_credit) as fb_amount,
            SUM(wp_cash) + SUM(wp_transfer) + SUM(wp_credit) as wp_amount,
            SUM(room_credit) + SUM(fb_credit) + SUM(wp_credit) as credit_amount"), 'total_credit')->first();

        $total_daily_revenue = $daily_revenue->front_amount + $daily_revenue->room_amount + $daily_revenue->fb_amount + $daily_revenue->wp_amount + $daily_revenue->credit_amount + $daily_revenue->total_credit;

        $total_verified = Revenues::whereMonth('date', $request->month)->whereYear('date', $request->year)->where('status', 1)->count();
        $total_unverified = Revenues::whereMonth('date', $request->month)->whereYear('date', $request->year)->where('status', 0)->count();

        $total_revenue_today = Revenues::whereDay('date', $day_now)->whereMonth('date', $request->month)->whereYear('date', $request->year)->select(
            DB::raw("
            (front_cash + front_transfer + front_credit) as front_amount, 
            (room_cash + room_transfer + room_credit) as room_amount, 
            (fb_cash + fb_transfer + fb_credit) as fb_amount,
            (wp_cash + wp_transfer + wp_credit) as wp_amount,
            (room_credit + fb_credit + wp_credit) as credit_amount
            "), 'total_credit_agoda', 'total_transaction', 'total_no_type', 'status')->first();

        // dd($total_revenue_today);
        $total_transfer = SMS_alerts::whereBetween('date', [$from, $to])->where('transfer_status', 1)->sum('amount');
        $total_transfer2 = SMS_alerts::whereBetween('date_into', [$from, $to])->where('transfer_status', 1)->count();
        $total_split = SMS_alerts::where('date_into', [$from, $to])->where('split_status', 1)->sum('amount');
        $total_split_transaction = SMS_alerts::whereBetween('date', [$from, $to])->where('split_status', 1)->count();
        $total_not_type = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 0)->whereNull('date_into')->count();
        $total_not_type_revenue = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 0)->whereNull('date_into')->sum('amount');

        $total_agoda_outstanding = Revenues::getManualTotalAgoda();
        $total_ev_outstanding = Revenues::getManualTotalEv();

        $total_day = $total_revenue_today->front_amount + $total_revenue_today->room_amount + $total_revenue_today->fb_amount
         + $total_revenue_today->wp_amount + $total_revenue_today->credit_amount + $total_revenue_today->total_credit_agoda;
        // dd($total_revenue_today);

        ## ข้อมูลในตาราง

        $credit_revenue = Revenues::whereDate('date', date($request->year.'-'.$request->month.'-'.$day_now))->first();
        $credit_revenue_month = Revenues::whereDay('date', $symbol, $day_now)->whereMonth('date', $request->month)->whereYear('date', $request->year)->select(DB::raw("SUM(total_credit) as total_credit"))->first();
        $credit_revenue_year = Revenues::whereDate('date', '<=', date($request->year.'-'.$request->month.'-'.$request->day))->select(DB::raw("SUM(total_credit) as total_credit"))->first();

        $total_front_revenue = Revenues::whereDate('date', date($request->year.'-'.$request->month.'-'.$day_now))->select('front_cash', 'front_transfer', 'front_credit')->first();
        $total_front_month = Revenues::whereDay('date', $symbol, $day_now)->whereMonth('date', $request->month)->whereYear('date', $request->year)->select(DB::raw("SUM(front_cash) as front_cash, SUM(front_transfer) as front_transfer, SUM(front_credit) as front_credit"))->first();
        $total_front_year = Revenues::whereDate('date', '<=', date($request->year.'-'.$request->month.'-'.$request->day))->select(DB::raw("SUM(front_cash) as front_cash, SUM(front_transfer) as front_transfer, SUM(front_credit) as front_credit"))->first();
        $front_charge = Revenues::getManualCharge(date($request->year.'-'.$request->month.'-'.$request->day), $request->month, $request->year, 6, 6);

        $total_guest_deposit = Revenues::whereDate('date', date($request->year.'-'.$request->month.'-'.$day_now))->select('room_cash', 'room_transfer', 'room_credit')->first();
        $total_guest_deposit_month = Revenues::whereDay('date', $symbol, $day_now)->whereMonth('date', $request->month)->whereYear('date', $request->year)->select(DB::raw("SUM(room_cash) as room_cash, SUM(room_transfer) as room_transfer, SUM(room_credit) as room_credit"))->first();
        $total_guest_deposit_year = Revenues::whereDate('date', '<=', date($request->year.'-'.$request->month.'-'.$request->day))->select(DB::raw("SUM(room_cash) as room_cash, SUM(room_transfer) as room_transfer, SUM(room_credit) as room_credit"))->first();
        $guest_deposit_charge = Revenues::getManualCharge(date($request->year.'-'.$request->month.'-'.$request->day), $request->month, $request->year, 1, 1);

        // dd($total_guest_deposit_month);

        $total_fb_revenue = Revenues::whereDay('date', $day_now)->whereMonth('date', $request->month)->whereYear('date', $request->year)->select('fb_cash', 'fb_transfer', 'fb_credit')->first();
        $total_fb_month = Revenues::whereDay('date', $symbol, $day_now)->whereMonth('date', $request->month)->whereYear('date', $request->year)->select(DB::raw("SUM(fb_cash) as fb_cash, SUM(fb_transfer) as fb_transfer, SUM(fb_credit) as fb_credit"))->first();
        $total_fb_year = Revenues::whereDate('date', '<=', date($request->year.'-'.$request->month.'-'.$request->day))->select(DB::raw("SUM(fb_cash) as fb_cash, SUM(fb_transfer) as fb_transfer, SUM(fb_credit) as fb_credit"))->first();
        $fb_charge = Revenues::getManualCharge(date($request->year.'-'.$request->month.'-'.$request->day), $request->month, $request->year, 2, 2);

        $total_agoda_revenue = Revenues::whereDay('date', $day_now)->whereMonth('date', $request->month)->whereYear('date', $request->year)->sum('total_credit_agoda');
        $total_agoda_month = Revenues::whereDay('date', $symbol, $day_now)->whereMonth('date', date('m'))->whereYear('date', date('Y'))->sum('total_credit_agoda');
        $total_agoda_year = Revenues::whereDate('date', '<=', date($request->year.'-'.$request->month.'-'.$request->day))->sum('total_credit_agoda');
        $agoda_charge = Revenues::getManualAgodaCharge(date($request->year.'-'.$request->month.'-'.$request->day), $request->month, $request->year, 1, 5);

        $total_wp_revenue = Revenues::whereDay('date', $day_now)->whereMonth('date', $request->month)->whereYear('date', $request->year)->select('wp_cash', 'wp_transfer', 'wp_credit')->first();
        $total_wp_month = Revenues::whereDay('date', $symbol, $day_now)->whereMonth('date', $request->month)->whereYear('date', $request->year)->select(DB::raw("SUM(wp_cash) as wp_cash, SUM(wp_transfer) as wp_transfer, SUM(wp_credit) as wp_credit"))->first();
        $total_wp_year = Revenues::whereDate('date', '<=', date($request->year.'-'.$request->month.'-'.$request->day))->select(DB::raw("SUM(wp_cash) as wp_cash, SUM(wp_transfer) as wp_transfer, SUM(wp_credit) as wp_credit"))->first();
        $wp_charge = Revenues::getManualCharge(date($request->year.'-'.$request->month.'-'.$request->day), $request->month, $request->year, 3, 3);

        $total_credit_transaction = SMS_alerts::whereDate('date_into', date($request->year.'-'.$request->month.'-'.$request->day))->where('into_account', "708-226792-1")->where('status', 4)->count();
        $total_credit_transaction_month = SMS_alerts::whereMonth('date_into', $request->month)->whereYear('date_into', $request->year)->where('into_account', "708-226792-1")->where('status', 4)->count();
        $total_credit_transaction_year = SMS_alerts::whereYear('date_into', $request->year)->where('into_account', "708-226792-1")->where('status', 4)->count();

        $total_transfer_month = SMS_alerts::whereDay('date_into', $symbol, $day_now)->whereMonth('date_into', $request->month)->whereYear('date_into', $request->year)->where('transfer_status', 1)->sum('amount');
        $total_transfer_year = SMS_alerts::whereDate('date_into', '<=', date($request->year.'-'.$request->month.'-'.$request->day))->where('transfer_status', 1)->sum('amount');

        $total_transfer2_month = SMS_alerts::whereDay('date_into', $symbol, $day_now)->whereMonth('date_into', $request->month)->whereYear('date_into', $request->year)->where('transfer_status', 1)->count();
        $total_transfer2_year = SMS_alerts::whereDate('date_into', '<=', date($request->year.'-'.$request->month.'-'.$request->day))->where('transfer_status', 1)->count();

        $total_split_transaction_month = SMS_alerts::whereDay('date_into', $symbol, $day_now)->whereMonth('date_into', $request->month)->whereYear('date_into', $request->year)->where('split_status', 1)->count();
        $total_split_transaction_year = SMS_alerts::whereDate('date_into', '<=', date($request->year.'-'.$request->month.'-'.$request->day))->where('split_status', 1)->count();

        $total_split_month = SMS_alerts::whereDay('date_into', $symbol, $day_now)->whereMonth('date_into', $request->month)->whereYear('date_into', $request->year)->where('split_status', 1)->sum('amount');
        $total_split_year = SMS_alerts::whereDate('date_into', '<=', date($request->year.'-'.$request->month.'-'.$request->day))->where('split_status', 1)->sum('amount');

        $total_transfer_transaction = SMS_alerts::whereDate('date_into', date($request->year.'-'.$request->month.'-'.$request->day))->where('transfer_status', 1)->select(DB::raw("COUNT(id) as transfer_amount"))->first();
        $total_transfer_transaction_month = SMS_alerts::whereDay('date_into', $symbol, $day_now)->whereMonth('date_into', $request->month)->whereYear('date_into', $request->year)->where('transfer_status', 1)->select(DB::raw("COUNT(id) as transfer_amount"))->first();
        $total_transfer_transaction_year = SMS_alerts::whereDate('date_into', '<=', date($request->year.'-'.$request->month.'-'.$request->day))->whereMonth('date_into', '<=', $request->month)->whereYear('date', $request->year)->where('transfer_status', 1)->select(DB::raw("COUNT(id) as transfer_amount"))->first();

        $total_transaction = Revenues::whereDay('date', $day_now)->whereMonth('date', $request->month)->whereYear('date', $request->year)->select('total_transaction')->first();
        $total_transaction_month = Revenues::whereDay('date', $symbol, $day_now)->whereMonth('date', $request->month)->whereYear('date', $request->year)->select(DB::raw("SUM(total_transaction) as total_transaction"))->first();
        $total_transaction_year = Revenues::whereDate('date', '<=', date($request->year.'-'.$request->month.'-'.$request->day))->select(DB::raw("SUM(total_transaction) as total_transaction"))->first();

        $total_not_type_revenue_month = SMS_alerts::whereDay('date', $day_now)->whereMonth('date', $request->month)->whereYear('date', $request->year)->where('status', 0)->whereNull('date_into')->sum('amount');
        $total_not_type_revenue_year = SMS_alerts::whereDate('date', '<=', date($request->year.'-'.$request->month.'-'.$request->day))->where('status', 0)->whereNull('date_into')->sum('amount');

        $total_no_type = Revenues::whereDay('date', $day_now)->whereMonth('date', $request->month)->whereYear('date', $request->year)->select('total_no_type')->first();
        $total_no_type_month = Revenues::whereDay('date', $symbol, $day_now)->whereMonth('date', $request->month)->whereYear('date', $request->year)->select(DB::raw("SUM(total_no_type) as total_no_type"))->first();
        $total_no_type_year = Revenues::whereDate('date', '<=', date($request->year.'-'.$request->month.'-'.$request->day))->select(DB::raw("SUM(total_no_type) as total_no_type"))->first();

        $day = $request->day;
        $month = $request->month;
        $year = $request->year;

        $total_ev_revenue = Revenues::whereDay('date', $day_now)->whereMonth('date', $request->month)->whereYear('date', $request->year)->select('total_elexa')->sum('total_elexa');
        $total_ev_month = Revenues::whereDay('date', $symbol, $day_now)->whereMonth('date', date('m'))->whereYear('date', date('Y'))->select('total_elexa')->sum('total_elexa');
        $total_ev_year = Revenues::whereDate('date', '<=', date($request->year.'-'.$request->month.'-'.$request->day))->select('total_elexa')->sum('total_elexa');
        $ev_charge = Revenues::getManualEvCharge(date($request->year.'-'.$request->month.'-'.$request->day), $request->month, $request->year, 8, 8);

        // dd($ev_charge);
        // dd($front_charge);

        $by_page = 'index';

        if (isset($_GET['byPage']) && @$_GET['byPage'] == 'department') {
            $by_page = 'index_department';
        } else {
            $by_page = 'index';
        }

        if (isset($_GET['dailyPage']) && @$_GET['dailyPage'] != 'daily') {
            $by_page = 'index_'.@$_GET['dailyPage'];
        }

        return view('revenue.'.$by_page, compact(
            // 'data_revenue',
            // 'data_bill',
            'total_daily_revenue',
            'total_revenue_today', 
            'total_day', 
            'total_verified', 
            'total_unverified', 
            'total_agoda_outstanding',
            'total_ev_outstanding',
            // 'total_wp', 
            // 'total_credit', 
            'total_transfer', 

            'total_transfer2',
            'total_transfer2_month',
            'total_transfer2_year',

            'total_split',
            'total_split_month',
            'total_split_year',

            'total_split_transaction',
            'total_split_transaction_month',
            'total_split_transaction_year',

            'credit_revenue',
            'credit_revenue_month',
            'credit_revenue_year',

            'total_front_revenue',
            'total_front_month',
            'total_front_year',
            'front_charge',

            'total_guest_deposit',
            'total_guest_deposit_month',
            'total_guest_deposit_year',
            'guest_deposit_charge',

            'total_fb_revenue',
            'total_fb_month',
            'total_fb_year',
            'fb_charge',

            'total_agoda_revenue',
            'total_agoda_month',
            'total_agoda_year',
            'agoda_charge',

            'total_credit_transaction',
            'total_credit_transaction_month',
            'total_credit_transaction_year',

            'total_transfer_month',
            'total_transfer_year',

            'total_transfer_transaction',
            'total_transfer_transaction_month',
            'total_transfer_transaction_year',

            'total_wp_revenue',
            'total_wp_month',
            'total_wp_year',
            'wp_charge',

            'total_not_type',

            'total_transaction',
            'total_transaction_month',
            'total_transaction_year',

            'total_no_type',
            'total_no_type_month',
            'total_no_type_year',

            'total_not_type_revenue',
            'total_not_type_revenue_month',
            'total_not_type_revenue_year',

            'total_ev_revenue',
            'total_ev_month',
            'total_ev_year',
            'ev_charge',

            'day', 'month', 'year'));
        }
    }

    public function search_calendar_all(Request $request) {

        // $adate= date('Y-m 21:00:00');
        $from = date("Y-m-d 21:00:00", strtotime("-1 day"));
        $to = date('Y-m-d 21:00:00');

        $daily_revenue = Revenues::whereMonth('date', $request->month)->whereYear('date', $request->year)->select(
            DB::raw("
            SUM(front_cash) + SUM(front_transfer) + SUM(front_credit) as front_amount, 
            SUM(room_cash) + SUM(room_transfer) + SUM(room_credit) as room_amount, 
            SUM(fb_cash) + SUM(fb_transfer) + SUM(fb_credit) as fb_amount,
            SUM(wp_cash) + SUM(wp_transfer) + SUM(wp_credit) as wp_amount,
            SUM(room_credit) + SUM(fb_credit) + SUM(wp_credit) as credit_amount"), 'total_credit')->first();

        $total_daily_revenue = $daily_revenue->front_amount + $daily_revenue->room_amount + $daily_revenue->fb_amount + $daily_revenue->wp_amount + $daily_revenue->credit_amount + $daily_revenue->total_credit;

        $total_verified = Revenues::whereMonth('date', $request->month)->whereYear('date', $request->year)->where('status', 1)->count();
        $total_unverified = Revenues::whereMonth('date', $request->month)->whereYear('date', $request->year)->where('status', 0)->count();

        $total_revenue_today = Revenues::whereMonth('date', $request->month)->whereYear('date', $request->year)->select(
            DB::raw("
            SUM(front_cash) + SUM(front_transfer) + SUM(front_credit) as front_amount, 
            SUM(room_cash) + SUM(room_transfer) + SUM(room_credit) as room_amount, 
            SUM(fb_cash) + SUM(fb_transfer) + SUM(fb_credit) as fb_amount,
            SUM(wp_cash) + SUM(wp_transfer) + SUM(wp_credit) as wp_amount,
            SUM(room_credit) + SUM(fb_credit) + SUM(wp_credit) as credit_amount,
            SUM(total_transaction) as total_transaction
            "), 'total_credit_agoda', 'total_no_type', 'status')->first();
        $total_transfer = SMS_alerts::whereBetween('date', [$from, $to])->where('transfer_status', 1)->sum('amount');
        $total_transfer2 = SMS_alerts::whereBetween('date_into', [$from, $to])->where('transfer_status', 1)->count();
        $total_split = SMS_alerts::where('date_into', [$from, $to])->where('split_status', 1)->sum('amount');
        $total_split_transaction = SMS_alerts::whereBetween('date', [$from, $to])->where('split_status', 1)->count();
        $total_not_type = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 0)->whereNull('date_into')->count();
        $total_not_type_revenue = SMS_alerts::whereMonth('date', $request->month)->whereYear('date', $request->year)->where('status', 0)->whereNull('date_into')->sum('amount');

        $total_agoda_outstanding = Revenues::getManualTotalAgoda();
        $total_ev_outstanding = Revenues::getManualTotalEv();

        $total_day = $total_revenue_today->front_amount + $total_revenue_today->room_amount + $total_revenue_today->fb_amount + $total_revenue_today->wp_amount + $total_revenue_today->credit_amount + $total_revenue_today->total_credit_agoda;

        ## ข้อมูลในตาราง
        $day_now = $request->day;
        $symbol = $day_now == "01" ? "=" : "<=";

        $credit_revenue = Revenues::whereDay('date', date('d'))->whereMonth('date', $request->month)->whereYear('date', $request->year)->select('total_credit')->first();
        $credit_revenue_month = Revenues::whereMonth('date', $request->month)->whereYear('date', $request->year)->select(DB::raw("SUM(total_credit) as total_credit"))->first();
        $credit_revenue_year = Revenues::whereYear('date', $request->year)->select(DB::raw("SUM(total_credit) as total_credit"))->first();

        $total_front_revenue = Revenues::whereDay('date', date('d'))->whereMonth('date', $request->month)->whereYear('date', $request->year)->select('front_cash', 'front_transfer', 'front_credit')->first();
        $total_front_month = Revenues::whereMonth('date', $request->month)->whereYear('date', $request->year)->select(DB::raw("SUM(front_cash) as front_cash, SUM(front_transfer) as front_transfer, SUM(front_credit) as front_credit"))->first();
        $total_front_year = Revenues::whereYear('date', $request->year)->select(DB::raw("SUM(front_cash) as front_cash, SUM(front_transfer) as front_transfer, SUM(front_credit) as front_credit"))->first();
        $front_charge = Revenues::getManualCharge(date($request->year.'-'.$request->month.'-'.$request->day), $request->month, $request->year, 6, 6);

        $total_guest_deposit = Revenues::whereDay('date', date('d'))->whereMonth('date', $request->month)->whereYear('date', $request->year)->select('room_cash', 'room_transfer', 'room_credit')->first();
        $total_guest_deposit_month = Revenues::whereMonth('date', $request->month)->whereYear('date', $request->year)->select(DB::raw("SUM(room_cash) as room_cash, SUM(room_transfer) as room_transfer, SUM(room_credit) as room_credit"))->first();
        $total_guest_deposit_year = Revenues::whereYear('date', $request->year)->select(DB::raw("SUM(room_cash) as room_cash, SUM(room_transfer) as room_transfer, SUM(room_credit) as room_credit"))->first();
        $guest_deposit_charge = Revenues::getManualCharge(date($request->year.'-'.$request->month.'-'.$request->day), $request->month, $request->year, 1, 1);

        $total_fb_revenue = Revenues::whereDay('date', date('d'))->whereMonth('date', $request->month)->whereYear('date', $request->year)->select('fb_cash', 'fb_transfer', 'fb_credit')->first();
        $total_fb_month = Revenues::whereMonth('date', $request->month)->whereYear('date', $request->year)->select(DB::raw("SUM(fb_cash) as fb_cash, SUM(fb_transfer) as fb_transfer, SUM(fb_credit) as fb_credit"))->first();
        $total_fb_year = Revenues::whereYear('date', $request->year)->select(DB::raw("SUM(fb_cash) as fb_cash, SUM(fb_transfer) as fb_transfer, SUM(fb_credit) as fb_credit"))->first();
        $fb_charge = Revenues::getManualCharge(date($request->year.'-'.$request->month.'-'.$request->day), $request->month, $request->year, 2, 2);

        $total_agoda_revenue = Revenues::whereDay('date', date('d'))->whereMonth('date', $request->month)->whereYear('date', $request->year)->sum('total_credit_agoda');
        $total_agoda_month = Revenues::whereMonth('date', $request->month)->whereYear('date', $request->year)->sum('total_credit_agoda');
        $total_agoda_year = Revenues::whereYear('date', $request->year)->sum('total_credit_agoda');
        $agoda_charge = Revenues::getManualAgodaCharge(date($request->year.'-'.$request->month.'-'.$request->day), $request->month, $request->year, 1, 5);

        $total_credit_transaction = SMS_alerts::whereMonth('date', $request->month)->whereYear('date', $request->year)->where('into_account', "708-226792-1")->where('status', 4)->count();
        $total_credit_transaction_month = SMS_alerts::whereMonth('date_into', $request->month)->whereYear('date_into', $request->year)->where('into_account', "708-226792-1")->where('status', 4)->count();
        $total_credit_transaction_year = SMS_alerts::whereYear('date_into', $request->year)->where('into_account', "708-226792-1")->where('status', 4)->count();

        $total_wp_revenue = Revenues::whereDay('date', date('d'))->whereMonth('date', $request->month)->whereYear('date', $request->year)->select('wp_cash', 'wp_transfer', 'wp_credit')->first();
        $total_wp_month = Revenues::whereMonth('date', $request->month)->whereYear('date', $request->year)->select(DB::raw("SUM(wp_cash) as wp_cash, SUM(wp_transfer) as wp_transfer, SUM(wp_credit) as wp_credit"))->first();
        $total_wp_year = Revenues::whereYear('date', $request->year)->select(DB::raw("SUM(wp_cash) as wp_cash, SUM(wp_transfer) as wp_transfer, SUM(wp_credit) as wp_credit"))->first();
        $wp_charge = Revenues::getManualCharge(date($request->year.'-'.$request->month.'-'.$request->day), $request->month, $request->year, 3, 3);

        $total_transfer_month = SMS_alerts::whereMonth('date_into', $request->month)->whereYear('date_into', $request->year)->where('transfer_status', 1)->sum('amount');
        $total_transfer_year = SMS_alerts::whereYear('date', $request->year)->where('transfer_status', 1)->sum('amount');

        $total_transfer2_month = SMS_alerts::whereMonth('date_into', $request->month)->whereYear('date_into', $request->year)->where('transfer_status', 1)->count();
        $total_transfer2_year = SMS_alerts::whereYear('date', $request->year)->where('transfer_status', 1)->count();

        $total_split_transaction_month = SMS_alerts::whereMonth('date_into', $request->month)->whereYear('date_into', $request->year)->where('split_status', 1)->count();
        $total_split_transaction_year = SMS_alerts::whereYear('date', $request->year)->where('split_status', 1)->count();

        $total_split_month = SMS_alerts::whereMonth('date_into', $request->month)->whereYear('date_into', $request->year)->where('split_status', 1)->sum('amount');
        $total_split_year = SMS_alerts::whereYear('date', $request->year)->where('split_status', 1)->sum('amount');
        
        $total_transfer_transaction = SMS_alerts::whereDate('date_into', date($request->year.'-'.$request->month.'-d'))->where('transfer_status', 1)->select(DB::raw("COUNT(id) as transfer_amount"))->first();
        $total_transfer_transaction_month = SMS_alerts::whereMonth('date_into', $request->month)->whereYear('date_into', $request->year)->where('transfer_status', 1)->select(DB::raw("COUNT(id) as transfer_amount"))->first();
        $total_transfer_transaction_year = SMS_alerts::whereYear('date', $request->year)->where('transfer_status', 1)->select(DB::raw("COUNT(id) as transfer_amount"))->first();

        $total_transaction = Revenues::whereDay('date', date('d'))->whereMonth('date', $request->month)->whereYear('date', $request->year)->select('total_transaction')->first();
        $total_transaction_month = Revenues::whereMonth('date', $request->month)->whereYear('date', $request->year)->select(DB::raw("SUM(total_transaction) as total_transaction"))->first();
        $total_transaction_year = Revenues::whereYear('date', $request->year)->select(DB::raw("SUM(total_transaction) as total_transaction"))->first();

        $total_not_type_revenue_month = SMS_alerts::whereMonth('date', $request->month)->whereYear('date', $request->year)->where('status', 0)->whereNull('date_into')->sum('amount');
        $total_not_type_revenue_year = SMS_alerts::whereYear('date', $request->year)->where('status', 0)->whereNull('date_into')->sum('amount');

        $total_no_type = Revenues::whereDay('date', date('d'))->whereMonth('date', $request->month)->whereYear('date', $request->year)->select('total_no_type')->first();
        $total_no_type_month = Revenues::whereMonth('date', $request->month)->whereYear('date', $request->year)->select(DB::raw("SUM(total_no_type) as total_no_type"))->first();
        $total_no_type_year = Revenues::whereYear('date', $request->year)->select(DB::raw("SUM(total_no_type) as total_no_type"))->first();

        $day = $request->day;
        $month = $request->month;
        $year = $request->year;

        $total_ev_revenue = Revenues::whereDay('date', date('d'))->whereMonth('date', $request->month)->whereYear('date', $request->year)->select('total_elexa')->sum('total_elexa');
        $total_ev_month = Revenues::whereMonth('date', $request->month)->whereYear('date', $request->year)->select('total_elexa')->sum('total_elexa');
        $total_ev_year = Revenues::whereYear('date', $request->year)->sum('total_elexa');
        $ev_charge = Revenues::getManualEvCharge(date($request->year.'-'.$request->month.'-'.$request->day), $request->month, $request->year, 8, 8);

        $by_page = 'index';

        if (isset($_GET['byPage']) && @$_GET['byPage'] == 'department') {
            $by_page = 'index_department';
        } else {
            $by_page = 'index';
        }

        if (isset($_GET['dailyPage']) && @$_GET['dailyPage'] != 'daily') {
            $by_page = 'index_'.@$_GET['dailyPage'];
        }

        return view('revenue.'.$by_page, compact(
            // 'data_revenue',
            // 'data_bill',
            'total_daily_revenue',
            'total_revenue_today', 
            'total_day', 
            'total_verified', 
            'total_unverified', 
            'total_agoda_outstanding',
            'total_ev_outstanding',
            // 'total_wp', 
            // 'total_credit', 
            'total_transfer', 

            'total_transfer2',
            'total_transfer2_month',
            'total_transfer2_year',

            'total_split',
            'total_split_month',
            'total_split_year',
            
            'total_split_transaction',
            'total_split_transaction_month',
            'total_split_transaction_year',

            'credit_revenue',
            'credit_revenue_month',
            'credit_revenue_year',

            'total_front_revenue',
            'total_front_month',
            'total_front_year',
            'front_charge',

            'total_guest_deposit',
            'total_guest_deposit_month',
            'total_guest_deposit_year',
            'guest_deposit_charge',

            'total_fb_revenue',
            'total_fb_month',
            'total_fb_year',
            'fb_charge',

            'total_agoda_revenue',
            'total_agoda_month',
            'total_agoda_year',
            'agoda_charge',

            'total_transfer_month',
            'total_transfer_year',

            'total_transfer_transaction',
            'total_transfer_transaction_month',
            'total_transfer_transaction_year',

            'total_wp_revenue',
            'total_wp_month',
            'total_wp_year',
            'wp_charge',

            'total_credit_transaction',
            'total_credit_transaction_month',
            'total_credit_transaction_year',

            'total_not_type',

            'total_transaction',
            'total_transaction_month',
            'total_transaction_year',

            'total_no_type',
            'total_no_type_month',
            'total_no_type_year',

            'total_not_type_revenue',
            'total_not_type_revenue_month',
            'total_not_type_revenue_year',

            'total_ev_revenue',
            'total_ev_month',
            'total_ev_year',
            'ev_charge',

            'day', 'month', 'year'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $charge = 0;
        $agoda_charge = 0;
        $agoda_outstanding = 0;
        $ev_charge = 0;
        $ev_outstanding = 0;

        $check_credit = Revenues::where('date', $request->date)->first();
        Revenue_credit::where('revenue_id', $check_credit->id)->delete();

        if (!empty($request->guest_batch)) {
            foreach ($request->guest_batch as $key => $value) {
                // $charge += $request->credit_amount[$key];

                Revenue_credit::create([
                    'revenue_id' => $check_credit->id,
                    'batch' => $request->guest_batch[$key],
                    'revenue_type' => $request->guest_revenue_type[$key],
                    'credit_amount' => $request->guest_credit_amount[$key],
                    'status' => 1
                ]);
            }
        }

        if (!empty($request->fb_batch)) {
            foreach ($request->fb_batch as $key => $value) {
                // $charge += $request->credit_amount[$key];

                Revenue_credit::create([
                    'revenue_id' => $check_credit->id,
                    'batch' => $request->fb_batch[$key],
                    'revenue_type' => $request->fb_revenue_type[$key],
                    'credit_amount' => $request->fb_credit_amount[$key],
                    'status' => 2
                ]);
            }
        }

        if (!empty($request->wp_batch)) {
            foreach ($request->wp_batch as $key => $value) {
                // $charge += $request->credit_amount[$key];

                Revenue_credit::create([
                    'revenue_id' => $check_credit->id,
                    'batch' => $request->wp_batch[$key],
                    'revenue_type' => $request->wp_revenue_type[$key],
                    'credit_amount' => $request->wp_credit_amount[$key],
                    'status' => 3
                ]);
            }
        }

        if (isset($request->batch)) {
            foreach ($request->batch as $key => $value) {
                $charge += $request->credit_amount[$key];

                Revenue_credit::create([
                    'revenue_id' => $check_credit->id,
                    'batch' => $request->batch[$key],
                    'revenue_type' => $request->revenue_type[$key],
                    'credit_amount' => $request->credit_amount[$key],
                    'status' => 4
                ]);
            }
        }

        if (!empty($request->agoda_batch)) {
            foreach ($request->agoda_batch as $key => $value) {
                $agoda_charge += $request->agoda_credit_amount[$key];
                $agoda_outstanding += $request->agoda_credit_outstanding[$key];

                Revenue_credit::create([
                    'revenue_id' => $check_credit->id,
                    'batch' => $request->agoda_batch[$key],
                    'revenue_type' => $request->agoda_revenue_type[$key],
                    'agoda_check_in' => $request->agoda_check_in[$key],
                    'agoda_check_out' => $request->agoda_check_out[$key],
                    'agoda_date_deposit' => date("Y-m-d", strtotime("+37 day",strtotime($request->agoda_check_out[$key]))),
                    'agoda_charge' => $request->agoda_credit_amount[$key],
                    'agoda_outstanding' => $request->agoda_credit_outstanding[$key],
                    'status' => 5
                ]);
            }
        }

        if (!empty($request->front_batch)) {
            foreach ($request->front_batch as $key => $value) {
                // $charge += $request->credit_amount[$key];
                Revenue_credit::create([
                    'revenue_id' => $check_credit->id,
                    'batch' => $request->front_batch[$key],
                    'revenue_type' => $request->front_revenue_type[$key],
                    'credit_amount' => $request->front_credit_amount[$key],
                    'status' => 6
                ]);
            }
        }

        if (!empty($request->ev_batch)) {
            foreach ($request->ev_batch as $key => $value) {
                $ev_charge += $request->ev_credit_amount[$key];
                // $ev_outstanding += $request->ev_credit_outstanding[$key];

                Revenue_credit::create([
                    'revenue_id' => $check_credit->id,
                    'batch' => $request->ev_batch[$key],
                    'revenue_type' => $request->ev_revenue_type[$key],
                    'ev_charge' => $request->ev_credit_amount[$key],
                    'ev_fee' => $request->ev_transaction_fee[$key],
                    'ev_vat' => $request->ev_vat[$key],
                    'ev_revenue' => $request->ev_total_revenue[$key],
                    'status' => 8
                ]);
            }
        }

        Revenues::where('date', $request->date)->update([
            'front_cash' => $request->front_cash ?? 0,
            'room_cash' => $request->cash ?? 0,
            'fb_cash' => $request->fb_cash ?? 0,
            'wp_cash' => $request->wp_cash ?? 0,
            // 'charge' => $charge - $check_credit->room_credit,
            'agoda_charge' => $agoda_charge - $agoda_outstanding,
            // 'ev_charge' => $ev_charge - $ev_outstanding,
        ]);
        
        // return redirect(route('revenue'))->with('success', 'ระบบได้ทำการบันทึกรายการในระบบเรียบร้อยแล้ว');
        return response()->json([
            'data' => 200,
        ]);

    }

    public function edit($date)
    {
        // dd($date);
        $data = Revenues::where('date', $date)->first();
        $data_credit = Revenue_credit::where('revenue_id', $data->id)->get();

        return response()->json([
            'data' => $data,
            'data_credit' => $data_credit
        ]);
    }

    public function daily_close(Request $request)
    {
        Revenues::where('date', $request->date)->update([
            'status' => 1
        ]);

        return response()->json([
            'status' => 200
        ]);
    }

    public function daily_open(Request $request)
    {
        // dd($request->date);
        Revenues::where('date', $request->date)->update([
            'status' => 0
        ]);

        return response()->json([
            'status' => 200
        ]);
    }

    public function export()
    {
        $data = Revenues::whereMonth('date', date('m'))->whereYear('date', date('Y'))->get();
        $pdf = FacadePdf::loadView('pdf.1A', compact('data'));

        return $pdf->stream();
    }

    public function detail($topic, $date)
    {

        $change_date = date_create($date);
        $change_fomat = date_format($change_date,"m-d");

        $exp = explode('-', $date);

        if ($exp[2] != 0) {
            $adate = $date;
            $from = date("Y-m-d 21:00:00", strtotime("-1 day",strtotime($adate)));
            $to = date($date.' 21:00:00');
            $title = "";
        } else {
            $adate = $exp[0]."-".$exp[1].'-'.$this->EOM($exp[1], $exp[0]);
            $from = date("Y-m-d 21:00:00", strtotime("last day of previous month",strtotime($adate)));
            $to = date($adate.' 21:00:00');
            $title = "";
        }
        

        // dd($date);
        if ($topic == "verified") {
            $data_verified = Revenues::whereMonth('date', $exp[1])->whereYear('date', $exp[0])->where('status', 1)->get();
            $title = "Verified";
            return view('revenue.detail_verified', compact('data_verified', 'title'));

        } if ($topic == "unverified") {
            $data_verified = Revenues::whereMonth('date', $exp[1])->whereYear('date', $exp[0])->where('status', 0)->get();
            $title = "Unverified";
            return view('revenue.detail_verified', compact('data_verified', 'title'));

        } if ($topic == "front") {
            $total_revenue = Revenues::whereDate('date', $adate)->select('front_cash as cash', 'front_transfer as transfer', 'front_credit as credit')->first();
            $charge = Revenues::getManualCharge($adate, 0, 0, 6, 6);
            $title = "Front Desk";

        } if($topic == "all_outlet") {
            $total_revenue = Revenues::whereDate('date', $adate)->select('fb_cash as cash', 'fb_transfer as transfer', 'fb_credit as credit')->first();
            $charge = Revenues::getManualCharge($adate, 0, 0, 2, 2);
            $title = "All Outlet";

        } if($topic == "guest") {
            $total_revenue = Revenues::whereDate('date', $adate)->select('room_cash as cash', 'room_transfer as transfer', 'room_credit as credit')->first();
            $charge = Revenues::getManualCharge($adate, 0, 0, 1, 1);
            $title = "Guest Deposit";

        } if($topic == "agoda_charge") {
            $agoda_charge = Revenue_credit::leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
            ->whereDate('revenue.date', $adate)->where('revenue_credit.status', 5)
            ->select('revenue_credit.agoda_check_in', 'revenue_credit.agoda_check_out', 'revenue_credit.revenue_type', 'revenue_credit.agoda_charge', 'revenue_credit.agoda_outstanding')->get();
            $title = "Credit Card Agoda Manual Charge";
            return view('revenue.agoda.agoda_charge', compact('agoda_charge', 'title'));

        } if($topic == "agoda_fee") {
            $agoda_fee = Revenue_credit::leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
            ->whereDate('revenue.date', $adate)->where('revenue_credit.status', 5)
            ->select('revenue_credit.agoda_check_in', 'revenue_credit.agoda_check_out', 'revenue_credit.revenue_type', 'revenue_credit.agoda_charge', 'revenue_credit.agoda_outstanding')->get();
            $title = "Credit Card Agoda Fee";
            return view('revenue.agoda.agoda_fee', compact('agoda_fee', 'title'));

        } if($topic == "agoda_outstanding") {
            $agoda_outstanding = Revenue_credit::leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
            ->whereDate('revenue.date', $adate)->where('revenue_credit.status', 5)
            ->select('revenue_credit.agoda_check_in', 'revenue_credit.agoda_check_out', 'revenue_credit.revenue_type', 'revenue_credit.agoda_charge', 'revenue_credit.agoda_outstanding')->get();
            $title = "Credit Agoda Revenue Outstanding";
            return view('revenue.agoda.agoda_outstanding', compact('agoda_outstanding', 'title'));

        } if($topic == "agoda_revenue") {
            $agoda_revenue= SMS_alerts::whereDate('date', $adate)->where('status', 5)->get();
            $title = "Agoda Revenue";
            return view('revenue.agoda.agoda_revenue', compact('agoda_revenue', 'title'));

        } if($topic == "credit_charge") {
            $credit_charge = Revenue_credit::leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
            ->whereDate('revenue.date', $adate)->where('revenue_credit.status', '!=', 5)->select('revenue_credit.*', 'revenue.date')->get();
            $title = "Credit Card Manual Charge";
            return view('revenue.credit.credit_charge', compact('credit_charge', 'title'));

        } if($topic == "credit_fee") {
            $credit_fee= Revenue_credit::leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
            ->whereDate('revenue.date', $adate)->where('revenue_credit.status', '!=', 5)->select('revenue_credit.*', 'revenue.date')->get();
            $title = "Credit Card Hotel Fee";
            return view('revenue.credit.credit_fee', compact('credit_fee', 'title'));

        } if($topic == "credit_revenue") {
            $credit_revenue = SMS_alerts::whereDate('date', $adate)->where('status', 4)->whereNull('date_into')->where('into_account', "708-226792-1")
            ->orWhereDate('date_into', $adate)->where('status', 4)->where('into_account', "708-226792-1")->get();
            $title = "Credit Card Hotel Revenue";
            return view('revenue.credit.credit_revenue', compact('credit_revenue', 'title'));

        } if($topic == "wp_charge") {
            $wp_charge = Revenue_credit::leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
            ->whereDate('revenue.date', $adate)->where('revenue_credit.status', 3)->select('revenue_credit.*', 'revenue.date')->get();
            $title = "Credit Card Water Park Manual Charge";
            return view('revenue.water_park.wp_charge', compact('wp_charge', 'title'));

        } if($topic == "wp_fee") {
            $wp_fee= Revenue_credit::leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
            ->whereDate('revenue.date', $adate)->where('revenue_credit.status', 3)->select('revenue_credit.*', 'revenue.date', 'revenue.total_credit')->get();
            $title = "Credit Card Warter Park Fee";
            return view('revenue.water_park.wp_fee', compact('wp_fee', 'title'));

        } if ($topic == "wp") {
            $total_revenue = Revenues::whereDate('date', $adate)->select('wp_cash as cash', 'wp_transfer as transfer', 'wp_credit as credit')->first();
            $charge = Revenues::getManualCharge($adate, 0, 0, 3, 3);
            $title = "Water Park Revenue";

        } if($topic == "wp_credit") {
            $wp_credit = SMS_alerts::whereDate('date', $adate)->where('status', 7)->get();
            $title = "Credit Card Water Park Revenue";
            return view('revenue.water_park.wp_credit', compact('wp_credit', 'title'));

        } if($topic == "ev_outstanding") {
            $ev_outstanding = Revenue_credit::leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
            ->whereDate('revenue.date', $adate)->where('revenue_credit.status', 8)
            ->select('revenue_credit.revenue_type', 'revenue_credit.ev_charge', 'revenue_credit.ev_outstanding')->get();
            $title = "Elexa EGAT Revenue Outstanding";
            return view('revenue.elexa.ev_outstanding', compact('ev_outstanding', 'title'));

        } if($topic == "ev_charge") {
            $ev_charge = Revenue_credit::leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
            ->whereDate('revenue.date', $adate)->where('revenue_credit.status', 8)
            ->select('revenue_credit.revenue_type', 'revenue_credit.ev_charge', 'revenue_credit.ev_outstanding')->get();
            $title = "Elexa EGAT Charge";
            return view('revenue.elexa.ev_charge', compact('ev_charge', 'title'));

        } if($topic == "ev_fee") {
            $ev_fee = Revenue_credit::leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
            ->whereDate('revenue.date', $adate)->where('revenue_credit.status', 8)
            ->select('revenue_credit.revenue_type', 'revenue_credit.ev_charge', 'revenue_credit.ev_outstanding')->get();
            $title = "Elexa Fee";
            return view('revenue.elexa.ev_fee', compact('ev_fee', 'title'));

        } if($topic == "elexa") {
            $data_sms = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 8)
                ->orWhereDate('date_into', date('Y-m-d'))
                ->where('status', 8)->get();
            $title = "Elexa EGAT Revenue";
            return view('revenue.elexa.elexa_revenue', compact('data_sms', 'title'));

        } if($topic == "credit") {
            $data_sms = SMS_alerts::whereDate('date_into', date('Y-m-d'))->where('into_account', "708-226792-1")->where('status', 4)->get();
            $title = "Credit Revenue";

        } if($topic == "transfer") {
            $data_sms = SMS_alerts::whereBetween('date', [$from, $to])->where('transfer_status', 1)->get();
            $title = "Transfer Revenue";
            return view('revenue.detail2', compact('data_sms', 'title'));

        } if($topic == "transfer_transaction") {
            $data_sms = SMS_alerts::whereBetween('date', [$from, $to])->where('transfer_status', 1)->get();
            $title = "Transfer Transaction";
            return view('revenue.detail2', compact('data_sms', 'title'));

        } if($topic == "credit_transaction") {
            $data_sms = SMS_alerts::whereBetween('date', [$from, $to])->where('transfer_status', 1)->get();
            $title = "Credit Card Hotel Transfer Transaction";
            return view('revenue.detail2', compact('data_sms', 'title'));

        } if($topic == "total_transaction") {
            $data_sms = SMS_alerts::whereBetween('date', [$from, $to])->get();
            $title = "Total Transaction";
            return view('revenue.detail2', compact('data_sms', 'title'));

        } if ($topic == "status") {
            $data_sms = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 0)->whereNull('date_into')->get();
            $title = "No Income Type";
            return view('revenue.detail2', compact('data_sms', 'title'));

        } if ($topic == "no_income_revenue") {
            $data_sms = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 0)->whereNull('date_into')->get();
            $title = "No Income Revenue";
            return view('revenue.detail2', compact('data_sms', 'title'));

        } if ($topic == "split_revenue") {
            $data_sms = SMS_alerts::whereDate('date_into', date('Y-m-d'))->where('split_status', 1)->get();
            $title = "Split Revenue";
            return view('revenue.detail2', compact('data_sms', 'title'));

        } if ($topic == "split_transaction") {
            $data_sms = SMS_alerts::whereBetween('date', [$from, $to])->where('split_status', 1)->get();
            $title = "Split Credit Card Hotel Transaction";
            return view('revenue.detail2', compact('data_sms', 'title'));

        } if($topic == "total_ev_outstanding") {
            $ev_outstanding = Revenue_credit::leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
            // ->whereMonth('revenue.date', $exp[1])->whereYear('revenue.date', $exp[0])
            ->where('revenue_credit.status', 8)
            ->select('revenue_credit.revenue_type', 'revenue_credit.ev_charge', 'revenue_credit.ev_outstanding')->get();
            $title = "Total Elexa EGAT Revenue Outstanding";
            return view('revenue.elexa.ev_outstanding', compact('ev_outstanding', 'title'));

        } if($topic == "total_agoda_outstanding") {
            $agoda_outstanding = Revenue_credit::leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
            // ->whereMonth('revenue.date', $exp[1])->whereYear('revenue.date', $exp[0])
            ->where('revenue_credit.status', 5)->where('revenue_credit.receive_payment', 0)
            ->select('revenue_credit.id', 'revenue_credit.agoda_check_in', 'revenue_credit.agoda_check_out', 'revenue_credit.revenue_type', 'revenue_credit.agoda_charge', 
            'revenue_credit.agoda_outstanding')->orderBy('revenue_id', 'asc')->get();

            // $agoda_revenue = SMS_alerts::where('status', 5)->get();

            $title = "Total Credit Agoda Revenue Outstanding";
            return view('revenue.agoda.agoda_outstanding', compact('agoda_outstanding', 'title'));

        }

        return view('revenue.detail', compact('total_revenue', 'charge', 'title'));
    }
}
