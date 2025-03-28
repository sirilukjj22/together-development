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
        $invoice = Quotation::query()->select('id','Quotation_ID')->where('status_document', 6)->get();
        $data_bank = Masters::where('category', "bank")->where('status', 1)->select('id', 'name_th', 'name_en')->get();
        $cheque = receive_cheque::query()->orderBy('created_at', 'desc')->get();
        $chequepaind = receive_cheque::query()->where('status', 1)->get();
        $chequeDedected = receive_cheque::query()->where('status', 2)->get();
        $chequeBounced = receive_cheque::query()->where('status', 0)->get();
        $date = Carbon::now();
        return view('recevie_cheque.index',compact('invoice','data_bank','cheque','chequepaind','chequeDedected','chequeBounced','date'));
    }
    public function NumberID(Request $request){
        $currentDate = Carbon::now();
        $ID = 'CQR-';
        $formattedDate = Carbon::parse($currentDate);       // วันที่
        $month = $formattedDate->format('m'); // เดือน
        $year = $formattedDate->format('y');
        $lastRun = receive_cheque::latest()->first();
        $nextNumber = 1;

        if ($lastRun == null) {
            $nextNumber = $lastRun + 1;

        }else{
            $lastRunid = $lastRun->id;
            $nextNumber = $lastRunid + 1;
        }
        $newRunNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        $NumberID = $ID.$year.$month.$newRunNumber;

        return response()->json([
            'Cheque'=>$NumberID,
        ]);
    }
    public function save(Request $request)
    {
        $date = Carbon::now();
        $formattedDate = Carbon::parse($date)->format('d/m/Y');
        try {
            $refer = 'อ้างอิงจาก : '.$request->Refer;
            $data_bank = Masters::where('id',$request->bank)->first();

            $bank = $data_bank->name_th.' '.'('.$data_bank->name_en.')';

            $cheque_number = 'เลขเช็ค : '.$request->chequeNumber;

            $amount = 'ยอดเงิน : ' . number_format($request->Amount).' บาท';

            $branch = 'สาขาธนาคาร : '.$request->branch;

            $issue_date = 'วันที่สร้างเช็ค : '.$request->formattedDate;

            $datacompany = '';

            $variables = [$refer, $bank, $cheque_number, $amount, $branch,$issue_date];

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
            $save = new receive_cheque();
            $save->Cheque_ID = $request->Cheque_ID;
            $save->refer_proposal = $request->Refer;
            $save->bank_cheque = $request->bank;
            $save->cheque_number = $request->chequeNumber;
            $save->amount = $request->Amount;
            $save->branch = $request->branch;
            $save->issue_date = $formattedDate;
            $save->Operated_by = $userid;
            $save->save();
            return redirect()->route('ReceiveCheque.index')->with('success', 'Data has been successfully saved.');
        } catch (\Throwable $e) {
            return redirect()->route('ReceiveCheque.index')->with('error', $e->getMessage());
        }
    }
    public function view($id){
        $view = receive_cheque::where('id',$id)->first();
        $Cheque_ID = $view->Cheque_ID;
        $invoice = $view->Refer;
        $proposal = $view->refer_proposal;
        $bank_cheque = $view->bank_cheque;
        $data_bank = Masters::where('id',$bank_cheque)->first();
        $bank = $data_bank->name_th.' '.'('.$data_bank->name_en.')';
        $receive_payment = $view->receive_payment;

        $cheque_number = $view->cheque_number;
        $amount = $view->amount;
        $deduct_date = $view->deduct_date;
        $issue_date = $view->issue_date;
        $branch = $view->branch;
        return response()->json([
            'invoice'=>$invoice,
            'proposal'=>$proposal,
            'bank_cheque'=>$bank,
            'receive_payment'=>$receive_payment,
            'cheque_number'=>$cheque_number,
            'amount' => $amount,
            'deduct_date' => $deduct_date,
            'issue_date'=>$issue_date,
            'Cheque_ID'=>$Cheque_ID,
            'branch'=>$branch,

        ]);
    }
    public function edit($id){
        $view = receive_cheque::where('id',$id)->first();
        $Cheque_ID = $view->Cheque_ID;
        $proposal = $view->refer_proposal;
        $bank_cheque = $view->bank_cheque;
        $bank_receiveds = $view->bank_received;
        $cheque_number = $view->cheque_number;
        $amount = $view->amount;
        $receive_date = $view->receive_date;
        $issue_date = $view->issue_date;
        $branch = $view->branch;
        return response()->json([
            'proposal'=>$proposal,
            'bank_cheque'=>$bank_cheque,
            'bank_received'=>$bank_receiveds,
            'cheque_number'=>$cheque_number,
            'amount' => $amount,
            'branch' => $branch,
            'issue_date'=>$issue_date,
            'Cheque_ID'=>$Cheque_ID,
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


        $datacheque = receive_cheque::where('refer_proposal',$request->Refer)->first();
        $dataArray = [
            'Refer' => $datacheque['refer_proposal'] ?? null,
            'bank'=>$datacheque['bank_cheque'] ?? null,
            'chequeNumber'=>$datacheque['cheque_number'] ?? null,
            'Amount'=>$datacheque['amount'] ?? null,
            'branch'=>$datacheque['branch'] ?? null,
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
        $branch =  $extractedData['branch'] ?? null;
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

            $Branch = null;
            if ($branch) {
                $Branch = 'สาขาธนาคาร : '.$branch;
            }

            $datacompany = '';

            $variables = [$refer, $bank,$Branch, $cheque_number, $amount];

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
            $save = receive_cheque::find($request->ids);
            $save->refer_proposal = $request->Refer;
            $save->bank_cheque = $request->bank;
            $save->cheque_number = $request->chequeNumber;
            $save->amount = $request->Amount;
            $save->branch = $request->branch;
            $save->Operated_by = $userid;
            $save->save();
            return redirect()->route('ReceiveCheque.index')->with('success', 'Data has been successfully saved.');
        } catch (\Throwable $e) {
            return redirect()->route('ReceiveCheque.index')->with('error', $e->getMessage());
        }
    }
    public function Approved($id){
        $cheque = receive_cheque::where('id',$id)->first();
        $cheque->status = 0;
        $cheque->save();
        $chequeNumber = $cheque->cheque_number;
        $userid = Auth::user()->id;
        $save = new log_company();
        $save->Created_by = $userid;
        $save->Company_ID = $chequeNumber;
        $save->type = 'Bounced Cheque';
        $save->Category = 'Bounced Cheque :: Cheque';
        $save->content = 'Bounced Cheque  Cheque Number: '.$chequeNumber;
        $save->save();
    }
}
