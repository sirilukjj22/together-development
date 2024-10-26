<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\companys;
use App\Models\representative;
use App\Models\representative_phone;
use App\Models\company_fax;
use App\Models\company_phone;
use App\Models\document_invoices;
use App\Models\Freelancer_Member;
use App\Models\province;
use App\Models\amphures;
use App\Models\districts;
use App\Models\master_document;
use App\Models\master_product_item;
use App\Models\master_quantity;
use App\Models\master_unit;
use App\Models\log;
use App\Models\Masters;
use App\Models\receive_payment;
use App\Models\log_company;
use App\Models\document_quotation;
use Illuminate\Support\Arr;
use App\Models\master_document_sheet;
use Auth;
use App\Models\User;
use Carbon\Carbon;
use PDF;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Dompdf\Dompdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\master_template;
use Illuminate\Support\Facades\DB;
use App\Models\Master_company;
use App\Models\phone_guest;
use App\Models\Guest;
use App\Models\receive_cheque;
class ReceiveChequeController extends Controller
{
    public function index()
    {
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $invoice = document_invoices::query()->select('id','Invoice_ID','Quotation_ID')->where('Paid',0)->get();
        $data_bank = Masters::where('category', "bank")->where('status', 1)->select('id', 'name_th', 'name_en')->get();
        $cheque = receive_cheque::query()->orderBy('created_at', 'desc')->paginate($perPage);
        return view('recevie_cheque.index',compact('invoice','data_bank','cheque'));
    }
    public function save(Request $request)
    {
        $data = $request->all();
        try {
            $refer = 'อ้างอิงจาก : '.$request->Refer;
            $data_bank = Masters::where('id',$request->bank)->first();

            $bank = $data_bank->name_th.' '.'('.$data_bank->name_en.')';

            $cheque_number = 'เลขเช็ค : '.$request->chequeNumber;

            $amount = 'ยอดเงิน : ' . number_format($request->Amount).' บาท';

            $receive_date = 'วันที่รับ : '.$request->receive_date;

            $issue_date = 'วันที่ตีเช็ค : '.$request->Issue_Date;

            $datacompany = '';

            $variables = [$refer, $bank, $cheque_number, $amount, $receive_date,$issue_date];

            // แปลง array ของ $formattedProductData เป็น string เดียวที่มีรายการทั้งหมด


            foreach ($variables as $variable) {
                if (!empty($variable)) {
                    if (!empty($datacompany)) {
                        $datacompany .= ' + ';
                    }
                    $datacompany .= $variable;
                }
            }

            $userid = Auth::user()->id;
            $save = new log_company();
            $save->Created_by = $userid;
            $save->Company_ID = $request->chequeNumber;
            $save->type = 'Create';
            $save->Category = 'Create :: Recevie Cheque';
            $save->content =$datacompany;
            $save->save();
        } catch (\Throwable $e) {
            return redirect()->route('ReceiveCheque.index')->with('error', $e->getMessage());
        }
        try {
            $userid = Auth::user()->id;
            $invoice = $request->Refer;
            $proposal = document_invoices::where('Invoice_ID',$invoice)->first();
            $Quotation_ID = $proposal->Quotation_ID;
            $save = new receive_cheque();
            $save->refer_invoice = $invoice;
            $save->refer_proposal = $Quotation_ID;
            $save->bank_cheque = $request->bank;
            $save->bank_received = $request->received;
            $save->cheque_number = $request->chequeNumber;
            $save->amount = $request->Amount;
            $save->receive_date = $request->receive_date;
            $save->issue_date = $request->Issue_Date;
            $save->Operated_by = $userid;
            $save->save();
            return redirect()->route('ReceiveCheque.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
        } catch (\Throwable $e) {
            return redirect()->route('ReceiveCheque.index')->with('error', $e->getMessage());
        }
    }
    public function view($id){
        $view = receive_cheque::where('id',$id)->first();
        $invoice = $view->refer_invoice;
        $proposal = $view->refer_proposal;
        $bank_cheque = $view->bank_cheque;
        $data_bank = Masters::where('id',$bank_cheque)->first();
        $bank = $data_bank->name_th.' '.'('.$data_bank->name_en.')';
        $bank_receiveds = $view->bank_received;
        if ($bank_receiveds) {
            $data_received = Masters::where('id',$bank_receiveds)->first();
            $bankreceived = $data_received->name_th.' '.'('.$data_received->name_en.')';
        }else {
            $bankreceived =null;
        }
        $cheque_number = $view->cheque_number;
        $amount = $view->amount;
        $receive_date = $view->receive_date;
        $issue_date = $view->issue_date;

        return response()->json([
            'invoice'=>$invoice,
            'proposal'=>$proposal,
            'bank_cheque'=>$bank,
            'bank_received'=>$bankreceived,
            'cheque_number'=>$cheque_number,
            'amount' => $amount,
            'receive_date' => $receive_date,
            'issue_date'=>$issue_date,
        ]);
    }
    public function edit($id){
        $view = receive_cheque::where('id',$id)->first();
        $invoice = $view->refer_invoice;
        $proposal = $view->refer_proposal;
        $bank_cheque = $view->bank_cheque;
        $bank_receiveds = $view->bank_received;
        $cheque_number = $view->cheque_number;
        $amount = $view->amount;
        $receive_date = $view->receive_date;
        $issue_date = $view->issue_date;

        return response()->json([
            'invoice'=>$invoice,
            'proposal'=>$proposal,
            'bank_cheque'=>$bank_cheque,
            'bank_received'=>$bank_receiveds,
            'cheque_number'=>$cheque_number,
            'amount' => $amount,
            'receive_date' => $receive_date,
            'issue_date'=>$issue_date,
        ]);
    }
    public function amount($id){
        $invoice = document_invoices::query()->where('Invoice_ID',$id)->first();
        $Amount = $invoice->sumpayment;
        return response()->json([
            'Amount'=>$Amount,
        ]);
    }
    public function update(Request $request){
        $data = $request->all();


        $datacheque = receive_cheque::where('refer_invoice',$request->Refer)->first();
        $dataArray = [
            'Refer' => $datacheque['refer_invoice'] ?? null,
            'bank'=>$datacheque['bank_cheque'] ?? null,
            'chequeNumber'=>$datacheque['cheque_number'] ?? null,
            'Amount'=>$datacheque['amount'] ?? null,
            'receive_date'=>$datacheque['receive_date'] ?? null,
            'Issue_Date'=>$datacheque['issue_date'] ?? null,

        ];
        $keysToCompare = ['Refer', 'bank', 'chequeNumber','Amount','receive_date','Issue_Date'];
        $differences = [];
        foreach ($keysToCompare as $key) {
            if (isset($dataArray[$key]) && isset($data[$key])) {
                // แปลงค่าของ $dataArray และ $data เป็นชุดข้อมูลเพื่อหาค่าที่แตกต่างกัน
                $dataArraySet = collect($dataArray[$key]);
                $dataSet = collect($data[$key]);

                // หาค่าที่แตกต่างกัน
                $onlyInDataArray = $dataArraySet->diff($dataSet)->values()->all();
                $onlyInRequest = $dataSet->diff($dataArraySet)->values()->all();

                // ตรวจสอบว่ามีค่าที่แตกต่างหรือไม่
                if (!empty($onlyInDataArray) || !empty($onlyInRequest)) {
                    $differences[$key] = [
                        'dataArray' => $onlyInDataArray,
                        'request' => $onlyInRequest
                    ];
                }
            }
        }
        $extractedData = [];

        // วนลูปเพื่อดึงชื่อคีย์และค่าจาก request
        foreach ($differences as $key => $value) {
            if ($key === 'phone'||$key === 'fax') {
                // ถ้าเป็น phoneCom ให้เก็บค่า request ทั้งหมดใน array
                $extractedData[$key] = $value['request'];
                $extractedDataA[$key] = $value['dataArray'];
            } elseif (isset($value['request'][0])) {
                // สำหรับคีย์อื่นๆ ให้เก็บค่าแรกจาก array
                $extractedData[$key] = $value['request'][0];
            }else{
                $extractedDataA[$key] = $value['dataArray'][0];
            }

        }

        $Refer = $extractedData['Refer'] ?? null;
        $Bank = $extractedData['bank'] ?? null;
        $chequeNumber =  $extractedData['chequeNumber'] ?? null;
        $Amount =  $extractedData['Amount'] ?? null;
        $Receive_date =  $extractedData['receive_date'] ?? null;
        $Issue_Date =  $extractedData['Issue_Date'] ?? null;
        try {
            $refer = null;
            if ($Refer) {
                $refer = 'อ้างอิงจาก : '.$Refer;
            }
            $bank = null;
            if ($Bank) {
                $data_bank = Masters::where('id',$Bank)->first();

                $bank = $data_bank->name_th.' '.'('.$data_bank->name_en.')';
            }

            $cheque_number = null;
            if ($chequeNumber) {
                $cheque_number = 'เลขเช็ค : '.$chequeNumber;
            }

            $amount = null;
            if ($Amount) {
                $amount = 'ยอดเงิน : ' . number_format($Amount).' บาท';
            }

            $receive_date = null;
            if ($Receive_date) {
                $receive_date = 'วันที่รับ : '.$Receive_date;
            }

            $issue_date = null;
            if ($Issue_Date) {
                $issue_date = 'วันที่ตีเช็ค : '.$Issue_Date;
            }

            $datacompany = '';

            $variables = [$refer, $bank, $cheque_number, $amount, $receive_date,$issue_date];

            // แปลง array ของ $formattedProductData เป็น string เดียวที่มีรายการทั้งหมด


            foreach ($variables as $variable) {
                if (!empty($variable)) {
                    if (!empty($datacompany)) {
                        $datacompany .= ' + ';
                    }
                    $datacompany .= $variable;
                }
            }
            $userid = Auth::user()->id;
            $save = new log_company();
            $save->Created_by = $userid;
            $save->Company_ID = $request->chequeNumber;
            $save->type = 'Edit';
            $save->Category = 'Edit :: Recevie Cheque';
            $save->content =$datacompany;
            $save->save();
        } catch (\Throwable $e) {
            return redirect()->route('ReceiveCheque.index')->with('error', $e->getMessage());
        }
        try {
            $invoice = $request->Refer;
            $proposal = document_invoices::where('Invoice_ID',$invoice)->first();
            $Quotation_ID = $proposal->Quotation_ID;
            $save = receive_cheque::find($request->ids);
            $save->refer_invoice = $invoice;
            $save->refer_proposal = $Quotation_ID;
            $save->bank_cheque = $request->bank;
            $save->bank_received = $request->received;
            $save->cheque_number = $request->chequeNumber;
            $save->amount = $request->Amount;
            $save->receive_date = $request->receive_date;
            $save->issue_date = $request->Issue_Date;
            $save->Operated_by = $userid;
            $save->save();
            return redirect()->route('ReceiveCheque.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
        } catch (\Throwable $e) {
            return redirect()->route('ReceiveCheque.index')->with('error', $e->getMessage());
        }
    }
    public function Approved($id){
        $cheque = receive_cheque::where('id',$id)->first();
        $cheque->status = 1;
        $cheque->save();
        $chequeNumber = $cheque->cheque_number;
        $userid = Auth::user()->id;
        $save = new log_company();
        $save->Created_by = $userid;
        $save->Company_ID = $chequeNumber;
        $save->type = 'Approved';
        $save->Category = 'Approved :: Receive Cheque';
        $save->content = 'Approved Receive Cheque Number: '.$chequeNumber;
        $save->save();
    }

    public function search_table_cheque(Request $request){
        $perPage = (int)$request->perPage;
        $search_value = $request->search_value;
        $guest_profile = $request->guest_profile;
        $userid = Auth::user()->id;
        $permissionid = Auth::user()->permission;
        if ($search_value) {
            $data_query = receive_cheque::where('refer_invoice', 'LIKE', '%'.$search_value.'%')
            ->orWhere('refer_proposal', 'LIKE', '%'.$search_value.'%')
            ->orWhere('cheque_number', 'LIKE', '%'.$search_value.'%')
            ->orWhere('amount', 'LIKE', '%'.$search_value.'%')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
        } else {
            $perPageS = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
            $data_query = receive_cheque::query()->orderBy('created_at', 'desc')->paginate($perPageS);
        }


        $page_1 = $request->page == 1 ? 1 : ($request->page - 1).'1';
        $page_2 = $request->page.'0';

        $perPage2 = $request->perPage > 10 ? $request->perPage : 10;
        $data = [];
        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $btn_action = "";
                $btn_status = "";
                $name ="";
                $checkbox = "";

                if ($value->status == 0) {
                    $btn_status = '<span class="badge rounded-pill " style="background-color: #FF6633">Pending</span>';
                } elseif ($value->status == 1) {
                    $btn_status = '<span class="badge rounded-pill bg-success">Approved</span>';
                } elseif ($value->status == 2) {
                    $btn_status = '<span class="badge rounded-pill "style="background-color: #0ea5e9">Generate</span>';
                }

                $rolePermission = Auth::user()->rolePermissionData(Auth::user()->id);
                $canViewProposal = Auth::user()->roleMenuView('Proposal', Auth::user()->id);
                $canEditProposal = Auth::user()->roleMenuEdit('Proposal', Auth::user()->id);
                $CreateBy = Auth::user()->id;
                $isOperatedByCreator = $value->Operated_by == $CreateBy;

                $btn_action = '<div class="dropdown">';
                $btn_action .= '<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>';
                $btn_action .= '<ul class="dropdown-menu border-0 shadow p-3">';
                if ($value->status == 1 || $value->status == 2) {
                    if ($canViewProposal) {
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="view(' . $value->id . ')">View</a></li>';
                    }
                }else{
                    if ($value->status == 0) {
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="view(' . $value->id . ')">View</a></li>';
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="edit(' . $value->id . ')">Edit</a></li>';
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Approved(' . $value->id . ')">Approved</a></li>';
                    }
                }
                $btn_action .= '</ul>';
                $btn_action .= '</div>';

                $data[] = [
                    'number' => $key + 1,
                    'Invoice' => $value->refer_invoice,
                    'proposal' => $value->refer_proposal,
                    'Bank' => @$value->bank->name_th.' '.(@$value->bank->name_en),
                    'Cheque_Number' => $value->cheque_number,
                    'Amount' => number_format($value->amount),
                    'Receive_Date' => $value->receive_date,
                    'Issue_Date' => $value->issue_date,
                    'Operated' => @$value->userOperated->name,
                    'status' => $btn_status,
                    'btn_action' => $btn_action,
                ];
            }
        }
        return response()->json([
            'data' => $data,
        ]);
    }
    public function paginate_table_cheque(Request $request){
        $perPage = (int)$request->perPage;
        $userid = Auth::user()->id;
        $data = [];
        $permissionid = Auth::user()->permission;
        if ($perPage == 10) {
            $data_query = receive_cheque::query()->orderBy('created_at', 'desc')
            ->limit($request->page.'0')
            ->get();
        } else {
            $data_query = receive_cheque::query()->orderBy('created_at', 'desc')->paginate($perPage);
        }


        $page_1 = $request->page == 1 ? 1 : ($request->page - 1).'1';
        $page_2 = $request->page.'0';

        $perPage2 = $request->perPage > 10 ? $request->perPage : 10;

        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $btn_action = "";
                $btn_status = "";
                $name ="";
                $checkbox = "";
                // สร้าง dropdown สำหรับการทำรายการ
                if (($key + 1) >= (int)$page_1 && ($key + 1) <= (int)$page_2 || (int)$perPage > 10 && $key < (int)$perPage2) {

                    if ($value->status == 0) {
                        $btn_status = '<span class="badge rounded-pill " style="background-color: #FF6633">Pending</span>';
                    } elseif ($value->status == 1) {
                        $btn_status = '<span class="badge rounded-pill bg-success">Approved</span>';
                    } elseif ($value->status == 2) {
                        $btn_status = '<span class="badge rounded-pill "style="background-color: #0ea5e9">Generate</span>';
                    }

                    $rolePermission = Auth::user()->rolePermissionData(Auth::user()->id);
                    $canViewProposal = Auth::user()->roleMenuView('Proposal', Auth::user()->id);
                    $canEditProposal = Auth::user()->roleMenuEdit('Proposal', Auth::user()->id);
                    $CreateBy = Auth::user()->id;
                    $isOperatedByCreator = $value->Operated_by == $CreateBy;

                    $btn_action = '<div class="dropdown">';
                    $btn_action .= '<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>';
                    $btn_action .= '<ul class="dropdown-menu border-0 shadow p-3">';
                    if ($value->status == 1 || $value->status == 2) {
                        if ($canViewProposal) {
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="view(' . $value->id . ')">View</a></li>';
                        }
                    }else{
                        if ($value->status == 0) {
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="view(' . $value->id . ')">View</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="edit(' . $value->id . ')">Edit</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Approved(' . $value->id . ')">Approved</a></li>';
                        }
                    }
                    $btn_action .= '</ul>';
                    $btn_action .= '</div>';

                    $data[] = [
                        'number' => $key + 1,
                        'Invoice' => $value->refer_invoice,
                        'proposal' => $value->refer_proposal,
                        'Bank' => @$value->bank->name_th.' '.(@$value->bank->name_en),
                        'Cheque_Number' => $value->cheque_number,
                        'Amount' => number_format($value->amount),
                        'Receive_Date' => $value->receive_date,
                        'Issue_Date' => $value->issue_date,
                        'Operated' => @$value->userOperated->name,
                        'status' => $btn_status,
                        'btn_action' => $btn_action,
                    ];
                }
            }
        }
        // dd($data);
        return response()->json([
            'data' => $data,
        ]);
    }
}
