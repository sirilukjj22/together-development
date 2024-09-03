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
class QuotationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userid = Auth::user()->id;
        $permissionid = Auth::user()->permission;
        $Quotation_IDs = Quotation::query()->pluck('Quotation_ID');
        $document = document_quotation::whereIn('Quotation_ID', $Quotation_IDs)->get();
        $document_IDs = $document->pluck('Quotation_ID');
        $missingQuotationIDs = $Quotation_IDs->diff($document_IDs);
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        Quotation::whereIn('Quotation_ID', $missingQuotationIDs)->delete();
        if ($user->permission == 0) {
            $Proposalcount = Quotation::query()->where('Operated_by',$userid)->count();
            $Proposal = Quotation::query()->where('Operated_by',$userid)->orderBy('created_at', 'desc')->paginate($perPage);
            $Pending = Quotation::query()->where('Operated_by',$userid)->whereIn('status_document',[1,3])->where('status_guest',0)->paginate($perPage);
            $Pendingcount = Quotation::query()->where('Operated_by',$userid)->whereIn('status_document',[1,3])->where('status_guest',0)->count();
            $Awaiting = Quotation::query()->where('Operated_by',$userid)->orderBy('created_at', 'desc')->where('status_document',2)->paginate($perPage);
            $Awaitingcount = Quotation::query()->where('Operated_by',$userid)->where('status_document',2)->count();
            $Approved = Quotation::query()->where('Operated_by',$userid)->orderBy('created_at', 'desc')->where('status_guest',1)->paginate($perPage);
            $Approvedcount = Quotation::query()->where('Operated_by',$userid)->where('status_guest',1)->count();
            $Reject = Quotation::query()->where('Operated_by',$userid)->orderBy('created_at', 'desc')->where('status_document',4)->paginate($perPage);
            $Rejectcount = Quotation::query()->where('Operated_by',$userid)->where('status_document',4)->count();
            $Cancel = Quotation::query()->where('Operated_by',$userid)->orderBy('created_at', 'desc')->where('status_document',0)->paginate($perPage);
            $Cancelcount = Quotation::query()->where('Operated_by',$userid)->where('status_document',0)->count();
            $User = User::where('permission',$permissionid)->select('name','id','permission')->get();
        }
        elseif ($user->permission == 1 || $user->permission == 2) {
            $Proposalcount = Quotation::query()->count();
            $Proposal = Quotation::query()->orderBy('created_at', 'desc')->paginate($perPage);;
            $Pending = Quotation::query()->whereIn('status_document',[1,3])->where('status_guest',0)->paginate($perPage);
            $Pendingcount = Quotation::query()->whereIn('status_document',[1,3])->where('status_guest',0)->count();
            $Awaiting = Quotation::query()->where('status_document',2)->paginate($perPage);
            $Awaitingcount = Quotation::query()->where('status_document',2)->count();
            $Approved = Quotation::query()->where('status_guest',1)->paginate($perPage);
            $Approvedcount = Quotation::query()->where('status_guest',1)->count();
            $Reject = Quotation::query()->where('status_document',4)->paginate($perPage);
            $Rejectcount = Quotation::query()->where('status_document',4)->count();
            $Cancel = Quotation::query()->where('status_document',0)->paginate($perPage);
            $Cancelcount = Quotation::query()->where('status_document',0)->count();
            $User = User::select('name','id','permission')->whereIn('permission',[0,1,2])->get();
        }

        return view('quotation.index',compact('Proposalcount','Proposal','Awaitingcount','Awaiting','Pending','Pendingcount','Approved','Approvedcount','Rejectcount','Reject','Cancel','Cancelcount','User'));
    }
    public function SearchAll(Request $request){

        $checkin  = $request->checkin;
        $checkout  = $request->checkout;
        $checkbox  = $request->checkbox;
        $checkboxAll = $request->checkboxAll;
        $Usercheck = $request->User;
        $status = $request->status;
        $Filter = $request->Filter;
        $user = Auth::user();
        $userid = Auth::user()->id;
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        if ($checkin) {
            $checkinDate = Carbon::createFromFormat('d/m/Y', $checkin)->format('Y-m-d');
        }

        if ($checkout) {
            $checkoutDate = Carbon::createFromFormat('d/m/Y', $checkout)->format('Y-m-d');
        }
        if ($user->permission == 1) {
            $User = User::select('name','id')->whereIn('permission',[0,1,2])->get();
            $Proposalcount = Quotation::query()->count();

            if ($Filter == 'All') {
                $Proposal = Quotation::query()->orderBy('created_at', 'desc')->get();
            }elseif ($Filter == 'Nocheckin') {
                if ($Filter == 'Nocheckin'&&$checkin ==null&& $checkout == null) {
                    if ($Filter == 'Nocheckin'&&$Usercheck ==null&& $status == null) {
                        $Proposal = Quotation::query()->where('checkin',null)->where('checkout',null)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Filter == 'Nocheckin'&&$Usercheck !==null&& $status == null) {
                        $Proposal = Quotation::query()->where('checkin',null)->where('checkout',null)->where('Operated_by',$Usercheck)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Filter == 'Nocheckin'&&$status == 1 && $Usercheck == null) {
                        $Proposal = Quotation::query()->where('checkin',null)->where('checkout',null)->whereIn('status_document',[1,3])->where('status_guest',0)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Filter == 'Nocheckin'&&$status == 3 && $Usercheck == null) {
                        $Proposal = Quotation::query()->where('checkin',null)->where('checkout',null)->where('status_guest',1)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Filter == 'Nocheckin'&&$status == 2 && $Usercheck == null) {
                        $Proposal = Quotation::query()->where('checkin',null)->where('checkout',null)->where('status_document',2)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Filter == 'Nocheckin'&&$status == 4 && $Usercheck == null) {
                        $Proposal = Quotation::query()->where('checkin',null)->where('checkout',null)->where('status_document',4)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Filter == 'Nocheckin'&&$status == 0 && $Usercheck == null) {
                        $Proposal = Quotation::query()->where('checkin',null)->where('checkout',null)->where('status_document',0)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Filter == 'Nocheckin'&&$status == 1 && $Usercheck !== null) {
                        $Proposal = Quotation::query()->where('checkin',null)->where('checkout',null)->where('Operated_by',$Usercheck)->whereIn('status_document',[1,3])->where('status_guest',0)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Filter == 'Nocheckin'&&$status == 3 && $Usercheck !== null) {
                        $Proposal = Quotation::query()->where('checkin',null)->where('checkout',null)->where('Operated_by',$Usercheck)->where('status_guest',1)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Filter == 'Nocheckin'&&$status == 2 && $Usercheck !== null) {
                        $Proposal = Quotation::query()->where('checkin',null)->where('checkout',null)->where('Operated_by',$Usercheck)->where('status_document',2)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Filter == 'Nocheckin'&&$status == 4 && $Usercheck !== null) {
                        $Proposal = Quotation::query()->where('checkin',null)->where('checkout',null)->where('Operated_by',$Usercheck)->where('status_document',4)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Filter == 'Nocheckin'&&$status == 0 && $Usercheck !== null) {
                        $Proposal = Quotation::query()->where('checkin',null)->where('checkout',null)->where('Operated_by',$Usercheck)->where('status_document',0)->orderBy('created_at', 'desc')->paginate($perPage);
                    }
                }
            }elseif ($Filter == 'Checkin') {
                if ($checkin && $checkout &&$Usercheck ==null&& $status == null ) {
                    $Proposal = Quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck !==null&& $status == null ) {
                    $Proposal = Quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('Operated_by',$Usercheck)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck ==null&& $status == 1 ) {
                    $Proposal = Quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->whereIn('status_document',[1,3])->where('status_guest',0)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck ==null&& $status == 2 ) {
                    $Proposal = Quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('status_document',2)->where('status_guest',0)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck ==null&& $status == 3 ) {
                    $Proposal = Quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('status_guest',1)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck ==null&& $status == 4 ) {
                    $Proposal = Quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('status_document',4)->where('status_guest',0)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck ==null&& $status == 0 ) {
                    $Proposal = Quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('status_document',0)->where('status_guest',0)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck !==null&& $status == 1 ) {
                    $Proposal = Quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('Operated_by',$Usercheck)->whereIn('status_document',[1,3])->where('status_guest',0)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck !==null&& $status == 2 ) {
                    $Proposal = Quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('Operated_by',$Usercheck)->where('status_document',2)->where('status_guest',0)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck !==null&& $status == 3 ) {
                    $Proposal = Quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('Operated_by',$Usercheck)->where('status_guest',1)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck !==null&& $status == 4 ) {
                    $Proposal = Quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('Operated_by',$Usercheck)->where('status_document',4)->where('status_guest',0)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck !==null&& $status == 0 ) {
                    $Proposal = Quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('Operated_by',$Usercheck)->where('status_document',0)->where('status_guest',0)->orderBy('created_at', 'desc')->paginate($perPage);
                }
            }elseif ($Filter == null) {
                if ($Usercheck) {
                    if ($Usercheck !== null && $status == null) {
                        $Proposal = Quotation::query()->orderBy('created_at', 'desc')->where('Operated_by',$Usercheck)->paginate($perPage);
                    }elseif ($Usercheck !== null && $status == 0) {
                        $Proposal = Quotation::query()->where('Operated_by',$Usercheck)->where('status_document',0)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Usercheck !== null && $status == 1) {
                        $Proposal = Quotation::query()->where('Operated_by',$Usercheck)->whereIn('status_document',[1,3])->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Usercheck !== null && $status == 2) {
                        $Proposal = Quotation::query()->where('Operated_by',$Usercheck)->where('status_document',2)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Usercheck !== null && $status == 3) {
                        $Proposal = Quotation::query()->where('Operated_by',$Usercheck)->where('status_guest',1)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Usercheck !== null && $status == 4) {
                        $Proposal = Quotation::query()->where('Operated_by',$Usercheck)->where('status_document',4)->orderBy('created_at', 'desc')->paginate($perPage);
                    }
                }else {
                    if ($status == 0) {
                        if ($status == null) {
                            $Proposal = Quotation::query()->where('status_document',0)->paginate($perPage);
                        }else{
                            $Proposal = Quotation::query()->where('status_document',0)->paginate($perPage);
                        }
                    }elseif ($status == 1) {
                        $Proposal = Quotation::query()->whereIn('status_document',[1,3])->paginate($perPage);

                    }elseif ($status == 2) {
                        $Proposal = Quotation::query()->where('status_document',2)->paginate($perPage);
                    }elseif ($status == 3) {
                        $Proposal = Quotation::query()->where('status_guest',1)->paginate($perPage);

                    }elseif ($status == 4) {
                        $Proposal = Quotation::query()->where('status_document',4)->paginate($perPage);
                    }
                }
            }
            $Pending = Quotation::query()->whereIn('status_document',[1,3])->where('status_guest',0)->paginate($perPage);
            $Approved = Quotation::query()->where('status_guest',1)->paginate($perPage);
            $Pendingcount = Quotation::query()->whereIn('status_document',[1,3])->where('status_guest',0)->count();
            $Awaiting = Quotation::query()->where('status_document',2)->paginate($perPage);
            $Awaitingcount = Quotation::query()->where('status_document',2)->count();
            $Approvedcount = Quotation::query()->where('status_guest',1)->count();
            $Reject = Quotation::query()->where('status_document',4)->orderBy('created_at', 'desc')->paginate($perPage);
            $Rejectcount = Quotation::query()->where('status_document',4)->count();
            $Cancel = Quotation::query()->where('status_document',0)->orderBy('created_at', 'desc')->paginate($perPage);
            $Cancelcount = Quotation::query()->where('status_document',0)->count();
        }
        if ($user->permission == 0) {

            $User = User::select('name','id')->where('id',$userid)->get();
            if ($Filter == 'All') {
                $Proposal = Quotation::query()->where('Operated_by',$Usercheck)->orderBy('created_at', 'desc')->paginate($perPage);
            }elseif ($Filter == 'Nocheckin') {
                if ($Filter == 'Nocheckin'&&$checkin ==null&& $checkout == null&&$status == null && $Usercheck !== null) {
                    $Proposal = Quotation::query()->where('checkin',null)->where('checkout',null)->where('Operated_by',$Usercheck)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($Filter == 'Nocheckin'&&$status == 1 && $Usercheck !== null) {
                    $Proposal = Quotation::query()->where('checkin',null)->where('checkout',null)->where('Operated_by',$Usercheck)->whereIn('status_document',[1,3])->where('status_guest',0)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($Filter == 'Nocheckin'&&$status == 3 && $Usercheck !== null) {
                    $Proposal = Quotation::query()->where('checkin',null)->where('checkout',null)->where('Operated_by',$Usercheck)->where('status_guest',1)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($Filter == 'Nocheckin'&&$status == 2 && $Usercheck !== null) {
                    $Proposal = Quotation::query()->where('checkin',null)->where('checkout',null)->where('Operated_by',$Usercheck)->where('status_document',2)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($Filter == 'Nocheckin'&&$status == 4 && $Usercheck !== null) {
                    $Proposal = Quotation::query()->where('checkin',null)->where('checkout',null)->where('Operated_by',$Usercheck)->where('status_document',4)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($Filter == 'Nocheckin'&&$status == 0 && $Usercheck !== null) {
                    $Proposal = Quotation::query()->where('checkin',null)->where('checkout',null)->where('Operated_by',$Usercheck)->where('status_document',0)->orderBy('created_at', 'desc')->paginate($perPage);
                }
            }elseif ($Filter == 'Checkin') {
                if ($checkin && $checkout &&$Usercheck !==null&& $status == null ) {
                    $Proposal = Quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('Operated_by',$Usercheck)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck !==null&& $status == 1 ) {
                    $Proposal = Quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('Operated_by',$Usercheck)->whereIn('status_document',[1,3])->where('status_guest',0)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck !==null&& $status == 2 ) {
                    $Proposal = Quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('Operated_by',$Usercheck)->where('status_document',2)->where('status_guest',0)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck !==null&& $status == 3 ) {
                    $Proposal = Quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('Operated_by',$Usercheck)->where('status_guest',1)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck !==null&& $status == 4 ) {
                    $Proposal = Quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('Operated_by',$Usercheck)->where('status_document',4)->where('status_guest',0)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck !==null&& $status == 0 ) {
                    $Proposal = Quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('Operated_by',$Usercheck)->where('status_document',0)->where('status_guest',0)->orderBy('created_at', 'desc')->paginate($perPage);
                }
            }elseif ($Filter == null) {
                if ($Usercheck) {
                    if ($Usercheck !== null && $status == null) {
                        $Proposal = Quotation::query()->orderBy('created_at', 'desc')->where('Operated_by',$Usercheck)->paginate($perPage);
                    }elseif ($Usercheck !== null && $status == 0) {
                        $Proposal = Quotation::query()->where('Operated_by',$Usercheck)->where('status_document',0)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Usercheck !== null && $status == 1) {
                        $Proposal = Quotation::query()->where('Operated_by',$Usercheck)->whereIn('status_document',[1,3])->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Usercheck !== null && $status == 2) {
                        $Proposal = Quotation::query()->where('Operated_by',$Usercheck)->where('status_document',2)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Usercheck !== null && $status == 3) {
                        $Proposal = Quotation::query()->where('Operated_by',$Usercheck)->where('status_guest',1)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Usercheck !== null && $status == 4) {
                        $Proposal = Quotation::query()->where('Operated_by',$Usercheck)->where('status_document',4)->orderBy('created_at', 'desc')->paginate($perPage);
                    }
                }
            }
            $Proposalcount = Quotation::query()->where('Operated_by',$userid)->count();
            $Pending = Quotation::query()->where('Operated_by',$userid)->whereIn('status_document',[1,3])->where('status_guest',0)->paginate($perPage);
            $Pendingcount = Quotation::query()->where('Operated_by',$userid)->whereIn('status_document',[1,3])->where('status_guest',0)->count();
            $Awaiting = Quotation::query()->where('Operated_by',$userid)->orderBy('created_at', 'desc')->where('status_document',2)->paginate($perPage);
            $Awaitingcount = Quotation::query()->where('Operated_by',$userid)->where('status_document',2)->count();
            $Approved = Quotation::query()->where('Operated_by',$userid)->orderBy('created_at', 'desc')->where('status_guest',1)->paginate($perPage);
            $Approvedcount = Quotation::query()->where('Operated_by',$userid)->where('status_guest',1)->count();
            $Reject = Quotation::query()->where('Operated_by',$userid)->orderBy('created_at', 'desc')->where('status_document',4)->paginate($perPage);
            $Rejectcount = Quotation::query()->where('Operated_by',$userid)->where('status_document',4)->count();
            $Cancel = Quotation::query()->where('Operated_by',$userid)->orderBy('created_at', 'desc')->where('status_document',0)->paginate($perPage);
            $Cancelcount = Quotation::query()->where('Operated_by',$userid)->where('status_document',0)->count();
        }
        return view('quotation.index',compact('Proposalcount','Proposal','Awaitingcount','Awaiting','Pending','Pendingcount','Approved','Approvedcount','Rejectcount','Reject','Cancel','Cancelcount'
        ,'User'));
    }
    public function  paginate_table_Index_Proposal(Request $request)
    {
        $perPage = (int)$request->perPage;
        $userid = Auth::user()->id;
        $data = [];
        if ($perPage == 10) {
            $data_query = Quotation::query()->where('Operated_by',$userid)->orderBy('created_at', 'desc')
            ->limit($request->page.'0')
            ->get();
        } else {
            $Proposal = Quotation::query()->where('Operated_by',$userid)->orderBy('created_at', 'desc')->paginate($perPage);
        }
        $page_1 = $request->page == 1 ? 1 : ($request->page - 1).'1';
        $page_2 = $request->page.'0';

        $perPage2 = $request->perPage > 10 ? $request->perPage : 10;

        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $item) {


                // สร้าง dropdown สำหรับการทำรายการ
                if (($key + 1) >= (int)$page_1 && ($key + 1) <= (int)$page_2 || (int)$perPage > 10 && $key < (int)$perPage2) {
                    $btn_action = "";
                    $btn_status = "";
                    if (Auth::user()->rolePermissionData(Auth::user()->id) == 1) {
                        $btn_action .= '<div class="dropdown">';
                        $btn_action .= '<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">ทำรายการ &nbsp;</button>';
                        $btn_action .= '<ul class="dropdown-menu border-0 shadow p-3">';
                        if (Auth::user()->roleMenuView('Proposal', Auth::user()->id) == 1) {
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Quotation/view/' . $item->id) . '">ดูรายละเอียด</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" target="_blank" href="' . url('/Quotation/Quotation/cover/document/PDF/' . $item->id) . '">Export</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Quotation/send/email/' . $item->id) . '">ส่งอีเมล</a></li>';
                        }
                        if (Auth::user()->roleMenuEdit('Proposal', Auth::user()->id) == 1) {
                            if (in_array($item->status_document, [1, 6, 3])) {
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Quotation/edit/quotation/' . $item->id) . '">แก้ไขรายการ</a></li>';
                            }
                            if ($item->status_document == 1 && $item->SpecialDiscountBath == 0 && $item->SpecialDiscount == 0) {
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" onclick="Approved(' . $item->id . ')">อนุมัติ</a></li>';
                            }
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Quotation/view/quotation/LOG/' . $item->id) . '">ดูประวัติ</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" onclick="Cancel()"><input type="hidden" name="id" id="id" value="' . $item->id . '">ยกเลิก</a></li>';
                        }
                    }
                    $btn_action .= '</ul>';
                    $btn_action .= '</div>';

                    // สร้างสถานะการใช้งาน
                    if ($item->status_guest == 1) {
                        $btn_status = '<span class="badge rounded-pill bg-success">อนุมัติ</span>';
                    } else {
                        if ($item->status_document == 0) {
                            $btn_status = '<span class="badge rounded-pill bg-danger">ยกเลิก</span>';
                        } elseif ($item->status_document == 1) {
                            $btn_status = '<span class="badge rounded-pill " style="background-color: #FF6633">รอดำเนินการ</span>';
                        } elseif ($item->status_document == 2) {
                            $btn_status = '<span class="badge rounded-pill bg-warning">รอการอนุมัติ</span>';
                        } elseif ($item->status_document == 3) {
                            $btn_status = '<span class="badge rounded-pill " style="background-color: #FF6633">รอดำเนินการ</span>';
                        } elseif ($item->status_document == 4) {
                            $btn_status = '<span class="badge rounded-pill " style="background-color:#1d4ed8">ปฏิเสธ</span>';
                        } elseif ($item->status_document == 6) {
                            $btn_status = '<span class="badge rounded-pill " style="background-color: #FF6633">รอดำเนินการ</span>';
                        }
                    }
                    $data[] = [
                        'number' => $key + 1,
                        'DummyNo' => $item->DummyNo == $item->Quotation_ID ? '-' : $item->DummyNo,
                        'Quotation_ID' => $item->Quotation_ID,
                        'Company_Name' => @$item->company->Company_Name,
                        'IssueDate' => $item->issue_date,
                        'ExpirationDate' => $item->Expirationdate,
                        'CheckIn' => $item->checkin ? \Carbon\Carbon::parse($item->checkin)->format('d/m/Y') : '-',
                        'CheckOut' => $item->checkout ? \Carbon\Carbon::parse($item->checkout)->format('d/m/Y') : '-',
                        'DiscountP' => $item->SpecialDiscount == 0 ? '-' : '<i class="bi bi-check-lg text-green"></i>',
                        'DiscountB' => $item->SpecialDiscountBath == 0 ? '-' : '<i class="bi bi-check-lg text-green"></i>',
                        'Approve' => $item->Confirm_by == 'Auto' || $item->Confirm_by == '-' ? $item->Confirm_by : @$item->userConfirm->name,
                        'Operated' => @$item->userOperated->name,
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
    public function search_table_Index_Proposal(Request $request)
    {
        $perPage = (int)$request->perPage;
        $search_value = $request->search_value;
        $guest_profile = $request->guest_profile;
        if ($search_value) {
            $data_query = Quotation::where('Quotation_ID', 'LIKE', '%'.$search_value.'%')
            ->orWhere('checkin', 'LIKE', '%'.$search_value.'%')
            ->orWhere('checkout', 'LIKE', '%'.$search_value.'%')
            ->orWhere('issue_date', 'LIKE', '%'.$search_value.'%')
            ->orWhere('Expirationdate', 'LIKE', '%'.$search_value.'%')
            ->where('Company_ID',$guest_profile)
            ->paginate($perPage);
        }else{
            $perPageS = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
            $data_query = Quotation::where('Company_ID',$guest_profile)->paginate($perPageS);
        }
        $data = [];
        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $btn_action = "";
                $btn_status = "";
                if (Auth::user()->rolePermissionData(Auth::user()->id) == 1) {
                    $btn_action .= '<div class="dropdown">';
                    $btn_action .= '<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">ทำรายการ &nbsp;</button>';
                    $btn_action .= '<ul class="dropdown-menu border-0 shadow p-3">';
                    if (Auth::user()->roleMenuView('Proposal', Auth::user()->id) == 1) {
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Quotation/view/' . $item->id) . '">ดูรายละเอียด</a></li>';
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" target="_blank" href="' . url('/Quotation/Quotation/cover/document/PDF/' . $item->id) . '">Export</a></li>';
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Quotation/send/email/' . $item->id) . '">ส่งอีเมล</a></li>';
                    }
                    if (Auth::user()->roleMenuEdit('Proposal', Auth::user()->id) == 1) {
                        if (in_array($item->status_document, [1, 6, 3])) {
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Quotation/edit/quotation/' . $item->id) . '">แก้ไขรายการ</a></li>';
                        }
                        if ($item->status_document == 1 && $item->SpecialDiscountBath == 0 && $item->SpecialDiscount == 0) {
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" onclick="Approved(' . $item->id . ')">อนุมัติ</a></li>';
                        }
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Quotation/view/quotation/LOG/' . $item->id) . '">ดูประวัติ</a></li>';
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" onclick="Cancel()"><input type="hidden" name="id" id="id" value="' . $item->id . '">ยกเลิก</a></li>';
                    }
                }
                $btn_action .= '</ul>';
                $btn_action .= '</div>';

                // สร้างสถานะการใช้งาน
                if ($item->status_guest == 1) {
                    $btn_status = '<span class="badge rounded-pill bg-success">อนุมัติ</span>';
                } else {
                    if ($item->status_document == 0) {
                        $btn_status = '<span class="badge rounded-pill bg-danger">ยกเลิก</span>';
                    } elseif ($item->status_document == 1) {
                        $btn_status = '<span class="badge rounded-pill " style="background-color: #FF6633">รอดำเนินการ</span>';
                    } elseif ($item->status_document == 2) {
                        $btn_status = '<span class="badge rounded-pill bg-warning">รอการอนุมัติ</span>';
                    } elseif ($item->status_document == 3) {
                        $btn_status = '<span class="badge rounded-pill " style="background-color: #FF6633">รอดำเนินการ</span>';
                    } elseif ($item->status_document == 4) {
                        $btn_status = '<span class="badge rounded-pill " style="background-color:#1d4ed8">ปฏิเสธ</span>';
                    } elseif ($item->status_document == 6) {
                        $btn_status = '<span class="badge rounded-pill " style="background-color: #FF6633">รอดำเนินการ</span>';
                    }
                }
                $data[] = [
                    'number' => $key + 1,
                    'DummyNo' => $item->DummyNo == $item->Quotation_ID ? '-' : $item->DummyNo,
                    'Quotation_ID' => $item->Quotation_ID,
                    'Company_Name' => @$item->company->Company_Name,
                    'IssueDate' => $item->issue_date,
                    'ExpirationDate' => $item->Expirationdate,
                    'CheckIn' => $item->checkin ? \Carbon\Carbon::parse($item->checkin)->format('d/m/Y') : '-',
                    'CheckOut' => $item->checkout ? \Carbon\Carbon::parse($item->checkout)->format('d/m/Y') : '-',
                    'DiscountP' => $item->SpecialDiscount == 0 ? '-' : '<i class="bi bi-check-lg text-green"></i>',
                    'DiscountB' => $item->SpecialDiscountBath == 0 ? '-' : '<i class="bi bi-check-lg text-green"></i>',
                    'Approve' => $item->Confirm_by == 'Auto' || $item->Confirm_by == '-' ? $item->Confirm_by : @$item->userConfirm->name,
                    'Operated' => @$item->userOperated->name,
                    'DocumentStatus' => $btn_status,
                    'btn_action' => $btn_action,
                ];
            }
        }
        return response()->json([
            'data' => $data,
        ]);
    }

}
