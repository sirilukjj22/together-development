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
class Document_invoice extends Controller
{
    public function index()
    {
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $userid = Auth::user()->id;
        $Approved = Quotation::query()
        ->leftJoin('document_invoice', 'quotation.Refler_ID', '=', 'document_invoice.Refler_ID')
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
        $Approvedcount = Quotation::query()->where('status_guest',1)->count();

        $invoice = document_invoices::query()->where('document_status',1)->paginate($perPage);
        $invoicecheck = document_invoices::query()->get();
       // ดึงข้อมูลจาก document_invoices รวมถึง Quotation_ID, total และ sumpayment
        $invoicecount = document_invoices::query()->where('document_status',1)->count();
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

     //------------------tablepending----------------------
    public function  paginate_table_invoice_pending(Request $request)
    {
        $perPage = (int)$request->perPage;
        $userid = Auth::user()->id;
        $data = [];
        $permissionid = Auth::user()->permission;
        if ($perPage == 10) {
            $data_query = document_invoices::query()->where('document_status',1)->limit($request->page.'0')
            ->get();
        } else {
            $data_query = document_invoices::query()->where('document_status',1)->paginate($perPage);
        }
        $page_1 = $request->page == 1 ? 1 : ($request->page - 1).'1';
        $page_2 = $request->page.'0';
        $perPage2 = $request->perPage > 10 ? $request->perPage : 10;
        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $btn_action = "";
                $btn_status = "";
                $name ="";
                $checkbox = "";
                $payment = "";
                // สร้าง dropdown สำหรับการทำรายการ
                if (($key + 1) >= (int)$page_1 && ($key + 1) <= (int)$page_2 || (int)$perPage > 10 && $key < (int)$perPage2) {


                    if ($value->type_Proposal == 'Company') {
                        $name = '<td>' .@$value->company00->Company_Name. '</td>';
                    }else {
                        $name = '<td>' . @$value->guest->First_name . ' ' . @$value->guest->Last_name . '</td>';
                    }
                    if ($value->paymentPercent == null) {
                        $payment = '<td>' .'0'. '</td>';
                    }else {
                        $payment = '<td>' .$value->paymentPercent. '</td>';
                    }

                    $btn_status = '<span class="badge rounded-pill " style="background-color: #FF6633">Pending</span>';


                    $rolePermission = Auth::user()->rolePermissionData(Auth::user()->id);
                    $canViewProposal = Auth::user()->roleMenuView('Proposal', Auth::user()->id);
                    $canEditProposal = Auth::user()->roleMenuEdit('Proposal', Auth::user()->id);
                    $CreateBy = Auth::user()->id;
                    $isOperatedByCreator = $value->Operated_by == $CreateBy;

                    $btn_action = '<div class="dropdown">';
                    $btn_action .= '<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>';
                    $btn_action .= '<ul class="dropdown-menu border-0 shadow p-3">';

                    if ($canViewProposal) {
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/view/' . $value->id) . '">View</a></li>';
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" target="_blank" href="' . url('/Invoice/cover/document/PDF/' . $value->id) . '">Export</a></li>';
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/view/LOG/' . $value->id) . '">LOG</a></li>';
                    }
                    if ($rolePermission > 0) {
                        if ($rolePermission == 1 && $isOperatedByCreator) {
                            if ($canEditProposal) {
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/revised/' . $value->id) . '">Edit</a></li>';
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/Generate/to/Re/' . $value->id) . '">Generate</a></li>';
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="delete(' . $value->id . ')">Delete</a></li>';
                            }
                        } elseif ($rolePermission == 2) {
                            if ($isOperatedByCreator) {
                                if ($canEditProposal) {
                                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/revised/' . $value->id) . '">Edit</a></li>';
                                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/Generate/to/Re/' . $value->id) . '">Generate</a></li>';
                                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="delete(' . $value->id . ')">Delete</a></li>';
                                }
                            }
                        } elseif ($rolePermission == 3) {
                            if ($canEditProposal) {
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/revised/' . $value->id) . '">Edit</a></li>';
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/Generate/to/Re/' . $value->id) . '">Generate</a></li>';
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="delete(' . $value->id . ')">Delete</a></li>';
                            }
                        }
                    } else {
                        if ($canViewProposal) {
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/view/' . $value->id) . '">View</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" target="_blank" href="' . url('/Invoice/cover/document/PDF/' . $value->id) . '">Export</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/view/LOG/' . $value->id) . '">LOG</a></li>';
                        }
                    }
                    $btn_action .= '</ul>';
                    $btn_action .= '</div>';

                    $data[] = [
                        'number' => $key+1,
                        'Invoice' => $value->Invoice_ID,
                        'Proposal'=> $value->Quotation_ID,
                        'Company_Name' => $name,
                        'IssueDate' => $value->IssueDate,
                        'ExpirationDate' => $value->Expiration,
                        'Amount' => number_format($value->Nettotal),
                        'PaymentB'=>number_format($value->payment),
                        'PaymentP'=>$payment,
                        'Balance'=>number_format($value->balance),
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
    public function search_table_invoice_pending(Request $request)
    {
        $perPage = (int)$request->perPage;
        $search_value = $request->search_value;
        $guest_profile = $request->guest_profile;
        $userid = Auth::user()->id;
        $permissionid = Auth::user()->permission;

        if ($search_value) {
            $data_query = document_invoices::where('document_status',1)
            ->where('Invoice_ID', 'LIKE', '%'.$search_value.'%')
            ->orWhere('Quotation_ID', 'LIKE', '%'.$search_value.'%')
            ->orWhere('IssueDate', 'LIKE', '%'.$search_value.'%')
            ->orWhere('Expiration', 'LIKE', '%'.$search_value.'%')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
        }else{
            $perPageS = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
            $data_query = document_invoices::query()->where('document_status',1)->paginate($perPageS);
        }


        $data = [];
        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $btn_action = "";
                $btn_status = "";
                $name ="";
                $checkbox = "";
                $payment = "";
                if ($value->type_Proposal == 'Company') {
                    $name = '<td>' .@$value->company00->Company_Name. '</td>';
                }else {
                    $name = '<td>' . @$value->guest->First_name . ' ' . @$value->guest->Last_name . '</td>';
                }
                if ($value->paymentPercent == null) {
                    $payment = '<td>' .'0'. '</td>';
                }else {
                    $payment = '<td>' .$value->paymentPercent. '</td>';
                }

                $btn_status = '<span class="badge rounded-pill " style="background-color: #FF6633">Pending</span>';


                $rolePermission = Auth::user()->rolePermissionData(Auth::user()->id);
                $canViewProposal = Auth::user()->roleMenuView('Proposal', Auth::user()->id);
                $canEditProposal = Auth::user()->roleMenuEdit('Proposal', Auth::user()->id);
                $CreateBy = Auth::user()->id;
                $isOperatedByCreator = $value->Operated_by == $CreateBy;

                $btn_action = '<div class="dropdown">';
                $btn_action .= '<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>';
                $btn_action .= '<ul class="dropdown-menu border-0 shadow p-3">';

                if ($canViewProposal) {
                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/view/' . $value->id) . '">View</a></li>';
                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" target="_blank" href="' . url('/Invoice/cover/document/PDF/' . $value->id) . '">Export</a></li>';
                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/view/LOG/' . $value->id) . '">LOG</a></li>';
                }
                if ($rolePermission > 0) {
                    if ($rolePermission == 1 && $isOperatedByCreator) {
                        if ($canEditProposal) {
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/revised/' . $value->id) . '">Edit</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/Generate/to/Re/' . $value->id) . '">Generate</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="delete(' . $value->id . ')">Delete</a></li>';
                        }
                    } elseif ($rolePermission == 2) {
                        if ($isOperatedByCreator) {
                            if ($canEditProposal) {
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/revised/' . $value->id) . '">Edit</a></li>';
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/Generate/to/Re/' . $value->id) . '">Generate</a></li>';
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="delete(' . $value->id . ')">Delete</a></li>';
                            }
                        }
                    } elseif ($rolePermission == 3) {
                        if ($canEditProposal) {
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/revised/' . $value->id) . '">Edit</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/Generate/to/Re/' . $value->id) . '">Generate</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="delete(' . $value->id . ')">Delete</a></li>';
                        }
                    }
                } else {
                    if ($canViewProposal) {
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/view/' . $value->id) . '">View</a></li>';
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" target="_blank" href="' . url('/Invoice/cover/document/PDF/' . $value->id) . '">Export</a></li>';
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Document/invoice/view/LOG/' . $value->id) . '">LOG</a></li>';
                    }
                }
                $btn_action .= '</ul>';
                $btn_action .= '</div>';

                $data[] = [
                    'number' => $key+1,
                    'Invoice' => $value->Invoice_ID,
                    'Proposal'=> $value->Quotation_ID,
                    'Company_Name' => $name,
                    'IssueDate' => $value->IssueDate,
                    'ExpirationDate' => $value->Expiration,
                    'Amount' => number_format($value->Nettotal),
                    'PaymentB'=>number_format($value->payment),
                    'PaymentP'=>$payment,
                    'Balance'=>number_format($value->balance),
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
            $CompanyID = $Quotation->Company_ID;
            $Company = Guest::where('Profile_ID',$CompanyID)->first();
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
            //-------------ที่อยู่
            $CityID=$Company->City;
            $amphuresID = $Company->Amphures;
            $TambonID = $Company->Tambon;
            $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
            $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
            $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
            $Fax_number = '-';
            $company_phone = phone_guest::where('Profile_ID',$CompanyID)->where('Sequence','main')->first();

        }
        $Checkin = $Quotation->checkin;
        $Checkout = $Quotation->checkout;
        if ($Checkin) {
            $checkin = $Checkin;
            $checkout = $Checkout;
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
                $valid = $request->valid;
                $balance = $request->balance;
                $sum = $request->sum;
                if ($sum &&$balance &&$valid == null) {
                    return redirect()->back()->with('error', 'กรุณากรอกข้อมูลให้ครบ');
                }

                $datarequest = [
                    'Proposal_ID' => $data['QuotationID'] ?? null,
                    'InvoiceID' => $data['InvoiceID'] ?? null,
                    'Refler_ID' => $data['Refler_ID'] ?? null,
                    'IssueDate' => $data['IssueDate'] ?? null,
                    'Expiration' => $data['Expiration'] ?? null,
                    'Selectdata' => $data['selecttype'] ?? null,
                    'Valid' => $data['valid'] ?? null,
                    'Deposit' => $data['Deposit'] ?? null,
                    'Payment' => $data['Payment'] ?? null,
                    'Mevent' => $data['eventformat'] ?? null,
                    'Nettotal' => $data['Nettotal'] ?? null,
                    'Company' => $data['company'] ?? null,
                    'Balance' => $data['balance'] ?? null,
                    'Sum' => $data['sum'] ?? null,
                    'PaymentPercent'=> $data['PaymentPercent'] ?? null,
                ];
                if ($datarequest['Selectdata'] == 'Company') {
                    $Data_ID = $datarequest['Company'];
                    $Company = companys::where('Profile_ID',$Data_ID)->first();
                    $Company_type = $Company->Company_type;
                    $Compannyname = $Company->Company_Name;
                    $Address = $Company->Address;
                    $Email = $Company->Company_Email;
                    $Taxpayer_Identification = $Company->Taxpayer_Identification;
                    $comtype = master_document::where('id', $Company_type)->where('Category', 'Mcompany_type')->first();
                    if ($comtype) {
                        if ($comtype->name_th == "บริษัทจำกัด") {
                            $comtypefullname = "บริษัท " . $Compannyname . " จำกัด";
                        } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                            $comtypefullname = "บริษัท " . $Compannyname . " จำกัด (มหาชน)";
                        } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                            $comtypefullname = "ห้างหุ้นส่วนจำกัด " . $Compannyname;
                        }
                    }
                    $representative = representative::where('Company_ID',$Data_ID)->where('status',1)->first();
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
                    $company_phone = company_phone::where('Profile_ID',$Data_ID)->where('Sequence','main')->first();
                    $Contact_phone = representative_phone::where('Company_ID',$Data_ID)->where('Sequence','main')->first();
                }else{
                    $Data_ID = $datarequest['Company'];
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
                    $profilecontact = 0;
                    $Contact_phone=0;
                    $company_fax =0;
                    $Contact_Name =0;
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
                $id = $datarequest['Proposal_ID'];
                $protocol = $request->secure() ? 'https' : 'http';
                $linkQR = $protocol . '://' . $request->getHost() . "/Invoice/cover/document/PDF/$id?page_shop=" . $request->input('page_shop');

                // Generate the QR code as PNG
                $qrCodeImage = QrCode::format('svg')->size(200)->generate($linkQR);
                $qrCodeBase64 = base64_encode($qrCodeImage);
                $Quotation = Quotation::where('Quotation_ID', $datarequest['Proposal_ID'])->first();

                $settingCompany = Master_company::orderBy('id', 'desc')->first();
                $date = Carbon::now();
                $date = Carbon::parse($date)->format('d/m/Y');
                $vattype= $Quotation->vat_type;
                $vat_type = master_document::where('id',$vattype)->first();
                $vatname = $vat_type->name_th;
                $Mevent =$datarequest['Mevent'];
                $eventformat = master_document::where('id',$Mevent)->select('name_th','id')->first();
                $checkin  = $Quotation->checkin;
                $checkout = $Quotation->checkout;
                $Day = $Quotation->day;
                $Night = $Quotation->night;
                $Adult = $Quotation->adult;
                $Children = $Quotation->children;
                $Checkin = $checkin;
                $Checkout = $checkout;
                $valid = $valid;
                $Deposit = $datarequest['Deposit'];
                $payment=$datarequest['Payment'];
                $Nettotal = floatval(str_replace(',', '', $datarequest['Nettotal']));
                if ($payment) {
                    $payment0 = $payment;
                    $Subtotal =0;
                    $total =0;
                    $addtax = 0;
                    $before = 0;
                    $balance =0;

                    $Subtotal = $payment;
                    $total = $payment;
                    $addtax = 0;
                    $before = $payment;
                    // $balance = $Nettotal-$Subtotal;
                    $balance = $Subtotal;
                }
                $paymentPercent=$datarequest['PaymentPercent'];
                if ($paymentPercent) {
                    $payment0 = $paymentPercent.'%';
                    $Subtotal =0;
                    $total =0;
                    $addtax = 0;
                    $before = 0;
                    $balance =0;
                    $Nettotal = floatval(str_replace(',', '', $request->Nettotal));
                    $paymentPercent = floatval($paymentPercent);
                    $Subtotal = ($Nettotal*$paymentPercent)/100;
                    $total = $Subtotal/1.07;
                    $addtax = $Subtotal-$total;
                    $before = $Subtotal-$addtax;
                    $balance = $Nettotal-$Subtotal;

                    $Subtotal = ($Nettotal*$paymentPercent)/100;
                    $total = $Subtotal/1.07;
                    $addtax = $Subtotal-$total;
                    $before = $Subtotal-$addtax;
                    // $balance = $Nettotal-$Subtotal;
                    $balance = $Nettotal-$Subtotal;

                }
                $balanceold =$request->balance;

                $data= [
                    'date'=>$date,
                    'settingCompany'=>$settingCompany,
                    'Selectdata'=>$datarequest['Selectdata'],
                    'Invoice_ID'=>$datarequest['InvoiceID'],
                    'IssueDate'=>$datarequest['IssueDate'],
                    'Expiration'=>$datarequest['Expiration'],
                    'qrCodeBase64'=>$qrCodeBase64,
                    'Quotation'=>$Quotation,
                    'fullName'=>$comtypefullname,
                    'Address'=>$Address,
                    'TambonID'=>$TambonID,
                    'amphuresID'=>$amphuresID,
                    'provinceNames'=>$provinceNames,
                    'Fax_number'=>$Fax_number,
                    'phone'=>$company_phone,
                    'Email'=>$Email,
                    'Taxpayer_Identification'=>$Taxpayer_Identification,
                    'Day'=>$Day,
                    'Night'=>$Night,
                    'Adult'=>$Adult,
                    'Children'=>$Children,
                    'Checkin'=>$Checkin,
                    'Checkout'=>$Checkout,
                    'valid'=>$valid,
                    'Contact_Name'=>$Contact_Name,
                    'Contact_phone'=>$Contact_phone,
                    'balance'=>$balance,
                    'Deposit'=>$Deposit,
                    'payment'=>$payment0,
                    'Nettotal'=>$Nettotal,
                    'Subtotal'=>$Subtotal,
                    'total'=>$total,
                    'addtax'=>$addtax,
                    'before'=>$before,
                    'balanceold'=>$balanceold,
                ];
                $pdf = FacadePdf::loadView('invoicePDF.preview',$data);
                return $pdf->stream();

            }else{
                {
                    $datarequest = [
                        'Proposal_ID' => $data['QuotationID'] ?? null,
                        'InvoiceID' => $data['InvoiceID'] ?? null,
                        'Refler_ID' => $data['Refler_ID'] ?? null,
                        'IssueDate' => $data['IssueDate'] ?? null,
                        'Expiration' => $data['Expiration'] ?? null,
                        'Selectdata' => $data['selecttype'] ?? null,
                        'Valid' => $data['valid'] ?? null,
                        'Deposit' => $data['Deposit'] ?? null,
                        'Payment' => $data['Payment'] ?? null,
                        'Mevent' => $data['eventformat'] ?? null,
                        'Nettotal' => $data['Nettotal'] ?? null,
                        'Company' => $data['company'] ?? null,
                        'Balance' => $data['balance'] ?? null,
                        'Sum' => $data['sum'] ?? null,
                        'PaymentPercent'=> $data['PaymentPercent'] ?? null,
                    ];

                    //log
                    $Proposal_ID = $datarequest['Proposal_ID'] ?? null;
                    $InvoiceID = $datarequest['InvoiceID'] ?? null;
                    $Valid = $datarequest['Valid'] ?? null;
                    $Deposit = $datarequest['Deposit'] ?? null;
                    $Nettotal = $datarequest['Nettotal'] ?? null;
                    $Payment = $datarequest['Payment'] ?? null;
                    $Sum = $datarequest['Sum'] ?? null;
                    $Balance = $datarequest['Balance'] ?? null;


                    $Validcheck = null;
                    if ($Valid) {
                        $Validcheck = 'วันที่ใช้งาน : '.$Valid;
                    }

                    $Nettotalcheck = null;
                    if ($Nettotal) {
                        $Nettotalcheck = 'ยอดเงินเต็ม : '.number_format($Nettotal). ' บาท';
                    }

                    $Paymentcheck = null;
                    if ($Sum) {
                        $Paymentcheck = 'ยอดเงินที่ชำระ : '. number_format($Sum). ' บาท';
                    }

                    $Balancecheck = null;
                    if ($Balance) {
                        $Balancecheck = 'ยอดเงินคงเหลือชำระ : '.number_format($Balance). ' บาท';
                    }
                    $fullname = null;
                    if ($InvoiceID) {
                        $fullname = 'รหัส : '.$InvoiceID.' + '.'อ้างอิงจาก : '.$Proposal_ID;
                    }

                    $datacompany = '';

                    $variables = [$fullname, $Nettotalcheck, $Paymentcheck, $Balancecheck, $Validcheck];

                    foreach ($variables as $variable) {
                        if (!empty($variable)) {
                            if (!empty($datacompany)) {
                                $datacompany .= ' + ';
                            }
                            $datacompany .= $variable;
                        }
                    }

                    $userids = Auth::user()->id;
                    $save = new log_company();
                    $save->Created_by = $userids;
                    $save->Company_ID = $InvoiceID;
                    $save->type = 'Generate';
                    $save->Category = 'Generate :: Proposal Invoice'.$InvoiceID;
                    $save->content =$datacompany;
                    $save->save();
                }
                {
                    //pdf
                    $datarequest = [
                        'Proposal_ID' => $data['QuotationID'] ?? null,
                        'InvoiceID' => $data['InvoiceID'] ?? null,
                        'Refler_ID' => $data['Refler_ID'] ?? null,
                        'IssueDate' => $data['IssueDate'] ?? null,
                        'Expiration' => $data['Expiration'] ?? null,
                        'Selectdata' => $data['selecttype'] ?? null,
                        'Valid' => $data['valid'] ?? null,
                        'Deposit' => $data['Deposit'] ?? null,
                        'Payment' => $data['Payment'] ?? null,
                        'Mevent' => $data['eventformat'] ?? null,
                        'Nettotal' => $data['Nettotal'] ?? null,
                        'Company' => $data['company'] ?? null,
                        'Balance' => $data['balance'] ?? null,
                        'Sum' => $data['sum'] ?? null,
                        'PaymentPercent'=> $data['PaymentPercent'] ?? null,
                    ];
                    $valid = $request->valid;
                    $balance = $request->balance;
                    $sum = $request->sum;
                    if ($datarequest['Selectdata'] == 'Company') {
                        $Data_ID = $datarequest['Company'];
                        $Company = companys::where('Profile_ID',$Data_ID)->first();
                        $Company_type = $Company->Company_type;
                        $Compannyname = $Company->Company_Name;
                        $Address = $Company->Address;
                        $Email = $Company->Company_Email;
                        $Taxpayer_Identification = $Company->Taxpayer_Identification;
                        $comtype = master_document::where('id', $Company_type)->where('Category', 'Mcompany_type')->first();
                        if ($comtype) {
                            if ($comtype->name_th == "บริษัทจำกัด") {
                                $comtypefullname = "บริษัท " . $Compannyname . " จำกัด";
                            } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                                $comtypefullname = "บริษัท " . $Compannyname . " จำกัด (มหาชน)";
                            } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                                $comtypefullname = "ห้างหุ้นส่วนจำกัด " . $Compannyname;
                            }
                        }

                        $representative = representative::where('Company_ID',$Data_ID)->where('status',1)->first();
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
                        $company_phone = company_phone::where('Profile_ID',$Data_ID)->where('Sequence','main')->first();
                        $Contact_phone = representative_phone::where('Company_ID',$Data_ID)->where('Sequence','main')->first();
                    }else{
                        $Data_ID = $datarequest['Company'];
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
                        $profilecontact = 0;
                        $Contact_phone=0;
                        $company_fax =0;
                        $Contact_Name =0;
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
                    $id = $datarequest['Proposal_ID'];
                    $protocol = $request->secure() ? 'https' : 'http';
                    $linkQR = $protocol . '://' . $request->getHost() . "/Invoice/cover/document/PDF/$id?page_shop=" . $request->input('page_shop');

                    // Generate the QR code as PNG
                    $qrCodeImage = QrCode::format('svg')->size(200)->generate($linkQR);
                    $qrCodeBase64 = base64_encode($qrCodeImage);
                    $Quotation = Quotation::where('Quotation_ID', $datarequest['Proposal_ID'])->first();

                    $settingCompany = Master_company::orderBy('id', 'desc')->first();
                    $date = Carbon::now();
                    $date = Carbon::parse($date)->format('d/m/Y');
                    $vattype= $Quotation->vat_type;
                    $vat_type = master_document::where('id',$vattype)->first();
                    $vatname = $vat_type->name_th;
                    $Mevent =$datarequest['Mevent'];
                    $eventformat = master_document::where('id',$Mevent)->select('name_th','id')->first();
                    $checkin  = $Quotation->checkin;
                    $checkout = $Quotation->checkout;
                    $Day = $Quotation->day;
                    $Night = $Quotation->night;
                    $Adult = $Quotation->adult;
                    $Children = $Quotation->children;
                    $Checkin = $checkin;
                    $Checkout = $checkout;
                    $valid = $valid;
                    $Deposit = $datarequest['Deposit'];
                    $payment=$datarequest['Sum'];
                    $Nettotal = floatval(str_replace(',', '', $datarequest['Nettotal']));
                    if ($payment) {
                        $payment0 = $payment;
                        $Subtotal =0;
                        $total =0;
                        $addtax = 0;
                        $before = 0;
                        $balance =0;

                        $Subtotal = $payment;
                        $total = $payment;
                        $addtax = 0;
                        $before = $payment;
                        // $balance = $Nettotal-$Subtotal;
                        $balance = $Subtotal;
                    }
                    $paymentPercent=$datarequest['PaymentPercent'];
                    if ($paymentPercent) {
                        $payment0 = $paymentPercent.'%';
                        $Subtotal =0;
                        $total =0;
                        $addtax = 0;
                        $before = 0;
                        $balance =0;
                        $Nettotal = floatval(str_replace(',', '', $datarequest['Nettotal']));
                        $paymentPercent = floatval($paymentPercent);
                        $Subtotal = ($Nettotal*$paymentPercent)/100;
                        $total = $Subtotal/1.07;
                        $addtax = $Subtotal-$total;
                        $before = $Subtotal-$addtax;
                        $balance = $Nettotal-$Subtotal;

                        $Subtotal = ($Nettotal*$paymentPercent)/100;
                        $total = $Subtotal/1.07;
                        $addtax = $Subtotal-$total;
                        $before = $Subtotal-$addtax;
                        // $balance = $Nettotal-$Subtotal;
                        $balance = $Nettotal-$Subtotal;

                    }
                    $balanceold =$datarequest['Balance'];

                    $data= [
                        'date'=>$date,
                        'settingCompany'=>$settingCompany,
                        'Selectdata'=>$datarequest['Selectdata'],
                        'Invoice_ID'=>$datarequest['InvoiceID'],
                        'IssueDate'=>$datarequest['IssueDate'],
                        'Expiration'=>$datarequest['Expiration'],
                        'qrCodeBase64'=>$qrCodeBase64,
                        'Quotation'=>$Quotation,
                        'fullName'=>$comtypefullname,
                        'Address'=>$Address,
                        'TambonID'=>$TambonID,
                        'amphuresID'=>$amphuresID,
                        'provinceNames'=>$provinceNames,
                        'Fax_number'=>$Fax_number,
                        'phone'=>$company_phone,
                        'Email'=>$Email,
                        'Taxpayer_Identification'=>$Taxpayer_Identification,
                        'Day'=>$Day,
                        'Night'=>$Night,
                        'Adult'=>$Adult,
                        'Children'=>$Children,
                        'Checkin'=>$Checkin,
                        'Checkout'=>$Checkout,
                        'valid'=>$valid,
                        'Contact_Name'=>$Contact_Name,
                        'Contact_phone'=>$Contact_phone,
                        'balance'=>$balance,
                        'Deposit'=>$Deposit,
                        'payment'=>$payment0,
                        'Nettotal'=>$Nettotal,
                        'Subtotal'=>$Subtotal,
                        'total'=>$total,
                        'addtax'=>$addtax,
                        'before'=>$before,
                        'balanceold'=>$balanceold,
                    ];
                    $template = master_template::query()->latest()->first();
                    $view= $template->name;
                    $pdf = FacadePdf::loadView('invoicePDF.'.$view,$data);
                    $path = 'Log_PDF/invoice/';
                    $pdf->save($path . $InvoiceID . '.pdf');
                    $currentDateTime = Carbon::now();
                    $currentDate = $currentDateTime->toDateString(); // Format: YYYY-MM-DD
                    $currentTime = $currentDateTime->toTimeString(); // Format: HH:MM:SS

                    // Optionally, you can format the date and time as per your requirement
                    $formattedDate = $currentDateTime->format('Y-m-d'); // Custom format for date
                    $formattedTime = $currentDateTime->format('H:i:s');
                    $savePDF = new log();
                    $savePDF->Quotation_ID = $InvoiceID;
                    $savePDF->QuotationType = 'invoice';
                    $savePDF->Approve_date = $formattedDate;
                    $savePDF->Approve_time = $formattedTime;
                    $savePDF->save();

                }
                {
                    //save
                    $count = $datarequest['Proposal_ID'];
                    $count = document_invoices::where('Quotation_ID',$count)->count();
                    $sequence = 1;
                    if ($count) {
                        $sequencenumber = $count+$sequence;
                    }else{
                        $sequencenumber = $sequence;
                    }
                    $NettotalQuotation = Quotation::where('Quotation_ID',$count)->first();
                    $NettotalPD = $NettotalQuotation->Nettotal;
                    $type_Proposal = $NettotalQuotation->type_Proposal;
                    $userid = Auth::user()->id;
                    $save = new document_invoices();
                    $save->deposit =$datarequest['Deposit'];
                    $save->valid =$datarequest['Valid'];
                    $save->payment=$datarequest['Payment'];
                    $save->paymentPercent=$datarequest['PaymentPercent'];
                    $save->balance=$datarequest['Balance'];
                    $save->company=$request->company;
                    $save->Invoice_ID=$datarequest['InvoiceID'];
                    $save->Quotation_ID =$datarequest['Proposal_ID'];
                    $save->Nettotal = $datarequest['Nettotal'];
                    $save->IssueDate= $datarequest['IssueDate'];
                    $save->Expiration= $datarequest['Expiration'];
                    $save->Operated_by = $userid;
                    $save->type_Proposal = $type_Proposal;
                    $save->Refler_ID = $datarequest['Refler_ID'];
                    $save->sequence = $sequencenumber;
                    $save->sumpayment = $datarequest['Sum'];
                    $save->total = $NettotalPD;
                    $save->save();
                    return redirect()->route('invoice.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
                }
            }
            dd($data);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function sheetpdf(Request $request ,$id) {
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
            $QuotationVat= $datarequest['Mvat'];
            $Mvat = master_document::where('id',$QuotationVat)->where('status', '1')->where('Category','Mvat')->select('name_th','id')->first();
            foreach ($items as $item) {
                // ตรวจสอบและกำหนดค่า quantity และ discount
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
}
