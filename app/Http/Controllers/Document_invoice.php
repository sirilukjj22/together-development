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

use Auth;
use App\Models\User;
use Carbon\Carbon;
use PDF;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Dompdf\Dompdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\master_template;
use Illuminate\Support\Facades\DB;

class Document_invoice extends Controller
{
    public function index()
    {
        $userid = Auth::user()->id;
        $Approved = Quotation::query()->where('Operated_by',$userid)->where('status_guest',1)->get();
        $Approvedcount = Quotation::query()->where('Operated_by',$userid)->where('status_guest',1)->count();
        return view('document_invoice.index',compact('Approved','Approvedcount'));
    }
    public function Generate($id){

        $currentDate = Carbon::now();
        $ID = 'IV-';
        $formattedDate = Carbon::parse($currentDate);       // วันที่
        $month = $formattedDate->format('m'); // เดือน
        $year = $formattedDate->format('y');
        $lastRun = document_invoices::latest()->first();
        $nextNumber = 1;

        if ($lastRun == null) {
            $nextNumber = $lastRun + 1;

        }else{
            $lastRunid = $lastRun->id;
            $nextNumber = $lastRunid + 1;
        }
        $Issue_date = Carbon::parse($currentDate)->translatedFormat('d/m/Y');
        $Valid_Until = Carbon::parse($currentDate)->addDays(7)->translatedFormat('d/m/Y');
        $newRunNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        $InvoiceID = $ID.$year.$month.$newRunNumber;
        $Quotation = Quotation::where('id', $id)->first();
        $QuotationID = $Quotation->Quotation_ID;
        $CompanyID = $Quotation->Company_ID;
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
        $checkin = Carbon::parse($Checkin)->format('d/m/Y');
        $checkout = Carbon::parse($Checkout)->format('d/m/Y');

        $Contact_phone = representative_phone::where('Company_ID',$CompanyID)->where('Profile_ID',$profilecontact)->where('Sequence','main')->first();
        $invoices =document_invoices::where('Quotation_ID',$QuotationID)->latest()->first();
        if ($invoices) {
            $deposit = $invoices->deposit;
            $Deposit =$deposit+ 1;
            $balance = $invoices->balance;
            return view('document_invoice.createM',compact('QuotationID','comtypefullname','provinceNames','amphuresID','InvoiceID','Contact_name','Company'
            ,'TambonID','company_phone','company_fax','Contact_phone','Quotation','checkin','checkout','CompanyID','Deposit','balance','invoices'));
        }else{
            $Deposit = 1;
            return view('document_invoice.create',compact('QuotationID','comtypefullname','provinceNames','amphuresID','InvoiceID','Contact_name','Company'
            ,'TambonID','company_phone','company_fax','Contact_phone','Quotation','checkin','checkout','CompanyID','Deposit'));
        }
    }
    public function save(Request $request){

        // try {
            $preview=$request->preview;
            if ($preview == 1) {
                $Quotation_ID =$request->QuotationID;
                $Invoice = document_invoices::where('Quotation_ID',$Quotation_ID)->first();
                $company = $request->company;
                $Invoice_ID = $request->InvoiceID;
                $Company_ID = companys::where('Profile_ID',$company)->first();
                $Company_typeID=$Company_ID->Company_type;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="บริษัทจำกัด") {
                    $comtypefullname = "บริษัท ". $Company_ID->Company_Name . " จำกัด";
                }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                    $comtypefullname = "บริษัท ". $Company_ID->Company_Name . " จำกัด (มหาชน)";
                }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                    $comtypefullname = "ห้างหุ้นส่วนจำกัด ". $Company_ID->Company_Name ;
                }else {
                    $comtypefullname = $Company_ID->Company_Name;
                }
                $CityID=$Company_ID->City;
                $amphuresID = $Company_ID->Amphures;
                $TambonID = $Company_ID->Tambon;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $company_fax = company_fax::where('Profile_ID',$company)->where('Sequence','main')->first();
                $company_phone = company_phone::where('Profile_ID',$company)->where('Sequence','main')->first();
                $Contact_name = representative::where('Company_ID',$company)->where('status',1)->first();
                $Contact_phone = representative_phone::where('Company_ID',$company)->where('Sequence','main')->first();
                $Quotation = Quotation::where('Quotation_ID', $Quotation_ID)->first();
                $vat_type= $Quotation->vat_type;
                $vat_type = master_document::where('id',$vat_type)->first();
                $vatname = $vat_type->name_th;
                $eventformat =$Quotation->eventformat;
                $eventformat = master_document::where('id',$eventformat)->select('name_th','id')->first();
                $Checkin  = $Quotation->checkin;
                $Checkout = $Quotation->checkout;
                $checkin = Carbon::parse($Checkin)->format('d/m/Y');
                $checkout = Carbon::parse($Checkout)->format('d/m/Y');
                $date = Carbon::now();
                $date = Carbon::parse($date)->format('d/m/Y');
                $id = $request->QuotationID;
                $protocol = $request->secure() ? 'https' : 'http';
                $linkQR = $protocol . '://' . $request->getHost() . "/Invoice/cover/document/PDF/$id?page_shop=" . $request->input('page_shop');

                // Generate the QR code as PNG
                $qrCodeImage = QrCode::format('svg')->size(200)->generate($linkQR);
                $qrCodeBase64 = base64_encode($qrCodeImage);
                $balance =$request->balance;
                $Deposit = $request->Deposit;
                $payment=$request->Payment;
                $Nettotal= $request->Nettotal;
                $valid=$request->valid;
                $valid = Carbon::parse($valid)->format('d/m/Y');
                if ($payment) {
                    $payment0 = $payment.'บาท';
                    $Subtotal =0;
                    $total =0;
                    $addtax = 0;
                    $before = 0;
                    $balance =0;

                    $Subtotal = $payment;
                    $total = $payment;
                    $addtax = 0;
                    $before = $payment;
                    $balance = $Nettotal-$Subtotal;
                }
                $paymentPercent=$request->PaymentPercent;
                if ($paymentPercent) {
                    $payment0 = $paymentPercent.'%';
                    $Subtotal =0;
                    $total =0;
                    $addtax = 0;
                    $before = 0;
                    $balance =0;
                    $Nettotal = floatval(str_replace(',', '', $request->Nettotal));
                    $paymentPercent = floatval($paymentPercent);
                    $Subtotal = ($Nettotal*$paymentPercent)/100;
                    $total = $Subtotal/1.07;
                    $addtax = $Subtotal-$total;
                    $before = $Subtotal-$addtax;
                    $balance = $Nettotal-$Subtotal;
                }
                $data = [
                    'valid'=>$valid,
                    'date'=>$date,
                    'qrCodeBase64'=>$qrCodeBase64,
                    'Quotation'=>$Quotation,
                    'Invoice_ID'=>$Invoice_ID,
                    'comtypefullname'=>$comtypefullname,
                    'Company_ID'=>$Company_ID,
                    'TambonID'=>$TambonID,
                    'provinceNames'=>$provinceNames,
                    'amphuresID'=>$amphuresID,
                    'company_fax'=>$company_fax,
                    'company_phone'=>$company_phone,
                    'Contact_name'=>$Contact_name,
                    'Contact_phone'=>$Contact_phone,
                    'checkin'=>$checkin,
                    'checkout'=>$checkout,
                    'balance'=>$balance,
                    'Deposit'=>$Deposit,
                    'payment'=>$payment0,
                    'Nettotal'=>$Nettotal,
                    'Subtotal'=>$Subtotal,
                    'total'=>$total,
                    'addtax'=>$addtax,
                    'before'=>$before,
                    'balance'=>$balance,
                    'vatname'=>$vatname,
                ];
                $template = master_template::query()->latest()->first();
                $view= $template->name;
                $pdf = FacadePdf::loadView('invoicePDF.preview',$data);
                return $pdf->stream();
            }
            $data = $request->all();
            $save = new document_invoices();
            $save->deposit =$request->Deposit;
            $save->valid =$request->valid;
            $save->payment=$request->Payment;
            $save->paymentPercent=$request->PaymentPercent;
            $save->balance=$request->balance;
            $save->company=$request->company;
            $save->Invoice_ID=$request->InvoiceID;
            $save->Quotation_ID =$request->QuotationID;
            $save->Nettotal = $request->Nettotal;
            $save->save();
            return redirect()->route('invoice.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
        // } catch (\Throwable $e) {
        //     return response()->json([
        //         'error' => $e->getMessage()
        //     ], 500);
        // }
    }
}
