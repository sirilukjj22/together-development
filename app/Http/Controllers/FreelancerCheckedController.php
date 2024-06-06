<?php

namespace App\Http\Controllers;
use App\Models\province;
use App\Models\amphures;
use App\Models\districts;
use App\Models\master_document;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\master_quantity;
use App\Models\master_unit;
use App\Models\Freelancer_checked;
use App\Models\Freelancer_checked_phone;
use App\Models\Freelancer_Member;
class FreelancerCheckedController extends Controller
{
    public function index()
    {
        $Freelancer_checked = Freelancer_checked::query()->get();
        $Mbooking = master_document::select('name_en','id')->get();
        return view('freelancer_checked.index',compact('Freelancer_checked','Mbooking'));
    }
    public function create()
    {
        $provinceNames = province::select('name_th','id')->get();
        $booking_channel = master_document::select('name_en', 'id')->where('status', 1)->Where('Category','Mbooking_channel')->get();
        $prefix = master_document::select('name_th','id')->Where('Category','Mprefix')->where('status', 1)->get();
        $Mbank = master_document::select('name_th','id')->Where('Category','Mbank')->where('status', 1)->get();
        return view('freelancer_checked.create',compact('provinceNames','booking_channel','prefix','Mbank'));
    }
    public function edit($id)
    {
        $Freelancer_checked = Freelancer_checked::find($id);
        $number =  preg_replace("/[^0-9]/", "", $Freelancer_checked->City);
        $Other_City =  preg_replace("/[^a-zA-Z]/", "", $Freelancer_checked->City);
        $provinceNames = province::select('name_th','id')->get();
        $Tambon = districts::where('amphure_id', $Freelancer_checked->Amphures)->select('name_th','id')->get();
        $amphures = amphures::where('province_id', $Freelancer_checked->City)->select('name_th','id')->get();
        $Zip_code = districts::where('amphure_id', $Freelancer_checked->Amphures)->select('zip_code','id')->get();
        $Mbank = master_document::select('name_th','id')->Where('Category','Mbank')->where('status', 1)->get();
        $booking_channel = master_document::select('name_en', 'id')->where('status', 1)->Where('Category','Mbooking_channel')->get();
        $prefix = master_document::select('name_th','id')->where('status', 1)->Where('Category','Mprefix')->get();
        $Profile_ID = $Freelancer_checked->id;
        $phone = Freelancer_checked_phone::where('Profile_ID', 'like', "%{$Profile_ID}%")->get();
        $phonecount = Freelancer_checked_phone::where('Profile_ID', 'like', "%{$Profile_ID}%")->count();
        $phoneArray = $phone->toArray();
        return view('freelancer_checked.edit',compact('Freelancer_checked','Other_City','provinceNames','amphures','Tambon','Zip_code'
        ,'phoneArray','booking_channel','prefix','Mbank','phonecount'));
    }
    public function view($id)
    {
        $Freelancer_checked = Freelancer_checked::find($id);
        $number =  preg_replace("/[^0-9]/", "", $Freelancer_checked->City);
        $Other_City =  preg_replace("/[^a-zA-Z]/", "", $Freelancer_checked->City);
        $provinceNames = province::select('name_th','id')->get();
        $Tambon = districts::where('amphure_id', $Freelancer_checked->Amphures)->select('name_th','id')->get();
        $amphures = amphures::where('province_id', $Freelancer_checked->City)->select('name_th','id')->get();
        $Zip_code = districts::where('amphure_id', $Freelancer_checked->Amphures)->select('zip_code','id')->get();
        $Mbank = master_document::select('name_th','id')->Where('Category','Mbank')->where('status', 1)->get();
        $booking_channel = master_document::select('name_en', 'id')->where('status', 1)->Where('Category','Mbooking_channel')->get();
        $prefix = master_document::select('name_th','id')->where('status', 1)->Where('Category','Mprefix')->get();
        $Profile_ID = $Freelancer_checked->id;
        $phone = Freelancer_checked_phone::where('Profile_ID', 'like', "%{$Profile_ID}%")->get();
        $phonecount = Freelancer_checked_phone::where('Profile_ID', 'like', "%{$Profile_ID}%")->count();

        $phoneArray = $phone->toArray();


        return view('freelancer_checked.view',compact('Freelancer_checked','Other_City','provinceNames','amphures','Tambon','Zip_code'
        ,'booking_channel','prefix','Mbank','phonecount','phoneArray'));
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
    public function savefreelancercheck(Request $request)
    {


        $image = $request->file('imageFile');
        $image_name_gen = hexdec(uniqid());
        $img_ext = strtolower($image->getClientOriginalExtension());
        $img_name1 = $image_name_gen . '.' . $img_ext;
        $upload_location_image = 'image_freelancer/check/profile/';
        if (!file_exists($upload_location_image)) {
            // สร้างโฟลเดอร์ถ้ายังไม่มี
            mkdir($upload_location_image, 0777, true);
        }

        if (!is_writable($upload_location_image)) {
            // ให้สิทธิ์ในการเขียนไฟล์
            chmod($upload_location_image, 0777);
        }
        $full_path_image = $upload_location_image . $img_name1;

        if ($request->hasFile('Identification_Number_file')) {
            $identification_file = $request->file('Identification_Number_file');
            $identification_name_gen = hexdec(uniqid());
            $img_ext = strtolower($identification_file->getClientOriginalExtension());
            $img_name2 = $identification_name_gen . '.' . $img_ext;
            $upload_location_identification = 'image_freelancer/check/Identification/';
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
            $bank_file = $request->file('Bank_file');
            $bank_name_gen = hexdec(uniqid());
            $img_ext = strtolower($bank_file->getClientOriginalExtension());
            $img_name3 = $bank_name_gen . '.' . $img_ext;
            $upload_location_bank = 'image_freelancer/check/Bank_file/';
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
        $Address = $request->address;
        $Email = $request->email;
        $Country =$request->countrydata;
        $Prefix = $request->Preface;
        $Booking_Channel =  implode(',',$request->booking_channel);

        $save = new Freelancer_checked();
        $save->prefix = $Prefix;
        $save->First_name=$First_name;
        $save->Email=$Email;
        $save->Last_name=$Last_name;
        $save->Zip_Code=$zip_code;
        $save->Address=$Address;
        $save->Identification_number=$request->identification_number;
        $save->Mbank=$Mbank;
        $save->Booking_Channel=$Booking_Channel;
        $save->Bank_Number =$request->Bank_number;
        $save->Bank_account_Name =$request->Account_Name;
        $save->Imagefreelan =$full_path_image;
        $save->Identification_file =$full_path_identification;
        $save->Bank_file =$full_path_bank;
        $save->Birthday = $request->Birthday;
        $save->First_day_work = $request->First_day_work;
        if ($Country == "Other_countries") {
            if ($city === null) {
                return redirect()->back()->with('error', 'กรุณากรอกประเทศของคุณ');
            }else {
                $save->City = $city;
            }
        }else {
            $save->Country = $Country;
            $save->City = $province;
            $save->Amphures = $amphures;
            $save->Tambon = $Tambon;
        }
         $save->save();
         $idfreeland = $save->id;
        foreach ($phones as $index => $phoneNumber) {
            if ($phoneNumber !== null) {
                $savephone = new Freelancer_checked_phone();
                $savephone->Profile_ID = $idfreeland;
                $savephone->Phone_number = $phoneNumber;
                $savephone->Sequence = ($index === 0) ? 'main' : 'secondary'; // กำหนดค่า Sequence
                $savephone->save();
            }
        }
        if ($savephone->save()) {
            $image->move($upload_location_image,$img_name1);
            $identification_file->move($upload_location_identification,$img_name2);
            $bank_file->move($upload_location_bank,$img_name3);
            return redirect()->route('freelancer.index')->with('alert_', 'บันทึกข้อมูลเรียบร้อย');
        } else {
            return redirect()->back()->with('error_', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
    }
    public function updatefreelancercheck(Request $request ,$id)
    {

        if ($request->hasFile('imageFile')) {
            $image = Freelancer_checked::find($id);
            $filePath = public_path($image->Imagefreelan);
            if (file_exists($filePath)) {
                unlink($filePath);
                // ลบไฟล์จากระบบไฟล์
            }
            $imageFile = $request->file('imageFile');
            $image_name_gen = hexdec(uniqid());
            $img_ext = strtolower($imageFile->getClientOriginalExtension());
            $img_name1 = $image_name_gen . '.' . $img_ext;
            $upload_location_image = 'image/member/profile/';
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
            $identification = Freelancer_checked::find($id);
            $filePath = public_path($identification->Identification_file);
            if (file_exists($filePath)) {
                unlink($filePath);
                // ลบไฟล์จากระบบไฟล์
            }
            $identification_file = $request->file('Identification_Number_file');
            $identification_name_gen = hexdec(uniqid());
            $img_ext = strtolower($identification_file->getClientOriginalExtension());
            $img_name2 = $identification_name_gen . '.' . $img_ext;
            $upload_location_identification = 'image/member/Identification/';
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
            $bank = Freelancer_checked::find($id);
            $filePath = public_path($bank->Bank_file);
            if (file_exists($filePath)) {
                unlink($filePath);
                // ลบไฟล์จากระบบไฟล์
            }
            $bank_file = $request->file('Bank_file');
            $bank_name_gen = hexdec(uniqid());
            $img_ext = strtolower($bank_file->getClientOriginalExtension());
            $img_name3 = $bank_name_gen . '.' . $img_ext;
            $upload_location_bank = 'image/member/Bank_file/';
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

        $save = Freelancer_checked::find($id);
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

        $idfreeland = $save->id;

        $deletePhone = Freelancer_checked::find($id);
        if ($deletePhone) {
            $Profile_ID = $deletePhone->id;
            $profilePhones = Freelancer_checked_phone::where('Profile_ID', $Profile_ID)->get();
            foreach ($profilePhones as $phone) {
                $phone->delete();
            }
        } else {
            return redirect()->back()->with('error', 'ไม่พบข้อมูล Freelancer_checked');
        }

        foreach ($phones as $index => $phoneNumber) {
            if ($phoneNumber !== null) {
                $savephone = new Freelancer_checked_phone();
                $savephone->Profile_ID = $idfreeland;
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
            return redirect()->route('freelancer.index')->with('alert_', 'บันทึกข้อมูลเรียบร้อย');
        } else {
            return redirect()->back()->with('error_', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
    }

    public function changeStatus(Request $request)
    {
        $id = $request->id;
        $status = $request->status; // รับค่า status ที่ส่งมาจาก Request

        $Freelancer_checked = Freelancer_checked::find($id);

        if ($Freelancer_checked) {
            // อัปเดตสถานะ
            $Freelancer_checked->status = $status == 1 ? 0 : 1;
            $Freelancer_checked->save();

            // ตรวจสอบสถานะใหม่
            if ($Freelancer_checked->status == 1) {
                $latestGuest = Freelancer_Member::latest('id')->first();
                $Profile_ID = $latestGuest ? $latestGuest->id + 1 : 1;
                $N_Profile = "F-" . $Profile_ID;

                // สร้างข้อมูลใหม่ในตาราง Freelancer_Main
                $newData = new Freelancer_Member();
                $newData->Profile_ID = $N_Profile;
                $newData->prefix = $Freelancer_checked->prefix;
                $newData->First_name = $Freelancer_checked->First_name;
                $newData->Last_name = $Freelancer_checked->Last_name;
                $newData->Booking_Channel = $Freelancer_checked->Booking_Channel;
                $newData->Country = $Freelancer_checked->Country;
                $newData->City = $Freelancer_checked->City;
                $newData->Amphures = $Freelancer_checked->Amphures;
                $newData->Tambon = $Freelancer_checked->Tambon;
                $newData->Address = $Freelancer_checked->Address;
                $newData->Zip_Code = $Freelancer_checked->Zip_Code;
                $newData->Email = $Freelancer_checked->Email;
                $newData->Identification_number = $Freelancer_checked->Identification_number;
                $newData->Bank_number = $Freelancer_checked->Bank_number;
                $newData->Bank_account_Name = $Freelancer_checked->Bank_account_Name;
                $newData->Mbank = $Freelancer_checked->Mbank;
                $newData->Birthday = $Freelancer_checked->Birthday;
                $newData->First_day_work = $Freelancer_checked->First_day_work;
                $newData->Imagefreelan = $Freelancer_checked->Imagefreelan;
                $newData->Identification_file = $Freelancer_checked->Identification_file;
                $newData->Bank_file = $Freelancer_checked->Bank_file;
                $newData->save();

                // อัปเดต Profile_ID ในตาราง Freelancer_checked_phone
                Freelancer_checked_phone::where('Profile_ID', $Freelancer_checked->id)
                    ->update(['Profile_ID' => $N_Profile]);
            }
            $Freelancer_checked->delete();
            return redirect()->route('freelancer_member.index')->with('alert_', 'บันทึกข้อมูลเรียบร้อย');
        } else {
            return redirect()->back()->with('error', 'ไม่พบข้อมูล Freelancer_checked');
        }
    }
}
