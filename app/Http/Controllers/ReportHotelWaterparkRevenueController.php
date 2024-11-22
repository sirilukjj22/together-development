<?php

namespace App\Http\Controllers;

use App\Models\Revenues;
use App\Models\TB_outstanding_balance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Maatwebsite\Excel\Facades\Excel;

class ReportHotelWaterparkRevenueController extends Controller
{
    public function index()
    {
        $filter_by = "date";
        $status = '';
        $search_date = date('d/m/Y');
        return view('report.hotel_water_park_revenue.index', compact('filter_by', 'search_date', 'status'));
    }

    public function search(Request $request)
    {
        if ($request->filter_by == "date" && $request->status == "summary" || $request->filter_by == "month" || $request->filter_by == "year" && $request->status == "summary") {
            return $this->search_month_year($request);
        } elseif ($request->filter_by == "year" && $request->status == "detail") {
            return $this->search_year_detail($request);
        } else {
            $filter_by = $request->filter_by;
            $status = $request->status;
            $endDate = '';

            $query = Revenues::query()->leftJoin('revenue_credit', 'revenue.id', '=', 'revenue_credit.revenue_id');

            if ($filter_by == "date") {
                $exp = explode('-', $request->startDate);
                $startDate = Carbon::createFromFormat('d/m/Y', trim($exp[0]))->format('Y-m-d');
                $endDate = Carbon::createFromFormat('d/m/Y', trim($exp[1]))->format('Y-m-d');

                $query->whereBetween('revenue.date', [$startDate, $endDate]);
                $search_date = date('d/m/Y', strtotime($startDate))." - ".date('d/m/Y', strtotime($endDate));
            }

            if ($filter_by == "month") {
                $startDate = $request->month ?? 0;
                $query->whereBetween('revenue.date', [date($startDate.'-01'), date($startDate.'-31')]);
                $search_date = date('F Y', strtotime(date($startDate.'-01')));
            }

            if ($filter_by == "year") {
                $startDate = $request->startDate ?? 0;
                $query->whereYear('revenue.date', $startDate);
                $search_date = $startDate;
            }

            $query->select(
                'revenue.date',
                'revenue.front_cash',
                'revenue.front_transfer',
                'revenue.room_cash',
                'revenue.room_transfer',
                'revenue.fb_cash',
                'revenue.fb_transfer',
                'revenue.wp_cash',
                'revenue.wp_transfer',
                'revenue.total_credit',
                'revenue.total_credit_agoda',
                'revenue.other_revenue',
                'revenue.total_elexa',
                'revenue.wp_credit',
                'revenue_credit.ev_fee',
                // SUM Cash, Bank
                DB::raw("revenue.front_cash + revenue.room_cash + revenue.fb_cash as cash"),
                DB::raw("revenue.front_transfer + revenue.room_transfer + revenue.fb_transfer + revenue.other_revenue as bank_transfer"),

                DB::raw("SUM(CASE WHEN revenue_credit.status = 1 THEN revenue_credit.credit_amount ELSE 0 END) as guest_charge"),
                DB::raw("SUM(CASE WHEN revenue_credit.status = 2 THEN revenue_credit.credit_amount ELSE 0 END) as outlet_charge"),
                DB::raw("SUM(CASE WHEN revenue_credit.status = 6 THEN revenue_credit.credit_amount ELSE 0 END) as front_charge"),

                // Agoda
                DB::raw("SUM(CASE WHEN revenue_credit.status = 5 THEN revenue_credit.agoda_charge ELSE 0 END) as agoda_charge"),
                DB::raw("SUM(CASE WHEN revenue_credit.status = 5 THEN revenue_credit.agoda_outstanding ELSE 0 END) as agoda_revenue"),
                DB::raw("SUM(revenue_credit.agoda_charge) - SUM(revenue_credit.agoda_outstanding) as agoda_fee"),
                DB::raw("revenue_credit.agoda_outstanding - revenue.total_credit_agoda as agoda_outstanding"),

                // Elexa
                DB::raw("SUM(CASE WHEN revenue_credit.status = 8 THEN revenue_credit.ev_charge ELSE 0 END) as ev_charge"),
                DB::raw("SUM(CASE WHEN revenue_credit.status = 8 THEN revenue_credit.ev_revenue ELSE 0 END) as ev_revenue"),

                // Water Park Credit 
                DB::raw("SUM(CASE WHEN revenue_credit.status = 7 THEN revenue_credit.credit_amount ELSE 0 END) as wp_charge"),
                DB::raw("SUM(CASE WHEN revenue_credit.status = 7 THEN revenue_credit.credit_amount ELSE 0 END) - revenue.wp_credit as wp_fee"),

                DB::raw("SUM(revenue_credit.credit_amount) as manual_charge"),
                DB::raw("SUM(revenue_credit.credit_amount) - revenue.total_credit as fee"));

            $data_query = $query->groupBy('revenue.date', 'revenue.total_credit')->orderBy('revenue.date', 'asc')->get();

            if ($request->method_name == "search") {
                return view('report.hotel_water_park_revenue.index', compact('data_query', 'filter_by', 'search_date', 'startDate', 'status'));

            } elseif ($request->method_name == "pdf") {

                $sum_page = count($data_query) / 25;
                $page_item = 1;
                if ($sum_page > 1.2 && $sum_page < 2.5) {
                    $page_item += 1;
                } elseif ($sum_page >= 2.5) {
                    $page_item = 1 + $sum_page > 2.5 ? ceil($sum_page) : 1;
                }

                $pdf = FacadePdf::loadView('pdf.hotel_water_park_revenue.1A', compact('data_query', 'filter_by', 'search_date', 'startDate', 'status', 'page_item'));
                return $pdf->stream();

            } elseif ($request->method_name == "excel") {
                // return Excel::download(new HotelManualChargeExport($filter_by, $data_query, $search_date), 'hotel_manual_charge.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            }
        }
    }

    public function search_month_year(Request $request)
    {
        if ($request->filter_by == "date") {
            $exp_date = array_map('trim', explode('-', $request->startDate));
            $FormatDate = Carbon::createFromFormat('d/m/Y', $exp_date[0]);
            $FormatDate2 = Carbon::createFromFormat('d/m/Y', $exp_date[1]);

            // Date
            $FromDate = $FormatDate->format('Y-m-d');
            $ToDate = $FormatDate2->format('Y-m-d');

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

        } elseif ($request->filter_by == "month") {
            $FormatDate = Carbon::createFromFormat('Y-m', $request->month);
            $FormatDate2 = Carbon::createFromFormat('Y-m', $request->month);

            // Date
            $FromDate = $FormatDate->startOfMonth()->format('Y-m-d');
            $ToDate = $FormatDate->endOfMonth()->format('Y-m-d');

            // Month
            $FromMonth = $FormatDate->startOfMonth()->format('Y-m-d');
            $ToMonth = $FormatDate->endOfMonth()->format('Y-m-d');

            // Year
            $FromYear = $FormatDate->format('Y-01-01');
            $ToYear = $FormatDate->endOfMonth()->format('Y-m-d');

        } elseif ($request->filter_by == "year") {
            $FormatDate = Carbon::createFromFormat('Y', $request->startDate);
            $FormatDate2 = Carbon::createFromFormat('Y', $request->startDate);

            // Date
            $FromDate = $FormatDate->format('Y-01-01');
            $ToDate = $FormatDate->format('Y-12-31');

            // Month
            $FromMonth = $FormatDate->format('Y-01-01');
            $ToMonth = $FormatDate->format('Y-12-31');

            // Year
            $FromYear = $FormatDate->format('Y-01-01');
            $ToYear = $FormatDate->format('Y-12-31');
        }

        // Outstanding Balance From Last Year
        $lastYear = date('Y', strtotime('-1 year'));
        $agoda_outstanding_last_year = TB_outstanding_balance::where('year', $lastYear)->sum('agoda_balance');
        $elexa_outstanding_last_year = TB_outstanding_balance::where('year', $lastYear)->sum('elexa_balance');

        ## ข้อมูลในตาราง

        ### Credit Card Hotel ###
        // Date
        $credit_revenue = Revenues::whereBetween('date', [$FromDate, $ToDate])->select(DB::raw("SUM(total_credit) as total_credit"))->first();

        // Month
        $credit_revenue_month = Revenues::whereBetween('date', [$FromMonth, $ToMonth])->select(DB::raw("SUM(total_credit) as total_credit"))->first();

        // Year
        $credit_revenue_year = Revenues::whereBetween('date', [$FromYear, $ToYear])->select(DB::raw("SUM(total_credit) as total_credit"))->first();

        ### Front Desk ###
        // Date
        $total_front_revenue = Revenues::whereBetween('date', [$FromDate, $ToDate])->select(DB::raw("SUM(front_cash) as front_cash, SUM(front_transfer) as front_transfer, SUM(front_credit) as front_credit"))->first();

        // Month
        $total_front_month = Revenues::whereBetween('date', [$FromMonth, $ToMonth])->select(DB::raw("SUM(front_cash) as front_cash, SUM(front_transfer) as front_transfer, SUM(front_credit) as front_credit"))->first();

        // Year
        $total_front_year = Revenues::whereBetween('date', [$FromYear, $ToYear])->select(DB::raw("SUM(front_cash) as front_cash, SUM(front_transfer) as front_transfer, SUM(front_credit) as front_credit"))->first();

        // Charge
        $front_charge = $this->getManualCharge($FromDate, $ToDate, $FromMonth, $ToMonth, $FromYear, $ToYear, 6);

        ### Guest Deposit ###
        // Date
        $total_guest_deposit = Revenues::whereBetween('date', [$FromDate, $ToDate])->select(DB::raw("SUM(room_cash) as room_cash, SUM(room_transfer) as room_transfer, SUM(room_credit) as room_credit"))->first();

        // Month
        $total_guest_deposit_month = Revenues::whereBetween('date', [$FromMonth, $ToMonth])->select(DB::raw("SUM(room_cash) as room_cash, SUM(room_transfer) as room_transfer, SUM(room_credit) as room_credit"))->first();

        // Year
        $total_guest_deposit_year = Revenues::whereBetween('date', [$FromYear, $ToYear])->select(DB::raw("SUM(room_cash) as room_cash, SUM(room_transfer) as room_transfer, SUM(room_credit) as room_credit"))->first();

        // Charge
        $guest_deposit_charge = $this->getManualCharge($FromDate, $ToDate, $FromMonth, $ToMonth, $FromYear, $ToYear, 1);
 
        ### All Outlet ###
        // Date
        $total_fb_revenue = Revenues::whereBetween('date', [$FromDate, $ToDate])->select(DB::raw("SUM(fb_cash) as fb_cash, SUM(fb_transfer) as fb_transfer, SUM(fb_credit) as fb_credit"))->first();

        // Month
        $total_fb_month = Revenues::whereBetween('date', [$FromMonth, $ToMonth])->select(DB::raw("SUM(fb_cash) as fb_cash, SUM(fb_transfer) as fb_transfer, SUM(fb_credit) as fb_credit"))->first();

        // Year
        $total_fb_year = Revenues::whereBetween('date', [$FromYear, $ToYear])->select(DB::raw("SUM(fb_cash) as fb_cash, SUM(fb_transfer) as fb_transfer, SUM(fb_credit) as fb_credit"))->first();

        // Charge
        $fb_charge = $this->getManualCharge($FromDate, $ToDate, $FromMonth, $ToMonth, $FromYear, $ToYear, 2);

        ## Other Revenue ###
        // Date
        $total_other_revenue = Revenues::whereBetween('date', [$FromDate, $ToDate])->select('other_revenue')->sum('other_revenue');

        // Month
        $total_other_month = Revenues::whereBetween('date', [$FromMonth, $ToDate])->select('other_revenue')->sum('other_revenue');

        // Year
        $total_other_year = Revenues::whereBetween('date', [$FromYear, $ToYear])->select('other_revenue')->sum('other_revenue');

        ### Agoda ###
        // Date
        $total_agoda_revenue = Revenues::whereBetween('date', [$FromDate, $ToDate])->sum('total_credit_agoda');

        // Month
        $total_agoda_month = Revenues::whereBetween('date', [$FromMonth, $ToMonth])->sum('total_credit_agoda');

        // Year
        $total_agoda_year = Revenues::whereBetween('date', [$FromYear, $ToYear])->sum('total_credit_agoda');

        // Charge
        $agoda_charge = $this->getManualAgodaCharge($FromDate, $ToDate, $FromMonth, $ToMonth, $FromYear, $ToYear, 5);

        ### Water Park ###
        // Date
        $total_wp_revenue = Revenues::whereBetween('date', [$FromDate, $ToDate])->select(DB::raw("SUM(wp_cash) as wp_cash, SUM(wp_transfer) as wp_transfer, SUM(wp_credit) as wp_credit"))->first();

        // Month
        $total_wp_month = Revenues::whereBetween('date', [$FromMonth, $ToMonth])->select(DB::raw("SUM(wp_cash) as wp_cash, SUM(wp_transfer) as wp_transfer, SUM(wp_credit) as wp_credit"))->first();

        // Year
        $total_wp_year = Revenues::whereBetween('date', [$FromYear, $ToYear])->select(DB::raw("SUM(wp_cash) as wp_cash, SUM(wp_transfer) as wp_transfer, SUM(wp_credit) as wp_credit"))->first();

        // Charge
        $wp_charge = $this->getManualCharge($FromDate, $ToDate, $FromMonth, $ToMonth, $FromYear, $ToYear, 3);

        ### Elexa EGAT ###
        // Date
        $total_ev_revenue = Revenues::whereBetween('date', [$FromDate, $ToDate])->select('total_elexa')->sum('total_elexa');

        // Month
        $total_ev_month = Revenues::whereBetween('date', [$FromMonth, $ToMonth])->select('total_elexa')->sum('total_elexa');

        // Year
        $total_ev_year = Revenues::whereBetween('date', [$FromYear, $ToYear])->select('total_elexa')->sum('total_elexa');

        // Charge
        $ev_charge = $this->getManualEvCharge($FromDate, $ToDate, $FromMonth, $ToMonth, $FromYear, $ToYear, 8);

        ## Filter ##
        $filter_by = $request->filter_by;
        $search_date = $request->startDate;
        $status = $request->status;

        return view('report.hotel_water_park_revenue.index', compact(
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

            'total_wp_revenue',
            'total_wp_month',
            'total_wp_year',
            'wp_charge',

            'total_ev_revenue',
            'total_ev_month',
            'total_ev_year',
            'ev_charge',

            'total_other_revenue',
            'total_other_month',
            'total_other_year',

            'agoda_outstanding_last_year',
            'elexa_outstanding_last_year',

            'filter_by', 'search_date', 'status'
        ));
    }

    public function search_year_detail(Request $request)
    {
        $FormatDate = Carbon::createFromFormat('Y', $request->startDate);
        $FormatDate2 = Carbon::createFromFormat('Y', $request->startDate);

        // Date
        $FromDate = $FormatDate->format('Y-01-01');
        $ToDate = $FormatDate->format('Y-12-31');

        // Month
        $FromMonth = $FormatDate->format('Y-01-01');
        $ToMonth = $FormatDate->format('Y-12-31');

        // Year
        $FromYear = $FormatDate->format('Y-01-01');
        $ToYear = $FormatDate->format('Y-12-31');

        ### Outstanding Balance From Last Year ###
        // $lastYear = date('Y', strtotime('-1 year'));
        // $agoda_outstanding_last_year = TB_outstanding_balance::where('year', $lastYear)->sum('agoda_balance');
        // $elexa_outstanding_last_year = TB_outstanding_balance::where('year', $lastYear)->sum('elexa_balance');

        ### Front Desk ###
        $total_front = Revenues::whereBetween('date', [$FromYear, $ToYear])
            ->select(
                DB::raw("SUM(front_cash) as front_cash, SUM(front_transfer) as front_transfer, SUM(front_credit) as front_credit"),
                DB::raw("MONTH(date) as month"), DB::raw("YEAR(date) as year"))
            ->groupBy(DB::raw('YEAR(date)'), DB::raw('MONTH(date)'))
            ->get();
        
        ### Guest Deposit ###
        $total_guest_deposit = Revenues::whereBetween('date', [$FromYear, $ToYear])
            ->select(
                DB::raw("SUM(room_cash) as room_cash, SUM(room_transfer) as room_transfer, SUM(room_credit) as room_credit"),
                DB::raw("MONTH(date) as month"), DB::raw("YEAR(date) as year"))
            ->groupBy(DB::raw('YEAR(date)'), DB::raw('MONTH(date)'))
            ->get();

        ### All Outlet ###
        $total_fb = Revenues::whereBetween('date', [$FromYear, $ToYear])
            ->select(
                DB::raw("SUM(fb_cash) as fb_cash, SUM(fb_transfer) as fb_transfer, SUM(fb_credit) as fb_credit"),
                DB::raw("MONTH(date) as month"), DB::raw("YEAR(date) as year"))
            ->groupBy(DB::raw('YEAR(date)'), DB::raw('MONTH(date)'))
            ->get();

        // Charge
        $front_charge = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 6)
            ->whereBetween('revenue.date', [$FromYear, $ToYear])
            ->select(
                DB::raw("(SUM(revenue_credit.credit_amount) - revenue.total_credit) as total_credit, SUM(revenue_credit.credit_amount) as credit_amount"),
                DB::raw("MONTH(revenue.date) as month"), DB::raw("YEAR(revenue.date) as year"), 'revenue.total_credit as total')
            ->groupBy(DB::raw('YEAR(revenue.date)'), DB::raw('MONTH(revenue.date)'))
            ->get();
        
        $guest_deposit_charge = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 1)
            ->whereBetween('revenue.date', [$FromYear, $ToYear])
            ->select(
                DB::raw("(SUM(revenue_credit.credit_amount) - revenue.total_credit) as total_credit, SUM(revenue_credit.credit_amount) as credit_amount"),
                DB::raw("MONTH(revenue.date) as month"), DB::raw("YEAR(revenue.date) as year"), 'revenue.total_credit as total')
            ->groupBy(DB::raw('YEAR(revenue.date)'), DB::raw('MONTH(revenue.date)'))
            ->get();

