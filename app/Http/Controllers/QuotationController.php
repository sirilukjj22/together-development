<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quotation;
use Carbon\Carbon;
use App\Models\companys;
use App\Models\representative;
use App\Models\representative_phone;
use App\Models\company_fax;
use App\Models\company_phone;
use App\Models\MasterEventFormate;
use App\Models\Freelancer_Member;
use App\Models\province;
use App\Models\amphures;
use App\Models\districts;
use App\Models\master_document;
use App\Models\master_product_item;
class QuotationController extends Controller
{
    public function index()
    {
        $Quotation = Quotation::query()->get();
        return view('quotation.index',compact('Quotation'));
    }
    public function create()
    {
        $currentDate = Carbon::now();
        $ID = 'Q';
        $formattedDate = Carbon::parse($currentDate);       // วันที่
        $month = $formattedDate->format('m'); // เดือน
        $year = $formattedDate->format('y');
        $lastRun = Quotation::latest()->first();
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
        $Quotation_ID = $ID.$year.$month.$newRunNumber;
        $Mevent = MasterEventFormate::select('name_th','id')->where('status', '1')->get();
        $Freelancer_member = Freelancer_Member::select('First_name','id','Profile_ID','Last_name')->where('status', '1')->get();
        $Company = companys::select('Company_Name','id','Profile_ID')->get();
        return view('quotation.create',compact('Quotation_ID','Company','Mevent','Freelancer_member','Issue_date','Valid_Until'));
    }
    public function Contact($companyID)
    {
        $Contact_name = representative::where('Company_ID',$companyID)->select('First_name','Last_name','Profile_ID','id')->orderby('id','desc')->get();
        return response()->json([
            'data' => $Contact_name,

        ]);
    }

    public function save(Request $request){
        $data = $request->all();
        $save = new Quotation();
        $save->Quotation_ID = $request->Quotation_ID;
        $save->Company_ID = $request->Company;
        $save->company_contact = $request->Company_Contact;
        $save->checkin = $request->Checkin;
        $save->checkout = $request->Checkout;
        $save->day = $request->Day;
        $save->night = $request->Night;
        $save->adult = $request->Adult;
        $save->children = $request->Children;
        $save->maxdiscount = $request->Max_discount;
        $save->ComRateCode = $request->Company_Rate_Code;
        $save->freelanceraiffiliate = $request->Freelancer_member;
        $save->commissionratecode = $request->Company_Commission_Rate_Code;
        $save->eventformat = $request->Mevent;
        $save->vat_type = $request->Vat_Type;
        $save->issue_date = $request->IssueDate;
        $save->Expirationdate = $request->Expiration;
        $save->save();
        if ( $save->save()) {
            return redirect()->to(url('/Quotation/Event_Formate/company/product/'.$save->Quotation_ID))->with('alert_', 'บันทึกข้อมูลเรียบร้อย');
        }else {
            return redirect()->back()->with('error_', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }

    }
    public function selectProduct($id)
    {
        $Quotation = Quotation::where('Quotation_ID', $id)->first();
        $Company = $Quotation->Company_ID;
        $Company_ID = companys::where('Profile_ID',$Company)->first();
        $Company_typeID=$Company_ID->Company_type;
        $CityID=$Company_ID->City;
        $amphuresID = $Company_ID->Amphures;
        $TambonID = $Company_ID->Tambon;
        $Company_type = master_document::where('id',$Company_typeID)->select('name_th','id')->first();
        $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
        $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
        $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
        $company_fax = company_fax::where('Profile_ID',$Company)->where('Sequence','main')->first();
        $company_phone = company_phone::where('Profile_ID',$Company)->where('Sequence','main')->first();
        // dd($Company_typeID);
        // ส่งตัวแปรไปยัง view
        $Contact_name = representative::where('Company_ID',$Company)->where('status',1)->first();
        $ContactCityID = $Contact_name->City;
        $ContactamphuresID = $Contact_name->Amphures;
        $ContactTambonID = $Contact_name->Tambon;
        $Contact_phone = representative_phone::where('Company_ID',$Company)->where('Sequence','main')->first();
        $ContactCity = province::where('id',$ContactCityID)->select('name_th','id')->first();
        $ContactamphuresID = amphures::where('id',$ContactamphuresID)->select('name_th','id')->first();
        $ContactTambonID = districts::where('id',$ContactTambonID)->select('name_th','id','Zip_Code')->first();
        $room_type = master_product_item::orderBy('Product_ID', 'asc')->where('status',1)->where('Category','Room_Type')->get();
        return view('quotation.selectproduct',compact('Quotation','Company_ID','Company_type','amphuresID','TambonID','provinceNames','company_fax','company_phone'
        ,'Contact_name','Contact_phone','ContactCity','ContactamphuresID','ContactTambonID','room_type'));
    }
}
