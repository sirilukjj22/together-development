<?php

namespace App\Http\Controllers;

use App\Models\Harmony_revenues;
use App\Models\Harmony_revenue_credit;
use App\Models\Harmony_SMS_alerts;
use App\Models\TB_outstanding_balance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Carbon\Carbon;

class RevenuesHarmonyController extends Controller
{

    public function index()
    {
        $adate= date('Y-m 21:00:00');
        $from = date("Y-m-d 21:00:00", strtotime("-1 day"));
        $to = date('Y-m-d 21:00:00');

        $check_data = Harmony_revenues::whereMonth('date', date('m'))->whereYear('date', date('Y'))->first();

        if (empty($check_data)) {
            $days = date('t');

            for ($i=1; $i <= $days; $i++) { 
                Harmony_revenues::create([
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
                $check_sms = Harmony_SMS_alerts::whereBetween('date', [date("Y-m-d 21:00:00", strtotime("last day of previous month")), date("Y-m-01 21:00:00")])->whereNull('date_into')
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
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 4 && $check_sms[$key]['into_account'] == "708-2-26792-1") {
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
                        $credit_array[$i] = [ 'total_credit' => 0, ];
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
                $check_sms = Harmony_SMS_alerts::whereBetween('date', [date("Y-m-".str_pad($i - 1, 2, '0', STR_PAD_LEFT).' 21:00:00'), date('Y-m-'.str_pad($i, 2, '0', STR_PAD_LEFT).' 21:00:00')])->whereNull('date_into')
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
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 4 && $check_sms[$key]['into_account'] == "708-2-26792-1") {
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
                        $credit_array[$i] = [ 'total_credit' => 0, ];
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
                Harmony_revenues::where('date', date('Y-m-'.$key))->update([
                    'room_transfer' => $value['total_room']
                ]);
            }
        }

        if (isset($fb_array)) {
            foreach ($fb_array as $key => $value) {
                Harmony_revenues::where('date', date('Y-m-'.$key))->update([
                    'fb_transfer' => $value['total_fb']
                ]);
            }
        }

        if (isset($wp_array)) {
            foreach ($wp_array as $key => $value) {
                Harmony_revenues::where('date', date('Y-m-'.$key))->update([
                    'wp_transfer' => $value['total_wp']
                ]);
            }
        }

        if (isset($credit_array)) {
            foreach ($credit_array as $key => $value) {
                Harmony_revenues::where('date', date('Y-m-'.$key))->update([
                    'total_credit' => $value['total_credit']
                ]);
            }
        }

        if (isset($front_array)) {
            foreach ($front_array as $key => $value) {
                Harmony_revenues::where('date', date('Y-m-'.$key))->update([
                    'front_transfer' => $value['total_front']
                ]);
            }
        }

        if (isset($agoda_array)) {
            foreach ($agoda_array as $key => $value) {
                Harmony_revenues::where('date', date('Y-m-'.$key))->update([
                    'total_credit_agoda' => $value['total_agoda']
                ]);
            }
        }

        if (isset($ev_array)) {
            foreach ($ev_array as $key => $value) {
                Harmony_revenues::where('date', date('Y-m-'.$key))->update([
                    'total_elexa' => $value['total_ev']
                ]);
            }
        }

        if (isset($other_array)) {
            foreach ($other_array as $key => $value) {
                Harmony_revenues::where('date', date('Y-m-'.$key))->update([
                    'other_revenue' => $value['total_other']
                ]);
            }
        }

        if (isset($transaction_array)) {
            foreach ($transaction_array as $key => $value) {
                Harmony_revenues::where('date', date('Y-m-'.$key))->update([
                    'total_transaction' => $value['bill']
                ]);
            }
        }

        if (isset($no_type_array)) {
            foreach ($no_type_array as $key => $value) {
                Harmony_revenues::where('date', date('Y-m-'.$key))->update([
                    'total_no_type' => $value['no_type']
                ]);
            }
        }

        $total_verified = Harmony_revenues::whereMonth('date', date('m'))->whereYear('date', date('Y'))->where('status', 1)->count();
        $total_unverified = Harmony_revenues::whereMonth('date', date('m'))->whereYear('date', date('Y'))->where('status', 0)->count();

        // Outstanding Balance From Last Year
        $lastYear = date('Y', strtotime('-1 year'));
        $agoda_outstanding_last_year = TB_outstanding_balance::where('year', $lastYear)->sum('agoda_balance');
        $elexa_outstanding_last_year = TB_outstanding_balance::where('year', $lastYear)->sum('elexa_balance');

        $total_revenue_today = Harmony_revenues::whereDate('date', date('Y-m-d'))->select(
            DB::raw("
                front_cash + front_transfer + front_credit as front_amount, 
                room_cash + room_transfer + room_credit as room_amount, 
                fb_cash + fb_transfer + fb_credit as fb_amount,
                wp_cash + wp_transfer + wp_credit as wp_amount,
                room_credit + fb_credit + wp_credit as credit_amount
            "), 'total_credit_agoda', 'other_revenue', 'total_transaction', 'total_no_type', 'status')->first();
        $total_transfer = Harmony_SMS_alerts::whereBetween('date', [$from, $to])->where('transfer_status', 1)->sum('amount');
        $total_transfer2 = Harmony_SMS_alerts::whereBetween('date', [$from, $to])->where('transfer_status', 1)->count();
        $total_split = Harmony_SMS_alerts::where('date_into', date('Y-m-d'))->where('split_status', 1)->sum('amount');
        $total_split_transaction = Harmony_SMS_alerts::whereBetween('date', [$from, $to])->where('split_status', 1)->count();
        $total_not_type = Harmony_SMS_alerts::whereBetween('date', [$from, $to])->where('status', 0)->whereNull('date_into')->count();
        $total_not_type_revenue = Harmony_SMS_alerts::whereBetween('date', [$from, $to])->where('status', 0)->whereNull('date_into')->sum('amount');
        $total_credit_transaction = Harmony_SMS_alerts::whereDate('date_into', date('Y-m-d'))->where('into_account', "708-2-26792-1")->where('status', 4)->count();

        $total_agoda_outstanding = Harmony_revenues::getManualTotalAgoda();
        $total_ev_outstanding = Harmony_revenues::getManualTotalEv();

        $total_day = $total_revenue_today->front_amount + $total_revenue_today->room_amount + $total_revenue_today->fb_amount + $total_revenue_today->wp_amount
         + $total_revenue_today->credit_amount + $total_revenue_today->total_credit_agoda + $total_revenue_today->other_revenue;

        ## ข้อมูลในตาราง
        $date = date('d');
        $symbol = $date == "01" ? "=" : "<=";

        $date_from = date('Y-m-d');
        $date_to = date('Y-m-d');

        // Hotel Fee
        $total_hotel_fee = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')
            ->whereIn('revenue_credit.status', [1, 2, 6])
            ->whereDate('date', date('Y-m-d'))
            ->groupBy('revenue.date')
            ->select(
                'revenue.date', 
                'revenue.total_credit', 
                'revenue_credit.batch', 
                'revenue_credit.revenue_type', 
                'revenue_credit.status',
                DB::raw('SUM(revenue_credit.credit_amount) - revenue.total_credit as amount'))
            ->get()->sum('amount');

        $credit_revenue = Harmony_revenues::whereDate('date', date('Y-m-d'))->select('total_credit')->first();
        $credit_revenue_today = $credit_revenue;
        $credit_revenue_month = Harmony_revenues::whereDay('date', $symbol, date('d'))->whereMonth('date', date('m'))->whereYear('date', date('Y'))->select(DB::raw("SUM(total_credit) as total_credit"))->first();
        $credit_revenue_year = Harmony_revenues::whereDate('date', '>=', date('Y-01-01'))->select(DB::raw("SUM(total_credit) as total_credit"))->first();

        $total_front_revenue = Harmony_revenues::whereDate('date', date('Y-m-d'))->select('front_cash', 'front_transfer', 'front_credit')->first();
        $today_front_revenue = $total_front_revenue;
        $total_front_month = Harmony_revenues::whereDay('date', $symbol, date('d'))->whereMonth('date', date('m'))->whereYear('date', date('Y'))->select(DB::raw("SUM(front_cash) as front_cash, SUM(front_transfer) as front_transfer, SUM(front_credit) as front_credit"))->first();
        $total_front_year = Harmony_revenues::whereDate('date', '>=', date('Y-01-01'))->select(DB::raw("SUM(front_cash) as front_cash, SUM(front_transfer) as front_transfer, SUM(front_credit) as front_credit"))->first();
        $front_charge = Harmony_revenues::getManualCharge("date", $date_from, $date_to, date('Y-m-d'), date('m'), date('Y'), 6, 6);

        $total_guest_deposit = Harmony_revenues::whereDate('date', date('Y-m-d'))->select('room_cash', 'room_transfer', 'room_credit')->first();
        $today_guest_deposit = $total_guest_deposit;
        $total_guest_deposit_month = Harmony_revenues::whereDate('date', '>=', date('Y-m-01'))->whereDate('date', $symbol, date('Y-m-d'))->select(DB::raw("SUM(room_cash) as room_cash, SUM(room_transfer) as room_transfer, SUM(room_credit) as room_credit"))->first();
        $total_guest_deposit_year = Harmony_revenues::whereDate('date', '>=', date('Y-01-01'))->select(DB::raw("SUM(room_cash) as room_cash, SUM(room_transfer) as room_transfer, SUM(room_credit) as room_credit"))->first();
        $guest_deposit_charge = Harmony_revenues::getManualCharge("date", $date_from, $date_to, date('Y-m-d'), date('m'), date('Y'), 1, 1);

        $total_fb_revenue = Harmony_revenues::whereDate('date', date('Y-m-d'))->select('fb_cash', 'fb_transfer', 'fb_credit')->first();
        $today_fb_revenue = $total_fb_revenue;
        $total_fb_month = Harmony_revenues::whereDay('date', $symbol, date('d'))->whereMonth('date', date('m'))->whereYear('date', date('Y'))->select(DB::raw("SUM(fb_cash) as fb_cash, SUM(fb_transfer) as fb_transfer, SUM(fb_credit) as fb_credit"))->first();
        $total_fb_year = Harmony_revenues::whereDate('date', '>=', date('Y-01-01'))->select(DB::raw("SUM(fb_cash) as fb_cash, SUM(fb_transfer) as fb_transfer, SUM(fb_credit) as fb_credit"))->first();
        $fb_charge = Harmony_revenues::getManualCharge("date", $date_from, $date_to, date('Y-m-d'), date('m'), date('Y'), 2, 2);

        $total_agoda_revenue = Harmony_revenues::whereDate('date', date('Y-m-d'))->sum('total_credit_agoda');
        $today_agoda_revenue = $total_agoda_revenue;
        $total_agoda_month = Harmony_revenues::whereDay('date', $symbol, date('d'))->whereMonth('date', date('m'))->whereYear('date', date('Y'))->sum('total_credit_agoda');
        $total_agoda_year = Harmony_revenues::whereDate('date', '>=', date('Y-01-01'))->sum('total_credit_agoda');
        $agoda_charge = Harmony_revenues::getManualAgodaCharge("date", $date_from, $date_to, date('Y-m-d'), date('m'), date('Y'), 1, 5);

        $total_wp_revenue = Harmony_revenues::whereDate('date', date('Y-m-d'))->select('wp_cash', 'wp_transfer', 'wp_credit')->first();
        $today_wp_revenue = $total_wp_revenue;
        $total_wp_month = Harmony_revenues::whereDay('date', $symbol, date('d'))->whereMonth('date', date('m'))->whereYear('date', date('Y'))->select(DB::raw("SUM(wp_cash) as wp_cash, SUM(wp_transfer) as wp_transfer, SUM(wp_credit) as wp_credit"))->first();
        $total_wp_year = Harmony_revenues::whereDate('date', '>=', date('Y-01-01'))->select(DB::raw("SUM(wp_cash) as wp_cash, SUM(wp_transfer) as wp_transfer, SUM(wp_credit) as wp_credit"))->first();
        $wp_charge = Harmony_revenues::getManualCharge("date", $date_from, $date_to, date('Y-m-d'), date('m'), date('Y'), 3, 3);

        $total_ev_revenue = Harmony_revenues::whereDate('date', date('Y-m-d'))->select('total_elexa')->sum('total_elexa');
        $today_ev_revenue = $total_ev_revenue;
        $total_ev_month = Harmony_revenues::whereDay('date', $symbol, date('d'))->whereMonth('date', date('m'))->whereYear('date', date('Y'))->select('total_elexa')->sum('total_elexa');
        $total_ev_year = Harmony_revenues::whereDate('date', '>=', date('Y-01-01'))->select('total_elexa')->sum('total_elexa');
        $ev_charge = Harmony_revenues::getManualEvCharge("date", $date_from, $date_to, date('Y-m-d'), date('m'), date('Y'), 8, 8);

        $total_other_revenue = Harmony_revenues::whereDate('date', date('Y-m-d'))->select('other_revenue')->sum('other_revenue');
        $today_other_revenue = $total_other_revenue;
        $total_other_month = Harmony_revenues::whereDay('date', $symbol, date('d'))->whereMonth('date', date('m'))->whereYear('date', date('Y'))->select('other_revenue')->sum('other_revenue');
        $total_other_year = Harmony_revenues::whereDate('date', '>=', date('Y-01-01'))->select('other_revenue')->sum('other_revenue');

        $by_page = 'index';

        if (isset($_GET['byPage']) && @$_GET['byPage'] == 'department') {
            $by_page = 'index_department';
        } else {
            $by_page = 'index';
        }

        if (isset($_GET['dailyPage']) && @$_GET['dailyPage'] != 'daily') {
            $by_page = 'index_'.@$_GET['dailyPage'];
        }

        $filter_by = 'date';
        $search_date = date('d/m/Y').' - '.date('d/m/Y');
        
        return view('revenue.'.$by_page, compact(
            'total_revenue_today', 
            'total_day', 
            'total_verified',
            'total_unverified',
            'total_agoda_outstanding',
            'total_ev_outstanding',
            'total_hotel_fee',
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

            'agoda_outstanding_last_year',
            'elexa_outstanding_last_year',

            'filter_by',
            'search_date'
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

        $check_data = Harmony_revenues::whereMonth('date', $month)->whereYear('date', date('Y'))->first();

        if (empty($check_data)) {
            for ($i=1; $i <= 31; $i++) { 
                Harmony_revenues::create([
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

                $check_sms = Harmony_SMS_alerts::whereBetween('date', [$from, $to])->whereNull('date_into')
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
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 4 && $check_sms[$key]['into_account'] == "708-2-26792-1") {
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
                        $credit_array[$i] = [ 'total_credit' => 0, ];
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
                $check_sms = Harmony_SMS_alerts::whereBetween('date', [date("Y-".$month."-".str_pad($i - 1, 2, '0', STR_PAD_LEFT).' 21:00:00'), date('Y-'.$month.'-'.str_pad($i, 2, '0', STR_PAD_LEFT).' 21:00:00')])->whereNull('date_into')
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
                    if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 4 && $check_sms[$key]['into_account'] == "708-2-26792-1") {
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
                        $credit_array[$i] = [ 'total_credit' => 0, ];
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

        // $check_sms = Harmony_SMS_alerts::whereBetween('date', [date("Y-m-".str_pad(5 - 1, 2, '0', STR_PAD_LEFT).' 21:00:00'), date('Y-m-'.str_pad(5, 2, '0', STR_PAD_LEFT).' 21:00:00')])
        //         ->select('sms_alert.*', DB::raw("COUNT(id) as transaction_bill, DATE(date) as date_fm, SUM(amount) as total_amount"))->groupBy('status')->get();

        $room_transfer = 0;
        $fb_transfer = 0;
        $wp_transfer = 0;
        $room_credit = 0;

        if (isset($room_array)) {
            foreach ($room_array as $key => $value) {
                Harmony_revenues::where('date', date('Y-'.$month.'-'.$key))->update([
                    'room_transfer' => $value['total_room']
                ]);
            }
        }

        if (isset($fb_array)) {
            foreach ($fb_array as $key => $value) {
                Harmony_revenues::where('date', date('Y-'.$month.'-'.$key))->update([
                    'fb_transfer' => $value['total_fb']
                ]);
            }
        }

        if (isset($wp_array)) {
            foreach ($wp_array as $key => $value) {
                Harmony_revenues::where('date', date('Y-'.$month.'-'.$key))->update([
                    'wp_transfer' => $value['total_wp']
                ]);
            }
        }

        if (isset($credit_array)) {
            foreach ($credit_array as $key => $value) {
                Harmony_revenues::where('date', date('Y-'.$month.'-'.$key))->update([
                    'total_credit' => $value['total_credit']
                ]);
            }
        }

        if (isset($front_array)) {
            foreach ($front_array as $key => $value) {
                Harmony_revenues::where('date', date('Y-'.$month.'-'.$key))->update([
                    'front_transfer' => $value['total_front']
                ]);
            }
        }

        if (isset($agoda_array)) {
            foreach ($agoda_array as $key => $value) {
                Harmony_revenues::where('date', date('Y-'.$month.'-'.$key))->update([
                    'total_credit_agoda' => $value['total_agoda']
                ]);
            }
        }

        if (isset($ev_array)) {
            foreach ($ev_array as $key => $value) {
                Harmony_revenues::where('date', date('Y-m-'.$key))->update([
                    'total_elexa' => $value['total_ev']
                ]);
            }
        }

        if (isset($other_array)) {
            foreach ($other_array as $key => $value) {
                Harmony_revenues::where('date', date('Y-m-'.$key))->update([
                    'other_revenue' => $value['total_other']
                ]);
            }
        }

        if (isset($transaction_array)) {
            foreach ($transaction_array as $key => $value) {
                Harmony_revenues::where('date', date('Y-'.$month.'-'.$key))->update([
                    'total_transaction' => $value['bill']
                ]);
            }
        }

        if (isset($no_type_array)) {
            foreach ($no_type_array as $key => $value) {
                Harmony_revenues::where('date', date('Y-'.$month.'-'.$key))->update([
                    'total_no_type' => $value['no_type']
                ]);
            }
        }

        return back();
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
        if ($request->revenue_type != '') {
            return $this->detail($request);

        } else {

            if ($request->filter_by != "week" && $request->filter_by != "thisMonth" && $request->filter_by != "thisYear") {
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

        $datetime = date('Y-m-d', strtotime($FromFormatDate));
        $last_day = $this->EOM($FromMonth, $ToMonth);
        // $last_day2 = $this->EOM(date("m", strtotime("-1 months", strtotime($datetime))), $Fyear);

        $check_data = Harmony_revenues::whereMonth('date', $FormatDate->format('m'))->whereYear('date', $FormatDate->format('Y'))->first();

        if (empty($check_data) && !empty($FromMonth)) {
            $days = $last_day;

            for ($i=1; $i <= $days; $i++) { 
                Harmony_revenues::create([
                    'date' => $FormatDate->format('Y-m').str_pad($i, 2, '0', STR_PAD_LEFT),
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
                    $check_sms = Harmony_SMS_alerts::whereBetween('date', [date("Y-m-d", strtotime("-1 day", strtotime($FromMonth))).' 21:00:00', date($FromMonth.' 20:59:59')])->whereNull('date_into')
                        ->orWhereDate('date_into', $FromMonth)->select('sms_alert.*', DB::raw("COUNT(id) as transaction_bill, DATE(date) as date_fm, SUM(amount) as total_amount"))
                        ->groupBy('status')->get();

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
                        if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 4 && $check_sms[$key]['into_account'] == "708-2-26792-1") {
                            $credit_array[$i] = [
                                'total_credit' => $check_sms[$key]['total_amount'],
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
                            $credit_array[$i] = [ 'total_credit' => 0, ];
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
                    $check_sms = Harmony_SMS_alerts::whereBetween('date', [date($FormatDate->format('Y-m-').str_pad($i - 1, 2, '0', STR_PAD_LEFT).' 21:00:00'), date($FormatDate->format('Y-m-').str_pad($i, 2, '0', STR_PAD_LEFT).' 20:59:59')])->whereNull('date_into')
                    ->orWhereDate('date_into', date($FormatDate->format('Y-m-').str_pad($i, 2, '0', STR_PAD_LEFT)))
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
                        if (isset($check_sms[$key]) && $check_sms[$key]['status'] == 4 && $check_sms[$key]['into_account'] == "708-2-26792-1") {
                            $credit_array[$i] = [
                                'total_credit' => $check_sms[$key]['total_amount'],
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
                            $credit_array[$i] = [ 'total_credit' => 0, ];
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
                    Harmony_revenues::where('date', date($FormatDate->format('Y-m').'-'.$key))->update([
                        'room_transfer' => $value['total_room']
                    ]);
                }
            }

            if (isset($fb_array)) {
                foreach ($fb_array as $key => $value) {
                    Harmony_revenues::where('date', date($FormatDate->format('Y-m').'-'.$key))->update([
                        'fb_transfer' => $value['total_fb']
                    ]);
                }
            }

            if (isset($wp_array)) {
                foreach ($wp_array as $key => $value) {
                    Harmony_revenues::where('date', date($FormatDate->format('Y-m').'-'.$key))->update([
                        'wp_transfer' => $value['total_wp']
                    ]);
                }
            }

            if (isset($credit_array)) {
                foreach ($credit_array as $key => $value) {
                    Harmony_revenues::where('date', date($FormatDate->format('Y-m').'-'.$key))->update([
                        'total_credit' => $value['total_credit']
                    ]);
                }
            }

            if (isset($front_array)) {
                foreach ($front_array as $key => $value) {
                    Harmony_revenues::where('date', date($FormatDate->format('Y-m').'-'.$key))->update([
                        'front_transfer' => $value['total_front']
                    ]);
                }
            }

            if (isset($agoda_array)) {
                foreach ($agoda_array as $key => $value) {
                    // dd($value['total_agoda']);
                    Harmony_revenues::where('date', date($FormatDate->format('Y-m').'-'.$key))->update([
                        'total_credit_agoda' => $value['total_agoda']
                    ]);
                }
            }

            if (isset($ev_array)) {
                foreach ($ev_array as $key => $value) {
                    Harmony_revenues::where('date', date($FormatDate->format('Y-m').'-'.$key))->update([
                        'total_elexa' => $value['total_ev']
                    ]);
                }
            }

            if (isset($other_array)) {
                foreach ($other_array as $key => $value) {
                    Harmony_revenues::where('date', date($FormatDate->format('Y-m').'-'.$key))->update([
                        'other_revenue' => $value['total_other']
                    ]);
                }
            }

            if (isset($transaction_array)) {
                foreach ($transaction_array as $key => $value) {
                    Harmony_revenues::where('date', date($FormatDate->format('Y-m').'-'.$key))->update([
                        'total_transaction' => $value['bill']
                    ]);
                }
            }

            if (isset($no_type_array)) {
                foreach ($no_type_array as $key => $value) {
                    Harmony_revenues::where('date', date($FormatDate->format('Y-m').'-'.$key))->update([
                        'total_no_type' => $value['no_type']
                    ]);
                }
            }

        $date1 = date('Y-m-d', strtotime(date($FormatDate->format('Y-m').'-01')));
        $date2 = date('Y-m-d', strtotime('last day of this month', strtotime(date(date($FormatDate->format('Y-m').'-01')))));

        // verified
        $total_verified = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->where('status', 1)->count();
        $total_unverified = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->where('status', 0)->count();

        // Outstanding Balance From Last Year
        $lastYear = date('Y', strtotime('-1 year'));
        $agoda_outstanding_last_year = TB_outstanding_balance::where('year', $lastYear)->sum('agoda_balance');
        $elexa_outstanding_last_year = TB_outstanding_balance::where('year', $lastYear)->sum('elexa_balance');

        $total_revenue_today = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->select(
            DB::raw("
                SUM(front_cash + front_transfer + front_credit) as front_amount, 
                SUM(room_cash + room_transfer + room_credit) as room_amount, 
                SUM(fb_cash + fb_transfer + fb_credit) as fb_amount,
                SUM(wp_cash + wp_transfer + wp_credit) as wp_amount,
                SUM(room_credit + fb_credit + wp_credit) as credit_amount,
                SUM(total_transaction) as total_transaction,
                SUM(total_credit_agoda) as total_credit_agoda, SUM(other_revenue) as other_revenue"), 'total_no_type', 'status')
            ->first();

        $total_transfer = Harmony_SMS_alerts::whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('transfer_status', 1)->sum('amount');
        $total_transfer2 = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('transfer_status', 1)->count();
        $total_split = Harmony_SMS_alerts::whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('split_status', 1)->sum('amount');
        $total_split_transaction = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('split_status', 1)->count();
        $total_not_type = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 0)->whereNull('date_into')->count();
        $total_not_type_revenue = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 0)->whereNull('date_into')->sum('amount');

        ### Credit Transaction ### // Date, Month, Year
        $total_credit_transaction = Harmony_SMS_alerts::whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('into_account', "708-2-26792-1")->where('status', 4)->count();

        $total_agoda_outstanding = Harmony_revenues::getManualTotalAgoda();
        $total_ev_outstanding = Harmony_revenues::getManualTotalEv();

        $total_day = $total_revenue_today->front_amount + $total_revenue_today->room_amount + $total_revenue_today->fb_amount
         + $total_revenue_today->wp_amount + $total_revenue_today->credit_amount + $total_revenue_today->total_credit_agoda + $total_revenue_today->other_revenue;

        ## Hotel Fee ##
        $total_hotel_fee = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')
            ->whereIn('revenue_credit.status', [1, 2, 6])
            ->whereBetween('date', [$FromFormatDate, $ToFormatDate])
            ->groupBy('revenue.date')
            ->select(
                'revenue.date', 
                'revenue.total_credit', 
                'revenue_credit.batch', 
                'revenue_credit.revenue_type', 
                'revenue_credit.status',
                DB::raw('SUM(revenue_credit.credit_amount) - revenue.total_credit as amount'))
            ->get()->sum('amount');

        ## ข้อมูลในตาราง

        ### Credit Card Hotel ###
        // Today
        $credit_revenue_today = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->select(DB::raw("SUM(total_credit) as total_credit"))->first();

        // Date
        $credit_revenue = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->select(DB::raw("SUM(total_credit) as total_credit"))->first();

        // Month
        $credit_revenue_month = Harmony_revenues::whereBetween('date', [$FromMonth, $ToMonth])->select(DB::raw("SUM(total_credit) as total_credit"))->first();

        // Year
        $credit_revenue_year = Harmony_revenues::whereBetween('date', [$FromYear, $ToYear])->select(DB::raw("SUM(total_credit) as total_credit"))->first();

        ### Front Desk ###
        // Today
        $today_front_revenue = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->select(DB::raw("SUM(front_cash) as front_cash, SUM(front_transfer) as front_transfer, SUM(front_credit) as front_credit"))->first();

        // Date
        $total_front_revenue = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->select(DB::raw("SUM(front_cash) as front_cash, SUM(front_transfer) as front_transfer, SUM(front_credit) as front_credit"))->first();

        // Month
        $total_front_month = Harmony_revenues::whereBetween('date', [$FromMonth, $ToMonth])->select(DB::raw("SUM(front_cash) as front_cash, SUM(front_transfer) as front_transfer, SUM(front_credit) as front_credit"))->first();

        // Year
        $total_front_year = Harmony_revenues::whereBetween('date', [$FromYear, $ToYear])->select(DB::raw("SUM(front_cash) as front_cash, SUM(front_transfer) as front_transfer, SUM(front_credit) as front_credit"))->first();

        // Charge
        $front_charge = $this->getManualCharge($FromFormatDate, $ToFormatDate, $FromMonth, $ToMonth, $FromYear, $ToYear, 6);

        ### Guest Deposit ###
        // Today
        $today_guest_deposit = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate]) ->select(DB::raw("SUM(room_cash) as room_cash, SUM(room_transfer) as room_transfer, SUM(room_credit) as room_credit"))->first();

        // Date
        $total_guest_deposit = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->select(DB::raw("SUM(room_cash) as room_cash, SUM(room_transfer) as room_transfer, SUM(room_credit) as room_credit"))->first();

        // Month
        $total_guest_deposit_month = Harmony_revenues::whereBetween('date', [$FromMonth, $ToMonth])->select(DB::raw("SUM(room_cash) as room_cash, SUM(room_transfer) as room_transfer, SUM(room_credit) as room_credit"))->first();

        // Year
        $total_guest_deposit_year = Harmony_revenues::whereBetween('date', [$FromYear, $ToYear])->select(DB::raw("SUM(room_cash) as room_cash, SUM(room_transfer) as room_transfer, SUM(room_credit) as room_credit"))->first();

        // Charge
        $guest_deposit_charge = $this->getManualCharge($FromFormatDate, $ToFormatDate, $FromMonth, $ToMonth, $FromYear, $ToYear, 1);
 
        ### All Outlet ###
        // Today 
        $today_fb_revenue = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->select(DB::raw("SUM(fb_cash) as fb_cash, SUM(fb_transfer) as fb_transfer, SUM(fb_credit) as fb_credit"))->first();

        // Date
        $total_fb_revenue = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->select(DB::raw("SUM(fb_cash) as fb_cash, SUM(fb_transfer) as fb_transfer, SUM(fb_credit) as fb_credit"))->first();

        // Month
        $total_fb_month = Harmony_revenues::whereBetween('date', [$FromMonth, $ToMonth])->select(DB::raw("SUM(fb_cash) as fb_cash, SUM(fb_transfer) as fb_transfer, SUM(fb_credit) as fb_credit"))->first();

        // Year
        $total_fb_year = Harmony_revenues::whereBetween('date', [$FromYear, $ToYear])->select(DB::raw("SUM(fb_cash) as fb_cash, SUM(fb_transfer) as fb_transfer, SUM(fb_credit) as fb_credit"))->first();

        // Charge
        $fb_charge = $this->getManualCharge($FromFormatDate, $ToFormatDate, $FromMonth, $ToMonth, $FromYear, $ToYear, 2);

        ## Other Revenue ###
        // Today
        $today_other_revenue = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->select('other_revenue')->sum('other_revenue');

        // Date
        $total_other_revenue = Harmony_revenues::whereBetween('date', [$FromMonth, $ToMonth])->select('other_revenue')->sum('other_revenue');

        // Month
        $total_other_month = Harmony_revenues::whereBetween('date', [$FromMonth, $ToMonth])->select('other_revenue')->sum('other_revenue');

        // Year
        $total_other_year = Harmony_revenues::whereBetween('date', [$FromYear, $ToYear])->select('other_revenue')->sum('other_revenue');

        ### Agoda ###
        // Today
        $today_agoda_revenue = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->sum('total_credit_agoda');

        // Date
        $total_agoda_revenue = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->sum('total_credit_agoda');

        // Month
        $total_agoda_month = Harmony_revenues::whereBetween('date', [$FromMonth, $ToMonth])->sum('total_credit_agoda');

        // Year
        $total_agoda_year = Harmony_revenues::whereBetween('date', [$FromYear, $ToYear])->sum('total_credit_agoda');

        // Charge
        $agoda_charge = $this->getManualAgodaCharge($FromFormatDate, $ToFormatDate, $FromMonth, $ToMonth, $FromYear, $ToYear, 5);

        ### Water Park ###
        // Today
        $today_wp_revenue = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->select(DB::raw("SUM(wp_cash) as wp_cash, SUM(wp_transfer) as wp_transfer, SUM(wp_credit) as wp_credit"))->first();

        // Date 
        $total_wp_revenue = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->select(DB::raw("SUM(wp_cash) as wp_cash, SUM(wp_transfer) as wp_transfer, SUM(wp_credit) as wp_credit"))->first();

        // Month
        $total_wp_month = Harmony_revenues::whereBetween('date', [$FromMonth, $ToMonth])->select(DB::raw("SUM(wp_cash) as wp_cash, SUM(wp_transfer) as wp_transfer, SUM(wp_credit) as wp_credit"))->first();

        // Year
        $total_wp_year = Harmony_revenues::whereBetween('date', [$FromYear, $ToYear])->select(DB::raw("SUM(wp_cash) as wp_cash, SUM(wp_transfer) as wp_transfer, SUM(wp_credit) as wp_credit"))->first();

        // Charge
        $wp_charge = $this->getManualCharge($FromFormatDate, $ToFormatDate, $FromMonth, $ToMonth, $FromYear, $ToYear, 3);

        ### Elexa EGAT ###
        // Today
        $today_ev_revenue = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->select('total_elexa')->sum('total_elexa');

        // Date
        $total_ev_revenue = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->select('total_elexa')->sum('total_elexa');

        // Month
        $total_ev_month = Harmony_revenues::whereBetween('date', [$FromMonth, $ToMonth])->select('total_elexa')->sum('total_elexa');

        // Year
        $total_ev_year = Harmony_revenues::whereBetween('date', [$FromYear, $ToYear])->select('total_elexa')->sum('total_elexa');

        // Charge
        $ev_charge = $this->getManualEvCharge($FromFormatDate, $ToFormatDate, $FromMonth, $ToMonth, $FromYear, $ToYear, 8);

        ## Filter ##
        $search_date = $request->date;

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
                        'total_revenue_today', 
                        'total_day', 
                        'total_verified', 
                        'total_unverified', 
                        'total_agoda_outstanding',
                        'total_ev_outstanding',
                        'total_hotel_fee',
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

                        'agoda_outstanding_last_year',
                        'elexa_outstanding_last_year',
            
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
                    'total_hotel_fee',
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

                    'agoda_outstanding_last_year',
                    'elexa_outstanding_last_year',
        
                    'btn_by_page',
        
                    'filter_by', 'search_date'
                ));
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

        $check_credit = Harmony_revenues::where('date', $request->date)->first();
        Harmony_revenue_credit::where('revenue_id', $check_credit->id)->where('status', '!=', 5)->delete();

        if (!empty($request->guest_batch)) {
            foreach ($request->guest_batch as $key => $value) {
                // $charge += $request->credit_amount[$key];

                Harmony_revenue_credit::create([
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

                Harmony_revenue_credit::create([
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

                Harmony_revenue_credit::create([
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

                Harmony_revenue_credit::create([
                    'revenue_id' => $check_credit->id,
                    'batch' => $request->batch[$key],
                    'revenue_type' => $request->revenue_type[$key],
                    'credit_amount' => $request->credit_amount[$key],
                    'status' => 4
                ]);
            }
        }

        if (!empty($request->agoda_batch)) {
            Harmony_revenue_credit::whereNotIn('batch', $request->agoda_batch)->where('revenue_id', $check_credit->id)->where('status', 5)->delete();
            foreach ($request->agoda_batch as $key => $value) {
                $agoda_charge += $request->agoda_credit_amount[$key];
                $agoda_outstanding += $request->agoda_credit_outstanding[$key];

                $check_agoda = Harmony_revenue_credit::where('batch', $request->agoda_batch[$key])->where('revenue_id', $check_credit->id)->where('status', 5)->first();

                if (!empty($check_agoda)) {
                    Harmony_revenue_credit::where('batch', $request->agoda_batch[$key])->where('revenue_id', $check_credit->id)->update([
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
                    Harmony_revenue_credit::create([
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
                Harmony_revenue_credit::where('revenue_id', $check_credit->id)->where('status', 5)->delete();
            }
        }

        if (!empty($request->front_batch)) {
            foreach ($request->front_batch as $key => $value) {
                // $charge += $request->credit_amount[$key];
                Harmony_revenue_credit::create([
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

                Harmony_revenue_credit::create([
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

        Harmony_revenues::where('date', $request->date)->update([
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
        $data = Harmony_revenues::where('date', $date)->first();
        $data_credit = Harmony_revenue_credit::where('revenue_id', $data->id)->get();

        return response()->json([
            'data' => $data,
            'data_credit' => $data_credit
        ]);
    }

    public function daily_close(Request $request)
    {
        $date = Carbon::parse($request->date)->format('Y-m-d');
        Harmony_revenues::where('date', $date)->update([
            'status' => 1
        ]);

        return response()->json([
            'status' => 200
        ]);
    }

    public function daily_open(Request $request)
    {
        $date = Carbon::parse($request->date)->format('Y-m-d');
        Harmony_revenues::where('date', $date)->update([
            'status' => 0
        ]);

        return response()->json([
            'status' => 200
        ]);
    }

    public function export()
    {
        $data = Harmony_revenues::whereMonth('date', date('m'))->whereYear('date', date('Y'))->get();
        $pdf = FacadePdf::loadView('pdf.1A', compact('data'));

        return $pdf->stream();
    }

    public function detail(Request $request)
    {
        if ($request->filter_by != "week" && $request->filter_by != "thisMonth" && $request->filter_by != "thisYear") {
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

        // dd($request);

        ## Cash
        if ($request->revenue_type == "cash_front") {
            $data_query = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->where('front_cash', '>', 0)->select('date', 'front_cash as amount')->paginate(10);
            $total_query = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->where('front_cash', '>', 0)->sum('front_cash');
            $title = "Front Desk Revenue (Cash)";
            $status = 'cash_front';
            $revenue_name = "cash";

        } if ($request->revenue_type == "cash_all_outlet") {
            $data_query = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->where('fb_cash', '>', 0)->select('date', 'fb_cash as amount')->paginate(10);
            $total_query = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->where('fb_cash', '>', 0)->sum('fb_cash');
            $title = "All Outlet Revenue (Cash)";
            $status = 'cash_all_outlet';
            $revenue_name = "cash";

        } if ($request->revenue_type == "cash_guest") {
            $data_query = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->where('room_cash', '>', 0)->select('date', 'room_cash as amount')->paginate(10);
            $total_query = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->where('room_cash', '>', 0)->sum('room_cash');
            $title = "Guest Deposit Revenue (Cash)";
            $status = 'cash_guest';
            $revenue_name = "cash";

        } if ($request->revenue_type == "cash_water_park") {
            $data_query = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->where('wp_cash', '>', 0)->select('date', 'wp_cash as amount')->paginate(10);
            $total_query = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->where('wp_cash', '>', 0)->sum('wp_cash');
            $title = "Water Park Revenue (Cash)";
            $status = 'cash_water_park';
            $revenue_name = "cash";

        }

        ## Bank Transfer
        if ($request->revenue_type == "tf_front") {
            $data_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 6)->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 6)->orderBy('date', 'asc')->paginate(10);
            $total_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 6)->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 6)->sum('amount');
            $title = "Front Desk";
            $status = 6;
            $revenue_name = "";

        } if($request->revenue_type == "tf_guest") {
            $data_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 1)->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 1)->orderBy('date', 'asc')->paginate(10);
            $total_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 1)->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 1)->sum('amount');
            $title = "Guest Deposit";
            $status = 1;
            $revenue_name = "";

        } if($request->revenue_type == "tf_all_outlet") {
            $data_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 2)->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 2)->orderBy('date', 'asc')->paginate(10);
            $total_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 2)->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 2)->sum('amount');
            $title = "All Outlet Revenue";
            $status = 2;
            $revenue_name = "";

        } if ($request->revenue_type == "tf_water_park") {
            $data_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 3)->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 3)->orderBy('date', 'asc')->paginate(10);
            $total_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 3)->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 3)->sum('amount');
            $title = "Water Park Revenue";
            $status = 3;
            $revenue_name = "";

        } if($request->revenue_type == "tf_agoda") {
            $data_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 5)->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 5)->orderBy('date', 'asc')->paginate(10);
            $total_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 5)->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 5)->sum('amount');
            $title = "Agoda Revenue";
            $status = 5;
            $revenue_name = "";

        } if($request->revenue_type == "tf_elexa") {
            $data_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 8)->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 8)->orderBy('date', 'asc')->paginate(10);
            $total_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 8)->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 8)->sum('amount');
            $title = "Elexa EGAT Revenue";
            $status = 8;
            $revenue_name = "";

        } if($request->revenue_type == "tf_other") {
            $data_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 9)->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 9)->orderBy('date', 'asc')->paginate(10);
            $total_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 9)->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 9)->sum('amount');
            $title = "Other Revenue";
            $status = 9;
            $revenue_name = "";

        } 

        ## Credit Card
        if($request->revenue_type == "cc_credit_hotel") {
            $data_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 4)->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 4)->paginate(10);
            $total_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 4)->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 4)->sum('amount');
            $title = "Credit Card Hotel Revenue";
            $status = 4;
            $revenue_name = "";

        } if($request->revenue_type == "cc_credit_water_park") {
            $data_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 7)->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 7)->paginate(10);
            $total_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 7)->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', 7)->sum('amount');
            $title = "Credit Card Water Park Revenue";
            $status = 7;
            $revenue_name = "";

        } 

        ## Manual Charge
        if($request->revenue_type == "mc_front_charge") {
            $data_query = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 6)
                ->where('revenue_credit.revenue_type', 6)->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])
                ->select('revenue.date', 'revenue.total_credit', 'revenue_credit.batch', 'revenue_credit.revenue_type', 'revenue_credit.credit_amount', 'revenue_credit.status')->paginate(10);
            $total_query = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 6)
                ->where('revenue_credit.revenue_type', 6)->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])->sum('revenue_credit.credit_amount');
            $title = "Credit Card Front Desk";
            $status = "manual_charge_6";
            $revenue_name = "";

        } if($request->revenue_type == "mc_guest_charge") {
            $data_query = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 1)
                ->where('revenue_credit.revenue_type', 1)->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])
                ->select('revenue.date', 'revenue.total_credit', 'revenue_credit.batch', 'revenue_credit.revenue_type', 'revenue_credit.credit_amount', 'revenue_credit.status')->paginate(10);
            $total_query = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 1)
                ->where('revenue_credit.revenue_type', 1)->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])->sum('revenue_credit.credit_amount');
            $title = "Credit Card Guest Deposit";
            $status = "manual_charge_1";
            $revenue_name = "";

        } if($request->revenue_type == "mc_all_outlet_charge") {
            $data_query = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 2)
                ->where('revenue_credit.revenue_type', 2)->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])
                ->select('revenue.date', 'revenue.total_credit', 'revenue_credit.batch', 'revenue_credit.revenue_type', 'revenue_credit.credit_amount', 'revenue_credit.status')->paginate(10);
            $total_query = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 2)
                ->where('revenue_credit.revenue_type', 2)->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])->sum('revenue_credit.credit_amount');
            $title = "Credit Card All Outlet";
            $status = "manual_charge_2";
            $revenue_name = "";

        } if($request->revenue_type == "mc_water_park_charge") {
            $data_query = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 3)
                ->where('revenue_credit.revenue_type', 3)->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])
                ->select('revenue.date', 'revenue.total_credit', 'revenue_credit.batch', 'revenue_credit.revenue_type', 'revenue_credit.credit_amount', 'revenue_credit.status')->paginate(10);
            $total_query = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 3)
                ->where('revenue_credit.revenue_type', 3)->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])->sum('revenue_credit.credit_amount');
            $title = "Credit Card Water Park";
            $status = "manual_charge_3";
            $revenue_name = "";

        } if($request->revenue_type == "mc_agoda_charge") {
            $data_query = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 5)
                ->where('revenue_credit.revenue_type', 1)->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])
                ->select('revenue.date', 'revenue_credit.batch', 'revenue_credit.agoda_charge', 'revenue_credit.agoda_outstanding', 'revenue_credit.status')->paginate(10);
            $total_query = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 5)
                ->where('revenue_credit.revenue_type', 1)->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])->sum('revenue_credit.agoda_charge');
            $title = "Agoda Charge";
            $status = "mc_agoda_charge";
            $revenue_name = "";

        } if($request->revenue_type == "mc_elexa_charge") {
            $data_query = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')
                ->where('revenue_credit.status', 8)->where('revenue_credit.revenue_type', 8)->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])
                ->select('revenue.date', 'revenue_credit.batch', 'revenue_credit.ev_charge', 'revenue_credit.ev_fee', 'revenue_credit.ev_vat', 'revenue_credit.ev_revenue')->orderBy('revenue.date', 'asc')->paginate(10);
            $total_query = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')
                ->where('revenue_credit.status', 8)->where('revenue_credit.revenue_type', 8)->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])->sum('revenue_credit.ev_charge');
            $title = "Elexa EGAT Charge";
            $status = "mc_elexa_charge";
            $revenue_name = "";
        }

        ## Fee
        if($request->revenue_type == "credit_hotel_fee") {
            $data_query = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')
                ->whereIn('revenue_credit.status', [1, 2, 6])
                ->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])
                ->groupBy('revenue.date')
                ->select(
                    'revenue.date', 
                    'revenue.total_credit', 
                    'revenue_credit.batch', 
                    'revenue_credit.revenue_type', 
                    'revenue_credit.status',
                    DB::raw('SUM(revenue_credit.credit_amount) - revenue.total_credit as amount, SUM(revenue.total_credit)'))
                ->paginate(10);

            $total_query = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')
                ->whereIn('revenue_credit.status', [1, 2, 6])
                ->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])
                ->groupBy('revenue.date')
                ->select(
                    'revenue.date', 
                    'revenue.total_credit', 
                    'revenue_credit.batch', 
                    'revenue_credit.revenue_type', 
                    'revenue_credit.status',
                    DB::raw('SUM(revenue_credit.credit_amount) - revenue.total_credit as amount'))
                ->get()->sum('amount');

            $title = "Credit Card Hotel Fee";
            $status = "credit_hotel_fee";
            $revenue_name = "fee";

        } if($request->revenue_type == "agoda_fee") {
            $data_query = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 5)
                ->where('revenue_credit.revenue_type', 1)->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])
                ->select('revenue.date', 'revenue_credit.batch', 'revenue_credit.agoda_charge', 'revenue_credit.agoda_outstanding', 'revenue_credit.status', 
                    DB::raw('revenue_credit.agoda_charge - revenue_credit.agoda_outstanding as fee'))->paginate(10);

            $total_query = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 5)
                ->where('revenue_credit.revenue_type', 1)->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])->sum(DB::raw('revenue_credit.agoda_charge - revenue_credit.agoda_outstanding'));
            $title = "Agoda Fee";
            $status = "agoda_fee";
            $revenue_name = "agoda_fee";

        } if($request->revenue_type == "water_park_fee") {
            $data_query = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->whereIn('revenue_credit.status', [7])
                ->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])
                ->select('revenue.date', 'revenue.total_credit', 'revenue_credit.batch', 'revenue_credit.revenue_type', 'revenue_credit.credit_amount', 'revenue_credit.status',
                    DB::raw('SUM(revenue_credit.credit_amount) - revenue.total_credit as amount'))->groupBy('revenue.date')
                ->paginate(10);

            $total_query = Harmony_revenues::leftJoin('revenue_credit', 'revenue.id', '=', 'revenue_credit.revenue_id')
                ->whereIn('revenue_credit.status', [7])->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])
                ->groupBy('revenue.date')->select(DB::raw('SUM(revenue_credit.credit_amount) - revenue.total_credit as total_difference'))
                ->get()->sum('total_difference');
            $title = "Credit Card Water Park Fee";
            $status = "water_park_fee";
            $revenue_name = "fee";

        } if($request->revenue_type == "elexa_fee") {
            $data_query = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->whereIn('revenue_credit.status', [8])
                ->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])
                ->select('revenue.date', 'revenue_credit.ev_charge', 'revenue_credit.ev_fee', 'revenue_credit.ev_vat', 'revenue_credit.ev_revenue', 'revenue_credit.status as credit_status', 
                DB::raw('SUM(revenue_credit.ev_fee) + SUM(revenue_credit.ev_vat) as amount'))
                ->groupBy('revenue.date')->paginate(10);

            $total_query = Harmony_revenues::leftJoin('revenue_credit', 'revenue.id', '=', 'revenue_credit.revenue_id')
                ->whereIn('revenue_credit.status', [8])->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])
                ->select('revenue.date', 'revenue_credit.ev_charge', 'revenue_credit.ev_fee', 'revenue_credit.ev_vat', 'revenue_credit.ev_revenue', 
                DB::raw('SUM(revenue_credit.ev_fee) + SUM(revenue_credit.ev_vat) as amount'))
                ->groupBy('revenue.date')->get()->sum('amount');
            $title = "Elexa EGAT Fee";
            $status = "elexa_fee";
            $revenue_name = "fee";

        }


        ## Total Revenue Outstanding
        if($request->revenue_type == "agoda_outstanding") {
            $data_query = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 5)
                ->where('receive_payment', 0)
                ->where('revenue_credit.revenue_type', 1)->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])
                ->select('revenue.date', 'revenue_credit.batch', 'revenue_credit.agoda_charge', 'revenue_credit.agoda_outstanding', 'revenue_credit.status')->orderBy('revenue.date', 'asc')->paginate(10);
            $total_query = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 5)
                ->where('receive_payment', 0)
                ->where('revenue_credit.revenue_type', 1)->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])->sum('revenue_credit.agoda_outstanding');
            $title = "Credit Agoda Revenue Outstanding";
            $status = "agoda_outstanding";
            $revenue_name = "";

        } if($request->revenue_type == "elexa_outstanding") {
            $data_query = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')
                ->where('revenue_credit.status', 8)->where('receive_payment', 0)->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])
                ->select('revenue.date', 'revenue_credit.ev_charge', 'revenue_credit.ev_fee', 'revenue_credit.ev_vat', 'revenue_credit.ev_revenue')->orderBy('revenue.date', 'asc')->paginate(10);
            $total_query = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')
                ->where('revenue_credit.status', 8)->where('receive_payment', 0)->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])->sum('revenue_credit.ev_revenue');
            $title = "Elexa EGAT Revenue Outstanding";
            $status = "elexa_outstanding";
            $revenue_name = "";

        } 

        ## Type
        if ($request->revenue_type == "transfer_revenue") {
            $data_query = Harmony_SMS_alerts::whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('transfer_status', 1)->paginate(10);
            $total_query = Harmony_SMS_alerts::whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('transfer_status', 1)->sum('amount');
            $title = "Transfer Revenue";
            $status = 'transfer_revenue';
            $revenue_name = "type";

        } if ($request->revenue_type == "credit_hotel_transfer") {
            $data_query = Harmony_SMS_alerts::whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('into_account', "708-2-26792-1")->where('status', 4)->paginate(10);
            $total_query = Harmony_SMS_alerts::whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('into_account', "708-2-26792-1")->where('status', 4)->count();
            $title = "Credit Card Hotel Transfer Transaction";
            $status = 'credit_hotel_transfer';
            $revenue_name = "type";

        } if ($request->revenue_type == "split_hotel_revenue") {
            $data_query = Harmony_SMS_alerts::whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('split_status', 1)->paginate(10);
            $total_query = Harmony_SMS_alerts::whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('split_status', 1)->sum('amount');
            $title = "Split Credit Card Hotel Revenue";
            $status = 'split_hotel_revenue';
            $revenue_name = "type";
            
        } if ($request->revenue_type == "split_hotel_transaction") {
            $data_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('split_status', 1)->paginate(10);
            $total_query = Harmony_SMS_alerts::where('date', [$smsFromDate, $smsToDate])->where('split_status', 1)->sum('amount');
            $title = "Split Credit Card Hotel Transaction";
            $status = 'split_hotel_transaction';
            $revenue_name = "type";

        } if ($request->revenue_type == "no_income_revenue") {
            $data_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 0)->whereNull('date_into')->paginate(10);
            $total_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 0)->whereNull('date_into')->sum('amount');
            $title = "No Income Revenue";
            $status = 'no_income_revenue';
            $revenue_name = "type";

        } if ($request->revenue_type == "total_transaction") {
            $data_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->orderBy('date', 'asc')->paginate(10);
            $total_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->orderBy('date', 'asc')->sum('amount');
            $title = "Total Trandaction";
            $status = 'total_transaction';
            $revenue_name = "type";

        } if ($request->revenue_type == "transfer_transaction") {
            $data_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('transfer_status', 1)->paginate(10);
            $total_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('transfer_status', 1)->sum('amount');
            $title = "Transfer Trandaction";
            $status = 'transfer_transaction';
            $revenue_name = "type";

        } if ($request->revenue_type == "no_income_type") {
            $data_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 0)->whereNull('date_into')->paginate(10);
            $total_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 0)->whereNull('date_into')->sum('amount');
            $title = "No Incoming Type";
            $status = 'no_income_type';
            $revenue_name = "type";
        }

        ## Verified / Unverified
        // $date1 = date('Y-m-01', strtotime($month_from));
        // $date2 = date('Y-m-d', strtotime('last day of this month', strtotime($month_from)));

        if ($request->revenue_type == "verified") {
            $data_query = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->where('status', 1)->paginate(10);
            $total_query = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->where('status', 1)->count();
            $title = "Verified";
            $status = 'verified';
            $revenue_name = "verified";

        } if ($request->revenue_type == "unverified") {
            $data_query = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->where('status', 0)->paginate(10);
            $total_query = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->where('status', 0)->count();
            $title = "Unverified";
            $status = 'unverified';
            $revenue_name = "verified";

        }

        ## Filter ##
        $filter_by = $request->filter_by;
        $search_date = $request->filter_by == "customRang" ? $request->customRang_start." ".$request->customRang_end : $request->date;

        $exp = explode("_", $request->revenue_type);

        if ($exp[0] == "mc" && $request->revenue_type != "mc_agoda_charge" && $request->revenue_type != "mc_elexa_charge") {
            return view('revenue.manual_charge_detail', compact('data_query', 'total_query', 'title', 'filter_by', 'search_date', 'status'));
        } elseif ($request->revenue_type == "mc_agoda_charge") {
            return view('revenue.manual_agoda_charge_detail', compact('data_query', 'total_query', 'title', 'filter_by', 'search_date', 'status'));
        } elseif ($request->revenue_type == "mc_elexa_charge") {
            return view('revenue.manual_elexa_charge_detail', compact('data_query', 'total_query', 'title', 'filter_by', 'search_date', 'status'));
        } elseif ($request->revenue_type == "agoda_outstanding") {
            return view('revenue.agoda_outstanding_detail', compact('data_query', 'total_query', 'title', 'filter_by', 'search_date', 'status'));
        } elseif ($request->revenue_type == "elexa_outstanding") {
            return view('revenue.elexa_outstanding_detail', compact('data_query', 'total_query', 'title', 'filter_by', 'search_date', 'status'));
        } elseif ($revenue_name == "type") {
            return view('revenue.type_detail', compact('data_query', 'total_query', 'title', 'filter_by', 'search_date', 'status'));
        } elseif ($revenue_name == "verified") {
            return view('revenue.verified_detail', compact('data_query', 'total_query', 'title', 'filter_by', 'search_date', 'status'));
        } elseif ($revenue_name == "cash") {
            return view('revenue.detail_cash', compact('data_query', 'total_query', 'title', 'filter_by', 'search_date', 'status'));
        } elseif ($revenue_name == "fee") {
            return view('revenue.fee_detail', compact('data_query', 'total_query', 'title', 'filter_by', 'search_date', 'status'));
        }  elseif ($revenue_name == "agoda_fee") {
            return view('revenue.fee_agoda_detail', compact('data_query', 'total_query', 'title', 'filter_by', 'search_date', 'status'));
        } 
        else {
            return view('revenue.detail', compact('data_query', 'total_query', 'title', 'filter_by', 'search_date', 'status'));
        }
    }

    public function paginate_table(Request $request)
    {
        if ($request->filter_by != "week" && $request->filter_by != "thisMonth" && $request->filter_by != "thisYear") {
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
        $exp = explode("_", $request->status);
        if ((int)$request->status != 0) { 
            if ($request->table_name == "revenueTable") {
                $query_sms = Harmony_SMS_alerts::query()->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('status', $request->status)
                    ->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', $request->status)->orderBy('date', 'asc');
    
                if ($perPage == 10) {
                    $data_query = $query_sms->limit($request->page.'0')->get();
                } else {
                    $data_query = $query_sms->paginate($perPage);
                }
            }
        } else {
            if ($request->status != "mc_elexa_charge" && $request->status != "mc_agoda_charge" && count($exp) > 1 && $exp[0]."_".$exp[1] == "manual_charge") {
                $query_revenue = Harmony_revenues::query()->leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', $exp[2])
                    ->where('revenue_credit.revenue_type', $exp[2])->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])
                    ->select('revenue.date', 'revenue.total_credit', 'revenue_credit.batch', 'revenue_credit.revenue_type', 'revenue_credit.credit_amount', 'revenue_credit.status');

                if ($perPage == 10) {
                    $data_query = $query_revenue->limit($request->page.'0')->get();
                } else {
                    $data_query = $query_revenue->paginate($perPage);
                }
            } elseif ($request->status == "mc_agoda_charge") {
                $query_revenue = Harmony_revenues::query()->leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 5)
                    ->where('revenue_credit.revenue_type', 1)->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])
                    ->select('revenue.date', 'revenue_credit.batch', 'revenue_credit.agoda_charge', 'revenue_credit.agoda_outstanding', 'revenue_credit.status');

                    if ($perPage == 10) {
                        $data_query = $query_revenue->limit($request->page.'0')->get();
                    } else {
                        $data_query = $query_revenue->paginate($perPage);
                    }

            } elseif ($request->status == "mc_elexa_charge") {
                $query_revenue = Harmony_revenues::query()->leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')
                    ->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])->where('revenue_credit.status', 8)
                    ->select('revenue.date', 'revenue_credit.batch', 'revenue_credit.ev_charge', 'revenue_credit.ev_fee', 'revenue_credit.ev_vat', 'revenue_credit.ev_revenue')
                    ->orderBy('revenue.date', 'asc');

                    if ($perPage == 10) {
                        $data_query = $query_revenue->limit($request->page.'0')->get();
                    } else {
                        $data_query = $query_revenue->paginate($perPage);
                    }

            } elseif ($request->status == "agoda_outstanding") {
                $query_revenue = Harmony_revenues::query()->leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 5)
                    ->where('revenue_credit.receive_payment', 0)->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])->orderBy('date', 'asc')
                    ->select('revenue.date', 'revenue_credit.batch', 'revenue_credit.agoda_charge', 'revenue_credit.agoda_outstanding', 'revenue_credit.status');

                    if ($perPage == 10) {
                        $data_query = $query_revenue->limit($request->page.'0')->get();
                    } else {
                        $data_query = $query_revenue->paginate($perPage);
                    }

            } elseif ($request->status == "elexa_outstanding") {
                $query_revenue = Harmony_revenues::query()->leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')
                    ->where('revenue_credit.status', 8)->where('revenue_credit.receive_payment', 0)->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])
                    ->orderBy('date', 'asc')->select('revenue.date', 'revenue_credit.batch', 'revenue_credit.ev_charge', 'revenue_credit.ev_fee', 'revenue_credit.ev_vat', 'revenue_credit.ev_revenue');

                    if ($perPage == 10) {
                        $data_query = $query_revenue->limit($request->page.'0')->get();
                    } else {
                        $data_query = $query_revenue->paginate($perPage);
                    }
            } elseif ($request->table_name == "typeTable") { 
                if ($request->status == "transfer_revenue") {

                    $query_sms = Harmony_SMS_alerts::query()->whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('transfer_status', 1);
    
                    if ($perPage == 10) {
                        $data_query = $query_sms->limit($request->page.'0')->get();
                    } else {
                        $data_query = $query_sms->paginate($perPage);
                    }

                } if ($request->status == "credit_hotel_transfer") {
                    $query_sms = Harmony_SMS_alerts::query()->whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('into_account', "708-2-26792-1")->where('status', 4);
    
                    if ($perPage == 10) {
                        $data_query = $query_sms->limit($request->page.'0')->get();
                    } else {
                        $data_query = $query_sms->paginate($perPage);
                    }

                } if ($request->status == "split_hotel_revenue") {
                    $query_sms = Harmony_SMS_alerts::query()->whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('split_status', 1);
    
                    if ($perPage == 10) {
                        $data_query = $query_sms->limit($request->page.'0')->get();
                    } else {
                        $data_query = $query_sms->paginate($perPage);
                    }

                } if ($request->status == "split_hotel_transaction") {
                    $query_sms = Harmony_SMS_alerts::query()->whereBetween('date', [$smsFromDate, $smsToDate])->where('split_status', 1);
    
                    if ($perPage == 10) {
                        $data_query = $query_sms->limit($request->page.'0')->get();
                    } else {
                        $data_query = $query_sms->paginate($perPage);
                    }

                } if ($request->status == "no_income_revenue") {
                    $query_sms = Harmony_SMS_alerts::query()->whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 0)->whereNull('date_into');
    
                    if ($perPage == 10) {
                        $data_query = $query_sms->limit($request->page.'0')->get();
                    } else {
                        $data_query = $query_sms->paginate($perPage);
                    }

                } if ($request->status == "total_transaction") {
                    $query_sms = Harmony_SMS_alerts::query()->whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->orderBy('date', 'asc');
    
                    if ($perPage == 10) {
                        $data_query = $query_sms->limit($request->page.'0')->get();
                    } else {
                        $data_query = $query_sms->paginate($perPage);
                    }

                } if ($request->status == "transfer_transaction") {
                    $query_sms = Harmony_SMS_alerts::query()->whereBetween('date', [$smsFromDate, $smsToDate])->where('transfer_status', 1);
    
                    if ($perPage == 10) {
                        $data_query = $query_sms->limit($request->page.'0')->get();
                    } else {
                        $data_query = $query_sms->paginate($perPage);
                    }

                } if ($request->status == "no_income_type") {
                    $query_sms = Harmony_SMS_alerts::query()->whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 0)->whereNull('date_into');
    
                    if ($perPage == 10) {
                        $data_query = $query_sms->limit($request->page.'0')->get();
                    } else {
                        $data_query = $query_sms->paginate($perPage);
                    }

                }

            } elseif ($request->table_name == "verifiedTable") {
                if ($request->status == "verified") {
                    $query_sms = Harmony_revenues::query()->whereBetween('date', [$FromFormatDate, $ToFormatDate])->where('status', 1);
    
                    if ($perPage == 10) {
                        $data_query = $query_sms->limit($request->page.'0')->get();
                    } else {
                        $data_query = $query_sms->paginate($perPage);
                    }

                } if ($request->status == "unverified") {
                    $query_sms = Harmony_revenues::query()->whereBetween('date', [$FromFormatDate, $ToFormatDate])->where('status', 0);
    
                    if ($perPage == 10) {
                        $data_query = $query_sms->limit($request->page.'0')->get();
                    } else {
                        $data_query = $query_sms->paginate($perPage);
                    }
                }
            } elseif ($request->table_name == "revenueCashTable") {
                $query_sms = Harmony_revenues::query()->whereBetween('date', [$FromFormatDate, $ToFormatDate]);

                    if ($request->status == "cash_front") {
                        $query_sms->where('front_cash', '>', 0);
                        $query_sms->select('date', 'front_cash as amount');
                    } elseif ($request->status == "cash_all_outlet") {
                        $query_sms->where('fb_cash', '>', 0);
                        $query_sms->select('date', 'fb_cash as amount');
                    } elseif ($request->status == "cash_guest") {
                        $query_sms->where('room_cash', '>', 0);
                        $query_sms->select('date', 'room_cash as amount');
                    } elseif ($request->status == "cash_water_park") {
                        $query_sms->where('wp_cash', '>', 0);
                        $query_sms->select('date', 'wp_cash as amount');
                    }
    
                    if ($perPage == 10) {
                        $data_query = $query_sms->limit($request->page.'0')->get();
                    } else {
                        $data_query = $query_sms->paginate($perPage);
                    }

            } elseif ($request->table_name == "feeTable") {
                
                if ($request->status == "elexa_fee") {
                    $query_sms = Harmony_revenues::query()->leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->whereIn('revenue_credit.status', [8])
                    ->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])
                    ->select('revenue.date', 'revenue_credit.ev_charge', 'revenue_credit.ev_fee', 'revenue_credit.ev_vat', 'revenue_credit.ev_revenue', 'revenue_credit.status as credit_status',
                    DB::raw('SUM(revenue_credit.ev_fee) + SUM(revenue_credit.ev_vat) as fee'))
                    ->groupBy('revenue.date');

                    if ($perPage == 10) {
                        $data_query = $query_sms->limit($request->page.'0')->get();
                    } else {
                        $data_query = $query_sms->paginate($perPage);
                    }

                } else {
                    $query_sms = Harmony_revenues::query()->leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->whereIn('revenue_credit.status', [1, 2, 6])
                    ->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])
                    ->select('revenue.date', 'revenue.total_credit', 'revenue_credit.batch', 'revenue_credit.revenue_type', 'revenue_credit.credit_amount', 'revenue_credit.status', 
                        DB::raw('SUM(revenue_credit.credit_amount) - revenue.total_credit as fee'))->groupBy('revenue.date');

                    if ($perPage == 10) {
                        $data_query = $query_sms->limit($request->page.'0')->get();
                    } else {
                        $data_query = $query_sms->paginate($perPage);
                    }
                }

            } elseif ($request->table_name == "agoda_feeTable") {
                $query_revenue = Harmony_revenues::query()->leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 5)
                    ->where('revenue_credit.revenue_type', 1)->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])
                    ->select('revenue.date', 'revenue_credit.batch', 'revenue_credit.agoda_charge', 'revenue_credit.agoda_outstanding', 'revenue_credit.status', 
                        DB::raw('revenue_credit.agoda_charge - revenue_credit.agoda_outstanding as fee'));

                    if ($perPage == 10) {
                        $data_query = $query_revenue->limit($request->page.'0')->get();
                    } else {
                        $data_query = $query_revenue->paginate($perPage);
                    }
            }
        }

        $data = [];

        $page_1 = $request->page == 1 ? 1 : ($request->page - 1).'1';
        $page_2 = $request->page.'0';

        $perPage2 = $request->perPage > 10 ? $request->perPage : 10;

        if (isset($data_query) && count($data_query) > 0) {
            if (count($exp) > 1 && $exp[0]."_".$exp[1] != "manual_charge" && $request->status != "mc_agoda_charge" && $request->status != "mc_elexa_charge" && $request->status != "agoda_outstanding" && $request->status != "elexa_outstanding" && $request->table_name != "revenueCashTable" && $request->table_name != "agoda_feeTable" && $request->table_name != "feeTable" || $request->table_name == "revenueTable") { ## Manual Charge
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
        
                        $transfer_bank = '<div class="flex-jc p-left-4">'.$img_bank.''.@$value->transfer_bank->name_en.'</div>';
        
                        // เข้าบัญชี
                        $into_account = '<div class="flex-jc p-left-4"><img class="img-bank" src="../image/bank/SCB.jpg">SCB '.$value->into_account.'</div>';
        
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
                            'stan' => $value->batch,
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

            } elseif ($request->table_name == "revenueCashTable") { 
                foreach ($data_query as $key => $value) {
                    if (($key + 1) >= (int)$page_1 && ($key + 1) <= (int)$page_2 || (int)$perPage > 10 && $key < (int)$perPage2) {
                        // if ($value->amount > 0) {
                            $data[] = [
                                'number' => $key + 1,
                                'date' => Carbon::parse($value->date)->format('d/m/Y'),
                                'amount' => number_format($value->amount, 2),
                            ];
                        // }
                    }
                }

            } elseif ($request->table_name == "agoda_feeTable") { 

                foreach ($data_query as $key => $value) {
                    if (($key + 1) >= (int)$page_1 && ($key + 1) <= (int)$page_2 || (int)$perPage > 10 && $key < (int)$perPage2) {

                        // ประเภทรายได้
                        $revenue_name = 'Agoda Revenue';

                        $data[] = [
                            'number' => $key + 1,
                            'date' => Carbon::parse($value->date)->format('d/m/Y'),
                            'stan' => $value->batch,
                            'revenue_name' => $revenue_name,
                            'amount' => number_format($value->fee, 2),
                        ];
                    }
                }

            } elseif ($request->table_name == "feeTable") { 

                foreach ($data_query as $key => $value) {
                    if (($key + 1) >= (int)$page_1 && ($key + 1) <= (int)$page_2 || (int)$perPage > 10 && $key < (int)$perPage2) {

                        $revenue_name = '';
                        // ประเภทรายได้
                        $revenue_name = 'Credit Card Hotel Fee';
                        if ($value->credit_status == 8) { $revenue_name = 'Elexa EGAT Fee'; }

                        $data[] = [
                            'number' => $key + 1,
                            'date' => Carbon::parse($value->date)->format('d/m/Y'),
                            'stan' => $value->batch,
                            'revenue_name' => $revenue_name,
                            'amount' => number_format($value->fee, 2),
                        ];
                    }
                }

            } elseif ($request->table_name == "verifiedTable") { 

                foreach ($data_query as $key => $value) {
                    if (($key + 1) >= (int)$page_1 && ($key + 1) <= (int)$page_2 || (int)$perPage > 10 && $key < (int)$perPage2) {

                        $data[] = [
                            'number' => $key + 1,
                            'date' => Carbon::parse($value->date)->format('d/m/Y'),
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
        if ($request->filter_by != "week" && $request->filter_by != "thisMonth" && $request->filter_by != "thisYear") {
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
        $exp = explode("_", $request->status);
        $search = $request->search_value;
        $status = (int)$request->status;

        if ($status > 0) {
            if ($request->table_name == "revenueTable") {
                if (!empty($request->search_value)) {
                    $data_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])
                        ->where('amount', 'LIKE', '%'.$search.'%')->whereNull('date_into')->where('status', $request->status)
                        ->orWhere('amount', 'LIKE', '%'.$search.'%')->whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', $request->status)
                        ->paginate($perPage);
                } else {
                    $data_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('status', $request->status)
                        ->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('status', $request->status)
                        ->orderBy('date', 'asc')->paginate($perPage);
                }
            }
        } else {
            if ($request->status != "mc_elexa_charge" && $request->status != "mc_agoda_charge" && count($exp) > 1 && $exp[0]."_".$exp[1] == "manual_charge") {
                if (!empty($request->search_value)) {
                    $data_query = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', $exp[2])
                        ->where('revenue_credit.revenue_type', $exp[2])->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])
                        ->where(function($query) use ($search) {
                            $query->where('revenue_credit.credit_amount', 'like', '%' . $search . '%')
                                  ->orWhere('revenue_credit.batch', 'like', '%' . $search . '%');
                        })
                        ->select('revenue.date', 'revenue.total_credit', 'revenue_credit.batch', 'revenue_credit.revenue_type', 'revenue_credit.credit_amount', 'revenue_credit.status')->paginate($perPage);
                } else {
                    $data_query = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', $exp[2])
                        ->where('revenue_credit.revenue_type', $exp[2])->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])
                        ->select('revenue.date', 'revenue.total_credit', 'revenue_credit.batch', 'revenue_credit.revenue_type', 'revenue_credit.credit_amount', 'revenue_credit.status')->paginate($perPage);
                }

            } elseif ($request->status == "mc_agoda_charge") {
                if (!empty($request->search_value)) {
                    $data_query = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 5)
                        ->where('revenue_credit.revenue_type', 1)->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])
                        ->where(function($query) use ($search) {
                            $query->where('revenue_credit.agoda_outstanding', 'like', '%' . $search . '%')
                                    ->orWhere('revenue_credit.agoda_charge', 'like', '%' . $search . '%')
                                    ->orWhere('revenue_credit.batch', 'like', '%' . $search . '%');
                        })
                        ->select('revenue.date', 'revenue_credit.batch', 'revenue_credit.agoda_charge', 'revenue_credit.agoda_outstanding', 'revenue_credit.status')->paginate($perPage);
                } else {
                    $data_query = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 5)
                        ->where('revenue_credit.revenue_type', 1)->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])
                        ->select('revenue.date', 'revenue_credit.batch', 'revenue_credit.agoda_charge', 'revenue_credit.agoda_outstanding', 'revenue_credit.status')->paginate($perPage);
                }

            } elseif ($request->status == "mc_elexa_charge") {
                if (!empty($request->search_value)) {
                    $data_query = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')
                        ->where('revenue_credit.status', 8)->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])
                        ->where(function($query) use ($search) {
                            $query->where('revenue_credit.ev_charge', 'like', '%' . $search . '%')
                                ->orWhere('revenue_credit.ev_fee', 'like', '%' . $search . '%')
                                ->orWhere('revenue_credit.ev_vat', 'like', '%' . $search . '%')
                                ->orWhere('revenue_credit.ev_revenue', 'like', '%' . $search . '%');
                        })
                        ->select('revenue.date', 'revenue_credit.ev_charge', 'revenue_credit.ev_fee', 'revenue_credit.ev_vat', 'revenue_credit.ev_revenue')
                        ->orderBy('revenue.date', 'asc')->paginate(10);
                } else {
                    $data_query = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')
                        ->where('revenue_credit.status', 8)->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])
                        ->select('revenue.date', 'revenue_credit.ev_charge', 'revenue_credit.ev_fee', 'revenue_credit.ev_vat', 'revenue_credit.ev_revenue')
                        ->orderBy('revenue.date', 'asc')->paginate(10);
                }

            }  elseif ($request->status == "agoda_outstanding") {
                if (!empty($request->search_value)) {
                    $data_query = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 5)
                        ->where('revenue_credit.receive_payment', 0)->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])
                        ->where(function($query) use ($search) {
                            $query->where('revenue_credit.agoda_outstanding', 'like', '%' . $search . '%');
                        })
                        ->select('revenue.date', 'revenue_credit.batch', 'revenue_credit.agoda_charge', 'revenue_credit.agoda_outstanding', 'revenue_credit.status')->orderBy('revenue.date', 'asc')->paginate($perPage);
                } else {
                    $data_query = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 5)
                        ->where('revenue_credit.receive_payment', 0)->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])
                        ->select('revenue.date', 'revenue_credit.batch', 'revenue_credit.agoda_charge', 'revenue_credit.agoda_outstanding', 'revenue_credit.status')->orderBy('revenue.date', 'asc')->paginate($perPage);
                }

            } elseif ($request->status == "elexa_outstanding") {
                if (!empty($request->search_value)) {
                    $data_query = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')
                        ->where('revenue_credit.status', 8)->where('revenue_credit.receive_payment', 0)->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])
                        ->where(function($query) use ($search) {
                            $query->where('revenue_credit.ev_revenue', 'like', '%' . $search . '%');
                        })
                        ->select('revenue.date', 'revenue_credit.ev_charge', 'revenue_credit.ev_fee', 'revenue_credit.ev_vat', 'revenue_credit.ev_revenue')->orderBy('revenue.date', 'asc')->paginate($perPage);
                } else {
                    $data_query = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')
                        ->where('revenue_credit.status', 8)->where('revenue_credit.receive_payment', 0)->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])
                        ->select('revenue.date', 'revenue_credit.ev_charge', 'revenue_credit.ev_fee', 'revenue_credit.ev_vat', 'revenue_credit.ev_revenue')->orderBy('revenue.date', 'asc')->paginate($perPage);
                }

            } elseif ($request->table_name == "typeTable") {
                if ($request->status == "transfer_revenue") {
                    if (!empty($request->search_value)) {
                        $data_query = Harmony_SMS_alerts::whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('transfer_status', 1)
                            ->where(function($query) use ($search) {
                                $query->where('date', 'like', '%' . $search . '%')
                                    ->orWhere('amount', 'like', '%' . $search . '%');
                            })->paginate($perPage);
                    } else {
                        $data_query = Harmony_SMS_alerts::whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('transfer_status', 1)->paginate($perPage);
                    }

                }  if ($request->status == "credit_hotel_transfer") {
                    if (!empty($request->search_value)) {
                        $data_query = Harmony_SMS_alerts::whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('into_account', "708-2-26792-1")->where('status', 4)
                            ->where(function($query) use ($search) {
                                $query->where('amount', 'like', '%' . $search . '%');
                            })->paginate($perPage);
                    } else {
                        $data_query = Harmony_SMS_alerts::whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('into_account', "708-2-26792-1")->where('status', 4)->paginate($perPage);
                    } 

                } if ($request->status == "split_hotel_revenue") {
                    if (!empty($request->search_value)) {
                        $data_query = Harmony_SMS_alerts::whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('split_status', 1)
                            ->where(function($query) use ($search) {
                                $query->where('amount', 'like', '%' . $search . '%');
                            })->paginate($perPage);
                    } else {
                        $data_query = Harmony_SMS_alerts::whereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->where('split_status', 1)->paginate($perPage);
                    }

                } if ($request->status == "split_hotel_transaction") {
                    if (!empty($request->search_value)) {
                        $data_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('split_status', 1)
                            ->where(function($query) use ($search) {
                                $query->where('amount', 'like', '%' . $search . '%');
                            })->paginate($perPage);
                    } else {
                        $data_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('split_status', 1)->paginate($perPage);
                    } 

                } if ($request->status == "no_income_revenue") {
                    if (!empty($request->search_value)) {
                        $data_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 0)->whereNull('date_into')
                            ->where(function($query) use ($search) {
                                $query->where('amount', 'like', '%' . $search . '%');
                            })->paginate($perPage);
                    } else {
                        $data_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 0)->whereNull('date_into')->paginate($perPage);
                    }

                } if ($request->status == "total_transaction") {
                    if (!empty($request->search_value)) {
                        $data_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->where('amount', 'like', '%' . $search . '%')
                            ->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])
                            ->where('amount', 'like', '%' . $search . '%')->orderBy('date', 'asc')->paginate($perPage);
                    } else {
                        $data_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->whereNull('date_into')->orWhereBetween(DB::raw('DATE(date_into)'), [$FromFormatDate, $ToFormatDate])->orderBy('date', 'asc')->paginate($perPage);
                    }

                } if ($request->status == "transfer_transaction") {
                    if (!empty($request->search_value)) {
                        $data_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('transfer_status', 1)
                            ->where(function($query) use ($search) {
                                $query->where('amount', 'like', '%' . $search . '%');
                            })->paginate($perPage);
                    } else {
                        $data_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('transfer_status', 1)->paginate($perPage);
                    }

                } if ($request->status == "no_income_type") {
                    if (!empty($request->search_value)) {
                        $data_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 0)->whereNull('date_into')
                            ->where(function($query) use ($search) {
                                $query->where('date', 'like', '%' . $search . '%')
                                    ->orWhere('amount', 'like', '%' . $search . '%');
                            })->paginate($perPage);
                    } else {
                        $data_query = Harmony_SMS_alerts::whereBetween('date', [$smsFromDate, $smsToDate])->where('status', 0)->whereNull('date_into')->paginate($perPage);
                    }

                }
            } elseif ($request->table_name == "verifiedTable") {
                if ($request->status == "verified") {
                    if (!empty($request->search_value)) {
                        $data_query = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->where('status', 1)
                            ->where('date', 'like', '%' . $request->search_value . '%')->paginate($perPage);
                    } else {
                        $data_query = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->where('status', 1)->paginate($perPage);
                    }

                } if ($request->status == "unverified") {
                    if (!empty($request->search_value)) {
                        $data_query = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->where('status', 0)
                            ->where('date', 'like', '%' . $request->search_value . '%')->paginate($perPage);
                    } else {
                        $data_query = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->where('status', 0)->paginate($perPage);
                    }
                }
            } elseif ($request->table_name == "revenueCashTable") {
                if (!empty($request->search_value)) {
                    if ($request->status == "cash_front") {
                        $data_query = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->where('front_cash', '>', 0)
                                ->where(function($query) use ($search) {
                                    $query->where('front_cash', 'like', '%' . $search . '%');
                                })->select('date', 'front_cash as amount')->paginate($perPage);

                    } elseif ($request->status == "cash_all_outlet") {
                        $data_query = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->where('fb_cash', '>', 0)
                                ->where(function($query) use ($search) {
                                    $query->where('fb_cash', 'like', '%' . $search . '%');
                                })
                                ->select('date', 'fb_cash as amount')->paginate($perPage);

                    } elseif ($request->status == "cash_guest") {
                        $data_query = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->where('room_cash', '>', 0)
                                ->where(function($query) use ($search) {
                                    $query->where('room_cash', 'like', '%' . $search . '%');
                                })
                                ->select('date', 'room_cash as amount')->paginate($perPage);

                    } elseif ($request->status == "cash_water_park") {
                        $data_query = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->where('wp_cash', '>', 0)
                                ->where(function($query) use ($search) {
                                    $query->where('wp_cash', 'like', '%' . $search . '%');
                                })
                                ->select('date', 'wp_cash as amount')->paginate($perPage);
                    }
                } else {
                    if ($request->status == "cash_front") {
                        $data_query = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->where('front_cash', '>', 0)->select('date', 'front_cash as amount')->paginate($perPage);

                    } elseif ($request->status == "cash_all_outlet") {
                        $data_query = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->where('fb_cash', '>', 0)->select('date', 'fb_cash as amount')->paginate($perPage);

                    } elseif ($request->status == "cash_guest") {
                        $data_query = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->where('room_cash', '>', 0)->select('date', 'room_cash as amount')->paginate($perPage);

                    } elseif ($request->status == "cash_water_park") {
                        $data_query = Harmony_revenues::whereBetween('date', [$FromFormatDate, $ToFormatDate])->where('wp_cash', '>', 0)->select('date', 'wp_cash as amount')->paginate($perPage);
                    }
                }

            } elseif ($request->table_name == "feeTable") {

                if ($request->status == "elexa_fee") {
                    $data_query = Harmony_revenues::query()->leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')
                        ->whereIn('revenue_credit.status', [8])->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])
                        ->select('revenue.date', 'revenue_credit.ev_charge', 'revenue_credit.ev_fee', 'revenue_credit.ev_vat', 'revenue_credit.ev_revenue', 'revenue_credit.status as credit_status', 
                            DB::raw('SUM(revenue_credit.ev_fee) + SUM(revenue_credit.ev_vat) as fee'))
                        ->groupBy('revenue.date')
                        ->havingRaw("CAST(fee AS CHAR) like ?", ['%' . $search . '%'])
                        ->paginate($perPage);
                } else {
                    $data_query = Harmony_revenues::query()->leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')
                        ->whereIn('revenue_credit.status', [1, 2, 6])->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])
                        ->select('revenue.date', 'revenue.total_credit', 'revenue_credit.batch', 'revenue_credit.revenue_type', 'revenue_credit.credit_amount', 'revenue_credit.status', 
                            DB::raw('SUM(revenue_credit.credit_amount) - revenue.total_credit as fee'))->groupBy('revenue.date')
                        ->havingRaw("CAST(fee AS CHAR) like ?", ['%' . $search . '%'])
                        ->paginate($perPage);
                }

            } elseif ($request->table_name == "agoda_feeTable") {
                $data_query = Harmony_revenues::query()->leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 5)
                    ->whereBetween('revenue.date', [$FromFormatDate, $ToFormatDate])
                    ->select('revenue.date', 'revenue_credit.batch', 'revenue_credit.agoda_charge', 'revenue_credit.agoda_outstanding', 'revenue_credit.status', 
                        DB::raw('revenue_credit.agoda_charge - revenue_credit.agoda_outstanding as fee'))
                        ->havingRaw("CAST(fee AS CHAR) like ?", ['%' . $search . '%'])->paginate($perPage);
            }
        }

        if (isset($data_query) && count($data_query) > 0) {
            if ($status > 0 || $request->status == "total_transaction" || $request->status == "transfer_revenue" || $request->status == "no_income_revenue" || $request->status == "no_income_type" || $request->status == "transfer_transaction" || $request->status == "split_hotel_revenue" || $request->status == "split_hotel_transaction" || $request->status == "credit_hotel_transfer") { ## Manual Charge
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

                    $transfer_bank = '<div class="flex-jc p-left-4">'.$img_bank.''.@$value->transfer_bank->name_en.'</div>';

                    // เข้าบัญชี
                    $into_account = '<div class="flex-jc p-left-4"><img class="img-bank" src="../image/bank/SCB.jpg">SCB '.$value->into_account.'</div>';

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
                        'stan' => $value->batch,
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

            } elseif ($request->table_name == "revenueCashTable") { 

                    foreach ($data_query as $key => $value) {
                        if ($value->amount > 0) {
                            $data[] = [
                                'number' => $key + 1,
                                'date' => Carbon::parse($value->date)->format('d/m/Y'),
                                'amount' => number_format($value->amount, 2),
                            ];
                        }
                    }

            } elseif ($request->table_name == "agoda_feeTable") { 

                foreach ($data_query as $key => $value) {
                    // ประเภทรายได้
                    $revenue_name = 'Agoda Revenue';

                    $data[] = [
                        'number' => $key + 1,
                        'date' => Carbon::parse($value->date)->format('d/m/Y'),
                        'stan' => $value->batch,
                        'revenue_name' => $revenue_name,
                        'amount' => number_format($value->fee, 2),
                    ];
                }

            } elseif ($request->table_name == "feeTable") { 

                foreach ($data_query as $key => $value) {
                        $revenue_name = '';
                        // ประเภทรายได้
                        $revenue_name = 'Credit Card Hotel Fee';
                        if ($value->credit_status == 8) { $revenue_name = 'Elexa EGAT Fee'; }

                        $data[] = [
                            'number' => $key + 1,
                            'date' => Carbon::parse($value->date)->format('d/m/Y'),
                            'stan' => $value->batch,
                            'revenue_name' => $revenue_name,
                            'amount' => number_format($value->fee, 2),
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

    public function getManualCharge($FromDate, $ToDate, $FromMonth, $ToMonth, $FromYear, $ToYear, $type)
    {
        $sum_revenue = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', $type)
            ->whereBetween('revenue.date', [$FromDate, $ToDate])
            ->select(DB::raw("(SUM(revenue_credit.credit_amount) - revenue.total_credit) as total_credit, SUM(revenue_credit.credit_amount) as credit_amount"), 'revenue.total_credit as total')
            ->first();

        $sum_revenue_month = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', $type)
            ->whereBetween('revenue.date', [$FromMonth, $ToMonth])
            ->select(DB::raw("(SUM(revenue_credit.credit_amount) - revenue.total_credit) as total_credit, SUM(revenue_credit.credit_amount) as credit_amount"), 'revenue.total_credit as total')
            ->first();

        $sum_revenue_year = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', $type)
            ->whereBetween('revenue.date', [$FromYear, $ToYear])
            ->select(DB::raw("(SUM(revenue_credit.credit_amount) - revenue.total_credit) as total_credit, SUM(revenue_credit.credit_amount) as credit_amount"), 'revenue.total_credit as total')
            ->first();

        $data[] = [
            'revenue_credit_date' => isset($sum_revenue) ? $sum_revenue->credit_amount : 0,
            'revenue_credit_month' => isset($sum_revenue_month) ? $sum_revenue_month->credit_amount : 0,
            'revenue_credit_year' => isset($sum_revenue_year) ? $sum_revenue_year->credit_amount : 0,
            'fee_date' => isset($sum_revenue) && $sum_revenue->total > 0 ? $sum_revenue->total_credit : 0,
            'fee_month' => isset($sum_revenue_month) && $sum_revenue_month->total > 0 ? $sum_revenue_month->total_credit : 0,
            'fee_year' => isset($sum_revenue_year) && $sum_revenue_year->total > 0 ? $sum_revenue_year->total_credit : 0,
            'total' => (isset($sum_revenue) ? $sum_revenue->credit_amount : 0) - (isset($sum_revenue) ? $sum_revenue->total_credit : 0),
            'total_month' => (isset($sum_revenue_month) ? $sum_revenue_month->credit_amount : 0) - (isset($sum_revenue_month) ? $sum_revenue_month->total_credit : 0),
            'total_year' => (isset($sum_revenue_year) ? $sum_revenue_year->credit_amount : 0) - (isset($sum_revenue_year) ? $sum_revenue_year->total_credit : 0)
        ];

        return $data;
    }

    public function getManualAgodaCharge($FromDate, $ToDate, $FromMonth, $ToMonth, $FromYear, $ToYear, $type)
    {
        $sum_revenue = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 5)
            ->whereBetween('revenue.date', [$FromDate, $ToDate])
            ->select(DB::raw("(SUM(revenue_credit.agoda_charge) - SUM(revenue_credit.agoda_outstanding)) as total_credit_agoda, SUM(revenue_credit.agoda_charge) as agoda_charge, SUM(revenue_credit.agoda_outstanding) as agoda_outstanding"))
            ->first();
        
        $sum_revenue_month = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 5)
            ->whereBetween('revenue.date', [$FromMonth, $ToMonth])
            ->select(DB::raw("(SUM(revenue_credit.agoda_charge) - SUM(revenue_credit.agoda_outstanding)) as total_credit_agoda, SUM(revenue_credit.agoda_charge) as agoda_charge, SUM(revenue_credit.agoda_outstanding) as agoda_outstanding"))
            ->first();

        $sum_revenue_year = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 5)
            ->whereBetween('revenue.date', [$FromYear, $ToYear])
            ->select(DB::raw("(SUM(revenue_credit.agoda_charge) - SUM(revenue_credit.agoda_outstanding)) as total_credit_agoda, SUM(revenue_credit.agoda_charge) as agoda_charge, SUM(revenue_credit.agoda_outstanding) as agoda_outstanding"))
            ->first();

        $data[] = [
            'revenue_credit_date' => isset($sum_revenue) ? $sum_revenue->agoda_charge : 0,
            'revenue_credit_month' => isset($sum_revenue_month) ? $sum_revenue_month->agoda_charge : 0,
            'revenue_credit_year' => isset($sum_revenue_year) ? $sum_revenue_year->agoda_charge : 0,
            'fee_date' => isset($sum_revenue) ? $sum_revenue->total_credit_agoda : 0,
            'fee_month' => isset($sum_revenue_month) ? $sum_revenue_month->total_credit_agoda : 0,
            'fee_year' => isset($sum_revenue_year) ? $sum_revenue_year->total_credit_agoda : 0,
            'total' => isset($sum_revenue) ? $sum_revenue->agoda_outstanding : 0,
            'total_month' => isset($sum_revenue_month) ? $sum_revenue_month->agoda_outstanding : 0,
            'total_year' => isset($sum_revenue_year) ? $sum_revenue_year->agoda_outstanding : 0,
        ];

        return $data;
    }

    public function getManualEvCharge($FromDate, $ToDate, $FromMonth, $ToMonth, $FromYear, $ToYear, $type)
    {

        $sum_revenue = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')
            ->where('revenue_credit.status', 8)->whereBetween('revenue.date', [$FromDate, $ToDate])
            ->select(DB::raw("SUM(revenue_credit.ev_charge) as ev_charge, (SUM(revenue_credit.ev_fee) + SUM(ev_vat)) as ev_fee, SUM(revenue_credit.ev_revenue) as ev_revenue"))
            ->first();

        $sum_revenue_month = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')
            ->where('revenue_credit.status', 8)->whereBetween('revenue.date', [$FromMonth, $ToMonth])
            ->select(DB::raw("SUM(revenue_credit.ev_charge) as ev_charge, (SUM(revenue_credit.ev_fee) + SUM(ev_vat)) as ev_fee, SUM(revenue_credit.ev_revenue) as ev_revenue"))
            ->first();

        $sum_revenue_year = Harmony_revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')
            ->where('revenue_credit.status', 8)->whereBetween('revenue.date', [$FromYear, $ToYear])
            ->select(DB::raw("SUM(revenue_credit.ev_charge) as ev_charge, (SUM(revenue_credit.ev_fee) + SUM(ev_vat)) as ev_fee, SUM(revenue_credit.ev_revenue) as ev_revenue"))
            ->first();

        $data[] = [ 
            'revenue_credit_date' => isset($sum_revenue) ? $sum_revenue->ev_charge : 0,
            'revenue_credit_month' => isset($sum_revenue_month) ? $sum_revenue_month->ev_charge : 0,
            'revenue_credit_year' => isset($sum_revenue_year) ? $sum_revenue_year->ev_charge : 0,
            'fee_date' => isset($sum_revenue) ? $sum_revenue->ev_fee : 0,
            'fee_month' => isset($sum_revenue_month) ? $sum_revenue_month->ev_fee : 0,
            'fee_year' => isset($sum_revenue_year) ? $sum_revenue_year->ev_fee : 0,
            'total' => isset($sum_revenue) ? $sum_revenue->ev_revenue : 0,
            'total_month' => isset($sum_revenue_month) ? $sum_revenue_month->ev_revenue : 0,
            'total_year' => isset($sum_revenue_year) ? $sum_revenue_year->ev_revenue : 0,
        ];

        return $data;
    }
}
