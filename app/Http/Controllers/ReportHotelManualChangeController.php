<?php

namespace App\Http\Controllers;

use App\Exports\HotelManualChargeExport;
use App\Models\Revenues;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Maatwebsite\Excel\Facades\Excel;

class ReportHotelManualChangeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $filter_by = "month";
        $search_date = date('F Y');

        $data_query = Revenues::leftJoin('revenue_credit', 'revenue.id', '=', 'revenue_credit.revenue_id')
            ->whereBetween('revenue.date', [date('Y-m-01'), date('Y-m-d')]) // ใช้ 'Y-m-t' เพื่อวันที่สิ้นสุดของเดือน
            ->select(
                'revenue.date',
                'revenue.total_credit',
                DB::raw("SUM(revenue_credit.credit_amount) as manual_charge"),
                DB::raw("SUM(revenue_credit.credit_amount) - revenue.total_credit as fee")
            )
            ->groupBy('revenue.date', 'revenue.total_credit')
            ->orderBy('revenue.date', 'asc')
            ->get();

        return view('report.hotel_manual_charge.index', compact('data_query', 'filter_by', 'search_date'));
    }

    public function search(Request $request)
    {
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

        if ($status == "hide_revenue") {
            $query->where('revenue.total_credit', '>', 0);
            $query->where('revenue_credit.credit_amount', '>', 0);
        }

        $query->select(
            'revenue.date',
            'revenue.total_credit',
            DB::raw("SUM(revenue_credit.credit_amount) as manual_charge"),
            DB::raw("SUM(revenue_credit.credit_amount) - revenue.total_credit as fee"));

        $data_query = $query->groupBy('revenue.date', 'revenue.total_credit')->orderBy('revenue.date', 'asc')->get();

        if ($request->method_name == "search") {
            return view('report.hotel_manual_charge.index', compact('data_query', 'filter_by', 'search_date', 'startDate', 'status'));

        } elseif ($request->method_name == "pdf") {

            $num = 0;
            if (isset($status) && $status == 'not_complete') 
            {
                foreach ($data_query as $key => $item)
                {
                    if ($item->manual_charge == 0 || $item->total_credit == 0)
                    {
                        $num += 1;
                    }
                }
            }

            $sum_page = $num / 25;
            $page_item = 1;
            if ($sum_page > 1.2 && $sum_page < 2.5) {
                $page_item += 1;
            } elseif ($sum_page >= 2.5) {
                $page_item = 1 + $sum_page > 2.5 ? ceil($sum_page) : 1;
            }

            $pdf = FacadePdf::loadView('pdf.hotel_manual_charge.1A', compact('data_query', 'filter_by', 'search_date', 'startDate', 'status', 'page_item'));
            return $pdf->stream();

        } elseif ($request->method_name == "excel") {
            // return Excel::download(new HotelManualChargeExport($filter_by, $data_query, $search_date), 'hotel_manual_charge.xlsx');
            return Excel::download(new HotelManualChargeExport($filter_by, $data_query, $search_date, $status), 'hotel_manual_charge.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        }
    }
}
