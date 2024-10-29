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
use App\Models\Quotation;
use App\Models\company_tax_phone;
use App\Models\company_tax;
use App\Models\log_company;
use App\Models\country;
use Auth;
class CompanyController extends Controller
{

    public function index($menu)
    {
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $Company = companys::query()
            ->leftJoin('company_phones', function($join) {
                $join->on('companys.Profile_ID', '=', 'company_phones.Profile_ID')
                    ->where('company_phones.Sequence', '=', 'main'); // เช็คว่า Sequence เป็น 'main'
            })
            ->where('companys.status', 1)
            ->select('companys.*', DB::raw('GROUP_CONCAT(company_phones.Phone_number) as Phone_numbers')) // รวมหมายเลขโทรศัพท์ที่มี Sequence = 'main'
            ->groupBy('companys.id') // จัดกลุ่มข้อมูลตาม ID ของบริษัท
            ->orderBy('companys.id', 'asc')
            ->paginate($perPage);
        $exp = explode('.', $menu);
        if (count($exp) > 1) {
            $search = $exp[1];
            if ($search == "all") {
                $Company = companys::query()
                    ->leftJoin('company_phones', 'companys.Profile_ID', '=', 'company_phones.Profile_ID')
                    ->where('companys.status', 1)
                    ->select('companys.*', 'company_phones.Phone_number as Phone_number')
                    ->orderBy('companys.id', 'asc')
                    ->paginate($perPage);
            }elseif ($search == 'ac') {
                $Company = companys::query()
                    ->leftJoin('company_phones', 'companys.Profile_ID', '=', 'company_phones.Profile_ID')
                    ->where('companys.status', 1)
                    ->select('companys.*', 'company_phones.Phone_number as Phone_number')
                    ->orderBy('companys.id', 'asc')
                    ->paginate($perPage);
            }else {
                $Company = companys::query()
                    ->leftJoin('company_phones', 'companys.Profile_ID', '=', 'company_phones.Profile_ID')
                    ->where('companys.status', 0)
                    ->select('companys.*', 'company_phones.Phone_number as Phone_number')
                    ->orderBy('companys.id', 'asc')
                    ->paginate($perPage);
            }
        }
        return view('company.index',compact('Company','menu'));
    }
    public function company_paginate_table(Request $request)
    {
        $perPage = (int)$request->perPage;

        $data = [];
        if ($perPage == 10) {
            $data_query = companys::query()
            ->leftJoin('company_phones', 'companys.Profile_ID', '=', 'company_phones.Profile_ID')
            ->where('companys.status', 1)
            ->select('companys.*', 'company_phones.Phone_number as Phone_number')
            ->orderBy('companys.id', 'asc')
            ->limit($request->page.'0')
            ->get();
        } else {
            $data_query = companys::query()
            ->leftJoin('company_phones', 'companys.Profile_ID', '=', 'company_phones.Profile_ID')
            ->where('companys.status', 1)
            ->select('companys.*', 'company_phones.Phone_number as Phone_number')
            ->orderBy('companys.id', 'asc')
            ->paginate($perPage);
        }
        $page_1 = $request->page == 1 ? 1 : ($request->page - 1).'1';
        $page_2 = $request->page.'0';

        $perPage2 = $request->perPage > 10 ? $request->perPage : 10;

        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $btn_action = "";
                $btn_status = "";
                if (($key + 1) >= (int)$page_1 && ($key + 1) <= (int)$page_2 || (int)$perPage > 10 && $key < (int)$perPage2) {
                    $rolePermission = Auth::user()->rolePermissionData(Auth::user()->id);
                    $canViewProposal = Auth::user()->roleMenuView('Company / Agent', Auth::user()->id);
                    $canEditProposal = Auth::user()->roleMenuEdit('Company / Agent', Auth::user()->id);
                    $btn_action .='<div class="btn-group">';
                    $btn_action .='<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>';
                    $btn_action .='<ul class="dropdown-menu border-0 shadow p-3">';
                    if ($rolePermission > 0) {
                        if ($canViewProposal) {
                            $btn_action .=' <li><a class="dropdown-item py-2 rounded" href=\'' . url('/Company/view/' . $value->id) . '\'>View</a></li>';
                        }
                        if ($canEditProposal) {
                            $btn_action .= ' <li><a class="dropdown-item py-2 rounded" href=\'' . url('/Company/edit/' . $value->id) . '\'>Edit</a></li>';
                        }
                    } else {
                        if ($canViewProposal) {
                            $btn_action .=' <li><a class="dropdown-item py-2 rounded" href=\'' . url('/Company/view/' . $value->id) . '\'>View</a></li>';
                        }
                    }
                    $btn_action .='</ul>';
                    $btn_action .='</div>';


                    if ($value->status == 1) {
                        $btn_status = '<button type="button" class="btn btn-light-success btn-sm" value="'.$value->id.'" onclick="btnstatus('.$value->id.')">ใช้งาน</button>';
                    } else {
                        $btn_status = '<button type="button" class="btn btn-light-danger btn-sm" value="'.$value->id.'" onclick="btnstatus('.$value->id.')">ปิดใช้งาน</button>';
                    }
                    $formattedPhone = substr($value->Phone_number, 0, 3) . '-' . substr($value->Phone_number, 3, 3) . '-' . substr($value->Phone_number, 6);
                    $data[] = [
                        'number' => $key + 1,
                        'Profile_ID'=>$value->Profile_ID,
                        'Company_Name'=>$value->Company_Name,
                        'Branch'=> $value->Branch,
                        'Phone'=>$formattedPhone,
                        'Status'=> $btn_status,
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
    public function company_search_table(Request $request)
    {
        $perPage = (int)$request->perPage;
        $search_value = $request->search_value;
        $guest_profile = $request->guest_profile;
        if ($search_value) {
            $data_query = companys::query()
            ->where('companys.Company_name', 'LIKE', '%'.$search_value.'%')
            ->orWhere('companys.Branch', 'LIKE', '%'.$search_value.'%')
            ->leftJoin('company_phones', 'companys.Profile_ID', '=', 'company_phones.Profile_ID')
            ->where('companys.status', 1)
            ->select('companys.*', 'company_phones.Phone_number as Phone_number')
            ->orderBy('companys.id', 'asc')
            ->paginate($perPage);
        }else{
            $perPageS = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
            $data_query = companys::query()
            ->leftJoin('company_phones', 'companys.Profile_ID', '=', 'company_phones.Profile_ID')
            ->where('companys.status', 1)
            ->select('companys.*', 'company_phones.Phone_number as Phone_number')
            ->orderBy('companys.id', 'asc')
            ->paginate($perPageS);
        }
        $data = [];
        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $btn_action = "";
                $btn_status = "";
                $rolePermission = Auth::user()->rolePermissionData(Auth::user()->id);
                $canViewProposal = Auth::user()->roleMenuView('Company / Agent', Auth::user()->id);
                $canEditProposal = Auth::user()->roleMenuEdit('Company / Agent', Auth::user()->id);
                $btn_action .='<div class="btn-group">';
                $btn_action .='<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>';
                $btn_action .='<ul class="dropdown-menu border-0 shadow p-3">';
                if ($rolePermission > 0) {
                    if ($canViewProposal) {
                        $btn_action .=' <li><a class="dropdown-item py-2 rounded" href=\'' . url('/Company/view/' . $value->id) . '\'>View</a></li>';
                    }
                    if ($canEditProposal) {
                        $btn_action .= ' <li><a class="dropdown-item py-2 rounded" href=\'' . url('/Company/edit/' . $value->id) . '\'>Edit</a></li>';
                    }
                } else {
                    if ($canViewProposal) {
                        $btn_action .=' <li><a class="dropdown-item py-2 rounded" href=\'' . url('/Company/view/' . $value->id) . '\'>View</a></li>';
                    }
                }
                $btn_action .='</ul>';
                $btn_action .='</div>';
                if ($value->status == 1) {
                    $btn_status = '<button type="button" class="btn btn-light-success btn-sm" value="'.$value->id.'" onclick="btnstatus('.$value->id.')">ใช้งาน</button>';
                } else {
                    $btn_status = '<button type="button" class="btn btn-light-danger btn-sm" value="'.$value->id.'" onclick="btnstatus('.$value->id.')">ปิดใช้งาน</button>';
                }
                $formattedPhone = substr($value->Phone_number, 0, 3) . '-' . substr($value->Phone_number, 3, 3) . '-' . substr($value->Phone_number, 6);
                $data[] = [
                    'number' => $key + 1,
                    'Profile_ID'=>$value->Profile_ID,
                    'Company_Name'=>$value->Company_Name,
                    'Branch'=> $value->Branch,
                    'Phone'=>$formattedPhone,
                    'Status'=> $btn_status,
                    'btn_action' => $btn_action,
                ];
            }
        }
        // dd($data);
        return response()->json([
            'data' => $data,
        ]);
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
        $latestAgent = 1;
        $A_Profile = $latestAgent;
        $country = country::select('ct_nameENG')->get();
        $provinceNames = province::select('name_th','id')->get();
        $booking_channel = master_document::select('name_en', 'id')->where('status', 1)->Where('Category','Mbooking_channel')->get();
        $MCompany_type = master_document::select('name_th', 'id')->where('status', 1)->Where('Category','Mcompany_type')->get();
        $Mmarket = master_document::select('name_th', 'id')->where('status', 1)->Where('Category','Mmarket')->get();
        $Mprefix = master_document::select('name_th','id')->where('status', 1)->Where('Category','Mprename')->get();
        return view('company.create',compact('booking_channel','provinceNames','MCompany_type','Mmarket','Mprefix','N_Profile','A_Profile','country'));
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
                return redirect()->route('Company.create')->with('error', 'ชื่อบริษัทและสาขาซ้ำกรุณากรอกใหม่');
            }
        } else {
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


            try {
                {
                    $CountryOther = $request->countrydata;
                    $Branch = $request->Branch;
                    $amphures = $request->amphures;
                    $Tambon = $request->Tambon;
                    $zip_code = $request->zip_code;
                    $city = $request->city;
                    $Address= $request->address;
                    $phone_company = $request->phone_company;
                    $fax = $request->fax;
                    $contract_rate_start_date = $request->contract_rate_start_date;
                    $contract_rate_end_date = $request->contract_rate_end_date;
                    $Lastest_Introduce_By = $request->Lastest_Introduce_By;
                    $Company_type =$request->Company_type;
                    $Company_Name = $request->Company_Name;
                    $Market =$request->Mmarket;
                    $Company_Email = $request->Company_Email;
                    $Company_Website = $request->Company_Website;
                    $address = $request->address;
                    $Taxpayer_Identification = $request->Taxpayer_Identification;
                    $booking_channel = $request->booking_channel;
                    $comtype = master_document::where('id', $Company_type)->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $comtypefullname = "บริษัท ". $Company_Name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $comtypefullname = "บริษัท ". $Company_Name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $comtypefullname = "ห้างหุ้นส่วนจำกัด ". $Company_Name ;
                    }else{
                        $comtypefullname = $comtype->name_th. $Company_Name ;
                    }
                    if ($CountryOther == 'Thailand') {
                        $provinceNames = province::where('id', $city)->first();
                        $TambonID = districts::where('id',$Tambon)->select('name_th','id','zip_code')->first();
                        $amphuresID = amphures::where('id',$amphures)->select('name_th','id')->first();
                        $provinceNames = $provinceNames->name_th;
                        $Tambon = $TambonID->name_th;
                        $amphures = $amphuresID->name_th;
                        $Zip_code = $TambonID->zip_code;
                        $AddressIndividual = 'ที่อยู่ : '.$address.' ตำบล : '.$Tambon.' อำเภอ : '.$amphures.' จังหวัด : '.$provinceNames.' '.$Zip_code;
                    }elseif ($City) {
                        $AddressIndividual = 'ที่อยู่ : '.$city;
                    }
                    if ($Company_Email) {
                        $Email = 'อีเมล์บริษัท : '.$Company_Email;
                    }
                    $Identification = null;
                    if ($Taxpayer_Identification) {
                        $Identification = 'เลขบัตรประจำตัว : '.$Taxpayer_Identification;
                    }
                    $Branchc = null;
                    if ($Branch) {
                        $Branchc = 'สาขา : '.$Branch;
                    }
                    $phone = null;
                    if ($phone_company) {
                        $phone = 'เพิ่มเบอร์โทรศัพท์ : ' . implode(', ', $phone_company);
                    }
                    $fax = null;
                    if ($fax) {
                        $fax = 'เพิ่มแฟกซ์ : ' . implode(', ', $fax);
                    }
                    if ($Market) {
                        $WMarket = master_document::where('id', $Market)->where('Category', 'Mmarket')->first();
                        $SMarket = $WMarket->name_th;
                        $Market = 'กลุ่มตลาด : '.$SMarket;
                    }
                    if ($booking_channel) {
                        $Booking = master_document::where('id', $booking_channel)->where('Category', 'Mbooking_channel')->first();
                        $BookingChannel = $Booking->name_th;
                        $Booking_Channel = 'ช่องทางการจอง : '.$BookingChannel;
                    }
                    if ($Company_Website) {
                        $Company_Website = 'เว็บไซต์ของบริษัท : '.$Company_Website;
                    }
                    if ($Lastest_Introduce_By) {
                        $Company_Website = 'ผู้แนะนำ : '.$Lastest_Introduce_By;
                    }
                    $Profile = 'รหัสบริษัท : '.$N_Profile;
                    $datacompany = '';

                    $variables = [$Profile,$comtypefullname , $Branchc, $AddressIndividual, $Email, $Identification,$Market ,$Booking_Channel,$Company_Website, $phone, $fax];

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
                    $save->Category = 'Create :: Company / Agent';
                    $save->content =$datacompany;
                    $save->save();
                }
                $save = new companys();
                $save->Profile_ID = $N_Profile;
                $save->Company_Name = $request->Company_Name;
                $save->Company_type = $request->Company_type;
                $save->Market =$request->Mmarket;
                $save->Booking_Channel = $request->booking_channel;
                if ($CountryOther != "Thailand") {
                    if ($city === null) {
                        return redirect()->back()->with('error', 'กรุณากรอกประเทศของคุณ');
                    }else {
                        $save->Country = $CountryOther;
                        $save->City = $city;
                        $save->Amphures = null;
                        $save->Address = $Address;
                        $save->Tambon = null;
                        $save->Zip_Code = null;
                    }
                }else {
                    $save->Country = $CountryOther;
                    $save->City = $city;
                    $save->Amphures = $request->amphures;
                    $save->Address = $Address;
                    $save->Tambon = $request->Tambon;
                    $save->Zip_Code = $request->zip_code;
                    $save->Branch = $Branch;
                }
                $save->Company_Email = $request->Company_Email;
                $save->Company_Website = $request->Company_Website;
                $save->Taxpayer_Identification = $request->Taxpayer_Identification;
                // $save->Discount_Contract_Rate = $request->Discount_Contract_Rate;
                $save->Contract_Rate_Start_Date = $contract_rate_start_date;
                $save->Contract_Rate_End_Date = $contract_rate_end_date;
                $save->Lastest_Introduce_By =$Lastest_Introduce_By;
                if ($phone_company !== null) {
                    foreach ($phone_company as $index => $phoneNumber) {
                        if ($phoneNumber !== null) {
                            $savephone = new company_phone();
                            $savephone->Profile_ID = $N_Profile;
                            $savephone->Phone_number = $phoneNumber;
                            $savephone->Sequence = ($index === 0) ? 'main' : 'secondary'; // กำหนดค่า Sequence
                            $savephone->save();
                        }
                    }
                }
                if ($fax !== null) {
                    foreach ($fax as $index => $faxNumber) {
                        if ($faxNumber !== null) {
                            $savefax = new company_fax();
                            $savefax->Profile_ID = $N_Profile;
                            $savefax->Fax_number = $faxNumber;
                            $savefax->Sequence = ($index === 0) ? 'main' : 'secondary'; // กำหนดค่า Sequence
                            $savefax->save();
                        }
                    }
                }
                $save->save();
            } catch (\Throwable $e) {
                return redirect()->route('Company','index')->with('error', $e->getMessage());
            }
            try {
                $latestAgent = representative::where('Company_Name', 'like', "%{$Company_Name}%")->where('Branch', 'like', "%{$Branch}%")->first();
                if ($latestAgent) {
                    $latestAgent = $latestAgent->Profile_ID + 1;
                } else {
                    $latestAgent = 1;
                }
                $A_Profile = $latestAgent;
                {
                    $countrydataA= $request->countrydataA;
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
                    $first_name = $request->first_nameAgent;
                    $last_name = $request->last_nameAgent;
                    $Preface = $request->Preface;
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

                    if ($countrydataA == 'Thailand') {
                        $provinceNames = province::where('id', $cityA)->first();
                        $TambonID = districts::where('id',$TambonA)->select('name_th','id','zip_code')->first();
                        $amphuresID = amphures::where('id',$amphuresA)->select('name_th','id')->first();
                        $provinceNames = $provinceNames->name_th;
                        $Tambon = $TambonID->name_th;
                        $amphures = $amphuresID->name_th;
                        $Zip_code = $TambonID->zip_code;
                        $AddressIndividual = 'ที่อยู่ : '.$addressAgent.' ตำบล : '.$Tambon.' อำเภอ : '.$amphures.' จังหวัด : '.$provinceNames.' '.$Zip_code;
                    }elseif ($cityA) {
                        $AddressIndividual = 'ที่อยู่ : '.$cityA;
                    }
                    if ($EmailAgent) {
                        $Email = 'อีเมล์ผู้ติดต่อ : '.$EmailAgent;
                    }
                    $Branch = null;
                    if ($Branch) {
                        $Branch = 'สาขา : '.$Branch;
                    }
                    $phone = null;
                    if ($phone) {
                        $phone = 'เพิ่มเบอร์โทรศัพท์ : ' . implode(', ', $phone);
                    }

                    $Profile = 'รหัสบริษัท : '.$N_Profile;
                    $ProfileContact = 'รหัสผู้ติดต่อ : '.$A_Profile;
                    $datacompanycontact = '';

                    $variables = [$Profile,$ProfileContact,$comtypefullname , $Branch, $AddressIndividual, $Email, $Identification, $phone];

                    foreach ($variables as $variable) {
                        if (!empty($variable)) {
                            if (!empty($datacompanycontact)) {
                                $datacompanycontact .= ' + ';
                            }
                            $datacompanycontact .= $variable;
                        }
                    }

                    $userid = Auth::user()->id;
                    $save = new log_company();
                    $save->Created_by = $userid;
                    $save->Company_ID = $N_Profile;
                    $save->type = 'Create';
                    $save->Category = 'Create :: Contact';
                    $save->content =$datacompanycontact;
                    $save->save();
                }

                $saveAgent = new representative();
                $saveAgent->Profile_ID = $A_Profile;
                $saveAgent->prefix = $request->Preface;
                $saveAgent->First_name = $request->first_nameAgent;
                $saveAgent->Last_name = $request->last_nameAgent;
                if ($countrydataA != "Thailand") {
                    $saveAgent->City = $cityA;
                    $saveAgent->Country = $countrydataA;
                    $saveAgent->Amphures = null;
                    $saveAgent->Address = $addressAgent;
                    $saveAgent->Tambon = null;
                    $saveAgent->Zip_Code = null;
                }else {
                    $saveAgent->Country = $countrydataA;
                    $saveAgent->City = $cityA;
                    $saveAgent->Amphures = $amphuresA;
                    $saveAgent->Address = $addressAgent;
                    $saveAgent->Tambon = $TambonA;
                    $saveAgent->Zip_Code = $zip_codeA;
                    $saveAgent->Email = $EmailAgent;
                    $saveAgent->Company_ID = $NProfile_ID;
                    $saveAgent->Company_Name = $Company_Name;
                    $saveAgent->Branch = $ABranch;
                    $phoneC = $request->phone;


                    foreach ($phoneC as $index => $phoneNumber) {
                        if ($phoneC !== null) {
                            $savephoneA = new representative_phone();
                            $savephoneA->Profile_ID = $A_Profile;
                            $savephoneA->Company_ID = $NProfile_ID;
                            $savephoneA->Phone_number = $phoneNumber;
                            $savephoneA->Sequence = ($index === 0) ? 'main' : 'secondary'; // กำหนดค่า Sequence
                            $savephoneA->save();
                        }
                    }
                    $saveAgent->save();
                }
            } catch (\Throwable $e) {
                companys::where('Profile_ID',$NProfile_ID)->delete();
                return redirect()->route('Company','index')->with('error', $e->getMessage());
            }
        }
        return redirect()->route('Company','index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }

    public function view($id)
    {
        $Company = companys::find($id);
        $Company_ID = $Company->Profile_ID;
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
        $phone = company_phone::where('Profile_ID', $Profile_ID)->get();
        $phonecount = company_phone::where('Profile_ID',$Profile_ID)->count();
        $phoneDataArray = $phone->toArray();

        $fax = company_fax::where('Profile_ID', 'like', "%{$Profile_ID}%")->get();
        $faxcount = company_fax::where('Profile_ID', 'like', "%{$Profile_ID}%")->count();
        $faxArray = $fax->toArray();
        $country = country::select('ct_nameENG')->get();
        $representative = representative::where('Company_ID', 'like', "%{$Company_ID}%")->where('status',1)->first();
        $representative_ID = $representative->Profile_ID;
        $repCompany_ID = $representative->Company_ID;
        $Mprefix = master_document::select('name_th','id')->where('status', 1)->Where('Category','Mprename')->get();
        $provinceNames = province::select('name_th','id')->get();
        $Tambon = districts::where('amphure_id', $Company->Amphures)->select('name_th','id')->get();
        $amphures = amphures::where('province_id', $Company->City)->select('name_th','id')->get();
        $Zip_code = districts::where('amphure_id', $Company->Amphures)->select('zip_code','id')->get();

        $phone = representative_phone::where('Profile_ID',$representative_ID)->where('Company_ID',$repCompany_ID)->get();
        $count = representative_phone::where('Profile_ID',$representative_ID)->where('Company_ID',$repCompany_ID)->count();
        $phoneArray = $phone->toArray();
        return view('company.view',compact('Company','booking_channel','provinceNames','Tambon','amphures',
        'Zip_code','faxArray','phoneDataArray','Company_Contact','Mmarket',
        'MCompany_type','Mprefix','phonecount','faxcount','Profile_ID','representative','Mprefix','provinceNames'
        ,'phoneArray','count','country'));

    }
    public function edit($id)
    {
            $Company = companys::where('id',$id)->first();
            $Company_ID = $Company->Profile_ID;
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
            $phone = company_phone::where('Profile_ID',$Profile_ID)->get();
            $phonecount = company_phone::where('Profile_ID',$Profile_ID)->count();
            $phoneDataArray = $phone->toArray();

            $fax = company_fax::where('Profile_ID',$Profile_ID)->get();
            $faxcount = company_fax::where('Profile_ID',$Profile_ID)->count();
            $faxArray = $fax->toArray();


            $Mprefix = master_document::select('name_th','id')->where('status', 1)->Where('Category','Mprename')->get();
            $provinceNames = province::select('name_th','id')->get();
            $country = country::select('ct_nameENG')->get();
            $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
            $Quotation = Quotation::where('Company_ID',$Company_ID)->paginate($perPage);
            $company_tax = company_tax::where('Company_ID',$Company_ID)->paginate($perPage);
            $representative = representative::where('Company_ID', $Company_ID)->where('status',1)->first();
            $representative_ID = $representative->Profile_ID;
            $repCompany_ID = $representative->Company_ID;
            $phone = representative_phone::where('Profile_ID',$representative_ID)->where('Company_ID',$repCompany_ID)->get();
            $phoneArray = $phone->toArray();
            $TambonA =null;
            $amphuresA =null;
            $Zip_codeA =null;
            $provinceNamesA =null;
            $provinceNamesAs = province::where('id',$representative->City)->first();
            $TambonAs = districts::where('amphure_id', $representative->Amphures)->select('name_th','id')->first();
            $amphuresAs = amphures::where('province_id', $representative->City)->select('name_th','id')->first();
            $Zip_codeAs = districts::where('amphure_id', $representative->Amphures)->select('zip_code','id')->first();
            $TambonA = $TambonAs->name_th;
            $amphuresA = $amphuresAs->name_th;
            $Zip_codeA = $Zip_codeAs->zip_code;
            $provinceNamesA = $provinceNamesAs->name_th;
            $log = log_company::where('Company_ID', $Company_ID)
            ->orderBy('updated_at', 'desc')
            ->paginate($perPage);
            return view('company.edit',compact('Company','booking_channel','provinceNames','Tambon','amphures',
            'Zip_code','faxArray','phoneDataArray','Company_Contact','Mmarket',
            'MCompany_type','Mprefix','phonecount','faxcount','Profile_ID','representative','Mprefix','Quotation','company_tax',
            'log','country','phoneArray','provinceNamesA','TambonA','amphuresA','Zip_codeA'));
    }
    public function update(Request $request, $id) {
        try {
            $company = companys::where('id', $id)->first();
            $company_id = $company->Profile_ID;
            $ids = $company->id;
            $phone = company_phone::where('Profile_ID', $company_id)->get();
            $fax = company_fax::where('Profile_ID', $company_id)->get();
            $dataArray = $company->toArray(); // แปลงข้อมูลบริษัทเป็น array
            $dataArray['phone'] = $phone->pluck('Phone_number')->toArray();
            $dataArray['fax'] = $fax->pluck('Fax_number')->toArray();// เพิ่มค่า phone เข้าไปใน $dataArray
            $datarequest = $request->all();
            $data = [
                'Company_type' => $datarequest['Company_type'] ?? null,
                'Company_Name' => $datarequest['Company_Name'] ?? null,
                'Address' => $datarequest['Address'] ?? null,
                'Booking_Channel' => $datarequest['Booking_Channel'] ?? null,
                'Branch' => $datarequest['Branch'] ?? null,
                'Market' => $datarequest['Market'] ?? null,
                'Country' => $datarequest['Country'] ?? null,
                'City' => $datarequest['City'] ?? null,
                'Amphures' => $datarequest['Amphures'] ?? null,
                'Zip_Code' => $datarequest['Zip_Code'] ?? null,
                'Company_Email' => $datarequest['Company_Email'] ?? null,
                'Tambon' => $datarequest['Tambon'] ?? null,
                'Company_Website' => $datarequest['Company_Website'] ?? null,
                'Taxpayer_Identification' => $datarequest['Taxpayer_Identification'] ?? null,
                'Lastest_Introduce_By' => $datarequest['Lastest_Introduce_By'] ?? null,
                'phone' => $datarequest['phone'] ?? null,
                'fax' => $datarequest['fax'] ?? null,
            ];


            $keysToCompare = ['Company_type', 'Company_Name', 'Address','Booking_Channel','Branch','Market', 'Country', 'City', 'Amphures', 'Tambon', 'Zip_Code', 'Company_Email', 'Company_Website', 'Taxpayer_Identification', 'Lastest_Introduce_By', 'phone','fax'];
            $differences = [];
            foreach ($keysToCompare as $key) {
                if (isset($dataArray[$key]) && isset($data[$key])) {
                    // แปลงค่าของ $dataArray และ $data เป็นชุดข้อมูลเพื่อหาค่าที่แตกต่างกัน
                    $dataArraySet = collect($dataArray[$key]);
                    $dataSet = collect($data[$key]);

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

            // วนลูปเพื่อดึงชื่อคีย์และค่าจาก request
            foreach ($differences as $key => $value) {
                if ($key === 'phone'||$key === 'fax') {
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
            $Company_type = $extractedData['Company_type'] ?? null;
            $Company_Name = $extractedData['Company_Name'] ?? null;
            $Branch =  $extractedData['Branch'] ?? null;
            $Market =  $extractedData['Market'] ?? null;
            $Booking_Channel =  $extractedData['Booking_Channel'] ?? null;
            $Country =  $extractedData['Country'] ?? null;
            $Address =  $extractedData['Address'] ?? null;
            $City = $extractedData['City'] ?? null;
            $Amphures =  $extractedData['Amphures'] ?? null;
            $Tambon =  $extractedData['Tambon'] ?? null;
            $Zip_Code =  $extractedData['Zip_Code'] ?? null;
            $Company_Email =  $extractedData['Company_Email'] ?? null;
            $Company_Website =  $extractedData['Company_Website'] ?? null;
            $Taxpayer_Identification =  $extractedData['Taxpayer_Identification'] ?? null;
            $Lastest_Introduce_By =  $extractedData['Lastest_Introduce_By'] ?? null;
            $phoneCom =  $extractedData['phone'] ?? null;
            $phoneComA =  $extractedDataA['phone'] ?? null;
            $faxCom =  $extractedData['fax'] ?? null;
            $faxComA =  $extractedDataA['fax'] ?? null;
            $comtypefullname = null;
            if ($Company_type && $Company_Name) {
                $comtype = master_document::where('id', $Company_type)->where('Category', 'Mcompany_type')->first();
                if ($comtype) {
                    if ($comtype->name_th == "บริษัทจำกัด") {
                        $comtypefullname = "บริษัท " . $Company_Name . " จำกัด";
                    } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                        $comtypefullname = "บริษัท " . $Company_Name . " จำกัด (มหาชน)";
                    } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                        $comtypefullname = "ห้างหุ้นส่วนจำกัด " . $Company_Name;
                    }else{
                        $comtypefullname = $comtype->name_th. $Company_Name ;
                    }
                }
            } elseif ($Company_Name && $Branch) {
                $comtypefullname = 'ชื่อบริษัท : ' . $Company_Name . ' สาขา : ' . $Branch;
            } elseif ($Company_type) {
                $comtype = master_document::where('id', $Company_type)->where('Category', 'Mcompany_type')->first();
                $comtypefullname = 'ประเภทบริษัท : ' . $comtype->name_th;
            } elseif ($Company_Name) {
                $comtypefullname = 'ชื่อบริษัท : ' . $Company_Name;
            } elseif ($Branch) {
                $comtypefullname = 'สาขา : ' . $Branch;
            }
            $Email = null;
            if ($Company_Email) {
                $Email = 'อีเมล์ : '.$Company_Email;
            }

            $Identification = null;
            if ($Taxpayer_Identification) {
                $Identification = 'เลขบัตรประจำตัว : ' . $Taxpayer_Identification;
            }
            $Branch = null;
            if ($Branch) {
                $Branch = 'สาขา : '.$Branch;
            }
            $phone = null;
            if ($phoneCom) {
                $phone = 'เพิ่มเบอร์โทรศัพท์ : ' . implode(', ', $phoneCom);
            }

            $phoneA = null;
            if ($phoneComA) {

                $phoneA = 'ลบเบอร์โทรศัพท์ : ' . implode(', ', $phoneComA);
            }

            $fax = null;
            if ($faxCom) {
                $fax = 'เพิ่มเบอร์แฟกซ์ : ' . implode(', ', $faxCom);
            }

            $faxA = null;
            if ($faxComA) {
                $faxA = 'ลบเบอร์แฟกซ์ : ' . implode(', ', $faxComA);
            }

            $AddressIndividual = null;
            $CountryCheck = null;
            $AddressCheck = null;
            $provinceNames = null;
            $TambonCheck = null;
            $AmphuresCheck = null;
            $Zip_CodeCheck =null;
            if ($Country) {
                $CountryCheck = 'ประเทศ : '.$Country;
            }
            if ($Address) {
                $AddressCheck = 'ที่อยู่ : '.$Address;
            }
            if ($City) {
                $provinceNames = province::where('id', $City)->first();
                $provinceNames = $provinceNames->name_th;
                $provinceNames = ' จังหวัด : '.$provinceNames;
            }
            if ($Tambon) {
                $TambonID = districts::where('id',$Tambon)->select('name_th','id')->first();
                $TambonName = $TambonID->name_th;
                $TambonCheck = ' ตำบล : '.$TambonName;
            }
            if ($Amphures) {
                $amphuresID = amphures::where('id',$Amphures)->select('name_th','id')->first();
                $amphures = $amphuresID->name_th;
                $AmphuresCheck = ' อำเภอ : '.$TambonName;
            }
            if ($Zip_Code) {
                $Zip_CodeCheck = ' รหัสไปรษณีย์ : '.$Zip_Code;
            }
            $AddressIndividual = $CountryCheck.'+'.$AddressCheck.' '.$TambonCheck.' '.$AmphuresCheck.'+'.$provinceNames.' '.$Zip_CodeCheck;
            if ($Market) {
                $WMarket = master_document::where('id', $Market)->where('Category', 'Mmarket')->first();
                $SMarket = $WMarket->name_th;
                $Market = 'กลุ่มตลาด : '.$SMarket;
            }
            if ($Booking_Channel) {
                $Booking = master_document::where('id', $Booking_Channel)->where('Category', 'Mbooking_channel')->first();
                $BookingChannel = $Booking->name_th;
                $Booking_Channel = 'ช่องทางการจอง : '.$BookingChannel;
            }
            if ($Company_Website) {
                $Company_Website = 'เว็บไซต์ของบริษัท : '.$Company_Website;
            }
            if ($Lastest_Introduce_By) {
                $Lastest_Introduce_By = 'ผู้แนะนำ : '.$Lastest_Introduce_By;
            }
            $datacompany = '';

            $variables = [$comtypefullname, $Email, $Identification, $Branch, $AddressIndividual, $phone ,$phoneA,$fax ,$faxA,$Market,$Booking_Channel,$Company_Website,$Lastest_Introduce_By];

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
            $save->Company_ID = $company_id;
            $save->type = 'Update';
            $save->Category = 'Edit :: Company / Agent ';
            $save->content =$datacompany;
            $save->save();
        } catch (\Throwable $e) {
            return redirect()->route('Company.edit', ['id' => $ids])->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
        try {
            $save = companys::find($id);
            $save->Company_Name = $request->Company_Name;
            $save->Company_type = $request->Company_type;
            $save->Market =$request->Market;
            $save->Booking_Channel = $request->Booking_Channel;

            if ($request->Country != "Thailand") {
                $save->Country = $request->Country;
                $save->Address = $request->Address;
                $save->City = null;
                $save->Amphures = null;
                $save->Tambon = null;
                $save->Zip_Code = null;
            }else {
                $save->Country = $request->Country;
                $save->City = $request->City;
                $save->Amphures = $request->Amphures;
                $save->Address = $request->Address;
                $save->Tambon = $request->Tambon;
                $save->Zip_Code = $request->Zip_Code;
            }
            $save->Branch = $request->Branch;
            $save->Company_Email = $request->Company_Email;
            $save->Company_Website = $request->Company_Website;
            $save->Taxpayer_Identification = $request->Taxpayer_Identification;
            $save->Contract_Rate_Start_Date = $request->contract_rate_start_date;
            $save->Contract_Rate_End_Date = $request->contract_rate_end_date;
            $save->Lastest_Introduce_By =$request->Lastest_Introduce_By;
            $ID = companys::find($id);
            $fax = company_fax::find($id);
            $Profile_ID = $ID->Profile_ID;
            $companyPhones = Company_phone::where('Profile_ID', $Profile_ID)->get();
            $companyfax = company_fax::where('Profile_ID', $Profile_ID)->get();
            Company_phone::where('Profile_ID', $Profile_ID)->delete();
            company_fax::where('Profile_ID', $Profile_ID)->delete();
            if ($request->phone !== null) {
                $phone = $request->phone;
                foreach ($phone as $index => $phoneNumber) {
                    if ($phoneNumber !== null) {
                        $savephone = new company_phone();
                        $savephone->Profile_ID = $Profile_ID;
                        $savephone->Phone_number = $phoneNumber;
                        $savephone->Sequence = ($index === 0) ? 'main' : 'secondary'; // กำหนดค่า Sequence
                        $savephone->save();
                    }
                }
            }
            if ($request->fax !== null) {
                $fax = $request->fax;
                foreach ($fax as $index => $faxNumber) {
                    if ($faxNumber !== null) {
                        $savefax = new company_fax();
                        $savefax->Profile_ID = $Profile_ID;
                        $savefax->Fax_number = $faxNumber;
                        $savefax->Sequence = ($index === 0) ? 'main' : 'secondary'; // กำหนดค่า Sequence
                        $savefax->save();
                    }
                }
            }
            $save->save();

        } catch (\Throwable $e) {
            return redirect()->route('Company.edit', ['id' => $ids])->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }

        return redirect()->route('Company.edit', ['id' => $ids])->with('success', 'บันทึกข้อมูลเรียบร้อยแล้ว');
    }

    //--------------------------------- reporn------------------------
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
    public function provinces($id)
    {
        $provinces = province::where('id',$id)->select('name_th','id')->orderby('id','desc')->get();
        return response()->json([
            'data' => $provinces,
        ]);
    }
    public function amphuresA($id)
    {
        $amphuresA= amphures::where('province_id',$id)->select('name_th','id')->orderby('id','desc')->get();
        return response()->json([
            'data' => $amphuresA,
        ]);
    }
    public function TambonA($id)
    {
        $TambonA = districts::where('amphure_id',$id)->select('name_th','id')->orderby('id','desc')->get();
        return response()->json([
            'data' => $TambonA,
        ]);
    }
    public function districtA($id)
    {
        $districtA = districts::where('id',$id)->select('zip_code','id')->orderby('id','desc')->get();
        return response()->json([
            'data' => $districtA,
        ]);
    }
    public function amphuresContact($id)
    {
        $amphures= amphures::where('province_id',$id)->select('name_th','id')->orderby('id','desc')->get();
        return response()->json([
            'data' => $amphures,
        ]);

    }
    public function TambonContact($id)
    {
        $Tambon = districts::where('amphure_id',$id)->select('name_th','id')->orderby('id','desc')->get();
        return response()->json([
            'data' => $Tambon,

        ]);
    }
    public function districtContact($id)
    {
        $district = districts::where('id',$id)->select('zip_code','name_th','id')->orderby('id','desc')->get();
        return response()->json([
            'data' => $district,

        ]);
    }
    public function SearchContact(Request $request)
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
            $Profile_IDr = $representative->Profile_ID;
            $ID_ContactA = $representative->Company_ID;
            $phone = representative_phone::where('Company_ID', $ID_ContactA)->where('Profile_ID',$Profile_IDr)->get();
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
    //-------------------------

    //-----------------------------Tax----------------------------
    public function Tax(Request $request,$id)
    {
        $data = $request->all();
        $Company = companys::where('id',$id)->first();
        $Profile_IDMain = $Company->Profile_ID;
        $ids = $Company->id;
        $latestCom = company_tax::latest('id')->first();
        if ($latestCom) {
            $Profile_ID = $latestCom->id + 1;
        } else {
            // ถ้าไม่มี Guest ในฐานข้อมูล เริ่มต้นด้วย 1
            $Profile_ID = 1;
        }
        $Id_profile ="-";
        $N_Profile = $Profile_IDMain.$Id_profile.$Profile_ID;
        try {
            if ($TaxSelectA == 'Company') {
                $save = new company_tax();
                $save->ComTax_ID =$N_Profile;
                $save->Company_ID = $Profile_IDMain;
                $save->Company_type = $request->Company_type_tax;
                $save->Companny_name =$request->Company_Name_tax;
                $save->Tax_Type = 'Company';
                $save->BranchTax = $request->BranchTax;

                if ($request->countrydataA != "Thailand") {
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
                $save->Taxpayer_Identification = str_replace('-', '', $request->Identification);
                $save->save();

                foreach ($request->phoneTax as $index => $phoneNumber) {
                    $cleanedPhoneNumber = str_replace('-', '', $phoneNumber);

                    if (!empty($cleanedPhoneNumber)) {
                        $savephoneA = new company_tax_phone();
                        $savephoneA->ComTax_ID = $N_Profile;
                        $savephoneA->Phone_number = $cleanedPhoneNumber;
                        $savephoneA->sequence = ($index === 0) ? 'main' : 'secondary'; // กำหนดค่า Sequence
                        $savephoneA->save();
                    }
                }
            }else {
                $save = new company_tax();
                $save->ComTax_ID =$N_Profile;
                $save->Company_ID = $Profile_IDMain;
                $save->Company_type = $request->prefix;
                $save->first_name =$request->first_nameCom;
                $save->last_name =$request->last_nameCom;
                $save->Tax_Type = 'Individual';
                if ($CountryOther != "Thailand") {
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
                    $cleanedPhoneNumber = str_replace('-', '', $phoneNumber);
                    if (!empty($cleanedPhoneNumber)) {
                        $savephoneA = new company_tax_phone();
                        $savephoneA->ComTax_ID = $N_Profile;
                        $savephoneA->Phone_number = $cleanedPhoneNumber;
                        $savephoneA->sequence = ($index === 0) ? 'main' : 'secondary'; // กำหนดค่า Sequence
                        $savephoneA->save();
                    }
                }
                $save->save();
            }

        } catch (\Throwable $e) {
            return redirect()->route('Company','index')->with('error', $e->getMessage());
        }
        try {
            //log
            $TaxSelectA = $request->TaxSelectA;
            $Company_type_tax = $request->Company_type_tax;
            $Company_Name_tax = $request->Company_Name_tax;
            $CountryOther = $request->countrydataA;
            $provinceAgent = $request->cityA;
            $amphuresA = $request->amphuresA;
            $TambonA = $request->TambonA;
            $zip_codeA = $request->zip_codeA;
            $EmailAgent = $request->EmailAgent;
            $addressAgent = $request->addressAgent;
            $BranchTax = $request->BranchTax;
            //-----------------------------------
            $phoneCom = $request->phoneTax;
            //-----------------------------------
            $Taxpayer_Identification =$request->Identification;
            //------------------------------------------------------------
            $prefix =$request->prefix;
            $first_name =$request->first_nameCom;
            $last_name =$request->last_nameCom;

            if ($TaxSelectA == 'Company') {
                $comtype = master_document::where('id', $Company_type_tax)->first();
                if ($comtype->name_th =="บริษัทจำกัด") {
                    $comtypefullname = "บริษัท ". $Company_Name_tax . " จำกัด";
                }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                    $comtypefullname = "บริษัท ". $Company_Name_tax . " จำกัด (มหาชน)";
                }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                    $comtypefullname = "ห้างหุ้นส่วนจำกัด ". $Company_Name_tax ;
                }else{
                    $comtypefullname = $comtype->name_th. $Company_Name_tax ;
                }
            }else{
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
            }

            if ($CountryOther == 'Thailand') {
                $provinceNames = province::where('id', $provinceAgent)->first();
                $TambonID = districts::where('id',$TambonA)->select('name_th','id','zip_code')->first();
                $amphuresID = amphures::where('id',$amphuresA)->select('name_th','id')->first();
                $provinceNames = $provinceNames->name_th;
                $Tambon = $TambonID->name_th;
                $amphures = $amphuresID->name_th;
                $Zip_code = $TambonID->zip_code;
                $AddressIndividual = 'ที่อยู่ : '.$addressAgent.' ตำบล : '.$Tambon.' อำเภอ : '.$amphures.' จังหวัด : '.$provinceNames.' '.$Zip_code;
            }else{
                $AddressIndividual = 'ที่อยู่ : '.$addressAgent;
            }
            if ($EmailAgent) {
                $Email = 'อีเมล์ : '.$EmailAgent;
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
            if ($phoneCom) {
                $phone = 'เพิ่มเบอร์โทรศัพท์ : ' . implode(', ', $phoneCom);
            }
            $Profile = 'รหัส : '.$N_Profile;
            $Company = 'รหัสบริษัท : '.$Profile_IDMain;

            $datacompany = '';

            $variables = [$Profile,$Company,$comtypefullname, $Email, $Identification, $Branch, $AddressIndividual, $phone];

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
            $save->Company_ID = $Profile_IDMain;
            $save->type = 'Create';
            $save->Category = 'Create :: Additional Company Tax Invoice';
            $save->content =$datacompany;
            $save->save();

        } catch (\Throwable $e) {
            return redirect()->route('Company','index')->with('error', $e->getMessage());
        }
        return redirect()->route('Company.edit', ['id' => $ids])->with('success', 'บันทึกข้อมูลเรียบร้อยแล้ว');
    }
    public function viewTax($id){

        $viewTax = company_tax::where('id',$id)->first();
        $ComTax_ID = $viewTax->ComTax_ID;
        $Company_ID =  $viewTax->Company_ID;
        $Company = companys::where('Profile_ID',$Company_ID)->first();
        $CompanyID = $Company->id;
        $phonetax = company_tax_phone::where('ComTax_ID',$ComTax_ID)->get();
        $phonetaxcount = company_tax_phone::where('ComTax_ID', $ComTax_ID)->count();
        $phonetaxDataArray = $phonetax->toArray();
        $Mprefix = master_document::select('name_th','id')->where('status', 1)->Where('Category','Mprename')->get();
        $provinceNames = province::select('name_th','id')->get();
        $Tambon = districts::where('amphure_id', $viewTax->Amphures)->select('name_th','id')->get();
        $amphures = amphures::where('province_id', $viewTax->City)->select('name_th','id')->get();
        $Zip_code = districts::where('amphure_id', $viewTax->Amphures)->select('zip_code','id')->get();
        $MCompany_type = master_document::select('name_th', 'id')->where('status', 1)->Where('Category','Mcompany_type')->get();
        $country = country::select('ct_nameENG')->get();
        return view('company.viewtax',compact('viewTax','phonetaxDataArray','provinceNames','Tambon','amphures',
            'Zip_code','phonetax','phonetaxcount','MCompany_type','Mprefix','CompanyID','ComTax_ID','country'));
    }
    public function editTax($id) {
        $viewTax = company_tax::where('id',$id)->first();
        $ComTax_ID = $viewTax->ComTax_ID;
        $Company_ID =  $viewTax->Company_ID;
        $Company = companys::where('Profile_ID',$Company_ID)->first();
        $CompanyID = $Company->id;
        $Profile_ID = $Company->Profile_ID;
        $phonetax = company_tax_phone::where('ComTax_ID',$ComTax_ID)->get();
        $phonetaxcount = company_tax_phone::where('ComTax_ID',$ComTax_ID)->count();
        $phonetaxDataArray = $phonetax->toArray();
        $Mprefix = master_document::select('name_th','id')->where('status', 1)->Where('Category','Mprename')->get();
        $provinceNames = province::select('name_th','id')->get();
        $Tambon = districts::where('amphure_id', $viewTax->Amphures)->select('name_th','id')->get();
        $amphures = amphures::where('province_id', $viewTax->City)->select('name_th','id')->get();
        $Zip_code = districts::where('amphure_id', $viewTax->Amphures)->select('zip_code','id')->get();
        $country = country::select('ct_nameENG')->get();
        $MCompany_type = master_document::select('name_th', 'id')->where('status', 1)->Where('Category','Mcompany_type')->get();
        return view('company.edittax',compact('viewTax','phonetaxDataArray','provinceNames','Tambon','amphures',
            'Zip_code','phonetax','phonetaxcount','MCompany_type','Mprefix','CompanyID','Profile_ID','ComTax_ID','country'));
    }
    public function updatetax(Request $request ,$Comid, $id){
        try {
            $content_last = company_tax::where('id',$id)->first();
            $ComTax_ID = $content_last->ComTax_ID;
            $phone = company_tax_phone::where('ComTax_ID',$ComTax_ID)->get();
            $dataArray = $content_last->toArray();
            $Company_ID = $content_last->Company_ID;
            $Company = companys::where('Profile_ID',$Company_ID)->first();
            $ids = $Company->id;
            // รวมหมายเลขโทรศัพท์ใน $dataArray
            $dataArray['phoneCom'] = $phone->pluck('Phone_number')->toArray();
            $data = $request->all();
            $TaxSelectA = $request->Tax_Type;
            $Country =$request->Country;
            if ($TaxSelectA == 'Company') {
                $save = company_tax::find($id);
                $save->Company_type = $request->Company_type;
                $save->Companny_name =$request->Companny_name;
                $save->Tax_Type = 'Company';
                $save->BranchTax = $request->BranchTax;
                $save->first_name =$request->first_name;
                $save->last_name =$request->last_name;
                if ($Country != "Thailand") {
                    $save->City = null;
                    $save->Country =$request->Country;
                    $save->Amphures =null;
                    $save->Tambon =null;
                    $save->Address =$request->Address;
                    $save->Zip_Code = null;
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
                $phoneCom = $request->phoneCom;

                company_tax_phone::where('ComTax_ID', $ComTax_ID)->delete();
                foreach ($phoneCom as $index => $phoneNumber) {
                    if ($phoneNumber !== null) {
                        $savephoneA = new company_tax_phone();
                        $savephoneA->ComTax_ID = $ComTax_ID;
                        $savephoneA->Phone_number = $phoneNumber;
                        $savephoneA->sequence = ($index === 0) ? 'main' : 'secondary'; // กำหนดค่า Sequence
                        $savephoneA->save();
                    }
                }
                $save->save();
            }else{
                $save = company_tax::find($id);
                $save->Company_type = $request->Company_type;
                $save->first_name =$request->first_name;
                $save->last_name =$request->last_name;
                $save->Tax_Type = 'Individual';
                $save->BranchTax = $request->BranchTax;
                $save->Companny_name =$request->Companny_name;
                if ($Country != "Thailand") {
                    $save->City = null;
                    $save->Country =$request->Country;
                    $save->Amphures =null;
                    $save->Tambon =null;
                    $save->Address =$request->Address;
                    $save->Zip_Code = null;
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
                $phoneCom = $request->phoneCom;
                company_tax_phone::where('ComTax_ID', $ComTax_ID)->delete();
                foreach ($phoneCom as $index => $phoneNumber) {
                    if ($phoneNumber !== null) {
                        $savephoneA = new company_tax_phone();
                        $savephoneA->ComTax_ID = $ComTax_ID;
                        $savephoneA->Phone_number = $phoneNumber;
                        $savephoneA->sequence = ($index === 0) ? 'main' : 'secondary'; // กำหนดค่า Sequence
                        $savephoneA->save();
                    }
                }
                $save->save();
            }
        } catch (\Throwable $e) {
            return redirect()->route('Company.edit', ['id' => $ids])->with('error', $e->getMessage());
        }
        try {
            $datarequest = [
                'Tax_Type' => $data['Tax_Type'] ?? null,
                'Company_type' => $data['Company_type'] ?? null,
                'Companny_name' => $data['Companny_name'] ?? null,
                'BranchTax' => $data['BranchTax'] ?? null,
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
                'phoneCom' => $data['phoneCom'] ?? null,
            ];
            {
                $keysToCompare = ['Tax_Type', 'Company_type', 'Companny_name','first_name','last_name', 'BranchTax', 'Taxpayer_Identification', 'Country', 'City', 'Amphures', 'Tambon', 'Zip_Code', 'Company_Email', 'Address', 'phoneCom'];
                $differences = [];
                foreach ($keysToCompare as $key) {
                    if (isset($dataArray[$key]) || isset($datarequest[$key])) {
                        // ตรวจสอบว่าค่าหนึ่งเป็น null หรือไม่
                        if ((isset($dataArray[$key]) && is_null($datarequest[$key])) || (is_null($dataArray[$key]) && isset($datarequest[$key]))) {
                            $differences[$key] = [
                                'dataArray' => $dataArray[$key] ?? 'null',
                                'request' => $datarequest[$key] ?? 'null'
                            ];
                        } else {
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
                }
                $extractedData = [];
                $extractedDataA= [];
                // วนลูปเพื่อดึงชื่อคีย์และค่าจาก request
                foreach ($differences as $key => $value) {
                    if ($key === 'phoneCom') {
                        // ถ้าเป็น phoneCom ให้เก็บค่า request ทั้งหมดใน array
                        $extractedData[$key] = $value['request'];
                        $extractedDataA[$key] = $value['dataArray'];
                    } elseif (isset($value['request'][0])) {
                        // สำหรับคีย์อื่นๆ ให้เก็บค่าแรกจาก array
                        if (isset($value['request']) && is_array($value['request']) && !empty($value['request'])) {
                            $extractedData[$key] = $value['request'][0]; // เก็บค่าแรกจาก array
                        } else {
                            $extractedData[$key] = $value['request']; // เก็บค่าปกติ
                        }
                    }else{
                        $extractedDataA[$key] = $value['dataArray'][0];
                    }
                }

                $Tax_Type = $extractedData['Tax_Type'] ?? null;
                $Company_type = $extractedData['Company_type'] ?? null;
                $Compannyname =  $extractedData['Companny_name'] ?? null;
                $first_name =  $extractedData['first_name'] ?? null;
                $last_name =  $extractedData['last_name'] ?? null;
                $BranchTax =  $extractedData['BranchTax'] ?? null;
                $Taxpayer_Identification =  $extractedData['Taxpayer_Identification'] ?? null;
                $Country = $extractedData['Country'] ?? null;
                $City =  $extractedData['City'] ?? null;
                $Amphures =  $extractedData['Amphures'] ?? null;
                $Tambon =  $extractedData['Tambon'] ?? null;
                $Zip_Code =  $extractedData['Zip_Code'] ?? null;
                $Company_Email =  $extractedData['Company_Email'] ?? null;
                $Address =  $extractedData['Address'] ?? null;
                $phoneCom =  $extractedData['phoneCom'] ?? null;
                $phoneComA =  $extractedDataA['phoneCom'] ?? null;

                $comtypefullname = null;


                // ตรวจสอบค่าต่างๆ ตามลำดับเงื่อนไข
                if ($Company_type && $first_name && $last_name) {
                    $Mprefix = master_document::where('id', $Company_type)->where('Category', 'Mprename')->first();
                    if ($Mprefix) {
                        if ($Mprefix->name_th == "นาย") {
                            $comtypefullname = "นาย " . $first_name . ' ' . $last_name;
                        } elseif ($Mprefix->name_th == "นาง") {
                            $comtypefullname = "นาง " . $first_name . ' ' . $last_name;
                        } elseif ($Mprefix->name_th == "นางสาว") {
                            $comtypefullname = "นางสาว " . $first_name . ' ' . $last_name;
                        }
                    }
                } elseif ($Company_type >= 30) {
                    $Mprefix = master_document::where('id', $Company_type)->where('Category', 'Mprename')->first();
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

                if ($Company_type && $Compannyname) {
                    $comtype = master_document::where('id', $Company_type)->where('Category', 'Mcompany_type')->first();
                    if ($comtype) {
                        if ($comtype->name_th == "บริษัทจำกัด") {
                            $comtypefullname = "บริษัท " . $Compannyname . " จำกัด";
                        } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                            $comtypefullname = "บริษัท " . $Compannyname . " จำกัด (มหาชน)";
                        } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                            $comtypefullname = "ห้างหุ้นส่วนจำกัด " . $Compannyname;
                        }else{
                            $comtypefullname = $comtype->name_th. $Company_Name_tax ;
                        }
                    }
                } elseif ($Compannyname && $BranchTax) {
                    $comtypefullname = 'ชื่อบริษัท : ' . $Compannyname . ' สาขา : ' . $BranchTax;
                } elseif ($Compannyname) {
                    $comtypefullname = 'ชื่อบริษัท : ' . $Compannyname;
                } elseif ($BranchTax) {
                    $comtypefullname = 'สาขา : ' . $BranchTax;
                }
                // แสดงผลลัพธ์
                $Email = null;
                if ($Company_Email) {
                    $Email = 'อีเมล์ : '.$Company_Email;
                }
                $Branch = null;
                if ($Tax_Type == 'Individual') {
                    $Branch = 'สาขา : -';
                }else{
                    if ($BranchTax) {
                        $Branch = 'สาขา : ' . $BranchTax;
                    }
                }
                $Identification = null;
                if ($Taxpayer_Identification) {
                    $Identification = 'เลขบัตรประจำตัว : '.$Taxpayer_Identification;
                }
                $phone = null;
                if ($phoneCom) {
                    $phone = 'เพิ่มเบอร์โทรศัพท์ : ' . implode(', ', $phoneCom);
                }
                $phoneA = null;
                if ($phoneComA) {
                    $phoneA = 'ลบเบอร์โทรศัพท์ : ' . implode(', ', $phoneComA);
                }
                $AddressIndividual = null;
                $CountryCheck = null;
                $AddressCheck = null;
                $provinceNames = null;
                $TambonCheck = null;
                $AmphuresCheck = null;
                $Zip_CodeCheck =null;
                if ($Country) {
                    $CountryCheck = 'ประเทศ : '.$Country;
                }
                if ($Address) {
                    $AddressCheck = 'ที่อยู่ : '.$Address;
                }
                if ($datarequest['Country'] == 'Thailand') {
                    if ($City) {
                        $provinceNames = province::where('id', $City)->first();
                        $provinceNames = $provinceNames->name_th;
                        $provinceNames = ' จังหวัด : '.$provinceNames;
                    }
                    if ($Tambon) {
                        $TambonID = districts::where('id',$Tambon)->select('name_th','id')->first();
                        $TambonName = $TambonID->name_th;
                        $TambonCheck = ' ตำบล : '.$TambonName;
                    }
                    if ($Amphures) {
                        $amphuresID = amphures::where('id',$Amphures)->select('name_th','id')->first();
                        $amphures = $amphuresID->name_th;
                        $AmphuresCheck = ' อำเภอ : '.$TambonName;
                    }
                    if ($Zip_Code) {
                        $Zip_CodeCheck = ' รหัสไปรษณีย์ : '.$Zip_Code;
                    }
                }
                $AddressIndividual = $CountryCheck.'+'.$AddressCheck.' '.$TambonCheck.' '.$AmphuresCheck.'+'.$provinceNames.' '.$Zip_CodeCheck;
                $datacompany = '';

                $variables = [$comtypefullname, $Email, $Identification, $Branch, $AddressIndividual, $phone ,$phoneA];

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
                $save->Company_ID = $Company_ID;
                $save->type = 'Update';
                $save->Category = 'Edit :: Additional Company Tax Invoice';
                $save->content =$datacompany;
                $save->save();
            }
        } catch (\Throwable $e) {
            return redirect()->route('Company.edit', ['id' => $ids])->with('error', $e->getMessage());
        }

        return redirect()->route('Company.edit', ['id' => $ids])->with('success', 'บันทึกข้อมูลเรียบร้อยแล้ว');
    }
    public function changeStatustax($id)
    {
        $company_tax = company_tax::find($id);
        if ($company_tax->status == 1 ) {
            $status = 0;
            $company_tax->status = $status;
        }elseif (($company_tax->status == 0 )) {
            $status = 1;
            $company_tax->status = $status;
        }
        $company_tax->save();
    }
    public function search_table_company(Request $request)
    {
        $perPage = (int)$request->perPage;
        $search_value = $request->search_value;
        $guest_profile = $request->guest_profile;
        if ($search_value) {
            $data_query = company_tax::where('Companny_name', 'LIKE', '%'.$search_value.'%')
            ->orWhere('BranchTax', 'LIKE', '%'.$search_value.'%')
            ->orWhere('first_name', 'LIKE', '%'.$search_value.'%')
            ->orWhere('last_name', 'LIKE', '%'.$search_value.'%')
            ->where('Company_ID',$guest_profile)
            ->paginate($perPage);
        }else{
            $perPageS = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
            $data_query = company_tax::where('Company_ID',$guest_profile)->paginate($perPageS);
        }
        $data = [];
        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $btn_Company = "";
                $btn_status = "";
                $btn_action = "";
                if ($value->status == 1) {
                    $btn_status = '<button type="button" class="btn btn-light-success btn-sm" value="'.$value->id.'" onclick="btnstatusTax('.$value->id.')">ใช้งาน</button>';
                } else {
                    $btn_status = '<button type="button" class="btn btn-light-danger btn-sm" value="'.$value->id.'" onclick="btnstatusTax('.$value->id.')">ปิดใช้งาน</button>';
                }
                if ($value->Tax_Type == 'Company') {
                    $btn_Company = $value->Companny_name;
                }else {
                    $btn_Company = $value->first_name.' '.$value->last_name;
                }
                $rolePermission = Auth::user()->rolePermissionData(Auth::user()->id);
                $canViewProposal = Auth::user()->roleMenuView('Company / Agent', Auth::user()->id);
                $canEditProposal = Auth::user()->roleMenuEdit('Company / Agent', Auth::user()->id);
                $btn_action .='<div class="btn-group">';
                $btn_action .='<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>';
                $btn_action .='<ul class="dropdown-menu border-0 shadow p-3">';
                if ($rolePermission > 0) {
                    if ($canViewProposal) {
                        $btn_action .=' <li><a class="dropdown-item py-2 rounded" href=\'' . url('/Company/viewTax/' . $value->id) . '\'>View</a></li>';
                    }
                    if ($canEditProposal) {
                        $btn_action .= ' <li><a class="dropdown-item py-2 rounded" href=\'' . url('/Company/editTax/' . $value->id) . '\'>Edit</a></li>';
                    }
                } else {
                    if ($canViewProposal) {
                        $btn_action .=' <li><a class="dropdown-item py-2 rounded" href=\'' . url('/Company/viewTax/' . $value->id) . '\'>View</a></li>';
                    }
                }
                $btn_action .='</ul>';
                $btn_action .='</div>';
                $data[] = [
                    'number' => $key + 1,
                    'Profile_ID_TAX'=>$value->ComTax_ID,
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
    public function paginate_table_company(Request $request)
    {

        $perPage = (int)$request->perPage;
        $guest_profile = $request->guest_profile;
        $data = [];
        if ($perPage == 10) {
            $data_query = company_tax::where('Company_ID',$guest_profile)->limit($request->page.'0')->get();
        } else {
            $data_query = company_tax::where('Company_ID',$guest_profile)->paginate($perPage);
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
                        $btn_status = '<button type="button" class="btn btn-light-success btn-sm" value="'.$value->id.'" onclick="btnstatusTax('.$value->id.')">ใช้งาน</button>';
                    } else {
                        $btn_status = '<button type="button" class="btn btn-light-danger btn-sm" value="'.$value->id.'" onclick="btnstatusTax('.$value->id.')">ปิดใช้งาน</button>';
                    }
                    if ($value->Tax_Type == 'Company') {
                        $btn_Company = $value->Companny_name;
                    }else {
                        $btn_Company = $value->first_name.' '.$value->last_name;
                    }
                    $rolePermission = Auth::user()->rolePermissionData(Auth::user()->id);
                    $canViewProposal = Auth::user()->roleMenuView('Company / Agent', Auth::user()->id);
                    $canEditProposal = Auth::user()->roleMenuEdit('Company / Agent', Auth::user()->id);
                    $btn_action .='<div class="btn-group">';
                    $btn_action .='<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>';
                    $btn_action .='<ul class="dropdown-menu border-0 shadow p-3">';
                    if ($rolePermission > 0) {
                        if ($canViewProposal) {
                            $btn_action .=' <li><a class="dropdown-item py-2 rounded" href=\'' . url('/Company/viewTax/' . $value->id) . '\'>View</a></li>';
                        }
                        if ($canEditProposal) {
                            $btn_action .= ' <li><a class="dropdown-item py-2 rounded" href=\'' . url('/Company/editTax/' . $value->id) . '\'>Edit</a></li>';
                        }
                    } else {
                        if ($canViewProposal) {
                            $btn_action .=' <li><a class="dropdown-item py-2 rounded" href=\'' . url('/Company/viewTax/' . $value->id) . '\'>View</a></li>';
                        }
                    }
                    $btn_action .='</ul>';
                    $btn_action .='</div>';

                    $data[] = [
                        'number' => $key + 1,
                        'Profile_ID_TAX'=>$value->ComTax_ID,
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
    //-----------------------------------Visit-------------------------------
    public function search_table_company_Visit(Request $request)
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
                if ($value->checkin) {
                    $btn_date_in =   $value->checkin;
                    $btn_date_out =  $value->checkout;
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
                $btn_action .='<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>';
                $btn_action .='<ul class="dropdown-menu border-0 shadow p-3">';
                $btn_action .=' <li><a class="dropdown-item py-2 rounded" target="_bank" href=\'' . url('/Proposal/Quotation/cover/document/PDF/' . $value->id) . '\'>Export</a></li>';
                $btn_action .='</ul>';
                $btn_action .='</div>';
                $data[] = [
                    'number' => $key + 1,
                    'ID'=>$value->Quotation_ID,
                    'Company'=>@$value->company->Company_Name,
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
    public function  paginate_table_company_Visit(Request $request)
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
                if (($key + 1) >= (int)$page_1 && ($key + 1) <= (int)$page_2 || (int)$perPage > 10 && $key < (int)$perPage2) {
                    if ($value->checkin) {
                        $btn_date_in =   $value->checkin;
                        $btn_date_out =  $value->checkout;
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
                    $btn_action .='<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>';
                    $btn_action .='<ul class="dropdown-menu border-0 shadow p-3">';
                    $btn_action .=' <li><a class="dropdown-item py-2 rounded" target="_bank" href=\'' . url('/Proposal/Quotation/cover/document/PDF/' . $value->id) . '\'>Export</a></li>';
                    $btn_action .='</ul>';
                    $btn_action .='</div>';
                    $data[] = [
                        'number' => $key + 1,
                        'ID'=>$value->Quotation_ID,
                        'Company'=>@$value->company->Company_Name,
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
    //--------------------------Contact--------------

    public function contact($id){
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $Company_ID = $id;
        $CompanyIDF = companys::query()->where('Profile_ID',$Company_ID)->first();
        $CompanyID = $CompanyIDF->id;
        $Company = representative::where('Company_ID', $Company_ID) // ระบุชื่อตารางให้ชัดเจน
            ->paginate($perPage);
            $count = representative::where('status',1)->where('Company_ID',$Company_ID)->count();

            return view('company.indexcontact',compact('Company','Company_ID','CompanyID','count'));
    }
    public function contactcreate(Request $request ,$id)
    {
        try {
            $data = $request->all();
            $Company = companys::where('id',$id)->first();
            $Profile_IDMain = $Company->Profile_ID;
            $Company_Name = $Company->Company_Name;
            $Branch = $Company->Branch;
            $ids = $Company->id;

            $prefix = $request->prefix;
            $first_name = $request->first_nameContact;
            $last_name = $request->last_nameContact;
            $Identification = $request->Identification;
            $countrydataC = $request->countrydataC;
            $cityC = $request->cityC;
            $AmphuresC = $request->amphuresC;
            $TambonC = $request->TambonC;
            $Zip_codeC = $request->zip_codeC;
            $EmailC = $request->EmailAgent;
            $AddressC = $request->addressAgent;
            $PhoneC = $request->phoneContact;

            $latestAgent = representative::where('Company_Name',$Company_Name)->where('Branch',$Branch)->latest('Profile_ID')->first();
            if ($latestAgent) {
                $latestAgent=$latestAgent->Profile_ID+1;
            }else {
                $latestAgent=1;
            }
            $A_Profile = $latestAgent;

            if ($prefix && $first_name && $last_name) {
                $Mprefix = master_document::where('id', $prefix)->where('Category', 'Mprename')->first();
                if ($Mprefix) {
                    if ($Mprefix->name_th == "นาย") {
                        $comtypefullname = "นาย " . $first_name . ' ' . $last_name;
                    } elseif ($Mprefix->name_th == "นาง") {
                        $comtypefullname = "นาง " . $first_name . ' ' . $last_name;
                    } elseif ($Mprefix->name_th == "นางสาว") {
                        $comtypefullname = "นางสาว " . $first_name . ' ' . $last_name;
                    }
                }
            }
            if ($countrydataC == 'Thailand') {
                $provinceNames = province::where('id', $cityC)->first();
                $TambonID = districts::where('id',$TambonC)->select('name_th','id','zip_code')->first();
                $amphuresID = amphures::where('id',$AmphuresC)->select('name_th','id')->first();
                $provinceNames = $provinceNames->name_th;
                $Tambon = $TambonID->name_th;
                $amphures = $amphuresID->name_th;
                $Zip_code = $TambonID->zip_code;
                $AddressIndividual = 'ที่อยู่ : '.$AddressC.' ตำบล : '.$Tambon.' อำเภอ : '.$amphures.' จังหวัด : '.$provinceNames.' '.$Zip_code;
            }else{
                $AddressIndividual = 'ที่อยู่ : '.$AddressC;
            }
            $Email = null;
            if ($EmailC) {
                $Email = 'อีเมล์ผู้ติดต่อ : '.$EmailC;
            }
            $phone = null;
            if ($PhoneC) {
                $phone = 'เพิ่มเบอร์โทรศัพท์ : ' . implode(', ', $PhoneC);
            }

            $Profile = 'รหัสโปรไฟล์ : '.$A_Profile;
            $datacompanycontact = '';

            $variables = [$Profile,$comtypefullname,$AddressIndividual, $Email,$Identification, $phone];

            foreach ($variables as $variable) {
                if (!empty($variable)) {
                    if (!empty($datacompanycontact)) {
                        $datacompanycontact .= ' + ';
                    }
                    $datacompanycontact .= $variable;
                }
            }
            $userid = Auth::user()->id;
            $save = new log_company();
            $save->Created_by = $userid;
            $save->Company_ID = $Profile_IDMain;
            $save->type = 'Create';
            $save->Category = 'Create :: Contract Rate Document';
            $save->content =$datacompanycontact;
            $save->save();

            $Profile_last = representative::where('Company_ID',$Profile_IDMain)
            ->where('Branch',$Branch)
            ->where('status', '1')->first();
            if ( $Profile_last !== null) {
                $status = $Profile_last->status;
                if ($status == 1 ) {
                    $status = 0;
                    $Profile_last->status = $status;
                }
                $Profile_last->save();
            }
            $saveC = new representative();
            $saveC->Profile_ID = $A_Profile;
            $saveC->prefix = $request->prefix;
            $saveC->First_name = $request->first_nameContact;
            $saveC->Last_name = $request->last_nameContact;
            if ($request->countrydataC != "Thailand") {
                $saveC->Country = $request->countrydataC;
                $saveC->City = null;
                $saveC->Amphures = null;
                $saveC->Tambon = null;
                $saveC->Zip_Code = null;
            }else{
                $saveC->Country = $request->countrydataC;
                $saveC->City = $request->cityC;
                $saveC->Amphures = $request->amphuresC;
                $saveC->Tambon = $request->TambonC;
                $saveC->Zip_Code = $request->zip_codeC;
            }
            $saveC->Address = $request->addressAgent;
            $saveC->Email = $request->EmailAgent;
            $saveC->Company_ID = $Profile_IDMain;
            $saveC->Company_Name = $Company_Name;
            $saveC->Branch = $Branch;
            foreach ($request->phoneContact as $index => $phoneNumber) {
                if ($phoneNumber !== null) {
                    $savephoneA = new representative_phone();
                    $savephoneA->Profile_ID = $A_Profile;
                    $savephoneA->Phone_number = $phoneNumber;
                    $savephoneA->Company_ID = $Profile_IDMain;
                    $savephoneA->sequence = ($index === 0) ? 'main' : 'secondary'; // กำหนดค่า Sequence
                    $savephoneA->save();
                }
            }
            $saveC->save();
            return redirect()->route('Company.edit', ['id' => $ids])->with('success', 'บันทึกข้อมูลเรียบร้อยแล้ว');
        } catch (\Throwable $e) {
            return redirect()->route('Company.edit', ['id' => $ids])->with('error', $e->getMessage());
        }
    }
    public function contactedit(Request $request, $id )
    {
        $representative = representative::where('id',$id)->first();
        $representative_ID = $representative->Profile_ID;
        $repCompany_ID = $representative->Company_ID;
        $Company = companys::where('Profile_ID',$repCompany_ID)->first();
        $CompanyID = $Company->id;
        $Mprefix = master_document::select('name_th','id')->where('status', 1)->Where('Category','Mprename')->get();
        $provinceNames = province::select('name_th','id')->get();
        $Tambon = districts::where('amphure_id', $representative->Amphures)->select('name_th','id')->get();
        $amphures = amphures::where('province_id', $representative->City)->select('name_th','id')->get();
        $Zip_code = districts::where('amphure_id', $representative->Amphures)->select('zip_code','id')->get();
        $phone = representative_phone::where('Profile_ID',$representative_ID)->where('Company_ID',$repCompany_ID)->get();
        $phonecount = representative_phone::where('Profile_ID',$representative_ID)->where('Company_ID',$repCompany_ID)->count();
        $phoneDataArray = $phone->toArray();
        $country = country::select('ct_nameENG')->get();
        return view('company.editcontact',compact('representative','Mprefix','provinceNames','CompanyID'
        ,'provinceNames','Tambon','amphures','Zip_code','phoneDataArray','phonecount','representative_ID','country'));
    }
    public function contactview($id){

        $representative = representative::where('id',$id)->where('status',1)->first();
        $representative_ID = $representative->Profile_ID;
        $repCompany_ID = $representative->Company_ID;
        $Company = companys::where('Profile_ID',$repCompany_ID)->first();
        $CompanyID = $Company->id;
        $Mprefix = master_document::select('name_th','id')->where('status', 1)->Where('Category','Mprename')->get();
        $provinceNames = province::select('name_th','id')->get();
        $Tambon = districts::where('amphure_id', $representative->Amphures)->select('name_th','id')->get();
        $amphures = amphures::where('province_id', $representative->City)->select('name_th','id')->get();
        $Zip_code = districts::where('amphure_id', $representative->Amphures)->select('zip_code','id')->get();
        $phone = representative_phone::where('Profile_ID',$representative_ID)->where('Company_ID',$repCompany_ID)->get();
        $phonecount = representative_phone::where('Profile_ID',$representative_ID)->where('Company_ID',$repCompany_ID)->count();
        $phoneDataArray = $phone->toArray();
        $country = country::select('ct_nameENG')->get();
        return view('company.viewcontact',compact('representative','Company','Mprefix','provinceNames'
        ,'provinceNames','Tambon','amphures','Zip_code','phoneDataArray','phonecount','representative_ID','CompanyID','country'));
    }
    public function contactupdate(Request $request, $id)
    {

        $content_last = representative::where('id',$id)->first();
        $Company_ID = $content_last->Company_ID;
        $Profile_ID = $content_last->Profile_ID;
        $Profile_ID_phone = $content_last->Profile_ID;
        $Company = companys::where('Profile_ID',$Company_ID)->first();
        $ids = $Company->id;
        $phone = representative_phone::where('Profile_ID',$Profile_ID)->where('Company_ID',$Company_ID)->get();
        $dataArray = $content_last->toArray();
        $dataArray['phone'] = $phone->pluck('Phone_number')->toArray();
        $data = $request->all();
        $datarequest = [
            'prefix' => $data['Company_type'] ?? null,
            'First_name' => $data['first_name'] ?? null,
            'Last_name' => $data['last_name'] ?? null,
            'Country' => $data['Country'] ?? null,
            'City' => $data['City'] ?? null,
            'Amphures' => $data['Amphures'] ?? null,
            'Tambon' => $data['Tambon'] ?? null,
            'Zip_Code' => $data['Zip_Code'] ?? null,
            'Email' => $data['Company_Email'] ?? null,
            'Address' => $data['Address'] ?? null,
            'phone' => $data['phoneCom'] ?? null,
        ];
        $keysToCompare = ['prefix', 'First_name', 'Last_name','Country','City', 'Amphures', 'Tambon', 'Zip_Code', 'Email', 'Address', 'phone'];
        $differences = [];
        foreach ($keysToCompare as $key) {
            if (isset($dataArray[$key]) || isset($datarequest[$key])) {
                // ตรวจสอบว่าค่าหนึ่งเป็น null หรือไม่
                if ((isset($dataArray[$key]) && is_null($datarequest[$key])) || (is_null($dataArray[$key]) && isset($datarequest[$key]))) {
                    $differences[$key] = [
                        'dataArray' => $dataArray[$key] ?? 'null',
                        'request' => $datarequest[$key] ?? 'null'
                    ];
                } else {
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
        }
        $extractedData = [];
        $extractedDataA= [];
        // วนลูปเพื่อดึงชื่อคีย์และค่าจาก request
        foreach ($differences as $key => $value) {
            if ($key === 'phone') {
                // ถ้าเป็น phoneCom ให้เก็บค่า request ทั้งหมดใน array
                $extractedData[$key] = $value['request'];
                $extractedDataA[$key] = $value['dataArray'];
            } elseif (isset($value['request'][0])) {
                // สำหรับคีย์อื่นๆ ให้เก็บค่าแรกจาก array
                if (isset($value['request']) && is_array($value['request']) && !empty($value['request'])) {
                    $extractedData[$key] = $value['request'][0]; // เก็บค่าแรกจาก array
                } else {
                    $extractedData[$key] = $value['request']; // เก็บค่าปกติ
                }
            }else{
                $extractedDataA[$key] = $value['dataArray'][0];
            }
        }
        $prefix = $extractedData['prefix'] ?? null;
        $First_name = $extractedData['First_name'] ?? null;
        $Last_name =  $extractedData['Last_name'] ?? null;
        $Country =  $extractedData['Country'] ?? null;
        $City =  $extractedData['City'] ?? null;
        $Amphures =  $extractedData['Amphures'] ?? null;
        $Tambon =  $extractedData['Tambon'] ?? null;
        $Zip_Code = $extractedData['Zip_Code'] ?? null;
        $Email =  $extractedData['Email'] ?? null;
        $Address =  $extractedData['Address'] ?? null;
        $phoneCom =  $extractedData['phone'] ?? null;
        $phoneComA =  $extractedDataA['phone'] ?? null;
        {
            $comtypefullname = null;
            if ($prefix && $First_name && $Last_name) {
                $Mprefix = master_document::where('id', $prefix)->where('Category', 'Mprename')->first();
                if ($Mprefix) {
                    if ($Mprefix->name_th == "นาย") {
                        $comtypefullname = "นาย " . $First_name . ' ' . $Last_name;
                    } elseif ($Mprefix->name_th == "นาง") {
                        $comtypefullname = "นาง " . $First_name . ' ' . $Last_name;
                    } elseif ($Mprefix->name_th == "นางสาว") {
                        $comtypefullname = "นางสาว " . $First_name . ' ' . $Last_name;
                    }
                }
            } elseif ($prefix >= 30) {
                $Mprefix = master_document::where('id', $prefix)->where('Category', 'Mprename')->first();
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
            $AddressIndividual = null;
            $CountryCheck = null;
            $AddressCheck = null;
            $provinceNames = null;
            $TambonCheck = null;
            $AmphuresCheck = null;
            $Zip_CodeCheck =null;
            if ($Country) {
                $CountryCheck = 'ประเทศ : '.$Country;
            }
            if ($Address) {
                $AddressCheck = 'ที่อยู่ : '.$Address;
            }

            if ($City) {
                $provinceNames = province::where('id', $City)->first();
                $provinceNames = $provinceNames->name_th;
                $provinceNames = ' จังหวัด : '.$provinceNames;
            }
            if ($Tambon) {
                $TambonID = districts::where('id',$Tambon)->select('name_th','id')->first();
                $TambonName = $TambonID->name_th;
                $TambonCheck = ' ตำบล : '.$TambonName;
            }
            if ($Amphures) {
                $amphuresID = amphures::where('id',$Amphures)->select('name_th','id')->first();
                $amphures = $amphuresID->name_th;
                $AmphuresCheck = ' อำเภอ : '.$TambonName;
            }
            if ($Zip_Code) {
                $Zip_CodeCheck = ' รหัสไปรษณีย์ : '.$Zip_Code;
            }
            $AddressIndividual = $CountryCheck.'+'.$AddressCheck.' '.$TambonCheck.' '.$AmphuresCheck.'+'.$provinceNames.' '.$Zip_CodeCheck;
            $Company_Email = null;
            if ($Email) {
                $Company_Email = 'อีเมล์ : '.$Email;
            }

            $phone = null;
            if ($phoneCom) {
                $phone = 'เพิ่มเบอร์โทรศัพท์ : ' . implode(', ', $phoneCom);
            }

            $phoneA = null;
            if ($phoneComA) {
                $phoneA = 'ลบเบอร์โทรศัพท์ : ' . implode(', ', $phoneComA);
            }
            $Profile_ID = 'รหัสตัวแทน : '.$Profile_ID;

            $datacompany = '';

            $variables = [$Profile_ID,$comtypefullname, $Company_Email, $AddressIndividual, $phone ,$phoneA];

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
            $save->Company_ID = $Company_ID;
            $save->type = 'Update';
            $save->Category = 'Edit :: Contact';
            $save->content =$datacompany;
            $save->save();
        }
        try {
            $saveC = representative::find($id);
            $saveC->prefix = $request->Company_type;
            $saveC->First_name = $request->first_name;
            $saveC->Last_name = $request->last_name;
            if ($request->Country != "Thailand") {
                $saveC->Country = $request->Country;
                $saveC->City = null;
                $saveC->Amphures = null;
                $saveC->Tambon = null;
                $saveC->Zip_Code = null;
            }else{
                $saveC->Country = $request->Country;
                $saveC->City = $request->City;
                $saveC->Amphures = $request->Amphures;
                $saveC->Tambon = $request->Tambon;
                $saveC->Zip_Code = $request->Zip_Code;
            }
            $saveC->Address = $request->Address;
            $saveC->Email = $request->Company_Email;
            $saveC->save();
            $deletedRows = representative_phone::where('Profile_ID', $Profile_ID_phone)
                ->where('Company_ID', $Company_ID)
                ->delete();
            $phone = $request->phoneCom;
            if ($phone !== null) {
                foreach ($phone as $index => $phoneNumber) {
                        $savephoneA = new representative_phone();
                        $savephoneA->Profile_ID = $Profile_ID_phone;
                        $savephoneA->Phone_number = $phoneNumber;
                        $savephoneA->Company_ID = $Company_ID;
                        $savephoneA->sequence = ($index === 0) ? 'main' : 'secondary'; // กำหนดค่า Sequence
                        $savephoneA->save();
                }
            }
            return redirect()->route('Company.edit', ['id' => $ids])->with('success', 'บันทึกข้อมูลเรียบร้อยแล้ว');
        } catch (\Throwable $e) {
            return redirect()->route('Company.edit', ['id' => $ids])->with('error', $e->getMessage());
        }

    }
    public function changeStatuscontact($id)
    {
        $status = representative::find($id);
        if ($status->status == 1 ) {
            $statuss = 0;
            $status->status = $statuss;
        }elseif (($status->status == 0 )) {
            $statuss = 1;
            $status->status = $statuss;
        }
        $status->save();
    }
    public function search_table_company_Contact(Request $request)
    {
        $perPage = (int)$request->perPage;
        $search_value = $request->search_value;
        $guest_profile = $request->guest_profile;

        if ($search_value) {
            $data_query = representative::where(function($query) use ($search_value) {
            $query->where('First_name', 'LIKE', '%'.$search_value.'%')
                    ->orWhere('Last_name', 'LIKE', '%'.$search_value.'%');
            })->where('Company_ID', $guest_profile)
            ->paginate($perPage);
        }else{
            $perPageS = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
            $data_query = representative::where('Company_ID',$guest_profile)->paginate($perPageS);
        }
        $data = [];
        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $btn_Company = "";
                $btn_status = "";
                $btn_action = "";
                if ($value->status == 1) {
                    $btn_status = '<button type="button" class="btn btn-light-success btn-sm" value="'.$value->id.'" onclick="btnstatusTax('.$value->id.')">ใช้งาน</button>';
                } else {
                    $btn_status = '<button type="button" class="btn btn-light-danger btn-sm" value="'.$value->id.'" onclick="btnstatusTax('.$value->id.')">ปิดใช้งาน</button>';
                }

                $btn_Company = 'คุณ '.$value->First_name.' '.$value->Last_name;
                $rolePermission = Auth::user()->rolePermissionData(Auth::user()->id);
                $canViewProposal = Auth::user()->roleMenuView('Company / Agent', Auth::user()->id);
                $canEditProposal = Auth::user()->roleMenuEdit('Company / Agent', Auth::user()->id);
                $btn_action .='<div class="btn-group">';
                $btn_action .='<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>';
                $btn_action .='<ul class="dropdown-menu border-0 shadow p-3">';
                if ($canViewProposal) {
                    $btn_action .=' <li><a class="dropdown-item py-2 rounded" href=\'' . url('/Company/view/contact/' . $value->id) . '\'>View</a></li>';
                }

                $btn_action .='</ul>';
                $btn_action .='</div>';
                $data[] = [
                    'number' => $key + 1,
                    'Profile_ID_TAX'=>$value->Profile_ID,
                    'Company'=>$value->Company_ID,
                    'Branch'=> $value->Branch,
                    'Name'=>$btn_Company,
                    'Status'=>$btn_status,
                    'Order' => $btn_action,
                ];
            }
        }
        return response()->json([
            'data' => $data,
        ]);
    }
    public function  paginate_table_company_Contact(Request $request)
    {
        $perPage = (int)$request->perPage;
        $guest_profile = $request->guest_profile;
        $data = [];
        if ($perPage == 10) {
            $data_query = representative::where('Company_ID',$guest_profile)->limit($request->page.'0')->get();
        } else {
            $data_query = representative::where('Company_ID',$guest_profile)->paginate($perPage);
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
                        $btn_status = '<button type="button" class="btn btn-light-success btn-sm" value="'.$value->id.'" onclick="btnstatusTax('.$value->id.')">ใช้งาน</button>';
                    } else {
                        $btn_status = '<button type="button" class="btn btn-light-danger btn-sm" value="'.$value->id.'" onclick="btnstatusTax('.$value->id.')">ปิดใช้งาน</button>';
                    }

                    $btn_Company = 'คุณ '.$value->First_name.' '.$value->Last_name;
                    $rolePermission = Auth::user()->rolePermissionData(Auth::user()->id);
                    $canViewProposal = Auth::user()->roleMenuView('Company / Agent', Auth::user()->id);
                    $canEditProposal = Auth::user()->roleMenuEdit('Company / Agent', Auth::user()->id);
                    $btn_action .='<div class="btn-group">';
                    $btn_action .='<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>';
                    $btn_action .='<ul class="dropdown-menu border-0 shadow p-3">';
                    if ($canViewProposal) {
                        $btn_action .=' <li><a class="dropdown-item py-2 rounded" href=\'' . url('/Company/view/contact/' . $value->id) . '\'>View</a></li>';
                    }
                    $btn_action .='</ul>';
                    $btn_action .='</div>';

                    $data[] = [
                        'number' => $key + 1,
                        'Profile_ID_TAX'=>$value->Profile_ID,
                        'Company'=>$value->Company_ID,
                        'Branch'=> $value->Branch,
                        'Name'=>$btn_Company,
                        'Status'=>$btn_status,
                        'Order' => $btn_action,
                    ];
                }
            }
        }

        return response()->json([
            'data' => $data,
        ]);
    }
    //-------------------------------Log----------------------------
    public function search_table_company_Log (Request $request)
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
    public function  paginate_table_company_Log (Request $request)
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
}
