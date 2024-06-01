<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\province;
use App\Models\amphures;
use App\Models\districts;
use App\Models\master_document;
use Carbon\Carbon;
use App\Models\master_quantity;
use App\Models\master_unit;
use App\Models\Freelancer_Member;
use App\Models\Freelancer_checked_phone;
use App\Models\master_product_item;
use App\Models\freelancer_com_massage;
use App\Models\freelancer_com_mfaxes;
use App\Models\freelancer_com_mphones;
use App\Models\freelancer_com_contents;
use App\Models\companys;
class FreelancerMemberController extends Controller
{
    public function index_member()
    {
        $Freelancer_checked = Freelancer_Member::query()->get();
        $Mbooking = master_document::select('name_en','id')->get();
        return view('freelancer_member.index',compact('Freelancer_checked','Mbooking'));
    }
    public function changeStatusmember(Request $request)
    {
        $id = $request->id;
        $status = $request->status; // รับค่า status ที่ส่งมาจาก Request

        $Freelancerstatus = Freelancer_Member::find($id);
        if ($status == 1 ) {
            $status = 0;
            $Freelancerstatus->status = $status;
        }elseif (($status == 0 )) {
            $status = 1;
            $Freelancerstatus->status = $status;
        }
        $Freelancerstatus->save();
    }
    public function ac(Request $request)
    {
        $ac = $request->value;
        if ($ac == 1 ) {
            $query = Freelancer_Member::query();
            $Freelancer_checked = $query->where('status', '1')->get();
        }
        return view('freelancer_member.index',compact('Freelancer_checked'));
    }
    public function no(Request $request)
    {
        $no = $request->value;
        if ($no == 0 ) {
            $query = Freelancer_Member::query();
            $Freelancer_checked = $query->where('status', '0')->get();
        }
        return view('freelancer_member.index',compact('Freelancer_checked'));
    }
    public function viewmember(Request $request ,$id)
    {
        $Freelancer_Main = Freelancer_Member::find($id);
        $number =  preg_replace("/[^0-9]/", "", $Freelancer_Main->City);
        $Other_City =  preg_replace("/[^a-zA-Z]/", "", $Freelancer_Main->City);
        $provinceNames = province::select('name_th','id')->get();
        $Tambon = districts::where('amphure_id', $Freelancer_Main->Amphures)->select('name_th','id')->get();
        $amphures = amphures::where('province_id', $Freelancer_Main->City)->select('name_th','id')->get();
        $Zip_code = districts::where('amphure_id', $Freelancer_Main->Amphures)->select('zip_code','id')->get();
        $Mbank = master_document::select('name_th','id')->Where('Category','Mbank')->get();
        $booking_channel = master_document::select('name_en', 'id')->where('status', 1)->Where('Category','Mbooking_channel')->get();
        $prefix = master_document::select('name_th','id')->where('status', 1)->Where('Category','Mprefix')->get();
        $Profile_ID = $Freelancer_Main->id;
        $Profile_member = $Freelancer_Main->Profile_ID;

        $phone = Freelancer_checked_phone::where('Profile_ID', 'like', "%{$Profile_ID}%")->get();
        $phonecount = Freelancer_checked_phone::where('Profile_ID', 'like', "%{$Profile_ID}%")->count();
        $birthday = Carbon::parse($Freelancer_Main->Birthday);
        $First_day_work = Carbon::parse($Freelancer_Main->First_day_work)->format('d-m-Y');
        $day = $birthday->format('d');
        $month =$birthday->format('M');
        $monthYear = $birthday->format('Y');
        $phoneArray = $phone->toArray();
        if($phoneArray !== null && count($phoneArray) > 0) {
            // ดึงค่าอาร์เรย์แต่ละอันมาใช้
            $phoneM= isset($phoneArray[0]) ? $phoneArray[0] : null;
            $phoneS1 = isset($phoneArray[1]) ? $phoneArray[1] : null;
            $phoneS2 = isset($phoneArray[2]) ? $phoneArray[2] : null;
            // ส่งข้อมูลไปยัง view
        }

       $Company_massage = freelancer_com_massage::where('Member_ID',$Profile_member)->get();
        return view('freelancer_member.view',compact('Freelancer_Main','Other_City','provinceNames','amphures','Tambon','Zip_code'
        ,'phoneM','phoneS1','phoneS2','booking_channel','prefix','Mbank','phonecount','day','monthYear','month','First_day_work','Company_massage'));
    }
    public function editmember($id)
    {
        $Freelancer_checked = Freelancer_Member::find($id);
        $number =  preg_replace("/[^0-9]/", "", $Freelancer_checked->City);
        $Other_City =  preg_replace("/[^a-zA-Z]/", "", $Freelancer_checked->City);
        $provinceNames = province::select('name_th','id')->get();
        $Tambon = districts::where('amphure_id', $Freelancer_checked->Amphures)->select('name_th','id')->get();
        $amphures = amphures::where('province_id', $Freelancer_checked->City)->select('name_th','id')->get();
        $Zip_code = districts::where('amphure_id', $Freelancer_checked->Amphures)->select('zip_code','id')->get();
        $Mbank = master_document::select('name_th','id')->Where('Category','Mbank')->get();
        $booking_channel = master_document::select('name_en', 'id')->where('status', 1)->Where('Category','Mbooking_channel')->get();
        $prefix = master_document::select('name_th','id')->where('status', 1)->Where('Category','Mprefix')->get();
        $Profile_ID = $Freelancer_checked->id;
        $phone = Freelancer_checked_phone::where('Profile_ID', 'like', "%{$Profile_ID}%")->get();
        $phonecount = Freelancer_checked_phone::where('Profile_ID', 'like', "%{$Profile_ID}%")->count();
        $phoneArray = $phone->toArray();


        return view('freelancer_member.edit',compact('Freelancer_checked','Other_City','provinceNames','amphures','Tambon','Zip_code'
        ,'phoneArray','booking_channel','prefix','Mbank','phonecount'));
    }

