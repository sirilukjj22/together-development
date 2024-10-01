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
        $invoice = document_invoices::query()->select('id','Invoice_ID','Quotation_ID')->get();
        $data_bank = Masters::where('category', "bank")->where('status', 1)->select('id', 'name_th', 'name_en')->get();
        $cheque = receive_cheque::query()->paginate($perPage);
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

            $amount = 'ยอดเงิน : ' . number_format($request->Amount);

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
        } catch (\Throwable $th) {
            return redirect()->route('ReceiveCheque.index')->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
        try {
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
            $save->save();
            return redirect()->route('ReceiveCheque.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
        } catch (\Throwable $th) {
            return redirect()->route('ReceiveCheque.index')->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
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
}
