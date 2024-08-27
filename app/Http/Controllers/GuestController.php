<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Guest;
use App\Models\province;
use App\Models\phone_guest;
use App\Models\amphures;
use App\Models\districts;
use App\Models\master_document;
use App\Models\log_company;
use Auth;
class GuestController extends Controller
{
    public function index()
    {
        $Guest = Guest::query()->get();
        $Mbooking = master_document::select('name_en','id')->get();
        return view('guest.index',compact('Guest','Mbooking'));
    }
    public function paginate_table(Request $request)
    {


        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $data_query = Guest::query()->get();
        $data = [];

        $page_1 = $request->page == 1 ? 1 : ($request->page - 1).'1';
        $page_2 = $request->page.'0';

        $perPage2 = $request->perPage > 10 ? $request->perPage : 10;
        $path = "/guest/edit/";

        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $btn_action = "";
                if (($key + 1) >= (int)$page_1 && ($key + 1) <= (int)$page_2 || (int)$perPage > 10 && $key < (int)$perPage2) {
                    $btn_action .='<div class="dropdown">';
                    $btn_action .='<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">ทำรายการ &nbsp;
                    </button>';
                    $btn_action .='<ul dropdown-menu border-0 shadow p-3>';
                    $btn_action .=' <li class="dropdown-item py-2 rounded">ดูรายละเอียด</li>
                                    <li class="dropdown-item py-2 rounded"  href="{{ url('.$path.''.$value->id.') }}">แก้ไขรายการ</li>';
                    $btn_action .='</ul>';
                    $btn_action .='</div>';

                    $data[] = [
                        'id' => $key + 1,
                        'Profile_ID'=>$value->Profile_ID,
                        'name'=>$value->First_name.$value->Last_name,
                        'Booking_Channel'=>$value->Booking_Channel,
                        'status'=>$value->status,
                        'btn_action' => $btn_action,
                    ];
                }
            }
        }
        // dd($data);
        return response()->json([
            'data' => $data,
        ]);

    }
    public function create()
    {
        $latestGuest = Guest::latest('id')->first();
        if ($latestGuest) {
            $Profile_ID = $latestGuest->id + 1;
        } else {
            // ถ้าไม่มี Guest ในฐานข้อมูล เริ่มต้นด้วย 1
            $Profile_ID = 1;
        }
        $Id_profile ="G-";
        $N_Profile = $Id_profile.$Profile_ID;
        $provinceNames = province::select('name_th','id')->get();
        $booking_channel = master_document::select('name_en', 'id')->where('status', 1)->Where('Category','Mbooking_channel')->get();
        $prefix = master_document::select('name_th','id')->Where('Category','Mprename')->where('status',1)->get();
        return view('guest.create',compact('provinceNames','booking_channel','prefix','N_Profile'));
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


    public function guestsave(Request $request){
        $data = $request->all();
        $latestGuest = Guest::latest('id')->first();
        if ($latestGuest) {
            $Profile_ID = $latestGuest->id + 1;
        } else {
            // ถ้าไม่มี Guest ในฐานข้อมูล เริ่มต้นด้วย 1
            $Profile_ID = 1;
        }
        $Id_profile ="G-";
        $N_Profile = $Id_profile.$Profile_ID;
        $province = $request->province;
        $Preface = $request->Preface;
        $amphures = $request->amphures;
        $Tambon = $request->Tambon;
        $zip_code = $request->zip_code;
        $Booking_Channel =  implode(',',$request->booking_channel);
        $booking_channel = $request->booking_channel;
        $First_name = $request->first_name;
        $Last_name = $request->last_name;
        $CountryOther = $request->countrydata;
        $city = $request->city;
        $Address = $request->address;
        $Email = $request->email;
        $identificationnumber = $request->identification_number;
        $Contract_Rate_Start_Date = $request->contract_rate_start_date;
        $Contract_Rate_End_Date = $request->contract_rate_end_date;
        $Discount_Contract_Rate = $request->discount_contract_rate;
        $Lastest_Introduce_By = $request->latest_introduced_by;
        $phones = $request->input('phone');

        {
            if ($Preface && $First_name && $Last_name) {
                $Mprefix = master_document::where('id', $Preface)->where('Category', 'Mprename')->first();
                if ($Mprefix) {
                    if ($Mprefix->name_th == "นาย") {
                        $comtypefullname = "นาย " . $First_name . ' ' . $Last_name;
                    } elseif ($Mprefix->name_th == "นาง") {
                        $comtypefullname = "นาง " . $First_name . ' ' . $Last_name;
                    } elseif ($Mprefix->name_th == "นางสาว") {
                        $comtypefullname = "นางสาว " . $First_name . ' ' . $Last_name;
                    }
                }
            } elseif ($Preface > 30) {
                $Mprefix = master_document::where('id', $Preface)->where('Category', 'Mprename')->first();
                if ($Mprefix) {
                    $prename = $Mprefix->name_th;
                    $comtypefullname = 'คำนำหน้า : ' . $prename;
                }
            } elseif ($First_name && $Last_name) {
                $comtypefullname = 'ชื่อ : ' . $First_name . ' ' . $Last_name;
            } elseif ($First_name) {
                $comtypefullname = 'ชื่อ : ' . $First_name;
            } elseif ($Last_name) {
                $comtypefullname = 'นามสกุล : ' . $Last_name;
            }
            if ($CountryOther == 'Thailand') {
                $provinceNames = province::where('id', $city)->first();
                $TambonID = districts::where('id',$Tambon)->select('name_th','id','zip_code')->first();
                $amphuresID = amphures::where('id',$amphures)->select('name_th','id')->first();
                $provinceNames = $provinceNames->name_th;
                $Tambon = $TambonID->name_th;
                $amphures = $amphuresID->name_th;
                $Zip_code = $TambonID->zip_code;
                $AddressIndividual = 'ที่อยู่ : '.$Address.' ตำบล : '.$Tambon.' อำเภอ : '.$amphures.' จังหวัด : '.$provinceNames.' '.$Zip_code;
            }else{
                $AddressIndividual = 'ที่อยู่ : '.$Address;
            }

            if ($booking_channel) {
                $booking_names = [];

                foreach ($booking_channel as $value) {
                    $bc = master_document::find($value);
                    if ($bc) {
                        $booking_names[] = $bc->name_en;
                    }
                }
                $booking = 'ช่องทางการจอง : '.implode(',', $booking_names);
            }
            $email = null;
            if ($Email) {
                $email = 'อีเมล์ : '.$Email;
            }
            $identification = null;
            if ($identificationnumber) {
                $identification = 'เลขที่บัตรประจำตัว : '.$identificationnumber;
            }

            $Date = null;
            if ($Contract_Rate_Start_Date) {
                $Date = 'วันที่เริ่มต้น : '.$Contract_Rate_Start_Date.' '.'วันที่สิ้นสุด : '.$Contract_Rate_End_Date;
            }
            $Discount = null;
            if ($Discount_Contract_Rate) {
                $Discount = 'ส่วนลด(เปอร์เซ็น) : '.$Discount_Contract_Rate;
            }
            $Introduce = null;
            if ($Lastest_Introduce_By) {
                $Introduce = 'ผู้แนะนำ : '.$Lastest_Introduce_By;
            }
            $phone = null;
            if ($phones) {
                $phone = 'เพิ่มเบอร์โทรศัพท์ : ' . implode(', ', $phones);
            }
            $datacompany = '';
            $Profile = 'รหัสลูกค้า : '.$N_Profile;
            $variables = [$Profile,$comtypefullname , $AddressIndividual, $email,$booking, $identification,$Date,$Discount,$Introduce,$phone];

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
            $save->Company_ID = $N_Profile;
            $save->type = 'Create';
            $save->Category = 'Create :: Guest';
            $save->content =$datacompany;
            $save->save();
        }
        $save = new Guest();
        $save->Profile_ID = $N_Profile;
        $save->preface =$Preface;
        $save->First_name =$First_name;
        $save->Last_name =$Last_name;
        $save->Booking_Channel =$Booking_Channel;
        if ($CountryOther == "Other_countries") {
            $save->Address = $Address;
            $save->Country = $CountryOther;
        }else {
            $save->Country = $CountryOther;
            $save->City = $province;
            $save->Amphures = $amphures;
            $save->Address = $Address;
            $save->Tambon = $Tambon;
            $save->Zip_Code = $zip_code;
        }
        $save->Email = $Email;
        $save->Identification_Number = $identificationnumber;
        $save->Contract_Rate_Start_Date = $Contract_Rate_Start_Date;
        $save->Contract_Rate_End_Date = $Contract_Rate_End_Date;
        $save->Discount_Contract_Rate =$Discount_Contract_Rate;
        $save->Lastest_Introduce_By = $Lastest_Introduce_By;

        foreach ($phones as $index => $phoneNumber) {
            if ($phoneNumber !== null) {
                $phoneGuest = new phone_guest();
                $phoneGuest->Profile_ID = $N_Profile;
                $phoneGuest->Phone_number = $phoneNumber;
                $phoneGuest->Sequence = ($index === 0) ? 'main' : 'secondary'; // กำหนดค่า Sequence
                $phoneGuest->save();
            }
        }

        $save->save();
        if ($save->save()) {
            return redirect()->route('guest.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
        } else {
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
    }
    public function ac(Request $request)
    {
        $ac = $request->value;
        if ($ac == 1 ) {
            $query = Guest::query();
            $Guest = $query->where('status', '1')->get();
        }
        return view('guest.index',compact('Guest'));
    }
    public function no(Request $request)
    {
        $no = $request->value;
        if ($no == 0 ) {
            $query = Guest::query();
            $Guest = $query->where('status', '0')->get();
        }
        return view('guest.index',compact('Guest'));
    }

    public function guestStatus($id)
    {

        $gueststatus = Guest::find($id);
        if ($gueststatus->status == 1 ) {
            $status = 0;
            $gueststatus->status = $status;
        }elseif (($gueststatus->status == 0 )) {
            $status = 1;
            $gueststatus->status = $status;
        }
        $gueststatus->save();

    }
    public function guest_edit($id)
    {

        $Guest = Guest::find($id);
        // dd();
        $number =  preg_replace("/[^0-9]/", "", $Guest->City);
        $Other_City =  preg_replace("/[^a-zA-Z]/", "", $Guest->City);
        $provinceNames = province::select('name_th','id')->get();
        $Tambon = districts::where('amphure_id', $Guest->Amphures)->select('name_th','id')->get();
        $amphures = amphures::where('province_id', $Guest->City)->select('name_th','id')->get();
        $Zip_code = districts::where('amphure_id', $Guest->Amphures)->select('zip_code','id')->get();

        $booking_channel = master_document::select('name_en', 'id')->where('status', 1)->Where('Category','Mbooking_channel')->get();
        $prefix = master_document::select('name_th','id')->where('status', 1)->Where('Category','Mprename')->get();
        $Profile_ID = $Guest->Profile_ID;
        $phone = phone_guest::where('Profile_ID', 'like', "%{$Profile_ID}%")->get();
        $phonecount = phone_guest::where('Profile_ID', 'like', "%{$Profile_ID}%")->count();
        $phoneDataArray = $phone->toArray();
        return view('guest.edit',compact('Guest','Other_City','provinceNames','amphures','Tambon','Zip_code'
        ,'booking_channel','prefix','phonecount','phoneDataArray'));
    }

    public function guest_update(Request $request, $id)
    {
        $data = $request->all();
        $province = $request->province;
        $Preface = $request->Preface;
        $amphures = $request->amphures;
        $Tambon = $request->Tambon;
        $zip_code = $request->zip_code;
        $Booking_Channel =  implode(',',$request->booking_channel);
        $First_name = $request->first_name;
        $Last_name = $request->last_name;
        $CountryOther = $request->countrydata;
        $city = $request->city;
        $Address = $request->address;
        $Email = $request->email;
        $identificationnumber = $request->identification_number;
        $Contract_Rate_Start_Date = $request->contract_rate_start_date;
        $Contract_Rate_End_Date = $request->contract_rate_end_date;
        $Discount_Contract_Rate = $request->discount_contract_rate;
        $Lastest_Introduce_By = $request->latest_introduced_by;
        $phones = $request->input('phone');
        $save = Guest::find($id);
        $save->preface =$Preface;
        $save->First_name =$First_name;
        $save->Last_name =$Last_name;
        $save->Booking_Channel =$Booking_Channel;
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
        }
        $save->Email = $Email;
        $save->Identification_Number = $identificationnumber;
        $save->Contract_Rate_Start_Date = $Contract_Rate_Start_Date;
        $save->Contract_Rate_End_Date = $Contract_Rate_End_Date;
        $save->Discount_Contract_Rate =$Discount_Contract_Rate;
        $save->Lastest_Introduce_By = $Lastest_Introduce_By;

        $Guest = Guest::find($id);
        $Profile_ID = $Guest->Profile_ID;// กำหนดค่า Profile_ID ที่ต้องการใช้งาน
        if ($Profile_ID) {
            $profilePhones = phone_guest::where('Profile_ID', $Profile_ID)->get();
            foreach ($profilePhones as $phone) {
                $phone->delete();
            }
        }
        foreach ($phones as $index => $phoneNumber) {
            if ($phoneNumber !== null) {
                $phoneGuest = new phone_guest();
                $phoneGuest->Profile_ID = $Profile_ID;
                $phoneGuest->Phone_number = $phoneNumber;
                $phoneGuest->Sequence = ($index === 0) ? 'main' : 'secondary'; // กำหนดค่า Sequence
                $phoneGuest->save();
            }
        }
         $save->save();
         if ($save->save()) {
             return redirect()->route('guest.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
         } else {
             return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
         }
    }
}
