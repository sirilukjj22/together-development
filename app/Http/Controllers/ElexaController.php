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

    public function receive_payment(Request $request) {

        if (isset($request->receive_id)) {

            $data_update = Revenue_credit::where('sms_revenue', $request->revenue_id)->update([
                'date_receive' => null,
                'receive_payment' => 0,
                'sms_revenue' => null
            ]);

            foreach ($request->receive_id as $key => $value) {
                $check_data = Revenue_credit::where('id', $value)->first();

                if ($check_data->receive_payment == 0) {
                    Revenue_credit::where('id', $value)->update([
                        'date_receive' => date('Y-m-d'),
                        'receive_payment' => 1,
                        'sms_revenue' => $request->revenue_id
                    ]);
                }

                SMS_alerts::where('id', $request->revenue_id)->update([
                    'status_receive_elexa' => 1
                ]);
            }

            return response()->json([
                'status' => 200,
                'message' => "บันทึกข้อมูลสำเร็จ"
            ]);

        } else {
            return response()->json([
                'status' => 404,
                'message' => "ไม่พบข้อมูล"
            ]);
        }
    }

    public function index_detail_receive($id, $month, $year)
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

            $title = "Debit Elexa Revenue";

            return view('elexa.detail_elexa_outstanding', compact('elexa_outstanding', 'elexa_revenue', 'total_outstanding_all', 'elexa_debit_outstanding', 'title', 'month', 'year'));
    }

    public function status_elexa_receive($status) {

        $elexa_received = Revenue_credit::where('revenue_credit.status', 8)->where('receive_payment', $status)
            ->select('revenue_credit.id', 'revenue_credit.batch', 'revenue_credit.revenue_type', 'revenue_credit.ev_charge',
                'revenue_credit.receive_payment', 'revenue_credit.sms_revenue')->orderBy('revenue_id', 'asc')->get();

            return response()->json([
                'data' => $elexa_received,
                'status' => 200,
            ]);
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
