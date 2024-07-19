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
        return view('document_invoice.create',compact('QuotationID','comtypefullname','provinceNames','amphuresID','InvoiceID','Contact_name','Company'
        ,'TambonID','company_phone','company_fax','Contact_phone','Quotation','checkin','checkout'));

    }
}
