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
class BillingFolioOverbill extends Controller
{
    public function index(){
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $Proposal = proposal_overbill::query()->paginate($perPage);
        return view('billingfolio.overbill.index',compact('Proposal'));
    }
    public function select(){
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $Proposal = Quotation::query()
                ->leftJoin('document_receive', 'quotation.Quotation_ID', '=', 'document_receive.Quotation_ID')
                ->where('quotation.status_guest', 1)
                ->select(
                    'quotation.*',
                    DB::raw('SUM(document_receive.Amount) as receive_amount')
                )
                ->groupBy('quotation.Quotation_ID')
                ->havingRaw('SUM(document_receive.Amount) - quotation.Nettotal = 0')
                ->paginate($perPage);
        return view('billingfolio.overbill.select',compact('Proposal'));
    }
    public function proposal($id){
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
        $Company = companys::select('Company_Name','id','Profile_ID')->get();
        $Guest = Guest::select('First_name','Last_name','id','Profile_ID')->get();
        $Mevent = master_document::select('name_th','id')->where('status', '1')->where('Category','Mevent')->get();
        $Mvat = master_document::select('name_th','id')->where('status', '1')->where('Category','Mvat')->get();
        $Freelancer_member = Freelancer_Member::select('First_name','id','Profile_ID','Last_name')->where('status', '1')->get();
        $selectproduct = document_quotation::where('Quotation_ID', $Quotation_ID)->get();
        $unit = master_unit::where('status',1)->get();
        $quantity = master_quantity::where('status',1)->get();
        return view('billingfolio.overbill.proposal',compact('settingCompany','Quotation','Quotation_ID','Company','Guest','Mevent','Mvat','Freelancer_member','selectproduct','unit','quantity','Quotation_IDoverbill'));
    }
    public function create(Request $request ,$id){
        $Quotation = Quotation::where('id', $id)->first();
        $preview = $request->preview;
        $Quotation_ID=$request->Quotation_ID;
        $userid = Auth::user()->id;
        $data = $request->all();
        $Additional_ID=$request->Additional_ID;
        if ($preview == 1) {
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
                $linkQR = $protocol . '://' . $request->getHost() . "/Document/BillingFolio/Proposal/Over/document/PDF/$id?page_shop=" . $request->input('page_shop');
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
            $user = User::where('id',$userid)->select('id','name')->first();
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
            $pdf = FacadePdf::loadView('billingfolio.overbill_pdf.preview',$data);
            return $pdf->stream();
        }else{
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
                'Proposal_ID' => $data['Quotation_ID'] ?? null,
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
                    $AdditionalID = 'Additional ID : '.$Additional_ID;
                    $datacompany = '';

                    $variables = [$AdditionalID,$QuotationID, $Issue_Date, $Expiration_Date, $fullName, $Contact_Name,$Time,$nameevent,$namevat,$Pax,$Head];

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
                    return redirect()->route('BillingFolioOver.proposal', ['id' => $Quotation->id])->with('error',$e->getMessage());
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
                        $linkQR = $protocol . '://' . $request->getHost() . "/Document/BillingFolio/Proposal/Over/document/PDF/$id?page_shop=" . $request->input('page_shop');
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
                    $user = User::where('id',$userid)->select('id','name')->first();
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
                    $pdf = FacadePdf::loadView('billingfolio.overbill_pdf.preview',$data);
                    $path = 'Log_PDF/proposaloverbill/';
                    $pdf->save($path . $Additional_ID . '.pdf');
                } catch (\Throwable $e) {
                    log_company::where('Category','Create :: Additional')->delete();
                    return redirect()->route('BillingFolioOver.proposal', ['id' => $Quotation->id])->with('error',$e->getMessage());
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
                    $path = 'Log_PDF/proposaloverbill/';
                    $file = $path . $Additional_ID . '.pdf';
                    if (is_file($file)) {
                        unlink($file); // ลบไฟล์
                    }
                    log::where('Quotation_ID',$Additional_ID)->delete();
                    return redirect()->route('BillingFolioOver.proposal', ['id' => $Quotation->id])->with('error',$e->getMessage());
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
                    $save->Operated_by = $userid;
                    $save->comment = $request->comment;
                    $save->Date_type = $Quotation->Date_type;
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
                    $path = 'Log_PDF/proposaloverbill/';
                    $file = $path . $Additional_ID . '.pdf';
                    if (is_file($file)) {
                        unlink($file); // ลบไฟล์
                    }
                    log::where('Quotation_ID',$Additional_ID)->delete();
                    proposal_overbill::where('Additional_ID',$Additional_ID)->delete();
                    return redirect()->route('BillingFolioOver.proposal', ['id' => $Quotation->id])->with('error',$e->getMessage());
                }
                return redirect()->route('BillingFolioOver.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
            }
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
        return view('billingfolio.overbill.edit',compact('settingCompany','Quotation','Quotation_ID','Company','Guest','Mevent','Mvat','Freelancer_member','selectproduct','unit','quantity','Quotation_IDoverbill'));
    }
    public function update(Request $request ,$id){
        $data = $request->all();
        $Quotation = proposal_overbill::where('id', $id)->first();
        $preview = $request->preview;
        $Quotation_ID=$request->Quotation_ID;
        $userid = Auth::user()->id;
        $data = $request->all();
        $Additional_ID=$request->Additional_ID;
        if ($preview == 1) {
            try {
                $userid = Auth::user()->id;
                $datarequest = [
                    'Proposal_ID' => $Quotation['Quotation_ID'] ?? null,
                    'Additional_ID'=> $Quotation['Additional_ID'] ?? null,
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
                    $linkQR = $protocol . '://' . $request->getHost() . "/Document/BillingFolio/Proposal/Over/document/PDF/$id?page_shop=" . $request->input('page_shop');
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
                $user = User::where('id',$userid)->select('id','name')->first();
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
                $pdf = FacadePdf::loadView('billingfolio.overbill_pdf.preview',$data);
                return $pdf->stream();
            } catch (\Throwable $e) {
                return redirect()->route('BillingFolioOver.edit', ['id' => $Quotation->id])->with('error',$e->getMessage());
            }
        }else{
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
                        $formattedProductData[] = 'ลบรายการ' . '+ ' . 'Code : ' . $product['Code'] . ' , ' . 'Detail : ' . $product['Detail'] . ' , ' . 'Amount : ' . $product['Amount'];
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
                        $formattedProductDataA[] = 'เพิ่มรายการ' . '+ ' . 'Code : ' . $product['Code'] . ' , ' . 'Detail : ' . $product['Detail'] . ' , ' . 'Amount : ' . $product['Amount'];
                    }
                }

                $Additional = 'Additional ID : '.$Additional_ID;
                $com = 'รายการ';
                $datacompany = '';

                $variables = [$Additional,$com];
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
                dd($datacompany);
            } catch (\Throwable $e) {
                //throw $th;
            }
            dd(1);
        }


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
            $linkQR = $protocol . '://' . $request->getHost() . "/Document/BillingFolio/Proposal/Over/document/PDF/$id?page_shop=" . $request->input('page_shop');
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
        $user = User::where('id',$userid)->select('id','name')->first();
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
        $pdf = FacadePdf::loadView('billingfolio.overbill_pdf.preview',$data);
        return $pdf->stream();
    }
    public function addProduct($Quotation_ID, Request $request){
        $value = $request->input('value');
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
    public function addProductselect($Quotation_ID, Request $request) {
        $id = $request->input('value');
        $products = Master_additional::where('id',$id)->get();
        return response()->json([
            'products' => $products,
        ]);
    }
    public function addProducttablecreatemain($Quotation_ID, Request $request) {
        $id = $request->input('value');
        $products = Master_additional::query()->get();
        return response()->json([
            'products' => $products,

        ]);
    }
    public function  paginate_table_billingover(Request $request)
    {
        $perPage = (int)$request->perPage;
        $userid = Auth::user()->id;
        $data = [];
        $permissionid = Auth::user()->permission;
        if ($perPage == 10) {
            $data_query =  proposal_overbill::query()->limit($request->page.'0')
            ->get();
        } else {
            $data_query =  proposal_overbill::query()->paginate($perPage);
        }


        $page_1 = $request->page == 1 ? 1 : ($request->page - 1).'1';
        $page_2 = $request->page.'0';

        $perPage2 = $request->perPage > 10 ? $request->perPage : 10;

        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $btn_action = "";
                $btn_status = "";
                $name ="";
                // สร้าง dropdown สำหรับการทำรายการ
                if (($key + 1) >= (int)$page_1 && ($key + 1) <= (int)$page_2 || (int)$perPage > 10 && $key < (int)$perPage2) {

                    if ($value->type_Proposal == 'Company') {
                        $name = '<td>' .@$value->company->Company_Name. '</td>';
                    }else {
                        $name = '<td>' . @$value->guest->First_name . ' ' . @$value->guest->Last_name . '</td>';
                    }
                    // สร้างสถานะการใช้งาน
                    $btn_status = '<span class="badge rounded-pill bg-success">Approved</span>';
                    $CreateBy = Auth::user()->id;
                    $isOperatedByCreator = $value->Operated_by == $CreateBy;
                    if ($isOperatedByCreator) {
                        $btn_action = '<button type="button" class="btn btn-color-green lift btn_modal" href="' . url('/Document/BillingFolio/Proposal/Over/' . $value->id) . '" >
                                        Select
                                        </button>';
                    }else{
                        $btn_action = '<button type="button" class="btn btn-color-green lift btn_modal" disabled>
                                        Select
                                        </button>';
                    }
                    $data[] = [
                        'number' => ($key + 1) ,
                        'Proposal_ID' => $value->Quotation_ID,
                        'Company_Name' => $name,
                        'IssueDate' => $value->issue_date,
                        'Type'=>$value->Date_type ? $value->Date_type : 'No Check in Date',
                        'CheckIn' => $value->checkin ? $value->checkin : '-',
                        'CheckOut' => $value->checkout ? $value->checkout : '-',
                        'ExpirationDate' => $value->Expirationdate,
                        'Operated' => @$value->userOperated->name,
                        'DocumentStatus' => $btn_status,
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
    public function search_table_billingover(Request $request)
    {
        $perPage = (int)$request->perPage;
        $search_value = $request->search_value;
        $guest_profile = $request->guest_profile;
        $userid = Auth::user()->id;

        if ($search_value) {
            $data_query = proposal_overbill::query()
            ->where('Quotation_ID', 'LIKE', '%'.$search_value.'%')
            ->orWhere('checkin', 'LIKE', '%'.$search_value.'%')
            ->orWhere('checkout', 'LIKE', '%'.$search_value.'%')
            ->orWhere('issue_date', 'LIKE', '%'.$search_value.'%')
            ->orWhere('Expirationdate', 'LIKE', '%'.$search_value.'%')
            ->where('Company_ID',$guest_profile)
            ->paginate($perPage);
        }else{
            $perPageS = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
            $data_query =  proposal_overbill::query()->where('status_guest',1)->paginate($perPageS);
        }

        $data = [];
        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $btn_action = "";
                $btn_status = "";
                $name ="";
                if ($value->type_Proposal == 'Company') {
                    $name = '<td>' .@$value->company->Company_Name. '</td>';
                }else {
                    $name = '<td>' . @$value->guest->First_name . ' ' . @$value->guest->Last_name . '</td>';
                }
                // สร้างสถานะการใช้งาน
                $btn_status = '<span class="badge rounded-pill bg-success">Approved</span>';
                $CreateBy = Auth::user()->id;
                $isOperatedByCreator = $value->Operated_by == $CreateBy;
                if ($isOperatedByCreator) {
                    $btn_action = '<button type="button" class="btn btn-color-green lift btn_modal" href="' . url('/Document/BillingFolio/Proposal/Over/' . $value->id) . '" >
                                    Select
                                    </button>';
                }else{
                    $btn_action = '<button type="button" class="btn btn-color-green lift btn_modal" disabled>
                                    Select
                                    </button>';
                }
                $data[] = [
                    'number' => ($key + 1) ,
                    'Proposal_ID' => $value->Quotation_ID,
                    'Company_Name' => $name,
                    'IssueDate' => $value->issue_date,
                    'Type'=>$value->Date_type ? $value->Date_type : 'No Check in Date',
                    'CheckIn' => $value->checkin ? $value->checkin : '-',
                    'CheckOut' => $value->checkout ? $value->checkout : '-',
                    'ExpirationDate' => $value->Expirationdate,
                    'Operated' => @$value->userOperated->name,
                    'DocumentStatus' => $btn_status,
                    'btn_action' => $btn_action,
                ];
            }
        }
        // dd($data);
        return response()->json([
            'data' => $data,
        ]);
    }
    public function  paginate_table_billingover_proposal(Request $request)
    {
        $perPage = (int)$request->perPage;
        $userid = Auth::user()->id;
        $data = [];
        $permissionid = Auth::user()->permission;
        if ($perPage == 10) {
            $data_query =  Quotation::query()->where('status_guest',1)->limit($request->page.'0')
            ->get();
        } else {
            $data_query =  Quotation::query()->where('status_guest',1)->paginate($perPage);
        }


        $page_1 = $request->page == 1 ? 1 : ($request->page - 1).'1';
        $page_2 = $request->page.'0';

        $perPage2 = $request->perPage > 10 ? $request->perPage : 10;

        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $btn_action = "";
                $btn_status = "";
                $name ="";
                // สร้าง dropdown สำหรับการทำรายการ
                if (($key + 1) >= (int)$page_1 && ($key + 1) <= (int)$page_2 || (int)$perPage > 10 && $key < (int)$perPage2) {

                    if ($value->type_Proposal == 'Company') {
                        $name = '<td>' .@$value->company->Company_Name. '</td>';
                    }else {
                        $name = '<td>' . @$value->guest->First_name . ' ' . @$value->guest->Last_name . '</td>';
                    }
                    // สร้างสถานะการใช้งาน
                    $btn_status = '<span class="badge rounded-pill bg-success">Approved</span>';
                    $CreateBy = Auth::user()->id;
                    $isOperatedByCreator = $value->Operated_by == $CreateBy;
                    if ($isOperatedByCreator) {
                        $btn_action = '<button type="button" class="btn btn-color-green lift btn_modal" href="' . url('/Document/BillingFolio/Proposal/Over/' . $value->id) . '" >
                                        Select
                                        </button>';
                    }else{
                        $btn_action = '<button type="button" class="btn btn-color-green lift btn_modal" disabled>
                                        Select
                                        </button>';
                    }
                    $data[] = [
                        'number' => ($key + 1) ,
                        'Proposal_ID' => $value->Quotation_ID,
                        'Company_Name' => $name,
                        'IssueDate' => $value->issue_date,
                        'Type'=>$value->Date_type ? $value->Date_type : 'No Check in Date',
                        'CheckIn' => $value->checkin ? $value->checkin : '-',
                        'CheckOut' => $value->checkout ? $value->checkout : '-',
                        'ExpirationDate' => $value->Expirationdate,
                        'Operated' => @$value->userOperated->name,
                        'DocumentStatus' => $btn_status,
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
    public function search_table_billingover_proposal(Request $request)
    {
        $perPage = (int)$request->perPage;
        $search_value = $request->search_value;
        $guest_profile = $request->guest_profile;
        $userid = Auth::user()->id;

        if ($search_value) {
            $data_query = Quotation::where('status_guest',1)
            ->where('Quotation_ID', 'LIKE', '%'.$search_value.'%')
            ->orWhere('checkin', 'LIKE', '%'.$search_value.'%')
            ->orWhere('checkout', 'LIKE', '%'.$search_value.'%')
            ->orWhere('issue_date', 'LIKE', '%'.$search_value.'%')
            ->orWhere('Expirationdate', 'LIKE', '%'.$search_value.'%')
            ->where('Company_ID',$guest_profile)
            ->paginate($perPage);
        }else{
            $perPageS = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
            $data_query =  Quotation::query()->where('status_guest',1)->paginate($perPageS);
        }



        $data = [];
        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $btn_action = "";
                $btn_status = "";
                $name ="";
                if ($value->type_Proposal == 'Company') {
                    $name = '<td>' .@$value->company->Company_Name. '</td>';
                }else {
                    $name = '<td>' . @$value->guest->First_name . ' ' . @$value->guest->Last_name . '</td>';
                }
                // สร้างสถานะการใช้งาน
                $btn_status = '<span class="badge rounded-pill bg-success">Approved</span>';
                $CreateBy = Auth::user()->id;
                $isOperatedByCreator = $value->Operated_by == $CreateBy;
                if ($isOperatedByCreator) {
                    $btn_action = '<button type="button" class="btn btn-color-green lift btn_modal" href="' . url('/Document/BillingFolio/Proposal/Over/' . $value->id) . '" >
                                    Select
                                    </button>';
                }else{
                    $btn_action = '<button type="button" class="btn btn-color-green lift btn_modal" disabled>
                                    Select
                                    </button>';
                }
                $data[] = [
                    'number' => ($key + 1) ,
                    'Proposal_ID' => $value->Quotation_ID,
                    'Company_Name' => $name,
                    'IssueDate' => $value->issue_date,
                    'Type'=>$value->Date_type ? $value->Date_type : 'No Check in Date',
                    'CheckIn' => $value->checkin ? $value->checkin : '-',
                    'CheckOut' => $value->checkout ? $value->checkout : '-',
                    'ExpirationDate' => $value->Expirationdate,
                    'Operated' => @$value->userOperated->name,
                    'DocumentStatus' => $btn_status,
                    'btn_action' => $btn_action,
                ];
            }
        }
        // dd($data);
        return response()->json([
            'data' => $data,
        ]);
    }
}
