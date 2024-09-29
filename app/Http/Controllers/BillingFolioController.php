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
            $data_query = document_receipt::query()
                ->limit($request->page.'0')
                ->get();
        } else {
            $data_query = document_receipt::query()
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
                    $rolePermission = Auth::user()->rolePermissionData(Auth::user()->id);
                    $canViewProposal = Auth::user()->roleMenuView('Proposal', Auth::user()->id);
                    $canEditProposal = Auth::user()->roleMenuEdit('Proposal', Auth::user()->id);
                    $CreateBy = Auth::user()->id;
                    $isOperatedByCreator = $value->Operated_by == $CreateBy;

                    $btn_action = '<div class="dropdown">';
                    $btn_action .= '<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>';
                    $btn_action .= '<ul class="dropdown-menu border-0 shadow p-3">';

                    if ($canViewProposal) {
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" target="_blank" href="' . url('/Proposal/cover/document/PDF/' . $value->id) . '">Export</a></li>';
                    }

                    if ($rolePermission > 0) {
                        if ($rolePermission == 1 || $rolePermission == 2 && $isOperatedByCreator) {
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
                        } elseif ($rolePermission == 3) {
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
    public function search_table_billing(Request $request)
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
                $CreateBy = Auth::user()->id;
                $isOperatedByCreator = $value->Operated_by == $CreateBy;
                $btn_action = '<div class="dropdown">';
                $btn_action .= '<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>';
                $btn_action .= '<ul class="dropdown-menu border-0 shadow p-3">';

                if ($canViewProposal) {
                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" target="_blank" href="' . url('/Proposal/cover/document/PDF/' . $value->id) . '">Export</a></li>';
                }

                if ($rolePermission > 0) {
                    if ($rolePermission == 1 || $rolePermission == 2 && $isOperatedByCreator) {
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
                    } elseif ($rolePermission == 3) {
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

    public function issuebill()
    {
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $userid = Auth::user()->id;
        $Approved = Quotation::query()
        ->leftJoin('receive_payment', 'quotation.Quotation_ID', '=', 'receive_payment.Quotation_ID')
        ->where('quotation.status_guest', 1)
        ->select(
            'quotation.*',
            DB::raw('SUM(receive_payment.Amount) as receive_amount'),
            DB::raw('MIN(CASE WHEN receive_payment.document_status IN (1, 2) THEN CAST(REPLACE(receive_payment.balance, ",", "") AS UNSIGNED) ELSE NULL END) as min_balance')
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
        //Credit Card Input
        $CardNumber = $request->CardNumber;
        $Expiry = $request->Expiry;
        //Cheque
        $chequeBank = $request->chequeBank;
        $cheque = $request->cheque;

        $paymentDate = $request->paymentDate;
        $note = $request->note;

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
        $sumpayment = $invoices->sumpayment;
        $Quotation_ID = $invoices->Quotation_ID;
        $template = master_template::query()->latest()->first();
        try {
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
            $save->paymentDate = $paymentDate;
            $save->note = $note;
            // $save->save();
            $settingCompany = Master_company::orderBy('id', 'desc')->first();
            $data = [
                'settingCompany'=>$settingCompany,
            ];
            $view= $template->name;
            $pdf = FacadePdf::loadView('billingfolioPDF.'.$view,$data);
            return $pdf->stream();
        } catch (\Throwable $th) {
            //throw $th;
        }

    }
    public function view(){
         return view('billingfolioPDF.templateA1');
    }

}
