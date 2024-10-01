<?php

namespace App\Http\Controllers;

use App\Models\Revenue_credit;
use App\Models\Revenues;
use App\Models\SMS_alerts;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Support\Facades\DB;

class AgodaRevenuesController extends Controller
{
    
    public function index()
    {
        $agoda_revenue = SMS_alerts::where('status', 5)->select('sms_alert.*', DB::raw("Month(date) as month, SUM(amount) as total_sum"))->groupBy('month')->get();

        $agoda_outstanding = Revenue_credit::leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
            // ->whereMonth('revenue.date', $exp[1])->whereYear('revenue.date', $exp[0])
            ->where('revenue_credit.status', 5)
            ->select('revenue_credit.id', 'revenue_credit.batch', 'revenue_credit.agoda_check_in', 'revenue_credit.agoda_check_out',
            'revenue_credit.revenue_type', 'revenue_credit.agoda_charge', 'revenue_credit.receive_payment',
            'revenue_credit.agoda_outstanding', 'revenue_credit.sms_revenue', 'revenue.date')
            ->get();

            $total_outstanding_all = 0;
            $agoda_debit_outstanding = 0;
            foreach ($agoda_outstanding as $key => $value) {
                if ($value->receive_payment == 1) {
                    $agoda_debit_outstanding += $value->agoda_outstanding;
                }
                $total_outstanding_all += $value->agoda_outstanding;
            }

        $title = "Agoda";

        return view('agoda.index', compact('agoda_outstanding', 'agoda_revenue', 'total_outstanding_all', 'agoda_debit_outstanding', 'title'));
    }

    public function index_list_days($month, $year)
    {
        $agoda_outstanding = Revenue_credit::leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
            // ->whereMonth('revenue.date', $exp[1])->whereYear('revenue.date', $exp[0])
            ->where('revenue_credit.status', 5)
            ->select('revenue_credit.id', 'revenue_credit.batch', 'revenue_credit.agoda_check_in', 'revenue_credit.agoda_check_out',
            'revenue_credit.revenue_type', 'revenue_credit.agoda_charge', 'revenue_credit.receive_payment',
            'revenue_credit.agoda_outstanding', 'revenue_credit.sms_revenue', 'revenue.date')
            ->get();

            $total_outstanding_all = 0;
            $agoda_debit_outstanding = 0;
            foreach ($agoda_outstanding as $key => $value) {
                if ($value->receive_payment == 1) {
                    $agoda_debit_outstanding += $value->agoda_outstanding;
                }
                $total_outstanding_all += $value->agoda_outstanding;
            }

            $agoda_revenue = SMS_alerts::where('status', 5)->whereMonth('date', $month)->whereYear('date', $year)->get();

            $title = "Agoda";

            return view('agoda.agoda_outstanding', compact('agoda_outstanding', 'agoda_revenue', 'total_outstanding_all', 'agoda_debit_outstanding', 'title', 'month', 'year'));
    }

    // public function index_update_agoda($month, $year)
    // {
    //     $agoda_revenue = SMS_alerts::where('status', 5)->whereMonth('date', $month)->whereYear('date', $year)->get();

    //     return view('agoda.list_agoda', compact('agoda_revenue', 'month', 'year'));
    // }

    public function index_receive($id, $month, $year)
    {
        $agoda_outstanding = Revenue_credit::leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
            // ->whereMonth('revenue.date', $exp[1])->whereYear('revenue.date', $exp[0])
            ->where('revenue_credit.status', 5)
            ->select('revenue_credit.id', 'revenue_credit.batch', 'revenue_credit.agoda_check_in', 'revenue_credit.agoda_check_out',
            'revenue_credit.revenue_type', 'revenue_credit.agoda_charge', 'revenue_credit.receive_payment',
            'revenue_credit.agoda_outstanding', 'revenue_credit.sms_revenue')
            ->orderBy('revenue_credit.agoda_check_in', 'asc')->get();

            $total_outstanding_all = 0;
            $agoda_debit_outstanding = 0;
            foreach ($agoda_outstanding as $key => $value) {
                if ($value->receive_payment == 1) {
                    $agoda_debit_outstanding += $value->agoda_outstanding;
                }
                $total_outstanding_all += $value->agoda_outstanding;
            }

            $agoda_revenue = SMS_alerts::where('id', $id)->where('status', 5)->select('id', 'amount')->first();

            $title = "Debit Agoda Revenue";

            return view('agoda.edit_agoda_outstanding', compact('agoda_outstanding', 'agoda_revenue', 'total_outstanding_all', 'agoda_debit_outstanding', 'title', 'month', 'year'));
    }

