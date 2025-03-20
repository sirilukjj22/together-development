<?php

namespace App\Http\Controllers\Harmony;

use App\Http\Controllers\Controller;
use App\Models\Harmony\Harmony_document_agoda;
use App\Models\Harmony\Harmony_log_agoda;
use App\Models\Harmony\Harmony_revenue_credit;
use App\Models\Harmony\Harmony_revenues;
use App\Models\Harmony\Harmony_SMS_alerts;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HarmonyAgodaRevenuesController extends Controller
{
    
    public function index()
    {
        $query_revenue = Harmony_SMS_alerts::query()->where('status', 5)
            ->select('sms_alert.*', 
                DB::raw("MONTH(date) as month, SUM(amount) as total_sum, COUNT(id) as total_item"),
                DB::raw("SUM(CASE WHEN status_receive_agoda = 1 THEN status_receive_agoda ELSE 0 END) as total_receive"))
            ->groupBy('month');

        $total_agoda_revenue = $query_revenue->get()->sum('total_sum');
        $agoda_revenue = $query_revenue->get();

        $agoda_outstanding = Harmony_revenue_credit::leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
            ->where('revenue_credit.status', 5) ->where('revenue_credit.receive_payment', 0)
            ->select('revenue_credit.id', 'revenue_credit.batch', 'revenue_credit.agoda_check_in', 'revenue_credit.agoda_check_out',
                'revenue_credit.revenue_type', 'revenue_credit.agoda_charge', 'revenue_credit.receive_payment',
                'revenue_credit.agoda_outstanding', 'revenue_credit.sms_revenue', 'revenue.date')
            ->orderBy('revenue.date', 'asc')->get();

        $agoda_debit = Harmony_revenue_credit::leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
            ->where('revenue_credit.status', 5) ->where('revenue_credit.receive_payment', 1)
            ->select('revenue_credit.id', 'revenue_credit.batch', 'revenue_credit.agoda_check_in', 'revenue_credit.agoda_check_out',
                'revenue_credit.revenue_type', 'revenue_credit.agoda_charge', 'revenue_credit.receive_payment',
                'revenue_credit.agoda_outstanding', 'revenue_credit.sms_revenue', 'revenue.date')
            ->orderBy('revenue.date', 'asc')->get();

        $sms_revenue_all = Harmony_SMS_alerts::query()->where('status', 5)
            ->select('amount', 'status_receive_agoda')->get();

        $agoda_all = Harmony_revenue_credit::leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
            ->where('revenue_credit.status', 5)
            ->select('revenue_credit.receive_payment', 'revenue_credit.agoda_outstanding', 'revenue_credit.agoda_charge')
            ->get();


            $totalAccountReceivableAll = 0;
            $totalPendingAccountReceivableAll = 0;
            foreach ($sms_revenue_all as $key => $value) {
                if ($value->status_receive_agoda == 1) {
                    $totalAccountReceivableAll += $value->amount;
                } else {
                    $totalPendingAccountReceivableAll += $value->amount;
                }
            }

            $total_outstanding_all = 0;
            $total_agoda_charge_all = 0;
            $total_agoda_fee = 0;
            $total_agoda_outstanding_revenue = 0;
            $total_agoda_debit_outstanding = 0;
            foreach ($agoda_all as $key => $value) {
                if ($value->receive_payment == 1) {
                    $total_agoda_debit_outstanding += $value->agoda_outstanding;
                } else {
                    $total_agoda_outstanding_revenue += $value->agoda_outstanding;
                }
                $total_outstanding_all += $value->agoda_outstanding;
                $total_agoda_charge_all += $value->agoda_charge;
            }

            $total_agoda_fee = $total_agoda_charge_all - $total_outstanding_all;

        $title = "Agoda";

        return view('agoda_harmony.index', compact(
            'totalAccountReceivableAll', 'totalPendingAccountReceivableAll',
            'agoda_revenue', 'agoda_outstanding', 'agoda_debit', 
            'total_agoda_revenue', 'total_outstanding_all', 'total_agoda_charge_all',
            'total_agoda_outstanding_revenue', 'total_agoda_debit_outstanding',
            'total_agoda_fee',
            'title'
        ));
    }

    // หน้าเลือกที่จะทำรายการ
    public function index_list_days()
    {
        $query = Harmony_SMS_alerts::query()->where('status', 5);
        
        $total_agoda_revenue = $query->get()->sum('amount');
        $agoda_revenue = $query->orderBy('date', 'asc')->get();

        $title = "Agoda Revenue";

        return view('agoda_harmony.list_agoda', compact('agoda_revenue', 'total_agoda_revenue', 'title'));
    }

    // หน้าเลือกรายการที่จะรับชำระ (Create/Edit)
    public function index_receive($id)
    {
        $agoda_revenue = Harmony_SMS_alerts::where('id', $id)->where('status', 5)->select('id', 'amount', 'status_receive_agoda', DB::raw('DATE(date) as sms_date'))->first();

        // เลขที่เอกสาร
        if ($agoda_revenue->status_receive_agoda == 0) {
            $document_no = $this->generateDocumentNumber();
        } else {
            $document_query = Harmony_document_agoda::where('sms_id', $id)->select('doc_no')->first();
            $document_no = !empty($document_query) ? $document_query->doc_no : '';
        }

        $agoda_outstanding = Harmony_revenue_credit::leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
            ->where('revenue_credit.status', 5)->where('revenue_credit.receive_payment', 0)
            ->select('revenue_credit.id', 'revenue_credit.batch', 'revenue_credit.agoda_check_in', 'revenue_credit.agoda_check_out',
                'revenue_credit.revenue_type', 'revenue_credit.agoda_charge', 'revenue_credit.receive_payment',
                'revenue_credit.agoda_outstanding', 'revenue_credit.sms_revenue')
            ->orderBy('revenue_credit.agoda_check_in', 'asc')->get();

        $agoda_debit_revenue = Harmony_revenue_credit::leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
            ->where('revenue_credit.status', 5)->where('revenue_credit.receive_payment', 1)
            ->where('revenue_credit.sms_revenue', $id)
            ->select('revenue_credit.id', 'revenue_credit.batch', 'revenue_credit.agoda_check_in', 'revenue_credit.agoda_check_out',
                'revenue_credit.revenue_type', 'revenue_credit.agoda_charge', 'revenue_credit.receive_payment',
                'revenue_credit.agoda_outstanding', 'revenue_credit.sms_revenue')
            ->orderBy('revenue_credit.agoda_check_in', 'asc')->get();

        $agoda_all = Harmony_revenue_credit::leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
            ->where('revenue_credit.status', 5)
            ->select('revenue_credit.id', 'revenue_credit.batch', 'revenue_credit.agoda_check_in', 'revenue_credit.agoda_check_out',
                'revenue_credit.revenue_type', 'revenue_credit.agoda_charge', 'revenue_credit.receive_payment',
                'revenue_credit.agoda_outstanding', 'revenue_credit.sms_revenue')
            ->orderBy('revenue_credit.agoda_check_in', 'asc')->get();

        $title = "Debit Agoda Revenue";

        $total_outstanding_all = 0;
        $total_agoda_outstanding_revenue = 0;
        $total_agoda_debit_outstanding = 0;
        foreach ($agoda_all as $key => $value) {
            if ($value->receive_payment == 1) {
                $total_agoda_debit_outstanding += $value->agoda_outstanding;
            } else {
                $total_agoda_outstanding_revenue += $value->agoda_outstanding;
            }
            $total_outstanding_all += $value->agoda_outstanding;
        }

        return view('agoda_harmony.edit_agoda_outstanding', compact('agoda_revenue', 'agoda_outstanding', 'agoda_debit_revenue', 'agoda_all', 'document_no', 'total_outstanding_all', 'total_agoda_outstanding_revenue', 'total_agoda_debit_outstanding', 'title'));
    }

    // หน้าดูรายละเอียด
    public function index_detail_receive($id)
    {
        $agoda_query = Harmony_revenue_credit::query()->leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
            ->where('revenue_credit.status', 5)->where('revenue_credit.receive_payment', 1)->where('revenue_credit.sms_revenue', $id)
            ->select('revenue_credit.id', 'revenue_credit.batch', 'revenue_credit.agoda_check_in', 'revenue_credit.agoda_check_out',
                'revenue_credit.revenue_type', 'revenue_credit.agoda_charge', 'revenue_credit.receive_payment',
                'revenue_credit.agoda_outstanding', 'revenue_credit.sms_revenue');
            
        $total_agoda_outstanding = $agoda_query->sum('revenue_credit.agoda_outstanding');
        $agoda_outstanding = $agoda_query->orderBy('revenue_credit.agoda_check_in', 'asc')->get();

        $agoda_revenue = Harmony_SMS_alerts::where('id', $id)->where('status', 5)->select('id', 'amount', DB::raw('DATE(date) as sms_date'))->first();

        $title = "Debit Agoda Revenue";

        return view('agoda_harmony.detail_agoda_outstanding', compact('agoda_outstanding', 'agoda_revenue', 'total_agoda_outstanding', 'title'));
    }

    // บันทึกข้อมูล
    public function receive_payment(Request $request) 
    {
        if (isset($request->receive_id)) {

            $check_document_old = Harmony_document_agoda::where('sms_id', $request->sms_id)->first();

            if (!empty($check_document_old)) {
               Harmony_document_agoda::where('sms_id', $request->sms_id)->update([
                    'issue_date' => $request->issue_date,
                    'sms_id' => $request->sms_id,
                    'status_lock' => 1,
                    'status_paid' => 1,
                    'created_by' => Auth::user()->id
                ]);

                $check_detail_old = Harmony_revenue_credit::where('sms_revenue', $request->sms_id)->select('id')->get();

                if ($check_detail_old->isNotEmpty()) {
                    $check_document_old->receive_id = [$check_detail_old->pluck('id')->toArray()]; // ใช้ pluck เพื่อดึง id ทั้งหมด
                } else {
                    $check_document_old->receive_id = []; // กรณีไม่มีข้อมูล
                }

                $request['id'] = $check_document_old->id;

                $log = Harmony_log_agoda::SaveLog('edit', $check_document_old, $request);

            } else {
                $data = Harmony_document_agoda::create([
                    'doc_no' => $this->generateDocumentNumber(),
                    'issue_date' => $request->issue_date,
                    'sms_id' => $request->sms_id,
                    'status_lock' => 1,
                    'status_paid' => 1,
                    'created_by' => Auth::user()->id
                ])->id;

                $request['id'] = $data;

                $log = Harmony_log_agoda::SaveLog('add', 0, $request);
            }
            
            if ($log) {
                // อัพเดทรายการเดิมให้เป็น 0 ก่อน
                Harmony_revenue_credit::where('sms_revenue', $request->sms_id)->update([
                    'date_receive' => null,
                    'receive_payment' => 0,
                    'sms_revenue' => null
                ]);
            }

            foreach ($request->receive_id as $key => $value) {
                $check_data = Harmony_revenue_credit::where('id', $value)->first();

                if ($check_data->receive_payment == 0) {
                    Harmony_revenue_credit::where('id', $value)->update([
                        'date_receive' => date('Y-m-d'),
                        'receive_payment' => 1,
                        'sms_revenue' => $request->sms_id
                    ]);
                }

                // เปลี่ยนสถานะ SMS เป็น 1 = รับชำระแล้ว
                Harmony_SMS_alerts::where('id', $request->sms_id)->update([
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

    public function select_agoda_received($id) 
    {
        $agoda_received = Harmony_revenue_credit::where('sms_revenue', $id)->where('revenue_credit.status', 5)->where('receive_payment', 1)
        ->select('revenue_credit.id', 'revenue_credit.batch', 'revenue_credit.agoda_check_in', 'revenue_credit.agoda_check_out',
        'revenue_credit.revenue_type', 'revenue_credit.agoda_charge', 'revenue_credit.receive_payment',
        'revenue_credit.agoda_outstanding', 'revenue_credit.sms_revenue')->orderBy('revenue_id', 'asc')->get();

            return response()->json([
                'data' => $agoda_received,
                'status' => 200,
            ]);
    }

    public function select_agoda_outstanding($id) 
    {
        $agoda_outstanding = Harmony_revenue_credit::where('id', $id)->where('revenue_credit.status', 5)
        ->select('revenue_credit.id', 'revenue_credit.batch', 'revenue_credit.agoda_check_in', 'revenue_credit.agoda_check_out',
            'revenue_credit.revenue_type', 'revenue_credit.agoda_charge', 'revenue_credit.receive_payment',
            'revenue_credit.agoda_outstanding', 'revenue_credit.sms_revenue')->first();

            return response()->json([
                'data' => $agoda_outstanding,
                'status' => 200,
            ]);
    }

    // ปุ่ม Confirm ใน Modal
    public function confirm_select_agoda_outstanding(Request $request) 
    {
        if (!empty($request->receive_select_id)) {
            $agoda_outstanding = Harmony_revenue_credit::whereIn('id', $request->receive_select_id)->where('revenue_credit.status', 5)
                ->select('revenue_credit.id', 'revenue_credit.batch', 'revenue_credit.agoda_check_in', 'revenue_credit.agoda_check_out',
                    'revenue_credit.revenue_type', 'revenue_credit.agoda_charge', 'revenue_credit.receive_payment',
                    'revenue_credit.agoda_outstanding', 'revenue_credit.sms_revenue')->get();

            
            return response()->json([
                'data' => $agoda_outstanding,
                'status' => 200,
            ]);
        } else {
            return response()->json([
                'status' => 404,
            ]);
        }
    }

    // public function status_agoda_receive($status, $startDate, $endDate) 
    // {
    //     if ($status == 'all' && $startDate == 'startAll') {
    //         $agoda_received = Harmony_revenue_credit::leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
    //             ->where('revenue_credit.status', 5)
    //             ->select('revenue_credit.id', 'revenue_credit.batch', 'revenue_credit.agoda_check_in', 'revenue_credit.agoda_check_out',
    //                 'revenue_credit.revenue_type', 'revenue_credit.agoda_charge', 'revenue_credit.receive_payment',
    //                 'revenue_credit.agoda_outstanding', 'revenue_credit.sms_revenue', 'revenue.date')->orderBy('revenue.date', 'asc')
    //             ->get();

    //     } elseif ($status != 'all' && $startDate == 'startAll') {
    //         $agoda_received = Harmony_revenue_credit::leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
    //             ->where('revenue_credit.status', 5)->where('receive_payment', $status)
    //             ->select('revenue_credit.id', 'revenue_credit.batch', 'revenue_credit.agoda_check_in', 'revenue_credit.agoda_check_out',
    //                 'revenue_credit.revenue_type', 'revenue_credit.agoda_charge', 'revenue_credit.receive_payment',
    //                 'revenue_credit.agoda_outstanding', 'revenue_credit.sms_revenue', 'revenue.date')->orderBy('revenue.date', 'asc')
    //             ->get();

    //     } elseif ($status != 'all' && $startDate != 'startAll') {
    //         $agoda_received = Harmony_revenue_credit::leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
    //             ->where('revenue_credit.status', 5)->where('receive_payment', $status)
    //             ->whereBetween('revenue.date', [$startDate, $endDate])
    //             ->select('revenue_credit.id', 'revenue_credit.batch', 'revenue_credit.agoda_check_in', 'revenue_credit.agoda_check_out',
    //                 'revenue_credit.revenue_type', 'revenue_credit.agoda_charge', 'revenue_credit.receive_payment',
    //                 'revenue_credit.agoda_outstanding', 'revenue_credit.sms_revenue', 'revenue.date')->orderBy('revenue.date', 'asc')
    //             ->get();

    //     }  elseif ($status == 'all' && $startDate != 'startAll') {
    //         $agoda_received = Harmony_revenue_credit::leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
    //             ->where('revenue_credit.status', 5)
    //             ->whereBetween('revenue.date', [$startDate, $endDate])
    //             ->select('revenue_credit.id', 'revenue_credit.batch', 'revenue_credit.agoda_check_in', 'revenue_credit.agoda_check_out',
    //                 'revenue_credit.revenue_type', 'revenue_credit.agoda_charge', 'revenue_credit.receive_payment',
    //                 'revenue_credit.agoda_outstanding', 'revenue_credit.sms_revenue', 'revenue.date')->orderBy('revenue.date', 'asc')
    //             ->get();
    //     }

    //     $data = [];
    //     $total_amount = 0;

    //     foreach ($agoda_received as $key => $value) {

    //         if ($value->receive_payment == 1) {
    //             $status_name = '<span class="badge bg-success">Paid</span>';
    //         } else {
    //             $status_name = '<span class="badge bg-danger">Unpaid</span>';
    //         }

    //         $total_amount += $value->agoda_outstanding;

    //         $data[] = [
    //             'id' => $value->id,
    //             'date' => Carbon::parse($value->date)->format('d/m/Y'),
    //             'batch' => $value->batch,
    //             'check_in' => Carbon::parse($value->agoda_check_in)->format('d/m/Y'),
    //             'check_out' => Carbon::parse($value->agoda_check_out)->format('d/m/Y'),
    //             'agoda_outstanding' => number_format($value->agoda_outstanding, 2),
    //             'status' => $status_name
    //         ];
    //     }

    //     return response()->json([
    //         'data' => $data,
    //         'total_amount' => $total_amount,
    //         'status' => 200,
    //     ]);
    // }

    public function export()
    {
        $data = Harmony_revenues::whereMonth('date', date('m'))->whereYear('date', date('Y'))->get();
        $pdf = FacadePdf::loadView('pdf.1A', compact('data'));

        return $pdf->stream();
    }

    // ค้นหารายการ (tr > child) ของหน้าแรกตารางที่ 1
    public function search_detail($group)
    {
        $exp = explode('group', $group);
        $id = $exp[1];
        
        $check = Harmony_SMS_alerts::where('id', $id)->select('date', 'date_into')->first();

        $data_child = Harmony_SMS_alerts::whereMonth('date', Carbon::parse($check->date)->format('m'))
                        ->whereYear('date', Carbon::parse($check->date)->format('Y'))->where('status', 5)
                        ->select('id', 'date', 'amount', 'status_receive_agoda')->get();

        return response()->json([
            'data' => $data_child,
        ]);
    }

    // เปลี่ยนสถานะ Lock/Unlock
    public function change_lock_unlock($id, $status)
    {
        
        try {
            Harmony_document_agoda::where('sms_id', $id)->update([
                'status_lock' => $status
            ]);

            $check = Harmony_document_agoda::where('sms_id', $id)->first();

            Harmony_log_agoda::create([
                'document_id' => $check->id,
                'type' => $status == 0 ? "Unlock" : "Lock",
                'changed_attributes' => "Status (Lock/Unlock) : ".$status == 0 ? "Unlock" : "Lock", // บันทึกเฉพาะฟิลด์ที่มีการเปลี่ยนแปลง
                'created_by' => Auth::user()->id, // ตรวจสอบการล็อกอินของผู้ใช้
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'status' => 404
            ]);
        }

        return response()->json([
            'status' => 200,
        ]);
    }

    // Generate Document No 
    public function generateDocumentNumber() {
        // ดึงปีและเดือนปัจจุบันในรูปแบบ YYMM
        $yearMonth = now()->format('y') . now()->format('m'); // ตัวอย่าง: 2411

        // ค้นหาเลขที่เอกสารล่าสุดที่ขึ้นต้นด้วย AG-ปีเดือน
        $lastDocument = Harmony_document_agoda::where('doc_no', 'LIKE', "AG-$yearMonth%")
            ->orderBy('doc_no', 'desc')
            ->first();

        // ตรวจสอบลำดับล่าสุด
        if ($lastDocument) {
            // ดึงตัวเลข 4 หลักสุดท้ายจากเลขที่เอกสารล่าสุด
            $lastNumber = (int)substr($lastDocument->doc_no, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT); // เพิ่ม 1 และเติม 0
        } else {
            // หากไม่มีเลขที่เอกสารในเดือนนั้น เริ่มต้นที่ 0001
            $newNumber = '0001';
        }

        // รวมเป็นเลขที่เอกสารใหม่
        return "AG-$yearMonth$newNumber";
    }

    // หน้า Log
    public function logs($id)
    {
        $document_agoda = Harmony_document_agoda::where('sms_id', $id)->first();

        $log_agoda = Harmony_log_agoda::where('document_id', $document_agoda->id)->get();

        $title = "Logs";

        return view('agoda_harmony.log_agoda', compact('document_agoda', 'log_agoda', 'title'));
    }

    public function search_table(Request $request)
    {
        $data = [];

        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;

        if ($request->table_name == "agodaRevenueTable") {
            if (!empty($request->search_value)) {
                $query = Harmony_SMS_alerts::query()->where('status', 5)
                    ->select('sms_alert.*', 
                        DB::raw("MONTH(date) as month, SUM(amount) as total_sum, COUNT(id) as total_item"),
                        DB::raw("SUM(CASE WHEN status_receive_agoda = 1 THEN status_receive_agoda ELSE 0 END) as total_receive"))
                    ->groupBy(DB::raw("MONTH(date)"));
    
                    if ($request->year == 'all') {
                        $query->havingRaw('SUM(amount) LIKE ? OR DATE_FORMAT(date, "%M") LIKE ? OR YEAR(date) LIKE ?', ['%' . $request->search_value . '%', '%' . $request->search_value . '%', '%' . $request->search_value . '%']);
                    } else {
                        $query->havingRaw('SUM(amount) LIKE ? AND YEAR(date) = ? OR DATE_FORMAT(date, "%M") LIKE ? AND YEAR(date) = ?', ['%' . $request->search_value . '%', $request->year, '%' . $request->search_value . '%', $request->year]);
                    }
    
                    $total_amount = $query->get()->sum('total_sum');
                    $total_count = $query->get()->count();
                    $data_query = $query->get();
            } else {
                $query = Harmony_SMS_alerts::query()->where('status', 5);
    
                    if (!empty($request->year) && $request->year != 'all') {
                        $query->whereYear('date', $request->year);
                    }
                    $query->select('sms_alert.*', 
                        DB::raw("MONTH(date) as month, SUM(amount) as total_sum, COUNT(id) as total_item"),
                        DB::raw("SUM(CASE WHEN status_receive_agoda = 1 THEN status_receive_agoda ELSE 0 END) as total_receive"))
                    ->groupBy(DB::raw("MONTH(date)"));
                    
                $total_amount = $query->get()->sum('total_sum');
                $total_count = $query->get()->count();
                $data_query = $query->get();
            }
        }

        elseif ($request->table_name == "agodaOutstandingTable") {
            $month = str_pad($request->month, 2, '0', STR_PAD_LEFT);

            if (!empty($request->search_value)) {

                $query = Harmony_revenue_credit::query()->leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
                    ->where('revenue_credit.status', 5) ->where('revenue_credit.receive_payment', 0);

                    if ($request->year == 'all' && $request->month == 'all') { // ทั้งหมด
                        $query->havingRaw('revenue_credit.agoda_outstanding LIKE ? 
                            OR revenue_credit.batch LIKE ? 
                            OR revenue.date LIKE ?', ['%' . $request->search_value . '%', '%' . $request->search_value . '%', '%' . $request->search_value . '%']);

                    } elseif ($request->year == 'all' && !empty($request->month) && $request->month != 'all') { // เลือกเฉพาะเดือน
                        $query->havingRaw('revenue_credit.agoda_outstanding LIKE ? AND MONTH(revenue.date) = ? 
                            OR revenue_credit.batch LIKE ? AND MONTH(revenue.date) = ?
                            OR revenue.date LIKE ? AND MONTH(revenue.date) = ?', ['%' . $request->search_value . '%', $month, '%' . $request->search_value . '%', $month, '%' . $request->search_value . '%', $month]);
                    } elseif (!empty($request->year) && $request->year != 'all' && $request->month == 'all') { // เลือกเฉพาะปี
                        $query->havingRaw('revenue_credit.agoda_outstanding LIKE ? AND YEAR(revenue.date) = ? 
                            OR revenue_credit.batch LIKE ? AND YEAR(revenue.date) = ?
                            OR revenue.date LIKE ? AND YEAR(revenue.date) = ?', ['%' . $request->search_value . '%', $request->year, '%' . $request->search_value . '%', $request->year, '%' . $request->search_value . '%', $request->year]);
                    } else { // ทั้งหมดไม่ใช่ค่า All
                        $query->havingRaw('revenue_credit.agoda_outstanding LIKE ? AND MONTH(revenue.date) = ? AND YEAR(revenue.date) = ? 
                            OR revenue_credit.batch LIKE ? AND MONTH(revenue.date) = ? AND YEAR(revenue.date) = ?
                            OR revenue.date LIKE ? AND MONTH(revenue.date) = ? AND YEAR(revenue.date) = ?', ['%' . $request->search_value . '%', $month, $request->year, '%' . $request->search_value . '%', $month, $request->year, '%' . $request->search_value . '%', $month, $request->year]);
                    }

                $query->select('revenue_credit.id', 'revenue_credit.batch', 'revenue_credit.agoda_check_in', 'revenue_credit.agoda_check_out',
                        'revenue_credit.revenue_type', 'revenue_credit.agoda_charge', 'revenue_credit.receive_payment',
                        'revenue_credit.agoda_outstanding', 'revenue_credit.sms_revenue', 'revenue.date')->orderBy('revenue.date', 'asc');

                $total_amount = $query->get()->sum('agoda_outstanding');
                $total_count = $query->get()->count();
                $data_query = $query->get();

            } else {
                $query = Harmony_revenue_credit::query()->leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
                    ->where('revenue_credit.status', 5) ->where('revenue_credit.receive_payment', 0);

                    if (!empty($request->month) && $request->month != 'all') {
                        $query->whereMonth('revenue.date', $request->month);
                    }

                    if (!empty($request->year) && $request->year != 'all') {
                        $query->whereYear('revenue.date', $request->year);
                    }

                $query->select('revenue_credit.id', 'revenue_credit.batch', 'revenue_credit.agoda_check_in', 'revenue_credit.agoda_check_out',
                        'revenue_credit.revenue_type', 'revenue_credit.agoda_charge', 'revenue_credit.receive_payment',
                        'revenue_credit.agoda_outstanding', 'revenue_credit.sms_revenue', 'revenue.date')->orderBy('revenue.date', 'asc');

                $total_amount = $query->get()->sum('agoda_outstanding');
                $total_count = $query->get()->count();
                $data_query = $query->get();
            }
        }

        elseif ($request->table_name == "agodaDebitTable") {
            $month = str_pad($request->month, 2, '0', STR_PAD_LEFT);

            if (!empty($request->search_value)) {

                $query = Harmony_revenue_credit::query()->leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
                    ->where('revenue_credit.status', 5) ->where('revenue_credit.receive_payment', 1);

                    if ($request->year == 'all' && $request->month == 'all') { // ทั้งหมด
                        $query->havingRaw('revenue_credit.agoda_outstanding LIKE ? 
                            OR revenue_credit.batch LIKE ? 
                            OR revenue.date LIKE ?', ['%' . $request->search_value . '%', '%' . $request->search_value . '%', '%' . $request->search_value . '%']);

                    } elseif ($request->year == 'all' && !empty($request->month) && $request->month != 'all') { // เลือกเฉพาะเดือน
                        $query->havingRaw('revenue_credit.agoda_outstanding LIKE ? AND MONTH(revenue.date) = ? 
                            OR revenue_credit.batch LIKE ? AND MONTH(revenue.date) = ?
                            OR revenue.date LIKE ? AND MONTH(revenue.date) = ?', ['%' . $request->search_value . '%', $month, '%' . $request->search_value . '%', $month, '%' . $request->search_value . '%', $month]);
                    } elseif (!empty($request->year) && $request->year != 'all' && $request->month == 'all') { // เลือกเฉพาะปี
                        $query->havingRaw('revenue_credit.agoda_outstanding LIKE ? AND YEAR(revenue.date) = ? 
                            OR revenue_credit.batch LIKE ? AND YEAR(revenue.date) = ?
                            OR revenue.date LIKE ? AND YEAR(revenue.date) = ?', ['%' . $request->search_value . '%', $request->year, '%' . $request->search_value . '%', $request->year, '%' . $request->search_value . '%', $request->year]);
                    } else { // ทั้งหมดไม่ใช่ค่า All
                        $query->havingRaw('revenue_credit.agoda_outstanding LIKE ? AND MONTH(revenue.date) = ? AND YEAR(revenue.date) = ? 
                            OR revenue_credit.batch LIKE ? AND MONTH(revenue.date) = ? AND YEAR(revenue.date) = ?
                            OR revenue.date LIKE ? AND MONTH(revenue.date) = ? AND YEAR(revenue.date) = ?', ['%' . $request->search_value . '%', $month, $request->year, '%' . $request->search_value . '%', $month, $request->year, '%' . $request->search_value . '%', $month, $request->year]);
                    }

                $query->select('revenue_credit.id', 'revenue_credit.batch', 'revenue_credit.agoda_check_in', 'revenue_credit.agoda_check_out',
                        'revenue_credit.revenue_type', 'revenue_credit.agoda_charge', 'revenue_credit.receive_payment',
                        'revenue_credit.agoda_outstanding', 'revenue_credit.sms_revenue', 'revenue.date')->orderBy('revenue.date', 'asc');

                $total_amount = $query->get()->sum('agoda_outstanding');
                $total_count = $query->get()->count();
                $data_query = $query->get();

            } else {
                $query = Harmony_revenue_credit::query()->leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
                    ->where('revenue_credit.status', 5) ->where('revenue_credit.receive_payment', 1);

                    if (!empty($request->month) && $request->month != 'all') {
                        $query->whereMonth('revenue.date', $request->month);
                    }

                    if (!empty($request->year) && $request->year != 'all') {
                        $query->whereYear('revenue.date', $request->year);
                    }

                $query->select('revenue_credit.id', 'revenue_credit.batch', 'revenue_credit.agoda_check_in', 'revenue_credit.agoda_check_out',
                        'revenue_credit.revenue_type', 'revenue_credit.agoda_charge', 'revenue_credit.receive_payment',
                        'revenue_credit.agoda_outstanding', 'revenue_credit.sms_revenue', 'revenue.date')
                        ->orderBy('revenue.date', 'asc');

                $total_amount = $query->get()->sum('agoda_outstanding');
                $total_count = $query->get()->count();
                $data_query = $query->get();
            }
        }

        elseif ($request->table_name == "agodaRevenueDayTable") {
            if (!empty($request->search_value)) {
                $query = Harmony_SMS_alerts::query()->where('status', 5);

                    if (!empty($request->year) && $request->year != 'all') 
                    {
                        $query->whereYear('date', $request->year);
                    }

                    if (!empty($request->month) && $request->month != 'all') 
                    {
                        $query->whereMonth('date', $request->month);
                    }

                    if (isset($request->status_paid) && $request->status_paid != 'all') 
                    {
                        $query->where('status_receive_agoda', $request->status_paid);
                    }

                    $query->where('amount', 'LIKE', '%'.$request->search_value.'%');
    
                    $total_amount = $query->get()->sum('amount');
                    $total_count = $query->get()->count();
                    $data_query = $query->get();
            } else {
                $query = Harmony_SMS_alerts::query()->where('status', 5);
    
                    if (!empty($request->year) && $request->year != 'all') 
                    {
                        $query->whereYear('date', $request->year);
                    }

                    if (!empty($request->month) && $request->month != 'all') 
                    {
                        $query->whereMonth('date', $request->month);
                    }

                    if (isset($request->status_paid) && $request->status_paid != 'all') 
                    {
                        $query->where('status_receive_agoda', $request->status_paid);
                    }
                    
                $total_amount = $query->get()->sum('amount');
                $total_count = $query->get()->count();
                $data_query = $query->get();
            }
        }

        // Agoda Revenue
        $total = $total_amount;
        $totalAllItem = 0;
        $totalAllReceive = 0;
        $totalList = $total_count;

        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {

                if ($request->table_name == "agodaRevenueTable") {
                    $btn_detail = '';
                    $status = '';

                    if ($value->total_receive == 0) 
                    {
                        $status = '<i class="fa fa-check-square" style="font-size:20px;color:rgb(131, 133, 131)"></i>';
                    } elseif ($value->total_receive < $value->total_item) {
                        $status = '<i class="fa fa-check-square" style="font-size:20px;color:#da8404;"></i>';
                    } else  {
                        $status = '<i class="fa fa-check-square" style="font-size:20px;color:#44a768;"></i>';
                    }

                    $btn_detail = '<div class="dropdown center viewbt">
                                        <button class="toggle-button btn-detail" data-group="group'.$value->id.'" value="0">
                                            View
                                        </button>
                                    </div>';

                    $totalAllItem += $value->total_item;
                    $totalAllReceive += $value->total_receive;

                    $data[] = [
                        'id' => $value->id,
                        'number' => $key + 1,
                        'month' => Carbon::parse($value->date)->format('F Y'),
                        'agoda_paid' => $value->total_sum,
                        'item' => $value->total_receive."/".$value->total_item,
                        'status' => $status,
                        'btn_detail' => $btn_detail,
                    ];
                }

                elseif ($request->table_name == "agodaOutstandingTable") {
                    $status = '<span class="wrap-status-unpaid">unpaid</span>';
    
                    $data[] = [
                        'id' => $value->id,
                        'number' => $key + 1,
                        'date' => Carbon::parse($value->date)->format('d/m/Y'),
                        'booking' => $value->batch,
                        'check_in' => Carbon::parse($value->agoda_check_in)->format('d/m/Y'),
                        'check_out' => Carbon::parse($value->agoda_check_out)->format('d/m/Y'),
                        'amount' => $value->agoda_outstanding,
                        'status' => $status,
                    ];
                }

                elseif ($request->table_name == "agodaDebitTable") {
                    $status = '<span class="wrap-status-paid">paid</span>';

                    $data[] = [
                        'id' => $value->id,
                        'number' => $key + 1,
                        'date' => Carbon::parse($value->date)->format('d/m/Y'),
                        'booking' => $value->batch,
                        'check_in' => Carbon::parse($value->agoda_check_in)->format('d/m/Y'),
                        'check_out' => Carbon::parse($value->agoda_check_out)->format('d/m/Y'),
                        'amount' => $value->agoda_outstanding,
                        'status' => $status,
                    ];
                }

                if ($request->table_name == "agodaRevenueDayTable") {
                    $btn_detail = '';
                    $status = '';
                    $status_lock = '';

                    $month = Carbon::parse($value->date)->format('m');
                    $year = Carbon::parse($value->date)->format('Y');

                    // เข้าบัญชี
                    $into_account = '<div class=""><img class="img-bank" src="../image/bank/SCB.jpg" style="border-radius: 50%;">SCB '.$value->into_account.'</div>';

                    if ($value->status_receive_agoda == 0) 
                    {
                        $status = '<span class="wrap-status-pending">pending</span>';
                    } else  {
                        $status = '<span class="wrap-status-paid">paid</span>';
                    }

                    if (@$value->statusLockAgoda->status_lock == 0) 
                    {
                        $status_lock = '<i class="fa fa-unlock"></i>';
                    } else  {
                        $status_lock = '<i class="fa fa-lock"></i>';
                    }

                    $btn_detail .= '<div class="dropdown center">
                                        <div style="width: 90px" class="dropdown-shoose-items dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                            Select
                                        </div>
                                            <ul class="dropdown-menu btn-dropdown-menu" aria-labelledby="dropdownMenuButton">';
                                                if ($value->status_receive_agoda == 0)
                                                {
                                                    $btn_detail .= '<li>
                                                                        <a href="/debit-agoda-update-receive/'.$value->id.'/'.$month.'/'.$year.'" class="dropdown-item">Create</a>
                                                                    </li>';
                                                } else {
                                                    // $checkReceiveDate = Harmony_revenue_credit::getAgodaReceiveDate($value->id);

                                                    //  Permission 1 และ 2 สามารถเห็นปุ่ม Lock/Unlock ได้
                                                    if (Auth::user()->permission == 1 || Auth::user()->permission == 2) {
                                                        if ($value->status_receive_agoda == 0)
                                                        {
                                                            $btn_detail .= '<li><a href="javascript:void(0);" class="dropdown-item lock-item" onclick="lockItem('.$value->id.', 1)">Lock</a></li>';
                                                        } else {
                                                            $btn_detail .= '<li><a href="javascript:void(0);" class="dropdown-item unlock-item" onclick="lockItem('.$value->id.', 0)">Unlock</a></li>';
                                                        }
                                                    }

                                                    // หากต้องการแก้ไขรายการ ต้องให้ Admin Unlock ให้ก่อน **Admin ต้อง Unlock ก่อนเหมือนกัน จะสามารถแก้ไขได้
                                                    if (@$value->statusLockAgoda->status_lock == 0)
                                                    {
                                                        $btn_detail .= '<li>
                                                                            <a href="/debit-agoda-update-receive/'.$value->id.'" class="dropdown-item">Edit</a>
                                                                        </li>';
                                                    }
                                                }

                                            $btn_detail .= '<li>
                                                    <a href="/debit-agoda-detail/'.$value->id.'" class="dropdown-item">View</a>
                                                </li>';

                                                if ($value->status_receive_agoda == 0)
                                                {
                                                    $btn_detail .= '<li>
                                                            <a href="/debtor-agoda-logs/'.$value->id.'" class="dropdown-item">Logs</a>
                                                        </li>';
                                                }
                                        
                                            $btn_detail .= '</ul></div>';
                                            
    
                    $data[] = [
                        'id' => $value->id,
                        'number' => $key + 1,
                        'date' => Carbon::parse($value->date)->format('d/m/Y'),
                        'into_account' => $into_account,
                        'amount' => $value->amount,
                        'status' => $status,
                        'lock_unlock' => $status_lock,
                        'btn_detail' => $btn_detail,
                    ];
                }
            }
        }

        return response()->json([
            'data' => $data,
            'total' => number_format($total, 2),
            'totalAllItem' => $totalAllItem,
            'totalAllReceive' => $totalAllReceive,
            'totalList' => $totalList
        ]);
    }

    // Graph Month Sales
    public function graph_month_sales()
    {
        $agoda_query = Harmony_revenue_credit::leftJoin('revenue', 'revenue_credit.revenue_id', '=', 'revenue.id')
            ->where('revenue_credit.status', 5)
            ->select(
                DB::raw('YEAR(revenue.date) as year'), // ดึงปี
                DB::raw('MONTH(revenue.date) as month'), // ดึงเดือน
                DB::raw('SUM(revenue_credit.agoda_outstanding) as total_agoda_outstanding'), // รวมยอด agoda_outstanding
            )
            ->groupBy('year', 'month') // Group ตามปีและเดือน
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

            $agoda = $agoda_query->keyBy('month');
            $agoda_year = $agoda_query->keyBy('year');
            $data = [];
            
            for ($y = 2024; $y <= 2026; $y += 1) { 
                for ($i = 1; $i <= 12; $i++) { 
                    if ($agoda_year->has($y)) {
                        $data[$y][] = $agoda->has($i) ? $agoda[$i]->total_agoda_outstanding : 0;
                    } else {
                        $data[$y][] = 0;
                    }
                }
            }

        return response()->json([
            'data' => $data,
            'status' => 200,
        ]);
    }

    // Graph Monthly Agoda Charge
    // public function graph_month_charge()
    // {
    //     // ยอด SMS ทั้งหมด
    //     $sms_query = Harmony_SMS_alerts::where('status', 5)
    //         ->select('amount', DB::raw('YEAR(date) as year'), DB::raw('MONTH(date) as month'), DB::raw("SUM(amount) as total_sum"))
    //         ->groupBy('year', 'month')->orderBy('year', 'asc')->orderBy('month', 'asc')
    //         ->get();

    //     // ยอด SMS ที่กดรับชำระแล้ว สถานะเป็น paid
    //     $sms_paid_query = Harmony_SMS_alerts::where('status', 5)
    //         ->where('status_receive_agoda', 1)
    //         ->select('amount',  DB::raw('YEAR(date) as year'), DB::raw('MONTH(date) as month'), DB::raw("SUM(amount) as total_sum"))
    //         ->groupBy('year', 'month')->orderBy('year', 'asc')->orderBy('month', 'asc')
    //         ->get();

    //     // ยอด SMS ที่ยังไม่ได้กดรับชำระแล้ว สถานะเป็น pending
    //     $sms_pending_query = Harmony_SMS_alerts::where('status', 5)
    //         ->where('status_receive_agoda', 0)
    //         ->select('amount',  DB::raw('YEAR(date) as year'), DB::raw('MONTH(date) as month'), DB::raw("SUM(amount) as total_sum"))
    //         ->groupBy('year', 'month')->orderBy('year', 'asc')->orderBy('month', 'asc')
    //         ->get();

    //     $agoda_outstanding = Harmony_revenue_credit::leftJoin('revenue', 'revenue_credit.revenue_id', '=', 'revenue.id')
    //         ->where('revenue_credit.status', 5)
    //         ->select(DB::raw('YEAR(revenue.date) as year'), DB::raw('MONTH(revenue.date) as month'),  DB::raw('SUM(revenue_credit.agoda_outstanding) as total_agoda_outstanding'))
    //         ->groupBy('year', 'month')->orderBy('year', 'asc')->orderBy('month', 'asc')
    //         ->get();

    //     $agoda_outstanding_sum = Harmony_revenue_credit::leftJoin('revenue', 'revenue_credit.revenue_id', '=', 'revenue.id')
    //         ->where('revenue_credit.status', 5)->select('revenue_credit.agoda_outstanding')
    //         ->sum('revenue_credit.agoda_outstanding');

    //         // SMS
    //         $sms = $sms_query->keyBy('month');
    //         $sms_year = $sms_query->keyBy('year');
    //         $sms_paid = $sms_paid_query->keyBy('month');
    //         $sms_paid_year = $sms_paid_query->keyBy('year');
    //         $sms_pending = $sms_pending_query->keyBy('month');
    //         $sms_pending_year = $sms_pending_query->keyBy('year');

    //         // Revenue
    //         $agoda = $agoda_outstanding->keyBy('month');
    //         $agoda_year = $agoda_outstanding->keyBy('year');

    //         // เก็บค่าเป็น Array
    //         $data_sms = [];
    //         $data_sms_pending = [];
    //         $data_outstanding = [];

    //         $sum = 0;
            
    //         for ($y = 2024; $y <= 2026; $y += 1) { 
    //             for ($i = 1; $i <= 12; $i++) { 
    //                 if ($sms_year->has($y)) {
    //                     $data_sms[$y][] = $sms->has($i) ? $sms[$i]->total_sum : 0;
    //                 } else {
    //                     $data_sms[$y][] = 0;
    //                 }

    //                 if ($sms_paid_year->has($y)) {
    //                     $data_sms_paid[$y][] = $sms_paid->has($i) ? $sms_paid[$i]->total_sum : 0;
    //                 } else {
    //                     $data_sms_paid[$y][] = 0;
    //                 }

    //                 if ($sms_pending_year->has($y)) {
    //                     $data_sms_pending[$y][] = $sms_pending->has($i) ? $sms_pending[$i]->total_sum : 0;
    //                 } else {
    //                     $data_sms_pending[$y][] = 0;
    //                 }

    //                 if ($sms_year->has($y)) {
    //                     // $sum = ($agoda->has($i) ? (double)$agoda[$i]->total_agoda_outstanding : 0) - ($sms_paid->has($i) && $sms_paid_year->has($y) ? (double)$sms_paid[$i]->total_sum : 0);
    //                     $agoda_outstanding_sum -= ($sms->has($i) ? (double)$sms[$i]->total_sum : 0);
    //                     if ($agoda_outstanding_sum > 0 && $i == date('m')) {
    //                         $data_outstanding[$y][] = $agoda_outstanding_sum;
    //                     } else {
    //                         $data_outstanding[$y][] = 0;
    //                     }
    //                 } else {
    //                     $data_outstanding[$y][] = 0;
    //                 }
    //             }
    //         }

    //     return response()->json([
    //         'data_sms_paid' => $data_sms_paid,
    //         'data_sms' => $data_sms,
    //         'data_sms_pending' => $data_sms_pending,
    //         'data_outstanding' => $data_outstanding,
    //         'status' => 200,
    //     ]);
    // }

    public function graph_month_charge()
    {
        // ยอด SMS ทั้งหมด
        $sms_query = Harmony_SMS_alerts::where('status', 5)
            ->select('amount', DB::raw('YEAR(date) as year'), DB::raw('MONTH(date) as month'), DB::raw("SUM(amount) as total_sum"))
            ->groupBy('year', 'month')->orderBy('year', 'asc')->orderBy('month', 'asc')
            ->get();

        // ยอด SMS ที่กดรับชำระแล้ว สถานะเป็น paid
        $sms_paid_query = Harmony_SMS_alerts::where('status', 5)
            ->where('status_receive_agoda', 1)
            ->select('amount',  DB::raw('YEAR(date) as year'), DB::raw('MONTH(date) as month'), DB::raw("SUM(amount) as total_sum"))
            ->groupBy('year', 'month')->orderBy('year', 'asc')->orderBy('month', 'asc')
            ->get();

        // ยอด SMS ที่ยังไม่ได้กดรับชำระแล้ว สถานะเป็น pending
        $sms_pending_query = Harmony_SMS_alerts::where('status', 5)
            ->where('status_receive_agoda', 0)
            ->select('amount',  DB::raw('YEAR(date) as year'), DB::raw('MONTH(date) as month'), DB::raw("SUM(amount) as total_sum"))
            ->groupBy('year', 'month')->orderBy('year', 'asc')->orderBy('month', 'asc')
            ->get();

        $agoda_outstanding = Harmony_revenue_credit::leftJoin('revenue', 'revenue_credit.revenue_id', '=', 'revenue.id')
            ->where('revenue_credit.status', 5)
            ->select(DB::raw('YEAR(revenue.date) as year'), DB::raw('MONTH(revenue.date) as month'),  DB::raw('SUM(revenue_credit.agoda_outstanding) as total_agoda_outstanding'))
            ->groupBy('year', 'month')->orderBy('year', 'asc')->orderBy('month', 'asc')
            ->get();

        $agoda_outstanding_sum = Harmony_revenue_credit::leftJoin('revenue', 'revenue_credit.revenue_id', '=', 'revenue.id')
            ->where('revenue_credit.status', 5)->select('revenue_credit.agoda_outstanding')
            ->sum('revenue_credit.agoda_outstanding');

            // SMS
            $sms = $sms_query->keyBy('month');
            $sms_year = $sms_query->keyBy('year');
            $sms_paid = $sms_paid_query->keyBy('month');
            $sms_paid_year = $sms_paid_query->keyBy('year');
            $sms_pending = $sms_pending_query->keyBy('month');
            $sms_pending_year = $sms_pending_query->keyBy('year');

            // Revenue
            $agoda = $agoda_outstanding->keyBy('month');
            // $agoda_year = $agoda_outstanding->keyBy('year');

            // เก็บค่าเป็น Array
            $data_sms = [];
            $data_sms_pending = [];
            $data_outstanding = [];

            $sum = 0;
            $sms_summary = $agoda_outstanding_sum;
            
            for ($y = 2024; $y <= date('Y', strtotime('+1 year')); $y += 1) { 
                for ($i = 1; $i <= 12; $i++) { 
                    if ($sms_year->has($y) && $sms->has($i)) {
                        $data_sms[$y][] = $sms->has($i) && $sms[$i]->year == $y ? $sms[$i]->total_sum : 0;
                    } else {
                        $data_sms[$y][] = 0;
                    }

                    if ($sms_paid_year->has($y)) {
                        $data_sms_paid[$y][] = $sms_paid->has($i) && $sms_paid[$i]->year == $y ? $sms_paid[$i]->total_sum : 0;
                    } else {
                        $data_sms_paid[$y][] = 0;
                    }

                    if ($sms_pending_year->has($y)) {
                        $data_sms_pending[$y][] = $sms_pending->has($i) && $sms_pending[$i]->year == $y ? $sms_pending[$i]->total_sum : 0;
                    } else {
                        $data_sms_pending[$y][] = 0;
                    }

                    if ($sms_year->has($y)) {
                        $sms_summary -= ($sms->has($i) && $sms[$i]->year == $y ? round($sms[$i]->total_sum, 2) : 0);
                        $sms_summary = round($sms_summary, 2);

                        if ($sms_summary >= 0 && $agoda->has($i) && $agoda[$i]->year == $y || $agoda->has($i) && $agoda[$i]->year == $y) {
                            $data_outstanding[$y][] = 0;
                        } else {
                            if ($y == date('Y') && $i == date('m')) {
                                $data_outstanding[$y][] = $sms_summary;
                            } else {
                                $data_outstanding[$y][] = 0;
                            }
                        }

                    } else {
                        $data_outstanding[$y][] = 0;
                    }
                }
            }

        return response()->json([
            'data_sms_paid' => $data_sms_paid,
            'data_sms' => $data_sms,
            'data_sms_pending' => $data_sms_pending,
            'data_outstanding' => $data_outstanding,
            'status' => 200,
        ]);
    }
}
