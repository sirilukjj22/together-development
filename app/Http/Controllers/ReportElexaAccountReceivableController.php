<?php

namespace App\Http\Controllers;

use App\Exports\DebtorAgodaAccountReceivableExport;
use App\Models\SMS_alerts;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Maatwebsite\Excel\Facades\Excel;

class ReportElexaAccountReceivableController extends Controller
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

        $data_query = SMS_alerts::whereBetween('date', [date('Y-m-d 21:00:00', strtotime('last day of previous month')), date('Y-m-t 20:59:59')])
            ->where('status', 8)->where('status_receive_elexa', 1)->whereNull('date_into')
            ->orderBy('date', 'asc')->get();

        $total_sms_amount = SMS_alerts::whereBetween('date', [date('Y-m-d 21:00:00', strtotime('last day of previous month')), date('Y-m-t 20:59:59')])
            ->where('status', 8)->where('status_receive_elexa', 1)->whereNull('date_into')
            ->orderBy('date', 'asc')->sum('amount');

        return view('report.elexa_account_receivable.index', compact('data_query', 'total_sms_amount', 'filter_by', 'search_date'));
    }

    public function search(Request $request)
    {
        $filter_by = $request->filter_by;
        $statusAll = $request->statusAll ?? 0;
        $statusHide = $request->statusHide ?? 0;
        $statusNotComplete = $request->statusNotComplete ?? 0;
        $endDate = '';

        $query = SMS_alerts::query()->where('status_receive_elexa', 1);

        if ($filter_by == "date") {
            $exp = explode('-', $request->startDate);
            $adate = Carbon::createFromFormat('d/m/Y', trim($exp[0]))->format('Y-m-d 21:00:00');
            $adate2 = Carbon::createFromFormat('d/m/Y', trim($exp[1]))->format('Y-m-d 20:59:59');

            $smsFromDate = date('Y-m-d 21:00:00', strtotime('-1 day', strtotime($adate)));
            $smsEndDate = $adate2;

            $startDate = Carbon::createFromFormat('d/m/Y', trim($exp[0]))->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', trim($exp[1]))->format('Y-m-d');

            $query->whereBetween('date', [$smsFromDate, $smsEndDate])->where('status', 8)->whereNull('date_into');
            $query->orWhereBetween(DB::raw('DATE(date_into)'), [$startDate, $endDate])->where('status', 8)->where('status_receive_elexa', 1);
            $search_date = date('d/m/Y', strtotime($startDate))." - ".date('d/m/Y', strtotime($endDate));
        }

        if ($filter_by == "month") {
            $startDate = $request->month ?? 0;
            $query->whereBetween('date', [date('Y-m-d 21:00:00', strtotime('-1 day', strtotime("$startDate-01"))), date('Y-m-t 20:59:59', strtotime("$startDate-01"))])->where('status', 8)->whereNull('date_into');
            $query->orWhereBetween(DB::raw('DATE(date_into)'), [date($startDate.'-01'), date('Y-m-t', strtotime("$startDate-01"))])->where('status', 8)->where('status_receive_elexa', 1);
            $search_date = date('F Y', strtotime(date($startDate.'-01')));
        }

        if ($filter_by == "year") {
            $startDate = $request->startDate ?? 0;
            $query->whereYear('date', $startDate)->where('status', 8)->whereNull('date_into');
            $query->orWhereYear(DB::raw('DATE(date_into)'), $startDate)->where('status', 8)->where('status_receive_elexa', 1);
            $search_date = $startDate;
        }

        $total_sms_amount = $query->sum('amount');

        $data_query = $query->orderBy('date', 'asc')->get();

        if ($request->method_name == "search") {
            return view('report.elexa_account_receivable.index', compact('data_query', 'total_sms_amount', 'filter_by', 'search_date', 'startDate'));

        } elseif ($request->method_name == "pdf") {

            $sum_page = count($data_query) / 12;
            $page_item = 1;
            if ($sum_page > 1 && $sum_page < 2.1) {
                $page_item += 1;
            } elseif ($sum_page >= 2.1) {
                $page_item = 1 + $sum_page > 2.1 ? ceil($sum_page) : 1;
            }

            $pdf = FacadePdf::loadView('pdf.report_elexa.elexa_account_receivable.1A', compact('data_query', 'total_sms_amount', 'filter_by', 'search_date', 'startDate', 'page_item'));
            return $pdf->stream();

        } elseif ($request->method_name == "excel") {
            return Excel::download(new DebtorAgodaAccountReceivableExport($filter_by, $data_query, $total_sms_amount, $search_date), 'elexa_account_receivable.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        }
    }
}
