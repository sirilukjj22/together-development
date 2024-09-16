<?php

namespace App\Http\Controllers;

use App\Models\Revenue_credit;
use App\Models\Revenues;
use App\Models\SMS_alerts;
use App\Models\TB_close_days;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Carbon\Carbon;
use PHPUnit\Framework\Constraint\Count;

class RevenuesController extends Controller
{

    public function index()
    {
        $adate= date('Y-m 21:00:00');
        $from = date("Y-m-d 21:00:00", strtotime("-1 day"));
        $to = date('Y-m-d 21:00:00');

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

        ## Update จำนวนเงินของแต่ละวัน
        $room_array = [];
        $fb_array = [];
        $wp_array = [];
        $credit_array = [];
        $agoda_array = [];
        $front_array = [];
        $credit_wp_array = [];
        $ev_array = [];
        $other_array = [];
        $no_type_array = [];
        $transaction_array = [];
        
        for ($i=1; $i <= 31; $i++) { 
            if ($i == 1) {
                $check_sms = SMS_alerts::whereBetween('date', [date("Y-m-d 21:00:00", strtotime("last day of previous month")), date("Y-m-01 21:00:00")])->whereNull('date_into')
                ->orWhereDate('date_into', date("Y-m-01"))
                ->select('sms_alert.*', DB::raw("COUNT(id) as transaction_bill, DATE(date) as date_fm, SUM(amount) as total_amount"))->groupBy('status')->get();

                $sum_bill = 0;
                foreach ($check_sms as $key => $value) {
                    // Guest Deposit
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 1) {
                        $room_array[$i] = [
                            'total_room' => $check_sms[$key]['total_amount'],
                        ];
                    }
                    // All Outlet
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 2) {
                        $fb_array[$i] = [
                            'total_fb' => $check_sms[$key]['total_amount'],
                        ];
                    }
                    // Water Park
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 3) {
                        $wp_array[$i] = [
                            'total_wp' => $check_sms[$key]['total_amount'],
                        ];
                    }
                    // Credit Card Hotel
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 4 && $check_sms[$key]['into_account'] == "708-226792-1") {
                        $credit_array[$i] = [
                            'total_credit' => $check_sms[$key]['amount'],
                        ];
                    }
                    // Agoda
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 5) {
                        $agoda_array[$i] = [
                            'total_agoda' => $check_sms[$key]['total_amount'],
                        ];
                    }
                    // Front Desk
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 6) {
                        $front_array[$i] = [
                            'total_front' => $check_sms[$key]['total_amount'],
                        ];
                    }
                    // Credit Card Water Park
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 7) {
                        $credit_wp_array[$i] = [
                            'total_credit_wp' => $check_sms[$key]['total_amount'],
                        ];
                    } 
                    // Elexa
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 8) {
                        $ev_array[$i] = [
                            'total_ev' => $check_sms[$key]['total_amount'],
                        ];
                    } 
                    // Other
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 9) {
                        $other_array[$i] = [
                            'total_other' => $check_sms[$key]['total_amount'],
                        ];
                    } 
                    // No Category
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 0) {
                        $no_type_array[$i] = ['no_type' => $check_sms[$key]['transaction_bill']];
                    } 

                    $sum_bill += $check_sms[$key]['transaction_bill'];

                    // เช็คค่า 0
                    if (!isset($room_array[$i])) {
                        $room_array[$i] = [ 'total_room' => 0, ];
                    }

                    if (!isset($fb_array[$i])) {
                        $fb_array[$i] = [ 'total_fb' => 0, ];
                    }

                    if (!isset($wp_array[$i])) {
                        $wp_array[$i] = [ 'total_wp' => 0, ];
                    }

                    if (!isset($credit_array[$i])) {
                        $fcredit_array[$i] = [ 'total_credit' => 0, ];
                    }

                    if (!isset($agoda_array[$i])) {
                        $agoda_array[$i] = [ 'total_agoda' => 0, ];
                    }

                    if (!isset($front_array[$i])) {
                        $front_array[$i] = [ 'total_front' => 0, ];
                    }

                    if (!isset($credit_wp_array[$i])) {
                        $credit_wp_array[$i] = [ 'total_credit_wp' => 0, ];
                    } 

                    if (!isset($ev_array[$i])) {
                        $ev_array[$i] = [ 'total_ev' => 0, ];
                    }

                    if (!isset($other_array[$i])) {
                        $other_array[$i] = [ 'total_other' => 0, ];
                    }
                }

                $transaction_array[$i] = ['bill' => $sum_bill];
                
            } else {
                $check_sms = SMS_alerts::whereBetween('date', [date("Y-m-".str_pad($i - 1, 2, '0', STR_PAD_LEFT).' 21:00:00'), date('Y-m-'.str_pad($i, 2, '0', STR_PAD_LEFT).' 21:00:00')])->whereNull('date_into')
                ->orWhereDate('date_into', date('Y-m-'.str_pad($i, 2, '0', STR_PAD_LEFT)))
                ->select('sms_alert.*', DB::raw("COUNT(id) as transaction_bill, DATE(date) as date_fm, SUM(amount) as total_amount"))->groupBy('status')->get();

                // dd($check_sms);
                $sum_bill = 0;
                foreach ($check_sms as $key => $value) {
                    // Guest Deposit
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 1) {
                        $room_array[$i] = [
                            'total_room' => $check_sms[$key]['total_amount'],
                        ];
                    }
                    // All Outlet
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 2) {
                        $fb_array[$i] = [
                            'total_fb' => $check_sms[$key]['total_amount'],
                        ];
                    }
                    // Water Park
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 3) {
                        $wp_array[$i] = [
                            'total_wp' => $check_sms[$key]['total_amount'],
                        ];
                    }
                    // Credit Card Hotel
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 4 && $check_sms[$key]['into_account'] == "708-226792-1") {
                        $credit_array[$i] = [
                            'total_credit' => $check_sms[$key]['amount'],
                        ];
                    }
                    // Agoda
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 5) {
                        $agoda_array[$i] = [
                            'total_agoda' => $check_sms[$key]['total_amount'],
                        ];
                    }
                    // Front Desk
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 6) {
                        $front_array[$i] = [
                            'total_front' => $check_sms[$key]['total_amount'],
                        ];
                    }
                    // Credit Card Water Park
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 7) {
                        $credit_wp_array[$i] = [
                            'total_credit_wp' => $check_sms[$key]['total_amount'],
                        ];
                    } 
                    // Elexa
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 8) {
                        $ev_array[$i] = [
                            'total_ev' => $check_sms[$key]['total_amount'],
                        ];
                    } 
                    // Other
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 9) {
                        $other_array[$i] = [
                            'total_other' => $check_sms[$key]['total_amount'],
                        ];
                    } 
                    // No Category
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 0) {
                        $no_type_array[$i] = ['no_type' => $check_sms[$key]['transaction_bill']];
                    } 

                    $sum_bill += $check_sms[$key]['transaction_bill'];

                    // เช็คค่า 0
                    if (!isset($room_array[$i])) {
                        $room_array[$i] = [ 'total_room' => 0, ];
                    }

                    if (!isset($fb_array[$i])) {
                        $fb_array[$i] = [ 'total_fb' => 0, ];
                    }

                    if (!isset($wp_array[$i])) {
                        $wp_array[$i] = [ 'total_wp' => 0, ];
                    }

                    if (!isset($credit_array[$i])) {
                        $fcredit_array[$i] = [ 'total_credit' => 0, ];
                    }

                    if (!isset($agoda_array[$i])) {
                        $agoda_array[$i] = [ 'total_agoda' => 0, ];
                    }

                    if (!isset($front_array[$i])) {
                        $front_array[$i] = [ 'total_front' => 0, ];
                    }

                    if (!isset($credit_wp_array[$i])) {
                        $credit_wp_array[$i] = [ 'total_credit_wp' => 0, ];
                    } 

                    if (!isset($ev_array[$i])) {
                        $ev_array[$i] = [ 'total_ev' => 0, ];
                    }

                    if (!isset($other_array[$i])) {
                        $other_array[$i] = [ 'total_other' => 0, ];
                    }
                    
                }
                $transaction_array[$i] = ['bill' => $sum_bill];
            }
            
        }

        // dd($room_array);

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

        if (isset($other_array)) {
            foreach ($other_array as $key => $value) {
                Revenues::where('date', date('Y-m-'.$key))->update([
                    'other_revenue' => $value['total_other']
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

        // $daily_revenue = Revenues::whereMonth('date', date('m'))->whereYear('date', date('Y'))->select(
        //     DB::raw("SUM(front_cash) + SUM(front_transfer) + SUM(front_credit) as front_amount, 
        //     SUM(room_cash) + SUM(room_transfer) + SUM(room_credit) as room_amount, 
        //     SUM(fb_cash) + SUM(fb_transfer) + SUM(fb_credit) as fb_amount,
        //     SUM(wp_cash) + SUM(wp_transfer) + SUM(wp_credit) as wp_amount,
        //     SUM(room_credit) + SUM(fb_credit) + SUM(wp_credit) as credit_amount"),
        //     DB::raw("SUM(other_revenue) as other_revenue"), 'total_credit')->first();

        // $total_daily_revenue = $daily_revenue->front_amount + $daily_revenue->room_amount + $daily_revenue->fb_amount + $daily_revenue->wp_amount + $daily_revenue->credit_amount + $daily_revenue->other_revenue + $daily_revenue->total_credit;

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
            "), 'total_credit_agoda', 'other_revenue', 'total_transaction', 'total_no_type', 'status')->first();
        $total_transfer = SMS_alerts::whereBetween('date', [$from, $to])->where('transfer_status', 1)->sum('amount');
        $total_transfer2 = SMS_alerts::whereBetween('date', [$from, $to])->where('transfer_status', 1)->count();
        $total_split = SMS_alerts::where('date_into', date('Y-m-d'))->where('split_status', 1)->sum('amount');
        $total_split_transaction = SMS_alerts::whereBetween('date', [$from, $to])->where('split_status', 1)->count();
        $total_not_type = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 0)->whereNull('date_into')->count();
        $total_not_type_revenue = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 0)->whereNull('date_into')->sum('amount');
        $total_credit_transaction = SMS_alerts::whereDate('date_into', date('Y-m-d'))->where('into_account', "708-226792-1")->where('status', 4)->count();

        $total_agoda_outstanding = Revenues::getManualTotalAgoda();
        $total_ev_outstanding = Revenues::getManualTotalEv();

        $total_day = $total_revenue_today->front_amount + $total_revenue_today->room_amount + $total_revenue_today->fb_amount + $total_revenue_today->wp_amount
         + $total_revenue_today->credit_amount + $total_revenue_today->total_credit_agoda + $total_revenue_today->other_revenue;
        // dd($total_guest_deposit);

        // dd($total_revenue_today->room_amount);

        ## ข้อมูลในตาราง
        $date = date('d');
        $symbol = $date == "01" ? "=" : "<=";

        $date_from = date('Y-m-d');
        $date_to = date('Y-m-d');


        $credit_revenue = Revenues::whereDate('date', date('Y-m-d'))->select('total_credit')->first();
        $credit_revenue_today = $credit_revenue;
        $credit_revenue_month = Revenues::whereDay('date', $symbol, date('d'))->whereMonth('date', date('m'))->whereYear('date', date('Y'))->select(DB::raw("SUM(total_credit) as total_credit"))->first();
        $credit_revenue_year = Revenues::whereDate('date', '<=', date('Y-m-d'))->select(DB::raw("SUM(total_credit) as total_credit"))->first();

        $total_front_revenue = Revenues::whereDate('date', date('Y-m-d'))->select('front_cash', 'front_transfer', 'front_credit')->first();
        $today_front_revenue = $total_front_revenue;
        $total_front_month = Revenues::whereDay('date', $symbol, date('d'))->whereMonth('date', date('m'))->whereYear('date', date('Y'))->select(DB::raw("SUM(front_cash) as front_cash, SUM(front_transfer) as front_transfer, SUM(front_credit) as front_credit"))->first();
        $total_front_year = Revenues::whereDate('date', '<=', date('Y-m-d'))->select(DB::raw("SUM(front_cash) as front_cash, SUM(front_transfer) as front_transfer, SUM(front_credit) as front_credit"))->first();
        $front_charge = Revenues::getManualCharge("date", $date_from, $date_to, date('Y-m-d'), date('m'), date('Y'), 6, 6);

        $total_guest_deposit = Revenues::whereDate('date', date('Y-m-d'))->select('room_cash', 'room_transfer', 'room_credit')->first();
        $today_guest_deposit = $total_guest_deposit;
        $total_guest_deposit_month = Revenues::whereDate('date', '>=', date('Y-m-01'))->whereDate('date', $symbol, date('Y-m-d'))->select(DB::raw("SUM(room_cash) as room_cash, SUM(room_transfer) as room_transfer, SUM(room_credit) as room_credit"))->first();
        $total_guest_deposit_year = Revenues::whereDate('date', '<=', date('Y-m-d'))->select(DB::raw("SUM(room_cash) as room_cash, SUM(room_transfer) as room_transfer, SUM(room_credit) as room_credit"))->first();
        $guest_deposit_charge = Revenues::getManualCharge("date", $date_from, $date_to, date('Y-m-d'), date('m'), date('Y'), 1, 1);

        $total_fb_revenue = Revenues::whereDate('date', date('Y-m-d'))->select('fb_cash', 'fb_transfer', 'fb_credit')->first();
        $today_fb_revenue = $total_fb_revenue;
        $total_fb_month = Revenues::whereDay('date', $symbol, date('d'))->whereMonth('date', date('m'))->whereYear('date', date('Y'))->select(DB::raw("SUM(fb_cash) as fb_cash, SUM(fb_transfer) as fb_transfer, SUM(fb_credit) as fb_credit"))->first();
        $total_fb_year = Revenues::whereDate('date', '<=', date('Y-m-d'))->select(DB::raw("SUM(fb_cash) as fb_cash, SUM(fb_transfer) as fb_transfer, SUM(fb_credit) as fb_credit"))->first();
        $fb_charge = Revenues::getManualCharge("date", $date_from, $date_to, date('Y-m-d'), date('m'), date('Y'), 2, 2);

        $total_agoda_revenue = Revenues::whereDate('date', date('Y-m-d'))->sum('total_credit_agoda');
        $today_agoda_revenue = $total_agoda_revenue;
        $total_agoda_month = Revenues::whereDay('date', $symbol, date('d'))->whereMonth('date', date('m'))->whereYear('date', date('Y'))->sum('total_credit_agoda');
        $total_agoda_year = Revenues::whereDate('date', '<=', date('Y-m-d'))->sum('total_credit_agoda');
        $agoda_charge = Revenues::getManualAgodaCharge("date", $date_from, $date_to, date('Y-m-d'), date('m'), date('Y'), 1, 5);

        $total_wp_revenue = Revenues::whereDate('date', date('Y-m-d'))->select('wp_cash', 'wp_transfer', 'wp_credit')->first();
        $today_wp_revenue = $total_wp_revenue;
        $total_wp_month = Revenues::whereDay('date', $symbol, date('d'))->whereMonth('date', date('m'))->whereYear('date', date('Y'))->select(DB::raw("SUM(wp_cash) as wp_cash, SUM(wp_transfer) as wp_transfer, SUM(wp_credit) as wp_credit"))->first();
        $total_wp_year = Revenues::whereDate('date', '<=', date('Y-m-d'))->select(DB::raw("SUM(wp_cash) as wp_cash, SUM(wp_transfer) as wp_transfer, SUM(wp_credit) as wp_credit"))->first();
        $wp_charge = Revenues::getManualCharge("date", $date_from, $date_to, date('Y-m-d'), date('m'), date('Y'), 3, 3);

        $total_ev_revenue = Revenues::whereDate('date', date('Y-m-d'))->select('total_elexa')->sum('total_elexa');
        $today_ev_revenue = $total_ev_revenue;
        $total_ev_month = Revenues::whereDay('date', $symbol, date('d'))->whereMonth('date', date('m'))->whereYear('date', date('Y'))->select('total_elexa')->sum('total_elexa');
        $total_ev_year = Revenues::whereDate('date', '<=', date('Y-m-d'))->select('total_elexa')->sum('total_elexa');
        $ev_charge = Revenues::getManualEvCharge("date", $date_from, $date_to, date('Y-m-d'), date('m'), date('Y'), 8, 8);

        $total_other_revenue = Revenues::whereDate('date', date('Y-m-d'))->select('other_revenue')->sum('other_revenue');
        $today_other_revenue = $total_other_revenue;
        $total_other_month = Revenues::whereDay('date', $symbol, date('d'))->whereMonth('date', date('m'))->whereYear('date', date('Y'))->select('other_revenue')->sum('other_revenue');
        $total_other_year = Revenues::whereDate('date', '<=', date('Y-m-d'))->select('other_revenue')->sum('other_revenue');

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
            'total_revenue_today', 
            'total_day', 
            'total_verified',
            'total_unverified',
            'total_agoda_outstanding',
            'total_ev_outstanding',
            'total_transfer', 

            'total_transfer2',

            'total_split',

            'total_split_transaction',
            'total_credit_transaction',

            'credit_revenue',
            'credit_revenue_today',
            'credit_revenue_month',
            'credit_revenue_year',

            'total_front_revenue',
            'today_front_revenue',
            'total_front_month',
            'total_front_year',
            'front_charge',

            'total_guest_deposit',
            'today_guest_deposit',
            'total_guest_deposit_month',
            'total_guest_deposit_year',
            'guest_deposit_charge',

            'total_fb_revenue',
            'today_fb_revenue',
            'total_fb_month',
            'total_fb_year',
            'fb_charge',

            'total_agoda_revenue',
            'today_agoda_revenue',
            'total_agoda_month',
            'total_agoda_year',
            'agoda_charge',

            'total_wp_revenue',
            'today_wp_revenue',
            'total_wp_month',
            'total_wp_year',
            'wp_charge',

            'total_ev_revenue',
            'today_ev_revenue',
            'total_ev_month',
            'total_ev_year',
            'ev_charge',

            'total_other_revenue',
            'today_other_revenue',
            'total_other_month',
            'total_other_year',

            'total_not_type',
            'total_not_type_revenue',
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
        $ev_array = [];
        $other_array = [];
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
                    // Guest Deposit
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 1) {
                        $room_array[$i] = [
                            'total_room' => $check_sms[$key]['total_amount'],
                        ];
                    }
                    // All Outlet
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 2) {
                        $fb_array[$i] = [
                            'total_fb' => $check_sms[$key]['total_amount'],
                        ];
                    }
                    // Water Park
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 3) {
                        $wp_array[$i] = [
                            'total_wp' => $check_sms[$key]['total_amount'],
                        ];
                    }
                    // Credit Card Hotel
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 4 && $check_sms[$key]['into_account'] == "708-226792-1") {
                        $credit_array[$i] = [
                            'total_credit' => $check_sms[$key]['amount'],
                        ];
                    }
                    // Agoda
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 5) {
                        $agoda_array[$i] = [
                            'total_agoda' => $check_sms[$key]['total_amount'],
                        ];
                    }
                    // Front Desk
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 6) {
                        $front_array[$i] = [
                            'total_front' => $check_sms[$key]['total_amount'],
                        ];
                    }
                    // Credit Card Water Park
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 7) {
                        $credit_wp_array[$i] = [
                            'total_credit_wp' => $check_sms[$key]['total_amount'],
                        ];
                    } 
                    // Elexa
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 8) {
                        $ev_array[$i] = [
                            'total_ev' => $check_sms[$key]['total_amount'],
                        ];
                    } 
                    // Other
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 9) {
                        $other_array[$i] = [
                            'total_other' => $check_sms[$key]['total_amount'],
                        ];
                    } 
                    // No Category
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 0) {
                        $no_type_array[$i] = ['no_type' => $check_sms[$key]['transaction_bill']];
                    } 

                    $sum_bill += $check_sms[$key]['transaction_bill'];

                    // เช็คค่า 0
                    if (!isset($room_array[$i])) {
                        $room_array[$i] = [ 'total_room' => 0, ];
                    }

                    if (!isset($fb_array[$i])) {
                        $fb_array[$i] = [ 'total_fb' => 0, ];
                    }

                    if (!isset($wp_array[$i])) {
                        $wp_array[$i] = [ 'total_wp' => 0, ];
                    }

                    if (!isset($credit_array[$i])) {
                        $fcredit_array[$i] = [ 'total_credit' => 0, ];
                    }

                    if (!isset($agoda_array[$i])) {
                        $agoda_array[$i] = [ 'total_agoda' => 0, ];
                    }

                    if (!isset($front_array[$i])) {
                        $front_array[$i] = [ 'total_front' => 0, ];
                    }

                    if (!isset($credit_wp_array[$i])) {
                        $credit_wp_array[$i] = [ 'total_credit_wp' => 0, ];
                    } 

                    if (!isset($ev_array[$i])) {
                        $ev_array[$i] = [ 'total_ev' => 0, ];
                    }

                    if (!isset($other_array[$i])) {
                        $other_array[$i] = [ 'total_other' => 0, ];
                    }
                }

                $transaction_array[$i] = ['bill' => $sum_bill];
                
            } else {
                $check_sms = SMS_alerts::whereBetween('date', [date("Y-".$month."-".str_pad($i - 1, 2, '0', STR_PAD_LEFT).' 21:00:00'), date('Y-'.$month.'-'.str_pad($i, 2, '0', STR_PAD_LEFT).' 21:00:00')])->whereNull('date_into')
                ->orWhereDate('date_into', date('Y-'.$month.'-'.str_pad($i, 2, '0', STR_PAD_LEFT)))
                ->select('sms_alert.*', DB::raw("COUNT(id) as transaction_bill, DATE(date) as date_fm, SUM(amount) as total_amount"))->groupBy('status')->get();

                // dd($check_sms);
                $sum_bill = 0;
                foreach ($check_sms as $key => $value) {
                    // Guest Deposit
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 1) {
                        $room_array[$i] = [
                            'total_room' => $check_sms[$key]['total_amount'],
                        ];
                    }
                    // All Outlet
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 2) {
                        $fb_array[$i] = [
                            'total_fb' => $check_sms[$key]['total_amount'],
                        ];
                    }
                    // Water Park
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 3) {
                        $wp_array[$i] = [
                            'total_wp' => $check_sms[$key]['total_amount'],
                        ];
                    }
                    // Credit Card Hotel
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 4 && $check_sms[$key]['into_account'] == "708-226792-1") {
                        $credit_array[$i] = [
                            'total_credit' => $check_sms[$key]['amount'],
                        ];
                    }
                    // Agoda
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 5) {
                        $agoda_array[$i] = [
                            'total_agoda' => $check_sms[$key]['total_amount'],
                        ];
                    }
                    // Front Desk
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 6) {
                        $front_array[$i] = [
                            'total_front' => $check_sms[$key]['total_amount'],
                        ];
                    }
                    // Credit Card Water Park
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 7) {
                        $credit_wp_array[$i] = [
                            'total_credit_wp' => $check_sms[$key]['total_amount'],
                        ];
                    } 
                    // Elexa
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 8) {
                        $ev_array[$i] = [
                            'total_ev' => $check_sms[$key]['total_amount'],
                        ];
                    } 
                    // Other
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 9) {
                        $other_array[$i] = [
                            'total_other' => $check_sms[$key]['total_amount'],
                        ];
                    } 
                    // No Category
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 0) {
                        $no_type_array[$i] = ['no_type' => $check_sms[$key]['transaction_bill']];
                    } 

                    $sum_bill += $check_sms[$key]['transaction_bill'];

                    // เช็คค่า 0
                    if (!isset($room_array[$i])) {
                        $room_array[$i] = [ 'total_room' => 0, ];
                    }

                    if (!isset($fb_array[$i])) {
                        $fb_array[$i] = [ 'total_fb' => 0, ];
                    }

                    if (!isset($wp_array[$i])) {
                        $wp_array[$i] = [ 'total_wp' => 0, ];
                    }

                    if (!isset($credit_array[$i])) {
                        $fcredit_array[$i] = [ 'total_credit' => 0, ];
                    }

                    if (!isset($agoda_array[$i])) {
                        $agoda_array[$i] = [ 'total_agoda' => 0, ];
                    }

                    if (!isset($front_array[$i])) {
                        $front_array[$i] = [ 'total_front' => 0, ];
                    }

                    if (!isset($credit_wp_array[$i])) {
                        $credit_wp_array[$i] = [ 'total_credit_wp' => 0, ];
                    } 

                    if (!isset($ev_array[$i])) {
                        $ev_array[$i] = [ 'total_ev' => 0, ];
                    }

                    if (!isset($other_array[$i])) {
                        $other_array[$i] = [ 'total_other' => 0, ];
                    }
                    
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

        if (isset($other_array)) {
            foreach ($other_array as $key => $value) {
                Revenues::where('date', date('Y-m-'.$key))->update([
                    'other_revenue' => $value['total_other']
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
        // dd($request);
        if ($request->revenue_type != '') {
            return $this->detail($request);

        } else {

        if ($request->filter_by == "date" || $request->filter_by == "today" || $request->filter_by == "yesterday" || $request->filter_by == "tomorrow") {
            $req_date = Carbon::parse($request->date)->format('Y-m-d');
            $adate = date('Y-m-d 21:00:00', strtotime($req_date));
            $from = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime(date($adate))));
            $to = date('Y-m-d 20:59:59', strtotime($adate));

            // Revenue
            $month_from = $req_date;
            $month_to = $req_date;
            $date_first_day = date('Y-m-d', strtotime('first day of this month', strtotime($req_date)));

            $Fday = date('d', strtotime($month_from));
            $Fmonth = date('m', strtotime($month_from));
            $Fyear = date('Y', strtotime($month_from));

        } elseif ($request->filter_by == "month") {
            $exp = explode('-', $request->date);

            $start_month = Carbon::parse($exp[0])->format('m');
            $end_month = Carbon::parse($exp[0])->format('m');
            $year = Carbon::parse($exp[0])->format('Y');

            if (isset($exp[1])) { // เลือกมากกว่า 1 เดือน
                $end_month = Carbon::parse($exp[1])->format('m');
                $year = Carbon::parse($exp[1])->format('Y');
            }

            $adate = date('Y-m-d', strtotime($year . '-' . $start_month . '-01'));
            $lastday = dayLast($end_month, $year); // หาวันสุดท้ายของเดือน

            $from = date('Y-m-d' . ' 21:00:00', strtotime('-1 day', strtotime(date($adate))));
            $to = date($year . '-' . $end_month . '-' . $lastday . ' 20:59:59');

            $month_from = date('Y-m-d', strtotime($adate));
            $month_to = date('Y-m-d', strtotime('last day of this month', strtotime(date($to))));
            $date_first_day = date('Y-m-d', strtotime('first day of this month', strtotime($adate)));

            $Fday = date('d', strtotime($adate));
            $Fmonth = $start_month;
            $Fyear = $year;

        } elseif ($request->filter_by == "year") {
            $year = $request->date;
            $adate = date($year . '-01' . '-01');
            $from = date('Y-m-d' . ' 21:00:00', strtotime('-1 day', strtotime(date($adate))));
            $to = date($year . '-12-31' . ' 20:59:59');

            $month_from = date('Y-m-d', strtotime($adate));
            $month_to = date('Y-m-d', strtotime('last day of this month', strtotime(date($to))));
            $date_first_day = date('Y-m-d', strtotime('first day of this month', strtotime($adate)));

            $Fday = date('d', strtotime($adate));
            $Fmonth = date('m', strtotime($month_from));
            $Fyear = $year;

        } elseif ($request->filter_by == "week") {
            $lastday = dayLast(date('m'), date('Y')); // หาวันสุดท้ายของเดือน
            $sundayOfWeek = date('Y-m-d', strtotime('last sunday', strtotime('next sunday')));
            $adate = $sundayOfWeek;
            $adate2 = date('Y-m-d', strtotime('+6 day', strtotime(date($adate))));

            $from = date('Y-m-d' . ' 21:00:00', strtotime('-1 day', strtotime(date($adate))));
            $to = date('Y-m-d' . ' 20:59:59', strtotime(date($adate2)));

            $month_from = $adate;
            $month_to = $adate2;
            $date_first_day = $adate;

            $year_from = date('Y-m-d', strtotime(date('Y-01-01')));
            $year_to = $adate2;

            $Fday = date('d', strtotime($month_from));
            $Fmonth = date('m', strtotime($month_from));
            $Fyear = date('Y', strtotime($month_from));

        } elseif ($request->filter_by == "thisMonth") {
            $lastday = dayLast(date('m'), date('Y')); // หาวันสุดท้ายของเดือน
            $adate = date('Y-m-d', strtotime(date('Y-m-01')));

            $from = date('Y-m-d' . ' 21:00:00', strtotime('-1 day', strtotime(date($adate))));
            $to = date('Y-m-d 20:59:59', strtotime(date('Y-m-d')));

            $month_from = date('Y-m-d', strtotime($adate));
            $month_to = date('Y-m-d', strtotime('last day of this month', strtotime(date($to))));
            $date_first_day = $adate;

            $year_from = date('Y-m-d', strtotime(date('Y-01-01')));
            $year_to = date('Y-m-d', strtotime(date($to)));

            $Fday = date('d', strtotime($month_from));
            $Fmonth = date('m', strtotime($month_from));
            $Fyear = date('Y', strtotime($month_from));

        } elseif ($request->filter_by == "thisYear") {
            $lastday = dayLast(date('m'), date('Y')); // หาวันสุดท้ายของเดือน
            $adate = date('Y-m-d', strtotime(date('Y-01-01')));

            $from = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime(date($adate))));
            $to = date('Y-m-d 20:59:59');

            $month_from = date('Y-m-d', strtotime($adate));
            $month_to = date('Y-m-d');
            $date_first_day = $adate; 

            $month_to_date = date('Y-m-d', strtotime(date('Y-01-01')));
            $month_to_date2 = date('Y-m-d', strtotime(date('Y-m-d')));

            $year_from = date('Y-m-d', strtotime(date('Y-01-01')));
            $year_to = date('Y-m-d');

            $Fday = date('d', strtotime($month_from));
            $Fmonth = date('m', strtotime($month_from));
            $Fyear = date('Y', strtotime($month_from));

        } elseif ($request->filter_by == "customRang") {
            $adate = date('Y-m-d', strtotime(date($request->customRang_start)));
            $adate2 = date('Y-m-d', strtotime(date($request->customRang_end)));

            $from = date('Y-m-d' . ' 21:00:00', strtotime('-1 day', strtotime(date($adate))));
            $to = date('Y-m-d' . ' 20:59:59', strtotime(date($adate2)));

            $month_from = date('Y-m-d', strtotime($adate));
            $month_to = date('Y-m-d', strtotime(date($to)));
            $date_first_day = $adate;

            $Fday = date('d', strtotime($month_from));
            $Fmonth = date('m', strtotime($month_from));
            $Fyear = date('Y', strtotime($month_from));
        }

        $datetime = date('Y-m-d', strtotime($month_from));
        $last_day = $this->EOM($Fmonth, $Fyear);
        $last_day2 = $this->EOM(date("m", strtotime("-1 months", strtotime($datetime))), $Fyear);

        $check_data = Revenues::whereMonth('date', $Fmonth)->whereYear('date', $Fyear)->first();

        if (empty($check_data)) {
            $days = $last_day;

            for ($i=1; $i <= $days; $i++) { 
                Revenues::create([
                    'date' => $Fyear.$Fmonth.str_pad($i, 2, '0', STR_PAD_LEFT),
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
        $ev_array = [];
        $other_array = [];
        $no_type_array = [];
        $transaction_array = [];

        if ($request->filter_by == "date" || $request->filter_by == "today" || $request->filter_by == "yesterday" || $request->filter_by == "tomorrow") 
        {
            for ($i=1; $i <= 31; $i++) { 
                if ($i == 1) {
                    $check_sms = SMS_alerts::whereBetween('date', [date("Y-m-".$last_day2, strtotime("-1 months", strtotime($datetime))).' 21:00:00', date('Y-'.$Fmonth.'-01 20:59:59')])->whereNull('date_into')
                    ->orWhereDate('date_into', date("Y-".$Fmonth."-01"))
                    ->select('sms_alert.*', DB::raw("COUNT(id) as transaction_bill, DATE(date) as date_fm, SUM(amount) as total_amount"))->groupBy('status')->get();

                    $sum_bill = 0;
                    foreach ($check_sms as $key => $value) {
                        // Guest Deposit
                        if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 1) {
                            $room_array[$i] = [
                                'total_room' => $check_sms[$key]['total_amount'],
                            ];
                        }
                        // All Outlet
                        if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 2) {
                            $fb_array[$i] = [
                                'total_fb' => $check_sms[$key]['total_amount'],
                            ];
                        }
                        // Water Park
                        if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 3) {
                            $wp_array[$i] = [
                                'total_wp' => $check_sms[$key]['total_amount'],
                            ];
                        }
                        // Credit Card Hotel
                        if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 4 && $check_sms[$key]['into_account'] == "708-226792-1") {
                            $credit_array[$i] = [
                                'total_credit' => $check_sms[$key]['amount'],
                            ];
                        }
                        // Agoda
                        if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 5) {
                            $agoda_array[$i] = [
                                'total_agoda' => $check_sms[$key]['total_amount'],
                            ];
                        }
                        // Front Desk
                        if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 6) {
                            $front_array[$i] = [
                                'total_front' => $check_sms[$key]['total_amount'],
                            ];
                        }
                        // Credit Card Water Park
                        if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 7) {
                            $credit_wp_array[$i] = [
                                'total_credit_wp' => $check_sms[$key]['total_amount'],
                            ];
                        } 
                        // Elexa
                        if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 8) {
                            $ev_array[$i] = [
                                'total_ev' => $check_sms[$key]['total_amount'],
                            ];
                        } 
                        // Other
                        if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 9) {
                            $other_array[$i] = [
                                'total_other' => $check_sms[$key]['total_amount'],
                            ];
                        }
                        // No Category
                        if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 0) {
                            $no_type_array[$i] = ['no_type' => $check_sms[$key]['transaction_bill']];
                        } 

                        $sum_bill += $check_sms[$key]['transaction_bill'];

                        // เช็คค่า 0
                        if (!isset($room_array[$i])) {
                            $room_array[$i] = [ 'total_room' => 0, ];
                        }

                        if (!isset($fb_array[$i])) {
                            $fb_array[$i] = [ 'total_fb' => 0, ];
                        }

                        if (!isset($wp_array[$i])) {
                            $wp_array[$i] = [ 'total_wp' => 0, ];
                        }

                        if (!isset($credit_array[$i])) {
                            $fcredit_array[$i] = [ 'total_credit' => 0, ];
                        }

                        if (!isset($agoda_array[$i])) {
                            $agoda_array[$i] = [ 'total_agoda' => 0, ];
                        }

                        if (!isset($front_array[$i])) {
                            $front_array[$i] = [ 'total_front' => 0, ];
                        }

                        if (!isset($credit_wp_array[$i])) {
                            $credit_wp_array[$i] = [ 'total_credit_wp' => 0, ];
                        } 

                        if (!isset($ev_array[$i])) {
                            $ev_array[$i] = [ 'total_ev' => 0, ];
                        }

                        if (!isset($other_array[$i])) {
                            $other_array[$i] = [ 'total_other' => 0, ];
                        }
                    }

                    $transaction_array[$i] = ['bill' => $sum_bill];
                    
                } else {
                    $check_sms = SMS_alerts::whereBetween('date', [date("Y-".$Fmonth."-".str_pad($i - 1, 2, '0', STR_PAD_LEFT).' 21:00:00'), date('Y-'.$Fmonth.'-'.str_pad($i, 2, '0', STR_PAD_LEFT).' 20:59:59')])->whereNull('date_into')
                    ->orWhereDate('date_into', date('Y-'.$Fmonth.'-'.str_pad($i, 2, '0', STR_PAD_LEFT)))
                    ->select('sms_alert.*', DB::raw("COUNT(id) as transaction_bill, DATE(date) as date_fm, SUM(amount) as total_amount"))->groupBy('status')->get();

                    $sum_bill = 0;
                    foreach ($check_sms as $key => $value) {
                        // Guest Deposit
                        if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 1) {
                            $room_array[$i] = [
                                'total_room' => $check_sms[$key]['total_amount'],
                            ];
                        }
                        // All Outlet
                        if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 2) {
                            $fb_array[$i] = [
                                'total_fb' => $check_sms[$key]['total_amount'],
                            ];
                        }
                        // Water Park
                        if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 3) {
                            $wp_array[$i] = [
                                'total_wp' => $check_sms[$key]['total_amount'],
                            ];
                        }
                        // Credit Card Hotel
                        if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 4 && $check_sms[$key]['into_account'] == "708-226792-1") {
                            $credit_array[$i] = [
                                'total_credit' => $check_sms[$key]['amount'],
                            ];
                        }
                        // Agoda
                        if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 5) {
                            $agoda_array[$i] = [
                                'total_agoda' => $check_sms[$key]['total_amount'],
                            ];
                        }
                        // Front Desk
                        if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 6) {
                            $front_array[$i] = [
                                'total_front' => $check_sms[$key]['total_amount'],
                            ];
                        }
                        // Credit Card Water Park
                        if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 7) {
                            $credit_wp_array[$i] = [
                                'total_credit_wp' => $check_sms[$key]['total_amount'],
                            ];
                        } 
                        // Elexa
                        if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 8) {
                            $ev_array[$i] = [
                                'total_ev' => $check_sms[$key]['total_amount'],
                            ];
                        } 
                        // Other
                        if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 9) {
                            $other_array[$i] = [
                                'total_other' => $check_sms[$key]['total_amount'],
                            ];
                        } 
                        // No Category
                        if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 0) {
                            $no_type_array[$i] = ['no_type' => $check_sms[$key]['transaction_bill']];
                        } 

                        $sum_bill += $check_sms[$key]['transaction_bill'];

                        // เช็คค่า 0
                        if (!isset($room_array[$i])) {
                            $room_array[$i] = [ 'total_room' => 0, ];
                        }

                        if (!isset($fb_array[$i])) {
                            $fb_array[$i] = [ 'total_fb' => 0, ];
                        }

                        if (!isset($wp_array[$i])) {
                            $wp_array[$i] = [ 'total_wp' => 0, ];
                        }

                        if (!isset($credit_array[$i])) {
                            $fcredit_array[$i] = [ 'total_credit' => 0, ];
                        }

                        if (!isset($agoda_array[$i])) {
                            $agoda_array[$i] = [ 'total_agoda' => 0, ];
                        }

                        if (!isset($front_array[$i])) {
                            $front_array[$i] = [ 'total_front' => 0, ];
                        }

                        if (!isset($credit_wp_array[$i])) {
                            $credit_wp_array[$i] = [ 'total_credit_wp' => 0, ];
                        } 

                        if (!isset($ev_array[$i])) {
                            $ev_array[$i] = [ 'total_ev' => 0, ];
                        }

                        if (!isset($other_array[$i])) {
                            $other_array[$i] = [ 'total_other' => 0, ];
                        }
                        
                    }
                    $transaction_array[$i] = ['bill' => $sum_bill];
                }
            }

            $room_transfer = 0;
            $fb_transfer = 0;
            $wp_transfer = 0;
            $room_credit = 0;

            if (isset($room_array)) {
                foreach ($room_array as $key => $value) {
                    Revenues::where('date', date('Y-'.$Fmonth.'-'.$key))->update([
                        'room_transfer' => $value['total_room']
                    ]);
                }
            }

            if (isset($fb_array)) {
                foreach ($fb_array as $key => $value) {
                    Revenues::where('date', date('Y-'.$Fmonth.'-'.$key))->update([
                        'fb_transfer' => $value['total_fb']
                    ]);
                }
            }

            if (isset($wp_array)) {
                foreach ($wp_array as $key => $value) {
                    Revenues::where('date', date('Y-'.$Fmonth.'-'.$key))->update([
                        'wp_transfer' => $value['total_wp']
                    ]);
                }
            }

            if (isset($credit_array)) {
                foreach ($credit_array as $key => $value) {
                    Revenues::where('date', date('Y-'.$Fmonth.'-'.$key))->update([
                        'total_credit' => $value['total_credit']
                    ]);
                }
            }

            if (isset($front_array)) {
                foreach ($front_array as $key => $value) {
                    Revenues::where('date', date('Y-'.$Fmonth.'-'.$key))->update([
                        'front_transfer' => $value['total_front']
                    ]);
                }
            }

            if (isset($agoda_array)) {
                foreach ($agoda_array as $key => $value) {
                    // dd($value['total_agoda']);
                    Revenues::where('date', date('Y-'.$Fmonth.'-'.$key))->update([
                        'total_credit_agoda' => $value['total_agoda']
                    ]);
                }
            }

            if (isset($ev_array)) {
                foreach ($ev_array as $key => $value) {
                    Revenues::where('date', date('Y-'.$Fmonth.'-'.$key))->update([
                        'total_elexa' => $value['total_ev']
                    ]);
                }
            }

            if (isset($other_array)) {
                foreach ($other_array as $key => $value) {
                    Revenues::where('date', date('Y-'.$Fmonth.'-'.$key))->update([
                        'other_revenue' => $value['total_other']
                    ]);
                }
            }

            if (isset($transaction_array)) {
                foreach ($transaction_array as $key => $value) {
                    Revenues::where('date', date('Y-'.$Fmonth.'-'.$key))->update([
                        'total_transaction' => $value['bill']
                    ]);
                }
            }

            if (isset($no_type_array)) {
                foreach ($no_type_array as $key => $value) {
                    Revenues::where('date', date('Y-'.$Fmonth.'-'.$key))->update([
                        'total_no_type' => $value['no_type']
                    ]);
                }
            }
        }
        
        if ($request->filter_by == "date" || $request->filter_by == "today" || $request->filter_by == "yesterday" || $request->filter_by == "tomorrow") 
        {
            $date_now = date('Y-m-d', strtotime($request->date));
        } else {
            $date_now = date('Y-m-d');
        }
        
        $day_now = $Fday;
        $symbol = $day_now == "01" ? "=" : "<=";

        $date1 = date('Y-m-d', strtotime(date($Fyear.'-'.$Fmonth.'-01')));
        $date2 = date('Y-m-d', strtotime('last day of this month', strtotime(date(date($Fyear.'-'.$Fmonth.'-01')))));

        // verified
        $total_verified = Revenues::whereBetween('date', [$date1, $date2])->where('status', 1)->count();
        $total_unverified = Revenues::whereBetween('date', [$date1, $date2])->where('status', 0)->count();

        $total_revenue_today = Revenues::whereBetween('date', [$month_from, $month_to])->select(
            DB::raw("
            SUM(front_cash + front_transfer + front_credit) as front_amount, 
            SUM(room_cash + room_transfer + room_credit) as room_amount, 
            SUM(fb_cash + fb_transfer + fb_credit) as fb_amount,
            SUM(wp_cash + wp_transfer + wp_credit) as wp_amount,
            SUM(room_credit + fb_credit + wp_credit) as credit_amount,
            SUM(total_transaction) as total_transaction"), 'total_credit_agoda', 'other_revenue', 'total_no_type', 'status')->first();

        $total_transfer = SMS_alerts::whereDate('date_into', '>=', $month_from)->whereDate('date_into', '<=', $month_to)->where('transfer_status', 1)->sum('amount');
        $total_transfer2 = SMS_alerts::whereBetween('date_into', [$from, $to])->where('transfer_status', 1)->count();
        $total_split = SMS_alerts::where('date_into', [$from, $to])->where('split_status', 1)->sum('amount');
        $total_split_transaction = SMS_alerts::whereBetween('date', [$from, $to])->where('split_status', 1)->count();
        $total_not_type = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 0)->whereNull('date_into')->count();
        $total_not_type_revenue = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 0)->whereNull('date_into')->sum('amount');

        ### Credit Transaction ### // Date, Month, Year
        $total_credit_transaction = SMS_alerts::whereDate('date_into', '>=', $month_from)->whereDate('date_into', '<=', $month_to)->where('into_account', "708-226792-1")->where('status', 4)->count();

        $total_agoda_outstanding = Revenues::getManualTotalAgoda();
        $total_ev_outstanding = Revenues::getManualTotalEv();

        $total_day = $total_revenue_today->front_amount + $total_revenue_today->room_amount + $total_revenue_today->fb_amount
         + $total_revenue_today->wp_amount + $total_revenue_today->credit_amount + $total_revenue_today->total_credit_agoda + $total_revenue_today->other_revenue;

        ## ข้อมูลในตาราง

        ### Credit Card Hotel ###
        // Today
        $credit_revenue_today = Revenues::where('date', $date_now)->select(DB::raw("SUM(total_credit) as total_credit"))->first();

        // Date
        if ($request->filter_by == "week") {
            $credit_revenue = Revenues::whereBetween('date', [$adate, $adate2])->select(DB::raw("SUM(total_credit) as total_credit"))->first();
        } else {
            $credit_revenue = Revenues::whereBetween('date', [$month_from, $month_to])->select(DB::raw("SUM(total_credit) as total_credit"))->first();
        }

        // Month
        $credit_month_query = Revenues::query();

            if ($request->filter_by == "date"|| $request->filter_by == "today") {
                $credit_month_query->whereDay('date', $symbol, $day_now)->whereMonth('date', $Fmonth)->whereYear('date', $Fyear);

            } elseif ($request->filter_by == "month" || $request->filter_by == "thisMonth" || $request->filter_by == "year" || $request->filter_by == "week" || $request->filter_by == "customRang") {
                $credit_month_query->whereBetween('date', [$month_from, $month_to]);

            } elseif ($request->filter_by == "thisYear") {
                $credit_month_query->whereBetween('date', [$month_to_date, $month_to_date2]);
            }

        $credit_month_query->select(DB::raw("SUM(total_credit) as total_credit"));
        $credit_revenue_month = $credit_month_query->first();

        // Year
        $credit_year_query = Revenues::query();
        
            if ($request->filter_by == "date") {
                $credit_year_query->whereDate('date', '<=', date($request->date));

            } elseif ($request->filter_by == "month") {
                $credit_year_query->whereBetween('date', [$month_from, $month_to]);

            } elseif ($request->filter_by == "year"  || $request->filter_by == "thisMonth") {
                $credit_year_query->whereBetween('date', [$year_from, $year_to]);
            }

        $credit_year_query->select(DB::raw("SUM(total_credit) as total_credit"));
        $credit_revenue_year = $credit_year_query->first();

        ### Front Desk ###
        // Today
        $today_front_revenue = Revenues::where('date', $date_now)->select(DB::raw("SUM(front_cash) as front_cash, SUM(front_transfer) as front_transfer, SUM(front_credit) as front_credit"))->first();

        // Date
        if ($request->filter_by == "week") {
            $total_front_revenue = Revenues::whereBetween('date', [$adate, $adate2])->select(DB::raw("SUM(front_cash) as front_cash, SUM(front_transfer) as front_transfer, SUM(front_credit) as front_credit"))->first();
            
        } else {
            $total_front_revenue = Revenues::whereBetween('date', [$month_from, $month_to])->select(DB::raw("SUM(front_cash) as front_cash, SUM(front_transfer) as front_transfer, SUM(front_credit) as front_credit"))->first();
        }

        // Month
        $total_front_month_query = Revenues::query();

            if ($request->filter_by == "date"|| $request->filter_by == "today") {
                $total_front_month_query->whereDay('date', $symbol, $day_now)->whereMonth('date', $Fmonth)->whereYear('date', $Fyear);

            } elseif ($request->filter_by == "month" || $request->filter_by == "thisMonth" || $request->filter_by == "year" || $request->filter_by == "week" || $request->filter_by == "customRang") {
                $total_front_month_query->whereBetween('date', [$month_from, $month_to]);

            } elseif ($request->filter_by == "thisYear") {
                $total_front_month_query->whereBetween('date', [$month_to_date, $month_to_date2]);
            }

        $total_front_month_query->select(DB::raw("SUM(front_cash) as front_cash, SUM(front_transfer) as front_transfer, SUM(front_credit) as front_credit"));
        $total_front_month = $total_front_month_query->first();

        // Year
        $total_front_year_query = Revenues::query();

            if ($request->filter_by == "date") {
                $total_front_year_query->whereDate('date', '<=', date($request->date));

            } elseif ($request->filter_by == "month") {
                $total_front_year_query->whereBetween('date', [$month_from, $month_to]);

            } elseif ($request->filter_by == "year" || $request->filter_by == "thisYear" || $request->filter_by == "thisMonth") {
                $total_front_year_query->whereBetween('date', [$year_from, $year_to]);
            }

        $total_front_year_query->select(DB::raw("SUM(front_cash) as front_cash, SUM(front_transfer) as front_transfer, SUM(front_credit) as front_credit"));
        $total_front_year = $total_front_year_query->first();

        // Charge
        $front_charge = Revenues::getManualCharge($request->filter_by, $month_from, $month_to, $date_now, $Fmonth, $Fyear, 6, 6);

        ### Guest Deposit ###
        // Today
        $today_guest_deposit = Revenues::where('date', $date_now)->select(DB::raw("SUM(room_cash) as room_cash, SUM(room_transfer) as room_transfer, SUM(room_credit) as room_credit"))->first();

        // Date
        if ($request->filter_by == "week") {
            $total_guest_deposit = Revenues::whereBetween('date', [$adate, $adate2])->select(DB::raw("SUM(room_cash) as room_cash, SUM(room_transfer) as room_transfer, SUM(room_credit) as room_credit"))->first();
        } else {
            $total_guest_deposit = Revenues::whereBetween('date', [$month_from, $month_to])->select(DB::raw("SUM(room_cash) as room_cash, SUM(room_transfer) as room_transfer, SUM(room_credit) as room_credit"))->first();
        }

        // Month
        $total_guest_deposit_month_query = Revenues::query();

        if ($request->filter_by == "date"|| $request->filter_by == "today") {
            $total_guest_deposit_month_query->whereDay('date', $symbol, $day_now)->whereMonth('date', $Fmonth)->whereYear('date', $Fyear);

        } elseif ($request->filter_by == "month" || $request->filter_by == "thisMonth" || $request->filter_by == "year" || $request->filter_by == "week" || $request->filter_by == "customRang") {
            $total_guest_deposit_month_query->whereBetween('date', [$month_from, $month_to]);

        } elseif ($request->filter_by == "thisYear") {
            $total_guest_deposit_month_query->whereBetween('date', [$month_to_date, $month_to_date2]);
        }

        $total_guest_deposit_month_query->select(DB::raw("SUM(room_cash) as room_cash, SUM(room_transfer) as room_transfer, SUM(room_credit) as room_credit"));
        $total_guest_deposit_month = $total_guest_deposit_month_query->first();

        // Year
        $guest_deposit_year_query = Revenues::query();

            if ($request->filter_by == "date") {
                $guest_deposit_year_query->whereDate('date', '<=', date($request->date));

            } elseif ($request->filter_by == "month") {
                $guest_deposit_year_query->whereBetween('date', [$month_from, $month_to]);

            } elseif ($request->filter_by == "year"  || $request->filter_by == "thisMonth") {
                $guest_deposit_year_query->whereBetween('date', [$year_from, $year_to]);
            }

        $guest_deposit_year_query->select(DB::raw("SUM(room_cash) as room_cash, SUM(room_transfer) as room_transfer, SUM(room_credit) as room_credit"));
        $total_guest_deposit_year = $guest_deposit_year_query->first();

        // Charge
        $guest_deposit_charge = Revenues::getManualCharge($request->filter_by, $month_from, $month_to, $date_now, $Fmonth, $Fyear, 1, 1);
 
        ### All Outlet ###
        // Today 
        $today_fb_revenue = Revenues::where('date', $date_now)->select(DB::raw("SUM(fb_cash) as fb_cash, SUM(fb_transfer) as fb_transfer, SUM(fb_credit) as fb_credit"))->first();

        // Date
        if ($request->filter_by == "week") {
            $total_fb_revenue = Revenues::whereBetween('date', [$adate, $adate2])->select(DB::raw("SUM(fb_cash) as fb_cash, SUM(fb_transfer) as fb_transfer, SUM(fb_credit) as fb_credit"))->first();
        } else {
            $total_fb_revenue = Revenues::whereBetween('date', [$month_from, $month_to])->select(DB::raw("SUM(fb_cash) as fb_cash, SUM(fb_transfer) as fb_transfer, SUM(fb_credit) as fb_credit"))->first();
        }

        // Month
        $fb_month_query = Revenues::query();
        
            if ($request->filter_by == "date"|| $request->filter_by == "today") {
                $fb_month_query->whereDay('date', $symbol, $day_now)->whereMonth('date', $Fmonth)->whereYear('date', $Fyear);

            } elseif ($request->filter_by == "month" || $request->filter_by == "thisMonth" || $request->filter_by == "year" || $request->filter_by == "week" || $request->filter_by == "customRang") {
                $fb_month_query->whereBetween('date', [$month_from, $month_to]);

            } elseif ($request->filter_by == "thisYear") {
                $fb_month_query->whereBetween('date', [$month_to_date, $month_to_date2]);
            }

        $fb_month_query->select(DB::raw("SUM(fb_cash) as fb_cash, SUM(fb_transfer) as fb_transfer, SUM(fb_credit) as fb_credit"));
        $total_fb_month = $fb_month_query->first();

        // Year
        $fb_year_query = Revenues::query();

            if ($request->filter_by == "date") {
                $fb_year_query->whereDate('date', '<=', date($request->date));

            } elseif ($request->filter_by == "month") {
                $fb_year_query->whereBetween('date', [$month_from, $month_to]);

            } elseif ($request->filter_by == "year"  || $request->filter_by == "thisMonth") {
                $fb_year_query->whereBetween('date', [$year_from, $year_to]);
            }

        $fb_year_query->select(DB::raw("SUM(fb_cash) as fb_cash, SUM(fb_transfer) as fb_transfer, SUM(fb_credit) as fb_credit"));
        $total_fb_year = $fb_year_query->first();

        // Charge
        $fb_charge = Revenues::getManualCharge($request->filter_by, $month_from, $month_to, $date_now, $Fmonth, $Fyear, 2, 2);

        ## Other Revenue ###
        // Today
        $today_other_revenue = Revenues::where('date', $date_now)->select('other_revenue')->sum('other_revenue');

        // Date
        if ($request->filter_by == "week") {
            $total_other_revenue = Revenues::whereBetween('date', [$adate, $adate2])->select('other_revenue')->sum('other_revenue');
        } else {
            $total_other_revenue = Revenues::whereBetween('date', [$month_from, $month_to])->select('other_revenue')->sum('other_revenue');
        }

        // dd([$adate, $adate2, $date_now]);

        // Month
        $other_month_query = Revenues::query();

        if ($request->filter_by == "date"|| $request->filter_by == "today") {
            $other_month_query->whereDay('date', $symbol, $day_now)->whereMonth('date', date('m'))->whereYear('date', date('Y'));

        } elseif ($request->filter_by == "month" || $request->filter_by == "thisMonth" || $request->filter_by == "year" || $request->filter_by == "week" || $request->filter_by == "customRang") {
            $other_month_query->whereBetween('date', [$month_from, $month_to]);

        } elseif ($request->filter_by == "thisYear") {
            $other_month_query->whereBetween('date', [$month_to_date, $month_to_date2]);
        }

        $other_month_query->select('other_revenue');
        $total_other_month = $other_month_query->sum('other_revenue');

        // Year
        $other_year_query = Revenues::query();

            if ($request->filter_by == "date") {
                $other_year_query->whereDate('date', '<=', date($request->date));

            } elseif ($request->filter_by == "month") {
                $other_year_query->whereBetween('date', [$month_from, $month_to]);

            } elseif ($request->filter_by == "year"  || $request->filter_by == "thisMonth") {
                $other_year_query->whereBetween('date', [$year_from, $year_to]);
            }

        $other_year_query->select('other_revenue');
        $total_other_year = $other_year_query->sum('other_revenue');

        ### Agoda ###
        // Today
        $today_agoda_revenue = Revenues::where('date', $date_now)->sum('total_credit_agoda');

        // Date
        if ($request->filter_by == "week") {
            $total_agoda_revenue = Revenues::whereBetween('date', [$adate, $adate2])->sum('total_credit_agoda');
        } else {
            $total_agoda_revenue = Revenues::whereBetween('date', [$month_from, $month_to])->sum('total_credit_agoda');
        }

        // Month
        $agoda_month_query = Revenues::query();

            if ($request->filter_by == "date"|| $request->filter_by == "today") {
                $agoda_month_query->whereDay('date', $symbol, $day_now)->whereMonth('date', $Fmonth)->whereYear('date', date('Y'));

            } elseif ($request->filter_by == "month" || $request->filter_by == "thisMonth" || $request->filter_by == "year" || $request->filter_by == "week" || $request->filter_by == "customRang") {
                $agoda_month_query->whereBetween('date', [$month_from, $month_to]);

            } elseif ($request->filter_by == "thisYear") {
                $agoda_month_query->whereBetween('date', [$month_to_date, $month_to_date2]);
            }

        $total_agoda_month = $agoda_month_query->sum('total_credit_agoda');

        // Year
        $agoda_year_query = Revenues::query();
        
            if ($request->filter_by == "date") {
                $agoda_year_query->whereDate('date', '<=', date($request->date));

            } elseif ($request->filter_by == "month") {
                $agoda_year_query->whereBetween('date', [$month_from, $month_to]);

            } elseif ($request->filter_by == "year"  || $request->filter_by == "thisMonth") {
                $agoda_year_query->whereBetween('date', [$year_from, $year_to]);
            }

        $total_agoda_year = $agoda_year_query->sum('total_credit_agoda');

        // Charge
        $agoda_charge = Revenues::getManualAgodaCharge($request->filter_by, $month_from, $month_to, $date_now, $Fmonth, $Fyear, 1, 5);

        ### Water Park ###
        // Today
        $today_wp_revenue = Revenues::where('date', $date_now)->select(DB::raw("SUM(wp_cash) as wp_cash, SUM(wp_transfer) as wp_transfer, SUM(wp_credit) as wp_credit"))->first();

        // Date
        if ($request->filter_by == "week") {
            $total_wp_revenue = Revenues::whereBetween('date', [$adate, $adate2])->select(DB::raw("SUM(wp_cash) as wp_cash, SUM(wp_transfer) as wp_transfer, SUM(wp_credit) as wp_credit"))->first();
        } else {
            $total_wp_revenue = Revenues::whereBetween('date', [$month_from, $month_to])->select(DB::raw("SUM(wp_cash) as wp_cash, SUM(wp_transfer) as wp_transfer, SUM(wp_credit) as wp_credit"))->first();
        }

        // Month
        $wp_month_query = Revenues::query();
        
            if ($request->filter_by == "date"|| $request->filter_by == "today") {
                $wp_month_query->whereDay('date', $symbol, $day_now)->whereMonth('date', $Fmonth)->whereYear('date', $Fyear);

            } elseif ($request->filter_by == "month" || $request->filter_by == "thisMonth" || $request->filter_by == "year" || $request->filter_by == "week" || $request->filter_by == "customRang") {
                $wp_month_query->whereBetween('date', [$month_from, $month_to]);
                
            } elseif ($request->filter_by == "thisYear") {
                $wp_month_query->whereBetween('date', [$month_to_date, $month_to_date2]);
            }

        $wp_month_query->select(DB::raw("SUM(wp_cash) as wp_cash, SUM(wp_transfer) as wp_transfer, SUM(wp_credit) as wp_credit"));
        $total_wp_month = $wp_month_query->first();

        // Year
        $wp_year_query = Revenues::query();
        
            if ($request->filter_by == "date") {
                $wp_year_query->whereDate('date', '<=', date($date_now));

            } elseif ($request->filter_by == "month") {
                $wp_year_query->whereBetween('date', [$month_from, $month_to]);

            } elseif ($request->filter_by == "year"  || $request->filter_by == "thisMonth") {
                $agoda_year_query->whereBetween('date', [$year_from, $year_to]);
            }

        $wp_year_query->select(DB::raw("SUM(wp_cash) as wp_cash, SUM(wp_transfer) as wp_transfer, SUM(wp_credit) as wp_credit"));
        $total_wp_year = $wp_year_query->first();

        // Charge
        $wp_charge = Revenues::getManualCharge($request->filter_by, $month_from, $month_to, $date_now, $Fmonth, $Fyear, 3, 3);

        ### Elexa EGAT ###
        // Today
        $today_ev_revenue = Revenues::where('date', $date_now)->select('total_elexa')->sum('total_elexa');

        // Date
        if ($request->filter_by == "week") {
            $total_ev_revenue = Revenues::whereBetween('date', [$adate, $adate2])->select('total_elexa')->sum('total_elexa');
        } else {
            $total_ev_revenue = Revenues::whereBetween('date', [$month_from, $month_to])->select('total_elexa')->sum('total_elexa');
        }

        // Month
        $ev_month_query = Revenues::query();
        
            if ($request->filter_by == "date"|| $request->filter_by == "today") {
                $ev_month_query->whereDay('date', $symbol, $day_now)->whereMonth('date', date('m'))->whereYear('date', date('Y'));

            } elseif ($request->filter_by == "month" || $request->filter_by == "thisMonth" || $request->filter_by == "year" || $request->filter_by == "week" || $request->filter_by == "customRang") {
                $ev_month_query->whereBetween('date', [$month_from, $month_to]);

            } elseif ($request->filter_by == "thisYear") {
                $ev_month_query->whereBetween('date', [$month_to_date, $month_to_date2]);
            }

        $ev_month_query->select('total_elexa');
        $total_ev_month = $ev_month_query->sum('total_elexa');

        // Year
        $ev_year_query = Revenues::query();
        
            if ($request->filter_by == "date") {
                $ev_year_query->whereDate('date', '<=', $date_now);

            } elseif ($request->filter_by == "month") {
                $ev_year_query->whereBetween('date', [$month_from, $month_to]);

            } elseif ($request->filter_by == "year"  || $request->filter_by == "thisMonth") {
                $agoda_year_query->whereBetween('date', [$year_from, $year_to]);
            }

        $ev_year_query->select('total_elexa');
        $total_ev_year = $ev_year_query->sum('total_elexa');

        // Charge
        $ev_charge = Revenues::getManualEvCharge($request->filter_by, $month_from, $month_to, $date_now, $Fmonth, $Fyear, 8, 8);

        ## Filter ##
        $filter_by = $request->filter_by;
        $search_date = $request->date;
        // $day = $request->day;
        // $month = $request->month;
        // $month_to = $request->month_to;
        // $year = $request->year;
        // $time = $request->time;

        $by_page = 'index';
        $by_page_pdf = '1A';
        $btn_by_page = $request->daily_page;

        if (isset($request->daily_page)) {
            $by_page = 'index_'.$request->daily_page;
            $by_page_pdf = '1A-'.$request->daily_page;
        }

            if ($request->export_pdf == 1) {
                $pdf = FacadePdf::loadView('pdf.revenue.'.$by_page_pdf, 
                    compact(
                        'total_revenue_today', 'total_day', 
                        'total_verified', 'total_unverified', 'total_agoda_outstanding',
                        'total_ev_outstanding', 'total_transfer', 

                        'total_transfer2',

                        'total_split',
                        'total_split_transaction',

                        'credit_revenue_today', 'credit_revenue', 'credit_revenue_month', 'credit_revenue_year',

                        'today_front_revenue', 'total_front_revenue', 'total_front_month', 'total_front_year', 'front_charge',

                        'today_guest_deposit', 'total_guest_deposit', 'total_guest_deposit_month', 'total_guest_deposit_year', 'guest_deposit_charge',

                        'today_fb_revenue', 'total_fb_revenue', 'total_fb_month', 'total_fb_year', 'fb_charge',

                        'today_agoda_revenue', 'total_agoda_revenue', 'total_agoda_month', 'total_agoda_year', 'agoda_charge',

                        'total_credit_transaction',

                        'today_wp_revenue',
                        'total_wp_revenue',
                        'total_wp_month',
                        'total_wp_year',
                        'wp_charge',

                        'total_not_type',

                        'total_not_type_revenue',

                        'today_ev_revenue',
                        'total_ev_revenue',
                        'total_ev_month',
                        'total_ev_year',
                        'ev_charge',

                        'today_other_revenue',
                        'total_other_revenue',
                        'total_other_month',
                        'total_other_year',

                        'btn_by_page',

                        'filter_by', 'search_date'
                    )
                );
                return $pdf->stream();
            } else {
                return view('revenue.'.$by_page, compact(
                    'total_revenue_today', 
                    'total_day', 
                    'total_verified', 
                    'total_unverified', 
                    'total_agoda_outstanding',
                    'total_ev_outstanding',
                    'total_transfer', 
        
                    'total_transfer2',
        
                    'total_split',
        
                    'total_split_transaction',
        
                    'credit_revenue_today',
                    'credit_revenue',
                    'credit_revenue_month',
                    'credit_revenue_year',
        
                    'today_front_revenue', 
                    'total_front_revenue',
                    'total_front_month',
                    'total_front_year',
                    'front_charge',
        
                    'today_guest_deposit',
                    'total_guest_deposit',
                    'total_guest_deposit_month',
                    'total_guest_deposit_year',
                    'guest_deposit_charge',
        
                    'today_fb_revenue',
                    'total_fb_revenue',
                    'total_fb_month',
                    'total_fb_year',
                    'fb_charge',
        
                    'today_agoda_revenue',
                    'total_agoda_revenue',
                    'total_agoda_month',
                    'total_agoda_year',
                    'agoda_charge',
        
                    'total_credit_transaction',
        
                    'today_wp_revenue',
                    'total_wp_revenue',
                    'total_wp_month',
                    'total_wp_year',
                    'wp_charge',
        
                    'total_not_type',
        
                    'total_not_type_revenue',
        
                    'today_ev_revenue',
                    'total_ev_revenue',
                    'total_ev_month',
                    'total_ev_year',
                    'ev_charge',

                    'today_other_revenue',
                    'total_other_revenue',
                    'total_other_month',
                    'total_other_year',
        
                    'btn_by_page',
        
                    'filter_by', 'search_date'));
            }
        }
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
        Revenue_credit::where('revenue_id', $check_credit->id)->where('status', '!=', 5)->delete();

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
            Revenue_credit::whereNotIn('batch', $request->agoda_batch)->where('revenue_id', $check_credit->id)->where('status', 5)->delete();
            foreach ($request->agoda_batch as $key => $value) {
                $agoda_charge += $request->agoda_credit_amount[$key];
                $agoda_outstanding += $request->agoda_credit_outstanding[$key];

                $check_agoda = Revenue_credit::where('batch', $request->agoda_batch[$key])->where('revenue_id', $check_credit->id)->where('status', 5)->first();

                if (!empty($check_agoda)) {
                    Revenue_credit::where('batch', $request->agoda_batch[$key])->where('revenue_id', $check_credit->id)->update([
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
                } else {
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
        } else {
            if (!isset($request->agoda_batch)) {
                Revenue_credit::where('revenue_id', $check_credit->id)->where('status', 5)->delete();
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

    public function detail(Request $request)
    {

        if ($request->filter_by == "date" || $request->filter_by == "today" || $request->filter_by == "yesterday" || $request->filter_by == "tomorrow") {
            $req_date = Carbon::parse($request->date)->format('Y-m-d');
            $adate = date('Y-m-d 21:00:00', strtotime($req_date));
            $from = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime(date($adate))));
            $to = date('Y-m-d 20:59:59', strtotime($adate));

            // Revenue
            $month_from = $req_date;
            $month_to = $req_date;
            $date_first_day = date('Y-m-d', strtotime('first day of this month', strtotime($req_date)));

        } elseif ($request->filter_by == "month") {
            $exp = explode('-', $request->date);

            $start_month = Carbon::parse($exp[0])->format('m');
            $end_month = Carbon::parse($exp[0])->format('m');
            $year = Carbon::parse($exp[0])->format('Y');

            if (isset($exp[1])) { // เลือกมากกว่า 1 เดือน
                $end_month = Carbon::parse($exp[1])->format('m');
                $year = Carbon::parse($exp[1])->format('Y');
            }

            $adate = date('Y-m-d', strtotime($year . '-' . $start_month . '-01'));
            $lastday = dayLast($end_month, $year); // หาวันสุดท้ายของเดือน

            $from = date('Y-m-d' . ' 21:00:00', strtotime('-1 day', strtotime(date($adate))));
            $to = date($year . '-' . $end_month . '-' . $lastday . ' 20:59:59');

            $month_from = date('Y-m-d', strtotime($adate));
            $month_to = date('Y-m-d', strtotime('last day of this month', strtotime(date($to))));
            $date_first_day = date('Y-m-d', strtotime('first day of this month', strtotime($adate)));

        } elseif ($request->filter_by == "year") {
            $year = $request->date;
            $adate = date($year . '-01' . '-01');
            $from = date('Y-m-d' . ' 21:00:00', strtotime('-1 day', strtotime(date($adate))));
            $to = date($year . '-12-31' . ' 20:59:59');

            $month_from = date('Y-m-d', strtotime($adate));
            $month_to = date('Y-m-d', strtotime('last day of this month', strtotime(date($to))));
            $date_first_day = date('Y-m-d', strtotime('first day of this month', strtotime(date($adate))));

        } elseif ($request->filter_by == "week") {
            $lastday = dayLast(date('m'), date('Y')); // หาวันสุดท้ายของเดือน
            $sundayOfWeek = date('Y-m-d', strtotime('last sunday', strtotime('next sunday')));
            $adate = $sundayOfWeek;
            $adate2 = date('Y-m-d', strtotime('+6 day', strtotime(date($adate))));

            $from = date('Y-m-d' . ' 21:00:00', strtotime('-1 day', strtotime(date($adate))));
            $to = date('Y-m-d' . ' 20:59:59', strtotime(date($adate2)));

            $month_from = date('Y-m-d', strtotime(date('Y-m-01')));
            $month_to = date('Y-m-d', strtotime(date($adate2)));
            $date_first_day = $adate;

            $year_from = date('Y-m-d', strtotime(date('Y-01-01')));
            $year_to = $adate2;

        } elseif ($request->filter_by == "thisMonth") {
            $lastday = dayLast(date('m'), date('Y')); // หาวันสุดท้ายของเดือน
            $adate = date('Y-m-d', strtotime(date('Y-m-01')));

            $from = date('Y-m-d' . ' 21:00:00', strtotime('-1 day', strtotime(date($adate))));
            $to = date('Y-m-d 20:59:59', strtotime(date('Y-m-d')));

            $month_from = date('Y-m-d', strtotime($adate));
            $month_to = date('Y-m-d', strtotime('last day of this month', strtotime(date($to))));
            $date_first_day = $adate;

            $year_from = date('Y-m-d', strtotime(date('Y-01-01')));
            $year_to = date('Y-m-d', strtotime(date($to)));

        } elseif ($request->filter_by == "thisYear") {
            $lastday = dayLast(date('m'), date('Y')); // หาวันสุดท้ายของเดือน
            $adate = date('Y-m-d', strtotime(date('Y-01-01')));

            $from = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime(date($adate))));
            $to = date('Y-m-d 20:59:59');

            $month_from = date('Y-m-d', strtotime($adate));
            $month_to = date('Y-m-d');
            $date_first_day = $adate; 

            $month_to_date = date('Y-m-d', strtotime(date('Y-01-01')));
            $month_to_date2 = date('Y-m-d', strtotime(date('Y-m-d')));

            $year_from = date('Y-m-d', strtotime(date('Y-01-01')));
            $year_to = date('Y-m-d');

        } elseif ($request->filter_by == "customRang") {
            $adate = date('Y-m-d', strtotime(date($request->customRang_start)));
            $adate2 = date('Y-m-d', strtotime(date($request->customRang_end)));

            $from = date('Y-m-d' . ' 21:00:00', strtotime('-1 day', strtotime(date($adate))));
            $to = date('Y-m-d' . ' 20:59:59', strtotime(date($adate2)));

            $month_from = date('Y-m-d', strtotime($adate));
            $month_to = date('Y-m-d', strtotime(date($to)));
            $date_first_day = $adate;
        }

        if ($request->filter_by == "date" || $request->filter_by == "today" || $request->filter_by == "yesterday" || $request->filter_by == "tomorrow") 
        {
            $date_now = date('Y-m-d', strtotime($request->date));
        } else {
            $date_now = date('Y-m-d');
        }

        ## Bank Transfer
        if ($request->revenue_type == "tf_front") {
            $data_query = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 6)->orWhereDate('date_into', '>=', $month_from)->whereDate('date_into', '<=', $month_to)->where('status', 6)->paginate(10);
            $total_query = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 6)->orWhereDate('date_into', '>=', $month_from)->whereDate('date_into', '<=', $month_to)->where('status', 6)->sum('amount');
            $title = "Front Desk";
            $status = 6;
            $revenue_name = "";

        } if($request->revenue_type == "tf_guest") {
            $data_query = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 1)->whereNull('date_into')->orWhereDate('date_into', '>=', $month_from)->whereDate('date_into', '<=', $month_to)->where('status', 1)->paginate(10);
            $total_query = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 1)->whereNull('date_into')->orWhereDate('date_into', '>=', $month_from)->whereDate('date_into', '<=', $month_to)->where('status', 1)->sum('amount');
            $title = "Guest Deposit";
            $status = 1;
            $revenue_name = "";

        } if($request->revenue_type == "tf_all_outlet") {
            $data_query = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 2)->whereNull('date_into')->orWhereDate('date_into', '>=', $month_from)->whereDate('date_into', '<=', $month_to)->where('status', 2)->paginate(10);
            $total_query = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 2)->whereNull('date_into')->orWhereDate('date_into', '>=', $month_from)->whereDate('date_into', '<=', $month_to)->where('status', 2)->sum('amount');
            $title = "All Outlet Revenue";
            $status = 2;
            $revenue_name = "";

        } if ($request->revenue_type == "tf_water_park") {
            $data_query = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 3)->whereNull('date_into')->orWhereDate('date_into', '>=', $month_from)->whereDate('date_into', '<=', $month_to)->where('status', 3)->paginate(10);
            $total_query = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 3)->whereNull('date_into')->orWhereDate('date_into', '>=', $month_from)->whereDate('date_into', '<=', $month_to)->where('status', 3)->sum('amount');
            $title = "Water Park Revenue";
            $status = 3;
            $revenue_name = "";

        } if($request->revenue_type == "tf_agoda") {
            $data_query = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 5)->whereNull('date_into')->orWhereDate('date_into', '>=', $month_from)->whereDate('date_into', '<=', $month_to)->where('status', 5)->paginate(10);
            $total_query = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 5)->whereNull('date_into')->orWhereDate('date_into', '>=', $month_from)->whereDate('date_into', '<=', $month_to)->where('status', 5)->sum('amount');
            $title = "Agoda Revenue";
            $status = 5;
            $revenue_name = "";

        } if($request->revenue_type == "tf_elexa") {
            $data_query = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 8)->whereNull('date_into')->orWhereDate('date_into', '>=', $month_from)->whereDate('date_into', '<=', $month_to)->where('status', 8)->paginate(10);
            $total_query = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 8)->whereNull('date_into')->orWhereDate('date_into', '>=', $month_from)->whereDate('date_into', '<=', $month_to)->where('status', 8)->sum('amount');
            $title = "Elexa EGAT Revenue";
            $status = 8;
            $revenue_name = "";

        } if($request->revenue_type == "tf_other") {
            $data_query = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 9)->whereNull('date_into')->orWhereDate('date_into', '>=', $month_from)->whereDate('date_into', '<=', $month_to)->where('status', 9)->paginate(10);
            $total_query = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 9)->whereNull('date_into')->orWhereDate('date_into', '>=', $month_from)->whereDate('date_into', '<=', $month_to)->where('status', 9)->sum('amount');
            $title = "Other Revenue";
            $status = 9;
            $revenue_name = "";

        } 

        ## Credit Card
        if($request->revenue_type == "cc_credit_hotel") {
            $data_query = SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', "708-226792-1")->where('status', 4)->whereNull('date_into')->orWhereDate('date_into', '>=', $month_from)->whereDate('date_into', '<=', $month_to)->where('into_account', "708-226792-1")->where('status', 4)->paginate(10);
            $total_query = SMS_alerts::whereBetween('date', [$from, $to])->where('into_account', "708-226792-1")->where('status', 4)->whereNull('date_into')->orWhereDate('date_into', '>=', $month_from)->whereDate('date_into', '<=', $month_to)->where('into_account', "708-226792-1")->where('status', 4)->sum('amount');
            $title = "Credit Card Hotel Revenue";
            $status = 4;
            $revenue_name = "";

        } if($request->revenue_type == "cc_credit_water_park") {
            $data_query = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 7)->whereNull('date_into')->orWhereDate('date_into', '>=', $month_from)->whereDate('date_into', '<=', $month_to)->where('status', 7)->paginate(10);
            $total_query = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 7)->whereNull('date_into')->orWhereDate('date_into', '>=', $month_from)->whereDate('date_into', '<=', $month_to)->where('status', 7)->sum('amount');
            $title = "Credit Card Water Park Revenue";
            $status = 7;
            $revenue_name = "";

        } 

        ## Manual Charge
        if($request->revenue_type == "mc_front_charge") {
            $data_query = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 6)
                ->where('revenue_credit.revenue_type', 6)->whereBetween('revenue.date', [$month_from, $month_to])
                ->select('revenue.date', 'revenue.total_credit', 'revenue_credit.batch', 'revenue_credit.revenue_type', 'revenue_credit.credit_amount', 'revenue_credit.status')->paginate(10);
            $total_query = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 6)
                ->where('revenue_credit.revenue_type', 6)->whereBetween('revenue.date', [$month_from, $month_to])->sum('revenue_credit.credit_amount');
            $title = "Credit Card Front Desk";
            $status = "manual_charge_6";
            $revenue_name = "";

        } if($request->revenue_type == "mc_guest_charge") {
            $data_query = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 1)
                ->where('revenue_credit.revenue_type', 1)->whereBetween('revenue.date', [$month_from, $month_to])
                ->select('revenue.date', 'revenue.total_credit', 'revenue_credit.batch', 'revenue_credit.revenue_type', 'revenue_credit.credit_amount', 'revenue_credit.status')->paginate(10);
            $total_query = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 1)
                ->where('revenue_credit.revenue_type', 1)->whereBetween('revenue.date', [$month_from, $month_to])->sum('revenue_credit.credit_amount');
            $title = "Credit Card Guest Deposit";
            $status = "manual_charge_1";
            $revenue_name = "";

        } if($request->revenue_type == "mc_all_outlet_charge") {
            $data_query = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 2)
                ->where('revenue_credit.revenue_type', 2)->whereBetween('revenue.date', [$month_from, $month_to])
                ->select('revenue.date', 'revenue.total_credit', 'revenue_credit.batch', 'revenue_credit.revenue_type', 'revenue_credit.credit_amount', 'revenue_credit.status')->paginate(10);
            $total_query = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 2)
                ->where('revenue_credit.revenue_type', 2)->whereBetween('revenue.date', [$month_from, $month_to])->sum('revenue_credit.credit_amount');
            $title = "Credit Card All Outlet";
            $status = "manual_charge_2";
            $revenue_name = "";

        } if($request->revenue_type == "mc_water_park_charge") {
            $data_query = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 3)
                ->where('revenue_credit.revenue_type', 3)->whereBetween('revenue.date', [$month_from, $month_to])
                ->select('revenue.date', 'revenue.total_credit', 'revenue_credit.batch', 'revenue_credit.revenue_type', 'revenue_credit.credit_amount', 'revenue_credit.status')->paginate(10);
            $total_query = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 3)
                ->where('revenue_credit.revenue_type', 3)->whereBetween('revenue.date', [$month_from, $month_to])->sum('revenue_credit.credit_amount');
            $title = "Credit Card Water Park";
            $status = "manual_charge_3";
            $revenue_name = "";

        } if($request->revenue_type == "mc_agoda_charge") {
            $data_query = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 5)
                ->where('revenue_credit.revenue_type', 1)->whereBetween('revenue.date', [$month_from, $month_to])
                ->select('revenue.date', 'revenue_credit.batch', 'revenue_credit.agoda_charge', 'revenue_credit.agoda_outstanding', 'revenue_credit.status')->paginate(10);
            $total_query = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 5)
                ->where('revenue_credit.revenue_type', 1)->whereBetween('revenue.date', [$month_from, $month_to])->sum('revenue_credit.agoda_charge');
            $title = "Agoda Charge";
            $status = "mc_agoda_charge";
            $revenue_name = "";

        } if($request->revenue_type == "mc_elexa_charge") {
            $data_query = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')
                ->where('revenue_credit.status', 8)->where('revenue_credit.revenue_type', 8)->whereBetween('revenue.date', [$month_from, $month_to])
                ->select('revenue_credit.ev_charge', 'revenue_credit.ev_fee', 'revenue_credit.ev_vat', 'revenue_credit.ev_revenue')->paginate(10);
            $total_query = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')
                ->where('revenue_credit.status', 8)->where('revenue_credit.revenue_type', 8)->whereBetween('revenue.date', [$month_from, $month_to])->sum('revenue_credit.ev_charge');
            $title = "Elexa EGAT Charge";
            $status = "mc_elexa_charge";
            $revenue_name = "";
        } 

        ## Total Revenue Outstanding
        if($request->revenue_type == "agoda_outstanding") {
            $data_query = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 5)
                ->where('revenue_credit.revenue_type', 1)->whereBetween('revenue.date', [$month_from, $month_to])
                ->select('revenue.date', 'revenue_credit.batch', 'revenue_credit.agoda_charge', 'revenue_credit.agoda_outstanding', 'revenue_credit.status')->paginate(10);
            $total_query = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 5)
                ->where('revenue_credit.revenue_type', 1)->whereBetween('revenue.date', [$month_from, $month_to])->sum('revenue_credit.agoda_outstanding');
            $title = "Credit Agoda Revenue Outstanding";
            $status = "agoda_outstanding";
            $revenue_name = "";

        } if($request->revenue_type == "elexa_outstanding") {
            $data_query = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')
                ->where('revenue_credit.status', 8)->where('revenue_credit.revenue_type', 8)->whereBetween('revenue.date', [$month_from, $month_to])
                ->select('revenue_credit.ev_charge', 'revenue_credit.ev_fee', 'revenue_credit.ev_vat', 'revenue_credit.ev_revenue')->paginate(10);
            $total_query = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')
                ->where('revenue_credit.status', 8)->where('revenue_credit.revenue_type', 8)->whereBetween('revenue.date', [$month_from, $month_to])->sum('revenue_credit.ev_revenue');
            $title = "Elexa EGAT Revenue Outstanding";
            $status = "elexa_outstanding";
            $revenue_name = "";

        } 

        ## Type
        if ($request->revenue_type == "transfer_revenue") {
            $data_query = SMS_alerts::whereDate('date_into', '>=', $month_from)->whereDate('date_into', '<=', $month_to)->where('transfer_status', 1)->paginate(10);
            $total_query = SMS_alerts::whereDate('date_into', '>=', $month_from)->whereDate('date_into', '<=', $month_to)->where('transfer_status', 1)->sum('amount');
            $title = "Transfer Revenue";
            $status = 'transfer_revenue';
            $revenue_name = "type";

        } if ($request->revenue_type == "credit_hotel_transfer") {
            $data_query = SMS_alerts::whereDate('date_into', '>=', $month_from)->whereDate('date_into', '<=', $month_to)->where('into_account', "708-226792-1")->where('status', 4)->paginate(10);
            $total_query = SMS_alerts::whereDate('date_into', '>=', $month_from)->whereDate('date_into', '<=', $month_to)->where('into_account', "708-226792-1")->where('status', 4)->count();
            $title = "Credit Card Hotel Transfer Transaction";
            $status = 'credit_hotel_transfer';
            $revenue_name = "type";

        } if ($request->revenue_type == "split_hotel_revenue") {
            $data_query = SMS_alerts::where('date_into', [$from, $to])->where('split_status', 1)->paginate(10);
            $total_query = SMS_alerts::where('date_into', [$from, $to])->where('split_status', 1)->sum('amount');
            $title = "Split Credit Card Hotel Revenue";
            $status = 'split_hotel_revenue';
            $revenue_name = "type";
            
        } if ($request->revenue_type == "split_hotel_transaction") {
            $data_query = SMS_alerts::whereBetween('date', [$from, $to])->where('split_status', 1)->paginate(10);
            $total_query = SMS_alerts::where('date_into', [$from, $to])->where('split_status', 1)->sum('amount');
            $title = "Split Credit Card Hotel Transaction";
            $status = 'split_hotel_transaction';
            $revenue_name = "type";

        } if ($request->revenue_type == "no_income_revenue") {
            $data_query = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 0)->whereNull('date_into')->paginate(10);
            $total_query = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 0)->whereNull('date_into')->sum('amount');
            $title = "No Income Revenue";
            $status = 'no_income_revenue';
            $revenue_name = "type";

        } if ($request->revenue_type == "total_transaction") {
            $data_query = SMS_alerts::whereBetween('date', [$from, $to])->orWhereDate('date_into', $adate)->paginate(10);
            $total_query = SMS_alerts::whereBetween('date', [$from, $to])->orWhereDate('date_into', $adate)->sum('amount');
            $title = "Total Trandaction";
            $status = 'total_transaction';
            $revenue_name = "type";

        } if ($request->revenue_type == "transfer_transaction") {
            $data_query = SMS_alerts::whereBetween('date_into', [$from, $to])->where('transfer_status', 1)->paginate(10);
            $total_query = SMS_alerts::whereBetween('date_into', [$from, $to])->where('transfer_status', 1)->sum('amount');
            $title = "Transfer Trandaction";
            $status = 'transfer_transaction';
            $revenue_name = "type";

        } if ($request->revenue_type == "no_income_type") {
            $data_query = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 0)->whereNull('date_into')->paginate(10);
            $total_query = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 0)->whereNull('date_into')->sum('amount');
            $title = "No Incoming Type";
            $status = 'no_income_type';
            $revenue_name = "type";
        }

        ## Verified / Unverified
        $date1 = date('Y-m-01', strtotime($month_from));
        $date2 = date('Y-m-d', strtotime('last day of this month', strtotime($month_from)));

        if ($request->revenue_type == "verified") {
            $data_query = Revenues::whereBetween('date', [$date1, $date2])->where('status', 1)->paginate(10);
            $total_query = Revenues::whereBetween('date', [$date1, $date2])->where('status', 1)->count();
            $title = "Verified";
            $status = 'verified';
            $revenue_name = "verified";

        } if ($request->revenue_type == "unverified") {
            $data_query = Revenues::whereBetween('date', [$date1, $date2])->where('status', 0)->paginate(10);
            $total_query = Revenues::whereBetween('date', [$date1, $date2])->where('status', 0)->count();
            $title = "Unverified";
            $status = 'unverified';
            $revenue_name = "verified";

        }

        // if($request->revenue_type == "fee_credit_hotel") {
        //     $data_query = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->whereIn('revenue_credit.status', [1, 2, 6])
        //         ->whereBetween('revenue.date', [$month_from, $month_to])
        //         ->select('revenue.date', 'revenue_credit.batch', 'revenue_credit.status', DB::raw("(SUM(revenue_credit.credit_amount) - revenue.total_credit) as fee"))->paginate(10);
        //     $total_query = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')
        //         ->whereIn('revenue_credit.status', [1, 2, 6])->whereBetween('revenue.date', [$month_from, $month_to])
        //         ->select('revenue.date', 'revenue_credit.status', DB::raw("(SUM(revenue_credit.credit_amount) - revenue.total_credit) as fee"))->first();
        //     $title = "Credit Card Hotel Fee";
        //     $status = "fee_all";

        // }

        ## Filter ##
        $filter_by = $request->filter_by;
        $search_date = $month_from;

        $exp = explode("_", $request->revenue_type);

        if ($exp[0] == "mc" && $request->revenue_type != "mc_agoda_charge" && $request->revenue_type != "mc_elexa_charge") {
            return view('revenue.manual_charge_detail', compact('data_query', 'total_query', 'title', 'filter_by', 'day', 'month', 'month_to', 'year', 'status'));
        } elseif ($request->revenue_type == "mc_agoda_charge") {
            return view('revenue.manual_agoda_charge_detail', compact('data_query', 'total_query', 'title', 'filter_by', 'day', 'month', 'month_to', 'year', 'status'));
        } elseif ($request->revenue_type == "mc_elexa_charge") {
            return view('revenue.manual_elexa_charge_detail', compact('data_query', 'total_query', 'title', 'filter_by', 'day', 'month', 'month_to', 'year', 'status'));
        } elseif ($request->revenue_type == "agoda_outstanding") {
            return view('revenue.agoda_outstanding_detail', compact('data_query', 'total_query', 'title', 'filter_by', 'day', 'month', 'month_to', 'year', 'status'));
        } elseif ($request->revenue_type == "elexa_outstanding") {
            return view('revenue.elexa_outstanding_detail', compact('data_query', 'total_query', 'title', 'filter_by', 'day', 'month', 'month_to', 'year', 'status'));
        } elseif ($revenue_name == "type") {
            return view('revenue.type_detail', compact('data_query', 'total_query', 'title', 'filter_by', 'day', 'month', 'month_to', 'year', 'status'));
        } elseif ($revenue_name == "verified") {
            return view('revenue.verified_detail', compact('data_query', 'total_query', 'title', 'filter_by', 'day', 'month', 'month_to', 'year', 'status'));
        }
        // elseif ($exp[0] == "fee") {
        //     return view('revenue.fee_detail', compact('data_query', 'total_query', 'title', 'filter_by', 'day', 'month', 'month_to', 'year', 'status'));
        // } 
        else {
            return view('revenue.detail', compact('data_query', 'total_query', 'title', 'filter_by', 'search_date', 'status'));
        }
    }

    public function paginate_table(Request $request)
    {
        if ($request->filter_by == "date" || $request->filter_by == "today" || $request->filter_by == "yesterday" || $request->filter_by == "tomorrow") {
            $req_date = Carbon::parse($request->date)->format('Y-m-d');
            $adate = $req_date;
            $adate2 = date('Y-m-d', strtotime(date($adate)));

            $from = date('Y-m-d' . ' 21:00:00', strtotime('-1 day', strtotime(date($adate))));
            $to = date('Y-m-d 20:59:59', strtotime($adate));

        } elseif ($request->filter_by == "month") {
            $exp = explode('-', $request->date);

            $start_month = Carbon::parse($exp[0])->format('m');
            $end_month = Carbon::parse($exp[0])->format('m');
            $year = Carbon::parse($exp[0])->format('Y');

            if (isset($exp[1])) { // เลือกมากกว่า 1 เดือน
                $end_month = Carbon::parse($exp[1])->format('m');
                $year = Carbon::parse($exp[1])->format('Y');
            }

            $lastday = dayLast($end_month, $year); // หาวันสุดท้ายของเดือน
            $adate = date('Y-m-d', strtotime($year . '-' . $start_month . '-01'));
            $adate2 = date('Y-m-d', strtotime($year . '-' . $end_month . '-' . $lastday));

            $from = date('Y-m-d' . ' 21:00:00', strtotime('-1 day', strtotime(date($adate))));
            $to = date($year . '-' . $end_month . '-' . $lastday . ' 20:59:59');

        } elseif ($request->filter_by == "thisMonth") {
            $lastday = dayLast(date('m'), date('Y')); // หาวันสุดท้ายของเดือน
            $adate = date('Y-m-01');
            $adate2 = date('Y-m-' . $lastday);

            $from = date('Y-m-d' . ' 21:00:00', strtotime('-1 day', strtotime(date($adate))));
            $to = date('Y-m-d 20:59:59', strtotime($adate2));

        } elseif ($request->filter_by == "year") {
            $year = $request->date;
            $adate = date('Y-m-d', strtotime($year . '-01' . '-01'));
            $adate2 = date('Y-m-d', strtotime(date($year . '-12-31')));

            $from = date('Y-m-d' . ' 21:00:00', strtotime('-1 day', strtotime(date($adate))));
            $to = date('Y-m-d 20:59:59', strtotime($year . '-12-31'));

        } elseif ($request->filter_by == "week") {
            $sundayOfWeek = date('Y-m-d', strtotime('last sunday', strtotime('next sunday')));
            $adate = $sundayOfWeek;
            $adate2 = date('Y-m-d', strtotime('+6 day', strtotime(date($adate))));

            $from = date('Y-m-d' . ' 21:00:00', strtotime('-1 day', strtotime(date($adate))));
            $to = date('Y-m-d' . ' 20:59:59', strtotime(date($adate2)));
        }

        $perPage = (int)$request->perPage;
        $exp = explode("_", $request->status);

        if (is_int($request->status)) { 
            if ($request->table_name == "revenueTable") {
                $query_sms = SMS_alerts::query()->whereBetween('date', [$from, $to])->whereNull('date_into')->where('status', $request->status);
    
                if ($perPage == 10) {
                    $data_query = $query_sms->limit($request->page.'0')->get();
                } else {
                    $data_query = $query_sms->paginate($perPage);
                }
            }
        } else {
            if ($request->status != "mc_elexa_charge" && $request->status != "mc_agoda_charge" && count($exp) > 1 && $exp[0]."_".$exp[1] == "manual_charge") {
                $query_revenue = Revenues::query()->leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', $exp[2])
                    ->where('revenue_credit.revenue_type', $exp[2])->whereBetween('revenue.date', [$adate, $adate2])
                    ->select('revenue.date', 'revenue.total_credit', 'revenue_credit.batch', 'revenue_credit.revenue_type', 'revenue_credit.credit_amount', 'revenue_credit.status');

                if ($perPage == 10) {
                    $data_query = $query_revenue->limit($request->page.'0')->get();
                } else {
                    $data_query = $query_revenue->paginate($perPage);
                }
            } elseif ($request->status == "mc_agoda_charge") {
                $query_revenue = Revenues::query()->leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 5)
                    ->where('revenue_credit.revenue_type', 1)->whereBetween('revenue.date', [$adate, $adate2])
                    ->select('revenue.date', 'revenue_credit.batch', 'revenue_credit.agoda_charge', 'revenue_credit.agoda_outstanding', 'revenue_credit.status');

                    if ($perPage == 10) {
                        $data_query = $query_revenue->limit($request->page.'0')->get();
                    } else {
                        $data_query = $query_revenue->paginate($perPage);
                    }

            } elseif ($request->status == "mc_elexa_charge") {
                $query_revenue = Revenues::query()->leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')
                    ->where('revenue_credit.status', 8)->where('revenue_credit.revenue_type', 8)->whereBetween('revenue.date', [$adate, $adate2])
                    ->select('revenue_credit.ev_charge', 'revenue_credit.ev_fee', 'revenue_credit.ev_vat', 'revenue_credit.ev_revenue');

                    if ($perPage == 10) {
                        $data_query = $query_revenue->limit($request->page.'0')->get();
                    } else {
                        $data_query = $query_revenue->paginate($perPage);
                    }

            } elseif ($request->status == "agoda_outstanding") {
                $query_revenue = Revenues::query()->leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 5)
                    ->where('revenue_credit.revenue_type', 1)->whereBetween('revenue.date', [$adate, $adate2])
                    ->select('revenue.date', 'revenue_credit.batch', 'revenue_credit.agoda_charge', 'revenue_credit.agoda_outstanding', 'revenue_credit.status');

                    if ($perPage == 10) {
                        $data_query = $query_revenue->limit($request->page.'0')->get();
                    } else {
                        $data_query = $query_revenue->paginate($perPage);
                    }

            } elseif ($request->status == "elexa_outstanding") {
                $query_revenue = Revenues::query()->leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')
                    ->where('revenue_credit.status', 8)->where('revenue_credit.revenue_type', 8)->whereBetween('revenue.date', [$adate, $adate2])
                    ->select('revenue_credit.ev_charge', 'revenue_credit.ev_fee', 'revenue_credit.ev_vat', 'revenue_credit.ev_revenue');

                    if ($perPage == 10) {
                        $data_query = $query_revenue->limit($request->page.'0')->get();
                    } else {
                        $data_query = $query_revenue->paginate($perPage);
                    }
            } elseif ($request->table_name == "typeTable") { 
                if ($request->status == "transfer_revenue") {
                    $query_sms = SMS_alerts::query()->whereDate('date_into', '>=', $from)->whereDate('date_into', '<=', $to)->whereNull('date_into')->where('transfer_status', 1);
    
                    if ($perPage == 10) {
                        $data_query = $query_sms->limit($request->page.'0')->get();
                    } else {
                        $data_query = $query_sms->paginate($perPage);
                    }

                } if ($request->status == "credit_hotel_transfer") {
                    $query_sms = SMS_alerts::query()->whereDate('date_into', '>=', $from)->whereDate('date_into', '<=', $to)->where('into_account', "708-226792-1")->where('status', 4);
    
                    if ($perPage == 10) {
                        $data_query = $query_sms->limit($request->page.'0')->get();
                    } else {
                        $data_query = $query_sms->paginate($perPage);
                    }

                } if ($request->status == "split_hotel_revenue") {
                    $query_sms = SMS_alerts::query()->where('date_into', [$from, $to])->where('split_status', 1);
    
                    if ($perPage == 10) {
                        $data_query = $query_sms->limit($request->page.'0')->get();
                    } else {
                        $data_query = $query_sms->paginate($perPage);
                    }

                } if ($request->status == "split_hotel_transaction") {
                    $query_sms = SMS_alerts::query()->whereBetween('date', [$from, $to])->where('split_status', 1);
    
                    if ($perPage == 10) {
                        $data_query = $query_sms->limit($request->page.'0')->get();
                    } else {
                        $data_query = $query_sms->paginate($perPage);
                    }

                } if ($request->status == "no_income_revenue") {
                    $query_sms = SMS_alerts::query()->whereBetween('date', [$from, $to])->where('status', 0)->whereNull('date_into');
    
                    if ($perPage == 10) {
                        $data_query = $query_sms->limit($request->page.'0')->get();
                    } else {
                        $data_query = $query_sms->paginate($perPage);
                    }

                } if ($request->status == "total_transaction") {
                    $query_sms = SMS_alerts::query()->whereBetween('date', [$from, $to])->orWhereDate('date_into', $adate);
    
                    if ($perPage == 10) {
                        $data_query = $query_sms->limit($request->page.'0')->get();
                    } else {
                        $data_query = $query_sms->paginate($perPage);
                    }

                } if ($request->status == "transfer_transaction") {
                    $query_sms = SMS_alerts::query()->whereBetween('date_into', [$from, $to])->where('transfer_status', 1);
    
                    if ($perPage == 10) {
                        $data_query = $query_sms->limit($request->page.'0')->get();
                    } else {
                        $data_query = $query_sms->paginate($perPage);
                    }

                } if ($request->status == "no_income_type") {
                    $query_sms = SMS_alerts::query()->whereBetween('date', [$from, $to])->where('status', 0)->whereNull('date_into');
    
                    if ($perPage == 10) {
                        $data_query = $query_sms->limit($request->page.'0')->get();
                    } else {
                        $data_query = $query_sms->paginate($perPage);
                    }

                }

            }  elseif ($request->table_name == "verifiedTable") {
                $date1 = date('Y-m-d', strtotime(date($request->year.'-'.$request->month.'-01')));
                $date2 = date('Y-m-d', strtotime('last day of this month', strtotime(date(date($request->year.'-'.$request->month.'-'.$request->day)))));

                if ($request->status == "verified") {
                    $query_sms = Revenues::query()->whereBetween('date', [$date1, $date2])->where('status', 1);
    
                    if ($perPage == 10) {
                        $data_query = $query_sms->limit($request->page.'0')->get();
                    } else {
                        $data_query = $query_sms->paginate($perPage);
                    }

                } if ($request->status == "unverified") {
                    $query_sms = Revenues::query()->whereBetween('date', [$date1, $date2])->where('status', 0);
    
                    if ($perPage == 10) {
                        $data_query = $query_sms->limit($request->page.'0')->get();
                    } else {
                        $data_query = $query_sms->paginate($perPage);
                    }
                }
            }
        }

        $data = [];

        $page_1 = $request->page == 1 ? 1 : ($request->page - 1).'1';
        $page_2 = $request->page.'0';

        $perPage2 = $request->perPage > 10 ? $request->perPage : 10;

        if (isset($data_query) && count($data_query) > 0) {
            if (count($exp) > 1 && $exp[0]."_".$exp[1] != "manual_charge" && $request->status != "mc_agoda_charge" && $request->status != "mc_elexa_charge" && $request->status != "agoda_outstanding" && $request->status != "elexa_outstanding") { ## Manual Charge
                foreach ($data_query as $key => $value) {
                    if (($key + 1) >= (int)$page_1 && ($key + 1) <= (int)$page_2 || (int)$perPage > 10 && $key < (int)$perPage2) {

                        $img_bank = '';
                        $transfer_bank = '';
                        $revenue_name = '';
        
                        // โอนจากธนาคาร
                        $filename = base_path() . '/public/image/bank/' . @$value->transfer_bank->name_en . '.jpg';
                        $filename2 = base_path() . '/public/image/bank/' . @$value->transfer_bank->name_en . '.png';
                    
                        if (file_exists($filename)) {
                            $img_bank = '<img class="img-bank" src="../image/bank/'.@$value->transfer_bank->name_en.'.jpg">';
                        } elseif (file_exists($filename2)) {
                            $img_bank = '<img class="img-bank" src="../image/bank/'.@$value->transfer_bank->name_en.'.png">';
                        }
        
                        $transfer_bank = '<div class="flex-jc p-left-4 center">'.$img_bank.''.@$value->transfer_bank->name_en.'</div>';
        
                        // เข้าบัญชี
                        $into_account = '<div class="flex-jc p-left-4 center"><img class="img-bank" src="../image/bank/SCB.jpg">SCB '.$value->into_account.'</div>';
        
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
        
                        $data[] = [
                            'number' => $key + 1,
                            'date' => Carbon::parse($value->date)->format('d/m/Y'),
                            'time' => Carbon::parse($value->date)->format('H:i:s'),
                            'transfer_bank' => $transfer_bank,
                            'into_account' => $into_account,
                            'amount' => number_format($value->amount, 2),
                            'remark' => $value->remark ?? 'Auto',
                            'revenue_name' => $revenue_name,
                            'date_into' => !empty($value->date_into) ? Carbon::parse($value->date_into)->format('d/m/Y') : '-',
                        ];
                    }
                }
            } elseif ($request->status == "mc_agoda_charge") { 

                foreach ($data_query as $key => $value) {
                    if (($key + 1) >= (int)$page_1 && ($key + 1) <= (int)$page_2 || (int)$perPage > 10 && $key < (int)$perPage2) {
                        $revenue_name = '';
                        // ประเภทรายได้
                        $revenue_name = 'Agoda Revenue';
        
                        $data[] = [
                            'number' => $key + 1,
                            'date' => Carbon::parse($value->date)->format('d/m/Y'),
                            'stan' => $value->batch,
                            'revenue_name' => $revenue_name,
                            'check_in' => Carbon::parse($value->agoda_check_in)->format('d/m/Y'),
                            'check_out' => Carbon::parse($value->agoda_check_out)->format('d/m/Y'),
                            'agoda_charge' => number_format($value->agoda_charge, 2),
                            'agoda_outstanding' => number_format($value->agoda_outstanding, 2),
                        ];
                    }
                }

            } elseif ($request->status == "mc_elexa_charge") { 

                foreach ($data_query as $key => $value) {
                    if (($key + 1) >= (int)$page_1 && ($key + 1) <= (int)$page_2 || (int)$perPage > 10 && $key < (int)$perPage2) {
                        $revenue_name = '';
                        // ประเภทรายได้
                        $revenue_name = 'Elexa EGAT Revenue';
        
                        $data[] = [
                            'number' => $key + 1,
                            'date' => Carbon::parse($value->date)->format('d/m/Y'),
                            'revenue_name' => $revenue_name,
                            'ev_charge' => number_format($value->ev_charge, 2),
                            'ev_fee' => number_format($value->ev_fee, 2),
                            'ev_vat' => number_format($value->ev_vat, 2),
                            'ev_revenue' => number_format($value->ev_revenue, 2),
                        ];
                    }
                }

            } elseif ($request->status == "agoda_outstanding") { 

                foreach ($data_query as $key => $value) {
                    if (($key + 1) >= (int)$page_1 && ($key + 1) <= (int)$page_2 || (int)$perPage > 10 && $key < (int)$perPage2) {
                        $revenue_name = '';
                        // ประเภทรายได้
                        $revenue_name = 'Agoda Revenue';
        
                        $data[] = [
                            'number' => $key + 1,
                            'date' => Carbon::parse($value->date)->format('d/m/Y'),
                            'stan' => $value->batch,
                            'revenue_name' => $revenue_name,
                            'check_in' => Carbon::parse($value->agoda_check_in)->format('d/m/Y'),
                            'check_out' => Carbon::parse($value->agoda_check_out)->format('d/m/Y'),
                            'agoda_outstanding' => number_format($value->agoda_outstanding, 2),
                        ];
                    }
                }

            } elseif ($request->status == "elexa_outstanding") { 

                foreach ($data_query as $key => $value) {
                    if (($key + 1) >= (int)$page_1 && ($key + 1) <= (int)$page_2 || (int)$perPage > 10 && $key < (int)$perPage2) {
                        $revenue_name = '';
                        // ประเภทรายได้
                        $revenue_name = 'Elexa EGAT Revenue';
        
                        $data[] = [
                            'number' => $key + 1,
                            'date' => Carbon::parse($value->date)->format('d/m/Y'),
                            'revenue_name' => $revenue_name,
                            'ev_revenue' => number_format($value->ev_revenue, 2),
                        ];
                    }
                }

            } else {
                foreach ($data_query as $key => $value) {
                    if (($key + 1) >= (int)$page_1 && ($key + 1) <= (int)$page_2 || (int)$perPage > 10 && $key < (int)$perPage2) {
                        $revenue_name = '';
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
        
                        $data[] = [
                            'number' => $key + 1,
                            'date' => Carbon::parse($value->date)->format('d/m/Y'),
                            'stan' => $value->batch,
                            'amount' => number_format($value->credit_amount, 2),
                            'revenue_name' => $revenue_name,
                        ];
                    }
                }
            }
        }

        return response()->json([
                'data' => $data,
            ]);
    }

    public function search_table(Request $request)
    {
        if ($request->filter_by == "date" || $request->filter_by == "today" || $request->filter_by == "yesterday" || $request->filter_by == "tomorrow") {
            $req_date = Carbon::parse($request->date)->format('Y-m-d');
            $adate = $req_date;
            $adate2 = date('Y-m-d', strtotime(date($adate)));

            $from = date('Y-m-d' . ' 21:00:00', strtotime('-1 day', strtotime(date($adate))));
            $to = date('Y-m-d 20:59:59', strtotime($adate));

        } elseif ($request->filter_by == "month") {
            $exp = explode('-', $request->date);

            $start_month = Carbon::parse($exp[0])->format('m');
            $end_month = Carbon::parse($exp[0])->format('m');
            $year = Carbon::parse($exp[0])->format('Y');

            if (isset($exp[1])) { // เลือกมากกว่า 1 เดือน
                $end_month = Carbon::parse($exp[1])->format('m');
                $year = Carbon::parse($exp[1])->format('Y');
            }

            $lastday = dayLast($end_month, $year); // หาวันสุดท้ายของเดือน
            $adate = date('Y-m-d', strtotime($year . '-' . $start_month . '-01'));
            $adate2 = date('Y-m-d', strtotime($year . '-' . $end_month . '-' . $lastday));

            $from = date('Y-m-d' . ' 21:00:00', strtotime('-1 day', strtotime(date($adate))));
            $to = date($year . '-' . $end_month . '-' . $lastday . ' 20:59:59');

        } elseif ($request->filter_by == "thisMonth") {
            $lastday = dayLast(date('m'), date('Y')); // หาวันสุดท้ายของเดือน
            $adate = date('Y-m-01');
            $adate2 = date('Y-m-' . $lastday);

            $from = date('Y-m-d' . ' 21:00:00', strtotime('-1 day', strtotime(date($adate))));
            $to = date('Y-m-d 20:59:59', strtotime($adate2));

        } elseif ($request->filter_by == "year") {
            $year = $request->date;
            $adate = date('Y-m-d', strtotime($year . '-01' . '-01'));
            $adate2 = date('Y-m-d', strtotime(date($year . '-12-31')));

            $from = date('Y-m-d' . ' 21:00:00', strtotime('-1 day', strtotime(date($adate))));
            $to = date('Y-m-d 20:59:59', strtotime($year . '-12-31'));

        } elseif ($request->filter_by == "week") {
            $sundayOfWeek = date('Y-m-d', strtotime('last sunday', strtotime('next sunday')));
            $adate = $sundayOfWeek;
            $adate2 = date('Y-m-d', strtotime('+6 day', strtotime(date($adate))));

            $from = date('Y-m-d' . ' 21:00:00', strtotime('-1 day', strtotime(date($adate))));
            $to = date('Y-m-d' . ' 20:59:59', strtotime(date($adate2)));
        }

        $data = [];

        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $exp = explode("_", $request->status);
        $search = $request->search_value;

        if (is_int($request->status) && $request->status > 0) {
            if ($request->table_name == "revenueTable") {
                if (!empty($request->search_value)) {
                    $data_query = SMS_alerts::whereBetween('date', [$from, $to])
                        ->where('date', 'LIKE', '%'.$search.'%')->whereNull('date_into')->where('status', $request->status)
                        ->orWhere('amount', 'LIKE', '%'.$search.'%')->whereBetween('date', [$from, $to])
                        ->whereNull('date_into')->where('status', $request->status)
                        ->paginate($perPage);
                } else {
                    $data_query = SMS_alerts::whereBetween('date', [$from, $to])->whereNull('date_into')->where('status', $request->status)->orderBy('date', 'asc')->paginate($perPage);
                }

            }
        } else {
            if ($request->status != "mc_elexa_charge" && $request->status != "mc_agoda_charge" && count($exp) > 1 && $exp[0]."_".$exp[1] == "manual_charge") {
                if (!empty($request->search_value)) {
                    $data_query = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', $exp[2])
                        ->where('revenue_credit.revenue_type', $exp[2])->whereBetween('revenue.date', [$adate, $adate2])
                        ->where(function($query) use ($search) {
                            $query->where('revenue_credit.credit_amount', 'like', '%' . $search . '%')
                                  ->orWhere('revenue_credit.batch', 'like', '%' . $search . '%')
                                  ->orWhere('revenue.date', 'like', '%' . $search . '%');
                        })
                        ->select('revenue.date', 'revenue.total_credit', 'revenue_credit.batch', 'revenue_credit.revenue_type', 'revenue_credit.credit_amount', 'revenue_credit.status')->paginate($perPage);
                } else {
                    $data_query = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', $exp[2])
                        ->where('revenue_credit.revenue_type', $exp[2])->whereBetween('revenue.date', [$adate, $adate2])
                        ->select('revenue.date', 'revenue.total_credit', 'revenue_credit.batch', 'revenue_credit.revenue_type', 'revenue_credit.credit_amount', 'revenue_credit.status')->paginate($perPage);
                }

            } elseif ($request->status == "mc_agoda_charge") {
                if (!empty($request->search_value)) {
                    $data_query = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 5)
                        ->where('revenue_credit.revenue_type', 1)->whereBetween('revenue.date', [$adate, $adate2])
                        ->where(function($query) use ($search) {
                            $query->where('revenue_credit.agoda_outstanding', 'like', '%' . $search . '%')
                                    ->orWhere('revenue_credit.agoda_charge', 'like', '%' . $search . '%')
                                    ->orWhere('revenue_credit.batch', 'like', '%' . $search . '%')
                                    ->orWhere('revenue.date', 'like', '%' . $search . '%');
                        })
                        ->select('revenue.date', 'revenue_credit.batch', 'revenue_credit.agoda_charge', 'revenue_credit.agoda_outstanding', 'revenue_credit.status')->paginate($perPage);
                } else {
                    $data_query = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 5)
                        ->where('revenue_credit.revenue_type', 1)->whereBetween('revenue.date', [$adate, $adate2])
                        ->select('revenue.date', 'revenue_credit.batch', 'revenue_credit.agoda_charge', 'revenue_credit.agoda_outstanding', 'revenue_credit.status')->paginate($perPage);
                }

            } elseif ($request->status == "mc_elexa_charge") {
                if (!empty($request->search_value)) {
                    $data_query = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')
                        ->where('revenue_credit.status', 8)->where('revenue_credit.revenue_type', 8)->whereBetween('revenue.date', [$adate, $adate2])
                        ->where(function($query) use ($search) {
                            $query->where('revenue_credit.ev_charge', 'like', '%' . $search . '%')
                                ->orWhere('revenue_credit.ev_fee', 'like', '%' . $search . '%')
                                ->orWhere('revenue_credit.ev_vat', 'like', '%' . $search . '%')
                                ->orWhere('revenue_credit.ev_revenue', 'like', '%' . $search . '%')
                                ->orWhere('revenue.date', 'like', '%' . $search . '%');
                        })
                        ->select('revenue_credit.ev_charge', 'revenue_credit.ev_fee', 'revenue_credit.ev_vat', 'revenue_credit.ev_revenue')->paginate(10);
                } else {
                    $data_query = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')
                        ->where('revenue_credit.status', 8)->where('revenue_credit.revenue_type', 8)->whereBetween('revenue.date', [$adate, $adate2])
                        ->select('revenue_credit.ev_charge', 'revenue_credit.ev_fee', 'revenue_credit.ev_vat', 'revenue_credit.ev_revenue')->paginate(10);
                }

            }  elseif ($request->status == "agoda_outstanding") {
                if (!empty($request->search_value)) {
                    $data_query = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 5)
                        ->where('revenue_credit.revenue_type', 1)->whereBetween('revenue.date', [$adate, $adate2])
                        ->where(function($query) use ($search) {
                            $query->where('revenue_credit.agoda_outstanding', 'like', '%' . $search . '%')
                                ->orWhere('revenue.date', 'like', '%' . $search . '%');
                        })
                        ->select('revenue.date', 'revenue_credit.batch', 'revenue_credit.agoda_charge', 'revenue_credit.agoda_outstanding', 'revenue_credit.status')->paginate($perPage);
                } else {
                    $data_query = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 5)
                        ->where('revenue_credit.revenue_type', 1)->whereBetween('revenue.date', [$adate, $adate2])
                        ->select('revenue.date', 'revenue_credit.batch', 'revenue_credit.agoda_charge', 'revenue_credit.agoda_outstanding', 'revenue_credit.status')->paginate($perPage);
                }

            } elseif ($request->status == "elexa_outstanding") {
                if (!empty($request->search_value)) {
                    $data_query = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')
                        ->where('revenue_credit.status', 8)->where('revenue_credit.revenue_type', 8)->whereBetween('revenue.date', [$adate, $adate2])
                        ->where(function($query) use ($search) {
                            $query->where('revenue_credit.ev_revenue', 'like', '%' . $search . '%')
                                ->orWhere('revenue.date', 'like', '%' . $search . '%');
                        })
                        ->select('revenue_credit.ev_charge', 'revenue_credit.ev_fee', 'revenue_credit.ev_vat', 'revenue_credit.ev_revenue')->paginate($perPage);
                } else {
                    $data_query = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')
                        ->where('revenue_credit.status', 8)->where('revenue_credit.revenue_type', 8)->whereBetween('revenue.date', [$adate, $adate2])
                        ->select('revenue_credit.ev_charge', 'revenue_credit.ev_fee', 'revenue_credit.ev_vat', 'revenue_credit.ev_revenue')->paginate($perPage);
                }

            } elseif ($request->table_name == "typeTable") {
                if ($request->status == "transfer_revenue") {
                    if (!empty($request->search_value)) {
                        $data_query = SMS_alerts::whereDate('date_into', '>=', $from)->whereDate('date_into', '<=', $to)->whereNull('date_into')->where('transfer_status', 1)
                            ->where(function($query) use ($search) {
                                $query->where('date', 'like', '%' . $search . '%')
                                    ->orWhere('amount', 'like', '%' . $search . '%');
                            })->paginate($perPage);
                    } else {
                        $data_query = SMS_alerts::whereDate('date_into', '>=', $from)->whereDate('date_into', '<=', $to)->whereNull('date_into')->where('transfer_status', 1)->paginate($perPage);
                    }

                }  if ($request->status == "credit_hotel_transfer") {
                    if (!empty($request->search_value)) {
                        $data_query = SMS_alerts::whereDate('date_into', '>=', $from)->whereDate('date_into', '<=', $to)->where('into_account', "708-226792-1")->where('status', 4)
                            ->where(function($query) use ($search) {
                                $query->where('date', 'like', '%' . $search . '%')
                                    ->orWhere('amount', 'like', '%' . $search . '%');
                            })->paginate($perPage);
                    } else {
                        $data_query = SMS_alerts::whereDate('date_into', '>=', $from)->whereDate('date_into', '<=', $to)->where('into_account', "708-226792-1")->where('status', 4)->paginate($perPage);
                    } 

                } if ($request->status == "split_hotel_revenue") {
                    if (!empty($request->search_value)) {
                        $data_query = SMS_alerts::where('date_into', [$from, $to])->where('split_status', 1)
                            ->where(function($query) use ($search) {
                                $query->where('date', 'like', '%' . $search . '%')
                                    ->orWhere('amount', 'like', '%' . $search . '%');
                            })->paginate($perPage);
                    } else {
                        $data_query = SMS_alerts::where('date_into', [$from, $to])->where('split_status', 1)->paginate($perPage);
                    }

                } if ($request->status == "split_hotel_transaction") {
                    if (!empty($request->search_value)) {
                        $data_query = SMS_alerts::whereBetween('date', [$from, $to])->where('split_status', 1)
                            ->where(function($query) use ($search) {
                                $query->where('date', 'like', '%' . $search . '%')
                                    ->orWhere('amount', 'like', '%' . $search . '%');
                            })->paginate($perPage);
                    } else {
                        $data_query = SMS_alerts::whereBetween('date', [$from, $to])->where('split_status', 1)->paginate($perPage);
                    } 

                } if ($request->status == "no_income_revenue") {
                    if (!empty($request->search_value)) {
                        $data_query = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 0)->whereNull('date_into')
                            ->where(function($query) use ($search) {
                                $query->where('date', 'like', '%' . $search . '%')
                                    ->orWhere('amount', 'like', '%' . $search . '%');
                            })->paginate($perPage);
                    } else {
                        $data_query = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 0)->whereNull('date_into')->paginate($perPage);
                    }

                } if ($request->status == "total_transaction") {
                    if (!empty($request->search_value)) {
                        $data_query = SMS_alerts::whereBetween('date', [$from, $to])->orWhereDate('date_into', $adate)
                            ->where(function($query) use ($search) {
                                $query->where('date', 'like', '%' . $search . '%')
                                    ->orWhere('amount', 'like', '%' . $search . '%');
                            })->paginate($perPage);
                    } else {
                        $data_query = SMS_alerts::whereBetween('date', [$from, $to])->orWhereDate('date_into', $adate)->paginate($perPage);
                    }

                } if ($request->status == "transfer_transaction") {
                    if (!empty($request->search_value)) {
                        $data_query = SMS_alerts::whereBetween('date_into', [$from, $to])->where('transfer_status', 1)
                            ->where(function($query) use ($search) {
                                $query->where('date', 'like', '%' . $search . '%')
                                    ->orWhere('amount', 'like', '%' . $search . '%');
                            })->paginate($perPage);
                    } else {
                        $data_query = SMS_alerts::whereBetween('date_into', [$from, $to])->where('transfer_status', 1)->paginate($perPage);
                    }

                } if ($request->status == "no_income_type") {
                    if (!empty($request->search_value)) {
                        $data_query = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 0)->whereNull('date_into')
                            ->where(function($query) use ($search) {
                                $query->where('date', 'like', '%' . $search . '%')
                                    ->orWhere('amount', 'like', '%' . $search . '%');
                            })->paginate($perPage);
                    } else {
                        $data_query = SMS_alerts::whereBetween('date', [$from, $to])->where('status', 0)->whereNull('date_into')->paginate($perPage);
                    }

                }
            } elseif ($request->table_name == "verifiedTable") {
                $date1 = date('Y-m-d', strtotime(date($request->year.'-'.$request->month.'-01')));
                $date2 = date('Y-m-d', strtotime('last day of this month', strtotime(date(date($request->year.'-'.$request->month.'-'.$request->day)))));

                if ($request->status == "verified") {
                    if (!empty($request->search_value)) {
                        $data_query = Revenues::whereBetween('date', [$date1, $date2])->where('status', 1)
                            ->where(function($query) use ($search) {
                                $query->where('date', 'like', '%' . $search . '%');
                            })->paginate($perPage);
                    } else {
                        $data_query = Revenues::whereBetween('date', [$date1, $date2])->where('status', 1)->paginate($perPage);
                    }

                } if ($request->status == "unverified") {
                    if (!empty($request->search_value)) {
                        $data_query = Revenues::whereBetween('date', [$date1, $date2])->where('status', 0)
                            ->where(function($query) use ($search) {
                                $query->where('date', 'like', '%' . $search . '%');
                            })->paginate($perPage);
                    } else {
                        $data_query = Revenues::whereBetween('date', [$date1, $date2])->where('status', 0)->paginate($perPage);
                    }
                }
            }
        }

        if (isset($data_query) && count($data_query) > 0) {
            if (count($exp) > 1 && $exp[0]."_".$exp[1] != "manual_charge" && $request->status != "mc_agoda_charge" && $request->status != "mc_elexa_charge" && $request->status != "agoda_outstanding" && $request->status != "elexa_outstanding") { ## Manual Charge
                foreach ($data_query as $key => $value) {

                    $img_bank = '';
                    $transfer_bank = '';
                    $revenue_name = '';

                    // โอนจากธนาคาร
                    $filename = base_path() . '/public/image/bank/' . @$value->transfer_bank->name_en . '.jpg';
                    $filename2 = base_path() . '/public/image/bank/' . @$value->transfer_bank->name_en . '.png';
                
                    if (file_exists($filename)) {
                        $img_bank = '<img class="img-bank" src="../image/bank/'.@$value->transfer_bank->name_en.'.jpg">';
                    } elseif (file_exists($filename2)) {
                        $img_bank = '<img class="img-bank" src="../image/bank/'.@$value->transfer_bank->name_en.'.png">';
                    }

                    $transfer_bank = '<div class="flex-jc p-left-4 center">'.$img_bank.''.@$value->transfer_bank->name_en.'</div>';

                    // เข้าบัญชี
                    $into_account = '<div class="flex-jc p-left-4 center"><img class="img-bank" src="../image/bank/SCB.jpg">SCB '.$value->into_account.'</div>';

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

                    $data[] = [
                        'id' => $key + 1,
                        'date' => Carbon::parse($value->date)->format('d/m/Y'),
                        'time' => Carbon::parse($value->date)->format('H:i:s'),
                        'transfer_bank' => $transfer_bank,
                        'into_account' => $into_account,
                        'amount' => number_format($value->amount, 2),
                        'remark' => $value->remark ?? 'Auto',
                        'revenue_name' => $revenue_name,
                        'date_into' => !empty($value->date_into) ? Carbon::parse($value->date_into)->format('d/m/Y') : '-',
                    ];
                }
            } elseif ($request->status == "mc_agoda_charge") { 

                foreach ($data_query as $key => $value) {
                    $revenue_name = '';
                    // ประเภทรายได้
                    $revenue_name = 'Agoda Revenue';
    
                    $data[] = [
                        'number' => $key + 1,
                        'date' => Carbon::parse($value->date)->format('d/m/Y'),
                        'stan' => $value->batch,
                        'revenue_name' => $revenue_name,
                        'check_in' => Carbon::parse($value->agoda_check_in)->format('d/m/Y'),
                        'check_out' => Carbon::parse($value->agoda_check_out)->format('d/m/Y'),
                        'agoda_charge' => number_format($value->agoda_charge, 2),
                        'agoda_outstanding' => number_format($value->agoda_outstanding, 2),
                    ];
                }

            } elseif ($request->status == "mc_elexa_charge") { 

                foreach ($data_query as $key => $value) {
                    $revenue_name = '';
                    // ประเภทรายได้
                    $revenue_name = 'Elexa EGAT Revenue';
    
                    $data[] = [
                        'number' => $key + 1,
                        'date' => Carbon::parse($value->date)->format('d/m/Y'),
                        'revenue_name' => $revenue_name,
                        'ev_charge' => number_format($value->ev_charge, 2),
                        'ev_fee' => number_format($value->ev_fee, 2),
                        'ev_vat' => number_format($value->ev_vat, 2),
                        'ev_revenue' => number_format($value->ev_revenue, 2),
                    ];
                }

            } elseif ($request->status == "agoda_outstanding") { 

                foreach ($data_query as $key => $value) {
                    $revenue_name = '';
                    // ประเภทรายได้
                    $revenue_name = 'Agoda Revenue';
    
                    $data[] = [
                        'number' => $key + 1,
                        'date' => Carbon::parse($value->date)->format('d/m/Y'),
                        'stan' => $value->batch,
                        'revenue_name' => $revenue_name,
                        'check_in' => Carbon::parse($value->agoda_check_in)->format('d/m/Y'),
                        'check_out' => Carbon::parse($value->agoda_check_out)->format('d/m/Y'),
                        'agoda_outstanding' => number_format($value->agoda_outstanding, 2),
                    ];
                }

            } elseif ($request->status == "elexa_outstanding") { 

                foreach ($data_query as $key => $value) {
                    $revenue_name = '';
                    // ประเภทรายได้
                    $revenue_name = 'Elexa EGAT Revenue';
    
                    $data[] = [
                        'number' => $key + 1,
                        'date' => Carbon::parse($value->date)->format('d/m/Y'),
                        'revenue_name' => $revenue_name,
                        'ev_revenue' => number_format($value->ev_revenue, 2),
                    ];
                }

            } else {
                foreach ($data_query as $key => $value) {
                    $revenue_name = '';
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
    
                    $data[] = [
                        'number' => $key + 1,
                        'date' => Carbon::parse($value->date)->format('d/m/Y'),
                        'stan' => $value->batch,
                        'amount' => number_format($value->credit_amount, 2),
                        'revenue_name' => $revenue_name,
                    ];
                }
            }
        }

        return response()->json([
            'data' => $data,
        ]);
    }
}
