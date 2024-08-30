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
        if ($request->day == 0) {
            return $this->search_calendar_all($request);

        } else {

        if ($request->filter_by == "date" || $request->filter_by == "today" || $request->filter_by == "yesterday" || $request->filter_by == "tomorrow") {
            $adate = date('Y-m-d 21:00:00', strtotime($request->year . '-' . $request->month . '-' . $request->day.' 21:00:00'));
            $from = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime(date($adate))));
            $to = date($adate . ' 20:59:59');

            // Revenue
            $month_from = date('Y-m-d', strtotime($request->year . '-' . $request->month . '-' . $request->day));
            $month_to = date('Y-m-d', strtotime(date($request->year . '-' . $request->month . '-' . $request->day)));
            $date_first_day = date('Y-m-d', strtotime('first day of this month', strtotime(date($request->year . '-' . $request->month . '-' . $request->day))));

        } elseif ($request->filter_by == "month") {
            $adate = date('Y-m-d', strtotime($request->year . '-' . $request->month . '-01'));
            $lastday = dayLast($request->month_to, $request->year); // หาวันสุดท้ายของเดือน

            $from = date('Y-m-d' . ' 21:00:00', strtotime('-1 day', strtotime(date($adate))));
            $to = date('Y-' . str_pad($request->month_to, 2 ,0, STR_PAD_LEFT) . '-' . $lastday . ' 20:59:59');

            $month_from = date('Y-m-d', strtotime($adate));
            $month_to = date('Y-m-d', strtotime('last day of this month', strtotime(date($to))));
            $date_first_day = date('Y-m-d', strtotime('first day of this month', strtotime(date($request->year . '-' . $request->month . '-' . $request->day))));

        } elseif ($request->filter_by == "year") {
            $adate = date($request->year . '-01' . '-01');
            $from = date('Y-m-d' . ' 21:00:00', strtotime('-1 day', strtotime(date($adate))));
            $to = date($request->year . '-12-31' . ' 20:59:59');

            $month_from = date('Y-m-d', strtotime($adate));
            $month_to = date('Y-m-d', strtotime('last day of this month', strtotime(date($to))));
            $date_first_day = date('Y-m-d', strtotime('first day of this month', strtotime(date($request->year . '-' . $request->month . '-' . $request->day))));

        } elseif ($request->filter_by == "week") {
            $adate = date('Y-m-d', strtotime($request->year . '-' . $request->month . '-' . $request->day));
            $adate2 = date('Y-m-d', strtotime('+6 day', strtotime(date($adate))));

            $from = date('Y-m-d' . ' 21:00:00', strtotime('-1 day', strtotime(date($adate))));
            $to = date('Y-m-d' . ' 20:59:59', strtotime(date($adate2)));

            $month_from = $adate;
            $month_to = $adate2;
            $date_first_day = $adate;

            $year_from = date('Y-m-d', strtotime(date($request->year . '-01-01')));
            $year_to = $adate2;

        } elseif ($request->filter_by == "thisMonth") {
            $lastday = dayLast(date('m'), date('Y')); // หาวันสุดท้ายของเดือน
            $adate = date('Y-m-d', strtotime(date($request->year . '-' . $request->month . '-01')));

            $from = date('Y-m-d' . ' 21:00:00', strtotime('-1 day', strtotime(date($adate))));
            $to = date('Y-m-d 20:59:59', strtotime(date($request->year . '-' . $request->month . '-' . $request->day)));

            $month_from = date('Y-m-d', strtotime($adate));
            $month_to = date('Y-m-d', strtotime(date($request->year . '-' . $request->month . '-' . $request->day)));
            $date_first_day = $adate;

            $year_from = date('Y-m-d', strtotime(date($request->year . '-01-01')));
            $year_to = date('Y-m-d', strtotime(date($request->year . '-' . $request->month . '-' . $request->day)));

        } elseif ($request->filter_by == "thisYear") {
            $lastday = dayLast(date('m'), date('Y')); // หาวันสุดท้ายของเดือน
            $adate = date('Y-m-d', strtotime(date($request->year . '-01-01')));

            $from = date('Y-m-d' . ' 21:00:00', strtotime('-1 day', strtotime(date($adate))));
            $to = date('Y-m-d 20:59:59', strtotime(date($request->year . '-' . $request->month . '-' . $request->day)));

            $month_from = date('Y-m-d', strtotime($adate));
            $month_to = date('Y-m-d', strtotime(date($request->year . '-' . $request->month . '-' . $request->day)));
            $date_first_day = $adate;

            $month_to_date = date('Y-m-d', strtotime(date($request->year . '-' . $request->month . '-01')));
            $month_to_date2 = date('Y-m-d', strtotime(date($request->year . '-' . $request->month . '-' . $request->day)));

            $year_from = date('Y-m-d', strtotime(date($request->year . '-01-01')));
            $year_to = date('Y-m-d', strtotime(date($request->year . '-' . $request->month . '-' . $request->day)));

        } elseif ($request->filter_by == "customRang") {
            $adate = date('Y-m-d', strtotime(date($request->customRang_start)));
            $adate2 = date('Y-m-d', strtotime(date($request->customRang_end)));

            $from = date('Y-m-d' . ' 21:00:00', strtotime('-1 day', strtotime(date($adate))));
            $to = date('Y-m-d' . ' 20:59:59', strtotime(date($adate2)));

            $month_from = date('Y-m-d', strtotime($adate));
            $month_to = date('Y-m-d', strtotime(date($to)));
            $date_first_day = $adate;

        }

        // dd([$month_from, $month_to]);

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
        $ev_array = [];
        $other_array = [];
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
                $check_sms = SMS_alerts::whereBetween('date', [date("Y-".$request->month."-".str_pad($i - 1, 2, '0', STR_PAD_LEFT).' 21:00:00'), date('Y-'.$request->month.'-'.str_pad($i, 2, '0', STR_PAD_LEFT).' 21:00:00')])->whereNull('date_into')
                ->orWhereDate('date_into', date('Y-'.$request->month.'-'.str_pad($i, 2, '0', STR_PAD_LEFT)))
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

        if (isset($other_array)) {
            foreach ($other_array as $key => $value) {
                Revenues::where('date', date('Y-m-'.$key))->update([
                    'other_revenue' => $value['total_other']
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

        $date_now = date('Y-m-d', strtotime(date($request->year.'-'.$request->month.'-'.$request->day)));
        $day_now = $request->day;
        $symbol = $day_now == "01" ? "=" : "<=";

        // verified
        $total_verified = Revenues::whereBetween('date', [$date_first_day, $month_to])->where('status', 1)->count();
        $total_unverified = Revenues::whereBetween('date', [$date_first_day, $month_to])->where('status', 0)->count();

        $total_revenue_today = Revenues::whereBetween('date', [$month_from, $month_to])->select(
            DB::raw("
            SUM(front_cash + front_transfer + front_credit) as front_amount, 
            SUM(room_cash + room_transfer + room_credit) as room_amount, 
            SUM(fb_cash + fb_transfer + fb_credit) as fb_amount,
            SUM(wp_cash + wp_transfer + wp_credit) as wp_amount,
            SUM(room_credit + fb_credit + wp_credit) as credit_amount,
            SUM(total_transaction) as total_transaction"), 'total_credit_agoda', 'other_revenue', 'total_no_type', 'status')->first();

        $total_transfer = SMS_alerts::whereBetween('date', [$from, $to])->where('transfer_status', 1)->sum('amount');
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
        $credit_revenue = Revenues::whereBetween('date', [$month_from, $month_to])->select(DB::raw("SUM(total_credit) as total_credit"))->first();

        // Month
        $credit_month_query = Revenues::query();

            if ($request->filter_by == "date"|| $request->filter_by == "today") {
                $credit_month_query->whereDay('date', $symbol, $day_now)->whereMonth('date', $request->month)->whereYear('date', $request->year);

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
                $credit_year_query->whereDate('date', '<=', date($request->year.'-'.$request->month.'-'.$request->day));

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
        $total_front_revenue = Revenues::whereBetween('date', [$month_from, $month_to])->select(DB::raw("SUM(front_cash) as front_cash, SUM(front_transfer) as front_transfer, SUM(front_credit) as front_credit"))->first();

        // Month
        $total_front_month_query = Revenues::query();

            if ($request->filter_by == "date"|| $request->filter_by == "today") {
                $total_front_month_query->whereDay('date', $symbol, $day_now)->whereMonth('date', $request->month)->whereYear('date', $request->year);

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
                $total_front_year_query->whereDate('date', '<=', date($request->year.'-'.$request->month.'-'.$request->day));

            } elseif ($request->filter_by == "month") {
                $total_front_year_query->whereBetween('date', [$month_from, $month_to]);

            } elseif ($request->filter_by == "year" || $request->filter_by == "thisYear" || $request->filter_by == "thisMonth") {
                $total_front_year_query->whereBetween('date', [$year_from, $year_to]);
            }

        $total_front_year_query->select(DB::raw("SUM(front_cash) as front_cash, SUM(front_transfer) as front_transfer, SUM(front_credit) as front_credit"));
        $total_front_year = $total_front_year_query->first();

        $front_charge = Revenues::getManualCharge($request->filter_by, $month_from, $month_to, $date_now, $request->month, $request->year, 6, 6);

        ### Guest Deposit ###
        // Today
        $today_guest_deposit = Revenues::where('date', $date_now)->select(DB::raw("SUM(room_cash) as room_cash, SUM(room_transfer) as room_transfer, SUM(room_credit) as room_credit"))->first();

        // Date
        $total_guest_deposit = Revenues::whereBetween('date', [$month_from, $month_to])->select(DB::raw("SUM(room_cash) as room_cash, SUM(room_transfer) as room_transfer, SUM(room_credit) as room_credit"))->first();

        // Month
        $total_guest_deposit_month_query = Revenues::query();

        if ($request->filter_by == "date"|| $request->filter_by == "today") {
            $total_guest_deposit_month_query->whereDay('date', $symbol, $day_now)->whereMonth('date', $request->month)->whereYear('date', $request->year);

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
                $guest_deposit_year_query->whereDate('date', '<=', date($request->year.'-'.$request->month.'-'.$request->day));

            } elseif ($request->filter_by == "month") {
                $guest_deposit_year_query->whereBetween('date', [$month_from, $month_to]);

            } elseif ($request->filter_by == "year"  || $request->filter_by == "thisMonth") {
                $guest_deposit_year_query->whereBetween('date', [$year_from, $year_to]);
            }

        $guest_deposit_year_query->select(DB::raw("SUM(room_cash) as room_cash, SUM(room_transfer) as room_transfer, SUM(room_credit) as room_credit"));
        $total_guest_deposit_year = $guest_deposit_year_query->first();

        // Charge
        $guest_deposit_charge = Revenues::getManualCharge($request->filter_by, $month_from, $month_to, $date_now, $request->month, $request->year, 1, 1);
 
        ### All Outlet ###
        // Today 
        $today_fb_revenue = Revenues::where('date', $date_now)->select(DB::raw("SUM(fb_cash) as fb_cash, SUM(fb_transfer) as fb_transfer, SUM(fb_credit) as fb_credit"))->first();

        // Date
        $total_fb_revenue = Revenues::whereBetween('date', [$month_from, $month_to])->select(DB::raw("SUM(fb_cash) as fb_cash, SUM(fb_transfer) as fb_transfer, SUM(fb_credit) as fb_credit"))->first();

        // Month
        $fb_month_query = Revenues::query();
        
            if ($request->filter_by == "date"|| $request->filter_by == "today") {
                $fb_month_query->whereDay('date', $symbol, $day_now)->whereMonth('date', $request->month)->whereYear('date', $request->year);

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
                $fb_year_query->whereDate('date', '<=', date($request->year.'-'.$request->month.'-'.$request->day));

            } elseif ($request->filter_by == "month") {
                $fb_year_query->whereBetween('date', [$month_from, $month_to]);

            } elseif ($request->filter_by == "year"  || $request->filter_by == "thisMonth") {
                $fb_year_query->whereBetween('date', [$year_from, $year_to]);
            }

        $fb_year_query->select(DB::raw("SUM(fb_cash) as fb_cash, SUM(fb_transfer) as fb_transfer, SUM(fb_credit) as fb_credit"));
        $total_fb_year = $fb_year_query->first();

        // Charge
        $fb_charge = Revenues::getManualCharge($request->filter_by, $month_from, $month_to, $date_now, $request->month, $request->year, 2, 2);

        ## Other Revenue ###
        // Today
        $today_other_revenue = Revenues::where('date', $date_now)->select('other_revenue')->sum('other_revenue');

        // Date
        $total_other_revenue = Revenues::whereBetween('date', [$month_from, $month_to])->select('other_revenue')->sum('other_revenue');

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
                $other_year_query->whereDate('date', '<=', date($request->year.'-'.$request->month.'-'.$request->day));

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
        $total_agoda_revenue = Revenues::whereBetween('date', [$month_from, $month_to])->sum('total_credit_agoda');

        // Month
        $agoda_month_query = Revenues::query();

            if ($request->filter_by == "date"|| $request->filter_by == "today") {
                $agoda_month_query->whereDay('date', $symbol, $day_now)->whereMonth('date', $request->month)->whereYear('date', date('Y'));

            } elseif ($request->filter_by == "month" || $request->filter_by == "thisMonth" || $request->filter_by == "year" || $request->filter_by == "week" || $request->filter_by == "customRang") {
                $agoda_month_query->whereBetween('date', [$month_from, $month_to]);

            } elseif ($request->filter_by == "thisYear") {
                $agoda_month_query->whereBetween('date', [$month_to_date, $month_to_date2]);
            }

        $total_agoda_month = $agoda_month_query->sum('total_credit_agoda');

        // Year
        $agoda_year_query = Revenues::query();
        
            if ($request->filter_by == "date") {
                $agoda_year_query->whereDate('date', '<=', date($request->year.'-'.$request->month.'-'.$request->day));

            } elseif ($request->filter_by == "month") {
                $agoda_year_query->whereBetween('date', [$month_from, $month_to]);

            } elseif ($request->filter_by == "year"  || $request->filter_by == "thisMonth") {
                $agoda_year_query->whereBetween('date', [$year_from, $year_to]);
            }

        $total_agoda_year = $agoda_year_query->sum('total_credit_agoda');

        // Charge
        $agoda_charge = Revenues::getManualAgodaCharge($request->filter_by, $month_from, $month_to, $date_now, $request->month, $request->year, 1, 5);

        ### Water Park ###
        // Today
        $today_wp_revenue = Revenues::where('date', $date_now)->select(DB::raw("SUM(wp_cash) as wp_cash, SUM(wp_transfer) as wp_transfer, SUM(wp_credit) as wp_credit"))->first();

        // Date
        $total_wp_revenue = Revenues::whereBetween('date', [$month_from, $month_to])->select(DB::raw("SUM(wp_cash) as wp_cash, SUM(wp_transfer) as wp_transfer, SUM(wp_credit) as wp_credit"))->first();

        // Month
        $wp_month_query = Revenues::query();
        
            if ($request->filter_by == "date"|| $request->filter_by == "today") {
                $wp_month_query->whereDay('date', $symbol, $day_now)->whereMonth('date', $request->month)->whereYear('date', $request->year);

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
        $wp_charge = Revenues::getManualCharge($request->filter_by, $month_from, $month_to, $date_now, $request->month, $request->year, 3, 3);

        ### Elexa EGAT ###
        // Today
        $today_ev_revenue = Revenues::where('date', $date_now)->select('total_elexa')->sum('total_elexa');

        // Date
        $total_ev_revenue = Revenues::whereBetween('date', [$month_from, $month_to])->select('total_elexa')->sum('total_elexa');

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
        $ev_charge = Revenues::getManualEvCharge($request->filter_by, $month_from, $month_to, $date_now, $request->month, $request->year, 8, 8);

        ## Filter ##
        $filter_by = $request->filter_by;
        $day = $request->day;
        $month = $request->month;
        $month_to = $request->month_to;
        $year = $request->year;
        $time = $request->time;

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

                        'filter_by', 'day', 'month', 'month_to', 'year'
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
        
                    'filter_by', 'day', 'month', 'month_to', 'year'));
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
