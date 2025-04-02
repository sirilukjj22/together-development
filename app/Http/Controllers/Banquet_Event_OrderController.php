<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\banquet_event_order;
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
class Banquet_Event_OrderController extends Controller
{
    public function index()
    {
        $Proposal = Quotation::query()
        ->leftJoin('banquet_event_order', 'quotation.Quotation_ID', '=', 'banquet_event_order.Quotation_ID')
        ->select(
            'quotation.*',
            DB::raw('banquet_event_order.Banquet_ID as Banquet_ID'),
            DB::raw('COUNT(banquet_event_order.Quotation_ID) as BEO_count')
        )
        ->where('quotation.status_document', 6)
        ->whereNull('banquet_event_order.Quotation_ID')
        ->groupBy('quotation.Quotation_ID')
        ->get();
        return view('banquet_event_order.index',compact('Proposal'));
    }

    public function create($id)
    {
        $currentDate = Carbon::now();
        $ID = 'BEO-';
        $formattedDate = Carbon::parse($currentDate);       // วันที่
        $month = $formattedDate->format('m'); // เดือน
        $year = $formattedDate->format('y');
        $lastRun = banquet_event_order::latest()->first();
        $nextNumber = 1;

        if ($lastRun == null) {
            $nextNumber = $lastRun + 1;

        }else{
            $lastRunid = $lastRun->id;
            $nextNumber = $lastRunid + 1;
        }
        $newRunNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        $BEOID = $ID.$year.$month.$newRunNumber;
        $Proposal = Quotation::where('id', $id)->first();
        $companyid = $Proposal->Company_ID;
        $settingCompany = Master_company::orderBy('id', 'desc')->first();
        $parts = explode('-', $companyid);
        $firstPart = $parts[0];
        if ($firstPart == 'C') {
            $Selectdata =  'Company';
            $company =  companys::where('Profile_ID',$companyid)->first();
            if ($company) {
                $Address=$company->Address;
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
                $CityID=$company->City;
                $amphuresID = $company->Amphures;
                $TambonID = $company->Tambon;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $address = $Address.' '.'ตำบล '.$TambonID->name_th.' '.'อำเภอ '.$amphuresID->name_th.' '.'จังหวัด '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
                $name_ID = $company->Profile_ID;
                $representative = representative::where('Company_ID', 'like', "%{$company->Profile_ID}%")->where('status',1)->first();
                if ($representative) {
                    $comtype = master_document::where('id',$representative->prefix)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="นาย") {
                        $contact = "นาย ". $representative->First_name . ' ' . $representative->Last_name;
                    }elseif ($comtype->name_th =="นาง") {
                        $contact = "นาง ". $representative->First_name . ' ' . $representative->Last_name;
                    }elseif ($comtype->name_th =="นางสาว") {
                        $contact = "นางสาว ". $representative->First_name . ' ' . $representative->Last_name ;
                    }else{
                        $contact = "คุณ ". $representative->First_name . ' ' . $representative->Last_name ;
                    }
                    $representative_ID = $representative->Profile_ID;
                    $repCompany_ID = $representative->Company_ID;
                    $phone = representative_phone::where('Profile_ID',$representative_ID)->where('Company_ID',$repCompany_ID)->where('Sequence','main')->first();
                    $Email = $representative->Email;
                }
            }
        }else{
            $guestdata =  Guest::where('Profile_ID',$companyid)->first();
            if ($guestdata) {
                $Selectdata =  'Guest';
                $Company_typeID=$guestdata->Company_type;
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
        $userid = Auth::user()->id;
        $user = User::where('id',$userid)->first();
        return view('banquet_event_order.create',compact('id','settingCompany','BEOID','Proposal','fullName','address','phone','Email','Selectdata','contact','user'));
    }
    public function save(Request $request ,$id){
        $data = $request->all();
        $schedules = [];
        $assets = [];
        $Food = [];
        foreach ($data as $key => $value) {
            if (preg_match('/^(DateSchedule|RoomSchedule|functionSchedule|setupSchedule|agrSchedule|setSchedule|StartSchedule|EndSchedule)_(\d+)$/', $key, $matches)) {
                $index = $matches[2]; // ดึงหมายเลข Schedule
                $field = $matches[1];  // ชื่อฟิลด์ เช่น DateSchedule, RoomSchedule

                $schedules[$index][$field] = $value;
            }
            if (preg_match('/^(assetItem|quantity|remarks|price)_(\d+)$/', $key, $matches)) {
                $indexItem = $matches[2]; // ดึงหมายเลข Schedule
                $fieldItem = $matches[1];  // ชื่อฟิลด์ เช่น DateSchedule, RoomSchedule

                $assets[$indexItem][$fieldItem] = $value;
            }
            if (preg_match('/^(date-food|startfoodTime|endfoodTime|foodinputRoom|foodinputSpecial|foodinputGuest|foodinputFood|foodinputtype|foodinputDrink)_(\d+)$/', $key, $matches)) {
                $indexFood = $matches[2]; // ดึงหมายเลข Schedule
                $fieldFood = $matches[1];  // ชื่อฟิลด์ เช่น DateSchedule, RoomSchedule

                $Food[$indexFood][$fieldFood] = $value;
            }
        }


        dd($id,$data,$schedules,$assets,$Food);
    }

}
