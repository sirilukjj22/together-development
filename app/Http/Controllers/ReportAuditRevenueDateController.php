<?php

namespace App\Http\Controllers;

use App\Models\Revenues;
use Illuminate\Http\Request;

class ReportAuditRevenueDateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $filter_by = "month";
        $data_query = Revenues::whereBetween('date', [date('Y-m-01'), date('Y-m-31')])->select('id', 'date', 'status')->paginate(10);

        return view('report.audit_revenue_date.index', compact('data_query', 'filter_by'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $filter_by = $request->filter_by;
        $startDate = $request->startDate ?? 0;
        $endDate = $request->endDate ?? 0;
        $status = $request->status;

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
        $data_query = $query->paginate(10);

        return view('report.audit_revenue_date.index', compact('data_query', 'filter_by', 'startDate', 'endDate', 'status'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
