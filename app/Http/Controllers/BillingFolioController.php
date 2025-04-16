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
use App\Models\document_receive_item;
use App\Models\log_company;
use App\Models\document_quotation;
use App\Models\company_tax;
use App\Models\company_tax_phone;
use App\Models\guest_tax_phone;
use App\Models\guest_tax;
use Illuminate\Support\Arr;
use App\Models\master_document_sheet;
use App\Models\proposal_overbill;
use App\Models\document_proposal_overbill;
use App\Models\Master_additional;
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
use App\Models\master_payment_and_complimentary;
use App\Models\document_deposit_revenue;
use App\Models\depositrevenue;
class BillingFolioController extends Controller
{
    public function index()
    {
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $userid = Auth::user()->id;
        $Approved = receive_payment::query()->WhereIn('document_status',[1,2])->get();
        $ApprovedCount = receive_payment::query()->WhereIn('document_status',[1,2])->count();
        $ComplateCount = Quotation::query()->where('quotation.status_document', 9)->count();
        $Complate = Quotation::query()
        ->leftJoin('document_receive', 'quotation.Quotation_ID', '=', 'document_receive.Quotation_ID')
        ->where('quotation.status_document', 9)
        ->select(
            'quotation.*',
            DB::raw('document_receive.fullname as fullname'),
            DB::raw('COUNT(document_receive.Quotation_ID) as receive_count'),
            DB::raw('SUM(document_receive.document_amount) as document_amount')
        )
        ->groupBy('quotation.Quotation_ID', 'quotation.status_document', 'quotation.status_receive')
        ->get();
        $create = Quotation::query()
        ->leftJoinSub(
            DB::table('document_invoice')
                ->select('Quotation_ID', DB::raw('COUNT(CASE WHEN document_status = 1 THEN 1 END) as invoice_count'))
                ->groupBy('Quotation_ID'),
            'document_invoice',
            'quotation.Quotation_ID',
            '=',
            'document_invoice.Quotation_ID'
        )
        ->leftJoinSub(
            DB::table('deposit_revenue')
                ->select('Quotation_ID',
                    DB::raw('COUNT(Quotation_ID) as receiveinvoice_count'),
                    DB::raw('COALESCE(SUM(amount), 0) as receiveinvoice_amount')
                )
                ->where('document_status', 1)
                ->groupBy('Quotation_ID'),
            'deposit_revenue',
            'quotation.Quotation_ID',
            '=',
            'deposit_revenue.Quotation_ID'
        )
        ->leftJoinSub(
            DB::table('proposal_overbill')
                ->select('Quotation_ID', 'Nettotal')
                ->where('status_document', 3)
                ->where('status_guest', 0)
                ->groupBy('Quotation_ID', 'Nettotal'),
            'proposal_overbill',
            'quotation.Quotation_ID',
            '=',
            'proposal_overbill.Quotation_ID'
        )
        ->leftJoin('document_receive', 'quotation.Quotation_ID', '=', 'document_receive.Quotation_ID')

        // ✅ เงื่อนไขให้แสดงเมื่อมีค่าใดค่าหนึ่ง
        ->where(function ($query) {
            $query->whereNotNull('document_invoice.invoice_count')
                ->orWhereNotNull('deposit_revenue.receiveinvoice_count')
                ->orWhereNotNull('proposal_overbill.Quotation_ID');
        })
        // ->whereNotExists(function ($query) {
        //     $query->select(DB::raw(1))
        //         ->from('proposal_overbill')
        //         ->whereColumn('proposal_overbill.Quotation_ID', 'quotation.Quotation_ID')
        //         ->where('proposal_overbill.status_document', 2); // ไม่แสดง status_document = 2
        // })
        ->where('quotation.status_document', 6)
        ->select(
            'quotation.*',
            'proposal_overbill.Nettotal as Adtotal',
            'document_invoice.invoice_count',
            'document_receive.fullname',
            DB::raw('COUNT(document_receive.Quotation_ID) as receive_count'),
            DB::raw('COALESCE(SUM(document_receive.document_amount), 0) as receive_amount')
        )
        ->groupBy('quotation.Quotation_ID', 'proposal_overbill.Nettotal', 'document_receive.fullname', 'document_invoice.invoice_count')
        ->get();



        $ProposalCount = Quotation::query()
        ->leftJoin('document_invoice', 'quotation.Quotation_ID', '=', 'document_invoice.Quotation_ID')
        ->leftJoin('document_receive', 'quotation.Quotation_ID', '=', 'document_receive.Quotation_ID')
        ->leftJoin('proposal_overbill', 'quotation.Quotation_ID', '=', 'proposal_overbill.Quotation_ID')
        ->where('document_invoice.document_status', 2)
        ->where('quotation.status_document', 6)
        ->select(
            'quotation.*',
            'proposal_overbill.Nettotal as Adtotal',
            DB::raw('document_receive.fullname as fullname'),
            DB::raw('COUNT(document_receive.Quotation_ID) as receive_count'),
            DB::raw('SUM(document_receive.document_amount) as receive_amount'),
            DB::raw('COUNT(CASE WHEN document_invoice.document_status = 2 THEN document_invoice.Quotation_ID END) as invoice_count')

        )
        ->groupBy('quotation.Quotation_ID')
        ->count();
        return view('billingfolio.index',compact('Approved','Complate','ComplateCount','ApprovedCount','ProposalCount','create'));
    }
    //---------------------------------table-----------------
    public function createmulti($id)
    {
        $invoices = document_invoices::where('id', $id)->first();
        $ids = $invoices->id;
        $Invoice_ID = $invoices->Invoice_ID;
        $proposalid = $invoices->Quotation_ID;
        $Payment = $invoices->payment;
        $companyid = $invoices->company;
        $valid = $invoices->created_at;
        $IssueDate = $invoices->IssueDate;
        $Expiration = $invoices->Expiration;
        $sumpayment = $invoices->sumpayment;
        $Payment = $invoices->payment;
        $amountproposal = $invoices->sumpayment;
        $parts = explode('-', $companyid);
        $firstPart = $parts[0];
        if ($firstPart == 'C') {
            $Selectdata =  'Company';
            $company =  companys::where('Profile_ID',$companyid)->first();
            if ($company) {
                $Address=$company->Address;
                $CityID=$company->City;
                $amphuresID = $company->Amphures;
                $TambonID = $company->Tambon;
                $Identification = $company->Taxpayer_Identification;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = company_phone::where('Profile_ID',$company->Profile_ID)->where('Sequence','main')->first();
                $Email = $company->Company_Email;
                $Company_typeID=$company->Company_type;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="บริษัทจำกัด") {
                    $fullName = "บริษัท ". $company->Company_Name . " จำกัด";
                }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                    $fullName = "บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                    $fullName = "ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                }else{
                    $fullName = $comtype->name_th . $company->Company_Name;
                }
                if ($comtype->name_th =="บริษัทจำกัด") {
                    $name = "บริษัท ". $company->Company_Name . " จำกัด";
                }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                    $name = "บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                    $name = "ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                }else{
                    $name = $comtype->name_th . $company->Company_Name;
                }
                $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
                $name_ID = $company->Profile_ID;
                $datasub = company_tax::where('Company_ID',$name_ID)->get();
            }
        }else{
            $guestdata =  Guest::where('Profile_ID',$companyid)->first();
            if ($guestdata) {
                $Selectdata =  'Guest';
                $Company_typeID=$guestdata->preface;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="นาย") {
                    $fullName = "นาย ". $guestdata->first_name . ' ' . $guestdata->last_name;
                }elseif ($comtype->name_th =="นาง") {
                    $fullName = "นาง ". $guestdata->first_name . ' ' . $guestdata->last_name;
                }elseif ($comtype->name_th =="นางสาว") {
                    $fullName = "นางสาว ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                }else{
                    $fullName = "คุณ ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                }
                $name =  'คุณ '.$guestdata->First_name.' '.$guestdata->Last_name;
                $Address=$guestdata->Address;
                $CityID=$guestdata->City;
                $amphuresID = $guestdata->Amphures;
                $TambonID = $guestdata->Tambon;
                $Identification = $guestdata->Identification_Number;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = phone_guest::where('Profile_ID',$guestdata->Profile_ID)->where('Sequence','main')->first();
                $Email = $guestdata->Company_Email;
                $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
                $name_ID = $guestdata->Profile_ID;
                $datasub = guest_tax::where('Company_ID',$name_ID)->get();
            }
        }
        $datamain[0] = [
            'id' => $name_ID ?? null,
            'name' => $name ?? null,
        ];

        $names = []; // สร้าง array เพื่อเก็บค่าชื่อ

        if ($datasub) {
            foreach ($datasub as $key => $item) {
                $comtype = DB::table('master_documents')->where('id', $item->Company_type)->first();

                if ($comtype) { // ตรวจสอบว่า $comtype ไม่เป็น null
                    if ($firstPart == 'C') {
                        if ($comtype->name_th == "บริษัทจำกัด") {
                            $name = "บริษัท " . $item->Companny_name . " จำกัด";
                        } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                            $name = "บริษัท " . $item->Companny_name . " จำกัด (มหาชน)";
                        } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                            $name = "ห้างหุ้นส่วนจำกัด " . $item->Companny_name;
                        } else {
                            $name = $comtype->name_th . ($item->Companny_name ?? ($item->first_name . " " . $item->last_name));
                        }
                        $name_id = $item->ComTax_ID;
                    } else {
                        if ($comtype->name_th == "บริษัทจำกัด") {
                            $name = "บริษัท " . $item->Company_name . " จำกัด";
                        } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                            $name = "บริษัท " . $item->Company_name . " จำกัด (มหาชน)";
                        } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                            $name = "ห้างหุ้นส่วนจำกัด " . $item->Company_name;
                        } else {
                            $name = $comtype->name_th . ($item->Company_name ?? ($item->first_name . " " . $item->last_name));
                        }
                        $name_id = $item->GuestTax_ID;
                    }

                    // เก็บค่า $name ลงใน array
                    $names[$key + 1] = [
                        'id' => $name_id ?? null,
                        'name' => $name ?? null,
                    ];
                }
            }
        }
        $data_select = array_merge($datamain, $names);
        $Proposal = Quotation::where('Quotation_ID',$proposalid)->first();
        $Deposit_ID = $invoices->Deposit_ID;
        $array = array_map('trim', explode(',', $Deposit_ID));
        $DepositID = depositrevenue::whereIn('Deposit_ID', $array)->get();
        $currentDate = Carbon::now();
        $ID = 'RE-';
        $formattedDate = Carbon::parse($currentDate);       // วันที่
        $month = $formattedDate->format('m'); // เดือน
        $year = $formattedDate->format('y');
        $lastRun = receive_payment::latest()->first();
        $nextNumber = 1;

        if ($lastRun == null) {
            $nextNumber = $lastRun + 1;

        }else{
            $lastRunid = $lastRun->id;
            $nextNumber = $lastRunid + 1;
        }
        $newRunNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        $REID = $ID.$year.$month.$newRunNumber;
        $Additional = proposal_overbill::where('Quotation_ID',$proposalid)->where('status_guest',0)->first();
        $additional_type= "";
        $additional_Nettotal= 0;
        $Cash= 0;
        $Complimentary= 0;
        if ($Additional) {
            $additional_type = $Additional->additional_type;
            $additional_Nettotal = $Additional->Nettotal;
            if ($additional_type == 'Cash') {
                $Cash = $additional_Nettotal*0.37;
                $Complimentary = $additional_Nettotal-$additional_Nettotal*0.37	;
            }elseif ($additional_type == 'Cash Manual') {
                $Cash = $Additional->Cash;
                $Complimentary = $Additional->Complimentary	;
            }else{
                $Cash = $additional_Nettotal;
                $Complimentary = 0	;
            }
        }
        $settingCompany = Master_company::orderBy('id', 'desc')->first();

        $type = $Proposal->type_Proposal;
        $vat_type = $Proposal->vat_type;
        $data_bank = Masters::where('category', "bank")->where('status', 1)->select('id', 'name_th', 'name_en')->get();
        $data_cheque =receive_cheque::where('refer_proposal',$proposalid)->where('status',1)->get();
        return view('billingfolio.invoice.createmulti',compact('Invoice_ID','Selectdata','address','Identification','fullName','phone','Email','valid','Proposal','Payment','sumpayment','amountproposal'
        ,'DepositID','REID','Invoice_ID','settingCompany','additional_type','additional_Nettotal','Cash','Complimentary','datasub','name_ID','name','type','vat_type','IssueDate','Expiration'
        ,'data_bank','data_cheque','ids','data_select'));
    }
    public function spiltebill(Request $request ,$id){
        $idss= $id;
        $payments = json_decode($request->input('paymentsData'), true);
        foreach ($payments as &$payment) { // ใช้ & เพื่อให้อัปเดตค่าได้
            if ($payment['type'] === 'cheque') {
                $chequeNumber = $payment['cheque'] ?? null;
                $chequeRe = receive_cheque::where('cheque_number', $chequeNumber)->first();

                if ($chequeRe) { // ป้องกันกรณีไม่พบข้อมูล
                    $payment['amount'] = $chequeRe->amount;
                }
            }
        }
        unset($payment);
        if ($payments == null) {
            return redirect()->route('BillingFolio.createmulti', ['id' => $id])->with('error', 'Please fill the amount on the list.');
        }
        $paymentsDataArray = json_decode($request->input('paymentsDataArray'), true);
        foreach ($paymentsDataArray as &$payment) { // ใช้ & เพื่อให้อัปเดตค่าได้
            if ($payment['type'] === 'cheque') {
                $chequeNumber = $payment['cheque'] ?? null;
                $chequeRe = receive_cheque::where('cheque_number', $chequeNumber)->first();

                if ($chequeRe) { // ป้องกันกรณีไม่พบข้อมูล
                    $payment['amount'] = $chequeRe->amount;
                }
            }
        }
        unset($payment);
        $cashAmount = array_reduce($paymentsDataArray, function ($carry, $item) {
            return ($item['type'] === 'cash' || $item['type'] === 'Complimentary') ? $carry + $item['amount'] : $carry;
        }, 0);

        $additional_type = $request->additional_type;
        $invoice = $request->invoice;
        $additional_amount = $request->additional ?? 0;
        $paymentdate = $request->paymentDate;
        $invoices = document_invoices::where('Invoice_ID', $invoice)->first();
        $companyid = $invoices->company;
        $proposalid = $invoices->Quotation_ID;
        $Invoice_ID =  $invoices->Invoice_ID;
        $parts = explode('-', $companyid);
        $firstPart = $parts[0];
        if ($firstPart == 'C') {
            $Selectdata =  'Company';
            $company =  companys::where('Profile_ID',$companyid)->first();
            if ($company) {
                $ids = $company->id;
                $Address=$company->Address;
                $CityID=$company->City;
                $amphuresID = $company->Amphures;
                $TambonID = $company->Tambon;
                $Identification = $company->Taxpayer_Identification;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = company_phone::where('Profile_ID',$company->Profile_ID)->where('Sequence','main')->first();
                $Email = $company->Company_Email;
                $Company_typeID=$company->Company_type;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="บริษัทจำกัด") {
                    $fullName = "บริษัท ". $company->Company_Name . " จำกัด";
                }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                    $fullName = "บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                    $fullName = "ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                }else{
                    $fullName = $comtype->name_th . $company->Company_Name;
                }
                if ($comtype->name_th =="บริษัทจำกัด") {
                    $name = "บริษัท ". $company->Company_Name . " จำกัด";
                }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                    $name = "บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                    $name = "ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                }else{
                    $name = $comtype->name_th . $company->Company_Name;
                }
                $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
                $name_ID = $company->Profile_ID;
                $datasub = company_tax::where('Company_ID',$name_ID)->get();
                $fax = company_fax::where('Profile_ID',$name_ID)->first();


            }
        }else{
            $guestdata =  Guest::where('Profile_ID',$companyid)->first();
            if ($guestdata) {
                $ids = $guestdata->id;
                $Selectdata =  'Guest';
                $Company_typeID=$guestdata->preface;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="นาย") {
                    $fullName = "นาย ". $guestdata->first_name . ' ' . $guestdata->last_name;
                }elseif ($comtype->name_th =="นาง") {
                    $fullName = "นาง ". $guestdata->first_name . ' ' . $guestdata->last_name;
                }elseif ($comtype->name_th =="นางสาว") {
                    $fullName = "นางสาว ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                }else{
                    $fullName = "คุณ ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                }
                $name =  'คุณ '.$guestdata->First_name.' '.$guestdata->Last_name;
                $Address=$guestdata->Address;
                $CityID=$guestdata->City;
                $amphuresID = $guestdata->Amphures;
                $TambonID = $guestdata->Tambon;
                $Identification = $guestdata->Identification_Number;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = phone_guest::where('Profile_ID',$guestdata->Profile_ID)->where('Sequence','main')->first();
                $Email = $guestdata->Company_Email;
                $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
                $name_ID = $guestdata->Profile_ID;
                $datasub = guest_tax::where('Company_ID',$name_ID)->get();
                $fax ='-';
            }
        }
        $datamain[0] = [
            'id' => $name_ID ?? null,
            'name' => $name ?? null,
        ];

        $names = []; // สร้าง array เพื่อเก็บค่าชื่อ

        if ($datasub) {
            foreach ($datasub as $key => $item) {
                $comtype = DB::table('master_documents')->where('id', $item->Company_type)->first();

                if ($comtype) { // ตรวจสอบว่า $comtype ไม่เป็น null
                    if ($firstPart == 'C') {
                        if ($comtype->name_th == "บริษัทจำกัด") {
                            $name = "บริษัท " . $item->Companny_name . " จำกัด";
                        } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                            $name = "บริษัท " . $item->Companny_name . " จำกัด (มหาชน)";
                        } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                            $name = "ห้างหุ้นส่วนจำกัด " . $item->Companny_name;
                        } else {
                            $name = $comtype->name_th . ($item->Companny_name ?? ($item->first_name . " " . $item->last_name));
                        }
                        $name_id = $item->ComTax_ID;
                    } else {
                        if ($comtype->name_th == "บริษัทจำกัด") {
                            $name = "บริษัท " . $item->Company_name . " จำกัด";
                        } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                            $name = "บริษัท " . $item->Company_name . " จำกัด (มหาชน)";
                        } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                            $name = "ห้างหุ้นส่วนจำกัด " . $item->Company_name;
                        } else {
                            $name = $comtype->name_th . ($item->Company_name ?? ($item->first_name . " " . $item->last_name));
                        }
                        $name_id = $item->GuestTax_ID;
                    }

                    // เก็บค่า $name ลงใน array
                    $names[$key + 1] = [
                        'id' => $name_id ?? null,
                        'name' => $name ?? null,
                    ];
                }
            }
        }
        $data_select = array_merge($datamain, $names);

        $currentDate = Carbon::now();
        $ID = 'RE-';
        $formattedDate = Carbon::parse($currentDate);       // วันที่
        $month = $formattedDate->format('m'); // เดือน
        $year = $formattedDate->format('y');
        $lastRun = receive_payment::latest()->first();
        $nextNumber = 1;

        if ($lastRun == null) {
            $nextNumber = $lastRun + 1;

        }else{
            $lastRunid = $lastRun->id;
            $nextNumber = $lastRunid + 1;
        }
        $newRunNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        $REID = $ID.$year.$month.$newRunNumber;
        $data_bank = Masters::where('category', "bank")->where('status', 1)->select('id', 'name_th', 'name_en')->get();
        $data_cheque =receive_cheque::where('refer_proposal',$proposalid)->where('status',1)->get();
        $valid = $invoices->Expiration;
        $reservationNo = $request->reservationNo ?? '-';
        $room = $request->roomNo ?? '-';
        $numberOfGuests = $request->numberOfGuests ?? '-';
        $arrival = $request->arrival ?? '-';
        $departure = $request->departure ?? '-';
        $Proposal = Quotation::where('Quotation_ID',$proposalid)->first();
        //additional_type
        $type = $Proposal->type_Proposal;
        $datadetailbill = [
            'valid'=>$valid,
            'reservationNo'=>$reservationNo,
            'room'=>$room,
            'numberOfGuests'=>$numberOfGuests,
            'arrival'=> $arrival,
            'departure'=>$departure
        ];

        return view('billingfolio.invoice.spiltebill',compact('Invoice_ID','Selectdata','address','Identification','fullName','phone','Email','valid','data_select','paymentsDataArray','cashAmount','idss','paymentdate'
        ,'REID','additional_type','datasub','name_ID','name','data_bank','data_cheque','payments','reservationNo','room','numberOfGuests','arrival','departure','fax','ids','type','datadetailbill','invoice','additional_amount'));
    }
    public function savemulti(Request $request) {


        if ($request->preview == 1) {
            $datadetailbill = json_decode($request->input('datadetailbill'), true);
            $datadetailpayment = json_decode($request->input('datadetailpayment'), true);
            $paymentdate = $request->paymentdate;
            $data = $request->all();

            $groupedData = [];
            foreach ($data as $key => $value) {
                // ใช้ regex เพื่อแยกค่า เช่น company-1, payment-type-1-1, amount-1-1
                preg_match('/(\D+)-(\d+)(-\d+)?/', $key, $matches);

                if (isset($matches[2])) {
                    $billId = $matches[2]; // เช่น "1", "2", "3"
                    $subId = isset($matches[3]) ? ltrim($matches[3], '-') : null; // เช่น "1", "2" หรือ null

                    if (!isset($groupedData[$billId])) {
                        $groupedData[$billId] = [
                            'bill' => $billId,
                            'company' => '',
                            'type' => '',
                            'fullname'=>'',
                            'Address' => '',
                            'Identification' => '',
                            'email'=>'',
                            'faxnumber'=>'',
                            'phonenumber'=>'',
                            'remark'=>'',
                            'payments' => []
                        ];
                    }

                    // จัดการ company
                    if (strpos($key, 'company') !== false) {
                        $groupedData[$billId]['company'] = $value;
                        $id = $value;
                        $parts = explode('-', $id);
                        $firstPart = $parts[0];
                        if ($firstPart == 'C') {

                            $company =  companys::where('Profile_ID',$id)->first();

                            if ($company) {

                                $name_ID = $company->Profile_ID;
                                $Address=$company->Address;
                                $CityID=$company->City;
                                $amphuresID = $company->Amphures;
                                $TambonID = $company->Tambon;
                                $Identification = $company->Taxpayer_Identification;
                                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                                $phone = company_phone::where('Profile_ID',$company->Profile_ID)->where('Sequence','main')->first();
                                $email = $company->Company_Email;
                                $Company_typeID=$company->Company_type;
                                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id','Category')->first();
                                if ($comtype->name_th =="บริษัทจำกัด") {
                                    $fullname = "บริษัท ". $company->Company_Name . " จำกัด";
                                }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                                    $fullname = "บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                                }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                                    $fullname = "ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                                }else{
                                    $fullname = $comtype->name_th . $company->Company_Name;
                                }
                                $Selectdata =  $comtype->Category;
                                $phonenuber = $phone->Phone_number ?? '-';
                                $fax = company_fax::where('Profile_ID',$name_ID)->first();
                                $faxnumber = $fax->Fax_number ?? '-';
                            }else{
                                $company =  company_tax::where('ComTax_ID',$id)->first();
                                $Company_typeID=$company->Company_type;
                                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id','Category')->first();
                                $Selectdata =  $comtype->Category;
                                if ($comtype->Category == 'Mcompany_type') {
                                    if ($comtype->name_th =="บริษัทจำกัด") {
                                        $fullname = "บริษัท ". $company->Companny_name . " จำกัด";
                                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                                        $fullname = "บริษัท ". $company->Companny_name . " จำกัด (มหาชน)";
                                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                                        $fullname = "ห้างหุ้นส่วนจำกัด ". $company->Companny_name ;
                                    }else{
                                        $fullname = $comtype->name_th . $company->Companny_name;
                                    }
                                }else{
                                    if ($comtype->name_th =="นาย") {
                                        $fullname = "นาย ". $company->first_name . ' ' . $company->last_name;
                                    }elseif ($comtype->name_th =="นาง") {
                                        $fullname = "นาง ". $company->first_name . ' ' . $company->last_name;
                                    }elseif ($comtype->name_th =="นางสาว") {
                                        $fullname = "นางสาว ". $company->first_name . ' ' . $company->last_name ;
                                    }else{
                                        $fullname = "คุณ ". $company->first_name . ' ' . $company->last_name ;
                                    }
                                }
                                $Address=$company->Address;
                                $CityID=$company->City;
                                $amphuresID = $company->Amphures;
                                $TambonID = $company->Tambon;
                                $Identification = $company->Taxpayer_Identification;
                                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                                $phone = company_tax_phone::where('ComTax_ID',$id)->where('Sequence','main')->first();
                                $email = $company->Company_Email;
                                $phonenuber = $phone->Phone_number ?? '-';
                                $faxnumber = '-';
                            }
                        }else{
                            $guestdata =  Guest::where('Profile_ID',$id)->first();
                            if ($guestdata) {
                                $name_ID = $guestdata->Profile_ID;

                                $Company_typeID=$guestdata->preface;
                                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id','Category')->first();
                                $Selectdata =  $comtype->Category;
                                if ($comtype->name_th =="นาย") {
                                    $fullname = "นาย ". $guestdata->First_name . ' ' . $guestdata->Last_name;
                                }elseif ($comtype->name_th =="นาง") {
                                    $fullname = "นาง ". $guestdata->First_name . ' ' . $guestdata->Last_name;
                                }elseif ($comtype->name_th =="นางสาว") {
                                    $fullname = "นางสาว ". $guestdata->First_name . ' ' . $guestdata->Last_name ;
                                }else{
                                    $fullname = "คุณ ". $guestdata->First_name . ' ' . $guestdata->Last_name ;
                                }
                                $Address=$guestdata->Address;
                                $CityID=$guestdata->City;
                                $amphuresID = $guestdata->Amphures;
                                $TambonID = $guestdata->Tambon;
                                $Identification = $guestdata->Identification_Number;
                                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                                $phone = phone_guest::where('Profile_ID',$guestdata->Profile_ID)->where('Sequence','main')->first();
                                $email = $guestdata->Email;
                                $phonenuber = $phone->Phone_number ?? '-';
                                $faxnumber = '-';
                            }else{
                                $guestdata =  guest_tax::where('GuestTax_ID',$id)->first();
                                $Company_typeID=$guestdata->Company_type;
                                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id','Category')->first();
                                $Selectdata =  $comtype->Category;
                                if ($comtype->Category == 'Mcompany_type') {
                                    if ($comtype->name_th =="บริษัทจำกัด") {
                                        $fullname = "บริษัท ". $guestdata->Company_name . " จำกัด";
                                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                                        $fullname = "บริษัท ". $guestdata->Company_name . " จำกัด (มหาชน)";
                                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                                        $fullname = "ห้างหุ้นส่วนจำกัด ". $guestdata->Company_name ;
                                    }else{
                                        $fullname = $comtype->name_th . $guestdata->Company_name;
                                    }
                                }else{
                                    if ($comtype->name_th =="นาย") {
                                        $fullname = "นาย ". $guestdata->first_name . ' ' . $guestdata->last_name;
                                    }elseif ($comtype->name_th =="นาง") {
                                        $fullname = "นาง ". $guestdata->first_name . ' ' . $guestdata->last_name;
                                    }elseif ($comtype->name_th =="นางสาว") {
                                        $fullname = "นางสาว ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                                    }else{
                                        $fullname = "คุณ ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                                    }
                                }
                                $Address=$guestdata->Address;
                                $CityID=$guestdata->City;
                                $amphuresID = $guestdata->Amphures;
                                $TambonID = $guestdata->Tambon;
                                $Identification = $guestdata->Identification_Number;
                                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                                $phone = guest_tax_phone::where('GuestTax_ID',$id)->where('Sequence','main')->first();
                                $email = $guestdata->Company_Email;
                                $phonenuber = $phone->Phone_number ?? '-';
                                $faxnumber = '-';
                            }
                        }
                        $groupedData[$billId]['fullname'] = $fullname;
                        $address = $Address . ' ตำบล ' . $TambonID->name_th . ' อำเภอ ' . $amphuresID->name_th . ' จังหวัด ' . $provinceNames->name_th . ' ' . $TambonID->Zip_Code;
                        $groupedData[$billId]['Address'] = $address;
                        $groupedData[$billId]['Identification'] = $Identification;
                        $groupedData[$billId]['email'] = $email;
                        $groupedData[$billId]['faxnumber'] = $faxnumber;
                        $groupedData[$billId]['phonenumber'] = $phonenuber;
                        $groupedData[$billId]['type'] = $Selectdata;
                    }

                    // จัดการ remark
                    if (strpos($key, 'remark') !== false) {
                        $groupedData[$billId]['remark'] = $value;
                    }
                    // จัดการ payment-type และ amount ให้อยู่ใน array เดียวกัน
                    elseif ($subId !== null) {
                        if (!isset($groupedData[$billId]['payments'][$subId])) {
                            $groupedData[$billId]['payments'][$subId] = [];
                        }

                        if (strpos($key, 'payment-type') !== false) {
                            $groupedData[$billId]['payments'][$subId]['payment-type'] = $value;
                        } elseif (strpos($key, 'amount') !== false) {
                            $groupedData[$billId]['payments'][$subId]['amount'] = $value;
                        }
                    }
                }
            }

            $bankMap = [];
            foreach ($datadetailpayment as $bank) {
                $bankMap[$bank["type"]] = $bank["datanamebank"];
            }

            // อัพเดตค่าของ payments โดยเพิ่ม datanamebank
            foreach ($groupedData as &$paymentGroup) {
                foreach ($paymentGroup["payments"] as &$payment) {
                    $type = $payment["payment-type"];
                    if (isset($bankMap[$type])) {
                        $payment["datanamebank"] = $bankMap[$type];
                    }
                }
            }


            $currentDate = Carbon::now();
            $ID = 'RE-';
            $month = $currentDate->format('m'); // ได้ค่าเป็น '03'
            $year = $currentDate->format('y');  // ได้ค่าเป็น '25'
            // ค้นหาเลขเอกสารล่าสุดของเดือนปัจจุบัน
            $lastRun = receive_payment::latest()->first();
            $nextNumber = 1; // กำหนดค่าเริ่มต้น
            // ถ้ามีเลขล่าสุด ให้ดึงเลขท้ายมาต่อ
            if ($lastRun) {
                $lastRunid = (int) substr($lastRun->id, -4); // ดึงเลข 4 ตัวท้าย
                $nextNumber = $lastRunid + 1; // เพิ่มขึ้น 1
            }
            // เก็บ REID ทั้งหมด
            foreach ($groupedData as $index => &$data) {
                $newRunNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                $REID = $ID . $year . $month . $newRunNumber;

                $data['bill'] = $REID;

                $nextNumber++; // เพิ่มหมายเลขรัน
            }
            $bill = count($groupedData);
            $settingCompany = Master_company::orderBy('id', 'desc')->first();
            $date = Carbon::now();
            $formattedDateprint = $date->format('d/m/Y');
            $formattedTime = $date->format('H:i');
            $invoices = document_invoices::where('Invoice_ID', $request->invoice)->first();
            $Quotation_ID = $invoices->Quotation_ID;
            $Deposit_ID = $invoices->Deposit_ID;
            $array = array_map('trim', explode(',', $Deposit_ID));
            $Proposal = Quotation::where('Quotation_ID',$Quotation_ID)->first();
            $receive = receive_payment::where('Deposit_ID', $array)
            ->leftJoin('document_receive_item', 'document_receive.Receipt_ID', '=', 'document_receive_item.receive_id')->get();
            $Additional = null;
            if ($request->additional != 0) {
                $Additional =  proposal_overbill::where('Quotation_ID',$Quotation_ID)->where('status_guest',0)->first();
            }
            return view('billingfolio.invoice.previewspiltebill',compact('groupedData','datadetailbill','settingCompany','REID','formattedDateprint','formattedTime','bill','paymentdate','Additional','Proposal','receive'));
        }else{
            try {
                {
                    $datadetailbill = json_decode($request->input('datadetailbill'), true);
                    $datadetailpayment = json_decode($request->input('datadetailpayment'), true);
                    $paymentdate = $request->paymentdate;
                    $data = $request->all();

                    $groupedData = [];

                    foreach ($data as $key => $value) {
                        // ใช้ regex เพื่อแยกค่า เช่น company-1, payment-type-1-1, amount-1-1
                        preg_match('/(\D+)-(\d+)(-\d+)?/', $key, $matches);

                        if (isset($matches[2])) {
                            $billId = $matches[2]; // เช่น "1", "2", "3"
                            $subId = isset($matches[3]) ? ltrim($matches[3], '-') : null; // เช่น "1", "2" หรือ null

                            if (!isset($groupedData[$billId])) {
                                $groupedData[$billId] = [
                                    'bill' => $billId,
                                    'company' => '',
                                    'type' => '',
                                    'fullname'=>'',
                                    'Address' => '',
                                    'Identification' => '',
                                    'email'=>'',
                                    'faxnumber'=>'',
                                    'phonenumber'=>'',
                                    'remark'=>'',
                                    'payments' => []
                                ];
                            }

                            // จัดการ company
                            if (strpos($key, 'company') !== false) {
                                $groupedData[$billId]['company'] = $value;
                                $id = $value;
                                $parts = explode('-', $id);
                                $firstPart = $parts[0];
                                if ($firstPart == 'C') {

                                    $company =  companys::where('Profile_ID',$id)->first();

                                    if ($company) {

                                        $name_ID = $company->Profile_ID;
                                        $Address=$company->Address;
                                        $CityID=$company->City;
                                        $amphuresID = $company->Amphures;
                                        $TambonID = $company->Tambon;
                                        $Identification = $company->Taxpayer_Identification;
                                        $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                                        $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                                        $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                                        $phone = company_phone::where('Profile_ID',$company->Profile_ID)->where('Sequence','main')->first();
                                        $email = $company->Company_Email;
                                        $Company_typeID=$company->Company_type;
                                        $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id','Category')->first();
                                        if ($comtype->name_th =="บริษัทจำกัด") {
                                            $fullname = "บริษัท ". $company->Company_Name . " จำกัด";
                                        }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                                            $fullname = "บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                                        }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                                            $fullname = "ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                                        }else{
                                            $fullname = $comtype->name_th . $company->Company_Name;
                                        }
                                        $Selectdata =  $comtype->Category;
                                        $phonenuber = $phone->Phone_number ?? '-';
                                        $fax = company_fax::where('Profile_ID',$name_ID)->first();
                                        $faxnumber = $fax->Fax_number ?? '-';
                                    }else{
                                        $company =  company_tax::where('ComTax_ID',$id)->first();
                                        $Company_typeID=$company->Company_type;
                                        $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id','Category')->first();
                                        $Selectdata =  $comtype->Category;
                                        if ($comtype->Category == 'Mcompany_type') {
                                            if ($comtype->name_th =="บริษัทจำกัด") {
                                                $fullname = "บริษัท ". $company->Companny_name . " จำกัด";
                                            }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                                                $fullname = "บริษัท ". $company->Companny_name . " จำกัด (มหาชน)";
                                            }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                                                $fullname = "ห้างหุ้นส่วนจำกัด ". $company->Companny_name ;
                                            }else{
                                                $fullname = $comtype->name_th . $company->Companny_name;
                                            }
                                        }else{
                                            if ($comtype->name_th =="นาย") {
                                                $fullname = "นาย ". $company->first_name . ' ' . $company->last_name;
                                            }elseif ($comtype->name_th =="นาง") {
                                                $fullname = "นาง ". $company->first_name . ' ' . $company->last_name;
                                            }elseif ($comtype->name_th =="นางสาว") {
                                                $fullname = "นางสาว ". $company->first_name . ' ' . $company->last_name ;
                                            }else{
                                                $fullname = "คุณ ". $company->first_name . ' ' . $company->last_name ;
                                            }
                                        }
                                        $Address=$company->Address;
                                        $CityID=$company->City;
                                        $amphuresID = $company->Amphures;
                                        $TambonID = $company->Tambon;
                                        $Identification = $company->Taxpayer_Identification;
                                        $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                                        $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                                        $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                                        $phone = company_tax_phone::where('ComTax_ID',$id)->where('Sequence','main')->first();
                                        $email = $company->Company_Email;
                                        $phonenuber = $phone->Phone_number ?? '-';
                                        $faxnumber = '-';
                                    }
                                }else{
                                    $guestdata =  Guest::where('Profile_ID',$id)->first();
                                    if ($guestdata) {
                                        $name_ID = $guestdata->Profile_ID;

                                        $Company_typeID=$guestdata->preface;
                                        $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                                        $Selectdata =  $comtype->Category;
                                        if ($comtype->name_th =="นาย") {
                                            $fullname = "นาย ". $guestdata->First_name . ' ' . $guestdata->Last_name;
                                        }elseif ($comtype->name_th =="นาง") {
                                            $fullname = "นาง ". $guestdata->First_name . ' ' . $guestdata->Last_name;
                                        }elseif ($comtype->name_th =="นางสาว") {
                                            $fullname = "นางสาว ". $guestdata->First_name . ' ' . $guestdata->Last_name ;
                                        }else{
                                            $fullname = "คุณ ". $guestdata->First_name . ' ' . $guestdata->Last_name ;
                                        }
                                        $Address=$guestdata->Address;
                                        $CityID=$guestdata->City;
                                        $amphuresID = $guestdata->Amphures;
                                        $TambonID = $guestdata->Tambon;
                                        $Identification = $guestdata->Identification_Number;
                                        $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                                        $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                                        $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                                        $phone = phone_guest::where('Profile_ID',$guestdata->Profile_ID)->where('Sequence','main')->first();
                                        $email = $guestdata->Company_Email;
                                        $phonenuber = $phone->Phone_number ?? '-';
                                        $faxnumber = '-';
                                    }else{
                                        $guestdata =  guest_tax::where('GuestTax_ID',$id)->first();
                                        $Company_typeID=$guestdata->Company_type;
                                        $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                                        $Selectdata =  $comtype->Category;
                                        if ($comtype->Category == 'Mcompany_type') {
                                            if ($comtype->name_th =="บริษัทจำกัด") {
                                                $fullname = "บริษัท ". $guestdata->Company_name . " จำกัด";
                                            }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                                                $fullname = "บริษัท ". $guestdata->Company_name . " จำกัด (มหาชน)";
                                            }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                                                $fullname = "ห้างหุ้นส่วนจำกัด ". $guestdata->Company_name ;
                                            }else{
                                                $fullname = $comtype->name_th . $guestdata->Company_name;
                                            }
                                        }else{
                                            if ($comtype->name_th =="นาย") {
                                                $fullname = "นาย ". $guestdata->first_name . ' ' . $guestdata->last_name;
                                            }elseif ($comtype->name_th =="นาง") {
                                                $fullname = "นาง ". $guestdata->first_name . ' ' . $guestdata->last_name;
                                            }elseif ($comtype->name_th =="นางสาว") {
                                                $fullname = "นางสาว ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                                            }else{
                                                $fullname = "คุณ ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                                            }
                                        }
                                        $Address=$guestdata->Address;
                                        $CityID=$guestdata->City;
                                        $amphuresID = $guestdata->Amphures;
                                        $TambonID = $guestdata->Tambon;
                                        $Identification = $guestdata->Identification_Number;
                                        $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                                        $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                                        $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                                        $phone = guest_tax_phone::where('GuestTax_ID',$id)->where('Sequence','main')->first();
                                        $email = $guestdata->Company_Email;
                                        $phonenuber = $phone->Phone_number ?? '-';
                                        $faxnumber = '-';
                                    }
                                }
                                $groupedData[$billId]['fullname'] = $fullname;
                                $address = $Address . ' ตำบล ' . $TambonID->name_th . ' อำเภอ ' . $amphuresID->name_th . ' จังหวัด ' . $provinceNames->name_th . ' ' . $TambonID->Zip_Code;
                                $groupedData[$billId]['Address'] = $address;
                                $groupedData[$billId]['Identification'] = $Identification;
                                $groupedData[$billId]['email'] = $email;
                                $groupedData[$billId]['faxnumber'] = $faxnumber;
                                $groupedData[$billId]['phonenumber'] = $phonenuber;
                                $groupedData[$billId]['type'] = $Selectdata;
                            }

                            // จัดการ remark
                            if (strpos($key, 'remark') !== false) {
                                $groupedData[$billId]['remark'] = $value;
                            }
                            // จัดการ payment-type และ amount ให้อยู่ใน array เดียวกัน
                            elseif ($subId !== null) {
                                if (!isset($groupedData[$billId]['payments'][$subId])) {
                                    $groupedData[$billId]['payments'][$subId] = [];
                                }

                                if (strpos($key, 'payment-type') !== false) {
                                    $groupedData[$billId]['payments'][$subId]['payment-type'] = $value;
                                } elseif (strpos($key, 'amount') !== false) {
                                    $groupedData[$billId]['payments'][$subId]['amount'] = $value;
                                }
                            }
                        }
                    }

                    $bankMap = [];
                    foreach ($datadetailpayment as $bank) {
                        $bankMap[$bank["type"]] = $bank["datanamebank"];
                    }

                    // อัพเดตค่าของ payments โดยเพิ่ม datanamebank
                    foreach ($groupedData as &$paymentGroup) {
                        foreach ($paymentGroup["payments"] as &$payment) {
                            $type = $payment["payment-type"];
                            if (isset($bankMap[$type])) {
                                $payment["datanamebank"] = $bankMap[$type];
                            }
                        }
                    }


                    $currentDate = Carbon::now();
                    $ID = 'RE-';
                    $month = $currentDate->format('m'); // ได้ค่าเป็น '03'
                    $year = $currentDate->format('y');  // ได้ค่าเป็น '25'
                    // ค้นหาเลขเอกสารล่าสุดของเดือนปัจจุบัน
                    $lastRun = receive_payment::lockForUpdate()->orderBy('id', 'desc')->first();
                    $nextNumber = 1; // กำหนดค่าเริ่มต้น
                    // ถ้ามีเลขล่าสุด ให้ดึงเลขท้ายมาต่อ
                    if ($lastRun) {
                        $lastRunid = (int) substr($lastRun->id, -4); // ดึงเลข 4 ตัวท้าย
                        $nextNumber = $lastRunid + 1; // เพิ่มขึ้น 1
                    }
                    // เก็บ REID ทั้งหมด
                    foreach ($groupedData as $index => &$data) {
                        $newRunNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                        $REID = $ID . $year . $month . $newRunNumber;

                        $data['bill'] = $REID;

                        $nextNumber++; // เพิ่มหมายเลขรัน
                    }
                    $bill = count($groupedData);
                }
                $paymentdate = $request->paymentdate;

                foreach ($groupedData as $product) {
                    $Bill_ID = 'รหัส : ' . $product['bill'];
                    $FullName = 'ลูกค้า : ' . $product['fullname'];
                    $paymentData = []; // เก็บข้อมูล payment หลายรายการ

                    foreach ($product['payments'] as $payment) {
                        $desc = 'Description : ' . $payment['datanamebank'];
                        $amount = 'Price item : ' . number_format($payment['amount']) . ' บาท';
                        $paymentData[] = $desc . ' , ' . $amount;
                    }

                    // รวมข้อมูล payments เป็นข้อความเดียว
                    $allPayments = implode(' , ', $paymentData);

                    // ดึงข้อมูลจาก $datadetailbill
                    $Reservation_No = $datadetailbill['reservationNo'] ?? 'ไม่มีข้อมูล'; // ให้ค่า default
                    $Room_No = $datadetailbill['room'] ?? 'ไม่มีข้อมูล';
                    $NumberOfGuests = $datadetailbill['reservationNo'] ?? 'ไม่มีข้อมูล';
                    $Arrival = $datadetailbill['arrival'] ?? 'ไม่มีข้อมูล';
                    $Departure = $datadetailbill['departure'] ?? 'ไม่มีข้อมูล';
                    $PaymentDate = $paymentdate ?? 'ไม่มีข้อมูล';
                    $spiltbill = 'รายละเอียดใบเสร็จ';

                    // รวมข้อมูลเป็นข้อความเดียว
                    $formattedProductData = implode(' + ', [
                        $Bill_ID,
                        $FullName,
                        'Reservation No : ' . $Reservation_No,
                        'Room No : ' . $Room_No,
                        'No. of guest : ' . $NumberOfGuests,
                        'Arrival : ' . $Arrival,
                        'Departure : ' . $Departure,
                        'วันที่ชำระ : ' . $PaymentDate,
                        $spiltbill,
                        $allPayments
                    ]);

                    // บันทึกข้อมูลทีละรอบในฐานข้อมูล
                    $userid = Auth::user()->id;
                    $save = new log_company();
                    $save->Created_by = $userid;
                    $save->Company_ID = $product['bill'];
                    $save->type = 'Create';
                    $save->Category = 'Create :: Billing Folio';
                    $save->content = $formattedProductData;  // บันทึกข้อความที่รวมในแต่ละรอบ
                    // $save->save();
                }
            } catch (\Throwable $e) {
                return redirect()->route('BillingFolio.index')->with('error', $e->getMessage());
            }
            try {
                {
                    $datadetailbill = json_decode($request->input('datadetailbill'), true);
                    $datadetailpayment = json_decode($request->input('datadetailpayment'), true);
                    $paymentdate = $request->paymentdate;
                    $data = $request->all();

                    $groupedData = [];

                    foreach ($data as $key => $value) {
                        // ใช้ regex เพื่อแยกค่า เช่น company-1, payment-type-1-1, amount-1-1
                        preg_match('/(\D+)-(\d+)(-\d+)?/', $key, $matches);

                        if (isset($matches[2])) {
                            $billId = $matches[2]; // เช่น "1", "2", "3"
                            $subId = isset($matches[3]) ? ltrim($matches[3], '-') : null; // เช่น "1", "2" หรือ null

                            if (!isset($groupedData[$billId])) {
                                $groupedData[$billId] = [
                                    'bill' => $billId,
                                    'company' => '',
                                    'type' => '',
                                    'fullname'=>'',
                                    'Address' => '',
                                    'Identification' => '',
                                    'email'=>'',
                                    'faxnumber'=>'',
                                    'phonenumber'=>'',
                                    'remark'=>'',
                                    'payments' => []
                                ];
                            }

                            // จัดการ company
                            if (strpos($key, 'company') !== false) {
                                $groupedData[$billId]['company'] = $value;
                                $id = $value;
                                $parts = explode('-', $id);
                                $firstPart = $parts[0];
                                if ($firstPart == 'C') {

                                    $company =  companys::where('Profile_ID',$id)->first();

                                    if ($company) {

                                        $name_ID = $company->Profile_ID;
                                        $Address=$company->Address;
                                        $CityID=$company->City;
                                        $amphuresID = $company->Amphures;
                                        $TambonID = $company->Tambon;
                                        $Identification = $company->Taxpayer_Identification;
                                        $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                                        $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                                        $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                                        $phone = company_phone::where('Profile_ID',$company->Profile_ID)->where('Sequence','main')->first();
                                        $email = $company->Company_Email;
                                        $Company_typeID=$company->Company_type;
                                        $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id','Category')->first();
                                        if ($comtype->name_th =="บริษัทจำกัด") {
                                            $fullname = "บริษัท ". $company->Company_Name . " จำกัด";
                                        }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                                            $fullname = "บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                                        }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                                            $fullname = "ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                                        }else{
                                            $fullname = $comtype->name_th . $company->Company_Name;
                                        }
                                        $Selectdata =  $comtype->Category;
                                        $phonenuber = $phone->Phone_number ?? '-';
                                        $fax = company_fax::where('Profile_ID',$name_ID)->first();
                                        $faxnumber = $fax->Fax_number ?? '-';
                                    }else{
                                        $company =  company_tax::where('ComTax_ID',$id)->first();
                                        $Company_typeID=$company->Company_type;
                                        $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id','Category')->first();
                                        $Selectdata =  $comtype->Category;
                                        if ($comtype->Category == 'Mcompany_type') {
                                            if ($comtype->name_th =="บริษัทจำกัด") {
                                                $fullname = "บริษัท ". $company->Companny_name . " จำกัด";
                                            }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                                                $fullname = "บริษัท ". $company->Companny_name . " จำกัด (มหาชน)";
                                            }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                                                $fullname = "ห้างหุ้นส่วนจำกัด ". $company->Companny_name ;
                                            }else{
                                                $fullname = $comtype->name_th . $company->Companny_name;
                                            }
                                        }else{
                                            if ($comtype->name_th =="นาย") {
                                                $fullname = "นาย ". $company->first_name . ' ' . $company->last_name;
                                            }elseif ($comtype->name_th =="นาง") {
                                                $fullname = "นาง ". $company->first_name . ' ' . $company->last_name;
                                            }elseif ($comtype->name_th =="นางสาว") {
                                                $fullname = "นางสาว ". $company->first_name . ' ' . $company->last_name ;
                                            }else{
                                                $fullname = "คุณ ". $company->first_name . ' ' . $company->last_name ;
                                            }
                                        }
                                        $Address=$company->Address;
                                        $CityID=$company->City;
                                        $amphuresID = $company->Amphures;
                                        $TambonID = $company->Tambon;
                                        $Identification = $company->Taxpayer_Identification;
                                        $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                                        $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                                        $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                                        $phone = company_tax_phone::where('ComTax_ID',$id)->where('Sequence','main')->first();
                                        $email = $company->Company_Email;
                                        $phonenuber = $phone->Phone_number ?? '-';
                                        $faxnumber = '-';
                                    }
                                }else{
                                    $guestdata =  Guest::where('Profile_ID',$id)->first();
                                    if ($guestdata) {
                                        $name_ID = $guestdata->Profile_ID;

                                        $Company_typeID=$guestdata->preface;
                                        $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                                        $Selectdata =  $comtype->Category;
                                        if ($comtype->name_th =="นาย") {
                                            $fullname = "นาย ". $guestdata->First_name . ' ' . $guestdata->Last_name;
                                        }elseif ($comtype->name_th =="นาง") {
                                            $fullname = "นาง ". $guestdata->First_name . ' ' . $guestdata->Last_name;
                                        }elseif ($comtype->name_th =="นางสาว") {
                                            $fullname = "นางสาว ". $guestdata->First_name . ' ' . $guestdata->Last_name ;
                                        }else{
                                            $fullname = "คุณ ". $guestdata->First_name . ' ' . $guestdata->Last_name ;
                                        }
                                        $Address=$guestdata->Address;
                                        $CityID=$guestdata->City;
                                        $amphuresID = $guestdata->Amphures;
                                        $TambonID = $guestdata->Tambon;
                                        $Identification = $guestdata->Identification_Number;
                                        $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                                        $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                                        $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                                        $phone = phone_guest::where('Profile_ID',$guestdata->Profile_ID)->where('Sequence','main')->first();
                                        $email = $guestdata->Company_Email;
                                        $phonenuber = $phone->Phone_number ?? '-';
                                        $faxnumber = '-';
                                    }else{
                                        $guestdata =  guest_tax::where('GuestTax_ID',$id)->first();
                                        $Company_typeID=$guestdata->Company_type;
                                        $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                                        $Selectdata =  $comtype->Category;
                                        if ($comtype->Category == 'Mcompany_type') {
                                            if ($comtype->name_th =="บริษัทจำกัด") {
                                                $fullname = "บริษัท ". $guestdata->Company_name . " จำกัด";
                                            }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                                                $fullname = "บริษัท ". $guestdata->Company_name . " จำกัด (มหาชน)";
                                            }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                                                $fullname = "ห้างหุ้นส่วนจำกัด ". $guestdata->Company_name ;
                                            }else{
                                                $fullname = $comtype->name_th . $guestdata->Company_name;
                                            }
                                        }else{
                                            if ($comtype->name_th =="นาย") {
                                                $fullname = "นาย ". $guestdata->first_name . ' ' . $guestdata->last_name;
                                            }elseif ($comtype->name_th =="นาง") {
                                                $fullname = "นาง ". $guestdata->first_name . ' ' . $guestdata->last_name;
                                            }elseif ($comtype->name_th =="นางสาว") {
                                                $fullname = "นางสาว ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                                            }else{
                                                $fullname = "คุณ ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                                            }
                                        }
                                        $Address=$guestdata->Address;
                                        $CityID=$guestdata->City;
                                        $amphuresID = $guestdata->Amphures;
                                        $TambonID = $guestdata->Tambon;
                                        $Identification = $guestdata->Identification_Number;
                                        $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                                        $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                                        $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                                        $phone = guest_tax_phone::where('GuestTax_ID',$id)->where('Sequence','main')->first();
                                        $email = $guestdata->Company_Email;
                                        $phonenuber = $phone->Phone_number ?? '-';
                                        $faxnumber = '-';
                                    }
                                }
                                $groupedData[$billId]['fullname'] = $fullname;
                                $address = $Address . ' ตำบล ' . $TambonID->name_th . ' อำเภอ ' . $amphuresID->name_th . ' จังหวัด ' . $provinceNames->name_th . ' ' . $TambonID->Zip_Code;
                                $groupedData[$billId]['Address'] = $address;
                                $groupedData[$billId]['Identification'] = $Identification;
                                $groupedData[$billId]['email'] = $email;
                                $groupedData[$billId]['faxnumber'] = $faxnumber;
                                $groupedData[$billId]['phonenumber'] = $phonenuber;
                                $groupedData[$billId]['type'] = $Selectdata;
                            }

                            // จัดการ remark
                            if (strpos($key, 'remark') !== false) {
                                $groupedData[$billId]['remark'] = $value;
                            }
                            // จัดการ payment-type และ amount ให้อยู่ใน array เดียวกัน
                            elseif ($subId !== null) {
                                if (!isset($groupedData[$billId]['payments'][$subId])) {
                                    $groupedData[$billId]['payments'][$subId] = [];
                                }

                                if (strpos($key, 'payment-type') !== false) {
                                    $groupedData[$billId]['payments'][$subId]['payment-type'] = $value;
                                } elseif (strpos($key, 'amount') !== false) {
                                    $groupedData[$billId]['payments'][$subId]['amount'] = $value;
                                }
                            }
                        }
                    }

                    $bankMap = [];
                    foreach ($datadetailpayment as $bank) {
                        $bankMap[$bank["type"]] = $bank["datanamebank"];
                    }

                    // อัพเดตค่าของ payments โดยเพิ่ม datanamebank
                    foreach ($groupedData as &$paymentGroup) {
                        foreach ($paymentGroup["payments"] as &$payment) {
                            $type = $payment["payment-type"];
                            if (isset($bankMap[$type])) {
                                $payment["datanamebank"] = $bankMap[$type];
                            }
                        }
                    }


                    $currentDate = Carbon::now();
                    $ID = 'RE-';
                    $month = $currentDate->format('m'); // ได้ค่าเป็น '03'
                    $year = $currentDate->format('y');  // ได้ค่าเป็น '25'
                    // ค้นหาเลขเอกสารล่าสุดของเดือนปัจจุบัน
                    $lastRun = receive_payment::latest()->first();
                    $nextNumber = 1; // กำหนดค่าเริ่มต้น
                    // ถ้ามีเลขล่าสุด ให้ดึงเลขท้ายมาต่อ
                    if ($lastRun) {
                        $lastRunid = (int) substr($lastRun->id, -4); // ดึงเลข 4 ตัวท้าย
                        $nextNumber = $lastRunid + 1; // เพิ่มขึ้น 1
                    }
                    // เก็บ REID ทั้งหมด
                    foreach ($groupedData as $index => &$data) {
                        $newRunNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                        $REID = $ID . $year . $month . $newRunNumber;

                        $data['bill'] = $REID;

                        $nextNumber++; // เพิ่มหมายเลขรัน
                    }
                    $bill = count($groupedData);
                }


                $Amount = 0;  // กำหนดค่าเริ่มต้นเป็น 0 ก่อนลูป
                foreach ($groupedData as $product) {
                    $settingCompany = Master_company::orderBy('id', 'desc')->first();
                    $date = Carbon::now();
                    $Date = $request->paymentdate;
                    $dateFormatted = $date->format('d/m/Y') . ' / ';
                    $dateTime = $date->format('H:i');
                    if ($product['type'] == 'Mcompany_type') {
                        $fullnameCom = $product['fullname'];
                        $fullname ='-';
                    }else{
                        $fullname = $product['fullname'];
                        $fullnameCom ='-';
                    }
                    preg_match('/^(.*? ถ\..*)\s(ตำบล.*)\s(อำเภอ.*)\s(เขต.*)\s(จังหวัด.*)\s(\d{5})$/', $product['Address'], $matches);


                    if (count($matches) > 0) {
                        // รวมบ้านเลขที่และถนนเข้าด้วยกัน
                        $Address = $matches[1];  // บ้านเลขที่ + ถนน
                        $tambon = $matches[2];   // ตำบล
                        $amphures = $matches[3]; // อำเภอ
                        $province = $matches[4]; // จังหวัด
                        $zip_code = $matches[5]; // รหัสไปรษณีย์
                    } else {
                        // กรณีที่ไม่สามารถจับข้อมูลได้
                        $Address = 'ไม่พบข้อมูลบ้านเลขที่และถนน';
                        $tambon = 'ไม่พบข้อมูลตำบล';
                        $amphures = 'ไม่พบข้อมูลอำเภอ';
                        $province = 'ไม่พบข้อมูลจังหวัด';
                        $zip_code = 'ไม่พบข้อมูลรหัสไปรษณีย์';
                    }


                    $reservationNo = $datadetailbill['reservationNo'] ?? '-'; // ให้ค่า default
                    $room = $datadetailbill['room'] ??  '-';
                    $numberOfGuests = $datadetailbill['reservationNo'] ??  '-';
                    $arrival = $datadetailbill['arrival'] ??  '-';
                    $departure = $datadetailbill['departure'] ??  '-';
                    $userid = Auth::user()->id;
                    $user = User::where('id', $userid)->first();

                    $productDetails = [];  // ใช้ชื่ออื่นสำหรับเก็บข้อมูลเกี่ยวกับการชำระเงิน

                    // ลูปผ่าน payments เพื่อหาผลรวม amount
                    foreach ($product['payments'] as $key => $payment) {
                        $Amount += $payment['amount'];  // บวกค่าของ amount ในแต่ละ payment
                        $productDetails[] = [
                            'detail' => $payment['datanamebank'],
                            'amount' => $payment['amount'],
                        ];
                        $key = $key;
                    }
                    $invoices = document_invoices::where('Invoice_ID', $request->invoice)->first();
                    $Quotation_ID = $invoices->Quotation_ID;
                    $Deposit_ID = $invoices->Deposit_ID;
                    $array = array_map('trim', explode(',', $Deposit_ID));
                    $Proposal = Quotation::where('Quotation_ID',$Quotation_ID)->first();
                    $receive = receive_payment::where('Deposit_ID', $array)
                    ->leftJoin('document_receive_item', 'document_receive.Receipt_ID', '=', 'document_receive_item.receive_id')->get();
                    $Additional = null;
                    if ($request->additional != 0) {
                        $Additional =  proposal_overbill::where('Quotation_ID',$Quotation_ID)->where('status_guest',0)->first();
                    }
                    $pdfdata = [
                        'settingCompany'=>$settingCompany,
                        'fullname'=>$fullname,
                        'fullnameCom'=>$fullnameCom,
                        'Identification'=>$product['Identification'],
                        'Address'=>$Address,
                        'province'=>$province,
                        'amphures'=>$amphures,
                        'tambon'=>$tambon,
                        'zip_code'=>$zip_code,
                        'reservationNo'=>$reservationNo,
                        'room'=>$room,
                        'user'=>$user,
                        'arrival'=>$arrival,
                        'departure'=>$departure,
                        'numberOfGuests'=>$numberOfGuests,
                        'dateFormatted'=>$dateFormatted,
                        'dateTime'=>$dateTime,
                        'created_at'=>$datadetailbill['valid'] ?? '-',
                        'Date'=>$Date,
                        'note'=>$product['remark'] ?? ' ',
                        'productItems'=>$productDetails,
                        'invoice'=>$product['bill'],
                        'Amount'=>$Amount,
                        'bill'=>$bill ?? 1,
                        'key'=>$key ?? 1,
                        'Additional'=>$Additional,
                        'Proposal'=>$Proposal,
                        'receive'=>$receive,
                    ];

                    $template = master_template::query()->latest()->first();
                    $view= $template->name;
                    $pdf = FacadePdf::loadView('billingfolioPDF.'.$view,$pdfdata);
                    $path = 'PDF/billingfolio/';
                    // return $pdf->stream();
                    // แสดงผลรวมของ Amount, ข้อมูลของ product และรายละเอียดการชำระเงินทั้งหมด
                    $pdf->save($path . $product['bill'] . '.pdf');
                    $currentDateTime = Carbon::now();
                    $currentDate = $currentDateTime->toDateString(); // Format: YYYY-MM-DD
                    $currentTime = $currentDateTime->toTimeString(); // Format: HH:MM:SS

                    // Optionally, you can format the date and time as per your requirement
                    $formattedDate = $currentDateTime->format('Y-m-d'); // Custom format for date
                    $formattedTime = $currentDateTime->format('H:i:s');
                    $savePDF = new log();
                    $savePDF->Quotation_ID = $product['bill'];
                    $savePDF->QuotationType = 'Receipt';
                    $savePDF->Company_Name = $product['fullname'];
                    $savePDF->Approve_date = $formattedDate;
                    $savePDF->Approve_time = $formattedTime;
                    $savePDF->save();
                }
            } catch (\Throwable $e) {
                return redirect()->route('BillingFolio.index')->with('error', $e->getMessage());
            }
            try {
                {
                    $datadetailbill = json_decode($request->input('datadetailbill'), true);
                    $datadetailpayment = json_decode($request->input('datadetailpayment'), true);
                    $paymentdate = $request->paymentdate;
                    $data = $request->all();

                    $groupedData = [];

                    foreach ($data as $key => $value) {
                        // ใช้ regex เพื่อแยกค่า เช่น company-1, payment-type-1-1, amount-1-1
                        preg_match('/(\D+)-(\d+)(-\d+)?/', $key, $matches);

                        if (isset($matches[2])) {
                            $billId = $matches[2]; // เช่น "1", "2", "3"
                            $subId = isset($matches[3]) ? ltrim($matches[3], '-') : null; // เช่น "1", "2" หรือ null

                            if (!isset($groupedData[$billId])) {
                                $groupedData[$billId] = [
                                    'bill' => $billId,
                                    'company' => '',
                                    'type' => '',
                                    'fullname'=>'',
                                    'Address' => '',
                                    'Identification' => '',
                                    'email'=>'',
                                    'faxnumber'=>'',
                                    'phonenumber'=>'',
                                    'remark'=>'',
                                    'payments' => []
                                ];
                            }

                            // จัดการ company
                            if (strpos($key, 'company') !== false) {
                                $groupedData[$billId]['company'] = $value;
                                $id = $value;
                                $parts = explode('-', $id);
                                $firstPart = $parts[0];
                                if ($firstPart == 'C') {

                                    $company =  companys::where('Profile_ID',$id)->first();

                                    if ($company) {

                                        $name_ID = $company->Profile_ID;
                                        $Address=$company->Address;
                                        $CityID=$company->City;
                                        $amphuresID = $company->Amphures;
                                        $TambonID = $company->Tambon;
                                        $Identification = $company->Taxpayer_Identification;
                                        $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                                        $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                                        $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                                        $phone = company_phone::where('Profile_ID',$company->Profile_ID)->where('Sequence','main')->first();
                                        $email = $company->Company_Email;
                                        $Company_typeID=$company->Company_type;
                                        $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id','Category')->first();
                                        if ($comtype->name_th =="บริษัทจำกัด") {
                                            $fullname = "บริษัท ". $company->Company_Name . " จำกัด";
                                        }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                                            $fullname = "บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                                        }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                                            $fullname = "ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                                        }else{
                                            $fullname = $comtype->name_th . $company->Company_Name;
                                        }
                                        $Selectdata =  $comtype->Category;
                                        $phonenuber = $phone->Phone_number ?? '-';
                                        $fax = company_fax::where('Profile_ID',$name_ID)->first();
                                        $faxnumber = $fax->Fax_number ?? '-';
                                    }else{
                                        $company =  company_tax::where('ComTax_ID',$id)->first();
                                        $Company_typeID=$company->Company_type;
                                        $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id','Category')->first();
                                        $Selectdata =  $comtype->Category;
                                        if ($comtype->Category == 'Mcompany_type') {
                                            if ($comtype->name_th =="บริษัทจำกัด") {
                                                $fullname = "บริษัท ". $company->Companny_name . " จำกัด";
                                            }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                                                $fullname = "บริษัท ". $company->Companny_name . " จำกัด (มหาชน)";
                                            }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                                                $fullname = "ห้างหุ้นส่วนจำกัด ". $company->Companny_name ;
                                            }else{
                                                $fullname = $comtype->name_th . $company->Companny_name;
                                            }
                                        }else{
                                            if ($comtype->name_th =="นาย") {
                                                $fullname = "นาย ". $company->first_name . ' ' . $company->last_name;
                                            }elseif ($comtype->name_th =="นาง") {
                                                $fullname = "นาง ". $company->first_name . ' ' . $company->last_name;
                                            }elseif ($comtype->name_th =="นางสาว") {
                                                $fullname = "นางสาว ". $company->first_name . ' ' . $company->last_name ;
                                            }else{
                                                $fullname = "คุณ ". $company->first_name . ' ' . $company->last_name ;
                                            }
                                        }
                                        $Address=$company->Address;
                                        $CityID=$company->City;
                                        $amphuresID = $company->Amphures;
                                        $TambonID = $company->Tambon;
                                        $Identification = $company->Taxpayer_Identification;
                                        $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                                        $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                                        $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                                        $phone = company_tax_phone::where('ComTax_ID',$id)->where('Sequence','main')->first();
                                        $email = $company->Company_Email;
                                        $phonenuber = $phone->Phone_number ?? '-';
                                        $faxnumber = '-';
                                    }
                                }else{
                                    $guestdata =  Guest::where('Profile_ID',$id)->first();
                                    if ($guestdata) {
                                        $name_ID = $guestdata->Profile_ID;

                                        $Company_typeID=$guestdata->preface;
                                        $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                                        $Selectdata =  $comtype->Category;
                                        if ($comtype->name_th =="นาย") {
                                            $fullname = "นาย ". $guestdata->First_name . ' ' . $guestdata->Last_name;
                                        }elseif ($comtype->name_th =="นาง") {
                                            $fullname = "นาง ". $guestdata->First_name . ' ' . $guestdata->Last_name;
                                        }elseif ($comtype->name_th =="นางสาว") {
                                            $fullname = "นางสาว ". $guestdata->First_name . ' ' . $guestdata->Last_name ;
                                        }else{
                                            $fullname = "คุณ ". $guestdata->First_name . ' ' . $guestdata->Last_name ;
                                        }
                                        $Address=$guestdata->Address;
                                        $CityID=$guestdata->City;
                                        $amphuresID = $guestdata->Amphures;
                                        $TambonID = $guestdata->Tambon;
                                        $Identification = $guestdata->Identification_Number;
                                        $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                                        $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                                        $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                                        $phone = phone_guest::where('Profile_ID',$guestdata->Profile_ID)->where('Sequence','main')->first();
                                        $email = $guestdata->Company_Email;
                                        $phonenuber = $phone->Phone_number ?? '-';
                                        $faxnumber = '-';
                                    }else{
                                        $guestdata =  guest_tax::where('GuestTax_ID',$id)->first();
                                        $Company_typeID=$guestdata->Company_type;
                                        $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                                        $Selectdata =  $comtype->Category;
                                        if ($comtype->Category == 'Mcompany_type') {
                                            if ($comtype->name_th =="บริษัทจำกัด") {
                                                $fullname = "บริษัท ". $guestdata->Company_name . " จำกัด";
                                            }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                                                $fullname = "บริษัท ". $guestdata->Company_name . " จำกัด (มหาชน)";
                                            }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                                                $fullname = "ห้างหุ้นส่วนจำกัด ". $guestdata->Company_name ;
                                            }else{
                                                $fullname = $comtype->name_th . $guestdata->Company_name;
                                            }
                                        }else{
                                            if ($comtype->name_th =="นาย") {
                                                $fullname = "นาย ". $guestdata->first_name . ' ' . $guestdata->last_name;
                                            }elseif ($comtype->name_th =="นาง") {
                                                $fullname = "นาง ". $guestdata->first_name . ' ' . $guestdata->last_name;
                                            }elseif ($comtype->name_th =="นางสาว") {
                                                $fullname = "นางสาว ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                                            }else{
                                                $fullname = "คุณ ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                                            }
                                        }
                                        $Address=$guestdata->Address;
                                        $CityID=$guestdata->City;
                                        $amphuresID = $guestdata->Amphures;
                                        $TambonID = $guestdata->Tambon;
                                        $Identification = $guestdata->Identification_Number;
                                        $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                                        $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                                        $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                                        $phone = guest_tax_phone::where('GuestTax_ID',$id)->where('Sequence','main')->first();
                                        $email = $guestdata->Company_Email;
                                        $phonenuber = $phone->Phone_number ?? '-';
                                        $faxnumber = '-';
                                    }
                                }
                                $groupedData[$billId]['fullname'] = $fullname;
                                $address = $Address . ' ตำบล ' . $TambonID->name_th . ' อำเภอ ' . $amphuresID->name_th . ' จังหวัด ' . $provinceNames->name_th . ' ' . $TambonID->Zip_Code;
                                $groupedData[$billId]['Address'] = $address;
                                $groupedData[$billId]['Identification'] = $Identification;
                                $groupedData[$billId]['email'] = $email;
                                $groupedData[$billId]['faxnumber'] = $faxnumber;
                                $groupedData[$billId]['phonenumber'] = $phonenuber;
                                $groupedData[$billId]['type'] = $Selectdata;
                            }

                            // จัดการ remark
                            if (strpos($key, 'remark') !== false) {
                                $groupedData[$billId]['remark'] = $value;
                            }
                            // จัดการ payment-type และ amount ให้อยู่ใน array เดียวกัน
                            elseif ($subId !== null) {
                                if (!isset($groupedData[$billId]['payments'][$subId])) {
                                    $groupedData[$billId]['payments'][$subId] = [];
                                }

                                if (strpos($key, 'payment-type') !== false) {
                                    $groupedData[$billId]['payments'][$subId]['payment-type'] = $value;
                                } elseif (strpos($key, 'amount') !== false) {
                                    $groupedData[$billId]['payments'][$subId]['amount'] = $value;
                                }
                            }
                        }
                    }

                    $bankMap = [];
                    foreach ($datadetailpayment as $bank) {
                        $bankMap[$bank["type"]] = $bank["datanamebank"];
                    }

                    // อัพเดตค่าของ payments โดยเพิ่ม datanamebank
                    foreach ($groupedData as &$paymentGroup) {
                        foreach ($paymentGroup["payments"] as &$payment) {
                            $type = $payment["payment-type"];
                            if (isset($bankMap[$type])) {
                                $payment["datanamebank"] = $bankMap[$type];
                            }
                        }
                    }


                    $currentDate = Carbon::now();
                    $ID = 'RE-';
                    $month = $currentDate->format('m'); // ได้ค่าเป็น '03'
                    $year = $currentDate->format('y');  // ได้ค่าเป็น '25'
                    // ค้นหาเลขเอกสารล่าสุดของเดือนปัจจุบัน
                    $lastRun = receive_payment::latest()->first();
                    $nextNumber = 1; // กำหนดค่าเริ่มต้น
                    // ถ้ามีเลขล่าสุด ให้ดึงเลขท้ายมาต่อ
                    if ($lastRun) {
                        $lastRunid = (int) substr($lastRun->id, -4); // ดึงเลข 4 ตัวท้าย
                        $nextNumber = $lastRunid + 1; // เพิ่มขึ้น 1
                    }
                    // เก็บ REID ทั้งหมด
                    foreach ($groupedData as $index => &$data) {
                        $newRunNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                        $REID = $ID . $year . $month . $newRunNumber;

                        $data['bill'] = $REID;

                        $nextNumber++; // เพิ่มหมายเลขรัน
                    }
                    $bill = count($groupedData);
                }
                $reservationNo = $datadetailbill['reservationNo'] ?? null; // ให้ค่า default
                $room = $datadetailbill['room'] ??  null;
                $numberOfGuests = $datadetailbill['reservationNo'] ??  null;
                $arrival = $datadetailbill['arrival'] ??  null;
                $departure = $datadetailbill['departure'] ??  null;
                $invoices = document_invoices::where('Invoice_ID', $request->invoice)->first();
                $Quotation_ID = $invoices->Quotation_ID;
                $Proposal = Quotation::where('Quotation_ID',$Quotation_ID)->first();
                $type_Proposal = $Proposal->type_Proposal;
                $Date = $request->paymentdate;

                foreach ($groupedData as &$group) {
                    // คำนวณยอดรวมก่อน
                    $Amount = 0;
                    foreach ($group['payments'] as &$payment) {
                        $Amount += $payment['amount'];

                        foreach ($datadetailpayment as $detail) {
                            if (
                                $payment['payment-type'] === $detail['type'] &&
                                $payment['datanamebank'] === $detail['datanamebank']
                            ) {
                                // ลบ 'amount' ออกจาก $detail ก่อน merge
                                $detailFiltered = array_diff_key($detail, ['amount' => '']);

                                // รวมข้อมูลที่เหลือจาก $detail เข้ากับ $payment
                                $payment = array_merge($payment, $detailFiltered);
                            }
                        }
                    }

                    // เพิ่มค่า Amount ลงใน group
                    $group['Amount'] = $Amount;  // เพิ่ม Amount ในกลุ่มนี้

                    // ใช้ $Amount ในที่นี้ตามต้องการ
                }


            } catch (\Throwable $e) {
                return redirect()->route('BillingFolio.index')->with('error', $e->getMessage());
            }
            try {
                if ($request->additional != 0) {
                    $Additional =  proposal_overbill::where('Quotation_ID',$Quotation_ID)->where('status_guest',0)->first();
                }
                foreach ($groupedData as $key => $product) {
                    $user = Auth::user()->id;
                    $save = new receive_payment();
                    $save->Receipt_ID = $product['bill'];
                    $save->Invoice_ID = $request->invoice;
                    $save->Additional_ID = $Additional->Additional_ID ?? null;
                    $save->Quotation_ID = $Quotation_ID;
                    $save->company = $product['company'];
                    $save->Amount = $product['Amount'];
                    $save->fullname = $product['fullname'];
                    $save->document_amount = $product['Amount'];
                    $save->reservationNo = $reservationNo;
                    $save->roomNo = $room;
                    $save->numberOfGuests = $numberOfGuests;
                    $save->arrival = $arrival;
                    $save->departure = $departure;
                    $save->type_Proposal = $type_Proposal;
                    $save->paymentDate = $Date;
                    $save->Operated_by = $user;
                    $save->note = $product['remark'];
                    $save->type_bill = 'spilte';
                    $save->num_bill = $key;
                    $save->save();
                    foreach ($product['payments'] as &$payment) {
                        $item = new document_receive_item();
                        $item->receive_id = $product['bill'];
                        $item->detail = $payment['datanamebank'];
                        $item->amount = $payment['amount'] ?? 0;
                        $item->type = $payment['payment-type'] ?? null;

                        // การตั้งค่า bank, cheque, และ chequedate ตาม payment-type
                        if ($payment['payment-type'] == 'bankTransfer') {
                            $item->bank = $payment['bank'] ?? null;
                        } elseif ($payment['payment-type'] == 'cheque') {
                            $item->bank = $payment['chequebank'] ?? null; // ใส่ชื่อธนาคารจาก cheque
                            $item->Cheque = $payment['cheque'] ?? null;   // ใส่หมายเลขเช็ค
                            $item->Deposit_date = $payment['chequedate'] ?? null; // ใส่วันที่เช็ค
                        }

                        $item->CardNumber = $payment['cardNumber'] ?? null; // ถ้ามีการชำระด้วยบัตร
                        $item->Expiry = $payment['expiry'] ?? null;         // ถ้ามีวันหมดอายุของบัตร

                        // บันทึกข้อมูล
                        $item->save();
                    }
                }

            } catch (\Throwable $e) {
                return redirect()->route('BillingFolio.index')->with('error', $e->getMessage());
            }

            try {
                // หาเอกสารที่เกี่ยวข้องกับ Invoice
                $invoices = document_invoices::where('Invoice_ID', $request->invoice)->first();

                if (!$invoices) {
                    return redirect()->route('BillingFolio.index')->with('error', 'Invoice not found.');
                }

                // อัปเดตสถานะของ document_invoices
                $invoices->document_status = 3;
                $invoices->save();

                // วนลูปข้อมูลที่ได้รับมา
                foreach ($groupedData as &$index) {
                    // ตรวจสอบว่า 'payments' มีอยู่ใน $index
                    if (isset($index['payments'])) {
                        foreach ($index['payments'] as $payment) {
                            // ตรวจสอบว่า 'payment-type' เป็น 'cheque'
                            if (isset($payment['payment-type']) && $payment['payment-type'] === 'cheque') {
                                // ดึงค่า 'cheque' ถ้ามี
                                $chequeNumber = isset($payment['cheque']) ? $payment['cheque'] : null;

                                if ($chequeNumber) {
                                    // ใช้ Carbon ในการดึงวันที่ปัจจุบัน
                                    $currentDateTime = Carbon::now();
                                    $formattedDate = $currentDateTime->format('Y-m-d'); // กำหนดรูปแบบวันที่

                                    // ค้นหา receive_cheque โดยใช้หมายเลขเช็คและสถานะเป็น 1
                                    $chequeRe = receive_cheque::where('cheque_number', $chequeNumber)
                                                              ->where('status', 1)
                                                              ->first();
                                    $userid = Auth::user()->id;
                                    if ($chequeRe) {
                                        $id_cheque = $chequeRe->id;
                                        // แก้ไขข้อมูลใน receive_cheque
                                        $savecheque = receive_cheque::find($id_cheque);
                                        $savecheque->receive_payment = $payment['chequebank'];
                                        $savecheque->status = 2; // เปลี่ยนสถานะเป็น 2
                                        $savecheque->deduct_date = $formattedDate;
                                        $savecheque->deduct_by = $userid; // ใช้ค่า $userid ที่ส่งมา
                                        $savecheque->save();
                                    }
                                }
                            }
                        }
                    }
                }

            } catch (\Throwable $e) {
                // ถ้ามีข้อผิดพลาดให้แสดงข้อความ
                return redirect()->route('BillingFolio.index')->with('error', $e->getMessage());
            }

            try {
                $invoiceid = $request->invoice;
                $invoices = document_invoices::where('Invoice_ID', $invoiceid)->first();
                $Quotation_ID = $invoices->Quotation_ID;
                $Additional =  proposal_overbill::where('Quotation_ID',$Quotation_ID)->where('status_guest',0)->first();
                if ($Additional) {
                    $AdditionalID = $Additional->id;
                    $saveAD = proposal_overbill::find($AdditionalID);
                    $saveAD->status_guest = 1;
                    $saveAD->save();
                }
            } catch (\Throwable $e) {
                return redirect()->route('BillingFolio.index')->with('error', $e->getMessage());
            }
            try {
                $invoiceid = $request->invoice;
                $invoices = document_invoices::where('Invoice_ID', $invoiceid)->first();
                $Quotation_ID = $invoices->Quotation_ID;
                $receive = receive_payment::where('Quotation_ID',$Quotation_ID)->get();
                $Amounttotal = 0;
                $Comtotal = 0;
                foreach ($receive as $value) {
                    $Amounttotal += $value->Amount;
                    $Comtotal += $value->complimentary;
                }
                $proposal = Quotation::where('Quotation_ID',$Quotation_ID)->first();
                $id = $proposal->id;
                $Additional =  proposal_overbill::where('Quotation_ID',$Quotation_ID)->first();
                $Additional_Nettotal =0;
                if ($Additional) {
                    $Additional_Nettotal = $Additional->Nettotal;
                }
                $Nettotal = $proposal->Nettotal;
                $amountMain=$Nettotal+$Additional_Nettotal;
                $amountPaid=$Amounttotal+$Comtotal;
                $total = $amountMain-$amountPaid;
                foreach ($receive as $value) {
                    $value->document_status = 2;
                    $value->save();
                }
                $update = Quotation::find($id);
                $update->status_document = 9;
                $update->save();
                return redirect()->route('BillingFolio.index')->with('success', 'Data has been successfully saved.');
            } catch (\Throwable $e) {
                return redirect()->route('BillingFolio.index')->with('error', $e->getMessage());
            }
        }

    }

    function SelectData($id){
        $parts = explode('-', $id);
        $firstPart = $parts[0];
        if ($firstPart == 'C') {
            $Selectdata =  'Company';
            $company =  companys::where('Profile_ID',$id)->first();
            if ($company) {
                $name_ID = $company->Profile_ID;
                $Address=$company->Address;
                $CityID=$company->City;
                $amphuresID = $company->Amphures;
                $TambonID = $company->Tambon;
                $Identification = $company->Taxpayer_Identification;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = company_phone::where('Profile_ID',$company->Profile_ID)->where('Sequence','main')->first();
                $email = $company->Company_Email;
                $Company_typeID=$company->Company_type;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id','Category')->first();
                if ($comtype->name_th =="บริษัทจำกัด") {
                    $fullname = "บริษัท ". $company->Company_Name . " จำกัด";
                }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                    $fullname = "บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                    $fullname = "ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                }else{
                    $fullname = $comtype->name_th . $company->Company_Name;
                }
                $phonenuber = $phone->Phone_number ?? '-';
                $fax = company_fax::where('Profile_ID',$name_ID)->first();
                $faxnumber = $fax->Fax_number ?? '-';
            }else{

                $company =  company_tax::where('ComTax_ID',$id)->first();

                $Company_typeID=$company->Company_type;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id','Category')->first();
                if ($comtype->Category == 'Mcompany_type') {
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $fullname = "บริษัท ". $company->Companny_name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $fullname = "บริษัท ". $company->Companny_name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $fullname = "ห้างหุ้นส่วนจำกัด ". $company->Companny_name ;
                    }else{
                        $fullname = $comtype->name_th . $company->Companny_name;
                    }
                }else{
                    if ($comtype->name_th =="นาย") {
                        $fullname = "นาย ". $company->first_name . ' ' . $company->last_name;
                    }elseif ($comtype->name_th =="นาง") {
                        $fullname = "นาง ". $company->first_name . ' ' . $company->last_name;
                    }elseif ($comtype->name_th =="นางสาว") {
                        $fullname = "นางสาว ". $company->first_name . ' ' . $company->last_name ;
                    }else{
                        $fullname = "คุณ ". $company->first_name . ' ' . $company->last_name ;
                    }
                }
                $Address=$company->Address;
                $CityID=$company->City;
                $amphuresID = $company->Amphures;
                $TambonID = $company->Tambon;
                $Identification = $company->Taxpayer_Identification;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = company_tax_phone::where('ComTax_ID',$id)->where('Sequence','main')->first();
                $email = $company->Company_Email;
                $phonenuber = $phone->Phone_number ?? '-';
                $faxnumber = '-';
            }
        }else{

            $guestdata =  Guest::where('Profile_ID',$id)->first();
            if ($guestdata) {
                $name_ID = $guestdata->Profile_ID;
                $Selectdata =  'Guest';
                $Company_typeID=$guestdata->preface;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="นาย") {
                    $fullname = "นาย ". $guestdata->first_name . ' ' . $guestdata->last_name;
                }elseif ($comtype->name_th =="นาง") {
                    $fullname = "นาง ". $guestdata->first_name . ' ' . $guestdata->last_name;
                }elseif ($comtype->name_th =="นางสาว") {
                    $fullname = "นางสาว ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                }else{
                    $fullname = "คุณ ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                }
                $Address=$guestdata->Address;
                $CityID=$guestdata->City;
                $amphuresID = $guestdata->Amphures;
                $TambonID = $guestdata->Tambon;
                $Identification = $guestdata->Identification_Number;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = phone_guest::where('Profile_ID',$guestdata->Profile_ID)->where('Sequence','main')->first();
                $email = $guestdata->Company_Email;
                $phonenuber = $phone->Phone_number ?? '-';
                $faxnumber = '-';
            }else{
                $guestdata =  guest_tax::where('GuestTax_ID',$id)->first();
                $Company_typeID=$guestdata->Company_type;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->Category == 'Mcompany_type') {
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $fullname = "บริษัท ". $guestdata->Company_name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $fullname = "บริษัท ". $guestdata->Company_name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $fullname = "ห้างหุ้นส่วนจำกัด ". $guestdata->Company_name ;
                    }else{
                        $fullname = $comtype->name_th . $guestdata->Company_name;
                    }
                }else{
                    if ($comtype->name_th =="นาย") {
                        $fullname = "นาย ". $guestdata->first_name . ' ' . $guestdata->last_name;
                    }elseif ($comtype->name_th =="นาง") {
                        $fullname = "นาง ". $guestdata->first_name . ' ' . $guestdata->last_name;
                    }elseif ($comtype->name_th =="นางสาว") {
                        $fullname = "นางสาว ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                    }else{
                        $fullname = "คุณ ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                    }
                }
                $Address=$guestdata->Address;
                $CityID=$guestdata->City;
                $amphuresID = $guestdata->Amphures;
                $TambonID = $guestdata->Tambon;
                $Identification = $guestdata->Identification_Number;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = guest_tax_phone::where('GuestTax_ID',$id)->where('Sequence','main')->first();
                $email = $guestdata->Company_Email;
                $phonenuber = $phone->Phone_number ?? '-';
                $faxnumber = '-';
            }
        }

        return response()->json([
            'fullname'=>$fullname,
            'Address' => $Address,
            'Identification' => $Identification,
            'province'=>$provinceNames,
            'amphures'=>$amphuresID,
            'Tambon'=>$TambonID,
            'email'=>$email,
            'faxnumber'=>$faxnumber,
            'phonenumber'=>$phonenuber,
        ]);
    }
    //---------------------------------table-----------------
    public function CheckPI($id)
    {
        $userid = Auth::user()->id;
        $ids = $id;
        $Proposal = Quotation::where('id',$id)->first();
        $ProposalID = $Proposal->id;
        $Proposal_ID = $Proposal->Quotation_ID;
        $totalAmount = $Proposal->Nettotal;
        $vat = $Proposal->vat_type;
        $nameid = $Proposal->Company_ID;
        $SpecialDiscountBath = $Proposal->SpecialDiscountBath;
        $SpecialDiscount = $Proposal->SpecialDiscount;
        $subtotal = 0;
        $beforeTax =0;
        $AddTax =0;
        $Nettotal =0;
        $total =0;
        $totalreceipt =0;
        $totalreceiptre =0;
        if ($vat == 50) {
            $total =  $totalAmount;
            $subtotal = $totalAmount;
            $beforeTax = $subtotal/1.07;
            $AddTax = $subtotal-$beforeTax;
            $Nettotal = $subtotal;

        }elseif ($vat == 51) {
            $total =  $totalAmount;
            $subtotal = $totalAmount;
            $Nettotal = $subtotal;
        }elseif ($vat == 52) {
            $total =  $totalAmount;
            $subtotal = $totalAmount;
            $AddTax = $subtotal/1.07;
            $Nettotal = $subtotal;
        }
        $parts = explode('-', $nameid);
        $firstPart = $parts[0];
        if ($firstPart == 'C') {
            $company =  companys::where('Profile_ID',$nameid)->first();
            $Company_type = $company->Company_type;
            $comtype = master_document::where('id', $Company_type)->where('Category', 'Mcompany_type')->first();
            if ($comtype) {
                if ($comtype->name_th == "บริษัทจำกัด") {
                    $fullname = "บริษัท " . $company->Company_Name . " จำกัด";
                } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                    $fullname = "บริษัท " . $company->Company_Name . " จำกัด (มหาชน)";
                } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                    $fullname = "ห้างหุ้นส่วนจำกัด " . $company->Company_Name;
                }else{
                    $fullname = $comtype->name_th . $company->Company_Name;
                }
            }
            $Address=$company->Address;
            $CityID=$company->City;
            $amphuresID = $company->Amphures;
            $TambonID = $company->Tambon;
            $Identification = $company->Taxpayer_Identification;
            $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
            $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
            $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
            $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
        }else{
            $guestdata =  Guest::where('Profile_ID',$nameid)->first();
            $fullname =  'คุณ '.$guestdata->First_name.' '.$guestdata->Last_name;
            $Address=$guestdata->Address;
            $CityID=$guestdata->City;
            $amphuresID = $guestdata->Amphures;
            $TambonID = $guestdata->Tambon;
            $Identification = $guestdata->Identification_Number;
            $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
            $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
            $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
            $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
        }


        $deposit_revenue = depositrevenue::where('Quotation_ID', $Proposal_ID)->where('document_status',1)->get();
        $deposit_revenue_amount  = 0;
        foreach ($deposit_revenue as $item) {
            $deposit_revenue_amount +=  $item->amount;
        }

        $invoices = document_invoices::where('Quotation_ID', $Proposal_ID)->where('document_status',1)->first();
        $totalinvoices = 0;
        if ($invoices) {
            $totalinvoices = $invoices->sumpayment;
        }


        $AdditionaltotalReceipt = 0;
        $Receipt = receive_payment::where('Quotation_ID', $Proposal_ID)->first();
        $totalReceipt = 0;
        if ($Receipt) {
            $totalReceipt = $Receipt->document_amount;
        }

        $Receiptover = null;
        $Receiptover = receive_payment::where('Quotation_ID', $Proposal_ID)->get();
        foreach ($Receiptover as $item) {
            $AdditionaltotalReceipt +=  $item->document_amount;
        }

        //-----------------------------------------------
        $room = document_quotation::where('Quotation_ID',$Proposal_ID)->where('Product_ID', 'LIKE', 'R' . '%')->get();
        $Meals = document_quotation::where('Quotation_ID',$Proposal_ID)->where('Product_ID', 'LIKE', 'M' . '%')->get();
        $Banquet = document_quotation::where('Quotation_ID',$Proposal_ID)->where('Product_ID', 'LIKE', 'B' . '%')->get();
        $entertainment = document_quotation::where('Quotation_ID',$Proposal_ID)->where('Product_ID', 'LIKE', 'E' . '%')->get();
        $unit = master_unit::where('status',1)->get();
        $quantity = master_quantity::where('status',1)->get();
        $totalnetpriceproduct = 0;
        foreach ($room as $item) {
            $totalnetpriceproduct +=  $item->netpriceproduct;
        }
        $totalnetMeals = 0;
        foreach ($Meals as $item) {
            $totalnetMeals +=  $item->netpriceproduct;
        }
        $totalnetBanquet = 0;
        foreach ($Banquet as $item) {
            $totalnetBanquet +=  $item->netpriceproduct;
        }
        $totalentertainment = 0;
        foreach ($entertainment as $item) {
            $totalentertainment +=  $item->netpriceproduct;
        }



        $Rm = []; // กำหนดตัวแปร $Rm เป็น array ว่าง
        $FB = [];
        $BQ = [];
        $AT = [];
        $EM = [];
        $RmCount = 0;
        $FBCount = 0;
        $BQCount = 0;
        $EMCount = 0;
        $ATCount = 0;
        $Additionaltotal= 0;
        $AdditionalHead = proposal_overbill::where('Quotation_ID',$Proposal_ID)->where('status_document',3)->get();
        if ($AdditionalHead) {
            foreach ($AdditionalHead as $item) {
                $Additionaltotal += $item->Nettotal;
            }
        }
        $Additional = proposal_overbill::where('Quotation_ID',$Proposal_ID)->where('status_document',3)->where('status_guest',0)->first();
        $additional_type= "";

        $Cash= 0;
        $Com= 0;

        $statusover = 1;

        $Additional_ID = null;
        if ($Additional) {
            $additional_type = $Additional->additional_type;

            $Cash = $Additional->Cash;
            $Com = $Additional->Complimentary;

            $Additional_ID = $Additional->Additional_ID;
            $document = document_proposal_overbill::where('Additional_ID',$Additional_ID)->get();

            $master = Master_additional::query()->get();
            $combinedData = $document->map(function($doc) use ($master) {
                $matchedMaster = $master->firstWhere('code', $doc->Code);

                if ($matchedMaster) { // ตรวจสอบว่าเจอข้อมูลที่ Code ตรงกันหรือไม่
                    return [
                        'Additional_ID' => $doc->Additional_ID,
                        'Code' => $doc->Code,
                        'Detail' => $doc->Detail,
                        'Amount' => $doc->Amount,
                        'type' => $matchedMaster->type,
                    ];
                }
                return null; // ถ้าไม่ตรงให้ส่งค่า null
            })->filter(); // ใช้ filter เพื่อกรอง null ออก

            foreach ($combinedData as $item) {
                if ($item['type'] == 'RM') {
                    $Rm[] = $item;
                } elseif ($item['type'] == 'FB') {
                    $FB[] = $item;
                } elseif ($item['type'] == 'BQ') {
                    $BQ[] = $item;
                } elseif ($item['type'] == 'AT') {
                    $AT[] = $item;
                } elseif ($item['type'] == 'EM') {
                    $EM[] = $item;
                }
            }

            foreach ($Rm as $item) {
                $RmCount +=  $item['Amount'];
            }

            foreach ($FB as $item) {
                $FBCount +=  $item['Amount'];
            }

            foreach ($BQ as $item) {
                $BQCount +=  $item['Amount'];
            }

            foreach ($EM as $item) {
                $EMCount +=  $item['Amount'];
            }

            foreach ($AT as $item) {
                $ATCount +=  $item['Amount'];
            }
            // ตรวจสอบผลลัพธ์
        }

        return view('billingfolio.check_pi',compact('Proposal_ID','subtotal','beforeTax','AddTax','Nettotal','SpecialDiscountBath','total','invoices','Proposal','ProposalID',
                    'totalnetpriceproduct','room','unit','quantity','totalnetMeals','Meals','Banquet','totalnetBanquet','totalentertainment','entertainment','Receipt','ids','fullname'
                    ,'firstPart','Identification','address','totalReceipt','vat','Additional','AdditionaltotalReceipt','Additionaltotal','Receiptover','statusover','Additional_ID',
                    'Rm','FB','BQ','AT','EM','RmCount','FBCount','BQCount','EMCount','ATCount','additional_type','totalinvoices','Cash','Com','deposit_revenue_amount','deposit_revenue','AdditionalHead'));
    }

    public function create_one($id){
        $invoices = document_invoices::where('id', $id)->first();
        $Invoice_ID = $invoices->Invoice_ID;
        $proposalid = $invoices->Quotation_ID;
        $Payment = $invoices->payment;
        $companyid = $invoices->company;
        $valid = Carbon::parse($invoices->created_at)->format('d/m/Y');
        $IssueDate = $invoices->IssueDate;
        $Expiration = $invoices->Expiration;
        $sumpayment = $invoices->sumpayment;
        $Payment = $invoices->payment;
        $amountproposal = $invoices->sumpayment;
        $parts = explode('-', $companyid);
        $firstPart = $parts[0];
        if ($firstPart == 'C') {
            $Selectdata =  'Company';
            $company =  companys::where('Profile_ID',$companyid)->first();
            if ($company) {
                $Address=$company->Address;
                $CityID=$company->City;
                $amphuresID = $company->Amphures;
                $TambonID = $company->Tambon;
                $Identification = $company->Taxpayer_Identification;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = company_phone::where('Profile_ID',$company->Profile_ID)->where('Sequence','main')->first();
                $Email = $company->Company_Email;
                $Company_typeID=$company->Company_type;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="บริษัทจำกัด") {
                    $fullName = "บริษัท ". $company->Company_Name . " จำกัด";
                }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                    $fullName = "บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                    $fullName = "ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                }else{
                    $fullName = $comtype->name_th . $company->Company_Name;
                }
                if ($comtype->name_th =="บริษัทจำกัด") {
                    $name = "บริษัท ". $company->Company_Name . " จำกัด";
                }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                    $name = "บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                    $name = "ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                }else{
                    $name = $comtype->name_th . $company->Company_Name;
                }
                $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
                $name_ID = $company->Profile_ID;
                $datasub = company_tax::where('Company_ID',$name_ID)->get();
            }
        }else{
            $guestdata =  Guest::where('Profile_ID',$companyid)->first();
            if ($guestdata) {
                $Selectdata =  'Guest';
                $Company_typeID=$guestdata->preface;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="นาย") {
                    $fullName = "นาย ". $guestdata->First_name . ' ' . $guestdata->Last_name;
                }elseif ($comtype->name_th =="นาง") {
                    $fullName = "นาง ". $guestdata->First_name . ' ' . $guestdata->Last_name;
                }elseif ($comtype->name_th =="นางสาว") {
                    $fullName = "นางสาว ". $guestdata->First_name . ' ' . $guestdata->Last_name ;
                }else{
                    $fullName = "คุณ ". $guestdata->First_name . ' ' . $guestdata->Last_name ;
                }
                $name =  'คุณ '.$guestdata->First_name.' '.$guestdata->Last_name;
                $Address=$guestdata->Address;
                $CityID=$guestdata->City;
                $amphuresID = $guestdata->Amphures;
                $TambonID = $guestdata->Tambon;
                $Identification = $guestdata->Identification_Number;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = phone_guest::where('Profile_ID',$guestdata->Profile_ID)->where('Sequence','main')->first();
                $Email = $guestdata->Company_Email;
                $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
                $name_ID = $guestdata->Profile_ID;
                $datasub = guest_tax::where('Company_ID',$name_ID)->get();
            }
        }
        $names = []; // สร้าง array เพื่อเก็บค่าชื่อ

        if ($datasub) {
            foreach ($datasub as $key => $item) {
                $comtype = DB::table('master_documents')->where('id', $item->Company_type)->first();

                if ($comtype) { // ตรวจสอบว่า $comtype ไม่เป็น null
                    if ($firstPart == 'C') {
                        if ($comtype->name_th == "บริษัทจำกัด") {
                            $name = "บริษัท " . $item->Companny_name . " จำกัด";
                        } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                            $name = "บริษัท " . $item->Companny_name . " จำกัด (มหาชน)";
                        } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                            $name = "ห้างหุ้นส่วนจำกัด " . $item->Companny_name;
                        } else {
                            $name = $comtype->name_th . ($item->Companny_name ?? ($item->first_name . " " . $item->last_name));
                        }
                        $name_id = $item->ComTax_ID;
                    } else {
                        if ($comtype->name_th == "บริษัทจำกัด") {
                            $name = "บริษัท " . $item->Company_name . " จำกัด";
                        } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                            $name = "บริษัท " . $item->Company_name . " จำกัด (มหาชน)";
                        } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                            $name = "ห้างหุ้นส่วนจำกัด " . $item->Company_name;
                        } else {
                            $name = $comtype->name_th . ($item->Company_name ?? ($item->first_name . " " . $item->last_name));
                        }
                        $name_id = $item->GuestTax_ID;
                    }

                    // เก็บค่า $name ลงใน array
                    $names[$key + 1] = [
                        'id' => $name_id ?? null,
                        'name' => $name ?? null,
                    ];
                }
            }
        }
        $datamain[0] = [
            'id' => $name_ID ?? null,
            'name' => $name ?? null,
        ];
        $data_select = array_merge($datamain, $names);
        $Proposal = Quotation::where('Quotation_ID',$proposalid)->first();
        $Deposit_ID = $invoices->Deposit_ID;
        $array = array_map('trim', explode(',', $Deposit_ID));
        $DepositID = depositrevenue::whereIn('Deposit_ID', $array)->get();
        $currentDate = Carbon::now();
        $ID = 'RE-';
        $formattedDate = Carbon::parse($currentDate);       // วันที่
        $month = $formattedDate->format('m'); // เดือน
        $year = $formattedDate->format('y');
        $lastRun = receive_payment::latest()->first();
        $nextNumber = 1;

        if ($lastRun == null) {
            $nextNumber = $lastRun + 1;

        }else{
            $lastRunid = $lastRun->id;
            $nextNumber = $lastRunid + 1;
        }
        $newRunNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        $REID = $ID.$year.$month.$newRunNumber;
        $data_cheque =receive_cheque::where('refer_proposal',$proposalid)->where('status',1)->get();
        $settingCompany = Master_company::orderBy('id', 'desc')->first();
        $data_bank = Masters::where('category', "bank")->where('status', 1)->select('id', 'name_th', 'name_en')->get();
        $chequeRe =receive_cheque::where('refer_proposal',$proposalid)->first();
        if ($chequeRe) {
            if ($chequeRe->status == '1') {
                // ถ้า status มีค่าเป็น 0 อย่างน้อยหนึ่งรายการ
                $chequeRestatus = 0;
            } else {
                $chequeRestatus = 1;
            }
        }else{
            $chequeRestatus = 1;
        }
        $type = $Proposal->type_Proposal;
        $vat_type = $Proposal->vat_type;

        $receive = receive_payment::where('Deposit_ID', $array)
        ->leftJoin('document_receive_item', 'document_receive.Receipt_ID', '=', 'document_receive_item.receive_id')->get();
        return view('billingfolio.invoice.createone',compact('Invoice_ID','Selectdata','address','Identification','fullName','phone','Email','valid','Proposal','Payment','sumpayment','amountproposal'
                    ,'DepositID','REID','Invoice_ID','settingCompany','data_bank','chequeRe','chequeRestatus','data_cheque','receive'
                    ,'datasub','name_ID','name','type','vat_type','IssueDate','Expiration','data_select'));
    }
    public function create($id){
        $invoices = document_invoices::where('id', $id)->first();
        $Invoice_ID = $invoices->Invoice_ID;
        $proposalid = $invoices->Quotation_ID;
        $Payment = $invoices->payment;
        $companyid = $invoices->company;
        $valid = $invoices->Expiration;
        $IssueDate = $invoices->IssueDate;
        $Expiration = $invoices->Expiration;
        $sumpayment = $invoices->sumpayment;
        $Payment = $invoices->payment;
        $amountproposal = $invoices->sumpayment;
        $parts = explode('-', $companyid);
        $firstPart = $parts[0];
        if ($firstPart == 'C') {
            $Selectdata =  'Company';
            $company =  companys::where('Profile_ID',$companyid)->first();
            if ($company) {
                $Address=$company->Address;
                $CityID=$company->City;
                $amphuresID = $company->Amphures;
                $TambonID = $company->Tambon;
                $Identification = $company->Taxpayer_Identification;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = company_phone::where('Profile_ID',$company->Profile_ID)->where('Sequence','main')->first();
                $Email = $company->Company_Email;
                $Company_typeID=$company->Company_type;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="บริษัทจำกัด") {
                    $fullName = "บริษัท ". $company->Company_Name . " จำกัด";
                }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                    $fullName = "บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                    $fullName = "ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                }else{
                    $fullName = $comtype->name_th . $company->Company_Name;
                }
                if ($comtype->name_th =="บริษัทจำกัด") {
                    $name = "บริษัท ". $company->Company_Name . " จำกัด";
                }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                    $name = "บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                    $name = "ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                }else{
                    $name = $comtype->name_th . $company->Company_Name;
                }
                $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
                $name_ID = $company->Profile_ID;
                $datasub = company_tax::where('Company_ID',$name_ID)->get();
            }
        }else{
            $guestdata =  Guest::where('Profile_ID',$companyid)->first();
            if ($guestdata) {
                $Selectdata =  'Guest';
                $Company_typeID=$guestdata->preface;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="นาย") {
                    $fullName = "นาย ". $guestdata->First_name . ' ' . $guestdata->Last_name;
                }elseif ($comtype->name_th =="นาง") {
                    $fullName = "นาง ". $guestdata->First_name . ' ' . $guestdata->Last_name;
                }elseif ($comtype->name_th =="นางสาว") {
                    $fullName = "นางสาว ". $guestdata->First_name . ' ' . $guestdata->Last_name ;
                }else{
                    $fullName = "คุณ ". $guestdata->First_name . ' ' . $guestdata->Last_name ;
                }
                $name =  'คุณ '.$guestdata->First_name.' '.$guestdata->Last_name;
                $Address=$guestdata->Address;
                $CityID=$guestdata->City;
                $amphuresID = $guestdata->Amphures;
                $TambonID = $guestdata->Tambon;
                $Identification = $guestdata->Identification_Number;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = phone_guest::where('Profile_ID',$guestdata->Profile_ID)->where('Sequence','main')->first();
                $Email = $guestdata->Company_Email;
                $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
                $name_ID = $guestdata->Profile_ID;
                $datasub = guest_tax::where('Company_ID',$name_ID)->get();
            }
        }
        $names = []; // สร้าง array เพื่อเก็บค่าชื่อ

        if ($datasub) {
            foreach ($datasub as $key => $item) {
                $comtype = DB::table('master_documents')->where('id', $item->Company_type)->first();

                if ($comtype) { // ตรวจสอบว่า $comtype ไม่เป็น null
                    if ($firstPart == 'C') {
                        if ($comtype->name_th == "บริษัทจำกัด") {
                            $name = "บริษัท " . $item->Companny_name . " จำกัด";
                        } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                            $name = "บริษัท " . $item->Companny_name . " จำกัด (มหาชน)";
                        } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                            $name = "ห้างหุ้นส่วนจำกัด " . $item->Companny_name;
                        } else {
                            $name = $comtype->name_th . ($item->Companny_name ?? ($item->first_name . " " . $item->last_name));
                        }
                        $name_id = $item->ComTax_ID;
                    } else {
                        if ($comtype->name_th == "บริษัทจำกัด") {
                            $name = "บริษัท " . $item->Company_name . " จำกัด";
                        } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                            $name = "บริษัท " . $item->Company_name . " จำกัด (มหาชน)";
                        } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                            $name = "ห้างหุ้นส่วนจำกัด " . $item->Company_name;
                        } else {
                            $name = $comtype->name_th . ($item->Company_name ?? ($item->first_name . " " . $item->last_name));
                        }
                        $name_id = $item->GuestTax_ID;
                    }

                    // เก็บค่า $name ลงใน array
                    $names[$key + 1] = [
                        'id' => $name_id ?? null,
                        'name' => $name ?? null,
                    ];
                }
            }
        }
        $datamain[0] = [
            'id' => $name_ID ?? null,
            'name' => $name ?? null,
        ];
        $data_select = array_merge($datamain, $names);
        $Proposal = Quotation::where('Quotation_ID',$proposalid)->first();
        $Deposit_ID = $invoices->Deposit_ID;
        $array = array_map('trim', explode(',', $Deposit_ID));
        $DepositID = depositrevenue::whereIn('Deposit_ID', $array)->get();
        $currentDate = Carbon::now();
        $ID = 'RE-';
        $formattedDate = Carbon::parse($currentDate);       // วันที่
        $month = $formattedDate->format('m'); // เดือน
        $year = $formattedDate->format('y');
        $lastRun = receive_payment::latest()->first();
        $nextNumber = 1;

        if ($lastRun == null) {
            $nextNumber = $lastRun + 1;

        }else{
            $lastRunid = $lastRun->id;
            $nextNumber = $lastRunid + 1;
        }
        $newRunNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        $REID = $ID.$year.$month.$newRunNumber;
        $data_cheque =receive_cheque::where('refer_proposal',$proposalid)->where('status',1)->get();
        $Additional = proposal_overbill::where('Quotation_ID',$proposalid)->where('status_guest',0)->first();
        $additional_type= "";
        $additional_Nettotal= 0;
        $Cash= 0;
        $Complimentary= 0;
        if ($Additional) {
            $additional_type = $Additional->additional_type;
            $additional_Nettotal = $Additional->Nettotal;
            if ($additional_type == 'Cash') {
                $Cash = $additional_Nettotal*0.37;
                $Complimentary = $additional_Nettotal-$additional_Nettotal*0.37	;
            }elseif ($additional_type == 'Cash Manual') {
                $Cash = $Additional->Cash;
                $Complimentary = $Additional->Complimentary	;
            }else{
                $Cash = $additional_Nettotal;
                $Complimentary = 0	;
            }
        }
        $settingCompany = Master_company::orderBy('id', 'desc')->first();
        $data_bank = Masters::where('category', "bank")->where('status', 1)->select('id', 'name_th', 'name_en')->get();
        $chequeRe =receive_cheque::where('refer_proposal',$proposalid)->first();
        if ($chequeRe) {
            if ($chequeRe->status == '1') {
                // ถ้า status มีค่าเป็น 0 อย่างน้อยหนึ่งรายการ
                $chequeRestatus = 0;
            } else {
                $chequeRestatus = 1;
            }
        }else{
            $chequeRestatus = 1;
        }
        $type = $Proposal->type_Proposal;
        $vat_type = $Proposal->vat_type;
        $receive = receive_payment::where('Deposit_ID', $array)
        ->leftJoin('document_receive_item', 'document_receive.Receipt_ID', '=', 'document_receive_item.receive_id')->get();
        return view('billingfolio.invoice.create',compact('Invoice_ID','Selectdata','address','Identification','fullName','phone','Email','valid','Proposal','Payment','sumpayment','amountproposal'
                    ,'DepositID','REID','Invoice_ID','settingCompany','additional_type','additional_Nettotal','Cash','Complimentary','data_bank','chequeRe','chequeRestatus','data_cheque'
                    ,'datasub','name_ID','name','type','vat_type','IssueDate','Expiration','data_select','receive','Additional'));
    }
    public function EditPaidInvoice($id){
        $re = receive_payment::where('id',$id)->first();
        $Invoice_ID = $re->Invoice_ID;
        $company = $re->company;
        $REID = $re->Receipt_ID;
        $proposalid = $re->Quotation_ID;
        $sumpayment =$re->document_amount;
        $fullname = $re->fullname;
        $valid = $re->valid;
        $productItems = document_receive_item::where('receive_id',$REID)->get();

        $Proposal = Quotation::where('Quotation_ID',$proposalid)->first();
        $guest = $Proposal->Company_ID;

        $type = $Proposal->type_Proposal;

        if ($type == 'Company') {
            $data = companys::where('Profile_ID',$guest)->first();
            $Company_typeID=$data->Company_type;
            $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
            if ($comtype->name_th =="บริษัทจำกัด") {
                $name = "บริษัท ". $data->Company_Name . " จำกัด";
            }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                $name = "บริษัท ". $data->Company_Name . " จำกัด (มหาชน)";
            }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                $name = "ห้างหุ้นส่วนจำกัด ". $data->Company_Name ;
            }else{
                $name = $comtype->name_th . $data->Company_Name;
            }
            $Identification = $data->Taxpayer_Identification;
            $name_ID = $data->Profile_ID;
            $datasub = company_tax::where('Company_ID',$name_ID)->get();
            $Address=$data->Address;
            $CityID=$data->City;
            $amphuresID = $data->Amphures;
            $TambonID = $data->Tambon;
            $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
            $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
            $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
            $address = $Address.' '.$TambonID->name_th.' '.$amphuresID->name_th.' '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
        }else {
            $data = Guest::where('Profile_ID',$guest)->first();
            $name =  'คุณ '.$data->First_name.' '.$data->Last_name;
            $Identification = $data->Identification_Number;
            $name_ID = $data->Profile_ID;
            $datasub = guest_tax::where('Company_ID',$name_ID)->get();
            $Address=$data->Address;
            $CityID=$data->City;
            $amphuresID = $data->Amphures;
            $TambonID = $data->Tambon;
            $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
            $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
            $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
            $address = $Address.' '.$TambonID->name_th.' '.$amphuresID->name_th.' '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
        }
        $settingCompany = Master_company::orderBy('id', 'desc')->first();
        $complimentary = $re->complimentary ?? 0;
        $additional =  $re->additional;
        $additional_type =$re->additional_type;
        $date = Carbon::now();

        $dateFormatted = $date->format('d/m/Y');
        $dateTime = $date->format('H:i:s');
        return view('billingfolio.editinvoicepaid',compact('company','address','re','Identification','valid','Proposal','name','fullname','name_ID','datasub','type','REID','Invoice_ID','settingCompany',
                    'sumpayment','complimentary','additional','additional_type','productItems','dateFormatted','dateTime'));
    }
    public function PaidInvoiceData($id)
    {
        $parts = explode('-', $id);
        $firstPart = $parts[0];
        if ($firstPart == 'C') {
            $Selectdata =  'Company';
            $company =  companys::where('Profile_ID',$id)->first();
            if ($company) {
                $Address=$company->Address;
                $CityID=$company->City;
                $amphuresID = $company->Amphures;
                $TambonID = $company->Tambon;
                $Identification = $company->Taxpayer_Identification;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = company_phone::where('Profile_ID',$company->Profile_ID)->where('Sequence','main')->first();
                $email = $company->Company_Email;
                $Company_typeID=$company->Company_type;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="บริษัทจำกัด") {
                    $fullname = "บริษัท ". $company->Company_Name . " จำกัด";
                }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                    $fullname = "บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                    $fullname = "ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                }else{
                    $fullname = $comtype->name_th . $company->Company_Name;
                }
            }else{

                $company =  company_tax::where('ComTax_ID',$id)->first();
                $Company_typeID=$company->Company_type;
                if ($Company_typeID == [30,31,32]) {
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $fullname = "บริษัท ". $company->Companny_name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $fullname = "บริษัท ". $company->Companny_name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $fullname = "ห้างหุ้นส่วนจำกัด ". $company->Companny_name ;
                    }elseif ($Company_typeID > 32){
                        $fullname = $comtype->name_th . $company->Companny_name;
                    }
                }else{
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="นาย") {
                        $fullname = "นาย ". $company->first_name . ' ' . $company->last_name;
                    }elseif ($comtype->name_th =="นาง") {
                        $fullname = "นาง ". $company->first_name . ' ' . $company->last_name;
                    }elseif ($comtype->name_th =="นางสาว") {
                        $fullname = "นางสาว ". $company->first_name . ' ' . $company->last_name ;
                    }else{
                        $fullname = "คุณ ". $company->first_name . ' ' . $company->last_name ;
                    }
                }
                $Address=$company->Address;
                $CityID=$company->City;
                $amphuresID = $company->Amphures;
                $TambonID = $company->Tambon;
                $Identification = $company->Taxpayer_Identification;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = company_tax_phone::where('ComTax_ID',$id)->where('Sequence','main')->first();
                $email = $company->Company_Email;
            }
        }else{

            $guestdata =  Guest::where('Profile_ID',$id)->first();

            if ($guestdata) {
                $Selectdata =  'Guest';
                $Company_typeID=$guestdata->preface;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="นาย") {
                    $fullname = "นาย ". $guestdata->first_name . ' ' . $guestdata->last_name;
                }elseif ($comtype->name_th =="นาง") {
                    $fullname = "นาง ". $guestdata->first_name . ' ' . $guestdata->last_name;
                }elseif ($comtype->name_th =="นางสาว") {
                    $fullname = "นางสาว ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                }else{
                    $fullname = "คุณ ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                }
                $Address=$guestdata->Address;
                $CityID=$guestdata->City;
                $amphuresID = $guestdata->Amphures;
                $TambonID = $guestdata->Tambon;
                $Identification = $guestdata->Identification_Number;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = phone_guest::where('Profile_ID',$guestdata->Profile_ID)->where('Sequence','main')->first();
                $email = $guestdata->Company_Email;
            }else{
                $guestdata =  guest_tax::where('GuestTax_ID',$id)->first();
                $Company_typeID=$guestdata->Company_type;
                if ($Company_typeID == [30,31,32]) {
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $fullname = "บริษัท ". $guestdata->Company_name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $fullname = "บริษัท ". $guestdata->Company_name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $fullname = "ห้างหุ้นส่วนจำกัด ". $guestdata->Company_name ;
                    }elseif ($Company_typeID > 32){
                        $fullname = $comtype->name_th . $guestdata->Company_name;
                    }
                }else{
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="นาย") {
                        $fullname = "นาย ". $guestdata->first_name . ' ' . $guestdata->last_name;
                    }elseif ($comtype->name_th =="นาง") {
                        $fullname = "นาง ". $guestdata->first_name . ' ' . $guestdata->last_name;
                    }elseif ($comtype->name_th =="นางสาว") {
                        $fullname = "นางสาว ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                    }else{
                        $fullname = "คุณ ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                    }
                }
                $Address=$guestdata->Address;
                $CityID=$guestdata->City;
                $amphuresID = $guestdata->Amphures;
                $TambonID = $guestdata->Tambon;
                $Identification = $guestdata->Identification_Number;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = guest_tax_phone::where('GuestTax_ID',$id)->where('Sequence','main')->first();
                $email = $guestdata->Company_Email;
            }
        }

        return response()->json([
            'fullname'=>$fullname,
            'Address' => $Address,
            'Identification' => $Identification,
            'province'=>$provinceNames,
            'amphures'=>$amphuresID,
            'Tambon'=>$TambonID,
        ]);
    }
    public function previewPdf($id){
        $parts = explode('-', $id);
        $firstPart = $parts[0];
        $fullCom ='-';
        $fullname ='-';
        if ($firstPart == 'C') {
            $Selectdata =  'Company';
            $company =  companys::where('Profile_ID',$id)->first();
            if ($company) {
                $Address=$company->Address;
                $CityID=$company->City;
                $amphuresID = $company->Amphures;
                $TambonID = $company->Tambon;
                $Identification = $company->Taxpayer_Identification;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = company_phone::where('Profile_ID',$company->Profile_ID)->where('Sequence','main')->first();
                $email = $company->Company_Email;
                $Company_typeID=$company->Company_type;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="บริษัทจำกัด") {
                    $fullCom = "บริษัท ". $company->Company_Name . " จำกัด";
                }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                    $fullCom = "บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                    $fullCom = "ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                }else{
                    $fullCom = $comtype->name_th . $company->Company_Name;
                }
            }else{

                $company =  company_tax::where('ComTax_ID',$id)->first();
                $Company_typeID=$company->Company_type;
                if ($Company_typeID == [30,31,32]) {
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $fullCom = "บริษัท ". $company->Companny_name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $fullCom = "บริษัท ". $company->Companny_name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $fullCom = "ห้างหุ้นส่วนจำกัด ". $company->Companny_name ;
                    }elseif ($Company_typeID > 32){
                        $fullCom = $comtype->name_th . $company->Companny_name;
                    }
                }else{
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="นาย") {
                        $fullname = "นาย ". $company->first_name . ' ' . $company->last_name;
                    }elseif ($comtype->name_th =="นาง") {
                        $fullname = "นาง ". $company->first_name . ' ' . $company->last_name;
                    }elseif ($comtype->name_th =="นางสาว") {
                        $fullname = "นางสาว ". $company->first_name . ' ' . $company->last_name ;
                    }else{
                        $fullname = "คุณ ". $company->first_name . ' ' . $company->last_name ;
                    }
                }
                $Address=$company->Address;
                $CityID=$company->City;
                $amphuresID = $company->Amphures;
                $TambonID = $company->Tambon;
                $Identification = $company->Taxpayer_Identification;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = company_tax_phone::where('ComTax_ID',$id)->where('Sequence','main')->first();
                $email = $company->Company_Email;
            }
        }else{

            $guestdata =  Guest::where('Profile_ID',$id)->first();

            if ($guestdata) {
                $Selectdata =  'Guest';
                $Company_typeID=$guestdata->preface;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="นาย") {
                    $fullname = "นาย ". $guestdata->First_name . ' ' . $guestdata->Last_name;
                }elseif ($comtype->name_th =="นาง") {
                    $fullname = "นาง ". $guestdata->First_name . ' ' . $guestdata->Last_name;
                }elseif ($comtype->name_th =="นางสาว") {
                    $fullname = "นางสาว ". $guestdata->First_name . ' ' . $guestdata->Last_name ;
                }else{
                    $fullname = "คุณ ". $guestdata->First_name . ' ' . $guestdata->Last_name ;
                }
                $Address=$guestdata->Address;
                $CityID=$guestdata->City;
                $amphuresID = $guestdata->Amphures;
                $TambonID = $guestdata->Tambon;
                $Identification = $guestdata->Identification_Number;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = phone_guest::where('Profile_ID',$guestdata->Profile_ID)->where('Sequence','main')->first();
                $email = $guestdata->Company_Email;
            }else{
                $guestdata =  guest_tax::where('GuestTax_ID',$id)->first();
                $Company_typeID=$guestdata->Company_type;
                if ($Company_typeID == [30,31,32]) {
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $fullCom = "บริษัท ". $guestdata->Company_name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $fullCom = "บริษัท ". $guestdata->Company_name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $fullCom = "ห้างหุ้นส่วนจำกัด ". $guestdata->Company_name ;
                    }elseif ($Company_typeID > 32){
                        $fullCom = $comtype->name_th . $guestdata->Company_name;
                    }
                }else{
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="นาย") {
                        $fullname = "นาย ". $guestdata->first_name . ' ' . $guestdata->last_name;
                    }elseif ($comtype->name_th =="นาง") {
                        $fullname = "นาง ". $guestdata->first_name . ' ' . $guestdata->last_name;
                    }elseif ($comtype->name_th =="นางสาว") {
                        $fullname = "นางสาว ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                    }else{
                        $fullname = "คุณ ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                    }
                }
                $Address=$guestdata->Address;
                $CityID=$guestdata->City;
                $amphuresID = $guestdata->Amphures;
                $TambonID = $guestdata->Tambon;
                $Identification = $guestdata->Identification_Number;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = guest_tax_phone::where('GuestTax_ID',$id)->where('Sequence','main')->first();
                $email = $guestdata->Company_Email;
            }
        }
        $date = Carbon::now();
        $dateFormatted = $date->format('d/m/Y');
        $dateTime = $date->format('h:i:s A');
        return response()->json([
            'fullname'=>$fullname,
            'fullCom'=>$fullCom,
            'Address' => $Address,
            'Identification' => $Identification,
            'province'=>$provinceNames,
            'amphures'=>$amphuresID,
            'Tambon'=>$TambonID,
            'date'=>$dateFormatted,
            'Time'=>$dateTime,
        ]);
    }
    public function cheque($id)
    {
        $chequeRe =receive_cheque::where('cheque_number',$id)->first();
        $bank = $chequeRe->bank_cheque;
        $amount= $chequeRe->amount;
        $issue_date= $chequeRe->issue_date;
        $data_bank = Masters::where('id',$bank)->where('category', "bank")->first();
        return response()->json([
            'amount'=>$amount,
            'issue_date'=>$issue_date,
            'data_bank'=>$data_bank,
        ]);
    }
    public function saveone(Request $request) {
        try {
            DB::beginTransaction();
            {
                $data = $request->all();

                $additional = $request->additional ?? 0;
                $cashcomp = $request->cashcomp ?? 0;
                $complimentary =  $additional-$cashcomp;
                $requestData = $request->all();
                $additional_type = $request->additional_type;
                $groupedData = []; // ตัวแปรสำหรับจัดเก็บข้อมูลที่ใช้ index

                foreach ($requestData as $key => $value) {
                    if (strpos($key, 'paymentType_') === 0) { // ตรวจสอบว่าคีย์ขึ้นต้นด้วย 'paymentType_'
                        preg_match('/\d+$/', $key, $matches); // ดึงตัวเลขท้ายคีย์
                        $index = $matches[0]; // ตัวเลขที่ได้จากคีย์ เช่น 0, 1, 2
                        $groupedData[$index ] = [
                            "paymentType" =>  $data["paymentType"] ?? null, // ค่า paymentType_x ที่ดึงมา
                            "cashAmount" =>  $data["cashAmount"] ?? null,
                            "bank" => $data["bank"] ?? null, // ค้นหา bank_x
                            "bankTransferAmount" => $data["bankTransferAmount"] ?? null, // ค้นหา bankTransferAmount_x
                            "CardNumber" => $data["CardNumber"] ?? null, // ค้นหา CardNumber_x
                            "Expiry" => $data["Expiry"] ?? null, // ค้นหา Expiry_x
                            "creditCardAmount" => $data["creditCardAmount"] ?? null, // ค้นหา creditCardAmount_x
                            "cheque" => $data["cheque"] ?? null, // ค้นหา creditCardAmount_
                            "deposit_date" => $data["deposit_date"] ?? null, // ค้นหา deposit_date_x
                            "chequebank" => $data["chequebank"] ?? null,
                            "chequebank_name" => $data["chequebank_name"] ?? null,
                            "chequeamount" => isset($data["chequeamount"])
                            ? str_replace([',', '.00'], ['', ''], $data["chequeamount"]) // ถอดคอมมาและ .00
                            : null, // ถ้าไม่มีค่าก็ให้เป็น null
                             // ถ้าไม่มีค่าก็ให้เป็น null
                            "detail" => ($data["paymentType"] == 'cash')
                            ? 'Cash'
                            : ($data["paymentType"] == 'bankTransfer'
                                ? $data["bank"] . ' Bank Transfer - Together Resort Ltd'
                                : ($data["paymentType"] == 'creditCard'
                                    ? 'Credit Card No. ' . $data["CardNumber"] . ' Exp. Date: ' . $data["Expiry"]
                                    : ($data["paymentType"] == 'cheque'
                                        ? 'Cheque Bank ' . $data["chequebank_name"] . ' Cheque Number ' . $data["cheque"]
                                            : null
                                    )
                                )
                            ),
                        ];
                        // สร้างอาร์เรย์ใหม่ที่ใช้ index
                        $groupedData[$index] = [
                            "paymentType" => $value, // ค่า paymentType_x ที่ดึงมา
                            "cashAmount" =>  $requestData["cashAmount_$index"] ?? null,
                            "bank" => $requestData["bank_$index"] ?? null, // ค้นหา bank_x
                            "bankTransferAmount" => $requestData["bankTransferAmount_$index"] ?? null, // ค้นหา bankTransferAmount_x
                            "CardNumber" => $requestData["CardNumber_$index"] ?? null, // ค้นหา CardNumber_x
                            "Expiry" => $requestData["Expiry_$index"] ?? null, // ค้นหา Expiry_x
                            "creditCardAmount" => $requestData["creditCardAmount_$index"] ?? null, // ค้นหา creditCardAmount_x
                            "cheque" => $requestData["cheque_$index"] ?? null, // ค้นหา creditCardAmount_
                            "deposit_date" => $requestData["deposit_date_$index"] ?? null, // ค้นหา deposit_date_x
                            "chequebank" => $requestData["chequebank_$index"] ?? null,
                            "chequebank_name" => $requestData["chequebank_name_$index"] ?? null,
                            "chequeamount" => isset($requestData["chequeamount_$index"])
                            ? str_replace([',', '.00'], ['', ''], $requestData["chequeamount_$index"]) // ถอดคอมมาและ .00
                            : null, // ถ้าไม่มีค่าก็ให้เป็น null
                            "detail" => ($value == 'cash')
                            ? 'Cash'
                            :
                            ($value == 'bankTransfer'
                                ? $requestData["bank_$index"] . ' Bank Transfer - Together Resort Ltd'
                                : ($value == 'creditCard'
                                    ? 'Credit Card No. ' . $requestData["CardNumber_$index"] . ' Exp. Date: ' . $requestData["Expiry_$index"]
                                    : ($value == 'cheque'
                                        ? 'Cheque Bank ' . $requestData["chequebank_name_$index"] . ' Cheque Number ' . $requestData["cheque_$index"]
                                        : null
                                    )
                                )
                            ),
                        ];
                    }else{
                        $groupedData[0] = [
                            "paymentType" =>  $data["paymentType"] ?? null, // ค่า paymentType_x ที่ดึงมา
                            "cashAmount" =>  $data["cashAmount"] ?? null,
                            "bank" => $data["bank"] ?? null, // ค้นหา bank_x
                            "bankTransferAmount" => $data["bankTransferAmount"] ?? null, // ค้นหา bankTransferAmount_x
                            "CardNumber" => $data["CardNumber"] ?? null, // ค้นหา CardNumber_x
                            "Expiry" => $data["Expiry"] ?? null, // ค้นหา Expiry_x
                            "creditCardAmount" => $data["creditCardAmount"] ?? null, // ค้นหา creditCardAmount_x
                            "cheque" => $data["cheque"] ?? null, // ค้นหา creditCardAmount_
                            "deposit_date" => $data["deposit_date"] ?? null, // ค้นหา deposit_date_x
                            "chequebank" => $data["chequebank"] ?? null,
                            "chequebank_name" => $data["chequebank_name"] ?? null,
                            "chequeamount" => isset($data["chequeamount"])
                            ? str_replace([',', '.00'], ['', ''], $data["chequeamount"]) // ถอดคอมมาและ .00
                            : null, // ถ้าไม่มีค่าก็ให้เป็น null
                             // ถ้าไม่มีค่าก็ให้เป็น null
                            "detail" => ($data["paymentType"] == 'cash')
                            ? 'Cash'
                            : ($data["paymentType"] == 'bankTransfer'
                                ? $data["bank"] . ' Bank Transfer - Together Resort Ltd'
                                : ($data["paymentType"] == 'creditCard'
                                    ? 'Credit Card No. ' . $data["CardNumber"] . ' Exp. Date: ' . $data["Expiry"]
                                    : ($data["paymentType"] == 'cheque'
                                        ? 'Cheque Bank ' . $data["chequebank_name"] . ' Cheque Number ' . $data["cheque"]
                                            : null
                                    )
                                )
                            ),
                        ];
                    }
                }
                $cash = 0;
                $cashbankTransfer = 0;
                $cashCard = 0;
                $cashcheque =0;
                $cashnoshow =0;
                foreach ($groupedData as $value) {
                    $cash += isset($value['cashAmount']) ? (float)$value['cashAmount'] : 0;
                    $cashbankTransfer += isset($value['bankTransferAmount']) ? (float)$value['bankTransferAmount'] : 0;
                    $cashCard += isset($value['creditCardAmount']) ? (float)$value['creditCardAmount'] : 0;
                    $cashcheque += isset($value['chequeamount']) ? (float)$value['chequeamount'] : 0;
                    $cashnoshow += isset($value['NoShowAmount']) ? (float)$value['NoShowAmount'] : 0;
                }
                if ($additional_type == 'H/G') {
                    $Amountall = $cash+$cashbankTransfer+$cashCard+$cashcheque+$cashnoshow;
                    $Amount = floatval($Amountall);
                    $RealAmount = $cash+$cashbankTransfer+$cashCard+$cashcheque+$cashnoshow;
                }else{
                    $Amountall = $cash+$cashbankTransfer+$cashCard+$cashcheque+$cashnoshow+$additional;
                    $Amount = floatval($Amountall);
                    $RealAmount = $cash+$cashbankTransfer+$cashCard+$cashcheque+$cashnoshow;
                }

                $guest = $request->Guest;
                $companyid = $request->Guest;
                $reservationNo = $request->reservationNo;
                $room = $request->roomNo;
                $numberOfGuests = $request->numberOfGuests;
                $arrival = $request->arrival;
                $departure = $request->departure;

                $additional = $request->additional ?? 0;
                $note = $request->note;
                $paymentDate = $request->paymentDate;
                $Complimentary = $request->Complimentary;
                $paymentType = $request->paymentTypecheque ?? $request->paymentType;
                if ($paymentType == null || $companyid == null || $reservationNo == null || $room == null || $numberOfGuests == null || $arrival == null || $departure == null) {
                    return redirect()->route('BillingFolio.index')->with('error', 'กรุณากรอกข้อมูลให้ครบ');
                }
                $invoice = $request->invoice;
                $parts = explode('-', $companyid);
                $firstPart = $parts[0];
                if ($firstPart == 'C') {
                    $Selectdata =  'Company';
                    $company =  companys::where('Profile_ID',$companyid)->first();

                    if ($company) {
                        $Address=$company->Address;
                        $CityID=$company->City;
                        $amphuresID = $company->Amphures;
                        $TambonID = $company->Tambon;
                        $Identification = $company->Taxpayer_Identification;
                        $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                        $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                        $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                        $phone = company_phone::where('Profile_ID',$company->Profile_ID)->where('Sequence','main')->first();
                        $email = $company->Company_Email;
                        $Company_typeID=$company->Company_type;
                        $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                        if ($comtype->name_th =="บริษัทจำกัด") {
                            $name = 'ลูกค้า : '."บริษัท ". $company->Company_Name . " จำกัด";
                            $nameold = "บริษัท ". $company->Company_Name . " จำกัด";
                        }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                            $name = 'ลูกค้า : '."บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                            $nameold = "บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                        }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                            $name = 'ลูกค้า : '."ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                            $nameold = "ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                        }else{
                            $name = 'ลูกค้า : '.$comtype->name_th . $company->Company_Name;
                            $nameold = $comtype->name_th . $company->Company_Name;
                        }
                    }else{

                        $company =  company_tax::where('ComTax_ID',$companyid)->first();
                        $Company_typeID=$company->Company_type;
                        if ($Company_typeID == [30,31,32]) {
                            $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                            if ($comtype->name_th =="บริษัทจำกัด") {
                                $name = 'ลูกค้า : '."บริษัท ". $company->Company_Name . " จำกัด";
                                $nameold = "บริษัท ". $company->Company_Name . " จำกัด";
                            }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                                $name = 'ลูกค้า : '."บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                                $nameold = "บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                            }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                                $name = 'ลูกค้า : '."ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                                $nameold = "ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                            }else{
                                $name = 'ลูกค้า : '.$comtype->name_th . $company->Company_Name;
                                $nameold = $comtype->name_th . $company->Company_Name;
                            }
                        }else{
                            $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                            if ($comtype->name_th =="นาย") {
                                $nameold = "นาย ". $company->first_name . ' ' . $company->last_name;
                                $name = 'ลูกค้า : '."นาย ". $company->first_name . ' ' . $company->last_name;
                            }elseif ($comtype->name_th =="นาง") {
                                $nameold = "นาง ". $company->first_name . ' ' . $company->last_name;
                                $name = 'ลูกค้า : '."นาง ". $company->first_name . ' ' . $company->last_name;
                            }elseif ($comtype->name_th =="นางสาว") {
                                $nameold = "นางสาว ". $company->first_name . ' ' . $company->last_name ;
                                $name = 'ลูกค้า : '."นางสาว ". $company->first_name . ' ' . $company->last_name ;
                            }else{
                                $nameold = "คุณ ". $company->first_name . ' ' . $company->last_name ;
                                $name = 'ลูกค้า : '."คุณ ". $company->first_name . ' ' . $company->last_name ;
                            }
                        }
                        $Address=$company->Address;
                        $CityID=$company->City;
                        $amphuresID = $company->Amphures;
                        $TambonID = $company->Tambon;
                        $Identification = $company->Taxpayer_Identification;
                        $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                        $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                        $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                        $phone = company_tax_phone::where('ComTax_ID',$companyid)->where('Sequence','main')->first();
                        $email = $company->Company_Email;
                    }
                }else{

                    $guestdata =  Guest::where('Profile_ID',$companyid)->first();

                    if ($guestdata) {
                        $Selectdata =  'Guest';
                        $Company_typeID=$guestdata->preface;
                        $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                        if ($comtype->name_th =="นาย") {
                            $nameold = "นาย ". $guestdata->First_name . ' ' . $guestdata->Last_name;
                            $name = 'ลูกค้า : '."นาย ". $guestdata->First_name . ' ' . $guestdata->Last_name;
                        }elseif ($comtype->name_th =="นาง") {
                            $nameold = "นาง ". $guestdata->First_name . ' ' . $guestdata->Last_name;
                            $name = 'ลูกค้า : '."นาง ". $guestdata->First_name . ' ' . $guestdata->Last_name;
                        }elseif ($comtype->name_th =="นางสาว") {
                            $nameold = "นางสาว ". $guestdata->First_name . ' ' . $guestdata->Last_name ;
                            $name = 'ลูกค้า : '."นางสาว ". $guestdata->First_name . ' ' . $guestdata->Last_name ;
                        }else{
                            $nameold = "คุณ ". $guestdata->First_name . ' ' . $guestdata->Last_name ;
                            $name = 'ลูกค้า : '."คุณ ". $guestdata->First_name . ' ' . $guestdata->Last_name ;
                        }
                        $Address=$guestdata->Address;
                        $CityID=$guestdata->City;
                        $amphuresID = $guestdata->Amphures;
                        $TambonID = $guestdata->Tambon;
                        $Identification = $guestdata->Identification_Number;
                        $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                        $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                        $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                        $phone = phone_guest::where('Profile_ID',$guestdata->Profile_ID)->where('Sequence','main')->first();
                        $email = $guestdata->Company_Email;
                    }else{
                        $guestdata =  guest_tax::where('GuestTax_ID',$companyid)->first();
                        $Company_typeID=$guestdata->Company_type;
                        if ($Company_typeID == [30,31,32]) {
                            $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                            if ($comtype->name_th =="บริษัทจำกัด") {
                                $nameold = "บริษัท ". $guestdata->Company_name . " จำกัด";
                                $name = 'ลูกค้า : '."บริษัท ". $guestdata->Company_name . " จำกัด";
                            }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                                $nameold = "บริษัท ". $guestdata->Company_name . " จำกัด (มหาชน)";
                                $name = 'ลูกค้า : '."บริษัท ". $guestdata->Company_name . " จำกัด";
                            }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                                $nameold = "ห้างหุ้นส่วนจำกัด ". $guestdata->Company_name ;
                                $name = 'ลูกค้า : '."บริษัท ". $guestdata->Company_name . " จำกัด";
                            }elseif ($Company_typeID > 32){
                                $nameold = $comtype->name_th . $guestdata->Company_name;
                                $name = 'ลูกค้า : '."บริษัท ". $guestdata->Company_name . " จำกัด";
                            }
                        }else{
                            $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                            if ($comtype->name_th =="นาย") {
                                $nameold = "นาย ". $guestdata->first_name . ' ' . $guestdata->last_name;
                                $name = 'ลูกค้า : '."นาย ". $guestdata->first_name . ' ' . $guestdata->last_name;
                            }elseif ($comtype->name_th =="นาง") {
                                $nameold = "นาง ". $guestdata->first_name . ' ' . $guestdata->last_name;
                                $name = 'ลูกค้า : '."นาง ". $guestdata->first_name . ' ' . $guestdata->last_name;
                            }elseif ($comtype->name_th =="นางสาว") {
                                $nameold = "นางสาว ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                                $name = 'ลูกค้า : '. "นางสาว ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                            }else{
                                $nameold = "คุณ ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                                $name = 'ลูกค้า : '."คุณ ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                            }
                        }
                        $Address=$guestdata->Address;
                        $CityID=$guestdata->City;
                        $amphuresID = $guestdata->Amphures;
                        $TambonID = $guestdata->Tambon;
                        $Identification = $guestdata->Identification_Number;
                        $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                        $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                        $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                        $phone = guest_tax_phone::where('GuestTax_ID',$companyid)->where('Sequence','main')->first();
                        $email = $guestdata->Company_Email;
                    }
                }

                $currentDate = Carbon::now();
                $ID = 'RE-';
                $formattedDate = Carbon::parse($currentDate);       // วันที่
                $month = $formattedDate->format('m'); // เดือน
                $year = $formattedDate->format('y');
                $lastRun = receive_payment::lockForUpdate()->orderBy('id', 'desc')->first();
                $nextNumber = 1;
                if ($lastRun == null) {
                    $nextNumber = $lastRun + 1;
                }else{
                    $lastRunid = $lastRun->id;
                    $nextNumber = $lastRunid + 1;
                }
                $newRunNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                $REID = $ID.$year.$month.$newRunNumber;

                $invoices = document_invoices::where('Invoice_ID', $invoice)->first();
                $idinvoices = $invoices->id;
                $sumpayment = $invoices->sumpayment;
                $Quotation_ID = $invoices->Quotation_ID;
                $Deposit_ID = $invoices->Deposit_ID;
                $array = array_map('trim', explode(',', $Deposit_ID));
                $valid = Carbon::parse($invoices->created_at)->format('d/m/Y');
                $proposaldata = Quotation::where('Quotation_ID',$Quotation_ID)->first();
                $type_Proposal =$proposaldata->type_Proposal;
                $created_at = Carbon::parse($invoices->created_at)->format('d/m/Y');
                $template = master_template::query()->latest()->first();
            }
            {
                $Reservation_No = null;
                if ($reservationNo) {
                    $Reservation_No = 'Reservation No : '.$reservationNo;
                }
                $Room_No = null;
                if ($room) {
                    $Room_No = 'Room No : '.$room;
                }
                $NumberOfGuests = null;
                if ($numberOfGuests) {
                    $NumberOfGuests = 'No. of guest : '.$numberOfGuests;
                }
                $Arrival = null;
                if ($arrival) {
                    $Arrival = 'Arrival : '.$arrival;
                }
                $Departure = null;
                if ($departure) {
                    $Departure = 'Departure : '.$departure;
                }
                $PaymentDate = null;
                if ($paymentDate) {
                    $PaymentDate = 'วันที่ชำระ : '.$paymentDate;
                }
                $Note = null;
                if ($note) {
                    $Note = 'รายละเอียด : '.$note;
                }
                $Comp_cash = null;
                if ($Complimentary) {
                    $Comp_cash = 'Complimentary : '.$Complimentary;
                }
                $fullname = 'รหัส : '.$REID;
                $amoute = 'ราคาที่จ่าย : '.number_format($RealAmount) . ' บาท';
                $total = 'ราคาออกเอกสาร : '.number_format($sumpayment) . ' บาท';
                $edit ='รายการ';

                $formattedProductData = [];

                foreach ($groupedData as $product) {
                    $totalAmount =
                                    ($product['cashAmount'] ?? 0) +
                                    ($product['Complimentary'] ?? 0) +
                                    ($product['bankTransferAmount'] ?? 0) +
                                    ($product['creditCardAmount'] ?? 0) +
                                    ($product['chequeamount'] ?? 0) +
                                    ($product['NoShowAmount'] ?? 0);

                    $formattedPrice = number_format($totalAmount) . ' บาท';
                    $formattedProductData[] = 'Description : ' . $product['detail'] . ' , '  . 'Price item : ' . $formattedPrice;
                }

                $datacompany = '';

                $variables = [$fullname, $Reservation_No,$Room_No,$NumberOfGuests, $Arrival,$Departure,$PaymentDate,$Note,$amoute,$total,$edit];
                $formattedProductDataString = implode(' + ', $formattedProductData);
                // แปลง array ของ $formattedProductData เป็น string เดียวที่มีรายการทั้งหมด
                $variables[] = $formattedProductDataString;


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
                $save->Company_ID = $REID;
                $save->type = 'Create';
                $save->Category = 'Create :: Billing Folio';
                $save->content =$datacompany;
                $save->save();
            }
            {
                $settingCompany = Master_company::orderBy('id', 'desc')->first();
                $parts = explode('-', $guest);
                $firstPart = $parts[0];
                if ($firstPart == 'C') {
                    $company =  companys::where('Profile_ID',$guest)->first();
                    if ($company) {
                        $fullname = "";
                        $Company_typeID=$company->Company_type;
                        if ($company->Company_Name) {
                            $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                            if ($comtype->name_th =="บริษัทจำกัด") {
                                $fullnameCom = " "." บริษัท ". $company->Company_Name . " จำกัด";
                            }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                                $fullnameCom = " "." บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                            }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                                $fullnameCom = " "." ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                            }else {
                                $fullnameCom = $comtype->name_th . $company->Company_Name;
                            }
                        }else{
                            $fullnameCom = "";
                        }
                        $Address=$company->Address;
                        $CityID=$company->City;
                        $amphuresID = $company->Amphures;
                        $TambonID = $company->Tambon;
                        $Identification = $company->Taxpayer_Identification;
                        $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                        $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                        $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                        if ($provinceNames) {
                            $province = ' จังหวัด '.$provinceNames->name_th;
                            $amphures = ' อำเภอ '.$amphuresID->name_th;
                            $tambon = ' ตำบล '.$TambonID->name_th;
                            $zip_code = $TambonID->Zip_Code;
                        }else{
                            $province ="";
                            $amphures="";
                            $tambon="";
                            $zip_code="";
                        }
                    }else{
                        $company =  company_tax::where('ComTax_ID',$guest)->first();
                        $fullname = $company && $company->Companny_name
                                    ? ""
                                    : 'คุณ ' . $company->first_name . ' ' . $company->last_name;
                        $Company_typeID=$company->Company_type;
                        if ($company->Companny_name) {
                            $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                            if ($comtype->name_th =="บริษัทจำกัด") {
                                $fullnameCom = " "." บริษัท ". $company->Companny_name . " จำกัด";
                            }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                                $fullnameCom = " "." บริษัท ". $company->Companny_name . " จำกัด (มหาชน)";
                            }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                                $fullnameCom = " "." ห้างหุ้นส่วนจำกัด ". $company->Companny_name ;
                            }else {
                                $fullnameCom = $comtype->name_th . $company->Companny_name;
                            }
                        }else{
                            $fullnameCom = "";
                        }
                        $Address=$company->Address;
                        $CityID=$company->City;
                        $amphuresID = $company->Amphures;
                        $TambonID = $company->Tambon;
                        $Identification = $company->Taxpayer_Identification;
                        $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                        $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                        $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                        if ($provinceNames) {
                            $province = ' จังหวัด '.$provinceNames->name_th;
                            $amphures = ' อำเภอ '.$amphuresID->name_th;
                            $tambon = ' ตำบล '.$TambonID->name_th;
                            $zip_code = $TambonID->Zip_Code;
                        }else{
                            $province ="";
                            $amphures="";
                            $tambon="";
                            $zip_code="";
                        }
                    }
                }else{
                    $guestdata =  Guest::where('Profile_ID',$guest)->first();
                    if ($guestdata) {
                        $fullname =  'คุณ '.$guestdata->First_name.' '.$guestdata->Last_name;
                        $fullnameCom = "";
                        $Address=$guestdata->Address;
                        $CityID=$guestdata->City;
                        $amphuresID = $guestdata->Amphures;
                        $TambonID = $guestdata->Tambon;
                        $Identification = $guestdata->Identification_Number;
                        $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                        $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                        $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                        if ($provinceNames) {
                            $province = ' จังหวัด '.$provinceNames->name_th;
                            $amphures = ' อำเภอ '.$amphuresID->name_th;
                            $tambon = ' ตำบล '.$TambonID->name_th;
                            $zip_code = $TambonID->Zip_Code;
                        }else{
                            $province ="";
                            $amphures="";
                            $tambon="";
                            $zip_code="";
                        }
                    }else{
                        $guestdata =  guest_tax::where('GuestTax_ID',$guest)->first();
                        $fullname = $guestdata && $guestdata->Company_name
                                    ? ""
                                    : 'คุณ ' . $guestdata->first_name . ' ' . $guestdata->last_name;
                        $Company_typeID=$guestdata->Company_type;
                        if ($guestdata->Company_name) {
                            $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                            if ($comtype->name_th =="บริษัทจำกัด") {
                                $fullnameCom = " "." บริษัท ". $guestdata->Company_name . " จำกัด";
                            }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                                $fullnameCom = " "." บริษัท ". $guestdata->Company_name . " จำกัด (มหาชน)";
                            }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                                $fullnameCom = " "." ห้างหุ้นส่วนจำกัด ". $guestdata->Company_name ;
                            }else {
                                $fullnameCom = $comtype->name_th . $guestdata->Company_name;
                            }
                        }else{
                            $fullnameCom = "";
                        }
                        $Address=$guestdata->Address;
                        $CityID=$guestdata->City;
                        $amphuresID = $guestdata->Amphures;
                        $TambonID = $guestdata->Tambon;
                        $Identification = $guestdata->Identification_Number;
                        $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                        $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                        $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                        if ($provinceNames) {
                            $province = ' จังหวัด '.$provinceNames->name_th;
                            $amphures = ' อำเภอ '.$amphuresID->name_th;
                            $tambon = ' ตำบล '.$TambonID->name_th;
                            $zip_code = $TambonID->Zip_Code;
                        }else{
                            $province ="";
                            $amphures="";
                            $tambon="";
                            $zip_code="";
                        }

                    }
                }
                $date = Carbon::now();
                $Date = $paymentDate;
                $dateFormatted = $date->format('d/m/Y').' / ';
                $dateTime = $date->format('H:i');
                $Amount = $Amountall;
                $userid = Auth::user()->id;
                $user = User::where('id',$userid)->first();

                $product = [];
                if ($additional_type == 'Cash' || $additional_type == 'Cash Manual') {
                    foreach ($groupedData as $value) {
                        $totalAmount =
                                        ($value['cashAmount'] ?? 0) +
                                        ($value['Complimentary'] ?? 0) +
                                        ($value['bankTransferAmount'] ?? 0) +
                                        ($value['creditCardAmount'] ?? 0) +
                                        ($value['chequeamount'] ?? 0) +
                                        ($value['NoShowAmount'] ?? 0);
                        if ($value['detail'] === 'Cash') {
                            $totalAmount += $additional;
                        }
                        $product[] = [
                            'detail' => $value['detail'],
                            'amount' => $totalAmount,
                        ];
                    }
                }else{
                    foreach ($groupedData as $value) {
                        $totalAmount =
                                        ($value['cashAmount'] ?? 0) +
                                        ($value['Complimentary'] ?? 0) +
                                        ($value['bankTransferAmount'] ?? 0) +
                                        ($value['creditCardAmount'] ?? 0) +
                                        ($value['chequeamount'] ?? 0) +
                                        ($value['NoShowAmount'] ?? 0);
                        $product[] = [
                            'detail' => $value['detail'],
                            'amount' => $totalAmount,
                        ];
                    }
                }
                $productItems = array_merge($product);
                $receive = receive_payment::where('Deposit_ID', $array)
                ->leftJoin('document_receive_item', 'document_receive.Receipt_ID', '=', 'document_receive_item.receive_id')->get();
                $Proposal = Quotation::where('Quotation_ID',$Quotation_ID)->first();
                $Additional = null;
                if ($additional != 0) {
                    $Additional =  proposal_overbill::where('Quotation_ID',$Quotation_ID)->where('status_guest',0)->first();
                }
                $bill =null;
                $key=null;
                $data = [
                    'settingCompany'=>$settingCompany,
                    'fullname'=>$fullname,
                    'fullnameCom'=>$fullnameCom,
                    'Identification'=>$Identification,
                    'Address'=>$Address,
                    'province'=>$province,
                    'amphures'=>$amphures,
                    'tambon'=>$tambon,
                    'zip_code'=>$zip_code,
                    'reservationNo'=>$reservationNo,
                    'room'=>$room,
                    'user'=>$user,
                    'arrival'=>$arrival,
                    'departure'=>$departure,
                    'numberOfGuests'=>$numberOfGuests,
                    'dateFormatted'=>$dateFormatted,
                    'dateTime'=>$dateTime,
                    'created_at'=>$created_at,
                    'Date'=>$Date,
                    'note'=>$note,
                    'productItems'=>$productItems,
                    'invoice'=>$REID,
                    'Amount'=>$Amount,
                    'Proposal'=>$Proposal,
                    'receive'=>$receive ?? [],
                    'Additional'=>$Additional ?? null,
                    'bill'=>$bill ?? 1,
                    'key'=>$key ?? 1,
                ];
                $view= $template->name;
                $pdf = FacadePdf::loadView('billingfolioPDF.'.$view,$data);
                $path = 'PDF/billingfolio/';
                // return $pdf->stream();
                $pdf->save($path . $REID . '.pdf');
                $currentDateTime = Carbon::now();
                $currentDate = $currentDateTime->toDateString(); // Format: YYYY-MM-DD
                $currentTime = $currentDateTime->toTimeString(); // Format: HH:MM:SS

                // Optionally, you can format the date and time as per your requirement
                $formattedDate = $currentDateTime->format('Y-m-d'); // Custom format for date
                $formattedTime = $currentDateTime->format('H:i:s');
                $savePDF = new log();
                $savePDF->Quotation_ID = $REID;
                $savePDF->QuotationType = 'Receipt';
                $savePDF->Company_Name = !empty($fullnameCom) ? $fullnameCom : $fullname;
                $savePDF->Approve_date = $formattedDate;
                $savePDF->Approve_time = $formattedTime;
                $savePDF->save();
            }
            {
                if ($additional != 0) {
                    $Additional =  proposal_overbill::where('Quotation_ID',$Quotation_ID)->where('status_guest',0)->first();
                }
                $user = Auth::user()->id;
                $save = new receive_payment();
                $save->Receipt_ID = $REID;
                $save->Invoice_ID = $invoice;
                $save->Quotation_ID = $Quotation_ID;
                $save->Additional_ID = $Additional->Additional_ID ?? null;
                $save->company = $companyid;
                $save->Amount = $RealAmount;
                $save->fullname = $nameold;
                $save->additional_type = $additional_type;
                $save->additional = $additional;
                $save->complimentary = $complimentary ?? 0;
                $save->additional_cash = $cashcomp ;
                $save->document_amount = $Amount;
                $save->reservationNo = $reservationNo;
                $save->roomNo = $room;
                $save->numberOfGuests = $numberOfGuests;
                $save->arrival = $arrival;
                $save->departure = $departure;
                $save->type_Proposal = $type_Proposal;
                $save->paymentDate = $paymentDate;
                $save->Operated_by = $user;
                $save->note = $note;
                $save->valid =$valid;
                $save->save();
            }
            {
                foreach ($groupedData as $index) {
                    $item = new document_receive_item();
                    $item->receive_id = $REID;
                    $item->detail = $index['detail'];
                    $item->amount =     ($index['cashAmount'] ?? 0) +
                                        ($index['bankTransferAmount'] ?? 0) +
                                        ($index['creditCardAmount'] ?? 0) +
                                        ($index['chequeamount'] ?? 0) +
                                        ($index['NoShowAmount'] ?? 0);
                    $item->type = $index['paymentType'] ?? null;
                    if ($index['paymentType'] == 'bankTransfer') {
                        $item->bank = $index['bank'] ?? null;
                    }elseif ($index['paymentType'] == 'cheque') {
                        $item->bank = $index['chequebank_name'] ?? null;
                    }
                    $item->CardNumber = $index['CardNumber'] ?? null;
                    $item->Expiry = $index['Expiry'] ?? null;
                    $item->Cheque = $index['cheque'] ?? null;
                    $item->Deposit_date = $index['deposit_date'] ?? null;
                    $item->save();
                }
            }
            {
                $saveRe = document_invoices::find($idinvoices);
                $saveRe->document_status = 3;
                $saveRe->save();
                foreach ($groupedData as $index) {
                    if (!empty($index['cheque'])) {
                        $chequeRe =receive_cheque::where('cheque_number',$index['cheque'])->where('status',1)->first();
                        $id_cheque = $chequeRe->id;
                        $savecheque = receive_cheque::find($id_cheque);
                        $savecheque->receive_payment =$index['chequebank'];
                        $savecheque->status = 2;
                        $savecheque->deduct_date = $formattedDate;
                        $savecheque->deduct_by = $userid;
                        $savecheque->save();
                    }
                }
            }
            {
                $invoiceid = $request->invoice;
                $invoices = document_invoices::where('Invoice_ID', $invoiceid)->first();
                $Quotation_ID = $invoices->Quotation_ID;
                if ($additional != 0) {
                    $Additional =  proposal_overbill::where('Quotation_ID',$Quotation_ID)->where('status_guest',0)->first();
                    $AdditionalID = $Additional->id;
                    $saveAD = proposal_overbill::find($AdditionalID);
                    $saveAD->status_guest = 1;
                    $saveAD->save();
                }
            }
            {
                $invoiceid = $request->invoice;
                $invoices = document_invoices::where('Invoice_ID', $invoiceid)->first();
                $Quotation_ID = $invoices->Quotation_ID;
                $receive = receive_payment::where('Quotation_ID',$Quotation_ID)->get();
                $Amounttotal = 0;
                $Comtotal = 0;
                foreach ($receive as $value) {
                    $Amounttotal += $value->Amount;
                    $Comtotal += $value->complimentary;
                }
                $proposal = Quotation::where('Quotation_ID',$Quotation_ID)->first();
                $id = $proposal->id;
                $Additional =  proposal_overbill::where('Quotation_ID',$Quotation_ID)->first();
                $Additional_Nettotal =0;
                if ($Additional) {
                    $Additional_Nettotal = $Additional->Nettotal;
                }
                $Nettotal = $proposal->Nettotal;
                $amountMain=$Nettotal+$Additional_Nettotal;
                $amountPaid=$Amounttotal+$Comtotal;
                $total = $amountMain-$amountPaid;
                foreach ($receive as $value) {
                    $value->document_status = 2;
                    $value->save();
                }
                $update = Quotation::find($id);
                $update->status_document = 9;
                $update->save();
            }
            DB::commit();
            return redirect()->route('BillingFolio.index')->with('success', 'Data has been successfully saved.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->route('BillingFolio.index')->with('error', $e->getMessage());
        }

    }
    public function update(Request $request , $id) {
        $data = $request->all();

        $guest = $request->Guest;
        $reservationNo = $request->reservationNo;
        $roomNo = $request->roomNo;
        $numberOfGuests = $request->numberOfGuests;
        $arrival = $request->arrival;
        $departure = $request->departure;
        $parts = explode('-', $guest);
        $firstPart = $parts[0];
        if ($firstPart == 'C') {
            $company =  companys::where('Profile_ID',$guest)->first();
            if ($company) {
                $type_Proposal = 'Company';
                $Company_typeID=$company->Company_type;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="บริษัทจำกัด") {
                    $name = "ลูกค้า : "." บริษัท ". $company->Company_Name . " จำกัด";
                    $nameold = "บริษัท ". $company->Company_Name . " จำกัด";
                }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                    $name = "ลูกค้า : "." บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                    $nameold = "บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                    $name = "ลูกค้า : "." ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                    $nameold = "ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                }else {
                    $name = 'ลูกค้า : '.$comtype->name_th . $company->Company_Name;
                    $nameold = $comtype->name_th . $company->Company_Name;
                }
            }else{
                $company =  company_tax::where('ComTax_ID',$guest)->first();
                $type_Proposal = 'company_tax';
                $Company_typeID=$company->Company_type;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($company->Companny_name) {
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $name = "ลูกค้า : "." บริษัท ". $company->Companny_name . " จำกัด";
                        $nameold = "บริษัท ". $company->Companny_name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $name = "ลูกค้า : "." บริษัท ". $company->Companny_name . " จำกัด (มหาชน)";
                        $nameold = "บริษัท ". $company->Companny_name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $name = "ลูกค้า : "." ห้างหุ้นส่วนจำกัด ". $company->Companny_name ;
                        $nameold = "ห้างหุ้นส่วนจำกัด ". $company->Companny_name ;
                    }else {
                        $name = 'ลูกค้า : '.$comtype->name_th . $company->Companny_name;
                        $nameold = $comtype->name_th . $company->Companny_name;
                    }
                }else{
                    $name =  'ลูกค้า : '.'คุณ '.$company->first_name.' '.$company->last_name;
                    $nameold = 'คุณ '.$company->first_name.' '.$company->last_name;
                }
            }
        }else{
            $guestdata =  Guest::where('Profile_ID',$guest)->first();
            if ($guestdata) {
                $type_Proposal = 'Guest';
                $name =  'ลูกค้า : '.'คุณ '.$guestdata->First_name.' '.$guestdata->Last_name;
                $nameold = 'คุณ '.$guestdata->First_name.' '.$guestdata->Last_name;
            }else{
                $guestdata =  guest_tax::where('GuestTax_ID',$guest)->first();
                $type_Proposal = 'guest_tax';
                $Company_typeID=$guestdata->Company_type;
                if ($guestdata->Company_name) {
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $name = "ลูกค้า : "." บริษัท ". $guestdata->Company_name . " จำกัด";
                        $nameold = "บริษัท ". $guestdata->Company_name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $name = "ลูกค้า : "." บริษัท ". $guestdata->Company_name . " จำกัด (มหาชน)";
                        $nameold = "บริษัท ". $guestdata->Company_name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $name = "ลูกค้า : "." ห้างหุ้นส่วนจำกัด ". $guestdata->Company_name ;
                        $nameold = "ห้างหุ้นส่วนจำกัด ". $guestdata->Company_name ;
                    }else {
                        $name = "ลูกค้า : ".$comtype->name_th . $guestdata->Company_name;
                        $nameold = $comtype->name_th . $guestdata->Company_name;
                    }
                }else{
                    $name =  'ลูกค้า : '.'คุณ '.$guestdata->first_name.' '.$guestdata->last_name;
                    $nameold = 'คุณ '.$guestdata->first_name.' '.$guestdata->last_name;
                }
            }
        }
        $dataArray= receive_payment::where('id',$id)->first();
        $REID =  $dataArray->Receipt_ID;
        $Invoice_ID =  $dataArray->Invoice_ID;
        $fullnameold =  $dataArray->fullname ?? '-';
        $correct = $dataArray->correct;
        $sumpayment =$dataArray->document_amount;
        $paymentDate =$dataArray->paymentDate;
        $note =$dataArray->note;
        $document_status =$dataArray->document_status;
        if ($correct >= 1) {
            $correctup = $correct + 1;
        }else{
            $correctup = 1;
        }
        $datamain = [
            'fullname' => $nameold,
            'reservationNo'=>$data['reservationNo'] ?? null,
            'roomNo'=>$data['roomNo'] ?? null,
            'numberOfGuests'=>$data['numberOfGuests'] ?? null,
            'arrival'=>$data['arrival'] ?? null,
            'departure'=>$data['departure'] ?? null,
        ];



        try {
            $keysToCompare = ['fullname','roomNo','numberOfGuests','arrival','departure','reservationNo'];
            $differences = [];
            foreach ($keysToCompare as $key) {
                if (isset($dataArray[$key]) && isset($datamain[$key])) {
                    // แปลงค่าของ $dataArray และ $data เป็นชุดข้อมูลเพื่อหาค่าที่แตกต่างกัน
                    $dataArraySet = collect($dataArray[$key]);
                    $dataSet = collect($datamain[$key]);

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
                if (isset($value['request'][0])) {
                    // สำหรับคีย์อื่นๆ ให้เก็บค่าแรกจาก array
                    $extractedData[$key] = $value['request'][0];
                }else{
                    $extractedDataA[$key] = $value['dataArray'][0];
                }
            }
            $reservationNo = $extractedData['reservationNo'] ?? null;
            $roomNo = $extractedData['roomNo'] ?? null;
            $numberOfGuests =  $extractedData['numberOfGuests'] ?? null;
            $arrival =  $extractedData['arrival'] ?? null;
            $departure =  $extractedData['departure'] ?? null;
            $fullname = $extractedData['fullname'] ?? null;
            $Reservation_No = null;
            if ($reservationNo) {
                $Reservation_No = 'Reservation No : '.$reservationNo;
            }
            $Room_No = null;
            if ($roomNo) {
                $Room_No = 'Room No : '.$roomNo;
            }
            $NumberOfGuests = null;
            if ($numberOfGuests) {
                $NumberOfGuests = 'No. of guest : '.$numberOfGuests;
            }
            $Arrival = null;
            if ($arrival) {
                $Arrival = 'Arrival : '.$arrival;
            }
            $Departure = null;
            if ($departure) {
                $Departure = 'Departure : '.$departure;
            }
            $FullName = null;
            if ($fullname) {
                $FullName = 'ลูกค้า : '.$fullname;
            }
            $fullname = 'รหัส : '.$REID;
            $edit = 'แก้ไข';
            $status = null;
            if ($document_status == 4) {
                $status = 'สถานะเอกสาร';
            }
            $datacompany = '';

            $variables = [$fullname,$edit,$status,$FullName, $Reservation_No,$Room_No,$NumberOfGuests, $Arrival,$Departure];
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
            $save->Company_ID = $REID;
            $save->type = 'Edit';
            $save->Category = 'Edit :: Billing Folio';
            $save->content =$datacompany;
            $save->save();
        } catch (\Throwable $e) {
            return redirect()->route('BillingFolio.index')->with('error', $e->getMessage());
        }
        try {
            $save = receive_payment::find($id);
            $save->company = $guest;
            $save->fullname = $nameold;
            $save->reservationNo = $request->reservationNo;
            $save->roomNo = $request->roomNo;
            $save->numberOfGuests = $request->numberOfGuests;
            $save->arrival = $request->arrival;
            $save->departure = $request->departure;
            $save->correct = $correctup;
            $save->document_status = 1;
            $save->save();
        } catch (\Throwable $e) {
            return redirect()->route('BillingFolio.index')->with('error', $e->getMessage());
        }
        try {
            $settingCompany = Master_company::orderBy('id', 'desc')->first();
            $parts = explode('-', $guest);
            $firstPart = $parts[0];
            if ($firstPart == 'C') {
                $company =  companys::where('Profile_ID',$guest)->first();
                if ($company) {
                    $fullname = "";
                    $Company_typeID=$company->Company_type;
                    if ($company->Company_Name) {
                        $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                        if ($comtype->name_th =="บริษัทจำกัด") {
                            $fullnameCom = " "." บริษัท ". $company->Company_Name . " จำกัด";
                        }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                            $fullnameCom = " "." บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                        }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                            $fullnameCom = " "." ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                        }else {
                            $fullnameCom = $comtype->name_th . $company->Company_Name;
                        }
                    }else{
                        $fullnameCom = "";
                    }
                    $Address=$company->Address;
                    $CityID=$company->City;
                    $amphuresID = $company->Amphures;
                    $TambonID = $company->Tambon;
                    $Identification = $company->Taxpayer_Identification;
                    $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                    $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                    $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                    if ($provinceNames) {
                        $province = ' จังหวัด '.$provinceNames->name_th;
                        $amphures = ' อำเภอ '.$amphuresID->name_th;
                        $tambon = ' ตำบล '.$TambonID->name_th;
                        $zip_code = $TambonID->Zip_Code;
                    }else{
                        $province ="";
                        $amphures="";
                        $tambon="";
                        $zip_code="";
                    }
                }else{
                    $company =  company_tax::where('ComTax_ID',$guest)->first();
                    $fullname = $company && $company->Companny_name
                                ? ""
                                : 'คุณ ' . $company->first_name . ' ' . $company->last_name;
                    $Company_typeID=$company->Company_type;
                    if ($company->Companny_name) {
                        $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                        if ($comtype->name_th =="บริษัทจำกัด") {
                            $fullnameCom = " "." บริษัท ". $company->Companny_name . " จำกัด";
                        }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                            $fullnameCom = " "." บริษัท ". $company->Companny_name . " จำกัด (มหาชน)";
                        }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                            $fullnameCom = " "." ห้างหุ้นส่วนจำกัด ". $company->Companny_name ;
                        }else {
                            $fullnameCom = $comtype->name_th . $company->Companny_name;
                        }
                    }else{
                        $fullnameCom = "";
                    }
                    $Address=$company->Address;
                    $CityID=$company->City;
                    $amphuresID = $company->Amphures;
                    $TambonID = $company->Tambon;
                    $Identification = $company->Taxpayer_Identification;
                    $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                    $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                    $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                    if ($provinceNames) {
                        $province = ' จังหวัด '.$provinceNames->name_th;
                        $amphures = ' อำเภอ '.$amphuresID->name_th;
                        $tambon = ' ตำบล '.$TambonID->name_th;
                        $zip_code = $TambonID->Zip_Code;
                    }else{
                        $province ="";
                        $amphures="";
                        $tambon="";
                        $zip_code="";
                    }
                }
            }else{
                $guestdata =  Guest::where('Profile_ID',$guest)->first();
                if ($guestdata) {
                    $fullname =  'คุณ '.$guestdata->First_name.' '.$guestdata->Last_name;
                    $fullnameCom = "";
                    $Address=$guestdata->Address;
                    $CityID=$guestdata->City;
                    $amphuresID = $guestdata->Amphures;
                    $TambonID = $guestdata->Tambon;
                    $Identification = $guestdata->Identification_Number;
                    $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                    $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                    $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                    if ($provinceNames) {
                        $province = ' จังหวัด '.$provinceNames->name_th;
                        $amphures = ' อำเภอ '.$amphuresID->name_th;
                        $tambon = ' ตำบล '.$TambonID->name_th;
                        $zip_code = $TambonID->Zip_Code;
                    }else{
                        $province ="";
                        $amphures="";
                        $tambon="";
                        $zip_code="";
                    }
                }else{
                    $guestdata =  guest_tax::where('GuestTax_ID',$guest)->first();
                    $fullname = $guestdata && $guestdata->Company_name
                                ? ""
                                : 'คุณ ' . $guestdata->first_name . ' ' . $guestdata->last_name;
                    $Company_typeID=$guestdata->Company_type;
                    if ($guestdata->Company_name) {
                        $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                        if ($comtype->name_th =="บริษัทจำกัด") {
                            $fullnameCom = " "." บริษัท ". $guestdata->Company_name . " จำกัด";
                        }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                            $fullnameCom = " "." บริษัท ". $guestdata->Company_name . " จำกัด (มหาชน)";
                        }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                            $fullnameCom = " "." ห้างหุ้นส่วนจำกัด ". $guestdata->Company_name ;
                        }else {
                            $fullnameCom = $comtype->name_th . $guestdata->Company_name;
                        }
                    }else{
                        $fullnameCom = "";
                    }
                    $Address=$guestdata->Address;
                    $CityID=$guestdata->City;
                    $amphuresID = $guestdata->Amphures;
                    $TambonID = $guestdata->Tambon;
                    $Identification = $guestdata->Identification_Number;
                    $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                    $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                    $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                    if ($provinceNames) {
                        $province = ' จังหวัด '.$provinceNames->name_th;
                        $amphures = ' อำเภอ '.$amphuresID->name_th;
                        $tambon = ' ตำบล '.$TambonID->name_th;
                        $zip_code = $TambonID->Zip_Code;
                    }else{
                        $province ="";
                        $amphures="";
                        $tambon="";
                        $zip_code="";
                    }

                }
            }
            $date = Carbon::now();
            $Date = $paymentDate;
            $dateFormatted = $date->format('d/m/Y').' / ';
            $dateTime = $date->format('H:i');
            $Amount = $sumpayment;
            $userid = Auth::user()->id;
            $user = User::where('id',$userid)->first();
            $invoices = document_invoices::where('Invoice_ID', $Invoice_ID)->first();
            $created_at = Carbon::parse($invoices->created_at)->format('d/m/Y');
            $productItems = document_receive_item::where('receive_id', $REID)
            ->get()
            ->map(function ($value) {
                return [
                    'detail' => $value->detail,
                    'amount' => $value->amount,
                ];
            })
            ->toArray();
            $reservationNo = $request->reservationNo;
            $room = $request->roomNo;
            $numberOfGuests = $request->numberOfGuests;
            $arrival = $request->arrival;
            $departure = $request->departure;
            $template = master_template::query()->latest()->first();
            $data = [
                'settingCompany'=>$settingCompany,
                'fullname'=>$fullname,
                'fullnameCom'=>$fullnameCom,
                'Identification'=>$Identification,
                'Address'=>$Address,
                'province'=>$province,
                'amphures'=>$amphures,
                'tambon'=>$tambon,
                'zip_code'=>$zip_code,
                'reservationNo'=>$reservationNo,
                'room'=>$room,
                'user'=>$user,
                'arrival'=>$arrival,
                'departure'=>$departure,
                'numberOfGuests'=>$numberOfGuests,
                'dateFormatted'=>$dateFormatted,
                'dateTime'=>$dateTime,
                'created_at'=>$created_at,
                'Date'=>$Date,
                'note'=>$note,
                'productItems'=>$productItems,
                'invoice'=>$REID,
                'Amount'=>$Amount,

            ];
            $view= $template->name;
            $pdf = FacadePdf::loadView('billingfolioPDF.'.$view,$data);
            $path = 'Log_PDF/billingfolio/';
            $pdf->save($path . $REID.'-'.$correctup . '.pdf');
            $currentDateTime = Carbon::now();
            $currentDate = $currentDateTime->toDateString(); // Format: YYYY-MM-DD
            $currentTime = $currentDateTime->toTimeString(); // Format: HH:MM:SS

            // Optionally, you can format the date and time as per your requirement
            $formattedDate = $currentDateTime->format('Y-m-d'); // Custom format for date
            $formattedTime = $currentDateTime->format('H:i:s');
            $savePDF = new log();
            $savePDF->Quotation_ID = $REID;
            $savePDF->QuotationType = 'Receipt';
            $savePDF->Company_Name = !empty($fullnameCom) ? $fullnameCom : $fullname;
            $savePDF->Approve_date = $formattedDate;
            $savePDF->Approve_time = $formattedTime;
            $savePDF->correct = $correctup;
            $savePDF->save();
        } catch (\Throwable $e) {
            return redirect()->route('BillingFolio.index')->with('error', $e->getMessage());
        }
        return redirect()->route('BillingFolio.index')->with('success', 'Data has been successfully saved.');
    }
    public function view($id){
        $receive = receive_payment::where('id',$id)->first();
        $Quotation_ID = $receive->Quotation_ID;
        $bill = receive_payment::where('Invoice_ID', $receive->Invoice_ID)
        ->whereNotNull('num_bill')
        ->where('num_bill', '!=', '')
        ->count();
        $Additional = null;
        $Proposal = null;
        $receivededuct = [];
        if ($receive->Invoice_ID) {
            $Additional_ID = $receive->Additional_ID;
            $Additional =  proposal_overbill::where('Additional_ID',$Additional_ID)->first();
            $Proposal = Quotation::where('Quotation_ID',$Quotation_ID)->first();
            $invoices = document_invoices::where('Invoice_ID', $receive->Invoice_ID)->first();
            $Deposit_ID = $invoices->Deposit_ID;
            $array = array_map('trim', explode(',', $Deposit_ID));
            $receivededuct = receive_payment::where('Deposit_ID', $array)
            ->leftJoin('document_receive_item', 'document_receive.Receipt_ID', '=', 'document_receive_item.receive_id')->get();
        }
        $Quotation = Quotation::where('Quotation_ID',$Quotation_ID)->first();
        $additional = $receive->additional;
        $num_bill = $receive->num_bill ?? 1;
        $ids = $Quotation->id;
        if ($receive->additional_type == null) {

            $productItems = document_receive_item::where('receive_id', $receive->Receipt_ID)
            ->get()
            ->map(function ($value) {
                return [
                    'detail' => $value->detail,
                    'amount' => $value->amount,
                ];
            })
            ->toArray();
        }else{

            $productItems = document_receive_item::where('receive_id', $receive->Receipt_ID)
            ->get()
            ->map(function ($value) {
                return [
                    'detail' => $value->detail,
                    'amount' => $value->amount,
                ];
            })
            ->groupBy('detail')
            ->map(function ($group, $detail) use ($receive) {
                $totalAmount = $group->sum('amount');

                // ถ้าเป็น 'Cash' ให้บวก $receive->additional
                if ($detail === 'Cash') {
                    $totalAmount += $receive->additional;
                }

                return [
                    'detail' => $detail,
                    'amount' => $totalAmount
                ];
            })
            ->values() // รีเซ็ต key index ให้เป็น array ปกติ
            ->toArray();
        }


        $settingCompany = Master_company::orderBy('id', 'desc')->first();
        $guest = $receive->company;
        $paymentDate = $receive->paymentDate;
        $sumpayment= $receive->document_amount;
        $Invoice_ID = $receive->Invoice_ID;
        $Deposit_ID = $receive->Deposit_ID;
        $reservationNo = $receive->reservationNo;
        $room = $receive->roomNo;
        $numberOfGuests = $receive->numberOfGuests;
        $arrival = $receive->arrival;
        $departure = $receive->departure;
        $note= $receive->note;
        $REID = $receive->Receipt_ID;
        $parts = explode('-', $guest);
        $firstPart = $parts[0];
        if ($firstPart == 'C') {
            $company =  companys::where('Profile_ID',$guest)->first();
            if ($company) {
                $fullname = "";
                $Company_typeID=$company->Company_type;
                if ($company->Company_Name) {
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $fullnameCom = " "." บริษัท ". $company->Company_Name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $fullnameCom = " "." บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $fullnameCom = " "." ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                    }else {
                        $fullnameCom = $comtype->name_th . $company->Company_Name;
                    }
                }else{
                    $fullnameCom = "";
                }
                $Address=$company->Address;
                $CityID=$company->City;
                $amphuresID = $company->Amphures;
                $TambonID = $company->Tambon;
                $Identification = $company->Taxpayer_Identification;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                if ($provinceNames) {
                    $province = ' จังหวัด '.$provinceNames->name_th;
                    $amphures = ' อำเภอ '.$amphuresID->name_th;
                    $tambon = ' ตำบล '.$TambonID->name_th;
                    $zip_code = $TambonID->Zip_Code;
                }else{
                    $province ="";
                    $amphures="";
                    $tambon="";
                    $zip_code="";
                }
            }else{
                $company =  company_tax::where('ComTax_ID',$guest)->first();
                $fullname = $company && $company->Companny_name
                            ? ""
                            : 'คุณ ' . $company->first_name . ' ' . $company->last_name;
                $Company_typeID=$company->Company_type;
                if ($company->Companny_name) {
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $fullnameCom = " "." บริษัท ". $company->Companny_name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $fullnameCom = " "." บริษัท ". $company->Companny_name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $fullnameCom = " "." ห้างหุ้นส่วนจำกัด ". $company->Companny_name ;
                    }else {
                        $fullnameCom = $comtype->name_th . $company->Companny_name;
                    }
                }else{
                    $fullnameCom = "";
                }
                $Address=$company->Address;
                $CityID=$company->City;
                $amphuresID = $company->Amphures;
                $TambonID = $company->Tambon;
                $Identification = $company->Taxpayer_Identification;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                if ($provinceNames) {
                    $province = ' จังหวัด '.$provinceNames->name_th;
                    $amphures = ' อำเภอ '.$amphuresID->name_th;
                    $tambon = ' ตำบล '.$TambonID->name_th;
                    $zip_code = $TambonID->Zip_Code;
                }else{
                    $province ="";
                    $amphures="";
                    $tambon="";
                    $zip_code="";
                }
            }
        }else{
            $guestdata =  Guest::where('Profile_ID',$guest)->first();
            if ($guestdata) {
                $fullname =  'คุณ '.$guestdata->First_name.' '.$guestdata->Last_name;
                $fullnameCom = "";
                $Address=$guestdata->Address;
                $CityID=$guestdata->City;
                $amphuresID = $guestdata->Amphures;
                $TambonID = $guestdata->Tambon;
                $Identification = $guestdata->Identification_Number;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                if ($provinceNames) {
                    $province = ' จังหวัด '.$provinceNames->name_th;
                    $amphures = ' อำเภอ '.$amphuresID->name_th;
                    $tambon = ' ตำบล '.$TambonID->name_th;
                    $zip_code = $TambonID->Zip_Code;
                }else{
                    $province ="";
                    $amphures="";
                    $tambon="";
                    $zip_code="";
                }
            }else{
                $guestdata =  guest_tax::where('GuestTax_ID',$guest)->first();
                $fullname = $guestdata && $guestdata->Company_name
                            ? ""
                            : 'คุณ ' . $guestdata->first_name . ' ' . $guestdata->last_name;
                $Company_typeID=$guestdata->Company_type;
                if ($guestdata->Company_name) {
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $fullnameCom = " "." บริษัท ". $guestdata->Company_name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $fullnameCom = " "." บริษัท ". $guestdata->Company_name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $fullnameCom = " "." ห้างหุ้นส่วนจำกัด ". $guestdata->Company_name ;
                    }else {
                        $fullnameCom = $comtype->name_th . $guestdata->Company_name;
                    }
                }else{
                    $fullnameCom = "";
                }
                $Address=$guestdata->Address;
                $CityID=$guestdata->City;
                $amphuresID = $guestdata->Amphures;
                $TambonID = $guestdata->Tambon;
                $Identification = $guestdata->Identification_Number;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                if ($provinceNames) {
                    $province = ' จังหวัด '.$provinceNames->name_th;
                    $amphures = ' อำเภอ '.$amphuresID->name_th;
                    $tambon = ' ตำบล '.$TambonID->name_th;
                    $zip_code = $TambonID->Zip_Code;
                }else{
                    $province ="";
                    $amphures="";
                    $tambon="";
                    $zip_code="";
                }

            }
        }
        $date = Carbon::now();
        $Date = $paymentDate;
        $dateFormatted = $date->format('d/m/Y');
        $dateTime = $date->format('H:i');
        $Amount = $sumpayment;
        $userid = Auth::user()->id;
        $user = User::where('id',$userid)->first();
        $invoices = document_invoices::where('Invoice_ID', $Invoice_ID)->first();
        $created_at =$receive->valid;
        $template = master_template::query()->latest()->first();
        $data = [
            'fullname'=>$fullname,
            'fullnameCom'=>$fullnameCom,
            'Identification'=>$Identification,
            'Address'=>$Address,
            'province'=>$province,
            'amphures'=>$amphures,
            'tambon'=>$tambon,
            'zip_code'=>$zip_code,
            'reservationNo'=>$reservationNo,
            'room'=>$room,
            'user'=>$user,
            'arrival'=>$arrival,
            'departure'=>$departure,
            'numberOfGuests'=>$numberOfGuests,
            'dateFormatted'=>$dateFormatted,
            'dateTime'=>$dateTime,
            'created_at'=>$created_at,
            'note'=>$note,
            'invoice'=>$REID,
            'Amount'=>$Amount,
        ];
        return view('billingfolio.invoice.view',compact('data','settingCompany','productItems','ids','Date','num_bill','Additional','Proposal','receivededuct','bill'));

    }

    public function export($id){
        $receivedetail = receive_payment::where('id',$id)->first();
        $key = $receivedetail->num_bill;
        $Quotation_ID = $receivedetail->Quotation_ID;
        $bill = receive_payment::where('Invoice_ID', $receivedetail->Invoice_ID)
        ->whereNotNull('num_bill')
        ->where('num_bill', '!=', '')
        ->count();
        $bill = ($bill == 0) ? 1 : $bill;
        $Additional = null;
        $Proposal = null;
        $receive = [];
        if ($receivedetail->Invoice_ID) {
            $Additional_ID = $receivedetail->Additional_ID;
            $Additional =  proposal_overbill::where('Additional_ID',$Additional_ID)->first();
            $Proposal = Quotation::where('Quotation_ID',$Quotation_ID)->first();
            $invoices = document_invoices::where('Invoice_ID', $receivedetail->Invoice_ID)->first();
            $Deposit_ID = $invoices->Deposit_ID;
            $array = array_map('trim', explode(',', $Deposit_ID));
            $receive = receive_payment::where('Deposit_ID', $array)
            ->leftJoin('document_receive_item', 'document_receive.Receipt_ID', '=', 'document_receive_item.receive_id')->get();
        }
        $Quotation = Quotation::where('Quotation_ID',$Quotation_ID)->first();
        $additional = $receivedetail->additional;
        $ids = $Quotation->id;
        if ($receivedetail->additional_type == null) {

            $productItems = document_receive_item::where('receive_id', $receivedetail->Receipt_ID)
            ->get()
            ->map(function ($value) {
                return [
                    'detail' => $value->detail,
                    'amount' => $value->amount,
                ];
            })
            ->toArray();
        }else{

            $productItems = document_receive_item::where('receive_id', $receivedetail->Receipt_ID)
            ->get()
            ->map(function ($value) {
                return [
                    'detail' => $value->detail,
                    'amount' => $value->amount,
                ];
            })
            ->groupBy('detail')
            ->map(function ($group, $detail) use ($receivedetail) {
                $totalAmount = $group->sum('amount');

                // ถ้าเป็น 'Cash' ให้บวก $receive->additional
                if ($detail === 'Cash') {
                    $totalAmount += $receivedetail->additional;
                }

                return [
                    'detail' => $detail,
                    'amount' => $totalAmount
                ];
            })
            ->values() // รีเซ็ต key index ให้เป็น array ปกติ
            ->toArray();
        }


        $settingCompany = Master_company::orderBy('id', 'desc')->first();
        $guest = $receivedetail->company;
        $paymentDate = $receivedetail->paymentDate;
        $sumpayment= $receivedetail->document_amount;
        $Invoice_ID = $receivedetail->Invoice_ID;
        $Deposit_ID = $receivedetail->Deposit_ID;
        $reservationNo = $receivedetail->reservationNo;
        $room = $receivedetail->roomNo;
        $numberOfGuests = $receivedetail->numberOfGuests;
        $arrival = $receivedetail->arrival;
        $departure = $receivedetail->departure;
        $note= $receivedetail->note;
        $REID = $receivedetail->Receipt_ID;
        $parts = explode('-', $guest);
        $firstPart = $parts[0];
        if ($firstPart == 'C') {
            $company =  companys::where('Profile_ID',$guest)->first();
            if ($company) {
                $fullname = "";
                $Company_typeID=$company->Company_type;
                if ($company->Company_Name) {
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $fullnameCom = " "." บริษัท ". $company->Company_Name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $fullnameCom = " "." บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $fullnameCom = " "." ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                    }else {
                        $fullnameCom = $comtype->name_th . $company->Company_Name;
                    }
                }else{
                    $fullnameCom = "";
                }
                $Address=$company->Address;
                $CityID=$company->City;
                $amphuresID = $company->Amphures;
                $TambonID = $company->Tambon;
                $Identification = $company->Taxpayer_Identification;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                if ($provinceNames) {
                    $province = ' จังหวัด '.$provinceNames->name_th;
                    $amphures = ' อำเภอ '.$amphuresID->name_th;
                    $tambon = ' ตำบล '.$TambonID->name_th;
                    $zip_code = $TambonID->Zip_Code;
                }else{
                    $province ="";
                    $amphures="";
                    $tambon="";
                    $zip_code="";
                }
            }else{
                $company =  company_tax::where('ComTax_ID',$guest)->first();
                $fullname = $company && $company->Companny_name
                            ? ""
                            : 'คุณ ' . $company->first_name . ' ' . $company->last_name;
                $Company_typeID=$company->Company_type;
                if ($company->Companny_name) {
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $fullnameCom = " "." บริษัท ". $company->Companny_name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $fullnameCom = " "." บริษัท ". $company->Companny_name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $fullnameCom = " "." ห้างหุ้นส่วนจำกัด ". $company->Companny_name ;
                    }else {
                        $fullnameCom = $comtype->name_th . $company->Companny_name;
                    }
                }else{
                    $fullnameCom = "";
                }
                $Address=$company->Address;
                $CityID=$company->City;
                $amphuresID = $company->Amphures;
                $TambonID = $company->Tambon;
                $Identification = $company->Taxpayer_Identification;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                if ($provinceNames) {
                    $province = ' จังหวัด '.$provinceNames->name_th;
                    $amphures = ' อำเภอ '.$amphuresID->name_th;
                    $tambon = ' ตำบล '.$TambonID->name_th;
                    $zip_code = $TambonID->Zip_Code;
                }else{
                    $province ="";
                    $amphures="";
                    $tambon="";
                    $zip_code="";
                }
            }
        }else{
            $guestdata =  Guest::where('Profile_ID',$guest)->first();
            if ($guestdata) {
                $fullname =  'คุณ '.$guestdata->First_name.' '.$guestdata->Last_name;
                $fullnameCom = "";
                $Address=$guestdata->Address;
                $CityID=$guestdata->City;
                $amphuresID = $guestdata->Amphures;
                $TambonID = $guestdata->Tambon;
                $Identification = $guestdata->Identification_Number;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                if ($provinceNames) {
                    $province = ' จังหวัด '.$provinceNames->name_th;
                    $amphures = ' อำเภอ '.$amphuresID->name_th;
                    $tambon = ' ตำบล '.$TambonID->name_th;
                    $zip_code = $TambonID->Zip_Code;
                }else{
                    $province ="";
                    $amphures="";
                    $tambon="";
                    $zip_code="";
                }
            }else{
                $guestdata =  guest_tax::where('GuestTax_ID',$guest)->first();
                $fullname = $guestdata && $guestdata->Company_name
                            ? ""
                            : 'คุณ ' . $guestdata->first_name . ' ' . $guestdata->last_name;
                $Company_typeID=$guestdata->Company_type;
                if ($guestdata->Company_name) {
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $fullnameCom = " "." บริษัท ". $guestdata->Company_name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $fullnameCom = " "." บริษัท ". $guestdata->Company_name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $fullnameCom = " "." ห้างหุ้นส่วนจำกัด ". $guestdata->Company_name ;
                    }else {
                        $fullnameCom = $comtype->name_th . $guestdata->Company_name;
                    }
                }else{
                    $fullnameCom = "";
                }
                $Address=$guestdata->Address;
                $CityID=$guestdata->City;
                $amphuresID = $guestdata->Amphures;
                $TambonID = $guestdata->Tambon;
                $Identification = $guestdata->Identification_Number;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                if ($provinceNames) {
                    $province = ' จังหวัด '.$provinceNames->name_th;
                    $amphures = ' อำเภอ '.$amphuresID->name_th;
                    $tambon = ' ตำบล '.$TambonID->name_th;
                    $zip_code = $TambonID->Zip_Code;
                }else{
                    $province ="";
                    $amphures="";
                    $tambon="";
                    $zip_code="";
                }

            }
        }
        $date = Carbon::now();
        $Date = $paymentDate;
        $dateFormatted = $date->format('d/m/Y');
        $dateTime = $date->format('H:i');
        $Amount = $sumpayment;
        $userid = Auth::user()->id;
        $user = User::where('id',$userid)->first();
        $invoices = document_invoices::where('Invoice_ID', $Invoice_ID)->first();
        $created_at =$receivedetail->valid;

        $template = master_template::query()->latest()->first();

        $data = [
            'fullname'=>$fullname,
            'fullnameCom'=>$fullnameCom,
            'Identification'=>$Identification,
            'Address'=>$Address,
            'province'=>$province,
            'amphures'=>$amphures,
            'tambon'=>$tambon,
            'zip_code'=>$zip_code,
            'reservationNo'=>$reservationNo,
            'room'=>$room,
            'user'=>$user,
            'arrival'=>$arrival,
            'departure'=>$departure,
            'numberOfGuests'=>$numberOfGuests,
            'dateFormatted'=>$dateFormatted,
            'settingCompany'=>$settingCompany,
            'dateTime'=>$dateTime,
            'created_at'=>$created_at,
            'note'=>$note,
            'invoice'=>$REID,
            'Amount'=>$Amount,
            'productItems'=>$productItems,
            'bill'=>$bill ?? 1,
            'key'=>$key,
            'Additional'=>$Additional,
            'receive'=>$receive,
            'Proposal'=>$Proposal,
            'Date'=>$Date,
        ];
        $view= $template->name;
        $pdf = FacadePdf::loadView('billingfolioPDF.'.$view,$data);
        $path = 'PDF/billingfolio/';
        return $pdf->stream();
    }
    public function log($id){
        $receive_payment = receive_payment::where('id', $id)->first();
        $correct = $receive_payment->correct;
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        if ($receive_payment) {
            $Receipt_ID = $receive_payment->Receipt_ID;
            // Use a regular expression to capture the part of the string before the first hyphen
            if (preg_match('/^(RE-\d{8})/', $Receipt_ID, $matches)) {
                $Receipt_ID = $matches[1];
            }
        }

        $log = log::where('Quotation_ID',$Receipt_ID)->get();
        $path = 'Log_PDF/billingfolio/';
        $logReceipt = log_company::where('Company_ID', $Receipt_ID)
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('billingfolio.document',compact('log','path','correct','logReceipt','Receipt_ID'));
    }

    public function QuotationView(Request $request ,$id){
        $Quotation = Quotation::where('id', $id)->first();
        $Quotation_ID = $Quotation->Quotation_ID;
        $selectproduct = document_quotation::where('Quotation_ID', $Quotation_ID)->get();
        $datarequest = [
            'Proposal_ID' => $Quotation['Quotation_ID'] ?? null,
            'IssueDate' => $Quotation['issue_date'] ?? null,
            'Expiration' => $Quotation['Expirationdate'] ?? null,
            'Selectdata' => $Quotation['type_Proposal'] ?? null,
            'Data_ID' => $Quotation['Company_ID'] ?? null,
            'Adult' => $Quotation['adult'] ?? null,
            'Children' => $Quotation['children'] ?? null,
            'Mevent' => $Quotation['eventformat'] ?? null,
            'Mvat' => $Quotation['vat_type'] ?? null,
            'DiscountAmount' => $Quotation['SpecialDiscountBath'] ?? null,
            'comment' => $Quotation['comment'] ?? null,
            'PaxToTalall' => $Quotation['TotalPax'] ?? null,
            'Checkin' => $Quotation['checkin'] ?? null,
            'Checkout' => $Quotation['checkout'] ?? null,
            'Day' => $Quotation['day'] ?? null,
            'Night' => $Quotation['night'] ?? null,
            'userid'=> $Quotation['Operated_by'] ?? null,
        ];
        $Products = Arr::wrap($selectproduct->pluck('Product_ID')->toArray());
        $quantities = $selectproduct->pluck('Quantity')->toArray();
        $discounts = $selectproduct->pluck('discount')->toArray();
        $priceUnits = $selectproduct->pluck('priceproduct')->toArray();
        $Unitmain = $selectproduct->pluck('Unit')->toArray();
        $productItems = [];
        $totaldiscount = [];
        foreach ($Products as $index => $productID) {
            if (count($quantities) === count($priceUnits) && count($priceUnits) === count($discounts) && count($priceUnits) === count($Unitmain)) {
                $totalPrices = []; // เปลี่ยนจากตัวแปรเดียวเป็น array เพื่อเก็บผลลัพธ์แต่ละรายการ
                $discountedPrices = [];
                $discountedPricestotal = [];
                $totaldiscount = [];
                // คำนวณราคาสำหรับแต่ละรายการ
                for ($i = 0; $i < count($quantities); $i++) {
                    $quantity = intval($quantities[$i]);
                    $unitValue = intval($Unitmain[$i]); // เปลี่ยนชื่อเป็น $unitValue
                    $priceUnit = floatval(str_replace(',', '', $priceUnits[$i]));
                    $discount = floatval($discounts[$i]);

                    $totaldiscount0 = (($priceUnit * $discount)/100);
                    $totaldiscount[] = $totaldiscount0;

                    $totalPrice = ($quantity * $unitValue) * $priceUnit;
                    $totalPrices[] = $totalPrice;

                    $discountedPrice = (($totalPrice * $discount) / 100);
                    $discountedPrices[] = $discountedPrice;

                    $discountedPriceTotal = $totalPrice - $discountedPrice;
                    $discountedPricestotal[] = $discountedPriceTotal;

                }
            }

            $items = master_product_item::where('Product_ID', $productID)->get();
            $QuotationVat= $datarequest['Mvat'];
            $Mvat = master_document::where('id',$QuotationVat)->where('status', '1')->where('Category','Mvat')->select('name_th','id')->first();
            foreach ($items as $item) {
                // ตรวจสอบและกำหนดค่า quantity และ discount
                $quantity = isset($quantities[$index]) ? $quantities[$index] : 0;
                $unitValue = isset($Unitmain[$index]) ? $Unitmain[$index] : 0;
                $discount = isset($discounts[$index]) ? $discounts[$index] : 0;
                $totalPrices = isset($totalPrices[$index]) ? $totalPrices[$index] : 0;
                $discountedPrices = isset($discountedPrices[$index]) ? $discountedPrices[$index] : 0;
                $discountedPricestotal = isset($discountedPricestotal[$index]) ? $discountedPricestotal[$index] : 0;
                $totaldiscount = isset($totaldiscount[$index]) ? $totaldiscount[$index] : 0;
                $productItems[] = [
                    'product' => $item,
                    'quantity' => $quantity,
                    'unit' => $unitValue,
                    'discount' => $discount,
                    'totalPrices'=>$totalPrices,
                    'discountedPrices'=>$discountedPrices,
                    'discountedPricestotal'=>$discountedPricestotal,
                    'totaldiscount'=>$totaldiscount,
                ];
            }

        }
        {//คำนวน
            $totalAmount = 0;
            $totalPrice = 0;
            $subtotal = 0;
            $beforeTax = 0;
            $AddTax = 0;
            $Nettotal =0;
            $totalaverage=0;

            $SpecialDistext = $datarequest['DiscountAmount'];
            $SpecialDis = floatval($SpecialDistext);
            $totalguest = 0;
            $totalguest = $datarequest['Adult'] + $datarequest['Children'];
            $guest = $datarequest['PaxToTalall'];
            if ($Mvat->id == 50) {
                foreach ($productItems as $item) {
                    $totalPrice += $item['totalPrices'];
                    $totalAmount += $item['discountedPricestotal'];
                    $subtotal = $totalAmount-$SpecialDis;
                    $beforeTax = $subtotal/1.07;
                    $AddTax = $subtotal-$beforeTax;
                    $Nettotal = $subtotal;
                    $totalaverage =$Nettotal/$guest;

                }
            }
            elseif ($Mvat->id == 51) {
                foreach ($productItems as $item) {
                    $totalPrice += $item['totalPrices'];
                    $totalAmount += $item['discountedPricestotal'];
                    $subtotal = $totalAmount-$SpecialDis;
                    $Nettotal = $subtotal;
                    $totalaverage =$Nettotal/$guest;

                }
            }
            elseif ($Mvat->id == 52) {
                foreach ($productItems as $item) {
                    $totalPrice += $item['totalPrices'];
                    $totalAmount += $item['discountedPricestotal'];
                    $subtotal = $totalAmount-$SpecialDis;
                    $AddTax = $subtotal*7/100;
                    $Nettotal = $subtotal+$AddTax;
                    $totalaverage =$Nettotal/$guest;
                }
            }else
            {
                foreach ($productItems as $item) {
                    $totalPrice += $item['totalPrices'];
                    $totalAmount += $item['discountedPricestotal'];
                    $subtotal = $totalAmount-$SpecialDis;
                    $beforeTax = $subtotal/1.07;
                    $AddTax = $subtotal-$beforeTax;
                    $Nettotal = $subtotal;
                    $totalaverage =$Nettotal/$guest;
                }
            }
            $pagecount = count($productItems);
            $page = $pagecount/10;

            $page_item = 1;
            if ($page > 1.1 && $page < 2.1) {
                $page_item += 1;

            } elseif ($page > 1.1) {
            $page_item = 1 + $page > 1.1 ? ceil($page) : 1;
            }
        }
        {//QRCODE
            $id = $datarequest['Proposal_ID'];
            $protocol = $request->secure() ? 'https' : 'http';
            $linkQR = $protocol . '://' . $request->getHost() . "/Quotation/Quotation/cover/document/PDF/$id?page_shop=" . $request->input('page_shop');
            $qrCodeImage = QrCode::format('svg')->size(200)->generate($linkQR);
            $qrCodeBase64 = base64_encode($qrCodeImage);
        }
        $userid = $datarequest['userid'];
        $Proposal_ID = $datarequest['Proposal_ID'];
        $IssueDate = $datarequest['IssueDate'];
        $Expiration = $datarequest['Expiration'];
        $Selectdata = $datarequest['Selectdata'];
        $Data_ID = $datarequest['Data_ID'];
        $Adult = $datarequest['Adult'];
        $Children = $datarequest['Children'];
        $Mevent = $datarequest['Mevent'];
        $Mvat = $datarequest['Mvat'];
        $DiscountAmount = $datarequest['DiscountAmount'];
        $Checkin = $datarequest['Checkin'];
        $Checkout = $datarequest['Checkout'];
        $Day = $datarequest['Day'];
        $Night = $datarequest['Night'];
        $comment = $datarequest['comment'];
        $user = User::where('id',$userid)->select('id','name')->first();
        $fullName = null;
        $Contact_Name = null;
        $Contact_phone =null;
        $Contact_Email = null;
        if ($Selectdata == 'Guest') {
            $Data = Guest::where('Profile_ID',$Data_ID)->first();
            $prename = $Data->preface;
            $First_name = $Data->First_name;
            $Last_name = $Data->Last_name;
            $Address = $Data->Address;
            $Email = $Data->Email;
            $Taxpayer_Identification = $Data->Identification_Number;
            $prefix = master_document::where('id',$prename)->where('Category','Mprename')->where('status',1)->first();
            $name = $prefix->name_th;
            $fullName = $name.' '.$First_name.' '.$Last_name;
            //-------------ที่อยู่
            $CityID=$Data->City;
            $amphuresID = $Data->Amphures;
            $TambonID = $Data->Tambon;
            $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
            $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
            $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
            $Fax_number = '-';
            $phone = phone_guest::where('Profile_ID',$Data_ID)->where('Sequence','main')->first();
        }else{
            $Company = companys::where('Profile_ID',$Data_ID)->first();
            $Company_type = $Company->Company_type;
            $Compannyname = $Company->Company_Name;
            $Address = $Company->Address;
            $Email = $Company->Company_Email;
            $Taxpayer_Identification = $Company->Taxpayer_Identification;
            $comtype = master_document::where('id', $Company_type)->where('Category', 'Mcompany_type')->first();
            if ($comtype) {
                if ($comtype->name_th == "บริษัทจำกัด") {
                    $fullName = "บริษัท " . $Compannyname . " จำกัด";
                } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                    $fullName = "บริษัท " . $Compannyname . " จำกัด (มหาชน)";
                } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                    $fullName = "ห้างหุ้นส่วนจำกัด " . $Compannyname;
                }
            }
            $representative = representative::where('Company_ID',$Data_ID)->first();
            $prename = $representative->prefix;
            $Contact_Email = $representative->Email;
            $prefix = master_document::where('id', $prename)->where('Category', 'Mprename')->first();
            $name = $prefix->name_th;
            $Contact_Name = $representative->First_name.' '.$representative->Last_name;
            $CityID=$Company->City;
            $amphuresID = $Company->Amphures;
            $TambonID = $Company->Tambon;
            $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
            $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
            $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
            $company_fax = company_fax::where('Profile_ID',$Data_ID)->where('Sequence','main')->first();
            if ($company_fax) {
                $Fax_number =  $company_fax->Fax_number;
            }else{
                $Fax_number = '-';
            }
            $phone = company_phone::where('Profile_ID',$Data_ID)->where('Sequence','main')->first();
            $Contact_phone = representative_phone::where('Company_ID',$Data_ID)->where('Sequence','main')->first();
        }
        $eventformat = master_document::where('id',$Mevent)->select('name_th','id')->first();
        $template = master_template::query()->latest()->first();
        $CodeTemplate = $template->CodeTemplate;
        $sheet = master_document_sheet::select('topic','name_th','id','CodeTemplate')->get();
        $Reservation_show = $sheet->where('topic', 'Reservation')->where('CodeTemplate',$CodeTemplate)->first();
        $Paymentterms = $sheet->where('topic', 'Paymentterms')->where('CodeTemplate',$CodeTemplate)->first();
        $note = $sheet->where('topic', 'note')->where('CodeTemplate',$CodeTemplate)->first();
        $Cancellations = $sheet->where('topic', 'Cancellations')->where('CodeTemplate',$CodeTemplate)->first();
        $Complimentary = $sheet->where('topic', 'Complimentary')->where('CodeTemplate',$CodeTemplate)->first();
        $All_rights_reserved = $sheet->where('topic', 'All_rights_reserved')->where('CodeTemplate',$CodeTemplate)->first();
        $date = Carbon::now();
        $unit = master_unit::where('status',1)->get();
        $quantity = master_quantity::where('status',1)->get();
        $settingCompany = Master_company::orderBy('id', 'desc')->first();
        if ($Checkin) {
            $checkin =$Checkin;
            $checkout = $Checkout;
        }else{
            $checkin = '-';
            $checkout = '-';
        }
        $data = [
            'settingCompany'=>$settingCompany,
            'page_item'=>$page_item,
            'page'=>$pagecount,
            'Selectdata'=>$Selectdata,
            'date'=>$date,
            'fullName'=>$fullName,
            'provinceNames'=>$provinceNames,
            'Address'=>$Address,
            'amphuresID'=>$amphuresID,
            'TambonID'=>$TambonID,
            'Email'=>$Email,
            'phone'=>$phone,
            'Fax_number'=>$Fax_number,
            'Day'=>$Day,
            'Night'=>$Night,
            'Checkin'=>$checkin,
            'Checkout'=>$checkout,
            'eventformat'=>$eventformat,
            'totalguest'=>$totalguest,
            'Reservation_show'=>$Reservation_show,
            'Paymentterms'=>$Paymentterms,
            'note'=>$note,
            'Cancellations'=>$Cancellations,
            'Complimentary'=>$Complimentary,
            'All_rights_reserved'=>$All_rights_reserved,
            'Proposal_ID'=>$Proposal_ID,
            'IssueDate'=>$IssueDate,
            'Expiration'=>$Expiration,
            'qrCodeBase64'=>$qrCodeBase64,
            'user'=>$user,
            'Taxpayer_Identification'=>$Taxpayer_Identification,
            'Adult'=>$Adult,
            'Children'=>$Children,
            'totalAmount'=>$totalAmount,
            'SpecialDis'=>$SpecialDis,
            'subtotal'=>$subtotal,
            'beforeTax'=>$beforeTax,
            'Nettotal'=>$Nettotal,
            'totalguest'=>$totalguest,
            'guest'=>$guest,
            'totalaverage'=>$totalaverage,
            'AddTax'=>$AddTax,
            'productItems'=>$productItems,
            'unit'=>$unit,
            'quantity'=>$quantity,
            'Mvat'=>$Mvat,
            'comment'=>$comment,
            'Mevent'=>$Mevent,
            'Contact_Name'=>$Contact_Name,
            'Contact_phone'=>$Contact_phone,
            'Contact_Email'=>$Contact_Email,
        ];
        $view= $template->name;
        $pdf = FacadePdf::loadView('quotationpdf.'.$view,$data);
        return $pdf->stream();

    }
    //deposit revenue
    public function deposit_re($id){
        $deposit = depositrevenue::where('id',$id)->first();
        $DepositID = $deposit->Deposit_ID;
        $Nettotal = $deposit->amount;
        $QuotationID = $deposit->Quotation_ID;
        $companyid = $deposit->Company_ID;
        $CompanyID = $deposit->Company_ID;
        $fullName = $deposit->fullname;
        $IssueDate = $deposit->Issue_date;
        $ExpirationDate = $deposit->ExpirationDate;
        $Deposit = $deposit->count;
        $data_bank = Masters::where('category', "bank")->where('status', 1)->select('id', 'name_th', 'name_en')->get();
        $data_cheque =receive_cheque::where('refer_proposal',$QuotationID)->where('status',1)->get();
        $Quotation = Quotation::where('Quotation_ID', $QuotationID)->first();
        $vat_type = $Quotation->vat_type;
        $currentDate = Carbon::now();
        $dateFormatted = $currentDate->format('d/m/Y');
        $ID = 'RE-';
        $formattedDate = Carbon::parse($currentDate);       // วันที่
        $month = $formattedDate->format('m'); // เดือน
        $year = $formattedDate->format('y');
        $lastRun = receive_payment::latest()->first();
        $nextNumber = 1;

        if ($lastRun == null) {
            $nextNumber = $lastRun + 1;

        }else{
            $lastRunid = $lastRun->id;
            $nextNumber = $lastRunid + 1;
        }
        $newRunNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        $REID = $ID.$year.$month.$newRunNumber;
        $parts = explode('-', $companyid);
        $firstPart = $parts[0];
        $Selectdata ='';
        if ($firstPart == 'C') {
            $Selectdata =  'Company';
            $company =  companys::where('Profile_ID',$companyid)->first();
            if ($company) {
                $ids = $company->id;
                $Address=$company->Address;
                $CityID=$company->City;
                $amphuresID = $company->Amphures;
                $TambonID = $company->Tambon;
                $Identification = $company->Taxpayer_Identification;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = company_phone::where('Profile_ID',$company->Profile_ID)->where('Sequence','main')->first();
                $Email = $company->Company_Email;
                $Company_typeID=$company->Company_type;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="บริษัทจำกัด") {
                    $fullName = "บริษัท ". $company->Company_Name . " จำกัด";
                }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                    $fullName = "บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                    $fullName = "ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                }else{
                    $fullName = $comtype->name_th . $company->Company_Name;
                }
                if ($comtype->name_th =="บริษัทจำกัด") {
                    $name = "บริษัท ". $company->Company_Name . " จำกัด";
                }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                    $name = "บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                    $name = "ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                }else{
                    $name = $comtype->name_th . $company->Company_Name;
                }
                $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
                $name_ID = $company->Profile_ID;
                $datasub = company_tax::where('Company_ID',$name_ID)->get();
                $fax = company_fax::where('Profile_ID',$name_ID)->first();


            }
        }else{
            $guestdata =  Guest::where('Profile_ID',$companyid)->first();
            if ($guestdata) {
                $ids = $guestdata->id;
                $Selectdata =  'Guest';
                $Company_typeID=$guestdata->preface;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="นาย") {
                    $fullName = "นาย ". $guestdata->First_name . ' ' . $guestdata->Last_name;
                }elseif ($comtype->name_th =="นาง") {
                    $fullName = "นาง ". $guestdata->First_name . ' ' . $guestdata->Last_name;
                }elseif ($comtype->name_th =="นางสาว") {
                    $fullName = "นางสาว ". $guestdata->First_name . ' ' . $guestdata->Last_name ;
                }else{
                    $fullName = "คุณ ". $guestdata->First_name . ' ' . $guestdata->Last_name ;
                }
                $name =  'คุณ '.$guestdata->First_name.' '.$guestdata->Last_name;
                $Address=$guestdata->Address;
                $CityID=$guestdata->City;
                $amphuresID = $guestdata->Amphures;
                $TambonID = $guestdata->Tambon;
                $Identification = $guestdata->Identification_Number;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = phone_guest::where('Profile_ID',$guestdata->Profile_ID)->where('Sequence','main')->first();
                $Email = $guestdata->Company_Email;
                $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
                $name_ID = $guestdata->Profile_ID;
                $datasub = guest_tax::where('Company_ID',$name_ID)->get();
                $fax ='-';
            }
        }
        $names = []; // สร้าง array เพื่อเก็บค่าชื่อ

        if ($datasub) {
            foreach ($datasub as $key => $item) {
                $comtype = DB::table('master_documents')->where('id', $item->Company_type)->first();

                if ($comtype) { // ตรวจสอบว่า $comtype ไม่เป็น null
                    if ($firstPart == 'C') {
                        if ($comtype->name_th == "บริษัทจำกัด") {
                            $name = "บริษัท " . $item->Companny_name . " จำกัด";
                        } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                            $name = "บริษัท " . $item->Companny_name . " จำกัด (มหาชน)";
                        } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                            $name = "ห้างหุ้นส่วนจำกัด " . $item->Companny_name;
                        } else {
                            $name = $comtype->name_th . ($item->Companny_name ?? ($item->first_name . " " . $item->last_name));
                        }
                        $name_id = $item->ComTax_ID;
                    } else {
                        if ($comtype->name_th == "บริษัทจำกัด") {
                            $name = "บริษัท " . $item->Company_name . " จำกัด";
                        } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                            $name = "บริษัท " . $item->Company_name . " จำกัด (มหาชน)";
                        } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                            $name = "ห้างหุ้นส่วนจำกัด " . $item->Company_name;
                        } else {
                            $name = $comtype->name_th . ($item->Company_name ?? ($item->first_name . " " . $item->last_name));
                        }
                        $name_id = $item->GuestTax_ID;
                    }

                    // เก็บค่า $name ลงใน array
                    $names[$key + 1] = [
                        'id' => $name_id ?? null,
                        'name' => $name ?? null,
                    ];
                }
            }
        }
        $datamain[0] = [
            'id' => $name_ID ?? null,
            'name' => $name ?? null,
        ];
        $data_select = array_merge($datamain, $names);

        $settingCompany = Master_company::orderBy('id', 'desc')->first();
        $userid = Auth::user()->id;
        $user = User::where('id',$userid)->first();
        $vattype= $Quotation->vat_type;
        $vat_type = master_document::where('id',$vattype)->first();
        if ($Nettotal) {
            $Subtotal =0;
            $total =0;
            $addtax = 0;
            $before = 0;
            $balance =0;
            if ($vattype == 51) {
                $Subtotal = $Nettotal;
                $total = $Nettotal;
                $addtax = 0;
                $before = $Nettotal;
                $balance = $Subtotal;
            }else{
                $Subtotal = $Nettotal;
                $total = $Subtotal/1.07;
                $addtax = $Subtotal-$total;
                $before = $Subtotal-$addtax;
                $balance = $Subtotal;
            }
        }
        $Proposal = Quotation::where('Quotation_ID',$QuotationID)->first();
        return view('billingfolio.deposit.paiddeposit',compact('DepositID','QuotationID','Deposit','data_bank','data_cheque','vat_type','fullName','address','Identification','Email','phone'
                    ,'Nettotal','Quotation','settingCompany','user','Subtotal','total','addtax','before','balance','deposit','REID','Selectdata','Proposal','dateFormatted','data_select','name_ID'));
    }

    public function savedeposit(Request $request){

        $data = $request->all();
        $additional = $request->additional ?? 0;
        $cashcomp = $request->cashcomp ?? 0;
        $complimentary =  $additional-$cashcomp;
        $requestData = $request->all();
        $additional_type = $request->additional_type;
        $groupedData = []; // ตัวแปรสำหรับจัดเก็บข้อมูลที่ใช้ index

        foreach ($requestData as $key => $value) {
            if (strpos($key, 'paymentType_') === 0) { // ตรวจสอบว่าคีย์ขึ้นต้นด้วย 'paymentType_'
                preg_match('/\d+$/', $key, $matches); // ดึงตัวเลขท้ายคีย์
                $index = $matches[0]; // ตัวเลขที่ได้จากคีย์ เช่น 0, 1, 2
                $groupedData[$index ] = [
                    "paymentType" =>  $data["paymentType"] ?? null, // ค่า paymentType_x ที่ดึงมา
                    "cashAmount" =>  $data["cashAmount"] ?? null,
                    "bank" => $data["bank"] ?? null, // ค้นหา bank_x
                    "bankTransferAmount" => $data["bankTransferAmount"] ?? null, // ค้นหา bankTransferAmount_x
                    "CardNumber" => $data["CardNumber"] ?? null, // ค้นหา CardNumber_x
                    "Expiry" => $data["Expiry"] ?? null, // ค้นหา Expiry_x
                    "creditCardAmount" => $data["creditCardAmount"] ?? null, // ค้นหา creditCardAmount_x
                    "cheque" => $data["cheque"] ?? null, // ค้นหา creditCardAmount_
                    "deposit_date" => $data["deposit_date"] ?? null, // ค้นหา deposit_date_x
                    "chequebank" => $data["chequebank"] ?? null,
                    "chequebank_name" => $data["chequebank_name"] ?? null,
                    "chequeamount" => isset($data["chequeamount"])
                    ? str_replace([',', '.00'], ['', ''], $data["chequeamount"]) // ถอดคอมมาและ .00
                    : null, // ถ้าไม่มีค่าก็ให้เป็น null
                     // ถ้าไม่มีค่าก็ให้เป็น null
                    "detail" => ($data["paymentType"] == 'cash')
                    ? 'Cash'
                    : ($data["paymentType"] == 'bankTransfer'
                        ? $data["bank"] . ' Bank Transfer - Together Resort Ltd'
                        : ($data["paymentType"] == 'creditCard'
                            ? 'Credit Card No. ' . $data["CardNumber"] . ' Exp. Date: ' . $data["Expiry"]
                            : ($data["paymentType"] == 'cheque'
                                ? 'Cheque Bank ' . $data["chequebank_name"] . ' Cheque Number ' . $data["cheque"]
                                    : null
                            )
                        )
                    ),
                ];
                // สร้างอาร์เรย์ใหม่ที่ใช้ index
                $groupedData[$index] = [
                    "paymentType" => $value, // ค่า paymentType_x ที่ดึงมา
                    "cashAmount" =>  $requestData["cashAmount_$index"] ?? null,
                    "bank" => $requestData["bank_$index"] ?? null, // ค้นหา bank_x
                    "bankTransferAmount" => $requestData["bankTransferAmount_$index"] ?? null, // ค้นหา bankTransferAmount_x
                    "CardNumber" => $requestData["CardNumber_$index"] ?? null, // ค้นหา CardNumber_x
                    "Expiry" => $requestData["Expiry_$index"] ?? null, // ค้นหา Expiry_x
                    "creditCardAmount" => $requestData["creditCardAmount_$index"] ?? null, // ค้นหา creditCardAmount_x
                    "cheque" => $requestData["cheque_$index"] ?? null, // ค้นหา creditCardAmount_
                    "deposit_date" => $requestData["deposit_date_$index"] ?? null, // ค้นหา deposit_date_x
                    "chequebank" => $requestData["chequebank_$index"] ?? null,
                    "chequebank_name" => $requestData["chequebank_name_$index"] ?? null,
                    "chequeamount" => isset($requestData["chequeamount_$index"])
                    ? str_replace([',', '.00'], ['', ''], $requestData["chequeamount_$index"]) // ถอดคอมมาและ .00
                    : null, // ถ้าไม่มีค่าก็ให้เป็น null
                    "detail" => ($value == 'cash')
                    ? 'Cash'
                    :
                    ($value == 'bankTransfer'
                        ? $requestData["bank_$index"] . ' Bank Transfer - Together Resort Ltd'
                        : ($value == 'creditCard'
                            ? 'Credit Card No. ' . $requestData["CardNumber_$index"] . ' Exp. Date: ' . $requestData["Expiry_$index"]
                            : ($value == 'cheque'
                                ? 'Cheque Bank ' . $requestData["chequebank_name_$index"] . ' Cheque Number ' . $requestData["cheque_$index"]
                                : null
                            )
                        )
                    ),
                ];
            }else{
                $groupedData[0] = [
                    "paymentType" =>  $data["paymentType"] ?? null, // ค่า paymentType_x ที่ดึงมา
                    "cashAmount" =>  $data["cashAmount"] ?? null,
                    "bank" => $data["bank"] ?? null, // ค้นหา bank_x
                    "bankTransferAmount" => $data["bankTransferAmount"] ?? null, // ค้นหา bankTransferAmount_x
                    "CardNumber" => $data["CardNumber"] ?? null, // ค้นหา CardNumber_x
                    "Expiry" => $data["Expiry"] ?? null, // ค้นหา Expiry_x
                    "creditCardAmount" => $data["creditCardAmount"] ?? null, // ค้นหา creditCardAmount_x
                    "cheque" => $data["cheque"] ?? null, // ค้นหา creditCardAmount_
                    "deposit_date" => $data["deposit_date"] ?? null, // ค้นหา deposit_date_x
                    "chequebank" => $data["chequebank"] ?? null,
                    "chequebank_name" => $data["chequebank_name"] ?? null,
                    "chequeamount" => isset($data["chequeamount"])
                    ? str_replace([',', '.00'], ['', ''], $data["chequeamount"]) // ถอดคอมมาและ .00
                    : null, // ถ้าไม่มีค่าก็ให้เป็น null
                     // ถ้าไม่มีค่าก็ให้เป็น null
                    "detail" => ($data["paymentType"] == 'cash')
                    ? 'Cash'
                    : ($data["paymentType"] == 'bankTransfer'
                        ? $data["bank"] . ' Bank Transfer - Together Resort Ltd'
                        : ($data["paymentType"] == 'creditCard'
                            ? 'Credit Card No. ' . $data["CardNumber"] . ' Exp. Date: ' . $data["Expiry"]
                            : ($data["paymentType"] == 'cheque'
                                ? 'Cheque Bank ' . $data["chequebank_name"] . ' Cheque Number ' . $data["cheque"]
                                    : null
                            )
                        )
                    ),
                ];
            }
        }
        $cash = 0;
        $cashbankTransfer = 0;
        $cashCard = 0;
        $cashcheque =0;
        foreach ($groupedData as $value) {
            $cash += isset($value['cashAmount']) ? (float)$value['cashAmount'] : 0;
            $cashbankTransfer += isset($value['bankTransferAmount']) ? (float)$value['bankTransferAmount'] : 0;
            $cashCard += isset($value['creditCardAmount']) ? (float)$value['creditCardAmount'] : 0;
            $cashcheque += isset($value['chequeamount']) ? (float)$value['chequeamount'] : 0;
        }
        $Amountall = $cash+$cashbankTransfer+$cashCard+$cashcheque;
        $Amount = floatval($Amountall);
        $RealAmount = $cash+$cashbankTransfer+$cashCard+$cashcheque;
        $guest = $request->Guest;
        $companyid = $request->Guest;
        $reservationNo = $request->reservationNo;
        $room = $request->roomNo;
        $numberOfGuests = $request->numberOfGuests;
        $arrival = $request->arrival;
        $departure = $request->departure;
        $note = $request->note;
        $paymentDate = $request->paymentDate;
        $paymentType = $request->paymentTypecheque ?? $request->paymentType;
        if ($paymentType == null || $companyid == null || $reservationNo == null || $room == null || $numberOfGuests == null || $arrival == null || $departure == null) {
            return redirect()->route('BillingFolio.index')->with('error', 'กรุณากรอกข้อมูลให้ครบ');
        }
        $invoice = $request->invoice;
        $parts = explode('-', $companyid);
        $firstPart = $parts[0];
        $Selectdata ='';
        if ($firstPart == 'C') {
            $Selectdata =  'Company';
            $company =  companys::where('Profile_ID',$companyid)->first();

            if ($company) {
                $Address=$company->Address;
                $CityID=$company->City;
                $amphuresID = $company->Amphures;
                $TambonID = $company->Tambon;
                $Identification = $company->Taxpayer_Identification;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = company_phone::where('Profile_ID',$company->Profile_ID)->where('Sequence','main')->first();
                $email = $company->Company_Email;
                $Company_typeID=$company->Company_type;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="บริษัทจำกัด") {
                    $name = 'ลูกค้า : '."บริษัท ". $company->Company_Name . " จำกัด";
                    $nameold = "บริษัท ". $company->Company_Name . " จำกัด";
                }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                    $name = 'ลูกค้า : '."บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                    $nameold = "บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                    $name = 'ลูกค้า : '."ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                    $nameold = "ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                }else{
                    $name = 'ลูกค้า : '.$comtype->name_th . $company->Company_Name;
                    $nameold = $comtype->name_th . $company->Company_Name;
                }
            }else{

                $company =  company_tax::where('ComTax_ID',$companyid)->first();
                $Company_typeID=$company->Company_type;
                if ($Company_typeID == [30,31,32]) {
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $name = 'ลูกค้า : '."บริษัท ". $company->Company_Name . " จำกัด";
                        $nameold = "บริษัท ". $company->Company_Name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $name = 'ลูกค้า : '."บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                        $nameold = "บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $name = 'ลูกค้า : '."ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                        $nameold = "ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                    }else{
                        $name = 'ลูกค้า : '.$comtype->name_th . $company->Company_Name;
                        $nameold = $comtype->name_th . $company->Company_Name;
                    }
                }else{
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="นาย") {
                        $nameold = "นาย ". $company->first_name . ' ' . $company->last_name;
                        $name = 'ลูกค้า : '."นาย ". $company->first_name . ' ' . $company->last_name;
                    }elseif ($comtype->name_th =="นาง") {
                        $nameold = "นาง ". $company->first_name . ' ' . $company->last_name;
                        $name = 'ลูกค้า : '."นาง ". $company->first_name . ' ' . $company->last_name;
                    }elseif ($comtype->name_th =="นางสาว") {
                        $nameold = "นางสาว ". $company->first_name . ' ' . $company->last_name ;
                        $name = 'ลูกค้า : '."นางสาว ". $company->first_name . ' ' . $company->last_name ;
                    }else{
                        $nameold = "คุณ ". $company->first_name . ' ' . $company->last_name ;
                        $name = 'ลูกค้า : '."คุณ ". $company->first_name . ' ' . $company->last_name ;
                    }
                }
                $Address=$company->Address;
                $CityID=$company->City;
                $amphuresID = $company->Amphures;
                $TambonID = $company->Tambon;
                $Identification = $company->Taxpayer_Identification;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = company_tax_phone::where('ComTax_ID',$companyid)->where('Sequence','main')->first();
                $email = $company->Company_Email;
            }
        }else{

            $guestdata =  Guest::where('Profile_ID',$companyid)->first();

            if ($guestdata) {
                $Selectdata =  'Guest';
                $Company_typeID=$guestdata->preface;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="นาย") {
                    $nameold = "นาย ". $guestdata->First_name . ' ' . $guestdata->Last_name;
                    $name = 'ลูกค้า : '."นาย ". $guestdata->First_name . ' ' . $guestdata->Last_name;
                }elseif ($comtype->name_th =="นาง") {
                    $nameold = "นาง ". $guestdata->First_name . ' ' . $guestdata->Last_name;
                    $name = 'ลูกค้า : '."นาง ". $guestdata->First_name . ' ' . $guestdata->Last_name;
                }elseif ($comtype->name_th =="นางสาว") {
                    $nameold = "นางสาว ". $guestdata->First_name . ' ' . $guestdata->Last_name ;
                    $name = 'ลูกค้า : '."นางสาว ". $guestdata->First_name . ' ' . $guestdata->Last_name ;
                }else{
                    $nameold = "คุณ ". $guestdata->First_name . ' ' . $guestdata->Last_name ;
                    $name = 'ลูกค้า : '."คุณ ". $guestdata->First_name . ' ' . $guestdata->Last_name ;
                }
                $Address=$guestdata->Address;
                $CityID=$guestdata->City;
                $amphuresID = $guestdata->Amphures;
                $TambonID = $guestdata->Tambon;
                $Identification = $guestdata->Identification_Number;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = phone_guest::where('Profile_ID',$guestdata->Profile_ID)->where('Sequence','main')->first();
                $email = $guestdata->Company_Email;
            }else{
                $guestdata =  guest_tax::where('GuestTax_ID',$companyid)->first();
                $Company_typeID=$guestdata->Company_type;
                if ($Company_typeID == [30,31,32]) {
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $nameold = "บริษัท ". $guestdata->Company_name . " จำกัด";
                        $name = 'ลูกค้า : '."บริษัท ". $guestdata->Company_name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $nameold = "บริษัท ". $guestdata->Company_name . " จำกัด (มหาชน)";
                        $name = 'ลูกค้า : '."บริษัท ". $guestdata->Company_name . " จำกัด";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $nameold = "ห้างหุ้นส่วนจำกัด ". $guestdata->Company_name ;
                        $name = 'ลูกค้า : '."บริษัท ". $guestdata->Company_name . " จำกัด";
                    }elseif ($Company_typeID > 32){
                        $nameold = $comtype->name_th . $guestdata->Company_name;
                        $name = 'ลูกค้า : '."บริษัท ". $guestdata->Company_name . " จำกัด";
                    }
                }else{
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="นาย") {
                        $nameold = "นาย ". $guestdata->first_name . ' ' . $guestdata->last_name;
                        $name = 'ลูกค้า : '."นาย ". $guestdata->first_name . ' ' . $guestdata->last_name;
                    }elseif ($comtype->name_th =="นาง") {
                        $nameold = "นาง ". $guestdata->first_name . ' ' . $guestdata->last_name;
                        $name = 'ลูกค้า : '."นาง ". $guestdata->first_name . ' ' . $guestdata->last_name;
                    }elseif ($comtype->name_th =="นางสาว") {
                        $nameold = "นางสาว ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                        $name = 'ลูกค้า : '. "นางสาว ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                    }else{
                        $nameold = "คุณ ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                        $name = 'ลูกค้า : '."คุณ ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                    }
                }
                $Address=$guestdata->Address;
                $CityID=$guestdata->City;
                $amphuresID = $guestdata->Amphures;
                $TambonID = $guestdata->Tambon;
                $Identification = $guestdata->Identification_Number;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = guest_tax_phone::where('GuestTax_ID',$companyid)->where('Sequence','main')->first();
                $email = $guestdata->Company_Email;
            }
        }

        $currentDate = Carbon::now();
        $ID = 'RE-';
        $formattedDate = Carbon::parse($currentDate);       // วันที่
        $month = $formattedDate->format('m'); // เดือน
        $year = $formattedDate->format('y');
        $lastRun = receive_payment::lockForUpdate()->orderBy('id', 'desc')->first();
        $nextNumber = 1;

        if ($lastRun == null) {
            $nextNumber = $lastRun + 1;
        }else{
            $lastRunid = $lastRun->id;
            $nextNumber = $lastRunid + 1;
        }
        $newRunNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        $REID = $ID.$year.$month.$newRunNumber;
        $invoices = depositrevenue::where('Deposit_ID', $invoice)->first();
        $idinvoices = $invoices->id;
        $sumpayment = $invoices->amount;
        $Quotation_ID = $invoices->Quotation_ID;
        $correct = $invoices->correct;

        if ($correct >= 1) {
            $correctup = $correct + 1;
        }else{
            $correctup = 1;
        }

        $proposaldata = Quotation::where('Quotation_ID',$Quotation_ID)->first();
        $type_Proposal =$proposaldata->type_Proposal;
        $created_at = Carbon::parse($invoices->created_at)->format('d/m/Y');
        $template = master_template::query()->latest()->first();
        try {
            $Reservation_No = null;
            if ($reservationNo) {
                $Reservation_No = 'Reservation No : '.$reservationNo;
            }
            $Room_No = null;
            if ($room) {
                $Room_No = 'Room No : '.$room;
            }
            $NumberOfGuests = null;
            if ($numberOfGuests) {
                $NumberOfGuests = 'No. of guest : '.$numberOfGuests;
            }
            $Arrival = null;
            if ($arrival) {
                $Arrival = 'Arrival : '.$arrival;
            }
            $Departure = null;
            if ($departure) {
                $Departure = 'Departure : '.$departure;
            }
            $PaymentDate = null;
            if ($paymentDate) {
                $PaymentDate = 'วันที่ชำระ : '.$paymentDate;
            }
            $Note = null;
            if ($note) {
                $Note = 'รายละเอียด : '.$note;
            }
            $fullname = 'รหัส : '.$REID;
            $amoute = 'ราคาที่จ่าย : '.number_format($RealAmount) . ' บาท';
            $total = 'ราคาออกเอกสาร : '.number_format($sumpayment) . ' บาท';
            $edit ='รายการ';

            $formattedProductData = [];

            foreach ($groupedData as $product) {
                $totalAmount =
                                ($product['cashAmount'] ?? 0) +
                                ($product['bankTransferAmount'] ?? 0) +
                                ($product['creditCardAmount'] ?? 0) +
                                ($product['chequeamount'] ?? 0);

                $formattedPrice = number_format($totalAmount) . ' บาท';
                $formattedProductData[] = 'Description : ' . $product['detail'] . ' , '  . 'Price item : ' . $formattedPrice;
            }

            $datacompany = '';

            $variables = [$fullname, $Reservation_No,$Room_No,$NumberOfGuests, $Arrival,$Departure,$PaymentDate,$Note,$amoute,$total,$edit];
            $formattedProductDataString = implode(' + ', $formattedProductData);
            // แปลง array ของ $formattedProductData เป็น string เดียวที่มีรายการทั้งหมด
            $variables[] = $formattedProductDataString;


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
            $save->Company_ID = $REID;
            $save->type = 'Create';
            $save->Category = 'Create :: Billing Folio';
            $save->content =$datacompany;
            $save->save();
        } catch (\Throwable $e) {
            return redirect()->route('BillingFolio.index')->with('error', $e->getMessage());
        }
        try {
            $settingCompany = Master_company::orderBy('id', 'desc')->first();
            $parts = explode('-', $guest);
            $firstPart = $parts[0];
            if ($firstPart == 'C') {
                $company =  companys::where('Profile_ID',$guest)->first();
                if ($company) {
                    $fullname = "";
                    $Company_typeID=$company->Company_type;
                    if ($company->Company_Name) {
                        $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                        if ($comtype->name_th =="บริษัทจำกัด") {
                            $fullnameCom = " "." บริษัท ". $company->Company_Name . " จำกัด";
                        }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                            $fullnameCom = " "." บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                        }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                            $fullnameCom = " "." ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                        }else {
                            $fullnameCom = $comtype->name_th . $company->Company_Name;
                        }
                    }else{
                        $fullnameCom = "";
                    }
                    $Address=$company->Address;
                    $CityID=$company->City;
                    $amphuresID = $company->Amphures;
                    $TambonID = $company->Tambon;
                    $Identification = $company->Taxpayer_Identification;
                    $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                    $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                    $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                    if ($provinceNames) {
                        $province = ' จังหวัด '.$provinceNames->name_th;
                        $amphures = ' อำเภอ '.$amphuresID->name_th;
                        $tambon = ' ตำบล '.$TambonID->name_th;
                        $zip_code = $TambonID->Zip_Code;
                    }else{
                        $province ="";
                        $amphures="";
                        $tambon="";
                        $zip_code="";
                    }
                }else{
                    $company =  company_tax::where('ComTax_ID',$guest)->first();
                    $fullname = $company && $company->Companny_name
                                ? ""
                                : 'คุณ ' . $company->first_name . ' ' . $company->last_name;
                    $Company_typeID=$company->Company_type;
                    if ($company->Companny_name) {
                        $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                        if ($comtype->name_th =="บริษัทจำกัด") {
                            $fullnameCom = " "." บริษัท ". $company->Companny_name . " จำกัด";
                        }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                            $fullnameCom = " "." บริษัท ". $company->Companny_name . " จำกัด (มหาชน)";
                        }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                            $fullnameCom = " "." ห้างหุ้นส่วนจำกัด ". $company->Companny_name ;
                        }else {
                            $fullnameCom = $comtype->name_th . $company->Companny_name;
                        }
                    }else{
                        $fullnameCom = "";
                    }
                    $Address=$company->Address;
                    $CityID=$company->City;
                    $amphuresID = $company->Amphures;
                    $TambonID = $company->Tambon;
                    $Identification = $company->Taxpayer_Identification;
                    $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                    $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                    $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                    if ($provinceNames) {
                        $province = ' จังหวัด '.$provinceNames->name_th;
                        $amphures = ' อำเภอ '.$amphuresID->name_th;
                        $tambon = ' ตำบล '.$TambonID->name_th;
                        $zip_code = $TambonID->Zip_Code;
                    }else{
                        $province ="";
                        $amphures="";
                        $tambon="";
                        $zip_code="";
                    }
                }
            }else{
                $guestdata =  Guest::where('Profile_ID',$guest)->first();
                if ($guestdata) {
                    $fullname =  'คุณ '.$guestdata->First_name.' '.$guestdata->Last_name;
                    $fullnameCom = "";
                    $Address=$guestdata->Address;
                    $CityID=$guestdata->City;
                    $amphuresID = $guestdata->Amphures;
                    $TambonID = $guestdata->Tambon;
                    $Identification = $guestdata->Identification_Number;
                    $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                    $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                    $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                    if ($provinceNames) {
                        $province = ' จังหวัด '.$provinceNames->name_th;
                        $amphures = ' อำเภอ '.$amphuresID->name_th;
                        $tambon = ' ตำบล '.$TambonID->name_th;
                        $zip_code = $TambonID->Zip_Code;
                    }else{
                        $province ="";
                        $amphures="";
                        $tambon="";
                        $zip_code="";
                    }
                }else{
                    $guestdata =  guest_tax::where('GuestTax_ID',$guest)->first();
                    $fullname = $guestdata && $guestdata->Company_name
                                ? ""
                                : 'คุณ ' . $guestdata->first_name . ' ' . $guestdata->last_name;
                    $Company_typeID=$guestdata->Company_type;
                    if ($guestdata->Company_name) {
                        $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                        if ($comtype->name_th =="บริษัทจำกัด") {
                            $fullnameCom = " "." บริษัท ". $guestdata->Company_name . " จำกัด";
                        }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                            $fullnameCom = " "." บริษัท ". $guestdata->Company_name . " จำกัด (มหาชน)";
                        }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                            $fullnameCom = " "." ห้างหุ้นส่วนจำกัด ". $guestdata->Company_name ;
                        }else {
                            $fullnameCom = $comtype->name_th . $guestdata->Company_name;
                        }
                    }else{
                        $fullnameCom = "";
                    }
                    $Address=$guestdata->Address;
                    $CityID=$guestdata->City;
                    $amphuresID = $guestdata->Amphures;
                    $TambonID = $guestdata->Tambon;
                    $Identification = $guestdata->Identification_Number;
                    $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                    $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                    $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                    if ($provinceNames) {
                        $province = ' จังหวัด '.$provinceNames->name_th;
                        $amphures = ' อำเภอ '.$amphuresID->name_th;
                        $tambon = ' ตำบล '.$TambonID->name_th;
                        $zip_code = $TambonID->Zip_Code;
                    }else{
                        $province ="";
                        $amphures="";
                        $tambon="";
                        $zip_code="";
                    }

                }
            }
            $date = Carbon::now();
            $Date = $paymentDate;
            $dateFormatted = $date->format('d/m/Y').' / ';
            $dateTime = $date->format('H:i:s');
            $Amount = $Amountall;
            $userid = Auth::user()->id;
            $user = User::where('id',$userid)->first();

            $product = [];
            foreach ($groupedData as $value) {
                $totalAmount =
                                ($value['cashAmount'] ?? 0) +
                                ($value['Complimentary'] ?? 0) +
                                ($value['bankTransferAmount'] ?? 0) +
                                ($value['creditCardAmount'] ?? 0) +
                                ($value['chequeamount'] ?? 0) +
                                ($value['NoShowAmount'] ?? 0);
                $product[] = [
                    'detail' => $value['detail'],
                    'amount' => $totalAmount,
                ];
            }
            $Proposal = null;
            $productItems = array_merge($product);
            $Additional = null;
            $receive = null;
            $key = null;
            $bill = null;
            $data = [
                'settingCompany'=>$settingCompany,
                'fullname'=>$fullname,
                'fullnameCom'=>$fullnameCom,
                'Identification'=>$Identification,
                'Address'=>$Address,
                'province'=>$province,
                'amphures'=>$amphures,
                'tambon'=>$tambon,
                'zip_code'=>$zip_code,
                'reservationNo'=>$reservationNo,
                'room'=>$room,
                'user'=>$user,
                'arrival'=>$arrival,
                'departure'=>$departure,
                'numberOfGuests'=>$numberOfGuests,
                'dateFormatted'=>$dateFormatted,
                'dateTime'=>$dateTime,
                'created_at'=>$created_at,
                'Date'=>$Date,
                'note'=>$note,
                'productItems'=>$productItems,
                'invoice'=>$REID,
                'Amount'=>$Amount,
                'Proposal'=>$Proposal,
                'Additional'=>$Additional,
                'receive'=>$receive ?? [],
                'key'=>$key ?? 1,
                'bill'=>$bill ?? 1,
            ];
            $view= $template->name;
            $pdf = FacadePdf::loadView('billingfolioPDF.'.$view,$data);
            $path = 'PDF/billingfolio/';
            // return $pdf->stream();
            $pdf->save($path . $REID . '.pdf');
            $currentDateTime = Carbon::now();
            $currentDate = $currentDateTime->toDateString(); // Format: YYYY-MM-DD
            $currentTime = $currentDateTime->toTimeString(); // Format: HH:MM:SS

            // Optionally, you can format the date and time as per your requirement
            $formattedDate = $currentDateTime->format('Y-m-d'); // Custom format for date
            $formattedTime = $currentDateTime->format('H:i:s');
            $savePDF = new log();
            $savePDF->Quotation_ID = $REID;
            $savePDF->QuotationType = 'Receipt';
            $savePDF->Company_Name = !empty($fullnameCom) ? $fullnameCom : $fullname;
            $savePDF->Approve_date = $formattedDate;
            $savePDF->Approve_time = $formattedTime;
            $savePDF->save();
        } catch (\Throwable $e) {
            return redirect()->route('BillingFolio.index')->with('error', $e->getMessage());
        }
        try {
            $user = Auth::user()->id;
            $save = new receive_payment();
            $save->Receipt_ID = $REID;
            $save->Deposit_ID = $invoice;
            $save->Quotation_ID = $Quotation_ID;
            $save->company = $companyid;
            $save->Amount = $RealAmount;
            $save->fullname = $nameold;
            $save->document_amount = $Amount;
            $save->reservationNo = $reservationNo;
            $save->roomNo = $room;
            $save->numberOfGuests = $numberOfGuests;
            $save->arrival = $arrival;
            $save->departure = $departure;
            $save->type_Proposal = $type_Proposal;
            $save->paymentDate = $paymentDate;
            $save->Operated_by = $user;
            $save->note = $note;
            $save->valid = $created_at;
            $save->save();
        } catch (\Throwable $e) {
            return redirect()->route('BillingFolio.index')->with('error', $e->getMessage());
        }
        try {
            foreach ($groupedData as $index) {
                $item = new document_receive_item();
                $item->receive_id = $REID;
                $item->detail = $index['detail'];
                $item->amount =     ($index['cashAmount'] ?? 0) +
                                    ($index['bankTransferAmount'] ?? 0) +
                                    ($index['creditCardAmount'] ?? 0) +
                                    ($index['chequeamount'] ?? 0);
                $item->type = $index['paymentType'] ?? null;
                if ($index['paymentType'] == 'bankTransfer') {
                    $item->bank = $index['bank'] ?? null;
                }elseif ($index['paymentType'] == 'cheque') {
                    $item->bank = $index['chequebank_name'] ?? null;
                }
                $item->CardNumber = $index['CardNumber'] ?? null;
                $item->Expiry = $index['Expiry'] ?? null;
                $item->Cheque = $index['cheque'] ?? null;
                $item->Deposit_date = $index['deposit_date'] ?? null;
                $item->save();
            }
        } catch (\Throwable $e) {
            receive_payment::where('Receipt_ID',$REID)->first()->delete();
            return redirect()->route('BillingFolio.index')->with('error', $e->getMessage());
        }
        try {
            $saveRe = depositrevenue::find($idinvoices);
            $saveRe->payment = $RealAmount;
            $saveRe->document_status = 2;
            $saveRe->fullname = $nameold;
            $saveRe->Issue_date = $arrival;
            $saveRe->ExpirationDate = $departure;
            $saveRe->correct = $correctup;
            $saveRe->date = $paymentDate;
            $saveRe->save();
            foreach ($groupedData as $index) {
                if (!empty($index['cheque'])) {
                    $chequeRe =receive_cheque::where('cheque_number',$index['cheque'])->where('status',1)->first();
                    $id_cheque = $chequeRe->id;
                    $savecheque = receive_cheque::find($id_cheque);
                    $savecheque->receive_payment =$index['chequebank'];
                    $savecheque->status = 2;
                    $savecheque->deduct_date = $formattedDate;
                    $savecheque->deduct_by = $userid;
                    $savecheque->save();
                }
            }
            $Quotation = Quotation::where('Quotation_ID', $Quotation_ID)->first();
            $Quotationid = $Quotation->id;
            $savequ = Quotation::find($Quotationid);
            $savequ->status_receive = 1;
            $savequ->save();
            return redirect()->route('BillingFolio.index')->with('success', 'Data has been successfully saved.');
        } catch (\Throwable $e) {
            return redirect()->route('BillingFolio.index')->with('error', $e->getMessage());
        }
    }
    public function depositView($id)
    {
        $deposit = depositrevenue::where('id',$id)->first();
        $companyid = $deposit->Company_ID;
        $Issue_date = $deposit->Issue_date;
        $Payment = $deposit->amount;
        $Company_ID = $deposit->Company_ID;
        $Quotation = Quotation::where('Quotation_ID',$deposit->Quotation_ID)->first();
        $Proposal = Quotation::where('Quotation_ID',$deposit->Quotation_ID)->first();
        $ids = $Proposal->id;
        $guest = $Proposal->Company_ID;
        $type = $Proposal->type_Proposal;
        if ($type == 'Company') {
            $data = companys::where('Profile_ID',$guest)->first();
            $Company_typeID=$data->Company_type;
            $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
            if ($comtype->name_th =="บริษัทจำกัด") {
                $name = "บริษัท ". $data->Company_Name . " จำกัด";
            }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                $name = "บริษัท ". $data->Company_Name . " จำกัด (มหาชน)";
            }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                $name = "ห้างหุ้นส่วนจำกัด ". $data->Company_Name ;
            }else{
                $name = $comtype->name_th . $data->Company_Name;
            }
            $name_ID = $data->Profile_ID;
            $datasub = company_tax::where('Company_ID',$name_ID)->get();
        }else {
            $data = Guest::where('Profile_ID',$guest)->first();
            $name =  'คุณ '.$data->First_name.' '.$data->Last_name;
            $name_ID = $data->Profile_ID;
            $datasub = guest_tax::where('Company_ID',$name_ID)->get();
        }
        $settingCompany = Master_company::orderBy('id', 'desc')->first();
        $DepositID = $deposit->Deposit_ID;
        $Issue_date = $deposit->Issue_date;
        $ExpirationDate = $deposit->ExpirationDate;
        $parts = explode('-', $companyid);
        $firstPart = $parts[0];

        if ($firstPart == 'C') {
            $Selectdata =  'Company';
            $company =  companys::where('Profile_ID',$companyid)->first();
            if ($company) {
                $Address=$company->Address;
                $CityID=$company->City;
                $amphuresID = $company->Amphures;
                $TambonID = $company->Tambon;
                $Identification = $company->Taxpayer_Identification;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = company_phone::where('Profile_ID',$company->Profile_ID)->where('Sequence','main')->first();
                $Email = $company->Company_Email;
                $Company_typeID=$company->Company_type;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="บริษัทจำกัด") {
                    $fullName = "บริษัท ". $company->Company_Name . " จำกัด";
                }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                    $fullName = "บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                    $fullName = "ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                }else{
                    $fullName = $comtype->name_th . $company->Company_Name;
                }
                $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
            }else{

                $company =  company_tax::where('ComTax_ID',$companyid)->first();

                $Company_typeID=$company->Company_type;
                if ($Company_typeID == [30,31,32]) {
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $fullName = "บริษัท ". $company->Companny_name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $fullName = "บริษัท ". $company->Companny_name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $fullName = "ห้างหุ้นส่วนจำกัด ". $company->Companny_name ;
                    }elseif ($Company_typeID > 32){
                        $fullName = $comtype->name_th . $company->Companny_name;
                    }
                }else{
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="นาย") {
                        $fullName = "นาย ". $company->first_name . ' ' . $company->last_name;
                    }elseif ($comtype->name_th =="นาง") {
                        $fullName = "นาง ". $company->first_name . ' ' . $company->last_name;
                    }elseif ($comtype->name_th =="นางสาว") {
                        $fullName = "นางสาว ". $company->first_name . ' ' . $company->last_name ;
                    }else{
                        $fullName = "คุณ ". $company->first_name . ' ' . $company->last_name ;
                    }
                }
                $Address=$company->Address;
                $CityID=$company->City;
                $amphuresID = $company->Amphures;
                $TambonID = $company->Tambon;
                $Identification = $company->Taxpayer_Identification;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = company_tax_phone::where('ComTax_ID',$companyid)->where('Sequence','main')->first();
                $Email = $company->Company_Email;
                $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
            }
        }else{

            $guestdata =  Guest::where('Profile_ID',$companyid)->first();

            if ($guestdata) {
                $Selectdata =  'Guest';
                $Company_typeID=$guestdata->preface;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="นาย") {
                    $fullName = "นาย ". $guestdata->first_name . ' ' . $guestdata->last_name;
                }elseif ($comtype->name_th =="นาง") {
                    $fullName = "นาง ". $guestdata->first_name . ' ' . $guestdata->last_name;
                }elseif ($comtype->name_th =="นางสาว") {
                    $fullName = "นางสาว ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                }else{
                    $fullName = "คุณ ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                }
                $Address=$guestdata->Address;
                $CityID=$guestdata->City;
                $amphuresID = $guestdata->Amphures;
                $TambonID = $guestdata->Tambon;
                $Identification = $guestdata->Identification_Number;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = phone_guest::where('Profile_ID',$guestdata->Profile_ID)->where('Sequence','main')->first();
                $Email = $guestdata->Company_Email;
                $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
            }else{
                $guestdata =  guest_tax::where('GuestTax_ID',$companyid)->first();

                $Company_typeID=$guestdata->Company_type;
                if ($Company_typeID == [30,31,32]) {
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $fullName = "บริษัท ". $guestdata->Company_name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $fullName = "บริษัท ". $guestdata->Company_name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $fullName = "ห้างหุ้นส่วนจำกัด ". $guestdata->Company_name ;
                    }elseif ($Company_typeID > 32){
                        $fullName = $comtype->name_th . $guestdata->Company_name;
                    }
                }else{
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="นาย") {
                        $fullName = "นาย ". $guestdata->first_name . ' ' . $guestdata->last_name;
                    }elseif ($comtype->name_th =="นาง") {
                        $fullName = "นาง ". $guestdata->first_name . ' ' . $guestdata->last_name;
                    }elseif ($comtype->name_th =="นางสาว") {
                        $fullName = "นางสาว ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                    }else{
                        $fullName = "คุณ ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                    }
                }
                $Address=$guestdata->Address;
                $CityID=$guestdata->City;
                $amphuresID = $guestdata->Amphures;
                $TambonID = $guestdata->Tambon;
                $Identification = $guestdata->Identification_Number;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = guest_tax_phone::where('GuestTax_ID',$companyid)->where('Sequence','main')->first();
                $Email = $guestdata->Company_Email;
                $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
            }
        }
        $QuotationID = $Quotation->Quotation_ID;
        $Deposit = $deposit->count;
        $userid = Auth::user()->id;
        $user = User::where('id',$userid)->first();
        $vattype= $Quotation->vat_type;
        $vat_type_num= $Quotation->vat_type;
        $vat_type = master_document::where('id',$vattype)->first();
        $Nettotal = floatval(str_replace(',', '', $Payment));
        if ($Payment) {
            $Subtotal =0;
            $total =0;
            $addtax = 0;
            $before = 0;
            $balance =0;
            if ($vattype == 51) {
                $Subtotal = $Payment;
                $total = $Payment;
                $addtax = 0;
                $before = $Payment;
                $balance = $Subtotal;
            }else{
                $Subtotal = $Payment;
                $total = $Subtotal/1.07;
                $addtax = $Subtotal-$total;
                $before = $Subtotal-$addtax;
                $balance = $Subtotal;
            }
        }
        $company = $deposit->Company_ID;
        $Mvat = master_document::select('name_th','id','lavel')->where('status', '1')->where('Category','Mvat')->get();

        $amountdeposit = depositrevenue::where('Quotation_ID',$QuotationID)->where('document_status',2)->get();
        $amdeposit = 0;
        foreach ($amountdeposit as $key => $value) {
            $amdeposit += $value->amount;
        }

        $Nettotal = $Proposal->Nettotal - $amdeposit;
        return view('billingfolio.deposit.view',compact('name_ID','name','datasub','Payment','type','company','settingCompany','DepositID','Issue_date','ExpirationDate','fullName'
        ,'Email','address','Identification','phone','Quotation','QuotationID','Deposit','Payment','Subtotal','total','addtax','before','balance','user','vat_type','deposit','Mvat','vat_type_num',
        'Nettotal','amdeposit','Company_ID','ids'));
    }
    public function depositcancel(Request $request ,$id){
        $data = depositrevenue::where('id',$id)->first();
        $Quotation_ID = $data->Deposit_ID;
        $userid = Auth::user()->id;
        $Proposal_ID = $data->Quotation_ID;
        $Proposal = Quotation::where('Quotation_ID',$Proposal_ID)->first();
        try {

            if ($data->document_status == 1) {

                $Quotation = depositrevenue::find($id);
                $Quotation->document_status = 0;
                $Quotation->remark = $request->note;
                $Quotation->save();
            }elseif ($data->document_status == 2) {
                $Quotation = depositrevenue::find($id);
                $Quotation->document_status = 1;
                $Quotation->remark = $request->note;
                $Quotation->save();
            }

        } catch (\Throwable $e) {
            return redirect()->route('BillingFolio.index')->with('error', $e->getMessage());
        }
        try {
            $savelog = new log_company();
            $savelog->Created_by = $userid;
            $savelog->Company_ID = $Quotation_ID;
            $savelog->type = 'Cancel';
            $savelog->Category = 'Cancel :: Deposit Invoice';
            $savelog->content = 'Cancel Document Deposit Invoice ID : '.$Quotation_ID.'+'.$request->note;
            $savelog->save();
            return redirect()->route('BillingFolio.CheckPI', ['id' => $Proposal->id])->with('success', 'Data has been successfully saved.');
        } catch (\Throwable $e) {
            return redirect()->route('BillingFolio.index')->with('error', $e->getMessage());
        }
    }
    //Additional

    public function additional_re($id){
        $additional = proposal_overbill::where('id',$id)->first();

        $DepositID = $additional->Additional_ID;
        $Nettotal = $additional->total;
        $QuotationID = $additional->Quotation_ID;
        $companyid = $additional->Company_ID;
        $CompanyID = $additional->Company_ID;
        $IssueDate = $additional->issue_date;
        $ExpirationDate = $additional->Expirationdate;
        $data_cheque =receive_cheque::where('refer_proposal',$QuotationID)->where('status',1)->get();
        $currentDate = Carbon::now();
        $dateFormatted = $currentDate->format('d/m/Y');
        $ID = 'RE-';
        $formattedDate = Carbon::parse($currentDate);       // วันที่
        $month = $formattedDate->format('m'); // เดือน
        $year = $formattedDate->format('y');
        $lastRun = receive_payment::latest()->first();
        $nextNumber = 1;

        if ($lastRun == null) {
            $nextNumber = $lastRun + 1;

        }else{
            $lastRunid = $lastRun->id;
            $nextNumber = $lastRunid + 1;
        }
        $newRunNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        $REID = $ID.$year.$month.$newRunNumber;
        $parts = explode('-', $companyid);
        $firstPart = $parts[0];
        $Selectdata ='';
        if ($firstPart == 'C') {
            $Selectdata =  'Company';
            $company =  companys::where('Profile_ID',$companyid)->first();
            if ($company) {
                $ids = $company->id;
                $Address=$company->Address;
                $CityID=$company->City;
                $amphuresID = $company->Amphures;
                $TambonID = $company->Tambon;
                $Identification = $company->Taxpayer_Identification;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = company_phone::where('Profile_ID',$company->Profile_ID)->where('Sequence','main')->first();
                $Email = $company->Company_Email;
                $Company_typeID=$company->Company_type;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="บริษัทจำกัด") {
                    $fullName = "บริษัท ". $company->Company_Name . " จำกัด";
                }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                    $fullName = "บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                    $fullName = "ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                }else{
                    $fullName = $comtype->name_th . $company->Company_Name;
                }
                if ($comtype->name_th =="บริษัทจำกัด") {
                    $name = "บริษัท ". $company->Company_Name . " จำกัด";
                }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                    $name = "บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                    $name = "ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                }else{
                    $name = $comtype->name_th . $company->Company_Name;
                }
                $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
                $name_ID = $company->Profile_ID;
                $datasub = company_tax::where('Company_ID',$name_ID)->get();
                $fax = company_fax::where('Profile_ID',$name_ID)->first();


            }
        }else{
            $guestdata =  Guest::where('Profile_ID',$companyid)->first();
            if ($guestdata) {
                $ids = $guestdata->id;
                $Selectdata =  'Guest';
                $Company_typeID=$guestdata->preface;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="นาย") {
                    $fullName = "นาย ". $guestdata->First_name . ' ' . $guestdata->Last_name;
                }elseif ($comtype->name_th =="นาง") {
                    $fullName = "นาง ". $guestdata->First_name . ' ' . $guestdata->Last_name;
                }elseif ($comtype->name_th =="นางสาว") {
                    $fullName = "นางสาว ". $guestdata->First_name . ' ' . $guestdata->Last_name ;
                }else{
                    $fullName = "คุณ ". $guestdata->First_name . ' ' . $guestdata->Last_name ;
                }
                $name =  'คุณ '.$guestdata->First_name.' '.$guestdata->Last_name;
                $Address=$guestdata->Address;
                $CityID=$guestdata->City;
                $amphuresID = $guestdata->Amphures;
                $TambonID = $guestdata->Tambon;
                $Identification = $guestdata->Identification_Number;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = phone_guest::where('Profile_ID',$guestdata->Profile_ID)->where('Sequence','main')->first();
                $Email = $guestdata->Company_Email;
                $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
                $name_ID = $guestdata->Profile_ID;
                $datasub = guest_tax::where('Company_ID',$name_ID)->get();
                $fax ='-';
            }
        }
        $names = []; // สร้าง array เพื่อเก็บค่าชื่อ

        if ($datasub) {
            foreach ($datasub as $key => $item) {
                $comtype = DB::table('master_documents')->where('id', $item->Company_type)->first();

                if ($comtype) { // ตรวจสอบว่า $comtype ไม่เป็น null
                    if ($firstPart == 'C') {
                        if ($comtype->name_th == "บริษัทจำกัด") {
                            $name = "บริษัท " . $item->Companny_name . " จำกัด";
                        } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                            $name = "บริษัท " . $item->Companny_name . " จำกัด (มหาชน)";
                        } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                            $name = "ห้างหุ้นส่วนจำกัด " . $item->Companny_name;
                        } else {
                            $name = $comtype->name_th . ($item->Companny_name ?? ($item->first_name . " " . $item->last_name));
                        }
                        $name_id = $item->ComTax_ID;
                    } else {
                        if ($comtype->name_th == "บริษัทจำกัด") {
                            $name = "บริษัท " . $item->Company_name . " จำกัด";
                        } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                            $name = "บริษัท " . $item->Company_name . " จำกัด (มหาชน)";
                        } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                            $name = "ห้างหุ้นส่วนจำกัด " . $item->Company_name;
                        } else {
                            $name = $comtype->name_th . ($item->Company_name ?? ($item->first_name . " " . $item->last_name));
                        }
                        $name_id = $item->GuestTax_ID;
                    }

                    // เก็บค่า $name ลงใน array
                    $names[$key + 1] = [
                        'id' => $name_id ?? null,
                        'name' => $name ?? null,
                    ];
                }
            }
        }
        $datamain[0] = [
            'id' => $name_ID ?? null,
            'name' => $name ?? null,
        ];
        $data_select = array_merge($datamain, $names);

        $settingCompany = Master_company::orderBy('id', 'desc')->first();
        $userid = Auth::user()->id;
        $user = User::where('id',$userid)->first();
        if ($Nettotal) {
            $Subtotal =0;
            $total =0;
            $addtax = 0;
            $before = 0;
            $balance =0;
            $Subtotal = $Nettotal;
            $total = $Subtotal/1.07;
            $addtax = $Subtotal-$total;
            $before = $Subtotal-$addtax;
            $balance = $Subtotal;
        }
        $Proposal = Quotation::where('Quotation_ID',$QuotationID)->first();
        $data_bank = Masters::where('category', "bank")->where('status', 1)->select('id', 'name_th', 'name_en')->get();
        $additional_Nettotal= 0;
        $Cash= 0;
        $Complimentary= 0;
        if ($additional) {
            $additional_type = $additional->additional_type;
            $additional_Nettotal = $additional->Nettotal;
            if ($additional_type == 'Cash') {
                $Cash = $additional_Nettotal*0.37;
                $Complimentary = $additional_Nettotal-$additional_Nettotal*0.37	;
            }elseif ($additional_type == 'Cash Manual') {
                $Cash = $additional->Cash;
                $Complimentary = $additional->Complimentary	;
            }else{
                $Cash = $additional_Nettotal;
                $Complimentary = 0	;
            }
        }
        return view('billingfolio.additional.paidadditinal',compact('DepositID','QuotationID','data_bank','data_cheque','fullName','address','Identification','Email','phone'
        ,'Nettotal','settingCompany','user','Subtotal','total','addtax','before','balance','REID','Selectdata','Proposal','dateFormatted','data_select','name_ID','IssueDate','ExpirationDate'
        ,'additional_type','Cash','Complimentary'));
    }
    public function additional_save(Request $request){
        $data=$request->all();
        $requestData = $request->all();
        $additional = $request->invoice;
        $cashcomp = $request->cashcomp ?? 0;
        $complimentary = floatval(str_replace(',', '', $request->Complimentary));
        $groupedData = []; // ตัวแปรสำหรับจัดเก็บข้อมูลที่ใช้ index

        foreach ($requestData as $key => $value) {
            if (strpos($key, 'paymentType_') === 0) { // ตรวจสอบว่าคีย์ขึ้นต้นด้วย 'paymentType_'
                preg_match('/\d+$/', $key, $matches); // ดึงตัวเลขท้ายคีย์
                $index = $matches[0]; // ตัวเลขที่ได้จากคีย์ เช่น 0, 1, 2
                $groupedData[$index ] = [
                    "paymentType" =>  $data["paymentType"] ?? null, // ค่า paymentType_x ที่ดึงมา
                    "cashAmount" =>  $data["cashAmount"] ?? null,
                    "bank" => $data["bank"] ?? null, // ค้นหา bank_x
                    "bankTransferAmount" => $data["bankTransferAmount"] ?? null, // ค้นหา bankTransferAmount_x
                    "CardNumber" => $data["CardNumber"] ?? null, // ค้นหา CardNumber_x
                    "Expiry" => $data["Expiry"] ?? null, // ค้นหา Expiry_x
                    "creditCardAmount" => $data["creditCardAmount"] ?? null, // ค้นหา creditCardAmount_x
                    "cheque" => $data["cheque"] ?? null, // ค้นหา creditCardAmount_
                    "deposit_date" => $data["deposit_date"] ?? null, // ค้นหา deposit_date_x
                    "chequebank" => $data["chequebank"] ?? null,
                    "chequebank_name" => $data["chequebank_name"] ?? null,
                    "chequeamount" => isset($data["chequeamount"])
                    ? str_replace([',', '.00'], ['', ''], $data["chequeamount"]) // ถอดคอมมาและ .00
                    : null, // ถ้าไม่มีค่าก็ให้เป็น null
                     // ถ้าไม่มีค่าก็ให้เป็น null
                    "detail" => ($data["paymentType"] == 'cash')
                    ? 'Cash'
                    : ($data["paymentType"] == 'bankTransfer'
                        ? $data["bank"] . ' Bank Transfer - Together Resort Ltd'
                        : ($data["paymentType"] == 'creditCard'
                            ? 'Credit Card No. ' . $data["CardNumber"] . ' Exp. Date: ' . $data["Expiry"]
                            : ($data["paymentType"] == 'cheque'
                                ? 'Cheque Bank ' . $data["chequebank_name"] . ' Cheque Number ' . $data["cheque"]
                                    : null
                            )
                        )
                    ),
                ];
                // สร้างอาร์เรย์ใหม่ที่ใช้ index
                $groupedData[$index] = [
                    "paymentType" => $value, // ค่า paymentType_x ที่ดึงมา
                    "cashAmount" =>  $requestData["cashAmount_$index"] ?? null,
                    "bank" => $requestData["bank_$index"] ?? null, // ค้นหา bank_x
                    "bankTransferAmount" => $requestData["bankTransferAmount_$index"] ?? null, // ค้นหา bankTransferAmount_x
                    "CardNumber" => $requestData["CardNumber_$index"] ?? null, // ค้นหา CardNumber_x
                    "Expiry" => $requestData["Expiry_$index"] ?? null, // ค้นหา Expiry_x
                    "creditCardAmount" => $requestData["creditCardAmount_$index"] ?? null, // ค้นหา creditCardAmount_x
                    "cheque" => $requestData["cheque_$index"] ?? null, // ค้นหา creditCardAmount_
                    "deposit_date" => $requestData["deposit_date_$index"] ?? null, // ค้นหา deposit_date_x
                    "chequebank" => $requestData["chequebank_$index"] ?? null,
                    "chequebank_name" => $requestData["chequebank_name_$index"] ?? null,
                    "chequeamount" => isset($requestData["chequeamount_$index"])
                    ? str_replace([',', '.00'], ['', ''], $requestData["chequeamount_$index"]) // ถอดคอมมาและ .00
                    : null, // ถ้าไม่มีค่าก็ให้เป็น null
                    "detail" => ($value == 'cash')
                    ? 'Cash'
                    :
                    ($value == 'bankTransfer'
                        ? $requestData["bank_$index"] . ' Bank Transfer - Together Resort Ltd'
                        : ($value == 'creditCard'
                            ? 'Credit Card No. ' . $requestData["CardNumber_$index"] . ' Exp. Date: ' . $requestData["Expiry_$index"]
                            : ($value == 'cheque'
                                ? 'Cheque Bank ' . $requestData["chequebank_name_$index"] . ' Cheque Number ' . $requestData["cheque_$index"]
                                : null
                            )
                        )
                    ),
                ];
            }else{
                $groupedData[0] = [
                    "paymentType" =>  $data["paymentType"] ?? null, // ค่า paymentType_x ที่ดึงมา
                    "cashAmount" =>  $data["cashAmount"] ?? null,
                    "bank" => $data["bank"] ?? null, // ค้นหา bank_x
                    "bankTransferAmount" => $data["bankTransferAmount"] ?? null, // ค้นหา bankTransferAmount_x
                    "CardNumber" => $data["CardNumber"] ?? null, // ค้นหา CardNumber_x
                    "Expiry" => $data["Expiry"] ?? null, // ค้นหา Expiry_x
                    "creditCardAmount" => $data["creditCardAmount"] ?? null, // ค้นหา creditCardAmount_x
                    "cheque" => $data["cheque"] ?? null, // ค้นหา creditCardAmount_
                    "deposit_date" => $data["deposit_date"] ?? null, // ค้นหา deposit_date_x
                    "chequebank" => $data["chequebank"] ?? null,
                    "chequebank_name" => $data["chequebank_name"] ?? null,
                    "chequeamount" => isset($data["chequeamount"])
                    ? str_replace([',', '.00'], ['', ''], $data["chequeamount"]) // ถอดคอมมาและ .00
                    : null, // ถ้าไม่มีค่าก็ให้เป็น null
                     // ถ้าไม่มีค่าก็ให้เป็น null
                    "detail" => ($data["paymentType"] == 'cash')
                    ? 'Cash'
                    : ($data["paymentType"] == 'bankTransfer'
                        ? $data["bank"] . ' Bank Transfer - Together Resort Ltd'
                        : ($data["paymentType"] == 'creditCard'
                            ? 'Credit Card No. ' . $data["CardNumber"] . ' Exp. Date: ' . $data["Expiry"]
                            : ($data["paymentType"] == 'cheque'
                                ? 'Cheque Bank ' . $data["chequebank_name"] . ' Cheque Number ' . $data["cheque"]
                                    : null
                            )
                        )
                    ),
                ];
            }
        }
        $cash = 0;
        $cashbankTransfer = 0;
        $cashCard = 0;
        $cashcheque =0;
        foreach ($groupedData as $value) {
            $cash += isset($value['cashAmount']) ? (float)$value['cashAmount'] : 0;
            $cashbankTransfer += isset($value['bankTransferAmount']) ? (float)$value['bankTransferAmount'] : 0;
            $cashCard += isset($value['creditCardAmount']) ? (float)$value['creditCardAmount'] : 0;
            $cashcheque += isset($value['chequeamount']) ? (float)$value['chequeamount'] : 0;
        }
        $Amountall = $cash+$cashbankTransfer+$cashCard+$cashcheque;
        $Amount = floatval($Amountall);
        $detail = proposal_overbill::where('Additional_ID',$additional)->first();
        $sumpayment = $detail->total;
        $created_at = Carbon::parse($detail->created_at)->format('d/m/Y');
        $idinvoices = $detail->id;
        $Quotation_ID = $detail->Quotation_ID;
        $Quotation = Quotation::where('Quotation_ID', $Quotation_ID)->first();
        $type_Proposal = $Quotation->type_Proposal;
        if ($detail->additional_type == 'H/G') {
            $RealAmount = $cash+$cashbankTransfer+$cashCard+$cashcheque;
        }else{
            $RealAmount = $cashcomp;
        }
        $guest = $request->Guest;
        $companyid = $request->Guest;
        $reservationNo = $request->reservationNo;
        $room = $request->roomNo;
        $numberOfGuests = $request->numberOfGuests;
        $arrival = $request->arrival;
        $departure = $request->departure;
        $note = $request->note;
        $paymentDate = $request->paymentDate;
        $paymentType = $request->paymentTypecheque ?? $request->paymentType ?? 'cash';
        if ($paymentType == null || $companyid == null || $reservationNo == null || $room == null || $numberOfGuests == null || $arrival == null || $departure == null) {
            return redirect()->route('BillingFolio.index')->with('error', 'กรุณากรอกข้อมูลให้ครบ');
        }
        $invoice = $request->invoice;
        $parts = explode('-', $companyid);
        $firstPart = $parts[0];
        $Selectdata ='';
        if ($firstPart == 'C') {
            $Selectdata =  'Company';
            $company =  companys::where('Profile_ID',$companyid)->first();

            if ($company) {
                $Address=$company->Address;
                $CityID=$company->City;
                $amphuresID = $company->Amphures;
                $TambonID = $company->Tambon;
                $Identification = $company->Taxpayer_Identification;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = company_phone::where('Profile_ID',$company->Profile_ID)->where('Sequence','main')->first();
                $email = $company->Company_Email;
                $Company_typeID=$company->Company_type;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="บริษัทจำกัด") {
                    $name = 'ลูกค้า : '."บริษัท ". $company->Company_Name . " จำกัด";
                    $nameold = "บริษัท ". $company->Company_Name . " จำกัด";
                }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                    $name = 'ลูกค้า : '."บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                    $nameold = "บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                    $name = 'ลูกค้า : '."ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                    $nameold = "ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                }else{
                    $name = 'ลูกค้า : '.$comtype->name_th . $company->Company_Name;
                    $nameold = $comtype->name_th . $company->Company_Name;
                }
            }else{

                $company =  company_tax::where('ComTax_ID',$companyid)->first();
                $Company_typeID=$company->Company_type;
                if ($Company_typeID == [30,31,32]) {
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $name = 'ลูกค้า : '."บริษัท ". $company->Company_Name . " จำกัด";
                        $nameold = "บริษัท ". $company->Company_Name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $name = 'ลูกค้า : '."บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                        $nameold = "บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $name = 'ลูกค้า : '."ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                        $nameold = "ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                    }else{
                        $name = 'ลูกค้า : '.$comtype->name_th . $company->Company_Name;
                        $nameold = $comtype->name_th . $company->Company_Name;
                    }
                }else{
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="นาย") {
                        $nameold = "นาย ". $company->first_name . ' ' . $company->last_name;
                        $name = 'ลูกค้า : '."นาย ". $company->first_name . ' ' . $company->last_name;
                    }elseif ($comtype->name_th =="นาง") {
                        $nameold = "นาง ". $company->first_name . ' ' . $company->last_name;
                        $name = 'ลูกค้า : '."นาง ". $company->first_name . ' ' . $company->last_name;
                    }elseif ($comtype->name_th =="นางสาว") {
                        $nameold = "นางสาว ". $company->first_name . ' ' . $company->last_name ;
                        $name = 'ลูกค้า : '."นางสาว ". $company->first_name . ' ' . $company->last_name ;
                    }else{
                        $nameold = "คุณ ". $company->first_name . ' ' . $company->last_name ;
                        $name = 'ลูกค้า : '."คุณ ". $company->first_name . ' ' . $company->last_name ;
                    }
                }
                $Address=$company->Address;
                $CityID=$company->City;
                $amphuresID = $company->Amphures;
                $TambonID = $company->Tambon;
                $Identification = $company->Taxpayer_Identification;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = company_tax_phone::where('ComTax_ID',$companyid)->where('Sequence','main')->first();
                $email = $company->Company_Email;
            }
        }else{

            $guestdata =  Guest::where('Profile_ID',$companyid)->first();

            if ($guestdata) {
                $Selectdata =  'Guest';
                $Company_typeID=$guestdata->preface;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="นาย") {
                    $nameold = "นาย ". $guestdata->First_name . ' ' . $guestdata->Last_name;
                    $name = 'ลูกค้า : '."นาย ". $guestdata->First_name . ' ' . $guestdata->Last_name;
                }elseif ($comtype->name_th =="นาง") {
                    $nameold = "นาง ". $guestdata->First_name . ' ' . $guestdata->Last_name;
                    $name = 'ลูกค้า : '."นาง ". $guestdata->First_name . ' ' . $guestdata->Last_name;
                }elseif ($comtype->name_th =="นางสาว") {
                    $nameold = "นางสาว ". $guestdata->First_name . ' ' . $guestdata->Last_name ;
                    $name = 'ลูกค้า : '."นางสาว ". $guestdata->First_name . ' ' . $guestdata->Last_name ;
                }else{
                    $nameold = "คุณ ". $guestdata->First_name . ' ' . $guestdata->Last_name ;
                    $name = 'ลูกค้า : '."คุณ ". $guestdata->First_name . ' ' . $guestdata->Last_name ;
                }
                $Address=$guestdata->Address;
                $CityID=$guestdata->City;
                $amphuresID = $guestdata->Amphures;
                $TambonID = $guestdata->Tambon;
                $Identification = $guestdata->Identification_Number;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = phone_guest::where('Profile_ID',$guestdata->Profile_ID)->where('Sequence','main')->first();
                $email = $guestdata->Company_Email;
            }else{
                $guestdata =  guest_tax::where('GuestTax_ID',$companyid)->first();
                $Company_typeID=$guestdata->Company_type;
                if ($Company_typeID == [30,31,32]) {
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $nameold = "บริษัท ". $guestdata->Company_name . " จำกัด";
                        $name = 'ลูกค้า : '."บริษัท ". $guestdata->Company_name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $nameold = "บริษัท ". $guestdata->Company_name . " จำกัด (มหาชน)";
                        $name = 'ลูกค้า : '."บริษัท ". $guestdata->Company_name . " จำกัด";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $nameold = "ห้างหุ้นส่วนจำกัด ". $guestdata->Company_name ;
                        $name = 'ลูกค้า : '."บริษัท ". $guestdata->Company_name . " จำกัด";
                    }elseif ($Company_typeID > 32){
                        $nameold = $comtype->name_th . $guestdata->Company_name;
                        $name = 'ลูกค้า : '."บริษัท ". $guestdata->Company_name . " จำกัด";
                    }
                }else{
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="นาย") {
                        $nameold = "นาย ". $guestdata->first_name . ' ' . $guestdata->last_name;
                        $name = 'ลูกค้า : '."นาย ". $guestdata->first_name . ' ' . $guestdata->last_name;
                    }elseif ($comtype->name_th =="นาง") {
                        $nameold = "นาง ". $guestdata->first_name . ' ' . $guestdata->last_name;
                        $name = 'ลูกค้า : '."นาง ". $guestdata->first_name . ' ' . $guestdata->last_name;
                    }elseif ($comtype->name_th =="นางสาว") {
                        $nameold = "นางสาว ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                        $name = 'ลูกค้า : '. "นางสาว ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                    }else{
                        $nameold = "คุณ ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                        $name = 'ลูกค้า : '."คุณ ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                    }
                }
                $Address=$guestdata->Address;
                $CityID=$guestdata->City;
                $amphuresID = $guestdata->Amphures;
                $TambonID = $guestdata->Tambon;
                $Identification = $guestdata->Identification_Number;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = guest_tax_phone::where('GuestTax_ID',$companyid)->where('Sequence','main')->first();
                $email = $guestdata->Company_Email;
            }
        }

        $currentDate = Carbon::now();
        $ID = 'RE-';
        $formattedDate = Carbon::parse($currentDate);       // วันที่
        $month = $formattedDate->format('m'); // เดือน
        $year = $formattedDate->format('y');
        $lastRun = receive_payment::lockForUpdate()->orderBy('id', 'desc')->first();
        $nextNumber = 1;

        if ($lastRun == null) {
            $nextNumber = $lastRun + 1;
        }else{
            $lastRunid = $lastRun->id;
            $nextNumber = $lastRunid + 1;
        }
        $newRunNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        $REID = $ID.$year.$month.$newRunNumber;
        try {
            $Reservation_No = null;
            if ($reservationNo) {
                $Reservation_No = 'Reservation No : '.$reservationNo;
            }
            $Room_No = null;
            if ($room) {
                $Room_No = 'Room No : '.$room;
            }
            $NumberOfGuests = null;
            if ($numberOfGuests) {
                $NumberOfGuests = 'No. of guest : '.$numberOfGuests;
            }
            $Arrival = null;
            if ($arrival) {
                $Arrival = 'Arrival : '.$arrival;
            }
            $Departure = null;
            if ($departure) {
                $Departure = 'Departure : '.$departure;
            }
            $PaymentDate = null;
            if ($paymentDate) {
                $PaymentDate = 'วันที่ชำระ : '.$paymentDate;
            }
            $Note = null;
            if ($note) {
                $Note = 'รายละเอียด : '.$note;
            }
            $fullname = 'รหัส : '.$REID;
            $amoute = 'ราคาที่จ่าย : '.number_format($RealAmount) . ' บาท';
            $total = 'ราคาออกเอกสาร : '.number_format($sumpayment) . ' บาท';
            $Reference_ID = 'อ้างอิงรหัส : '.$request->invoice;
            $edit ='รายการ';

            $formattedProductData = [];

            foreach ($groupedData as $product) {
                $totalAmount =
                                ($product['cashAmount'] ?? 0) +
                                ($product['bankTransferAmount'] ?? 0) +
                                ($product['creditCardAmount'] ?? 0) +
                                ($product['chequeamount'] ?? 0);

                $formattedPrice = number_format($totalAmount) . ' บาท';
                $formattedProductData[] = 'Description : ' . $product['detail'] . ' , '  . 'Price item : ' . $formattedPrice;
            }

            $datacompany = '';

            $variables = [$fullname,$Reference_ID, $Reservation_No,$Room_No,$NumberOfGuests, $Arrival,$Departure,$PaymentDate,$Note,$amoute,$total,$edit];
            $formattedProductDataString = implode(' + ', $formattedProductData);
            // แปลง array ของ $formattedProductData เป็น string เดียวที่มีรายการทั้งหมด
            $variables[] = $formattedProductDataString;


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
            $save->Company_ID = $REID;
            $save->type = 'Create';
            $save->Category = 'Create :: Billing Folio';
            $save->content =$datacompany;
            $save->save();
        } catch (\Throwable $e) {
            return redirect()->route('BillingFolio.index')->with('error', $e->getMessage());
        }
        try {
            $settingCompany = Master_company::orderBy('id', 'desc')->first();
            $parts = explode('-', $guest);
            $firstPart = $parts[0];
            if ($firstPart == 'C') {
                $company =  companys::where('Profile_ID',$guest)->first();
                if ($company) {
                    $fullname = "";
                    $Company_typeID=$company->Company_type;
                    if ($company->Company_Name) {
                        $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                        if ($comtype->name_th =="บริษัทจำกัด") {
                            $fullnameCom = " "." บริษัท ". $company->Company_Name . " จำกัด";
                        }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                            $fullnameCom = " "." บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                        }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                            $fullnameCom = " "." ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                        }else {
                            $fullnameCom = $comtype->name_th . $company->Company_Name;
                        }
                    }else{
                        $fullnameCom = "";
                    }
                    $Address=$company->Address;
                    $CityID=$company->City;
                    $amphuresID = $company->Amphures;
                    $TambonID = $company->Tambon;
                    $Identification = $company->Taxpayer_Identification;
                    $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                    $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                    $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                    if ($provinceNames) {
                        $province = ' จังหวัด '.$provinceNames->name_th;
                        $amphures = ' อำเภอ '.$amphuresID->name_th;
                        $tambon = ' ตำบล '.$TambonID->name_th;
                        $zip_code = $TambonID->Zip_Code;
                    }else{
                        $province ="";
                        $amphures="";
                        $tambon="";
                        $zip_code="";
                    }
                }else{
                    $company =  company_tax::where('ComTax_ID',$guest)->first();
                    $fullname = $company && $company->Companny_name
                                ? ""
                                : 'คุณ ' . $company->first_name . ' ' . $company->last_name;
                    $Company_typeID=$company->Company_type;
                    if ($company->Companny_name) {
                        $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                        if ($comtype->name_th =="บริษัทจำกัด") {
                            $fullnameCom = " "." บริษัท ". $company->Companny_name . " จำกัด";
                        }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                            $fullnameCom = " "." บริษัท ". $company->Companny_name . " จำกัด (มหาชน)";
                        }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                            $fullnameCom = " "." ห้างหุ้นส่วนจำกัด ". $company->Companny_name ;
                        }else {
                            $fullnameCom = $comtype->name_th . $company->Companny_name;
                        }
                    }else{
                        $fullnameCom = "";
                    }
                    $Address=$company->Address;
                    $CityID=$company->City;
                    $amphuresID = $company->Amphures;
                    $TambonID = $company->Tambon;
                    $Identification = $company->Taxpayer_Identification;
                    $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                    $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                    $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                    if ($provinceNames) {
                        $province = ' จังหวัด '.$provinceNames->name_th;
                        $amphures = ' อำเภอ '.$amphuresID->name_th;
                        $tambon = ' ตำบล '.$TambonID->name_th;
                        $zip_code = $TambonID->Zip_Code;
                    }else{
                        $province ="";
                        $amphures="";
                        $tambon="";
                        $zip_code="";
                    }
                }
            }else{
                $guestdata =  Guest::where('Profile_ID',$guest)->first();
                if ($guestdata) {
                    $fullname =  'คุณ '.$guestdata->First_name.' '.$guestdata->Last_name;
                    $fullnameCom = "";
                    $Address=$guestdata->Address;
                    $CityID=$guestdata->City;
                    $amphuresID = $guestdata->Amphures;
                    $TambonID = $guestdata->Tambon;
                    $Identification = $guestdata->Identification_Number;
                    $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                    $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                    $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                    if ($provinceNames) {
                        $province = ' จังหวัด '.$provinceNames->name_th;
                        $amphures = ' อำเภอ '.$amphuresID->name_th;
                        $tambon = ' ตำบล '.$TambonID->name_th;
                        $zip_code = $TambonID->Zip_Code;
                    }else{
                        $province ="";
                        $amphures="";
                        $tambon="";
                        $zip_code="";
                    }
                }else{
                    $guestdata =  guest_tax::where('GuestTax_ID',$guest)->first();
                    $fullname = $guestdata && $guestdata->Company_name
                                ? ""
                                : 'คุณ ' . $guestdata->first_name . ' ' . $guestdata->last_name;
                    $Company_typeID=$guestdata->Company_type;
                    if ($guestdata->Company_name) {
                        $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                        if ($comtype->name_th =="บริษัทจำกัด") {
                            $fullnameCom = " "." บริษัท ". $guestdata->Company_name . " จำกัด";
                        }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                            $fullnameCom = " "." บริษัท ". $guestdata->Company_name . " จำกัด (มหาชน)";
                        }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                            $fullnameCom = " "." ห้างหุ้นส่วนจำกัด ". $guestdata->Company_name ;
                        }else {
                            $fullnameCom = $comtype->name_th . $guestdata->Company_name;
                        }
                    }else{
                        $fullnameCom = "";
                    }
                    $Address=$guestdata->Address;
                    $CityID=$guestdata->City;
                    $amphuresID = $guestdata->Amphures;
                    $TambonID = $guestdata->Tambon;
                    $Identification = $guestdata->Identification_Number;
                    $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                    $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                    $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                    if ($provinceNames) {
                        $province = ' จังหวัด '.$provinceNames->name_th;
                        $amphures = ' อำเภอ '.$amphuresID->name_th;
                        $tambon = ' ตำบล '.$TambonID->name_th;
                        $zip_code = $TambonID->Zip_Code;
                    }else{
                        $province ="";
                        $amphures="";
                        $tambon="";
                        $zip_code="";
                    }

                }
            }
            $date = Carbon::now();
            $Date = $paymentDate;
            $dateFormatted = $date->format('d/m/Y').' / ';
            $dateFormatted = $date->format('d/m/Y');
            $dateTime = $date->format('H:i:s');
            $Amount = $Amountall;
            $userid = Auth::user()->id;
            $user = User::where('id',$userid)->first();

            $product = [];
            foreach ($groupedData as $value) {
                $totalAmount =
                                ($value['cashAmount'] ?? 0) +
                                ($value['bankTransferAmount'] ?? 0) +
                                ($value['creditCardAmount'] ?? 0) +
                                ($value['chequeamount'] ?? 0);
                $product[] = [
                    'detail' => $value['detail'],
                    'amount' => $totalAmount,
                ];
            }

            $productItems = array_merge($product);

            $template = master_template::query()->latest()->first();
            $Proposal = null;
            $productItems = array_merge($product);
            $Additional = null;
            $receive = null;
            $bill= null;
            $key = null;
            $data = [
                'settingCompany'=>$settingCompany,
                'fullname'=>$fullname,
                'fullnameCom'=>$fullnameCom,
                'Identification'=>$Identification,
                'Address'=>$Address,
                'province'=>$province,
                'amphures'=>$amphures,
                'tambon'=>$tambon,
                'zip_code'=>$zip_code,
                'reservationNo'=>$reservationNo,
                'room'=>$room,
                'user'=>$user,
                'arrival'=>$arrival,
                'departure'=>$departure,
                'numberOfGuests'=>$numberOfGuests,
                'dateFormatted'=>$dateFormatted,
                'dateTime'=>$dateTime,
                'created_at'=>$created_at,
                'Date'=>$Date,
                'note'=>$note,
                'productItems'=>$productItems,
                'invoice'=>$REID,
                'Amount'=>$Amount,
                'receive'=>$receive ?? [],
                'Additional'=>$Additional,
                'Proposal'=>$Proposal,
                'bill'=>$bill ?? 1,
                'key'=>$key ?? 1,
            ];
            $view= $template->name;
            $pdf = FacadePdf::loadView('billingfolioPDF.'.$view,$data);
            $path = 'PDF/billingfolio/';
            // return $pdf->stream();
            $pdf->save($path . $REID . '.pdf');
            $currentDateTime = Carbon::now();
            $currentDate = $currentDateTime->toDateString(); // Format: YYYY-MM-DD
            $currentTime = $currentDateTime->toTimeString(); // Format: HH:MM:SS

            // Optionally, you can format the date and time as per your requirement
            $formattedDate = $currentDateTime->format('Y-m-d'); // Custom format for date
            $formattedTime = $currentDateTime->format('H:i:s');
            $savePDF = new log();
            $savePDF->Quotation_ID = $REID;
            $savePDF->QuotationType = 'Receipt';
            $savePDF->Company_Name = !empty($fullnameCom) ? $fullnameCom : $fullname;
            $savePDF->Approve_date = $formattedDate;
            $savePDF->Approve_time = $formattedTime;
            $savePDF->save();
        } catch (\Throwable $e) {
            return redirect()->route('BillingFolio.index')->with('error', $e->getMessage());
        }
        try {
            $user = Auth::user()->id;
            $save = new receive_payment();
            $save->Receipt_ID = $REID;
            $save->Additional_ID = $invoice;
            $save->Quotation_ID = $Quotation_ID;
            $save->company = $companyid;
            $save->Amount = $RealAmount;
            $save->fullname = $nameold;
            $save->document_amount = $Amount;
            $save->reservationNo = $reservationNo;
            $save->roomNo = $room;
            $save->numberOfGuests = $numberOfGuests;
            $save->arrival = $arrival;
            $save->departure = $departure;
            $save->type_Proposal = $type_Proposal;
            $save->paymentDate = $paymentDate;
            $save->Operated_by = $user;
            $save->note = $note;
            $save->valid = $created_at;
            $save->save();
        } catch (\Throwable $e) {
            return redirect()->route('BillingFolio.index')->with('error', $e->getMessage());
        }
        try {
            foreach ($groupedData as $index) {
                $item = new document_receive_item();
                $item->receive_id = $REID;
                $item->detail = $index['detail'];
                $item->amount =     ($index['cashAmount'] ?? 0) +
                                    ($index['bankTransferAmount'] ?? 0) +
                                    ($index['creditCardAmount'] ?? 0) +
                                    ($index['chequeamount'] ?? 0);
                $item->type = $index['paymentType'] ?? null;
                if ($index['paymentType'] == 'bankTransfer') {
                    $item->bank = $index['bank'] ?? null;
                }elseif ($index['paymentType'] == 'cheque') {
                    $item->bank = $index['chequebank_name'] ?? null;
                }
                $item->CardNumber = $index['CardNumber'] ?? null;
                $item->Expiry = $index['Expiry'] ?? null;
                $item->Cheque = $index['cheque'] ?? null;
                $item->Deposit_date = $index['deposit_date'] ?? null;
                $item->save();
            }
        } catch (\Throwable $e) {
            receive_payment::where('Receipt_ID',$REID)->first()->delete();
            return redirect()->route('BillingFolio.index')->with('error', $e->getMessage());
        }
        try {
            $saveRe = proposal_overbill::find($idinvoices);
            $saveRe->status_guest = 1;
            $saveRe->save();
            foreach ($groupedData as $index) {
                if (!empty($index['cheque'])) {
                    $chequeRe =receive_cheque::where('cheque_number',$index['cheque'])->where('status',1)->first();
                    $id_cheque = $chequeRe->id;
                    $savecheque = receive_cheque::find($id_cheque);
                    $savecheque->receive_payment =$index['chequebank'];
                    $savecheque->status = 2;
                    $savecheque->deduct_date = $formattedDate;
                    $savecheque->deduct_by = $userid;
                    $savecheque->save();
                }
            }

            $Quotationid = $Quotation->id;
            if ($Quotation->status_guest == 1) {
                $savequ = Quotation::find($Quotationid);
                $savequ->status_document = 9;
                $savequ->status_receive = 1;
                $savequ->status_guest = 0;
                $savequ->save();
            }else{
                $savequ = Quotation::find($Quotationid);
                $savequ->status_receive = 1;
                $savequ->save();
            }
            return redirect()->route('BillingFolio.index')->with('success', 'Data has been successfully saved.');
        } catch (\Throwable $e) {
            return redirect()->route('BillingFolio.index')->with('error', $e->getMessage());
        }

    }

    //check
    function deposit_edit($id){
        $deposit = depositrevenue::where('id',$id)->first();
        $companyid = $deposit->Company_ID;
        $Issue_date = $deposit->Issue_date;
        $Payment = $deposit->amount;
        $Quotation = Quotation::where('Quotation_ID',$deposit->Quotation_ID)->first();
        $Proposal = Quotation::where('Quotation_ID',$deposit->Quotation_ID)->first();
        $guest = $Proposal->Company_ID;
        $type = $Proposal->type_Proposal;
        if ($type == 'Company') {
            $data = companys::where('Profile_ID',$guest)->first();
            $Company_typeID=$data->Company_type;
            $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
            if ($comtype->name_th =="บริษัทจำกัด") {
                $name = "บริษัท ". $data->Company_Name . " จำกัด";
            }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                $name = "บริษัท ". $data->Company_Name . " จำกัด (มหาชน)";
            }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                $name = "ห้างหุ้นส่วนจำกัด ". $data->Company_Name ;
            }else{
                $name = $comtype->name_th . $data->Company_Name;
            }
            $name_ID = $data->Profile_ID;
            $datasub = company_tax::where('Company_ID',$name_ID)->get();
        }else {
            $data = Guest::where('Profile_ID',$guest)->first();
            $name =  'คุณ '.$data->First_name.' '.$data->Last_name;
            $name_ID = $data->Profile_ID;
            $datasub = guest_tax::where('Company_ID',$name_ID)->get();
        }
        $settingCompany = Master_company::orderBy('id', 'desc')->first();
        $DepositID = $deposit->Deposit_ID;
        $Issue_date = $deposit->Issue_date;
        $ExpirationDate = $deposit->ExpirationDate;
        $parts = explode('-', $companyid);
        $firstPart = $parts[0];

        if ($firstPart == 'C') {
            $Selectdata =  'Company';
            $company =  companys::where('Profile_ID',$companyid)->first();
            if ($company) {
                $Address=$company->Address;
                $CityID=$company->City;
                $amphuresID = $company->Amphures;
                $TambonID = $company->Tambon;
                $Identification = $company->Taxpayer_Identification;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = company_phone::where('Profile_ID',$company->Profile_ID)->where('Sequence','main')->first();
                $Email = $company->Company_Email;
                $Company_typeID=$company->Company_type;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="บริษัทจำกัด") {
                    $fullName = "บริษัท ". $company->Company_Name . " จำกัด";
                }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                    $fullName = "บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                    $fullName = "ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                }else{
                    $fullName = $comtype->name_th . $company->Company_Name;
                }
                $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
            }else{

                $company =  company_tax::where('ComTax_ID',$companyid)->first();

                $Company_typeID=$company->Company_type;
                if ($Company_typeID == [30,31,32]) {
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $fullName = "บริษัท ". $company->Companny_name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $fullName = "บริษัท ". $company->Companny_name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $fullName = "ห้างหุ้นส่วนจำกัด ". $company->Companny_name ;
                    }elseif ($Company_typeID > 32){
                        $fullName = $comtype->name_th . $company->Companny_name;
                    }
                }else{
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="นาย") {
                        $fullName = "นาย ". $company->first_name . ' ' . $company->last_name;
                    }elseif ($comtype->name_th =="นาง") {
                        $fullName = "นาง ". $company->first_name . ' ' . $company->last_name;
                    }elseif ($comtype->name_th =="นางสาว") {
                        $fullName = "นางสาว ". $company->first_name . ' ' . $company->last_name ;
                    }else{
                        $fullName = "คุณ ". $company->first_name . ' ' . $company->last_name ;
                    }
                }
                $Address=$company->Address;
                $CityID=$company->City;
                $amphuresID = $company->Amphures;
                $TambonID = $company->Tambon;
                $Identification = $company->Taxpayer_Identification;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = company_tax_phone::where('ComTax_ID',$companyid)->where('Sequence','main')->first();
                $Email = $company->Company_Email;
                $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
            }
        }else{

            $guestdata =  Guest::where('Profile_ID',$companyid)->first();

            if ($guestdata) {
                $Selectdata =  'Guest';
                $Company_typeID=$guestdata->preface;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="นาย") {
                    $fullName = "นาย ". $guestdata->first_name . ' ' . $guestdata->last_name;
                }elseif ($comtype->name_th =="นาง") {
                    $fullName = "นาง ". $guestdata->first_name . ' ' . $guestdata->last_name;
                }elseif ($comtype->name_th =="นางสาว") {
                    $fullName = "นางสาว ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                }else{
                    $fullName = "คุณ ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                }
                $Address=$guestdata->Address;
                $CityID=$guestdata->City;
                $amphuresID = $guestdata->Amphures;
                $TambonID = $guestdata->Tambon;
                $Identification = $guestdata->Identification_Number;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = phone_guest::where('Profile_ID',$guestdata->Profile_ID)->where('Sequence','main')->first();
                $Email = $guestdata->Company_Email;
                $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
            }else{
                $guestdata =  guest_tax::where('GuestTax_ID',$companyid)->first();

                $Company_typeID=$guestdata->Company_type;
                if ($Company_typeID == [30,31,32]) {
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $fullName = "บริษัท ". $guestdata->Company_name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $fullName = "บริษัท ". $guestdata->Company_name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $fullName = "ห้างหุ้นส่วนจำกัด ". $guestdata->Company_name ;
                    }elseif ($Company_typeID > 32){
                        $fullName = $comtype->name_th . $guestdata->Company_name;
                    }
                }else{
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="นาย") {
                        $fullName = "นาย ". $guestdata->first_name . ' ' . $guestdata->last_name;
                    }elseif ($comtype->name_th =="นาง") {
                        $fullName = "นาง ". $guestdata->first_name . ' ' . $guestdata->last_name;
                    }elseif ($comtype->name_th =="นางสาว") {
                        $fullName = "นางสาว ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                    }else{
                        $fullName = "คุณ ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                    }
                }
                $Address=$guestdata->Address;
                $CityID=$guestdata->City;
                $amphuresID = $guestdata->Amphures;
                $TambonID = $guestdata->Tambon;
                $Identification = $guestdata->Identification_Number;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = guest_tax_phone::where('GuestTax_ID',$companyid)->where('Sequence','main')->first();
                $Email = $guestdata->Company_Email;
                $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
            }
        }
        $QuotationID = $Quotation->Quotation_ID;
        $Deposit = $deposit->count;
        $userid = Auth::user()->id;
        $user = User::where('id',$userid)->first();
        $vattype= $Quotation->vat_type;
        $vat_type_num= $Quotation->vat_type;
        $vat_type = master_document::where('id',$vattype)->first();
        $Nettotal = floatval(str_replace(',', '', $Payment));
        if ($Payment) {
            $Subtotal =0;
            $total =0;
            $addtax = 0;
            $before = 0;
            $balance =0;
            if ($vattype == 51) {
                $Subtotal = $Payment;
                $total = $Payment;
                $addtax = 0;
                $before = $Payment;
                $balance = $Subtotal;
            }else{
                $Subtotal = $Payment;
                $total = $Subtotal/1.07;
                $addtax = $Subtotal-$total;
                $before = $Subtotal-$addtax;
                $balance = $Subtotal;
            }
        }
        $company = $deposit->Company_ID;
        $Mvat = master_document::select('name_th','id','lavel')->where('status', '1')->where('Category','Mvat')->get();

        $amountdeposit = depositrevenue::where('Quotation_ID',$QuotationID)->where('document_status',2)->get();
        $amdeposit = 0;
        foreach ($amountdeposit as $key => $value) {
            $amdeposit += $value->amount;
        }
        $Nettotal = $Proposal->Nettotal - $amdeposit;
        return view('billingfolio.deposit.edit',compact('name_ID','name','datasub','Payment','type','company','settingCompany','DepositID','Issue_date','ExpirationDate','fullName'
        ,'Email','address','Identification','phone','Quotation','QuotationID','Deposit','Payment','Subtotal','total','addtax','before','balance','user','vat_type','deposit','Mvat','vat_type_num',
        'Nettotal','amdeposit','Proposal'));
    }

    public function deposit_update(Request $request,$id){
        $data = $request->all();

        $userid = Auth::user()->id;
        $datarequest = [
            'Proposal_ID' => $data['QuotationID'] ?? null,
            'IssueDate' => $data['IssueDate'] ?? null,
            'Expiration' => $data['Expiration'] ?? null,
            'Sum' => $data['totaldeposit'] ?? null,
            'company' => $data['Guest'] ?? null,
            'fullname' => $data['fullname'] ?? null,
        ];
        $deposit = depositrevenue::where('id',$id)->first();
        $deposit_id = $id;
        $IssueDateMain = $deposit->Issue_date;
        $ExpirationMain = $deposit->ExpirationDate;
        $AmountMain = $deposit->amount;
        $fullnameMain = $deposit->fullname;
        $companyMain = $deposit->Company_ID;
        $DepositID = $deposit->Deposit_ID;
        $correct = $deposit->correct;
        $Deposit = $deposit->count;
        if ($correct >= 1) {
            $correctup = $correct + 1;
        }else{
            $correctup = 1;
        }

        $IssueDate = $datarequest['IssueDate'] ?? null;
        $Expiration = $datarequest['Expiration'] ?? null;
        $company = $datarequest['company'] ?? null;
        $Payment = $datarequest['Sum'] ?? null;
        $fullname = $datarequest['fullname'] ?? null;

        $Proposal_ID = $datarequest['Proposal_ID'] ?? null;
        $IssueDate = $datarequest['IssueDate'] ?? null;
        $Expiration = $datarequest['Expiration'] ?? null;

        $companyid = $datarequest['company'] ?? null;
        $Payment = $datarequest['Sum'] ?? null;
        $fullnamemain = $datarequest['fullname'] ?? null;
        $DepositID = $deposit->Deposit_ID;
        try {
            $Paymenttotal = null;
            if ($Payment != $AmountMain) {
                $Paymenttotal = 'ยอดเงิน : '.number_format($Payment). ' บาท';
            }
            $issuedate = null;
            if ($IssueDate != $IssueDateMain) {
                $issuedate = 'วันที่ออกเอกสาร : '.$IssueDate;
            }
            $expiration = null;
            if ($Expiration != $ExpirationMain) {
                $expiration = 'วันที่เอกสารหมดอายุ : '.$Expiration;
            }
            $full_name = null;
            if ($fullname != $fullnameMain) {
                $full_name = 'ชื่อบริษัท/ลูกค้า : '.$fullname.' + '.'รหัส : '.$company;
            }
            $full = 'แก้ไขข้อมูลเป็น';
            $datacompany = '';

            $variables = [$full,$full_name, $issuedate, $expiration, $Paymenttotal];

            foreach ($variables as $variable) {
                if (!empty($variable)) {
                    if (!empty($datacompany)) {
                        $datacompany .= ' + ';
                    }
                    $datacompany .= $variable;
                }
            }

            $userids = Auth::user()->id;
            $save = new log_company();
            $save->Created_by = $userids;
            $save->Company_ID = $DepositID;
            $save->type = 'Edit';
            $save->Category = 'Edit :: Invoice / Deposit';
            $save->content =$datacompany;
            $save->save();

            $parts = explode('-', $companyid);
            $firstPart = $parts[0];
            if ($firstPart == 'C') {
                $Selectdata =  'Company';
                $company =  companys::where('Profile_ID',$companyid)->first();

                if ($company) {
                    $Address=$company->Address;
                    $CityID=$company->City;
                    $amphuresID = $company->Amphures;
                    $TambonID = $company->Tambon;
                    $Identification = $company->Taxpayer_Identification;
                    $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                    $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                    $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                    $phone = company_phone::where('Profile_ID',$company->Profile_ID)->where('Sequence','main')->first();
                    $email = $company->Company_Email;
                    $Company_typeID=$company->Company_type;
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $fullname = "บริษัท ". $company->Company_Name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $fullname = "บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $fullname = "ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                    }else{
                        $fullname = $comtype->name_th . $company->Company_Name;
                    }
                }else{

                    $company =  company_tax::where('ComTax_ID',$companyid)->first();
                    $Company_typeID=$company->Company_type;
                    if ($Company_typeID == [30,31,32]) {
                        $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                        if ($comtype->name_th =="บริษัทจำกัด") {
                            $fullname = "บริษัท ". $company->Companny_name . " จำกัด";
                        }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                            $fullname = "บริษัท ". $company->Companny_name . " จำกัด (มหาชน)";
                        }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                            $fullname = "ห้างหุ้นส่วนจำกัด ". $company->Companny_name ;
                        }elseif ($Company_typeID > 32){
                            $fullname = $comtype->name_th . $company->Companny_name;
                        }
                    }else{
                        $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                        if ($comtype->name_th =="นาย") {
                            $fullname = "นาย ". $company->first_name . ' ' . $company->last_name;
                        }elseif ($comtype->name_th =="นาง") {
                            $fullname = "นาง ". $company->first_name . ' ' . $company->last_name;
                        }elseif ($comtype->name_th =="นางสาว") {
                            $fullname = "นางสาว ". $company->first_name . ' ' . $company->last_name ;
                        }else{
                            $fullname = "คุณ ". $company->first_name . ' ' . $company->last_name ;
                        }
                    }
                    $Address=$company->Address;
                    $CityID=$company->City;
                    $amphuresID = $company->Amphures;
                    $TambonID = $company->Tambon;
                    $Identification = $company->Taxpayer_Identification;
                    $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                    $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                    $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                    $phone = company_tax_phone::where('ComTax_ID',$companyid)->where('Sequence','main')->first();
                    $email = $company->Company_Email;
                }
            }else{

                $guestdata =  Guest::where('Profile_ID',$companyid)->first();

                if ($guestdata) {
                    $Selectdata =  'Guest';
                    $Company_typeID=$guestdata->preface;
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="นาย") {
                        $fullname = "นาย ". $guestdata->first_name . ' ' . $guestdata->last_name;
                    }elseif ($comtype->name_th =="นาง") {
                        $fullname = "นาง ". $guestdata->first_name . ' ' . $guestdata->last_name;
                    }elseif ($comtype->name_th =="นางสาว") {
                        $fullname = "นางสาว ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                    }else{
                        $fullname = "คุณ ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                    }
                    $Address=$guestdata->Address;
                    $CityID=$guestdata->City;
                    $amphuresID = $guestdata->Amphures;
                    $TambonID = $guestdata->Tambon;
                    $Identification = $guestdata->Identification_Number;
                    $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                    $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                    $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                    $phone = phone_guest::where('Profile_ID',$guestdata->Profile_ID)->where('Sequence','main')->first();
                    $email = $guestdata->Company_Email;
                }else{
                    $guestdata =  guest_tax::where('GuestTax_ID',$companyid)->first();
                    $Company_typeID=$guestdata->Company_type;
                    if ($Company_typeID == [30,31,32]) {
                        $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                        if ($comtype->name_th =="บริษัทจำกัด") {
                            $fullname = "บริษัท ". $guestdata->Company_name . " จำกัด";
                        }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                            $fullname = "บริษัท ". $guestdata->Company_name . " จำกัด (มหาชน)";
                        }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                            $fullname = "ห้างหุ้นส่วนจำกัด ". $guestdata->Company_name ;
                        }elseif ($Company_typeID > 32){
                            $fullname = $comtype->name_th . $guestdata->Company_name;
                        }
                    }else{
                        $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                        if ($comtype->name_th =="นาย") {
                            $fullname = "นาย ". $guestdata->first_name . ' ' . $guestdata->last_name;
                        }elseif ($comtype->name_th =="นาง") {
                            $fullname = "นาง ". $guestdata->first_name . ' ' . $guestdata->last_name;
                        }elseif ($comtype->name_th =="นางสาว") {
                            $fullname = "นางสาว ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                        }else{
                            $fullname = "คุณ ". $guestdata->first_name . ' ' . $guestdata->last_name ;
                        }
                    }
                    $Address=$guestdata->Address;
                    $CityID=$guestdata->City;
                    $amphuresID = $guestdata->Amphures;
                    $TambonID = $guestdata->Tambon;
                    $Identification = $guestdata->Identification_Number;
                    $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                    $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                    $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                    $phone = guest_tax_phone::where('GuestTax_ID',$companyid)->where('Sequence','main')->first();
                    $email = $guestdata->Company_Email;
                }
            }

            $Quotation = Quotation::where('Quotation_ID', $Proposal_ID)->first();
            $Checkin = $Quotation->checkin;
            $Checkout = $Quotation->checkout;
            $Day = $Quotation->day;
            $Night = $Quotation->night;
            $Adult = $Quotation->adult;
            $Children = $Quotation->children;

            $settingCompany = Master_company::orderBy('id', 'desc')->first();
            $id = $DepositID;

            $protocol = $request->secure() ? 'https' : 'http';
            $linkQR = $protocol . '://' . $request->getHost() . "/Deposit/Quotation/cover/document/PDF/$id";
            $qrCodeImage = QrCode::format('svg')->size(200)->generate($linkQR);
            $qrCodeBase64 = base64_encode($qrCodeImage);
            $userid = Auth::user()->id;
            $user = User::where('id',$userid)->first();
            $vattype= $Quotation->vat_type;
            $vat_type = master_document::where('id',$vattype)->first();
            $Nettotal = floatval(str_replace(',', '', $Payment));
            if ($Payment) {
                $Subtotal =0;
                $total =0;
                $addtax = 0;
                $before = 0;
                $balance =0;
                if ($vattype == 51) {
                    $Subtotal = $Payment;
                    $total = $Payment;
                    $addtax = 0;
                    $before = $Payment;
                    $balance = $Subtotal;
                }else{
                    $Subtotal = $Payment;
                    $total = $Subtotal/1.07;
                    $addtax = $Subtotal-$total;
                    $before = $Subtotal-$addtax;
                    $balance = $Subtotal;
                }
            }
            $data= [
                'settingCompany'=>$settingCompany,
                'DepositID'=>$DepositID,
                'IssueDate'=>$IssueDate,
                'Expiration'=>$Expiration,
                'qrCodeBase64'=>$qrCodeBase64,
                'user'=>$user,
                'fullname'=>$fullname,
                'Address'=>$Address,
                'Identification'=>$Identification,
                'TambonID'=>$TambonID,
                'amphuresID'=>$amphuresID,
                'provinceNames'=>$provinceNames,
                'phone'=>$phone,
                'email'=>$email,
                'Deposit'=>$Deposit,
                'Payment'=>$Payment,
                'Checkin'=>$Checkin,
                'Checkout'=>$Checkout,
                'Day'=>$Day,
                'Night'=>$Night,
                'Adult'=>$Adult,
                'Subtotal'=>$Subtotal,
                'total'=>$total,
                'addtax'=>$addtax,
                'before'=>$before,
                'balance'=>$balance,
                'Children'=>$Children,
                'Quotation'=>$Quotation,
            ];
            $template = master_template::query()->latest()->first();
            $view= $template->name;
            $pdf = FacadePdf::loadView('deposit_revenue.pdf.' . $view, $data);
            $path = 'PDF/Deposit_Revenue/';
            $pdf->save($path . $DepositID .'-'. $correctup . '.pdf');
            $currentDateTime = Carbon::now();
            $currentDate = $currentDateTime->toDateString(); // Format: YYYY-MM-DD
            $currentTime = $currentDateTime->toTimeString(); // Format: HH:MM:SS
            $formattedDate = $currentDateTime->format('Y-m-d'); // Custom format for date
            $formattedTime = $currentDateTime->format('H:i:s');
            $savePDF = new log();
            $savePDF->Quotation_ID = $DepositID;
            $savePDF->Company_Name = $fullnamemain;
            $savePDF->QuotationType = 'Deposit Revenue';
            $savePDF->Approve_date = $formattedDate;
            $savePDF->Approve_time = $formattedTime;
            $savePDF->correct = $correctup;
            $savePDF->save();

            $save = depositrevenue::find($deposit_id);
            $save->Company_ID = $companyid;
            $save->amount = $Payment;
            $save->fullname = $fullnamemain;
            $save->Issue_date = $IssueDate;
            $save->ExpirationDate = $Expiration;
            $save->correct = $correctup;
            $save->save();
            $deposit = depositrevenue::where('Deposit_ID',$DepositID)->first();
            $ids = $deposit->id;
            return redirect()->route('BillingFolio.CheckPI', ['id' => $Quotation->id])->with('success', 'Data has been successfully saved.');
        } catch (\Throwable $e) {
            return redirect()->route('BillingFolio.index')->with('error', $e->getMessage());
        }
    }

    public function additional_edit($id){
        $settingCompany = Master_company::orderBy('id', 'desc')->first();
        $Quotation = proposal_overbill::where('id', $id)->first();
        $Proposal = Quotation::where('Quotation_ID',$Quotation->Quotation_ID)->first();
        $Additional_ID = $Quotation->Additional_ID;
        $Quotation_ID = $Quotation->Quotation_ID;
        $Quotation_IDoverbill = $Quotation->Additional_ID;
        $Company = companys::select('Company_Name','id','Profile_ID')->get();
        $Guest = Guest::select('First_name','Last_name','id','Profile_ID')->get();
        $Mevent = master_document::select('name_th','id')->where('status', '1')->where('Category','Mevent')->get();
        $Mvat = master_document::select('name_th','id')->where('status', '1')->where('Category','Mvat')->get();
        $Freelancer_member = Freelancer_Member::select('First_name','id','Profile_ID','Last_name')->where('status', '1')->get();
        $selectproduct = document_proposal_overbill::where('Additional_ID', $Additional_ID)->get();
        $unit = master_unit::where('status',1)->get();
        $quantity = master_quantity::where('status',1)->get();
        $Selectdata =  $Quotation->type_Proposal;
        if ($Selectdata == 'Guest') {
            $Data = Guest::where('Profile_ID',$Quotation->Company_ID)->first();
            $prename = $Data->preface;
            $First_name = $Data->First_name;
            $Last_name = $Data->Last_name;
            $Address = $Data->Address;
            $Email = $Data->Email;
            $Taxpayer_Identification = $Data->Identification_Number;
            $prefix = master_document::where('id',$prename)->where('Category','Mprename')->where('status',1)->first();
            $name = $prefix->name_th;
            $fullName = $name.' '.$First_name.' '.$Last_name;
            //-------------ที่อยู่
            $CityID=$Data->City;
            $amphuresID = $Data->Amphures;
            $TambonID = $Data->Tambon;
            $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
            $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
            $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
            $Fax_number = '-';
            $Contact_Name = ' ';
            $Contact_phone = ' ';
            $phone = phone_guest::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
        }else{
            $Company = companys::where('Profile_ID',$Quotation->Company_ID)->first();
            $Company_type = $Company->Company_type;
            $Compannyname = $Company->Company_Name;
            $Address = $Company->Address;
            $Email = $Company->Company_Email;
            $Taxpayer_Identification = $Company->Taxpayer_Identification;
            $comtype = master_document::where('id', $Company_type)->where('Category', 'Mcompany_type')->first();
            if ($comtype) {
                if ($comtype->name_th == "บริษัทจำกัด") {
                    $fullName = "บริษัท " . $Compannyname . " จำกัด";
                } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                    $fullName = "บริษัท " . $Compannyname . " จำกัด (มหาชน)";
                } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                    $fullName = "ห้างหุ้นส่วนจำกัด " . $Compannyname;
                }else{
                    $fullName = $comtype->name_th . $Compannyname;
                }
            }
            $representative = representative::where('Company_ID',$Quotation->Company_ID)->first();
            $prename = $representative->prefix;
            $Contact_Email = $representative->Email;
            $prefix = master_document::where('id', $prename)->where('Category', 'Mprename')->first();
            $name = $prefix->name_th;
            $Contact_Name = 'คุณ '.$representative->First_name.' '.$representative->Last_name;
            $CityID=$Company->City;
            $amphuresID = $Company->Amphures;
            $TambonID = $Company->Tambon;
            $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
            $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
            $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
            $company_fax = company_fax::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
            if ($company_fax) {
                $Fax_number =  $company_fax->Fax_number;
            }else{
                $Fax_number = '-';
            }
            $phone = company_phone::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
            $Contact_phone = representative_phone::where('Company_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
        }
        return view('billingfolio.additional.edit',compact('Address','fullName','settingCompany','Quotation','Quotation_ID','Company','Guest','Mevent','Mvat','Freelancer_member','selectproduct','unit','quantity','Quotation_IDoverbill',
                    'provinceNames','amphuresID','TambonID','Fax_number','phone','Email','Taxpayer_Identification','Contact_Name','Contact_phone','Selectdata','Proposal' ));
    }
    public function additional_update(Request $request ,$id){
        $data = $request->all();
        $Quotation = proposal_overbill::where('id', $id)->first();
        $QuotationID = $Quotation->Quotation_ID;
        $preview = $request->preview;
        $Quotation_ID=$request->Quotation_ID;
        $userid = Auth::user()->id;
        $data = $request->all();
        $Additional_ID=$request->Additional_ID;
        $Mvat = $Quotation->vat_type;
        $additional_type =  $Quotation->additional_type;
        $datarequest = [
            'Proposal_ID' => $data['Quotation_ID'] ?? null,
            'Code' => $data['Code'] ?? null ,
            'CheckProduct' => $data['CheckProduct'] ?? null ,
            'Amount' => $data['Amount'] ?? [],
            'IssueDate' => $Quotation['issue_date'] ?? null,
            'Expiration' => $Quotation['Expirationdate'] ?? null,
            'Selectdata' => $Quotation['type_Proposal'] ?? null,
            'Data_ID' => $Quotation['Company_ID'] ?? null,
            'Adult' => $Quotation['adult'] ?? null,
            'Children' => $Quotation['children'] ?? null,
            'Mevent' => $Quotation['eventformat'] ?? null,
            'Mvat' => $Quotation['vat_type'] ?? null,
            'comment' => $data['comment'] ?? null,
            'PaxToTalall' => $Quotation['TotalPax'] ?? null,
            'Checkin' => $Quotation['checkin'] ?? null,
            'Checkout' => $Quotation['checkout'] ?? null,
            'Day' => $Quotation['day'] ?? null,
            'Night' => $Quotation['night'] ?? null,
        ];
        {   //จัด product
            $Products = $datarequest['Code'];
            $Productslast = $datarequest['CheckProduct'];
            if (is_array($Products) && is_array($Productslast)) {
                $commonValues = array_intersect($Products, $Productslast);
                if (!empty($commonValues)) {
                    $diffFromProducts = array_diff($Products, $Productslast);
                    $diffFromProductslast = array_diff($Productslast, $Products);
                    $Code = array_merge($commonValues,$diffFromProducts,$diffFromProductslast);
                } else {
                    $Code = array_merge($Productslast,$Products);
                }

            }else{
                $Code = $Productslast;
            }
            $Amount = $datarequest['Amount'];
            $productItems = [];
            $productItemsData = [];

            if (count($Code) === count($Amount)) {
                foreach ($Code as $index => $productID) {
                    // Retrieve the product details based on Code
                    $items = Master_additional::where('code', $productID)->get();
                    foreach ($items as $item) {
                        // Use corresponding Amount for each productID based on index
                        $quantity = isset($Amount[$index]) ? intval($Amount[$index]) : 0;
                        $productItems[] = [
                            'product' => $item,
                            'Amount' => $quantity,
                        ];
                        $productItemsData[] = [
                            'product' => $item,
                            'Amount' => $quantity,
                        ];
                    }
                }
            }

            $productDataSave = [];
            if (!empty($productItems)) {
                foreach ($productItems as $product) {
                    $productDataSave[] = [
                        'Code' => $product['product']->code,
                        'Detail' => $product['product']->description,
                        'Amount' => $product['Amount'],
                    ];
                }
                $productData['Product'] = $productDataSave; // Assign the whole array once after the loop
            }

            {//คำนวน
                $totalAmount = 0;
                $totalPrice = 0;
                $subtotal = 0;
                $beforeTax = 0;
                $AddTax = 0;
                $Nettotal =0;
                $totalaverage=0;


                $totalguest = 0;
                $totalguest = $datarequest['Adult'] + $datarequest['Children'];
                $guest =  $datarequest['Adult'] + $datarequest['Children'];

                if ($Mvat == 50) {
                    foreach ($productItems as $item) {
                        $totalPrice += $item['Amount'];
                        $subtotal = $totalPrice;
                        $beforeTax = $subtotal/1.07;
                        $AddTax = $subtotal-$beforeTax;
                        $Nettotal = $subtotal;
                        $totalaverage =$Nettotal/$totalguest;
                        $totalAmount = $totalPrice;

                    }
                }
                elseif ($Mvat == 51) {
                    foreach ($productItems as $item) {
                        $totalPrice += $item['Amount'];
                        $subtotal = $totalPrice;
                        $Nettotal = $subtotal;
                        $totalaverage =$Nettotal/$totalguest;

                    }
                }
                elseif ($Mvat == 52) {
                    foreach ($productItems as $item) {
                        $totalPrice += $item['Amount'];
                        $subtotal = $totalPrice;
                        $AddTax = $subtotal*7/100;
                        $Nettotal = $subtotal+$AddTax;
                        $totalaverage =$Nettotal/$totalguest;
                    }
                }else
                {
                    foreach ($productItems as $item) {
                        $totalPrice += $item['Amount'];
                        $subtotal = $totalAmount-$SpecialDis;
                        $beforeTax = $subtotal/1.07;
                        $AddTax = $subtotal-$beforeTax;
                        $Nettotal = $subtotal;
                        $totalaverage =$Nettotal/$guest;
                    }
                }

            }
        }
        try {
            $ProposalData = proposal_overbill::where('id', $id)->first();
            $ProposalID = $ProposalData->Additional_ID;

            // Retrieve and filter proposal products
            $ProposalProducts = document_proposal_overbill::where('Additional_ID', $ProposalID)->get();
            $dataArray['Product'] = $ProposalProducts->map(function ($item) {
                // Remove unnecessary fields from each item
                return Arr::only($item->toArray(), ['Code', 'Detail', 'Amount']);
            })->toArray();

            $productData['Product'] = $productData['Product'] ?? [];
            $keysToCompare = ['Product'];
            $differences = [];

            foreach ($keysToCompare as $key) {
                if (is_array($dataArray[$key]) && is_array($productData[$key])) {
                    foreach ($dataArray[$key] as $index => $value) {
                        if (isset($productData[$key][$index])) {
                            if ($value != $productData[$key][$index]) {
                                $differences[$key][$index] = [
                                    'dataArray' => $value,
                                    'request' => $productData[$key][$index]
                                ];
                            }
                        } else {
                            $differences[$key][$index] = [
                                'dataArray' => $value,
                                'request' => null
                            ];
                        }
                    }
                    // Handle case where $productData has extra elements
                    foreach ($productData[$key] as $index => $value) {
                        if (!isset($dataArray[$key][$index])) {
                            $differences[$key][$index] = [
                                'dataArray' => null,
                                'request' => $value
                            ];
                        }
                    }
                } elseif (isset($dataArray[$key])) {
                    $differences[$key] = [
                        'dataArray' => $dataArray[$key],
                        'request' => null
                    ];
                } elseif (isset($productData[$key])) {
                    $differences[$key] = [
                        'dataArray' => null,
                        'request' => $productData[$key]
                    ];
                }
            }

            // แยกข้อมูลที่ไม่ซ้ำกันในแต่ละ array
            $onlyInDataArray = [];
            $onlyInRequest = [];

            if (isset($differences['Product'])) {
                $onlyInDataArray = array_filter($dataArray['Product'], function ($item) use ($productData) {
                    return !in_array($item, $productData['Product']);
                });

                $onlyInRequest = array_filter($productData['Product'], function ($item) use ($dataArray) {
                    return !in_array($item, $dataArray['Product']);
                });
            }
            $extractedData = [];
            $extractedDataA = [];
            foreach ($differences as $key => $value) {
                if ($key === 'Product') {
                    // ถ้าเป็น Products ให้เก็บค่า request และ dataArray ที่แตกต่างกัน
                    $extractedData[$key] = $onlyInDataArray; // ใช้ข้อมูลจาก $onlyInDataArray (ลบ)
                    $extractedDataA[$key] = $onlyInRequest;  // ใช้ข้อมูลจาก $onlyInRequest (เพิ่ม)
                }
            }
            $Products =  $extractedData['Product'] ?? null;
            $ProductsA =  $extractedDataA['Product'] ?? null;
            $formattedProductData = [];
            $formattedProductDataA = [];
            if ($Products) {
                $productDelete = [];
                foreach ($Products as $product) {
                    $productDelete[] = [
                        'Code' => $product['Code'],
                        'Detail' => $product['Detail'],
                        'Amount' => $product['Amount'],
                    ];
                }
                // จัดรูปแบบข้อมูลของผลิตภัณฑ์
                foreach ($productDelete as $product) {
                    $formattedProductData[] = 'ลบรายการ' . ' ' . 'Code : ' . $product['Code'] . ' , ' . 'Detail : ' . $product['Detail'] . ' , ' . 'Amount : ' . $product['Amount'];
                }
            }
            if ($ProductsA) {
                $productIncrease  = [];
                foreach ($ProductsA as $product) {
                    $productIncrease[] = [
                        'Code' => $product['Code'],
                        'Detail' => $product['Detail'],
                        'Amount' => $product['Amount'],
                    ];

                }

                // จัดรูปแบบข้อมูลของผลิตภัณฑ์
                foreach ($productIncrease as $product) {
                    $formattedProductDataA[] = 'เพิ่มรายการ' . ' ' . 'Code : ' . $product['Code'] . ' , ' . 'Detail : ' . $product['Detail'] . ' , ' . 'Amount : ' . $product['Amount'];
                }
            }
            $Additional_type = null;
            $Cash = null;
            $Complimentary = null;
            $Additional_type = 'Additional Type : '.$request->additional_type;
            if ($request->Cash) {
                if ( $Quotation->Cash != $request->Cash) {
                    $Cash = 'Cash : '.$request->Cash;
                }
            }
            if ($request->Complimentary) {
                if ( $Quotation->Complimentary != $request->Complimentary) {
                    $Complimentary = 'Complimentary : '.$request->Complimentary;
                }
            }
            $Additional = 'Additional ID : '.$Additional_ID;
            $com = 'รายการ';
            $datacompany = '';

            $variables = [$Additional,$com,$Additional_type,$Cash,$Complimentary];
            // แปลง array ของ $formattedProductData เป็น string เดียวที่มีรายการทั้งหมด
            $formattedProductDataString = implode(' + ', $formattedProductData);
            $formattedProductDataStringA = implode(' + ', $formattedProductDataA);

            // รวม $formattedProductDataString เข้าไปใน $variables
            $variables[] = $formattedProductDataString;
            $variables[] = $formattedProductDataStringA;
            foreach ($variables as $variable) {
                if (!empty($variable)) {
                    if (!empty($datacompany)) {
                        $datacompany .= ' + ';
                    }
                    $datacompany .= $variable;
                }
            }
            $userids = Auth::user()->id;
            $save = new log_company();
            $save->Created_by = $userids;
            $save->Company_ID = $Additional_ID;
            $save->type = 'Edit';
            $save->Category = 'Edit :: Additional';
            $save->content =$datacompany;
            $save->save();
        } catch (\Throwable $e) {
            return redirect()->route('BillingFolio.additional_edit', ['id' => $Quotation->id])->with('error',$e->getMessage());
        }
        try {
            $userid = Auth::user()->id;
            $Quotationcheck = proposal_overbill::where('id',$id)->first();
            $correct = $Quotationcheck->correct;
            if ($correct >= 1) {
                $correctup = $correct + 1;
            }else{
                $correctup = 1;
            }
            $datarequest = [
                'Proposal_ID' => $data['Quotation_ID'] ?? null,
                'Code' => $data['Code'] ?? null ,
                'CheckProduct' => $data['CheckProduct'] ?? null ,
                'Amount' => $data['Amount'] ?? [],
                'IssueDate' => $Quotation['issue_date'] ?? null,
                'Expiration' => $Quotation['Expirationdate'] ?? null,
                'Selectdata' => $Quotation['type_Proposal'] ?? null,
                'Data_ID' => $Quotation['Company_ID'] ?? null,
                'Adult' => $Quotation['adult'] ?? null,
                'Children' => $Quotation['children'] ?? null,
                'Mevent' => $Quotation['eventformat'] ?? null,
                'Mvat' => $Quotation['vat_type'] ?? null,
                'comment' => $data['comment'] ?? null,
                'PaxToTalall' => $Quotation['TotalPax'] ?? null,
                'Checkin' => $Quotation['checkin'] ?? null,
                'Checkout' => $Quotation['checkout'] ?? null,
                'Day' => $Quotation['day'] ?? null,
                'Night' => $Quotation['night'] ?? null,
            ];
            $Products = $datarequest['Code'];
            $Productslast = $datarequest['CheckProduct'];
            if (is_array($Products) && is_array($Productslast)) {
                $commonValues = array_intersect($Products, $Productslast);
                if (!empty($commonValues)) {
                    $diffFromProducts = array_diff($Products, $Productslast);
                    $diffFromProductslast = array_diff($Productslast, $Products);
                    $Code = array_merge($commonValues,$diffFromProducts,$diffFromProductslast);
                } else {
                    $Code = array_merge($Productslast,$Products);
                }

            }else{
                $Code = $Productslast;
            }
            $Amount = $datarequest['Amount'];
            $productItems = [];

            if (count($Code) === count($Amount)) {
                foreach ($Code as $index => $productID) {
                    // Retrieve the product details based on Code
                    $items = Master_additional::where('code', $productID)->get();

                    foreach ($items as $item) {
                        // Use corresponding Amount for each productID based on index
                        $quantity = isset($Amount[$index]) ? intval($Amount[$index]) : 0;
                        $productItems[] = [
                            'product' => $item,
                            'Amount' => $quantity,
                        ];
                    }
                }
            }
            {//คำนวน
                $totalAmount = 0;
                $totalPrice = 0;
                $subtotal = 0;
                $beforeTax = 0;
                $AddTax = 0;
                $Nettotal =0;
                $totalaverage=0;


                $totalguest = 0;
                $totalguest = $datarequest['Adult'] + $datarequest['Children'];
                $guest =  $datarequest['Adult'] + $datarequest['Children'];

                foreach ($productItems as $item) {
                    $totalPrice += $item['Amount'];
                    $subtotal = $totalPrice;
                    $beforeTax = $subtotal/1.07;
                    $AddTax = $subtotal-$beforeTax;
                    $Nettotal = $subtotal;
                    $totalaverage =$Nettotal/$totalguest;
                    $totalAmount = $totalPrice;
                }

                $pagecount = count($productItems);
                $page = $pagecount/10;

                $page_item = 1;
                if ($page > 1.1 && $page < 2.1) {
                    $page_item += 1;

                } elseif ($page > 1.1) {
                $page_item = 1 + $page > 1.1 ? ceil($page) : 1;
                }
            }
            {//QRCODE
                $id = $datarequest['Proposal_ID'];
                $protocol = $request->secure() ? 'https' : 'http';
                $linkQR = $protocol . '://' . $request->getHost() . "/Document/Additional/Charge/document/PDF/$id?page_shop=" . $request->input('page_shop');
                $qrCodeImage = QrCode::format('svg')->size(200)->generate($linkQR);
                $qrCodeBase64 = base64_encode($qrCodeImage);
            }
            $Proposal_ID = $datarequest['Proposal_ID'];
            $IssueDate = $datarequest['IssueDate'];
            $Expiration = $datarequest['Expiration'];
            $Selectdata = $datarequest['Selectdata'];
            $Data_ID = $datarequest['Data_ID'];
            $Adult = $datarequest['Adult'];
            $Children = $datarequest['Children'];
            $Mevent = $datarequest['Mevent'];
            $Mvat = $datarequest['Mvat'];
            $Checkin = $datarequest['Checkin'];
            $Checkout = $datarequest['Checkout'];
            $Day = $datarequest['Day'];
            $Night = $datarequest['Night'];
            $comment = $datarequest['comment'];
                $user = User::where('id',$userid)->first();
            $fullName = null;
            $Contact_Name = null;
            $Contact_phone =null;
            $Contact_Email = null;
            if ($Selectdata == 'Guest') {
                $Data = Guest::where('Profile_ID',$Quotation->Company_ID)->first();
                $prename = $Data->preface;
                $First_name = $Data->First_name;
                $Last_name = $Data->Last_name;
                $Address = $Data->Address;
                $Email = $Data->Email;
                $Taxpayer_Identification = $Data->Identification_Number;
                $prefix = master_document::where('id',$prename)->where('Category','Mprename')->where('status',1)->first();
                $name = $prefix->name_th;
                $fullName = $name.' '.$First_name.' '.$Last_name;
                //-------------ที่อยู่
                $CityID=$Data->City;
                $amphuresID = $Data->Amphures;
                $TambonID = $Data->Tambon;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $Fax_number = '-';
                $phone = phone_guest::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
            }else{
                $Company = companys::where('Profile_ID',$Quotation->Company_ID)->first();
                $Company_type = $Company->Company_type;
                $Compannyname = $Company->Company_Name;
                $Address = $Company->Address;
                $Email = $Company->Company_Email;
                $Taxpayer_Identification = $Company->Taxpayer_Identification;
                $comtype = master_document::where('id', $Company_type)->where('Category', 'Mcompany_type')->first();
                if ($comtype) {
                    if ($comtype->name_th == "บริษัทจำกัด") {
                        $fullName = "บริษัท " . $Compannyname . " จำกัด";
                    } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                        $fullName = "บริษัท " . $Compannyname . " จำกัด (มหาชน)";
                    } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                        $fullName = "ห้างหุ้นส่วนจำกัด " . $Compannyname;
                    }else{
                        $fullName = $comtype->name_th . $Compannyname;
                    }
                }
                $representative = representative::where('Company_ID',$Quotation->Company_ID)->first();
                $prename = $representative->prefix;
                $Contact_Email = $representative->Email;
                $prefix = master_document::where('id', $prename)->where('Category', 'Mprename')->first();
                $name = $prefix->name_th;
                $Contact_Name = $representative->First_name.' '.$representative->Last_name;
                $CityID=$Company->City;
                $amphuresID = $Company->Amphures;
                $TambonID = $Company->Tambon;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $company_fax = company_fax::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
                if ($company_fax) {
                    $Fax_number =  $company_fax->Fax_number;
                }else{
                    $Fax_number = '-';
                }
                $phone = company_phone::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
                $Contact_phone = representative_phone::where('Company_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
            }
            $eventformat = master_document::where('id',$Mevent)->select('name_th','id')->first();
            $template = master_template::query()->latest()->first();
            $CodeTemplate = $template->CodeTemplate;
            $sheet = master_document_sheet::select('topic','name_th','id','CodeTemplate')->get();
            $Reservation_show = $sheet->where('topic', 'Reservation')->where('CodeTemplate',$CodeTemplate)->first();
            $Paymentterms = $sheet->where('topic', 'Paymentterms')->where('CodeTemplate',$CodeTemplate)->first();
            $note = $sheet->where('topic', 'note')->where('CodeTemplate',$CodeTemplate)->first();
            $Cancellations = $sheet->where('topic', 'Cancellations')->where('CodeTemplate',$CodeTemplate)->first();
            $Complimentary = $sheet->where('topic', 'Complimentary')->where('CodeTemplate',$CodeTemplate)->first();
            $All_rights_reserved = $sheet->where('topic', 'All_rights_reserved')->where('CodeTemplate',$CodeTemplate)->first();
            $date = Carbon::now();
            $unit = master_unit::where('status',1)->get();
            $quantity = master_quantity::where('status',1)->get();
            $settingCompany = Master_company::orderBy('id', 'desc')->first();
            if ($Checkin) {
                $checkin = $Checkin;
                $checkout = $Checkout;
            }else{
                $checkin = '-';
                $checkout = '-';
            }
            $data = [
                'settingCompany'=>$settingCompany,
                'page_item'=>$page_item,
                'page'=>$pagecount,
                'Selectdata'=>$Selectdata,
                'date'=>$date,
                'fullName'=>$fullName,
                'provinceNames'=>$provinceNames,
                'Address'=>$Address,
                'amphuresID'=>$amphuresID,
                'TambonID'=>$TambonID,
                'Email'=>$Email,
                'phone'=>$phone,
                'Fax_number'=>$Fax_number,
                'Additional_ID'=>$Additional_ID,
                'Day'=>$Day,
                'Night'=>$Night,
                'Checkin'=>$checkin,
                'Checkout'=>$checkout,
                'eventformat'=>$eventformat,
                'totalguest'=>$totalguest,
                'Reservation_show'=>$Reservation_show,
                'Paymentterms'=>$Paymentterms,
                'note'=>$note,
                'Cancellations'=>$Cancellations,
                'Complimentary'=>$Complimentary,
                'All_rights_reserved'=>$All_rights_reserved,
                'Proposal_ID'=>$Proposal_ID,
                'IssueDate'=>$IssueDate,
                'Expiration'=>$Expiration,
                'qrCodeBase64'=>$qrCodeBase64,
                'user'=>$user,
                'Taxpayer_Identification'=>$Taxpayer_Identification,
                'Adult'=>$Adult,
                'Children'=>$Children,
                'totalAmount'=>$totalAmount,
                'subtotal'=>$subtotal,
                'beforeTax'=>$beforeTax,
                'Nettotal'=>$Nettotal,
                'totalguest'=>$totalguest,
                'guest'=>$guest,
                'totalaverage'=>$totalaverage,
                'AddTax'=>$AddTax,
                'productItems'=>$productItems,
                'unit'=>$unit,
                'quantity'=>$quantity,
                'Mvat'=>$Mvat,
                'comment'=>$comment,
                'Mevent'=>$Mevent,
                'Contact_Name'=>$Contact_Name,
                'Contact_phone'=>$Contact_phone,
                'Contact_Email'=>$Contact_Email,
            ];
            $view= $template->name;
            $pdf = FacadePdf::loadView('additional_charge.additional_charge_pdf.'.$view,$data);
            $path = 'PDF/additional/';
            $pdf->save($path . $Additional_ID.'-'.$correctup . '.pdf');
        } catch (\Throwable $e) {
            return redirect()->route('BillingFolio.additional_edit', ['id' => $Quotation->id])->with('error',$e->getMessage());
        }
        try {
            $currentDateTime = Carbon::now();
            $currentDate = $currentDateTime->toDateString(); // Format: YYYY-MM-DD
            $currentTime = $currentDateTime->toTimeString(); // Format: HH:MM:SS
            // Optionally, you can format the date and time as per your requirement
            $formattedDate = $currentDateTime->format('Y-m-d'); // Custom format for date
            $formattedTime = $currentDateTime->format('H:i:s');
            {
                $Proposal_ID = $datarequest['Proposal_ID'];
                $IssueDate = $datarequest['IssueDate'];
                $Expiration = $datarequest['Expiration'];
                $Selectdata = $Quotation->type_Proposal;
                $Data_ID = $Quotation->Company_ID;
                $Adult = $datarequest['Adult'];
                $Children = $datarequest['Children'];
                $Mevent = $datarequest['Mevent'];
                $Mvat = $datarequest['Mvat'];
                $Checkin = $datarequest['Checkin'];
                $Checkout = $datarequest['Checkout'];
                $Day = $datarequest['Day'];
                $Night = $datarequest['Night'];
                $comment = $datarequest['comment'];
                if ($Selectdata == 'Guest') {
                    $Data = Guest::where('Profile_ID',$Data_ID)->first();
                    $prename = $Data->preface;
                    $First_name = $Data->First_name;
                    $Last_name = $Data->Last_name;
                    $prefix = master_document::where('id',$prename)->where('Category','Mprename')->where('status',1)->first();
                    $name = $prefix->name_th;
                    $fullName = $name.' '.$First_name.' '.$Last_name;
                    //-------------ที่อยู่

                }else{
                    $Company = companys::where('Profile_ID',$Data_ID)->first();
                    $Company_type = $Company->Company_type;
                    $Compannyname = $Company->Company_Name;
                    $comtype = master_document::where('id', $Company_type)->where('Category', 'Mcompany_type')->first();
                    if ($comtype) {
                        if ($comtype->name_th == "บริษัทจำกัด") {
                            $fullName = "บริษัท " . $Compannyname . " จำกัด";
                        } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                            $fullName = "บริษัท " . $Compannyname . " จำกัด (มหาชน)";
                        } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                            $fullName = "ห้างหุ้นส่วนจำกัด " . $Compannyname;
                        }
                    }

                }
            }
            $savePDF = new log();
            $savePDF->Quotation_ID = $Additional_ID;
            $savePDF->QuotationType = 'Additional';
            $savePDF->Company_Name = $fullName;
            $savePDF->Approve_date = $formattedDate;
            $savePDF->Approve_time = $formattedTime;
            $savePDF->correct = $correctup;
            $savePDF->save();
        } catch (\Throwable $e) {
            log_company::where('Category', 'Edit :: Additional')
            ->orderBy('created_at', 'desc')
            ->limit(1) // ลบข้อมูลล่าสุด 1 แถว
            ->delete();
            $path = 'PDF/additional/';
            $file = $path . $Additional_ID .'-'.$correctup.'.pdf';
            if (is_file($file)) {
                unlink($file); // ลบไฟล์
            }
            log::where('Quotation_ID',$Additional_ID)->orderBy('created_at', 'desc')->limit(1)->delete();
            return redirect()->route('BillingFolio.additional_edit', ['id' => $Quotation->id])->with('error',$e->getMessage());
        }

        try {
            $totalPrice = 0; // กำหนดตัวแปรเริ่มต้น
            foreach ($productItemsData as $item) {
                $totalPrice += $item['Amount']; // รวมยอดราคา
            }

            $subtotal = $totalPrice;
            $beforeTax = $subtotal / 1.07; // คำนวณก่อนหักภาษี
            $AddTaxD = $subtotal - $beforeTax; // คำนวณภาษี
            $NettotalD = $subtotal; // ยอดรวมสุทธิ
            $totalaverage = $Nettotal / $totalguest; // คำนวณค่าเฉลี่ย
            $totalAmount = $totalPrice; // ยอดรวมทั้งหมด
            $save = proposal_overbill::find($Quotation->id); // ค้นหา proposal ที่ต้องการบันทึก
            $save->AddTax = $AddTaxD; // บันทึกภาษี
            $save->Nettotal = $NettotalD; // บันทึกยอดรวมสุทธิ
            $save->total = $NettotalD; // บันทึกยอดรวม
            $save->correct = $correctup;
            $save->additional_type = $request->additional_type;
            $save->Cash = $request->Cash;
            $save->Complimentary = $request->Complimentary;
            $save->status_document = 2;
            $save->save(); // บันทึกข้อมูล
        } catch (\Throwable $e) {
            log_company::where('Category', 'Edit :: Additional')
            ->orderBy('created_at', 'desc')
            ->limit(1) // ลบข้อมูลล่าสุด 1 แถว
            ->delete();
            $path = 'PDF/additional/';
            $file = $path . $Additional_ID .'-'.$correctup.'.pdf';
            if (is_file($file)) {
                unlink($file); // ลบไฟล์
            }
            log::where('Quotation_ID',$Additional_ID)->orderBy('created_at', 'desc')->limit(1)->delete();
            return redirect()->route('BillingFolio.additional_edit', ['id' => $Quotation->id])->with('error',$e->getMessage());
        }
        try {
            if ($productDataSave) {
                $productold = document_proposal_overbill::where('Additional_ID', $Additional_ID)->delete();
                foreach ($productDataSave as $product) {
                    $saveProduct = new document_proposal_overbill();
                    $saveProduct->Additional_ID = $Additional_ID;
                    $saveProduct->Quotation_ID = $Quotation_ID;
                    $saveProduct->Code = $product['Code'];
                    $saveProduct->Detail = $product['Detail'];
                    $saveProduct->Amount = $product['Amount'];
                    $saveProduct->save();
                }
            }
        } catch (\Throwable $e) {
            log_company::where('Category', 'Edit :: Additional')
            ->orderBy('created_at', 'desc')
            ->limit(1) // ลบข้อมูลล่าสุด 1 แถว
            ->delete();
            $path = 'PDF/additional/';
            $file = $path . $Additional_ID .'-'.$correctup.'.pdf';
            if (is_file($file)) {
                unlink($file); // ลบไฟล์
            }
            log::where('Quotation_ID',$Additional_ID)->orderBy('created_at', 'desc')->limit(1)->delete();
            return redirect()->route('BillingFolio.additional_edit', ['id' => $Quotation->id])->with('error',$e->getMessage());
        }
        try {
            $userid = Auth::user()->id;
            $log = new log_company();
            $log->Created_by = $userid;
            $log->Company_ID = $Additional_ID;
            $log->type = 'Send documents';
            $log->Category = 'Send documents :: Additional';
            $log->content = 'Send Document Additional : ' . $Additional_ID;
            $log->save();
        } catch (\Throwable $th) {
            return redirect()->route('BillingFolio.additional_edit', ['id' => $Quotation->id])->with('error',$e->getMessage());
        }
        $Quotationid = Quotation::where('Quotation_ID', $QuotationID)->first();
        return redirect()->route('BillingFolio.CheckPI', ['id' => $Quotationid->id])->with('success', 'Data has been successfully saved.');
    }


}
