<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\dummy_quotation;
use App\Models\Quotation;
use Carbon\Carbon;
use App\Models\companys;
use App\Models\representative;
use App\Models\representative_phone;
use App\Models\company_fax;
use App\Models\company_phone;

use App\Models\Freelancer_Member;
use App\Models\province;
use App\Models\amphures;
use App\Models\districts;
use App\Models\master_document;
use App\Models\master_product_item;
use App\Models\master_quantity;
use App\Models\master_unit;
use App\Models\document_dummy_quotation;
use App\Models\document_quotation;
use App\Models\log;
use Auth;
use App\Models\User;
use PDF;
use App\Models\log_company;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use App\Models\master_document_sheet;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\DB;
use App\Models\master_template;
use Illuminate\Support\Arr;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
class DummyQuotationController extends Controller
{
    public function index()
    {
        $userid = Auth::user()->id;
        $Quotation = dummy_quotation::where('Operated_by',$userid)->get();
        $Quotationcount = dummy_quotation::where('Operated_by',$userid)->count();
        $Pending = dummy_quotation::where('Operated_by',$userid)->where('status_document', 1 )->get();
        $Pendingcount = dummy_quotation::where('Operated_by',$userid)->where('status_document',1)->count();
        $Awaitingcount = dummy_quotation::where('Operated_by',$userid)->where('status_document',2)->count();
        $Awaiting  = dummy_quotation::where('Operated_by',$userid)->where('status_document', 2 )->get();
        $Approvedcount = dummy_quotation::where('Operated_by',$userid)->where('status_document',3)->count();
        $Approved = dummy_quotation::where('Operated_by',$userid)->where('status_document', 3 )->get();
        $Rejectcount = dummy_quotation::where('Operated_by',$userid)->where('status_document',4)->count();
        $Reject = dummy_quotation::where('Operated_by',$userid)->where('status_document', 4)->get();
        $cancelcount = dummy_quotation::where('Operated_by',$userid)->where('status_document',0)->count();
        $cancel = dummy_quotation::where('Operated_by',$userid)->where('status_document',0)->get();
        $Generatecount = dummy_quotation::where('Operated_by',$userid)->where('status_document',5)->count();
        $Generate = dummy_quotation::where('Operated_by',$userid)->where('status_document', 5 )->get();

        $DummyNo = dummy_quotation::query()->pluck('DummyNo');
        $document = document_dummy_quotation::whereIn('Quotation_ID', $DummyNo)->get();
        $document_IDs = $document->pluck('Quotation_ID');
        $missingQuotationIDs = $DummyNo->diff($document_IDs);
        dummy_quotation::whereIn('DummyNo', $missingQuotationIDs)->delete();
        return view('dummy_quotation.index',compact('Quotation','Quotationcount','Pending','Pendingcount','Awaiting','Awaitingcount','Approvedcount','Approved','Rejectcount','Reject','cancelcount','cancel','Generatecount','Generate'));
    }
    public function changestatus($id ,$status)
    {
        try {
            $statusdata = dummy_quotation::find($id);
            if ($statusdata->status == 2 ) {
                $statusdata->status_document = $status;
            }elseif ($status == 0) {
                $statusdata->status_document = $status;
                $statusdata->status = 0;
            }
            $statusdata->save();

            return response()->json(['message' => 'Status updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating status.'], 500);
        }

    }
    public function ac(Request $request)
    {
        $ac = $request->value;
        if ($ac == 1 ) {
            $query = dummy_quotation::query();
            $Quotation = $query->where('status', '1')->get();
        }
        return view('dummy_quotation.index',compact('Quotation'));
    }
    public function no(Request $request)
    {
        $no = $request->value;
        if ($no == 0 ) {
            $query = dummy_quotation::query();
            $Quotation = $query->where('status', '0')->get();
        }
        return view('dummy_quotation.index',compact('Quotation'));
    }
    public function create()
    {
        $currentDate = Carbon::now();
        $ID = 'DD-';
        $formattedDate = Carbon::parse($currentDate);       // วันที่
        $month = $formattedDate->format('m'); // เดือน
        $year = $formattedDate->format('y');
        $lastRun = dummy_quotation::latest()->first();
        $nextNumber = 1;

        if ($lastRun == null) {
            $nextNumber = $lastRun + 1;
        }else{
            $lastRunid = $lastRun->id;
            $nextNumber = $lastRunid + 1;
        }
        $Issue_date = Carbon::parse($currentDate)->translatedFormat('d/m/Y');
        $Valid_Until = Carbon::parse($currentDate)->addDays(7)->translatedFormat('d/m/Y');
        $newRunNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        $DummyNo = $ID.$year.$month.$newRunNumber;
        $Mevent = master_document::select('name_th','id')->where('status', '1')->where('Category','Mevent')->get();
        $Mvat = master_document::select('name_th','id','lavel')->where('status', '1')->where('Category','Mvat')->get();
        $Freelancer_member = Freelancer_Member::select('First_name','id','Profile_ID','Last_name')->where('status', '1')->get();
        $Company = companys::select('Company_Name','id','Profile_ID')->get();
        return view('dummy_quotation.create',compact('DummyNo','Company','Mevent','Freelancer_member','Issue_date','Valid_Until','Mvat'));
    }
    public function Contactcreate($companyID)
    {
        $company =  companys::where('Profile_ID',$companyID)->first();
        $Company_typeID=$company->Company_type;
        $CityID=$company->City;
        $amphuresID = $company->Amphures;
        $TambonID = $company->Tambon;
        $Company_type = master_document::where('id',$Company_typeID)->select('name_th','id')->first();
        $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
        $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
        $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
        $company_fax = company_fax::where('Profile_ID',$companyID)->where('Sequence','main')->first();
        if (!$company_fax) {
            $company_fax = '-';
        }
        $company_phone = company_phone::where('Profile_ID',$companyID)->where('Sequence','main')->first();

        $Contact_names = representative::where('Company_ID', $companyID)
            ->where('status', 1)
            ->orderby('id', 'desc')
            ->first();
        $phone=$Contact_names->Profile_ID;
        $Contact_phones = representative_phone::where('Profile_ID',$phone)->where('Sequence','main')->first();
        return response()->json([
            'data' => $Contact_names,
            'Contact_phones' => $Contact_phones,
            'company'=>$company,
            'company_phone'=>$company_phone,
            'company_fax'=>$company_fax,
            'Company_type'=>$Company_type,
            'province'=>$provinceNames,
            'amphures'=>$amphuresID,
            'Tambon'=>$TambonID,
        ]);
    }

    public function save(Request $request){
        try {

            $preview=$request->preview;
            $Quotation_IDcheck =$request->DummyNo;
            $adult=$request->Adult;
            $children=$request->Children;
            $userid = Auth::user()->id;
            $IDquotation = dummy_quotation::where('DummyNo',$Quotation_IDcheck)->first();
            if ($preview ==1) {
                $data = $request->all();

                $company= $request->Company;
                $eventformat= $request->Mevent;
                $Company_ID = companys::where('Profile_ID',$company)->first();

                $Company_typeID=$Company_ID->Company_type;
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="บริษัทจำกัด") {
                    $comtypefullname = "บริษัท ". $Company_ID->Company_Name . " จำกัด";
                }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                    $comtypefullname = "บริษัท ". $Company_ID->Company_Name . " จำกัด (มหาชน)";
                }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                    $comtypefullname = "ห้างหุ้นส่วนจำกัด ". $Company_ID->Company_Name ;
                }else {
                    $comtypefullname = $Company_ID->Company_Name;
                }
                $CityID=$Company_ID->City;
                $amphuresID = $Company_ID->Amphures;
                $TambonID = $Company_ID->Tambon;
                $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
                $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
                $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
                $company_fax = company_fax::where('Profile_ID',$company)->where('Sequence','main')->first();
                if ($company_fax) {
                    $Fax_number =  $company_fax->Fax_number;
                }else{
                    $Fax_number = '-';
                }
                $company_phone = company_phone::where('Profile_ID',$company)->where('Sequence','main')->first();
                $Contact_name = representative::where('Company_ID',$company)->where('status',1)->first();
                $Contact_phone = representative_phone::where('Company_ID',$company)->where('Sequence','main')->first();
                $eventformat = master_document::where('id',$eventformat)->select('name_th','id')->first();
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
                $Products = Arr::wrap($request->input('ProductIDmain'));
                $quantities = $request->input('Quantitymain', []); // ตัวอย่างใช้ 'pricetotal' เป็น quantity
                $discounts = $request->input('discountmain', []);
                $priceUnits = $request->input('priceproductmain', []);
                $productItems = [];
                $totaldiscount = [];
                foreach ($Products as $index => $productID) {

                    if (count($quantities) === count($priceUnits) && count($priceUnits) === count($discounts)) {
                        $totalPrices = []; // เปลี่ยนจากตัวแปรเดียวเป็น array เพื่อเก็บผลลัพธ์แต่ละรายการ
                        $discountedPrices = [];
                        $discountedPricestotal = [];
                        $totaldiscount = [];
                        // คำนวณราคาสำหรับแต่ละรายการ
                        for ($i = 0; $i < count($quantities); $i++) {
                            $quantity = intval($quantities[$i]);
                            $priceUnit = floatval(str_replace(',', '', $priceUnits[$i]));
                            $discount = floatval($discounts[$i]);

                            $totaldiscount0 = (($priceUnit * $discount)/100);
                            $totaldiscount[] = $totaldiscount0;

                            $totalPrice = ($quantity * $priceUnit);
                            $totalPrices[] = $totalPrice;

                            $discountedPrice = (($totalPrice * $discount )/ 100);
                            $discountedPrices[] = $priceUnit-$totaldiscount0;

                            $discountedPriceTotal = $totalPrice - $discountedPrice;
                            $discountedPricestotal[] = $discountedPriceTotal;
                        }
                    }
                    // dd( $priceUnit,$discountedPrices);

                    $items = master_product_item::where('Product_ID', $productID)->get();
                    $totalguest = $request->PaxToTalall;

                    $QuotationVat= $request->Mvat;
                    $Mvat = master_document::where('id',$QuotationVat)->where('status', '1')->where('Category','Mvat')->select('name_th','id')->first();
                    foreach ($items as $item) {
                        // ตรวจสอบและกำหนดค่า quantity และ discount
                        $quantity = isset($quantities[$index]) ? $quantities[$index] : 0;
                        $discount = isset($discounts[$index]) ? $discounts[$index] : 0;
                        $totalPrices = isset($totalPrices[$index]) ? $totalPrices[$index] : 0;
                        $discountedPrices = isset($discountedPrices[$index]) ? $discountedPrices[$index] : 0;
                        $discountedPricestotal = isset($discountedPricestotal[$index]) ? $discountedPricestotal[$index] : 0;
                        $totaldiscount = isset($totaldiscount[$index]) ? $totaldiscount[$index] : 0;
                        // รวมข้อมูลของผลิตภัณฑ์เข้ากับ quantity และ discount
                        $productItems[] = [
                            'product' => $item,
                            'quantity' => $quantity,
                            'discount' => $discount,
                            'totalPrices'=>$totalPrices,
                            'discountedPrices'=>$discountedPrices,
                            'discountedPricestotal'=>$discountedPricestotal,
                            'totaldiscount'=>$totaldiscount,
                        ];

                    }
                }
                // dd($productItems);
                $totalAmount = 0;
                $totalPrice = 0;
                $subtotal = 0;
                $beforeTax = 0;
                $AddTax = 0;
                $Nettotal =0;
                $totalaverage=0;

