<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quotation;
use Carbon\Carbon;
use App\Models\Guest;
use App\Models\companys;
use App\Models\representative;
use App\Models\representative_phone;
use App\Models\company_fax;
use App\Models\company_phone;
use App\Models\master_promotion;
use App\Models\Freelancer_Member;
use App\Models\province;
use App\Models\amphures;
use App\Models\districts;
use App\Models\master_document;
use App\Models\master_product_item;
use App\Models\master_quantity;
use App\Models\master_unit;
use App\Models\document_quotation;
use App\Models\log;
use App\Models\Master_company;
use App\Models\phone_guest;
use App\Models\document_invoices;
use App\Models\document_proposal_overbill;
use App\Models\Master_additional;
use App\Models\proposal_overbill;
use App\Models\company_tax;
use App\Models\guest_tax;
use App\Models\receive_payment;
use Auth;
use App\Models\User;
use PDF;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use App\Models\master_document_sheet;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\DB;
use App\Models\master_template;
use Illuminate\Support\Arr;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Mail\QuotationEmail;
use Illuminate\Support\Facades\Mail;
use App\Models\master_document_email;
use App\Models\log_company;

class Additional extends Controller
{
    public function index(){
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $Awaiting = proposal_overbill::query()->where('status_document',2)->get();
        $Awaitingcount = proposal_overbill::query()->where('status_document',2)->count();
        $Approved = proposal_overbill::query()->where('status_document',3)->get();
        $Approvedcount = proposal_overbill::query()->where('status_document',3)->count();
        $Reject = proposal_overbill::query()->where('status_document',4)->get();
        $Rejectcount = proposal_overbill::query()->where('status_document',4)->count();
        $Cancel = proposal_overbill::query()->where('status_document',0)->get();
        $Cancelcount = proposal_overbill::query()->where('status_document',0)->count();

        $Complete = proposal_overbill::query()->where('status_guest',1)->get();
        $Completecount = proposal_overbill::query()->where('status_guest',1)->count();
        return view('additional_charge.index',compact('Awaiting','Awaitingcount','Approved','Approvedcount','Reject','Rejectcount',
                    'Cancel','Cancelcount','Complete','Completecount'));
    }
    public function create($id){
        $currentDate = Carbon::now();
        $ID = 'AD-';
        $formattedDate = Carbon::parse($currentDate);       // วันที่
        $month = $formattedDate->format('m'); // เดือน
        $year = $formattedDate->format('y');
        $lastRun = proposal_overbill::latest()->first();
        $nextNumber = 1;

        if ($lastRun == null) {
            $nextNumber = $lastRun + 1;
        }else{
            $lastRunid = $lastRun->id;
            $nextNumber = $lastRunid + 1;
        }
        $newRunNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        $Quotation_IDoverbill = $ID.$year.$month.$newRunNumber;
        $settingCompany = Master_company::orderBy('id', 'desc')->first();
        $Quotation = Quotation::where('id', $id)->first();
        $Quotation_ID = $Quotation->Quotation_ID;
        $Selectdata =  $Quotation->type_Proposal;
        if ($Selectdata == 'Guest') {
            $Data = Guest::where('Profile_ID',$Quotation->Company_ID)->first();
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
            $Contact_Name = ' ';
            $Contact_phone = ' ';
            $phone = phone_guest::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
        }else{
            $Company = companys::where('Profile_ID',$Quotation->Company_ID)->first();
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
            $representative = representative::where('Company_ID',$Quotation->Company_ID)->first();
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
            $company_fax = company_fax::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
            if ($company_fax) {
                $Fax_number =  $company_fax->Fax_number;
            }else{
                $Fax_number = '-';
            }
            $phone = company_phone::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
            $Contact_phone = representative_phone::where('Company_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
        }

        $Mevent = master_document::select('name_th','id')->where('status', '1')->where('Category','Mevent')->get();
        $Mvat = master_document::select('name_th','id')->where('status', '1')->where('Category','Mvat')->get();
        $Freelancer_member = Freelancer_Member::select('First_name','id','Profile_ID','Last_name')->where('status', '1')->get();
        $selectproduct = document_quotation::where('Quotation_ID', $Quotation_ID)->get();
        $unit = master_unit::where('status',1)->get();
        $quantity = master_quantity::where('status',1)->get();

        return view('additional_charge.create',compact('Address','fullName','settingCompany','Quotation','Quotation_ID','Mevent','Mvat','Freelancer_member','selectproduct','unit','quantity','Quotation_IDoverbill',
                    'provinceNames','amphuresID','TambonID','Fax_number','phone','Email','Taxpayer_Identification','Contact_Name','Contact_phone','Selectdata'      ));
    }
    public function select_create(){
        $currentDate = Carbon::now();
        $ID = 'AD-';
        $formattedDate = Carbon::parse($currentDate);       // วันที่
        $month = $formattedDate->format('m'); // เดือน
        $year = $formattedDate->format('y');
        $lastRun = proposal_overbill::latest()->first();
        $nextNumber = 1;

        if ($lastRun == null) {
            $nextNumber = $lastRun + 1;
        }else{
            $lastRunid = $lastRun->id;
            $nextNumber = $lastRunid + 1;
        }
        $newRunNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        $additional = $ID.$year.$month.$newRunNumber;
        $settingCompany = Master_company::orderBy('id', 'desc')->first();
        $Mevent = master_document::select('name_th','id')->where('status', '1')->where('Category','Mevent')->get();
        $Mvat = master_document::select('name_th','id')->where('status', '1')->where('Category','Mvat')->get();
        $Quotation = Quotation::whereIn('status_document',[6,9])->get();

        return view('additional_charge.createselect',compact('additional','settingCompany','Mevent','Mvat','Quotation'));
    }
    public function select_save(Request $request){
        $Quotation = Quotation::where('Quotation_ID', $request->Quotation_ID)->first();
        $preview = $request->preview;
        $Quotation_ID=$request->Quotation_ID;
        $Quotationid = $Quotation->id;
        $Mvat = $Quotation->vat_type;
        $userid = Auth::user()->id;
        $data = $request->all();
        $Additional_ID=$request->Additional_ID;
        $additional_type=$request->additional_type;
        $currentDate = Carbon::now();
        $ID = 'AD-';
        $formattedDate = Carbon::parse($currentDate);       // วันที่
        $month = $formattedDate->format('m'); // เดือน
        $year = $formattedDate->format('y');
        $lastRun = proposal_overbill::latest()->first();
        $nextNumber = 1;

        if ($lastRun == null) {
            $nextNumber = $lastRun + 1;
        }else{
            $lastRunid = $lastRun->id;
            $nextNumber = $lastRunid + 1;
        }
        $newRunNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        $Additional_ID = $ID.$year.$month.$newRunNumber;
        $datarequest = [
            'Proposal_ID' => $Quotation['Quotation_ID'] ?? null,
            'Code' => $data['Code'] ?? [],
            'Amount' => $data['Amount'] ?? [],
            'IssueDate' => $Quotation['issue_date'] ?? null,
            'Expiration' => $Quotation['Expirationdate'] ?? null,
            'Selectdata' => $Quotation['type_Proposal'] ?? null,
            'Data_ID' => $Quotation['Company_ID'] ?? null,
            'Adult' => $Quotation['adult'] ?? null,
            'Children' => $Quotation['children'] ?? null,
            'Mevent' => $Quotation['eventformat'] ?? null,
            'Mvat' => $Quotation['vat_type'] ?? null,
            'comment' => $data['comment'] ?? null,
            'PaxToTalall' => $Quotation['TotalPax'] ?? null,
            'Checkin' => $Quotation['checkin'] ?? null,
            'Checkout' => $Quotation['checkout'] ?? null,
            'Day' => $Quotation['day'] ?? null,
            'Night' => $Quotation['night'] ?? null,
        ];
        {   //จัด product
            $Code = $datarequest['Code'];
            $Amount = $datarequest['Amount'];
            $productItems = [];
            if (count($Code) === count($Amount)) {
                foreach ($Code as $index => $productID) {
                    // Retrieve the product details based on Code
                    $items = Master_additional::where('code', $productID)->get();
                    foreach ($items as $item) {
                        // Use corresponding Amount for each productID based on index
                        $quantity = isset($Amount[$index]) ? intval($Amount[$index]) : 0;
                        $productItems[] = [
                            'product' => $item,
                            'Amount' => $quantity,
                        ];
                    }
                }
            }
            $productDataSave = [];
            if (!empty($productItems)) {
                foreach ($productItems as $product) {
                    $productDataSave[] = [
                        'Code' => $product['product']->code,
                        'Detail' => $product['product']->description,
                        'Amount' => $product['Amount'], // Use the correct Amount value for each product
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


                $totalguest = 0;
                $totalguest = $datarequest['Adult'] + $datarequest['Children'];
                $guest =  $datarequest['Adult'] + $datarequest['Children'];

                foreach ($productItems as $item) {
                    $totalPrice += $item['Amount'];
                    $subtotal = $totalPrice;
                    $beforeTax = $subtotal/1.07;
                    $AddTax = $subtotal-$beforeTax;
                    $Nettotal = $subtotal;
                    $totalaverage =$Nettotal/$totalguest;
                    $totalAmount = $totalPrice;
                }
            }
        }
        {
            try {
                $Quotation_ID = $datarequest['Proposal_ID'];
                $Amount = $datarequest['Amount'];
                $Code = $datarequest['Code'];
                $IssueDate = $datarequest['IssueDate'];
                $Expiration = $datarequest['Expiration'];
                $Selectdata = $datarequest['Selectdata'];
                $Data_ID = $datarequest['Data_ID'];
                $Adult = $datarequest['Adult'];
                $Children = $datarequest['Children'];
                $Mevent = $datarequest['Mevent'];
                $Mvat = $datarequest['Mvat'];
                $Checkin = $datarequest['Checkin'];
                $Checkout = $datarequest['Checkout'];
                $Day = $datarequest['Day'];
                $Night = $datarequest['Night'];
                $TotalPax = $datarequest['PaxToTalall'];

                $Head = 'รายการ';

                $productData = [];
                if (!empty($productItems)) {
                    foreach ($productItems as $product) {
                        $productData[] = [
                            'Code' => $product['product']->code,
                            'Detail' => $product['product']->description,
                            'Amount' => $product['Amount'], // Use the correct Amount value for each product
                        ];
                    }
                }

                $formattedProductData = [];

                foreach ($productData as $product) {
                    $formattedPrice = number_format($product['Amount']).' '.'บาท';
                    $formattedProductData[] = 'Code : ' . $product['Code'] . ' , '.'Description : ' . $product['Detail'] . ' , ' . $formattedPrice;
                }

                if ($Quotation_ID) {
                    $QuotationID = 'Proposal ID : '.$Quotation_ID;
                }
                if ($IssueDate) {
                    $Issue_Date = 'Issue Date : '.$IssueDate;
                }
                if ($Expiration) {
                    $Expiration_Date = 'Expiration Date : '.$Expiration;
                }

                $fullName = null;
                $Contact_Name = null;
                if ($Selectdata == 'Guest') {
                    $Data = Guest::where('Profile_ID',$Data_ID)->first();
                    $prename = $Data->preface;
                    $First_name = $Data->First_name;
                    $Last_name = $Data->Last_name;
                    $prefix = master_document::where('id',$prename)->where('Category','Mprename')->where('status',1)->first();
                    $name = $prefix->name_th;
                    $fullName = $name.$First_name.' '.$Last_name;
                }else{
                    $Company = companys::where('Profile_ID',$Data_ID)->first();
                    $Company_type = $Company->Company_type;
                    $Compannyname = $Company->Company_Name;
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
                    $representative = representative::where('Company_ID',$Data_ID)->first();
                    $prename = $representative->prefix;
                    $prefix = master_document::where('id', $prename)->where('Category', 'Mprename')->first();
                    $name = $prefix->name_th;
                    $Contact_Name = 'ตัวแทน : '.$name.$representative->First_name.' '.$representative->Last_name;
                }
                $nameevent = null;
                if ($Mevent) {
                    $Mevent = master_document::where('id',$Mevent)->where('status', '1')->where('Category','Mevent')->first();
                    $nameevent = 'ประเภท : '.$Mevent->name_th;
                }
                $namevat = null;
                if ($Mvat) {
                    $Mvat = master_document::where('id',$Mvat)->where('status', '1')->where('Category','Mvat')->first();
                    $namevat = 'ประเภท VAT : '.$Mvat->name_th;
                }
                $Time =null;
                if ($Checkin) {
                    $checkin = $Checkin;
                    $checkout = $Checkout;
                    $Time = 'วันเข้าที่พัก : '.$checkin.' '.'วันออกที่พัก : '.$checkout.' '.'จำนวน : '.$Day.' วัน '.' '.$Night.' คืน ';
                }
                $Pax = null;
                if ($TotalPax) {
                    $Pax = 'รวมความจุของห้องพัก : '.$TotalPax;
                }
                $Cash = null;
                $Complimentary = null;
                if ($additional_type == 'Cash Manual') {
                    $Cash = 'Cash : '.$request->Cash;
                    $Complimentary = 'Complimentary : '.$request->Complimentary;
                }
                $type ='Additional Type : '.$additional_type;
                $AdditionalID = 'Additional ID : '.$Additional_ID;
                $datacompany = '';

                $variables = [$AdditionalID,$QuotationID, $Issue_Date, $Expiration_Date, $fullName, $Contact_Name,$Time,$nameevent,$namevat,$Pax,$Head,$type,$Cash,$Complimentary];

                // แปลง array ของ $formattedProductData เป็น string เดียวที่มีรายการทั้งหมด
                $formattedProductDataString = implode(' + ', $formattedProductData);

                // รวม $formattedProductDataString เข้าไปใน $variables
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
                $save->Company_ID = $Additional_ID;
                $save->type = 'Create';
                $save->Category = 'Create :: Additional';
                $save->content =$datacompany;
                $save->save();
            } catch (\Throwable $e) {
                return redirect()->route('Additional.create', ['id' => $Quotation->id])->with('error',$e->getMessage());
            }
            try {
                $userid = Auth::user()->id;
                $datarequest = [
                    'Proposal_ID' => $data['Quotation_ID'] ?? null,
                    'Additional_ID'=> $data['Additional_ID'] ?? null,
                    'Code' => $data['Code'] ?? [],
                    'Amount' => $data['Amount'] ?? [],
                    'IssueDate' => $Quotation['issue_date'] ?? null,
                    'Expiration' => $Quotation['Expirationdate'] ?? null,
                    'Selectdata' => $Quotation['type_Proposal'] ?? null,
                    'Data_ID' => $Quotation['Company_ID'] ?? null,
                    'Adult' => $Quotation['adult'] ?? null,
                    'Children' => $Quotation['children'] ?? null,
                    'Mevent' => $Quotation['eventformat'] ?? null,
                    'Mvat' => $Quotation['vat_type'] ?? null,
                    'comment' => $data['comment'] ?? null,
                    'PaxToTalall' => $Quotation['TotalPax'] ?? null,
                    'Checkin' => $Quotation['checkin'] ?? null,
                    'Checkout' => $Quotation['checkout'] ?? null,
                    'Day' => $Quotation['day'] ?? null,
                    'Night' => $Quotation['night'] ?? null,
                ];
                $Code = $datarequest['Code'];
                $Amount = $datarequest['Amount'];
                $productItems = [];
                $Mvat = $datarequest['Mvat'];
                if (count($Code) === count($Amount)) {
                    foreach ($Code as $index => $productID) {
                        // Retrieve the product details based on Code
                        $items = Master_additional::where('code', $productID)->get();

                        foreach ($items as $item) {
                            // Use corresponding Amount for each productID based on index
                            $quantity = isset($Amount[$index]) ? intval($Amount[$index]) : 0;
                            $productItems[] = [
                                'product' => $item,
                                'Amount' => $quantity,
                            ];
                        }
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


                    $totalguest = 0;
                    $totalguest = $datarequest['Adult'] + $datarequest['Children'];
                    $guest =  $datarequest['Adult'] + $datarequest['Children'];

                    if ($Mvat == 50) {
                        foreach ($productItems as $item) {
                            $totalPrice += $item['Amount'];
                            $subtotal = $totalPrice;
                            $beforeTax = $subtotal/1.07;
                            $AddTax = $subtotal-$beforeTax;
                            $Nettotal = $subtotal;
                            $totalaverage =$Nettotal/$totalguest;
                            $totalAmount = $totalPrice;

                        }
                    }
                    elseif ($Mvat == 51) {
                        foreach ($productItems as $item) {
                            $totalPrice += $item['Amount'];
                            $subtotal = $totalPrice;
                            $Nettotal = $subtotal;
                            $totalaverage =$Nettotal/$totalguest;

                        }
                    }
                    elseif ($Mvat == 52) {
                        foreach ($productItems as $item) {
                            $totalPrice += $item['Amount'];
                            $subtotal = $totalPrice;
                            $AddTax = $subtotal*7/100;
                            $Nettotal = $subtotal+$AddTax;
                            $totalaverage =$Nettotal/$totalguest;
                        }
                    }else
                    {
                        foreach ($productItems as $item) {
                            $totalPrice += $item['Amount'];
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
                    $linkQR = $protocol . '://' . $request->getHost() . "/Document/Additional/Charge/document/PDF/$id?page_shop=" . $request->input('page_shop');
                    $qrCodeImage = QrCode::format('svg')->size(200)->generate($linkQR);
                    $qrCodeBase64 = base64_encode($qrCodeImage);
                }
                $Proposal_ID = $datarequest['Proposal_ID'];
                $IssueDate = $datarequest['IssueDate'];
                $Expiration = $datarequest['Expiration'];
                $Selectdata = $datarequest['Selectdata'];
                $Data_ID = $datarequest['Data_ID'];
                $Adult = $datarequest['Adult'];
                $Children = $datarequest['Children'];
                $Mevent = $datarequest['Mevent'];
                $Mvat = $datarequest['Mvat'];
                $Checkin = $datarequest['Checkin'];
                $Checkout = $datarequest['Checkout'];
                $Day = $datarequest['Day'];
                $Night = $datarequest['Night'];
                $comment = $datarequest['comment'];
                    $user = User::where('id',$userid)->first();
                $fullName = null;
                $Contact_Name = null;
                $Contact_phone =null;
                $Contact_Email = null;
                if ($Selectdata == 'Guest') {
                    $Data = Guest::where('Profile_ID',$Quotation->Company_ID)->first();
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
                    $phone = phone_guest::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
                }else{
                    $Company = companys::where('Profile_ID',$Quotation->Company_ID)->first();
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
                    $representative = representative::where('Company_ID',$Quotation->Company_ID)->first();
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
                    $company_fax = company_fax::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
                    if ($company_fax) {
                        $Fax_number =  $company_fax->Fax_number;
                    }else{
                        $Fax_number = '-';
                    }
                    $phone = company_phone::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
                    $Contact_phone = representative_phone::where('Company_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
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
                    'Additional_ID'=>$Additional_ID,
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
                    'subtotal'=>$subtotal,
                    'beforeTax'=>$beforeTax,
                    'Nettotal'=>$Nettotal,
                    'totalguest'=>$totalguest,
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
                $pdf = FacadePdf::loadView('additional_charge.additional_charge_pdf.'.$view,$data);
                $path = 'PDF/additional/';
                $pdf->save($path . $Additional_ID . '.pdf');
            } catch (\Throwable $e) {
                log_company::where('Category','Create :: Additional')->delete();
                return redirect()->route('Additional.create', ['id' => $Quotation->id])->with('error',$e->getMessage());
            }
            try {
                $currentDateTime = Carbon::now();
                $currentDate = $currentDateTime->toDateString(); // Format: YYYY-MM-DD
                $currentTime = $currentDateTime->toTimeString(); // Format: HH:MM:SS
                // Optionally, you can format the date and time as per your requirement
                $formattedDate = $currentDateTime->format('Y-m-d'); // Custom format for date
                $formattedTime = $currentDateTime->format('H:i:s');
                {
                    $Proposal_ID = $datarequest['Proposal_ID'];
                    $IssueDate = $datarequest['IssueDate'];
                    $Expiration = $datarequest['Expiration'];
                    $Selectdata = $Quotation->type_Proposal;
                    $Data_ID = $Quotation->Company_ID;
                    $Adult = $datarequest['Adult'];
                    $Children = $datarequest['Children'];
                    $Mevent = $datarequest['Mevent'];
                    $Mvat = $datarequest['Mvat'];
                    $Checkin = $datarequest['Checkin'];
                    $Checkout = $datarequest['Checkout'];
                    $Day = $datarequest['Day'];
                    $Night = $datarequest['Night'];
                    $comment = $datarequest['comment'];
                    if ($Selectdata == 'Guest') {
                        $Data = Guest::where('Profile_ID',$Data_ID)->first();
                        $prename = $Data->preface;
                        $First_name = $Data->First_name;
                        $Last_name = $Data->Last_name;
                        $prefix = master_document::where('id',$prename)->where('Category','Mprename')->where('status',1)->first();
                        $name = $prefix->name_th;
                        $fullName = $name.' '.$First_name.' '.$Last_name;
                        //-------------ที่อยู่

                    }else{
                        $Company = companys::where('Profile_ID',$Data_ID)->first();
                        $Company_type = $Company->Company_type;
                        $Compannyname = $Company->Company_Name;
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

                    }
                }
                $savePDF = new log();
                $savePDF->Quotation_ID = $Additional_ID;
                $savePDF->QuotationType = 'Additional';
                $savePDF->Company_Name = $fullName;
                $savePDF->Approve_date = $formattedDate;
                $savePDF->Approve_time = $formattedTime;
                $savePDF->save();
            } catch (\Throwable $e) {

                log_company::where('Category','Create :: Additional')->delete();
                $path = 'PDF/additional/';
                $file = $path . $Additional_ID . '.pdf';
                if (is_file($file)) {
                    unlink($file); // ลบไฟล์
                }
                log::where('Quotation_ID',$Additional_ID)->delete();
                return redirect()->route('Additional.create', ['id' => $Quotation->id])->with('error',$e->getMessage());
            }

            try {
                $save = new proposal_overbill();
                $save->Additional_ID = $Additional_ID;
                $save->Quotation_ID = $Quotation_ID;
                $save->Company_ID = $Quotation->Company_ID;
                $save->company_contact = $Quotation->company_contact;
                $save->checkin = $Quotation->checkin;
                $save->checkout = $Quotation->checkout;
                $save->TotalPax = $Quotation->TotalPax;
                $save->day = $Quotation->day;
                $save->night = $Quotation->night;
                $save->adult = $Quotation->adult;
                $save->children = $Quotation->children;
                $save->eventformat = $Quotation->eventformat;
                $save->AddTax = $AddTax;
                $save->Nettotal = $Nettotal;
                $save->total = $Nettotal;
                $save->vat_type = $Quotation->vat_type;
                $save->type_Proposal = $Quotation->type_Proposal;
                $save->issue_date = $Quotation->issue_date;
                $save->Expirationdate = $Quotation->Expirationdate;
                $save->status_document = 2;
                $save->Operated_by = $userid;
                $save->comment = $request->comment;
                $save->Date_type = $Quotation->Date_type;
                $save->additional_type = $request->additional_type;
                $save->Cash = $request->Cash;
                $save->Complimentary = $request->Complimentary;
                $save->save();
                if ($productDataSave !== null) {
                    foreach ($productDataSave as $product) {
                        $saveProduct = new document_proposal_overbill();
                        $saveProduct->Additional_ID = $Additional_ID;
                        $saveProduct->Quotation_ID = $Quotation_ID;
                        $saveProduct->Code = $product['Code'];
                        $saveProduct->Detail = $product['Detail'];
                        $saveProduct->Amount = $product['Amount'];
                        $saveProduct->save();
                    }
                }
            } catch (\Throwable $e) {
                log_company::where('Category','Create :: Additional')->delete();
                $path = 'PDF/additional/';
                $file = $path . $Additional_ID . '.pdf';
                if (is_file($file)) {
                    unlink($file); // ลบไฟล์
                }
                log::where('Quotation_ID',$Additional_ID)->delete();
                proposal_overbill::where('Additional_ID',$Additional_ID)->delete();
                return redirect()->route('Additional.create', ['id' => $Quotation->id])->with('error',$e->getMessage());
            }
            try {
                $log = new log_company();
                $log->Created_by = $userid;
                $log->Company_ID = $Additional_ID;
                $log->type = 'Send documents';
                $log->Category = 'Send documents :: Additional';
                $log->content = 'Send Document Additional : ' . $Additional_ID;
                $log->save();

            } catch (\Throwable $e) {
                return redirect()->route('Additional.create', ['id' => $Quotation->id])->with('error',$e->getMessage());
            }
            try {
                $Quotation = Quotation::where('Quotation_ID', $Quotation_ID)->first();
                if ($Quotation->status_document == 9) {
                    $save = Quotation::find($Quotation->id);
                    $save->status_document = 6;
                    $save->status_guest = 1;
                    $save->save();
                }
            } catch (\Throwable $th) {
                return redirect()->route('Additional.create', ['id' => $Quotation->id])->with('error',$e->getMessage());
            }

            return redirect()->route('Additional.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
        }

    }
    public function Quotation(Request $request){
        $data = $request->all();
        if (isset($data['value']) && $data['value'] == 'all') {
            $Quotation = Quotation::with('guest', 'company', 'documentoverbill')
            ->whereIn('status_document', [6, 9])
            ->whereDoesntHave('documentoverbill', function($query) {
                $query->where('status_guest', 0);
            })
            ->get();
        } elseif (isset($data['value']) && $data['value'] == 'company') {
            $Quotation = Quotation::with('company', 'documentoverbill')
            ->where('type_Proposal','Company')
            ->whereIn('status_document', [6,9])
            ->whereDoesntHave('documentoverbill', function($query) {
                $query->where('status_guest', 0);
            })
            ->get();
        } elseif (isset($data['value']) && $data['value'] == 'guest') {
            $Quotation = Quotation::with('guest', 'documentoverbill')
            ->where('type_Proposal','Guest')
            ->whereIn('status_document', [6,9])
            ->whereDoesntHave('documentoverbill', function($query) {
                $query->where('status_guest', 0);
            })
            ->get();
        }
        return response()->json([
            'products' => $Quotation,
        ]);
    }
    public function deposit_pd($id)
    {
        $Quotation = Quotation::where('id',$id)->first();
        $Company_ID = $Quotation->Company_ID;
        $Quotation_ID = $Quotation->Quotation_ID;
        $vat = $Quotation->vat_type;


        $checkin = $Quotation->checkin ?? 'No Check In Date';
        $checkout = $Quotation->checkout ?? ' ' ;
        $day = $Quotation->day;
        $night = $Quotation->night;
        $adult = $Quotation->adult;
        $children = $Quotation->children;

        $Mvat = master_document::where('id',$vat)->select('name_th', 'id')->first();
        $vattype = $Mvat->name_th;
        $parts = explode('-', $Company_ID);
        $firstPart = $parts[0];
        if ($firstPart == 'C') {
            $Selectdata =  'Company';
            $company =  companys::where('Profile_ID',$Company_ID)->first();
            if ($company) {
                $name_ID = $company->Profile_ID;
                $datasub = company_tax::where('Company_ID',$name_ID)->get();
                $Company_typeID=$company->Company_type;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="บริษัทจำกัด") {
                    $name = "บริษัท ". $company->Company_Name . " จำกัด";
                }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                    $name = "บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                    $name = "ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                }else{
                    $name = $comtype->name_th . $company->Company_Name;
                }
                $Address=$company->Address;
                $CityID=$company->City;
                $amphuresID = $company->Amphures;
                $TambonID = $company->Tambon;
                $Identification = $company->Taxpayer_Identification;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = company_phone::where('Profile_ID',$company->Profile_ID)->where('Sequence','main')->first();
                $email = $company->Company_Email;
                $Company_typeID=$company->Company_type;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="บริษัทจำกัด") {
                    $fullname = "บริษัท ". $company->Company_Name . " จำกัด";
                }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                    $fullname = "บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                    $fullname = "ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                }else{
                    $fullname = $comtype->name_th . $company->Company_Name;
                }
                $company_fax = company_fax::where('Profile_ID',$Company_ID)->where('Sequence','main')->first();
                if ($company_fax) {
                    $Fax_number =  $company_fax->Fax_number;
                }else{
                    $Fax_number = '-';
                }
                $representative = representative::where('Company_ID',$Company_ID)->first();
                $prename = $representative->prefix;
                $Contact_Email = $representative->Email;
                $prefix = master_document::where('id', $prename)->where('Category', 'Mprename')->first();
                $name = $prefix->name_th ?? 'คุณ';
                $Contact_Name = $name.$representative->First_name.' '.$representative->Last_name;
                $Contact_phone = representative_phone::where('Company_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
            }
        }else{

            $guestdata =  Guest::where('Profile_ID',$Company_ID)->first();

            if ($guestdata) {
                $name =  'คุณ '.$guestdata->First_name.' '.$guestdata->Last_name;
                $name_ID = $guestdata->Profile_ID;
                $datasub = guest_tax::where('Company_ID',$name_ID)->get();
                $Selectdata =  'Guest';
                $Company_typeID=$guestdata->preface;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="นาย") {
                    $fullname = "นาย ". $guestdata->First_name . ' ' . $guestdata->Last_name;
                }elseif ($comtype->name_th =="นาง") {
                    $fullname = "นาง ". $guestdata->First_name . ' ' . $guestdata->Last_name;
                }elseif ($comtype->name_th =="นางสาว") {
                    $fullname = "นางสาว ". $guestdata->First_name . ' ' . $guestdata->Last_name ;
                }else{
                    $fullname = "คุณ ". $guestdata->First_name . ' ' . $guestdata->Last_name ;
                }
                $Address=$guestdata->Address;
                $CityID=$guestdata->City;
                $amphuresID = $guestdata->Amphures;
                $TambonID = $guestdata->Tambon;
                $Identification = $guestdata->Identification_Number;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $phone = phone_guest::where('Profile_ID',$guestdata->Profile_ID)->where('Sequence','main')->first();
                $email = $guestdata->Email;
                $Fax_number = '-';
                $Contact_Name = ' ';
                $Contact_phone = ' ';
                $Contact_Email = ' ';
            }
        }

        return response()->json([
            'phone'=>$phone,
            'Fax_number'=>$Fax_number,
            'Contact_Name'=>$Contact_Name,
            'Contact_phone'=>$Contact_phone,
            'Selectdata'=>$Selectdata,
            'fullname'=>$fullname,
            'Address' => $Address,
            'Identification' => $Identification,
            'province'=>$provinceNames,
            'amphures'=>$amphuresID,
            'Tambon'=>$TambonID,
            'email'=>$email,
            'nameID'=>$Company_ID,
            'proposal_id'=>$Quotation_ID,
            'name'=>$name,
            'name_ID'=>$name_ID,
            'vat_type'=>$vattype,
            'checkin'=>$checkin,
            'checkout'=>$checkout,
            'day'=>$day,
            'night'=>$night,
            'adult'=>$adult,
            'children'=>$children,
            'Contact_Email' => $Contact_Email,
            'vat'=>$vat,
            'Quotation'=>$Quotation,
        ]);
    }
    public function save(Request $request ,$id){
        $Quotation = Quotation::where('id', $id)->first();
        $preview = $request->preview;
        $Quotation_ID=$request->Quotation_ID;
        $Quotationid = $Quotation->id;
        $Mvat = $Quotation->vat_type;
        $userid = Auth::user()->id;
        $data = $request->all();
        $Additional_ID=$request->Additional_ID;
        $additional_type=$request->additional_type;
        $currentDate = Carbon::now();
        $ID = 'AD-';
        $formattedDate = Carbon::parse($currentDate);       // วันที่
        $month = $formattedDate->format('m'); // เดือน
        $year = $formattedDate->format('y');
        $lastRun = proposal_overbill::latest()->first();
        $nextNumber = 1;

        if ($lastRun == null) {
            $nextNumber = $lastRun + 1;
        }else{
            $lastRunid = $lastRun->id;
            $nextNumber = $lastRunid + 1;
        }
        $newRunNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        $Additional_ID = $ID.$year.$month.$newRunNumber;
        $datarequest = [
            'Proposal_ID' => $Quotation['Quotation_ID'] ?? null,
            'Code' => $data['Code'] ?? [],
            'Amount' => $data['Amount'] ?? [],
            'IssueDate' => $Quotation['issue_date'] ?? null,
            'Expiration' => $Quotation['Expirationdate'] ?? null,
            'Selectdata' => $Quotation['type_Proposal'] ?? null,
            'Data_ID' => $Quotation['Company_ID'] ?? null,
            'Adult' => $Quotation['adult'] ?? null,
            'Children' => $Quotation['children'] ?? null,
            'Mevent' => $Quotation['eventformat'] ?? null,
            'Mvat' => $Quotation['vat_type'] ?? null,
            'comment' => $data['comment'] ?? null,
            'PaxToTalall' => $Quotation['TotalPax'] ?? null,
            'Checkin' => $Quotation['checkin'] ?? null,
            'Checkout' => $Quotation['checkout'] ?? null,
            'Day' => $Quotation['day'] ?? null,
            'Night' => $Quotation['night'] ?? null,
        ];
        {   //จัด product
            $Code = $datarequest['Code'];
            $Amount = $datarequest['Amount'];
            $productItems = [];
            if (count($Code) === count($Amount)) {
                foreach ($Code as $index => $productID) {
                    // Retrieve the product details based on Code
                    $items = Master_additional::where('code', $productID)->get();
                    foreach ($items as $item) {
                        // Use corresponding Amount for each productID based on index
                        $quantity = isset($Amount[$index]) ? intval($Amount[$index]) : 0;
                        $productItems[] = [
                            'product' => $item,
                            'Amount' => $quantity,
                        ];
                    }
                }
            }
            $productDataSave = [];
            if (!empty($productItems)) {
                foreach ($productItems as $product) {
                    $productDataSave[] = [
                        'Code' => $product['product']->code,
                        'Detail' => $product['product']->description,
                        'Amount' => $product['Amount'], // Use the correct Amount value for each product
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


                $totalguest = 0;
                $totalguest = $datarequest['Adult'] + $datarequest['Children'];
                $guest =  $datarequest['Adult'] + $datarequest['Children'];

                foreach ($productItems as $item) {
                    $totalPrice += $item['Amount'];
                    $subtotal = $totalPrice;
                    $beforeTax = $subtotal/1.07;
                    $AddTax = $subtotal-$beforeTax;
                    $Nettotal = $subtotal;
                    $totalaverage =$Nettotal/$totalguest;
                    $totalAmount = $totalPrice;
                }
            }
        }
        {
            try {
                $Quotation_ID = $datarequest['Proposal_ID'];
                $Amount = $datarequest['Amount'];
                $Code = $datarequest['Code'];
                $IssueDate = $datarequest['IssueDate'];
                $Expiration = $datarequest['Expiration'];
                $Selectdata = $datarequest['Selectdata'];
                $Data_ID = $datarequest['Data_ID'];
                $Adult = $datarequest['Adult'];
                $Children = $datarequest['Children'];
                $Mevent = $datarequest['Mevent'];
                $Mvat = $datarequest['Mvat'];
                $Checkin = $datarequest['Checkin'];
                $Checkout = $datarequest['Checkout'];
                $Day = $datarequest['Day'];
                $Night = $datarequest['Night'];
                $TotalPax = $datarequest['PaxToTalall'];

                $Head = 'รายการ';

                $productData = [];
                if (!empty($productItems)) {
                    foreach ($productItems as $product) {
                        $productData[] = [
                            'Code' => $product['product']->code,
                            'Detail' => $product['product']->description,
                            'Amount' => $product['Amount'], // Use the correct Amount value for each product
                        ];
                    }
                }

                $formattedProductData = [];

                foreach ($productData as $product) {
                    $formattedPrice = number_format($product['Amount']).' '.'บาท';
                    $formattedProductData[] = 'Code : ' . $product['Code'] . ' , '.'Description : ' . $product['Detail'] . ' , ' . $formattedPrice;
                }

                if ($Quotation_ID) {
                    $QuotationID = 'Proposal ID : '.$Quotation_ID;
                }
                if ($IssueDate) {
                    $Issue_Date = 'Issue Date : '.$IssueDate;
                }
                if ($Expiration) {
                    $Expiration_Date = 'Expiration Date : '.$Expiration;
                }

                $fullName = null;
                $Contact_Name = null;
                if ($Selectdata == 'Guest') {
                    $Data = Guest::where('Profile_ID',$Data_ID)->first();
                    $prename = $Data->preface;
                    $First_name = $Data->First_name;
                    $Last_name = $Data->Last_name;
                    $prefix = master_document::where('id',$prename)->where('Category','Mprename')->where('status',1)->first();
                    $name = $prefix->name_th;
                    $fullName = $name.$First_name.' '.$Last_name;
                }else{
                    $Company = companys::where('Profile_ID',$Data_ID)->first();
                    $Company_type = $Company->Company_type;
                    $Compannyname = $Company->Company_Name;
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
                    $representative = representative::where('Company_ID',$Data_ID)->first();
                    $prename = $representative->prefix;
                    $prefix = master_document::where('id', $prename)->where('Category', 'Mprename')->first();
                    $name = $prefix->name_th;
                    $Contact_Name = 'ตัวแทน : '.$name.$representative->First_name.' '.$representative->Last_name;
                }
                $nameevent = null;
                if ($Mevent) {
                    $Mevent = master_document::where('id',$Mevent)->where('status', '1')->where('Category','Mevent')->first();
                    $nameevent = 'ประเภท : '.$Mevent->name_th;
                }
                $namevat = null;
                if ($Mvat) {
                    $Mvat = master_document::where('id',$Mvat)->where('status', '1')->where('Category','Mvat')->first();
                    $namevat = 'ประเภท VAT : '.$Mvat->name_th;
                }
                $Time =null;
                if ($Checkin) {
                    $checkin = $Checkin;
                    $checkout = $Checkout;
                    $Time = 'วันเข้าที่พัก : '.$checkin.' '.'วันออกที่พัก : '.$checkout.' '.'จำนวน : '.$Day.' วัน '.' '.$Night.' คืน ';
                }
                $Pax = null;
                if ($TotalPax) {
                    $Pax = 'รวมความจุของห้องพัก : '.$TotalPax;
                }
                $Cash = null;
                $Complimentary = null;
                if ($additional_type == 'Cash Manual') {
                    $Cash = 'Cash : '.$request->Cash;
                    $Complimentary = 'Complimentary : '.$request->Complimentary;
                }
                $type ='Additional Type : '.$additional_type;
                $AdditionalID = 'Additional ID : '.$Additional_ID;
                $datacompany = '';

                $variables = [$AdditionalID,$QuotationID, $Issue_Date, $Expiration_Date, $fullName, $Contact_Name,$Time,$nameevent,$namevat,$Pax,$Head,$type,$Cash,$Complimentary];

                // แปลง array ของ $formattedProductData เป็น string เดียวที่มีรายการทั้งหมด
                $formattedProductDataString = implode(' + ', $formattedProductData);

                // รวม $formattedProductDataString เข้าไปใน $variables
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
                $save->Company_ID = $Additional_ID;
                $save->type = 'Create';
                $save->Category = 'Create :: Additional';
                $save->content =$datacompany;
                $save->save();
            } catch (\Throwable $e) {
                return redirect()->route('Additional.create', ['id' => $Quotation->id])->with('error',$e->getMessage());
            }
            try {
                $userid = Auth::user()->id;
                $datarequest = [
                    'Proposal_ID' => $data['Quotation_ID'] ?? null,
                    'Additional_ID'=> $data['Additional_ID'] ?? null,
                    'Code' => $data['Code'] ?? [],
                    'Amount' => $data['Amount'] ?? [],
                    'IssueDate' => $Quotation['issue_date'] ?? null,
                    'Expiration' => $Quotation['Expirationdate'] ?? null,
                    'Selectdata' => $Quotation['type_Proposal'] ?? null,
                    'Data_ID' => $Quotation['Company_ID'] ?? null,
                    'Adult' => $Quotation['adult'] ?? null,
                    'Children' => $Quotation['children'] ?? null,
                    'Mevent' => $Quotation['eventformat'] ?? null,
                    'Mvat' => $Quotation['vat_type'] ?? null,
                    'comment' => $data['comment'] ?? null,
                    'PaxToTalall' => $Quotation['TotalPax'] ?? null,
                    'Checkin' => $Quotation['checkin'] ?? null,
                    'Checkout' => $Quotation['checkout'] ?? null,
                    'Day' => $Quotation['day'] ?? null,
                    'Night' => $Quotation['night'] ?? null,
                ];
                $Code = $datarequest['Code'];
                $Amount = $datarequest['Amount'];
                $productItems = [];
                $Mvat = $datarequest['Mvat'];
                if (count($Code) === count($Amount)) {
                    foreach ($Code as $index => $productID) {
                        // Retrieve the product details based on Code
                        $items = Master_additional::where('code', $productID)->get();

                        foreach ($items as $item) {
                            // Use corresponding Amount for each productID based on index
                            $quantity = isset($Amount[$index]) ? intval($Amount[$index]) : 0;
                            $productItems[] = [
                                'product' => $item,
                                'Amount' => $quantity,
                            ];
                        }
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


                    $totalguest = 0;
                    $totalguest = $datarequest['Adult'] + $datarequest['Children'];
                    $guest =  $datarequest['Adult'] + $datarequest['Children'];

                    if ($Mvat == 50) {
                        foreach ($productItems as $item) {
                            $totalPrice += $item['Amount'];
                            $subtotal = $totalPrice;
                            $beforeTax = $subtotal/1.07;
                            $AddTax = $subtotal-$beforeTax;
                            $Nettotal = $subtotal;
                            $totalaverage =$Nettotal/$totalguest;
                            $totalAmount = $totalPrice;

                        }
                    }
                    elseif ($Mvat == 51) {
                        foreach ($productItems as $item) {
                            $totalPrice += $item['Amount'];
                            $subtotal = $totalPrice;
                            $Nettotal = $subtotal;
                            $totalaverage =$Nettotal/$totalguest;

                        }
                    }
                    elseif ($Mvat == 52) {
                        foreach ($productItems as $item) {
                            $totalPrice += $item['Amount'];
                            $subtotal = $totalPrice;
                            $AddTax = $subtotal*7/100;
                            $Nettotal = $subtotal+$AddTax;
                            $totalaverage =$Nettotal/$totalguest;
                        }
                    }else
                    {
                        foreach ($productItems as $item) {
                            $totalPrice += $item['Amount'];
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
                    $linkQR = $protocol . '://' . $request->getHost() . "/Document/Additional/Charge/document/PDF/$id?page_shop=" . $request->input('page_shop');
                    $qrCodeImage = QrCode::format('svg')->size(200)->generate($linkQR);
                    $qrCodeBase64 = base64_encode($qrCodeImage);
                }
                $Proposal_ID = $datarequest['Proposal_ID'];
                $IssueDate = $datarequest['IssueDate'];
                $Expiration = $datarequest['Expiration'];
                $Selectdata = $datarequest['Selectdata'];
                $Data_ID = $datarequest['Data_ID'];
                $Adult = $datarequest['Adult'];
                $Children = $datarequest['Children'];
                $Mevent = $datarequest['Mevent'];
                $Mvat = $datarequest['Mvat'];
                $Checkin = $datarequest['Checkin'];
                $Checkout = $datarequest['Checkout'];
                $Day = $datarequest['Day'];
                $Night = $datarequest['Night'];
                $comment = $datarequest['comment'];
                    $user = User::where('id',$userid)->first();
                $fullName = null;
                $Contact_Name = null;
                $Contact_phone =null;
                $Contact_Email = null;
                if ($Selectdata == 'Guest') {
                    $Data = Guest::where('Profile_ID',$Quotation->Company_ID)->first();
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
                    $phone = phone_guest::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
                }else{
                    $Company = companys::where('Profile_ID',$Quotation->Company_ID)->first();
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
                    $representative = representative::where('Company_ID',$Quotation->Company_ID)->first();
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
                    $company_fax = company_fax::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
                    if ($company_fax) {
                        $Fax_number =  $company_fax->Fax_number;
                    }else{
                        $Fax_number = '-';
                    }
                    $phone = company_phone::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
                    $Contact_phone = representative_phone::where('Company_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
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
                    'Additional_ID'=>$Additional_ID,
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
                    'subtotal'=>$subtotal,
                    'beforeTax'=>$beforeTax,
                    'Nettotal'=>$Nettotal,
                    'totalguest'=>$totalguest,
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
                $pdf = FacadePdf::loadView('additional_charge.additional_charge_pdf.'.$view,$data);
                $path = 'PDF/additional/';
                $pdf->save($path . $Additional_ID . '.pdf');
            } catch (\Throwable $e) {
                log_company::where('Category','Create :: Additional')->delete();
                return redirect()->route('Additional.create', ['id' => $Quotation->id])->with('error',$e->getMessage());
            }
            try {
                $currentDateTime = Carbon::now();
                $currentDate = $currentDateTime->toDateString(); // Format: YYYY-MM-DD
                $currentTime = $currentDateTime->toTimeString(); // Format: HH:MM:SS
                // Optionally, you can format the date and time as per your requirement
                $formattedDate = $currentDateTime->format('Y-m-d'); // Custom format for date
                $formattedTime = $currentDateTime->format('H:i:s');
                {
                    $Proposal_ID = $datarequest['Proposal_ID'];
                    $IssueDate = $datarequest['IssueDate'];
                    $Expiration = $datarequest['Expiration'];
                    $Selectdata = $Quotation->type_Proposal;
                    $Data_ID = $Quotation->Company_ID;
                    $Adult = $datarequest['Adult'];
                    $Children = $datarequest['Children'];
                    $Mevent = $datarequest['Mevent'];
                    $Mvat = $datarequest['Mvat'];
                    $Checkin = $datarequest['Checkin'];
                    $Checkout = $datarequest['Checkout'];
                    $Day = $datarequest['Day'];
                    $Night = $datarequest['Night'];
                    $comment = $datarequest['comment'];
                    if ($Selectdata == 'Guest') {
                        $Data = Guest::where('Profile_ID',$Data_ID)->first();
                        $prename = $Data->preface;
                        $First_name = $Data->First_name;
                        $Last_name = $Data->Last_name;
                        $prefix = master_document::where('id',$prename)->where('Category','Mprename')->where('status',1)->first();
                        $name = $prefix->name_th;
                        $fullName = $name.' '.$First_name.' '.$Last_name;
                        //-------------ที่อยู่

                    }else{
                        $Company = companys::where('Profile_ID',$Data_ID)->first();
                        $Company_type = $Company->Company_type;
                        $Compannyname = $Company->Company_Name;
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

                    }
                }
                $savePDF = new log();
                $savePDF->Quotation_ID = $Additional_ID;
                $savePDF->QuotationType = 'Additional';
                $savePDF->Company_Name = $fullName;
                $savePDF->Approve_date = $formattedDate;
                $savePDF->Approve_time = $formattedTime;
                $savePDF->save();
            } catch (\Throwable $e) {

                log_company::where('Category','Create :: Additional')->delete();
                $path = 'PDF/additional/';
                $file = $path . $Additional_ID . '.pdf';
                if (is_file($file)) {
                    unlink($file); // ลบไฟล์
                }
                log::where('Quotation_ID',$Additional_ID)->delete();
                return redirect()->route('Additional.create', ['id' => $Quotation->id])->with('error',$e->getMessage());
            }

            try {
                $save = new proposal_overbill();
                $save->Additional_ID = $Additional_ID;
                $save->Quotation_ID = $Quotation_ID;
                $save->Company_ID = $Quotation->Company_ID;
                $save->company_contact = $Quotation->company_contact;
                $save->checkin = $Quotation->checkin;
                $save->checkout = $Quotation->checkout;
                $save->TotalPax = $Quotation->TotalPax;
                $save->day = $Quotation->day;
                $save->night = $Quotation->night;
                $save->adult = $Quotation->adult;
                $save->children = $Quotation->children;
                $save->eventformat = $Quotation->eventformat;
                $save->AddTax = $AddTax;
                $save->Nettotal = $Nettotal;
                $save->total = $Nettotal;
                $save->vat_type = $Quotation->vat_type;
                $save->type_Proposal = $Quotation->type_Proposal;
                $save->issue_date = $Quotation->issue_date;
                $save->Expirationdate = $Quotation->Expirationdate;
                $save->status_document = 2;
                $save->Operated_by = $userid;
                $save->comment = $request->comment;
                $save->Date_type = $Quotation->Date_type;
                $save->additional_type = $request->additional_type;
                $save->Cash = $request->Cash;
                $save->Complimentary = $request->Complimentary;
                $save->save();
                if ($productDataSave !== null) {
                    foreach ($productDataSave as $product) {
                        $saveProduct = new document_proposal_overbill();
                        $saveProduct->Additional_ID = $Additional_ID;
                        $saveProduct->Quotation_ID = $Quotation_ID;
                        $saveProduct->Code = $product['Code'];
                        $saveProduct->Detail = $product['Detail'];
                        $saveProduct->Amount = $product['Amount'];
                        $saveProduct->save();
                    }
                }
            } catch (\Throwable $e) {
                log_company::where('Category','Create :: Additional')->delete();
                $path = 'PDF/additional/';
                $file = $path . $Additional_ID . '.pdf';
                if (is_file($file)) {
                    unlink($file); // ลบไฟล์
                }
                log::where('Quotation_ID',$Additional_ID)->delete();
                proposal_overbill::where('Additional_ID',$Additional_ID)->delete();
                return redirect()->route('Additional.create', ['id' => $Quotation->id])->with('error',$e->getMessage());
            }
            try {
                $log = new log_company();
                $log->Created_by = $userid;
                $log->Company_ID = $Additional_ID;
                $log->type = 'Send documents';
                $log->Category = 'Send documents :: Additional';
                $log->content = 'Send Document Additional : ' . $Additional_ID;
                $log->save();

            } catch (\Throwable $e) {
                return redirect()->route('Additional.create', ['id' => $Quotation->id])->with('error',$e->getMessage());
            }
            try {
                $Quotation = Quotation::where('Quotation_ID', $Quotation_ID)->first();
                if ($Quotation->status_document == 9) {
                    $save = Quotation::find($Quotation->id);
                    $save->status_document = 6;
                    $save->status_guest = 1;
                    $save->save();
                }
            } catch (\Throwable $th) {
                return redirect()->route('Additional.create', ['id' => $Quotation->id])->with('error',$e->getMessage());
            }

            return redirect()->route('Additional.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
        }

    }
    public function edit($id){
        $settingCompany = Master_company::orderBy('id', 'desc')->first();
        $Quotation = proposal_overbill::where('id', $id)->first();
        $Additional_ID = $Quotation->Additional_ID;
        $Quotation_ID = $Quotation->Quotation_ID;
        $Quotation_IDoverbill = $Quotation->Additional_ID;
        $Company = companys::select('Company_Name','id','Profile_ID')->get();
        $Guest = Guest::select('First_name','Last_name','id','Profile_ID')->get();
        $Mevent = master_document::select('name_th','id')->where('status', '1')->where('Category','Mevent')->get();
        $Mvat = master_document::select('name_th','id')->where('status', '1')->where('Category','Mvat')->get();
        $Freelancer_member = Freelancer_Member::select('First_name','id','Profile_ID','Last_name')->where('status', '1')->get();
        $selectproduct = document_proposal_overbill::where('Additional_ID', $Additional_ID)->get();
        $unit = master_unit::where('status',1)->get();
        $quantity = master_quantity::where('status',1)->get();
        $Selectdata =  $Quotation->type_Proposal;
        if ($Selectdata == 'Guest') {
            $Data = Guest::where('Profile_ID',$Quotation->Company_ID)->first();
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
            $Contact_Name = ' ';
            $Contact_phone = ' ';
            $phone = phone_guest::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
        }else{
            $Company = companys::where('Profile_ID',$Quotation->Company_ID)->first();
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
            $representative = representative::where('Company_ID',$Quotation->Company_ID)->first();
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
            $company_fax = company_fax::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
            if ($company_fax) {
                $Fax_number =  $company_fax->Fax_number;
            }else{
                $Fax_number = '-';
            }
            $phone = company_phone::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
            $Contact_phone = representative_phone::where('Company_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
        }
        return view('additional_charge.edit',compact('Address','fullName','settingCompany','Quotation','Quotation_ID','Company','Guest','Mevent','Mvat','Freelancer_member','selectproduct','unit','quantity','Quotation_IDoverbill',
                    'provinceNames','amphuresID','TambonID','Fax_number','phone','Email','Taxpayer_Identification','Contact_Name','Contact_phone','Selectdata' ));
    }
    public function update(Request $request ,$id){
        $data = $request->all();
        $Quotation = proposal_overbill::where('id', $id)->first();
        $preview = $request->preview;
        $Quotation_ID=$request->Quotation_ID;
        $userid = Auth::user()->id;
        $data = $request->all();
        $Additional_ID=$request->Additional_ID;
        $Mvat = $Quotation->vat_type;
        $additional_type =  $Quotation->additional_type;
        $datarequest = [
            'Proposal_ID' => $data['Quotation_ID'] ?? null,
            'Code' => $data['Code'] ?? null ,
            'CheckProduct' => $data['CheckProduct'] ?? null ,
            'Amount' => $data['Amount'] ?? [],
            'IssueDate' => $Quotation['issue_date'] ?? null,
            'Expiration' => $Quotation['Expirationdate'] ?? null,
            'Selectdata' => $Quotation['type_Proposal'] ?? null,
            'Data_ID' => $Quotation['Company_ID'] ?? null,
            'Adult' => $Quotation['adult'] ?? null,
            'Children' => $Quotation['children'] ?? null,
            'Mevent' => $Quotation['eventformat'] ?? null,
            'Mvat' => $Quotation['vat_type'] ?? null,
            'comment' => $data['comment'] ?? null,
            'PaxToTalall' => $Quotation['TotalPax'] ?? null,
            'Checkin' => $Quotation['checkin'] ?? null,
            'Checkout' => $Quotation['checkout'] ?? null,
            'Day' => $Quotation['day'] ?? null,
            'Night' => $Quotation['night'] ?? null,
        ];
        {   //จัด product
            $Products = $datarequest['Code'];
            $Productslast = $datarequest['CheckProduct'];
            if (is_array($Products) && is_array($Productslast)) {
                $commonValues = array_intersect($Products, $Productslast);
                if (!empty($commonValues)) {
                    $diffFromProducts = array_diff($Products, $Productslast);
                    $diffFromProductslast = array_diff($Productslast, $Products);
                    $Code = array_merge($commonValues,$diffFromProducts,$diffFromProductslast);
                } else {
                    $Code = array_merge($Productslast,$Products);
                }

            }else{
                $Code = $Productslast;
            }
            $Amount = $datarequest['Amount'];
            $productItems = [];
            $productItemsData = [];

            if (count($Code) === count($Amount)) {
                foreach ($Code as $index => $productID) {
                    // Retrieve the product details based on Code
                    $items = Master_additional::where('code', $productID)->get();
                    foreach ($items as $item) {
                        // Use corresponding Amount for each productID based on index
                        $quantity = isset($Amount[$index]) ? intval($Amount[$index]) : 0;
                        $productItems[] = [
                            'product' => $item,
                            'Amount' => $quantity,
                        ];
                        $productItemsData[] = [
                            'product' => $item,
                            'Amount' => $quantity,
                        ];
                    }
                }
            }

            $productDataSave = [];
            if (!empty($productItems)) {
                foreach ($productItems as $product) {
                    $productDataSave[] = [
                        'Code' => $product['product']->code,
                        'Detail' => $product['product']->description,
                        'Amount' => $product['Amount'],
                    ];
                }
                $productData['Product'] = $productDataSave; // Assign the whole array once after the loop
            }

            {//คำนวน
                $totalAmount = 0;
                $totalPrice = 0;
                $subtotal = 0;
                $beforeTax = 0;
                $AddTax = 0;
                $Nettotal =0;
                $totalaverage=0;


                $totalguest = 0;
                $totalguest = $datarequest['Adult'] + $datarequest['Children'];
                $guest =  $datarequest['Adult'] + $datarequest['Children'];

                if ($Mvat == 50) {
                    foreach ($productItems as $item) {
                        $totalPrice += $item['Amount'];
                        $subtotal = $totalPrice;
                        $beforeTax = $subtotal/1.07;
                        $AddTax = $subtotal-$beforeTax;
                        $Nettotal = $subtotal;
                        $totalaverage =$Nettotal/$totalguest;
                        $totalAmount = $totalPrice;

                    }
                }
                elseif ($Mvat == 51) {
                    foreach ($productItems as $item) {
                        $totalPrice += $item['Amount'];
                        $subtotal = $totalPrice;
                        $Nettotal = $subtotal;
                        $totalaverage =$Nettotal/$totalguest;

                    }
                }
                elseif ($Mvat == 52) {
                    foreach ($productItems as $item) {
                        $totalPrice += $item['Amount'];
                        $subtotal = $totalPrice;
                        $AddTax = $subtotal*7/100;
                        $Nettotal = $subtotal+$AddTax;
                        $totalaverage =$Nettotal/$totalguest;
                    }
                }else
                {
                    foreach ($productItems as $item) {
                        $totalPrice += $item['Amount'];
                        $subtotal = $totalAmount-$SpecialDis;
                        $beforeTax = $subtotal/1.07;
                        $AddTax = $subtotal-$beforeTax;
                        $Nettotal = $subtotal;
                        $totalaverage =$Nettotal/$guest;
                    }
                }

            }
        }
        try {
            $ProposalData = proposal_overbill::where('id', $id)->first();
            $ProposalID = $ProposalData->Additional_ID;

            // Retrieve and filter proposal products
            $ProposalProducts = document_proposal_overbill::where('Additional_ID', $ProposalID)->get();
            $dataArray['Product'] = $ProposalProducts->map(function ($item) {
                // Remove unnecessary fields from each item
                return Arr::only($item->toArray(), ['Code', 'Detail', 'Amount']);
            })->toArray();

            $productData['Product'] = $productData['Product'] ?? [];
            $keysToCompare = ['Product'];
            $differences = [];

            foreach ($keysToCompare as $key) {
                if (is_array($dataArray[$key]) && is_array($productData[$key])) {
                    foreach ($dataArray[$key] as $index => $value) {
                        if (isset($productData[$key][$index])) {
                            if ($value != $productData[$key][$index]) {
                                $differences[$key][$index] = [
                                    'dataArray' => $value,
                                    'request' => $productData[$key][$index]
                                ];
                            }
                        } else {
                            $differences[$key][$index] = [
                                'dataArray' => $value,
                                'request' => null
                            ];
                        }
                    }
                    // Handle case where $productData has extra elements
                    foreach ($productData[$key] as $index => $value) {
                        if (!isset($dataArray[$key][$index])) {
                            $differences[$key][$index] = [
                                'dataArray' => null,
                                'request' => $value
                            ];
                        }
                    }
                } elseif (isset($dataArray[$key])) {
                    $differences[$key] = [
                        'dataArray' => $dataArray[$key],
                        'request' => null
                    ];
                } elseif (isset($productData[$key])) {
                    $differences[$key] = [
                        'dataArray' => null,
                        'request' => $productData[$key]
                    ];
                }
            }

            // แยกข้อมูลที่ไม่ซ้ำกันในแต่ละ array
            $onlyInDataArray = [];
            $onlyInRequest = [];

            if (isset($differences['Product'])) {
                $onlyInDataArray = array_filter($dataArray['Product'], function ($item) use ($productData) {
                    return !in_array($item, $productData['Product']);
                });

                $onlyInRequest = array_filter($productData['Product'], function ($item) use ($dataArray) {
                    return !in_array($item, $dataArray['Product']);
                });
            }
            $extractedData = [];
            $extractedDataA = [];
            foreach ($differences as $key => $value) {
                if ($key === 'Product') {
                    // ถ้าเป็น Products ให้เก็บค่า request และ dataArray ที่แตกต่างกัน
                    $extractedData[$key] = $onlyInDataArray; // ใช้ข้อมูลจาก $onlyInDataArray (ลบ)
                    $extractedDataA[$key] = $onlyInRequest;  // ใช้ข้อมูลจาก $onlyInRequest (เพิ่ม)
                }
            }
            $Products =  $extractedData['Product'] ?? null;
            $ProductsA =  $extractedDataA['Product'] ?? null;
            $formattedProductData = [];
            $formattedProductDataA = [];
            if ($Products) {
                $productDelete = [];
                foreach ($Products as $product) {
                    $productDelete[] = [
                        'Code' => $product['Code'],
                        'Detail' => $product['Detail'],
                        'Amount' => $product['Amount'],
                    ];
                }
                // จัดรูปแบบข้อมูลของผลิตภัณฑ์
                foreach ($productDelete as $product) {
                    $formattedProductData[] = 'ลบรายการ' . ' ' . 'Code : ' . $product['Code'] . ' , ' . 'Detail : ' . $product['Detail'] . ' , ' . 'Amount : ' . $product['Amount'];
                }
            }
            if ($ProductsA) {
                $productIncrease  = [];
                foreach ($ProductsA as $product) {
                    $productIncrease[] = [
                        'Code' => $product['Code'],
                        'Detail' => $product['Detail'],
                        'Amount' => $product['Amount'],
                    ];

                }

                // จัดรูปแบบข้อมูลของผลิตภัณฑ์
                foreach ($productIncrease as $product) {
                    $formattedProductDataA[] = 'เพิ่มรายการ' . ' ' . 'Code : ' . $product['Code'] . ' , ' . 'Detail : ' . $product['Detail'] . ' , ' . 'Amount : ' . $product['Amount'];
                }
            }
            $Additional_type = null;
            $Cash = null;
            $Complimentary = null;
            $Additional_type = 'Additional Type : '.$request->additional_type;
            if ($request->Cash) {
                if ( $Quotation->Cash != $request->Cash) {
                    $Cash = 'Cash : '.$request->Cash;
                }
            }
            if ($request->Complimentary) {
                if ( $Quotation->Complimentary != $request->Complimentary) {
                    $Complimentary = 'Complimentary : '.$request->Complimentary;
                }
            }
            $Additional = 'Additional ID : '.$Additional_ID;
            $com = 'รายการ';
            $datacompany = '';

            $variables = [$Additional,$com,$Additional_type,$Cash,$Complimentary];
            // แปลง array ของ $formattedProductData เป็น string เดียวที่มีรายการทั้งหมด
            $formattedProductDataString = implode(' + ', $formattedProductData);
            $formattedProductDataStringA = implode(' + ', $formattedProductDataA);

            // รวม $formattedProductDataString เข้าไปใน $variables
            $variables[] = $formattedProductDataString;
            $variables[] = $formattedProductDataStringA;
            foreach ($variables as $variable) {
                if (!empty($variable)) {
                    if (!empty($datacompany)) {
                        $datacompany .= ' + ';
                    }
                    $datacompany .= $variable;
                }
            }
            $userids = Auth::user()->id;
            $save = new log_company();
            $save->Created_by = $userids;
            $save->Company_ID = $Additional_ID;
            $save->type = 'Edit';
            $save->Category = 'Edit :: Additional';
            $save->content =$datacompany;
            $save->save();
        } catch (\Throwable $e) {
            return redirect()->route('Additional.edit', ['id' => $Quotation->id])->with('error',$e->getMessage());
        }
        try {
            $userid = Auth::user()->id;
            $Quotationcheck = proposal_overbill::where('id',$id)->first();
            $correct = $Quotationcheck->correct;
            if ($correct >= 1) {
                $correctup = $correct + 1;
            }else{
                $correctup = 1;
            }
            $datarequest = [
                'Proposal_ID' => $data['Quotation_ID'] ?? null,
                'Code' => $data['Code'] ?? null ,
                'CheckProduct' => $data['CheckProduct'] ?? null ,
                'Amount' => $data['Amount'] ?? [],
                'IssueDate' => $Quotation['issue_date'] ?? null,
                'Expiration' => $Quotation['Expirationdate'] ?? null,
                'Selectdata' => $Quotation['type_Proposal'] ?? null,
                'Data_ID' => $Quotation['Company_ID'] ?? null,
                'Adult' => $Quotation['adult'] ?? null,
                'Children' => $Quotation['children'] ?? null,
                'Mevent' => $Quotation['eventformat'] ?? null,
                'Mvat' => $Quotation['vat_type'] ?? null,
                'comment' => $data['comment'] ?? null,
                'PaxToTalall' => $Quotation['TotalPax'] ?? null,
                'Checkin' => $Quotation['checkin'] ?? null,
                'Checkout' => $Quotation['checkout'] ?? null,
                'Day' => $Quotation['day'] ?? null,
                'Night' => $Quotation['night'] ?? null,
            ];
            $Products = $datarequest['Code'];
            $Productslast = $datarequest['CheckProduct'];
            if (is_array($Products) && is_array($Productslast)) {
                $commonValues = array_intersect($Products, $Productslast);
                if (!empty($commonValues)) {
                    $diffFromProducts = array_diff($Products, $Productslast);
                    $diffFromProductslast = array_diff($Productslast, $Products);
                    $Code = array_merge($commonValues,$diffFromProducts,$diffFromProductslast);
                } else {
                    $Code = array_merge($Productslast,$Products);
                }

            }else{
                $Code = $Productslast;
            }
            $Amount = $datarequest['Amount'];
            $productItems = [];

            if (count($Code) === count($Amount)) {
                foreach ($Code as $index => $productID) {
                    // Retrieve the product details based on Code
                    $items = Master_additional::where('code', $productID)->get();

                    foreach ($items as $item) {
                        // Use corresponding Amount for each productID based on index
                        $quantity = isset($Amount[$index]) ? intval($Amount[$index]) : 0;
                        $productItems[] = [
                            'product' => $item,
                            'Amount' => $quantity,
                        ];
                    }
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


                $totalguest = 0;
                $totalguest = $datarequest['Adult'] + $datarequest['Children'];
                $guest =  $datarequest['Adult'] + $datarequest['Children'];

                foreach ($productItems as $item) {
                    $totalPrice += $item['Amount'];
                    $subtotal = $totalPrice;
                    $beforeTax = $subtotal/1.07;
                    $AddTax = $subtotal-$beforeTax;
                    $Nettotal = $subtotal;
                    $totalaverage =$Nettotal/$totalguest;
                    $totalAmount = $totalPrice;
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
                $linkQR = $protocol . '://' . $request->getHost() . "/Document/Additional/Charge/document/PDF/$id?page_shop=" . $request->input('page_shop');
                $qrCodeImage = QrCode::format('svg')->size(200)->generate($linkQR);
                $qrCodeBase64 = base64_encode($qrCodeImage);
            }
            $Proposal_ID = $datarequest['Proposal_ID'];
            $IssueDate = $datarequest['IssueDate'];
            $Expiration = $datarequest['Expiration'];
            $Selectdata = $datarequest['Selectdata'];
            $Data_ID = $datarequest['Data_ID'];
            $Adult = $datarequest['Adult'];
            $Children = $datarequest['Children'];
            $Mevent = $datarequest['Mevent'];
            $Mvat = $datarequest['Mvat'];
            $Checkin = $datarequest['Checkin'];
            $Checkout = $datarequest['Checkout'];
            $Day = $datarequest['Day'];
            $Night = $datarequest['Night'];
            $comment = $datarequest['comment'];
                $user = User::where('id',$userid)->first();
            $fullName = null;
            $Contact_Name = null;
            $Contact_phone =null;
            $Contact_Email = null;
            if ($Selectdata == 'Guest') {
                $Data = Guest::where('Profile_ID',$Quotation->Company_ID)->first();
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
                $phone = phone_guest::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
            }else{
                $Company = companys::where('Profile_ID',$Quotation->Company_ID)->first();
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
                $representative = representative::where('Company_ID',$Quotation->Company_ID)->first();
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
                $company_fax = company_fax::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
                if ($company_fax) {
                    $Fax_number =  $company_fax->Fax_number;
                }else{
                    $Fax_number = '-';
                }
                $phone = company_phone::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
                $Contact_phone = representative_phone::where('Company_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
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
                'Additional_ID'=>$Additional_ID,
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
                'subtotal'=>$subtotal,
                'beforeTax'=>$beforeTax,
                'Nettotal'=>$Nettotal,
                'totalguest'=>$totalguest,
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
            $pdf = FacadePdf::loadView('additional_charge.additional_charge_pdf.'.$view,$data);
            $path = 'PDF/additional/';
            $pdf->save($path . $Additional_ID.'-'.$correctup . '.pdf');
        } catch (\Throwable $e) {
            return redirect()->route('Additional.edit', ['id' => $Quotation->id])->with('error',$e->getMessage());
        }
        try {
            $currentDateTime = Carbon::now();
            $currentDate = $currentDateTime->toDateString(); // Format: YYYY-MM-DD
            $currentTime = $currentDateTime->toTimeString(); // Format: HH:MM:SS
            // Optionally, you can format the date and time as per your requirement
            $formattedDate = $currentDateTime->format('Y-m-d'); // Custom format for date
            $formattedTime = $currentDateTime->format('H:i:s');
            {
                $Proposal_ID = $datarequest['Proposal_ID'];
                $IssueDate = $datarequest['IssueDate'];
                $Expiration = $datarequest['Expiration'];
                $Selectdata = $Quotation->type_Proposal;
                $Data_ID = $Quotation->Company_ID;
                $Adult = $datarequest['Adult'];
                $Children = $datarequest['Children'];
                $Mevent = $datarequest['Mevent'];
                $Mvat = $datarequest['Mvat'];
                $Checkin = $datarequest['Checkin'];
                $Checkout = $datarequest['Checkout'];
                $Day = $datarequest['Day'];
                $Night = $datarequest['Night'];
                $comment = $datarequest['comment'];
                if ($Selectdata == 'Guest') {
                    $Data = Guest::where('Profile_ID',$Data_ID)->first();
                    $prename = $Data->preface;
                    $First_name = $Data->First_name;
                    $Last_name = $Data->Last_name;
                    $prefix = master_document::where('id',$prename)->where('Category','Mprename')->where('status',1)->first();
                    $name = $prefix->name_th;
                    $fullName = $name.' '.$First_name.' '.$Last_name;
                    //-------------ที่อยู่

                }else{
                    $Company = companys::where('Profile_ID',$Data_ID)->first();
                    $Company_type = $Company->Company_type;
                    $Compannyname = $Company->Company_Name;
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

                }
            }
            $savePDF = new log();
            $savePDF->Quotation_ID = $Additional_ID;
            $savePDF->QuotationType = 'Additional';
            $savePDF->Company_Name = $fullName;
            $savePDF->Approve_date = $formattedDate;
            $savePDF->Approve_time = $formattedTime;
            $savePDF->correct = $correctup;
            $savePDF->save();
        } catch (\Throwable $e) {
            log_company::where('Category', 'Edit :: Additional')
            ->orderBy('created_at', 'desc')
            ->limit(1) // ลบข้อมูลล่าสุด 1 แถว
            ->delete();
            $path = 'PDF/additional/';
            $file = $path . $Additional_ID .'-'.$correctup.'.pdf';
            if (is_file($file)) {
                unlink($file); // ลบไฟล์
            }
            log::where('Quotation_ID',$Additional_ID)->orderBy('created_at', 'desc')->limit(1)->delete();
            return redirect()->route('Additional.create', ['id' => $Quotation->id])->with('error',$e->getMessage());
        }

        try {
            $totalPrice = 0; // กำหนดตัวแปรเริ่มต้น
            foreach ($productItemsData as $item) {
                $totalPrice += $item['Amount']; // รวมยอดราคา
            }

            $subtotal = $totalPrice;
            $beforeTax = $subtotal / 1.07; // คำนวณก่อนหักภาษี
            $AddTaxD = $subtotal - $beforeTax; // คำนวณภาษี
            $NettotalD = $subtotal; // ยอดรวมสุทธิ
            $totalaverage = $Nettotal / $totalguest; // คำนวณค่าเฉลี่ย
            $totalAmount = $totalPrice; // ยอดรวมทั้งหมด
            $save = proposal_overbill::find($Quotation->id); // ค้นหา proposal ที่ต้องการบันทึก
            $save->AddTax = $AddTaxD; // บันทึกภาษี
            $save->Nettotal = $NettotalD; // บันทึกยอดรวมสุทธิ
            $save->total = $NettotalD; // บันทึกยอดรวม
            $save->correct = $correctup;
            $save->additional_type = $request->additional_type;
            $save->Cash = $request->Cash;
            $save->Complimentary = $request->Complimentary;
            $save->status_document = 2;
            $save->save(); // บันทึกข้อมูล
        } catch (\Throwable $e) {
            log_company::where('Category', 'Edit :: Additional')
            ->orderBy('created_at', 'desc')
            ->limit(1) // ลบข้อมูลล่าสุด 1 แถว
            ->delete();
            $path = 'PDF/additional/';
            $file = $path . $Additional_ID .'-'.$correctup.'.pdf';
            if (is_file($file)) {
                unlink($file); // ลบไฟล์
            }
            log::where('Quotation_ID',$Additional_ID)->orderBy('created_at', 'desc')->limit(1)->delete();
            return redirect()->route('Additional.create', ['id' => $Quotation->id])->with('error',$e->getMessage());
        }
        try {
            if ($productDataSave) {
                $productold = document_proposal_overbill::where('Additional_ID', $Additional_ID)->delete();
                foreach ($productDataSave as $product) {
                    $saveProduct = new document_proposal_overbill();
                    $saveProduct->Additional_ID = $Additional_ID;
                    $saveProduct->Quotation_ID = $Quotation_ID;
                    $saveProduct->Code = $product['Code'];
                    $saveProduct->Detail = $product['Detail'];
                    $saveProduct->Amount = $product['Amount'];
                    $saveProduct->save();
                }
            }
        } catch (\Throwable $e) {
            log_company::where('Category', 'Edit :: Additional')
            ->orderBy('created_at', 'desc')
            ->limit(1) // ลบข้อมูลล่าสุด 1 แถว
            ->delete();
            $path = 'PDF/additional/';
            $file = $path . $Additional_ID .'-'.$correctup.'.pdf';
            if (is_file($file)) {
                unlink($file); // ลบไฟล์
            }
            log::where('Quotation_ID',$Additional_ID)->orderBy('created_at', 'desc')->limit(1)->delete();
            return redirect()->route('Additional.create', ['id' => $Quotation->id])->with('error',$e->getMessage());
        }
        try {
            $userid = Auth::user()->id;
            $log = new log_company();
            $log->Created_by = $userid;
            $log->Company_ID = $Additional_ID;
            $log->type = 'Send documents';
            $log->Category = 'Send documents :: Additional';
            $log->content = 'Send Document Additional : ' . $Additional_ID;
            $log->save();
        } catch (\Throwable $th) {
            return redirect()->route('Additional.create', ['id' => $Quotation->id])->with('error',$e->getMessage());
        }
        return redirect()->route('Additional.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }
    public function view($id){

        $settingCompany = Master_company::orderBy('id', 'desc')->first();
        $Quotation = proposal_overbill::where('id', $id)->first();
        $Additional_ID = $Quotation->Additional_ID;
        $Quotation_ID = $Quotation->Quotation_ID;
        $Quotation_IDoverbill = $Quotation->Additional_ID;
        $Company = companys::select('Company_Name','id','Profile_ID')->get();
        $Guest = Guest::select('First_name','Last_name','id','Profile_ID')->get();
        $Mevent = master_document::select('name_th','id')->where('status', '1')->where('Category','Mevent')->get();
        $Mvat = master_document::select('name_th','id')->where('status', '1')->where('Category','Mvat')->get();
        $Freelancer_member = Freelancer_Member::select('First_name','id','Profile_ID','Last_name')->where('status', '1')->get();
        $selectproduct = document_proposal_overbill::where('Additional_ID', $Additional_ID)->get();
        $unit = master_unit::where('status',1)->get();
        $quantity = master_quantity::where('status',1)->get();
        $Selectdata =  $Quotation->type_Proposal;
        if ($Selectdata == 'Guest') {
            $Data = Guest::where('Profile_ID',$Quotation->Company_ID)->first();
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
            $Contact_Name = ' ';
            $Contact_phone = ' ';
            $phone = phone_guest::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
        }else{
            $Company = companys::where('Profile_ID',$Quotation->Company_ID)->first();
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
            $representative = representative::where('Company_ID',$Quotation->Company_ID)->first();
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
            $company_fax = company_fax::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
            if ($company_fax) {
                $Fax_number =  $company_fax->Fax_number;
            }else{
                $Fax_number = '-';
            }
            $phone = company_phone::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
            $Contact_phone = representative_phone::where('Company_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
        }
        return view('additional_charge.view',compact('Address','fullName','settingCompany','Quotation','Quotation_ID','Company','Guest','Mevent','Mvat','Freelancer_member','selectproduct','unit','quantity','Quotation_IDoverbill',
                    'provinceNames','amphuresID','TambonID','Fax_number','phone','Email','Taxpayer_Identification','Contact_Name','Contact_phone','Selectdata'));
    }
    public function log($id){
        $Quotation = proposal_overbill::where('id', $id)->first();
        $QuotationID = $Quotation->Additional_ID;
        $correct = $Quotation->correct;
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        if ($Quotation) {


            // Use a regular expression to capture the part of the string before the first hyphen
            if (preg_match('/^(PD-\d{8})/', $QuotationID, $matches)) {
                $QuotationID = $matches[1];
            }

        }
        $log = log::where('Quotation_ID', 'LIKE', $QuotationID . '%')->get();
        $path = 'PDF/additional/';

        $logproposal = log_company::where('Company_ID', $QuotationID)
            ->orderBy('updated_at', 'desc')
            ->get();
        return view('additional_charge.log',compact('log','path','correct','logproposal','QuotationID'));
    }
    public function sheetpdf(Request $request ,$id){
        $Quotation = proposal_overbill::where('id', $id)->first();
        $Additional_ID = $Quotation->Additional_ID;
        $userid = Auth::user()->id;
        $selectproduct = document_proposal_overbill::where('Additional_ID', $Additional_ID)->get();
        $datarequest = [
            'Proposal_ID' => $Quotation['Quotation_ID'] ?? null,
            'Code' => $Quotation['Code'] ?? [],
            'Amount' => $Quotation['Amount'] ?? [],
            'IssueDate' => $Quotation['issue_date'] ?? null,
            'Expiration' => $Quotation['Expirationdate'] ?? null,
            'Selectdata' => $Quotation['type_Proposal'] ?? null,
            'Data_ID' => $Quotation['Company_ID'] ?? null,
            'Adult' => $Quotation['adult'] ?? null,
            'Children' => $Quotation['children'] ?? null,
            'Mevent' => $Quotation['eventformat'] ?? null,
            'Mvat' => $Quotation['vat_type'] ?? null,
            'comment' => $Quotation['comment'] ?? null,
            'PaxToTalall' => $Quotation['TotalPax'] ?? null,
            'Checkin' => $Quotation['checkin'] ?? null,
            'Checkout' => $Quotation['checkout'] ?? null,
            'Day' => $Quotation['day'] ?? null,
            'Night' => $Quotation['night'] ?? null,
            'Nettotal' => $Quotation['Nettotal'] ?? null,
            'AddTax' => $Quotation['AddTax'] ?? null,
            'total' => $Quotation['total'] ?? null,
        ];
        $productItems = [];
        foreach ($selectproduct as $product) {
            $productrequest = (object)[
                'Additional_ID' => $product->Additional_ID ?? null,
                'Quotation_ID' => $product->Quotation_ID ?? null,
                'code' => $product->Code ?? null,
                'description' => $product->Detail ?? null,
                'Amount' => $product->Amount ?? null,
            ];

            $productItems[] = [
                'product' => $productrequest,
                'Amount' => $productrequest->Amount,
            ];
        }
        {//คำนวน
            $totalAmount = 0;
            $totalPrice = 0;
            $subtotal = 0;
            $beforeTax = 0;
            $AddTax = 0;
            $Nettotal =0;
            $totalaverage=0;


            $totalguest = 0;
            $totalguest = $datarequest['Adult'] + $datarequest['Children'];
            $guest =  $datarequest['Adult'] + $datarequest['Children'];

            foreach ($productItems as $item) {
                $totalPrice += $item['Amount'];
                $subtotal = $totalPrice;
                $beforeTax = $subtotal/1.07;
                $AddTax = $subtotal-$beforeTax;
                $Nettotal = $subtotal;
                $totalaverage =$Nettotal/$totalguest;
                $totalAmount = $totalPrice;
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
            $linkQR = $protocol . '://' . $request->getHost() . "/Document/Additional/Charge/document/PDF/$id?page_shop=" . $request->input('page_shop');
            $qrCodeImage = QrCode::format('svg')->size(200)->generate($linkQR);
            $qrCodeBase64 = base64_encode($qrCodeImage);
        }
        $Proposal_ID = $datarequest['Proposal_ID'];
        $IssueDate = $datarequest['IssueDate'];
        $Expiration = $datarequest['Expiration'];
        $Selectdata = $datarequest['Selectdata'];
        $Data_ID = $datarequest['Data_ID'];
        $Adult = $datarequest['Adult'];
        $Children = $datarequest['Children'];
        $Mevent = $datarequest['Mevent'];
        $Mvat = $datarequest['Mvat'];
        $Checkin = $datarequest['Checkin'];
        $Checkout = $datarequest['Checkout'];
        $Day = $datarequest['Day'];
        $Night = $datarequest['Night'];
        $comment = $datarequest['comment'];
        $user = User::where('id',$userid)->first();
        $fullName = null;
        $Contact_Name = null;
        $Contact_phone =null;
        $Contact_Email = null;
        if ($Selectdata == 'Guest') {
            $Data = Guest::where('Profile_ID',$Quotation->Company_ID)->first();
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
            $phone = phone_guest::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
        }else{
            $Company = companys::where('Profile_ID',$Quotation->Company_ID)->first();
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
            $representative = representative::where('Company_ID',$Quotation->Company_ID)->first();
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
            $company_fax = company_fax::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
            if ($company_fax) {
                $Fax_number =  $company_fax->Fax_number;
            }else{
                $Fax_number = '-';
            }
            $phone = company_phone::where('Profile_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
            $Contact_phone = representative_phone::where('Company_ID',$Quotation->Company_ID)->where('Sequence','main')->first();
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
            'Additional_ID'=>$Additional_ID,
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
            'subtotal'=>$subtotal,
            'beforeTax'=>$beforeTax,
            'Nettotal'=>$Nettotal,
            'totalguest'=>$totalguest,
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
        $pdf = FacadePdf::loadView('additional_charge.additional_charge_pdf.preview',$data);
        return $pdf->stream();
    }
    public function Cancel(Request $request ,$id){
        $Quotation = proposal_overbill::find($id);
        $Quotation->status_document = 0;
        $Quotation->remark = $request->note;
        $Quotation->save();
        $data = proposal_overbill::where('id',$id)->first();
        $Additional_ID = $data->Additional_ID;
        $userid = Auth::user()->id;
        $save = new log_company();
        $save->Created_by = $userid;
        $save->Company_ID = $Additional_ID;
        $save->type = 'Cancel';
        $save->Category = 'Cancel :: Additional';
        $save->content = 'Cancel Document Additional ID : '.$Additional_ID.'+'.$request->note;
        $save->save();
        return redirect()->route('Additional.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }
    public function Delete(Request $request ,$id){
        $data = proposal_overbill::where('id',$id)->first();
        $Additional_ID = $data->Additional_ID;
        try {
            document_proposal_overbill::where('Additional_ID', $Additional_ID)->delete();
            proposal_overbill::where('id',$id)->delete();
        } catch (\Throwable $e) {
            return redirect()->route('Additional.index')->with('error',$e->getMessage());
        }
        return redirect()->route('Additional.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }
    public function Revice(Request $request ,$id){
        $Quotation = proposal_overbill::find($id);
        $Quotation->status_document = 3;
        $Quotation->save();
        $data = proposal_overbill::where('id',$id)->first();
        $Additional_ID = $data->Additional_ID;
        $userid = Auth::user()->id;
        $save = new log_company();
        $save->Created_by = $userid;
        $save->Company_ID = $Additional_ID;
        $save->type = 'Revice';
        $save->Category = 'Revice :: Additional';
        $save->content = 'Revice Document Additional ID : '.$Additional_ID;
        $save->save();
        return redirect()->route('Additional.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }

    public function addProduct($Quotation_ID){
        $value = $Quotation_ID;
        if ($value == 'Room_Type') {
            $products = Master_additional::where('type','RM')->get();
        }
        elseif ($value == 'Banquet') {
            $products = Master_additional::where('type','BQ')->get();
        }
        elseif ($value == 'Meals') {
            $products = Master_additional::where('type','FB')->get();
        }
        elseif ($value == 'Entertainment') {
            $products = Master_additional::where('type','AT')->get();
        }
        elseif ($value == 'Other') {
            $products = Master_additional::where('type','EM')->get();
        }
        elseif ($value == 'all'){
            $products = Master_additional::query()->get();
        }
        return response()->json([
            'products' => $products,

        ]);
    }
    public function addProductselect($Quotation_ID) {
        $id = $Quotation_ID;
        $products = Master_additional::where('id',$id)->get();
        return response()->json([
            'products' => $products,
        ]);
    }
    public function addProducttablecreatemain($Quotation_ID) {
        $id = $Quotation_ID;
        $products = Master_additional::query()->get();
        return response()->json([
            'products' => $products,

        ]);
    }

    public function Generate(Request $request ,$id){
        $Additional = proposal_overbill::where('id', $id)->first();
        $Additional_ID = $Additional->Additional_ID;
        $guest = $Additional->Company_ID;
        $type = $Additional->type_Proposal;
        $total = $Additional->total;
        if ($type == 'Company') {
            $data = companys::where('Profile_ID',$guest)->first();
            $Identification = $data->Taxpayer_Identification;
            $Company_typeID=$data->Company_type;
            $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
            if ($comtype->name_th =="บริษัทจำกัด") {
                $name = "บริษัท ". $data->Company_Name . " จำกัด";
            }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                $name = "บริษัท ". $data->Company_Name . " จำกัด (มหาชน)";
            }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                $name = "ห้างหุ้นส่วนจำกัด ". $data->Company_Name ;
            }else{
                $name = $comtype->name_th . $data->Company_Name;
            }
            $name_ID = $data->Profile_ID;
            $datasub = company_tax::where('Company_ID',$name_ID)->get();
            $Address=$data->Address;
            $CityID=$data->City;
            $amphuresID = $data->Amphures;
            $TambonID = $data->Tambon;
            $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
            $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
            $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
            $address = $Address.' '.$TambonID->name_th.' '.$amphuresID->name_th.' '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
        }else {
            $data = Guest::where('Profile_ID',$guest)->first();
            $name =  'คุณ '.$data->First_name.' '.$data->Last_name;
            $Identification = $data->Identification_Number;
            $name_ID = $data->Profile_ID;
            $datasub = guest_tax::where('Company_ID',$name_ID)->get();
            $Address=$data->Address;
            $CityID=$data->City;
            $amphuresID = $data->Amphures;
            $TambonID = $data->Tambon;
            $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
            $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
            $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
            $address = $Address.' '.$TambonID->name_th.' '.$amphuresID->name_th.' '.$provinceNames->name_th.' '.$TambonID->Zip_Code;
        }
        $currentDate = Carbon::now();
        $ID = 'RE-';
        $formattedDate = Carbon::parse($currentDate);       // วันที่
        $month = $formattedDate->format('m'); // เดือน
        $year = $formattedDate->format('y');
        $lastRun = receive_payment::latest()->first();
        $nextNumber = 1;

        if ($lastRun == null) {
            $nextNumber = $lastRun + 1;

        }else{
            $lastRunid = $lastRun->id;
            $nextNumber = $lastRunid + 1;
        }
        $newRunNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        $REID = $ID.$year.$month.$newRunNumber;
        $settingCompany = Master_company::orderBy('id', 'desc')->first();
        $sumpayment = $total;
        return view('billingfolio.overbill.paid',compact('address','Identification','sumpayment','Additional','name','settingCompany','type','total','name_ID','REID','datasub'));
    }
    public function PaidDataprewive($id)
    {
        $parts = explode('-', $id);
        $firstPart = $parts[0];
        if ($firstPart == 'C') {
            $company =  companys::where('Profile_ID',$id)->first();
            if ($company) {
                $fullname = "";
                $Company_typeID=$company->Company_type;
                if ($company->Company_Name) {
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $fullnameCom = " บริษัท ". $company->Company_Name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $fullnameCom = " บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $fullnameCom = " ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                    }else {
                        $fullnameCom = $comtype->name_th . $company->Company_Name;
                    }
                }else{
                    $fullnameCom = "";
                }
                $Address=$company->Address;
                $CityID=$company->City;
                $amphuresID = $company->Amphures;
                $TambonID = $company->Tambon;
                $Identification = $company->Taxpayer_Identification;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
            }else{
                $company =  company_tax::where('ComTax_ID',$id)->first();
                $fullname = $company && $company->Companny_name
                            ? ""
                            : 'คุณ ' . $company->first_name . ' ' . $company->last_name;
                $Company_typeID=$company->Company_type;
                if ($company->Companny_name) {
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $fullnameCom = " บริษัท ". $company->Companny_name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $fullnameCom = " บริษัท ". $company->Companny_name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $fullnameCom = " ห้างหุ้นส่วนจำกัด ". $company->Companny_name ;
                    }else {
                        $fullnameCom = $comtype->name_th . $company->Companny_name;
                    }
                }else{
                    $fullnameCom = "";
                }
                $Address=$company->Address;
                $CityID=$company->City;
                $amphuresID = $company->Amphures;
                $TambonID = $company->Tambon;
                $Identification = $company->Taxpayer_Identification;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
            }
        }else{
            $guestdata =  Guest::where('Profile_ID',$id)->first();
            if ($guestdata) {
                $fullname =  'คุณ '.$guestdata->First_name.' '.$guestdata->Last_name;
                $fullnameCom = "";
                $Address=$guestdata->Address;
                $CityID=$guestdata->City;
                $amphuresID = $guestdata->Amphures;
                $TambonID = $guestdata->Tambon;
                $Identification = $guestdata->Identification_Number;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
            }else{
                $guestdata =  guest_tax::where('GuestTax_ID',$id)->first();
                $fullname = $guestdata && $guestdata->Company_name
                            ? ""
                            : 'คุณ ' . $guestdata->first_name . ' ' . $guestdata->last_name;
                $Company_typeID=$guestdata->Company_type;
                if ($guestdata->Company_name) {
                    $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                    if ($comtype->name_th =="บริษัทจำกัด") {
                        $fullnameCom = " บริษัท ". $guestdata->Company_name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $fullnameCom = " บริษัท ". $guestdata->Company_name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $fullnameCom = " ห้างหุ้นส่วนจำกัด ". $guestdata->Company_name ;
                    }else {
                        $fullnameCom = $comtype->name_th . $guestdata->Company_name;
                    }
                }else{
                    $fullnameCom = "";
                }
                $Address=$guestdata->Address;
                $CityID=$guestdata->City;
                $amphuresID = $guestdata->Amphures;
                $TambonID = $guestdata->Tambon;
                $Identification = $guestdata->Identification_Number;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
            }
        }
        $date = Carbon::now();
        $dateFormatted = $date->format('d/m/Y');
        $dateTime = $date->format('h:i:s A');
        return response()->json([
            'date'=>$dateFormatted,
            'Time'=>$dateTime,
            'fullname'=>$fullname,
            'fullnameCom'=>$fullnameCom,
            'Address' => $Address,
            'Identification' => $Identification,
            'province'=>$provinceNames,
            'amphures'=>$amphuresID,
            'Tambon'=>$TambonID,
        ]);
    }


}
