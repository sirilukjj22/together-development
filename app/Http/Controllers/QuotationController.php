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
use App\Models\MasterEventFormate;
use App\Models\Freelancer_Member;
use App\Models\province;
use App\Models\amphures;
use App\Models\districts;
use App\Models\master_document;
use App\Models\master_product_item;
use App\Models\master_quantity;
use App\Models\master_unit;
use App\Models\document_quotation;
use Auth;
use App\Models\User;
use PDF;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use App\Models\master_document_sheet;
use Dompdf\Dompdf;
class QuotationController extends Controller
{
    public function index()
    {
        $Quotation = Quotation::query()->get();
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
        $Mevent = MasterEventFormate::select('name_th','id')->where('status', '1')->get();
        $Freelancer_member = Freelancer_Member::select('First_name','id','Profile_ID','Last_name')->where('status', '1')->get();
        $Company = companys::select('Company_Name','id','Profile_ID')->get();
        return view('quotation.create',compact('Quotation_ID','Company','Mevent','Freelancer_member','Issue_date','Valid_Until'));
    }
    public function Contact($companyID)
    {
        $Contact_name = representative::where('Company_ID',$companyID)->select('First_name','Last_name','Profile_ID','id')->orderby('id','desc')->get();
        return response()->json([
            'data' => $Contact_name,

        ]);
    }