                $SpecialDistext = $request->SpecialDis;
                $SpecialDis = floatval($SpecialDistext);
                if ($Mvat->id == 50) {
                    foreach ($productItems as $item) {
                        $totalPrice += $item['totalPrices'];
                        $totalAmount += $item['discountedPricestotal'];
                        $subtotal = $totalAmount-$SpecialDis;
                        $beforeTax = $subtotal/1.07;
                        $AddTax = $subtotal-$beforeTax;
                        $Nettotal = $subtotal;
                        $totalaverage =$Nettotal/$totalguest;
                    }
                }
                elseif ($Mvat->id == 51) {
                    foreach ($productItems as $item) {
                        $totalPrice += $item['totalPrices'];
                        $totalAmount += $item['discountedPricestotal'];
                        $subtotal = $totalAmount-$SpecialDis;
                        $Nettotal = $subtotal;
                        $totalaverage =$Nettotal/$totalguest;
                    }
                }
                elseif ($Mvat->id == 52) {
                    foreach ($productItems as $item) {
                        $totalPrice += $item['totalPrices'];
                        $totalAmount += $item['discountedPricestotal'];
                        $subtotal = $totalAmount-$SpecialDis;
                        $AddTax = $subtotal*7/100;
                        $Nettotal = $subtotal+$AddTax;
                        $totalaverage =$Nettotal/$totalguest;
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
                        $totalaverage =$Nettotal/$totalguest;
                    }
                }
                $unit = master_unit::where('status',1)->get();
                $quantity = master_quantity::where('status',1)->get();
                $pagecount = count($productItems);
                $page = $pagecount/10;

                $page_item = 1;
                if ($page > 1.1 && $page < 2.1) {
                    $page_item += 1;

                } elseif ($page > 1.1) {
                $page_item = 1 + $page > 1.1 ? ceil($page) : 1;
                }
                $id = $request->Quotation_ID;
                $protocol = $request->secure() ? 'https' : 'http';
                $linkQR = $protocol . '://' . $request->getHost() . "/Quotation/Quotation/cover/document/PDF/$id?page_shop=" . $request->input('page_shop');

