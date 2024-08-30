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
use Auth;
class CompanyController extends Controller
{
    public function index()
    {
        $Company = companys::query()
            ->leftJoin('company_phones', 'companys.Profile_ID', '=', 'company_phones.Profile_ID')
            ->where('companys.status', 1)
            ->select('companys.*', 'company_phones.Phone_number as Phone_number')
            ->orderBy('companys.id', 'asc')
            ->get();

        return view('company.index',compact('Company'));
    }
    public function contact($id)
    {
        $Company = companys::find($id);
        $Company_ID = $Company->Profile_ID;
        $representative = representative::where('Company_ID', 'like', "%{$Company_ID}%")->get();
        $Mprefix = master_document::select('name_th','id')->where('status', 1)->Where('Category','Mprename')->get();
        $provinceNames = province::select('name_th','id')->get();
        return view('company.contact',compact('provinceNames','Mprefix','Company','representative'));
    }
    public function contactcreate(Request $request ,$id)
    {
        try {
            $data = $request->all();
            $Company = companys::find($id);
            $N_Profile = $Company->Profile_ID;
            $Company_NameA = $Company->Company_Name;
            $Company_Name = $Company->Company_Name;
            $BranchA = $Company->Branch;
            $Branch = $Company->Branch;
            $ids = $Company->id;
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
            {
                $Preface = $request->prefix;
                $first_name = $request->first_nameAgent;
                $last_name = $request->last_nameAgent;
                $cityAA = $request->cityAA;
                $addressAgent= $request->addressAgent;
                $EmailAgent= $request->EmailAgent;
                $countrydataA = $request->countrydataA;
                $phone = $request->phoneCon;
                $TambonA = $request->TambonA;
                $amphuresA = $request->amphuresA;
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
                    $provinceNames = province::where('id', $cityAA)->first();
                    $TambonID = districts::where('id',$TambonA)->select('name_th','id','zip_code')->first();
                    $amphuresID = amphures::where('id',$amphuresA)->select('name_th','id')->first();
                    $provinceNames = $provinceNames->name_th;
                    $Tambon = $TambonID->name_th;
                    $amphures = $amphuresID->name_th;
                    $Zip_code = $TambonID->zip_code;
                    $AddressIndividual = 'ที่อยู่ : '.$addressAgent.' ตำบล : '.$Tambon.' อำเภอ : '.$amphures.' จังหวัด : '.$provinceNames.' '.$Zip_code;
                }elseif ($cityAA) {
                    $AddressIndividual = 'ที่อยู่ : '.$cityAA;
                }

                $Email = null;
                if ($EmailAgent) {
                    $Email = 'อีเมล์ผู้ติดต่อ : '.$EmailAgent;
                }
                $Branch = null;
                if ($BranchA) {
                    $Branch = 'สาขา : '.$BranchA;
                }
                $phone = null;
                if ($phone) {
                    $phone = 'เพิ่มเบอร์โทรศัพท์ : ' . implode(', ', $phone);
                }

                $Profile = 'รหัสบริษัท : '.$N_Profile;
                $ProfileContact = 'รหัสผู้ติดต่อ : '.$A_Profile;
                $datacompanycontact = '';

                $variables = [$Profile,$ProfileContact,$comtypefullname , $Branch, $AddressIndividual, $Email, $phone];

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
            $saveAgent->prefix = $request->prefix;
            $saveAgent->First_name = $request->first_nameAgent;
            $saveAgent->Last_name = $request->last_nameAgent;
            $countrydataA= $request->countrydataA;
            $AmphuresA= $request->amphuresA;
            $TambonA= $request->TambonA;
            $zip_codeA= $request->zip_codeA;
            $cityA = $request->cityAA;
            $addressAgent= $request->addressAgent;
            $EmailAgent= $request->EmailAgent;
            $NProfile_ID = $N_Profile;
            $phones = $request->phoneCon;

            if ($countrydataA == "Other_countries") {
                if ($cityA === null) {
                    return redirect()->back()->with('error', 'กรุณากรอกประเทศของคุณ');
                }else {
                    $saveAgent->City = $cityA;
                    $saveAgent->Country = $countrydataA;
                    $saveAgent->Amphures = null;
                    $saveAgent->Tambon = null;
                    $saveAgent->Zip_Code = null;
                }
            }else {
                $saveAgent->Country = $countrydataA;
                $saveAgent->City = $cityA;
                $saveAgent->Amphures = $AmphuresA;
                $saveAgent->Tambon = $TambonA;
                $saveAgent->Zip_Code = $zip_codeA;

            }
            $saveAgent->Address = $addressAgent;
            $saveAgent->Email = $EmailAgent;
            $saveAgent->Company_ID = $NProfile_ID;
            $saveAgent->Company_Name = $Company_NameA;
            $saveAgent->Branch = $BranchA;
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

            return redirect()->route('Company_edit', ['id' => $ids])->with('success', 'บันทึกข้อมูลเรียบร้อยแล้ว');
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function contactedit(Request $request, $companyId , $itemId)
    {
        $Company = companys::find($companyId);
        $representative = representative::find($itemId);
        $representative_ID = $representative->Profile_ID;
        $repCompany_ID = $representative->Company_ID;
        $Mprefix = master_document::select('name_th','id')->where('status', 1)->Where('Category','Mprename')->get();
        $number =  preg_replace("/[^0-9]/", "", $representative->City);
        $Other_City =  preg_replace("/[^a-zA-Z]/", "", $representative->City);
        $provinceNames = province::select('name_th','id')->get();
        $Tambon = districts::where('amphure_id', $representative->Amphures)->select('name_th','id')->get();
        $amphures = amphures::where('province_id', $representative->City)->select('name_th','id')->get();
        $Zip_code = districts::where('amphure_id', $representative->Amphures)->select('zip_code','id')->get();
        $phone = representative_phone::where('Profile_ID',$representative_ID)->where('Company_ID', 'like', "%{$repCompany_ID}%")->get();
        $phonecount = representative_phone::where('Profile_ID',$representative_ID)->where('Company_ID', 'like', "%{$repCompany_ID}%")->count();
        $phoneDataArray = $phone->toArray();

        return view('company.editContact',compact('representative','Company','Mprefix','provinceNames'
        ,'number','Other_City','provinceNames','Tambon','amphures','Zip_code','phoneDataArray','phonecount','representative_ID'));
    }
    public function contactupdate(Request $request, $companyId , $itemId)
    {

            $content_last = representative::where('id',$itemId)->first();
            $Company_ID = $content_last->Company_ID;
            $Profile_ID = $content_last->Profile_ID;
            $phone = representative_phone::where('Profile_ID',$Profile_ID)->where('Company_ID',$Company_ID)->get();
            $dataArray = $content_last->toArray();

            // รวมหมายเลขโทรศัพท์ใน $dataArray
            $dataArray['phone'] = $phone->pluck('Phone_number')->toArray();
            $data = $request->all();
            // dd($data);
            {
                $keysToCompare = ['prefix', 'First_name', 'Last_name','Country','City', 'Amphures', 'Tambon', 'Address', 'Zip_Code', 'Email', 'phone'];
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
                    if ($key === 'phoneCom') {
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
                $Company_type = $extractedData['prefix'] ?? null;
                $first_name = $extractedData['First_name'] ?? null;
                $last_name =  $extractedData['Last_name'] ?? null;
                $Country =  $extractedData['Country'] ?? null;
                $City =  $extractedData['City'] ?? null;
                $Amphures =  $extractedData['Amphures'] ?? null;
                $Tambon =  $extractedData['Tambon'] ?? null;
                $Address = $extractedData['Address'] ?? null;
                $Zip_Code =  $extractedData['Zip_Code'] ?? null;
                $Email =  $extractedData['Email'] ?? null;
                $phone =  $extractedData['phone'] ?? null;
                $phoneA =  $extractedDataA['phone'] ?? null;

                $comtypefullname = null;
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
                $Email = null;
                if ($Email) {
                    $Email = 'อีเมล์ : '.$Email;
                }
                $phone = null;
                if ($phone) {
                    $phone = 'เพิ่มเบอร์โทรศัพท์ : ' . implode(', ', $phone);
                }
                $phoneA = null;
                if ($phoneA) {
                    $phoneA = 'ลบเบอร์โทรศัพท์ : ' . implode(', ', $phoneA);
                }
                $AddressIndividual = null;
                if ($Country == 'Thailand') {
                    $provinceNames = province::where('id', $City)->first();
                    $TambonID = districts::where('id',$Tambon)->select('name_th','id','zip_code')->first();
                    $amphuresID = amphures::where('id',$Amphures)->select('name_th','id')->first();
                    $provinceNames = $provinceNames->name_th;
                    $Tambon = $TambonID->name_th;
                    $amphures = $amphuresID->name_th;
                    $Zip_code = $TambonID->zip_code;
                    $AddressIndividual = 'ที่อยู่ : '.$Address.'+'.' ตำบล : '.$Tambon.'+'.' อำเภอ : '.$amphures.'+'.' จังหวัด : '.$provinceNames.'+'.$Zip_code;
                }elseif ($City) {
                    $AddressIndividual = 'ที่อยู่ : '.$City;
                }
                if ($Profile_ID) {
                    $Profile_ID = 'รหัสตัวแทน : '.$Profile_ID;
                }
                $datacompany = '';

                $variables = [$Profile_ID,$comtypefullname, $Email,$AddressIndividual, $phone ,$phoneA];

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
          //---------------------------------------------
            $Company = companys::find($companyId);
            $countrydataA= $request->Country;
            $amphuresA= $request->Amphures;
            $TambonA= $request->Tambon;
            $zip_codeA= $request->Zip_Code;
            $cityA = $request->City;
            $addressAgent= $request->Address;
            $EmailAgent= $request->Email;
            $saveAgent = representative::find($itemId);
            $saveAgent->prefix = $request->prefix;
            $saveAgent->First_name = $request->First_name;
            $saveAgent->Last_name = $request->Last_name;
            $phones = $request->phone;
            if ($countrydataA == "Other_countries") {
                if ($cityA === null) {
                    return redirect()->back()->with('error', 'กรุณากรอกประเทศของคุณ');
                }else {
                    $saveAgent->City = $cityA;
                    $saveAgent->Country = $countrydataA;
                    $saveAgent->Amphures = null;
                    $saveAgent->Tambon = null;
                    $saveAgent->Zip_Code = null;
                }
            }else {
                $saveAgent->Country = $countrydataA;
                $saveAgent->City = $cityA;
                $saveAgent->Amphures = $amphuresA;
                $saveAgent->Tambon = $TambonA;
                $saveAgent->Zip_Code = $zip_codeA;

            }
            $saveAgent->Email = $EmailAgent;
            $saveAgent->Address = $addressAgent;
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
            return redirect()->route('Company_edit', ['id' => $companyId])->with('success', 'บันทึกข้อมูลเรียบร้อยแล้ว');


    }


    public function detail($id)
    {
        $Company = companys::find($id);
        $Company_ID = $Company->Profile_ID;
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

        $representative = representative::where('Company_ID', 'like', "%{$Company_ID}%")->where('status',1)->first();
        $representative_ID = $representative->Profile_ID;
        $repCompany_ID = $representative->Company_ID;
        $Mprefix = master_document::select('name_th','id')->where('status', 1)->Where('Category','Mprename')->get();
        $number =  preg_replace("/[^0-9]/", "", $Company->City);
        $Other_City =  preg_replace("/[^a-zA-Z]/", "", $Company->City);
        $provinceNames = province::select('name_th','id')->get();
        $Tambon = districts::where('amphure_id', $Company->Amphures)->select('name_th','id')->get();
        $amphures = amphures::where('province_id', $Company->City)->select('name_th','id')->get();
        $Zip_code = districts::where('amphure_id', $Company->Amphures)->select('zip_code','id')->get();

        $phone = representative_phone::where('Profile_ID',$representative_ID)->where('Company_ID', 'like', "%{$repCompany_ID}%")->get();
        $count = representative_phone::where('Profile_ID',$representative_ID)->where('Company_ID', 'like', "%{$repCompany_ID}%")->count();
        $phoneArray = $phone->toArray();
        return view('company.detail',compact('Company','booking_channel','provinceNames','Tambon','amphures',
        'Zip_code','Other_City','faxArray','phoneDataArray','Company_Contact','Mmarket',
        'MCompany_type','Mprefix','phonecount','faxcount','Profile_ID','representative','Mprefix','provinceNames'
        ,'phoneArray','count'));

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
        $provinceNames = province::select('name_th','id')->get();
        $booking_channel = master_document::select('name_en', 'id')->where('status', 1)->Where('Category','Mbooking_channel')->get();
        $MCompany_type = master_document::select('name_th', 'id')->where('status', 1)->Where('Category','Mcompany_type')->get();
        $Mmarket = master_document::select('name_th', 'id')->where('status', 1)->Where('Category','Mmarket')->get();
        $Mprefix = master_document::select('name_th','id')->where('status', 1)->Where('Category','Mprename')->get();
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
    public function provinces($id)
    {
        $provinces = province::where('id',$id)->select('name_th','id')->orderby('id','desc')->get();
        return response()->json([
            'data' => $provinces,
        ]);
    }
    public function changeStatus($id)
    { // รับค่า status ที่ส่งมาจาก Request
        $Company = companys::find($id);
        if ($Company->status == 1 ) {
            $status = 0;
            $Company->status = $status;
        }elseif (($Company->status == 0 )) {
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
        try {
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
                {

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
                if ($CountryOther == "Other_countries") {
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
                //agent
                $latestAgent = representative::where('Company_Name', 'like', "%{$Company_Name}%")->where('Branch', 'like', "%{$Branch}%")->first();
                if ($latestAgent) {
                    $latestAgent = $latestAgent->Profile_ID + 1;
                } else {
                    $latestAgent = 1;
                }
                $A_Profile = $latestAgent;



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

                {

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
                if ($countrydataA == "Other_countries") {
                    if ($cityA === null) {
                        return redirect()->back()->with('error', 'กรุณากรอกประเทศของคุณ');
                    }else {
                        $saveAgent->City = $cityA;
                        $saveAgent->Country = $countrydataA;
                        $saveAgent->Amphures = null;
                        $saveAgent->Address = $addressAgent;
                        $saveAgent->Tambon = null;
                        $saveAgent->Zip_Code = null;
                    }
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
                        if ($phoneNumber !== null) {
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

                return redirect()->route('Company.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
            }
        } catch (\Exception $e) {
            // return response()->json([
            //     'error' => $e->getMessage()
            // ], 500);
            return redirect()->route('Company.create')->with('error', 'เกิดข้อผิดพลาด');
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
    public function Company_edit($id)
    {

        try {
            $Company = companys::where('id',$id)->first();

            $Company_ID = $Company->Profile_ID;

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

            $representative = representative::where('Company_ID', 'like', "%{$Company_ID}%")->get();
            $Mprefix = master_document::select('name_th','id')->where('status', 1)->Where('Category','Mprename')->get();
            $provinceNames = province::select('name_th','id')->get();

            $Quotation = Quotation::where('Company_ID',$Company_ID)->get();
            $company_tax = company_tax::where('Company_ID',$Company_ID)->get();

            $log = log_company::where('Company_ID', $Company_ID)
            ->orderBy('created_at', 'desc')
            ->get();
            return view('company.edit',compact('Company','booking_channel','provinceNames','Tambon','amphures',
            'Zip_code','Other_City','faxArray','phoneDataArray','Company_Contact','Mmarket',
            'MCompany_type','Mprefix','phonecount','faxcount','Profile_ID','representative','Mprefix','provinceNames','Quotation','company_tax',
            'log'));
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function Company_update(Request $request, $id) {
            try {
                $company = companys::where('id', $id)->first();
                $company_id = $company->Profile_ID;
                $ids = $company->id;
                $phone = company_phone::where('Profile_ID', $company_id)->get();
                $fax = company_fax::where('Profile_ID', $company_id)->get();
                $dataArray = $company->toArray(); // แปลงข้อมูลบริษัทเป็น array
                $dataArray['phone'] = $phone->pluck('Phone_number')->toArray(); // เพิ่มค่า phone เข้าไปใน $dataArray
                $data = $request->all(); // ดึงข้อมูลที่ส่งมาทั้งหมดจาก request
                {
                    $keysToCompare = ['Company_type', 'Company_Name', 'Booking_Channel','Branch','Market', 'Country', 'City', 'Amphures', 'Tambon', 'Zip_Code', 'Company_Email', 'Company_Website', 'Taxpayer_Identification', 'Lastest_Introduce_By', 'phone'];
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
                            }
                        }
                    } elseif ($Company_Name && $Branch) {
                        $comtypefullname = 'ชื่อบริษัท : ' . $Company_Name . ' สาขา : ' . $Branch;
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
                        $Identification = 'เลขบัตรประจำตัว : '.$Taxpayer_Identification;
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
                    $AddressIndividual = null;
                    if ($Country == 'Thailand') {
                        $provinceNames = province::where('id', $City)->first();
                        $TambonID = districts::where('id',$Tambon)->select('name_th','id','zip_code')->first();

                        $amphuresID = amphures::where('id',$Amphures)->select('name_th','id')->first();
                        $provinceNames = $provinceNames->name_th;
                        $Tambon = $TambonID->name_th;
                        $amphures = $amphuresID->name_th;
                        $Zip_code = $TambonID->zip_code;
                        $AddressIndividual = 'ที่อยู่ : '.$Address.'+'.' ตำบล : '.$Tambon.'+'.' อำเภอ : '.$amphures.'+'.' จังหวัด : '.$provinceNames.'+'.$Zip_code;
                    }elseif ($City) {
                        $AddressIndividual = 'ที่อยู่ : '.$City;
                    }
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

                    $variables = [$comtypefullname, $Email, $Identification, $Branch, $AddressIndividual, $phone ,$phoneA,$Market,$Booking_Channel,$Company_Website,$Lastest_Introduce_By];

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
                }
                $CountryOther = $request->Country;
                $Branch = $request->Branch;
                $province = $request->province;
                $amphures = $request->Amphures;
                $Tambon = $request->Tambon;
                $zip_code = $request->Zip_Code;
                $city = $request->City;
                $Address= $request->Address;
                $phone_company = $request->phone;
                $faxnew = $request->fax;
                $Company_type = $request->Company_type;
                $Company_Name = $request->Company_Name;
                $Market =$request->Market;
                $Booking_Channel = $request->Booking_Channel;
                $Company_Email = $request->Company_Email;
                $Company_Website = $request->Company_Website;
                $Taxpayer_Identification = $request->Taxpayer_Identification;

                $save = companys::find($id);
                $save->Company_Name = $request->Company_Name;
                $save->Company_type = $request->Company_type;
                $save->Market =$Market;
                $save->Booking_Channel = $Booking_Channel;

                if ($CountryOther == "Other_countries") {
                    if ($city === null) {
                        return redirect()->back()->with('error', 'กรุณากรอกประเทศของคุณ');
                    }else {
                        $save->Country = $CountryOther;
                        $save->City = $city;
                        $save->Amphures = ' ';
                        $save->Address = ' ';
                        $save->Tambon = ' ';
                        $save->Zip_Code = ' ';
                    }
                }else {
                    $save->Country = $CountryOther;
                    $save->City = $city;
                    $save->Amphures = $amphures;
                    $save->Address = $Address;
                    $save->Tambon = $Tambon;
                    $save->Zip_Code = $zip_code;
                    $save->Branch = $Branch;
                }
                $save->Company_Email = $request->Company_Email;
                $save->Company_Website = $request->Company_Website;
                $save->Taxpayer_Identification = $request->Taxpayer_Identification;
                // $save->Discount_Contract_Rate = $request->Discount_Contract_Rate;
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
                if ($phone_company !== null) {
                    foreach ($phone_company as $index => $phoneNumber) {
                        if ($phoneNumber !== null) {
                            $savephone = new company_phone();
                            $savephone->Profile_ID = $Profile_ID;
                            $savephone->Phone_number = $phoneNumber;
                            $savephone->Sequence = ($index === 0) ? 'main' : 'secondary'; // กำหนดค่า Sequence
                            $savephone->save();
                        }
                    }
                }
                if ($faxnew !== null) {
                    foreach ($faxnew as $index => $faxNumber) {
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
                return redirect()->route('Company_edit', ['id' => $ids])->with('success', 'บันทึกข้อมูลเรียบร้อยแล้ว');
            } catch (\Throwable $e) {
                return response()->json([
                'error' => $e->getMessage()
            ], 500);
            }
    }

    public function Tax(Request $request,$id){
        try {
            $TaxSelectA = $request->TaxSelectA;
            $Company_type_tax = $request->Company_type_tax;
            $Company_Name_tax = $request->Company_Name_tax;
            $CountryOther = $request->countrydataA;
            $Company_id = $request->Company_id;
            $cityA = $request->cityA;
            $provinceAgent = $request->provinceAgent;
            $amphuresA = $request->amphuresA;
            $TambonA = $request->TambonA;
            $zip_codeA = $request->zip_codeA;
            $EmailAgent = $request->EmailAgent;
            $addressAgent = $request->addressAgent;
            $BranchTax = $request->BranchTax;
            $cityA = $request->cityA;
            //-----------------------------------
            $phoneCom = $request->phoneCom;
            //-----------------------------------
            $Taxpayer_Identification =$request->Taxpayer_Identification;
            //------------------------------------------------------------
            $prefix =$request->prefix;
            $first_nameCom =$request->first_nameCom;
            $last_nameCom =$request->last_nameCom;
            $latestCom = company_tax::latest('id')->first();
            if ($latestCom) {
                $Profile_ID = $latestCom->id + 1;
            } else {
                // ถ้าไม่มี Guest ในฐานข้อมูล เริ่มต้นด้วย 1
                $Profile_ID = 1;
            }
            $Id_profile ="CT-";
            $N_Profile = $Id_profile.$Profile_ID;

            {
                $comtype = master_document::where('id', $Company_type_tax)->first();
                if ($comtype->name_th =="บริษัทจำกัด") {
                    $comtypefullname = "บริษัท ". $Company_Name_tax . " จำกัด";
                }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                    $comtypefullname = "บริษัท ". $Company_Name_tax . " จำกัด (มหาชน)";
                }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                    $comtypefullname = "ห้างหุ้นส่วนจำกัด ". $Company_Name_tax ;
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
                }elseif ($City) {
                    $AddressIndividual = 'ที่อยู่ : '.$cityA;
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
                $Company = 'รหัสบริษัท : '.$Company_id;
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
                $save->Company_ID = $Company_id;
                $save->type = 'Create';
                $save->Category = 'Create :: Additional Company Tax Invoice';
                $save->content =$datacompany;
                $save->save();
            }
            if ($TaxSelectA == 'Company') {
                $save = new company_tax();
                $save->ComTax_ID =$N_Profile;
                $save->Company_ID = $Company_id;
                $save->Company_type = $Company_type_tax;
                $save->Companny_name =$Company_Name_tax;
                $save->Tax_Type = 'Individual';
                $save->BranchTax = $BranchTax;

                if ($CountryOther == "Other_countries") {
                    if ($city === null) {
                        return redirect()->back()->with('error', 'กรุณากรอกประเทศของคุณ');
                    }else {
                        $save->City = $cityA;
                    }
                }else {
                    $save->Country =$CountryOther;
                    $save->City =$provinceAgent;
                    $save->Amphures =$amphuresA;
                    $save->Tambon =$TambonA;
                    $save->Address =$addressAgent;
                    $save->Zip_Code = $zip_codeA;
                }
                $save->Company_Email = $EmailAgent;
                $save->Taxpayer_Identification = $Taxpayer_Identification;
                $save->save();

                foreach ($phoneCom as $index => $phoneNumber) {
                    if ($phoneNumber !== null) {
                        $savephoneA = new company_tax_phone();
                        $savephoneA->ComTax_ID = $N_Profile;
                        $savephoneA->Phone_number = $phoneNumber;
                        $savephoneA->sequence = ($index === 0) ? 'main' : 'secondary'; // กำหนดค่า Sequence
                        $savephoneA->save();
                    }
                }
            }else {
                $save = new company_tax();
                $save->ComTax_ID =$N_Profile;
                $save->Company_ID = $Company_id;
                $save->Company_type = $prefix;
                $save->first_name =$first_nameCom;
                $save->last_name =$last_nameCom;
                if ($CountryOther == "Other_countries") {
                    if ($city === null) {
                        return redirect()->back()->with('error', 'กรุณากรอกประเทศของคุณ');
                    }else {
                        $save->City = $cityA;
                    }
                }else {
                    $save->Country =$CountryOther;
                    $save->City =$provinceAgent;
                    $save->Amphures =$amphuresA;
                    $save->Tambon =$TambonA;
                    $save->Address =$addressAgent;
                    $save->Zip_Code = $zip_codeA;
                }
                $save->Company_Email = $EmailAgent;
                $save->Taxpayer_Identification = $Taxpayer_Identification;
                foreach ($phoneCom as $index => $phoneNumber) {
                    if ($phoneNumber !== null) {
                        $savephoneA = new company_tax_phone();
                        $savephoneA->ComTax_ID = $N_Profile;
                        $savephoneA->Phone_number = $phoneNumber;
                        $savephoneA->sequence = ($index === 0) ? 'main' : 'secondary'; // กำหนดค่า Sequence
                        $savephoneA->save();
                    }
                }
                $save->save();
            }
            return redirect()->route('Company_edit', ['id' => $id])->with('success', 'บันทึกข้อมูลเรียบร้อยแล้ว');
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function viewTax($id){

        $viewTax = company_tax::where('id',$id)->first();
        $ComTax_ID = $viewTax->ComTax_ID;
        $Company_ID =  $viewTax->Company_ID;
        $Company = companys::where('Profile_ID',$Company_ID)->first();
        $CompanyID = $Company->id;
        $phonetax = company_tax_phone::where('ComTax_ID', 'like', "%{$ComTax_ID}%")->get();
        $phonetaxcount = company_tax_phone::where('ComTax_ID', 'like', "%{$ComTax_ID}%")->count();
        $phonetaxDataArray = $phonetax->toArray();
        $Mprefix = master_document::select('name_th','id')->where('status', 1)->Where('Category','Mprename')->get();
        $number =  preg_replace("/[^0-9]/", "", $viewTax->City);
        $Other_City =  preg_replace("/[^a-zA-Z]/", "", $viewTax->City);
        $provinceNames = province::select('name_th','id')->get();
        $Tambon = districts::where('amphure_id', $viewTax->Amphures)->select('name_th','id')->get();
        $amphures = amphures::where('province_id', $viewTax->City)->select('name_th','id')->get();
        $Zip_code = districts::where('amphure_id', $viewTax->Amphures)->select('zip_code','id')->get();
        $MCompany_type = master_document::select('name_th', 'id')->where('status', 1)->Where('Category','Mcompany_type')->get();
        return view('company.viewtax',compact('viewTax','phonetaxDataArray','provinceNames','Tambon','amphures',
            'Zip_code','Other_City','phonetax','phonetaxcount','MCompany_type','Mprefix','CompanyID'));
    }
    public function editTax($id) {
        $viewTax = company_tax::where('id',$id)->first();
        $ComTax_ID = $viewTax->ComTax_ID;
        $Company_ID =  $viewTax->Company_ID;
        $Company = companys::where('Profile_ID',$Company_ID)->first();
        $CompanyID = $Company->id;
        $Profile_ID = $Company->Profile_ID;
        $phonetax = company_tax_phone::where('ComTax_ID', 'like', "%{$ComTax_ID}%")->get();
        $phonetaxcount = company_tax_phone::where('ComTax_ID', 'like', "%{$ComTax_ID}%")->count();
        $phonetaxDataArray = $phonetax->toArray();
        $Mprefix = master_document::select('name_th','id')->where('status', 1)->Where('Category','Mprename')->get();
        $number =  preg_replace("/[^0-9]/", "", $viewTax->City);
        $Other_City =  preg_replace("/[^a-zA-Z]/", "", $viewTax->City);
        $provinceNames = province::select('name_th','id')->get();
        $Tambon = districts::where('amphure_id', $viewTax->Amphures)->select('name_th','id')->get();
        $amphures = amphures::where('province_id', $viewTax->City)->select('name_th','id')->get();
        $Zip_code = districts::where('amphure_id', $viewTax->Amphures)->select('zip_code','id')->get();
        $MCompany_type = master_document::select('name_th', 'id')->where('status', 1)->Where('Category','Mcompany_type')->get();
        return view('company.edittax',compact('viewTax','phonetaxDataArray','provinceNames','Tambon','amphures',
            'Zip_code','Other_City','phonetax','phonetaxcount','MCompany_type','Mprefix','CompanyID','Profile_ID'));
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
            {
                $keysToCompare = ['Tax_Type', 'Company_type', 'Companny_name','first_name','last_name', 'BranchTax', 'Taxpayer_Identification', 'Country', 'City', 'Amphures', 'Tambon', 'Zip_Code', 'Company_Email', 'Address', 'phoneCom'];
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
                    if ($key === 'phoneCom') {
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
                $phoneA = null;
                if ($phoneComA) {
                    $phoneA = 'ลบเบอร์โทรศัพท์ : ' . implode(', ', $phoneComA);
                }
                $AddressIndividual = null;
                if ($Country == 'Thailand') {
                    $provinceNames = province::where('id', $City)->first();
                    $TambonID = districts::where('id',$Tambon)->select('name_th','id','zip_code')->first();
                    $amphuresID = amphures::where('id',$Amphures)->select('name_th','id')->first();
                    $provinceNames = $provinceNames->name_th;
                    $Tambon = $TambonID->name_th;
                    $amphures = $amphuresID->name_th;
                    $Zip_code = $TambonID->zip_code;
                    $AddressIndividual = 'ที่อยู่ : '.$Address.'+'.' ตำบล : '.$Tambon.'+'.' อำเภอ : '.$amphures.'+'.' จังหวัด : '.$provinceNames.'+'.$Zip_code;
                }elseif ($City) {
                    $AddressIndividual = 'ที่อยู่ : '.$City;
                }
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
            $TaxSelectA = $request->Tax_Type;
            $Country =$request->Country;
            if ($TaxSelectA == 'Company') {
                $save = company_tax::find($id);
                $save->Company_type = $request->Company_type;
                $save->Companny_name =$request->Companny_name;
                $save->Tax_Type = 'Company';
                $save->BranchTax = $request->BranchTax;

                if ($Country == "Other_countries") {
                    if ($city === null) {
                        return redirect()->back()->with('error', 'กรุณากรอกประเทศของคุณ');
                    }else {
                        $save->City = $request->City;
                        $save->Country =$request->Country;
                        $save->Amphures =null;
                        $save->Tambon =null;
                        $save->Address =$request->Address;
                        $save->Zip_Code = null;
                    }
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
                if ($Country == "Other_countries") {
                    if ($city === null) {
                        return redirect()->back()->with('error', 'กรุณากรอกประเทศของคุณ');
                    }else {
                        $save->City = $request->City;
                        $save->Country =$request->Country;
                        $save->Amphures =null;
                        $save->Tambon =null;
                        $save->Address =$request->Address;
                        $save->Zip_Code = null;
                    }
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
            return redirect()->route('Company_edit', ['id' => $ids])->with('success', 'บันทึกข้อมูลเรียบร้อยแล้ว');
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
