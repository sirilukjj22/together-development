<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
use App\Models\document_quotation;
use App\Models\Quotation_main_confirm;
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
class QuotationController extends Controller
{
    public function index()
    {
        $userid = Auth::user()->id;
        $Quotation_IDs = Quotation::query()->pluck('Quotation_ID');
        $document = document_quotation::whereIn('Quotation_ID', $Quotation_IDs)->get();
        $document_IDs = $document->pluck('Quotation_ID');
        $missingQuotationIDs = $Quotation_IDs->diff($document_IDs);
        Quotation::whereIn('Quotation_ID', $missingQuotationIDs)->delete();
        $Quotation = Quotation::query()->where('Operated_by',$userid)->whereIn('status_document', [1, 2])->get();
        return view('quotation.index',compact('Quotation'));
    }
    public function changestatus($id ,$status)
    {

        try {
            $statusdata = Quotation::find($id);
            if ($status == 2 ) {
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
            $query = Quotation::query();
            $Quotation = $query->where('status', '1')->get();
        }
        return view('quotation.index',compact('Quotation'));
    }
    public function no(Request $request)
    {
        $no = $request->value;
        if ($no == 0 ) {
            $query = Quotation::query();
            $Quotation = $query->where('status', '0')->get();
        }
        return view('quotation.index',compact('Quotation'));
    }
    public function create()
    {
        $currentDate = Carbon::now();
        $ID = 'PD-';
        $formattedDate = Carbon::parse($currentDate);       // วันที่
        $month = $formattedDate->format('m'); // เดือน
        $year = $formattedDate->format('y');
        $lastRun = Quotation::latest()->first();
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
        $Quotation_ID = $ID.$year.$month.$newRunNumber;
        $Mevent = master_document::select('name_th','id')->where('status', '1')->where('Category','Mevent')->get();
        $Mvat = master_document::select('name_th','id')->where('status', '1')->where('Category','Mvat')->get();
        $Freelancer_member = Freelancer_Member::select('First_name','id','Profile_ID','Last_name')->where('status', '1')->get();
        $Company = companys::select('Company_Name','id','Profile_ID')->get();

        return view('quotation.create',compact('Quotation_ID','Company','Mevent','Freelancer_member','Issue_date','Valid_Until','Mvat'));
    }
    public function Contact($companyID)
    {
        $Contact_name = representative::where('Company_ID',$companyID)->where('status',1)->select('First_name','Last_name','Profile_ID','id')->orderby('id','desc')->get();
        return response()->json([
            'data' => $Contact_name,

        ]);
    }

    public function save(Request $request){
        $data = $request->all();
        $userid = Auth::user()->id;
        $Quotation_IDcheck =$request->Quotation_ID;
        $IDquotation = Quotation::where('Quotation_ID',$Quotation_IDcheck)->first();

        if ($IDquotation) {
            $currentDate = Carbon::now();
            $ID = 'PD-';
            $formattedDate = Carbon::parse($currentDate);       // วันที่
            $month = $formattedDate->format('m'); // เดือน
            $year = $formattedDate->format('y');
            $lastRun = Quotation::latest()->first();
            $nextNumber = 1;
            $lastRunid = $lastRun->id;
            $nextNumber = $lastRunid + 1;
            $newRunNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            $Quotation_ID = $ID.$year.$month.$newRunNumber;
        }else{
            $Quotation_ID =$request->Quotation_ID;
        }
        $save = new Quotation();
        $save->place = $request->place;
        $save->Quotation_ID = $Quotation_ID;
        $save->Company_ID = $request->Company;
        $save->company_contact = $request->Company_Contact;
        $save->checkin = $request->Checkin;
        $save->checkout = $request->Checkout;
        $save->day = $request->Day;
        $save->night = $request->Night;
        $save->adult = $request->Adult;
        $save->children = $request->Children;
        $save->maxdiscount = $request->Max_discount;
        $save->ComRateCode = $request->Company_Rate_Code;
        $save->freelanceraiffiliate = $request->Freelancer_member;
        $save->commissionratecode = $request->Company_Commission_Rate_Code;
        $save->eventformat = $request->Mevent;
        $save->vat_type = $request->Mvat;
        $save->issue_date = $request->IssueDate;
        $save->Expirationdate = $request->Expiration;
        $save->Document_issuer = $userid;
        $save->save();
        if ( $save->save()) {
            return redirect()->to(url('/Quotation/selectproduct/company/create/'.$save->Quotation_ID))->with('success', 'บันทึกข้อมูลเรียบร้อย');
        }else {
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }

    }
    public function selectProduct($id)
    {
        $Quotation = Quotation::where('Quotation_ID', $id)->first();
        $Quotation_ID = $Quotation->Quotation_ID;
        $Company = $Quotation->Company_ID;
        $Company_ID = companys::where('Profile_ID',$Company)->first();
        $Company_typeID=$Company_ID->Company_type;
        $CityID=$Company_ID->City;
        $amphuresID = $Company_ID->Amphures;
        $TambonID = $Company_ID->Tambon;
        $Company_type = master_document::where('id',$Company_typeID)->select('name_th','id')->first();
        $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
        $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
        $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
        $company_fax = company_fax::where('Profile_ID',$Company)->where('Sequence','main')->first();
        $company_phone = company_phone::where('Profile_ID',$Company)->where('Sequence','main')->first();
        // dd($Company_typeID);
        // ส่งตัวแปรไปยัง view
        $Contact_name = representative::where('Company_ID',$Company)->where('status',1)->first();
        $ContactCityID = $Contact_name->City;
        $ContactamphuresID = $Contact_name->Amphures;
        $ContactTambonID = $Contact_name->Tambon;
        $Contact_phone = representative_phone::where('Company_ID',$Company)->where('Sequence','main')->first();
        $ContactCity = province::where('id',$ContactCityID)->select('name_th','id')->first();
        $ContactamphuresID = amphures::where('id',$ContactamphuresID)->select('name_th','id')->first();
        $ContactTambonID = districts::where('id',$ContactTambonID)->select('name_th','id','Zip_Code')->first();
        $booking_channel = master_document::select('name_en', 'id')->where('status', 1)->Where('Category','Mbooking_channel')->get();
        $product = master_product_item::where('status',1)->get();
        $unit = master_unit::where('status',1)->get();
        $quantity = master_quantity::where('status',1)->get();
        $Mvat = master_document::select('name_th','id')->where('status', '1')->where('Category','Mvat')->get();
        return view('quotation.selectproduct',compact('Quotation','Company_ID','Company_type','amphuresID','TambonID','provinceNames','company_fax','company_phone'
        ,'Contact_name','Contact_phone','ContactCity','ContactamphuresID','ContactTambonID','product','unit','quantity','Quotation_ID','Mvat'));
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
            $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->orderBy('master_product_items.Product_ID', 'asc')
            ->where('master_product_items.status',1)->select('master_product_items.*','master_units.name_th as unit_name')->get();
        }
        return response()->json([
            'products' => $products,

        ]);
    }