    public function save(Request $request){
        $data = $request->all();
        $userid = Auth::user()->id;
        $save = new Quotation();
        $save->place = $request->place;
        $save->Quotation_ID = $request->Quotation_ID;
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
        $save->vat_type = $request->Vat_Type;
        $save->issue_date = $request->IssueDate;
        $save->Expirationdate = $request->Expiration;
        $save->Document_issuer = $userid;
        $save->save();
        if ( $save->save()) {
            return redirect()->to(url('/Quotation/selectproduct/company/create/'.$save->Quotation_ID))->with('alert_', 'บันทึกข้อมูลเรียบร้อย');
        }else {
            return redirect()->back()->with('error_', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }

    }
    public function selectProduct($id)
    {
        $Quotation = Quotation::where('Quotation_ID', $id)->first();
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
        return view('quotation.selectproduct',compact('Quotation','Company_ID','Company_type','amphuresID','TambonID','provinceNames','company_fax','company_phone'
        ,'Contact_name','Contact_phone','ContactCity','ContactamphuresID','ContactTambonID','product','unit','quantity'));
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

        }elseif ($value == 'all'){
            $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->orderBy('master_product_items.Product_ID', 'asc')
            ->where('master_product_items.status',1)->select('master_product_items.*','master_units.name_th as unit_name')->get();
        }else {
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
        $userid = Auth::user()->id;
        $quantities = $request->input('quantitymain', []); // ตัวอย่างใช้ 'pricetotal' เป็น quantity
        $priceUnits = $request->input('price-unit', []);
        $discounts = $request->input('discountmain', []);

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
        $IssueDate = $request->IssueDate;
        $ExpirationDate = $request->ExpirationDate;
        $Quotation = Quotation::where('Quotation_ID', $Quotation_ID)->first();
        $Company_ID = $Quotation->Company_ID;
        $freelanceraiffiliate = $Quotation->freelanceraiffiliate;
        $Products =$request->input('Product_ID');
        foreach ($priceUnits as $key => $price) {
            $priceUnits[$key] = str_replace(array(',', '.00'), '', $price);
        }
        // dd($data);
        if ($Products !== null) {
            foreach ($Products as $index => $ProductID) {
                $saveProduct = new document_quotation();
                $saveProduct->Quotation_ID = $Quotation_ID;
                $saveProduct->Company_ID = $Company_ID;
                $saveProduct->Product_ID = $ProductID;
                $saveProduct->Issue_date = $IssueDate;
                $saveProduct->discount =$discounts[$index];
                $saveProduct->priceproduct =$priceUnits[$index];
                $saveProduct->netpriceproduct =$discountedPricestotal[$index];
                $saveProduct->totalpriceproduct =$totalPrices[$index];
                $saveProduct->ExpirationDate = $ExpirationDate;
                $saveProduct->freelanceraiffiliate = $freelanceraiffiliate;
                $saveProduct->Quantity = $quantities[$index];
                $saveProduct->Document_issuer = $userid;
                $saveProduct->save();
            }
            if ( $saveProduct->save()) {
                return redirect()->route('Quotation.index')->with('alert_', 'บันทึกข้อมูลเรียบร้อย');
            }else {
                return redirect()->back()->with('error_', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
            }
        }else{
            return redirect()->route('Quotation.index')->with('alert_', 'ใบเสนอราคายังไม่ถูกสร้าง');
        }

    }

    public function updatequotation(Request $request ,$id)
    {
        $data = $request->all();
        $userid = Auth::user()->id;
        $Quotation0 = Quotation::where('id', $id)->first();
        $Quotation_ID = $Quotation0->Quotation_ID;
        $Quotation = Quotation::where('Quotation_ID', $Quotation_ID)->first();
        $Company_ID = $Quotation->Company_ID;
        $IssueDate = $request->IssueDate;
        $ExpirationDate = $request->ExpirationDate;
        $freelanceraiffiliate = $Quotation->freelanceraiffiliate;
        //----------------------------document_quotation---------------------------------------
        $ProductIDmain = $request->input('ProductIDmain');
        // dd($data); // ตัวอย่างใช้ 'pricetotal' เป็น quantity
        $Quantitymain = $request->input('Quantitymain');
        $priceproductmain = $request->input('priceproductmain');
        $discountmain = $request->input('discountmain');
        $net_discountmain = $request->input('net_discountmain');
        $allcounttotalmain = $request->input('allcounttotalmain');
        //----------------------------new data and calculate-------------------------------------------------
        $product = $request->input('product', []);
        $quantities = $request->input('quantity', []);
        $priceUnits = $request->input('price-unit', []);
        $discounts = $request->input('discount', []);
        foreach ($priceUnits as $key => $price) {
            $priceUnits[$key] = str_replace(array(',', '.00'), '', $price);
        }
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

                $discountedPrice = (($totalPrice * $discount) / 100);
                $discountedPrices[] = $discountedPrice;

                $discountedPriceTotal = $totalPrice - $discountedPrice;
                $discountedPricestotal[] = $discountedPriceTotal;
            }
        }
        if ($ProductIDmain !== null) {
            if ($Quotation_ID) {
                $profileid = document_quotation::where('Quotation_ID', $Quotation_ID)->get();
                foreach ($profileid as $document) {
                    $document->delete();
                }
                if ($ProductIDmain !== null) {
                    foreach ($ProductIDmain as $index => $ProductID) {
                        $saveProduct = new document_quotation();
                        $saveProduct->Quotation_ID = $Quotation_ID;
                        $saveProduct->Company_ID = $Company_ID;
                        $saveProduct->Product_ID = $ProductID;
                        $saveProduct->Issue_date = $IssueDate;
                        $saveProduct->discount =$discountmain[$index];
                        $saveProduct->priceproduct =$priceproductmain[$index];
                        $saveProduct->netpriceproduct =$net_discountmain[$index];
                        $saveProduct->totalpriceproduct =$allcounttotalmain[$index];
                        $saveProduct->ExpirationDate = $ExpirationDate;
                        $saveProduct->freelanceraiffiliate = $freelanceraiffiliate;
                        $saveProduct->Quantity = $Quantitymain[$index];
                        $saveProduct->Document_issuer = $userid;
                        $saveProduct->save();
                    }
                }
                if ($product !== null) {
                    foreach ($product as $index => $ProductID) {
                        $save = new document_quotation();
                        $save->Quotation_ID = $Quotation_ID;
                        $save->Company_ID = $Company_ID;
                        $save->Product_ID = $ProductID;
                        $save->Issue_date = $IssueDate;
                        $save->discount =$discounts[$index];
                        $save->priceproduct =$priceUnits[$index];
                        $save->netpriceproduct =$discountedPricestotal[$index];
                        $save->totalpriceproduct =$totalPrices[$index];
                        $save->ExpirationDate = $ExpirationDate;
                        $save->freelanceraiffiliate = $freelanceraiffiliate;
                        $save->Quantity = $quantities[$index];
                        $save->Document_issuer = $userid;
                        $save->save();
                    }
                    if ( $save->save()) {
                        return redirect()->route('Quotation.index')->with('alert_', 'บันทึกข้อมูลเรียบร้อย');
                    }else {
                        return redirect()->back()->with('error_', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
                    }
                }
            }
            // dd($profileid,$ProductIDmain,$Quantitymain,$priceproductmain,$discountmain,$net_discountmain,$allcounttotalmain);
        }else{

            if ($product !== null) {
                foreach ($product as $index => $ProductID) {
                    $save = new document_quotation();
                    $save->Quotation_ID = $Quotation_ID;
                    $save->Company_ID = $Company_ID;
                    $save->Product_ID = $ProductID;
                    $save->Issue_date = $IssueDate;
                    $save->discount =$discounts[$index];
                    $save->priceproduct =$priceUnits[$index];
                    $save->netpriceproduct =$discountedPricestotal[$index];
                    $save->totalpriceproduct =$totalPrices[$index];
                    $save->ExpirationDate = $ExpirationDate;
                    $save->freelanceraiffiliate = $freelanceraiffiliate;
                    $save->Quantity = $quantities[$index];
                    $save->Document_issuer = $userid;
                    $save->save();
                }
                if ( $save->save()) {
                    return redirect()->route('Quotation.index')->with('alert_', 'บันทึกข้อมูลเรียบร้อย');
                }else {
                    return redirect()->back()->with('error_', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
                }
            }

        }
    }
    public function edit($id)
    {
        $Quotation = Quotation::where('id', $id)->first();
        $Mevent = MasterEventFormate::select('name_th','id')->where('status', '1')->get();
        $Freelancer_member = Freelancer_Member::select('First_name','id','Profile_ID','Last_name')->where('status', '1')->get();
        $Company = companys::select('Company_Name','id','Profile_ID')->get();
        return view('quotation.edit',compact('Quotation','Freelancer_member','Company','Mevent'));
    }
    public function updateCompanyQuotation(Request $request ,$id){
        $data = $request->all();
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
        $save->freelanceraiffiliate = $request->Freelancer_member;
        $save->commissionratecode = $request->Company_Commission_Rate_Code;
        $save->eventformat = $request->Mevent;
        $save->vat_type = $request->Vat_Type;
        $save->issue_date = $request->IssueDate;
        $save->Expirationdate = $request->Expiration;
        $save->Document_issuer = $userid;
        $save->save();
        if ( $save->save()) {
            return redirect()->route('Quotation.index')->with('alert_', 'บันทึกข้อมูลเรียบร้อย');
        }else {
            return redirect()->back()->with('error_', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
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

        return view('quotation.editProduct',compact('Quotation','Company_ID','Company_type','amphuresID','TambonID','provinceNames','company_fax','company_phone'
        ,'Contact_name','Contact_phone','ContactCity','ContactamphuresID','ContactTambonID','product','unit','quantity','selectproduct'));
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

        }elseif ($value == 'all'){
            $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->orderBy('master_product_items.Product_ID', 'asc')
            ->where('master_product_items.status',1)->select('master_product_items.*','master_units.name_th as unit_name')->get();
        }else {
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
    //-------------------------------ใบปะหน้า-----------------------------
    public function coverdocument($id)
    {
        $Quotation = Quotation::where('id', $id)->first();
        $Company = $Quotation->Company_ID;
        $Quotation_ID = $Quotation->Quotation_ID;
        $eventformat = $Quotation->eventformat;
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
        $Contact_name = representative::where('Company_ID',$Company)->where('status',1)->first();
        $Contact_phone = representative_phone::where('Company_ID',$Company)->where('Sequence','main')->first();
        $eventformat = MasterEventFormate::where('id',$eventformat)->select('name_th','id')->first();


        $date = Carbon::now();
        $sheet = master_document_sheet::select('topic','name_th','id')->get();
        $Reservation_show = $sheet->where('topic', 'Reservation')->first();
        $Paymentterms = $sheet->where('topic', 'Paymentterms')->first();
        $note = $sheet->where('topic', 'note')->first();
        $Cancellations = $sheet->where('topic', 'Cancellations')->first();
        $Complimentary = $sheet->where('topic', 'Complimentary')->first();
        $All_rights_reserved = $sheet->where('topic', 'All_rights_reserved')->first();

        return view('quotation.document',compact('Quotation','Company_ID','Company_type','provinceNames','amphuresID','TambonID','company_fax','company_phone',
        'Contact_name','Contact_phone','date','eventformat','Reservation_show','Paymentterms','note','Cancellations','Complimentary','All_rights_reserved'));
    }
    public function sheet(Request $request,$id)
    {
        $data=$request->all();
        $data = [
            "Reservation" => $request->input('Reservation'),
            "Paymentterms" => $request->input('Paymentterms'),
            "note" => $request->input('note'),
            "Cancellations" => $request->input('Cancellations'),
            "Complimentary" => $request->input('Complimentary'),
            "All_rights_reserved" => $request->input('All_rights_reserved'),
        ];
        master_document_sheet::truncate();
        foreach ($data as $key => $value) {
            DB::table('master_document_sheet')->insert([
                'topic' => $key,
                'name_th' => $value,
                'name_en' => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        return redirect()->back()->with('alert_', 'บันทึกใบปะหน้า');
    }
    public function sheetpdf($id) {
        // Increase the maximum execution time if needed
        set_time_limit(120);

        // Create a new Dompdf instance
        $dompdf = new Dompdf();

        // Load HTML content
        $quotation = Quotation::findOrFail($id);
        $imageUrl = asset('logo_crop.png'); // Get the URL to the image

        // Generate the HTML content with the image
        $html = '<html>
            <head>
                <style>
                    .logo {
                        width: 100px; /* Adjust the size as needed */
                        height: auto;
                    }
                </style>
            </head>
            <body>
                <div class="col-12">
                    <div class="row">
                        <div class="col-lg-8 col-md-12 col-sm-12 image-container">
                            <img src="' . $imageUrl . '" alt="Together Resort Logo" class="logo"/>
                        </div>
                    </div>
                </div>
                <div>
                    <!-- Add other content here -->
                </div>
            </body>
        </html>';

        // Load the HTML content into Dompdf
        $dompdf->loadHtml($html);

        // Set paper size and orientation
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        return $dompdf->stream();
    }
}
