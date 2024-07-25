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
use App\Models\log;
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
        $user = Auth::user();
        $userid = Auth::user()->id;
        $Quotation_IDs = Quotation::query()->pluck('Quotation_ID');
        $document = document_quotation::whereIn('Quotation_ID', $Quotation_IDs)->get();
        $document_IDs = $document->pluck('Quotation_ID');
        $missingQuotationIDs = $Quotation_IDs->diff($document_IDs);
        Quotation::whereIn('Quotation_ID', $missingQuotationIDs)->delete();
        if ($user->permission_edit == 1) {
            $Proposalcount = Quotation::query()->where('Operated_by',$userid)->count();
            $Proposal = Quotation::query()->where('Operated_by',$userid)->get();
            $Pending = Quotation::query()->where('Operated_by',$userid)->whereIn('status_document',[1,3])->where('status_guest',0)->get();
            $Pendingcount = Quotation::query()->where('Operated_by',$userid)->whereIn('status_document',[1,3])->where('status_guest',0)->count();
            $Awaiting = Quotation::query()->where('Operated_by',$userid)->where('status_document',2)->get();
            $Awaitingcount = Quotation::query()->where('Operated_by',$userid)->where('status_document',2)->count();
            $Approved = Quotation::query()->where('Operated_by',$userid)->where('status_guest',1)->get();
            $Approvedcount = Quotation::query()->where('Operated_by',$userid)->where('status_guest',1)->count();
            $Reject = Quotation::query()->where('Operated_by',$userid)->where('status_document',4)->get();
            $Rejectcount = Quotation::query()->where('Operated_by',$userid)->where('status_document',4)->count();
            $Cancel = Quotation::query()->where('Operated_by',$userid)->where('status_document',0)->get();
            $Cancelcount = Quotation::query()->where('Operated_by',$userid)->where('status_document',0)->count();
        }
        elseif ($user->permission_edit == 2 || $user->permission_edit == 3) {
            $Proposalcount = Quotation::query()->count();
            $Proposal = Quotation::query()->get();
            $Pending = Quotation::query()->whereIn('status_document',[1,3])->where('status_guest',0)->get();
            $Pendingcount = Quotation::query()->whereIn('status_document',[1,3])->where('status_guest',0)->count();
            $Awaiting = Quotation::query()->where('status_document',2)->get();
            $Awaitingcount = Quotation::query()->where('status_document',2)->count();
            $Approved = Quotation::query()->where('status_guest',1)->get();
            $Approvedcount = Quotation::query()->where('status_guest',1)->count();
            $Reject = Quotation::query()->where('status_document',4)->get();
            $Rejectcount = Quotation::query()->where('status_document',4)->count();
            $Cancel = Quotation::query()->where('status_document',0)->get();
            $Cancelcount = Quotation::query()->where('status_document',0)->count();
        }
        elseif ($user->permission_edit == 0) {
            $Proposalcount = Quotation::query()->count();
            $Proposal = Quotation::query()->get();
            $Pending = Quotation::query()->whereIn('status_document',[1,3])->where('status_guest',0)->get();
            $Pendingcount = Quotation::query()->whereIn('status_document',[1,3])->where('status_guest',0)->count();
            $Awaiting = Quotation::query()->where('status_document',2)->get();
            $Awaitingcount = Quotation::query()->where('status_document',2)->count();
            $Approved = Quotation::query()->where('status_guest',1)->get();
            $Approvedcount = Quotation::query()->where('status_guest',1)->count();
            $Reject = Quotation::query()->where('status_document',4)->get();
            $Rejectcount = Quotation::query()->where('status_document',4)->count();
            $Cancel = Quotation::query()->where('status_document',0)->get();
            $Cancelcount = Quotation::query()->where('status_document',0)->count();
        }
        return view('quotation.index',compact('Proposalcount','Proposal','Awaitingcount','Awaiting','Pending','Pendingcount','Approved','Approvedcount','Rejectcount','Reject','Cancel','Cancelcount'));
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
        $Mevent = master_document::select('name_th','id','lavel')->where('status', '1')->where('Category','Mevent')->get();
        $Mvat = master_document::select('name_th','id','lavel')->where('status', '1')->where('Category','Mvat')->get();
        $Freelancer_member = Freelancer_Member::select('First_name','id','Profile_ID','Last_name')->where('status', '1')->get();
        $Company = companys::select('Company_Name','id','Profile_ID')->get();
        return view('quotation.create',compact('Quotation_ID','Company','Mevent','Freelancer_member','Issue_date','Valid_Until','Mvat'));
    }
    public function senddocuments(Request $request){
        $idsString = $request->query('ids');
        // แปลง string เป็น array
        $idArray = explode(',', $idsString);
        $documents = Quotation::whereIn('id', $idArray)->get();
        foreach ($documents as $document) {
            if ($document->status_document == 1) {
                $document->status_document = 2; // สมมติว่าคุณต้องการตั้งค่าเป็น 1
            } elseif ($document->status_document == 0) {
                $document->status_document = 1;
            }
            $document->save();
        }
        return response()->json(['success' => true, 'message' => 'Documents updated successfully!']);
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
            $data = $request->all();
            $preview=$request->preview;
            $Quotation_IDcheck =$request->Quotation_ID;
            $adult=$request->Adult;
            $children=$request->Children;

            $SpecialDiscount = $request->SpecialDiscount;
            $SpecialDiscountBath = $request->DiscountAmount;
            $userid = Auth::user()->id;
            $IDquotation = Quotation::where('Quotation_ID',$Quotation_IDcheck)->first();
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
                $checkin = Carbon::parse($Checkin)->format('d/m/Y');
                $checkout = Carbon::parse($Checkout)->format('d/m/Y');
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
                    'company_fax'=>$company_fax,
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
                $Quotation_ID =$Quotation_IDcheck;
            }
            $save = new Quotation();
            $save->Quotation_ID = $Quotation_ID;
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
            $save->ComRateCode = $request->Company_Discount;
            $save->Expirationdate = $request->Expiration;
            $save->Operated_by = $userid;
            if ($SpecialDiscount == 0 && $SpecialDiscountBath == 0) {
                $save->SpecialDiscount = $SpecialDiscount;
                $save->SpecialDiscountBath = $SpecialDiscountBath;
                $save->status_document = 1;
                $save->Confirm_by = 'Auto';
                $save->save();
            }else {
                $save->SpecialDiscount = $SpecialDiscount;
                $save->SpecialDiscountBath = $SpecialDiscountBath;
                $save->status_document = 1;
                $save->Confirm_by = '-';
                $save->save();
            }
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
            }
            foreach ($priceUnits as $key => $price) {
                $priceUnits[$key] = str_replace(array(',', '.00'), '', $price);
            }
            $Products=$request->input('ProductIDmain');
            $pax=$request->input('pax');
            if ($Products !== null) {
                foreach ($Products as $index => $ProductID) {
                    $saveProduct = new document_quotation();
                    $saveProduct->Quotation_ID = $Quotation_ID;
                    $saveProduct->Company_ID = $request->Company;
                    $saveProduct->Product_ID = $ProductID;
                    $paxValue = $pax[$index] ?? 0;
                    $saveProduct->pax = $paxValue;
                    $saveProduct->Issue_date = $request->IssueDate;
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
                $currentDateTime = Carbon::now();
                $currentDate = $currentDateTime->toDateString(); // Format: YYYY-MM-DD
                $currentTime = $currentDateTime->toTimeString(); // Format: HH:MM:SS

                // Optionally, you can format the date and time as per your requirement
                $formattedDate = $currentDateTime->format('Y-m-d'); // Custom format for date
                $formattedTime = $currentDateTime->format('H:i:s');
                $savePDF = new log();
                $savePDF->Quotation_ID = $Quotation_ID;
                $savePDF->QuotationType = 'Proposal';
                $savePDF->Approve_date = $formattedDate;
                $savePDF->Approve_time = $formattedTime;
                $savePDF->save();
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
                $SpecialDiscountBath = $request->DiscountAmount;
                if ($Mvat->id == 50) {
                    foreach ($productItems as $item) {
                        $totalPrice += $item['totalPrices'];
                        $totalAmount += $item['discountedPricestotal'];
                        $subtotal = $totalAmount-$SpecialDiscountBath;
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
                        $subtotal = $totalAmount-$SpecialDiscountBath;
                        $Nettotal = $subtotal;
                        $totalaverage =$Nettotal/$totalguest;


                    }
                }
                elseif ($Mvat->id == 52) {
                    foreach ($productItems as $item) {
                        $totalPrice += $item['totalPrices'];
                        $totalAmount += $item['discountedPricestotal'];
                        $subtotal = $totalAmount-$SpecialDiscountBath;
                        $AddTax = $subtotal*7/100;
                        $Nettotal = $subtotal+$AddTax;
                        $totalaverage =$Nettotal/$totalguest;
                    }
                }else
                {
                    foreach ($productItems as $item) {
                        $totalPrice += $item['totalPrices'];
                        $totalAmount += $item['discountedPricestotal'];
                        $subtotal = $totalAmount-$SpecialDiscountBath;
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
                    'company_fax'=>$company_fax,
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
                    'SpecialDis'=>$SpecialDiscountBath,
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
                // บันทึกไฟล์ PDF
                $path = 'Log_PDF/proposal/';
                $pdf->save($path . $Quotation_ID . '.pdf');
                $Quotation = Quotation::where('Quotation_ID',$Quotation_IDcheck)->first();
                $Quotation->AddTax = $AddTax;
                $Quotation->Nettotal = $Nettotal;
                $Quotation->save();
                return redirect()->route('Quotation.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
            }else{
            $delete = Quotation::find($id);
            $delete->delete();
                return redirect()->route('Quotation.index')->with('success', 'ใบเสนอราคายังไม่ถูกสร้าง');
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function edit($id)
    {
        $Quotation = Quotation::where('id', $id)->first();
        $QuotationID= $Quotation->Quotation_ID;
        $Company_ID = $Quotation->Company_ID;
        $contact = $Quotation->company_contact;
        if (preg_match('/^PD-\d{8}$/', $QuotationID)) {
            $editpart = '-';
            $number = 1;
            $Quotation_ID = $QuotationID.$editpart.$number;

        } else {
            $parts = explode('-', $QuotationID);
            $numberPart = (int)array_pop($parts);
            $newNumberPart = $numberPart + 1;
            $Quotation_ID = implode('-', $parts) . '-' . $newNumberPart;
        }

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
        $selectproduct = document_quotation::where('Quotation_ID', $QuotationID)->get();
        $unit = master_unit::where('status',1)->get();
        $quantity = master_quantity::where('status',1)->get();
        return view('quotation.edit',compact('Quotation','Freelancer_member','Company','Mevent','Mvat','Quotation_ID','Contact_name','comtypefullname','CompanyID'
        ,'TambonID','amphuresID','CityID','provinceNames','company_fax','company_phone','Contact_phone','selectproduct','unit','quantity','QuotationID'));
    }

    public function update(Request $request)
    {
        $data = $request->all();

        try {
            $preview = $request->preview;
            $Quotation_ID=$request->Quotation_ID;
            $Quotationold=$request->Quotationold;
            $adult=$request->Adult;
            $children=$request->Children;
            $SpecialDiscount = $request->SpecialDiscount;
            $SpecialDiscountBath = $request->DiscountAmount;
            $QuotationID = Quotation::where('Quotation_ID', $Quotation_ID)->first();
            if ($QuotationID) {
                $parts = explode('-', $Quotation_ID);
                $numberPart = (int)array_pop($parts);
                $newNumberPart = $numberPart + 1;
                $Quotation_ID = implode('-', $parts) . '-' . $newNumberPart;
            }

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

                $SpecialDiscountBath = $request->DiscountAmount;
                if ($Mvat->id == 50) {
                    foreach ($productItems as $item) {
                        $totalPrice += $item['totalPrices'];
                        $totalAmount += $item['discountedPricestotal'];
                        $subtotal = $totalAmount-$SpecialDiscountBath;
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
                        $subtotal = $totalAmount-$SpecialDiscountBath;
                        $Nettotal = $subtotal;
                        $totalaverage =$Nettotal/$totalguest;
                    }
                }
                elseif ($Mvat->id == 52) {
                    foreach ($productItems as $item) {
                        $totalPrice += $item['totalPrices'];
                        $totalAmount += $item['discountedPricestotal'];
                        $subtotal = $totalAmount-$SpecialDiscountBath;
                        $AddTax = $subtotal*7/100;
                        $Nettotal = $subtotal+$AddTax;
                        $totalaverage =$Nettotal/$totalguest;
                    }
                }else
                {
                    foreach ($productItems as $item) {
                        $totalPrice += $item['totalPrices'];
                        $totalAmount += $item['discountedPricestotal'];
                        $subtotal = $totalAmount-$SpecialDiscountBath;
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
                if ($Checkin) {
                    $checkin = Carbon::parse($Checkin)->format('d/m/Y');
                    $checkout = Carbon::parse($Checkout)->format('d/m/Y');
                }else{
                    $checkin = '-';
                    $checkout = '-';
                }
                $Day = $request->Day;
                $Night = $request->Night;
                $comment = $request->comment;

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
                    'company_fax'=>$company_fax,
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
                    'SpecialDis'=>$SpecialDiscountBath,
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
            $userid = Auth::user()->id;
            $save = new Quotation();
            $save->Quotation_ID = $Quotation_ID;
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
            $save->ComRateCode = $request->Company_Discount;
            $save->Expirationdate = $request->Expiration;
            $save->Operated_by = $userid;
            if ($SpecialDiscount == 0 && $SpecialDiscountBath == 0) {
                $save->SpecialDiscount = $SpecialDiscount;
                $save->SpecialDiscountBath = $SpecialDiscountBath;
                $save->status_document = 1;
                $save->Confirm_by = 'Auto';
                $save->save();
            }else {
                $save->SpecialDiscount = $SpecialDiscount;
                $save->SpecialDiscountBath = $SpecialDiscountBath;
                $save->status_document = 1;
                $save->Confirm_by = '-';
                $save->save();
            }
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
            }
            foreach ($priceUnits as $key => $price) {
                $priceUnits[$key] = str_replace(array(',', '.00'), '', $price);
            }
            $Products=$request->input('ProductIDmain');
            $pax=$request->input('pax');
            if ($Products !== null) {
                foreach ($Products as $index => $ProductID) {
                    $saveProduct = new document_quotation();
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
                    $saveProduct->Document_issuer = $userid;
                    $saveProduct->save();
                }
            }
            $Quotation_ID=$request->Quotation_ID;
            $Quotation = Quotation::where('Quotation_ID', $Quotation_ID)->first();
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
            $Checkin = $request->Checkin;
            $Checkout = $request->Checkout;
            if ($Checkin) {
                $checkin = Carbon::parse($Checkin)->format('d/m/Y');
                $checkout = Carbon::parse($Checkout)->format('d/m/Y');
            }else{
                $checkin = '-';
                $checkout = '-';
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

            $totalguest = $Quotation->TotalPax;
            $totalaverage=0;
            if ($Mvat->id == 50) {
                foreach ($selectproduct as $item) {
                    $totalPrice +=  $item->priceproduct;
                    $totalAmount += $item->netpriceproduct;
                    $subtotal = $totalAmount-$SpecialDiscountBath;
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
                    $subtotal = $totalAmount-$SpecialDiscountBath;
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
                    $subtotal = $totalAmount-$SpecialDiscountBath;
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
                    $subtotal = $totalAmount-$SpecialDiscountBath;
                    $beforeTax = $subtotal/1.07;
                    $AddTax = $subtotal-$beforeTax;
                    $Nettotal = $subtotal;
                    $totalaverage =$Nettotal/$totalguest;
                }
            }

            $Quotation->AddTax = $AddTax;
            $Quotation->Nettotal = $Nettotal;
            $Quotation->save();
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

            $currentDateTime = Carbon::now();
            $currentDate = $currentDateTime->toDateString(); // Format: YYYY-MM-DD
            $currentTime = $currentDateTime->toTimeString(); // Format: HH:MM:SS
            // Optionally, you can format the date and time as per your requirement
            $formattedDate = $currentDateTime->format('Y-m-d'); // Custom format for date
            $formattedTime = $currentDateTime->format('H:i:s');
            $savePDF = new log();
            $savePDF->Quotation_ID = $Quotation_ID;
            $savePDF->QuotationType = 'Proposal';
            $savePDF->Approve_date = $formattedDate;
            $savePDF->	Approve_time = $formattedTime;
            $savePDF->save();

            $QuotationoldID = Quotation::where('Quotation_ID',$Quotationold)->delete();
            $documentQuotationoldID = document_quotation::where('Quotation_ID',$Quotationold)->delete();
            return redirect()->route('Quotation.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function Approve($id){
        $quotation = Quotation::find($id);
        $quotation->status_guest = 1;
        $quotation->save();
        return response()->json(['success' => true]);
    }
    public function LOG($id)
    {

        $Quotation = Quotation::where('id', $id)->first();
        if ($Quotation) {
            $QuotationID = $Quotation->Quotation_ID;

            // Use a regular expression to capture the part of the string before the first hyphen
            if (preg_match('/^(PD-\d{8})/', $QuotationID, $matches)) {
                $QuotationID = $matches[1];
            }
        }
        $log = log::where('Quotation_ID', 'LIKE', $QuotationID . '%')->get();
        $path = 'Log_PDF/proposal/';
        return view('quotation.document',compact('log','path'));
    }

    public function cancel($id){
        $Quotation = Quotation::where('id', $id)->first();
        $Quotation->status_document = 0;
        $Quotation->save();
        return redirect()->route('Quotation.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }
    public function Revice($id){
        $Quotation = Quotation::where('id', $id)->first();
        $Quotation->status_document = 1;
        $Quotation->save();
        return redirect()->route('Quotation.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
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


    public function sheetpdf(Request $request ,$id) {


        $Quotation = Quotation::where('id', $id)->first();
        $Company = $Quotation->Company_ID;
        $Quotation_ID = $Quotation->Quotation_ID;
        $eventformat = $Quotation->eventformat;
        $SpecialDiscountBath = $Quotation->SpecialDiscountBath;
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
            $checkin = $request->Checkin;
            $checkout = $request->Checkout;
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
        $adult = !empty($adult) ? $adult : 0;
        $children = !empty($children) ? $children : 0;
        $totalguest = $adult+$children;
        $totalaverage=0;
        if ($Mvat->id == 50) {
            foreach ($selectproduct as $item) {
                $totalPrice +=  $item->priceproduct;
                $totalAmount += $item->netpriceproduct;
                $subtotal = $totalAmount-$SpecialDiscountBath;
                $beforeTax = $subtotal/1.07;
                $AddTax = $subtotal-$beforeTax;
                $Nettotal = $subtotal;
                if ($totalguest == 0) {
                    $totalaverage = 0;
                }else{
                    $totalaverage =$Nettotal/$totalguest;
                }
            }
        }
        elseif ($Mvat->id == 51) {
            foreach ($selectproduct as $item) {
                $totalPrice +=  $item->priceproduct;
                $totalAmount += $item->netpriceproduct;
                $subtotal = $totalAmount-$SpecialDiscountBath;
                $beforeTax = 0;
                $AddTax = 0;
                $Nettotal = $subtotal;
                if ($totalguest == 0) {
                    $totalaverage = 0;
                }else{
                    $totalaverage =$Nettotal/$totalguest;
                }
            }
        }
        elseif ($Mvat->id == 52) {
            foreach ($selectproduct as $item) {
                $totalPrice +=  $item->priceproduct;
                $totalAmount += $item->netpriceproduct;
                $subtotal = $totalAmount-$SpecialDiscountBath;
                $beforeTax = $subtotal/1.07;
                $AddTax = $subtotal*7/100;
                $Nettotal = $subtotal+$AddTax;
                if ($totalguest == 0) {
                    $totalaverage = 0;
                }else{
                    $totalaverage =$Nettotal/$totalguest;
                }
            }
        }else
        {
            foreach ($selectproduct as $item) {
                $totalPrice +=  $item->priceproduct;
                $totalAmount += $item->netpriceproduct;
                $subtotal = $totalAmount-$SpecialDiscountBath;
                $beforeTax = $subtotal/1.07;
                $AddTax = $subtotal-$beforeTax;
                $Nettotal = $subtotal;
                if ($totalguest == 0) {
                    $totalaverage = 0;
                }else{
                    $totalaverage =$Nettotal/$totalguest;
                }
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
            'checkin'=>$checkin,
            'checkout'=>$checkout,
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
        return $pdf->stream();
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


}