    public function index_detail_receive($id, $month, $year)
    {
        $agoda_outstanding = Revenue_credit::leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
            // ->whereMonth('revenue.date', $exp[1])->whereYear('revenue.date', $exp[0])
            ->where('revenue_credit.status', 5)
            ->select('revenue_credit.id', 'revenue_credit.batch', 'revenue_credit.agoda_check_in', 'revenue_credit.agoda_check_out',
            'revenue_credit.revenue_type', 'revenue_credit.agoda_charge', 'revenue_credit.receive_payment',
            'revenue_credit.agoda_outstanding', 'revenue_credit.sms_revenue')
            ->orderBy('revenue_credit.agoda_check_in', 'asc')->get();

            $total_outstanding_all = 0;
            $agoda_debit_outstanding = 0;
            foreach ($agoda_outstanding as $key => $value) {
                if ($value->receive_payment == 1) {
                    $agoda_debit_outstanding += $value->agoda_outstanding;
                }
                $total_outstanding_all += $value->agoda_outstanding;
            }

            $agoda_revenue = SMS_alerts::where('id', $id)->where('status', 5)->select('id', 'amount')->first();

            $title = "Debit Agoda Revenue";

            return view('agoda.detail_agoda_outstanding', compact('agoda_outstanding', 'agoda_revenue', 'total_outstanding_all', 'agoda_debit_outstanding', 'title', 'month', 'year'));
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
                    'status_receive_agoda' => 1
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

    public function select_agoda_received($id) {

        $agoda_received = Revenue_credit::where('sms_revenue', $id)->where('revenue_credit.status', 5)->where('receive_payment', 1)
        ->select('revenue_credit.id', 'revenue_credit.batch', 'revenue_credit.agoda_check_in', 'revenue_credit.agoda_check_out',
        'revenue_credit.revenue_type', 'revenue_credit.agoda_charge', 'revenue_credit.receive_payment',
        'revenue_credit.agoda_outstanding', 'revenue_credit.sms_revenue')->orderBy('revenue_id', 'asc')->get();

            return response()->json([
                'data' => $agoda_received,
                'status' => 200,
            ]);
    }

    public function select_agoda_outstanding($id) {

        $agoda_outstanding = Revenue_credit::where('id', $id)->where('revenue_credit.status', 5)
        ->select('revenue_credit.id', 'revenue_credit.batch', 'revenue_credit.agoda_check_in', 'revenue_credit.agoda_check_out',
            'revenue_credit.revenue_type', 'revenue_credit.agoda_charge', 'revenue_credit.receive_payment',
            'revenue_credit.agoda_outstanding', 'revenue_credit.sms_revenue')->first();

            return response()->json([
                'data' => $agoda_outstanding,
                'status' => 200,
            ]);
    }

    public function status_agoda_receive($status) {

        $agoda_received = Revenue_credit::where('revenue_credit.status', 5)->where('receive_payment', $status)
        ->select('revenue_credit.id', 'revenue_credit.batch', 'revenue_credit.agoda_check_in', 'revenue_credit.agoda_check_out',
        'revenue_credit.revenue_type', 'revenue_credit.agoda_charge', 'revenue_credit.receive_payment',
        'revenue_credit.agoda_outstanding', 'revenue_credit.sms_revenue')->orderBy('revenue_id', 'asc')->get();

            return response()->json([
                'data' => $agoda_received,
                'status' => 200,
            ]);
    }

    public function export()
    {
        $data = Revenues::whereMonth('date', date('m'))->whereYear('date', date('Y'))->get();
        $pdf = FacadePdf::loadView('pdf.1A', compact('data'));

        return $pdf->stream();
    }
}
