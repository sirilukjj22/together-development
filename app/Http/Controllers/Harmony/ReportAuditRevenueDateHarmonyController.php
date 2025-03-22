<?php

namespace App\Http\Controllers\Harmony;

use App\Http\Controllers\Controller;

use App\Models\Harmony\Harmony_revenues;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportAuditRevenueDateHarmonyController extends Controller
{
    
    public function index()
    {
        $filter_by = "month";
        $data_query = Harmony_revenues::whereBetween('date', [date('Y-m-01'), date('Y-m-31')])->select('id', 'date', 'status')->get();
        $data_all = Harmony_revenues::whereBetween('date', [date('Y-m-01'), date('Y-m-31')])->select('id', 'date', 'status')->get();

        $total_all = count($data_all);
        $verified = 0;
        $unverified = 0;

        foreach ($data_all as $key => $value) {
            if ($value->status == 1) {
                $verified += 1;
            } else {
                $unverified += 1;
            }
        }

        return view('report.audit_revenue_date_harmony.index', compact('data_query', 'total_all', 'verified', 'unverified', 'filter_by'));
    }

    public function search(Request $request)
    {
        $filter_by = $request->filter_by;
        $startDate = $request->startDate ?? 0;
        $endDate = $request->endDate ?? 0;
        $status = $request->status;

        $query = Harmony_revenues::query();
        $query_all = Harmony_revenues::query();

        if ($filter_by == "date") {
            $exp = explode('-', $request->startDate);
            $startDate = Carbon::createFromFormat('d/m/Y', trim($exp[0]))->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', trim($exp[1]))->format('Y-m-d');

            $query->whereBetween('date', [$startDate, $endDate]);
            $query_all->whereBetween('date', [$startDate, $endDate]);
            $search_date = date('d/m/Y', strtotime($startDate))." - ".date('d/m/Y', strtotime($endDate));
        }

        if ($filter_by == "month") {
            $startDate = $request->month ?? 0;
            $query->whereBetween('date', [date($startDate.'-01'), date($startDate.'-31')]);
            $query_all->whereBetween('date', [date($startDate.'-01'), date($startDate.'-31')]);
            $search_date = date('F Y', strtotime(date($startDate.'-01')));
        }

        if ($filter_by == "year") {
            $startDate = $request->startDate ?? 0;
            $query->whereYear('date', $startDate);
            $query_all->whereYear('date', $startDate);
            $search_date = $startDate;
        }
        
        $data_query = $query->select('id', 'date', 'status')->get();

        $data_all = $query_all->select('id', 'date', 'status')->get();

        $total_all = count($data_all);
        $verified = 0;
        $unverified = 0;

        foreach ($data_all as $key => $value) {
            if ($value->status == 1) {
                $verified += 1;
            } else {
                $unverified += 1;
            }
        }

        return view('report.audit_revenue_date_harmony.index', compact('data_query', 'total_all', 'verified', 'unverified', 'filter_by', 'search_date', 'startDate', 'status'));
    }
}
