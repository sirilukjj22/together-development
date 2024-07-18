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
class GuestController extends Controller
{
    public function index()
    {
        $Guest = Guest::query()->get();
        $Mbooking = master_document::select('name_en','id')->get();
        return view('guest.index',compact('Guest','Mbooking'));
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


    //     dd($N_Profile,$province,$Preface,$amphures,$Tambon,$zip_code
    //     ,$Booking_Channel,$CountryOther,$city,$Address,$Email,$identificationnumber,
    //     $Contract_Rate_Start_Date,$Contract_Rate_End_Date,$Discount_Contract_Rate
    // ,$Lastest_Introduce_By,$phones);
        $save = new Guest();
        $save->Profile_ID = $N_Profile;
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

    public function guest_update(Request $request, $id) {
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
