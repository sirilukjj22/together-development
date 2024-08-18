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
use App\Models\document_quotation;
use App\Models\document_receipt;
use Auth;
use App\Models\User;
use Carbon\Carbon;
use PDF;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Dompdf\Dompdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\master_template;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
class receiptController extends Controller
{
    public function index()
    {
        $userid = Auth::user()->id;
        $Proposal = Quotation::query()
        ->leftJoin('document_receipt', 'quotation.Quotation_ID', '=', 'document_receipt.Quotation_ID')
        ->leftJoin('document_invoice', 'quotation.Quotation_ID', '=', 'document_invoice.Quotation_ID')
        ->where('quotation.Operated_by', $userid)
        ->where('quotation.status_guest', 1)
        ->select(
            'quotation.*',

            DB::raw('SUM(document_receipt.deposit) as receipt_deposit'),
            DB::raw('MIN(CAST(REPLACE(document_receipt.total, ",", "") AS UNSIGNED)) as receipt_Nettotal'),
            DB::raw('COUNT(DISTINCT document_invoice.deposit) as invoice_count')// Count the number of document_invoices
        )
        ->groupBy('quotation.Quotation_ID', 'quotation.Operated_by', 'quotation.status_guest', 'quotation.status_receive')
        ->get();

        $Proposalcount = Quotation::query()->where('Operated_by',$userid)->where('status_receive',1)->count();

        $receiptcount = document_receipt::query()->where('Operated_by',$userid)->count();
        $receipt = document_receipt::query()->where('Operated_by',$userid)->get();


        //-----------------------modal PD PI RE----------------------------


        return view('receipt.index',compact('Proposal','Proposalcount','receiptcount','receipt'));
    }
    public function LOG($id)
    {
        $Quotation = Quotation::where('id', $id)->first();
        if ($Quotation) {
            $QuotationID = $Quotation->Quotation_ID;
            $correct = $Quotation->correct;
            // Use a regular expression to capture the part of the string before the first hyphen
            if (preg_match('/^(PD-\d{8})/', $QuotationID, $matches)) {
                $QuotationID = $matches[1];
            }

        }
        $log = log::where('Quotation_ID', 'LIKE', $QuotationID . '%')->get();
        $path = 'Log_PDF/proposal/';

        $invoice = document_invoices::where('Quotation_ID', $QuotationID)->first();
        if ($invoice) {
            $Invoice_ID = $invoice->Invoice_ID;
            $correctinvoice = $invoice->correct;
            // Use a regular expression to capture the part of the string before the first hyphen
            if (preg_match('/^(PI-\d{8})/', $Invoice_ID, $matches)) {
                $InvoiceID = $matches[1];
            }
        }

        $loginvoice = log::where('Quotation_ID',$InvoiceID)->get();
        $pathinvoice = 'Log_PDF/invoice/';
        return view('receipt.document',compact('log','path','correct','loginvoice','pathinvoice','correctinvoice'));
    }

