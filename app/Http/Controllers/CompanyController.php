<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\companys;
use App\Models\master_document;
use App\Models\province;
use App\Models\amphures;
use App\Models\districts;
use App\Models\representative_phone;
use App\Models\company_fax;
use App\Models\company_phone;
use App\Models\representative;

class CompanyController extends Controller
{
    public function index()
    {
        $Company = companys::query()->get();
        $Mbooking = master_document::select('name_en','id')->get();
        return view('company.index',compact('Mbooking','Company'));
    }
    public function contact($id)
    {
        $Company = companys::find($id);
        $Company_Name = $Company->Company_Name;
        $Branch = $Company->Branch;
        $representative = representative::where('Company_Name', 'like', "%{$Company_Name}%")
                        ->where('Branch', 'like', "%{$Branch}%")->get();
        $N_Profile_ID = representative::where('Company_Name', 'like', "%{$Company_Name}%")
                        ->where('Branch', 'like', "%{$Branch}%")
                        ->where('status', '1')->count();
        $N_Profile_ID = $N_Profile_ID + 1 ;
        $Mprefix = master_document::select('name_th','id')->where('status', 1)->Where('Category','Mprefix')->get();
        $provinceNames = province::select('name_th','id')->get();
        return view('company.contact',compact('provinceNames','Mprefix','Company','N_Profile_ID','representative'));
    }
    public function contactcreate(Request $request ,$id)
    {
        $data = $request->all();
        $Company = companys::find($id);
       $N_Profile = $Company->Profile_ID;
        $Company_NameA = $Company->Company_Name;
        $Company_Name = $Company->Company_Name;
        $BranchA = $Company->Branch;
        $Branch = $Company->Branch;
        $Profile_last = representative::where('Company_Name', 'like', "%{$Company_Name}%")
                        ->where('Branch', 'like', "%{$Branch}%")
                        ->where('status', '1')->first();
        if ( $Profile_last !== null) {
            $status = $Profile_last->status;
            if ($status == 1 ) {
                $status = 0;
                $Profile_last->status = $status;
            }
            $Profile_last->save();
        }
        $latestAgent = representative::where('Company_Name', 'like', "%{$Company_Name}%")->where('Branch', 'like', "%{$Branch}%")->latest('Profile_ID')->first();
        if ($latestAgent) {
            $latestAgent=$latestAgent->Profile_ID+1;
        }else {
            $latestAgent=1;
        }
        $A_Profile = $latestAgent;
        $saveAgent = new representative();
        $saveAgent->Profile_ID = $A_Profile;
        $saveAgent->prefix = $request->prefix;
        $saveAgent->First_name = $request->first_nameAgent;
        $saveAgent->Last_name = $request->last_nameAgent;
        $countrydataA= $request->countrydataA;
        $provinceAgent= $request->provinceAgent;
        $amphuresA= $request->amphuresA;
        $TambonA= $request->TambonA;
        $zip_codeA= $request->zip_codeA;
        $cityA = $request->cityA;
        $addressAgent= $request->addressAgent;
        $EmailAgent= $request->EmailAgent;
        $NProfile_ID = $N_Profile;
        $phones = $request->phone;

        if ($countrydataA == "Other_countries") {
            if ($cityA === null) {
                return redirect()->back()->with('error', 'กรุณากรอกประเทศของคุณ');
            }else {
                $saveAgent->City = $cityA;
            }
        }else {
            $saveAgent->Country = $countrydataA;
            $saveAgent->City = $provinceAgent;
            $saveAgent->Amphures = $amphuresA;
            $saveAgent->Address = $addressAgent;
            $saveAgent->Tambon = $TambonA;
            $saveAgent->Zip_Code = $zip_codeA;
            $saveAgent->Email = $EmailAgent;
            $saveAgent->Company_ID = $NProfile_ID;
            $saveAgent->Company_Name = $Company_NameA;
            $saveAgent->Branch = $BranchA;
        }
        $saveAgent->save();
        foreach ($phones as $index => $phoneNumber) {
            if ($phoneNumber !== null) {
                $savephoneA = new representative_phone();
                $savephoneA->Profile_ID = $A_Profile;
                $savephoneA->Company_ID =$NProfile_ID;
                $savephoneA->Phone_number = $phoneNumber;
                $savephoneA->Sequence = ($index === 0) ? 'main' : 'secondary'; // กำหนดค่า Sequence
                $savephoneA->save();
            }
        }
        if ($savephoneA->save()) {
            return redirect()->to(url('/Company/edit/contact/'.$Company->id))->with('alert_', 'บันทึกข้อมูลเรียบร้อย');
        } else {
            return redirect()->back()->with('error_', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
    }

    public function contactedit(Request $request, $companyId , $itemId)
    {
        $Company = companys::find($companyId);
        $representative = representative::find($itemId);
        $representative_ID = $representative->Profile_ID;
        $repCompany_ID = $representative->Company_ID;
        $Mprefix = master_document::select('name_th','id')->where('status', 1)->Where('Category','Mprefix')->get();
        $number =  preg_replace("/[^0-9]/", "", $Company->City);
        $Other_City =  preg_replace("/[^a-zA-Z]/", "", $Company->City);
        $provinceNames = province::select('name_th','id')->get();
        $Tambon = districts::where('amphure_id', $Company->Amphures)->select('name_th','id')->get();
        $amphures = amphures::where('province_id', $Company->City)->select('name_th','id')->get();
        $Zip_code = districts::where('amphure_id', $Company->Amphures)->select('zip_code','id')->get();

        $phone = representative_phone::where('Profile_ID',$representative_ID)->where('Company_ID', 'like', "%{$repCompany_ID}%")->get();
        $phonecount = representative_phone::where('Profile_ID',$representative_ID)->where('Company_ID', 'like', "%{$repCompany_ID}%")->count();
        $phoneDataArray = $phone->toArray();

        return view('company.editContact',compact('representative','Company','Mprefix','provinceNames'
    ,'number','Other_City','provinceNames','Tambon','amphures','Zip_code','phoneDataArray','phonecount','representative_ID'));
    }
    public function contactupdate(Request $request, $companyId , $itemId)
    {
        $data = $request->all();
      //dd($data,$companyId,$itemId);
       $Company = companys::find($companyId);
        $countrydataA= $request->countrydataA;
        $provinceAgent= $request->province;
        $amphuresA= $request->amphures;
        $TambonA= $request->Tambon;
        $zip_codeA= $request->zip_code;
        $cityA = $request->city;
        $addressAgent= $request->addressAgent;
        $EmailAgent= $request->EmailAgent;
        $saveAgent = representative::find($itemId);
        $saveAgent->prefix = $request->Mprefix;
        $saveAgent->First_name = $request->first_nameAgent;
        $saveAgent->Last_name = $request->last_nameAgent;
        $phones = $request->phone;
        if ($countrydataA == "Other_countries") {
            if ($cityA === null) {
                return redirect()->back()->with('error', 'กรุณากรอกประเทศของคุณ');
            }else {
                $saveAgent->City = $cityA;
            }
        }else {
            $saveAgent->Country = $countrydataA;
            $saveAgent->City = $provinceAgent;
            $saveAgent->Amphures = $amphuresA;
            $saveAgent->Address = $addressAgent;
            $saveAgent->Tambon = $TambonA;
            $saveAgent->Zip_Code = $zip_codeA;
            $saveAgent->Email = $EmailAgent;
        }
        $CompanyId = representative::find($itemId);
        $Profile_ID = $CompanyId->Profile_ID;
        $Company_ID = $CompanyId->Company_ID;
        $Profile_last = representative_phone::where('Profile_ID',$Profile_ID)
                        ->where('Company_ID', 'like', "%{$Company_ID}%")->delete();

        foreach ($phones as $index => $phoneNumber) {
            if ($phoneNumber !== null) {
                $savephoneA = new representative_phone();
                $savephoneA->Profile_ID = $Profile_ID;
                $savephoneA->Company_ID = $Company_ID;
                $savephoneA->Phone_number = $phoneNumber;
                $savephoneA->Sequence = ($index === 0) ? 'main' : 'secondary'; // กำหนดค่า Sequence
                $savephoneA->save();
            }
        }
        $saveAgent->save();
        if ($savephoneA->save()) {
            return redirect()->to(url('/Company/edit/contact/'.$Company->id))->with('alert_', 'บันทึกข้อมูลเรียบร้อย');
        } else {
            return redirect()->back()->with('error_', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
    }
    public function acCon(Request $request ,$id)
    {
        $Company = companys::find($id);
        $Company_Name = $Company->Company_Name;
        $Branch = $Company->Branch;
        $representative = representative::where('Company_Name', 'like', "%{$Company_Name}%")
                        ->where('Branch', 'like', "%{$Branch}%")->get();
        $N_Profile_ID = representative::where('Company_Name', 'like', "%{$Company_Name}%")
                        ->where('Branch', 'like', "%{$Branch}%")
                        ->where('status', '1')->count();
        $N_Profile_ID = $N_Profile_ID + 1 ;
        $Mprefix = master_document::select('name_th','id')->where('status', 1)->Where('Category','Mprefix')->get();
        $provinceNames = province::select('name_th','id')->get();
        $ac = $request->value;
        if ($ac == 1 ) {
            $query = representative::query();
            $representative = $query->where('status', '1')->get();
        }
        return view('Company.contact',compact('Company','N_Profile_ID','representative','Mprefix','provinceNames'));
    }
    public function noCon(Request $request,$id)
    {
        $Company = companys::find($id);
        $Company_Name = $Company->Company_Name;
        $Branch = $Company->Branch;
        $representative = representative::where('Company_Name', 'like', "%{$Company_Name}%")
                        ->where('Branch', 'like', "%{$Branch}%")->get();
        $N_Profile_ID = representative::where('Company_Name', 'like', "%{$Company_Name}%")
                        ->where('Branch', 'like', "%{$Branch}%")
                        ->where('status', '1')->count();
        $N_Profile_ID = $N_Profile_ID + 1 ;
        $Mprefix = master_document::select('name_th','id')->where('status', 1)->Where('Category','Mprefix')->get();
        $provinceNames = province::select('name_th','id')->get();
        $no = $request->value;
        if ($no == 0 ) {
            $query = representative::query();
            $representative = $query->where('status', '0')->get();
        }
        return view('company.contact',compact('Company','representative','N_Profile_ID','Mprefix','provinceNames'));
    }
    public function deleteContact($companyId, $itemId) {
        // ตรวจสอบค่าที่ส่งมา
        $checkID = representative::where('id', $itemId)->first();

    if (!$checkID) {
        return response()->json(['success' => false, 'message' => 'ไม่พบข้อมูลที่ต้องการลบ'], 404);
    }

    $Profile_ID = $checkID->Profile_ID;
    $Company_ID = $checkID->Company_ID;

    try {
        // เริ่มการทำธุรกรรม
        DB::beginTransaction();

        // ลบข้อมูลจากตาราง representative_phone
        representative_phone::where('Profile_ID', $Profile_ID)
            ->where('Company_ID', 'like', "%{$Company_ID}%")
            ->delete();

        // ลบข้อมูลจากตาราง representative
        $checkID->delete();

        // ทำการยืนยันการลบข้อมูล
        DB::commit();

        return response()->json(['success' => true, 'message' => 'ลบข้อมูลเรียบร้อยแล้ว']);
    } catch (\Exception $e) {
        // ยกเลิกการทำธุรกรรมหากเกิดข้อผิดพลาด
        DB::rollBack();

        return response()->json(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการลบข้อมูล', 'error' => $e->getMessage()], 500);
    }

    }

    public function detail($id)
    {
        $Company = companys::find($id);

        return view('company.detail',compact('Company'));
    }
    public function changeStatuscontact(Request $request ,$id)
    {
        $ids = $request->ids;
        $status = $request->status; // รับค่า status ที่ส่งมาจาก Request
        $statusSS = representative::find($ids);
        $statusCheck = $statusSS->status;
        if ($statusCheck == 0 ) {
            $statusUP = 1;
            $saveAgent = representative::find($ids);
            $saveAgent->status = $statusUP;
            $saveAgent->save();
        }elseif (($statusCheck == 1 )) {
            $statusUP = 0;
            $saveAgent = representative::find($ids);
            $saveAgent->status = $statusUP;
            $saveAgent->save();

        }

    }

    public function create()
    {

        $latestCom = companys::latest('id')->first();
        if ($latestCom) {
            $Profile_ID = $latestCom->id + 1;
        } else {
            // ถ้าไม่มี Guest ในฐานข้อมูล เริ่มต้นด้วย 1
            $Profile_ID = 1;
        }
        $Id_profile ="C-";
        $N_Profile = $Id_profile.$Profile_ID;


            // ถ้าไม่มี Guest ในฐานข้อมูล เริ่มต้นด้วย 1
        $latestAgent = 1;
        $A_Profile = $latestAgent;
        $provinceNames = province::select('name_th','id')->get();
        $booking_channel = master_document::select('name_en', 'id')->where('status', 1)->Where('Category','Mbooking_channel')->get();
        $MCompany_type = master_document::select('name_th', 'id')->where('status', 1)->Where('Category','Mcompany_type')->get();
        $Mmarket = master_document::select('name_th', 'id')->where('status', 1)->Where('Category','Mmarket')->get();
        $Mprefix = master_document::select('name_th','id')->where('status', 1)->Where('Category','Mprefix')->get();
        return view('company.create',compact('booking_channel','provinceNames','MCompany_type','Mmarket','Mprefix','N_Profile','A_Profile'));
    }



    public function amphures($id)
    {
        $amphures= amphures::where('province_id',$id)->select('name_th','id')->orderby('id','desc')->get();
        return response()->json([
            'data' => $amphures,
        ]);

    }
    public function Tambon($id)
    {
        $Tambon = districts::where('amphure_id',$id)->select('name_th','id')->orderby('id','desc')->get();
        return response()->json([
            'data' => $Tambon,

        ]);
    }
    public function district($id)
    {
        $district = districts::where('id',$id)->select('zip_code','id')->orderby('id','desc')->get();
        return response()->json([
            'data' => $district,

        ]);
    }
    public function amphuresAgent($id)
    {

        $amphuresA= amphures::where('province_id',$id)->select('name_th','id')->orderby('id','desc')->get();
        return response()->json([
            'data' => $amphuresA,
        ]);
    }
    public function TambonAgent($id)
    {
        $TambonA = districts::where('amphure_id',$id)->select('name_th','id')->orderby('id','desc')->get();
        return response()->json([
            'data' => $TambonA,

        ]);
    }
    public function districtAgent($id)
    {

        $districtA = districts::where('id',$id)->select('zip_code','id')->orderby('id','desc')->get();
        return response()->json([
            'data' => $districtA,

        ]);

    }



    public function changeStatus(Request $request)
    {
        $id = $request->id;
        $status = $request->status; // รับค่า status ที่ส่งมาจาก Request
        $Company = companys::find($id);
        if ($status == 1 ) {
            $status = 0;
            $Company->status = $status;
        }elseif (($status == 0 )) {
            $status = 1;
            $Company->status = $status;
        }
        $Company->save();

    }
    public function ac(Request $request)
    {
        $ac = $request->value;
        if ($ac == 1 ) {
            $query = companys::query();
            $Company = $query->where('status', '1')->get();
        }
        return view('company.index',compact('Company'));
    }
    public function no(Request $request)
    {
        $no = $request->value;
        if ($no == 0 ) {
            $query = companys::query();
            $Company = $query->where('status', '0')->get();
        }
        return view('company.index',compact('Company'));
    }
    public function save(Request $request){
        $data = $request->all();
        $Company_Name = $request->Company_Name;
        $Branch = $request->Branch;

        $Company_Name = companys::where('Company_Name', 'like', "%{$Company_Name}%")
                        ->where('Branch', 'like', "%{$Branch}%")
                        ->where('status', '1')->first();
        if ($Company_Name) {
            if ($Company_Name->status === 1) {
                return redirect()->route('Company.create')->with('error_', 'ชื่อบริษัทและสาขาซ้ำกรุณากรอกใหม่');
            }
        } else {
            //save company
            $data = $request->all();
            $latestCom = companys::latest('id')->first();
            if ($latestCom) {
                $Profile_ID = $latestCom->id + 1;
            } else {
                // ถ้าไม่มี Guest ในฐานข้อมูล เริ่มต้นด้วย 1
                $Profile_ID = 1;
            }
            $Id_profile ="C-";
            $N_Profile = $Id_profile.$Profile_ID;
            $CountryOther = $request->countrydata;
            $Branch = $request->Branch;
            $province = $request->province;
            $amphures = $request->amphures;
            $Tambon = $request->Tambon;
            $zip_code = $request->zip_code;
            $city = $request->city;
            $Address= $request->address;
            $phone_company = $request->phone_company;
            $fax = $request->fax;

            $save = new companys();
            $save->Profile_ID = $N_Profile;
            $save->Company_Name = $request->Company_Name;
            $save->Company_type = $request->Company_type;
            $save->Market =$request->Mmarket;
            $save->Booking_Channel = $request->booking_channel;
            if ($CountryOther == "Other_countries") {
                if ($city === null) {
                    return redirect()->back()->with('error', 'กรุณากรอกประเทศของคุณ');
                }else {
                    $save->City = $city;
                }
            }else {
                $save->Country = $CountryOther;
                $save->City = $province;
                $save->Amphures = $amphures;
                $save->Address = $Address;
                $save->Tambon = $Tambon;
                $save->Zip_Code = $zip_code;
                $save->Branch = $Branch;
            }
            $save->Company_Email = $request->Company_Email;
            $save->Company_Website = $request->Company_Website;
            $save->Taxpayer_Identification = $request->Taxpayer_Identification;
            $save->Discount_Contract_Rate = $request->Discount_Contract_Rate;
            $save->Contract_Rate_Start_Date = $request->contract_rate_start_date;
            $save->Contract_Rate_End_Date = $request->contract_rate_end_date;
            $save->Lastest_Introduce_By =$request->Lastest_Introduce_By;
            $save->save();

            foreach ($phone_company as $index => $phoneNumber) {
                if ($phoneNumber !== null) {
                    $savephone = new company_phone();
                    $savephone->Profile_ID = $N_Profile;
                    $savephone->Phone_number = $phoneNumber;
                    $savephone->Sequence = ($index === 0) ? 'main' : 'secondary'; // กำหนดค่า Sequence
                    $savephone->save();
                }
            }
            foreach ($fax as $index => $faxNumber) {
                if ($faxNumber !== null) {
                    $savefax = new company_fax();
                    $savefax->Profile_ID = $N_Profile;
                    $savefax->Fax_number = $faxNumber;
                    $savefax->Sequence = ($index === 0) ? 'main' : 'secondary'; // กำหนดค่า Sequence
                    $savefax->save();
                }
            }
            //agent
            $latestAgent = representative::where('Company_Name', 'like', "%{$Company_Name}%")->where('Branch', 'like', "%{$Branch}%")->first();
            if ($latestAgent) {
                $latestAgent = $latestAgent->Profile_ID + 1;
            } else {
                // ถ้าไม่มี Guest ในฐานข้อมูล เริ่มต้นด้วย 1
                $latestAgent = 1;
            }
            $A_Profile = $latestAgent;
            $saveAgent = new representative();
            $saveAgent->Profile_ID = $A_Profile;
            $saveAgent->prefix = $request->Mprefix;
            $saveAgent->First_name = $request->first_nameAgent;
            $saveAgent->Last_name = $request->last_nameAgent;
            $countrydataA= $request->countrydataA;
            $provinceAgent= $request->provinceAgent;
            $amphuresA= $request->amphuresA;
            $TambonA= $request->TambonA;
            $zip_codeA= $request->zip_codeA;
            $cityA = $request->cityA;
            $addressAgent= $request->addressAgent;
            $EmailAgent= $request->EmailAgent;
            $NProfile_ID = $N_Profile;
            $ABranch = $request->Branch;
            $Company_Name = $request->Company_Name;
           $phone = $request->phone;
            if ($countrydataA == "Other_countries") {
                if ($cityA === null) {
                    return redirect()->back()->with('error', 'กรุณากรอกประเทศของคุณ');
                }else {
                    $saveAgent->City = $cityA;
                }
            }else {
                $saveAgent->Country = $countrydataA;
                $saveAgent->City = $provinceAgent;
                $saveAgent->Amphures = $amphuresA;
                $saveAgent->Address = $addressAgent;
                $saveAgent->Tambon = $TambonA;
                $saveAgent->Zip_Code = $zip_codeA;
                $saveAgent->Email = $EmailAgent;
                $saveAgent->Company_ID = $NProfile_ID;
                $saveAgent->Company_Name = $Company_Name;
                $saveAgent->Branch = $ABranch;
            }
            $saveAgent->save();
            foreach ($phone as $index => $phoneNumber) {
                if ($phoneNumber !== null) {
                    $savephoneA = new representative_phone();
                    $savephoneA->Profile_ID = $A_Profile;
                    $savephoneA->Company_ID = $NProfile_ID;
                    $savephoneA->Phone_number = $phoneNumber;
                    $savephoneA->Sequence = ($index === 0) ? 'main' : 'secondary'; // กำหนดค่า Sequence
                    $savephoneA->save();
                }
            }
            if ($savephoneA->save()) {
                return redirect()->route('Company.index')->with('alert_', 'บันทึกข้อมูลเรียบร้อย');
            } else {
                return redirect()->back()->with('error_', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
            }
        }
    }
    public function representative(Request $request)
    {
        $Company_Name = $request->Company_Name;
        $Branch = $request->Branch;
        $representative = representative::where('Company_Name', 'like', "%{$Company_Name}%")
        ->where('Branch', 'like', "%{$Branch}%")
        ->where('status', 1 )->first();
        if ($representative == null) {
            return response()->json([
                'representative' => $representative,
            ]);

        }else {
            $ID_ContactA = $representative->Profile_ID;
            $phone = representative_phone::where('Profile_ID', $ID_ContactA)->get();
        }

        $CompanyCount = representative::where('Company_Name', 'like', "%{$Company_Name}%")
            ->where('Branch', 'like', "%{$Branch}%")->where('status', 0 )
            ->count();
        if ($CompanyCount) {
            $CompanyCountA = $CompanyCount + 1;
        }else{
            $CompanyCountA = 1;
        }

        return response()->json([
            'representative' => $representative,
            'CompanyCountA' => $CompanyCountA,
            'phone' => $phone,


        ]);

    }
    public function Company_edit($id)
    {

        $Company = companys::find($id);

        $number =  preg_replace("/[^0-9]/", "", $Company->City);
         $Other_City =  preg_replace("/[^a-zA-Z]/", "", $Company->City);
         $provinceNames = province::select('name_th','id')->get();
         $Tambon = districts::where('amphure_id', $Company->Amphures)->select('name_th','id')->get();
         $amphures = amphures::where('province_id', $Company->City)->select('name_th','id')->get();
         $Zip_code = districts::where('amphure_id', $Company->Amphures)->select('zip_code','id')->get();

         $Company_Contact = representative_phone::find($id);
         $booking_channel = master_document::select('name_en', 'id')->where('status', 1)->Where('Category','Mbooking_channel')->get();
        $MCompany_type = master_document::select('name_th', 'id')->where('status', 1)->Where('Category','Mcompany_type')->get();
        $Mmarket = master_document::select('name_th', 'id')->where('status', 1)->Where('Category','Mmarket')->get();
        $Mprefix = master_document::select('name_th','id')->where('status', 1)->Where('Category','Mprefix')->get();
        $Profile_ID = $Company->Profile_ID;
        $phone = company_phone::where('Profile_ID', 'like', "%{$Profile_ID}%")->get();
        $phonecount = company_phone::where('Profile_ID', 'like', "%{$Profile_ID}%")->count();
        $phoneDataArray = $phone->toArray();

        $fax = company_fax::where('Profile_ID', 'like', "%{$Profile_ID}%")->get();
        $faxcount = company_fax::where('Profile_ID', 'like', "%{$Profile_ID}%")->count();
        $faxArray = $fax->toArray();


        return view('company.edit',compact('Company','booking_channel','provinceNames','Tambon','amphures',
        'Zip_code','Other_City','faxArray','phoneDataArray','Company_Contact','Mmarket',
        'MCompany_type','Mprefix','phonecount','faxcount','Profile_ID'));
    }

    public function Company_update(Request $request, $id) {
        $data = $request->all();
        $CountryOther = $request->countrydata;
        $Branch = $request->Branch;
        $province = $request->province;
        $amphures = $request->amphures;
        $Tambon = $request->Tambon;
        $zip_code = $request->zip_code;
        $city = $request->city;
        $Address= $request->address;
        $phone_company = $request->phone;
        $faxNumber = $request->fax;
        $save = companys::find($id);
        $save->Company_Name = $request->Company_Name;
        $save->Company_type = $request->Company_type;
        $save->Market =$request->Mmarket;
        $save->Booking_Channel = $request->booking_channel;
        if ($CountryOther == "Other_countries") {
            if ($city === null) {
                return redirect()->back()->with('error', 'กรุณากรอกประเทศของคุณ');
            }else {
                $save->City = $city;
            }
        }else {
            $save->Country = $CountryOther;
            $save->City = $province;
            $save->Amphures = $amphures;
            $save->Address = $Address;
            $save->Tambon = $Tambon;
            $save->Zip_Code = $zip_code;
            $save->Branch = $Branch;
        }
        $save->Company_Email = $request->Company_Email;
        $save->Company_Website = $request->Company_Website;
        $save->Taxpayer_Identification = $request->Taxpayer_Identification;
        $save->Discount_Contract_Rate = $request->Discount_Contract_Rate;
        $save->Contract_Rate_Start_Date = $request->contract_rate_start_date;
        $save->Contract_Rate_End_Date = $request->contract_rate_end_date;
        $save->Lastest_Introduce_By =$request->Lastest_Introduce_By;
        $save->save();

            $ID = companys::find($id);
            $fax = company_fax::find($id);
            $Profile_ID = $ID->Profile_ID;
            $companyPhones = Company_phone::where('Profile_ID', $Profile_ID)->get();
            $companyfax = company_fax::where('Profile_ID', $Profile_ID)->get();
            Company_phone::where('Profile_ID', $Profile_ID)->delete();
            company_fax::where('Profile_ID', $Profile_ID)->delete();
            foreach ($phone_company as $index => $phoneNumber) {
                if ($phoneNumber !== null) {
                    $savephone = new company_phone();
                    $savephone->Profile_ID = $Profile_ID;
                    $savephone->Phone_number = $phoneNumber;
                    $savephone->Sequence = ($index === 0) ? 'main' : 'secondary'; // กำหนดค่า Sequence
                    $savephone->save();
                }
            }
            foreach ($faxNumber as $index => $faxNumber) {
                if ($faxNumber !== null) {
                    $savefax = new company_fax();
                    $savefax->Profile_ID = $Profile_ID;
                    $savefax->Fax_number = $faxNumber;
                    $savefax->Sequence = ($index === 0) ? 'main' : 'secondary'; // กำหนดค่า Sequence
                    $savefax->save();
                }
            }

        if ($save->save()) {
            $Company = Companys::find($id); // สมมุติว่า $id คือ ID ของ Company ที่ต้องการ
            return redirect()->to(url('/Company/edit/'.$Company->id))->with('alert_', 'บันทึกข้อมูลเรียบร้อย');

        } else {
            return redirect()->back()->with('error_', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
    }



}