                // Generate the QR code as PNG
                $qrCodeImage = QrCode::format('svg')->size(200)->generate($linkQR);
                $qrCodeBase64 = base64_encode($qrCodeImage);
                $IssueDate = $request->IssueDate;
                $Expiration = $request->Expiration;
                $Checkin = $request->Checkin;
                $Checkout = $request->Checkout;
                $Day = $request->Day;
                $Night = $request->Night;
                $comment = $request->comment;
                if ($Checkin) {
                    $checkin = Carbon::parse($Checkin)->format('d/m/Y');
                    $checkout = Carbon::parse($Checkout)->format('d/m/Y');
                }else{
                    $checkin = '-';
                    $checkout = '-';
                }
                $user = User::where('id',$userid)->select('id','name')->first();
                $Mevent= $request->Mevent;
                $data = [
                    'date' => $date,
                    'Checkin'=>$checkin,
                    'Checkout'=>$checkout,
                    'IssueDate'=>$IssueDate,
                    'day'=>$Day,
                    'night'=>$Night,
                    'Expiration'=>$Expiration,
                    'comtypefullname'=>$comtypefullname,
                    'Company_ID'=>$Company_ID,
                    'TambonID'=>$TambonID,
                    'CityID'=>$CityID,
                    'amphuresID'=>$amphuresID,
                    'provinceNames'=>$provinceNames,
                    'company_fax'=>$Fax_number,
                    'company_phone'=>$company_phone,
                    'Contact_name'=>$Contact_name,
                    'Contact_phone'=>$Contact_phone,
                    'Quotation'=>$Quotation_IDcheck,
                    'eventformat'=>$eventformat,
                    'Reservation_show'=>$Reservation_show,
                    'Paymentterms'=>$Paymentterms,
                    'note'=>$note,
                    'Cancellations'=>$Cancellations,
                    'Complimentary'=>$Complimentary,
                    'All_rights_reserved'=>$All_rights_reserved,
                    'totalAmount'=>$totalAmount,
                    'SpecialDis'=>$SpecialDis,
                    'subtotal'=>$subtotal,
                    'beforeTax'=>$beforeTax,
                    'Nettotal'=>$Nettotal,
                    'totalguest'=>$totalguest,
                    'totalaverage'=>$totalaverage,
                    'AddTax'=>$AddTax,
                    'productItems'=>$productItems,
                    'unit'=>$unit,
                    'quantity'=>$quantity,
                    'page_item'=>$page_item,
                    'page'=>$pagecount,
                    'qrCodeBase64'=>$qrCodeBase64,
                    'Mvat'=>$Mvat,
                    'comment'=>$comment,
                    'adult'=>$adult,
                    'children'=>$children,
                    'user'=>$user,
                    'Mevent'=>$Mevent,
                ];
                $view= $template->name;
                $pdf = FacadePdf::loadView('quotationpdf.preview',$data);
                return $pdf->stream();
            }
            if ($IDquotation) {
                $currentDate = Carbon::now();
                $ID = 'DD-';
                $formattedDate = Carbon::parse($currentDate);       // วันที่
                $month = $formattedDate->format('m'); // เดือน
                $year = $formattedDate->format('y');
                $lastRun = dummy_quotation::latest()->first();
                $nextNumber = 1;
                $lastRunid = $lastRun->id;
                $nextNumber = $lastRunid + 1;
                $newRunNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                $Quotation_ID = $ID.$year.$month.$newRunNumber;

            }else{
                $Quotation_ID =$Quotation_IDcheck;
            }
            $data = $request->all();
            {
                $quantities = $request->input('Quantitymain', []); // ตัวอย่างใช้ 'pricetotal' เป็น quantity
                $discounts = $request->input('discountmain', []);
                $priceUnits = $request->input('priceproductmain', []);

                $discounts = array_map(function($value) {
                    return ($value !== null) ? $value : "0";
                }, $discounts);

                if (count($quantities) === count($priceUnits) && count($priceUnits) === count($discounts)) {
                    $totalPrices = []; // เปลี่ยนจากตัวแปรเดียวเป็น array เพื่อเก็บผลลัพธ์แต่ละรายการ
                    $discountedPrices = [];
                    $discountedPricestotal = [];
                    // คำนวณราคาสำหรับแต่ละรายการ
                    for ($i = 0; $i < count($quantities); $i++) {
                        $quantity = intval($quantities[$i]);
                        $priceUnit = floatval(str_replace(',', '', $priceUnits[$i]));
                        $discount = floatval($discounts[$i]);

                        $totalPrice = ($quantity * $priceUnit);
                        $totalPrices[] = $totalPrice;

                        $discountedPrice = (($totalPrice * $discount )/ 100);
                        $discountedPrices[] = $discountedPrice;

                        $discountedPriceTotal = $totalPrice - $discountedPrice;
                        $discountedPricestotal[] = $discountedPriceTotal;
                    }
                }
                foreach ($priceUnits as $key => $price) {
                    $priceUnits[$key] = str_replace(array(',', '.00'), '', $price);
                }
                $Products=$request->input('ProductIDmain');
                $pax=$request->input('pax');
                $productsArray = [];

                foreach ($Products as $index => $ProductID) {
                    $saveProduct = [
                        'Quotation_ID' => $Quotation_ID,
                        'Company_ID' => $request->Company,
                        'Product_ID' => $ProductID,
                        'pax' => $pax[$index] ?? 0,
                        'Issue_date' => $request->IssueDate,
                        'discount' => $discounts[$index],
                        'priceproduct' => $priceUnits[$index],
                        'netpriceproduct' => $discountedPricestotal[$index],
                        'totaldiscount' => $discountedPrices[$index],
                        'ExpirationDate' => $request->Expiration,
                        'freelanceraiffiliate' => $request->Freelancer_member,
                        'Quantity' => $quantities[$index],
                        'Document_issuer' => $userid,
                    ];

                    $productsArray[] = $saveProduct;
                }

                $DummyNo = $request->DummyNo;
                $IssueDate = $request->IssueDate;
                $Expiration = $request->Expiration;
                $CompanyID = $request->Company;
                $Company_Contact = $request->Company_Contact;
                $Adult = $request->Adult;
                $Children = $request->Children;
                $Mevent = $request->Mevent;
                $Mvat = $request->Mvat;
                $DiscountAmount = $request->SpecialDiscount;
                $Head = 'รายการ';

                if ($productsArray) {
                    $products['products'] =$productsArray;
                    $productsArray = $products['products']; // ใช้ array ที่คุณมีอยู่แล้ว
                    $productData = [];

                    foreach ($productsArray as $product) {
                        $productID = $product['Product_ID'];

                        // ค้นหาข้อมูลในฐานข้อมูลจาก Product_ID
                        $productDetails = master_product_item::LeftJoin('master_units', 'master_product_items.unit', '=', 'master_units.id')
                            ->where('master_product_items.Product_ID', $productID)
                            ->select('master_product_items.*', 'master_units.name_th as unit_name')
                            ->first();

                        $ProductName = $productDetails->name_en;
                        $unitName = $productDetails->unit_name;

                        if ($productDetails) {
                            $productData[] = [
                                'Product_ID' => $productID,
                                'Quantity' => $product['Quantity'],
                                'netpriceproduct' => $product['netpriceproduct'],
                                'Product_Name' => $ProductName,
                                'Product_Unit' => $unitName, // หรือระบุฟิลด์ที่ต้องการจาก $productDetails
                            ];
                        }
                    }
                }

                $formattedProductData = [];

                foreach ($productData as $product) {

                    $formattedProductData[] = 'Description : ' . $product['Product_Name'] . ' , ' . 'Quantity : ' . $product['Quantity'] .' '. $product['Product_Unit'] . ' , ' . 'Price Product : ' . $product['netpriceproduct'];
                }

                if ($DummyNo) {
                    $QuotationID = 'Dummy Proposal ID : '.$DummyNo;
                }
                if ($IssueDate) {
                    $IssueDate = 'Issue Date : '.$IssueDate;
                }
                if ($Expiration) {
                    $Expiration = 'Expiration Date : '.$Expiration;
                }
                $Company_Name = null;
                $Contact_Name = null;
                if ($CompanyID) {
                    $Company = companys::where('Profile_ID',$CompanyID)->first();
                    $Company_Name = 'บริษัท : '.$Company->Company_Name;
                    $representative = representative::where('Company_ID',$CompanyID)->first();
                    $Contact_Name = 'ตัวแทน : '.$representative->First_name.' '.$representative->Last_name;
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
                $datacompany = '';

                $variables = [$QuotationID, $IssueDate, $Expiration, $Company_Name, $Contact_Name,$nameevent,$namevat,$Head];

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
                $save->Company_ID = $Quotation_ID;
                $save->type = 'Create';
                $save->Category = 'Create :: Dummy Proposal';
                $save->content =$datacompany;
                $save->save();
            }
            $Products=$request->input('ProductIDmain');
            if ($Products == null) {
                return redirect()->back()->with('error', 'กรอกข้อมูลไม่ครบ');
            }
            $SpecialDiscount= $request->SpecialDiscount;
            $save = new dummy_quotation();
            $save->DummyNo = $Quotation_ID;
            $save->Company_ID = $request->Company;
            $save->company_contact = $request->Company_Contact;
            $save->checkin = $request->Checkin;
            $save->checkout = $request->Checkout;
            $save->TotalPax = $request->PaxToTalall;
            $save->day = $request->Day;
            $save->night = $request->Night;
            $save->adult = $request->Adult;
            $save->children = $request->Children;
            $save->ComRateCode = $request->Company_Rate_Code;
            $save->freelanceraiffiliate = $request->Freelancer_member;
            $save->commissionratecode = $request->Company_Commission_Rate_Code;
            $save->eventformat = $request->Mevent;
            $save->vat_type = $request->Mvat;
            $save->issue_date = $request->IssueDate;
            $save->Expirationdate = $request->Expiration;
            $save->Operated_by = $userid;
            $save->SpecialDiscount=$SpecialDiscount;
            $save->save();

            //-----------------------------ส่วน product
            $quantities = $request->input('Quantitymain', []); // ตัวอย่างใช้ 'pricetotal' เป็น quantity
            $discounts = $request->input('discountmain', []);
            $priceUnits = $request->input('priceproductmain', []);
            $discounts = array_map(function($value) {
                return ($value !== null) ? $value : "0";
            }, $discounts);

            if (count($quantities) === count($priceUnits) && count($priceUnits) === count($discounts)) {
                $totalPrices = []; // เปลี่ยนจากตัวแปรเดียวเป็น array เพื่อเก็บผลลัพธ์แต่ละรายการ
                $discountedPrices = [];
                $discountedPricestotal = [];
                // คำนวณราคาสำหรับแต่ละรายการ
                for ($i = 0; $i < count($quantities); $i++) {
                    $quantity = intval($quantities[$i]);
                    $priceUnit = floatval(str_replace(',', '', $priceUnits[$i]));
                    $discount = floatval($discounts[$i]);

                    $totalPrice = ($quantity * $priceUnit);
                    $totalPrices[] = $totalPrice;

                    $discountedPrice = (($totalPrice * $discount )/ 100);
                    $discountedPrices[] = $discountedPrice;

                    $discountedPriceTotal = $totalPrice - $discountedPrice;
                    $discountedPricestotal[] = $discountedPriceTotal;
                }
                $totalDiscountedPrice = array_sum($discountedPricestotal);
                $beforTax = $totalDiscountedPrice/1.07;
                $AddTax = $totalDiscountedPrice - $beforTax;
                $dummyId = $save->id;
                $ID = 'DD-';
                $currentDate = Carbon::now();
                $formattedDate = Carbon::parse($currentDate);       // วันที่
                $month = $formattedDate->format('m'); // เดือน
                $year = $formattedDate->format('y');
                $newRunNumber = str_pad($dummyId, 4, '0', STR_PAD_LEFT);
                $Quotation_IDnew = $ID.$year.$month.$newRunNumber;
                $vat_type = $request->Mvat;
                $saveid = dummy_quotation::find($dummyId);
                $saveid->DummyNo = $Quotation_IDnew;
                if ($vat_type == 50 || $vat_type == 52) {
                    $saveid->AddTax = $AddTax;
                }
                $saveid->Nettotal = $totalDiscountedPrice;
                $saveid->save();
            }
            foreach ($priceUnits as $key => $price) {
                $priceUnits[$key] = str_replace(array(',', '.00'), '', $price);
            }
            $pax=$request->input('pax');
            if ($Products !== null) {
                foreach ($Products as $index => $ProductID) {
                    $saveProduct = new document_dummy_quotation();
                    $saveProduct->Quotation_ID = $Quotation_IDnew;
                    $saveProduct->Company_ID = $request->Company;
                    $saveProduct->Product_ID = $ProductID;
                    $saveProduct->Issue_date = $request->IssueDate;
                    $paxValue = $pax[$index] ?? 0;
                    $saveProduct->pax = $paxValue;
                    $saveProduct->discount =$discounts[$index];
                    $saveProduct->priceproduct =$priceUnits[$index];
                    $saveProduct->netpriceproduct =$discountedPricestotal[$index];
                    $saveProduct->totaldiscount =$discountedPrices[$index];
                    $saveProduct->ExpirationDate = $request->Expiration;
                    $saveProduct->freelanceraiffiliate = $request->Freelancer_member;
                    $saveProduct->Quantity = $quantities[$index];
                    $saveProduct->Document_issuer = $userid;
                    $saveProduct->save();

                }

                return redirect()->route('DummyQuotation.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
            }else{
            $delete = dummy_quotation::find($id);
            $delete->delete();
                return redirect()->route('DummyQuotation.index')->with('success', 'ใบเสนอราคายังไม่ถูกสร้าง');
            }
        } catch (\Exception $e) {
            // return response()->json(['error' => 'Error updating status.'], 500);
            // return redirect()->route('DummyQuotation.index')->with(['error' => true, 'message' => 'Document save error.'],500);
            // return $e->getMessage();
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function edit($id)
    {
        $Quotation = dummy_quotation::where('id', $id)->first();
        $QuotationID= $Quotation->DummyNo;
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
        if (!$company_fax) {
            $company_fax = '-';
        }
        $company_phone = company_phone::where('Profile_ID',$Company_ID)->where('Sequence','main')->first();
        $Contact_name = representative::where('Company_ID',$Company_ID)->where('id',$contact)->where('status',1)->first();
        $profilecontact = $Contact_name->Profile_ID;
        $Contact_phone = representative_phone::where('Company_ID',$Company_ID)->where('Profile_ID',$profilecontact)->where('Sequence','main')->first();
        $selectproduct = document_dummy_quotation::where('Quotation_ID', $QuotationID)->get();
        $unit = master_unit::where('status',1)->get();
        $quantity = master_quantity::where('status',1)->get();
        return view('dummy_quotation.edit',compact('Quotation','Freelancer_member','Company','Mevent','Mvat','Contact_name','comtypefullname','CompanyID'
        ,'TambonID','amphuresID','CityID','provinceNames','company_fax','company_phone','Contact_phone','selectproduct','unit','quantity','QuotationID'));
    }
    public function update(Request $request ,$id)
    {
        $data = $request->all();
        $preview=$request->preview;
        if ($preview == 1) {
            $data = $request->all();
            $adult=$request->Adult;
            $children=$request->Children;
            $userid = Auth::user()->id;
            $Quotation_IDcheck=$request->Quotation_ID;
            $company= $request->Company;
            $eventformat= $request->Mevent;
            $Company_ID = companys::where('Profile_ID',$company)->first();
            $Company_typeID=$Company_ID->Company_type;
            $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
            if ($comtype->name_th =="บริษัทจำกัด") {
                $comtypefullname = "บริษัท ". $Company_ID->Company_Name . " จำกัด";
            }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                $comtypefullname = "บริษัท ". $Company_ID->Company_Name . " จำกัด (มหาชน)";
            }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                $comtypefullname = "ห้างหุ้นส่วนจำกัด ". $Company_ID->Company_Name ;
            }else {
                $comtypefullname = $Company_ID->Company_Name;
            }
            $CityID=$Company_ID->City;
            $amphuresID = $Company_ID->Amphures;
            $TambonID = $Company_ID->Tambon;
            $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
            $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
            $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
            $company_fax = company_fax::where('Profile_ID',$company)->where('Sequence','main')->first();
            if ($company_fax) {
                $Fax_number =  $company_fax->Fax_number;
            }else{
                $Fax_number = '-';
            }
            $company_phone = company_phone::where('Profile_ID',$company)->where('Sequence','main')->first();
            $Contact_name = representative::where('Company_ID',$company)->where('status',1)->first();
            $Contact_phone = representative_phone::where('Company_ID',$company)->where('Sequence','main')->first();
            $eventformat = master_document::where('id',$eventformat)->select('name_th','id')->first();
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
            $Products = Arr::wrap($request->input('ProductIDmain'));
            $quantities = $request->input('Quantitymain', []); // ตัวอย่างใช้ 'pricetotal' เป็น quantity
            $discounts = $request->input('discountmain', []);
            $priceUnits = $request->input('priceproductmain', []);
            $productItems = [];
            $totaldiscount = [];
            foreach ($Products as $index => $productID) {

                if (count($quantities) === count($priceUnits) && count($priceUnits) === count($discounts)) {
                    $totalPrices = []; // เปลี่ยนจากตัวแปรเดียวเป็น array เพื่อเก็บผลลัพธ์แต่ละรายการ
                    $discountedPrices = [];
                    $discountedPricestotal = [];
                    $totaldiscount = [];

                    // คำนวณราคาสำหรับแต่ละรายการ
                    for ($i = 0; $i < count($quantities); $i++) {
                        $quantity = intval($quantities[$i]);
                        $priceUnit = floatval(str_replace(',', '', $priceUnits[$i]));
                        $discount = floatval($discounts[$i]);


                        $totaldiscount0 = (($priceUnit * $discount)/100);
                        $totaldiscount[] = $totaldiscount0;

                        $totalPrice = ($quantity * $priceUnit);
                        $totalPrices[] = $totalPrice;

                        $discountedPrice = (($totalPrice * $discount )/ 100);
                        $discountedPrices[] = $priceUnit-$totaldiscount0;

                        $discountedPriceTotal = $totalPrice - $discountedPrice;
                        $discountedPricestotal[] = $discountedPriceTotal;
                    }
                }
                // dd( $priceUnit,$discountedPrices);

                $items = master_product_item::where('Product_ID', $productID)->get();
                $totalguest = $request->PaxToTalall;

                $QuotationVat= $request->Mvat;
                $Mvat = master_document::where('id',$QuotationVat)->where('status', '1')->where('Category','Mvat')->select('name_th','id')->first();
                foreach ($items as $item) {
                    // ตรวจสอบและกำหนดค่า quantity และ discount
                    $quantity = isset($quantities[$index]) ? $quantities[$index] : 0;
                    $discount = isset($discounts[$index]) ? $discounts[$index] : 0;
                    $totalPrices = isset($totalPrices[$index]) ? $totalPrices[$index] : 0;
                    $discountedPrices = isset($discountedPrices[$index]) ? $discountedPrices[$index] : 0;
                    $discountedPricestotal = isset($discountedPricestotal[$index]) ? $discountedPricestotal[$index] : 0;
                    $totaldiscount = isset($totaldiscount[$index]) ? $totaldiscount[$index] : 0;
                    // รวมข้อมูลของผลิตภัณฑ์เข้ากับ quantity และ discount
                    $productItems[] = [
                        'product' => $item,
                        'quantity' => $quantity,
                        'discount' => $discount,
                        'totalPrices'=>$totalPrices,
                        'discountedPrices'=>$discountedPrices,
                        'discountedPricestotal'=>$discountedPricestotal,
                        'totaldiscount'=>$totaldiscount,
                    ];

                }
            }
            // dd($productItems);
            $totalAmount = 0;
            $totalPrice = 0;
            $subtotal = 0;
            $beforeTax = 0;
            $AddTax = 0;
            $Nettotal =0;
            $totalaverage=0;

            $SpecialDistext = $request->SpecialDis;
            $SpecialDis = floatval($SpecialDistext);
            if ($Mvat->id == 50) {
                foreach ($productItems as $item) {
                    $totalPrice += $item['totalPrices'];
                    $totalAmount += $item['discountedPricestotal'];
                    $subtotal = $totalAmount-$SpecialDis;
                    $beforeTax = $subtotal/1.07;
                    $AddTax = $subtotal-$beforeTax;
                    $Nettotal = $subtotal;
                    $totalaverage =$Nettotal/$totalguest;
                }
            }
            elseif ($Mvat->id == 51) {
                foreach ($productItems as $item) {
                    $totalPrice += $item['totalPrices'];
                    $totalAmount += $item['discountedPricestotal'];
                    $subtotal = $totalAmount-$SpecialDis;
                    $Nettotal = $subtotal;
                    $totalaverage =$Nettotal/$totalguest;
                }
            }
            elseif ($Mvat->id == 52) {
                foreach ($productItems as $item) {
                    $totalPrice += $item['totalPrices'];
                    $totalAmount += $item['discountedPricestotal'];
                    $subtotal = $totalAmount-$SpecialDis;
                    $AddTax = $subtotal*7/100;
                    $Nettotal = $subtotal+$AddTax;
                    $totalaverage =$Nettotal/$totalguest;
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
                    $totalaverage =$Nettotal/$totalguest;
                }
            }
            $unit = master_unit::where('status',1)->get();
            $quantity = master_quantity::where('status',1)->get();
            $pagecount = count($productItems);
            $page = $pagecount/10;

            $page_item = 1;
            if ($page > 1.1 && $page < 2.1) {
                $page_item += 1;

            } elseif ($page > 1.1) {
            $page_item = 1 + $page > 1.1 ? ceil($page) : 1;
            }
            $id = $request->Quotation_ID;
            $protocol = $request->secure() ? 'https' : 'http';
            $linkQR = $protocol . '://' . $request->getHost() . "/Quotation/Quotation/cover/document/PDF/$id?page_shop=" . $request->input('page_shop');

            // Generate the QR code as PNG
            $qrCodeImage = QrCode::format('svg')->size(200)->generate($linkQR);
            $qrCodeBase64 = base64_encode($qrCodeImage);
            $IssueDate = $request->IssueDate;
            $Expiration = $request->Expiration;
            $Checkin = $request->Checkin;
            $Checkout = $request->Checkout;
            $Day = $request->Day;
            $Night = $request->Night;
            $comment = $request->comment;
            if ($Checkin) {
                $checkin = Carbon::parse($Checkin)->format('d/m/Y');
                $checkout = Carbon::parse($Checkout)->format('d/m/Y');
            }else{
                $checkin = '-';
                $checkout = '-';
            }
            $user = User::where('id',$userid)->select('id','name')->first();
            $Mevent= $request->Mevent;
            $data = [
                'date' => $date,
                'Checkin'=>$checkin,
                'Checkout'=>$checkout,
                'IssueDate'=>$IssueDate,
                'day'=>$Day,
                'night'=>$Night,
                'Expiration'=>$Expiration,
                'comtypefullname'=>$comtypefullname,
                'Company_ID'=>$Company_ID,
                'TambonID'=>$TambonID,
                'CityID'=>$CityID,
                'amphuresID'=>$amphuresID,
                'provinceNames'=>$provinceNames,
                'company_fax'=>$Fax_number,
                'company_phone'=>$company_phone,
                'Contact_name'=>$Contact_name,
                'Contact_phone'=>$Contact_phone,
                'Quotation'=>$Quotation_IDcheck,
                'eventformat'=>$eventformat,
                'Reservation_show'=>$Reservation_show,
                'Paymentterms'=>$Paymentterms,
                'note'=>$note,
                'Cancellations'=>$Cancellations,
                'Complimentary'=>$Complimentary,
                'All_rights_reserved'=>$All_rights_reserved,
                'totalAmount'=>$totalAmount,
                'SpecialDis'=>$SpecialDis,
                'subtotal'=>$subtotal,
                'beforeTax'=>$beforeTax,
                'Nettotal'=>$Nettotal,
                'totalguest'=>$totalguest,
                'totalaverage'=>$totalaverage,
                'AddTax'=>$AddTax,
                'productItems'=>$productItems,
                'unit'=>$unit,
                'quantity'=>$quantity,
                'page_item'=>$page_item,
                'page'=>$pagecount,
                'qrCodeBase64'=>$qrCodeBase64,
                'Mvat'=>$Mvat,
                'comment'=>$comment,
                'adult'=>$adult,
                'children'=>$children,
                'user'=>$user,
                'Mevent'=>$Mevent,
            ];
            $view= $template->name;
            $pdf = FacadePdf::loadView('quotationpdf.preview',$data);
            return $pdf->stream();
        }
        try {

            {
                $quantities = $request->input('Quantitymain', []); // ตัวอย่างใช้ 'pricetotal' เป็น quantity
                $discounts = $request->input('discountmain', []);
                $priceUnits = $request->input('priceproductmain', []);
                $discounts = array_map(function($value) {
                    return ($value !== null) ? $value : "0";
                }, $discounts);

                if (count($quantities) === count($priceUnits) && count($priceUnits) === count($discounts)) {
                    $totalPrices = []; // เปลี่ยนจากตัวแปรเดียวเป็น array เพื่อเก็บผลลัพธ์แต่ละรายการ
                    $discountedPrices = [];
                    $discountedPricestotal = [];
                    // คำนวณราคาสำหรับแต่ละรายการ
                    for ($i = 0; $i < count($quantities); $i++) {
                        $quantity = intval($quantities[$i]);
                        $priceUnit = floatval(str_replace(',', '', $priceUnits[$i]));
                        $discount = floatval($discounts[$i]);

                        $totalPrice = ($quantity * $priceUnit);
                        $totalPrices[] = $totalPrice;

                        $discountedPrice = (($totalPrice * $discount )/ 100);
                        $discountedPrices[] = $discountedPrice;

                        $discountedPriceTotal = $totalPrice - $discountedPrice;
                        $discountedPricestotal[] = $discountedPriceTotal;
                    }
                }
                foreach ($priceUnits as $key => $price) {
                    $priceUnits[$key] = str_replace(array(',', '.00'), '', $price);
                }

                $Products = $request->input('ProductIDmain');
                $Productslast = $request->input('tr-select-main');
                $productsCount = is_array($Products) ? count($Products) : 0;
                $productslastCount = is_array($Productslast) ? count($Productslast) : 0;

                if ($productsCount > $productslastCount) {
                    $Productslast = null;
                }
                elseif ($Products === null) {
                    //ลบ
                    $Products = $Productslast;
                } elseif ($productsCount == $productslastCount) {
                    //ลบและเพิ่ม
                    // $Products = array_merge($Productslast,$Products);
                    $Products = $Products;
                }elseif ($productsCount != $productslastCount){
                    $Products = array_merge($Productslast,$Products);
                }

                $pax=$request->input('pax');
                $userid = Auth::user()->id;
                $productsArray = [];
                foreach ($Products as $index => $ProductID) {
                    $saveProduct = [
                        'Quotation_ID' => $request->Quotation_ID,
                        'Company_ID' => $request->Company,
                        'Product_ID' => $ProductID,
                        'pax' => $pax[$index] ?? 0,
                        'Issue_date' => $request->IssueDate,
                        'discount' => $discounts[$index],
                        'priceproduct' => $priceUnits[$index],
                        'netpriceproduct' => $discountedPricestotal[$index],
                        'totaldiscount' => $discountedPrices[$index],
                        'ExpirationDate' => $request->Expiration,
                        'freelanceraiffiliate' => $request->Freelancer_member,
                        'Quantity' => $quantities[$index],
                        'Document_issuer' => $userid,
                    ];

                    $productsArray[] = $saveProduct;
                }

                $data = $request->all();
                $datarequest = [
                    'Quotation_ID' => $data['Quotation_ID'] ?? null,
                    'issue_date' => $data['IssueDate'] ?? null,
                    'Expirationdate' => $data['Expiration'] ?? null,
                    'Company_ID' => $data['Company'] ?? null,
                    'company_contact' => $data['Company_Contact'] ?? null,
                    'checkin' => $data['Checkin'] ?? null,
                    'checkout' => $data['Checkout'] ?? null,
                    'day' => $data['Day'] ?? null,
                    'night' => $data['Night'] ?? null,
                    'adult' => $data['Adult'] ?? null,
                    'children' => $data['Children'] ?? null,
                    'comment' => $data['comment'] ?? null,
                    'eventformat' => $data['Mevent'] ?? null,
                    'vat_type' => $data['Mvat'] ?? null,
                    'SpecialDiscountBath' => $data['DiscountAmount'] ?? null,
                    'TotalPax' => $data['PaxToTalall'] ?? null,
                ];
                $datarequest['Products'] = $productsArray;
                $ProposalData = dummy_quotation::where('id',$id)->first();
                $ProposalID = $ProposalData->DummyNo;
                $ProposalProducts = document_dummy_quotation::where('Quotation_ID',$ProposalID)->get();
                $dataArray = $ProposalData->toArray();
                $dataArray['Products'] = $ProposalProducts->map(function($item) {
                    // ปรับแต่ง $item ที่ได้จากแต่ละแถว
                    unset($item['id'], $item['created_at'], $item['updated_at'], $item['SpecialDiscount']);
                    return $item;
                })->toArray();

                $keysToCompare = ['Quotation_ID', 'issue_date', 'Expirationdate', 'Company_ID', 'company_contact', 'checkin', 'checkout', 'day', 'night', 'adult', 'children', 'comment', 'eventformat', 'vat_type', 'SpecialDiscountBath', 'TotalPax', 'Products'];
                $differences = [];
                foreach ($keysToCompare as $key) {
                    if (isset($dataArray[$key]) && isset($datarequest[$key])) {
                        // Check if both values are arrays
                        if (is_array($dataArray[$key]) && is_array($datarequest[$key])) {
                            foreach ($dataArray[$key] as $index => $value) {
                                if (isset($datarequest[$key][$index])) {
                                    if ($value != $datarequest[$key][$index]) {
                                        $differences[$key][$index] = [
                                            'dataArray' => $value,
                                            'request' => $datarequest[$key][$index]
                                        ];
                                    }
                                } else {
                                    $differences[$key][$index] = [
                                        'dataArray' => $value,
                                        'request' => null
                                    ];
                                }
                            }
                            // Handle case where $datarequest has extra elements
                            foreach ($datarequest[$key] as $index => $value) {
                                if (!isset($dataArray[$key][$index])) {
                                    $differences[$key][$index] = [
                                        'dataArray' => null,
                                        'request' => $value
                                    ];
                                }
                            }
                        } else {
                            // Compare non-array values
                            if ($dataArray[$key] != $datarequest[$key]) {
                                $differences[$key] = [
                                    'dataArray' => $dataArray[$key],
                                    'request' => $datarequest[$key]
                                ];
                            }
                        }
                    } elseif (isset($dataArray[$key])) {
                        // Handle case where $datarequest does not have the key
                        $differences[$key] = [
                            'dataArray' => $dataArray[$key],
                            'request' => null
                        ];
                    } elseif (isset($datarequest[$key])) {
                        // Handle case where $dataArray does not have the key
                        $differences[$key] = [
                            'dataArray' => null,
                            'request' => $datarequest[$key]
                        ];
                    }
                }


                $dataArrayProductIds = collect($dataArray['Products'])->map(function ($item) {
                    return implode('|', [
                        $item['Product_ID'] ?? '',
                        $item['discount'] ?? '',
                        $item['Quantity'] ?? '',
                        $item['netpriceproduct'] ?? ''
                    ]);
                })->unique();

                // ดึงค่าจาก Request Products และแปลงเป็น string
                $requestProductIds = collect($datarequest['Products'])->map(function ($item) {
                    return implode('|', [
                        $item['Product_ID'] ?? '',
                        $item['discount'] ?? '',
                        $item['Quantity'] ?? '',
                        $item['netpriceproduct'] ?? ''
                    ]);
                })->unique();

                // หาค่าที่แตกต่าง
                $onlyInDataArray = $dataArrayProductIds->diff($requestProductIds)->values()->all();
                $onlyInRequest = $requestProductIds->diff($dataArrayProductIds)->values()->all();

                $onlyInDataArray = collect($onlyInDataArray)->map(function ($item) {
                    $parts = explode('|', $item);
                    return [
                        'Product_ID' => $parts[0],
                        'discount' => $parts[1],
                        'Quantity' => $parts[2],
                        'netpriceproduct' => $parts[3]
                    ];
                })->values()->all();

                $onlyInRequest = collect($onlyInRequest)->map(function ($item) {
                    $parts = explode('|', $item);
                    return [
                        'Product_ID' => $parts[0],
                        'discount' => $parts[1],
                        'Quantity' => $parts[2],
                        'netpriceproduct' => $parts[3]
                    ];
                })->values()->all();
                $onlyInDataArray = collect($onlyInDataArray);
                $onlyInRequest = collect($onlyInRequest);

                $extractedData = [];
                $extractedDataA = [];
                // วนลูปเพื่อดึงชื่อคีย์และค่าจาก differences
                foreach ($differences as $key => $value) {
                    if ($key === 'Products') {
                        // ถ้าเป็น Products ให้เก็บค่า request และ dataArray ที่แตกต่างกัน

                        $extractedData[$key] = $onlyInDataArray->toArray(); // ใช้ข้อมูลจาก $onlyInRequest
                        $extractedDataA[$key] = $onlyInRequest->toArray(); // ใช้ข้อมูลจาก $onlyInDataArray
                    } elseif (isset($value['request'][0])) {
                        // สำหรับคีย์อื่นๆ ให้เก็บค่าแรกจาก array
                        // $extractedData[$key] = $value['request'][0];
                        $extractedData[$key] = $value['request'][0];
                    } else {
                        // $extractedData[$key] = $value['request'] ?? null;
                        $extractedDataA[$key] = $value['dataArray'][0];
                    }
                }
                $Company_ID = $extractedData['Company_ID'] ?? null;
                $company_contact = $extractedData['company_contact'] ?? null;
                $checkin =  $extractedData['checkin'] ?? null;
                $checkout =  $extractedData['checkout'] ?? null;
                $day =  $extractedData['day'] ?? null;
                $night =  $extractedData['night'] ?? null;
                $adult =  $extractedData['adult'] ?? null;
                $children = $extractedData['children'] ?? null;
                $comment =  $extractedData['comment'] ?? null;
                $eventformat =  $extractedData['eventformat'] ?? null;
                $vat_type =  $extractedData['vat_type'] ?? null;
                $SpecialDiscountBath =  $extractedData['SpecialDiscountBath'] ?? null;
                $TotalPax =  $extractedData['TotalPax'] ?? null;
                $Products =  $extractedData['Products'] ?? null;
                $ProductsA =  $extractedDataA['Products'] ?? null;
                $issue_date =  $extractedDataA['issue_date'] ?? null;
                $Expirationdate =  $extractedDataA['Expirationdate'] ?? null;


                $comtypefullname = null;
                if ($Company_ID) {
                    $company =companys::where('Profile_ID',$Company_ID)->first();
                    $Company_Name = $company->Company_Name;
                    $comtypefullname = 'ชื่อบริษัท : ' . $Company_Name;
                }
                $Contactname = null;
                if ($company_contact) {
                    $Contact_name = representative::where('Company_ID',$Company_ID)->where('status',1)->first();
                    $Contactname = 'ชื่อผู้ติดต่อ : '.$Contact_name->First_name.' '.$Contact_name->Last_name;
                }
                $Checkin =null;
                if ($checkin || $checkout) {
                    $Checkin = 'Check in date : '.$checkin;
                    if ($checkin&&$checkout) {
                        $Checkin = 'Check in date : '.$checkin.' '.'Check out date : '.$checkout;
                    }elseif ($checkout) {
                        $Checkin = 'Check out date : '.$checkout;
                    }
                }
                $DAY =null;
                if ($day || $night) {
                    $DAY = 'Day : '.$day;
                    if ($day&&$night) {
                        $DAY = 'Day : '.$day.' '.'Night : '.$night;
                    }elseif ($night) {
                        $DAY = 'Night : '.$night;
                    }
                }
                $people =null;
                if ($adult || $children) {
                    $people = 'Adult : '.$adult;
                    if ($adult&&$children) {
                        $people = 'Adult : '.$adult.' '.'Children : '.$children;
                    }elseif ($children) {
                        $people = 'Children : '.$children;
                    }
                }
                $Comment = null;
                if ($comment) {
                    $Comment = 'comment : '.$comment;
                }
                $nameevent = null;
                if ($eventformat) {
                    $Mevent = master_document::where('id',$eventformat)->where('status', '1')->where('Category','Mevent')->first();
                    $nameevent = 'ประเภท : '.$Mevent->name_th;
                }
                $namevat = null;
                if ($vat_type) {
                    $Mvat = master_document::where('id',$vat_type)->where('status', '1')->where('Category','Mvat')->first();
                    $namevat = 'ประเภท VAT : '.$Mvat->name_th;
                }
                $discount = null;
                if ($SpecialDiscountBath) {
                    $discount = 'ส่วนลด : '.$SpecialDiscountBath;
                }
                $Pax = null;
                if ($TotalPax) {
                    $Pax = 'รวมความจุของห้องพัก : '.$TotalPax;
                }
                $issue_date = null;
                if ($issue_date) {
                    $issue_date = 'วันเริ่มใช้งานเอกสาร : '.$issue_date;
                }
                $Expirationdate = null;
                if ($Expirationdate) {
                    $Expirationdate = 'วันหมดอายุเอกสาร : '.$Expirationdate;
                }
               // กำหนดค่าเริ่มต้นให้กับตัวแปร
                $formattedProductData = [];
                $formattedProductDataA = [];

                // หาก $Products มีค่า
                if ($Products) {
                    $productData = [];
                    foreach ($Products as $product) {
                        $productID = $product['Product_ID'];

                        // ค้นหาข้อมูลในฐานข้อมูลจาก Product_ID
                        $productDetails = master_product_item::leftJoin('master_units', 'master_product_items.unit', '=', 'master_units.id')
                            ->where('master_product_items.Product_ID', $productID)
                            ->select('master_product_items.name_en as Product_Name', 'master_units.name_th as unit_name')
                            ->first();

                        if ($productDetails) {
                            $productData[] = [
                                'Product_ID' => $productID,
                                'Discount' => $product['discount'],
                                'Quantity' => $product['Quantity'],
                                'netpriceproduct' => $product['netpriceproduct'],
                                'Product_Name' => $productDetails->Product_Name,
                                'Product_Unit' => $productDetails->unit_name,
                            ];
                        }
                    }

                    // จัดรูปแบบข้อมูลของผลิตภัณฑ์
                    foreach ($productData as $product) {
                        $formattedProductData[] = 'ลบรายการ' . '+ ' . 'Description : ' . $product['Product_Name'] . ' , ' . 'Quantity : ' . $product['Quantity'] . ' ' . $product['Product_Unit'] . ' , ' . 'Discount : ' . $product['Discount'] . '% ' . ' , Price Product : ' . $product['netpriceproduct'];
                    }
                }

                // หาก $ProductsA มีค่า
                if ($ProductsA) {
                    $productDataA = [];
                    foreach ($ProductsA as $product) {
                        $productID = $product['Product_ID'];

                        // ค้นหาข้อมูลในฐานข้อมูลจาก Product_ID
                        $productDetails = master_product_item::leftJoin('master_units', 'master_product_items.unit', '=', 'master_units.id')
                            ->where('master_product_items.Product_ID', $productID)
                            ->select('master_product_items.name_en as Product_Name', 'master_units.name_th as unit_name')
                            ->first();

                        if ($productDetails) {
                            $productDataA[] = [
                                'Product_ID' => $productID,
                                'Discount' => $product['discount'],
                                'Quantity' => $product['Quantity'],
                                'netpriceproduct' => $product['netpriceproduct'],
                                'Product_Name' => $productDetails->Product_Name,
                                'Product_Unit' => $productDetails->unit_name,
                            ];
                        }
                    }

                    // จัดรูปแบบข้อมูลของผลิตภัณฑ์
                    foreach ($productDataA as $product) {
                        $formattedProductDataA[] = 'เพิ่มรายการ' . '+ ' . 'Description : ' . $product['Product_Name'] . ' , ' . 'Quantity : ' . $product['Quantity'] . ' ' . $product['Product_Unit'] . ' , ' . 'Discount : ' . $product['Discount'] . '% ' . ' , Price Product : ' . $product['netpriceproduct'];
                    }
                }
                $datacompany = '';

                $variables = [$comtypefullname, $Contactname,$issue_date, $Expirationdate, $Checkin, $DAY,$people,$nameevent,$namevat,$discount
                            ,$Pax,$Comment];

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

                $Quotation_ID=$request->Quotation_ID;
                $userids = Auth::user()->id;
                $save = new log_company();
                $save->Created_by = $userids;
                $save->Company_ID = $Quotation_ID;
                $save->type = 'Edit';
                $save->Category = 'Edit :: Dummy Proposal';
                $save->content =$datacompany;
                $save->save();
            }

            $preview = $request->preview;
            $Quotation_ID=$request->Quotation_ID;
            $userid = Auth::user()->id;
            $save =  dummy_quotation::find($id);
            $save->DummyNo = $Quotation_ID;
            $save->Company_ID = $request->Company;
            $save->company_contact = $request->Company_Contact;
            $save->checkin = $request->Checkin;
            $save->checkout = $request->Checkout;
            $save->TotalPax = $request->PaxToTalall;
            $save->day = $request->Day;
            $save->night = $request->Night;
            $save->adult = $request->Adult;
            $save->children = $request->Children;
            $save->ComRateCode = $request->Company_Rate_Code;
            $save->freelanceraiffiliate = $request->Freelancer_member;
            $save->commissionratecode = $request->Company_Commission_Rate_Code;
            $save->eventformat = $request->Mevent;
            $save->vat_type = $request->Mvat;
            $save->issue_date = $request->IssueDate;
            $save->Expirationdate = $request->Expiration;
            $save->status_document = 1;
            $save->Operated_by = $userid;
            $save->SpecialDiscount=$request->SpecialDiscount;
            $save->save();
            //-----------------------------ส่วน product-----------------------------
            $Products = $request->input('ProductIDmain');
            $Productslast = $request->input('tr-select-main');
            $productsCount = is_array($Products) ? count($Products) : 0;
            $productslastCount = is_array($Productslast) ? count($Productslast) : 0;

            if ($productsCount > $productslastCount) {
                $Productslast = null;
            }
            elseif ($Products === null) {
                //ลบ
                $Products = $Productslast;
            } elseif ($productsCount == $productslastCount) {
                //ลบและเพิ่ม
                // $Products = array_merge($Productslast,$Products);
                $Products = $Products;
            }elseif ($productsCount != $productslastCount){
                $Products = array_merge($Productslast,$Products);
            }

            $quantities = $request->input('Quantitymain', []); // ตัวอย่างใช้ 'pricetotal' เป็น quantity
            $discounts = $request->input('discountmain', []);
            $priceUnits = $request->input('priceproductmain', []);
            $discounts = array_map(function($value) {
                return ($value !== null) ? $value : "0";
            }, $discounts);

            if (count($quantities) === count($priceUnits) && count($priceUnits) === count($discounts)) {
                $totalPrices = []; // เปลี่ยนจากตัวแปรเดียวเป็น array เพื่อเก็บผลลัพธ์แต่ละรายการ
                $discountedPrices = [];
                $discountedPricestotal = [];
                // คำนวณราคาสำหรับแต่ละรายการ
                for ($i = 0; $i < count($quantities); $i++) {
                    $quantity = intval($quantities[$i]);
                    $priceUnit = floatval(str_replace(',', '', $priceUnits[$i]));
                    $discount = floatval($discounts[$i]);

                    $totalPrice = ($quantity * $priceUnit);
                    $totalPrices[] = $totalPrice;

                    $discountedPrice = (($totalPrice * $discount )/ 100);
                    $discountedPrices[] = $discountedPrice;

                    $discountedPriceTotal = $totalPrice - $discountedPrice;
                    $discountedPricestotal[] = $discountedPriceTotal;
                }
                $totalDiscountedPrice = array_sum($discountedPricestotal);
                $beforTax = $totalDiscountedPrice/1.07;
                $AddTax = $totalDiscountedPrice - $beforTax;
            }
            foreach ($priceUnits as $key => $price) {
                $priceUnits[$key] = str_replace(array(',', '.00'), '', $price);
            }
            $vat_type = $request->Mvat;
            $savetotal =  dummy_quotation::find($id);
            $savetotal->Nettotal = $totalDiscountedPrice;
            if ($vat_type == 50 || $vat_type == 52) {
                $savetotal->AddTax = $AddTax;
            }
            $savetotal->save();
            $pax=$request->input('pax');
            if ($Products !== null) {
                document_dummy_quotation::where('Quotation_ID',$Quotation_ID)->delete();
                foreach ($Products as $index => $ProductID) {
                    $saveProduct = new document_dummy_quotation();
                    $saveProduct->Quotation_ID = $Quotation_ID;
                    $saveProduct->Company_ID = $request->Company;
                    $saveProduct->Product_ID = $ProductID;
                    $saveProduct->Issue_date = $request->IssueDate;
                    $paxValue = $pax[$index] ?? 0;
                    $saveProduct->pax = $paxValue;
                    $saveProduct->discount =$discounts[$index];
                    $saveProduct->priceproduct =$priceUnits[$index];
                    $saveProduct->netpriceproduct =$discountedPricestotal[$index];
                    $saveProduct->totaldiscount =$discountedPrices[$index];
                    $saveProduct->ExpirationDate = $request->Expiration;
                    $saveProduct->freelanceraiffiliate = $request->Freelancer_member;
                    $saveProduct->Quantity = $quantities[$index];
                    $saveProduct->save();
                }
                return redirect()->route('DummyQuotation.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
            }elseif ($Products == null) {
                document_dummy_quotation::where('Quotation_ID',$Quotation_ID)->delete();
                foreach ($Products as $index => $ProductID) {
                    $saveProduct = new document_dummy_quotation();
                    $saveProduct->Quotation_ID = $Quotation_ID;
                    $saveProduct->Company_ID = $request->Company;
                    $saveProduct->Product_ID = $ProductID;
                    $saveProduct->Issue_date = $request->IssueDate;
                    $paxValue = $pax[$index] ?? 0;
                    $saveProduct->pax = $paxValue;
                    $saveProduct->discount =$discounts[$index];
                    $saveProduct->priceproduct =$priceUnits[$index];
                    $saveProduct->netpriceproduct =$discountedPricestotal[$index];
                    $saveProduct->totaldiscount =$discountedPrices[$index];
                    $saveProduct->ExpirationDate = $request->Expiration;
                    $saveProduct->freelanceraiffiliate = $request->Freelancer_member;
                    $saveProduct->Quantity = $quantities[$index];
                    $saveProduct->save();
                }
                return redirect()->route('DummyQuotation.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
            }
            else{
            $delete = dummy_quotation::find($id);
            $delete->delete();
                return redirect()->route('DummyQuotation.index')->with('success', 'ใบเสนอราคายังไม่ถูกสร้าง');
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function sheetpdf(Request $request ,$id) {
        $Quotation = dummy_quotation::where('id', $id)->first();
        if ($Quotation) {
            $QuotationID= $Quotation->DummyNo;
            $selectproduct = document_dummy_quotation::where('Quotation_ID', $QuotationID)->get();
            $SpecialDiscount = document_dummy_quotation::where('Quotation_ID', $QuotationID)->first();
        }else{
            $Quotation = Quotation::where('id', $id)->first();
            $QuotationID= $Quotation->Quotation_ID;
            $selectproduct = document_quotation::where('Quotation_ID', $QuotationID)->get();
            $SpecialDiscount = document_quotation::where('Quotation_ID', $QuotationID)->first();
        }
        $Company = $Quotation->Company_ID;
        $Quotation_ID = $Quotation->DummyNo;
        $eventformat = $Quotation->eventformat;
        $Company_ID = companys::where('Profile_ID',$Company)->first();
        $Company_typeID=$Company_ID->Company_type;
        $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
        $company_fax = company_fax::where('Profile_ID',$Company)->where('Sequence','main')->first();
        if ($company_fax) {
            $Fax_number =  $company_fax->Fax_number;
        }else{
            $Fax_number = '-';
        }
        $company_phone = company_phone::where('Profile_ID',$Company)->where('Sequence','main')->first();
        $Contact_name = representative::where('Company_ID',$Company)->where('status',1)->first();
        $Contact_phone = representative_phone::where('Company_ID',$Company)->where('Sequence','main')->first();
        $eventformat = master_document::where('id',$eventformat)->select('name_th','id')->first();
        if ($comtype->name_th =="บริษัทจำกัด") {
            $comtypefullname = "บริษัท ". $Company_ID->Company_Name . " จำกัด";
        }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
            $comtypefullname = "บริษัท ". $Company_ID->Company_Name . " จำกัด (มหาชน)";
        }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
            $comtypefullname = "ห้างหุ้นส่วนจำกัด ". $Company_ID->Company_Name ;
        }else {
            $comtypefullname = $Company_ID->Company_Name;
        }

        $CityID=$Company_ID->City;
        $amphuresID = $Company_ID->Amphures;
        $TambonID = $Company_ID->Tambon;
        $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
        $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
        $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
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
        $QuotationVat= $Quotation->vat_type;
        $Mvat = master_document::where('id',$QuotationVat)->where('status', '1')->where('Category','Mvat')->select('name_th','id')->first();

        $SpecialDis=$Quotation->SpecialDiscountBath;
        $Checkin = $Quotation->checkin;
        $Checkout = $Quotation->checkout;

        $Products = Arr::wrap($selectproduct->pluck('Product_ID')->toArray());
        $quantities = $selectproduct->pluck('Quantity')->toArray();
        $discounts = $selectproduct->pluck('discount')->toArray();
        $priceUnits = $selectproduct->pluck('priceproduct')->toArray();
        $productItems = [];
        $totaldiscount = [];
        foreach ($Products as $index => $productID) {

            if (count($quantities) === count($priceUnits) && count($priceUnits) === count($discounts)) {
                $totalPrices = []; // เปลี่ยนจากตัวแปรเดียวเป็น array เพื่อเก็บผลลัพธ์แต่ละรายการ
                $discountedPrices = [];
                $discountedPricestotal = [];
                $totaldiscount = [];
                // คำนวณราคาสำหรับแต่ละรายการ
                for ($i = 0; $i < count($quantities); $i++) {
                    $quantity = intval($quantities[$i]);
                    $priceUnit = floatval(str_replace(',', '', $priceUnits[$i]));
                    $discount = floatval($discounts[$i]);

                    $totaldiscount0 = (($priceUnit * $discount)/100);
                    $totaldiscount[] = $totaldiscount0;

                    $totalPrice = ($quantity * $priceUnit);
                    $totalPrices[] = $totalPrice;

                    $discountedPrice = (($totalPrice * $discount )/ 100);
                    $discountedPrices[] = $priceUnit-$totaldiscount0;

                    $discountedPriceTotal = $totalPrice - $discountedPrice;
                    $discountedPricestotal[] = $discountedPriceTotal;
                }
            }
            $items = master_product_item::where('Product_ID', $productID)->get();
            $QuotationVat= $Quotation->vat_type;
            $Mvat = master_document::where('id',$QuotationVat)->where('status', '1')->where('Category','Mvat')->select('name_th','id')->first();
            foreach ($items as $item) {
                // ตรวจสอบและกำหนดค่า quantity และ discount
                $quantity = isset($quantities[$index]) ? $quantities[$index] : 0;
                $discount = isset($discounts[$index]) ? $discounts[$index] : 0;
                $totalPrices = isset($totalPrices[$index]) ? $totalPrices[$index] : 0;
                $discountedPrices = isset($discountedPrices[$index]) ? $discountedPrices[$index] : 0;
                $discountedPricestotal = isset($discountedPricestotal[$index]) ? $discountedPricestotal[$index] : 0;
                $totaldiscount = isset($totaldiscount[$index]) ? $totaldiscount[$index] : 0;
                // รวมข้อมูลของผลิตภัณฑ์เข้ากับ quantity และ discount
                $productItems[] = [
                    'product' => $item,
                    'quantity' => $quantity,
                    'discount' => $discount,
                    'totalPrices'=>$totalPrices,
                    'discountedPrices'=>$discountedPrices,
                    'discountedPricestotal'=>$discountedPricestotal,
                    'totaldiscount'=>$totaldiscount,
                ];

            }
        }

        $totalAmount = 0;
        $totaldiscount = 0;
        $netprice=0;
        $totalPrice = 0;
        $vat=0;
        $total=0;
        $adult = $Quotation->adult;
        $children = $Quotation->children;
        $totalguest = 0;
        $totalguest = $adult + $children;
        $guest = $Quotation->TotalPax;
        $totalaverage=0;
        if ($Mvat->id == 50) {
            foreach ($selectproduct as $item) {
                $totalPrice +=  $item->priceproduct;
                $totalAmount += $item->netpriceproduct;
                $subtotal = $totalAmount-$SpecialDis;
                $beforeTax = $subtotal/1.07;
                $AddTax = $subtotal-$beforeTax;
                $Nettotal = $subtotal;
                $totalaverage =$Nettotal/$guest;
            }
        }
        elseif ($Mvat->id == 51) {
            foreach ($selectproduct as $item) {
                $totalPrice +=  $item->priceproduct;
                $totalAmount += $item->netpriceproduct;
                $subtotal = $totalAmount-$SpecialDis;
                $beforeTax = 0;
                $AddTax = 0;
                $Nettotal = $subtotal;
                $totalaverage =$Nettotal/$guest;
            }
        }
        elseif ($Mvat->id == 52) {
            foreach ($selectproduct as $item) {
                $totalPrice +=  $item->priceproduct;
                $totalAmount += $item->netpriceproduct;
                $subtotal = $totalAmount-$SpecialDis;
                $beforeTax = $subtotal/1.07;
                $AddTax = $subtotal*7/100;
                $Nettotal = $subtotal+$AddTax;
                $totalaverage =$Nettotal/$guest;
            }
        }else
        {
            foreach ($selectproduct as $item) {
                $totalPrice +=  $item->priceproduct;
                $totalAmount += $item->netpriceproduct;
                $subtotal = $totalAmount-$SpecialDis;
                $beforeTax = $subtotal/1.07;
                $AddTax = $subtotal-$beforeTax;
                $Nettotal = $subtotal;
                $totalaverage =$Nettotal/$guest;
            }
        }
        $protocol = $request->secure() ? 'https' : 'http';
        $linkQR = $protocol . '://' . $request->getHost() . "/Quotation/Quotation/cover/document/PDF/$id?page_shop=" . $request->input('page_shop');

        // Generate the QR code as PNG
        $qrCodeImage = QrCode::format('svg')->size(200)->generate($linkQR);
        $qrCodeBase64 = base64_encode($qrCodeImage);
        $unit = master_unit::where('status',1)->get();
        $quantity = master_quantity::where('status',1)->get();
        if ($Checkin) {
            $checkin = Carbon::parse($Checkin)->format('d/m/Y');
            $checkout = Carbon::parse($Checkout)->format('d/m/Y');
        }else{
            $checkin = '-';
            $checkout = '-';
        }
        $pagecount = count($selectproduct);
            $page = $pagecount/10;

            $page_item = 1;
            if ($page > 1.1 && $page < 2.1) {
                $page_item += 1;

            } elseif ($page > 1.1) {
            $page_item = 1 + $page > 1.1 ? ceil($page) : 1;
            }


        $data = [
            'date' => $date,
            'comtypefullname'=>$comtypefullname,
            'Company_ID'=>$Company_ID,
            'TambonID'=>$TambonID,
            'CityID'=>$CityID,
            'amphuresID'=>$amphuresID,
            'provinceNames'=>$provinceNames,
            'company_fax'=>$Fax_number,
            'company_phone'=>$company_phone,
            'Contact_name'=>$Contact_name,
            'Contact_phone'=>$Contact_phone,
            'Quotation'=>$Quotation,
            'eventformat'=>$eventformat,
            'Reservation_show'=>$Reservation_show,
            'Paymentterms'=>$Paymentterms,
            'note'=>$note,
            'Cancellations'=>$Cancellations,
            'Complimentary'=>$Complimentary,
            'All_rights_reserved'=>$All_rights_reserved,
            'productItems'=>$productItems,
            'unit'=>$unit,
            'quantity'=>$quantity,
            'totalAmount'=>$totalAmount,
            'SpecialDis'=>$SpecialDis,
            'subtotal'=>$subtotal,
            'beforeTax'=>$beforeTax,
            'AddTax'=>$AddTax,
            'Nettotal'=>$Nettotal,
            'totalguest'=>$totalguest,
            'totalaverage'=>$totalaverage,
            'pagecount'=>$pagecount,
            'page'=>$page,
            'page_item'=>$page_item,
            'qrCodeBase64'=>$qrCodeBase64,
            'Mvat'=>$Mvat,
            'Checkin'=>$checkin,
            'Checkout'=>$checkout,
            'guest'=>$guest,
        ];

        $view= $template->name;
        $pdf = FacadePdf::loadView('quotationpdf.'.$view,$data);
        return $pdf->stream();
    }

    public function senddocuments(Request $request){
        $idsString = $request->query('ids');
        // แปลง string เป็น array
        $idArray = explode(',', $idsString);
        $documents = dummy_quotation::whereIn('id', $idArray)->get();
        foreach ($documents as $document) {
            if ($document->status_document == 1) {
                $document->status_document = 2; // สมมติว่าคุณต้องการตั้งค่าเป็น 1
            } elseif ($document->status_document == 0) {
                $document->status_document = 1;
            }
            $document->save();
        }
        $userid = Auth::user()->id;
        $save = new log_company();
        $save->Created_by = $userid;
        $save->Company_ID = $DummyNo;
        $save->type = 'Send documents';
        $save->Category = 'Send documents :: Dummy Proposal';
        $save->content = 'Send documents to proposal request'.'+'.'Document Dummy Proposal ID : '.$DummyNo;
        $save->save();
        return response()->json(['success' => true, 'message' => 'Documents updated successfully!']);
    }

    public function Cancel($id){
        $dummy = dummy_quotation::where('id', $id)->first();
        $DummyNo =$dummy->DummyNo;
        $dummystatus =$dummy->status_document;
        $dummy = dummy_quotation::find($id);
        if($dummystatus == 0){
            $dummy->status_document = 1;
            $userid = Auth::user()->id;
            $save = new log_company();
            $save->Created_by = $userid;
            $save->Company_ID = $DummyNo;
            $save->type = 'Revice';
            $save->Category = 'Revice Cancel :: Dummy Proposal';
            $save->content = 'Revice Cancel Document Dummy Proposal ID : '.$DummyNo;
            $save->save();
        }else{
            $dummy->status_document = 0;
            $userid = Auth::user()->id;
            $save = new log_company();
            $save->Created_by = $userid;
            $save->Company_ID = $DummyNo;
            $save->type = 'Cancel';
            $save->Category = 'Cancel :: Dummy Proposal';
            $save->content = 'Cancel Document Dummy Proposal ID : '.$DummyNo;
            $save->save();
        }
        $dummy->save();

        return redirect()->route('DummyQuotation.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }
    public function Revice($id){
        $Quotation = dummy_quotation::find($id);
        $Quotation->status_document = 1;
        $Quotation->save();
        $data = dummy_quotation::where('id',$id)->first();
        $Quotation_ID = $data->DummyNo;
        $userid = Auth::user()->id;
        $save = new log_company();
        $save->Created_by = $userid;
        $save->Company_ID = $Quotation_ID;
        $save->type = 'Revice';
        $save->Category = 'Revice Reject :: Dummy Proposal';
        $save->content = 'Revice Reject Document Dummy Proposal ID : '.$Quotation_ID;
        $save->save();
        return redirect()->route('DummyQuotation.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }
    public function Generate(Request $request ,$id){
        try {
        $dummy = dummy_quotation::where('id', $id)->first();
        $dummy->status_document = 5;
        $dummy->save();
        $dummyID = $dummy->DummyNo;
        $save = new Quotation();
        $save->DummyNo = $dummyID;
        $save->Company_ID = $dummy->Company_ID;
        $save->company_contact = $dummy->company_contact;
        $save->checkin = $dummy->checkin;
        $save->checkout = $dummy->checkout;
        $save->day = $dummy->day;
        $save->night = $dummy->night;
        $save->adult = $dummy->adult;
        $save->children = $dummy->children;
        $save->ComRateCode = $dummy->ComRateCode;
        $save->SpecialDiscount = $dummy->SpecialDiscount;
        $save->Nettotal = $dummy->Nettotal;
        $save->AddTax = $dummy->AddTax;
        $save->freelanceraiffiliate = $dummy->freelanceraiffiliate;
        $save->commissionratecode = $dummy->commissionratecode;
        $save->eventformat = $dummy->eventformat;
        $save->vat_type = $dummy->vat_type;
        $save->issue_date = $dummy->issue_date;
        $save->Expirationdate = $dummy->Expirationdate;
        $save->Document_issuer = $dummy->Document_issuer;
        $save->Operated_by = $dummy->Operated_by;
        $save->Confirm_by = $dummy->Confirm_by;
        $save->Approve_at = $dummy->Approve_at;
        $save->save();
        $qutationID = Quotation::where('DummyNo',$dummyID)->first();
        $IDmain =  $qutationID->id;
        $ID = 'PD-';
        $currentDate = Carbon::now();
        $formattedDate = Carbon::parse($currentDate);       // วันที่
        $month = $formattedDate->format('m'); // เดือน
        $year = $formattedDate->format('y');
        $newRunNumber = str_pad($IDmain, 4, '0', STR_PAD_LEFT);
        $Quotation_IDnew = $ID.$year.$month.$newRunNumber;
        $qutationID->Quotation_ID = $Quotation_IDnew;
        $qutationID->save();
        $Quotation_ID =  $qutationID->DummyNo;
        $QuotationID =  $qutationID->Quotation_ID;
        $document_dummy = document_dummy_quotation::where('Quotation_ID', $Quotation_ID)->get();
        foreach ($document_dummy as $document) {
            $saveProduct = new document_quotation();
            $saveProduct->Quotation_ID = $QuotationID;
            $saveProduct->Company_ID = $document->Company_ID;
            $saveProduct->Product_ID = $document->Product_ID;
            $saveProduct->Issue_date = $document->Issue_date;
            $saveProduct->discount =$document->discount;
            $saveProduct->priceproduct =$document->priceproduct;
            $saveProduct->netpriceproduct =$document->netpriceproduct;
            $saveProduct->totaldiscount =$document->totaldiscount;
            $saveProduct->ExpirationDate = $document->ExpirationDate;
            $saveProduct->freelanceraiffiliate = $document->freelanceraiffiliate;
            $saveProduct->Quantity = $document->Quantity;
            $saveProduct->save();
        }
            $currentDateTime = Carbon::now();
            $currentDate = $currentDateTime->toDateString(); // Format: YYYY-MM-DD
            $currentTime = $currentDateTime->toTimeString(); // Format: HH:MM:SS

            // Optionally, you can format the date and time as per your requirement
            $formattedDate = $currentDateTime->format('Y-m-d'); // Custom format for date
            $formattedTime = $currentDateTime->format('H:i:s');
            $savePDF = new log();
            $savePDF->Quotation_ID = $QuotationID;
            $savePDF->QuotationType = 'Proposal';
            $savePDF->Approve_date = $formattedDate;
            $savePDF->Approve_time = $formattedTime;
            $savePDF->save();


            $userid = Auth::user()->id;
            $save = new log_company();
            $save->Created_by = $userid;
            $save->Company_ID = $QuotationID;
            $save->type = 'Generate';
            $save->Category = 'Generate :: Dummy Proposal';
            $save->content = 'Generate to Proposal '.'+'.'Document Proposal ID : '.$QuotationID;
            $save->save();
            $Quotation = Quotation::where('Quotation_ID', $QuotationID)->first();

            $id = $Quotation->id;
            $Company = $Quotation->Company_ID;
            $Quotation_ID = $Quotation->Quotation_ID;
            $eventformat = $Quotation->eventformat;
            $Company_ID = companys::where('Profile_ID',$Company)->first();

            $Company_typeID=$Company_ID->Company_type;
            $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
            $company_fax = company_fax::where('Profile_ID',$Company)->where('Sequence','main')->first();
            $company_phone = company_phone::where('Profile_ID',$Company)->where('Sequence','main')->first();
            $Contact_name = representative::where('Company_ID',$Company)->where('status',1)->first();
            $Contact_phone = representative_phone::where('Company_ID',$Company)->where('Sequence','main')->first();
            $eventformat = master_document::where('id',$eventformat)->select('name_th','id')->first();

            if ($comtype->name_th =="บริษัทจำกัด") {
                $comtypefullname = "บริษัท ". $Company_ID->Company_Name . " จำกัด";
            }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                $comtypefullname = "บริษัท ". $Company_ID->Company_Name . " จำกัด (มหาชน)";
            }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                $comtypefullname = "ห้างหุ้นส่วนจำกัด ". $Company_ID->Company_Name ;
            }else {
                $comtypefullname = $Company_ID->Company_Name;
            }

            $checkin = $Quotation->checkin;
            $checkout = $Quotation->checkout;
            $CityID=$Company_ID->City;
            $amphuresID = $Company_ID->Amphures;
            $TambonID = $Company_ID->Tambon;

            $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
            $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
            $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
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
            $selectproduct = document_quotation::where('Quotation_ID', $Quotation_ID)->get();

            $QuotationVat= $Quotation->vat_type;
            $Mvat = master_document::where('id',$QuotationVat)->where('status', '1')->where('Category','Mvat')->select('name_th','id')->first();

            $Products = Arr::wrap($selectproduct->pluck('Product_ID')->toArray());
            $quantities = $selectproduct->pluck('Quantity')->toArray();
            $discounts = $selectproduct->pluck('discount')->toArray();
            $priceUnits = $selectproduct->pluck('priceproduct')->toArray();
            $productItems = [];
            $totaldiscount = [];
            foreach ($Products as $index => $productID) {

                if (count($quantities) === count($priceUnits) && count($priceUnits) === count($discounts)) {
                    $totalPrices = []; // เปลี่ยนจากตัวแปรเดียวเป็น array เพื่อเก็บผลลัพธ์แต่ละรายการ
                    $discountedPrices = [];
                    $discountedPricestotal = [];
                    $totaldiscount = [];
                    // คำนวณราคาสำหรับแต่ละรายการ
                    for ($i = 0; $i < count($quantities); $i++) {
                        $quantity = intval($quantities[$i]);
                        $priceUnit = floatval(str_replace(',', '', $priceUnits[$i]));
                        $discount = floatval($discounts[$i]);

                        $totaldiscount0 = (($priceUnit * $discount)/100);
                        $totaldiscount[] = $totaldiscount0;

                        $totalPrice = ($quantity * $priceUnit);
                        $totalPrices[] = $totalPrice;

                        $discountedPrice = (($totalPrice * $discount )/ 100);
                        $discountedPrices[] = $priceUnit-$totaldiscount0;

                        $discountedPriceTotal = $totalPrice - $discountedPrice;
                        $discountedPricestotal[] = $discountedPriceTotal;
                    }
                }
                // dd( $priceUnit,$discountedPrices);

                $items = master_product_item::where('Product_ID', $productID)->get();
                $QuotationVat= $Quotation->vat_type;
                $Mvat = master_document::where('id',$QuotationVat)->where('status', '1')->where('Category','Mvat')->select('name_th','id')->first();
                foreach ($items as $item) {
                    // ตรวจสอบและกำหนดค่า quantity และ discount
                    $quantity = isset($quantities[$index]) ? $quantities[$index] : 0;
                    $discount = isset($discounts[$index]) ? $discounts[$index] : 0;
                    $totalPrices = isset($totalPrices[$index]) ? $totalPrices[$index] : 0;
                    $discountedPrices = isset($discountedPrices[$index]) ? $discountedPrices[$index] : 0;
                    $discountedPricestotal = isset($discountedPricestotal[$index]) ? $discountedPricestotal[$index] : 0;
                    $totaldiscount = isset($totaldiscount[$index]) ? $totaldiscount[$index] : 0;
                    // รวมข้อมูลของผลิตภัณฑ์เข้ากับ quantity และ discount
                    $productItems[] = [
                        'product' => $item,
                        'quantity' => $quantity,
                        'discount' => $discount,
                        'totalPrices'=>$totalPrices,
                        'discountedPrices'=>$discountedPrices,
                        'discountedPricestotal'=>$discountedPricestotal,
                        'totaldiscount'=>$totaldiscount,
                    ];

                }
            }

            $totalAmount = 0;
            $totaldiscount = 0;
            $netprice=0;
            $totalPrice = 0;
            $vat=0;
            $total=0;
            $adult = $Quotation->adult;
            $children = $Quotation->children;
            $totalguest = $adult+$children;
            $totalaverage=0;
            $SpecialDiscountBath=0;
            if ($Mvat->id == 50) {
                foreach ($selectproduct as $item) {
                    $totalPrice +=  $item->priceproduct;
                    $totalAmount += $item->netpriceproduct;
                    $subtotal = $totalAmount;
                    $beforeTax = $subtotal/1.07;
                    $AddTax = $subtotal-$beforeTax;
                    $Nettotal = $subtotal;
                    $totalaverage =$Nettotal/$totalguest;
                }
            }
            elseif ($Mvat->id == 51) {
                foreach ($selectproduct as $item) {
                    $totalPrice +=  $item->priceproduct;
                    $totalAmount += $item->netpriceproduct;
                    $subtotal = $totalAmount;
                    $beforeTax = 0;
                    $AddTax = 0;
                    $Nettotal = $subtotal;
                    $totalaverage =$Nettotal/$totalguest;
                }
            }
            elseif ($Mvat->id == 52) {
                foreach ($selectproduct as $item) {
                    $totalPrice +=  $item->priceproduct;
                    $totalAmount += $item->netpriceproduct;
                    $subtotal = $totalAmount;
                    $beforeTax = $subtotal/1.07;
                    $AddTax = $subtotal*7/100;
                    $Nettotal = $subtotal+$AddTax;
                    $totalaverage =$Nettotal/$totalguest;
                }
            }else
            {
                foreach ($selectproduct as $item) {
                    $totalPrice +=  $item->priceproduct;
                    $totalAmount += $item->netpriceproduct;
                    $subtotal = $totalAmount;
                    $beforeTax = $subtotal/1.07;
                    $AddTax = $subtotal-$beforeTax;
                    $Nettotal = $subtotal;
                    $totalaverage =$Nettotal/$totalguest;
                }
            }

            $protocol = $request->secure() ? 'https' : 'http';
            $linkQR = $protocol . '://' . $request->getHost() . "/Quotation/Quotation/cover/document/PDF/$id?page_shop=" . $request->input('page_shop');

            // Generate the QR code as PNG
            $qrCodeImage = QrCode::format('svg')->size(200)->generate($linkQR);
            $qrCodeBase64 = base64_encode($qrCodeImage);
            $unit = master_unit::where('status',1)->get();
            $quantity = master_quantity::where('status',1)->get();



            $pagecount = count($selectproduct);
                $page = $pagecount/10;

                $page_item = 1;
                if ($page > 1.1 && $page < 2.1) {
                    $page_item += 1;

                } elseif ($page > 1.1) {
                $page_item = 1 + $page > 1.1 ? ceil($page) : 1;
                }


            $data = [
                'date' => $date,
                'comtypefullname'=>$comtypefullname,
                'Company_ID'=>$Company_ID,
                'TambonID'=>$TambonID,
                'CityID'=>$CityID,
                'checkin'=>$checkin,
                'checkout'=>$checkout,
                'amphuresID'=>$amphuresID,
                'provinceNames'=>$provinceNames,
                'company_fax'=>$company_fax,
                'company_phone'=>$company_phone,
                'Contact_name'=>$Contact_name,
                'Contact_phone'=>$Contact_phone,
                'Quotation'=>$Quotation,
                'eventformat'=>$eventformat,
                'Reservation_show'=>$Reservation_show,
                'Paymentterms'=>$Paymentterms,
                'note'=>$note,
                'Cancellations'=>$Cancellations,
                'Complimentary'=>$Complimentary,
                'All_rights_reserved'=>$All_rights_reserved,
                'productItems'=>$productItems,
                'unit'=>$unit,
                'quantity'=>$quantity,
                'totalAmount'=>$totalAmount,
                'SpecialDis'=>$SpecialDiscountBath,
                'subtotal'=>$subtotal,
                'beforeTax'=>$beforeTax,
                'AddTax'=>$AddTax,
                'Nettotal'=>$Nettotal,
                'totalguest'=>$totalguest,
                'totalaverage'=>$totalaverage,
                'pagecount'=>$pagecount,
                'page'=>$page,
                'page_item'=>$page_item,
                'qrCodeBase64'=>$qrCodeBase64,
                'Mvat'=>$Mvat,
            ];

            $view= $template->name;
            $pdf = FacadePdf::loadView('quotationpdf.'.$view,$data);
            $path = 'Log_PDF/proposal/';
            $pdf->save($path . $Quotation_ID . '.pdf');




            return redirect()->route('DummyQuotation.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function addProduct($Quotation_ID, Request $request) {
        $value = $request->input('value');
        if ($value == 'Room_Type') {

            $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->orderBy('master_product_items.Product_ID', 'asc')
            ->where('master_product_items.status',1)->where('master_product_items.Category','Room_Type')->select('master_product_items.*','master_units.name_th as unit_name')->get();

        }elseif ($value == 'Banquet') {
            $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->orderBy('master_product_items.Product_ID', 'asc')
            ->where('master_product_items.status',1)->where('master_product_items.Category','Banquet')->select('master_product_items.*','master_units.name_th as unit_name')->get();

        }elseif ($value == 'Meals') {
            $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->orderBy('master_product_items.Product_ID', 'asc')
            ->where('master_product_items.status',1)->where('master_product_items.Category','Meals')->select('master_product_items.*','master_units.name_th as unit_name')->get();

        }elseif ($value == 'Entertainment') {
            $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->orderBy('master_product_items.Product_ID', 'asc')
            ->where('master_product_items.status',1)->where('master_product_items.Category','Entertainment')->select('master_product_items.*','master_units.name_th as unit_name')->get();

        }
        elseif ($value == 'all'){
            $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->orderBy('master_product_items.type', 'asc')
            ->where('master_product_items.status',1)->select('master_product_items.*','master_units.name_th as unit_name')->get();
        }
        return response()->json([
            'products' => $products,

        ]);
    }


    public function addProducttable($Quotation_ID, Request $request) {

        $value = $request->input('value');
        if ($value == 'Room_Type') {

            $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->orderBy('master_product_items.Product_ID', 'asc')
            ->where('master_product_items.status',1)->where('master_product_items.Category','Room_Type')->select('master_product_items.*','master_units.name_th as unit_name')->get();

        }elseif ($value == 'Banquet') {
            $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->orderBy('master_product_items.Product_ID', 'asc')
            ->where('master_product_items.status',1)->where('master_product_items.Category','Banquet')->select('master_product_items.*','master_units.name_th as unit_name')->get();

        }elseif ($value == 'Meals') {
            $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->orderBy('master_product_items.Product_ID', 'asc')
            ->where('master_product_items.status',1)->where('master_product_items.Category','Meals')->select('master_product_items.*','master_units.name_th as unit_name')->get();

        }elseif ($value == 'Entertainment') {
            $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->orderBy('master_product_items.Product_ID', 'asc')
            ->where('master_product_items.status',1)->where('master_product_items.Category','Entertainment')->select('master_product_items.*','master_units.name_th as unit_name')->get();

        }
        elseif ($value == 'all'){
            $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->orderBy('master_product_items.type', 'asc')
            ->where('master_product_items.status',1)->select('master_product_items.*','master_units.name_th as unit_name')->get();

        }
        return response()->json([
            'products' => $products,

        ]);
    }

    public function addProductselect($Quotation_ID, Request $request) {
        $value = $request->input('value');
        $products = master_product_item::leftJoin('master_units', 'master_product_items.unit', '=', 'master_units.id')
        ->orderBy('master_product_items.type', 'asc')
        ->where('master_product_items.status', 1)
        ->where('master_product_items.id', $value)
        ->select('master_product_items.*', 'master_units.name_th as unit_name')
        ->get();

        return response()->json([
            'products' => $products,

        ]);
    }
    public function addProducttableselect($Quotation_ID, Request $request) {
        $value = $request->input('value');
        $products = master_product_item::leftJoin('master_units', 'master_product_items.unit', '=', 'master_units.id')
        ->orderBy('master_product_items.type', 'asc')
        ->where('master_product_items.status', 1)
        ->where('master_product_items.id', $value)
        ->select('master_product_items.*', 'master_units.name_th as unit_name')
        ->get();

        return response()->json([
            'products' => $products,

        ]);
    }
    public function addProducttablemain($Quotation_ID, Request $request) {
        $value = $request->input('value');
        $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->Leftjoin('master_quantities','master_product_items.quantity','master_quantities.id')->orderBy('master_product_items.type', 'asc')
        ->where('master_product_items.status',1)->select('master_product_items.*','master_units.name_th as unit_name','master_quantities.name_th as quantity_name')->get();
        return response()->json([
            'products' => $products,

        ]);
    }
    public function addProducttablecreatemain($Quotation_ID, Request $request) {
        $value = $request->input('value');
        $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->Leftjoin('master_quantities','master_product_items.quantity','master_quantities.id')->orderBy('master_product_items.type', 'asc')
        ->where('master_product_items.status',1)->select('master_product_items.*','master_units.name_th as unit_name','master_quantities.name_th as quantity_name')->get();
        return response()->json([
            'products' => $products,

        ]);
    }
    public function view($id)
    {
        $Quotation = dummy_quotation::where('id', $id)->first();
        $QuotationID= $Quotation->DummyNo;
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
        if (!$company_fax) {
            $company_fax = '-';
        }
        $company_phone = company_phone::where('Profile_ID',$Company_ID)->where('Sequence','main')->first();
        $Contact_name = representative::where('Company_ID',$Company_ID)->where('id',$contact)->where('status',1)->first();
        $profilecontact = $Contact_name->Profile_ID;
        $Contact_phone = representative_phone::where('Company_ID',$Company_ID)->where('Profile_ID',$profilecontact)->where('Sequence','main')->first();
        $selectproduct = document_dummy_quotation::where('Quotation_ID', $QuotationID)->get();
        $unit = master_unit::where('status',1)->get();
        $quantity = master_quantity::where('status',1)->get();
        return view('dummy_quotation.view',compact('Quotation','Freelancer_member','Company','Mevent','Mvat','Contact_name','comtypefullname','CompanyID'
        ,'TambonID','amphuresID','CityID','provinceNames','company_fax','company_phone','Contact_phone','selectproduct','unit','quantity','QuotationID'));
    }
    public function LOG($id)
    {
        $Quotation = dummy_quotation::where('id', $id)->first();
        $QuotationID = $Quotation->DummyNo;


        $logproposal = log_company::where('Company_ID', $QuotationID)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('dummy_quotation.document',compact('logproposal'));
    }

}