    public function CheckPI($id)
    {
        $userid = Auth::user()->id;
        $Proposal = Quotation::where('id',$id)->where('Operated_by',$userid)->first();
        $ProposalID = $Proposal->id;
        $Proposal_ID = $Proposal->Quotation_ID;
        $totalAmount = $Proposal->Nettotal;
        $SpecialDiscountBath = $Proposal->SpecialDiscountBath;
        $SpecialDiscount = $Proposal->SpecialDiscount;
        $subtotal = 0;
        $beforeTax =0;
        $AddTax =0;
        $Nettotal =0;
        $total =0;
        $totalreceipt =0;
        $totalreceiptre =0;
        $total =  $totalAmount;
        $subtotal = $totalAmount-$SpecialDiscountBath;
        $beforeTax = $subtotal/1.07;
        $AddTax = $subtotal-$beforeTax;
        $Nettotal = $subtotal;

        $receipt = document_receipt::where('Quotation_ID',$Proposal_ID)->where('Operated_by',$userid)
                ->select()
                ->get();
        foreach ($receipt as $item) {
            $totalreceiptre +=  $item->deposit;
        }
        $totalreceipt = $Nettotal-$totalreceiptre;
        $invoices = document_invoices::where('Quotation_ID', $Proposal_ID)->where('Operated_by',$userid)->get();
        if ($invoices->contains('status_receive', 0)) {
            // ถ้า status มีค่าเป็น 0 อย่างน้อยหนึ่งรายการ
            $status = 0;
        } else {
            $status = 1;
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
        return view('receipt.check_pi',compact('Proposal_ID','subtotal','beforeTax','AddTax','Nettotal','SpecialDiscountBath','total','receipt','totalreceipt','invoices','status','Proposal','ProposalID',
                    'totalnetpriceproduct','room','unit','quantity','totalnetMeals','Meals','Banquet','totalnetBanquet','totalentertainment','entertainment'));
    }
    public function QuotationView($id){
        $ProposalID = $id;
        $Quotation = Quotation::where('id', $id)->first();
        $QuotationID= $Quotation->Quotation_ID;
        $Quotation_ID= $Quotation->Quotation_ID;
        $Company_ID = $Quotation->Company_ID;
        $contact = $Quotation->company_contact;
        $Mevent = master_document::select('name_th','id')->where('status', '1')->where('Category','Mevent')->get();
        $Mvat = master_document::select('name_th','id')->where('status', '1')->where('Category','Mvat')->get();
        $Freelancer_member = Freelancer_Member::select('First_name','id','Profile_ID','Last_name')->where('status', '1')->get();
        $Company = companys::select('Company_Name','id','Profile_ID')->get();
        $CompanyID = companys::where('Profile_ID',$Company_ID)->first();
        $Company_typeID=$CompanyID->Company_type;
        $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
        if ($comtype->name_th =="บริษัทจำกัด") {
            $comtypefullname = "บริษัท ". $CompanyID->Company_Name . " จำกัด";
        }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
            $comtypefullname = "บริษัท ". $CompanyID->Company_Name . " จำกัด (มหาชน)";
        }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
            $comtypefullname = "ห้างหุ้นส่วนจำกัด ". $CompanyID->Company_Name ;
        }else {
            $comtypefullname = $CompanyID->Company_Name;
        }
        $CityID=$CompanyID->City;
        $amphuresID = $CompanyID->Amphures;
        $TambonID = $CompanyID->Tambon;
        $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
        $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
        $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
        $company_fax = company_fax::where('Profile_ID',$Company_ID)->where('Sequence','main')->first();
        if (!$company_fax) {
            $company_fax = '-';
        }
        $company_phone = company_phone::where('Profile_ID',$Company_ID)->where('Sequence','main')->first();
        $Contact_name = representative::where('Company_ID',$Company_ID)->where('id',$contact)->where('status',1)->first();
        $profilecontact = $Contact_name->Profile_ID;
        $Contact_phone = representative_phone::where('Company_ID',$Company_ID)->where('Profile_ID',$profilecontact)->where('Sequence','main')->first();
        $selectproduct = document_quotation::where('Quotation_ID', $QuotationID)->get();
        $unit = master_unit::where('status',1)->get();
        $quantity = master_quantity::where('status',1)->get();
        return view('receipt.quotation_view',compact('Quotation','Freelancer_member','Company','Mevent','Mvat','Quotation_ID','Contact_name','comtypefullname','CompanyID'
        ,'TambonID','amphuresID','CityID','provinceNames','company_fax','company_phone','Contact_phone','selectproduct','unit','quantity','QuotationID','ProposalID'));
    }
    public function InvoiceView($id){
        $invoices =document_invoices::where('id',$id)->first();
        $QuotationID = $invoices->Quotation_ID;
        $Quotation_ID = $invoices->Quotation_ID;
        $QuotationIDindex = Quotation::where('Quotation_ID', $Quotation_ID)->first();
        $ProposalID = $QuotationIDindex->id;


        $Refler_ID = $invoices->Refler_ID;
        $InvoiceID =  $invoices->Invoice_ID;
        $IssueDate=$invoices->IssueDate;
        $Expiration=$invoices->Expiration;
        $CompanyID = $invoices->company;
        $Deposit  =$invoices->deposit;
        $status = $invoices->document_status;
        $valid = $invoices->valid;
        $sequence = $invoices->sequence;
        $Operated_by=$invoices->Operated_by;
        $payment = $invoices->payment;
        $paymentPercent = $invoices->paymentPercent;


        if ($sequence == 1&&$status == 1) {
            $Quotation = Quotation::where('Quotation_ID', $QuotationID)->latest()->first();
            $Nettotal = $Quotation->Nettotal;

        }else{
            $Quotation = Quotation::where('Quotation_ID', $QuotationID)->latest()->first();
            $Nettotal = $invoices->Nettotal;
        }
        if ($status == 0) {
            $Quotation = Quotation::where('Refler_ID', $Refler_ID)->latest()->first();
            $Nettotal = $invoices->Nettotal;
        }


        $day =$Quotation->day;
        $night =$Quotation->night;
        $adult = $Quotation->adult;
        $children = $Quotation->children;
        $contact = $Quotation->company_contact;
        $Company = companys::where('Profile_ID',$CompanyID)->first();
        $Company_typeID=$Company->Company_type;
        $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
        if ($comtype->name_th =="บริษัทจำกัด") {
            $comtypefullname = "บริษัท ". $Company->Company_Name . " จำกัด";
        }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
            $comtypefullname = "บริษัท ". $Company->Company_Name . " จำกัด (มหาชน)";
        }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
            $comtypefullname = "ห้างหุ้นส่วนจำกัด ". $Company->Company_Name ;
        }else {
            $comtypefullname = $Company->Company_Name;
        }
        $CityID=$Company->City;
        $amphuresID = $Company->Amphures;
        $TambonID = $Company->Tambon;
        $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
        $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
        $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
        $company_fax = company_fax::where('Profile_ID',$CompanyID)->where('Sequence','main')->first();
        $company_phone = company_phone::where('Profile_ID',$CompanyID)->where('Sequence','main')->first();
        $Contact_name = representative::where('Company_ID',$CompanyID)->where('id',$contact)->where('status',1)->first();
        $Checkin = $Quotation->checkin;
        $Checkout = $Quotation->checkout;
        $profilecontact = $Contact_name->Profile_ID;
        if ($Checkin) {
            $checkin = Carbon::parse($Checkin)->format('d/m/Y');
            $checkout = Carbon::parse($Checkout)->format('d/m/Y');
        }else{
            $checkin = '-';
            $checkout = '-';
        }
        $Contact_phone = representative_phone::where('Company_ID',$CompanyID)->where('Profile_ID',$profilecontact)->where('Sequence','main')->first();
        if ($payment) {

            $payment0 = $payment;
            $Subtotal =0;
            $total =0;
            $addtax = 0;
            $before = 0;
            $balance = 0;

            $Subtotal = $payment;
            $total = $payment;
            $addtax = 0;
            $before = $payment;
            $balance = $Subtotal;
        }
        if ($paymentPercent) {
            $payment0 = $paymentPercent.'%';
            $Subtotal =0;
            $total =0;
            $addtax = 0;
            $before = 0;
            $balance = 0;
            $Nettotal = floatval(str_replace(',', '', $Nettotal));
            $paymentPercent = floatval($paymentPercent);
            $Subtotal = ($Nettotal*$paymentPercent)/100;
            $total = $Subtotal/1.07;
            $addtax = $Subtotal-$total;
            $before = $Subtotal-$addtax;
            $balance = $Nettotal-$Subtotal;

        }
        $formattedNumber = number_format($balance, 2, '.', ',');

        return view('receipt.invoice_view',compact('Quotation_ID','InvoiceID','comtypefullname','Company','TambonID','amphuresID','provinceNames','company_phone','company_fax','Contact_name'
        ,'Contact_phone','checkin','checkout','Quotation','QuotationID','Deposit','CompanyID','IssueDate','Expiration','day','night','adult','children','valid','Nettotal','payment'
        ,'paymentPercent','Subtotal','before','formattedNumber','addtax','ProposalID'));
    }


    public function save(Request $request){
        try {
            $userid = Auth::user()->id;
            $QuotationID = $request->QuotationID;
            $Quotation = Quotation::where('Quotation_ID', $QuotationID)->first();
            $Company_ID = $Quotation->Company_ID;
            $save = new document_receipt();
            $save->receipt_ID = $request->receipt_ID;
            $save->Quotation_ID = $request->QuotationID;
            $save->Nettotal = $request->Nettotal;
            $save->total = $request->total;
            $save->deposit = $request->deposit;
            $sequence= document_receipt::where('Quotation_ID', $QuotationID)->latest()->first();
            $sequence_re = $sequence->sequence_re;
            $sequencenumber = $sequence_re +1;

            $save->sequence_re = $sequencenumber;
            $save->Operated_by = $userid;
            $save->company = $Company_ID;
            $save->save();
            $invoice = document_invoices::where('Quotation_ID', $QuotationID)->where('sequence_re',0)->update(['sequence_re' => $sequencenumber]);
            return redirect()->route('receipt.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function view($id)
    {
        $receiptDATA =document_receipt::where('id', $id)->first();
        $QuotationIDreceipt =$receiptDATA->Quotation_ID;
        $sequence_re =$receiptDATA->sequence_re;
        $Quotation = Quotation::where('Quotation_ID', $QuotationIDreceipt)->first();
        $QuotationID= $Quotation->Quotation_ID;
        $Quotation_ID= $Quotation->Quotation_ID;
        $Company_ID = $Quotation->Company_ID;
        $contact = $Quotation->company_contact;
        $SpecialDiscountBath = $Quotation->SpecialDiscountBath;
        $vat_type = $Quotation->vat_type;
        $TotalPax = $Quotation->TotalPax;
        $Company = companys::select('Company_Name','id','Profile_ID')->get();
        $CompanyID = companys::where('Profile_ID',$Company_ID)->first();
        $Company_typeID=$CompanyID->Company_type;
        $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
        if ($comtype->name_th =="บริษัทจำกัด") {
            $comtypefullname = "บริษัท ". $CompanyID->Company_Name . " จำกัด";
        }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
            $comtypefullname = "บริษัท ". $CompanyID->Company_Name . " จำกัด (มหาชน)";
        }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
            $comtypefullname = "ห้างหุ้นส่วนจำกัด ". $CompanyID->Company_Name ;
        }else {
            $comtypefullname = $CompanyID->Company_Name;
        }
        $CityID=$CompanyID->City;
        $amphuresID = $CompanyID->Amphures;
        $TambonID = $CompanyID->Tambon;
        $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
        $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
        $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
        $company_fax = company_fax::where('Profile_ID',$Company_ID)->where('Sequence','main')->first();
        if (!$company_fax) {
            $company_fax = '-';
        }
        $company_phone = company_phone::where('Profile_ID',$Company_ID)->where('Sequence','main')->first();
        $Contact_name = representative::where('Company_ID',$Company_ID)->where('id',$contact)->where('status',1)->first();
        $profilecontact = $Contact_name->Profile_ID;
        $Contact_phone = representative_phone::where('Company_ID',$Company_ID)->where('Profile_ID',$profilecontact)->where('Sequence','main')->first();
        $selectproduct = document_quotation::where('Quotation_ID', $QuotationID)->get();
        $unit = master_unit::where('status',1)->get();
        $quantity = master_quantity::where('status',1)->get();
        $receipt_ID = $receiptDATA->receipt_ID;
        $Products = Arr::wrap($selectproduct->pluck('Product_ID')->toArray());
        $quantities = $selectproduct->pluck('Quantity')->toArray();
        $discounts = $selectproduct->pluck('discount')->toArray();
        $priceUnits = $selectproduct->pluck('priceproduct')->toArray();
        $productItems = [];
        $totaldiscount = [];
        foreach ($Products as $index => $productID) {

            if (count($quantities) === count($priceUnits) && count($priceUnits) === count($discounts)) {
                $totalPrices = []; // เปลี่ยนจากตัวแปรเดียวเป็น array เพื่อเก็บผลลัพธ์แต่ละรายการ
                $discountedPrices = [];
                $discountedPricestotal = [];
                $totaldiscount = [];
                // คำนวณราคาสำหรับแต่ละรายการ
                for ($i = 0; $i < count($quantities); $i++) {
                    $quantity = intval($quantities[$i]);
                    $priceUnit = floatval(str_replace(',', '', $priceUnits[$i]));
                    $discount = floatval($discounts[$i]);

                    $totaldiscount0 = (($priceUnit * $discount)/100);
                    $totaldiscount[] = $totaldiscount0;

                    $totalPrice = ($quantity * $priceUnit);
                    $totalPrices[] = $totalPrice;

                    $discountedPrice = (($totalPrice * $discount )/ 100);
                    $discountedPrices[] = $priceUnit-$totaldiscount0;

                    $discountedPriceTotal = $totalPrice - $discountedPrice;
                    $discountedPricestotal[] = $discountedPriceTotal;
                }
            }

            $Checkin = $Quotation->checkin;
            $Checkout = $Quotation->checkout;
            if ($Checkin) {
                $checkin = Carbon::parse($Checkin)->format('d/m/Y');
                $checkout = Carbon::parse($Checkout)->format('d/m/Y');
            }else{
                $checkin = '-';
                $checkout = '-';
            }
            $items = master_product_item::where('Product_ID', $productID)->get();
            $QuotationVat= $Quotation->vat_type;
            $Mvat = master_document::where('id',$QuotationVat)->where('status', '1')->where('Category','Mvat')->select('name_th','id')->first();

            foreach ($items as $item) {
                // ตรวจสอบและกำหนดค่า quantity และ discount
                $quantity = isset($quantities[$index]) ? $quantities[$index] : 0;
                $discount = isset($discounts[$index]) ? $discounts[$index] : 0;
                $totalPrices = isset($totalPrices[$index]) ? $totalPrices[$index] : 0;
                $discountedPrices = isset($discountedPrices[$index]) ? $discountedPrices[$index] : 0;
                $discountedPricestotal = isset($discountedPricestotal[$index]) ? $discountedPricestotal[$index] : 0;
                $totaldiscount = isset($totaldiscount[$index]) ? $totaldiscount[$index] : 0;
                // รวมข้อมูลของผลิตภัณฑ์เข้ากับ quantity และ discount
                $productItems[] = [
                    'product' => $item,
                    'quantity' => $quantity,
                    'discount' => $discount,
                    'totalPrices'=>$totalPrices,
                    'discountedPrices'=>$discountedPrices,
                    'discountedPricestotal'=>$discountedPricestotal,
                    'totaldiscount'=>$totaldiscount,
                ];

            }
        }
        $invoice = document_invoices::where('Quotation_ID', $QuotationID)->where('sequence_re',$sequence_re)->get();
        $totalAmount = 0;
        $totaldiscount = 0;
        $netprice=0;
        $totalPrice = 0;
        $vat=0;
        $total=0;
        $adult = $Quotation->adult;
        $children = $Quotation->children;
        $totalguest = 0;
        $totalguest = $adult + $children;
        $guest = $Quotation->TotalPax;
        $totalaverage=0;
        $totalinvoice = 0;
        $totalreceipt = 0;
        $Nettotal00 = 0;
        foreach ($invoice as $item) {
            $totalinvoice +=  $item->sumpayment;

        }

        $receiptdatacheck = document_receipt::where('Quotation_ID', $QuotationID)->where('sequence_re', '<',$sequence_re)->get();
        $ids = []; // กำหนดตัวแปร $ids เป็น array เปล่า

        foreach ($receiptdatacheck as $receipt) {
            $ids[] = $receipt->id;
            // ดึง id ของแต่ละ receipt และเก็บใน array $ids
        }
        if (in_array($id, $ids)) { // เช็คว่าค่า $id อยู่ใน array $ids หรือไม่
            $ids = array_diff($ids, [$id]); // ลบค่า $id ออกจาก $ids
        }
        $receiptdata = document_receipt::whereIn('id', $ids)->get();
        foreach ($receiptdata as $item) {
            $totalreceipt +=  $item->deposit;

        }

        if ($Mvat->id == 50) {

            foreach ($selectproduct as $item) {
                $totalPrice +=  $item->priceproduct;
                $totalAmount += $item->netpriceproduct;
                $subtotal = $totalAmount-$SpecialDiscountBath;
                $beforeTax = $subtotal/1.07;
                $AddTax = $subtotal-$beforeTax;
                $Nettotal = $subtotal-$totalinvoice-$totalreceipt;
                $totalaverage =$Nettotal/$guest;
            }

        }
        elseif ($Mvat->id == 51) {
            foreach ($selectproduct as $item) {
                $totalPrice +=  $item->priceproduct;
                $totalAmount += $item->netpriceproduct;
                $subtotal = $totalAmount-$SpecialDiscountBath;
                $beforeTax = 0;
                $AddTax = 0;
                $Nettotal = $subtotal-$totalinvoice-$totalreceipt;
                $totalaverage =$Nettotal/$guest;
            }
        }
        elseif ($Mvat->id == 52) {
            foreach ($selectproduct as $item) {
                $totalPrice +=  $item->priceproduct;
                $totalAmount += $item->netpriceproduct;
                $subtotal = $totalAmount-$SpecialDiscountBath;
                $beforeTax = $subtotal/1.07;
                $AddTax = $subtotal*7/100;
                $Nettotal = $subtotal+$AddTax;
                $Nettotal00 = $Nettotal-$totalinvoice-$totalreceipt;
                $totalaverage =$Nettotal00/$guest;
            }
        }

        if ($invoice->contains('status_receive', 0)) {
            // ถ้า status มีค่าเป็น 0 อย่างน้อยหนึ่งรายการ
            $status = 0;
        } else {
            $status = 1;
        }
        return view('receipt.view',compact('Quotation','Company','Quotation_ID','Contact_name','comtypefullname','CompanyID'
        ,'TambonID','amphuresID','CityID','provinceNames','company_fax','company_phone','Contact_phone','unit','quantity','QuotationID','vat_type','TotalPax',
        'subtotal','SpecialDiscountBath','beforeTax','AddTax','Nettotal','totalreceipt','totalaverage','totalAmount','invoice','totalinvoice','receipt_ID','status','receiptdata','selectproduct','totalreceipt'));
    }
}
