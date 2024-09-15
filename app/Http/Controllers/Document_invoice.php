<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
class Document_invoice extends Controller
{
    public function index()
    {
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $userid = Auth::user()->id;
        $Approved = Quotation::query()
        ->leftJoin('document_invoice', 'quotation.Refler_ID', '=', 'document_invoice.Refler_ID')
        ->where('quotation.Operated_by', $userid)
        ->where('quotation.status_guest', 1)
        ->select(
            'quotation.*',
            'document_invoice.Quotation_ID as QID',
            'document_invoice.document_status',  // Separate this field for clarity
            DB::raw('1 as status'),
            DB::raw('COALESCE(SUM(CASE WHEN document_invoice.document_status IN (1, 2) THEN document_invoice.sumpayment ELSE 0 END), 0) as total_payment'),
            DB::raw('MIN(CASE WHEN document_invoice.document_status IN (1, 2) THEN CAST(REPLACE(document_invoice.balance, ",", "") AS UNSIGNED) ELSE NULL END) as min_balance')
        )
        ->groupBy('quotation.Quotation_ID','quotation.Operated_by','quotation.status_guest')
        ->paginate($perPage);
        $Approvedcount = Quotation::query()->where('Operated_by',$userid)->where('status_guest',1)->count();

        $invoice = document_invoices::query()->where('Operated_by',$userid)->where('document_status',1)->get();
        $invoicecheck = document_invoices::query()->where('Operated_by',$userid)->get();
       // ดึงข้อมูลจาก document_invoices รวมถึง Quotation_ID, total และ sumpayment
        $invoicecount = document_invoices::query()->where('Operated_by',$userid)->where('document_status',1)->count();
        $Complete = document_invoices::query()->where('Operated_by',$userid)->where('document_status',2)->where('status_receive',1)->get();

        $Completecount = document_invoices::query()->where('Operated_by',$userid)->where('document_status',2)->where('status_receive',1)->count();
        $Cancel = document_invoices::query()->where('Operated_by',$userid)->where('document_status',0)->get();
        $Cancelcount =document_invoices::query()->where('Operated_by',$userid)->where('document_status',0)->count();
        return view('document_invoice.index',compact('Approved','Approvedcount','invoice','invoicecount','Complete','Completecount','Cancel','Cancelcount','invoicecheck'));
    }
    //---------------------------------table-----------------
    public function  paginate_table_invoice(Request $request)
    {
        $perPage = (int)$request->perPage;
        $userid = Auth::user()->id;
        $data = [];
        $permissionid = Auth::user()->permission;
        if ($perPage == 10) {
            $data_query = Quotation::query()
                ->leftJoin('document_invoice', 'quotation.Refler_ID', '=', 'document_invoice.Refler_ID')
                ->where('quotation.Operated_by', $userid)
                ->where('quotation.status_guest', 1)
                ->select(
                    'quotation.*',
                    'document_invoice.Quotation_ID as QID',
                    'document_invoice.document_status',  // Separate this field for clarity
                    DB::raw('1 as status'),
                    DB::raw('COALESCE(SUM(CASE WHEN document_invoice.document_status IN (1, 2) THEN document_invoice.sumpayment ELSE 0 END), 0) as total_payment'),
                    DB::raw('MIN(CASE WHEN document_invoice.document_status IN (1, 2) THEN CAST(REPLACE(document_invoice.balance, ",", "") AS UNSIGNED) ELSE NULL END) as min_balance')
                )
                ->groupBy('quotation.Quotation_ID','quotation.Operated_by','quotation.status_guest')
                ->limit($request->page.'0')
                ->get();
            $invoice = document_invoices::query()->where('Operated_by',$userid)->where('document_status',1)->get();
            $invoicecheck = document_invoices::query()->where('Operated_by',$userid)->get();
        } else {
            $data_query = Quotation::query()
                ->leftJoin('document_invoice', 'quotation.Refler_ID', '=', 'document_invoice.Refler_ID')
                ->where('quotation.Operated_by', $userid)
                ->where('quotation.status_guest', 1)
                ->select(
                    'quotation.*',
                    'document_invoice.Quotation_ID as QID',
                    'document_invoice.document_status',  // Separate this field for clarity
                    DB::raw('1 as status'),
                    DB::raw('COALESCE(SUM(CASE WHEN document_invoice.document_status IN (1, 2) THEN document_invoice.sumpayment ELSE 0 END), 0) as total_payment'),
                    DB::raw('MIN(CASE WHEN document_invoice.document_status IN (1, 2) THEN CAST(REPLACE(document_invoice.balance, ",", "") AS UNSIGNED) ELSE NULL END) as min_balance')
                )
                ->groupBy('quotation.Quotation_ID','quotation.Operated_by','quotation.status_guest')
                ->paginate($perPage);
            $invoice = document_invoices::query()->where('Operated_by',$userid)->where('document_status',1)->get();
            $invoicecheck = document_invoices::query()->where('Operated_by',$userid)->get();
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

                    $btn_status = '<span class="badge rounded-pill bg-success">Proposal</span>';
                    $rolePermission = Auth::user()->rolePermissionData(Auth::user()->id);
                    $canViewProposal = Auth::user()->roleMenuView('Proposal', Auth::user()->id);
                    $canEditProposal = Auth::user()->roleMenuEdit('Proposal', Auth::user()->id);

                    $btn_action = '<div class="dropdown">';
                    $btn_action .= '<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>';
                    $btn_action .= '<ul class="dropdown-menu border-0 shadow p-3">';

                    if ($canViewProposal) {
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" target="_blank" href="' . url('/Proposal/cover/document/PDF/' . $value->id) . '">Export</a></li>';
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Proposal/view/quotation/LOG/' . $value->id) . '">LOG</a></li>';
                    }

                    if ($rolePermission > 0) {
                        if (!empty($invoice) && $invoice->count() == 0) {
                            if ($canEditProposal) {
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/Generate/' . $value->id) . '">Generate</a></li>';
                            }
                        } else {
                            if ($canEditProposal == 1) {
                                $hasStatusReceiveZero = false;

                                foreach ($invoicecheck as $item2) {
                                    if ($item->QID == $item2->Quotation_ID && $item2->status_receive == 0) {
                                        $hasStatusReceiveZero = true;
                                        break; // หยุดการลูปทันทีเมื่อพบเงื่อนไขที่ต้องการ
                                    }
                                }

                                if (!$hasStatusReceiveZero) {
                                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/Generate/' . $value->id) . '">Generate</a></li>';
                                }
                            }
                        }
                    }

                    $btn_action .= '</ul>';
                    $btn_action .= '</div>';


                    $data[] = [
                        'number' => $key +1,
                        'Proposal' => $value->Quotation_ID,
                        'Company_Name' => $name,
                        'IssueDate' => $value->issue_date,
                        'ExpirationDate' => $value->Expirationdate,
                        'Amount' => number_format($value->Nettotal),
                        'Deposit' => number_format($value->total_payment ?? 0, 2),
                        'Balance' => number_format($value->min_balance ?? 0, 2),
                        'Approve' => $value->Confirm_by == null ? 'Auto' : @$value->userConfirm->name,
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
    public function search_table_invoice(Request $request)
    {
        $perPage = (int)$request->perPage;
        $search_value = $request->search_value;
        $guest_profile = $request->guest_profile;
        $userid = Auth::user()->id;

        if ($search_value) {
            $data_query = Quotation::query()
                ->leftJoin('document_invoice', 'quotation.Refler_ID', '=', 'document_invoice.Refler_ID')
                ->where('quotation.Operated_by', $userid)
                ->where('quotation.status_guest', 1)
                ->select(
                    'quotation.*',
                    'document_invoice.Quotation_ID as QID',
                    'document_invoice.document_status',
                    DB::raw('1 as status'),
                    DB::raw('COALESCE(SUM(CASE WHEN document_invoice.document_status IN (1, 2) THEN document_invoice.sumpayment ELSE 0 END), 0) as total_payment'),
                    DB::raw('MIN(CASE WHEN document_invoice.document_status IN (1, 2) THEN CAST(REPLACE(document_invoice.balance, ",", "") AS UNSIGNED) ELSE NULL END) as min_balance')
                )
                ->where('quotation.Quotation_ID', 'LIKE', '%'.$search_value.'%')
                ->groupBy('quotation.Quotation_ID', 'quotation.Operated_by', 'quotation.status_guest')
                ->paginate($perPage);

            $invoice = document_invoices::query()->where('Operated_by', $userid)->where('document_status', 1)->get();
            $invoicecheck = document_invoices::query()->where('Operated_by', $userid)->get();
        } else {
            $perPageS = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;

            $data_query = Quotation::query()
                ->leftJoin('document_invoice', 'quotation.Refler_ID', '=', 'document_invoice.Refler_ID')
                ->where('quotation.Operated_by', $userid)
                ->where('quotation.status_guest', 1)
                ->select(
                    'quotation.*',
                    'document_invoice.Quotation_ID as QID',
                    'document_invoice.document_status',
                    DB::raw('1 as status'),
                    DB::raw('COALESCE(SUM(CASE WHEN document_invoice.document_status IN (1, 2) THEN document_invoice.sumpayment ELSE 0 END), 0) as total_payment'),
                    DB::raw('MIN(CASE WHEN document_invoice.document_status IN (1, 2) THEN CAST(REPLACE(document_invoice.balance, ",", "") AS UNSIGNED) ELSE NULL END) as min_balance')
                )
                ->groupBy('quotation.Quotation_ID', 'quotation.Operated_by', 'quotation.status_guest')
                ->paginate($perPageS);

            $invoice = document_invoices::query()->where('Operated_by', $userid)->where('document_status', 1)->get();
            $invoicecheck = document_invoices::query()->where('Operated_by', $userid)->get();
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

                $btn_status = '<span class="badge rounded-pill bg-success">Proposal</span>';
                $rolePermission = Auth::user()->rolePermissionData(Auth::user()->id);
                $canViewProposal = Auth::user()->roleMenuView('Proposal', Auth::user()->id);
                $canEditProposal = Auth::user()->roleMenuEdit('Proposal', Auth::user()->id);

                $btn_action = '<div class="dropdown">';
                $btn_action .= '<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>';
                $btn_action .= '<ul class="dropdown-menu border-0 shadow p-3">';

                if ($canViewProposal) {
                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" target="_blank" href="' . url('/Proposal/cover/document/PDF/' . $value->id) . '">Export</a></li>';
                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Proposal/view/quotation/LOG/' . $value->id) . '">LOG</a></li>';
                }

                if ($rolePermission > 0) {
                    if (!empty($invoice) && $invoice->count() == 0) {
                        if ($canEditProposal) {
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/Generate/' . $value->id) . '">Generate</a></li>';
                        }
                    } else {
                        if ($canEditProposal == 1) {
                            $hasStatusReceiveZero = false;

                            foreach ($invoicecheck as $item2) {
                                if ($item->QID == $item2->Quotation_ID && $item2->status_receive == 0) {
                                    $hasStatusReceiveZero = true;
                                    break; // หยุดการลูปทันทีเมื่อพบเงื่อนไขที่ต้องการ
                                }
                            }

                            if (!$hasStatusReceiveZero) {
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/Generate/' . $value->id) . '">Generate</a></li>';
                            }
                        }
                    }
                }

                $btn_action .= '</ul>';
                $btn_action .= '</div>';

                $data[] = [
                    'number' => $key +1,
                    'Proposal' => $value->Quotation_ID,
                    'Company_Name' => $name,
                    'IssueDate' => $value->issue_date,
                    'ExpirationDate' => $value->Expirationdate,
                    'Amount' => number_format($value->Nettotal),
                    'Deposit' => number_format($value->total_payment ?? 0, 2),
                    'Balance' => number_format($value->min_balance ?? 0, 2),
                    'Approve' => $value->Confirm_by == null ? 'Auto' : @$value->userConfirm->name,
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
    public function Generate($id){

        $currentDate = Carbon::now();
        $ID = 'PI-';
        $formattedDate = Carbon::parse($currentDate);       // วันที่
        $month = $formattedDate->format('m'); // เดือน
        $year = $formattedDate->format('y');
        $lastRun = document_invoices::latest()->first();
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
        $InvoiceID = $ID.$year.$month.$newRunNumber;
        $Quotation = Quotation::where('id', $id)->first();
        $QuotationID = $Quotation->Quotation_ID;
        if ($Quotation->type_Proposal == 'Company') {
            $CompanyID = $Quotation->Company_ID;
            $Company = companys::where('Profile_ID',$CompanyID)->first();
            $Company_typeID=$Company->Company_type;
            $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
            if ($comtype->name_th =="บริษัทจำกัด") {
                $comtypefullname = "บริษัท ". $Company->Company_Name . " จำกัด";
            }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                $comtypefullname = "บริษัท ". $Company->Company_Name . " จำกัด (มหาชน)";
            }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                $comtypefullname = "ห้างหุ้นส่วนจำกัด ". $Company->Company_Name ;
            }else {
                $comtypefullname = $Company->Company_Name;
            }
            $CityID=$Company->City;
            $amphuresID = $Company->Amphures;
            $TambonID = $Company->Tambon;
            $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
            $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
            $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
            $company_fax = company_fax::where('Profile_ID',$CompanyID)->where('Sequence','main')->first();
            $company_phone = company_phone::where('Profile_ID',$CompanyID)->where('Sequence','main')->first();
            $Contact_name = representative::where('Company_ID',$CompanyID)->where('status',1)->first();
            $profilecontact = $Contact_name->Profile_ID;
            $Contact_phone = representative_phone::where('Company_ID',$CompanyID)->where('Profile_ID',$profilecontact)->where('Sequence','main')->first();
        }else{
            $Data_ID = $Quotation->Company_ID;
            $Company = Guest::where('Profile_ID',$Data_ID)->first();
            $prename = $Company->preface;
            $First_name = $Company->First_name;
            $Last_name = $Company->Last_name;
            $Address = $Company->Address;
            $Email = $Company->Email;
            $Taxpayer_Identification = $Company->Identification_Number;
            $prefix = master_document::where('id',$prename)->where('Category','Mprename')->where('status',1)->first();
            $name = $prefix->name_th;
            $comtypefullname = $name.' '.$First_name.' '.$Last_name;

            $Contact_name =0;
            $profilecontact = 0;
            $Contact_phone=0;
            $company_fax =0;
            $CompanyID = 0;
            //-------------ที่อยู่
            $CityID=$Company->City;
            $amphuresID = $Company->Amphures;
            $TambonID = $Company->Tambon;
            $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
            $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
            $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
            $Fax_number = '-';
            $company_phone = phone_guest::where('Profile_ID',$Data_ID)->where('Sequence','main')->first();

        }
        $Checkin = $Quotation->checkin;
        $Checkout = $Quotation->checkout;
        if ($Checkin) {
            $checkin = Carbon::parse($Checkin)->format('d/m/Y');
            $checkout = Carbon::parse($Checkout)->format('d/m/Y');
        }else{
            $checkin = '-';
            $checkout = '-';
        }

        $invoices =document_invoices::where('Quotation_ID',$QuotationID)->whereIn('document_status',[1,2])->latest()->first();
        $settingCompany = Master_company::orderBy('id', 'desc')->first();
        if ($invoices) {
            $deposit = $invoices->deposit;
            $Deposit =$deposit+ 1;
            $balance = $invoices->balance;
            $parts = explode('-', $QuotationID);
            $cleanedID = $parts[0] . '-' . $parts[1];
            $Refler_ID = $cleanedID;
        }else{

            $parts = explode('-', $QuotationID);
            $cleanedID = $parts[0] . '-' . $parts[1];
            $invoices =document_invoices::where('Quotation_ID',$cleanedID)->where('document_status',1)->latest()->first();
            $Deposit = 1;
            $balance = 0;
            $Refler_ID = $QuotationID;
        }
        return view('document_invoice.create',compact('QuotationID','comtypefullname','provinceNames','amphuresID','InvoiceID','Contact_name','Company'
        ,'Refler_ID','TambonID','company_phone','company_fax','Contact_phone','Quotation','checkin','checkout','CompanyID','Deposit','settingCompany','invoices','balance'));
    }
    public function save(Request $request){
        try {
            $data = $request->all();
            $preview = $request->preview;
            $save = $request->save;
            if ($preview == 1 &&  $save == null ) {

            }else{
                {
                    //pdf
                }
                {
                    //save
                }

            }
            dd($data);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