    public function savequotation(Request $request ,$Quotation_ID)
    {
        $data = $request->all();
        $preview = $request->preview;
        $SpecialDis = $request->SpecialDis;
        $userid = Auth::user()->id;
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
        $Quotation_ID = $request->Quotation_ID;


        $Quotation = Quotation::where('Quotation_ID', $Quotation_ID)->first();
        $id = $Quotation->id;
        $IssueDate = $Quotation->issue_date;
        $ExpirationDate = $Quotation->Expirationdate;
        $Company_ID = $Quotation->Company_ID;
        $freelanceraiffiliate = $Quotation->freelanceraiffiliate;

        foreach ($priceUnits as $key => $price) {
            $priceUnits[$key] = str_replace(array(',', '.00'), '', $price);
        }
        if ($preview) {
            $Quotation = Quotation::where('Quotation_ID', $Quotation_ID)->first();

            $Company = $Quotation->Company_ID;
            $Quotation_ID = $Quotation->Quotation_ID;
            $eventformat = $Quotation->eventformat;
            $Company_ID = companys::where('Profile_ID',$Company)->first();
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
            $company_fax = company_fax::where('Profile_ID',$Company)->where('Sequence','main')->first();
            $company_phone = company_phone::where('Profile_ID',$Company)->where('Sequence','main')->first();
            $Contact_name = representative::where('Company_ID',$Company)->where('status',1)->first();
            $Contact_phone = representative_phone::where('Company_ID',$Company)->where('Sequence','main')->first();
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
            // dd($productItems);
            $totalAmount = 0;
            $totalPrice = 0;
            $subtotal = 0;
            $beforeTax = 0;
            $AddTax = 0;
            $Nettotal =0;
            $totalaverage=0;
            $adult = $Quotation->adult;
            $children = $Quotation->children;
            $totalguest = $adult+$children;

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
            $data = [
                'date' => $date,
                'comtypefullname'=>$comtypefullname,
                'Company_ID'=>$Company_ID,
                'TambonID'=>$TambonID,
                'CityID'=>$CityID,
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
            ];
            $view= $template->name;
            $pdf = FacadePdf::loadView('quotationpdf.preview',$data);
            return $pdf->stream();
        }

        $Products=$request->input('ProductIDmain');
        if ($Products !== null) {
            $save = Quotation::find($id);
            $save->SpecialDiscount = $SpecialDis;
            $save->Operated_by = $userid;
            $save->save();
            foreach ($Products as $index => $ProductID) {
                $saveProduct = new document_quotation();
                $saveProduct->Quotation_ID = $Quotation_ID;
                $saveProduct->Company_ID = $Company_ID;
                $saveProduct->Product_ID = $ProductID;
                $saveProduct->Issue_date = $IssueDate;
                $saveProduct->discount =$discounts[$index];
                $saveProduct->priceproduct =$priceUnits[$index];
                $saveProduct->netpriceproduct =$discountedPricestotal[$index];
                $saveProduct->totaldiscount =$discountedPrices[$index];
                $saveProduct->ExpirationDate = $ExpirationDate;
                $saveProduct->freelanceraiffiliate = $freelanceraiffiliate;
                $saveProduct->Quantity = $quantities[$index];
                $saveProduct->Document_issuer = $userid;
                $saveProduct->SpecialDiscount = $SpecialDis;
                $saveProduct->save();
            }
            if ( $saveProduct->save()) {
                return redirect()->route('Quotation.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
            }else {
                return redirect()->back()->with('error_', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
            }
        }else{
           $delete = Quotation::find($id);
           $delete->delete();
            return redirect()->route('Quotation.index')->with('success', 'ใบเสนอราคายังไม่ถูกสร้าง');
        }

    }

    public function updatequotation(Request $request ,$id)
    {
        $data = $request->all();
        $preview = $request->preview;
        $SpecialDis = $request->SpecialDis;
        $userid = Auth::user()->id;
        $Quotation0 = Quotation::where('id', $id)->first();
        $Quotation_ID = $Quotation0->Quotation_ID;
        $Quotation = Quotation::where('Quotation_ID', $Quotation_ID)->first();
        $Company_ID = $Quotation->Company_ID;
        $IssueDate = $request->IssueDate;
        $ExpirationDate = $request->ExpirationDate;
        $freelanceraiffiliate = $Quotation->freelanceraiffiliate;
        //----------------------------document_quotation---------------------------------------
        $product = $request->input('ProductIDmain');
        $quantities = $request->input('Quantitymain', []);
        $priceUnits = $request->input('priceproductmain', []);
        $discounts = $request->input('discountmain', []);
        // $trselectmain = $request->input('tr-select-main', []);
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
        if ($preview) {
            $Quotation = Quotation::where('Quotation_ID', $Quotation_ID)->first();

            $Company = $Quotation->Company_ID;
            $Quotation_ID = $Quotation->Quotation_ID;
            $eventformat = $Quotation->eventformat;
            $Company_ID = companys::where('Profile_ID',$Company)->first();
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
            $company_fax = company_fax::where('Profile_ID',$Company)->where('Sequence','main')->first();
            $company_phone = company_phone::where('Profile_ID',$Company)->where('Sequence','main')->first();
            $Contact_name = representative::where('Company_ID',$Company)->where('status',1)->first();
            $Contact_phone = representative_phone::where('Company_ID',$Company)->where('Sequence','main')->first();
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
            $quantities = $request->input('Quantitymain', []);
            $discounts = $request->input('discountmain', []);
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
            // dd($productItems);
            $totalAmount = 0;
            $totalPrice = 0;
            $subtotal = 0;
            $beforeTax = 0;
            $AddTax = 0;
            $Nettotal =0;
            $totalaverage=0;
            $adult = $Quotation->adult;
            $children = $Quotation->children;
            $totalguest = $adult+$children;

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

            $protocol = $request->secure() ? 'https' : 'http';
            $linkQR = $protocol . '://' . $request->getHost() . "/Quotation/Quotation/cover/document/PDF/$id?page_shop=" . $request->input('page_shop');

            // Generate the QR code as PNG
            $qrCodeImage = QrCode::format('svg')->size(200)->generate($linkQR);
            $qrCodeBase64 = base64_encode($qrCodeImage);
            $data = [
                'date' => $date,
                'comtypefullname'=>$comtypefullname,
                'Company_ID'=>$Company_ID,
                'TambonID'=>$TambonID,
                'CityID'=>$CityID,
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
            ];
            $view= $template->name;
            $pdf = FacadePdf::loadView('quotationpdf.preview',$data);
            return $pdf->stream();
        }
        foreach ($priceUnits as $key => $price) {
            $priceUnits[$key] = str_replace(array(',', '.00'), '', $price);
        }
        if ($product !== null) {
            if ($Quotation_ID) {
                $save = Quotation::find($id);
                $save->SpecialDiscount = $SpecialDis;
                $save->Confirm = 0;
                $save->Operated_by = $userid;
                $save->save();
                $profileid = document_quotation::where('Quotation_ID', $Quotation_ID)->get();
                foreach ($profileid as $document) {
                    $document->delete();
                }
                if ($product !== null) {
                    foreach ($product as $index => $ProductID) {
                        $saveProduct = new document_quotation();
                        $saveProduct->Quotation_ID = $Quotation_ID;
                        $saveProduct->Company_ID = $Company_ID;
                        $saveProduct->Product_ID = $ProductID;
                        $saveProduct->Issue_date = $IssueDate;
                        $saveProduct->discount =$discounts[$index];
                        $saveProduct->priceproduct =$priceUnits[$index];
                        $saveProduct->netpriceproduct =$discountedPricestotal[$index];
                        $saveProduct->totaldiscount =$discountedPrices[$index];
                        $saveProduct->ExpirationDate = $ExpirationDate;
                        $saveProduct->freelanceraiffiliate = $freelanceraiffiliate;
                        $saveProduct->Quantity = $quantities[$index];
                        $saveProduct->Document_issuer = $userid;
                        $saveProduct->SpecialDiscount = $SpecialDis;
                        $saveProduct->save();
                    }
                    if ( $saveProduct->save()) {
                        return redirect()->route('Quotation.index')->with('susses', 'บันทึกข้อมูลเรียบร้อย');
                    }else {
                        return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
                    }
                }
            }
            // dd($profileid,$ProductIDmain,$Quantitymain,$priceproductmain,$discountmain,$net_discountmain,$allcounttotalmain);
        }

    }
    public function edit($id)
    {

        $Quotation = Quotation::where('id', $id)->first();
        $Mevent = master_document::select('name_th','id')->where('status', '1')->where('Category','Mevent')->get();
        $Mvat = master_document::select('name_th','id')->where('status', '1')->where('Category','Mvat')->get();
        $Freelancer_member = Freelancer_Member::select('First_name','id','Profile_ID','Last_name')->where('status', '1')->get();
        $Company = companys::select('Company_Name','id','Profile_ID')->get();

        return view('quotation.edit',compact('Quotation','Freelancer_member','Company','Mevent','Mvat'));
    }
    public function updateCompanyQuotation(Request $request ,$id){
        $data = $request->all();
        $Freelancer_member = $request->Freelancer_member;
        $QuotationID = Quotation::where('id', $id)->first();
        if(
            $QuotationID->Company_ID == $data['Company'] &&
            $QuotationID->checkin == $data['IssueDate'] &&
            $QuotationID->checkout == $data['Expiration'] &&
            $QuotationID->company_contact == $data['Company_Contact'] &&
            $QuotationID->day == $data['Day'] &&
            $QuotationID->night == $data['Night'] &&
            $QuotationID->adult == $data['Adult'] &&
            $QuotationID->children == $data['Children'] &&
            $QuotationID->maxdiscount == $data['Max_discount'] &&
            $QuotationID->ComRateCode == $data['Company_Rate_Code'] &&
            $QuotationID->freelanceraiffiliate == $data['Freelancer_member'] &&
            $QuotationID->commissionratecode == $data['Company_Commission_Rate_Code'] &&
            $QuotationID->place == $data['place'] &&
            $QuotationID->eventformat == $data['Mevent'] &&
            $QuotationID->vat_type == $data['Mvat']
        ){
            $save = Quotation::find($id);
            $userid = Auth::user()->id;
            $save->place = $request->place;
            $save->Quotation_ID = $request->Quotation_ID;
            $save->checkin = $request->Checkin;
            $save->checkout = $request->Checkout;
            $save->day = $request->Day;
            $save->night = $request->Night;
            $save->adult = $request->Adult;
            $save->children = $request->Children;
            $save->maxdiscount = $request->Max_discount;
            $save->ComRateCode = $request->Company_Rate_Code;
            $save->freelanceraiffiliate = $Freelancer_member;
            $save->commissionratecode = $request->Company_Commission_Rate_Code;
            $save->eventformat = $request->Mevent;
            $save->vat_type = $request->Mvat;
            $save->issue_date = $request->IssueDate;
            $save->Expirationdate = $request->Expiration;
            $save->Document_issuer = $userid;
            $save->Operated_by = $userid;
            $save->save();

        }
        else
        {
            $Quotation_ID=$request->Quotation_ID;
            if (preg_match('/^PD-\d{8}$/', $Quotation_ID)) {
                $editpart = '-';
                $number = 1;
                $Quotation_ID = $Quotation_ID.$editpart.$number;

            } else {
                $parts = explode('-', $Quotation_ID);
                $numberPart = (int)array_pop($parts);
                $newNumberPart = $numberPart + 1;
                $Quotation_ID = implode('-', $parts) . '-' . $newNumberPart;
            }
            $save = new Quotation();
            $userid = Auth::user()->id;
            $save->place = $request->place;
            $save->Quotation_ID = $Quotation_ID;
            $save->Company_ID = $request->Company;
            $save->company_contact = $request->Company_Contact;
            $save->checkin = $request->Checkin;
            $save->checkout = $request->Checkout;
            $save->day = $request->Day;
            $save->night = $request->Night;
            $save->adult = $request->Adult;
            $save->children = $request->Children;
            $save->maxdiscount = $request->Max_discount;
            $save->ComRateCode = $request->Company_Rate_Code;
            $save->freelanceraiffiliate = $Freelancer_member;
            $save->commissionratecode = $request->Company_Commission_Rate_Code;
            $save->eventformat = $request->Mevent;
            $save->vat_type = $request->Mvat;
            $save->issue_date = $request->IssueDate;
            $save->Expirationdate = $request->Expiration;
            $save->Document_issuer = $userid;
            $save->save();
        }
        if ( $save->save()) {
            return redirect()->to(url('/Quotation/edit/quotation/select/'.$id))->with('success', 'บันทึกข้อมูลเรียบร้อย');
        }else {
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }



    }
    public function editselect($id)
    {
        $Quotation = Quotation::where('id', $id)->first();
        $Company = $Quotation->Company_ID;
        $Quotation_ID = $Quotation->Quotation_ID;
        $Company_ID = companys::where('Profile_ID',$Company)->first();
        $Company_typeID=$Company_ID->Company_type;
        $CityID=$Company_ID->City;
        $amphuresID = $Company_ID->Amphures;
        $TambonID = $Company_ID->Tambon;
        $Company_type = master_document::where('id',$Company_typeID)->select('name_th','id')->first();
        $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
        $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
        $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
        $company_fax = company_fax::where('Profile_ID',$Company)->where('Sequence','main')->first();
        $company_phone = company_phone::where('Profile_ID',$Company)->where('Sequence','main')->first();
        // dd($Company_typeID);
        // ส่งตัวแปรไปยัง view
        $Contact_name = representative::where('Company_ID',$Company)->where('status',1)->first();
        $ContactCityID = $Contact_name->City;
        $ContactamphuresID = $Contact_name->Amphures;
        $ContactTambonID = $Contact_name->Tambon;
        $Contact_phone = representative_phone::where('Company_ID',$Company)->where('Sequence','main')->first();
        $ContactCity = province::where('id',$ContactCityID)->select('name_th','id')->first();
        $ContactamphuresID = amphures::where('id',$ContactamphuresID)->select('name_th','id')->first();
        $ContactTambonID = districts::where('id',$ContactTambonID)->select('name_th','id','Zip_Code')->first();
        $booking_channel = master_document::select('name_en', 'id')->where('status', 1)->Where('Category','Mbooking_channel')->get();
        $product = master_product_item::where('status',1)->get();
        $unit = master_unit::where('status',1)->get();
        $quantity = master_quantity::where('status',1)->get();
        $selectproduct = document_quotation::where('Quotation_ID', $Quotation_ID)->get();
        $SpecialDiscount = document_quotation::where('Quotation_ID', $Quotation_ID)->first();
        if ($SpecialDiscount==null) {
            $SpecialDiscount=0;
        }else{
            $SpecialDiscount=$SpecialDiscount->SpecialDiscount;
        }

        $Mvat = master_document::select('name_th','id')->where('status', '1')->where('Category','Mvat')->get();
        return view('quotation.editproduct',compact('Quotation','Company_ID','Company_type','amphuresID','TambonID','provinceNames','company_fax','company_phone'
        ,'Contact_name','Contact_phone','ContactCity','ContactamphuresID','ContactTambonID','product','unit','quantity','selectproduct','Mvat','SpecialDiscount'));
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
            $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->orderBy('master_product_items.Product_ID', 'asc')
            ->where('master_product_items.status',1)->select('master_product_items.*','master_units.name_th as unit_name')->get();

        }
        return response()->json([
            'products' => $products,

        ]);
    }

    public function addProductselect($Quotation_ID, Request $request) {
        $value = $request->input('value');
        $products = master_product_item::leftJoin('master_units', 'master_product_items.unit', '=', 'master_units.id')
        ->orderBy('master_product_items.Product_ID', 'asc')
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
        ->orderBy('master_product_items.Product_ID', 'asc')
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
        $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->Leftjoin('master_quantities','master_product_items.quantity','master_quantities.id')->orderBy('master_product_items.Product_ID', 'asc')
        ->where('master_product_items.status',1)->select('master_product_items.*','master_units.name_th as unit_name','master_quantities.name_th as quantity_name')->get();
        return response()->json([
            'products' => $products,

        ]);
    }
    public function addProducttablecreatemain($Quotation_ID, Request $request) {
        $value = $request->input('value');
        $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->Leftjoin('master_quantities','master_product_items.quantity','master_quantities.id')->orderBy('master_product_items.Product_ID', 'asc')
        ->where('master_product_items.status',1)->select('master_product_items.*','master_units.name_th as unit_name','master_quantities.name_th as quantity_name')->get();
        return response()->json([
            'products' => $products,

        ]);
    }


    public function sheetpdf(Request $request ,$id) {


        $Quotation = Quotation::where('id', $id)->first();
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
        $SpecialDiscount = document_quotation::where('Quotation_ID', $Quotation_ID)->first();
        $SpecialDis=$SpecialDiscount->SpecialDiscount;

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
        if ($Mvat->id == 50) {
            foreach ($selectproduct as $item) {
                $totalPrice +=  $item->priceproduct;
                $totalAmount += $item->netpriceproduct;
                $subtotal = $totalAmount-$SpecialDis;
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
                $subtotal = $totalAmount-$SpecialDis;
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
                $subtotal = $totalAmount-$SpecialDis;
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
                $subtotal = $totalAmount-$SpecialDis;
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
        ];

        $view= $template->name;
        $pdf = FacadePdf::loadView('quotationpdf.'.$view,$data);
        return $pdf->stream();
    }

    public function index_check()
    {
        $Quotation = Quotation::query()->where('Confirm',0)->get();
        return view('quotation_check.index',compact('Quotation'));
    }

    public function  changeconfirm($id)
    {
        $confirm = Quotation::find($id);
        $userid = Auth::user()->id;
        if ($confirm->Confirm == 1 ) {
            $statuss = 0;
            $confirm->Confirm = $statuss;
            $confirm->Confirm_by = $userid;
        }elseif (($confirm->Confirm == 0 )) {
            $statuss = 1;
            $confirm->Confirm = $statuss;
            $confirm->Confirm_by = $userid;
        }
        $confirm->save();
    }
    public function updateSpecialDis(Request $request, $id){
        dd($request->all());

    }
    public function editCompany($id){
        $Quotation = Quotation::where('id', $id)->first();
        $Mevent = master_document::select('name_th','id')->where('status', '1')->where('Category','Mevent')->get();
        $Mvat = master_document::select('name_th','id')->where('status', '1')->where('Category','Mvat')->get();
        $Freelancer_member = Freelancer_Member::select('First_name','id','Profile_ID','Last_name')->where('status', '1')->get();
        $Company = companys::select('Company_Name','id','Profile_ID')->get();
        return view('quotation.editcom',compact('Quotation','Freelancer_member','Company','Mevent','Mvat'));
    }
    public function updateCompanyQuotationfirstsave(Request $request, $id){

        $Freelancer_member = $request->Freelancer_member;
        $save = Quotation::find($id);
        $userid = Auth::user()->id;
        $save->place = $request->place;
        $save->Quotation_ID = $request->Quotation_ID;
        $save->checkin = $request->Checkin;
        $save->checkout = $request->Checkout;
        $save->day = $request->Day;
        $save->night = $request->Night;
        $save->adult = $request->Adult;
        $save->children = $request->Children;
        $save->maxdiscount = $request->Max_discount;
        $save->ComRateCode = $request->Company_Rate_Code;
        $save->freelanceraiffiliate = $Freelancer_member;
        $save->commissionratecode = $request->Company_Commission_Rate_Code;
        $save->eventformat = $request->Mevent;
        $save->vat_type = $request->Mvat;
        $save->issue_date = $request->IssueDate;
        $save->Expirationdate = $request->Expiration;
        $save->Document_issuer = $userid;
        $save->save();
        if ( $save->save()) {
            return redirect()->to(url('/Quotation/selectproduct/company/create/'.$save->Quotation_ID))->with('success', 'บันทึกข้อมูลเรียบร้อย');
        }else {
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
    }

}
