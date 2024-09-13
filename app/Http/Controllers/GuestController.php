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
use App\Models\guest_tax;
use App\Models\guest_tax_phone;
use App\Models\Quotation;
use Auth;
class GuestController extends Controller
{
    public function index($menu)
    {
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $Guest = Guest::query()->paginate($perPage);
        $Mbooking = master_document::select('name_en','id')->get();
        $exp = explode('.', $menu);
        if (count($exp) > 1) {
            $search = $exp[1];
            if ($search == "all") {
                $Guest = Guest::paginate($perPage);
            }elseif ($search == 'ac') {
                $Guest = Guest::where('status', 1)->paginate($perPage);
            }else {
                $Guest = Guest::where('status', 0)->paginate($perPage);
            }
        }
        return view('guest.index',compact('Guest','Mbooking', 'menu'));
    }
    public function search_table(Request $request)
    {

        $perPage = (int)$request->perPage;
        $search_value = $request->search_value;
        if ($search_value) {
            $data_query = Guest::where('Profile_ID', 'LIKE', '%'.$search_value.'%')
                ->orWhere('First_name', 'LIKE', '%'.$search_value.'%')
                ->orWhere('Last_name', 'LIKE', '%'.$search_value.'%')
                ->paginate($perPage);
        }else{
            $perPageS = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
            $data_query = Guest::query()->paginate($perPageS);
        }
        $data = [];
        $path = "/guest/edit/";
        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $btn_action = "";
                $btn_status = "";
                $exportbook = explode(',', $value->Booking_Channel);
                $booking_names = [];

                $booking_names = array_filter(array_map(function($exportbook) {
                    $bc = master_document::find($exportbook);
                    return $bc ? $bc->name_en : null;
                }, $exportbook));
                $booking = implode('</br>', $booking_names);
                $btn_action .='<div class="dropdown">';
                $btn_action .='<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">ทำรายการ &nbsp;
                </button>';
                $btn_action .='<ul class="dropdown-menu border-0 shadow p-3">';
                $btn_action .='<li class="dropdown-item py-2 rounded">ดูรายละเอียด</li>';
                $btn_action .= '<li class="dropdown-item py-2 rounded" onclick="window.location.href=\'' . url('/guest/edit/' . $value->id) . '\'">แก้ไขรายการ</li>';
                $btn_action .='</ul>';
                $btn_action .='</div>';
                if ($value->status == 1) {
                    $btn_status = '<button type="button" class="btn btn-light-success btn-sm" value="'.$value->id.'" onclick="btnstatus('.$value->id.')">ใช้งาน</button>';
                } else {
                    $btn_status = '<button type="button" class="btn btn-light-danger btn-sm" value="'.$value->id.'" onclick="btnstatus('.$value->id.')">ปิดใช้งาน</button>';
                }
                $data[] = [
                    'number' => $key + 1,
                    'Profile_ID'=>$value->Profile_ID,
                    'name'=>$value->First_name.' '.$value->Last_name,
                    'Booking_Channel'=> $booking,
                    'status'=>$btn_status,
                    'btn_action' => $btn_action,
                ];
            }
        }
        return response()->json([
            'data' => $data,
        ]);
    }
    public function paginate_table(Request $request)
    {
        $perPage = (int)$request->perPage;

        $data = [];
        if ($perPage == 10) {
            $data_query = Guest::query()->limit($request->page.'0')->get();
        } else {
            $data_query = Guest::query()->paginate($perPage);
        }
        $page_1 = $request->page == 1 ? 1 : ($request->page - 1).'1';
        $page_2 = $request->page.'0';

        $perPage2 = $request->perPage > 10 ? $request->perPage : 10;
        $path = "/guest/edit/";

        $path_view = "/guest/view/";
        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $btn_action = "";
                $btn_status = "";
                $exportbook = explode(',', $value->Booking_Channel);
                $booking_names = [];

                $booking_names = array_filter(array_map(function($exportbook) {
                    $bc = master_document::find($exportbook);
                    return $bc ? $bc->name_en : null;
                }, $exportbook));
                $booking = implode('</br>', $booking_names);
                if (($key + 1) >= (int)$page_1 && ($key + 1) <= (int)$page_2 || (int)$perPage > 10 && $key < (int)$perPage2) {
                    $btn_action .='<div class="dropdown">';
                    $btn_action .='<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">ทำรายการ &nbsp;
                    </button>';
                    $btn_action .='<ul class="dropdown-menu border-0 shadow p-3">';
                    $btn_action .='<li class="dropdown-item py-2 rounded " onclick="window.location.href=\'' . url('/guest/view/' . $value->id) . '\'">ดูรายละเอียด</li>';
                    $btn_action .= '<li class="dropdown-item py-2 rounded" onclick="window.location.href=\'' . url('/guest/edit/' . $value->id) . '\'">แก้ไขรายการ</li>';
                    $btn_action .='</ul>';
                    $btn_action .='</div>';
                    if ($value->status == 1) {
                        $btn_status = '<button type="button" class="btn btn-light-success btn-sm" value="'.$value->id.'" onclick="btnstatus('.$value->id.')">ใช้งาน</button>';
                    } else {
                        $btn_status = '<button type="button" class="btn btn-light-danger btn-sm" value="'.$value->id.'" onclick="btnstatus('.$value->id.')">ปิดใช้งาน</button>';
                    }
                    $data[] = [
                        'number' => $key + 1,
                        'Profile_ID'=>$value->Profile_ID,
                        'name'=>$value->First_name.' '.$value->Last_name,
                        'Booking_Channel'=> $booking,
                        'status'=>$btn_status,
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
    public function amphuresT($id)
    {

        $amphuresA= amphures::where('province_id',$id)->select('name_th','id')->orderby('id','desc')->get();
        return response()->json([
            'data' => $amphuresA,
        ]);
    }
    public function TambonT($id)
    {
        $TambonA = districts::where('amphure_id',$id)->select('name_th','id')->orderby('id','desc')->get();
        return response()->json([
            'data' => $TambonA,

        ]);
    }
    public function districtT($id)
    {

        $districtA = districts::where('id',$id)->select('zip_code','id')->orderby('id','desc')->get();
        return response()->json([
            'data' => $districtA,

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
                $provinceNames = province::where('id', $province)->first();
                $TambonID = districts::where('id',$Tambon)->select('name_th','id','zip_code')->first();
                $amphuresID = amphures::where('id',$amphures)->select('name_th','id')->first();
                $provinceNames = $provinceNames->name_th;
                $Tambon = $TambonID->name_th;
                $amphures = $amphuresID->name_th;
                $Zip_code = $TambonID->zip_code;
                $AddressIndividual = 'ที่อยู่ : '.$Address.'+'.' ประเทศ : '.$CountryOther.' ตำบล : '.$Tambon.' อำเภอ : '.$amphures.' จังหวัด : '.$provinceNames.' '.$Zip_code;
            }else{
                $AddressIndividual = 'ที่อยู่ : '.$Address.'+'.' ประเทศ : '.$CountryOther;
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
        $save->preface =$request->Preface;
        $save->First_name =$First_name;
        $save->Last_name =$Last_name;
        $save->Booking_Channel =$Booking_Channel;
        if ($CountryOther == "Other_countries") {
            $save->Address = $Address;
            $save->Country = $CountryOther;
        }else {
            $save->Country = $CountryOther;
            $save->City = $request->province;
            $save->Amphures = $request->amphures;
            $save->Address = $Address;
            $save->Tambon = $request->Tambon;
            $save->Zip_Code = $request->zip_code;
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
            return redirect()->route('guest','index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
        } else {
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
    }
    public function ac(Request $request)
    {
        $ac = $request->value;
        if ($ac == 1 ) {
            $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
            $Guest = Guest::where('status', '1')->paginate($perPage);
        }
        return view('guest.index',compact('Guest'));
    }
    public function no(Request $request)
    {
        $no = $request->value;
        if ($no == 0 ) {
            $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
            $Guest = Guest::where('status', '0')->paginate($perPage);
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
        $number =  preg_replace("/[^0-9]/", "", $Guest->City);
        $Other_City =  preg_replace("/[^a-zA-Z]/", "", $Guest->City);
        $provinceNames = province::select('name_th','id')->get();
        $Tambon = districts::where('amphure_id', $Guest->Amphures)->select('name_th','id')->get();
        $amphures = amphures::where('province_id', $Guest->City)->select('name_th','id')->get();
        $Zip_code = districts::where('amphure_id', $Guest->Amphures)->select('zip_code','id')->get();

        $booking_channel = master_document::select('name_en', 'id')->where('status', 1)->Where('Category','Mbooking_channel')->get();
        $prefix = master_document::select('name_th','id')->where('status', 1)->Where('Category','Mprename')->get();
        $Profile_ID = $Guest->Profile_ID;
        $phone = phone_guest::where('Profile_ID',$Profile_ID)->get();
        $phonecount = phone_guest::where('Profile_ID',$Profile_ID)->count();
        $phoneDataArray = $phone->toArray();

        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $Quotation = Quotation::where('Company_ID',$Profile_ID)->paginate($perPage);
        $log = log_company::where('Company_ID', $Profile_ID)
        ->orderBy('updated_at', 'desc')
        ->paginate($perPage);
        $guesttax = guest_tax::where('Company_ID', $Profile_ID)
        ->paginate($perPage);
        $MCompany_type = master_document::select('name_th', 'id')->where('status', 1)->Where('Category','Mcompany_type')->get();
        $Mprefix = master_document::select('name_th','id')->where('status', 1)->Where('Category','Mprename')->get();

        return view('guest.edit',compact('Guest','Other_City','provinceNames','amphures','Tambon','Zip_code'
        ,'booking_channel','prefix','phonecount','phoneDataArray','log','MCompany_type','Mprefix','guesttax','Quotation'));
    }
    public function view($id){
        $Guest = Guest::find($id);
        $number =  preg_replace("/[^0-9]/", "", $Guest->City);
        $Other_City =  preg_replace("/[^a-zA-Z]/", "", $Guest->City);
        $provinceNames = province::select('name_th','id')->get();
        $Tambon = districts::where('amphure_id', $Guest->Amphures)->select('name_th','id')->get();
        $amphures = amphures::where('province_id', $Guest->City)->select('name_th','id')->get();
        $Zip_code = districts::where('amphure_id', $Guest->Amphures)->select('zip_code','id')->get();

        $booking_channel = master_document::select('name_en', 'id')->where('status', 1)->Where('Category','Mbooking_channel')->get();
        $prefix = master_document::select('name_th','id')->where('status', 1)->Where('Category','Mprename')->get();
        $Profile_ID = $Guest->Profile_ID;
        $phone = phone_guest::where('Profile_ID',$Profile_ID)->get();
        $phonecount = phone_guest::where('Profile_ID',$Profile_ID)->count();
        $phoneDataArray = $phone->toArray();
        return view('guest.view',compact('Guest','Other_City','provinceNames','amphures','Tambon','Zip_code'
        ,'booking_channel','prefix','phonecount','phoneDataArray'));
    }
    public function search_table_log(Request $request)
    {

        $perPage = (int)$request->perPage;
        $search_value = $request->search_value;
        $guest_profile = $request->guest_profile;

        if ($search_value) {
            $data_query = log_company::where('created_at', 'LIKE', '%'.$search_value.'%')
                ->where('Company_ID',$guest_profile)
                ->orderBy('updated_at', 'desc')
                ->paginate($perPage);
        }else{
            $perPageS = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
            $data_query = log_company::where('Company_ID',$guest_profile)->orderBy('updated_at', 'desc')->paginate($perPageS);
        }
        $data = [];
        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $contentArray = explode('+', $value->content);
                $content = implode('</br>', $contentArray);
                $Category = '<b style="color:#0000FF ">' . $value->Category . '</b>';
                $name = $Category.'</br>'.$content;
                $data[] = [
                    'number' => $key + 1,
                    'Category'=>$value->Category,
                    'type'=>$value->type,
                    'Created_by'=>@$value->userOperated->name,
                    'created_at' => \Carbon\Carbon::parse($value->created_at)->format('d/m/Y'),
                    'Content' => $name,
                ];
            }
        }
        return response()->json([
            'data' => $data,
        ]);
    }
    public function paginate_table_log(Request $request)
    {
        $perPage = (int)$request->perPage;
        $guest_profile = $request->guest_profile;
        $data = [];
        if ($perPage == 10) {
            $data_query = log_company::where('Company_ID',$guest_profile)->orderBy('updated_at', 'desc')->limit($request->page.'0')->get();
        } else {
            $data_query = log_company::where('Company_ID',$guest_profile)->orderBy('updated_at', 'desc')->paginate($perPage);
        }
        $page_1 = $request->page == 1 ? 1 : ($request->page - 1).'1';
        $page_2 = $request->page.'0';

        $perPage2 = $request->perPage > 10 ? $request->perPage : 10;

        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $contentArray = explode('+', $value->content);
                $content = implode('</br>', $contentArray);
                $Category = '<b style="color:#0000FF ">' . $value->Category . '</b>';
                $name = $Category.'</br>'.$content;
                if (($key + 1) >= (int)$page_1 && ($key + 1) <= (int)$page_2 || (int)$perPage > 10 && $key < (int)$perPage2) {
                    $data[] = [
                        'number' => $key + 1,
                        'Category'=>$value->Category,
                        'type'=>$value->type,
                        'Created_by'=>@$value->userOperated->name,
                        'created_at' => \Carbon\Carbon::parse($value->created_at)->format('d/m/Y'),
                        'Content' => $name,
                    ];
                }
            }
        }
        // dd($data);
        return response()->json([
            'data' => $data,
        ]);

    }
    public function guest_update(Request $request, $id)
    {
        {
            $guest = Guest::where('id', $id)->first();
            $guest_id = $guest->Profile_ID;
            $ids = $guest->id;
            $phone = phone_guest::where('Profile_ID', $guest_id)->get();
            $dataArray = $guest->toArray(); // แปลงข้อมูลบริษัทเป็น array
            $dataArray['phone'] = $phone->pluck('Phone_number')->toArray(); // เพิ่มค่า phone เข้าไปใน $dataArray
            $data = $request->all(); // ดึงข้อมูลที่ส่งมาทั้งหมดจาก request
            $Booking_Channel =  implode(',',$request->booking_channel);
            $datarequest = [
                'preface' => $data['Preface'] ?? null,
                'First_name' => $data['first_name'] ?? null,
                'Last_name' => $data['last_name'] ?? null,
                'Booking_Channel' => $Booking_Channel ?? null,
                'Country' => $data['countrydata'] ?? null,
                'City' => $data['province'] ?? null,
                'Amphures' => $data['amphures'] ?? null,
                'Tambon' => $data['Tambon'] ?? null,
                'Address' => $data['address'] ?? null,
                'Zip_Code' => $data['zip_code'] ?? null,
                'Email' => $data['email'] ?? null,
                'Identification_Number' => $data['identification_number'] ?? null,
                'Contract_Rate_Start_Date' => $data['contract_rate_start_date'] ?? null,
                'Contract_Rate_End_Date' => $data['contract_rate_end_date'] ?? null,
                'Discount_Contract_Rate' => $data['discount_contract_rate'] ?? null,
                'Lastest_Introduce_By' => $data['latest_introduced_by'] ?? null,
                'phone' => $data['phone'] ?? null,
            ];
            $keysToCompare = ['preface', 'First_name', 'Last_name','Booking_Channel','Country', 'City', 'Amphures', 'Tambon', 'phone',
            'Address', 'Zip_Code', 'Email', 'Identification_Number', 'Contract_Rate_Start_Date', 'Contract_Rate_End_Date', 'Discount_Contract_Rate','Lastest_Introduce_By'];
            $differences = [];
                    foreach ($keysToCompare as $key) {
                        if (isset($dataArray[$key]) && isset($datarequest[$key])) {
                            // แปลงค่าของ $dataArray และ $data เป็นชุดข้อมูลเพื่อหาค่าที่แตกต่างกัน
                            $dataArraySet = collect($dataArray[$key]);
                            $dataSet = collect($datarequest[$key]);

                            // หาค่าที่แตกต่างกัน
                            $onlyInDataArray = $dataArraySet->diff($dataSet)->values()->all();
                            $onlyInRequest = $dataSet->diff($dataArraySet)->values()->all();

                            // ตรวจสอบว่ามีค่าที่แตกต่างหรือไม่
                            if (!empty($onlyInDataArray) || !empty($onlyInRequest)) {
                                $differences[$key] = [
                                    'dataArray' => $onlyInDataArray,
                                    'request' => $onlyInRequest
                                ];
                            }
                        }
                    }
                    $extractedData = [];
                    $extractedDataA = [];
                    // วนลูปเพื่อดึงชื่อคีย์และค่าจาก request
                    foreach ($differences as $key => $value) {
                        if ($key === 'phone') {
                            // ถ้าเป็น phoneCom ให้เก็บค่า request ทั้งหมดใน array
                            $extractedData[$key] = $value['request'];
                            $extractedDataA[$key] = $value['dataArray'];
                        } elseif (isset($value['request'][0])) {
                            // สำหรับคีย์อื่นๆ ให้เก็บค่าแรกจาก array
                            $extractedData[$key] = $value['request'][0];
                        }else{
                            $extractedDataA[$key] = $value['dataArray'][0];
                        }
                    }
                    $Preface = $extractedData['preface'] ?? null;
                    $first_name = $extractedData['First_name'] ?? null;
                    $last_name =  $extractedData['Last_name'] ?? null;
                    $Booking_Channel =  $extractedData['Booking_Channel'] ?? null;
                    $Address =  $extractedData['Address'] ?? null;
                    $Country =  $extractedData['Country'] ?? null;
                    $City =  $extractedData['City'] ?? null;
                    $Amphures =  $extractedData['Amphures'] ?? null;
                    $Tambon =  $extractedData['Tambon'] ?? null;
                    $Zip_Code =  $extractedData['Zip_Code'] ?? null;
                    $Emailcheck =  $extractedData['Email'] ?? null;
                    $Identification_Number =  $extractedData['Identification_Number'] ?? null;
                    $Contract_Rate_Start_Date =  $extractedData['Contract_Rate_Start_Date'] ?? null;
                    $Contract_Rate_End_Date =  $extractedData['Contract_Rate_End_Date'] ?? null;
                    $Discount_Contract_Rate =  $extractedData['Discount_Contract_Rate'] ?? null;
                    $Lastest_Introduce_By =  $extractedData['Lastest_Introduce_By'] ?? null;
                    $phoneCom =  $extractedData['phone'] ?? null;
                    $phoneComA =  $extractedDataA['phone'] ?? null;

                    $comtypefullname = null;
                    if ($Preface && $first_name && $last_name) {
                        $Mprefix = master_document::where('id', $Preface)->where('Category', 'Mprename')->first();
                        if ($Mprefix) {
                            if ($Mprefix->name_th == "นาย") {
                                $comtypefullname = "นาย " . $first_name . ' ' . $last_name;
                            } elseif ($Mprefix->name_th == "นาง") {
                                $comtypefullname = "นาง " . $first_name . ' ' . $last_name;
                            } elseif ($Mprefix->name_th == "นางสาว") {
                                $comtypefullname = "นางสาว " . $first_name . ' ' . $last_name;
                            }
                        }
                    } elseif ($Preface > 30) {
                        $Mprefix = master_document::where('id', $Preface)->where('Category', 'Mprename')->first();
                        if ($Mprefix) {
                            $prename = $Mprefix->name_th;
                            $comtypefullname = 'คำนำหน้า : ' . $prename;
                        }
                    } elseif ($first_name && $last_name) {
                        $comtypefullname = 'ชื่อ : ' . $first_name . ' ' . $last_name;
                    } elseif ($first_name) {
                        $comtypefullname = 'ชื่อ : ' . $first_name;
                    } elseif ($last_name) {
                        $comtypefullname = 'นามสกุล : ' . $last_name;
                    }
                    $AddressIndividual = null;
                    if ($datarequest['Country'] == 'Thailand') {

                        $provinceNames = province::where('id', $City)->first();
                        $TambonID = districts::where('id',$Tambon)->select('name_th','id','zip_code')->first();
                        $amphuresID = amphures::where('id',$Amphures)->select('name_th','id')->first();
                        $provinceNames = $provinceNames->name_th;
                        $TambonCheck = $TambonID->name_th;
                        $amphures = $amphuresID->name_th;
                        $Zip_code = $TambonID->zip_code;
                        $AddressCheck = null;
                        if ($Address) {
                            $AddressCheck = 'ที่อยู่ : '.$Address;
                        }
                        $CountryCheck = null;
                        if ($Country) {
                            $CountryCheck = ' ประเทศ : '.$Country;
                        }
                        $AddressIndividual = $AddressCheck.'+'.$CountryCheck.'+'.' ตำบล : '.$Tambon.'+'.' อำเภอ : '.$amphures.'+'.' จังหวัด : '.$provinceNames.' '.$Zip_code;
                    }else{
                        $AddressCheck = null;
                        if ($Address) {
                            $AddressCheck = 'ที่อยู่ : '.$Address;
                        }
                        $CountryCheck = null;
                        if ($Country) {
                            $CountryCheck = ' ประเทศ : '.$Country;
                        }
                        $AddressIndividual = $AddressCheck.'+'.$CountryCheck;
                    }
                    $Email = null;
                    if ($Emailcheck) {
                        $Email = 'อีเมล์ผู้ติดต่อ : '.$Emailcheck;
                    }
                    $phone = null;
                    if ($phoneCom) {
                        $phone = 'เพิ่มเบอร์โทรศัพท์ : ' . implode(', ', $phoneCom);
                    }
                    $phoneA = null;
                    if ($phoneComA) {
                        $phoneA = 'ลบเบอร์โทรศัพท์ : ' . implode(', ', $phoneComA);
                    }
                    $Identification = null;
                    if ($Identification_Number) {
                        $Identification = 'เลขบัตรประจำตัว : '.$Identification_Number;
                    }
                    $Date = null;
                    if ($Contract_Rate_Start_Date && $Contract_Rate_End_Date) {
                        $Date = 'วันที่เริ่มต้น : '.$Contract_Rate_Start_Date.' '.'วันที่สิ้นสุด : '.$Contract_Rate_End_Date;
                    }elseif ($Contract_Rate_Start_Date) {
                        $Date = 'วันที่เริ่มต้น : '.$Contract_Rate_Start_Date;
                    }elseif ($Contract_Rate_End_Date) {
                        $Date ='วันที่สิ้นสุด : '.$Contract_Rate_End_Date;
                    }
                    $Discount = null;
                    if ($Discount_Contract_Rate) {
                        $Discount = 'ส่วนลด(เปอร์เซ็น) : '.$Discount_Contract_Rate;
                    }
                    $Introduce = null;
                    if ($Lastest_Introduce_By) {
                        $Introduce = 'ผู้แนะนำ : '.$Lastest_Introduce_By;
                    }
                    $booking = null;
                    if ($Booking_Channel) {
                        $booking_names = [];
                        $Mbooking = explode(',', $Booking_Channel);
                        foreach ($Mbooking as $value) {
                            $bc = master_document::find($value);
                            if ($bc) {
                                $booking_names[] = $bc->name_en;
                            }
                        }
                        $booking = 'ช่องทางการจอง : '.implode(',', $booking_names);
                    }
                    $datacompany = '';
                    $variables = [$comtypefullname , $AddressIndividual, $Email,$booking, $Identification,$Date,$Discount,$Introduce,$phone,$phoneA];

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
                    $save->Company_ID = $guest_id;
                    $save->type = 'Edit';
                    $save->Category = 'Edit :: Guest';
                    $save->content =$datacompany;
                    $save->save();
        }

        $province = $request->province;
        $Preface = $request->Preface;
        $amphures = $request->amphures;
        $Tambon = $request->Tambon;
        $zip_code = $request->zip_code;
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
        $Booking_Channel =  implode(',',$request->booking_channel);
        $phones = $request->input('phone');
        try {

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
                    $save->Country = $CountryOther;
                    $save->Address = $Address;
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
            return redirect()->route('guest.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
    }
    public function guest_cover(Request $request, $id)
    {
        $data = $request->all();
        $profileguest = Guest::where('id',$id)->first();
        $Profile_IDGuest = $profileguest->Profile_ID;
        $latestGuest = guest_tax::latest('id')->first();
        if ($latestGuest) {
            $Profile_ID = $latestGuest->id + 1;
        } else {
            // ถ้าไม่มี Guest ในฐานข้อมูล เริ่มต้นด้วย 1
            $Profile_ID = 1;
        }
        $Id_profile ="-";
        $N_Profile = $Profile_IDGuest.$Id_profile.$Profile_ID;
        try {

                $TaxSelect = $request->TaxSelectA;
                $Company_type = $request->Company_type_tax;
                $Company_Name = $request->Company_Name_tax;
                $CountryOther = $request->countrydataA;
                $Province = $request->cityA;
                $Amphures = $request->amphuresA;
                $Tambon = $request->TambonA;
                $Zip_code = $request->zip_codeA;
                $Email = $request->EmailAgent;
                $Address = $request->addressAgent;
                $BranchTax = $request->BranchTax;
                //-----------------------------------
                $phoneGuest = $request->phoneTax;
                //-----------------------------------
                $Taxpayer_Identification =$request->Identification;
                //------------------------------------------------------------
                $prefix =$request->prefix;
                $first_name =$request->first_nameCom;
                $last_name =$request->last_nameCom;

                if ($TaxSelect == 'Company') {
                    $comtype = master_document::where('id', $Company_type)->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $comtypefullname = "บริษัท ". $Company_Name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $comtypefullname = "บริษัท ". $Company_Name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $comtypefullname = "ห้างหุ้นส่วนจำกัด ". $Company_Name ;
                    }
                }else{
                    if ($prefix && $first_name && $last_name) {
                        $Mprefix = master_document::where('id', $prefix)->where('Category', 'Mprename')->first();
                        if ($Mprefix) {
                            if ($Mprefix->name_th == "นาย") {
                                $comtypefullname = 'ชื่อ : '."นาย " . $first_name . ' ' . $last_name;
                            } elseif ($Mprefix->name_th == "นาง") {
                                $comtypefullname = 'ชื่อ : '."นาง " . $first_name . ' ' . $last_name;
                            } elseif ($Mprefix->name_th == "นางสาว") {
                                $comtypefullname = 'ชื่อ : '."นางสาว " . $first_name . ' ' . $last_name;
                            }
                        }

                    } elseif ($prefix > 30) {
                        $Mprefix = master_document::where('id', $prefix)->where('Category', 'Mprename')->first();
                        if ($Mprefix) {
                            $prename = $Mprefix->name_th;
                            $comtypefullname = 'คำนำหน้า : ' . $prename;
                        }
                    } elseif ($first_name && $last_name) {
                        $comtypefullname = 'ชื่อ : ' . $first_name . ' ' . $last_name;
                    } elseif ($first_name) {
                        $comtypefullname = 'ชื่อ : ' . $first_name;
                    } elseif ($last_name) {
                        $comtypefullname = 'นามสกุล : ' . $last_name;
                    }
                }


                if ($CountryOther == 'Thailand') {
                    $provinceNames = province::where('id', $Province)->first();
                    $TambonID = districts::where('id',$Tambon)->select('name_th','id','zip_code')->first();
                    $amphuresID = amphures::where('id',$Amphures)->select('name_th','id')->first();
                    $provinceNames = $provinceNames->name_th;
                    $Tambon = $TambonID->name_th;
                    $amphures = $amphuresID->name_th;
                    $Zip_code = $TambonID->zip_code;
                    $AddressIndividual = 'ที่อยู่ : '.$Address.'+'.' ประเทศ : '.$CountryOther.' ตำบล : '.$Tambon.' อำเภอ : '.$amphures.' จังหวัด : '.$provinceNames.' '.$Zip_code;
                }
                else{
                    $AddressIndividual = 'ที่อยู่ : '.$Address.'+'.' ประเทศ : '.$CountryOther;
                }
                $EmailN = null;
                if ($Email) {
                    $EmailN = 'อีเมล์ : '.$Email;
                }
                $Identification = null;
                if ($Taxpayer_Identification) {
                    $Identification = 'เลขบัตรประจำตัว : '.$Taxpayer_Identification;
                }
                $Branch = null;
                if ($BranchTax) {
                    $Branch = 'สาขา : '.$BranchTax;
                }
                $phone = null;
                if ($phoneGuest) {
                    $phone = 'เพิ่มเบอร์โทรศัพท์ : ' . implode(', ', $phoneGuest);
                }
                $Profile = 'รหัส : '.$N_Profile;
                $Company = null;
                if ($Profile_IDGuest) {
                    $Company = 'รหัสลูกค้า : '.$Profile_IDGuest;
                }

                $datacompany = '';

                $variables = [$Profile,$Company,$comtypefullname, $EmailN, $Identification, $Branch, $AddressIndividual, $phone];

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
                $save->Company_ID = $Profile_IDGuest;
                $save->type = 'Create';
                $save->Category = 'Create :: Additional Guest Tax Invoice';
                $save->content =$datacompany;
                $save->save();
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }


        try {
            if ($request->TaxSelectA == 'Company') {
                $save = new guest_tax();
                $save->GuestTax_ID =$N_Profile;
                $save->Company_ID = $Profile_IDGuest;
                $save->Company_type = $request->Company_type_tax;
                $save->Company_name =$request->Company_Name_tax;
                $save->Tax_Type = 'Company';
                $save->BranchTax = $BranchTax;

                if ($request->countrydataA == "Other_countries") {
                    $save->Country =$request->countrydataA;
                    $save->Address =$request->addressAgent;
                }else {
                    $save->Country =$request->countrydataA;
                    $save->City =$request->cityA;
                    $save->Amphures =$request->amphuresA;
                    $save->Tambon =$request->TambonA;
                    $save->Address =$request->addressAgent;
                    $save->Zip_Code = $request->zip_codeA;
                }
                $save->Company_Email = $request->EmailAgent;
                $save->Taxpayer_Identification = $request->Identification;
                $save->save();

                foreach ($request->phoneTax as $index => $phoneNumber) {
                    if ($phoneNumber !== null) {
                        $savephoneA = new guest_tax_phone();
                        $savephoneA->GuestTax_ID = $N_Profile;
                        $savephoneA->Phone_number = $phoneNumber;
                        $savephoneA->sequence = ($index === 0) ? 'main' : 'secondary'; // กำหนดค่า Sequence
                        $savephoneA->save();
                    }
                }
            }else {
                $save = new guest_tax();
                $save->GuestTax_ID =$N_Profile;
                $save->Company_ID = $Profile_IDGuest;
                $save->Company_type = $request->prefix;
                $save->first_name =$request->first_nameCom;
                $save->last_name =$request->last_nameCom;
                $save->Tax_Type = 'Individual';
                if ($request->countrydataA == "Other_countries") {
                    $save->Country =$request->countrydataA;
                    $save->Address =$request->addressAgent;
                }else {
                    $save->Country =$request->countrydataA;
                    $save->City =$request->cityA;
                    $save->Amphures =$request->amphuresA;
                    $save->Tambon =$request->TambonA;
                    $save->Address =$request->addressAgent;
                    $save->Zip_Code = $request->zip_codeA;
                }
                $save->Company_Email = $request->EmailAgent;
                $save->Taxpayer_Identification = $request->Identification;
                foreach ($request->phoneTax as $index => $phoneNumber) {
                    if ($phoneNumber !== null) {
                        $savephoneA = new guest_tax_phone();
                        $savephoneA->GuestTax_ID = $N_Profile;
                        $savephoneA->Phone_number = $phoneNumber;
                        $savephoneA->sequence = ($index === 0) ? 'main' : 'secondary'; // กำหนดค่า Sequence
                        $savephoneA->save();
                    }
                }
                $save->save();
            }
            return redirect()->route('guest_edit', ['id' => $id])->with('success', 'บันทึกข้อมูลเรียบร้อยแล้ว');
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function guestStatustax($id)
    {
        $gueststatus = guest_tax::find($id);
        if ($gueststatus->status == 1 ) {
            $status = 0;
            $gueststatus->status = $status;
        }elseif (($gueststatus->status == 0 )) {
            $status = 1;
            $gueststatus->status = $status;
        }
        $gueststatus->save();
    }
    public function paginate_table_guest(Request $request)
    {
        $perPage = (int)$request->perPage;
        $guest_profile = $request->guest_profile;
        $data = [];
        if ($perPage == 10) {
            $data_query = guest_tax::where('Company_ID',$guest_profile)->limit($request->page.'0')->get();
        } else {
            $data_query = guest_tax::where('Company_ID',$guest_profile)->paginate($perPage);
        }
        $page_1 = $request->page == 1 ? 1 : ($request->page - 1).'1';
        $page_2 = $request->page.'0';

        $perPage2 = $request->perPage > 10 ? $request->perPage : 10;
        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $btn_Company = "";
                $btn_status = "";
                $btn_action = "";
                if (($key + 1) >= (int)$page_1 && ($key + 1) <= (int)$page_2 || (int)$perPage > 10 && $key < (int)$perPage2) {
                    if ($value->status == 1) {
                        $btn_status = '<button type="button" class="btn btn-light-success btn-sm" value="'.$value->id.'" onclick="btnstatus('.$value->id.')">ใช้งาน</button>';
                    } else {
                        $btn_status = '<button type="button" class="btn btn-light-danger btn-sm" value="'.$value->id.'" onclick="btnstatus('.$value->id.')">ปิดใช้งาน</button>';
                    }
                    if ($value->Tax_Type == 'Company') {
                        $btn_Company = $value->Company_name;
                    }else {
                        $btn_Company = $value->first_name.' '.$value->last_name;
                    }
                    $btn_action .='<div class="btn-group">';
                    $btn_action .='<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">ทำรายการ &nbsp;</button>';
                    $btn_action .='<ul class="dropdown-menu border-0 shadow p-3">';
                    $btn_action .=' <li><a class="dropdown-item py-2 rounded" href=\'' . url('/guest/Tax/view/' . $value->id) . '\'>ดูรายละเอียด</a></li>';
                    $btn_action .= ' <li><a class="dropdown-item py-2 rounded" href=\'' . url('/guest/Tax/edit/' . $value->id) . '\'>แก้ไขรายการ</a></li>';
                    $btn_action .='</ul>';
                    $btn_action .='</div>';

                    $data[] = [
                        'number' => $key + 1,
                        'Company/Individual'=>$btn_Company,
                        'Branch'=> $value->BranchTax,
                        'Status'=>$btn_status,
                        'Order' => $btn_action,
                    ];
                }
            }
        }
        // dd($data);
        return response()->json([
            'data' => $data,
        ]);

    }
    public function search_table_guest(Request $request)
    {
        $perPage = (int)$request->perPage;
        $search_value = $request->search_value;
        $guest_profile = $request->guest_profile;
        if ($search_value) {
            $data_query = guest_tax::where('Company_name', 'LIKE', '%'.$search_value.'%')
            ->orWhere('BranchTax', 'LIKE', '%'.$search_value.'%')
            ->orWhere('first_name', 'LIKE', '%'.$search_value.'%')
            ->orWhere('last_name', 'LIKE', '%'.$search_value.'%')
            ->where('Company_ID',$guest_profile)
            ->paginate($perPage);
        }else{
            $perPageS = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
            $data_query = guest_tax::where('Company_ID',$guest_profile)->paginate($perPageS);
        }
        $data = [];
        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $btn_Company = "";
                $btn_status = "";
                $btn_action = "";
                if ($value->status == 1) {
                    $btn_status = '<button type="button" class="btn btn-light-success btn-sm" value="'.$value->id.'" onclick="btnstatus('.$value->id.')">ใช้งาน</button>';
                } else {
                    $btn_status = '<button type="button" class="btn btn-light-danger btn-sm" value="'.$value->id.'" onclick="btnstatus('.$value->id.')">ปิดใช้งาน</button>';
                }
                if ($value->Tax_Type == 'Company') {
                    $btn_Company = $value->Company_name;
                }else {
                    $btn_Company = $value->first_name.' '.$value->last_name;
                }
                $btn_action .='<div class="btn-group">';
                $btn_action .='<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">ทำรายการ &nbsp;</button>';
                $btn_action .='<ul class="dropdown-menu border-0 shadow p-3">';
                $btn_action .=' <li><a class="dropdown-item py-2 rounded" href=\'' . url('/guest/Tax/view/' . $value->id) . '\'>ดูรายละเอียด</a></li>';
                $btn_action .= ' <li><a class="dropdown-item py-2 rounded" href=\'' . url('/guest/Tax/edit/' . $value->id) . '\'>แก้ไขรายการ</a></li>';
                $btn_action .='</ul>';
                $btn_action .='</div>';

                $data[] = [
                    'number' => $key + 1,
                    'Profile_ID_TAX'=>$value->GuestTax_ID,
                    'Company/Individual'=>$btn_Company,
                    'Branch'=> $value->BranchTax,
                    'Status'=>$btn_status,
                    'Order' => $btn_action,
                ];
            }
        }
        return response()->json([
            'data' => $data,
        ]);
    }
    public function guest_edit_tax($id)
    {
        $Guest = guest_tax::where('id',$id)->first();
        $guesttax = $Guest->id;
        $Profile_ID = $Guest->GuestTax_ID;
        $Company_ID = $Guest->Company_ID;
        $GuestID = Guest::where('Profile_ID', $Company_ID)->first();
        $ID = $GuestID->id;
        $phone = guest_tax_phone::where('GuestTax_ID',$Profile_ID)->get();
        $phonecount = guest_tax_phone::where('GuestTax_ID',$Profile_ID)->count();
        $phoneDataArray = $phone->toArray();
        $provinceNames = province::select('name_th','id')->get();
        $Tambon = districts::where('amphure_id', $Guest->Amphures)->select('name_th','id')->get();
        $amphures = amphures::where('province_id', $Guest->City)->select('name_th','id')->get();
        $Zip_code = districts::where('amphure_id', $Guest->Amphures)->select('zip_code','id')->get();
        $prefix = master_document::select('name_th','id')->where('status', 1)->Where('Category','Mprename')->get();
        $MCompany_type = master_document::select('name_th', 'id')->where('status', 1)->Where('Category','Mcompany_type')->get();
        return view('guest.edittax',compact('MCompany_type','prefix','Zip_code','amphures','Tambon','phoneDataArray','phonecount','phone','Profile_ID','Guest','provinceNames','ID'));
    }
    public function guest_update_tax(Request $request ,$id)
    {
        $data = $request->all();
        $guest = guest_tax::where('id', $id)->first();
        $GuestTax_ID = $guest->GuestTax_ID;
        $Profile_IDGuest = $guest->Company_ID;
        $Guest = Guest::where('Profile_ID', $Profile_IDGuest)->first();
        $ids = $Guest->id;
        $phone = guest_tax_phone::where('GuestTax_ID', $GuestTax_ID)->get();
        $dataArray = $guest->toArray(); // แปลงข้อมูลบริษัทเป็น array
        $dataArray['phone'] = $phone->pluck('Phone_number')->toArray(); // เพิ่มค่า phone เข้าไปใน $dataArray
        $datarequest = [
            'Tax_Type' => $data['Tax_Type'] ?? null,
            'Company_type' => $data['Company_type'] ?? null,
            'Company_name' => $data['Company_name'] ?? null,
            'BranchTax' => $data['Branch'] ?? null,
            'first_name' => $data['first_name'] ?? null,
            'last_name' => $data['last_name'] ?? null,
            'Taxpayer_Identification' => $data['Taxpayer_Identification'] ?? null,
            'Company_Email' => $data['Company_Email'] ?? null,
            'Address' => $data['Address'] ?? null,
            'Country' => $data['Country'] ?? null,
            'City' => $data['City'] ?? null,
            'Amphures' => $data['Amphures'] ?? null,
            'Tambon' => $data['Tambon'] ?? null,
            'Zip_Code' => $data['Zip_Code'] ?? null,
            'phone' => $data['phoneCom'] ?? null,

        ];
        $keysToCompare = ['Tax_Type', 'Company_type', 'Company_name', 'BranchTax', 'first_name', 'last_name', 'Taxpayer_Identification', 'Company_Email', 'Address',
            'Country', 'City', 'Amphures', 'Tambon', 'Zip_Code', 'phone'];
        $differences = [];

        foreach ($keysToCompare as $key) {
            if (isset($dataArray[$key]) && isset($datarequest[$key])) {
                // ตรวจสอบชนิดข้อมูลก่อน
                if (is_array($dataArray[$key]) && is_array($datarequest[$key])) {
                    // เปรียบเทียบอาร์เรย์
                    $onlyInDataArray = array_diff($dataArray[$key], $datarequest[$key]);
                    $onlyInRequest = array_diff($datarequest[$key], $dataArray[$key]);
                } else {
                    // เปรียบเทียบค่าสตริงหรือค่าทั่วไป
                    if ($dataArray[$key] !== $datarequest[$key]) {
                        $onlyInDataArray = $dataArray[$key] !== null ? [$dataArray[$key]] : [];
                        $onlyInRequest = $datarequest[$key] !== null ? [$datarequest[$key]] : [];
                    } else {
                        $onlyInDataArray = [];
                        $onlyInRequest = [];
                    }
                }

                // ตรวจสอบว่ามีค่าที่แตกต่างหรือไม่
                if (!empty($onlyInDataArray) || !empty($onlyInRequest)) {
                    $differences[$key] = [
                        'dataArray' => $onlyInDataArray,
                        'request' => $onlyInRequest
                    ];
                }
            }
        }

        $extractedData = [];
        $extractedDataA = [];
        // วนลูปเพื่อดึงชื่อคีย์และค่าจาก request
        foreach ($differences as $key => $value) {
            if ($key === 'phone') {
                // ถ้าเป็น phoneCom ให้เก็บค่า request ทั้งหมดใน array
                $extractedData[$key] = $value['request'];
                $extractedDataA[$key] = $value['dataArray'];
            } elseif (isset($value['request'][0])) {
                // สำหรับคีย์อื่นๆ ให้เก็บค่าแรกจาก array
                $extractedData[$key] = $value['request'][0];
            }else{
                $extractedDataA[$key] = $value['dataArray'][0];
            }
        }
        $Tax_Type = $extractedData['Tax_Type'] ?? null;
        $Company_type = $extractedData['Company_type'] ?? null;
        $Company_name = $extractedData['Company_name'] ?? null;
        $Branch = $extractedData['BranchTax'] ?? null;
        $first_name = $extractedData['first_name'] ?? null;
        $last_name = $extractedData['last_name'] ?? null;
        $Identification = $extractedData['Taxpayer_Identification'] ?? null;
        $Email = $extractedData['Company_Email'] ?? null;
        $Address = $extractedData['Address'] ?? null;
        $Country = $extractedData['Country'] ?? null;
        $City = $extractedData['City'] ?? null;
        $Amphures = $extractedData['Amphures'] ?? null;
        $Tambon = $extractedData['Tambon'] ?? null;
        $Zip_Code = $extractedData['Zip_Code'] ?? null;
        $phone = $extractedData['phone'] ?? null;
        $phoneA = $extractedDataA['phone'] ?? null;


        $AddressIndividual = null;
        if ($datarequest['Country'] == 'Thailand') {

            $provinceNames = province::where('id', $City)->first();
            $TambonID = districts::where('id',$Tambon)->select('name_th','id','zip_code')->first();
            $amphuresID = amphures::where('id',$Amphures)->select('name_th','id')->first();
            $provinceNames = $provinceNames->name_th;
            $TambonCheck = $TambonID->name_th;
            $amphures = $amphuresID->name_th;
            $Zip_code = $TambonID->zip_code;
            $AddressCheck = null;
            if ($Address) {
                $AddressCheck = 'ที่อยู่ : '.$Address;
            }
            $CountryCheck = null;
            if ($Country) {
                $CountryCheck = ' ประเทศ : '.$Country;
            }
            $AddressIndividual = $AddressCheck.'+'.$CountryCheck.'+'.' ตำบล : '.$Tambon.'+'.' อำเภอ : '.$amphures.'+'.' จังหวัด : '.$provinceNames.' '.$Zip_code;

        }else{

            $AddressCheck = null;
            if ($Address) {
                $AddressCheck = 'ที่อยู่ : '.$Address;
            }
            $CountryCheck = null;
            if ($Country) {
                $CountryCheck = ' ประเทศ : '.$Country;
            }
            $AddressIndividual = $AddressCheck.'+'.$CountryCheck;
        }

        $EmailTax = null;
        if ($Email) {
            $EmailTax = 'อีเมล์ : '.$Email;
        }
        $IdentificationTax = null;
        if ($Identification) {
            $IdentificationTax = 'เลขบัตรประจำตัว : '.$Identification;
        }
        $phoneTax = null;
        if ($phone) {
            $phoneTax = 'เพิ่มเบอร์โทรศัพท์ : ' . implode(', ', $phone);
        }
        $phoneTaxA = null;
        if ($phoneA) {
            $phoneTaxA = 'ลบเบอร์โทรศัพท์ : ' . implode(', ', $phoneA);
        }
        $TaxType = null;
        $comtypefullname = null;
        if ($Tax_Type) {
            if ($Company_type >= 30) {
                $Mprefix = master_document::where('id', $Company_type)->where('Category', 'Mprename')->first();
                if ($Mprefix) {
                    $prename = $Mprefix->name_th;
                    $comtypefullname = 'คำนำหน้า : ' . $prename.'+'.' ชื่อ : ' . $request->first_name . ' ' . $request->last_name;
                }
            }else{
                $comtype = master_document::where('id', $Company_type)->where('Category', 'Mcompany_type')->first();
                if ($comtype && $Company_name) {
                    if ($comtype->name_th == "บริษัทจำกัด") {
                        $comtypefullname = "บริษัท " . $request->Company_name . " จำกัด";
                    } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                        $comtypefullname = "บริษัท " . $request->Company_name . " จำกัด (มหาชน)";
                    } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                        $comtypefullname = "ห้างหุ้นส่วนจำกัด " . $request->Company_name;
                    }
                }elseif ($request->Branch) {
                    $comtypefullname = 'สาขา : ' . $request->Branch;
                }elseif ($comtype) {
                    $comtypefullname = 'ประเภทบริษัท : ' . $comtype->name_th;
                }
            }
            $TaxType = 'ประเภทภาษี : '.$Tax_Type;
        }else{
            if ($request->Tax_Type == 'Individual') {
                if ($Company_type >= 30 && $first_name && $last_name) {
                    $Mprefix = master_document::where('id', $Company_type)->where('Category', 'Mprename')->first();
                    if ($Mprefix) {
                        if ($Mprefix->name_th == "นาย") {
                            $comtypefullname = 'ชื่อ : '."นาย " . $first_name . ' ' . $last_name;
                        } elseif ($Mprefix->name_th == "นาง") {
                            $comtypefullname = 'ชื่อ : '."นาง " . $first_name . ' ' . $last_name;
                        } elseif ($Mprefix->name_th == "นางสาว") {
                            $comtypefullname = 'ชื่อ : '."นางสาว " . $first_name . ' ' . $last_name;
                        }
                    }

                }
                elseif ($Company_type >= 30 && $first_name) {
                    $Mprefix = master_document::where('id', $Company_type)->where('Category', 'Mprename')->first();
                    if ($Mprefix) {
                        $prename = $Mprefix->name_th;
                        $comtypefullname = 'คำนำหน้า : ' . $prename.'+'.' ชื่อ : ' . $first_name;
                    }
                }
                elseif ($Company_type >= 30 && $last_name) {
                    $Mprefix = master_document::where('id', $Company_type)->where('Category', 'Mprename')->first();
                    if ($Mprefix) {
                        $prename = $Mprefix->name_th;
                        $comtypefullname = 'คำนำหน้า : ' . $prename.'+'.' นามสกุล : ' . $last_name;;
                    }
                }
                elseif ($Company_type >= 30) {
                    $Mprefix = master_document::where('id', $Company_type)->where('Category', 'Mprename')->first();
                    if ($Mprefix) {
                        $prename = $Mprefix->name_th;
                        $comtypefullname = 'คำนำหน้า : ' . $prename;
                    }
                }
                elseif ($first_name && $last_name) {
                    $comtypefullname = 'ชื่อ : ' . $first_name . ' ' . $last_name;
                } elseif ($first_name) {
                    $comtypefullname = 'ชื่อ : ' . $first_name;
                } elseif ($last_name) {
                    $comtypefullname = 'นามสกุล : ' . $last_name;
                }
            }else{
                if ($Company_type) {
                    if ($Company_type < 30 && $Company_name) {
                        $comtype = master_document::where('id', $Company_type)->where('Category', 'Mcompany_type')->first();
                        if ($comtype) {
                            if ($comtype->name_th == "บริษัทจำกัด") {
                                $comtypefullname = "บริษัท " . $request->Company_name . " จำกัด";
                            } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                                $comtypefullname = "บริษัท " . $request->Company_name . " จำกัด (มหาชน)";
                            } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                                $comtypefullname = "ห้างหุ้นส่วนจำกัด " . $request->Company_name;
                            }
                        }
                    }elseif ($Company_type < 30 && $Branch) {
                        $comtype = master_document::where('id', $Company_type)->where('Category', 'Mcompany_type')->first();
                        if ($comtype) {
                            $comtype = $comtype->name_th;
                            $comtypefullname = 'ประเภทบริษัท : ' . $comtype.'+'.' สาขา : ' . $Branch;
                        }
                    }elseif ($Company_type < 30) {
                        $comtype = master_document::where('id', $Company_type)->where('Category', 'Mcompany_type')->first();
                        if ($comtype) {
                            $comtype = $comtype->name_th;
                            $comtypefullname = 'ประเภทบริษัท : ' . $comtype;
                        }
                    }
                }
                elseif ($Company_name && $Branch) {
                    $comtypefullname = 'ชื่อบริษัท : ' . $Company_name.'+'.' สาขา : ' . $Branch;
                }
                elseif ($Company_name) {
                    $comtypefullname = 'ชื่อบริษัท : ' . $Company_name;
                }
                elseif ($Branch) {
                    $comtypefullname = 'สาขา : ' . $Branch;
                }
            }
        }
        if ($Profile_IDGuest) {
            $Company = 'รหัสลูกค้า : '.$Profile_IDGuest;
        }
        $Profile = 'รหัส : '.$GuestTax_ID;
        $datacompany = '';

        $variables = [$Profile,$Company,$TaxType,$comtypefullname, $EmailTax, $IdentificationTax, $AddressIndividual, $phone ,$phoneA];

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
        $save->Company_ID = $Profile_IDGuest;
        $save->type = 'Update';
        $save->Category = 'Edit :: Additional Guest Tax Invoice';
        $save->content =$datacompany;
        $save->save();
        try {
            if ($request->Tax_Type == 'Company') {
                $save = guest_tax::find($id);
                $save->Company_type = $request->Company_type;
                $save->Company_name =$request->Company_name;
                $save->Tax_Type = 'Company';
                $save->BranchTax = $request->Branch;

                if ($request->Country == "Other_countries") {
                    $save->Country =$request->Country;
                    $save->Address =$request->Address;
                }else {
                    $save->Country =$request->Country;
                    $save->City =$request->City;
                    $save->Amphures =$request->Amphures;
                    $save->Tambon =$request->Tambon;
                    $save->Address =$request->Address;
                    $save->Zip_Code = $request->Zip_Code;
                }
                $save->Company_Email = $request->Company_Email;
                $save->Taxpayer_Identification = $request->Taxpayer_Identification;
                $Guest = guest_tax::find($id);
                $Profile_ID = $Guest->GuestTax_ID;// กำหนดค่า Profile_ID ที่ต้องการใช้งาน
                if ($Profile_ID) {
                    $profilePhones = guest_tax_phone::where('GuestTax_ID', $Profile_ID)->get();
                    foreach ($profilePhones as $phone) {
                        $phone->delete();
                    }
                }
                foreach ($request->phoneCom as $index => $phoneNumber) {
                    if ($phoneNumber !== null) {
                        $phoneGuest = new guest_tax_phone();
                        $phoneGuest->GuestTax_ID = $Profile_ID;
                        $phoneGuest->Phone_number = $phoneNumber;
                        $phoneGuest->Sequence = ($index === 0) ? 'main' : 'secondary'; // กำหนดค่า Sequence
                        $phoneGuest->save();
                    }
                }
                $save->save();
            }else {
                $save = guest_tax::find($id);
                $save->Company_type = $request->Company_type;
                $save->first_name =$request->first_name;
                $save->last_name =$request->last_name;
                $save->Tax_Type = 'Individual';
                $save->BranchTax = $request->Branch;

                if ($request->Country == "Other_countries") {
                    $save->Country =$request->Country;
                    $save->Address =$request->Address;
                }else {
                    $save->Country =$request->Country;
                    $save->City =$request->City;
                    $save->Amphures =$request->Amphures;
                    $save->Tambon =$request->Tambon;
                    $save->Address =$request->Address;
                    $save->Zip_Code = $request->Zip_Code;
                }
                $save->Company_Email = $request->Company_Email;
                $save->Taxpayer_Identification = $request->Taxpayer_Identification;
                $Guest = guest_tax::find($id);
                $Profile_ID = $Guest->GuestTax_ID;// กำหนดค่า Profile_ID ที่ต้องการใช้งาน
                if ($Profile_ID) {
                    $profilePhones = guest_tax_phone::where('GuestTax_ID', $Profile_ID)->get();
                    foreach ($profilePhones as $phone) {
                        $phone->delete();
                    }
                }
                foreach ($request->phoneCom as $index => $phoneNumber) {
                    if ($phoneNumber !== null) {
                        $phoneGuest = new guest_tax_phone();
                        $phoneGuest->GuestTax_ID = $Profile_ID;
                        $phoneGuest->Phone_number = $phoneNumber;
                        $phoneGuest->Sequence = ($index === 0) ? 'main' : 'secondary'; // กำหนดค่า Sequence
                        $phoneGuest->save();
                    }
                }
                $save->save();
            }
            return redirect()->route('guest_edit', ['id' => $ids])->with('success', 'บันทึกข้อมูลเรียบร้อยแล้ว');
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function guest_view_tax($id)
    {
        $Guest = guest_tax::where('id',$id)->first();
        $guesttax = $Guest->id;
        $Profile_ID = $Guest->GuestTax_ID;
        $Company_ID = $Guest->Company_ID;
        $GuestID = Guest::where('Profile_ID', $Company_ID)->first();
        $ID = $GuestID->id;
        $phone = guest_tax_phone::where('GuestTax_ID',$Profile_ID)->get();
        $phonecount = guest_tax_phone::where('GuestTax_ID',$Profile_ID)->count();
        $phoneDataArray = $phone->toArray();
        $provinceNames = province::select('name_th','id')->get();
        $Tambon = districts::where('amphure_id', $Guest->Amphures)->select('name_th','id')->get();
        $amphures = amphures::where('province_id', $Guest->City)->select('name_th','id')->get();
        $Zip_code = districts::where('amphure_id', $Guest->Amphures)->select('zip_code','id')->get();
        $prefix = master_document::select('name_th','id')->where('status', 1)->Where('Category','Mprename')->get();
        $MCompany_type = master_document::select('name_th', 'id')->where('status', 1)->Where('Category','Mcompany_type')->get();
        return view('guest.viewtax',compact('MCompany_type','prefix','Zip_code','amphures','Tambon','phoneDataArray','phonecount','phone','Profile_ID','Guest','provinceNames','ID'));
    }

     //-----------------------------------Visit-------------------------------
    public function search_table_guest_Visit(Request $request)
    {
        $perPage = (int)$request->perPage;
        $search_value = $request->search_value;
        $guest_profile = $request->guest_profile;
        if ($search_value) {
            $data_query = Quotation::where('Quotation_ID', 'LIKE', '%'.$search_value.'%')
            ->orWhere('checkin', 'LIKE', '%'.$search_value.'%')
            ->orWhere('checkout', 'LIKE', '%'.$search_value.'%')
            ->orWhere('issue_date', 'LIKE', '%'.$search_value.'%')
            ->orWhere('Expirationdate', 'LIKE', '%'.$search_value.'%')
            ->where('Company_ID',$guest_profile)
            ->paginate($perPage);
        }else{
            $perPageS = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
            $data_query = Quotation::where('Company_ID',$guest_profile)->paginate($perPageS);
        }
        $data = [];
        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $btn_status = "";
                $btn_dis = "";
                $btn_date_in = "";
                $btn_date_out = "";
                $btn_action = "";
                $name = "";
                $name = '<td>' . @$value->guest->First_name . ' ' . @$value->guest->Last_name . '</td>';
                if ($value->checkin) {
                    $btn_date_in =   \Carbon\Carbon::parse($value->checkin)->format('d/m/Y');
                    $btn_date_out =   \Carbon\Carbon::parse($value->checkout)->format('d/m/Y');
                }else {
                    $btn_date_in = '-';
                    $btn_date_out = '-';
                }
                if ($value->SpecialDiscountBath == 0) {
                    $btn_dis = '-';
                }else {
                    $btn_dis = $value->SpecialDiscountBath;
                }
                if ($value->status_guest == 1){
                    $btn_status = '<span class="badge rounded-pill bg-success">Approved</span>';
                }else{
                    if ($value->status_document == 0){
                        $btn_status = '<span class="badge rounded-pill bg-danger">Cancel</span>';
                    }elseif($value->status_document == 1){
                        $btn_status = '<span class="badge rounded-pill "style="background-color: #FF6633">Pending</span>';
                    }elseif($value->status_document == 2){
                        $btn_status = '<span class="badge rounded-pill bg-warning">Awaiting Approva</span>';
                    }elseif($value->status_document == 3){
                        $btn_status = '<span class="badge rounded-pill "style="background-color: #FF6633">Pending</span>';
                    }elseif($value->status_document == 4){
                        $btn_status = '<span class="badge rounded-pill "style="background-color:#1d4ed8">Reject</span>';
                    }elseif($value->status_document == 6){
                        $btn_status = '<span class="badge rounded-pill "style="background-color: #FF6633">Pending</span>';
                    }
                }
                $btn_action .='<div class="btn-group">';
                $btn_action .='<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">ทำรายการ &nbsp;</button>';
                $btn_action .='<ul class="dropdown-menu border-0 shadow p-3">';
                $btn_action .=' <li><a class="dropdown-item py-2 rounded" target="_bank" href=\'' . url('/Proposal/Quotation/cover/document/PDF/' . $value->id) . '\'>Export</a></li>';
                $btn_action .='</ul>';
                $btn_action .='</div>';
                $data[] = [
                    'number' => $key + 1,
                    'ID'=>$value->Quotation_ID,
                    'Company'=>$name,
                    'IssueDate'=> $value->issue_date,
                    'ExpirationDate'=>$value->Expirationdate,
                    'CheckIn' => $btn_date_in,
                    'CheckOut' => $btn_date_out,
                    'Discount' => $btn_dis,
                    'OperatedBy' => @$value->userOperated->name,
                    'Documentstatus' => $btn_status,
                    'Order'=>$btn_action,
                ];
            }
        }
        return response()->json([
            'data' => $data,
        ]);
    }
    public function  paginate_table_guest_Visit(Request $request)
    {
        $perPage = (int)$request->perPage;
        $guest_profile = $request->guest_profile;
        $data = [];
        if ($perPage == 10) {
            $data_query = Quotation::where('Company_ID',$guest_profile)->limit($request->page.'0')->get();
        } else {
            $data_query = Quotation::where('Company_ID',$guest_profile)->paginate($perPage);
        }

        $page_1 = $request->page == 1 ? 1 : ($request->page - 1).'1';
        $page_2 = $request->page.'0';
        $perPage2 = $request->perPage > 10 ? $request->perPage : 10;
        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $btn_status = "";
                $btn_dis = "";
                $btn_date_in = "";
                $btn_date_out = "";
                $btn_action = "";
                $name = "";
                if (($key + 1) >= (int)$page_1 && ($key + 1) <= (int)$page_2 || (int)$perPage > 10 && $key < (int)$perPage2) {
                    $name = '<td>' . @$value->guest->First_name . ' ' . @$value->guest->Last_name . '</td>';
                    if ($value->checkin) {
                        $btn_date_in =   \Carbon\Carbon::parse($value->checkin)->format('d/m/Y');
                        $btn_date_out =   \Carbon\Carbon::parse($value->checkout)->format('d/m/Y');
                    }else {
                        $btn_date_in = '-';
                        $btn_date_out = '-';
                    }
                    if ($value->SpecialDiscountBath == 0) {
                        $btn_dis = '-';
                    }else {
                        $btn_dis = $value->SpecialDiscountBath;
                    }
                    if ($value->status_guest == 1){
                        $btn_status = '<span class="badge rounded-pill bg-success">Approved</span>';
                    }else{
                        if ($value->status_document == 0){
                            $btn_status = '<span class="badge rounded-pill bg-danger">Cancel</span>';
                        }elseif($value->status_document == 1){
                            $btn_status = '<span class="badge rounded-pill "style="background-color: #FF6633">Pending</span>';
                        }elseif($value->status_document == 2){
                            $btn_status = '<span class="badge rounded-pill bg-warning">Awaiting Approva</span>';
                        }elseif($value->status_document == 3){
                            $btn_status = '<span class="badge rounded-pill "style="background-color: #FF6633">Pending</span>';
                        }elseif($value->status_document == 4){
                            $btn_status = '<span class="badge rounded-pill "style="background-color:#1d4ed8">Reject</span>';
                        }elseif($value->status_document == 6){
                            $btn_status = '<span class="badge rounded-pill "style="background-color: #FF6633">Pending</span>';
                        }
                    }
                    $btn_action .='<div class="btn-group">';
                    $btn_action .='<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">ทำรายการ &nbsp;</button>';
                    $btn_action .='<ul class="dropdown-menu border-0 shadow p-3">';
                    $btn_action .=' <li><a class="dropdown-item py-2 rounded" target="_bank" href=\'' . url('/Proposal/Quotation/cover/document/PDF/' . $value->id) . '\'>Export</a></li>';
                    $btn_action .='</ul>';
                    $btn_action .='</div>';
                    $data[] = [
                        'number' => $key + 1,
                        'ID'=>$value->Quotation_ID,
                        'Company'=> $name,
                        'IssueDate'=> $value->issue_date,
                        'ExpirationDate'=>$value->Expirationdate,
                        'CheckIn' => $btn_date_in,
                        'CheckOut' => $btn_date_out,
                        'Discount' => $btn_dis,
                        'OperatedBy' => @$value->userOperated->name,
                        'Documentstatus' => $btn_status,
                        'Order'=>$btn_action,
                    ];
                }
            }
        }

        return response()->json([
            'data' => $data,
        ]);
    }

}
