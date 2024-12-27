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
class BillingFolioController extends Controller
{
    public function index()
    {
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $userid = Auth::user()->id;
        $Approved = receive_payment::query()->where('type','billing')->WhereIn('document_status',[1,2])->get();
        $ApprovedCount = receive_payment::query()->where('type','billing')->WhereIn('document_status',[1,2])->count();
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
        $Rejectcount = receive_payment::query()->where('type','billing')->where('document_status',4)->count();
        $Reject = receive_payment::query()->where('type','billing')->where('document_status',4)->get();
        return view('billingfolio.index',compact('Approved','Complate','ComplateCount','ApprovedCount','Reject','Rejectcount'));
    }
    //---------------------------------table-----------------


    public function issuebill()
    {
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $userid = Auth::user()->id;
        $Approved = Quotation::query()
        ->leftJoin('document_receive', 'quotation.Quotation_ID', '=', 'document_receive.Quotation_ID')
        ->leftJoin('proposal_overbill', 'quotation.Quotation_ID', '=', 'proposal_overbill.Quotation_ID')
        ->where('quotation.status_guest', 1)
        ->where('quotation.status_receive', 1)
        ->select(
            'quotation.*',
            'proposal_overbill.Nettotal as Adtotal',
            DB::raw('SUM(document_receive.document_amount) as receive_amount'),
        )
        ->groupBy('quotation.Quotation_ID', 'quotation.status_guest', 'quotation.status_receive')
        ->get();
        return view('billingfolio.proposal',compact('Approved'));
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
            $AddTax =$subtotal*7/100;
            $Nettotal = $subtotal+$AddTax;
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
        $invoices = document_invoices::where('Quotation_ID', $Proposal_ID)->where('Paid',0)->get();
        $totalinvoices = 0;
        foreach ($invoices as $item) {
            $totalinvoices +=  $item->sumpayment;
        }
        if ($invoices->contains('Paid', 0)) {
            // ถ้า status มีค่าเป็น 0 อย่างน้อยหนึ่งรายการ
            $status = 0;
        } else {
            $status = 1;
        }
        $Receipt = receive_payment::where('Quotation_ID', $Proposal_ID)->get();
        $totalReceipt = 0;
        $totalReceiptCom = 0;
        foreach ($Receipt as $item) {
            $totalReceipt +=  $item->Amount;
            $totalReceiptCom +=  $item->complimentary;
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
        $Additional = proposal_overbill::where('Quotation_ID',$Proposal_ID)->where('status_document',3)->first();
        $additional_type= "";
        $Additionaltotal= 0;
        $Cash= 0;
        $Com= 0;
        $AdditionaltotalReceipt = 0;
        $statusover = 1;
        $Receiptover = null;
        $Additional_ID = null;
        if ($Additional) {
            $additional_type = $Additional->additional_type;
            $Additionaltotal = $Additional->Nettotal;
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

            $Receiptover = receive_payment::where('Quotation_ID', $Additional_ID)->get();

            foreach ($Receiptover as $item) {
                $AdditionaltotalReceipt +=  $item->Amount;
            }
            if ($Receiptover->contains('Paid', 0)) {
                // ถ้า status มีค่าเป็น 0 อย่างน้อยหนึ่งรายการ
                $statusover = 0;
            } else {
                $statusover = 1;
            }
        }




        return view('billingfolio.check_pi',compact('Proposal_ID','subtotal','beforeTax','AddTax','Nettotal','SpecialDiscountBath','total','invoices','status','Proposal','ProposalID',
                    'totalnetpriceproduct','room','unit','quantity','totalnetMeals','Meals','Banquet','totalnetBanquet','totalentertainment','entertainment','Receipt','ids','fullname'
                    ,'firstPart','Identification','address','totalReceipt','vat','Additional','AdditionaltotalReceipt','Additionaltotal','Receiptover','statusover','Additional_ID',
                    'Rm','FB','BQ','AT','EM','RmCount','FBCount','BQCount','EMCount','ATCount','additional_type','totalinvoices','Cash','Com','totalReceiptCom'));
    }

    public function PaidInvoice($id){
        $invoices = document_invoices::where('id', $id)->first();
        $proposalid = $invoices->Quotation_ID;
        $Invoice_ID = $invoices->Invoice_ID;
        $sumpayment = $invoices->sumpayment;
        $amountproposal = $invoices->sumpayment;
        $valid = $invoices->valid;
        $Proposal = Quotation::where('Quotation_ID',$proposalid)->first();
        $guest = $Proposal->Company_ID;

        $type = $Proposal->type_Proposal;
        $Percent = $invoices->paymentPercent;
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

        return view('billingfolio.invoicepaid',compact('invoices','address','Identification','valid','Proposal','name','Percent','name_ID','datasub','type','REID','Invoice_ID','settingCompany','data_bank','sumpayment','chequeRe','chequeRestatus','additional_type',
                    'additional_Nettotal','data_cheque','amountproposal','Cash','Complimentary'));
    }
    public function EditPaidInvoice($id){
        $re = receive_payment::where('id',$id)->first();
        $Invoice_ID = $re->Invoice_ID;
        $company = $re->company;
        $REID = $re->Receipt_ID;
        $proposalid = $re->Quotation_ID;
        $sumpayment =$re->document_amount;
        $fullname = $re->fullname;
        $productItems = document_receive_item::where('receive_id',$REID)->get();

        $Proposal = Quotation::where('Quotation_ID',$proposalid)->first();
        $guest = $Proposal->Company_ID;
        $invoices = document_invoices::where('Invoice_ID', $Invoice_ID)->first();
        $valid = $invoices->valid;
        $type = $Proposal->type_Proposal;
        $Percent = $invoices->paymentPercent;
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

        return view('billingfolio.editinvoicepaid',compact('company','address','re','Identification','valid','Proposal','name','fullname','Percent','name_ID','datasub','type','REID','Invoice_ID','settingCompany',
                    'sumpayment','complimentary','additional','additional_type','productItems'));
    }
    public function PaidInvoiceData($id)
    {
        $parts = explode('-', $id);
        $firstPart = $parts[0];
        if ($firstPart == 'C') {
            $company =  companys::where('Profile_ID',$id)->first();
            if ($company) {
                $fullname = $company->Company_Name;
                $Address=$company->Address;
                $CityID=$company->City;
                $amphuresID = $company->Amphures;
                $TambonID = $company->Tambon;
                $Identification = $company->Taxpayer_Identification;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
            }else{
                $company =  company_tax::where('ComTax_ID',$id)->first();
                $fullname = $company && $company->Companny_name
                            ? 'บริษัท ' . $company->Companny_name . ' จำกัด'
                            : 'คุณ ' . $company->first_name . ' ' . $company->last_name;
                $Address=$company->Address;
                $CityID=$company->City;
                $amphuresID = $company->Amphures;
                $TambonID = $company->Tambon;
                $Identification = $company->Taxpayer_Identification;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
            }
        }else{

            $guestdata =  Guest::where('Profile_ID',$id)->first();

            if ($guestdata) {
                $fullname =  'คุณ '.$guestdata->First_name.' '.$guestdata->Last_name;
                $Address=$guestdata->Address;
                $CityID=$guestdata->City;
                $amphuresID = $guestdata->Amphures;
                $TambonID = $guestdata->Tambon;
                $Identification = $guestdata->Identification_Number;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
            }else{
                $guestdata =  guest_tax::where('GuestTax_ID',$id)->first();
                $fullname = $guestdata && $guestdata->Company_name
                            ? 'บริษัท ' . $guestdata->Company_name . ' จำกัด'
                            : 'คุณ ' . $guestdata->first_name . ' ' . $guestdata->last_name;
                $Address=$guestdata->Address;
                $CityID=$guestdata->City;
                $amphuresID = $guestdata->Amphures;
                $TambonID = $guestdata->Tambon;
                $Identification = $guestdata->Identification_Number;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
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
    public function PaidInvoiceDataprewive($id,$ids)
    {
        $parts = explode('-', $id);
        $firstPart = $parts[0];
        if ($firstPart == 'C') {
            $company =  companys::where('Profile_ID',$id)->first();
            if ($company) {
                $fullname = "";
                $Company_typeID=$company->Company_type;
                if ($company->Company_Name) {
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $fullnameCom = " บริษัท ". $company->Company_Name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $fullnameCom = " บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $fullnameCom = " ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
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
            }else{
                $company =  company_tax::where('ComTax_ID',$id)->first();
                $fullname = $company && $company->Companny_name
                            ? ""
                            : 'คุณ ' . $company->first_name . ' ' . $company->last_name;
                $Company_typeID=$company->Company_type;
                if ($company->Companny_name) {
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $fullnameCom = " บริษัท ". $company->Companny_name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $fullnameCom = " บริษัท ". $company->Companny_name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $fullnameCom = " ห้างหุ้นส่วนจำกัด ". $company->Companny_name ;
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
            }
        }else{
            $guestdata =  Guest::where('Profile_ID',$id)->first();
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
            }else{
                $guestdata =  guest_tax::where('GuestTax_ID',$id)->first();
                $fullname = $guestdata && $guestdata->Company_name
                            ? ""
                            : 'คุณ ' . $guestdata->first_name . ' ' . $guestdata->last_name;
                $Company_typeID=$guestdata->Company_type;
                if ($guestdata->Company_name) {
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $fullnameCom = " บริษัท ". $guestdata->Company_name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $fullnameCom = " บริษัท ". $guestdata->Company_name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $fullnameCom = " ห้างหุ้นส่วนจำกัด ". $guestdata->Company_name ;
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
            }
        }
        $date = Carbon::now();
        $dateFormatted = $date->format('d/m/Y');
        $dateTime = $date->format('h:i:s A');
        $invoices = document_invoices::where('Invoice_ID', $ids)->first();
        $valid = $invoices->valid;
        return response()->json([
            'valid'=>$valid,
            'date'=>$dateFormatted,
            'Time'=>$dateTime,
            'fullname'=>$fullname,
            'fullnameCom'=>$fullnameCom,
            'Address' => $Address,
            'Identification' => $Identification,
            'province'=>$provinceNames,
            'amphures'=>$amphuresID,
            'Tambon'=>$TambonID,
        ]);
    }
    public function cheque ($id)
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
    public function savere(Request $request) {
        $data = $request->all();

        $requestData = $request->all();

        $groupedData = []; // ตัวแปรสำหรับจัดเก็บข้อมูลที่ใช้ index

        foreach ($requestData as $key => $value) {
            if (strpos($key, 'paymentType_') === 0) { // ตรวจสอบว่าคีย์ขึ้นต้นด้วย 'paymentType_'
                preg_match('/\d+$/', $key, $matches); // ดึงตัวเลขท้ายคีย์
                $index = $matches[0]; // ตัวเลขที่ได้จากคีย์ เช่น 0, 1, 2
                $groupedData[$index ] = [
                    "paymentType" =>  $data["paymentType"] ?? null, // ค่า paymentType_x ที่ดึงมา
                    "cashAmount" =>  $data["cashAmount"] ?? null,
                    "Complimentary" => $data["Complimentary"] ?? null,
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
                    "NoShowAmount" => isset($data["NoShowAmount"])
                    ? str_replace([',', '.00'], ['', ''], $data["NoShowAmount"]) // ถอดคอมมาและ .00
                    : null, // ถ้าไม่มีค่าก็ให้เป็น null
                    "detail" => ($data["paymentType"] == 'cash')
                    ? 'Cash'
                    : ($data["paymentType"] == 'bankTransfer'
                        ? $data["bank"] . ' Bank Transfer - Together Resort Ltd'
                        : ($data["paymentType"] == 'creditCard'
                            ? 'Credit Card No. ' . $data["CardNumber"] . ' Exp. Date: ' . $data["Expiry"]
                            : ($data["paymentType"] == 'cheque'
                                ? 'Cheque Bank ' . $data["chequebank_name"] . ' Cheque Number ' . $data["cheque"]
                                : ($data["paymentType"] == 'No Show'
                                    ? 'No Show '
                                    : null
                                )
                            )
                        )
                    ),
                ];
                // สร้างอาร์เรย์ใหม่ที่ใช้ index
                $groupedData[$index + 1] = [
                    "paymentType" => $value, // ค่า paymentType_x ที่ดึงมา
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
                    "detail" => ($value == 'bankTransfer'
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
                    "Complimentary" => $data["Complimentary"] ?? null,
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
                    "NoShowAmount" => isset($data["NoShowAmount"])
                    ? str_replace([',', '.00'], ['', ''], $data["NoShowAmount"]) // ถอดคอมมาและ .00
                    : null, // ถ้าไม่มีค่าก็ให้เป็น null
                    "detail" => ($data["paymentType"] == 'cash')
                    ? 'Cash'
                    : ($data["paymentType"] == 'bankTransfer'
                        ? $data["bank"] . ' Bank Transfer - Together Resort Ltd'
                        : ($data["paymentType"] == 'creditCard'
                            ? 'Credit Card No. ' . $data["CardNumber"] . ' Exp. Date: ' . $data["Expiry"]
                            : ($data["paymentType"] == 'cheque'
                                ? 'Cheque Bank ' . $data["chequebank_name"] . ' Cheque Number ' . $data["cheque"]
                                : ($data["paymentType"] == 'No Show'
                                    ? 'No Show '
                                    : null
                                )
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
        $Amountall = $cash+$cashbankTransfer+$cashCard+$cashcheque+$cashnoshow;
        $Amount = intval($Amountall);

        $guest = $request->Guest;
        $reservationNo = $request->reservationNo;
        $room = $request->roomNo;
        $numberOfGuests = $request->numberOfGuests;
        $arrival = $request->arrival;
        $departure = $request->departure;
        $additional_type = $request->additional_type;
        $additional = $request->additional ?? 0;
        $note = $request->note;
        $paymentDate = $request->paymentDate;
        $Complimentary = $request->Complimentary;
        $paymentType = $request->paymentTypecheque ?? $request->paymentType;
        if ($paymentType == null || $guest == null || $reservationNo == null || $room == null || $numberOfGuests == null || $arrival == null || $departure == null) {
            return redirect()->route('BillingFolio.index')->with('error', 'กรุณากรอกข้อมูลให้ครบ');
        }
        $invoice = $request->invoice;
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
        $invoices = document_invoices::where('Invoice_ID', $invoice)->first();
        $idinvoices = $invoices->id;
        $sumpayment = $invoices->sumpayment;
        $Quotation_ID = $invoices->Quotation_ID;
        $created_at = Carbon::parse($invoices->created_at)->format('d/m/Y');
        $template = master_template::query()->latest()->first();
        try {
            $user = Auth::user()->id;
            $save = new receive_payment();
            $save->Receipt_ID = $REID;
            $save->Invoice_ID = $invoice;
            $save->Quotation_ID = $Quotation_ID;
            $save->company = $guest;
            $save->Amount = $Amount;
            $save->fullname = $nameold;
            $save->additional_type = $additional_type;
            $save->additional = $additional;
            $save->complimentary = $data["Complimentary"] ?? null;
            $save->document_amount = ($Amount + ($data["Complimentary"] ?? 0));
            $save->reservationNo = $reservationNo;
            $save->roomNo = $room;
            $save->numberOfGuests = $numberOfGuests;
            $save->arrival = $arrival;
            $save->departure = $departure;
            $save->type_Proposal = $type_Proposal;
            $save->paymentDate = $paymentDate;
            $save->Operated_by = $user;
            $save->note = $note;
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
                                    ($index['Complimentary'] ?? 0) +
                                    ($index['bankTransferAmount'] ?? 0) +
                                    ($index['creditCardAmount'] ?? 0) +
                                    ($index['chequeamount'] ?? 0) +
                                    ($index['NoShowAmount'] ?? 0);
                $item->complimentary = $index['Complimentary'] ?? null;
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
            $amoute = 'ราคาที่จ่าย : '.number_format($Amount) . ' บาท';
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

            $variables = [$fullname,$name, $Reservation_No,$Room_No,$NumberOfGuests, $Arrival,$Departure,$PaymentDate,$Note,$amoute,$total,$edit];
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
            $dateTime = $date->format('H:i');
            $Amount = $sumpayment;
            $userid = Auth::user()->id;
            $user = User::where('id',$userid)->first();

            $productItems = [];
            foreach ($groupedData as $value) {
                $totalAmount =
                                ($value['cashAmount'] ?? 0) +
                                ($value['Complimentary'] ?? 0) +
                                ($value['bankTransferAmount'] ?? 0) +
                                ($value['creditCardAmount'] ?? 0) +
                                ($value['chequeamount'] ?? 0) +
                                ($value['NoShowAmount'] ?? 0);
                $productItems[] = [
                    'detail' => $value['detail'],
                    'amount' => $totalAmount,
                ];
            }
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
            $saveRe = document_invoices::find($idinvoices);
            $saveRe->status_receive = 2;
            $saveRe->Paid = 1;
            $saveRe->save();
            foreach ($groupedData as $index) {
                if (!empty($index['cheque'])) {
                    $chequeRe =receive_cheque::where('cheque_number',$index['cheque'])->where('status',1)->first();
                    $id_cheque = $chequeRe->id;
                    $chequeBankReceivedname= Masters::where('name_en', $index['chequebank'])->first();
                    $bank_received = $chequeBankReceivedname->id;
                    $savecheque = receive_cheque::find($id_cheque);
                    $savecheque->bank_received =$bank_received;
                    $savecheque->deposit_date =$index['deposit_date'];
                    $savecheque->status = 2;
                    $savecheque->save();
                }
            }
        } catch (\Throwable $e) {
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
                if ($Complimentary) {
                    $saveAD->status_guest = 1;
                }
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
            if ($total == 0) {
                foreach ($receive as $value) {
                   $value->document_status = 2;
                   $value->save();
                }
                $update = Quotation::find($id);
                $update->status_document = 9;
                $update->status_receive = 9;
                $update->save();
                return redirect()->route('BillingFolio.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
            }else{
                return redirect()->route('BillingFolio.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
            }
        } catch (\Throwable $e) {
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
        return redirect()->route('BillingFolio.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }
    public function view($id){
        $receive = receive_payment::where('id',$id)->first();
        $productItems = document_receive_item::where('receive_id', $receive->Receipt_ID)
            ->get()
            ->map(function ($value) {
                return [
                    'detail' => $value->detail,
                    'amount' => $value->amount,
                ];
            })
            ->toArray();
        $settingCompany = Master_company::orderBy('id', 'desc')->first();
        $guest = $receive->company;
        $paymentDate = $receive->paymentDate;
        $sumpayment= $receive->document_amount;
        $Invoice_ID = $receive->Invoice_ID;
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
        $dateFormatted = $date->format('d/m/Y').' / ';
        $dateTime = $date->format('H:i');
        $Amount = $sumpayment;
        $userid = Auth::user()->id;
        $user = User::where('id',$userid)->first();
        $invoices = document_invoices::where('Invoice_ID', $Invoice_ID)->first();
        $created_at = Carbon::parse($invoices->created_at)->format('d/m/Y');
        $template = master_template::query()->latest()->first();
        // dd( $receive,$productItems,$created_at);
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

}
