<?php

namespace App\Http\Controllers\Harmony;

use App\Http\Controllers\Controller;
use App\Models\Harmony\Harmony_document_elexa;
use App\Models\Harmony\Harmony_elexa_debit_revenue;
use App\Models\Harmony\Harmony_log_elexa;
use App\Models\Harmony\Harmony_revenue_credit;
use App\Models\Harmony\Harmony_SMS_alerts;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HarmonyElexaController extends Controller
{
    public function index()
    {
        $query_revenue = Harmony_SMS_alerts::query()->where('status', 8)
            ->select('sms_alert.*', 
                DB::raw("MONTH(date) as month, SUM(amount) as total_sum, COUNT(id) as total_item"),
                DB::raw("SUM(CASE WHEN status_receive_elexa = 1 THEN status_receive_elexa ELSE 0 END) as total_receive"))
            ->groupBy('month');

        $total_elexa_revenue = $query_revenue->get()->sum('total_sum');
        $elexa_revenue = $query_revenue->get();

        $elexa_outstanding = Harmony_revenue_credit::leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
            ->where('revenue_credit.status', 8) ->where('revenue_credit.receive_payment', 0)
            ->select(
                'revenue_credit.id', 
                'revenue_credit.batch',
                'revenue_credit.revenue_type', 
                'revenue_credit.ev_charge', 
                'revenue_credit.ev_revenue', 
                'revenue_credit.receive_payment', 
                'revenue_credit.sms_revenue', 
                'revenue.date'
            )->orderBy('revenue.date', 'asc')->get();

        $elexa_debit = Harmony_revenue_credit::leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
            ->where('revenue_credit.status', 8) ->where('revenue_credit.receive_payment', 1)
            ->select(
                'revenue_credit.id', 
                'revenue_credit.batch',
                'revenue_credit.revenue_type', 
                'revenue_credit.ev_charge', 
                'revenue_credit.ev_revenue', 
                'revenue_credit.receive_payment', 
                'revenue_credit.sms_revenue', 
                'revenue.date'
            )->orderBy('revenue.date', 'asc')->get();

        $sms_revenue_all = Harmony_SMS_alerts::where('status', 8)->select('amount', 'status_receive_elexa')->get();

        $elexa_all = Harmony_revenue_credit::leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
            ->where('revenue_credit.status', 8)
            ->select('revenue_credit.receive_payment', 'revenue_credit.ev_revenue', 'revenue_credit.ev_charge', 'revenue_credit.ev_fee',)
            ->get();


            $totalAccountReceivableAll = 0;
            $totalPendingAccountReceivableAll = 0;
            foreach ($sms_revenue_all as $key => $value) {
                if ($value->status_receive_elexa == 1) {
                    $totalAccountReceivableAll += $value->amount;
                } else {
                    $totalPendingAccountReceivableAll += $value->amount;
                }
            }

            $total_outstanding_all = 0;
            $total_elexa_charge_all = 0;
            $total_elexa_fee = 0;
            $total_elexa_outstanding_revenue = 0;
            $total_elexa_debit_outstanding = 0;
            foreach ($elexa_all as $key => $value) {
                if ($value->receive_payment == 1) {
                    $total_elexa_debit_outstanding += $value->ev_revenue;
                } else {
                    $total_elexa_outstanding_revenue += $value->ev_revenue;
                }
                $total_outstanding_all += $value->ev_revenue;
                $total_elexa_charge_all += $value->ev_charge;
                $total_elexa_fee += $value->ev_fee;
            }

        $title = "Elexa EGAT";

        return view('elexa_harmony.index', compact(
            'totalAccountReceivableAll', 
            'totalPendingAccountReceivableAll',
            'elexa_revenue', 
            'elexa_outstanding', 
            'elexa_debit', 
            'total_elexa_revenue', 
            'total_outstanding_all', 
            'total_elexa_charge_all',
            'total_elexa_outstanding_revenue', 
            'total_elexa_debit_outstanding',
            'total_elexa_fee',
            'title'
        ));
    }

    public function get_outstanding(Request $request)
    {
        $search = $request->input('search.value');  // ค่าการค้นหาจาก DataTables
        $start = $request->input('start');           // จุดเริ่มต้นของหน้า
        $length = $request->input('length');         // จำนวนข้อมูลที่ต้องการแสดงในแต่ละหน้า

        // สร้าง query สำหรับการค้นหา
        $query = Harmony_revenue_credit::query()->leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
            ->where('revenue_credit.status', 8)->where('revenue_credit.receive_payment', 0);

        if (isset($request->receiveID) && !empty($request->receiveID)) {
            $query->whereNotIn('revenue_credit.id', $request->receiveID);
        }

        if (isset($request->confirmReceiveID) && !empty($request->confirmReceiveID)) {
            $query->whereNotIn('revenue_credit.id', $request->confirmReceiveID);
        }

        if (isset($request->year) && $request->year != "all") {
            $query->whereYear('revenue.date', $request->year);
        }

        if (isset($request->month) && $request->month != "all") {
            $query->whereMonth('revenue.date', $request->month);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('revenue_credit.date', 'like', "%{$search}%")
                ->orWhere('revenue_credit.batch', 'like', "%{$search}%")
                ->orWhere('revenue_credit.ev_charge', 'like', "%{$search}%")
                ->orWhere('revenue_credit.ev_revenue', 'like', "%{$search}%");
            });
        }

        // การนับจำนวนทั้งหมดและจำนวนที่กรองแล้ว
        $recordsTotal = Harmony_revenue_credit::count();
        $recordsFiltered = $query->count();

        $query->select('revenue_credit.id', 'revenue_credit.batch','revenue_credit.revenue_type', 'revenue_credit.ev_charge', 'revenue_credit.ev_fee',  'revenue_credit.ev_vat', 
        'revenue_credit.ev_revenue', 'revenue_credit.receive_payment', 'revenue_credit.sms_revenue', 'revenue.date');

        $totalAmount = $query->sum('revenue_credit.ev_revenue');

        $data_elexa = $query->orderBy('revenue.date', 'asc')->get();

        $data = [];

        if (count($data_elexa) > 0) {
            foreach ($data_elexa as $key => $value) 
            {
                $btn_action = "";
                $ev_revenue = $value->ev_revenue;

                if ($value->receive_payment == 0)
                {
                    $btn_action = '<button type="button" class="btn btn-color-green btn-sm rounded-pill text-white btn-receive-pay" id="btn-receive-'.$value->id.'" value="0"
                    onclick="select_receive_payment(this, '.$value->id.', '.$ev_revenue.')">Receive</button>';
                } else {
                    $btn_action = '<button type="button" class="btn btn-color-green btn-sm rounded-pill text-white btn-receive-pay" id="btn-receive-'.$value->id.'" value="0"
                    onclick="select_receive_payment(this, '.$value->id.', '.$ev_revenue.')" disabled>Receive</button>';
                }

                if (isset($request->select_all) && $request->select_all == 'true') {
                    $btn_checkbox = '<div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="'.$value->id.'" id="checkboxReceive-'.$value->id.'" checked>
                                        <label class="form-check-label" for="checkboxReceive-'.$value->id.'"></label>
                                    </div>';
                } else {
                    $btn_checkbox = '<div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="'.$value->id.'" id="checkboxReceive-'.$value->id.'">
                                        <label class="form-check-label" for="checkboxReceive-'.$value->id.'"></label>
                                    </div>';
                }

                $data[] = [
                    'id' => $value->id,
                    'check_box' => $btn_checkbox,
                    'date' => $value->date,
                    'order_id' => $value->batch,
                    'ev_charge' => $value->ev_charge,
                    'ev_vat' => $value->ev_vat,
                    'ev_fee' => $value->ev_fee,
                    'amount' => $value->ev_revenue,
                    'btn_action' => $btn_action,
                ];
            }
        }

        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
            'totalAmount' => number_format($totalAmount, 2)
        ]);
    }

    // หน้าเลือกที่จะทำรายการ
    public function index_list_days()
    {
        $query = Harmony_SMS_alerts::query()->where('status', 8);
        
        $total_elexa_revenue = $query->get()->sum('amount');
        $elexa_revenue = $query->orderBy('date', 'asc')->get();

        $title = "Elexa EGAT Revenue";

        return view('elexa_harmony.list_elexa', compact('elexa_revenue', 'total_elexa_revenue', 'title'));
    }

    // หน้าเลือกรายการที่จะรับชำระ (Create/Edit)
    public function index_receive($id)
    {
        $elexa_revenue = Harmony_SMS_alerts::where('id', $id)->where('status', 8)->select('id', 'amount', 'status_receive_elexa', DB::raw('DATE(date) as sms_date'))->first();

        // เลขที่เอกสาร
        if ($elexa_revenue->status_receive_elexa == 0) {
            $document_query = '';
            $document_no = $this->generateDocumentNumber();
        } else {
            $document_query = Harmony_document_elexa::where('sms_id', $id)->select('id', 'doc_no', 'debit_amount')->first();
            $document_no = !empty($document_query) ? $document_query->doc_no : '';
        }

        $elexa_outstanding = Harmony_revenue_credit::leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
            ->where('revenue_credit.status', 8)->where('revenue_credit.receive_payment', 0)
            ->select(
                'revenue_credit.id', 
                'revenue_credit.batch', 
                'revenue_credit.revenue_type', 
                'revenue_credit.ev_charge',
                'revenue_credit.ev_revenue', 
                'revenue_credit.ev_fee',
                'revenue_credit.receive_payment', 
                'revenue_credit.sms_revenue', 
                'revenue.date'
            )->orderBy('revenue.date', 'asc')->get();

        $elexa_debit_revenue = Harmony_revenue_credit::leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
            ->where('revenue_credit.status', 8)->where('revenue_credit.receive_payment', 1)
            ->where('revenue_credit.sms_revenue', $id)
            ->select(
                'revenue_credit.id', 
                'revenue_credit.batch', 
                'revenue_credit.revenue_type', 
                'revenue_credit.ev_charge',
                'revenue_credit.ev_revenue', 
                'revenue_credit.ev_fee',
                'revenue_credit.receive_payment', 
                'revenue_credit.sms_revenue', 
                'revenue.date'
            )->orderBy('revenue.date', 'asc')->get();

        $elexa_all = Harmony_revenue_credit::leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
            ->where('revenue_credit.status', 8)
            ->select(
                'revenue_credit.id', 
                'revenue_credit.batch', 
                'revenue_credit.revenue_type', 
                'revenue_credit.ev_charge',
                'revenue_credit.ev_revenue', 
                'revenue_credit.ev_fee',
                'revenue_credit.receive_payment', 
                'revenue_credit.sms_revenue', 
                'revenue.date'
            )->orderBy('revenue.date', 'asc')->get();

        if (!empty($document_query->id)) {
            $elexa_debit_out = Harmony_elexa_debit_revenue::where('document_elexa', $document_query->id)->get();
        } else {
            $elexa_debit_out = '';
        }

        $title = "Debit Elexa EGAT Revenue";

        $total_outstanding_all = 0;
        $total_elexa_outstanding_revenue = 0;
        $total_elexa_debit_outstanding = 0;
        foreach ($elexa_all as $key => $value) {
            if ($value->receive_payment == 1) {
                $total_elexa_debit_outstanding += $value->ev_revenue;
            } else {
                $total_elexa_outstanding_revenue += $value->ev_revenue;
            }
            $total_outstanding_all += $value->elexa_outstanding;
        }

        return view('elexa_harmony.edit_elexa_outstanding', compact('document_query', 'elexa_revenue', 'elexa_outstanding', 'elexa_debit_revenue', 'elexa_all', 'elexa_debit_out', 'document_no', 'total_outstanding_all', 'total_elexa_outstanding_revenue', 'total_elexa_debit_outstanding', 'title'));
    }

    public function select_elexa_outstanding($id) 
    {

        $elexa_outstanding = Harmony_revenue_credit::leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
            ->where('revenue_credit.id', $id)->where('revenue_credit.status', 8)
            ->select(
                'revenue_credit.id', 
                'revenue_credit.batch', 
                'revenue_credit.revenue_type', 
                'revenue_credit.ev_charge', 
                'revenue_credit.ev_revenue',
                'revenue_credit.receive_payment', 
                'revenue_credit.sms_revenue', 
                'revenue.date'
            )->first();

            return response()->json([
                'data' => $elexa_outstanding,
                'status' => 200,
            ]);
    }

    public function select_all_elexa_outstanding(Request $request) {

        $query = Harmony_revenue_credit::query()->leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
            ->whereIn('revenue_credit.id', $request->selectedItems)
            ->where('revenue_credit.status', 8);

        if (isset($request->year) && $request->year != "all") {
            $query->whereYear('revenue.date', $request->year);
        }

        if (isset($request->month) && $request->month != "all") {
            $query->whereMonth('revenue.date', $request->month);
        }
            
        $query->select(
            'revenue_credit.id', 
            'revenue_credit.batch', 
            'revenue_credit.revenue_type', 
            'revenue_credit.ev_charge', 
            'revenue_credit.ev_revenue',
            'revenue_credit.receive_payment', 
            'revenue_credit.sms_revenue', 
            'revenue.date'
        );

        $totalAmount = $query->sum('revenue_credit.ev_revenue');

        $elexa_outstanding = $query->get();

        if ($totalAmount > $request->total_revenue_amount) {
            $elexa_outstanding = $request->total_revenue_amount;
            $status = 500;
        } else {
            $status = 200;
        }
        

            return response()->json([
                'data' => $elexa_outstanding,
                'total_amount' => $totalAmount,
                'status' => $status,
            ]);
    }

    // ค้นหารายการ (tr > child) ของหน้าแรกตารางที่ 1
    public function search_detail($group)
    {
        $exp = explode('group', $group);
        $id = $exp[1];
        
        $check = Harmony_SMS_alerts::where('id', $id)->select('date', 'date_into')->first();

        $data_child = Harmony_SMS_alerts::whereMonth('date', Carbon::parse($check->date)->format('m'))
                        ->whereYear('date', Carbon::parse($check->date)->format('Y'))->where('status', 8)
                        ->select('id', 'date', 'amount', 'status_receive_elexa')->get();

        return response()->json([
            'data' => $data_child,
        ]);
    }

    // ปุ่ม Confirm ใน Modal
    public function confirm_select_elexa_outstanding(Request $request) 
    {
        if (!empty($request->receive_select_id)) {
            $elexa_outstanding = Harmony_revenue_credit::leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
                ->whereIn('revenue_credit.id', $request->receive_select_id)->where('revenue_credit.status', 8)
                ->select(
                    'revenue_credit.id', 
                    'revenue_credit.batch',
                    'revenue_credit.revenue_type', 
                    'revenue_credit.ev_charge', 
                    'revenue_credit.receive_payment',
                    'revenue_credit.ev_revenue', 
                    'revenue_credit.sms_revenue', 
                    'revenue.date'
                )->get();

            
            return response()->json([
                'data' => $elexa_outstanding,
                'status' => 200,
            ]);
        } else {
            return response()->json([
                'status' => 404,
            ]);
        }
    }

    // บันทึกข้อมูล
    public function receive_payment(Request $request) 
    {
        if (isset($request->receive_id)) {

            $check_document_old = Harmony_document_elexa::where('sms_id', $request->sms_id)->first();

            if (!empty($check_document_old)) {
               Harmony_document_elexa::where('sms_id', $request->sms_id)->update([
                    'issue_date' => $request->issue_date,
                    'sms_id' => $request->sms_id,
                    'debit_amount' => $request->debit_out_amount ?? 0,
                    'status_lock' => 1,
                    'status_paid' => 1,
                    'created_by' => Auth::user()->id
                ]);

                Harmony_elexa_debit_revenue::where('document_elexa', $check_document_old->id)->delete();
                
                if (isset($request->debit_revenue_amount)) {
                    foreach ($request->debit_revenue_amount as $key => $value) {
                        Harmony_elexa_debit_revenue::create([
                            'document_elexa' => $check_document_old->id,
                            'date' => $request->issue_date,
                            'status_type' => $request->status_type ?? 0,
                            'amount' => $value,
                            'remark' => $request->debit_revenue_remark[$key] ?? null,
                            'created_by' => Auth::user()->id
                        ]);
                    }
                }

                $check_detail_old = Harmony_revenue_credit::where('sms_revenue', $request->sms_id)->select('id')->get();

                if ($check_detail_old->isNotEmpty()) {
                    $check_document_old->receive_id = [$check_detail_old->pluck('id')->toArray()]; // ใช้ pluck เพื่อดึง id ทั้งหมด
                } else {
                    $check_document_old->receive_id = []; // กรณีไม่มีข้อมูล
                }

                $request['id'] = $check_document_old->id;

                $log = Harmony_log_elexa::SaveLog('edit', $check_document_old, $request);

            } else {
                $data = Harmony_document_elexa::create([
                    'doc_no' => $this->generateDocumentNumber(),
                    'issue_date' => $request->issue_date,
                    'sms_id' => $request->sms_id,
                    'debit_amount' => $request->debit_out_amount ?? 0,
                    'status_lock' => 1,
                    'status_paid' => 1,
                    'created_by' => Auth::user()->id 
                ])->id;

                $request['id'] = $data;


                if (isset($request->debit_revenue_amount)) {
                    foreach ($request->debit_revenue_amount as $key => $value) {
                        Harmony_elexa_debit_revenue::create([
                            'document_elexa' => $data,
                            'date' => date('Y-m-d'),
                            'status_type' => $request->status_type ?? 0,
                            'amount' => $value,
                            'remark' => $request->debit_revenue_remark[$key] ?? null,
                            'created_by' => Auth::user()->id
                        ]);
                    }
                }

                $log = Harmony_log_elexa::SaveLog('add', 0, $request);
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

    // หน้าดูรายละเอียด
    public function index_detail_receive($id)
    {
        $elexa_query = Harmony_revenue_credit::query()->leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
            ->where('revenue_credit.status', 8)->where('revenue_credit.receive_payment', 1)->where('revenue_credit.sms_revenue', $id)
            ->select(
                'revenue_credit.id', 
                'revenue_credit.batch',
                'revenue_credit.revenue_type', 
                'revenue_credit.ev_charge', 
                'revenue_credit.receive_payment',
                'revenue_credit.ev_revenue', 
                'revenue_credit.sms_revenue', 
                'revenue.date'
            );
            
        $total_elexa_outstanding = $elexa_query->sum('revenue_credit.ev_revenue');
        $elexa_outstanding = $elexa_query->orderBy('revenue.date', 'asc')->get();

        $elexa_revenue = Harmony_SMS_alerts::where('id', $id)->where('status', 8)->select('id', 'amount', DB::raw('DATE(date) as sms_date'))->first();

        $title = "Debit Elexa EGAT Revenue";

        return view('elexa_harmony.detail_elexa_outstanding', compact('elexa_outstanding', 'elexa_revenue', 'total_elexa_outstanding', 'title'));
    }

    public function status_elexa_receive($status) 
    {

        $elexa_received = Harmony_revenue_credit::where('revenue_credit.status', 8)->where('receive_payment', $status)
            ->select(
                'revenue_credit.id', 
                'revenue_credit.batch', 
                'revenue_credit.revenue_type', 
                'revenue_credit.ev_charge', 
                'revenue_credit.ev_revenue',
                'revenue_credit.receive_payment', 
                'revenue_credit.sms_revenue'
            )->orderBy('revenue_id', 'asc')->get();

            return response()->json([
                'data' => $elexa_received,
                'status' => 200,
            ]);
    }

    // เปลี่ยนสถานะ Lock/Unlock
    public function change_lock_unlock($id, $status)
    {
        try {
            Harmony_document_elexa::where('sms_id', $id)->update([
                'status_lock' => $status
            ]);

            $check = Harmony_document_elexa::where('sms_id', $id)->first();

            Harmony_log_elexa::create([
                'document_id' => $check->id,
                'type' => $status == 0 ? "Unlock" : "Lock",
                'changed_attributes' => "Status (Lock/Unlock) : " . $status == 0 ? "Unlock" : "Lock", // บันทึกเฉพาะฟิลด์ที่มีการเปลี่ยนแปลง
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

    // หน้า Log
    public function logs($id)
    {
        $document_elexa = Harmony_document_elexa::where('sms_id', $id)->first();

        $log_elexa = Harmony_log_elexa::where('document_id', $document_elexa->id)->get();

        $title = "Logs";

        return view('elexa_harmony.log_elexa', compact('document_elexa', 'log_elexa', 'title'));
    }

    // Generate Document No 
    public function generateDocumentNumber() {
        // ดึงปีและเดือนปัจจุบันในรูปแบบ YYMM
        $yearMonth = now()->format('y') . now()->format('m'); // ตัวอย่าง: 2411

        // ค้นหาเลขที่เอกสารล่าสุดที่ขึ้นต้นด้วย EV-ปีเดือน
        $lastDocument = Harmony_document_elexa::where('doc_no', 'LIKE', "EV-$yearMonth%")
            ->orderBy('doc_no', 'desc')->first();

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
        return "EV-$yearMonth$newNumber";
    }

    public function search_table(Request $request)
    {
        $data = [];

        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;

        if ($request->table_name == "elexaRevenueTable") {
            if (!empty($request->search_value)) {
                $query = Harmony_SMS_alerts::query()->where('status', 8)
                    ->select('sms_alert.*', 
                        DB::raw("MONTH(date) as month, SUM(amount) as total_sum, COUNT(id) as total_item"),
                        DB::raw("SUM(CASE WHEN status_receive_elexa = 1 THEN status_receive_elexa ELSE 0 END) as total_receive"))
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
                $query = Harmony_SMS_alerts::query()->where('status', 8);
    
                    if (!empty($request->year) && $request->year != 'all') {
                        $query->whereYear('date', $request->year);
                    }
                    $query->select('sms_alert.*', 
                        DB::raw("MONTH(date) as month, SUM(amount) as total_sum, COUNT(id) as total_item"),
                        DB::raw("SUM(CASE WHEN status_receive_elexa = 1 THEN status_receive_elexa ELSE 0 END) as total_receive"))
                    ->groupBy(DB::raw("MONTH(date)"));
                    
                $total_amount = $query->get()->sum('total_sum');
                $total_count = $query->get()->count();
                $data_query = $query->get();
            }
        }

        elseif ($request->table_name == "elexaOutstandingTable") {
            $month = str_pad($request->month, 2, '0', STR_PAD_LEFT);

            if (!empty($request->search_value)) {

                $query = Harmony_revenue_credit::query()->leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
                    ->where('revenue_credit.status', 8) ->where('revenue_credit.receive_payment', 0);

                    if ($request->year == 'all' && $request->month == 'all') { // ทั้งหมด
                        $query->havingRaw('revenue_credit.ev_revenue LIKE ? 
                            OR revenue_credit.batch LIKE ? 
                            OR revenue.date LIKE ?', ['%' . $request->search_value . '%', '%' . $request->search_value . '%', '%' . $request->search_value . '%']);

                    } elseif ($request->year == 'all' && !empty($request->month) && $request->month != 'all') { // เลือกเฉพาะเดือน
                        $query->havingRaw('revenue_credit.ev_revenue LIKE ? AND MONTH(revenue.date) = ? 
                            OR revenue_credit.batch LIKE ? AND MONTH(revenue.date) = ?
                            OR revenue.date LIKE ? AND MONTH(revenue.date) = ?', ['%' . $request->search_value . '%', $month, '%' . $request->search_value . '%', $month, '%' . $request->search_value . '%', $month]);
                    } elseif (!empty($request->year) && $request->year != 'all' && $request->month == 'all') { // เลือกเฉพาะปี
                        $query->havingRaw('revenue_credit.ev_revenue LIKE ? AND YEAR(revenue.date) = ? 
                            OR revenue_credit.batch LIKE ? AND YEAR(revenue.date) = ?
                            OR revenue.date LIKE ? AND YEAR(revenue.date) = ?', ['%' . $request->search_value . '%', $request->year, '%' . $request->search_value . '%', $request->year, '%' . $request->search_value . '%', $request->year]);
                    } else { // ทั้งหมดไม่ใช่ค่า All
                        $query->havingRaw('revenue_credit.ev_revenue LIKE ? AND MONTH(revenue.date) = ? AND YEAR(revenue.date) = ? 
                            OR revenue_credit.batch LIKE ? AND MONTH(revenue.date) = ? AND YEAR(revenue.date) = ?
                            OR revenue.date LIKE ? AND MONTH(revenue.date) = ? AND YEAR(revenue.date) = ?', ['%' . $request->search_value . '%', $month, $request->year, '%' . $request->search_value . '%', $month, $request->year, '%' . $request->search_value . '%', $month, $request->year]);
                    }

                $query->select('revenue_credit.id', 'revenue_credit.batch', 'revenue_credit.revenue_type', 'revenue_credit.ev_charge', 
                        'revenue_credit.receive_payment', 'revenue_credit.ev_revenue', 'revenue_credit.ev_fee', 'revenue_credit.sms_revenue', 'revenue.date')
                    ->orderBy('revenue.date', 'asc');

                $total_amount = $query->get()->sum('ev_revenue');
                $total_count = $query->get()->count();
                $data_query = $query->get();

            } else {
                $query = Harmony_revenue_credit::query()->leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
                    ->where('revenue_credit.status', 8) ->where('revenue_credit.receive_payment', 0);

                    if (!empty($request->month) && $request->month != 'all') {
                        $query->whereMonth('revenue.date', $request->month);
                    }

                    if (!empty($request->year) && $request->year != 'all') {
                        $query->whereYear('revenue.date', $request->year);
                    }

                $query->select('revenue_credit.id', 'revenue_credit.batch', 'revenue_credit.revenue_type', 'revenue_credit.ev_charge', 
                            'revenue_credit.receive_payment', 'revenue_credit.ev_revenue', 'revenue_credit.ev_fee', 'revenue_credit.sms_revenue', 'revenue.date')
                        ->orderBy('revenue.date', 'asc');

                $total_amount = $query->get()->sum('ev_revenue');
                $total_count = $query->get()->count();
                $data_query = $query->get();
            }
        }

        elseif ($request->table_name == "elexaDebitTable") {
            $month = str_pad($request->month, 2, '0', STR_PAD_LEFT);

            if (!empty($request->search_value)) {

                $query = Harmony_revenue_credit::query()->leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
                    ->where('revenue_credit.status', 8) ->where('revenue_credit.receive_payment', 1);

                    if ($request->year == 'all' && $request->month == 'all') { // ทั้งหมด
                        $query->havingRaw('revenue_credit.ev_revenue LIKE ? 
                            OR revenue_credit.batch LIKE ? 
                            OR revenue.date LIKE ?', ['%' . $request->search_value . '%', '%' . $request->search_value . '%', '%' . $request->search_value . '%']);

                    } elseif ($request->year == 'all' && !empty($request->month) && $request->month != 'all') { // เลือกเฉพาะเดือน
                        $query->havingRaw('revenue_credit.ev_revenue LIKE ? AND MONTH(revenue.date) = ? 
                            OR revenue_credit.batch LIKE ? AND MONTH(revenue.date) = ?
                            OR revenue.date LIKE ? AND MONTH(revenue.date) = ?', ['%' . $request->search_value . '%', $month, '%' . $request->search_value . '%', $month, '%' . $request->search_value . '%', $month]);
                    } elseif (!empty($request->year) && $request->year != 'all' && $request->month == 'all') { // เลือกเฉพาะปี
                        $query->havingRaw('revenue_credit.ev_revenue LIKE ? AND YEAR(revenue.date) = ? 
                            OR revenue_credit.batch LIKE ? AND YEAR(revenue.date) = ?
                            OR revenue.date LIKE ? AND YEAR(revenue.date) = ?', ['%' . $request->search_value . '%', $request->year, '%' . $request->search_value . '%', $request->year, '%' . $request->search_value . '%', $request->year]);
                    } else { // ทั้งหมดไม่ใช่ค่า All
                        $query->havingRaw('revenue_credit.ev_revenue LIKE ? AND MONTH(revenue.date) = ? AND YEAR(revenue.date) = ? 
                            OR revenue_credit.batch LIKE ? AND MONTH(revenue.date) = ? AND YEAR(revenue.date) = ?
                            OR revenue.date LIKE ? AND MONTH(revenue.date) = ? AND YEAR(revenue.date) = ?', ['%' . $request->search_value . '%', $month, $request->year, '%' . $request->search_value . '%', $month, $request->year, '%' . $request->search_value . '%', $month, $request->year]);
                    }

                $query->select('revenue_credit.id', 'revenue_credit.batch', 'revenue_credit.revenue_type', 'revenue_credit.ev_charge', 
                            'revenue_credit.receive_payment', 'revenue_credit.ev_revenue', 'revenue_credit.ev_fee', 'revenue_credit.sms_revenue', 'revenue.date')
                        ->orderBy('revenue.date', 'asc');

                $total_amount = $query->get()->sum('ev_revenue');
                $total_count = $query->get()->count();
                $data_query = $query->get();

            } else {
                $query = Harmony_revenue_credit::query()->leftjoin('revenue', 'revenue_credit.revenue_id', 'revenue.id')
                    ->where('revenue_credit.status', 8) ->where('revenue_credit.receive_payment', 1);

                    if (!empty($request->month) && $request->month != 'all') {
                        $query->whereMonth('revenue.date', $request->month);
                    }

                    if (!empty($request->year) && $request->year != 'all') {
                        $query->whereYear('revenue.date', $request->year);
                    }

                $query->select('revenue_credit.id', 'revenue_credit.batch', 'revenue_credit.revenue_type', 'revenue_credit.ev_charge', 
                            'revenue_credit.receive_payment', 'revenue_credit.ev_revenue', 'revenue_credit.ev_fee', 'revenue_credit.sms_revenue', 'revenue.date')
                        ->orderBy('revenue.date', 'asc');

                $total_amount = $query->get()->sum('ev_revenue');
                $total_count = $query->get()->count();
                $data_query = $query->get();
            }
        }

        elseif ($request->table_name == "elexaRevenueDayTable") {
            if (!empty($request->search_value)) {
                $query = Harmony_SMS_alerts::query()->where('status', 8);

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
                        $query->where('status_receive_elexa', $request->status_paid);
                    }

                    $query->where('amount', 'LIKE', '%'.$request->search_value.'%');
    
                    $total_amount = $query->get()->sum('amount');
                    $total_count = $query->get()->count();
                    $data_query = $query->get();
            } else {
                $query = Harmony_SMS_alerts::query()->where('status', 8);
    
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
                        $query->where('status_receive_elexa', $request->status_paid);
                    }
                    
                $total_amount = $query->get()->sum('amount');
                $total_count = $query->get()->count();
                $data_query = $query->get();
            }
        }

        // Elexa Revenue
        $total = $total_amount;
        $totalAllItem = 0;
        $totalAllReceive = 0;
        $totalList = $total_count;

        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {

                if ($request->table_name == "elexaRevenueTable") {
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
                        'elexa_paid' => $value->total_sum,
                        'item' => $value->total_receive."/".$value->total_item,
                        'status' => $status,
                        'btn_detail' => $btn_detail,
                    ];
                }

                elseif ($request->table_name == "elexaOutstandingTable") {
                    $status = '<span class="wrap-status-unpaid">unpaid</span>';
    
                    $data[] = [
                        'id' => $value->id,
                        'number' => $key + 1,
                        'date' => Carbon::parse($value->date)->format('d/m/Y'),
                        'orderID' => $value->batch,
                        'amount' => $value->ev_revenue,
                        'status' => $status,
                    ];
                }

                elseif ($request->table_name == "elexaDebitTable") {
                    $status = '<span class="wrap-status-paid">paid</span>';

                    $data[] = [
                        'id' => $value->id,
                        'number' => $key + 1,
                        'date' => Carbon::parse($value->date)->format('d/m/Y'),
                        'orderID' => $value->batch,
                        'amount' => $value->ev_revenue,
                        'status' => $status,
                    ];
                }

                if ($request->table_name == "elexaRevenueDayTable") {
                    $btn_detail = '';
                    $status = '';
                    $status_lock = '';

                    $month = Carbon::parse($value->date)->format('m');
                    $year = Carbon::parse($value->date)->format('Y');

                    // เข้าบัญชี
                    $into_account = '<div class=""><img class="img-bank" src="../image/bank/SCB.jpg" style="border-radius: 50%;">SCB '.$value->into_account.'</div>';

                    if ($value->status_receive_elexa == 0) 
                    {
                        $status = '<span class="wrap-status-pending">pending</span>';
                    } else  {
                        $status = '<span class="wrap-status-paid">paid</span>';
                    }

                    if (@$value->statusLockElexa->status_lock == 0) 
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
                                                if ($value->status_receive_elexa == 0)
                                                {
                                                    $btn_detail .= '<li>
                                                                        <a href="/debit-elexa-update-receive/'.$value->id.'/'.$month.'/'.$year.'" class="dropdown-item">Create</a>
                                                                    </li>';
                                                } else {
                                                    $checkReceiveDate = Harmony_revenue_credit::getElexaReceiveDate($value->id);

                                                    //  Permission 1 และ 2 สามารถเห็นปุ่ม Lock/Unlock ได้
                                                    if (Auth::user()->permission == 1 || Auth::user()->permission == 2) {
                                                        if ($value->status_receive_elexa == 0)
                                                        {
                                                            $btn_detail .= '<li><a href="javascript:void(0);" class="dropdown-item lock-item" onclick="lockItem('.$value->id.', 1)">Lock</a></li>';
                                                        } else {
                                                            $btn_detail .= '<li><a href="javascript:void(0);" class="dropdown-item unlock-item" onclick="lockItem('.$value->id.', 0)">Unlock</a></li>';
                                                        }
                                                    }

                                                    // หากต้องการแก้ไขรายการ ต้องให้ Admin Unlock ให้ก่อน **Admin ต้อง Unlock ก่อนเหมือนกัน จะสามารถแก้ไขได้
                                                    if (@$value->statusLockElexa->status_lock == 0)
                                                    {
                                                        $btn_detail .= '<li>
                                                                            <a href="/debit-elexa-update-receive/'.$value->id.'" class="dropdown-item">Edit</a>
                                                                        </li>';
                                                    }
                                                }

                                            $btn_detail .= '<li>
                                                    <a href="/debit-elexa-detail/'.$value->id.'" class="dropdown-item">View</a>
                                                </li>';

                                                if ($value->status_receive_elexa == 0)
                                                {
                                                    $btn_detail .= '<li>
                                                            <a href="/debtor-elexa-logs/'.$value->id.'" class="dropdown-item">Logs</a>
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
        $elexa_query = Harmony_revenue_credit::leftJoin('revenue', 'revenue_credit.revenue_id', '=', 'revenue.id')
            ->where('revenue_credit.status', 8)
            ->select(DB::raw('YEAR(revenue.date) as year'), DB::raw('MONTH(revenue.date) as month'), DB::raw('SUM(revenue_credit.ev_revenue) as total_ev_revenue'))
            ->groupBy('year', 'month')->orderBy('year', 'asc')->orderBy('month', 'asc')
            ->get();

            $elexa = $elexa_query->keyBy('month');
            $elexa_year = $elexa_query->keyBy('year');
            $data = [];
            
            for ($y = 2024; $y <= 2026; $y += 1) { 
                for ($i = 1; $i <= 12; $i++) { 
                    if ($elexa_year->has($y)) {
                        $data[$y][] = $elexa->has($i) ? $elexa[$i]->total_ev_revenue : 0;
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

    // Graph Monthly elexa Charge 2026
    public function graph_month_charge()
    {
        // ยอด SMS ทั้งหมด
        $sms_query = Harmony_SMS_alerts::where('status', 8)
            ->select('amount', DB::raw('YEAR(date) as year'), DB::raw('MONTH(date) as month'), DB::raw("SUM(amount) as total_sum"))
            ->groupBy('year', 'month')->orderBy('year', 'asc')->orderBy('month', 'asc')
            ->get();

        // ยอด SMS ที่กดรับชำระแล้ว สถานะเป็น paid
        $sms_paid_query = Harmony_SMS_alerts::where('status', 8)
            ->where('status_receive_elexa', 1)
            ->select('amount',  DB::raw('YEAR(date) as year'), DB::raw('MONTH(date) as month'), DB::raw("SUM(amount) as total_sum"))
            ->groupBy('year', 'month')->orderBy('year', 'asc')->orderBy('month', 'asc')
            ->get();

        // ยอด SMS ที่ยังไม่ได้กดรับชำระแล้ว สถานะเป็น pending
        $sms_pending_query = Harmony_SMS_alerts::where('status', 8)
            ->where('status_receive_elexa', 0)
            ->select('amount', DB::raw('YEAR(date) as year'), DB::raw('MONTH(date) as month'), DB::raw("SUM(amount) as total_sum"))
            ->groupBy('year', 'month')->orderBy('year', 'asc')->orderBy('month', 'asc')
            ->get();

        $elexa_outstanding = Harmony_revenue_credit::leftJoin('revenue', 'revenue_credit.revenue_id', '=', 'revenue.id')
            ->where('revenue_credit.status', 8)
            ->select(DB::raw('YEAR(revenue.date) as year'), DB::raw('MONTH(revenue.date) as month'), DB::raw('SUM(revenue_credit.ev_revenue) as total_ev_revenue'))
            ->groupBy('year', 'month')->orderBy('year', 'asc')->orderBy('month', 'asc')
            ->get();

        $elexa_outstanding_sum = Harmony_revenue_credit::leftJoin('revenue', 'revenue_credit.revenue_id', '=', 'revenue.id')
            ->where('revenue_credit.status', 8)->select('revenue_credit.ev_revenue')
            ->sum('revenue_credit.ev_revenue');

            // SMS
            $sms = $sms_query->keyBy('month');
            $sms_year = $sms_query->keyBy('year');
            $sms_paid = $sms_paid_query->keyBy('month');
            $sms_paid_year = $sms_paid_query->keyBy('year');
            $sms_pending = $sms_pending_query->keyBy('month');
            $sms_pending_year = $sms_pending_query->keyBy('year');

            // Revenue
            $elexa = $elexa_outstanding->keyBy('month');
            // $elexa_year = $elexa_outstanding->keyBy('year');

            // เก็บค่าเป็น Array
            $data_sms = [];
            $data_sms_pending = [];
            $data_outstanding = [];

            $sum = 0;
            $sms_summary = $elexa_outstanding_sum;
            
            for ($y = 2024; $y <= date('Y', strtotime('+1 year')); $y += 1) { 
                for ($i = 1; $i <= 12; $i++) { 
                    if ($sms_year->has($y) && $sms->has($i)) {
                        $data_sms[$y][] = $sms->has($i) && $sms[$i]->year == $y ? $sms[$i]->total_sum : 0;
                    } else {
                        $data_sms[$y][] = 0;
                    }

                    if ($sms_paid_year->has($y) && $sms_paid->has($i)) {
                        $data_sms_paid[$y][] = $sms_paid->has($i) && $sms_paid[$i]->year == $y ? $sms_paid[$i]->total_sum : 0;
                    } else {
                        $data_sms_paid[$y][] = 0;
                    }

                    if ($sms_pending_year->has($y) && $sms_pending->has($i)) {
                        $data_sms_pending[$y][] = $sms_pending->has($i) && $sms_pending[$i]->year == $y ? $sms_pending[$i]->total_sum : 0;
                    } else {
                        $data_sms_pending[$y][] = 0;
                    }

                    if ($sms_year->has($y)) {
                        $sms_summary -= ($sms->has($i) && $sms[$i]->year == $y ? round($sms[$i]->total_sum, 2) : 0);
                        $sms_summary = round($sms_summary, 2);

                        if ($sms_summary >= 0 && $elexa->has($i) && $elexa[$i]->year == $y && $y != date('Y') && $i != date('m') || $elexa->has($i) && $elexa[$i]->year == $y && $y != date('Y') && $i != date('m')) {
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