    public function updatefreelancermember(Request $request ,$id)
    {
       // dd($request->all());
        if ($request->hasFile('imageFile')) {
            $image = Freelancer_Member::find($id);
            $filePath = public_path($image->Imagefreelan);
            if (file_exists($filePath)) {
                unlink($filePath);
                // ลบไฟล์จากระบบไฟล์
            }
            $imageFile = $request->file('imageFile');
            $image_name_gen = hexdec(uniqid());
            $img_ext = strtolower($imageFile->getClientOriginalExtension());
            $img_name1 = $image_name_gen . '.' . $img_ext;
            $upload_location_image = 'image_freelancer/member/profile/';
            if (!file_exists($upload_location_image)) {
                // สร้างโฟลเดอร์ถ้ายังไม่มี
                mkdir($upload_location_image, 0777, true);
            }

            if (!is_writable($upload_location_image)) {
                // ให้สิทธิ์ในการเขียนไฟล์
                chmod($upload_location_image, 0777);
            }
            $full_path_image = $upload_location_image . $img_name1;
        }

        if ($request->hasFile('Identification_Number_file')) {
            $identification = Freelancer_Member::find($id);
            $filePath = public_path($identification->Identification_file);
            if (file_exists($filePath)) {
                unlink($filePath);
                // ลบไฟล์จากระบบไฟล์
            }
            $identification_file = $request->file('Identification_Number_file');
            $identification_name_gen = hexdec(uniqid());
            $img_ext = strtolower($identification_file->getClientOriginalExtension());
            $img_name2 = $identification_name_gen . '.' . $img_ext;
            $upload_location_identification = 'image_freelancer/member/Identification/';
            if (!file_exists($upload_location_identification)) {
                // สร้างโฟลเดอร์ถ้ายังไม่มี
                mkdir($upload_location_identification, 0777, true);
            }

            if (!is_writable($upload_location_identification)) {
                // ให้สิทธิ์ในการเขียนไฟล์
                chmod($upload_location_identification, 0777);
            }
            $full_path_identification = $upload_location_identification . $img_name2;
        }

        if ($request->hasFile('Bank_file')) {
            $bank = Freelancer_Member::find($id);
            $filePath = public_path($bank->Bank_file);
            if (file_exists($filePath)) {
                unlink($filePath);
                // ลบไฟล์จากระบบไฟล์
            }
            $bank_file = $request->file('Bank_file');
            $bank_name_gen = hexdec(uniqid());
            $img_ext = strtolower($bank_file->getClientOriginalExtension());
            $img_name3 = $bank_name_gen . '.' . $img_ext;
            $upload_location_bank = 'image_freelancer/member/Bank_file/';
            if (!file_exists($upload_location_bank)) {
                // สร้างโฟลเดอร์ถ้ายังไม่มี
                mkdir($upload_location_bank, 0777, true);
            }

            if (!is_writable($upload_location_bank)) {
                // ให้สิทธิ์ในการเขียนไฟล์
                chmod($upload_location_bank, 0777);
            }
            $full_path_bank = $upload_location_bank . $img_name3;
        }
        $phones = $request->phone;
        $province = $request->province;
        $Mbank = $request->Mbank;
        $amphures = $request->amphures;
        $Tambon = $request->Tambon;
        $Preface = $request->Preface;
        $zip_code = $request->zip_code;
        $Address = $request->address;
        $city = $request->city;
        $First_name = $request->first_name;
        $Last_name = $request->last_name;
        $Email = $request->email;
        $Country = $request->countrydata;
        $Prefix = $request->Preface;
        $Booking_Channel = implode(',', $request->booking_channel);

        $save = Freelancer_Member::find($id);
        $save->prefix = $Prefix;
        $save->First_name = $First_name;
        $save->Email = $Email;
        $save->Last_name = $Last_name;
        $save->Zip_Code = $zip_code;
        $save->Address = $Address;
        $save->Identification_number = $request->identification_number;
        $save->Mbank = $Mbank;
        $save->Booking_Channel = $Booking_Channel;
        $save->Bank_Number = $request->Bank_number;
        $save->Bank_account_Name = $request->Account_Name;
        $save->Birthday = $request->Birthday;
        $save->First_day_work = $request->First_day_work;
        $save->Imagefreelan = $full_path_image ?? $save->Imagefreelan;
        $save->Identification_file = $full_path_identification ?? $save->Identification_file;
        $save->Bank_file = $full_path_bank ?? $save->Bank_file;

        if ($Country == "Other_countries") {
            if ($city === null) {
                return redirect()->back()->with('error', 'กรุณากรอกประเทศของคุณ');
            } else {
                $save->City = $city;
            }
        } else {
            $save->Country = $Country;
            $save->City = $province;
            $save->Amphures = $amphures;
            $save->Tambon = $Tambon;
        }
        $save->save(); // Save the data before deleting phones



        $deletePhone = Freelancer_Member::find($id);
        if ($deletePhone) {
            $Profile_ID = $deletePhone->Profile_ID;
            $profilePhones = Freelancer_checked_phone::where('Profile_ID', $Profile_ID)->get();
            foreach ($profilePhones as $phone) {
                $phone->delete();
            }
        } else {
            return redirect()->back()->with('error', 'ไม่พบข้อมูล Freelancer_checked');
        }
        $Profile_ID = $deletePhone->Profile_ID;
        foreach ($phones as $index => $phoneNumber) {
            if ($phoneNumber !== null) {
                $savephone = new Freelancer_checked_phone();
                $savephone->Profile_ID = $Profile_ID;
                $savephone->Phone_number = $phoneNumber;
                $savephone->Sequence = ($index === 0) ? 'main' : 'secondary'; // กำหนดค่า Sequence
                $savephone->save();
            }
        }

        if ($savephone->save()) {
            // ย้ายไฟล์ imageFile ถ้าไม่เป็น null
            if ($imageFile ?? false) {
                $imageFile->move($upload_location_image, $img_name1);
            }
            // ย้ายไฟล์ identification_file ถ้าไม่เป็น null
            if ($identification_file ?? false) {
                $identification_file->move($upload_location_identification, $img_name2);
            }
            // ย้ายไฟล์ bank_file ถ้าไม่เป็น null
            if ($bank_file ?? false) {
                $bank_file->move($upload_location_bank, $img_name3);
            }
            return redirect()->to(url('/Freelancer/member/view/'.$save->id))->with('alert_', 'บันทึกข้อมูลเรียบร้อย');
        } else {
            return redirect()->back()->with('error_', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
    }
    public function order_list($id)
    {
        $Freelancer_member = Freelancer_Member::find($id);
        $Mmarket = master_document::select('name_th', 'id')->where('status', 1)->Where('Category','Mmarket')->get();
        $provinceNames = province::select('name_th','id')->get();
        $booking_channel = master_document::select('name_en', 'id')->where('status', 1)->Where('Category','Mbooking_channel')->get();
        $room_type = master_product_item::orderBy('Product_ID', 'asc')->where('status',1)->where('Category','Room_Type')->get();
        $Banquet =  master_product_item::orderBy('Product_ID', 'asc')->where('status',1)->where('Category','Banquet')->get();
        $Meals =  master_product_item::orderBy('Product_ID', 'asc')->where('status',1)->where('Category','Meals')->get();
        $Entertainment = master_product_item::orderBy('Product_ID', 'asc')->where('status',1)->where('Category','Entertainment')->get();
        $Company = companys::where('status',1)->get();
        $unit = master_unit::where('status',1)->get();
        $quantity = master_quantity::where('status',1)->get();
        return view('freelancer_member.order_list',compact('room_type','Banquet','Meals','Entertainment','Company','Mmarket','booking_channel','provinceNames','Freelancer_member'
    ,'unit','quantity'));
    }
    public function getRepresentative(Request $request)
    {
        $profileId = $request->input('profileId');
        $representative = Representative::where('Company_ID', $profileId)->first();
        $First_name =$representative->First_name;
        $Last_name = $representative->Last_name;
        $contact = $First_name.' '.$Last_name;
        $companys = companys::where('Profile_ID', $profileId)->first();
        $Email = $companys->Company_Email;
        return response()->json([
            'name' =>  $contact,
            'Email'=> $Email
             // ส่งชื่อตัวแทนกลับไปให้ JavaScript
        ]);
    }
    public function order_listsave(Request $request ,$id)
    {

        $data = $request->all();
        $Freelancer_member = Freelancer_Member::find($id);
        $Profile_member = $Freelancer_member->Profile_ID;
        $Company_Name =$request->Company_Name;
        $Branch = $request->Branch;
        $Contact_Name = $request->Contact_Name;
        $Mmarket = $request->Mmarket;
        $booking_channel = $request->booking_channel;
        $Country = $request->countrydata;
        $Address = $request->address;
        $city = $request->city;
        $province =$request->province;
        $amphures = $request->amphures;
        $Tambon = $request->Tambon;
        $zip_code= $request->zip_code;
        $Check_In_date= $request->Check_In_date;
        $Check_Out_date= $request->Check_Out_date;
        $Pax= $request->Pax;
        $Email= $request->Email;
        $Company_Website= $request->Company_Website;
        $Taxpayer_Identification= $request->Taxpayer_Identification;
        //------------------Product---------------------//
        $RoomID= $request->RoomID;
        $countroom= $request->countroom;
        $BanquetID= $request->BanquetID;
        $countBanquet= $request->countBanquet;
        $MealsID= $request->MealsID;
        $countMeals= $request->countMeals;
        $EntertainmentID= $request->EntertainmentID;
        $countEntertainment= $request->countEntertainment;
        $Company_massage = freelancer_com_massage::latest('id')->first();
        if ($Company_massage) {
            $Profile_ID = $Company_massage->id + 1;
        } else {
            // ถ้าไม่มี Guest ในฐานข้อมูล เริ่มต้นด้วย 1
            $Profile_ID = 1;
        }
        $save = new freelancer_com_massage();
        $save->Profile_ID = $Profile_ID;
        $save->Member_ID = $Profile_member;
        $save->Company_Name =$request->Company_Name;
        $save->Branch = $request->Branch;
        $save->Contact_Name = $request->Contact_Name;
        $save->Market = $request->Mmarket;
        $save->Booking_Channel = $request->booking_channel;
        $save->Check_In_Date = $request->Check_In_date;
        $save->Check_Out_Date = $request->Check_Out_date;
        $save->Pax = $request->Pax;
        $save->Company_Email = $request->Email;
        $save->Company_Website = $request->Company_Website;
        $save->Taxpayer_Identification = $request->Taxpayer_Identification;
        if ($Country == "Other_countries") {
            if ($city === null) {
                return redirect()->back()->with('error', 'กรุณากรอกประเทศของคุณ');
            } else {
                $save->City = $request->city;
            }
        } else {
            $save->Country = $Country;
            $save->City = $request->province;
            $save->Amphures = $request->amphures;
            $save->Tambon = $request->Tambon;
            $save->Zip_Code= $request->zip_code;
            $save->Address = $request->address;
        }

        //------------phone------------//
        $MProfil = $save->Profile_ID;
        $phones = $request->phone_company;
        foreach ($phones as $index => $phoneNumber) {
            if ($phoneNumber !== null) {
                $savephone = new freelancer_com_mphones();
                $savephone->Profile_ID = $MProfil;
                $savephone->Phone_number = $phoneNumber;
                $savephone->Sequence = ($index === 0) ? 'main' : 'secondary'; // กำหนดค่า Sequence
                $savephone->save();
            }
        }
        //------------fax--------------//
        $fax = $request->fax;
        foreach ($fax as $index => $faxNumber) {
            if ($faxNumber !== null) {
                $savefax = new freelancer_com_mfaxes();
                $savefax->Profile_ID = $MProfil;
                $savefax->Fax_number = $faxNumber;
                $savefax->Sequence = ($index === 0) ? 'main' : 'secondary'; // กำหนดค่า Sequence
                $savefax->save();
            }
        }
        $rooms = $request->input('RoomID');
        $roomCounts = $request->input('countroom');
        if ($rooms !== null) {
            foreach ($rooms as $index => $roomId) {
                $saveRoom = new freelancer_com_contents();
                $saveRoom->Profile_ID = $MProfil;
                $saveRoom->Product_ID = $roomId;
                $saveRoom->Quantity = $roomCounts[$index];
                $saveRoom->save();
            }
        }

        // จัดการ BanquetID
        $banquets = $request->input('BanquetID');
        $banquetCounts = $request->input('countBanquet');
        if ($banquets !== null) {
            foreach ($banquets as $index => $banquetId) {
                $saveBanquet = new freelancer_com_contents();
                $saveBanquet->Profile_ID = $MProfil;
                $saveBanquet->Product_ID = $banquetId;
                $saveBanquet->Quantity = $banquetCounts[$index];
                $saveBanquet->save();
            }
        }

        // จัดการ MealsID
        $meals = $request->input('MealsID');
        $mealCounts = $request->input('countMeals');
        if ($meals !== null) {
            foreach ($meals as $index => $mealId) {
                $saveMeal = new freelancer_com_contents();
                $saveMeal->Profile_ID = $MProfil;
                $saveMeal->Product_ID = $mealId;
                $saveMeal->Quantity = $mealCounts[$index];
                $saveMeal->save();
            }
        }

        // จัดการ EntertainmentID
        $entertainments = $request->input('EntertainmentID');
        $entertainmentCounts = $request->input('countEntertainment');
        if ($entertainments !== null) {
            foreach ($entertainments as $index => $entertainmentId) {
                $saveEntertainment = new freelancer_com_contents();
                $saveEntertainment->Profile_ID = $MProfil;
                $saveEntertainment->Product_ID = $entertainmentId;
                $saveEntertainment->Quantity = $entertainmentCounts[$index];
                $saveEntertainment->save();
            }
        }
        $save->save();
        if ($save->save()) {
            return redirect()->to(url('/Freelancer/member/view/'.$Freelancer_member->id))->with('alert_', 'ส่งข้อมูลเรียบร้อย');
        } else {
            return redirect()->back()->with('error_', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
    }
    public function viewdatamember($Freeid,$Comid)
    {
        $memberid = $Freeid;
        $id = $Comid;
        $viewComMassage = freelancer_com_massage::find($id);
        $provinceNames = province::select('name_th','id')->get();
        $Tambon = districts::where('amphure_id', $viewComMassage->Amphures)->select('name_th','id')->get();
        $amphures = amphures::where('province_id', $viewComMassage->City)->select('name_th','id')->get();
        $Zip_code = districts::where('amphure_id', $viewComMassage->Amphures)->select('zip_code','id')->get();
        $phone = freelancer_com_mphones::where('Profile_ID', 'like', "%{$id}%")->get();
        $phoneArray = $phone->toArray();
        $phoneM= isset($phoneArray[0]) ? $phoneArray[0] : null;
        $fax = freelancer_com_mfaxes::where('Profile_ID', 'like', "%{$id}%")->get();
        $faxArray = $fax->toArray();
        $faxM= isset($faxArray[0]) ? $faxArray[0] : null;
        $Check_In_Date = Carbon::parse($viewComMassage->Check_In_Date)->format('d/m/Y');
        $Check_Out_Date = Carbon::parse($viewComMassage->Check_Out_Date)->format('d/m/Y');
        $Freelancer_Main = Freelancer_Member::find($memberid);
        $First_name = $Freelancer_Main->First_name;
        $Last_name = $Freelancer_Main->Last_name;
        $member_name = $First_name.' '.$Last_name;
        $viewComcontent = freelancer_com_contents::where('Profile_ID',$id)->get();
        return view('freelancer_member.viewdata',compact('viewComMassage','provinceNames','Tambon','amphures','Zip_code','phoneM','faxM','Check_In_Date','Check_Out_Date','member_name','viewComcontent','Freelancer_Main'));
    }
    public function examine()
    {
        $Company_massage = freelancer_com_massage::query()->get();
        return view('bossapprove.allmessage',compact('Company_massage'));
    }
    public function viewdataexamine($id)
    {
        $viewComMassage = freelancer_com_massage::find($id);
        $datamassage = $viewComMassage->id;
        $memberid= $viewComMassage->Member_ID;
        $provinceNames = province::select('name_th','id')->get();
        $Tambon = districts::where('amphure_id', $viewComMassage->Amphures)->select('name_th','id')->get();
        $amphures = amphures::where('province_id', $viewComMassage->City)->select('name_th','id')->get();
        $Zip_code = districts::where('amphure_id', $viewComMassage->Amphures)->select('zip_code','id')->get();
        $phone = freelancer_com_mphones::where('Profile_ID', 'like', "%{$id}%")->get();
        $phoneArray = $phone->toArray();
        $phoneM= isset($phoneArray[0]) ? $phoneArray[0] : null;
        $fax = freelancer_com_mfaxes::where('Profile_ID', 'like', "%{$id}%")->get();
        $faxArray = $fax->toArray();
        $faxM= isset($faxArray[0]) ? $faxArray[0] : null;
        $Check_In_Date = Carbon::parse($viewComMassage->Check_In_Date)->format('d/m/Y');
        $Check_Out_Date = Carbon::parse($viewComMassage->Check_Out_Date)->format('d/m/Y');
        $Freelancer_Main = Freelancer_Member::where('Profile_ID','like', "%{$memberid}%")->first();
        $First_name = $Freelancer_Main->First_name;
        $Last_name = $Freelancer_Main->Last_name;
        $member_name = $First_name.' '.$Last_name;
        $viewComcontent = freelancer_com_contents::where('Profile_ID',$id)->get();
        return view('bossapprove.viewdatamessage',compact('viewComMassage','datamassage','provinceNames','Tambon','amphures','Zip_code','phoneM','faxM','Check_In_Date','Check_Out_Date','member_name','viewComcontent','Freelancer_Main'));
    }
    public function examinestatus($id)
    {

        $status = 1; // 1 คือค่าที่bossกดยืนยัน
        $save = freelancer_com_massage::find($id);
        $save->status = $status;
        $save->save();
        if ($save->save()) {
            return redirect()->route('freelancer.boss.examine')->with('alert_', 'ส่งข้อมูลเรียบร้อย');
        } else {
            return redirect()->back()->with('error_', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
    }
    public function examineemployee()
    {
        $Company_massage = freelancer_com_massage::whereIn('status', [1, 2])->get();
        return view('employee.allmessageemployee',compact('Company_massage'));
    }
    public function viewdataexamineemployee($id)
    {
        $viewComMassage = freelancer_com_massage::find($id);
        $datamassage = $viewComMassage->id;
        $memberid= $viewComMassage->Member_ID;
        $provinceNames = province::select('name_th','id')->get();
        $Tambon = districts::where('amphure_id', $viewComMassage->Amphures)->select('name_th','id')->get();
        $amphures = amphures::where('province_id', $viewComMassage->City)->select('name_th','id')->get();
        $Zip_code = districts::where('amphure_id', $viewComMassage->Amphures)->select('zip_code','id')->get();
        $phone = freelancer_com_mphones::where('Profile_ID', 'like', "%{$id}%")->get();
        $phoneArray = $phone->toArray();
        $phoneM= isset($phoneArray[0]) ? $phoneArray[0] : null;
        $fax = freelancer_com_mfaxes::where('Profile_ID', 'like', "%{$id}%")->get();
        $faxArray = $fax->toArray();
        $faxM= isset($faxArray[0]) ? $faxArray[0] : null;
        $Check_In_Date = Carbon::parse($viewComMassage->Check_In_Date)->format('d/m/Y');
        $Check_Out_Date = Carbon::parse($viewComMassage->Check_Out_Date)->format('d/m/Y');
        $Freelancer_Main = Freelancer_Member::where('Profile_ID','like', "%{$memberid}%")->first();
        $First_name = $Freelancer_Main->First_name;
        $Last_name = $Freelancer_Main->Last_name;
        $member_name = $First_name.' '.$Last_name;
        $viewComcontent = freelancer_com_contents::where('Profile_ID',$id)->get();
        return view('employee.viewdatamessageemployee',compact('viewComMassage','datamassage','provinceNames','Tambon','amphures','Zip_code','phoneM','faxM','Check_In_Date','Check_Out_Date','member_name','viewComcontent','Freelancer_Main'));
    }
    public function examinestatusemployee($id)
    {
        $status = 2;
        $Operated_by = 'นาย A' ;// 1 คือค่าที่bossกดยืนยัน
        $save = freelancer_com_massage::find($id);
        $save->status = $status;
        $save->Operated_by = $Operated_by;
        $save->save();
        if ($save->save()) {
            return redirect()->route('freelancer.employee.examine')->with('alert_', 'ส่งข้อมูลเรียบร้อย');
        } else {
            return redirect()->back()->with('error_', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
    }
}
