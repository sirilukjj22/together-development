<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\dummy_quotation;
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
use App\Models\document_dummy_quotation;
use App\Models\document_quotation;
use App\Models\log;
use Auth;
use App\Models\User;
use PDF;
use App\Models\log_company;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use App\Models\master_document_sheet;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\DB;
use App\Models\master_template;
use Illuminate\Support\Arr;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
class DummyQuotationController extends Controller
{
    public function index()
    {
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $userid = Auth::user()->id;
        $Proposal = dummy_quotation::query()->paginate($perPage);
        $Proposalcount = dummy_quotation::query()->count();
        $Pending = dummy_quotation::query()->where('status_document', 1 )->paginate($perPage);
        $Pendingcount = dummy_quotation::query()->where('status_document',1)->count();
        $Awaitingcount = dummy_quotation::query()->where('status_document',2)->count();
        $Awaiting  = dummy_quotation::query()->where('status_document', 2 )->paginate($perPage);
        $Approvedcount = dummy_quotation::query()->where('status_document',3)->count();
        $Approved = dummy_quotation::query()->where('status_document', 3 )->paginate($perPage);
        $Rejectcount = dummy_quotation::query()->where('status_document',4)->count();
        $Reject = dummy_quotation::query()->where('status_document', 4)->paginate($perPage);
        $Cancelcount = dummy_quotation::query()->where('status_document',0)->count();
        $Cancel = dummy_quotation::query()->where('status_document',0)->paginate($perPage);
        $Generatecount = dummy_quotation::query()->where('status_document',5)->count();
        $Generate = dummy_quotation::query()->where('status_document', 5 )->paginate($perPage);

        $DummyNo = dummy_quotation::query()->pluck('DummyNo');
        $document = document_dummy_quotation::whereIn('Quotation_ID', $DummyNo)->get();
        $document_IDs = $document->pluck('Quotation_ID');
        $missingQuotationIDs = $DummyNo->diff($document_IDs);
        dummy_quotation::whereIn('DummyNo', $missingQuotationIDs)->delete();
        $User = User::select('name','id','permission')->whereIn('permission',[0,1,2])->get();
        return view('dummy_quotation.index',compact('Proposal','Proposalcount','Pending','Pendingcount','Awaiting','Awaitingcount','Approvedcount','Approved','Rejectcount',
        'Reject','Cancelcount','Cancel','Generatecount','Generate','User'));
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
            $Proposalcount = dummy_quotation::query()->count();

            if ($Filter == 'All') {
                $Proposal = dummy_quotation::query()->orderBy('created_at', 'desc')->paginate($perPage);
            }elseif ($Filter == 'Nocheckin') {
                if ($Filter == 'Nocheckin'&&$checkin ==null&& $checkout == null) {
                    if ($Filter == 'Nocheckin'&&$Usercheck ==null&& $status == null) {
                        $Proposal = dummy_quotation::query()->where('checkin',null)->where('checkout',null)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Filter == 'Nocheckin'&&$Usercheck !==null&& $status == null) {
                        $Proposal = dummy_quotation::query()->where('checkin',null)->where('checkout',null)->where('Operated_by',$Usercheck)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Filter == 'Nocheckin'&&$status == 1 && $Usercheck == null) {
                        $Proposal = dummy_quotation::query()->where('checkin',null)->where('checkout',null)->where('status_document',1)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Filter == 'Nocheckin'&&$status == 3 && $Usercheck == null) {
                        $Proposal = dummy_quotation::query()->where('checkin',null)->where('checkout',null)->where('status_document',3)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Filter == 'Nocheckin'&&$status == 2 && $Usercheck == null) {
                        $Proposal = dummy_quotation::query()->where('checkin',null)->where('checkout',null)->where('status_document',2)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Filter == 'Nocheckin'&&$status == 4 && $Usercheck == null) {
                        $Proposal = dummy_quotation::query()->where('checkin',null)->where('checkout',null)->where('status_document',4)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Filter == 'Nocheckin'&&$status == 5 && $Usercheck == null) {
                        $Proposal = dummy_quotation::query()->where('checkin',null)->where('checkout',null)->where('created_at','desc')->where('status_document',5)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Filter == 'Nocheckin'&&$status == 0 && $Usercheck == null) {
                        $Proposal = dummy_quotation::query()->where('checkin',null)->where('checkout',null)->where('status_document',0)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Filter == 'Nocheckin'&&$status == 1 && $Usercheck !== null) {
                        $Proposal = dummy_quotation::query()->where('checkin',null)->where('checkout',null)->where('Operated_by',$Usercheck)->where('status_document',1)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Filter == 'Nocheckin'&&$status == 3 && $Usercheck !== null) {
                        $Proposal = dummy_quotation::query()->where('checkin',null)->where('checkout',null)->where('Operated_by',$Usercheck)->where('status_document',3)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Filter == 'Nocheckin'&&$status == 2 && $Usercheck !== null) {
                        $Proposal = dummy_quotation::query()->where('checkin',null)->where('checkout',null)->where('Operated_by',$Usercheck)->where('status_document',2)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Filter == 'Nocheckin'&&$status == 4 && $Usercheck !== null) {
                        $Proposal = dummy_quotation::query()->where('checkin',null)->where('checkout',null)->where('Operated_by',$Usercheck)->where('status_document',4)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Filter == 'Nocheckin'&&$status == 5 && $Usercheck !== null) {
                        $Proposal = dummy_quotation::query()->where('checkin',null)->where('checkout',null)->where('Operated_by',$Usercheck)->where('status_document',5)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Filter == 'Nocheckin'&&$status == 0 && $Usercheck !== null) {
                        $Proposal = dummy_quotation::query()->where('checkin',null)->where('checkout',null)->where('Operated_by',$Usercheck)->where('status_document',0)->orderBy('created_at', 'desc')->paginate($perPage);
                    }
                }
            }elseif ($Filter == 'Checkin') {
                if ($checkin && $checkout &&$Usercheck ==null&& $status == null ) {
                    $Proposal = dummy_quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck !==null&& $status == null ) {
                    $Proposal = dummy_quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('Operated_by',$Usercheck)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck ==null&& $status == 1 ) {
                    $Proposal = dummy_quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('status_document',1)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck ==null&& $status == 2 ) {
                    $Proposal = dummy_quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('status_document',2)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck ==null&& $status == 3 ) {
                    $Proposal = dummy_quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('status_document',3)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck ==null&& $status == 4 ) {
                    $Proposal = dummy_quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('status_document',4)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck ==null&& $status == 5 ) {
                    $Proposal = dummy_quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('status_document',5)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck ==null&& $status == 0 ) {
                    $Proposal = dummy_quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('status_document',0)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck !==null&& $status == 1 ) {
                    $Proposal = dummy_quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('Operated_by',$Usercheck)->whereIn('status_document',1)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck !==null&& $status == 2 ) {
                    $Proposal = dummy_quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('Operated_by',$Usercheck)->where('status_document',2)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck !==null&& $status == 3 ) {
                    $Proposal = dummy_quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('Operated_by',$Usercheck)->where('status_document',3)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck !==null&& $status == 4 ) {
                    $Proposal = dummy_quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('Operated_by',$Usercheck)->where('status_document',4)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck !==null&& $status == 5 ) {
                    $Proposal = dummy_quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('Operated_by',$Usercheck)->where('status_document',5)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck !==null&& $status == 0 ) {
                    $Proposal = dummy_quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('Operated_by',$Usercheck)->where('status_document',0)->orderBy('created_at', 'desc')->paginate($perPage);
                }
            }elseif ($Filter == null) {
                if ($Usercheck) {
                    if ($Usercheck !== null && $status == null) {
                        $Proposal = dummy_quotation::query()->orderBy('created_at', 'desc')->where('Operated_by',$Usercheck)->paginate($perPage);
                    }elseif ($Usercheck !== null && $status == 0) {
                        $Proposal = dummy_quotation::query()->where('Operated_by',$Usercheck)->where('status_document',0)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Usercheck !== null && $status == 1) {
                        $Proposal = dummy_quotation::query()->where('Operated_by',$Usercheck)->where('status_document',1)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Usercheck !== null && $status == 2) {
                        $Proposal = dummy_quotation::query()->where('Operated_by',$Usercheck)->where('status_document',2)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Usercheck !== null && $status == 3) {
                        $Proposal = dummy_quotation::query()->where('Operated_by',$Usercheck)->where('status_document',3)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Usercheck !== null && $status == 4) {
                        $Proposal = dummy_quotation::query()->where('Operated_by',$Usercheck)->where('status_document',4)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Usercheck !== null && $status == 5) {
                        $Proposal = dummy_quotation::query()->where('Operated_by',$Usercheck)->where('status_document',5)->orderBy('created_at', 'desc')->paginate($perPage);
                    }
                }else {
                    if ($status == 0) {
                        if ($status == null) {
                            $Proposal = dummy_quotation::query()->where('status_document',0)->paginate($perPage);
                        }else{
                            $Proposal = dummy_quotation::query()->where('status_document',0)->paginate($perPage);
                        }
                    }elseif ($status == 1) {
                        $Proposal = dummy_quotation::query()->where('status_document',1)->paginate($perPage);

                    }elseif ($status == 2) {
                        $Proposal = dummy_quotation::query()->where('status_document',2)->paginate($perPage);
                    }elseif ($status == 3) {
                        $Proposal = dummy_quotation::query()->where('status_document',3)->paginate($perPage);

                    }elseif ($status == 4) {
                        $Proposal = dummy_quotation::query()->where('status_document',4)->paginate($perPage);
                    }elseif ($status == 5) {
                        $Proposal = dummy_quotation::query()->where('status_document',5)->paginate($perPage);
                    }
                }
            }
            $Pending = dummy_quotation::query()->where('status_document',1)->paginate($perPage);
            $Approved = dummy_quotation::query()->where('status_document',3)->paginate($perPage);
            $Pendingcount = dummy_quotation::query()->where('status_document',1)->count();
            $Awaiting = dummy_quotation::query()->where('status_document',2)->paginate($perPage);
            $Awaitingcount = dummy_quotation::query()->where('status_document',2)->count();
            $Approvedcount = dummy_quotation::query()->where('status_document',3)->count();
            $Approved = dummy_quotation::query()->where('status_document',3)->paginate($perPage);
            $Reject = dummy_quotation::query()->where('status_document',4)->orderBy('created_at', 'desc')->paginate($perPage);
            $Rejectcount = dummy_quotation::query()->where('status_document',4)->count();
            $Cancel = dummy_quotation::query()->where('status_document',0)->orderBy('created_at', 'desc')->paginate($perPage);
            $Cancelcount = dummy_quotation::query()->where('status_document',0)->count();
            $Generatecount = dummy_quotation::query()->where('status_document',5)->count();
            $Generate = dummy_quotation::query()->where('status_document', 5 )->paginate($perPage);
        }
        if ($user->permission == 0) {

            $User = User::select('name','id')->where('id',$userid)->get();
            if ($Filter == 'All') {
                $Proposal = dummy_quotation::query()->where('Operated_by',$Usercheck)->orderBy('created_at', 'desc')->paginate($perPage);
            }elseif ($Filter == 'Nocheckin') {
                if ($Filter == 'Nocheckin'&&$checkin ==null&& $checkout == null&&$status == null && $Usercheck !== null) {
                    $Proposal = dummy_quotation::query()->where('checkin',null)->where('checkout',null)->where('Operated_by',$Usercheck)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($Filter == 'Nocheckin'&&$status == 1 && $Usercheck !== null) {
                    $Proposal = dummy_quotation::query()->where('checkin',null)->where('checkout',null)->where('Operated_by',$Usercheck)->where('status_document',1)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($Filter == 'Nocheckin'&&$status == 3 && $Usercheck !== null) {
                    $Proposal = dummy_quotation::query()->where('checkin',null)->where('checkout',null)->where('Operated_by',$Usercheck)->where('status_document',3)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($Filter == 'Nocheckin'&&$status == 2 && $Usercheck !== null) {
                    $Proposal = dummy_quotation::query()->where('checkin',null)->where('checkout',null)->where('Operated_by',$Usercheck)->where('status_document',2)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($Filter == 'Nocheckin'&&$status == 4 && $Usercheck !== null) {
                    $Proposal = dummy_quotation::query()->where('checkin',null)->where('checkout',null)->where('Operated_by',$Usercheck)->where('status_document',4)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($Filter == 'Nocheckin'&&$status == 5 && $Usercheck !== null) {
                    $Proposal = dummy_quotation::query()->where('checkin',null)->where('checkout',null)->where('Operated_by',$Usercheck)->where('status_document',5)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($Filter == 'Nocheckin'&&$status == 0 && $Usercheck !== null) {
                    $Proposal = dummy_quotation::query()->where('checkin',null)->where('checkout',null)->where('Operated_by',$Usercheck)->where('status_document',0)->orderBy('created_at', 'desc')->paginate($perPage);
                }
            }elseif ($Filter == 'Checkin') {
                if ($checkin && $checkout &&$Usercheck !==null&& $status == null ) {
                    $Proposal = dummy_quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('Operated_by',$Usercheck)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck !==null&& $status == 1 ) {
                    $Proposal = dummy_quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('Operated_by',$Usercheck)->where('status_document',1)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck !==null&& $status == 2 ) {
                    $Proposal = dummy_quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('Operated_by',$Usercheck)->where('status_document',2)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck !==null&& $status == 3 ) {
                    $Proposal = dummy_quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('Operated_by',$Usercheck)->where('status_document',3)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck !==null&& $status == 4 ) {
                    $Proposal = dummy_quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('Operated_by',$Usercheck)->where('status_document',4)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck !==null&& $status == 5 ) {
                    $Proposal = dummy_quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('Operated_by',$Usercheck)->where('status_document',5)->orderBy('created_at', 'desc')->paginate($perPage);
                }elseif ($checkin && $checkout &&$Usercheck !==null&& $status == 0 ) {
                    $Proposal = dummy_quotation::query()->where('checkin',$checkinDate)->where('checkout',$checkoutDate)->where('Operated_by',$Usercheck)->where('status_document',0)->orderBy('created_at', 'desc')->paginate($perPage);
                }
            }elseif ($Filter == null) {
                if ($Usercheck) {
                    if ($Usercheck !== null && $status == null) {
                        $Proposal = dummy_quotation::query()->orderBy('created_at', 'desc')->where('Operated_by',$Usercheck)->paginate($perPage);
                    }elseif ($Usercheck !== null && $status == 0) {
                        $Proposal = dummy_quotation::query()->where('Operated_by',$Usercheck)->where('status_document',0)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Usercheck !== null && $status == 1) {
                        $Proposal = dummy_quotation::query()->where('Operated_by',$Usercheck)->where('status_document',1)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Usercheck !== null && $status == 2) {
                        $Proposal = dummy_quotation::query()->where('Operated_by',$Usercheck)->where('status_document',2)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Usercheck !== null && $status == 3) {
                        $Proposal = dummy_quotation::query()->where('Operated_by',$Usercheck)->where('status_document',3)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Usercheck !== null && $status == 4) {
                        $Proposal = dummy_quotation::query()->where('Operated_by',$Usercheck)->where('status_document',4)->orderBy('created_at', 'desc')->paginate($perPage);
                    }elseif ($Usercheck !== null && $status == 5) {
                        $Proposal = dummy_quotation::query()->where('Operated_by',$Usercheck)->where('status_document',5)->orderBy('created_at', 'desc')->paginate($perPage);
                    }
                }
            }
            $Proposalcount = dummy_quotation::query()->where('Operated_by',$userid)->count();
            $Pending = dummy_quotation::query()->where('Operated_by',$userid)->where('status_document',1)->paginate($perPage);
            $Pendingcount = dummy_quotation::query()->where('Operated_by',$userid)->where('status_document',1)->count();
            $Awaiting = dummy_quotation::query()->where('Operated_by',$userid)->orderBy('created_at', 'desc')->where('status_document',2)->paginate($perPage);
            $Awaitingcount = dummy_quotation::query()->where('Operated_by',$userid)->where('status_document',2)->count();
            $Approved = dummy_quotation::query()->where('Operated_by',$userid)->orderBy('created_at', 'desc')->where('status_document',3)->paginate($perPage);
            $Approvedcount = dummy_quotation::query()->where('Operated_by',$userid)->where('status_document',3)->count();
            $Reject = dummy_quotation::query()->where('Operated_by',$userid)->orderBy('created_at', 'desc')->where('status_document',4)->paginate($perPage);
            $Rejectcount = dummy_quotation::query()->where('Operated_by',$userid)->where('status_document',4)->count();
            $Cancel = dummy_quotation::query()->where('Operated_by',$userid)->orderBy('created_at', 'desc')->where('status_document',0)->paginate($perPage);
            $Cancelcount = dummy_quotation::query()->where('Operated_by',$userid)->where('status_document',0)->count();
            $Generatecount = dummy_quotation::query()->where('Operated_by',$userid)->where('status_document',5)->count();
            $Generate = dummy_quotation::query()->where('Operated_by',$userid)->where('status_document', 5 )->paginate($perPage);
        }
        return view('quotation.index',compact('Proposalcount','Proposal','Awaitingcount','Awaiting','Pending','Pendingcount','Approved','Approvedcount','Rejectcount','Reject','Cancel','Cancelcount'
        ,'User','Generate','Generatecount'));
    }
    public function  paginate_table_dummyproposal(Request $request)
    {
        $perPage = (int)$request->perPage;
        $userid = Auth::user()->id;
        $data = [];
        $permissionid = Auth::user()->permission;
        if ($perPage == 10) {
            $data_query = dummy_quotation::query()->orderBy('created_at', 'desc')
            ->limit($request->page.'0')
            ->get();
        } else {
            $data_query = dummy_quotation::query()->orderBy('created_at', 'desc')->paginate($perPage);
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
                // สร้าง dropdown สำหรับการทำรายการ
                if (($key + 1) >= (int)$page_1 && ($key + 1) <= (int)$page_2 || (int)$perPage > 10 && $key < (int)$perPage2) {

                    if ($value->status_document == 1) {
                        // Checkbox is enabled
                        $checkbox = '
                        <div class="form-check form-check-inline">
                            <input class="form-check-input checkbox-select checkbox-'.($key + 1).'" type="checkbox" name="checkbox[]" value="'.$value->id.'" id="checkbox-'.($key + 1).'" rel="'.$value->vat.'">
                            <label class="form-check-label" for="checkbox-'.($key + 1).'"></label>
                        </div>';
                    } else {
                        // Checkbox is disabled
                        $checkbox = '
                        <div class="form-check form-check-inline">
                            <input class="form-check-input checkbox-select checkbox-'.($key + 1).'" type="checkbox" name="checkbox[]" value="'.$value->id.'" id="checkbox-'.($key + 1).'" rel="'.$value->vat.'" disabled>
                            <label class="form-check-label" for="checkbox-'.($key + 1).'"></label>
                        </div>';
                    }
                    if ($value->type_Proposal == 'Company') {
                        $name = '<td>' .@$value->company->Company_Name. '</td>';
                    }else {
                        $name = '<td>' . @$value->guest->First_name . ' ' . @$value->guest->Last_name . '</td>';
                    }
                    // สร้างสถานะการใช้งาน
                    if ($value->status_guest == 1) {
                        $btn_status = '<span class="badge rounded-pill bg-success">Approved</span>';
                    } else {
                        if ($value->status_document == 0) {
                            $btn_status = '<span class="badge rounded-pill bg-danger">Cancel</span>';
                        } elseif ($value->status_document == 1) {
                            $btn_status = '<span class="badge rounded-pill " style="background-color: #FF6633">Pending</span>';
                        } elseif ($value->status_document == 2) {
                            $btn_status = '<span class="badge rounded-pill bg-warning">Awaiting Approval</span>';
                        } elseif ($value->status_document == 3) {
                            $btn_status = '<span class="badge rounded-pill " style="background-color: #FF6633">Approved</span>';
                        } elseif ($value->status_document == 4) {
                            $btn_status = '<span class="badge rounded-pill " style="background-color:#1d4ed8">Reject</span>';
                        } elseif ($value->status_document == 5) {
                            $btn_status = '<span class="badge rounded-pill " style="background-color: #FF6633">Generate</span>';
                        }
                    }
                    $rolePermission = Auth::user()->rolePermissionData(Auth::user()->id);
                    $canViewProposal = Auth::user()->roleMenuView('Proposal', Auth::user()->id);
                    $canEditProposal = Auth::user()->roleMenuEdit('Proposal', Auth::user()->id);
                    $CreateBy = Auth::user()->id;
                    $isOperatedByCreator = $value->Operated_by == $CreateBy;

                    $btn_action = '<div class="dropdown">';
                    $btn_action .= '<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>';
                    $btn_action .= '<ul class="dropdown-menu border-0 shadow p-3">';

                    if ($rolePermission > 0) {
                        if ($rolePermission == 1 && $isOperatedByCreator) {
                            if ($canViewProposal) {
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/view/' . $value->id) . '">View</a></li>';
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" target="_blank" href="' . url('/Dummy/Proposal/Quotation/cover/document/PDF/' . $value->id) . '">Export</a></li>';
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/view/quotation/LOG/' . $value->id) . '">LOG</a></li>';
                            }
                            if ($canEditProposal) {
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/send/email/' . $value->id) . '">Send Email</a></li>';
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/edit/quotation/' . $value->id) . '">Edit</a></li>';
                                if ($value->status_document == 3 || ($value->status_document == 1 && $value->SpecialDiscountBath == 0 && $value->SpecialDiscount == 0)) {
                                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Approved(' . $value->id . ')">Approved</a></li>';
                                }
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel(' . $value->id . ')">Cancel</a></li>';
                            }
                        } elseif ($rolePermission == 2) {
                            if ($canViewProposal) {
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/view/' . $value->id) . '">View</a></li>';
                            }
                            if ($isOperatedByCreator) {
                                if ($canViewProposal) {
                                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/view/quotation/LOG/' . $value->id) . '">LOG</a></li>';
                                }
                                if ($canEditProposal) {
                                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" target="_blank" href="' . url('/Proposal/Quotation/cover/document/PDF/' . $value->id) . '">Export</a></li>';
                                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/send/email/' . $value->id) . '">Send Email</a></li>';
                                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/edit/quotation/' . $value->id) . '">Edit</a></li>';
                                    if ($value->status_document == 3 || ($value->status_document == 1 && $value->SpecialDiscountBath == 0 && $value->SpecialDiscount == 0)) {
                                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Approved(' . $value->id . ')">Approved</a></li>';
                                    }
                                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel(' . $value->id . ')">Cancel</a></li>';
                                }
                            }
                        } elseif ($rolePermission == 3) {
                            if ($canViewProposal) {
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/view/' . $value->id) . '">View</a></li>';
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/view/quotation/LOG/' . $value->id) . '">LOG</a></li>';
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" target="_blank" href="' . url('/Dummy/Proposal/Quotation/cover/document/PDF/' . $value->id) . '">Export</a></li>';
                            }
                            if ($canEditProposal) {
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/send/email/' . $value->id) . '">Send Email</a></li>';
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/edit/quotation/' . $value->id) . '">Edit</a></li>';
                                if ($value->status_document == 3 || ($value->status_document == 1 && $value->SpecialDiscountBath == 0 && $value->SpecialDiscount == 0)) {
                                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Approved(' . $value->id . ')">Approved</a></li>';
                                }
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel(' . $value->id . ')">Cancel</a></li>';
                            }
                        }
                    } else {
                        if ($canViewProposal) {
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/view/' . $value->id) . '">View</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" target="_blank" href="' . url('/Dummy/Proposal/Quotation/cover/document/PDF/' . $value->id) . '">Export</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/view/quotation/LOG/' . $value->id) . '">LOG</a></li>';
                        }
                    }


                    $btn_action .= '</ul>';
                    $btn_action .= '</div>';

                    $data[] = [
                        'number' => $checkbox,
                        'DummyNo' => $value->DummyNo,
                        'Company_Name' => $name,
                        'IssueDate' => $value->issue_date,
                        'ExpirationDate' => $value->Expirationdate,
                        'DiscountP' => $value->SpecialDiscount == 0 ? '-' : '<i class="bi bi-check-lg text-green"></i>',
                        'Approve' => $value->Confirm_by == null ? '-' : @$value->userConfirm->name,
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
    public function search_table_dummyproposal(Request $request)
    {
        $perPage = (int)$request->perPage;
        $search_value = $request->search_value;
        $guest_profile = $request->guest_profile;
        $userid = Auth::user()->id;
        $permissionid = Auth::user()->permission;
        if ($permissionid == 0) {
            if ($search_value) {
                $data_query = dummy_quotation::where('Operated_by',$userid)
                ->where('DummyNo', 'LIKE', '%'.$search_value.'%')
                ->orWhere('checkin', 'LIKE', '%'.$search_value.'%')
                ->orWhere('checkout', 'LIKE', '%'.$search_value.'%')
                ->orWhere('issue_date', 'LIKE', '%'.$search_value.'%')
                ->orWhere('Expirationdate', 'LIKE', '%'.$search_value.'%')
                ->where('Company_ID',$guest_profile)
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);
            }else{
                $perPageS = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
                $data_query = dummy_quotation::query()->where('Operated_by',$userid)->orderBy('created_at', 'desc')->paginate($perPageS);
            }
        }else{
            if ($search_value) {
                $data_query = dummy_quotation::where('DummyNo', 'LIKE', '%'.$search_value.'%')
                ->orWhere('checkin', 'LIKE', '%'.$search_value.'%')
                ->orWhere('checkout', 'LIKE', '%'.$search_value.'%')
                ->orWhere('issue_date', 'LIKE', '%'.$search_value.'%')
                ->orWhere('Expirationdate', 'LIKE', '%'.$search_value.'%')
                ->where('Company_ID',$guest_profile)
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);
            }else{
                $perPageS = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
                $data_query = dummy_quotation::query()->orderBy('created_at', 'desc')->paginate($perPageS);
            }
        }

        $data = [];
        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $btn_action = "";
                $btn_status = "";
                $name ="";
                $checkbox = "";
                // สร้าง dropdown สำหรับการทำรายการ
                if ($value->status_document == 1) {
                    // Checkbox is enabled
                    $checkbox = '<div class="form-check form-check-inline">
                                    <input class="form-check-input checkbox-select checkbox-' . ($key + 1) . '" type="checkbox" name="checkbox[]" value="' . $value->id . '" id="checkbox-' . ($key + 1) . '" rel="' . $value->vat . '">
                                    <label class="form-check-label" for="checkbox-' . ($key + 1) . '"></label>
                                </div>';
                } else {
                    // Checkbox is disabled
                    $checkbox = '<div class="form-check form-check-inline">
                                    <input class="form-check-input checkbox-select checkbox-' . ($key + 1) . '" type="checkbox" name="checkbox[]" value="' . $value->id . '" id="checkbox-' . ($key + 1) . '" rel="' . $value->vat . '" disabled>
                                    <label class="form-check-label" for="checkbox-' . ($key + 1) . '"></label>
                                </div>';
                }
                    if ($value->type_Proposal == 'Company') {
                        $name = '<td>' .@$value->company->Company_Name. '</td>';
                    }else {
                        $name = '<td>' . @$value->guest->First_name . ' ' . @$value->guest->Last_name . '</td>';
                    }
                    // สร้างสถานะการใช้งาน
                    if ($value->status_guest == 1) {
                        $btn_status = '<span class="badge rounded-pill bg-success">Approved</span>';
                    } else {
                        if ($value->status_document == 0) {
                            $btn_status = '<span class="badge rounded-pill bg-danger">Cancel</span>';
                        } elseif ($value->status_document == 1) {
                            $btn_status = '<span class="badge rounded-pill " style="background-color: #FF6633">Pending</span>';
                        } elseif ($value->status_document == 2) {
                            $btn_status = '<span class="badge rounded-pill bg-warning">Awaiting Approval</span>';
                        } elseif ($value->status_document == 3) {
                            $btn_status = '<span class="badge rounded-pill " style="background-color: #FF6633">Approved</span>';
                        } elseif ($value->status_document == 4) {
                            $btn_status = '<span class="badge rounded-pill " style="background-color:#1d4ed8">Reject</span>';
                        } elseif ($value->status_document == 5) {
                            $btn_status = '<span class="badge rounded-pill " style="background-color: #FF6633">Generate</span>';
                        }
                    }
                    $rolePermission = Auth::user()->rolePermissionData(Auth::user()->id);
                    $canViewProposal = Auth::user()->roleMenuView('Proposal', Auth::user()->id);
                    $canEditProposal = Auth::user()->roleMenuEdit('Proposal', Auth::user()->id);
                    $CreateBy = Auth::user()->id;
                    $isOperatedByCreator = $value->Operated_by == $CreateBy;

                    $btn_action = '<div class="dropdown">';
                    $btn_action .= '<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>';
                    $btn_action .= '<ul class="dropdown-menu border-0 shadow p-3">';

                    if ($rolePermission > 0) {
                        if ($rolePermission == 1 && $isOperatedByCreator) {
                            if ($canViewProposal) {
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/view/' . $value->id) . '">View</a></li>';
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" target="_blank" href="' . url('/Dummy/Proposal/Quotation/cover/document/PDF/' . $value->id) . '">Export</a></li>';
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/view/quotation/LOG/' . $value->id) . '">LOG</a></li>';
                            }
                            if ($canEditProposal) {
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/send/email/' . $value->id) . '">Send Email</a></li>';
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/edit/quotation/' . $value->id) . '">Edit</a></li>';
                                if ($value->status_document == 3 || ($value->status_document == 1 && $value->SpecialDiscountBath == 0 && $value->SpecialDiscount == 0)) {
                                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Approved(' . $value->id . ')">Approved</a></li>';
                                }
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel(' . $value->id . ')">Cancel</a></li>';
                            }
                        } elseif ($rolePermission == 2) {
                            if ($canViewProposal) {
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/view/' . $value->id) . '">View</a></li>';
                            }
                            if ($isOperatedByCreator) {
                                if ($canViewProposal) {
                                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/view/quotation/LOG/' . $value->id) . '">LOG</a></li>';
                                }
                                if ($canEditProposal) {
                                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" target="_blank" href="' . url('/Proposal/Quotation/cover/document/PDF/' . $value->id) . '">Export</a></li>';
                                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/send/email/' . $value->id) . '">Send Email</a></li>';
                                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/edit/quotation/' . $value->id) . '">Edit</a></li>';
                                    if ($value->status_document == 3 || ($value->status_document == 1 && $value->SpecialDiscountBath == 0 && $value->SpecialDiscount == 0)) {
                                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Approved(' . $value->id . ')">Approved</a></li>';
                                    }
                                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel(' . $value->id . ')">Cancel</a></li>';
                                }
                            }
                        } elseif ($rolePermission == 3) {
                            if ($canViewProposal) {
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/view/' . $value->id) . '">View</a></li>';
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/view/quotation/LOG/' . $value->id) . '">LOG</a></li>';
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" target="_blank" href="' . url('/Dummy/Proposal/Quotation/cover/document/PDF/' . $value->id) . '">Export</a></li>';
                            }
                            if ($canEditProposal) {
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/send/email/' . $value->id) . '">Send Email</a></li>';
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/edit/quotation/' . $value->id) . '">Edit</a></li>';
                                if ($value->status_document == 3 || ($value->status_document == 1 && $value->SpecialDiscountBath == 0 && $value->SpecialDiscount == 0)) {
                                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Approved(' . $value->id . ')">Approved</a></li>';
                                }
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel(' . $value->id . ')">Cancel</a></li>';
                            }
                        }
                    } else {
                        if ($canViewProposal) {
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/view/' . $value->id) . '">View</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" target="_blank" href="' . url('/Dummy/Proposal/Quotation/cover/document/PDF/' . $value->id) . '">Export</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/view/quotation/LOG/' . $value->id) . '">LOG</a></li>';
                        }
                    }


                    $btn_action .= '</ul>';
                    $btn_action .= '</div>';

                    $data[] = [
                        'number' => $checkbox,
                        'DummyNo' => $value->DummyNo,
                        'Company_Name' => $name,
                        'IssueDate' => $value->issue_date,
                        'ExpirationDate' => $value->Expirationdate,
                        'DiscountP' => $value->SpecialDiscount == 0 ? '-' : '<i class="bi bi-check-lg text-green"></i>',
                        'Approve' => $value->Confirm_by == null ? '-' : @$value->userConfirm->name,
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
}
