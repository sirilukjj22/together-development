<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\dummy_quotation;
use Illuminate\Support\Facades\DB;
use App\Models\document_quotation;
use App\Models\document_dummy_quotation;
use App\Models\Quotation;
use Carbon\Carbon;
use App\Models\companys;
use App\Models\log;
use App\Models\representative;
use App\Models\representative_phone;
use App\Models\company_fax;
use App\Models\company_phone;
use App\Models\log_company;
use App\Models\Freelancer_Member;
use App\Models\province;
use App\Models\amphures;
use App\Models\districts;
use App\Models\master_document;
use App\Models\master_product_item;
use App\Models\master_quantity;
use App\Models\master_unit;
use App\Models\Quotation_main_confirm;
use App\Models\Master_company;
use App\Models\receive_payment;
use App\Models\phone_guest;
use App\Models\Guest;
use Auth;
use App\Models\User;
use PDF;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use App\Models\master_document_sheet;
use App\Models\confirmation_request;
use Dompdf\Dompdf;
use App\Models\master_template;
use Illuminate\Support\Arr;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\document_proposal_overbill;
use App\Models\Master_additional;
use App\Models\proposal_overbill;
class proposal_request extends Controller
{
    public function index()
    {
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $quotation = Quotation::where('status_document', 2)
        ->groupBy('Company_ID','Operated_by')->select('id','DummyNo','type_Proposal', 'Company_ID','Operated_by','QuotationType',DB::raw("COUNT(DummyNo) as COUNTDummyNo"));

        $proposal = dummy_quotation::where('status_document', 2)
            ->groupBy('Company_ID','Operated_by')
            ->select('id','DummyNo','type_Proposal', 'Company_ID','Operated_by','QuotationType',DB::raw("COUNT(DummyNo) as COUNTDummyNo"))
            ->union($quotation)
            ->get();
        $quotation1 = Quotation::where('status_document', 2)
        ->groupBy('Company_ID', 'Operated_by')
        ->select('id', 'DummyNo', 'Company_ID', 'Operated_by', 'QuotationType', DB::raw("COUNT(DummyNo) as COUNTDummyNo"));

        $proposal1 = dummy_quotation::where('status_document', 2)
        ->groupBy('Company_ID', 'Operated_by')
        ->select('id', 'DummyNo', 'Company_ID', 'Operated_by', 'QuotationType', DB::raw("COUNT(DummyNo) as COUNTDummyNo"))
        ->union($quotation1);
        $proposalcount = DB::table(DB::raw("({$proposal1->toSql()}) as sub"))->mergeBindings($proposal1->getQuery())->count();
        $requestcount =confirmation_request::query()->where('status',1)->count();
        $currentDateTime = Carbon::now();
        confirmation_request::where('expiration_time', '<', $currentDateTime)->delete();
        $request =confirmation_request::query()->where('status',1)->get();
        $Additional =proposal_overbill::query()->where('status_document',2)->get();
        $Additionalcount =proposal_overbill::query()->where('status_document',2)->count();
        return view('proposal_req.index',compact('proposal','proposalcount','requestcount','request','Additional','Additionalcount'));
    }
    public function view($id,$Type,$createby)
    {
        if ($Type == 'DummyProposal') {
            $Data = dummy_quotation::where('Company_ID', $id)->where('Operated_by', $createby)->where('status_document', 2)->get();
            $Datacount = dummy_quotation::where('Company_ID', $id)->where('Operated_by', $createby)->where('status_document', 2)->count();
        } else if ($Type == 'Proposal') {
            $Data = Quotation::where('Company_ID', $id)->where('Operated_by', $createby)->where('status_document', 2)->get();
            $Datacount = Quotation::where('Company_ID', $id)->where('Operated_by', $createby)->where('status_document', 2)->count();
        }

        $myData = [];
            foreach ($Data as $item) {
                $Company_ID = $item->Company_ID;
                $type_Proposal = $item->type_Proposal;
                if ($type_Proposal == 'Company') {
                    $contact = representative::where('status', 1)
                                ->where('Company_ID', $Company_ID)
                                ->first();
                    $representative_ID = $contact->Profile_ID;
                    $repCompany_ID = $contact->Company_ID;
                    $phone = representative_phone::where('Profile_ID',$representative_ID)->where('Company_ID',$repCompany_ID)->where('Sequence','main')->first();
                    $phonecontact = $phone->Phone_number;
                    $company_fax = company_fax::where('Profile_ID',$Company_ID)->where('Sequence','main')->first();
                    if ($company_fax) {
                        $fax = $company_fax->Fax_number;
                    }else {
                        $fax = '-';
                    }
                    $company_phone = company_phone::where('Profile_ID',$Company_ID)->where('Sequence','main')->first();
                    $phone = $company_phone->Phone_number;
                    $company = companys::where('Profile_ID', $Company_ID)->first();
                    $Company_type = $company->Company_type;
                    $CityID=$company->City;
                    $provinceNames =null;
                    $amphuresNames =null;
                    $TambonNames =null;
                    $Zip_Code =null;
                    if ($CityID) {
                        $amphuresID = $company->Amphures;
                        $TambonID = $company->Tambon;
                        $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                        $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                        $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                        //----------------------------------------
                        $provinceNames = ' จังหวัด : '.$provinceNames->name_th;
                        $amphuresNames = ' อำเภอ : '.$amphuresID->name_th;
                        $TambonNames = ' ตำบล : '.$TambonID->name_th;
                        $Zip_Code = $TambonID->Zip_Code;
                    }
                    $comtype = master_document::where('id', $Company_type)->where('Category', 'Mcompany_type')->first();
                    if ($comtype) {
                        if ($comtype->name_th == "บริษัทจำกัด") {
                            $fullName = "บริษัท " . $company->Company_Name . " จำกัด";
                        } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                            $fullName = "บริษัท " . $company->Company_Name . " จำกัด (มหาชน)";
                        } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                            $fullName = "ห้างหุ้นส่วนจำกัด " . $company->Company_Name;
                        }else{
                            $fullName = $comtype->name_th. $company->Company_Name ;
                        }
                    }
                    $Adress = $company->Address;
                    $email = $company->Company_Email;
                    $Identification = $company->Taxpayer_Identification;
                    //----------------------------------

                    $fullNameCon = $contact->First_name.' '.$contact->Last_name;
                    $emailcontact = $contact->Email;
                }else {
                    $guest = Guest::where('Profile_ID', $Company_ID)->first();
                    $Preface = $guest->preface;
                    $Mprefix = master_document::where('id', $Preface)->where('Category', 'Mprename')->first();
                    if ($Mprefix) {
                        $fullName = $Mprefix->name_th . $guest->First_name . ' ' . $guest->First_name;
                    }
                    $CityID=$guest->City;
                    $provinceNames =null;
                    $amphuresNames =null;
                    $TambonNames =null;
                    $Zip_Code =null;
                    if ($CityID) {
                        $amphuresID = $guest->Amphures;
                        $TambonID = $guest->Tambon;
                        $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                        $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                        $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                        //----------------------------------------
                        $provinceNames = ' จังหวัด : '.$provinceNames->name_th;
                        $amphuresNames = ' อำเภอ : '.$amphuresID->name_th;
                        $TambonNames = ' ตำบล : '.$TambonID->name_th;
                        $Zip_Code = $TambonID->Zip_Code;
                    }

                    $Adress = $guest->Address;
                    $email = $guest->Email;
                    $Identification = $guest->Identification_Number;
                    //-------------------------------------
                    $phone = phone_guest::where('Profile_ID',$Company_ID)->where('Sequence','main')->first();
                    $phonecontact = $phone->Phone_number;
                    $phone = $phone->Phone_number;
                    $fullNameCon = $guest->First_name.' '.$guest->Last_name;
                    $emailcontact = $guest->Email;
                    $fax = '-';
                }
                $checkin = $item->checkin;
                $checkout = $item->checkout;
                $day = $item->day;
                $night = $item->night;
                $adult = $item->adult;
                $children = $item->children;
                $DummyNo = $item->DummyNo;
                $issue_date = $item->issue_date;
                $Expirationdate = $item->Expirationdate;
                $vat_type = $item->vat_type;
                if ($vat_type == 50) {
                    $vat ='Price Include Vat';
                }elseif ($vat_type == 51) {
                    $vat ='Price Exclude Vat';
                }else{
                    $vat = 'Price Plus Vat';
                }
                $Date_type = $item->Date_type;
                $myData[] = [
                    'id' => $item->id,
                    'Proposal' => $DummyNo,
                    'type_Proposal'=>$type_Proposal,
                    'fullName'=>$fullName,
                    'Adress'=>$Adress,
                    'email'=>$email,
                    'Identification'=>$Identification,
                    'amphuresNames'=>$amphuresNames,
                    'provinceNames'=>$provinceNames,
                    'TambonNames'=>$TambonNames,
                    'Zip_Code'=>$Zip_Code,
                    'phonecontact'=>$phonecontact,
                    'fax'=>$fax,
                    'phone'=>$phone,
                    'fullNameCon'=>$fullNameCon,
                    'emailcontact'=>$emailcontact,
                    'checkin'=>$checkin,
                    'checkout'=>$checkout,
                    'day'=>$day,
                    'night'=>$night,
                    'adult'=>$adult,
                    'children'=>$children,
                    'issue_date'=>$issue_date,
                    'Expirationdate'=>$Expirationdate,
                    'vat'=>$vat,
                    'Type'=>$Type,
                    'Date_type'=>$Date_type,

                ];
            }
            $datarequest = collect($myData);
            $product = master_product_item::where('status',1)->get();
            $unit = master_unit::where('status',1)->get();
            $quantity = master_quantity::where('status',1)->get();
        return view('proposal_req.view', compact('datarequest','Data','product','unit','quantity','Datacount'));
    }
    public function Approve(Request $request){
        try {
            $data=$request->all();
            $id = $request->approved_id;
            $QuotationType = $request->QuotationType;

            if ($QuotationType == 'DummyProposal') {
                $proposal = dummy_quotation::where('DummyNo',$id)->first();
                $status = $proposal->status_document;
                $company = $proposal->Company_ID;
                $dummyNos = $proposal->DummyNo;
                $userid = Auth::user()->id;
                $currentDateTime = Carbon::now();
                $currentDate = $currentDateTime->toDateString(); // Format: YYYY-MM-DD
                $currentTime = $currentDateTime->toTimeString(); // Format: HH:MM:SS
                // Optionally, you can format the date and time as per your requirement
                $formattedDate = $currentDateTime->format('Y-m-d'); // Custom format for date
                $formattedTime = $currentDateTime->format('H:i:s');
                if ($status == 2) {
                    $proposal->status_document = 3;
                    $proposal->Confirm_by = $userid;
                    $proposal->Approve_at = $currentDateTime;
                    $proposal->save();
                }
                $userids = Auth::user()->id;
                $save = new log_company();
                $save->Created_by = $userids;
                $save->Company_ID = $dummyNos;
                $save->type = 'Request Approval';
                $save->Category = 'Approval :: Dummy Proposal';
                $save->content = 'Approval Document Dummy Proposal ID : '.$dummyNos;
                $save->save();
                $proposalNo = dummy_quotation::where('Company_ID',$company)->where('status_document', 2)->get();

                foreach ($proposalNo as $item) {
                    $dummyNoid = $item->DummyNo;
                    $savePDF = new log();
                    $savePDF->Quotation_ID = $dummyNoid;
                    $savePDF->QuotationType = 'DummyProposal';
                    $savePDF->Approve_date = $formattedDate;
                    $savePDF->Approve_time = $formattedTime;
                    $savePDF->save();
                    // ดึงข้อมูลใบเสนอราคาที่มี DummyNo ตามที่กำหนด
                    $Quotation = dummy_quotation::where('DummyNo', $dummyNoid)->first();
                    $Quotation_ID = $Quotation->DummyNo;
                    $selectproduct = document_dummy_quotation::where('Quotation_ID', $Quotation_ID)->get();
                    $datarequest = [
                        'Proposal_ID' => $Quotation['DummyNo'] ?? null,
                        'IssueDate' => $Quotation['issue_date'] ?? null,
                        'Expiration' => $Quotation['Expirationdate'] ?? null,
                        'Selectdata' => $Quotation['type_Proposal'] ?? null,
                        'Data_ID' => $Quotation['Company_ID'] ?? null,
                        'Adult' => $Quotation['adult'] ?? null,
                        'Children' => $Quotation['children'] ?? null,
                        'Mevent' => $Quotation['eventformat'] ?? null,
                        'Mvat' => $Quotation['vat_type'] ?? null,
                        'DiscountAmount' => $Quotation['SpecialDiscountBath'] ?? null,
                        'comment' => $Quotation['comment'] ?? null,
                        'PaxToTalall' => $Quotation['TotalPax'] ?? null,
                        'Checkin' => $Quotation['checkin'] ?? null,
                        'Checkout' => $Quotation['checkout'] ?? null,
                        'Day' => $Quotation['day'] ?? null,
                        'Night' => $Quotation['night'] ?? null,
                        'userid'=> $Quotation['Operated_by'] ?? null,
                    ];
                    $Products = Arr::wrap($selectproduct->pluck('Product_ID')->toArray());
                    $quantities = $selectproduct->pluck('Quantity')->toArray();
                    $discounts = $selectproduct->pluck('discount')->toArray();
                    $priceUnits = $selectproduct->pluck('priceproduct')->toArray();
                    $Unitmain = $selectproduct->pluck('Unit')->toArray();
                    $productItems = [];
                    $totaldiscount = [];
                    foreach ($Products as $index => $productID) {
                        if (count($quantities) === count($priceUnits) && count($priceUnits) === count($discounts) && count($priceUnits) === count($Unitmain)) {
                            $totalPrices = []; // เปลี่ยนจากตัวแปรเดียวเป็น array เพื่อเก็บผลลัพธ์แต่ละรายการ
                            $discountedPrices = [];
                            $discountedPricestotal = [];
                            $totaldiscount = [];
                            // คำนวณราคาสำหรับแต่ละรายการ
                            for ($i = 0; $i < count($quantities); $i++) {
                                $quantity = intval($quantities[$i]);
                                $unitValue = intval($Unitmain[$i]); // เปลี่ยนชื่อเป็น $unitValue
                                $priceUnit = floatval(str_replace(',', '', $priceUnits[$i]));
                                $discount = floatval($discounts[$i]);

                                $totaldiscount0 = (($priceUnit * $discount)/100);
                                $totaldiscount[] = $totaldiscount0;

                                $totalPrice = ($quantity * $unitValue) * $priceUnit;
                                $totalPrices[] = $totalPrice;

                                $discountedPrice = (($totalPrice * $discount) / 100);
                                $discountedPrices[] = $discountedPrice;

                                $discountedPriceTotal = $totalPrice - $discountedPrice;
                                $discountedPricestotal[] = $discountedPriceTotal;

                            }
                        }

                        $items = master_product_item::where('Product_ID', $productID)->get();
                        $QuotationVat= $datarequest['Mvat'];
                        $Mvat = master_document::where('id',$QuotationVat)->where('status', '1')->where('Category','Mvat')->select('name_th','id')->first();
                        foreach ($items as $item) {
                            // ตรวจสอบและกำหนดค่า quantity และ discount
                            $quantity = isset($quantities[$index]) ? $quantities[$index] : 0;
                            $unitValue = isset($Unitmain[$index]) ? $Unitmain[$index] : 0;
                            $discount = isset($discounts[$index]) ? $discounts[$index] : 0;
                            $totalPrices = isset($totalPrices[$index]) ? $totalPrices[$index] : 0;
                            $discountedPrices = isset($discountedPrices[$index]) ? $discountedPrices[$index] : 0;
                            $discountedPricestotal = isset($discountedPricestotal[$index]) ? $discountedPricestotal[$index] : 0;
                            $totaldiscount = isset($totaldiscount[$index]) ? $totaldiscount[$index] : 0;
                            $productItems[] = [
                                'product' => $item,
                                'quantity' => $quantity,
                                'unit' => $unitValue,
                                'discount' => $discount,
                                'totalPrices'=>$totalPrices,
                                'discountedPrices'=>$discountedPrices,
                                'discountedPricestotal'=>$discountedPricestotal,
                                'totaldiscount'=>$totaldiscount,
                            ];
                        }

                    }
                    {//คำนวน
                        $totalAmount = 0;
                        $totalPrice = 0;
                        $subtotal = 0;
                        $beforeTax = 0;
                        $AddTax = 0;
                        $Nettotal =0;
                        $totalaverage=0;

                        $SpecialDistext = $datarequest['DiscountAmount'];
                        $SpecialDis = floatval($SpecialDistext);
                        $totalguest = 0;
                        $totalguest = $datarequest['Adult'] + $datarequest['Children'];
                        $guest = $datarequest['PaxToTalall'];
                        if ($Mvat->id == 50) {
                            foreach ($productItems as $item) {
                                $totalPrice += $item['totalPrices'];
                                $totalAmount += $item['discountedPricestotal'];
                                $subtotal = $totalAmount-$SpecialDis;
                                $beforeTax = $subtotal/1.07;
                                $AddTax = $subtotal-$beforeTax;
                                $Nettotal = $subtotal;
                                $totalaverage =$Nettotal/$guest;

                            }
                        }
                        elseif ($Mvat->id == 51) {
                            foreach ($productItems as $item) {
                                $totalPrice += $item['totalPrices'];
                                $totalAmount += $item['discountedPricestotal'];
                                $subtotal = $totalAmount-$SpecialDis;
                                $Nettotal = $subtotal;
                                $totalaverage =$Nettotal/$guest;

                            }
                        }
                        elseif ($Mvat->id == 52) {
                            foreach ($productItems as $item) {
                                $totalPrice += $item['totalPrices'];
                                $totalAmount += $item['discountedPricestotal'];
                                $subtotal = $totalAmount-$SpecialDis;
                                $AddTax = $subtotal*7/100;
                                $Nettotal = $subtotal+$AddTax;
                                $totalaverage =$Nettotal/$guest;
                            }
                        }else
                        {
                            foreach ($productItems as $item) {
                                $totalPrice += $item['totalPrices'];
                                $totalAmount += $item['discountedPricestotal'];
                                $subtotal = $totalAmount-$SpecialDis;
                                $beforeTax = $subtotal/1.07;
                                $AddTax = $subtotal-$beforeTax;
                                $Nettotal = $subtotal;
                                $totalaverage =$Nettotal/$guest;
                            }
                        }
                        $pagecount = count($productItems);
                        $page = $pagecount/10;

                        $page_item = 1;
                        if ($page > 1.1 && $page < 2.1) {
                            $page_item += 1;

                        } elseif ($page > 1.1) {
                        $page_item = 1 + $page > 1.1 ? ceil($page) : 1;
                        }
                    }
                    {//QRCODE
                        $id = $datarequest['Proposal_ID'];
                        $protocol = $request->secure() ? 'https' : 'http';
                        $linkQR = $protocol . '://' . $request->getHost() . "/Quotation/Quotation/cover/document/PDF/$id?page_shop=" . $request->input('page_shop');
                        $qrCodeImage = QrCode::format('svg')->size(200)->generate($linkQR);
                        $qrCodeBase64 = base64_encode($qrCodeImage);
                    }
                    $userid = $datarequest['userid'];
                    $Proposal_ID = $datarequest['Proposal_ID'];
                    $IssueDate = $datarequest['IssueDate'];
                    $Expiration = $datarequest['Expiration'];
                    $Selectdata = $datarequest['Selectdata'];
                    $Data_ID = $datarequest['Data_ID'];
                    $Adult = $datarequest['Adult'];
                    $Children = $datarequest['Children'];
                    $Mevent = $datarequest['Mevent'];
                    $Mvat = $datarequest['Mvat'];
                    $DiscountAmount = $datarequest['DiscountAmount'];
                    $Checkin = $datarequest['Checkin'];
                    $Checkout = $datarequest['Checkout'];
                    $Day = $datarequest['Day'];
                    $Night = $datarequest['Night'];
                    $comment = $datarequest['comment'];
                    $user = User::where('id',$userid)->select('id','name')->first();
                    $fullName = null;
                    $Contact_Name = null;
                    $Contact_phone =null;
                    $Contact_Email = null;
                    if ($Selectdata == 'Guest') {
                        $Data = Guest::where('Profile_ID',$Data_ID)->first();
                        $prename = $Data->preface;
                        $First_name = $Data->First_name;
                        $Last_name = $Data->Last_name;
                        $Address = $Data->Address;
                        $Email = $Data->Email;
                        $Taxpayer_Identification = $Data->Identification_Number;
                        $prefix = master_document::where('id',$prename)->where('Category','Mprename')->where('status',1)->first();
                        $name = $prefix->name_th;
                        $fullName = $name.' '.$First_name.' '.$Last_name;
                        //-------------ที่อยู่
                        $CityID=$Data->City;
                        $amphuresID = $Data->Amphures;
                        $TambonID = $Data->Tambon;
                        $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                        $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                        $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                        $Fax_number = '-';
                        $phone = phone_guest::where('Profile_ID',$Data_ID)->where('Sequence','main')->first();
                    }else{
                        $Company = companys::where('Profile_ID',$Data_ID)->first();
                        $Company_type = $Company->Company_type;
                        $Compannyname = $Company->Company_Name;
                        $Address = $Company->Address;
                        $Email = $Company->Company_Email;
                        $Taxpayer_Identification = $Company->Taxpayer_Identification;
                        $comtype = master_document::where('id', $Company_type)->where('Category', 'Mcompany_type')->first();
                        if ($comtype) {
                            if ($comtype->name_th == "บริษัทจำกัด") {
                                $fullName = "บริษัท " . $Compannyname . " จำกัด";
                            } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                                $fullName = "บริษัท " . $Compannyname . " จำกัด (มหาชน)";
                            } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                                $fullName = "ห้างหุ้นส่วนจำกัด " . $Compannyname;
                            }
                        }
                        $representative = representative::where('Company_ID',$Data_ID)->first();
                        $prename = $representative->prefix;
                        $Contact_Email = $representative->Email;
                        $prefix = master_document::where('id', $prename)->where('Category', 'Mprename')->first();
                        $name = $prefix->name_th;
                        $Contact_Name = $representative->First_name.' '.$representative->Last_name;
                        $CityID=$Company->City;
                        $amphuresID = $Company->Amphures;
                        $TambonID = $Company->Tambon;
                        $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                        $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                        $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                        $company_fax = company_fax::where('Profile_ID',$Data_ID)->where('Sequence','main')->first();
                        if ($company_fax) {
                            $Fax_number =  $company_fax->Fax_number;
                        }else{
                            $Fax_number = '-';
                        }
                        $phone = company_phone::where('Profile_ID',$Data_ID)->where('Sequence','main')->first();
                        $Contact_phone = representative_phone::where('Company_ID',$Data_ID)->where('Sequence','main')->first();
                    }
                    $eventformat = master_document::where('id',$Mevent)->select('name_th','id')->first();
                    $template = master_template::query()->latest()->first();
                    $CodeTemplate = $template->CodeTemplate;
                    $sheet = master_document_sheet::select('topic','name_th','id','CodeTemplate')->get();
                    $Reservation_show = $sheet->where('topic', 'Reservation')->where('CodeTemplate',$CodeTemplate)->first();
                    $Paymentterms = $sheet->where('topic', 'Paymentterms')->where('CodeTemplate',$CodeTemplate)->first();
                    $note = $sheet->where('topic', 'note')->where('CodeTemplate',$CodeTemplate)->first();
                    $Cancellations = $sheet->where('topic', 'Cancellations')->where('CodeTemplate',$CodeTemplate)->first();
                    $Complimentary = $sheet->where('topic', 'Complimentary')->where('CodeTemplate',$CodeTemplate)->first();
                    $All_rights_reserved = $sheet->where('topic', 'All_rights_reserved')->where('CodeTemplate',$CodeTemplate)->first();
                    $date = Carbon::now();
                    $unit = master_unit::where('status',1)->get();
                    $quantity = master_quantity::where('status',1)->get();
                    $settingCompany = Master_company::orderBy('id', 'desc')->first();
                    if ($Checkin) {
                        $checkin = $Checkin;
                        $checkout = $Checkout;
                    }else{
                        $checkin = '-';
                        $checkout = '-';
                    }
                    $data = [
                        'settingCompany'=>$settingCompany,
                        'page_item'=>$page_item,
                        'page'=>$pagecount,
                        'Selectdata'=>$Selectdata,
                        'date'=>$date,
                        'fullName'=>$fullName,
                        'provinceNames'=>$provinceNames,
                        'Address'=>$Address,
                        'amphuresID'=>$amphuresID,
                        'TambonID'=>$TambonID,
                        'Email'=>$Email,
                        'phone'=>$phone,
                        'Fax_number'=>$Fax_number,
                        'Day'=>$Day,
                        'Night'=>$Night,
                        'Checkin'=>$checkin,
                        'Checkout'=>$checkout,
                        'eventformat'=>$eventformat,
                        'totalguest'=>$totalguest,
                        'Reservation_show'=>$Reservation_show,
                        'Paymentterms'=>$Paymentterms,
                        'note'=>$note,
                        'Cancellations'=>$Cancellations,
                        'Complimentary'=>$Complimentary,
                        'All_rights_reserved'=>$All_rights_reserved,
                        'Proposal_ID'=>$Proposal_ID,
                        'IssueDate'=>$IssueDate,
                        'Expiration'=>$Expiration,
                        'qrCodeBase64'=>$qrCodeBase64,
                        'user'=>$user,
                        'Taxpayer_Identification'=>$Taxpayer_Identification,
                        'Adult'=>$Adult,
                        'Children'=>$Children,
                        'totalAmount'=>$totalAmount,
                        'SpecialDis'=>$SpecialDis,
                        'subtotal'=>$subtotal,
                        'beforeTax'=>$beforeTax,
                        'Nettotal'=>$Nettotal,
                        'totalguest'=>$totalguest,
                        'SpecialDistext'=>$SpecialDistext,
                        'guest'=>$guest,
                        'totalaverage'=>$totalaverage,
                        'AddTax'=>$AddTax,
                        'productItems'=>$productItems,
                        'unit'=>$unit,
                        'quantity'=>$quantity,
                        'Mvat'=>$Mvat,
                        'comment'=>$comment,
                        'Mevent'=>$Mevent,
                        'Contact_Name'=>$Contact_Name,
                        'Contact_phone'=>$Contact_phone,
                        'Contact_Email'=>$Contact_Email,
                    ];
                    $view= $template->name;
                    $pdf = FacadePdf::loadView('quotationpdf.'.$view,$data);
                    $path = 'Log_PDF/proposal/';
                    $pdf->save($path . $Quotation_ID . '.pdf');
                    $save = new log_company();
                    $save->Created_by = $userid;
                    $save->Company_ID = $Quotation_ID;
                    $save->type = 'Request Delete';
                    $save->Category = 'Delete :: Dummy Proposal';
                    $save->content = 'Delete Document Dummy Proposal ID : '.$Quotation_ID;
                    $save->save();
                    $dummyldID = dummy_quotation::where('DummyNo',$Quotation_ID)->delete();
                    $documentQuotationoldID = document_quotation::where('Quotation_ID',$Quotation_ID)->delete();
                }
                return redirect()->route('ProposalReq.index')->with('success', 'Data has been successfully saved.');
            }
            if($QuotationType == 'Proposal'){
                $proposal = Quotation::where('Quotation_ID',$id)->first();
                $status = $proposal->status_document;
                $company = $proposal->Company_ID;
                $userid = Auth::user()->id;
                $currentDateTime = Carbon::now();
                $currentDate = $currentDateTime->toDateString(); // Format: YYYY-MM-DD
                $currentTime = $currentDateTime->toTimeString(); // Format: HH:MM:SS

                // Optionally, you can format the date and time as per your requirement
                $formattedDate = $currentDateTime->format('Y-m-d'); // Custom format for date
                $formattedTime = $currentDateTime->format('H:i:s');
                if ($status == 2) {
                    $proposal->status_document = 1;
                    $proposal->Confirm_by = $userid;
                    $proposal->Approve_at = $currentDateTime;
                    $proposal->save();
                    $userids = Auth::user()->id;
                    $save = new log_company();
                    $save->Created_by = $userids;
                    $save->Company_ID = $id;
                    $save->type = 'Request Approval';
                    $save->Category = 'Approval :: Dummy Proposal';
                    $save->content = 'Approval Document Dummy Proposal ID : '.$id;
                    $save->save();
                }
                return redirect()->route('ProposalReq.index')->with('success', 'Data has been successfully saved.');
            }
        } catch (\Throwable $e) {
            return redirect()->route('ProposalReq.index')->with('error', $e->getMessage());
        }

    }
    public function Reject(Request $request){
        try{
            $data = $request->all();
            $id = $request->DummyNo;
            if (is_array($id)) {
                $string = implode(",", $id); // แปลง array เป็น string
            } else {
                $string = $id; // ถ้าไม่ใช่ array ให้ใช้ค่าของตัวแปรนั้นโดยตรง
            }

            $Type = $request->QuotationType;
            $dummyNos = explode(',', $string);
            if ($Type =='DummyProposal') {
                $proposalNo = dummy_quotation::whereIn('DummyNo',$dummyNos)->where('status_document', 2)->get();
                $userid = Auth::user()->id;
                foreach ($proposalNo as $item) {
                    $item->status_document = 4;
                    $item->Confirm_by = $userid;
                    $item->save();
                    $userid = Auth::user()->id;
                    $save = new log_company();
                    $save->Created_by = $userid;
                    $save->Company_ID = $dummyNos;
                    $save->type = 'Request Reject';
                    $save->Category = 'Reject :: Dummy Proposal';
                    $save->content = 'Reject Document Dummy Proposal ID : '.$dummyNos;
                    $save->save();
                }
            }
            if ($Type =='Proposal') {

                $proposalNo = Quotation::whereIn('Quotation_ID',$dummyNos)->where('status_document', 2)->get();
                $userid = Auth::user()->id;
                foreach ($proposalNo as $item) {
                    $item->status_document = 4;
                    $item->Confirm_by = $userid;
                    $item->save();

                    $userid = Auth::user()->id;
                    $save = new log_company();
                    $save->Created_by = $userid;
                    $save->Company_ID = $dummyNos;
                    $save->type = 'Request Reject';
                    $save->Category = 'Reject :: Proposal';
                    $save->content = 'Reject Document Proposal ID : '.$dummyNos;
                    $save->save();
                }
            }
            return redirect()->route('ProposalReq.index')->with('success', 'Data has been successfully saved.');
        } catch (\Throwable $e) {
            return redirect()->route('ProposalReq.index')->with('error', $e->getMessage());
        }
    }


    public function viewApprove($id)
    {
        $Quotation = dummy_quotation::where('id', $id)->first();
        if ($Quotation) {
            $QuotationID= $Quotation->DummyNo;
            $selectproduct = document_dummy_quotation::where('Quotation_ID', $QuotationID)->get();
        }else{
            $Quotation = Quotation::where('id', $id)->first();
            $QuotationID= $Quotation->Quotation_ID;
            $selectproduct = document_quotation::where('Quotation_ID', $QuotationID)->get();
        }

        $Company_ID = $Quotation->Company_ID;
        $contact = $Quotation->company_contact;

        $Mevent = master_document::select('name_th','id')->where('status', '1')->where('Category','Mevent')->get();
        $Mvat = master_document::select('name_th','id')->where('status', '1')->where('Category','Mvat')->get();
        $Freelancer_member = Freelancer_Member::select('First_name','id','Profile_ID','Last_name')->where('status', '1')->get();
        $Company = companys::select('Company_Name','id','Profile_ID')->get();
        $CompanyID = companys::where('Profile_ID',$Company_ID)->first();
        $Company_typeID=$CompanyID->Company_type;
        $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
        if ($comtype->name_th =="บริษัทจำกัด") {
            $comtypefullname = "บริษัท ". $CompanyID->Company_Name . " จำกัด";
        }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
            $comtypefullname = "บริษัท ". $CompanyID->Company_Name . " จำกัด (มหาชน)";
        }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
            $comtypefullname = "ห้างหุ้นส่วนจำกัด ". $CompanyID->Company_Name ;
        }else {
            $comtypefullname = $CompanyID->Company_Name;
        }
        $CityID=$CompanyID->City;
        $amphuresID = $CompanyID->Amphures;
        $TambonID = $CompanyID->Tambon;
        $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
        $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
        $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
        $company_fax = company_fax::where('Profile_ID',$Company_ID)->where('Sequence','main')->first();
        $company_phone = company_phone::where('Profile_ID',$Company_ID)->where('Sequence','main')->first();
        $Contact_name = representative::where('Company_ID',$Company_ID)->where('id',$contact)->where('status',1)->first();
        $profilecontact = $Contact_name->Profile_ID;
        $Contact_phone = representative_phone::where('Company_ID',$Company_ID)->where('Profile_ID',$profilecontact)->where('Sequence','main')->first();

        $unit = master_unit::where('status',1)->get();
        $quantity = master_quantity::where('status',1)->get();
        return view('proposal_req.viewlog',compact('Quotation','Freelancer_member','Company','Mevent','Mvat','Contact_name','comtypefullname','CompanyID'
        ,'TambonID','amphuresID','CityID','provinceNames','company_fax','company_phone','Contact_phone','selectproduct','unit','quantity','QuotationID'));
    }

    public function LOG()
    {
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $userid = Auth::user()->id;
        $log = log_company::select(
            'log_company.*',
            'quotation.id as quotation_id',
            'dummy_quotation.id as dummy_quotation_id'
        )
        ->whereIn('log_company.type', ['Request Reject', 'Request Approval', 'Request Delete','Send documents'])
        ->leftJoin('quotation', 'log_company.Company_ID', '=', 'quotation.Quotation_ID')
        ->leftJoin('dummy_quotation', 'log_company.Company_ID', '=', 'dummy_quotation.DummyNo')
        ->orderBy('log_company.updated_at', 'desc')
        ->get();
        $logcount = log_company::whereIn('type', ['Request Reject', 'Request Approval', 'Request Delete ','Send documents'])
        ->orderBy('updated_at', 'desc')
        ->count();
        $path = 'Log_PDF/proposal/';
        return view('proposal_req.log',compact('userid','log','logcount','path'));
    }



    public function Additional($id){

        $settingCompany = Master_company::orderBy('id', 'desc')->first();
        $Quotation = proposal_overbill::where('id', $id)->first();
        $Additional_ID = $Quotation->Additional_ID;
        $Quotation_ID = $Quotation->Quotation_ID;
        $Quotation_IDoverbill = $Quotation->Additional_ID;
        $Operated_by= $Quotation->Operated_by;
        $additional_type= $Quotation->additional_type;
        $Mvat= $Quotation->vat_type;
        $Company = companys::select('Company_Name','id','Profile_ID')->get();
        $Guest = Guest::select('First_name','Last_name','id','Profile_ID')->get();
        $Freelancer_member = Freelancer_Member::select('First_name','id','Profile_ID','Last_name')->where('status', '1')->get();
        $selectproduct = document_proposal_overbill::where('Additional_ID', $Additional_ID)->get();
        $unit = master_unit::where('status',1)->get();
        $quantity = master_quantity::where('status',1)->get();
        $user = User::where('id',$Operated_by)->first();
        $path = 'Log_PDF/proposaloverbill/';

        $Proposal = Quotation::where('Quotation_ID',$Quotation_ID)->first();
        $Selectdata =  $Proposal->type_Proposal;
        if ($Selectdata == 'Guest') {
            $Data = Guest::where('Profile_ID',$Proposal->Company_ID)->first();
            $prename = $Data->preface;
            $First_name = $Data->First_name;
            $Last_name = $Data->Last_name;
            $Address = $Data->Address;
            $Email = $Data->Email;
            $Taxpayer_Identification = $Data->Identification_Number;
            $prefix = master_document::where('id',$prename)->where('Category','Mprename')->where('status',1)->first();
            $name = $prefix->name_th;
            $fullName = $name.' '.$First_name.' '.$Last_name;
            //-------------ที่อยู่
            $CityID=$Data->City;
            $amphuresID = $Data->Amphures;
            $TambonID = $Data->Tambon;
            $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
            $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
            $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
            $Fax_number = '-';
            $phone = phone_guest::where('Profile_ID',$Proposal->Company_ID)->where('Sequence','main')->first();

            $Contact_Name = null;
            $Contact_phone =null;
            $Contact_Email = null;
        }else{
            $Company = companys::where('Profile_ID',$Proposal->Company_ID)->first();
            $Company_type = $Company->Company_type;
            $Compannyname = $Company->Company_Name;
            $Address = $Company->Address;
            $Email = $Company->Company_Email;
            $Taxpayer_Identification = $Company->Taxpayer_Identification;
            $comtype = master_document::where('id', $Company_type)->where('Category', 'Mcompany_type')->first();
            if ($comtype) {
                if ($comtype->name_th == "บริษัทจำกัด") {
                    $fullName = "บริษัท " . $Compannyname . " จำกัด";
                } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                    $fullName = "บริษัท " . $Compannyname . " จำกัด (มหาชน)";
                } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                    $fullName = "ห้างหุ้นส่วนจำกัด " . $Compannyname;
                }else{
                    $fullName = $comtype->name_th . $Compannyname;
                }
            }
            $representative = representative::where('Company_ID',$Proposal->Company_ID)->first();
            $prename = $representative->prefix;
            $Contact_Email = $representative->Email;
            $prefix = master_document::where('id', $prename)->where('Category', 'Mprename')->first();
            $name = $prefix->name_th;
            $Contact_Name = 'คุณ '.$representative->First_name.' '.$representative->Last_name;
            $CityID=$Company->City;
            $amphuresID = $Company->Amphures;
            $TambonID = $Company->Tambon;
            $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
            $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
            $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
            $company_fax = company_fax::where('Profile_ID',$Proposal->Company_ID)->where('Sequence','main')->first();
            if ($company_fax) {
                $Fax_number =  $company_fax->Fax_number;
            }else{
                $Fax_number = '-';
            }
            $phone = company_phone::where('Profile_ID',$Proposal->Company_ID)->where('Sequence','main')->first();
            $Contact_phone = representative_phone::where('Company_ID',$Proposal->Company_ID)->where('Sequence','main')->first();
        }
        $ProposalID = $Proposal->id;
        $Proposal_ID = $Proposal->Quotation_ID;
        $totalAmount = $Proposal->Nettotal;
        $vat = $Proposal->vat_type;
        $nameid = $Proposal->Company_ID;
        $SpecialDiscountBath = $Proposal->SpecialDiscountBath;
        $SpecialDiscount = $Proposal->SpecialDiscount;
        $Selectdata =  $Proposal->type_Proposal;
        $subtotal = 0;
        $beforeTax =0;
        $AddTax =0;
        $Nettotal =0;
        $total =0;
        $totalreceipt =0;
        $totalreceiptre =0;
        if ($vat == 50) {
            $total =  $totalAmount;
            $subtotal = $totalAmount;
            $beforeTax = $subtotal/1.07;
            $AddTax = $subtotal-$beforeTax;
            $Nettotal = $subtotal;

        }elseif ($vat == 51) {
            $total =  $totalAmount;
            $subtotal = $totalAmount;
            $Nettotal = $subtotal;
        }elseif ($vat == 52) {
            $total =  $totalAmount;
            $subtotal = $totalAmount;
            $AddTax =$subtotal*7/100;
            $Nettotal = $subtotal+$AddTax;
        }
        $parts = explode('-', $nameid);
        $firstPart = $parts[0];
        if ($firstPart == 'C') {
            $company =  companys::where('Profile_ID',$nameid)->first();
            $Company_type = $company->Company_type;
            $comtype = master_document::where('id', $Company_type)->where('Category', 'Mcompany_type')->first();
            if ($comtype) {
                if ($comtype->name_th == "บริษัทจำกัด") {
                    $fullname = "บริษัท " . $company->Company_Name . " จำกัด";
                } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                    $fullname = "บริษัท " . $company->Company_Name . " จำกัด (มหาชน)";
                } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                    $fullname = "ห้างหุ้นส่วนจำกัด " . $company->Company_Name;
                }else{
                    $fullname = $comtype->name_th . $company->Company_Name;
                }
            }
            $Address=$company->Address;
            $CityID=$company->City;
            $amphuresID = $company->Amphures;
            $TambonID = $company->Tambon;
            $Identification = $company->Taxpayer_Identification;
            $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
            $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
            $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
            $address = $Address.' '.$TambonID->name_th.' '.$amphuresID->name_th.' '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
        }else{
            $guestdata =  Guest::where('Profile_ID',$nameid)->first();
            $fullname =  'คุณ '.$guestdata->First_name.' '.$guestdata->Last_name;
            $Address=$guestdata->Address;
            $CityID=$guestdata->City;
            $amphuresID = $guestdata->Amphures;
            $TambonID = $guestdata->Tambon;
            $Identification = $guestdata->Identification_Number;
            $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
            $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
            $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
            $address = $Address.' '.$TambonID->name_th.' '.$amphuresID->name_th.' '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
        }

        $Additional = proposal_overbill::where('Quotation_ID',$Proposal_ID)->first();

        $Rm = []; // กำหนดตัวแปร $Rm เป็น array ว่าง
        $FB = [];
        $BQ = [];
        $AT = [];
        $EM = [];
        $RmCount = 0;
        $FBCount = 0;
        $BQCount = 0;
        $EMCount = 0;
        $ATCount = 0;
        $AdditionaltotalReceipt = 0;
        $statusover = 1;
        $Receiptover = null;
        $Additional_ID = null;
        if ($Additional) {
            $AdditionaltotalReceipt =  $Additional->Nettotal;
            $Additional_ID = $Additional->Additional_ID;
            $document = document_proposal_overbill::where('Additional_ID',$Additional_ID)->get();

            $master = Master_additional::query()->get();
            $combinedData = $document->map(function($doc) use ($master) {
                $matchedMaster = $master->firstWhere('code', $doc->Code);

                if ($matchedMaster) { // ตรวจสอบว่าเจอข้อมูลที่ Code ตรงกันหรือไม่
                    return [
                        'Additional_ID' => $doc->Additional_ID,
                        'Code' => $doc->Code,
                        'Detail' => $doc->Detail,
                        'Amount' => $doc->Amount,
                        'type' => $matchedMaster->type,
                    ];
                }
                return null; // ถ้าไม่ตรงให้ส่งค่า null
            })->filter(); // ใช้ filter เพื่อกรอง null ออก

            foreach ($combinedData as $item) {
                if ($item['type'] == 'RM') {
                    $Rm[] = $item;
                } elseif ($item['type'] == 'FB') {
                    $FB[] = $item;
                } elseif ($item['type'] == 'BQ') {
                    $BQ[] = $item;
                } elseif ($item['type'] == 'AT') {
                    $AT[] = $item;
                } elseif ($item['type'] == 'EM') {
                    $EM[] = $item;
                }
            }

            foreach ($Rm as $item) {
                $RmCount +=  $item['Amount'];
            }

            foreach ($FB as $item) {
                $FBCount +=  $item['Amount'];
            }

            foreach ($BQ as $item) {
                $BQCount +=  $item['Amount'];
            }

            foreach ($EM as $item) {
                $EMCount +=  $item['Amount'];
            }

            foreach ($AT as $item) {
                $ATCount +=  $item['Amount'];
            }
        }
        return view('proposal_req.additional',compact('user','path','settingCompany','Quotation','Quotation_ID','Company','Guest','Mvat','Freelancer_member','selectproduct','Quotation_IDoverbill','Additional_ID',
                    'Proposal_ID','subtotal','beforeTax','AddTax','Nettotal','SpecialDiscountBath','total','Proposal','ProposalID','additional_type',
                    'fullname','firstPart','Identification','address','vat','Additional','AdditionaltotalReceipt','Receiptover','statusover','Additional_ID',
                    'Rm','FB','BQ','AT','EM','RmCount','FBCount','BQCount','EMCount','ATCount','provinceNames','amphuresID','TambonID','Fax_number','phone','Email','Taxpayer_Identification','Contact_Name','Contact_phone'
                    ,'Selectdata'));
    }
    public function Additional_Approve(Request $request){
        try {
            $Additional = proposal_overbill::where('Additional_ID', $request->approved_id)->first();
            $id = $Additional->id;
            $Additional_ID = $Additional->Additional_ID;
            $save = proposal_overbill::find($id);
            $save->status_document = 3;
            $save->save();
            $userid = Auth::user()->id;
            $save = new log_company();
            $save->Created_by = $userid;
            $save->Company_ID = $Additional_ID;
            $save->type = 'Request Approve Additional';
            $save->Category = 'Approve :: Approve Additional';
            $save->content = 'Approve Document Additional ID : '.$Additional_ID;
            $save->save();
        } catch (\Throwable $e) {
            return redirect()->route('ProposalReq.index')->with('error', $e->getMessage());
        }
        return redirect()->route('ProposalReq.index')->with('success', 'Data has been successfully saved.');
    }
    public function Additional_Reject(Request $request){
        try {
            $Additional = proposal_overbill::where('Additional_ID', $request->approved_id)->first();
            $id = $Additional->id;
            $Additional_ID = $Additional->Additional_ID;
            $save = proposal_overbill::find($id);
            $save->status_document = 4;
            $save->save();
            $userid = Auth::user()->id;
            $save = new log_company();
            $save->Created_by = $userid;
            $save->Company_ID = $Additional_ID;
            $save->type = 'Request Reject Additional';
            $save->Category = 'Reject :: Reject Additional';
            $save->content = 'Reject Document Additional ID : '.$Additional_ID;
            $save->save();
        } catch (\Throwable $e) {
            return redirect()->route('ProposalReq.index')->with('error', $e->getMessage());
        }
        return redirect()->route('ProposalReq.index')->with('success', 'Data has been successfully saved.');
    }
    public function Additional_LOG()
    {
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $userid = Auth::user()->id;
        $log = log_company::select(
            'log_company.*',
            'proposal_overbill.id as Additional_id',
            'proposal_overbill.correct as Additional_correct',
        )
        ->whereIn('log_company.type', ['Request Reject Additional', 'Request Approve Additional','Send documents'])
        ->leftJoin('proposal_overbill', 'log_company.Company_ID', '=', 'proposal_overbill.Additional_ID')
        ->orderBy('log_company.updated_at', 'desc')
        ->get();
        $logcount = log_company::whereIn('type', ['Request Reject', 'Request Approval', 'Request Delete','Send documents'])
        ->orderBy('updated_at', 'desc')
        ->count();
        $path = 'Log_PDF/additional/';
        return view('proposal_req.additional_log',compact('userid','log','logcount','path'));
    }



}
