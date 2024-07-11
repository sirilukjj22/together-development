<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\dummy_quotation;
use Illuminate\Support\Facades\DB;
use App\Models\document_quotation;
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
use App\Models\Quotation_main_confirm;
use Auth;
use App\Models\User;
use PDF;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use App\Models\master_document_sheet;
use Dompdf\Dompdf;
use App\Models\master_template;
use Illuminate\Support\Arr;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
class proposal_request extends Controller
{
    public function index()
    {
        $proposal = dummy_quotation::where('status_document', 2)
            ->groupBy('Company_ID','Operated_by')
            ->select('dummy_quotation.*',DB::raw("COUNT(DummyNo) as COUNTDummyNo"))
            ->get();
        return view('proposal_req.index',compact('proposal'));
    }
    public function view($id)
    {
        $proposal = dummy_quotation::where('Company_ID',$id)->where('status_document', 2)->get();
        return view('proposal_req.view',compact('proposal'));
    }
    public function Approve(Request $request,$id){
        try {
            $proposal = dummy_quotation::where('DummyNo',$id)->first();
            $status = $proposal->status_document;
            $company = $proposal->Company_ID;
            $userid = Auth::user()->id;
            if ($status == 2) {
                $proposal->status_document = 3;
                $proposal->Confirm_by = $userid;
                $proposal->save();
            }
           // ดึงข้อมูลใบเสนอราคาที่สถานะเอกสารเป็น 2
            $proposalNo = dummy_quotation::where('Company_ID',$company)->where('status_document', 2)->get();

            foreach ($proposalNo as $item) {
                $dummyNoid = $item->DummyNo;

                // ดึงข้อมูลใบเสนอราคาที่มี DummyNo ตามที่กำหนด
                $Quotation = dummy_quotation::where('DummyNo', $dummyNoid)->first();
                $id = $Quotation->id;
                $Company = $Quotation->Company_ID;
                $Quotation_ID = $Quotation->DummyNo;
                $eventformat = $Quotation->eventformat;
                $Checkin =$Quotation->checkin;
                $Checkout=$Quotation->checkout;
                $checkin = Carbon::parse($Checkin)->format('d/m/Y');
                $checkout = Carbon::parse($Checkout)->format('d/m/Y');
                // ดึงข้อมูลประเภทบริษัท
                $Company_ID = companys::where('Profile_ID',$Company)->first();
                $Company_typeID = $Company_ID->Company_type;
                $comtype = master_document::where('id', $Company_typeID)->select('name_th', 'id')->first();

                // ดึงข้อมูลเบอร์แฟกซ์และเบอร์โทรศัพท์ของบริษัท
                $company_fax = company_fax::where('Profile_ID', $Company)->where('Sequence', 'main')->first();
                $company_phone = company_phone::where('Profile_ID', $Company)->where('Sequence', 'main')->first();

                // ดึงข้อมูลผู้ติดต่อและเบอร์โทรศัพท์ผู้ติดต่อ
                $Contact_name = representative::where('Company_ID', $Company)->where('status', 1)->first();
                $Contact_phone = representative_phone::where('Company_ID', $Company)->where('Sequence', 'main')->first();

                // ดึงข้อมูลรูปแบบกิจกรรม
                $eventformat = master_document::where('id', $eventformat)->select('name_th', 'id')->first();

                // ตรวจสอบประเภทบริษัทและกำหนดชื่อเต็มของบริษัท
                if ($comtype->name_th == "บริษัทจำกัด") {
                    $comtypefullname = "บริษัท " . $Company_ID->Company_Name . " จำกัด";
                } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                    $comtypefullname = "บริษัท " . $Company_ID->Company_Name . " จำกัด (มหาชน)";
                } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                    $comtypefullname = "ห้างหุ้นส่วนจำกัด " . $Company_ID->Company_Name;
                } else {
                    $comtypefullname = $Company_ID->Company_Name;
                }

                // ดึงข้อมูลที่อยู่
                $CityID = $Company_ID->City;
                $amphuresID = $Company_ID->Amphures;
                $TambonID = $Company_ID->Tambon;
                $provinceNames = province::where('id', $CityID)->select('name_th', 'id')->first();
                $amphuresID = amphures::where('id', $amphuresID)->select('name_th', 'id')->first();
                $TambonID = districts::where('id', $TambonID)->select('name_th', 'id', 'Zip_Code')->first();

                // ดึงข้อมูลเทมเพลตเอกสาร
                $template = master_template::query()->latest()->first();
                $CodeTemplate = $template->CodeTemplate;
                $sheet = master_document_sheet::select('topic', 'name_th', 'id', 'CodeTemplate')->get();

                // ดึงข้อมูลหัวข้อเอกสาร
                $Reservation_show = $sheet->where('topic', 'Reservation')->where('CodeTemplate', $CodeTemplate)->first();
                $Paymentterms = $sheet->where('topic', 'Paymentterms')->where('CodeTemplate', $CodeTemplate)->first();
                $note = $sheet->where('topic', 'note')->where('CodeTemplate', $CodeTemplate)->first();
                $Cancellations = $sheet->where('topic', 'Cancellations')->where('CodeTemplate', $CodeTemplate)->first();
                $Complimentary = $sheet->where('topic', 'Complimentary')->where('CodeTemplate', $CodeTemplate)->first();
                $All_rights_reserved = $sheet->where('topic', 'All_rights_reserved')->where('CodeTemplate', $CodeTemplate)->first();

                // วันที่ปัจจุบัน
                $date = Carbon::now();

                // ดึงข้อมูลสินค้าที่อยู่ในใบเสนอราคา
                $selectproduct = document_quotation::where('Quotation_ID', $Quotation_ID)->get();

                // ดึงข้อมูลภาษี
                $QuotationVat = $Quotation->vat_type;
                $Mvat = master_document::where('id', $QuotationVat)->where('status', '1')->where('Category', 'Mvat')->select('name_th', 'id')->first();

                // ดึงข้อมูลส่วนลดพิเศษ

                $SpecialDis = 0;

                // สร้าง array ของสินค้าพร้อมข้อมูล
                $Products = Arr::wrap($selectproduct->pluck('Product_ID')->toArray());
                $quantities = $selectproduct->pluck('Quantity')->toArray();
                $discounts = $selectproduct->pluck('discount')->toArray();
                $priceUnits = $selectproduct->pluck('priceproduct')->toArray();
                $productItems = [];
                $totaldiscount = [];

                foreach ($Products as $index => $productID) {
                    if (count($quantities) === count($priceUnits) && count($priceUnits) === count($discounts)) {
                        $totalPrices = [];
                        $discountedPrices = [];
                        $discountedPricestotal = [];
                        $totaldiscount = [];

                        for ($i = 0; $i < count($quantities); $i++) {
                            $quantity = intval($quantities[$i]);
                            $priceUnit = floatval(str_replace(',', '', $priceUnits[$i]));
                            $discount = floatval($discounts[$i]);

                            $totaldiscount0 = (($priceUnit * $discount) / 100);
                            $totaldiscount[] = $totaldiscount0;

                            $totalPrice = ($quantity * $priceUnit);
                            $totalPrices[] = $totalPrice;

                            $discountedPrice = (($totalPrice * $discount) / 100);
                            $discountedPrices[] = $priceUnit - $totaldiscount0;

                            $discountedPriceTotal = $totalPrice - $discountedPrice;
                            $discountedPricestotal[] = $discountedPriceTotal;
                        }
                    }

                    $items = master_product_item::where('Product_ID', $productID)->get();
                    foreach ($items as $item) {
                        $quantity = isset($quantities[$index]) ? $quantities[$index] : 0;
                        $discount = isset($discounts[$index]) ? $discounts[$index] : 0;
                        $totalPrices = isset($totalPrices[$index]) ? $totalPrices[$index] : 0;
                        $discountedPrices = isset($discountedPrices[$index]) ? $discountedPrices[$index] : 0;
                        $discountedPricestotal = isset($discountedPricestotal[$index]) ? $discountedPricestotal[$index] : 0;
                        $totaldiscount = isset($totaldiscount[$index]) ? $totaldiscount[$index] : 0;

                        $productItems[] = [
                            'product' => $item,
                            'quantity' => $quantity,
                            'discount' => $discount,
                            'totalPrices' => $totalPrices,
                            'discountedPrices' => $discountedPrices,
                            'discountedPricestotal' => $discountedPricestotal,
                            'totaldiscount' => $totaldiscount,
                        ];
                    }
                }

                // คำนวณค่าสินค้าทั้งหมด
                $totalAmount = 0;
                $totaldiscount = 0;
                $netprice = 0;
                $totalPrice = 0;
                $vat = 0;
                $total = 0;
                $adult = $Quotation->adult;
                $children = $Quotation->children;
                $totalguest = $adult + $children;
                $totalaverage = 0;
                $subtotal =0;
                $beforeTax=0;
                $AddTax=0;
                $Nettotal =0;
                if ($Mvat->id == 50) {
                    foreach ($selectproduct as $item) {
                        $totalPrice += $item->priceproduct;
                        $totalAmount += $item->netpriceproduct;
                        $subtotal = $totalAmount - $SpecialDis;
                        $beforeTax = $subtotal / 1.07;
                        $AddTax = $subtotal - $beforeTax;
                        $Nettotal = $subtotal;
                        $totalaverage = $Nettotal / $totalguest;
                    }
                } elseif ($Mvat->id == 51) {
                    foreach ($selectproduct as $item) {
                        $totalPrice += $item->priceproduct;
                        $totalAmount += $item->netpriceproduct;
                        $subtotal = $totalAmount - $SpecialDis;
                        $beforeTax = 0;
                        $AddTax = 0;
                        $Nettotal = $subtotal;
                        $totalaverage = $Nettotal / $totalguest;
                    }
                } elseif ($Mvat->id == 52) {
                    foreach ($selectproduct as $item) {
                        $totalPrice += $item->priceproduct;
                        $totalAmount += $item->netpriceproduct;
                        $subtotal = $totalAmount - $SpecialDis;
                        $beforeTax = $subtotal / 1.07;
                        $AddTax = $subtotal * 7 / 100;
                        $Nettotal = $subtotal + $AddTax;
                        $totalaverage = $Nettotal / $totalguest;
                    }
                } else {
                    foreach ($selectproduct as $item) {
                        $totalPrice += $item->priceproduct;
                        $totalAmount += $item->netpriceproduct;
                        $subtotal = $totalAmount - $SpecialDis;
                        $beforeTax = $subtotal / 1.07;
                        $AddTax = $subtotal - $beforeTax;
                        $Nettotal = $subtotal;
                        $totalaverage = $Nettotal / $totalguest;
                    }
                }

                // สร้างลิงก์ QR Code
                $protocol = $request->secure() ? 'https' : 'http';
                $linkQR = $protocol . '://' . $request->getHost() . "/Quotation/Quotation/cover/document/PDF/$id?page_shop=" . $request->input('page_shop');

                // Generate QR code เป็น PNG
                $qrCodeImage = QrCode::format('svg')->size(200)->generate($linkQR);
                $qrCodeBase64 = base64_encode($qrCodeImage);

                // ดึงข้อมูลหน่วยและปริมาณ
                $unit = master_unit::where('status', 1)->get();
                $quantity = master_quantity::where('status', 1)->get();

                // คำนวณจำนวนหน้าของเอกสาร
                $pagecount = count($selectproduct);
                $page = $pagecount / 10;

                $page_item = 1;
                if ($page > 1.1 && $page < 2.1) {
                    $page_item += 1;
                } elseif ($page > 1.1) {
                    $page_item = 1 + $page > 1.1 ? ceil($page) : 1;
                }

                // เตรียมข้อมูลสำหรับสร้าง PDF
                $data = [
                    'date' => $date,
                    'comtypefullname' => $comtypefullname,
                    'Company_ID' => $Company_ID,
                    'TambonID' => $TambonID,
                    'CityID' => $CityID,
                    'amphuresID' => $amphuresID,
                    'provinceNames' => $provinceNames,
                    'company_fax' => $company_fax,
                    'company_phone' => $company_phone,
                    'Contact_name' => $Contact_name,
                    'Contact_phone' => $Contact_phone,
                    'Quotation' => $Quotation,
                    'eventformat' => $eventformat,
                    'Reservation_show' => $Reservation_show,
                    'Paymentterms' => $Paymentterms,
                    'note' => $note,
                    'Cancellations' => $Cancellations,
                    'Complimentary' => $Complimentary,
                    'All_rights_reserved' => $All_rights_reserved,
                    'productItems' => $productItems,
                    'unit' => $unit,
                    'quantity' => $quantity,
                    'totalAmount' => $totalAmount,
                    'SpecialDis' => $SpecialDis,
                    'subtotal' => $subtotal,
                    'beforeTax' => $beforeTax,
                    'AddTax' => $AddTax,
                    'Nettotal' => $Nettotal,
                    'totalguest' => $totalguest,
                    'totalaverage' => $totalaverage,
                    'pagecount' => $pagecount,
                    'page' => $page,
                    'page_item' => $page_item,
                    'qrCodeBase64' => $qrCodeBase64,
                    'Mvat' => $Mvat,
                    'checkin'=>$checkin,
                    'checkout'=>$checkout,
                ];

                // เลือกเทมเพลตสำหรับสร้าง PDF
                $view = $template->name;

                // สร้าง PDF ด้วยข้อมูลที่เตรียมไว้
                $pdf = FacadePdf::loadView('quotationpdf.' . $view, $data);

                // บันทึกไฟล์ PDF
                $path = 'Log_PDF/dummy_proposal/';
                $pdf->save($path . $Quotation_ID . '.pdf');
                $dummyldID = dummy_quotation::where('DummyNo',$Quotation_ID)->delete();
                $documentQuotationoldID = document_quotation::where('Quotation_ID',$Quotation_ID)->delete();
            }
            return response()->json(['success' => true, 'message' => 'Document approved successfully.']);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }

    }
    public function Reject(Request $request,$id){
        try{
            $dummyNos = explode(',', $id);
            $proposalNo = dummy_quotation::whereIn('DummyNo',$dummyNos)->where('status_document', 2)->get();
            $userid = Auth::user()->id;
            foreach ($proposalNo as $item) {
                $item->status_document = 4;
                // $item->Confirm_by = $userid;
                $item->save();
            }
            return response()->json(['success' => true, 'message' => 'Document approved successfully.']);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
