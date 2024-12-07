<?php

namespace App\Http\Controllers;

use App\Exports\DebtorAgodaOutstandingExport;
use App\Models\Revenue_credit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Maatwebsite\Excel\Facades\Excel;

class ReportAgodaOutstandingController extends Controller
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

        $data_query = Revenue_credit::leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
            ->where('revenue_credit.status', 5) ->where('revenue_credit.receive_payment', 0)
            ->whereBetween('revenue.date', [date('Y-m-d', strtotime('last day of previous month')), date('Y-m-t')])
            ->select('revenue_credit.id', 'revenue_credit.batch', 'revenue_credit.agoda_check_in', 'revenue_credit.agoda_check_out',
                'revenue_credit.revenue_type', 'revenue_credit.agoda_charge', 'revenue_credit.receive_payment',
                'revenue_credit.agoda_outstanding', 'revenue_credit.sms_revenue', 'revenue.date')
            ->orderBy('revenue.date', 'asc')->get();

        $total_agoda_amount = Revenue_credit::leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
            ->where('revenue_credit.status', 5) ->where('revenue_credit.receive_payment', 0)
            ->select('revenue_credit.agoda_outstanding')
            ->sum('revenue_credit.agoda_outstanding');

        return view('report.agoda_outstanding.index', compact('data_query', 'total_agoda_amount', 'filter_by', 'search_date'));
    }

    public function search(Request $request)
    {
        $filter_by = $request->filter_by;
        $statusAll = $request->statusAll ?? 0;
        $statusHide = $request->statusHide ?? 0;
        $statusNotComplete = $request->statusNotComplete ?? 0;
        $endDate = '';

        $query = Revenue_credit::query()->leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
            ->where('revenue_credit.status', 5) ->where('revenue_credit.receive_payment', 0);

        if ($filter_by == "date") {
            $exp = explode('-', $request->startDate);
            $adate = Carbon::createFromFormat('d/m/Y', trim($exp[0]))->format('Y-m-d');
            $adate2 = Carbon::createFromFormat('d/m/Y', trim($exp[1]))->format('Y-m-d');

            $startDate = Carbon::createFromFormat('d/m/Y', trim($exp[0]))->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', trim($exp[1]))->format('Y-m-d');

            $query->whereBetween('revenue.date', [$startDate, $endDate]);
            $search_date = date('d/m/Y', strtotime($startDate))." - ".date('d/m/Y', strtotime($endDate));
        }

        if ($filter_by == "month") {
            $startDate = $request->month ?? 0;
            $query->whereBetween('revenue.date', [date('Y-m-d', strtotime('-1 day', strtotime("$startDate-01"))), date('Y-m-t', strtotime("$startDate-01"))]);
            $search_date = date('F Y', strtotime(date($startDate.'-01')));
        }

        if ($filter_by == "year") {
            $startDate = $request->startDate ?? 0;
            $query->whereYear('revenue.date', $startDate);
            $search_date = $startDate;
        }

        $total_agoda_amount = $query->sum('revenue_credit.agoda_outstanding');

        $data_query = $query->select('revenue_credit.id', 'revenue_credit.batch', 'revenue_credit.agoda_check_in', 'revenue_credit.agoda_check_out',
                'revenue_credit.revenue_type', 'revenue_credit.agoda_charge', 'revenue_credit.receive_payment',
                'revenue_credit.agoda_outstanding', 'revenue_credit.sms_revenue', 'revenue.date')
            ->orderBy('revenue.date', 'asc')->get();

        if ($request->method_name == "search") {
            return view('report.agoda_outstanding.index', compact('data_query', 'total_agoda_amount', 'filter_by', 'search_date', 'startDate'));

        } elseif ($request->method_name == "pdf") {

            $sum_page = count($data_query) / 12;
            $page_item = 1;
            if ($sum_page > 1 && $sum_page < 2.1) {
                $page_item += 1;
            } elseif ($sum_page >= 2.1) {
                $page_item = 1 + $sum_page > 2.1 ? ceil($sum_page) : 1;
            }

            $pdf = FacadePdf::loadView('pdf.report_agoda.agoda_outstanding.1A', compact('data_query', 'total_agoda_amount', 'filter_by', 'search_date', 'startDate', 'page_item'));
            return $pdf->stream();

        } elseif ($request->method_name == "excel") {
            return Excel::download(new DebtorAgodaOutstandingExport($filter_by, $data_query, $total_agoda_amount, $search_date), 'agoda_outstanding.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        }
    }
}
