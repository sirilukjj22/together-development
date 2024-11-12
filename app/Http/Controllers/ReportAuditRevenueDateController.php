<?php

namespace App\Http\Controllers;

use App\Models\Revenues;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportAuditRevenueDateController extends Controller
{
    
    public function index()
    {
        $filter_by = "month";
        $data_query = Revenues::whereBetween('date', [date('Y-m-01'), date('Y-m-31')])->select('id', 'date', 'status')->paginate(10);
        $data_all = Revenues::whereBetween('date', [date('Y-m-01'), date('Y-m-31')])->select('id', 'date', 'status')->get();

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

        return view('report.audit_revenue_date.index', compact('data_query', 'total_all', 'verified', 'unverified', 'filter_by'));
    }

    public function search(Request $request)
    {
        $filter_by = $request->filter_by;
        $startDate = $request->startDate ?? 0;
        $endDate = $request->endDate ?? 0;
        $status = $request->status;

        $query = Revenues::query();
        $query_all = Revenues::query();

        if ($filter_by == "date") {
            $query->where('date', $startDate);
            $query_all->where('date', $startDate);
        }

        if ($filter_by == "month") {
            $query->whereBetween('date', [date($startDate.'-01'), date($endDate.'-31')]);
            $query_all->whereBetween('date', [date($startDate.'-01'), date($endDate.'-31')]);
        }

        if ($filter_by == "year") {
            $query->whereYear('date', $startDate);
            $query_all->whereYear('date', $startDate);
        }

        if ($filter_by == "custom") {
            $query->whereBetween('date', [$startDate, $endDate]);
            $query_all->whereBetween('date', [$startDate, $endDate]);
        }

        if ($status != "all") {
            $query->where('status', $status);
            $query_all->where('status', $status);
        }
        
        $data_query = $query->select('id', 'date', 'status')->paginate(10);

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

        return view('report.audit_revenue_date.index', compact('data_query', 'total_all', 'verified', 'unverified', 'filter_by', 'startDate', 'endDate', 'status'));
    }

    public function search_table(Request $request)
    {
        $filter_by = $request->filter_by;
        $startDate = $request->startDate ?? 0;
        $endDate = $request->endDate ?? 0;
        $status = $request->status;

        $perPage = (int)$request->perPage;
        
        $query = Revenues::query();

        if ($filter_by == "date") {
            $query->where('date', $startDate);
        }

        if ($filter_by == "month") {
            $query->whereBetween('date', [date($startDate.'-01'), date($endDate.'-31')]);
        }

        if ($filter_by == "year") {
            $query->whereYear('date', $startDate);
        }

        if ($filter_by == "custom") {
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        if ($status != "all") {
            $query->where('status', $status);
        }

        $query->where('date', 'like', '%' . $request->search_value . '%');
        $data_query = $query->select('id', 'date', 'status')->paginate($perPage);

        $data = [];

        $page_1 = $request->page == 1 ? 1 : ($request->page - 1).'1';
        $page_2 = $request->page.'0';

        $perPage2 = $request->perPage > 10 ? $request->perPage : 10;

        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                if ($value->status == 1) {
                    $status_name = '<span class="badge bg-success">Verified</span>';
                } else {
                    $status_name = '<span class="badge bg-danger">Unverified</span>';
                }

                $data[] = [
                    'number' => $key + 1,
                    'date' => Carbon::parse($value->date)->format('d/m/Y'),
                    'status' => $status_name
                ];
            }
        }

        return response()->json([
                'data' => $data,
            ]);
    }

    public function paginate_table(Request $request)
    {
        $filter_by = $request->filter_by;
        $startDate = $request->startDate ?? 0;
        $endDate = $request->endDate ?? 0;
        $status = $request->status;

        $perPage = (int)$request->perPage;
        
        $query = Revenues::query();

        if ($filter_by == "date") {
            $query->where('date', $startDate);
        }

        if ($filter_by == "month") {
            $query->whereBetween('date', [date($startDate.'-01'), date($endDate.'-31')]);
        }

        if ($filter_by == "year") {
            $query->whereYear('date', $startDate);
        }

        if ($filter_by == "custom") {
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        if ($status != "all") {
            $query->where('status', $status);
        }
        
        $query->select('id', 'date', 'status');

        if ($perPage == 10) {
            $data_query = $query->limit($request->page.'0')->get();
        } else {
            $data_query = $query->paginate($perPage);
        }

        $data = [];

        $page_1 = $request->page == 1 ? 1 : ($request->page - 1).'1';
        $page_2 = $request->page.'0';

        $perPage2 = $request->perPage > 10 ? $request->perPage : 10;

        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                if (($key + 1) >= (int)$page_1 && ($key + 1) <= (int)$page_2 || (int)$perPage > 10 && $key < (int)$perPage2) {

                    if ($value->status == 1) {
                        $status_name = '<span class="badge bg-success">Verified</span>';
                    } else {
                        $status_name = '<span class="badge bg-danger">Unverified</span>';
                    }

                    $data[] = [
                        'number' => $key + 1,
                        'date' => Carbon::parse($value->date)->format('d/m/Y'),
                        'status' => $status_name
                    ];
                }
            }
        }

        return response()->json([
                'data' => $data,
            ]);
    }
}
