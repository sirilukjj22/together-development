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
use App\Models\banquet_asset;
use App\Models\banquet_food;
use App\Models\banquet_schedule;
use App\Models\banquet_setup;
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
        $tempGroup = [];
        $setup = [];
        $tempSetupGroup = [];
        $tempschedulesGroup = [];
        $defaultschedulesKeys = [
            "DateSchedule" => null,
            "StartSchedule" => null,
            "EndSchedule" => null,
            "RoomSchedule" => null,
            "functionSchedule" => null,
            "setupSchedule" => null,
            "agrSchedule" => null,
            "gtdSchedule" => null,
            "setSchedule" => null,
        ];
        $defaultFoodKeys = [
            "date-food" => null,
            "startfoodTime" => null,
            "endfoodTime" => null,
            "foodinputRoom" => null,
            "foodinputSpecial" => null,
            "foodinputGuest" => null,
            "foodinputFood" => null,
            "foodinputtype" => null,
            "foodinputDrink" => null,
        ];
        foreach ($data as $key => $value) {
            if (preg_match('/^(DateSchedule|RoomSchedule|functionSchedule|setupSchedule|agrSchedule|setSchedule|StartSchedule|EndSchedule)_(\d+)$/', $key, $matches)) {
                $index = $matches[2]; // ดึงหมายเลข Schedule
                $field = $matches[1];  // ชื่อฟิลด์ เช่น DateSchedule, RoomSchedule

                $tempschedulesGroup[$index][$field] = $value;
            }
            if (preg_match('/^(assetItem|quantity|remarks|price)_(\d+)$/', $key, $matches)) {
                $indexItem = $matches[2]; // ดึงหมายเลข Schedule
                $fieldItem = $matches[1];  // ชื่อฟิลด์ เช่น DateSchedule, RoomSchedule

                $assets[$indexItem][$fieldItem] = $value;
            }
            if (preg_match('/^(date-food|startfoodTime|endfoodTime|foodinputRoom|foodinputSpecial|foodinputGuest|foodinputFood|foodinputtype|foodinputDrink)_(\d+(?:_\d+)?)$/', $key, $matches)) {
                $field = $matches[1];
                $index = $matches[2];
                $tempGroup[$index][$field] = $value;

            }


            foreach ($data as $key => $value) {
                if (preg_match('/^(date-setup|setupRoom|setupDetails|startsetupTime|endsetupTime|setup-id)_(\d+(?:_\d+)?)$/', $key, $matches)) {
                    $field = $matches[1];
                    $index = $matches[2];
                    $tempSetupGroup[$index][$field] = $value;
                }
            }
        }
        $newschedulesIndex = 1;
        foreach ($tempschedulesGroup as $group) {
            $schedules[$newschedulesIndex++] = array_merge($defaultschedulesKeys, $group);
        }
        $newIndex = 1;
        foreach ($tempGroup as $group) {
            $Food[$newIndex++] = array_merge($defaultFoodKeys, $group);
        }
        $newSetupIndex = 1;
        foreach ($tempSetupGroup as $group) {
            $setup[$newSetupIndex++] = $group;
        }
        $eventInfo = [
            "sales" => $request->input('sales'),
            "eventDate" => $request->input('eventDate'),
            "catering" => $request->input('catering'),
            "number" => $request->input('team'),
            "vehicle" => $request->input('vehicle')
        ];

        $Quotation = Quotation::where('id', $id)->first();
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
        $companyid= $Quotation->Company_ID;
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

            }
        }
        try {
            $userid = Auth::user()->id;
            $save = new banquet_event_order();
            $save->Banquet_ID = $BEOID;
            $save->Quotation_ID = $Quotation->Quotation_ID;
            $save->Company_ID = $Quotation->Company_ID;
            $save->event_date = $eventInfo['eventDate'];
            $save->sales = $eventInfo['sales'];
            $save->catering = $eventInfo['catering'];
            $save->number = $eventInfo['number'];
            $save->Operated_by = $userid;
            $save->vehicle = $eventInfo['vehicle'];
            $save->save();
        } catch (\Throwable $e) {
            return redirect()->route('Banquet.index')->with('error', $e->getMessage());
        }
        try {
            foreach ($schedules as $value) {
                $schedulesave = new banquet_schedule();
                $schedulesave->Banquet_ID = $BEOID;
                $schedulesave->date = $value['DateSchedule'];
                $schedulesave->first_time = $value['StartSchedule'];
                $schedulesave->last_time = $value['EndSchedule'];
                $schedulesave->room = $value['RoomSchedule'];
                $schedulesave->function = $value['functionSchedule'];
                $schedulesave->setup = $value['setupSchedule'];
                $schedulesave->agr_schedule = $value['agrSchedule'];
                $schedulesave->gtd_schedule = $value['gtdSchedule'];
                $schedulesave->set_schedule = $value['setSchedule'];
                $schedulesave->save();
            }
        } catch (\Throwable $e) {
            banquet_event_order::where('Banquet_ID', $BEOID)->delete();
            banquet_schedule::where('Banquet_ID', $BEOID)->delete();
            banquet_asset::where('Banquet_ID', $BEOID)->delete();
            banquet_food::where('Banquet_ID', $BEOID)->delete();
            banquet_setup::where('Banquet_ID', $BEOID)->delete();
            return redirect()->route('Banquet.index')->with('error', $e->getMessage());
        }
        try {
            foreach ($assets as $value) {
                $assetsave = new banquet_asset();
                $assetsave->Banquet_ID = $BEOID;
                $assetsave->item = $value['assetItem'];
                $assetsave->quantity = $value['quantity'];
                $assetsave->remarks = $value['remarks'];
                $assetsave->price = $value['price'];
                $assetsave->save();
            }
        } catch (\Throwable $e) {
            banquet_event_order::where('Banquet_ID', $BEOID)->delete();
            banquet_schedule::where('Banquet_ID', $BEOID)->delete();
            banquet_asset::where('Banquet_ID', $BEOID)->delete();
            banquet_food::where('Banquet_ID', $BEOID)->delete();
            banquet_setup::where('Banquet_ID', $BEOID)->delete();
            return redirect()->route('Banquet.index')->with('error', $e->getMessage());
        }
        try {
            foreach ($Food as $value) {
                $foodsave = new banquet_food();
                $foodsave->Banquet_ID = $BEOID;
                $foodsave->date = $value['date-food'];
                $foodsave->first_time = $value['startfoodTime'];
                $foodsave->last_time = $value['endfoodTime'];
                $foodsave->room = $value['foodinputRoom'];
                $foodsave->special = $value['foodinputSpecial'];
                $foodsave->number_guest	 = $value['foodinputGuest'];
                $foodsave->food = $value['foodinputFood'];
                $foodsave->food_type = $value['foodinputtype'];
                $foodsave->drink = $value['foodinputDrink'];
                $foodsave->save();
            }
        } catch (\Throwable $e) {
            banquet_event_order::where('Banquet_ID', $BEOID)->delete();
            banquet_schedule::where('Banquet_ID', $BEOID)->delete();
            banquet_asset::where('Banquet_ID', $BEOID)->delete();
            banquet_food::where('Banquet_ID', $BEOID)->delete();
            banquet_setup::where('Banquet_ID', $BEOID)->delete();
            return redirect()->route('Banquet.index')->with('error', $e->getMessage());
        }
        try {
            foreach ($setup as $value) {
                $setupsave = new banquet_setup();
                $setupsave->Banquet_ID = $BEOID;
                $setupsave->setup_id = $value['setup-id'];
                $setupsave->date = $value['date-setup'];
                $setupsave->first_time = $value['startsetupTime'];
                $setupsave->last_time = $value['endsetupTime'];
                $setupsave->room = $value['setupRoom'];
                $setupsave->details = $value['setupDetails'];
                $setupsave->save();
            }
            //code...banquet_setup
        } catch (\Throwable $e) {
            banquet_event_order::where('Banquet_ID', $BEOID)->delete();
            banquet_schedule::where('Banquet_ID', $BEOID)->delete();
            banquet_asset::where('Banquet_ID', $BEOID)->delete();
            banquet_food::where('Banquet_ID', $BEOID)->delete();
            banquet_setup::where('Banquet_ID', $BEOID)->delete();
            return redirect()->route('Banquet.index')->with('error', $e->getMessage());
        }
        try {
            $name = 'ชื่อลูกค้า : '.$fullName;
            $doc = 'รหัสใบคำสั่งจัดงานเลี้ยง : '.$BEOID;
            $refresh = 'อ้างอิงจาก : '.$Quotation->Quotation_ID;
            $formattedschedules = [];
            foreach ($schedules as $data) {
                $formattedschedules[] =
                    'Date : ' . ($data['DateSchedule'] ?? ' ') . ' , ' .
                    'Room : ' . ($data['RoomSchedule'] ?? ' ') . ' , ' .
                    'Start : ' . ($data['StartSchedule'] ?? ' ') . ' , ' .
                    'End : ' . ($data['EndSchedule'] ?? ' ') . ' , ' .
                    'Function : ' . ($data['functionSchedule'] ?? ' ') . ' , ' .
                    'Setup : ' . ($data['setupSchedule'] ?? ' ') . ' , ' .
                    'Agr : ' . ($data['agrSchedule'] ?? ' ') . ' , ' .
                    'GTD : ' . ($data['gtdSchedule'] ?? '-');
            }
            $formattedassets = [];
            foreach ($assets as $data) {
                $formattedassets[] =
                    'Asset : ' . ($data['assetItem'] ?? ' ') . ' , ' .
                    'Quantity : ' . ($data['quantity'] ?? ' ') . ' , ' .
                    'Remarks : ' . ($data['remarks'] ?? ' ') . ' , ' .
                    'Price : ' . ($data['price'] ?? '-');
            }
            $formattedFood = [];
            foreach ($Food as $data) {
                $formattedFood[] =
                    'Date : ' . ($data['date-food'] ?? ' ') . ' , ' .
                    'Room : ' . ($data['foodinputRoom'] ?? ' ') . ' , ' .
                    'Start : ' . ($data['startfoodTime'] ?? ' ') . ' , ' .
                    'End : ' . ($data['endfoodTime'] ?? ' ') . ' , ' .
                    'Special : ' . ($data['foodinputSpecial'] ?? ' ') . ' , ' .
                    'Guest : ' . ($data['foodinputGuest'] ?? '-') . ' , ' .
                    'Food : ' . ($data['foodinputFood'] ?? '-') . ' , ' .
                    'Type : ' . ($data['foodinputtype'] ?? '-') . ' , ' .
                    'Drink :  '.($data['foodinputDrink'] ?? '-');
            }
            $formattedsetup = [];
            foreach ($setup as $data) {
                $formattedsetup[] =
                    'Date : ' . ($data['date-setup'] ?? ' ') . ' , ' .
                    'Room : ' . ($data['setupRoom'] ?? ' ') . ' , ' .
                    'Start : ' . ($data['startsetupTime'] ?? ' ') . ' , ' .
                    'End : ' . ($data['endsetupTime'] ?? '-') . ' , ' .
                    'Details :  '.($data['setupDetails'] ?? '-');
            }
            $datacompany = '';

            $variables = [$name, $refresh,$doc];
            $formattedProductDataString = implode(' + ', array_merge(
                $formattedschedules,
                $formattedassets,
                $formattedFood,
                $formattedsetup
            ));
            $variables[] = $formattedProductDataString;
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
            $save->Company_ID = $BEOID;
            $save->type = 'Create';
            $save->Category = 'Create :: Banquet event order';
            $save->content =$datacompany;
            $save->save();
        } catch (\Throwable $e) {
            banquet_event_order::where('Banquet_ID', $BEOID)->delete();
            banquet_schedule::where('Banquet_ID', $BEOID)->delete();
            banquet_asset::where('Banquet_ID', $BEOID)->delete();
            banquet_food::where('Banquet_ID', $BEOID)->delete();
            banquet_setup::where('Banquet_ID', $BEOID)->delete();
            return redirect()->route('Banquet.index')->with('error', $e->getMessage());
        }
        $data = $request->all();
        $selectsetupJson = $request->input('selectsteup'); // รับค่า JSON string
        $selectsetupArray = json_decode($selectsetupJson, true); // แปลงเป็น array
        $selectsetupArray['Banquet_ID'] = $BEOID; // เพิ่มค่าใหม่เข้า array

        $Setup_ID = $selectsetupArray['setup'];
        $Banquet_ID = $selectsetupArray['Banquet_ID'];
        $setup_id =  $Setup_ID.','.$Banquet_ID;
        return redirect()->route('Banquet.create_room', ['id' => $setup_id]);
    }
    public function create_room($id)
    {
        $parts = explode(',', $id);
        $Setup_ID = $parts[0];
        $Banquet_ID = $parts[1];
        $setup = banquet_setup::where('setup_id',$Setup_ID)->where('Banquet_ID',$Banquet_ID)->first();
        return view('banquet_event_order.create_room',compact('setup','Setup_ID','Banquet_ID'));
    }
    public function save_room(Request $request ,$id)
    {

        $base64Image = $request->input('image_data');
        $imageId = $request->input('image-id');
        $BEO = $id;

        $image_parts = explode(";base64,", $base64Image);
        $image_base64 = base64_decode($image_parts[1]);
        $filename = 'image_' . $BEO .'-'. $imageId . '.png';
        $filePath = 'image_banquet/' . $filename; // ถูกต้อง
        file_put_contents(public_path($filePath), $image_base64);
        $setup = banquet_setup::where('Banquet_ID',$BEO)->where('setup_id',$imageId)->first();
        $setup_id = $setup->id;
        $image_id =  $BEO.'-'.$imageId;
        try {
            $save = banquet_setup::find($setup_id);
            $save->image = $filePath;
            $save->Image_ID = $image_id;
            $save->save();
            return redirect()->route('Banquet.index')->with('success', 'Data has been successfully saved.');
        } catch (\Throwable $e) {
            return redirect()->route('Banquet.index')->with('error', $e->getMessage());
        }
    }
    public function edit($id){
        $Proposal = Quotation::where('id', $id)->first();
        $companyid = $Proposal->Company_ID;
        $Quotation_ID = $Proposal->Quotation_ID;
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
        $schedule =null;
        $banquet = banquet_event_order::where('Quotation_ID', $Quotation_ID)->first();
        if ($banquet) {
            $BEOID =  $banquet->Banquet_ID;
            $schedule = banquet_schedule::where('Banquet_ID', $BEOID)->get();
            $asset = banquet_asset::where('Banquet_ID', $BEOID)->get();
        }else{
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
        }
        return view('banquet_event_order.createmuti',compact('id','settingCompany','BEOID','Proposal','fullName','address','phone','Email','Selectdata','contact','user','banquet','schedule','Quotation_ID','asset'));
    }
    public function save_event(Request $request){
        $data = $request->all();
        $BEOID = $request->BEOID;
        $formattedDate = Carbon::createFromFormat('l, d M Y', $request->event_date)
        ->format('d/m/Y'); // หรือ translatedFormat('d F Y') สำหรับภาษาไทย
        $newData = [
            'event_date' =>    $formattedDate,
            'catering'    => $request->catering,
            'number'      => $request->number,
            'vehicle'     => $request->vehicle,
        ];

        $banquet = banquet_event_order::where('Banquet_ID', $BEOID)->first();
        if ($banquet) {
            $banquet_id = $banquet->id;
            $changed = array_filter($newData, function ($value, $key) use ($banquet) {
                return $banquet->$key != $value;
            }, ARRAY_FILTER_USE_BOTH);

            try {
                DB::beginTransaction();
                $fullname = 'รหัส : '.$BEOID;
                $edit = 'รายการแก้ไข';
                $formattedschedules = [];
                foreach ($changed as $key => $newValue) {
                    $label = ucfirst($key); // ทำให้ตัวแรกเป็นตัวพิมพ์ใหญ่
                    $formattedschedules[] = "$label : $newValue";
                }
                $formattedschedulesText = implode(' , ', $formattedschedules);
                $datacompany = '';

                $variables = [$fullname, $edit, $formattedschedulesText];
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
                $save->Company_ID = $BEOID;
                $save->type = 'Edit';
                $save->Category = 'Edit :: Banquet event order';
                $save->content =$datacompany;
                $save->save();
                $save = banquet_event_order::find($banquet_id);
                $save->event_date =$newData['event_date'];
                $save->catering =$newData['catering'];
                $save->number =$newData['number'];
                $save->vehicle =$newData['vehicle'];
                $save->save();
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'The data has been edited.'
                ]);
            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Error : ' . $e->getMessage()
                ]);
            }
        }else{
            $Proposal = $request->proposal;
            $Quotation = Quotation::where('Quotation_ID',$Proposal)->first();
            $banquet = banquet_event_order::where('Banquet_ID',$BEOID)->first();
            if ($banquet) {
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
                $BEO_ID = $ID.$year.$month.$newRunNumber;
            }else{
                $BEO_ID = $BEOID;
            }
            try {
                DB::beginTransaction();
                $fullname = 'รหัส : '.$BEO_ID;
                $edit = 'รายละเอียด';
                $formattedschedules = [];
                foreach ($newData as $key => $newValue) {
                    $label = ucfirst($key); // ทำให้ตัวแรกเป็นตัวพิมพ์ใหญ่
                    $formattedschedules[] = "$label : $newValue";
                }
                $formattedschedulesText = implode(' , ', $formattedschedules);
                $datacompany = '';
                $variables = [$fullname, $edit, $formattedschedulesText];
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
                $save->Company_ID = $BEO_ID;
                $save->type = 'Create';
                $save->Category = 'Create :: Banquet event order';
                $save->content =$datacompany;
                $save->save();
                $userid = Auth::user()->id;
                $username = Auth::user()->firstname;
                $save = new banquet_event_order();
                $save->Banquet_ID =$BEO_ID;
                $save->Quotation_ID =$Quotation->Quotation_ID;
                $save->Company_ID =$Quotation->Company_ID;
                $save->event_date =$newData['event_date'];
                $save->catering =$newData['catering'];
                $save->number =$newData['number'];
                $save->vehicle =$newData['vehicle'];
                $save->sales =$username;
                $save->Operated_by =$userid;
                $save->save();
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data has been successfully saved.',
                    'BEOID'=>$BEO_ID
                ]);
            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Error : ' . $e->getMessage()
                ]);
            }
        }
    }
    public function save_schedule(Request $request){
        $data = $request->all(); // รับข้อมูลจาก JS
        $scheduleData = [
            'row_id'        => $data['data']['row'] ?? null,
            'Banquet_ID'    => $data['data']['BEOID'] ?? null,
            'date'          => $data['data']['date'] ?? null,
            'room'          => $data['data']['room'] ?? null,
            'function'      => $data['data']['function'] ?? null,
            'setup'         => $data['data']['setup'] ?? null,
            'agr_schedule'  => $data['data']['agr'] ?? null,
            'gtd_schedule'  => $data['data']['gtd'] ?? null,
            'set_schedule'  => $data['data']['set'] ?? null,
            'first_time'    => $data['data']['start'] ?? null,
            'last_time'     => $data['data']['end'] ?? null, // คุณเขียนผิดว่า start ทั้งสองช่อง
        ];

        $banquet = banquet_event_order::where('Banquet_ID', $scheduleData['Banquet_ID'])->first();
        if ( $banquet) {
            $BEOID = $banquet->Banquet_ID;
            $schedule = banquet_schedule::where('Banquet_ID', $scheduleData['Banquet_ID'])->where('row_id', $scheduleData['row_id'])->first();
            if ($schedule) {
                $schedule_id = $schedule->id;
                $changed = array_filter($scheduleData, function ($value, $key) use ($schedule) {
                    return $schedule->$key != $value;
                }, ARRAY_FILTER_USE_BOTH);
                try {
                    DB::beginTransaction();
                    $fullname = 'รหัส : '.$BEOID;
                    $edit = 'รายการแก้ไข';
                    $formattedschedules = [];
                    foreach ($changed as $key => $newValue) {
                        $label = ucfirst($key); // ทำให้ตัวแรกเป็นตัวพิมพ์ใหญ่
                        $formattedschedules[] = "$label : $newValue";
                    }
                    $formattedschedulesText = implode(' , ', $formattedschedules);
                    $datacompany = '';

                    $variables = [$fullname, $edit, $formattedschedulesText];
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
                    $save->Company_ID = $BEOID;
                    $save->type = 'Edit Schedule';
                    $save->Category = 'Edit :: Banquet event order (schedule)';
                    $save->content =$datacompany;
                    $save->save();
                    $save =banquet_schedule::find($schedule_id);
                    $save->Banquet_ID = $BEOID;
                    $save->date = $scheduleData['date'];
                    $save->first_time = $scheduleData['first_time'];
                    $save->last_time = $scheduleData['last_time'];
                    $save->room = $scheduleData['room'];
                    $save->function = $scheduleData['function'];
                    $save->setup = $scheduleData['setup'];
                    $save->agr_schedule = $scheduleData['agr_schedule'];
                    $save->gtd_schedule = $scheduleData['gtd_schedule'];
                    $save->set_schedule = $scheduleData['set_schedule'];
                    $save->save();
                    DB::commit();
                    return response()->json([
                        'status' => 'success',
                        'message' => 'The data has been edited.',
                    ]);
                } catch (\Throwable $e) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Error : ' . $e->getMessage()
                    ]);
                }
            }else{
                try {
                    DB::beginTransaction();
                    $fullname = 'รหัส : '.$scheduleData['Banquet_ID'];
                    $edit = 'รายละเอียด';
                    $formattedschedules = [];
                    foreach ($scheduleData as $key => $newValue) {
                        $label = ucfirst($key); // ทำให้ตัวแรกเป็นตัวพิมพ์ใหญ่
                        $formattedschedules[] = "$label : $newValue";
                    }
                    $formattedschedulesText = implode(' , ', $formattedschedules);
                    $datacompany = '';
                    $variables = [$fullname, $edit, $formattedschedulesText];
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
                    $save->Company_ID = $BEOID;
                    $save->type = 'Create Schedule';
                    $save->Category = 'Create :: Banquet event order (schedule)';
                    $save->content =$datacompany;
                    $save->save();
                    $save = new banquet_schedule();
                    $save->Banquet_ID = $BEOID;
                    $save->row_id = $scheduleData['row_id'];
                    $save->date = $scheduleData['date'];
                    $save->first_time = $scheduleData['first_time'];
                    $save->last_time = $scheduleData['last_time'];
                    $save->room = $scheduleData['room'];
                    $save->function = $scheduleData['function'];
                    $save->setup = $scheduleData['setup'];
                    $save->agr_schedule = $scheduleData['agr_schedule'];
                    $save->gtd_schedule = $scheduleData['gtd_schedule'];
                    $save->set_schedule = $scheduleData['set_schedule'];
                    $save->save();
                    DB::commit();
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data has been successfully saved.',
                    ]);
                } catch (\Throwable $e) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Error : ' . $e->getMessage()
                    ]);
                }
            }
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Error : Please enter the Event Details first.'
            ]);
        }

    }
    public function delete_schedule(Request $request){
        $data = $request->all(); // รับข้อมูลจาก JS
        $scheduleData = [
            'row_id'        => $data['data']['row'] ?? null,
            'Banquet_ID'    => $data['data']['BEOID'] ?? null,
        ];
        try {
            DB::beginTransaction();
            banquet_schedule::where('Banquet_ID', $scheduleData['Banquet_ID'])->where('row_id',$scheduleData['row_id'])->delete();
            $userid = Auth::user()->id;
            $save = new log_company();
            $save->Created_by = $userid;
            $save->Company_ID = $scheduleData['Banquet_ID'];
            $save->type = 'Delete Schedule';
            $save->Category = 'Delete :: Banquet event order (schedule)';
            $save->content ='ลบข้อมูล Schedule +รหัส :'.$scheduleData['Banquet_ID'].'+'.'ลำดับที่ '.$scheduleData['row_id'];
            $save->save();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'The data has been deleted.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Error : ' . $e->getMessage()
            ]);
        }
    }

    public function save_asset(Request $request){
        $data = $request->all(); // รับข้อมูลจาก JS
        $assetdata = [
            'row_id'        => $data['data']['row'] ?? null,
            'Banquet_ID'    => $data['data']['BEOID'] ?? null,
            'item'          => $data['data']['assetItem'] ?? null,
            'quantity'          => $data['data']['quantity'] ?? null,
            'remarks'      => $data['data']['remarks'] ?? null,
            'price'         => $data['data']['price'] ?? null,
        ];
        $banquet = banquet_event_order::where('Banquet_ID', $assetdata['Banquet_ID'])->first();
        if ($banquet) {
            $asset = banquet_asset::where('Banquet_ID', $assetdata['Banquet_ID'])->where('row_id', $assetdata['row_id'])->first();
            if ( $asset) {
                $asset_id = $asset->id;
                $BEOID = $asset->Banquet_ID;
                $changed = array_filter($assetdata, function ($value, $key) use ($asset) {
                    return $asset->$key != $value;
                }, ARRAY_FILTER_USE_BOTH);
                try {
                    DB::beginTransaction();
                    $fullname = 'รหัส : '.$BEOID;
                    $edit = 'รายการแก้ไข';
                    $formattedschedules = [];
                    foreach ($changed as $key => $newValue) {
                        $label = ucfirst($key); // ทำให้ตัวแรกเป็นตัวพิมพ์ใหญ่
                        $formattedschedules[] = "$label : $newValue";
                    }
                    $formattedschedulesText = implode(' , ', $formattedschedules);
                    $datacompany = '';

                    $variables = [$fullname, $edit, $formattedschedulesText];
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
                    $save->Company_ID = $BEOID;
                    $save->type = 'Edit Assets';
                    $save->Category = 'Edit :: Banquet event order (assets)';
                    $save->content =$datacompany;
                    $save->save();
                    $save = banquet_asset::find($asset_id);
                    $save->Banquet_ID = $BEOID;
                    $save->row_id = $assetdata['row_id'];
                    $save->item = $assetdata['item'];
                    $save->quantity = $assetdata['quantity'];
                    $save->remarks = $assetdata['remarks'];
                    $save->price = $assetdata['price'];
                    $save->save();
                    DB::commit();
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data has been successfully saved.',
                    ]);
                } catch (\Throwable $e) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Error : ' . $e->getMessage()
                    ]);
                }
            }else{
                try {
                    DB::beginTransaction();
                    $fullname = 'รหัส : '.$assetdata['Banquet_ID'];
                    $edit = 'รายละเอียด';
                    $formattedasset = [];
                    foreach ($assetdata as $key => $newValue) {
                        $label = ucfirst($key); // ทำให้ตัวแรกเป็นตัวพิมพ์ใหญ่
                        $formattedasset[] = "$label : $newValue";
                    }
                    $formattedassetText = implode(' , ', $formattedasset);
                    $datacompany = '';
                    $variables = [$fullname, $edit, $formattedassetText];
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
                    $save->Company_ID = $assetdata['Banquet_ID'];
                    $save->type = 'Create Assets';
                    $save->Category = 'Create :: Banquet event order (assets)';
                    $save->content =$datacompany;
                    $save->save();
                    $save = new banquet_asset();
                    $save->Banquet_ID = $assetdata['Banquet_ID'];
                    $save->row_id = $assetdata['row_id'];
                    $save->item = $assetdata['item'];
                    $save->quantity = $assetdata['quantity'];
                    $save->remarks = $assetdata['remarks'];
                    $save->price = $assetdata['price'];
                    $save->save();
                    DB::commit();
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data has been successfully saved.',
                    ]);
                } catch (\Throwable $e) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Error : ' . $e->getMessage()
                    ]);
                }
            }
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Error : Please enter the Event Details first.'
            ]);
        }
    }
    public function delete_asset(Request $request){
        $data = $request->all(); // รับข้อมูลจาก JS
        $assetdata = [
            'row_id'        => $data['data']['row'] ?? null,
            'Banquet_ID'    => $data['data']['BEOID'] ?? null,
        ];
        try {
            DB::beginTransaction();
            banquet_asset::where('Banquet_ID', $assetdata['Banquet_ID'])->where('row_id',$assetdata['row_id'])->delete();
            $userid = Auth::user()->id;
            $save = new log_company();
            $save->Created_by = $userid;
            $save->Company_ID = $assetdata['Banquet_ID'];
            $save->type = 'Delete Assets';
            $save->Category = 'Delete :: Banquet event order (assets)';
            $save->content ='ลบข้อมูล Assets +รหัส :'.$assetdata['Banquet_ID'].'+'.'ลำดับที่ '.$assetdata['row_id'];
            $save->save();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'The data has been deleted.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Error : ' . $e->getMessage()
            ]);
        }
    }
}
