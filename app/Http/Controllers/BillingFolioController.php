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
use App\Models\log_company;
use App\Models\document_quotation;
use App\Models\company_tax;
use App\Models\guest_tax;
use Illuminate\Support\Arr;
use App\Models\master_document_sheet;
use App\Models\proposal_overbill;
use App\Models\document_proposal_overbill;
use App\Models\Master_additional;
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
use App\Models\receive_cheque;
class BillingFolioController extends Controller
{
    public function index()
    {
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $userid = Auth::user()->id;
        $Approved = receive_payment::query()->where('type','billing')->paginate($perPage);
        $ApprovedCount = receive_payment::query()->where('type','billing')->count();
        $ComplateCount = Quotation::query()->where('quotation.status_document', 9)->count();
        $Complate = Quotation::query()
        ->leftJoin('document_receive', 'quotation.Quotation_ID', '=', 'document_receive.Quotation_ID')
        ->where('quotation.status_document', 9)
        ->select(
            'quotation.*',
            DB::raw('COUNT(document_receive.Quotation_ID) as receive_count')
        )
        ->groupBy('quotation.Quotation_ID', 'quotation.status_document', 'quotation.status_receive')
        ->paginate($perPage);
        return view('billingfolio.index',compact('Approved','Complate','ComplateCount','ApprovedCount'));
    }
    //---------------------------------table-----------------
    public function  paginate_table_billing(Request $request)
    {
        $perPage = (int)$request->perPage;
        $userid = Auth::user()->id;
        $data = [];
        $permissionid = Auth::user()->permission;
        if ($perPage == 10) {
            $data_query = receive_payment::query()->where('type','billing')
                ->limit($request->page.'0')
                ->get();
        } else {
            $data_query = receive_payment::query()->where('type','billing')
                ->paginate($perPage);
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
                        $name = '<td>' . (isset($value->company->Company_Name) ? $value->company->Company_Name : '') . '</td>';
                    } elseif ($value->type_Proposal == 'Guest') {
                        $name = '<td>' . (isset($value->guest->First_name) && isset($value->guest->Last_name) ? $value->guest->First_name . ' ' . $value->guest->Last_name : '') . '</td>';
                    } elseif ($value->type_Proposal == 'company_tax') {
                        $name = '<td>' . (isset($value->company_tax->Companny_name) ? $value->company_tax->Companny_name : (isset($value->company_tax->first_name) && isset($value->company_tax->last_name) ? $value->company_tax->first_name . ' ' . $value->company_tax->last_name : '')) . '</td>';
                    } elseif ($value->type_Proposal == 'guest_tax') {
                        $name = '<td>' . (isset($value->guest_tax->Company_name) ? $value->guest_tax->Company_name : (isset($value->guest_tax->first_name) && isset($value->guest_tax->last_name) ? $value->guest_tax->first_name . ' ' . $value->guest_tax->last_name : '')) . '</td>';
                    }


                    $btn_status = '<span class="badge rounded-pill bg-success">Confirm</span>';
                    $rolePermission = Auth::user()->rolePermissionData(Auth::user()->id);
                    $canViewProposal = Auth::user()->roleMenuView('Billing Folio', Auth::user()->id);
                    $canEditProposal = Auth::user()->roleMenuEdit('Billing Folio', Auth::user()->id);
                    $CreateBy = Auth::user()->id;
                    $isOperatedByCreator = $value->Operated_by == $CreateBy;

                    $btn_action = '<div class="dropdown">';
                    $btn_action .= '<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>';
                    $btn_action .= '<ul class="dropdown-menu border-0 shadow p-3">';
                    if ($canViewProposal) {
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" target="_blank" href="' . url('/Document/BillingFolio/Proposal/invoice/view/' . $value->id) . '">Export</a></li>';
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/BillingFolio/Proposal/invoice/log/' . $value->id) . '">LOG</a></li>';

                    } if ($rolePermission == 1 && $isOperatedByCreator) {
                        if ($canEditProposal) {
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/BillingFolio/Proposal/invoice/Generate/Paid/Edit/' . $value->id) . '">Edit</a></li>';
                        }
                    } elseif ($rolePermission == 2) {
                        if ($canEditProposal) {
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/BillingFolio/Proposal/invoice/Generate/Paid/Edit/' . $value->id) . '">Edit</a></li>';
                        }
                    } elseif ($rolePermission == 3) {
                        if ($canEditProposal) {
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/BillingFolio/Proposal/invoice/Generate/Paid/Edit/' . $value->id) . '">Edit</a></li>';
                        }
                    }
                    $btn_action .= '</ul>';
                    $btn_action .= '</div>';

                    $data[] = [
                        'number' => $key +1,
                        'Proposal' => $value->Receipt_ID,
                        'Company_Name' => $name,
                        'IssueDate' => $value->roomNo,
                        'ExpirationDate' => $value->paymentDate,
                        'Amount' => number_format($value->Amount),
                        'Balance' => $value->category,
                        'Approve' =>  @$value->userOperated->name,
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
    public function search_table_billing(Request $request)
    {
        $perPage = (int)$request->perPage;
        $search_value = $request->search_value;
        $guest_profile = $request->guest_profile;
        $userid = Auth::user()->id;

        if ($search_value) {
            $data_query = receive_payment::query()->where('type','billing')
            ->where(function ($query) use ($search_value) {
                // ค้นหาในผู้ใช้ (userOperated) ตามชื่อ
                $query->whereHas('userOperated', function ($q) use ($search_value) {
                    $q->where('name', 'LIKE', '%'.$search_value.'%');
                })
                // ค้นหาตาม Receipt_ID หรือ Amount
                ->orWhere('receive_payment.Receipt_ID', 'LIKE', '%'.$search_value.'%')
                ->orWhere('receive_payment.Amount', 'LIKE', '%'.$search_value.'%');
            })
            ->paginate($perPage);

        } else {
            $perPageS = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;

            $data_query = receive_payment::query()->where('type','billing')
                ->paginate($perPageS);
        }



        $data = [];
        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $btn_action = "";
                $btn_status = "";
                $name ="";
                if ($value->type_Proposal == 'Company') {
                    $name = '<td>' . (isset($value->company->Company_Name) ? $value->company->Company_Name : '') . '</td>';
                } elseif ($value->type_Proposal == 'Guest') {
                    $name = '<td>' . (isset($value->guest->First_name) && isset($value->guest->Last_name) ? $value->guest->First_name . ' ' . $value->guest->Last_name : '') . '</td>';
                } elseif ($value->type_Proposal == 'company_tax') {
                    $name = '<td>' . (isset($value->company_tax->Companny_name) ? $value->company_tax->Companny_name : (isset($value->company_tax->first_name) && isset($value->company_tax->last_name) ? $value->company_tax->first_name . ' ' . $value->company_tax->last_name : '')) . '</td>';
                } elseif ($value->type_Proposal == 'guest_tax') {
                    $name = '<td>' . (isset($value->guest_tax->Company_name) ? $value->guest_tax->Company_name : (isset($value->guest_tax->first_name) && isset($value->guest_tax->last_name) ? $value->guest_tax->first_name . ' ' . $value->guest_tax->last_name : '')) . '</td>';
                }


                $btn_status = '<span class="badge rounded-pill bg-success">Confirm</span>';
                $rolePermission = Auth::user()->rolePermissionData(Auth::user()->id);
                $canViewProposal = Auth::user()->roleMenuView('Billing Folio', Auth::user()->id);
                $canEditProposal = Auth::user()->roleMenuEdit('Billing Folio', Auth::user()->id);
                $CreateBy = Auth::user()->id;
                $isOperatedByCreator = $value->Operated_by == $CreateBy;

                $btn_action = '<div class="dropdown">';
                $btn_action .= '<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>';
                $btn_action .= '<ul class="dropdown-menu border-0 shadow p-3">';
                if ($canViewProposal) {
                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" target="_blank" href="' . url('/Document/BillingFolio/Proposal/invoice/view/' . $value->id) . '">Export</a></li>';
                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/BillingFolio/Proposal/invoice/log/' . $value->id) . '">LOG</a></li>';

                } if ($rolePermission == 1 && $isOperatedByCreator) {
                    if ($canEditProposal) {
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/BillingFolio/Proposal/invoice/Generate/Paid/Edit/' . $value->id) . '">Edit</a></li>';
                    }
                } elseif ($rolePermission == 2) {
                    if ($canEditProposal) {
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/BillingFolio/Proposal/invoice/Generate/Paid/Edit/' . $value->id) . '">Edit</a></li>';
                    }
                } elseif ($rolePermission == 3) {
                    if ($canEditProposal) {
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/BillingFolio/Proposal/invoice/Generate/Paid/Edit/' . $value->id) . '">Edit</a></li>';
                    }
                }
                $btn_action .= '</ul>';
                $btn_action .= '</div>';
                $data[] = [
                    'number' => $key +1,
                    'Proposal' => $value->Receipt_ID,
                    'Company_Name' => $name,
                    'IssueDate' => $value->roomNo,
                    'ExpirationDate' => $value->paymentDate,
                    'Amount' => number_format($value->Amount),
                    'Balance' => $value->category,
                    'Approve' =>  @$value->userOperated->name,
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

    public function issuebill()
    {
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $userid = Auth::user()->id;
        $Approved = Quotation::query()
        ->leftJoin('document_receive', 'quotation.Quotation_ID', '=', 'document_receive.Quotation_ID')
        ->where('quotation.status_guest', 1)
        ->select(
            'quotation.*',
            DB::raw('SUM(document_receive.Amount) as receive_amount'),
        )
        ->groupBy('quotation.Quotation_ID', 'quotation.status_guest', 'quotation.status_receive')
        ->paginate($perPage);
        return view('billingfolio.proposal',compact('Approved'));
    }
    //---------------------------------table-----------------
    public function  paginate_table_billingpd(Request $request)
    {
        $perPage = (int)$request->perPage;
        $userid = Auth::user()->id;
        $data = [];
        $permissionid = Auth::user()->permission;
        if ($perPage == 10) {
            $data_query = Quotation::query()
                ->leftJoin('document_receive', 'quotation.Quotation_ID', '=', 'document_receive.Quotation_ID')
                ->where('quotation.status_guest', 1)
                ->select(
                    'quotation.*',
                    DB::raw('SUM(document_receive.Amount) as receive_amount'),
                )
                ->groupBy('quotation.Quotation_ID', 'quotation.status_guest')
                ->limit($request->page.'0')
                ->get();
        } else {
            $data_query = Quotation::query()
                ->leftJoin('document_receive', 'quotation.Quotation_ID', '=', 'document_receive.Quotation_ID')
                ->where('quotation.status_guest', 1)
                ->select(
                    'quotation.*',
                    DB::raw('SUM(document_receive.Amount) as receive_amount'),
                )
                ->groupBy('quotation.Quotation_ID', 'quotation.status_guest')
                ->paginate($perPage);
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



                    $btn_action = '<button type="button" class="btn btn-color-green lift btn_modal" href="' . url('/Document/BillingFolio/Proposal/invoice/CheckPI/' . $value->id) . '" >
                                    Select
                                    </button>';
                    $data[] = [
                        'number' => $key +1,
                        'Proposal' => $value->Quotation_ID,
                        'Company_Name' => $name,
                        'IssueDate' => $value->issue_date,
                        'ExpirationDate' => $value->Expirationdate,
                        'Amount' => number_format($value->Nettotal),
                        'Deposit' => number_format($value->receive_amount ?? 0, 2),
                        'Approve' => empty($value->Confirm_by) ? 'Auto' : ($value->userConfirm->name ?? 'Auto'),
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
    public function search_table_billingpd(Request $request)
    {
        $perPage = (int)$request->perPage;
        $search_value = $request->search_value;
        $guest_profile = $request->guest_profile;
        $userid = Auth::user()->id;

        if ($search_value) {
            $data_query = Quotation::query()
                ->leftJoin('document_receive', 'quotation.Quotation_ID', '=', 'document_receive.Quotation_ID')
                ->where('quotation.status_guest', 1)
                ->select(
                    'quotation.*',
                    DB::raw('SUM(document_receive.Amount) as receive_amount'),
                )
                ->where('quotation.Quotation_ID', 'LIKE', '%'.$search_value.'%')
                ->groupBy('quotation.Quotation_ID', 'quotation.status_guest', 'quotation.status_receive')
                ->paginate($perPage);
        } else {
            $perPageS = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;

            $data_query = Quotation::query()
                ->leftJoin('document_receive', 'quotation.Quotation_ID', '=', 'document_receive.Quotation_ID')
                ->where('quotation.status_guest', 1)
                ->select(
                    'quotation.*',
                    DB::raw('SUM(document_receive.Amount) as receive_amount'),
                )
                ->where('quotation.Quotation_ID', 'LIKE', '%'.$search_value.'%')
                ->groupBy('quotation.Quotation_ID', 'quotation.status_guest', 'quotation.status_receive')
                ->paginate($perPageS);
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

                $btn_action = '<button type="button" class="btn btn-color-green lift btn_modal" href="' . url('/Document/BillingFolio/Proposal/invoice/CheckPI/' . $value->id) . '" >
                                                Select
                                            </button>';
                $data[] = [
                    'number' => $key +1,
                    'Proposal' => $value->Quotation_ID,
                    'Company_Name' => $name,
                    'IssueDate' => $value->issue_date,
                    'ExpirationDate' => $value->Expirationdate,
                    'Amount' => number_format($value->Nettotal),
                    'Deposit' => number_format($value->receive_amount ?? 0, 2),
                    'Approve' => empty($value->Confirm_by) ? 'Auto' : ($value->userConfirm->name ?? 'Auto'),
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
    public function CheckPI($id)
    {
        $userid = Auth::user()->id;
        $ids = $id;
        $Proposal = Quotation::where('id',$id)->first();
        $ProposalID = $Proposal->id;
        $Proposal_ID = $Proposal->Quotation_ID;
        $totalAmount = $Proposal->Nettotal;
        $vat = $Proposal->vat_type;
        $nameid = $Proposal->Company_ID;
        $SpecialDiscountBath = $Proposal->SpecialDiscountBath;
        $SpecialDiscount = $Proposal->SpecialDiscount;
        $subtotal = 0;
        $beforeTax =0;
        $AddTax =0;
        $Nettotal =0;
        $total =0;
        $totalreceipt =0;
        $totalreceiptre =0;
        if ($vat == 50) {
            $total =  $totalAmount;
            $subtotal = $totalAmount-$SpecialDiscountBath;
            $beforeTax = $subtotal/1.07;
            $AddTax = $subtotal-$beforeTax;
            $Nettotal = $subtotal;

        }elseif ($vat == 51) {
            $total =  $totalAmount;
            $subtotal = $totalAmount-$SpecialDiscountBath;
            $Nettotal = $subtotal;
        }elseif ($vat == 52) {
            $total =  $totalAmount;
            $subtotal = $totalAmount-$SpecialDiscountBath;
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
        $invoices = document_invoices::where('Quotation_ID', $Proposal_ID)->where('Paid',0)->get();
        if ($invoices->contains('Paid', 0)) {
            // ถ้า status มีค่าเป็น 0 อย่างน้อยหนึ่งรายการ
            $status = 0;
        } else {
            $status = 1;
        }
        $Receipt = receive_payment::where('Quotation_ID', $Proposal_ID)->get();
        $totalReceipt = 0;
        foreach ($Receipt as $item) {
            $totalReceipt +=  $item->Amount;
        }
        //-----------------------------------------------
        $room = document_quotation::where('Quotation_ID',$Proposal_ID)->where('Product_ID', 'LIKE', 'R' . '%')->get();
        $Meals = document_quotation::where('Quotation_ID',$Proposal_ID)->where('Product_ID', 'LIKE', 'M' . '%')->get();
        $Banquet = document_quotation::where('Quotation_ID',$Proposal_ID)->where('Product_ID', 'LIKE', 'B' . '%')->get();
        $entertainment = document_quotation::where('Quotation_ID',$Proposal_ID)->where('Product_ID', 'LIKE', 'E' . '%')->get();
        $unit = master_unit::where('status',1)->get();
        $quantity = master_quantity::where('status',1)->get();
        $totalnetpriceproduct = 0;
        foreach ($room as $item) {
            $totalnetpriceproduct +=  $item->netpriceproduct;
        }
        $totalnetMeals = 0;
        foreach ($Meals as $item) {
            $totalnetMeals +=  $item->netpriceproduct;
        }
        $totalnetBanquet = 0;
        foreach ($Banquet as $item) {
            $totalnetBanquet +=  $item->netpriceproduct;
        }
        $totalentertainment = 0;
        foreach ($entertainment as $item) {
            $totalentertainment +=  $item->netpriceproduct;
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
            // ตรวจสอบผลลัพธ์

            $Receiptover = receive_payment::where('Quotation_ID', $Additional_ID)->get();

            foreach ($Receiptover as $item) {
                $AdditionaltotalReceipt +=  $item->Amount;
            }
            if ($Receiptover->contains('Paid', 0)) {
                // ถ้า status มีค่าเป็น 0 อย่างน้อยหนึ่งรายการ
                $statusover = 0;
            } else {
                $statusover = 1;
            }
        }

        return view('billingfolio.check_pi',compact('Proposal_ID','subtotal','beforeTax','AddTax','Nettotal','SpecialDiscountBath','total','invoices','status','Proposal','ProposalID',
                    'totalnetpriceproduct','room','unit','quantity','totalnetMeals','Meals','Banquet','totalnetBanquet','totalentertainment','entertainment','Receipt','ids','fullname'
                    ,'firstPart','Identification','address','totalReceipt','vat','Additional','AdditionaltotalReceipt','Receiptover','statusover','Additional_ID',
                    'Rm','FB','BQ','AT','EM','RmCount','FBCount','BQCount','EMCount','ATCount'));
    }

    public function PaidInvoice($id){
        $invoices = document_invoices::where('id', $id)->first();
        $proposalid = $invoices->Quotation_ID;
        $Invoice_ID = $invoices->Invoice_ID;
        $sumpayment = $invoices->sumpayment;
        $valid = $invoices->valid;
        $Proposal = Quotation::where('Quotation_ID',$proposalid)->first();
        $guest = $Proposal->Company_ID;
        $type = $Proposal->type_Proposal;
        $Percent = $invoices->paymentPercent;
        if ($type == 'Company') {
            $data = companys::where('Profile_ID',$guest)->first();
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
            $Identification = $data->Taxpayer_Identification;
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
        $data_bank = Masters::where('category', "bank")->where('status', 1)->select('id', 'name_th', 'name_en')->get();
        $chequeRe =receive_cheque::where('refer_proposal',$proposalid)->where('refer_invoice',$Invoice_ID)->first();
        if ($chequeRe) {
            $bank_cheque = $chequeRe->bank_cheque;
            $databank= Masters::where('id', $bank_cheque)->first();
            $databankname = $databank->name_en;
            if ($chequeRe->status == '1') {
                // ถ้า status มีค่าเป็น 0 อย่างน้อยหนึ่งรายการ
                $chequeRestatus = 0;
            } else {
                $chequeRestatus = 1;
            }
        }else{
            $chequeRestatus = 1;
            $bank_cheque ="";
            $databankname = "";
        }

        return view('billingfolio.invoicepaid',compact('invoices','address','Identification','valid','Proposal','name','Percent','name_ID','datasub','type','REID','Invoice_ID','settingCompany','databankname','data_bank','sumpayment','chequeRe','chequeRestatus','bank_cheque'));
    }
    public function EditPaidInvoice($id){
        $re = receive_payment::where('id',$id)->first();
        $InvoiceID = $re->Invoice_ID;
        $company = $re->company;
        $REID = $re->Receipt_ID;
        $invoices = document_invoices::where('Invoice_ID', $InvoiceID)->first();
        $proposalid = $invoices->Quotation_ID;
        $Invoice_ID = $invoices->Invoice_ID;
        $sumpayment = $invoices->sumpayment;
        $valid = $invoices->valid;
        $Proposal = Quotation::where('Quotation_ID',$proposalid)->first();
        $guest = $Proposal->Company_ID;
        $type = $Proposal->type_Proposal;
        $Percent = $invoices->paymentPercent;
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
        $settingCompany = Master_company::orderBy('id', 'desc')->first();
        $data_bank = Masters::where('category', "bank")->where('status', 1)->select('id', 'name_th', 'name_en')->get();
        $chequeRe =receive_cheque::where('refer_receive',$REID)->where('refer_proposal',$proposalid)->where('refer_invoice',$Invoice_ID)->first();
        if ($chequeRe) {
            $bank_cheque = $chequeRe->bank_cheque;
            $bank_received = $chequeRe->bank_received;
            $databank= Masters::where('id', $bank_cheque)->first();
            $databankname = $databank->name_en;
            $databanknamere = null;
            if ($bank_received) {
                $databankre= Masters::where('id', $bank_received)->first();
                $databanknamere = $databankre->name_en;
            }

            if ($chequeRe->status == '1') {
                // ถ้า status มีค่าเป็น 0 อย่างน้อยหนึ่งรายการ
                $chequeRestatus = 0;
            } else {
                $chequeRestatus = 1;
            }
        }else{
            $chequeRestatus = 1;
            $bank_cheque ="";
            $databankname = "";
            $databanknamere = null;
        }
        return view('billingfolio.editinvoicepaid',compact('company','invoices','address','re','Identification','valid','Proposal','name','Percent','name_ID','datasub','type','REID','Invoice_ID','settingCompany','databankname','data_bank','sumpayment','chequeRe','chequeRestatus','bank_cheque','databanknamere'));
    }
    public function PaidInvoiceData($id)
    {
        $parts = explode('-', $id);
        $firstPart = $parts[0];
        if ($firstPart == 'C') {
            $company =  companys::where('Profile_ID',$id)->first();
            if ($company) {
                $fullname = $company->Company_Name;
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
                            ? 'บริษัท ' . $company->Companny_name . ' จำกัด'
                            : 'คุณ ' . $company->first_name . ' ' . $company->last_name;
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
                            ? 'บริษัท ' . $guestdata->Company_name . ' จำกัด'
                            : 'คุณ ' . $guestdata->first_name . ' ' . $guestdata->last_name;
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

        return response()->json([
            'fullname'=>$fullname,
            'Address' => $Address,
            'Identification' => $Identification,
            'province'=>$provinceNames,
            'amphures'=>$amphuresID,
            'Tambon'=>$TambonID,
        ]);
    }
    public function PaidInvoiceDataprewive($id,$ids)
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
                        $fullnameCom = "Company : "." บริษัท ". $company->Company_Name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $fullnameCom = "Company : "." บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $fullnameCom = "Company : "." ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
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
                        $fullnameCom = "Company : "." บริษัท ". $company->Companny_name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $fullnameCom = "Company : "." บริษัท ". $company->Companny_name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $fullnameCom = "Company : "." ห้างหุ้นส่วนจำกัด ". $company->Companny_name ;
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
                        $fullnameCom = "Company : "." บริษัท ". $guestdata->Company_name . " จำกัด";
                    }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                        $fullnameCom = "Company : "." บริษัท ". $guestdata->Company_name . " จำกัด (มหาชน)";
                    }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                        $fullnameCom = "Company : "." ห้างหุ้นส่วนจำกัด ". $guestdata->Company_name ;
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
        $invoices = document_invoices::where('Invoice_ID', $ids)->first();
        $valid = $invoices->valid;
        return response()->json([
            'valid'=>$valid,
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
    public function savere(Request $request) {
        $data = $request->all();
        $guest = $request->Guest;
        $reservationNo = $request->reservationNo;
        $room = $request->roomNo;
        $numberOfGuests = $request->numberOfGuests;
        $arrival = $request->arrival;
        $departure = $request->departure;
        $paymentType = $request->paymentTypecheque ?? $request->paymentType;

        if ($paymentType == null || $guest == null || $reservationNo == null || $room == null || $numberOfGuests == null || $arrival == null || $departure == null) {
            return redirect()->route('BillingFolio.index')->with('error', 'กรุณากรอกข้อมูลให้ครบ');
        }

        $invoice = $request->invoice;
        //bank
        $bank = $request->bank;
        //Credit Card Input
        $CardNumber = $request->CardNumber;
        $Expiry = $request->Expiry;
        //Cheque
        $chequeBank = $request->chequeBank;
        $chequeBankReceived = $request->chequeBankReceived;
        if ($paymentType == 'cheque') {
            if ($chequeBank == null) {

                $chequeRe =receive_cheque::where('refer_invoice',$invoice)->where('status',1)->first();
                $bank_cheque = $chequeRe->bank_cheque;
                $databank= Masters::where('id', $bank_cheque)->first();
                $databankname = $databank->name_en;
            }else{
                $databankname = $chequeBank;
            }
        }

        $cheque = $request->cheque;
        $paymentDate = $request->paymentDate;
        $note = $request->note;
        if ($paymentType == 'cash') {
            $datanamebank = ' Cash ' ;
        }else if($paymentType == 'bankTransfer') {
            $datanamebank = $bank .' Bank Transfer - Together Resort Ltd - Reservation Deposit' ;
        }else if($paymentType == 'creditCard') {
            $datanamebank =  ' Credit Card No. '.$CardNumber.' Exp. Date : '.$Expiry ;
        }else if($paymentType == 'cheque') {
            $datanamebank =  ' Cheque Bank '.$databankname.' Cheque Number '.$cheque;
        }
        $parts = explode('-', $guest);
        $firstPart = $parts[0];
        if ($firstPart == 'C') {
            $company =  companys::where('Profile_ID',$guest)->first();
            if ($company) {
                $type_Proposal = 'Company';
            }else{
                $company =  company_tax::where('ComTax_ID',$guest)->first();
                $type_Proposal = 'company_tax';
            }
        }else{
            $guestdata =  Guest::where('Profile_ID',$guest)->first();
            if ($guestdata) {
                $type_Proposal = 'Guest';
            }else{
                $guestdata =  guest_tax::where('GuestTax_ID',$guest)->first();
                $type_Proposal = 'guest_tax';
            }
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

        $invoices = document_invoices::where('Invoice_ID', $invoice)->first();
        $idinvoices = $invoices->id;
        $sumpayment = $invoices->sumpayment;
        $Quotation_ID = $invoices->Quotation_ID;
        $created_at = Carbon::parse($invoices->created_at)->format('d/m/Y');
        $template = master_template::query()->latest()->first();
        try {
            $user = Auth::user()->id;
            $save = new receive_payment();
            $save->Receipt_ID = $REID;
            $save->Invoice_ID = $invoice;
            $save->Quotation_ID = $Quotation_ID;
            $save->company = $guest;
            $save->category =  $paymentType;
            $save->Amount = $sumpayment;
            if($paymentType == 'bankTransfer') {
                $save->Bank = $bank;
            }else if($paymentType == 'creditCard') {
                $save->Credit = $CardNumber;
                $save->Expire = $Expiry;
            }else if($paymentType == 'cheque') {
                $save->Cheque = $cheque;
                $save->Bank = $databankname;
            }
            $save->reservationNo = $reservationNo;
            $save->roomNo = $room;
            $save->numberOfGuests = $numberOfGuests;
            $save->arrival = $arrival;
            $save->departure = $departure;
            $save->type_Proposal = $type_Proposal;
            $save->paymentDate = $paymentDate;
            $save->Operated_by = $user;
            $save->note = $note;
            $save->save();

            if ($paymentType == 'cheque') {
                $chequeRe =receive_cheque::where('refer_invoice',$invoice)->where('status',1)->first();
                $id_cheque = $chequeRe->id;
                $chequeBankReceivedname= Masters::where('name_en', $chequeBankReceived)->first();
                $bank_received = $chequeBankReceivedname->id;
                $savecheque = receive_cheque::find($id_cheque);
                $savecheque->refer_receive =$REID;
                $savecheque->bank_received =$bank_received;
                $savecheque->save();
            }
            {   //PDF
                $settingCompany = Master_company::orderBy('id', 'desc')->first();
                $parts = explode('-', $guest);
                $firstPart = $parts[0];
                if ($firstPart == 'C') {
                    $company =  companys::where('Profile_ID',$guest)->first();
                    if ($company) {
                        $fullname = "";
                        $Company_typeID=$company->Company_type;
                        if ($company->Company_Name) {
                            $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                            if ($comtype->name_th =="บริษัทจำกัด") {
                                $fullnameCom = "Company : "." บริษัท ". $company->Company_Name . " จำกัด";
                            }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                                $fullnameCom = "Company : "." บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                            }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                                $fullnameCom = "Company : "." ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
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
                        if ($provinceNames) {
                            $province = ' จังหวัด '.$provinceNames->name_th;
                            $amphures = ' อำเภอ '.$amphuresID->name_th;
                            $tambon = ' ตำบล '.$TambonID->name_th;
                            $zip_code = $TambonID->Zip_Code;
                        }else{
                            $province ="";
                            $amphures="";
                            $tambon="";
                            $zip_code="";
                        }
                    }else{
                        $company =  company_tax::where('ComTax_ID',$guest)->first();
                        $fullname = $company && $company->Companny_name
                                    ? ""
                                    : 'คุณ ' . $company->first_name . ' ' . $company->last_name;
                        $Company_typeID=$company->Company_type;
                        if ($company->Companny_name) {
                            $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                            if ($comtype->name_th =="บริษัทจำกัด") {
                                $fullnameCom = "Company : "." บริษัท ". $company->Companny_name . " จำกัด";
                            }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                                $fullnameCom = "Company : "." บริษัท ". $company->Companny_name . " จำกัด (มหาชน)";
                            }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                                $fullnameCom = "Company : "." ห้างหุ้นส่วนจำกัด ". $company->Companny_name ;
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
                        if ($provinceNames) {
                            $province = ' จังหวัด '.$provinceNames->name_th;
                            $amphures = ' อำเภอ '.$amphuresID->name_th;
                            $tambon = ' ตำบล '.$TambonID->name_th;
                            $zip_code = $TambonID->Zip_Code;
                        }else{
                            $province ="";
                            $amphures="";
                            $tambon="";
                            $zip_code="";
                        }
                    }
                }else{
                    $guestdata =  Guest::where('Profile_ID',$guest)->first();
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
                        if ($provinceNames) {
                            $province = ' จังหวัด '.$provinceNames->name_th;
                            $amphures = ' อำเภอ '.$amphuresID->name_th;
                            $tambon = ' ตำบล '.$TambonID->name_th;
                            $zip_code = $TambonID->Zip_Code;
                        }else{
                            $province ="";
                            $amphures="";
                            $tambon="";
                            $zip_code="";
                        }
                    }else{
                        $guestdata =  guest_tax::where('GuestTax_ID',$guest)->first();
                        $fullname = $guestdata && $guestdata->Company_name
                                    ? ""
                                    : 'คุณ ' . $guestdata->first_name . ' ' . $guestdata->last_name;
                        $Company_typeID=$guestdata->Company_type;
                        if ($guestdata->Company_name) {
                            $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                            if ($comtype->name_th =="บริษัทจำกัด") {
                                $fullnameCom = "Company : "." บริษัท ". $guestdata->Company_name . " จำกัด";
                            }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                                $fullnameCom = "Company : "." บริษัท ". $guestdata->Company_name . " จำกัด (มหาชน)";
                            }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                                $fullnameCom = "Company : "." ห้างหุ้นส่วนจำกัด ". $guestdata->Company_name ;
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
                        if ($provinceNames) {
                            $province = ' จังหวัด '.$provinceNames->name_th;
                            $amphures = ' อำเภอ '.$amphuresID->name_th;
                            $tambon = ' ตำบล '.$TambonID->name_th;
                            $zip_code = $TambonID->Zip_Code;
                        }else{
                            $province ="";
                            $amphures="";
                            $tambon="";
                            $zip_code="";
                        }

                    }
                }
                $date = Carbon::now();
                $Date = $date->format('d/m/Y');
                $dateFormatted = $date->format('d/m/Y').' / ';
                $dateTime = $date->format('H:i');
                $Amount = $sumpayment;

                $data = [
                    'settingCompany'=>$settingCompany,
                    'fullname'=>$fullname,
                    'fullnameCom'=>$fullnameCom,
                    'Identification'=>$Identification,
                    'Address'=>$Address,
                    'province'=>$province,
                    'amphures'=>$amphures,
                    'tambon'=>$tambon,
                    'zip_code'=>$zip_code,
                    'reservationNo'=>$reservationNo,
                    'room'=>$room,
                    'arrival'=>$arrival,
                    'departure'=>$departure,
                    'numberOfGuests'=>$numberOfGuests,
                    'dateFormatted'=>$dateFormatted,
                    'dateTime'=>$dateTime,
                    'created_at'=>$created_at,
                    'Date'=>$Date,
                    'Amount'=>$Amount,
                    'note'=>$note,
                    'datanamebank'=>$datanamebank,
                    'invoice'=>$REID,

                ];
                $view= $template->name;
                $pdf = FacadePdf::loadView('billingfolioPDF.'.$view,$data);
                $path = 'Log_PDF/billingfolio/';
                $pdf->save($path . $REID . '.pdf');
                $parts = explode('-', $guest);
                $firstPart = $parts[0];

                $fullname = '';
                $fullnameCom = '';

                if ($firstPart == 'C') {
                    $company = companys::where('Profile_ID', $guest)->first();
                    if ($company) {
                        $Company_typeID=$company->Company_type;
                        if ($company->Company_Name) {
                            $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                            if ($comtype->name_th =="บริษัทจำกัด") {
                                $fullnameCom = "Company : "." บริษัท ". $company->Company_Name . " จำกัด";
                            }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                                $fullnameCom = "Company : "." บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                            }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                                $fullnameCom = "Company : "." ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                            }else {
                                $fullnameCom = $comtype->name_th . $company->Company_Name;
                            }
                        }else{
                            $fullnameCom = "";
                        }

                    } else {
                        $company = company_tax::where('ComTax_ID', $guest)->first();
                        if ($company) {
                            $Company_typeID=$company->Company_type;
                            if ($company->Companny_name) {
                                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                                if ($comtype->name_th =="บริษัทจำกัด") {
                                    $fullnameCom = "Company : "." บริษัท ". $company->Companny_name . " จำกัด";
                                }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                                    $fullnameCom = "Company : "." บริษัท ". $company->Companny_name . " จำกัด (มหาชน)";
                                }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                                    $fullnameCom = "Company : "." ห้างหุ้นส่วนจำกัด ". $company->Companny_name ;
                                }else {
                                    $fullnameCom = $comtype->name_th . $company->Companny_name;
                                }
                            }else{
                                $fullnameCom = "";
                            }
                        } else {
                            $fullname = 'คุณ ' . $company->first_name . ' ' . $company->last_name;
                        }
                    }
                } else {
                    $guestdata = Guest::where('Profile_ID', $guest)->first();
                    if ($guestdata) {
                        $fullname = 'คุณ ' . $guestdata->First_name . ' ' . $guestdata->Last_name;
                    } else {
                        $guestdata = guest_tax::where('GuestTax_ID', $guest)->first();
                        if ($guestdata && $guestdata->Company_name) {
                            $Company_typeID=$guestdata->Company_type;
                            if ($guestdata->Company_name) {
                                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                                if ($comtype->name_th =="บริษัทจำกัด") {
                                    $fullnameCom = "Company : "." บริษัท ". $guestdata->Company_name . " จำกัด";
                                }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                                    $fullnameCom = "Company : "." บริษัท ". $guestdata->Company_name . " จำกัด (มหาชน)";
                                }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                                    $fullnameCom = "Company : "." ห้างหุ้นส่วนจำกัด ". $guestdata->Company_name ;
                                }else {
                                    $fullnameCom = $comtype->name_th . $guestdata->Company_name;
                                }
                            }else{
                                $fullnameCom = "";
                            }
                        } else {
                            $fullname = 'คุณ ' . $guestdata->first_name . ' ' . $guestdata->last_name;
                        }
                    }
                }
                $currentDateTime = Carbon::now();
                $currentDate = $currentDateTime->toDateString(); // Format: YYYY-MM-DD
                $currentTime = $currentDateTime->toTimeString(); // Format: HH:MM:SS

                // Optionally, you can format the date and time as per your requirement
                $formattedDate = $currentDateTime->format('Y-m-d'); // Custom format for date
                $formattedTime = $currentDateTime->format('H:i:s');
                $savePDF = new log();
                $savePDF->Quotation_ID = $REID;
                $savePDF->QuotationType = 'Receipt';
                $savePDF->Company_Name = !empty($fullnameCom) ? $fullnameCom : $fullname;
                $savePDF->Approve_date = $formattedDate;
                $savePDF->Approve_time = $formattedTime;
                $savePDF->save();
            }
            { //invoice
                $saveRe = document_invoices::find($idinvoices);
                $saveRe->status_receive = 2;
                $saveRe->Paid = 1;
                $saveRe->save();
            }
            {
                $datarequest = [
                    'InvoiceID' => $data['invoice'] ?? null,
                    'Guest' => $data['Guest'] ?? null,
                    'reservationNo' => $data['reservationNo'] ?? null,
                    'roomNo' => $data['roomNo'] ?? null,
                    'numberOfGuests' => $data['numberOfGuests'] ?? null,
                    'arrival' => $data['arrival'] ?? null,
                    'departure' => $data['departure'] ?? null,
                    'datanamebank' => $data['datanamebank'] ?? null,
                    'bank' => $data['bank'] ?? null,
                    'CardNumber' => $data['CardNumber'] ?? null,
                    'Expiry' => $data['Expiry'] ?? null,
                    'cheque' => $data['cheque'] ?? null,
                    'chequeBank' => $data['chequeBank'] ?? null,
                    'paymentDate'=> $data['paymentDate'] ?? null,
                    'note'=> $data['note'] ?? null,
                ];
                $invoices = document_invoices::where('id', $idinvoices)->first();
                $sumpayment = $invoices->sumpayment;
                $Quotation_ID = $invoices->Quotation_ID;
                $Invoice_ID = $invoices->Invoice_ID;
                $REID = $REID;

                $Guest = $datarequest['Guest'] ?? null;
                $reservationNo = $datarequest['reservationNo'] ?? null;
                $roomNo = $datarequest['roomNo'] ?? null;
                $numberOfGuests = $datarequest['numberOfGuests'] ?? null;
                $arrival = $datarequest['arrival'] ?? null;
                $departure = $datarequest['departure'] ?? null;
                $datanamebank = $datarequest['datanamebank'] ?? null;
                $bank = $datarequest['bank'] ?? null;
                $CardNumber = $datarequest['CardNumber'] ?? null;
                $Expiry = $datarequest['Expiry'] ?? null;
                $cheque = $datarequest['cheque'] ?? null;
                $chequeBank = $datarequest['chequeBank'] ?? null;
                $paymentDate = $datarequest['paymentDate'] ?? null;
                $note = $datarequest['note'] ?? null;
                $databank = 'รูปแบบการชำระ :'.$datanamebank;
                $Reservation_No = null;
                if ($reservationNo) {
                    $Reservation_No = 'Reservation No : '.$reservationNo;
                }
                $Room_No = null;
                if ($roomNo) {
                    $Room_No = 'Room No : '.$roomNo;
                }
                $NumberOfGuests = null;
                if ($numberOfGuests) {
                    $NumberOfGuests = 'No. of guest : '.$numberOfGuests;
                }
                $Arrival = null;
                if ($arrival) {
                    $Arrival = 'Arrival : '.$arrival;
                }
                $Departure = null;
                if ($departure) {
                    $Departure = 'Departure : '.$departure;
                }
                $PaymentDate = null;
                if ($paymentDate) {
                    $PaymentDate = 'วันที่ชำระ : '.$paymentDate;
                }
                $Note = null;
                if ($note) {
                    $Note = 'รายละเอียด : '.$note;
                }
                $fullname = 'รหัส : '.$REID.' + '.'อ้างอิงจาก Proforma Invoice ID : '.$Invoice_ID;

                $datacompany = '';

                $variables = [$fullname, $Reservation_No, $Room_No, $NumberOfGuests, $Arrival,$Departure, $PaymentDate,$databank,$Note];

                foreach ($variables as $variable) {
                    if (!empty($variable)) {
                        if (!empty($datacompany)) {
                            $datacompany .= ' + ';
                        }
                        $datacompany .= $variable;
                    }
                }
                $REID = $REID;
                $userids = Auth::user()->id;
                $save = new log_company();
                $save->Created_by = $userids;
                $save->Company_ID = $REID;
                $save->type = 'Paid';
                $save->Category = 'Paid :: Receipt';
                $save->content =$datacompany;
                $save->save();

            }

        } catch (\Throwable $e) {
            return redirect()->route('BillingFolio.index')->with('error', $e->getMessage());
        }
        try {
            $invoiceid = $request->invoice;
            $invoices = document_invoices::where('Invoice_ID', $invoiceid)->first();
            $Quotation_ID = $invoices->Quotation_ID;
            $receive = receive_payment::where('Quotation_ID',$Quotation_ID)->get();
            $Amounttotal = 0;
            foreach ($receive as $value) {
                $Amounttotal += $value->Amount;
            }
            $proposal = Quotation::where('Quotation_ID',$Quotation_ID)->first();
            $id = $proposal->id;
            $Nettotal = $proposal->Nettotal;
            $total = $Nettotal-$Amounttotal;
            if ($total == 0) {
                foreach ($receive as $value) {
                   $value->document_status = 2;
                   $value->save();
                }
                $update = Quotation::find($id);
                $update->status_document = 9;
                $update->status_receive = 9;
                $update->save();
                return redirect()->route('BillingFolio.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
            }else{
                return redirect()->route('BillingFolio.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
            }
        } catch (\Throwable $e) {
            return redirect()->route('BillingFolio.index')->with('error', $e->getMessage());
        }
    }
    public function update(Request $request , $id) {
        $dataArray= receive_payment::where('id',$id)->first();
        $REID =  $dataArray->Receipt_ID;
        $sumpayment =  $dataArray->Amount;
        $invoice =  $dataArray->Invoice_ID;
        $invoices = document_invoices::where('Invoice_ID', $invoice)->first();
        $idinvoices = $invoices->id;
        $Quotation_ID = $invoices->Quotation_ID;
        $created_at = Carbon::parse($invoices->created_at)->format('d/m/Y');
        $template = master_template::query()->latest()->first();
        $correct = $dataArray->correct;
        if ($correct >= 1) {
            $correctup = $correct + 1;
        }else{
            $correctup = 1;
        }
        try {

            $data = $request->all();

            $datamain = [
                'company' => $data['Guest'] ?? null,
                'note'=>$data['note'] ?? null,
                'reservationNo'=>$data['reservationNo'] ?? null,
                'category'=>$data['paymentTypecheque'] ?? $data['paymentType'],
                'Cheque'=>$data['cheque'] ?? null,
                'roomNo'=>$data['roomNo'] ?? null,
                'numberOfGuests'=>$data['numberOfGuests'] ?? null,
                'arrival'=>$data['arrival'] ?? null,
                'departure'=>$data['departure'] ?? null,
                'paymentDate'=>$data['paymentDate'] ?? null,
                'Credit'=>$data['CardNumber'] ?? null,
                'Expire'=>$data['Expiry'] ?? null,
                'Bank'=>$data['bank'] ?? null,

            ];

            $keysToCompare = ['company','Bank' ,'note', 'category','Amount','Cheque','roomNo','numberOfGuests','arrival','departure','paymentDate','Credit','Expire','reservationNo'];
            $differences = [];
            foreach ($keysToCompare as $key) {
                if (isset($dataArray[$key]) && isset($datamain[$key])) {
                    // แปลงค่าของ $dataArray และ $data เป็นชุดข้อมูลเพื่อหาค่าที่แตกต่างกัน
                    $dataArraySet = collect($dataArray[$key]);
                    $dataSet = collect($datamain[$key]);

                    // หาค่าที่แตกต่างกัน
                    $onlyInDataArray = $dataArraySet->diff($dataSet)->values()->all();
                    $onlyInRequest = $dataSet->diff($dataArraySet)->values()->all();

                    // ตรวจสอบว่ามีค่าที่แตกต่างหรือไม่
                    if (!empty($onlyInDataArray) || !empty($onlyInRequest)) {
                        $differences[$key] = [
                            'dataArray' => $onlyInDataArray,
                            'request' => $onlyInRequest
                        ];
                    }
                }
            }
            $extractedData = [];
            // วนลูปเพื่อดึงชื่อคีย์และค่าจาก request
            foreach ($differences as $key => $value) {
                if (isset($value['request'][0])) {
                    // สำหรับคีย์อื่นๆ ให้เก็บค่าแรกจาก array
                    $extractedData[$key] = $value['request'][0];
                }else{
                    $extractedDataA[$key] = $value['dataArray'][0];
                }

            }
            $reservationNo = $extractedData['reservationNo'] ?? null;
            $roomNo = $extractedData['roomNo'] ?? null;
            $numberOfGuests =  $extractedData['numberOfGuests'] ?? null;
            $arrival =  $extractedData['arrival'] ?? null;
            $departure =  $extractedData['departure'] ?? null;
            $paymentDate =  $extractedData['paymentDate'] ?? null;
            $note =  $extractedData['note'] ?? null;
            $datanamebank = $extractedData['category'] ?? null;
            $bank = $extractedData['Bank'] ?? null;

            $id = $extractedData['company'] ?? null;
            $Amount = $extractedData['Amount'] ?? null;
            $Cheque = $extractedData['Cheque'] ?? null;
            $Credit = $extractedData['Credit'] ?? null;
            $Expire = $extractedData['Expire'] ?? null;

            $name= null;
            if ($id) {
                $parts = explode('-', $id);
                $firstPart = $parts[0];
                if ($firstPart == 'C') {
                    $company =  companys::where('Profile_ID',$id)->first();
                    if ($company) {
                        $Company_typeID=$company->Company_type;
                        $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                        if ($comtype->name_th =="บริษัทจำกัด") {
                            $name = "ลูกค้า : "." บริษัท ". $company->Company_Name . " จำกัด";
                        }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                            $name = "ลูกค้า : "." บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                        }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                            $name = "ลูกค้า : "." ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                        }else {
                            $name = 'ลูกค้า : '.$comtype->name_th . $company->Company_Name;
                        }
                    }else{
                        $company =  company_tax::where('ComTax_ID',$id)->first();
                        $name = $company && $company->Companny_name
                                    ? ""
                                    : 'ลูกค้า : '.'คุณ ' . $company->first_name . ' ' . $company->last_name;
                    }
                }else{
                    $guestdata =  Guest::where('Profile_ID',$id)->first();
                    if ($guestdata) {
                        $name =  'ลูกค้า : '.'คุณ '.$guestdata->First_name.' '.$guestdata->Last_name;
                    }else{
                        $guestdata =  guest_tax::where('GuestTax_ID',$id)->first();
                        $Company_typeID=$guestdata->Company_type;
                        if ($guestdata->Company_name) {
                            $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                            if ($comtype->name_th =="บริษัทจำกัด") {
                                $name = "ลูกค้า : "." บริษัท ". $guestdata->Company_name . " จำกัด";
                            }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                                $name = "ลูกค้า : "." บริษัท ". $guestdata->Company_name . " จำกัด (มหาชน)";
                            }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                                $name = "ลูกค้า : "." ห้างหุ้นส่วนจำกัด ". $guestdata->Company_name ;
                            }else {
                                $name = "ลูกค้า : ".$comtype->name_th . $guestdata->Company_name;
                            }
                        }else{
                            $name = "";
                        }
                    }
                }
            }

            $cheque= null;
            if ($Cheque) {
                $cheque = 'เลขเช็ค : '.$Cheque;
            }
            $credit= null;
            if ($Credit) {
                $credit = 'เลขบัตร : '.$Credit;
            }
            $expire= null;
            if ($Expire) {
                $expire = 'วันหมดอายุบัตร : '.$Expire;
            }
            $databank= null;
            if ($datanamebank) {
                $databank = 'รูปแบบการชำระ : '.$datanamebank;
                if ($datanamebank == 'creditCard') {
                    $CardNumber = $data['CardNumber'] ?? null;
                    $Expiry = $data['Expiry'] ?? null;
                    $credit = 'เลขบัตร : '.$CardNumber;
                    $expire = 'วันหมดอายุบัตร : '. $Expiry;
                }elseif ($datanamebank == 'bankTransfer') {
                    $bank = $data['bank'] ?? null;
                    $Bank = 'ธนาคาร : '.$bank;
                }
            }
            $Bank= null;
            if ($bank) {
                $Bank = 'ธนาคาร : '.$bank;
            }
            $Reservation_No = null;
            if ($reservationNo) {
                $Reservation_No = 'Reservation No : '.$reservationNo;
            }
            $Room_No = null;
            if ($roomNo) {
                $Room_No = 'Room No : '.$roomNo;
            }
            $NumberOfGuests = null;
            if ($numberOfGuests) {
                $NumberOfGuests = 'No. of guest : '.$numberOfGuests;
            }
            $Arrival = null;
            if ($arrival) {
                $Arrival = 'Arrival : '.$arrival;
            }
            $Departure = null;
            if ($departure) {
                $Departure = 'Departure : '.$departure;
            }
            $PaymentDate = null;
            if ($paymentDate) {
                $PaymentDate = 'วันที่ชำระ : '.$paymentDate;
            }
            $Note = null;
            if ($note) {
                $Note = 'รายละเอียด : '.$note;
            }
            $fullname = 'รหัส : '.$REID;
            $amoute = 'ราคา : '.$sumpayment;
            $edit ='แก้ไข';
            $datacompany = '';

            $variables = [$fullname,$edit,$name, $databank, $Bank,$cheque,$credit,$expire,$amoute,$Reservation_No,$Room_No,$NumberOfGuests, $Arrival,$Departure,$PaymentDate,$Note];

            // แปลง array ของ $formattedProductData เป็น string เดียวที่มีรายการทั้งหมด


            foreach ($variables as $variable) {
                if (!empty($variable)) {
                    if (!empty($datacompany)) {
                        $datacompany .= ' + ';
                    }
                    $datacompany .= $variable;
                }
            }
            // dd($data,$dataArray,$extractedData,$differences,$datacompany);
            $userids = Auth::user()->id;
            $save = new log_company();
            $save->Created_by = $userids;
            $save->Company_ID = $REID;
            $save->type = 'Edit';
            $save->Category = 'Edit :: Receipt';
            $save->content =$datacompany;
            $save->save();
        } catch (\Throwable $e) {
            return redirect()->route('BillingFolio.index')->with('error', $e->getMessage());
        }
        try {
            $data = $request->all();
            $guest = $request->Guest;
            $reservationNo = $request->reservationNo;
            $room = $request->roomNo;
            $numberOfGuests = $request->numberOfGuests;
            $arrival = $request->arrival;
            $departure = $request->departure;
            $paymentType = $request->paymentTypecheque ?? $request->paymentType;

            $invoice = $request->invoice;
            //bank
            $bank = $request->bank;
            //Credit Card Input
            $CardNumber = $request->CardNumber;
            $Expiry = $request->Expiry;
            //Cheque
            $chequeBank = $request->chequeBank;
            $chequeBankReceived = $request->chequeBankReceived;
            if ($paymentType == 'cheque') {
                if ($chequeBank == null) {

                    $chequeRe =receive_cheque::where('refer_invoice',$invoice)->where('status',1)->first();
                    $bank_cheque = $chequeRe->bank_cheque;
                    $databank= Masters::where('id', $bank_cheque)->first();
                    $databankname = $databank->name_en;
                }else{
                    $databankname = $chequeBank;
                }
            }

            $cheque = $request->cheque;
            $paymentDate = $request->paymentDate;
            $note = $request->note;
            if ($paymentType == 'cash') {
                $datanamebank = ' Cash ' ;
            }else if($paymentType == 'bankTransfer') {
                $datanamebank = $bank .' Bank Transfer - Together Resort Ltd - Reservation Deposit' ;
            }else if($paymentType == 'creditCard') {
                $datanamebank =  ' Credit Card No. '.$CardNumber .' Exp. Date : '.$Expiry ;
            }else if($paymentType == 'cheque') {
                $datanamebank =  ' Cheque Bank '.$databankname.' Cheque Number '.$cheque;
            }
            $parts = explode('-', $guest);
            $firstPart = $parts[0];
            if ($firstPart == 'C') {
                $company =  companys::where('Profile_ID',$guest)->first();
                if ($company) {
                    $type_Proposal = 'Company';
                }else{
                    $company =  company_tax::where('ComTax_ID',$guest)->first();
                    $type_Proposal = 'company_tax';
                }
            }else{
                $guestdata =  Guest::where('Profile_ID',$guest)->first();
                if ($guestdata) {
                    $type_Proposal = 'Guest';
                }else{
                    $guestdata =  guest_tax::where('GuestTax_ID',$guest)->first();
                    $type_Proposal = 'guest_tax';
                }
            }
            $user = Auth::user()->id;
            $save = receive_payment::find($id);
            $save->company = $guest;
            $save->category =  $paymentType;
            $save->Amount = $sumpayment;
            if($paymentType == 'bankTransfer') {
                $save->Bank = $bank;
                $save->Credit = null;
                $save->Expire = null;
            }else if($paymentType == 'creditCard') {
                $save->Credit = $CardNumber;
                $save->Expire = $Expiry;
                $save->Bank = null;
            }else if($paymentType == 'cheque') {
                $save->Cheque = $cheque;
                $save->Bank = $databankname;
                $save->Credit = null;
                $save->Expire = null;
            }else{
                $save->Cheque = null;
                $save->Bank = null;
                $save->Credit = null;
                $save->Expire = null;
            }
            $save->reservationNo = $reservationNo;
            $save->roomNo = $room;
            $save->numberOfGuests = $numberOfGuests;
            $save->arrival = $arrival;
            $save->departure = $departure;
            $save->type_Proposal = $type_Proposal;
            $save->paymentDate = $paymentDate;
            $save->Operated_by = $user;
            $save->note = $note;
            $save->correct = $correctup;
            $save->save();

            if ($paymentType == 'cheque') {
                $chequeRe =receive_cheque::where('refer_invoice',$invoice)->where('refer_receive',$REID)->first();
                $id_cheque = $chequeRe->id;
                $chequeBankReceivedname= Masters::where('name_en', $chequeBankReceived)->first();
                $bank_received = $chequeBankReceivedname->id;
                $savecheque = receive_cheque::find($id_cheque);
                $savecheque->refer_receive =$REID;
                $savecheque->bank_received =$bank_received;
                $savecheque->save();
            }
            {   //PDF
                $settingCompany = Master_company::orderBy('id', 'desc')->first();
                $parts = explode('-', $guest);
                $firstPart = $parts[0];
                if ($firstPart == 'C') {
                    $company =  companys::where('Profile_ID',$guest)->first();
                    if ($company) {
                        $fullname = "";
                        $Company_typeID=$company->Company_type;
                        if ($company->Company_Name) {
                            $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                            if ($comtype->name_th =="บริษัทจำกัด") {
                                $fullnameCom = "Company : "." บริษัท ". $company->Company_Name . " จำกัด";
                            }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                                $fullnameCom = "Company : "." บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                            }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                                $fullnameCom = "Company : "." ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
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
                        if ($provinceNames) {
                            $province = ' จังหวัด '.$provinceNames->name_th;
                            $amphures = ' อำเภอ '.$amphuresID->name_th;
                            $tambon = ' ตำบล '.$TambonID->name_th;
                            $zip_code = $TambonID->Zip_Code;
                        }else{
                            $province ="";
                            $amphures="";
                            $tambon="";
                            $zip_code="";
                        }
                    }else{
                        $company =  company_tax::where('ComTax_ID',$guest)->first();
                        $fullname = $company && $company->Companny_name
                                    ? ""
                                    : 'คุณ ' . $company->first_name . ' ' . $company->last_name;
                        $Company_typeID=$company->Company_type;
                        if ($company->Companny_name) {
                            $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                            if ($comtype->name_th =="บริษัทจำกัด") {
                                $fullnameCom = "Company : "." บริษัท ". $company->Companny_name . " จำกัด";
                            }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                                $fullnameCom = "Company : "." บริษัท ". $company->Companny_name . " จำกัด (มหาชน)";
                            }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                                $fullnameCom = "Company : "." ห้างหุ้นส่วนจำกัด ". $company->Companny_name ;
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
                        if ($provinceNames) {
                            $province = ' จังหวัด '.$provinceNames->name_th;
                            $amphures = ' อำเภอ '.$amphuresID->name_th;
                            $tambon = ' ตำบล '.$TambonID->name_th;
                            $zip_code = $TambonID->Zip_Code;
                        }else{
                            $province ="";
                            $amphures="";
                            $tambon="";
                            $zip_code="";
                        }
                    }
                }else{
                    $guestdata =  Guest::where('Profile_ID',$guest)->first();
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
                        if ($provinceNames) {
                            $province = ' จังหวัด '.$provinceNames->name_th;
                            $amphures = ' อำเภอ '.$amphuresID->name_th;
                            $tambon = ' ตำบล '.$TambonID->name_th;
                            $zip_code = $TambonID->Zip_Code;
                        }else{
                            $province ="";
                            $amphures="";
                            $tambon="";
                            $zip_code="";
                        }
                    }else{
                        $guestdata =  guest_tax::where('GuestTax_ID',$guest)->first();
                        $fullname = $guestdata && $guestdata->Company_name
                                    ? ""
                                    : 'คุณ ' . $guestdata->first_name . ' ' . $guestdata->last_name;
                        $Company_typeID=$guestdata->Company_type;
                        if ($guestdata->Company_name) {
                            $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                            if ($comtype->name_th =="บริษัทจำกัด") {
                                $fullnameCom = "Company : "." บริษัท ". $guestdata->Company_name . " จำกัด";
                            }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                                $fullnameCom = "Company : "." บริษัท ". $guestdata->Company_name . " จำกัด (มหาชน)";
                            }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                                $fullnameCom = "Company : "." ห้างหุ้นส่วนจำกัด ". $guestdata->Company_name ;
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
                        if ($provinceNames) {
                            $province = ' จังหวัด '.$provinceNames->name_th;
                            $amphures = ' อำเภอ '.$amphuresID->name_th;
                            $tambon = ' ตำบล '.$TambonID->name_th;
                            $zip_code = $TambonID->Zip_Code;
                        }else{
                            $province ="";
                            $amphures="";
                            $tambon="";
                            $zip_code="";
                        }

                    }
                }
                $date = Carbon::now();
                $Date = $date->format('d/m/Y');
                $dateFormatted = $date->format('d/m/Y').' / ';
                $dateTime = $date->format('H:i');
                $Amount = $sumpayment;

                $data = [
                    'settingCompany'=>$settingCompany,
                    'fullname'=>$fullname,
                    'fullnameCom'=>$fullnameCom,
                    'Identification'=>$Identification,
                    'Address'=>$Address,
                    'province'=>$province,
                    'amphures'=>$amphures,
                    'tambon'=>$tambon,
                    'zip_code'=>$zip_code,
                    'reservationNo'=>$reservationNo,
                    'room'=>$room,
                    'arrival'=>$arrival,
                    'departure'=>$departure,
                    'numberOfGuests'=>$numberOfGuests,
                    'dateFormatted'=>$dateFormatted,
                    'dateTime'=>$dateTime,
                    'created_at'=>$created_at,
                    'Date'=>$Date,
                    'Amount'=>$Amount,
                    'note'=>$note,
                    'datanamebank'=>$datanamebank,
                    'invoice'=>$REID,

                ];
                $view= $template->name;
                $pdf = FacadePdf::loadView('billingfolioPDF.'.$view,$data);
                $path = 'Log_PDF/billingfolio/';
                $pdf->save($path . $REID.'-'.$correctup . '.pdf');

                $parts = explode('-', $guest);
                $firstPart = $parts[0];

                $fullname = '';
                $fullnameCom = '';

                if ($firstPart == 'C') {
                    $company = companys::where('Profile_ID', $guest)->first();
                    if ($company) {
                        $Company_typeID=$company->Company_type;
                        if ($company->Company_Name) {
                            $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                            if ($comtype->name_th =="บริษัทจำกัด") {
                                $fullnameCom = "Company : "." บริษัท ". $company->Company_Name . " จำกัด";
                            }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                                $fullnameCom = "Company : "." บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                            }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                                $fullnameCom = "Company : "." ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
                            }else {
                                $fullnameCom = $comtype->name_th . $company->Company_Name;
                            }
                        }else{
                            $fullnameCom = "";
                        }

                    } else {
                        $company = company_tax::where('ComTax_ID', $guest)->first();
                        if ($company) {
                            $Company_typeID=$company->Company_type;
                            if ($company->Companny_name) {
                                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                                if ($comtype->name_th =="บริษัทจำกัด") {
                                    $fullnameCom = "Company : "." บริษัท ". $company->Companny_name . " จำกัด";
                                }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                                    $fullnameCom = "Company : "." บริษัท ". $company->Companny_name . " จำกัด (มหาชน)";
                                }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                                    $fullnameCom = "Company : "." ห้างหุ้นส่วนจำกัด ". $company->Companny_name ;
                                }else {
                                    $fullnameCom = $comtype->name_th . $company->Companny_name;
                                }
                            }else{
                                $fullnameCom = "";
                            }
                        } else {
                            $fullname = 'คุณ ' . $company->first_name . ' ' . $company->last_name;
                        }
                    }
                } else {
                    $guestdata = Guest::where('Profile_ID', $guest)->first();
                    if ($guestdata) {
                        $fullname = 'คุณ ' . $guestdata->First_name . ' ' . $guestdata->Last_name;
                    } else {
                        $guestdata = guest_tax::where('GuestTax_ID', $guest)->first();
                        if ($guestdata && $guestdata->Company_name) {
                            $Company_typeID=$guestdata->Company_type;
                            if ($guestdata->Company_name) {
                                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                                if ($comtype->name_th =="บริษัทจำกัด") {
                                    $fullnameCom = "Company : "." บริษัท ". $guestdata->Company_name . " จำกัด";
                                }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                                    $fullnameCom = "Company : "." บริษัท ". $guestdata->Company_name . " จำกัด (มหาชน)";
                                }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                                    $fullnameCom = "Company : "." ห้างหุ้นส่วนจำกัด ". $guestdata->Company_name ;
                                }else {
                                    $fullnameCom = $comtype->name_th . $guestdata->Company_name;
                                }
                            }else{
                                $fullnameCom = "";
                            }
                        } else {
                            $fullname = 'คุณ ' . $guestdata->first_name . ' ' . $guestdata->last_name;
                        }
                    }
                }
                $currentDateTime = Carbon::now();
                $currentDate = $currentDateTime->toDateString(); // Format: YYYY-MM-DD
                $currentTime = $currentDateTime->toTimeString(); // Format: HH:MM:SS

                // Optionally, you can format the date and time as per your requirement
                $formattedDate = $currentDateTime->format('Y-m-d'); // Custom format for date
                $formattedTime = $currentDateTime->format('H:i:s');
                $savePDF = new log();
                $savePDF->Quotation_ID = $REID;
                $savePDF->QuotationType = 'Receipt';
                $savePDF->Company_Name = !empty($fullnameCom) ? $fullnameCom : $fullname;
                $savePDF->Approve_date = $formattedDate;
                $savePDF->Approve_time = $formattedTime;
                $savePDF->correct = $correctup;
                $savePDF->save();
            }
        } catch (\Throwable $e) {
            return redirect()->route('BillingFolio.index')->with('error', $e->getMessage());
        }

        return redirect()->route('BillingFolio.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }
    public function view($id){
        $receive = receive_payment::where('id',$id)->first();
        $name_receive = $receive->company;
        $data = [
            'Receipt_ID' => $receive['Receipt_ID'] ?? null,
            'Invoice_ID' => $receive['Invoice_ID'] ?? null,
            'Quotation_ID' => $receive['Quotation_ID'] ?? null,
            'company' => $receive['company'] ?? null,
            'note' => $receive['note'] ?? null,
            'category' => $receive['category'] ?? null,
            'Amount' => $receive['Amount'] ?? null,
            'Bank' => $receive['Bank'] ?? null,
            'Cheque' => $receive['Cheque'] ?? null,
            'Credit' => $receive['Credit'] ?? null,
            'Expire' => $receive['Expire'] ?? null,
            'reservationNo' => $receive['reservationNo'] ?? null,
            'roomNo' => $receive['roomNo'] ?? null,
            'numberOfGuests' => $receive['numberOfGuests'] ?? null,
            'arrival' => $receive['arrival'] ?? null,
            'departure' => $receive['departure'] ?? null,
            'paymentDate' => $receive['paymentDate'] ?? null,
            'Operated_by' => $receive['Operated_by'] ?? null,
            'type_Proposal' => $receive['type_Proposal'] ?? null,
        ];
        $invoices = document_invoices::where('Invoice_ID', $data['Invoice_ID'])->first();
        $idinvoices = $invoices->id;
        $sumpayment = $invoices->sumpayment;
        $Quotation_ID = $invoices->Quotation_ID;
        $created_at = Carbon::parse($invoices->created_at)->format('d/m/Y');
        $template = master_template::query()->latest()->first();
        $settingCompany = Master_company::orderBy('id', 'desc')->first();
        if ($data['type_Proposal'] == 'Company') {
            $company =  companys::where('Profile_ID',$data['company'])->first();
            $fullname = "";
            $Company_typeID=$company->Company_type;
            if ($company->Company_Name) {
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="บริษัทจำกัด") {
                    $fullnameCom = "Company : "." บริษัท ". $company->Company_Name . " จำกัด";
                }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                    $fullnameCom = "Company : "." บริษัท ". $company->Company_Name . " จำกัด (มหาชน)";
                }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                    $fullnameCom = "Company : "." ห้างหุ้นส่วนจำกัด ". $company->Company_Name ;
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
            if ($provinceNames) {
                $province = ' จังหวัด '.$provinceNames->name_th;
                $amphures = ' อำเภอ '.$amphuresID->name_th;
                $tambon = ' ตำบล '.$TambonID->name_th;
                $zip_code = $TambonID->Zip_Code;
            }else{
                $province ="";
                $amphures="";
                $tambon="";
                $zip_code="";
            }
        }elseif ($data['type_Proposal'] == 'company_tax') {
            $company =  company_tax::where('ComTax_ID',$data['company'])->first();
            $fullname = $company && $company->Companny_name
                        ? ""
                        : 'คุณ ' . $company->first_name . ' ' . $company->last_name;
            $Company_typeID=$company->Company_type;
            if ($company->Companny_name) {
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="บริษัทจำกัด") {
                    $fullnameCom = "Company : "." บริษัท ". $company->Companny_name . " จำกัด";
                }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                    $fullnameCom = "Company : "." บริษัท ". $company->Companny_name . " จำกัด (มหาชน)";
                }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                    $fullnameCom = "Company : "." ห้างหุ้นส่วนจำกัด ". $company->Companny_name ;
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
            if ($provinceNames) {
                $province = ' จังหวัด '.$provinceNames->name_th;
                $amphures = ' อำเภอ '.$amphuresID->name_th;
                $tambon = ' ตำบล '.$TambonID->name_th;
                $zip_code = $TambonID->Zip_Code;
            }else{
                $province ="";
                $amphures="";
                $tambon="";
                $zip_code="";
            }
        }elseif ($data['type_Proposal'] == 'Guest') {
            $guestdata =  Guest::where('Profile_ID',$data['company'])->first();
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
            if ($provinceNames) {
                $province = ' จังหวัด '.$provinceNames->name_th;
                $amphures = ' อำเภอ '.$amphuresID->name_th;
                $tambon = ' ตำบล '.$TambonID->name_th;
                $zip_code = $TambonID->Zip_Code;
            }else{
                $province ="";
                $amphures="";
                $tambon="";
                $zip_code="";
            }
        }elseif ($data['type_Proposal'] == 'guest_tax') {
            $guestdata =  guest_tax::where('GuestTax_ID',$data['company'])->first();
            $fullname = $guestdata && $guestdata->Company_name
                        ? ""
                        : 'คุณ ' . $guestdata->first_name . ' ' . $guestdata->last_name;
            $Company_typeID=$guestdata->Company_type;
            if ($guestdata->Company_name) {
                $comtype = master_document::where('id',$Company_typeID)->select('name_th', 'id')->first();
                if ($comtype->name_th =="บริษัทจำกัด") {
                    $fullnameCom = "Company : "." บริษัท ". $guestdata->Company_name . " จำกัด";
                }elseif ($comtype->name_th =="บริษัทมหาชนจำกัด") {
                    $fullnameCom = "Company : "." บริษัท ". $guestdata->Company_name . " จำกัด (มหาชน)";
                }elseif ($comtype->name_th =="ห้างหุ้นส่วนจำกัด") {
                    $fullnameCom = "Company : "." ห้างหุ้นส่วนจำกัด ". $guestdata->Company_name ;
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
            if ($provinceNames) {
                $province = ' จังหวัด '.$provinceNames->name_th;
                $amphures = ' อำเภอ '.$amphuresID->name_th;
                $tambon = ' ตำบล '.$TambonID->name_th;
                $zip_code = $TambonID->Zip_Code;
            }else{
                $province ="";
                $amphures="";
                $tambon="";
                $zip_code="";
            }
        }
        $date = Carbon::now();
        $Date = $date->format('d/m/Y');
        $dateFormatted = $date->format('d/m/Y').' / ';
        $dateTime = $date->format('H:i');
        if ($data['category'] == 'cash') {
            $datanamebank = ' Cash ' ;
        }else if($data['category'] == 'bankTransfer') {
            $datanamebank = $data['Bank'] .' Bank Transfer - Together Resort Ltd - Reservation Deposit' ;
        }else if($data['category'] == 'creditCard') {
            $datanamebank =  'Credit Card No. '.$data['Credit'] .' Exp. Date : '.$data['Expire'] ;
        }else if($data['category'] == 'cheque') {
            $datanamebank =  ' Cheque Bank '.$data['Bank'].' Cheque Number '.$data['Cheque'];
        }
        $data = [
            'settingCompany'=>$settingCompany,
            'fullname'=>$fullname,
            'fullnameCom'=>$fullnameCom,
            'Identification'=>$Identification,
            'Address'=>$Address,
            'province'=>$province,
            'amphures'=>$amphures,
            'tambon'=>$tambon,
            'zip_code'=>$zip_code,
            'reservationNo'=>$data['reservationNo'],
            'room'=>$data['roomNo'],
            'arrival'=>$data['arrival'],
            'departure'=>$data['departure'],
            'numberOfGuests'=>$data['numberOfGuests'],
            'dateFormatted'=>$dateFormatted,
            'dateTime'=>$dateTime,
            'created_at'=>$created_at,
            'Date'=>$Date,
            'Amount'=>$data['Amount'],
            'note'=>$data['note'],
            'datanamebank'=>$datanamebank,
            'invoice'=>$data['Receipt_ID'],

        ];
        $view= $template->name;
        $pdf = FacadePdf::loadView('billingfolioPDF.'.$view,$data);
        return $pdf->stream();

    }

    public function log($id){
        $receive_payment = receive_payment::where('id', $id)->first();
        $correct = $receive_payment->correct;
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        if ($receive_payment) {
            $Receipt_ID = $receive_payment->Receipt_ID;
            // Use a regular expression to capture the part of the string before the first hyphen
            if (preg_match('/^(RE-\d{8})/', $Receipt_ID, $matches)) {
                $Receipt_ID = $matches[1];
            }
        }

        $log = log::where('Quotation_ID',$Receipt_ID)->paginate($perPage);
        $path = 'Log_PDF/billingfolio/';
        $logReceipt = log_company::where('Company_ID', $Receipt_ID)
            ->orderBy('updated_at', 'desc')
            ->paginate($perPage);

        return view('billingfolio.document',compact('log','path','correct','logReceipt','Receipt_ID'));
    }

    public function QuotationView(Request $request ,$id){
        $Quotation = Quotation::where('id', $id)->first();
        $Quotation_ID = $Quotation->Quotation_ID;
        $selectproduct = document_quotation::where('Quotation_ID', $Quotation_ID)->get();
        $datarequest = [
            'Proposal_ID' => $Quotation['Quotation_ID'] ?? null,
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
            $checkin =$Checkin;
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
        ];
        $view= $template->name;
        $pdf = FacadePdf::loadView('quotationpdf.'.$view,$data);
        return $pdf->stream();

    }

    public function ReceiptCreate($id){
        $Proposal = Quotation::where('id',$id)->first();
        $Proposal_ID = $Proposal->Quotation_ID;
        $Amount = $Proposal->Nettotal;
        $Receipt = receive_payment::where('Quotation_ID', $Proposal_ID)->get();
        $ReceiptCount = receive_payment::where('Quotation_ID', $Proposal_ID)->count();
        $total = 0; // Initialize the total

        foreach ($Receipt as $value) {
            $total += $value->Amount; // Add each Amount to the total
        }
        $balance = $Amount- $total ;
        dd($Proposal,$total,$ReceiptCount,$balance);
    }

    public function search_table_paginate_log_doc (Request $request)
    {
        $perPage = (int)$request->perPage;
        $search_value = $request->search_value;
        $guest_profile = $request->guest_profile;

        if ($search_value) {
            $data_query = log_company::where('created_at', 'LIKE', '%'.$search_value.'%')
                ->where('Company_ID',$guest_profile)
                ->orderBy('updated_at', 'desc')
                ->paginate($perPage);
        }else{
            $perPageS = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
            $data_query = log_company::where('Company_ID',$guest_profile)->orderBy('updated_at', 'desc')->paginate($perPageS);
        }
        $data = [];
        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $contentArray = explode('+', $value->content);
                $content = implode('</br>', $contentArray);
                $Category = '<b style="color:#0000FF ">' . $value->Category . '</b>';
                $name = $Category.'</br>'.$content;
                $data[] = [
                    'number' => $key + 1,
                    'Category'=>$value->Category,
                    'type'=>$value->type,
                    'Created_by'=>@$value->userOperated->name,
                    'created_at' => \Carbon\Carbon::parse($value->created_at)->format('d/m/Y'),
                    'Content' => $name,
                ];
            }
        }
        return response()->json([
            'data' => $data,
        ]);
    }
    public function  paginate_log_doc_table_billing (Request $request)
    {
        $perPage = (int)$request->perPage;
        $guest_profile = $request->guest_profile;
        $data = [];
        if ($perPage == 10) {
            $data_query = log_company::where('Company_ID',$guest_profile)->orderBy('updated_at', 'desc')->limit($request->page.'0')->get();
        } else {
            $data_query = log_company::where('Company_ID',$guest_profile)->orderBy('updated_at', 'desc')->paginate($perPage);
        }
        $page_1 = $request->page == 1 ? 1 : ($request->page - 1).'1';
        $page_2 = $request->page.'0';

        $perPage2 = $request->perPage > 10 ? $request->perPage : 10;

        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $contentArray = explode('+', $value->content);
                $content = implode('</br>', $contentArray);
                $Category = '<b style="color:#0000FF ">' . $value->Category . '</b>';
                $name = $Category.'</br>'.$content;
                if (($key + 1) >= (int)$page_1 && ($key + 1) <= (int)$page_2 || (int)$perPage > 10 && $key < (int)$perPage2) {
                    $data[] = [
                        'number' => $key + 1,
                        'Category'=>$value->Category,
                        'type'=>$value->type,
                        'Created_by'=>@$value->userOperated->name,
                        'created_at' => \Carbon\Carbon::parse($value->created_at)->format('d/m/Y'),
                        'Content' => $name,
                    ];
                }
            }
        }
        // dd($data);
        return response()->json([
            'data' => $data,
        ]);
    }
    public function  paginate_log_pdf_table_billing(Request $request){
        $perPage = (int)$request->perPage;
        $guest_profile = $request->guest_profile;
        $data = [];
        if ($perPage == 10) {
            $data_query = log::where('Quotation_ID',$guest_profile)->limit($request->page.'0')
            ->get();
        } else {
            $data_query =  log::where('Quotation_ID',$guest_profile)->paginate($perPage);
        }
        $page_1 = $request->page == 1 ? 1 : ($request->page - 1).'1';
        $page_2 = $request->page.'0';
        $perPage2 = $request->perPage > 10 ? $request->perPage : 10;

        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $btn_action = "";
                // สร้าง dropdown สำหรับการทำรายการ
                if (($key + 1) >= (int)$page_1 && ($key + 1) <= (int)$page_2 || (int)$perPage > 10 && $key < (int)$perPage2) {
                    $correct = $value->correct;
                    $path = 'Log_PDF/billingfolio/';
                    $pdf_url = asset($path . $value->Quotation_ID. ".pdf");
                    if ($value->correct == $correct) {
                        if ($correct == 0) {
                            $btn_action = '<a href="' . $pdf_url . '" type="button" class="btn btn-outline-dark rounded-pill lift" target="_blank" data-toggle="tooltip" data-placement="top" title="พิมพ์เอกสาร">';
                            $btn_action .= '<i class="fa fa-print"></i>';
                            $btn_action .= '</a>';
                        } else {
                            $btn_action = '<a href="' . asset($path . $value->Quotation_ID . '-' . $correct . ".pdf") . '" type="button" class="btn btn-outline-dark rounded-pill lift" target="_blank" data-toggle="tooltip" data-placement="top" title="พิมพ์เอกสาร">';
                            $btn_action .= '<i class="fa fa-print"></i>';
                            $btn_action .= '</a>';
                        }
                    } else {
                        $btn_action = '<a href="' . $pdf_url . '" type="button" class="btn btn-outline-dark rounded-pill lift" target="_blank" data-toggle="tooltip" data-placement="top" title="พิมพ์เอกสาร">';
                        $btn_action .= '<i class="fa fa-print"></i>';
                        $btn_action .= '</a>';
                    }

                    $data[] = [
                        'number' => $key + 1,
                        'Quotation_ID' => $value->Quotation_ID,
                        'type' => $value->QuotationType,
                        'Correct' => $value->correct,
                        'created_at' =>\Carbon\Carbon::parse($value->created_at)->format('d/m/Y'),
                        'Export' => $btn_action,
                    ];
                }
            }
        }
        // dd($data);
        return response()->json([
            'data' => $data,
        ]);
    }
    public function  search_table_paginate_log_pdf(Request $request){
        $perPage = (int)$request->perPage;
        $guest_profile = $request->guest_profile;
        $search_value = $request->search_value;
        $data = [];
        if ($search_value) {
            $query = Log::where('Quotation_ID', $guest_profile);
            $data_query = $query->where('created_at', 'LIKE', '%'.$search_value.'%')->paginate($perPage);
        } else {
            $perPageS = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
            $data_query =  log::where('Quotation_ID',$guest_profile)->paginate($perPageS);
        }
        $page_1 = $request->page == 1 ? 1 : ($request->page - 1).'1';
        $page_2 = $request->page.'0';
        $perPage2 = $request->perPage > 10 ? $request->perPage : 10;

        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $btn_action = "";
                // สร้าง dropdown สำหรับการทำรายการ
                $correct = $value->correct;
                $path = 'Log_PDF/billingfolio/';
                $pdf_url = asset($path . $value->Quotation_ID. ".pdf");
                if ($value->correct == $correct) {
                    if ($correct == 0) {
                        $btn_action = '<a href="' . $pdf_url . '" type="button" class="btn btn-outline-dark rounded-pill lift" target="_blank" data-toggle="tooltip" data-placement="top" title="พิมพ์เอกสาร">';
                        $btn_action .= '<i class="fa fa-print"></i>';
                        $btn_action .= '</a>';
                    } else {
                        $btn_action = '<a href="' . asset($path . $value->Quotation_ID . '-' . $correct . ".pdf") . '" type="button" class="btn btn-outline-dark rounded-pill lift" target="_blank" data-toggle="tooltip" data-placement="top" title="พิมพ์เอกสาร">';
                        $btn_action .= '<i class="fa fa-print"></i>';
                        $btn_action .= '</a>';
                    }
                } else {
                    $btn_action = '<a href="' . $pdf_url . '" type="button" class="btn btn-outline-dark rounded-pill lift" target="_blank" data-toggle="tooltip" data-placement="top" title="พิมพ์เอกสาร">';
                    $btn_action .= '<i class="fa fa-print"></i>';
                    $btn_action .= '</a>';
                }

                $data[] = [
                    'number' => $key + 1,
                    'Quotation_ID' => $value->Quotation_ID,
                    'type' => $value->QuotationType,
                    'Correct' => $value->correct,
                    'created_at' =>\Carbon\Carbon::parse($value->created_at)->format('d/m/Y'),
                    'Export' => $btn_action,
                ];
            }
        }
        // dd($data);
        return response()->json([
            'data' => $data,
        ]);
    }
}
