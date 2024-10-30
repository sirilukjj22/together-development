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
        $Proposal = Quotation::query()->where('status_guest',1)->paginate($perPage);
        return view('billingfolio.overbill.select',compact('Proposal'));
    }
    public function proposal($id){
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
        return view('billingfolio.overbill.proposal',compact('settingCompany','Quotation','Quotation_ID','Company','Guest','Mevent','Mvat','Freelancer_member','selectproduct','unit','quantity'));
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
                        'Type'=>$value->Date_type,
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
                    'Type'=>$value->Date_type,
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
                        'Type'=>$value->Date_type,
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
                    'Type'=>$value->Date_type,
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
    public function create(Request $request ,$id){
        $Quotation = Quotation::where('id', $id)->first();
        $preview = $request->preview;
        $Quotation_ID=$request->Quotation_ID;
        $userid = Auth::user()->id;
        $data = $request->all();

        if ($preview == 1) {
            $userid = Auth::user()->id;
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
            $datarequest = [
                'Proposal_ID' => $data['Quotation_ID'] ?? null,
                'IssueDate' => $data['IssueDate'] ?? null,
                'Expiration' => $data['Expiration'] ?? null,
                'Selectdata' => $data['selectdata'] ?? null,
                'Data_ID' => $data['Guest'] ?? $data['Company'] ?? null,
                'Adult' => $data['Adult'] ?? null,
                'Children' => $data['Children'] ?? null,
                'Mevent' => $data['Mevent'] ?? null,
                'Mvat' => $data['Mvat'] ?? null,
                'DiscountAmount' => $data['DiscountAmount'] ?? null,
                'ProductIDmain' => $data['ProductIDmain'] ?? null,
                'pax' => $data['pax'] ?? null,
                'CheckProduct' => $data['CheckProduct'] ?? null,
                'Quantitymain' => $data['Quantitymain'] ?? null,
                'priceproductmain' => $data['priceproductmain'] ?? null,
                'discountmain' => $data['discountmain'] ?? null,
                'comment' => $data['comment'] ?? null,
                'PaxToTalall' => $data['PaxToTalall'] ?? null,
                'FreelancerMember' => $data['Freelancer_member'] ?? null,
                'Checkin' => $data['Checkin'] ?? null,
                'Checkout' => $data['Checkout'] ?? null,
                'Day' => $data['Day'] ?? null,
                'Night' => $data['Night'] ?? null,
                'Unitmain' => $data['Unitmain'] ?? null,
            ];
            {   //จัด product
                $quantities = $datarequest['Quantitymain'] ?? [];
                $discounts = $datarequest['discountmain'] ?? [];
                $priceUnits = $datarequest['priceproductmain'] ?? [];
                $Unitmain = $datarequest['Unitmain'] ?? [];
                $discounts = array_map(function($value) {
                    return ($value !== null) ? $value : "0";
                }, $discounts);

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

                        $discountedPrice = (($priceUnit * $discount) / 100);
                        $discountedPrices[] =  $priceUnit - $discountedPrice;

                        $total = ($quantity * $unitValue);

                        $discountedPriceTotal = $total *($priceUnit -$discountedPrice);
                        $discountedPricestotal[] = $discountedPriceTotal;

                    }
                }
                foreach ($priceUnits as $key => $price) {
                    $priceUnits[$key] = str_replace(array(',', '.00'), '', $price);
                }
                $Products = $datarequest['ProductIDmain'];
                $Productslast = $datarequest['CheckProduct'];
                $pax=$datarequest['pax'];
                $productsCount = is_array($Products) ? count($Products) : 0;
                $productslastCount = is_array($Productslast) ? count($Productslast) : 0;
                if (is_array($Products) && is_array($Productslast)) {
                    $commonValues = array_intersect($Products, $Productslast);
                    if (!empty($commonValues)) {
                        $diffFromProducts = array_diff($Products, $Productslast);
                        $diffFromProductslast = array_diff($Productslast, $Products);
                        $Products = array_merge($commonValues,$diffFromProducts,$diffFromProductslast);
                    } else {
                        $Products = array_merge($Productslast,$Products);
                    }
                }else{
                    $Products = $Productslast;
                }
                $productsArray = [];
                foreach ($Products as $index => $ProductID) {
                    $saveProduct = [
                        'Quotation_ID' => $Quotation_ID,
                        'Company_ID' => $Quotation->Company_ID,
                        'Product_ID' => $ProductID,
                        'pax' => $pax[$index] ?? 0,
                        'Issue_date' => $request->IssueDate,
                        'discount' => $discounts[$index],
                        'priceproduct' => $priceUnits[$index],
                        'netpriceproduct' => $discountedPrices[$index],
                        'totaldiscount' => $discountedPricestotal[$index],
                        'ExpirationDate' => $request->Expiration,
                        'freelanceraiffiliate' => $request->Freelancer_member,
                        'Quantity' => $quantities[$index],
                        'Unit' => $Unitmain[$index],
                        'Document_issuer' => $userid,
                    ];
                    $productsArray[] = $saveProduct;
                }
            }
            {
                $currentDate = Carbon::now();
                $ID = 'PDOB-';
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
                $newRunNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                $Overbill = $ID.$year.$month.$newRunNumber;
            }
            {
                try {
                    $Quotation_ID = $request->Quotation_ID;
                    $IssueDate = $request->IssueDate;
                    $Expiration = $request->Expiration;
                    $Selectdata = $Quotation->type_Proposal;
                    $Data_ID = $Quotation->Company_ID;
                    $Adult = $request->Adult;
                    $Children = $request->Children;
                    $Mevent = $request->Mevent;
                    $Mvat = $request->Mvat;
                    $DiscountAmount = $request->DiscountAmount;
                    $Checkin = $request->CheckinNew;
                    $Checkout = $request->CheckoutNew;
                    $Day = $request->Day;
                    $Night = $request->Night;
                    $TotalPax = $request->PaxToTalall;

                    $Head = 'รายการ';
                    if ($productsArray) {
                        $products['products'] =$productsArray;
                        $productsArray = $products['products']; // ใช้ array ที่คุณมีอยู่แล้ว
                        $productData = [];

                        foreach ($productsArray as $product) {
                            $productID = $product['Product_ID'];

                            // ค้นหาข้อมูลในฐานข้อมูลจาก Product_ID
                            $productDetails = master_product_item::LeftJoin('master_units', 'master_product_items.unit', '=', 'master_units.id')
                                ->Leftjoin('master_quantities','master_product_items.quantity','master_quantities.id')
                                ->where('master_product_items.Product_ID', $productID)
                                ->select('master_product_items.*', 'master_units.name_th as unit_name','master_quantities.name_th as quantity_name')
                                ->first();

                            $ProductName = $productDetails->name_en;
                            $unitName = $productDetails->unit_name;
                            $quantity_name = $productDetails->quantity_name;

                            if ($productDetails) {
                                $productData[] = [
                                    'Product_ID' => $productID,
                                    'Quantity' => $product['Quantity'],
                                    'Unit' => $product['Unit'],
                                    'netpriceproduct' => $product['totaldiscount'],
                                    'Product_Name' => $ProductName,
                                    'Product_Quantity' => $unitName,
                                    'Product_Unit' => $quantity_name, // หรือระบุฟิลด์ที่ต้องการจาก $productDetails
                                ];
                            }
                        }
                    }
                    $formattedProductData = [];

                    foreach ($productData as $product) {
                        $formattedPrice = number_format($product['netpriceproduct']).' '.'บาท';
                        $formattedProductData[] = 'Description : ' . $product['Product_Name'] . ' , ' . 'Quantity : ' . $product['Quantity'] . ' ' . $product['Product_Unit'] . ' , ' . 'Price Product : ' . $formattedPrice;
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
                    $datacompany = '';

                    $variables = [$QuotationID, $Issue_Date, $Expiration_Date, $fullName, $Contact_Name,$Time,$nameevent,$namevat,$Pax,$Head];

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
                    $save->Company_ID = $Overbill;
                    $save->type = 'Create';
                    $save->Category = 'Create :: Proposal Over Bill';
                    $save->content =$datacompany;
                    $save->save();
                } catch (\Throwable $e) {
                    return redirect()->route('BillingFolioOver.proposal', ['id' => $Quotation->id])->with('error',$e->getMessage());
                }
                try {
                    $Products = $datarequest['ProductIDmain'];
                    $Productslast = $datarequest['CheckProduct'];
                    $pax=$datarequest['pax'];
                    $productsCount = is_array($Products) ? count($Products) : 0;
                    $productslastCount = is_array($Productslast) ? count($Productslast) : 0;
                    if (is_array($Products) && is_array($Productslast)) {
                        $commonValues = array_intersect($Products, $Productslast);
                        if (!empty($commonValues)) {
                            $diffFromProducts = array_diff($Products, $Productslast);
                            $diffFromProductslast = array_diff($Productslast, $Products);
                            $Products = array_merge($commonValues,$diffFromProducts,$diffFromProductslast);
                        } else {
                            $Products = array_merge($Productslast,$Products);
                        }

                    }else{
                        $Products = $Productslast;
                    }
                    $quantities = $datarequest['Quantitymain'] ?? [];
                    $discounts = $datarequest['discountmain'] ?? [];
                    $priceUnits = $datarequest['priceproductmain'] ?? [];
                    $Unitmain = $datarequest['Unitmain'] ?? [];
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

                                $discountedPrice = (($priceUnit * $discount) / 100);
                                $discountedPrices[] =  $priceUnit - $discountedPrice;

                                $total = ($quantity * $unitValue);

                                $discountedPriceTotal = $total *($priceUnit -$discountedPrice);
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
                        $totalguest = $adult + $children;
                        $guest = $request->PaxToTalall;
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
                        $linkQR = $protocol . '://' . $request->getHost() . "/Document/BillingFolio/Proposal/Over/document/PDF/$id?page_shop=" . $request->input('page_shop');
                        $qrCodeImage = QrCode::format('svg')->size(200)->generate($linkQR);
                        $qrCodeBase64 = base64_encode($qrCodeImage);
                    }
                    $Proposal_ID = $datarequest['Proposal_ID'];
                    $IssueDate = $datarequest['IssueDate'];
                    $Expiration = $datarequest['Expiration'];
                    $Selectdata = $Quotation->type_Proposal;
                    $Data_ID = $Quotation->Company_ID;
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
                        'SpecialDistext'=>$SpecialDistext,
                    ];
                    $view= $template->name;
                    $pdf = FacadePdf::loadView('quotationpdf.'.$view,$data);
                    // บันทึกไฟล์ PDF
                    $path = 'Log_PDF/proposaloverbill/';
                    $pdf->save($path . $Quotation_ID . '.pdf');
                } catch (\Throwable $e) {
                    log_company::where('Category','Create :: Proposal Over Bill')->delete();
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
                        $DiscountAmount = $datarequest['DiscountAmount'];
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
                    $savePDF->Quotation_ID = $Overbill;
                    $savePDF->QuotationType = 'ProposalOverBill';
                    $savePDF->Company_Name = $fullName;
                    $savePDF->Approve_date = $formattedDate;
                    $savePDF->Approve_time = $formattedTime;
                    $savePDF->save();
                } catch (\Throwable $e) {

                    log_company::where('Category','Create :: Proposal Over Bill')->delete();
                    $path = 'Log_PDF/proposaloverbill/';
                    $file = $path . $Quotation_ID . '.pdf';
                    if (is_file($file)) {
                        unlink($file); // ลบไฟล์
                    }
                    log::where('Quotation_ID',$Overbill)->delete();
                    return redirect()->route('BillingFolioOver.proposal', ['id' => $Quotation->id])->with('error',$e->getMessage());
                }
                {   //จัด product
                    $quantities = $datarequest['Quantitymain'] ?? [];
                    $discounts = $datarequest['discountmain'] ?? [];
                    $priceUnits = $datarequest['priceproductmain'] ?? [];
                    $Unitmain = $datarequest['Unitmain'] ?? [];
                    $discounts = array_map(function($value) {
                        return ($value !== null) ? $value : "0";
                    }, $discounts);

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

                            $discountedPrice = (($priceUnit * $discount) / 100);
                            $discountedPrices[] =  $priceUnit - $discountedPrice;

                            $total = ($quantity * $unitValue);

                            $discountedPriceTotal = $total *($priceUnit -$discountedPrice);
                            $discountedPricestotal[] = $discountedPriceTotal;

                        }
                    }
                    foreach ($priceUnits as $key => $price) {
                        $priceUnits[$key] = str_replace(array(',', '.00'), '', $price);
                    }
                    $Products = $datarequest['ProductIDmain'];
                    $Productslast = $datarequest['CheckProduct'];
                    $pax=$datarequest['pax'];
                    $productsCount = is_array($Products) ? count($Products) : 0;
                    $productslastCount = is_array($Productslast) ? count($Productslast) : 0;
                    if (is_array($Products) && is_array($Productslast)) {
                        $commonValues = array_intersect($Products, $Productslast);
                        if (!empty($commonValues)) {
                            $diffFromProducts = array_diff($Products, $Productslast);
                            $diffFromProductslast = array_diff($Productslast, $Products);
                            $Products = array_merge($commonValues,$diffFromProducts,$diffFromProductslast);
                        } else {
                            $Products = array_merge($Productslast,$Products);
                        }
                    }else{
                        $Products = $Productslast;
                    }
                    $productsArray = [];
                    foreach ($Products as $index => $ProductID) {
                        $saveProduct = [
                            'Quotation_ID' => $Quotation_ID,
                            'Company_ID' => $Quotation->Company_ID,
                            'Product_ID' => $ProductID,
                            'pax' => $pax[$index] ?? 0,
                            'Issue_date' => $request->IssueDate,
                            'discount' => $discounts[$index],
                            'priceproduct' => $priceUnits[$index],
                            'netpriceproduct' => $discountedPrices[$index],
                            'totaldiscount' => $discountedPricestotal[$index],
                            'ExpirationDate' => $request->Expiration,
                            'freelanceraiffiliate' => $request->Freelancer_member,
                            'Quantity' => $quantities[$index],
                            'Unit' => $Unitmain[$index],
                            'Document_issuer' => $userid,
                        ];
                        $productsArray[] = $saveProduct;
                    }
                }
                try {
                    $save = new proposal_overbill();
                    $save->Overbill_ID = $Overbill;
                    $save->Quotation_ID = $Quotation_ID;
                    $save->Company_ID = $Quotation->Company_ID;
                    $save->company_contact = $Quotation->company_contact;
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
                    $save->AddTax = $AddTax;
                    $save->Nettotal = $Nettotal;
                    $save->total = $Nettotal;
                    $save->vat_type = $request->Mvat;
                    $save->type_Proposal = $Quotation->type_Proposal;
                    $save->issue_date = $request->IssueDate;
                    $save->ComRateCode = $request->Company_Discount;
                    $save->Expirationdate = $request->Expiration;
                    $save->Operated_by = $userid;
                    $save->comment = $request->comment;
                    $save->Date_type = $request->Date_type;
                    if ($Add_discount == 0 && $SpecialDiscountBath == 0) {
                        $save->SpecialDiscount = $SpecialDiscount;
                        $save->SpecialDiscountBath = $SpecialDiscountBath;
                        $save->additional_discount = $Add_discount;
                        $save->status_document = 1;
                        $save->Confirm_by = 'Auto';
                        $save->save();
                    }else {
                        $save->SpecialDiscount = $SpecialDiscount;
                        $save->SpecialDiscountBath = $SpecialDiscountBath;
                        $save->additional_discount = $Add_discount;
                        $save->status_document = 2;
                        $save->Confirm_by = '-';
                        $save->save();
                    }
                    if ($Products !== null) {
                        foreach ($Products as $index => $ProductID) {
                            $saveProduct = new document_proposal_overbill();
                            $saveProduct->Quotation_ID = $Quotation_ID;
                            $saveProduct->Company_ID = $Quotation->Company_ID;
                            $saveProduct->Product_ID = $ProductID;
                            $paxValue = $pax[$index] ?? 0;
                            $saveProduct->pax = $paxValue;
                            $saveProduct->Issue_date = $request->IssueDate;
                            $saveProduct->discount =$discounts[$index];
                            $saveProduct->priceproduct =$priceUnits[$index];
                            $saveProduct->netpriceproduct =$discountedPrices[$index];
                            $saveProduct->totaldiscount =$discountedPricestotal[$index];
                            $saveProduct->ExpirationDate = $request->Expiration;
                            $saveProduct->freelanceraiffiliate = $request->Freelancer_member;
                            $saveProduct->Quantity = $quantities[$index];
                            $saveProduct->Unit = $Unitmain[$index];
                            $saveProduct->Document_issuer = $userid;
                            $saveProduct->save();
                        }
                    }
                } catch (\Throwable $e) {
                    log_company::where('Category','Create :: Proposal Over Bill')->delete();
                    $path = 'Log_PDF/proposaloverbill/';
                    $file = $path . $Quotation_ID . '.pdf';
                    if (is_file($file)) {
                        unlink($file); // ลบไฟล์
                    }
                    log::where('Quotation_ID',$Overbill)->delete();
                    proposal_overbill::where('Overbill_ID',$Overbill)->delete();
                    return redirect()->route('BillingFolioOver.proposal', ['id' => $Quotation->id])->with('error',$e->getMessage());
                }
                return redirect()->route('BillingFolioOver.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
            }

        }



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

}