        $all_outlet_charge = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 2)
            ->whereBetween('revenue.date', [$FromYear, $ToYear])
            ->select(
                DB::raw("(SUM(revenue_credit.credit_amount) - revenue.total_credit) as total_credit, SUM(revenue_credit.credit_amount) as credit_amount"),
                DB::raw("MONTH(revenue.date) as month"), DB::raw("YEAR(revenue.date) as year"), 'revenue.total_credit as total')
            ->groupBy(DB::raw('YEAR(revenue.date)'), DB::raw('MONTH(revenue.date)'))
            ->get();

        ### Sum Charge ###
        $sum_charge = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->whereIn('revenue_credit.status', [1, 2, 6])
            ->whereBetween('revenue.date', [$FromYear, $ToYear])
            ->select(
                DB::raw("SUM(revenue_credit.credit_amount) as credit_amount"),
                DB::raw("MONTH(revenue.date) as month"), DB::raw("YEAR(revenue.date) as year"))
            ->groupBy(DB::raw('YEAR(revenue.date)'), DB::raw('MONTH(revenue.date)'))
            ->get();

        ### Credit Card ###
        $total_credit = Revenues::whereBetween('date', [$FromYear, $ToYear])
            ->select(
                DB::raw("SUM(total_credit) as total_credit"),
                DB::raw("MONTH(revenue.date) as month"), DB::raw("YEAR(revenue.date) as year"))
            ->groupBy(DB::raw('YEAR(revenue.date)'), DB::raw('MONTH(revenue.date)'))
            ->get();

        ### Agoda ###
        $total_agoda = Revenues::whereBetween('date', [$FromYear, $ToYear])
            ->select(DB::raw("SUM(total_credit_agoda) as sum_credit_agoda"), 
                    DB::raw("MONTH(date) as month"), DB::raw("YEAR(date) as year"))
            ->groupBy(DB::raw('YEAR(date)'), DB::raw('MONTH(date)'))
            ->get();

        $total_agoda_charge = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 5)
            ->whereBetween('revenue.date', [$FromYear, $ToYear])
            ->select(DB::raw("(SUM(revenue_credit.agoda_charge) - SUM(revenue_credit.agoda_outstanding)) as total_credit_agoda, SUM(revenue_credit.agoda_charge) as agoda_charge, SUM(revenue_credit.agoda_outstanding) as agoda_outstanding"),
                    DB::raw("MONTH(date) as month"), DB::raw("YEAR(date) as year"))
            ->groupBy(DB::raw('YEAR(revenue.date)'), DB::raw('MONTH(revenue.date)'))
            ->get();

        ### Other Revenue ###
        $total_other = Revenues::whereBetween('date', [$FromYear, $ToYear])
            ->select(DB::raw("SUM(other_revenue) as total_other_revenue"),
                    DB::raw("MONTH(date) as month"), DB::raw("YEAR(date) as year"))
            ->groupBy(DB::raw('YEAR(revenue.date)'), DB::raw('MONTH(revenue.date)'))
            ->get();

        ### Water Park ###
        $total_wp = Revenues::whereBetween('date', [$FromYear, $ToYear])
            ->select(DB::raw("SUM(wp_cash) as wp_cash, SUM(wp_transfer) as wp_transfer, SUM(wp_credit) as wp_credit"),
                    DB::raw("MONTH(date) as month"), DB::raw("YEAR(date) as year"))
            ->groupBy(DB::raw('YEAR(revenue.date)'), DB::raw('MONTH(revenue.date)'))
            ->get();

        $wp_charge = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 7)
            ->whereBetween('revenue.date', [$FromYear, $ToYear])
            ->select(
                DB::raw("(SUM(revenue_credit.credit_amount) - revenue.total_credit) as total_credit, SUM(revenue_credit.credit_amount) as credit_amount"),
                DB::raw("MONTH(revenue.date) as month"), DB::raw("YEAR(revenue.date) as year"), 'revenue.total_credit as total')
            ->groupBy(DB::raw('YEAR(revenue.date)'), DB::raw('MONTH(revenue.date)'))
            ->get();

        ### Elexa ###
        $total_ev = Revenues::whereBetween('date', [$FromYear, $ToYear])
            ->select(DB::raw("SUM(total_elexa) as sum_credit_ev"), 
                    DB::raw("MONTH(date) as month"), DB::raw("YEAR(date) as year"))
            ->groupBy(DB::raw('YEAR(date)'), DB::raw('MONTH(date)'))
            ->get();

        $total_ev_charge = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 8)
            ->whereBetween('revenue.date', [$FromYear, $ToYear])
            ->select(DB::raw("SUM(revenue_credit.ev_charge) as ev_charge, (SUM(revenue_credit.ev_fee) + SUM(ev_vat)) as ev_fee, SUM(revenue_credit.ev_revenue) as ev_revenue"),
                    DB::raw("MONTH(date) as month"), DB::raw("YEAR(date) as year"))
            ->groupBy(DB::raw('YEAR(revenue.date)'), DB::raw('MONTH(revenue.date)'))
            ->get();

        ## Filter ##
        $filter_by = $request->filter_by;
        $search_date = $request->startDate;
        $status = $request->status;

        return view('report.hotel_water_park_revenue.index', compact(
            // 'agoda_outstanding_last_year',
            // 'elexa_outstanding_last_year',

            'total_credit',
            'total_front',
            'total_guest_deposit',
            'total_fb',

            'front_charge',
            'guest_deposit_charge',
            'all_outlet_charge',
            'sum_charge',

            'total_agoda',
            'total_agoda_charge',

            'total_other',

            'total_wp',
            'wp_charge',

            'total_ev',
            'total_ev_charge',

            'filter_by', 'search_date', 'status'
        ));
    }

    public function getManualCharge($FromDate, $ToDate, $FromMonth, $ToMonth, $FromYear, $ToYear, $type)
    {
        $sum_revenue = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', $type)
            ->whereBetween('revenue.date', [$FromDate, $ToDate])
            ->select(DB::raw("(SUM(revenue_credit.credit_amount) - revenue.total_credit) as total_credit, SUM(revenue_credit.credit_amount) as credit_amount"), 'revenue.total_credit as total')
            ->first();

        $sum_revenue_month = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', $type)
            ->whereBetween('revenue.date', [$FromMonth, $ToMonth])
            ->select(DB::raw("(SUM(revenue_credit.credit_amount) - revenue.total_credit) as total_credit, SUM(revenue_credit.credit_amount) as credit_amount"), 'revenue.total_credit as total')
            ->first();

        $sum_revenue_year = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', $type)
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
        $sum_revenue = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 5)
            ->whereBetween('revenue.date', [$FromDate, $ToDate])
            ->select(DB::raw("(SUM(revenue_credit.agoda_charge) - SUM(revenue_credit.agoda_outstanding)) as total_credit_agoda, SUM(revenue_credit.agoda_charge) as agoda_charge, SUM(revenue_credit.agoda_outstanding) as agoda_outstanding"))
            ->first();
        
        $sum_revenue_month = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 5)
            ->whereBetween('revenue.date', [$FromMonth, $ToMonth])
            ->select(DB::raw("(SUM(revenue_credit.agoda_charge) - SUM(revenue_credit.agoda_outstanding)) as total_credit_agoda, SUM(revenue_credit.agoda_charge) as agoda_charge, SUM(revenue_credit.agoda_outstanding) as agoda_outstanding"))
            ->first();

        $sum_revenue_year = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 5)
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

        $sum_revenue = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')
            ->where('revenue_credit.status', 8)->whereBetween('revenue.date', [$FromDate, $ToDate])
            ->select(DB::raw("SUM(revenue_credit.ev_charge) as ev_charge, (SUM(revenue_credit.ev_fee) + SUM(ev_vat)) as ev_fee, SUM(revenue_credit.ev_revenue) as ev_revenue"))
            ->first();

        $sum_revenue_month = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')
            ->where('revenue_credit.status', 8)->whereBetween('revenue.date', [$FromMonth, $ToMonth])
            ->select(DB::raw("SUM(revenue_credit.ev_charge) as ev_charge, (SUM(revenue_credit.ev_fee) + SUM(ev_vat)) as ev_fee, SUM(revenue_credit.ev_revenue) as ev_revenue"))
            ->first();

        $sum_revenue_year = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')
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
