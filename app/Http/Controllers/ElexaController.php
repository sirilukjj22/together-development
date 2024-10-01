<?php

namespace App\Http\Controllers;

use App\Models\Revenue_credit;
use App\Models\SMS_alerts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ElexaController extends Controller
{
    public function index()
    {
        $elexa_revenue = SMS_alerts::where('status', 8)->select('sms_alert.*', DB::raw("Month(date) as month, SUM(amount) as total_sum"))->groupBy('month')->get();

        $elexa_outstanding = Revenue_credit::leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
            // ->whereMonth('revenue.date', $exp[1])->whereYear('revenue.date', $exp[0])
            ->where('revenue_credit.status', 8)
            ->select('revenue_credit.id', 'revenue_credit.batch',
            'revenue_credit.revenue_type', 'revenue_credit.ev_charge', 'revenue_credit.receive_payment', 'revenue_credit.sms_revenue', 'revenue.date')
            ->get();

            $total_outstanding_all = 0;
            $elexa_debit_outstanding = 0;
            foreach ($elexa_outstanding as $key => $value) {
                if ($value->receive_payment == 1) {
                    $elexa_debit_outstanding += $value->ev_charge;
                }
                $total_outstanding_all += $value->ev_charge;
            }

        $title = "Elexa";

        return view('elexa.index', compact('elexa_outstanding', 'elexa_revenue', 'total_outstanding_all', 'elexa_debit_outstanding', 'title'));
    }

    public function index_list_days($month, $year)
    {
        $elexa_outstanding = Revenue_credit::leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
            ->where('revenue_credit.status', 8)
            ->select('revenue_credit.id', 'revenue_credit.batch', 'revenue_credit.revenue_type', 'revenue_credit.ev_charge',
                'revenue_credit.receive_payment', 'revenue_credit.sms_revenue', 'revenue.date')
            ->get();

            $total_outstanding_all = 0;
            $elexa_debit_outstanding = 0;
            foreach ($elexa_outstanding as $key => $value) {
                if ($value->receive_payment == 1) {
                    $elexa_debit_outstanding += $value->ev_charge;
                }
                $total_outstanding_all += $value->ev_charge;
            }

        $title = "Elexa";

        $elexa_revenue = SMS_alerts::where('status', 8)->whereMonth('date', $month)->whereYear('date', $year)->get();

        return view('elexa.elexa_outstanding', compact('elexa_outstanding', 'elexa_revenue', 'total_outstanding_all', 'elexa_debit_outstanding', 'title', 'month', 'year'));
    }

    public function index_receive($id, $month, $year)
    {
        $elexa_outstanding = Revenue_credit::leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
            ->where('revenue_credit.status', 8)
            ->select('revenue_credit.id', 'revenue_credit.batch', 'revenue_credit.revenue_type', 'revenue_credit.ev_charge',
                'revenue_credit.receive_payment', 'revenue_credit.sms_revenue', 'revenue.date')
            ->orderBy('revenue.date', 'asc')->get();

            $total_outstanding_all = 0;
            $elexa_debit_outstanding = 0;
            foreach ($elexa_outstanding as $key => $value) {
                if ($value->receive_payment == 1) {
                    $elexa_debit_outstanding += $value->ev_charge;
                }
                $total_outstanding_all += $value->ev_charge;
            }

            $elexa_revenue = SMS_alerts::where('id', $id)->where('status', 8)->select('id', 'amount')->first();

            $title = "Elexa";

        return view('elexa.edit_elexa_outstanding', compact('elexa_outstanding', 'elexa_revenue', 'total_outstanding_all', 'elexa_debit_outstanding', 'title', 'month', 'year'));
    }

    public function select_elexa_outstanding($id) {

        $elexa_outstanding = Revenue_credit::where('id', $id)->where('revenue_credit.status', 8)
            ->select('revenue_credit.id', 'revenue_credit.batch', 'revenue_credit.revenue_type', 'revenue_credit.ev_charge',
                'revenue_credit.receive_payment', 'revenue_credit.sms_revenue')->first();

            return response()->json([
                'data' => $elexa_outstanding,
                'status' => 200,
            ]);
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
