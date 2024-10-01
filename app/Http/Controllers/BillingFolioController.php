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
class BillingFolioController extends Controller
{
    public function index()
    {
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $userid = Auth::user()->id;
        $Approved = receive_payment::query()->paginate($perPage);
        return view('billingfolio.index',compact('Approved'));
    }
    //---------------------------------table-----------------
    public function  paginate_table_billing(Request $request)
    {
        $perPage = (int)$request->perPage;
        $userid = Auth::user()->id;
        $data = [];
        $permissionid = Auth::user()->permission;
        if ($perPage == 10) {
            $data_query = receive_payment::query()
                ->limit($request->page.'0')
                ->get();
        } else {
            $data_query = receive_payment::query()
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
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" target="_blank" href="' . url('/Document/BillingFolio/Proposal/invoice/log/' . $value->id) . '">Export</a></li>';
                    }

                    if ($rolePermission > 0) {
                        if ($rolePermission == 1 || $rolePermission == 2 && $isOperatedByCreator) {

                            if ($canEditProposal) {

                            }

                        } elseif ($rolePermission == 3) {

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
            $data_query = receive_payment::query()
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

            $data_query = receive_payment::query()
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
                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" target="_blank" href="' . url('/Document/BillingFolio/Proposal/invoice/log/' . $value->id) . '">Export</a></li>';
                }

                if ($rolePermission > 0) {
                    if ($rolePermission == 1 || $rolePermission == 2 && $isOperatedByCreator) {

                        if ($canEditProposal) {

                        }

                    } elseif ($rolePermission == 3) {

                    }
                }

                $btn_action .= '</ul>';
                $btn_action .= '</div>';



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
            DB::raw('MIN(CASE WHEN document_receive.document_status IN (1, 2) THEN CAST(REPLACE(document_receive.balance, ",", "") AS UNSIGNED) ELSE NULL END) as min_balance')
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
                ->leftJoin('receive_payment', 'quotation.Quotation_ID', '=', 'receive_payment.Quotation_ID')
                ->where('quotation.status_guest', 1)
                ->select(
                    'quotation.*',
                    DB::raw('SUM(receive_payment.Amount) as receive_amount'),
                    DB::raw('MIN(CASE WHEN receive_payment.document_status IN (1, 2) THEN CAST(REPLACE(receive_payment.balance, ",", "") AS UNSIGNED) ELSE NULL END) as min_balance')
                )
                ->groupBy('quotation.Quotation_ID', 'quotation.status_guest', 'quotation.status_receive')
                ->limit($request->page.'0')
                ->get();
        } else {
            $data_query = Quotation::query()
                ->leftJoin('receive_payment', 'quotation.Quotation_ID', '=', 'receive_payment.Quotation_ID')
                ->where('quotation.status_guest', 1)
                ->select(
                    'quotation.*',
                    DB::raw('SUM(receive_payment.Amount) as receive_amount'),
                    DB::raw('MIN(CASE WHEN receive_payment.document_status IN (1, 2) THEN CAST(REPLACE(receive_payment.balance, ",", "") AS UNSIGNED) ELSE NULL END) as min_balance')
                )
                ->groupBy('quotation.Quotation_ID', 'quotation.status_guest', 'quotation.status_receive')
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
    public function search_table_billingpd(Request $request)
    {
        $perPage = (int)$request->perPage;
        $search_value = $request->search_value;
        $guest_profile = $request->guest_profile;
        $userid = Auth::user()->id;

        if ($search_value) {
            $data_query = Quotation::query()
                ->leftJoin('receive_payment', 'quotation.Quotation_ID', '=', 'receive_payment.Quotation_ID')
                ->where('quotation.status_guest', 1)
                ->select(
                    'quotation.*',
                    DB::raw('SUM(receive_payment.Amount) as receive_amount'),
                    DB::raw('MIN(CASE WHEN receive_payment.document_status IN (1, 2) THEN CAST(REPLACE(receive_payment.balance, ",", "") AS UNSIGNED) ELSE NULL END) as min_balance')
                )
                ->where('quotation.Quotation_ID', 'LIKE', '%'.$search_value.'%')
                ->groupBy('quotation.Quotation_ID', 'quotation.status_guest', 'quotation.status_receive')
                ->paginate($perPage);
        } else {
            $perPageS = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;

            $data_query = Quotation::query()
                ->leftJoin('receive_payment', 'quotation.Quotation_ID', '=', 'receive_payment.Quotation_ID')
                ->where('quotation.status_guest', 1)
                ->select(
                    'quotation.*',
                    DB::raw('SUM(receive_payment.Amount) as receive_amount'),
                    DB::raw('MIN(CASE WHEN receive_payment.document_status IN (1, 2) THEN CAST(REPLACE(receive_payment.balance, ",", "") AS UNSIGNED) ELSE NULL END) as min_balance')
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
    public function CheckPI($id)
    {
        $userid = Auth::user()->id;
        $Proposal = Quotation::where('id',$id)->first();
        $ProposalID = $Proposal->id;
        $Proposal_ID = $Proposal->Quotation_ID;
        $totalAmount = $Proposal->Nettotal;
        $SpecialDiscountBath = $Proposal->SpecialDiscountBath;
        $SpecialDiscount = $Proposal->SpecialDiscount;
        $subtotal = 0;
        $beforeTax =0;
        $AddTax =0;
        $Nettotal =0;
        $total =0;
        $totalreceipt =0;
        $totalreceiptre =0;
        $total =  $totalAmount;
        $subtotal = $totalAmount-$SpecialDiscountBath;
        $beforeTax = $subtotal/1.07;
        $AddTax = $subtotal-$beforeTax;
        $Nettotal = $subtotal;


        $invoices = document_invoices::where('Quotation_ID', $Proposal_ID)->get();
        if ($invoices->contains('status_receive', 0)) {
            // ถ้า status มีค่าเป็น 0 อย่างน้อยหนึ่งรายการ
            $status = 0;
        } else {
            $status = 1;
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
        return view('billingfolio.check_pi',compact('Proposal_ID','subtotal','beforeTax','AddTax','Nettotal','SpecialDiscountBath','total','invoices','status','Proposal','ProposalID',
                    'totalnetpriceproduct','room','unit','quantity','totalnetMeals','Meals','Banquet','totalnetBanquet','totalentertainment','entertainment'));
    }

    public function PaidInvoice($id){
        $invoices = document_invoices::where('id', $id)->first();
        $proposalid = $invoices->Quotation_ID;
        $Invoice_ID = $invoices->Invoice_ID;
        $sumpayment = $invoices->sumpayment;
        $Proposal = Quotation::where('Quotation_ID',$proposalid)->first();
        $guest = $Proposal->Company_ID;
        $type = $Proposal->type_Proposal;

        if ($type == 'Company') {
            $data = companys::where('Profile_ID',$guest)->select('Company_Name','id','Profile_ID')->first();
            $name =  'บริษัท '.$data->Company_Name.' จำกัด';
            $name_ID = $data->Profile_ID;
            $datasub = company_tax::where('Company_ID',$name_ID)->get();

        }else {
            $data = Guest::where('Profile_ID',$guest)->select('First_name','Last_name','id','Profile_ID')->first();
            $name =  'คุณ '.$data->First_name.' '.$data->Last_name;
            $name_ID = $data->Profile_ID;
            $datasub = guest_tax::where('Company_ID',$name_ID)->get();
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
        return view('billingfolio.invoicepaid',compact('invoices','Proposal','name','name_ID','datasub','type','REID','Invoice_ID','settingCompany','data_bank','sumpayment'));
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
                $fullnameCom = 'บริษัท ' . $company->Company_Name . ' จำกัด' ;
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
                $fullnameCom = $company && $company->Companny_name
                            ? 'บริษัท ' . $company->Companny_name . ' จำกัด'
                            : "";
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
                $fullnameCom = $guestdata && $guestdata->Company_name
                            ? "'บริษัท ' . $guestdata->Company_name . ' จำกัด'"
                            : "";
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
        $paymentType = $request->paymentType;

        $invoice = $request->invoice;
        //bank
        $bank = $request->bank;
        if ($paymentType == 'cash') {
            $datanamebank = ' Cash - Together Resort Ltd - Reservation Deposit' ;
        }else if($paymentType == 'bankTransfer') {
            $datanamebank = $bank +' Bank Transfer - Together Resort Ltd - Reservation Deposit' ;
        }else if($paymentType == 'creditCard') {
            $datanamebank =  ' Credit Card - Together Resort Ltd - Reservation Deposit' ;
        }else if($paymentType == 'cheque') {
            $datanamebank =  ' Cheque - Together Resort Ltd - Reservation Deposit' ;
        }
        //Credit Card Input
        $CardNumber = $request->CardNumber;
        $Expiry = $request->Expiry;
        //Cheque
        $chequeBank = $request->chequeBank;
        $cheque = $request->cheque;

        $paymentDate = $request->paymentDate;
        $note = $request->note;
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
            $save->Bank = $bank;
            $save->Cheque = $cheque;
            $save->Credit = $CardNumber;
            $save->Expire = $Expiry;
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
            {   //PDF
                $settingCompany = Master_company::orderBy('id', 'desc')->first();
                $parts = explode('-', $guest);
                $firstPart = $parts[0];
                if ($firstPart == 'C') {
                    $company =  companys::where('Profile_ID',$guest)->first();
                    if ($company) {
                        $fullname = "";
                        $fullnameCom = 'บริษัท ' . $company->Company_Name . ' จำกัด' ;
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
                        $fullnameCom = $company && $company->Companny_name
                                    ? 'บริษัท ' . $company->Companny_name . ' จำกัด'
                                    : "";
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
                        $fullnameCom = $guestdata && $guestdata->Company_name
                                    ? "'บริษัท ' . $guestdata->Company_name . ' จำกัด'"
                                    : "";
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
                $currentDateTime = Carbon::now();
                $currentDate = $currentDateTime->toDateString(); // Format: YYYY-MM-DD
                $currentTime = $currentDateTime->toTimeString(); // Format: HH:MM:SS

                // Optionally, you can format the date and time as per your requirement
                $formattedDate = $currentDateTime->format('Y-m-d'); // Custom format for date
                $formattedTime = $currentDateTime->format('H:i:s');
                $savePDF = new log();
                $savePDF->Quotation_ID = $REID;
                $savePDF->QuotationType = 'Receipt';
                $savePDF->Approve_date = $formattedDate;
                $savePDF->Approve_time = $formattedTime;
                $savePDF->save();
            }
            { //invoice
                $saveRe = document_invoices::find($idinvoices);
                $saveRe->status_receive = 2;
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
                return redirect()->route('BillingFolio.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
            }

        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }

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
            $fullnameCom = 'บริษัท ' . $company->Company_Name . ' จำกัด' ;
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
            $fullnameCom = $company && $company->Companny_name
                        ? 'บริษัท ' . $company->Companny_name . ' จำกัด'
                        : "";
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
            $fullnameCom = $guestdata && $guestdata->Company_name
                        ? "'บริษัท ' . $guestdata->Company_name . ' จำกัด'"
                        : "";
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
            $datanamebank = ' Cash - Together Resort Ltd - Reservation Deposit' ;
        }else if($data['category'] == 'bankTransfer') {
            $datanamebank = $data['Bank'] +' Bank Transfer - Together Resort Ltd - Reservation Deposit' ;
        }else if($data['category'] == 'creditCard') {
            $datanamebank =  ' Credit Card - Together Resort Ltd - Reservation Deposit' ;
        }else if($data['category'] == 'cheque') {
            $datanamebank =  ' Cheque - Together Resort Ltd - Reservation Deposit' ;
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
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        if ($receive_payment) {
            $Receipt_ID = $receive_payment->Receipt_ID;
            // Use a regular expression to capture the part of the string before the first hyphen
            if (preg_match('/^(PI-\d{8})/', $Receipt_ID, $matches)) {
                $Receipt_ID = $matches[1];
            }
        }

        $log = log::where('Quotation_ID',$Receipt_ID)->paginate($perPage);
        $path = 'Log_PDF/billingfolio/';
        $logReceipt = log_company::where('Company_ID', $Receipt_ID)
            ->orderBy('updated_at', 'desc')
            ->paginate($perPage);
            dd($logReceipt);
        return view('billingfolio.document',compact('log','path','logReceipt','Receipt_ID'));
    }
}
