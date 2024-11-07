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
use App\Models\phone_guest;
use App\Models\Guest;
use App\Models\Master_company;
class DummyQuotationController extends Controller
{
    public function index()
    {
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $userid = Auth::user()->id;
        $Proposal = dummy_quotation::query()->orderBy('created_at', 'desc')->paginate($perPage);
        $Proposalcount = dummy_quotation::query()->count();
        $Pending = dummy_quotation::query()->orderBy('created_at', 'desc')->where('status_document', 1 )->paginate($perPage);
        $Pendingcount = dummy_quotation::query()->where('status_document',1)->count();
        $Awaitingcount = dummy_quotation::query()->where('status_document',2)->count();
        $Awaiting  = dummy_quotation::query()->orderBy('created_at', 'desc')->where('status_document', 2 )->paginate($perPage);
        $Approvedcount = dummy_quotation::query()->where('status_document',3)->count();
        $Approved = dummy_quotation::query()->orderBy('created_at', 'desc')->where('status_document', 3 )->paginate($perPage);
        $Rejectcount = dummy_quotation::query()->where('status_document',4)->count();
        $Reject = dummy_quotation::query()->orderBy('created_at', 'desc')->where('status_document', 4)->paginate($perPage);
        $Cancelcount = dummy_quotation::query()->where('status_document',0)->count();
        $Cancel = dummy_quotation::query()->orderBy('created_at', 'desc')->where('status_document',0)->paginate($perPage);
        $Generatecount = dummy_quotation::query()->where('status_document',5)->count();
        $Generate = dummy_quotation::query()->orderBy('created_at', 'desc')->where('status_document', 5 )->paginate($perPage);

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
        $search_value = $request->inputcompanyindividual;
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
            }elseif ($Filter == 'Company') {
                $nameCom = companys::where('Company_Name', 'LIKE', '%'.$search_value.'%')->first();
                $nameGuest = Guest::where('First_name', 'LIKE', '%'.$search_value.'%')->orWhere('Last_name', 'LIKE', '%'.$search_value.'%')->first();
                $porfile= null;
                if ($nameCom) {
                    $porfile = $nameCom->Profile_ID;
                }
                if ($nameGuest) {
                    $porfile = $nameGuest->Profile_ID;
                }
                if ($porfile) {
                    $Proposal = dummy_quotation::query()->where('Company_ID',$porfile)->paginate($perPage);
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
            }elseif ($Filter == 'Company') {
                $nameCom = companys::where('Company_Name', 'LIKE', '%'.$search_value.'%')->first();
                $nameGuest = Guest::where('First_name', 'LIKE', '%'.$search_value.'%')->orWhere('Last_name', 'LIKE', '%'.$search_value.'%')->first();
                $porfile= null;
                if ($nameCom) {
                    $porfile = $nameCom->Profile_ID;
                }
                if ($nameGuest) {
                    $porfile = $nameGuest->Profile_ID;
                }
                if ($porfile) {
                    $Proposal = dummy_quotation::query()->where('Company_ID',$porfile)->paginate($perPage);
                }
            }
            elseif ($Filter == null) {
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
        return view('dummy_quotation.index',compact('Proposalcount','Proposal','Awaitingcount','Awaiting','Pending','Pendingcount','Approved','Approvedcount','Rejectcount','Reject','Cancel','Cancelcount'
        ,'User','Generate','Generatecount'));
    }
    //---------------------------create-------------------
    public function create()
    {
        $currentDate = Carbon::now();
        $ID = 'DD-';
        $formattedDate = Carbon::parse($currentDate);       // วันที่
        $month = $formattedDate->format('m'); // เดือน
        $year = $formattedDate->format('y');
        $lastRun = dummy_quotation::latest()->first();
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
        $Guest = Guest::select('First_name','Last_name','id','Profile_ID')->get();
        $settingCompany = Master_company::orderBy('id', 'desc')->first();
        return view('dummy_quotation.create',compact('Quotation_ID','Company','Mevent','Freelancer_member','Issue_date','Valid_Until','Mvat','settingCompany','Guest'));
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
        if (!$company_fax) {
            $company_fax = '-';
        }
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
    public function Guestcreate($Guest){
        $Guest = Guest::where('Profile_ID',$Guest)->first();
        $Profile_ID=$Guest->Profile_ID;
        $Company_typeID=$Guest->preface;
        $CityID=$Guest->City;
        $amphuresID = $Guest->Amphures;
        $TambonID = $Guest->Tambon;
        $Company_type = master_document::where('id',$Company_typeID)->select('name_th','id')->first();
        $provinceNames = province::where('id',$CityID)->select('name_th','id')->first();
        $amphuresID = amphures::where('id',$amphuresID)->select('name_th','id')->first();
        $TambonID = districts::where('id',$TambonID)->select('name_th','id','Zip_Code')->first();
        $phone = phone_guest::where('Profile_ID',$Profile_ID)->where('Sequence','main')->first();
        return response()->json([
            'data' => $Guest,
            'phone'=>$phone,
            'Company_type'=>$Company_type,
            'province'=>$provinceNames,
            'amphures'=>$amphuresID,
            'Tambon'=>$TambonID,
        ]);
    }
    public function save(Request $request){
        $data = $request->all();
        $preview=$request->preview;
        $ProposalID =$request->Quotation_ID;
        $adult = (int) $request->input('Adult', 0); // ใช้ค่าเริ่มต้นเป็น 0 ถ้าค่าไม่ถูกต้อง
        $children = (int) $request->input('Children', 0);
        $SpecialDiscount = floor($request->SpecialDiscount);
        $SpecialDiscountBath = $request->DiscountAmount;
        $userid = Auth::user()->id;
        $Proposal_ID = dummy_quotation::where('Quotation_ID',$ProposalID)->first();
        if ($Proposal_ID) {
            $currentDate = Carbon::now();
            $ID = 'PD-';
            $formattedDate = Carbon::parse($currentDate);       // วันที่
            $month = $formattedDate->format('m'); // เดือน
            $year = $formattedDate->format('y');
            $lastRun = dummy_quotation::latest()->first();
            $nextNumber = 1;
            $lastRunid = $lastRun->id;
            $nextNumber = $lastRunid + 1;
            $newRunNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            $Quotation_ID = $ID.$year.$month.$newRunNumber;
        }else{
            $Quotation_ID =$ProposalID;
        }
        if ($preview == 1) {
            try {
                $datarequest = [
                    'Proposal_ID' => $Quotation_ID,
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
                    'Quantitymain' => $data['Quantitymain'] ?? null,
                    'priceproductmain' => $data['priceproductmain'] ?? null,
                    'discountmain' => $data['discountmain'] ?? null,
                    'comment' => $data['comment'] ?? null,
                    'PaxToTalall' => $data['PaxToTalall'] ?? null,
                    'Checkin' => $data['Checkin'] ?? null,
                    'Checkout' => $data['Checkout'] ?? null,
                    'Day' => $data['Day'] ?? null,
                    'Night' => $data['Night'] ?? null,
                    'Unitmain' => $data['Unitmain'] ?? null,
                ];

                $Products = Arr::wrap($datarequest['ProductIDmain']);
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
                    $linkQR = $protocol . '://' . $request->getHost() . "/Quotation/Quotation/cover/document/PDF/$id?page_shop=" . $request->input('page_shop');
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
                    'SpecialDistext'=>$SpecialDistext,
                    'Mvat'=>$Mvat,
                    'comment'=>$comment,
                    'Mevent'=>$Mevent,
                    'Contact_Name'=>$Contact_Name,
                    'Contact_phone'=>$Contact_phone,
                    'Contact_Email'=>$Contact_Email,
                ];
                $view= $template->name;
                $pdf = FacadePdf::loadView('quotationpdf.preview',$data);
                return $pdf->stream();
            } catch (\Exception $e) {
                return redirect()->route('DummyQuotation.index')->with('error', $e->getMessage());
            }
        }else{

            try {
                $datarequest = [
                    'Proposal_ID' => $Quotation_ID,
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
                $pax=$datarequest['pax'];
                $productsArray = [];

                foreach ($Products as $index => $ProductID) {
                    $saveProduct = [
                        'Quotation_ID' => $datarequest['Proposal_ID'],
                        'Company_ID' => $datarequest['Data_ID'],
                        'Product_ID' => $ProductID,
                        'pax' => $pax[$index] ?? 0,
                        'Issue_date' => $datarequest['IssueDate'],
                        'discount' => $discounts[$index],
                        'priceproduct' => $priceUnits[$index],
                        'netpriceproduct' => $discountedPrices[$index],
                        'totaldiscount' => $discountedPricestotal[$index],
                        'ExpirationDate' => $datarequest['Expiration'],
                        'freelanceraiffiliate' => $datarequest['FreelancerMember'],
                        'Quantity' => $quantities[$index],
                        'Unit' => $Unitmain[$index],
                        'Document_issuer' => $userid,
                    ];

                    $productsArray[] = $saveProduct;
                }
                {
                    $Quotation_ID = $datarequest['Proposal_ID'];
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
                                    'Product_Unit' => $quantity_name,// หรือระบุฟิลด์ที่ต้องการจาก $productDetails
                                ];
                            }
                        }
                    }
                    $formattedProductData = [];
                    foreach ($productData as $product) {
                        $formattedPrice = number_format($product['netpriceproduct']).' '.'บาท';
                        $formattedProductData[] = 'Description : ' . $product['Product_Name'] . ' , ' . 'Quantity : ' . $product['Quantity'] . ' ' . $product['Product_Quantity'] . ' , '. 'Unit : '. $product['Unit']. ' ' . $product['Product_Unit'] . ' , ' . 'Price Product : ' . $formattedPrice;
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
                            }else{
                                $fullName = $comtype->name_th . $Compannyname;
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

                    $datacompany = '';

                    $variables = [$QuotationID, $Issue_Date, $Expiration_Date, $fullName, $Contact_Name,$Time,$nameevent,$namevat,$Head];

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
                    $save->Company_ID = $Quotation_ID;
                    $save->type = 'Create';
                    $save->Category = 'Create :: Dummy Proposal';
                    $save->content =$datacompany;
                    $save->save();
                }
            } catch (\Exception $e) {
                return redirect()->route('DummyQuotation.index')->with('error', $e->getMessage());
            }
            {
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
                    'Quantitymain' => $data['Quantitymain'] ?? null,
                    'priceproductmain' => $data['priceproductmain'] ?? null,
                    'discountmain' => $data['discountmain'] ?? null,
                    'comment' => $data['comment'] ?? null,
                    'PaxToTalall' => $data['PaxToTalall'] ?? null,
                    'Checkin' => $data['Checkin'] ?? null,
                    'Checkout' => $data['Checkout'] ?? null,
                    'Day' => $data['Day'] ?? null,
                    'Night' => $data['Night'] ?? null,
                    'Unitmain' => $data['Unitmain'] ?? null,
                ];
                $Products = Arr::wrap($datarequest['ProductIDmain']);
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
                }
            }
            {
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
                    $productsArray = [];
                    foreach ($Products as $index => $ProductID) {
                        $saveProduct = [
                            'Quotation_ID' => $datarequest['Proposal_ID'],
                            'Company_ID' => $datarequest['Data_ID'],
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
            }
            try {
                $save = new dummy_quotation();
                $save->DummyNo = $Quotation_ID;
                $save->Company_ID = $datarequest['Data_ID'];
                $save->company_contact = $datarequest['Data_ID'];
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
                $save->type_Proposal = $Selectdata;
                $save->issue_date = $request->IssueDate;
                $save->ComRateCode = $request->Company_Discount;
                $save->Expirationdate = $request->Expiration;
                $save->Operated_by = $userid;
                $save->AddTax = $AddTax;
                $save->Nettotal = $Nettotal;
                $save->comment = $request->comment;
                $save->SpecialDiscount = $SpecialDiscount;
                $save->additional_discount = $request->Add_discount;
                $save->Date_type = $request->Date_type;
                $save->status_document = 1;
                $save->save();
                if ($Products !== null) {
                    foreach ($Products as $index => $ProductID) {
                        $saveProduct = new document_dummy_quotation();
                        $saveProduct->Quotation_ID = $Quotation_ID;
                        $saveProduct->Company_ID = $datarequest['Data_ID'];
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
                }else{
                    $delete = dummy_quotation::find($id);
                    $delete->delete();
                    return redirect()->route('DummyQuotation.index')->with('success', 'ใบเสนอราคายังไม่ถูกสร้าง');
                }
            } catch (\Exception $e) {
                return redirect()->route('DummyQuotation.index')->with('error', $e->getMessage());
            }
        }
        return redirect()->route('DummyQuotation.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }
    //-----------------------------edit-----------------------
    public function edit($id)
    {
        $settingCompany = Master_company::orderBy('id', 'desc')->first();
        $Quotation = dummy_quotation::where('id', $id)->first();
        $Quotation_ID = $Quotation->DummyNo;
        $Company = companys::select('Company_Name','id','Profile_ID')->get();
        $Guest = Guest::select('First_name','Last_name','id','Profile_ID')->get();
        $Mevent = master_document::select('name_th','id')->where('status', '1')->where('Category','Mevent')->get();
        $Mvat = master_document::select('name_th','id')->where('status', '1')->where('Category','Mvat')->get();
        $Freelancer_member = Freelancer_Member::select('First_name','id','Profile_ID','Last_name')->where('status', '1')->get();
        $selectproduct = document_dummy_quotation::where('Quotation_ID', $Quotation_ID)->get();
        $unit = master_unit::where('status',1)->get();
        $quantity = master_quantity::where('status',1)->get();
        return view('dummy_quotation.edit',compact('settingCompany','Quotation','Quotation_ID','Company','Guest','Mevent','Mvat','Freelancer_member','selectproduct','unit','quantity'));
    }
    public function update(Request $request,$id)
    {
        $preview = $request->preview;
        $Quotation_ID=$request->Quotation_ID;
        $adult=$request->Adult;
        $children=$request->Children;
        $SpecialDiscount = $request->SpecialDiscount;
        $SpecialDiscountBath = $request->DiscountAmount;
        $data = $request->all();
        if ($preview == 1) {
            try {
                $userid = Auth::user()->id;
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
                    $linkQR = $protocol . '://' . $request->getHost() . "/Quotation/Quotation/cover/document/PDF/$id?page_shop=" . $request->input('page_shop');
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
                        }else{
                            $fullName = $comtype->name_th . $Compannyname;
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
                $pdf = FacadePdf::loadView('quotationpdf.preview',$data);
                return $pdf->stream();
            } catch (\Throwable $e) {
                return redirect()->route('DummyQuotation.index')->with('error', $e->getMessage());
            }
        }else{
            try {
                $userid = Auth::user()->id;
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
                        'Company_ID' => $request->Company,
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
                $DataProduct = [
                    'Quotation_ID' => $data['Quotation_ID'] ?? null,
                    'issue_date' => $data['IssueDate'] ?? null,
                    'Expirationdate' => $data['Expiration'] ?? null,
                    'type_Proposal' => $data['selectdata'] ?? null,
                    'Company_ID' => $data['Guest'] ?? $data['Company'] ?? null,
                    'adult' => $data['Adult'] ?? null,
                    'children' => $data['Children'] ?? null,
                    'eventformat' => $data['Mevent'] ?? null,
                    'vat_type' => $data['Mvat'] ?? null,
                    'SpecialDiscountBath' => $data['DiscountAmount'] ?? null,
                    'comment' => $data['comment'] ?? null,
                    'TotalPax' => $data['PaxToTalall'] ?? null,
                    'freelanceraiffiliate' => $data['Freelancer_member'] ?? null,
                    'checkin' => $data['Checkin'] ?? null,
                    'checkout' => $data['Checkout'] ?? null,
                    'day' => $data['Day'] ?? null,
                    'night' => $data['Night'] ?? null,

                ];
                $DataProduct['Products'] = $productsArray;
                $ProposalData = dummy_quotation::where('id',$id)->first();
                $ProposalID = $ProposalData->DummyNo;
                $ProposalProducts = document_dummy_quotation::where('Quotation_ID',$ProposalID)->get();
                $dataArray = $ProposalData->toArray();
                $dataArray['Products'] = $ProposalProducts->map(function($item) {
                    // ปรับแต่ง $item ที่ได้จากแต่ละแถว
                    unset($item['id'], $item['created_at'], $item['updated_at'], $item['SpecialDiscount']);
                    return $item;
                })->toArray();

                $keysToCompare = ['Quotation_ID', 'issue_date', 'Expirationdate', 'type_Proposal','Company_ID', 'company_contact', 'checkin', 'checkout', 'day', 'night', 'adult', 'children', 'comment', 'eventformat', 'vat_type', 'SpecialDiscountBath', 'TotalPax', 'Products'];
                $differences = [];
                foreach ($keysToCompare as $key) {
                    if (isset($dataArray[$key]) && isset($DataProduct[$key])) {
                        // Check if both values are arrays
                        if (is_array($dataArray[$key]) && is_array($DataProduct[$key])) {
                            foreach ($dataArray[$key] as $index => $value) {
                                if (isset($DataProduct[$key][$index])) {
                                    if ($value != $DataProduct[$key][$index]) {
                                        $differences[$key][$index] = [
                                            'dataArray' => $value,
                                            'request' => $DataProduct[$key][$index]
                                        ];
                                    }
                                } else {
                                    $differences[$key][$index] = [
                                        'dataArray' => $value,
                                        'request' => null
                                    ];
                                }
                            }
                            // Handle case where $datarequest has extra elements
                            foreach ($DataProduct[$key] as $index => $value) {
                                if (!isset($dataArray[$key][$index])) {
                                    $differences[$key][$index] = [
                                        'dataArray' => null,
                                        'request' => $value
                                    ];
                                }
                            }
                        } else {
                            // Compare non-array values
                            if ($dataArray[$key] != $DataProduct[$key]) {
                                $differences[$key] = [
                                    'dataArray' => $dataArray[$key],
                                    'request' => $DataProduct[$key]
                                ];
                            }
                        }
                    } elseif (isset($dataArray[$key])) {
                        // Handle case where $datarequest does not have the key
                        $differences[$key] = [
                            'dataArray' => $dataArray[$key],
                            'request' => null
                        ];
                    } elseif (isset($DataProduct[$key])) {
                        // Handle case where $dataArray does not have the key
                        $differences[$key] = [
                            'dataArray' => null,
                            'request' => $DataProduct[$key]
                        ];
                    }
                }
                $dataArrayProductIds = collect($dataArray['Products'])->map(function ($item) {
                    return implode('|', [
                        $item['Product_ID'] ?? '',
                        $item['discount'] ?? '',
                        $item['Quantity'] ?? '',
                        $item['Unit'] ?? '',
                        $item['totaldiscount'] ?? ''
                    ]);
                })->unique();

                // ดึงค่าจาก Request Products และแปลงเป็น string
                $requestProductIds = collect($DataProduct['Products'])->map(function ($item) {
                    return implode('|', [
                        $item['Product_ID'] ?? '',
                        $item['discount'] ?? '',
                        $item['Quantity'] ?? '',
                        $item['Unit'] ?? '',
                        $item['totaldiscount'] ?? ''
                    ]);
                })->unique();

                // หาค่าที่แตกต่าง
                $onlyInDataArray = $dataArrayProductIds->diff($requestProductIds)->values()->all();
                $onlyInRequest = $requestProductIds->diff($dataArrayProductIds)->values()->all();

                $onlyInDataArray = collect($onlyInDataArray)->map(function ($item) {
                    $parts = explode('|', $item);
                    return [
                        'Product_ID' => $parts[0],
                        'discount' => $parts[1],
                        'Quantity' => $parts[2],
                        'Unit' => $parts[3],
                        'totaldiscount' => $parts[4]
                    ];
                })->values()->all();

                $onlyInRequest = collect($onlyInRequest)->map(function ($item) {
                    $parts = explode('|', $item);
                    return [
                        'Product_ID' => $parts[0],
                        'discount' => $parts[1],
                        'Quantity' => $parts[2],
                        'Unit' => $parts[3],
                        'totaldiscount' => $parts[4]
                    ];
                })->values()->all();
                $onlyInDataArray = collect($onlyInDataArray);
                $onlyInRequest = collect($onlyInRequest);

                $extractedData = [];
                $extractedDataA = [];
                // วนลูปเพื่อดึงชื่อคีย์และค่าจาก differences
                foreach ($differences as $key => $value) {
                    if ($key === 'Products') {
                        // ถ้าเป็น Products ให้เก็บค่า request และ dataArray ที่แตกต่างกัน

                        $extractedData[$key] = $onlyInDataArray->toArray(); // ใช้ข้อมูลจาก $onlyInRequest
                        $extractedDataA[$key] = $onlyInRequest->toArray(); // ใช้ข้อมูลจาก $onlyInDataArray
                    } elseif (isset($value['request'][0])) {
                        // สำหรับคีย์อื่นๆ ให้เก็บค่าแรกจาก array
                        // $extractedData[$key] = $value['request'][0];
                        $extractedData[$key] = $value['request'];
                    } else {
                        // $extractedData[$key] = $value['request'] ?? null;
                        $extractedDataA[$key] = $value['dataArray'];
                    }
                }

                $Company_ID = $extractedData['Company_ID'] ?? null;
                $company_contact = $extractedData['company_contact'] ?? null;
                $checkin =  $extractedData['checkin'] ?? null;
                $checkout =  $extractedData['checkout'] ?? null;
                $day =  $extractedData['day'] ?? null;
                $night =  $extractedData['night'] ?? null;
                $adult =  $extractedData['adult'] ?? null;
                $children = $extractedData['children'] ?? null;
                $comment =  $extractedData['comment'] ?? null;
                $eventformat =  $extractedData['eventformat'] ?? null;
                $vat_type =  $extractedData['vat_type'] ?? null;
                $SpecialDiscountBath =  $extractedData['SpecialDiscountBath'] ?? null;
                $TotalPax =  $extractedData['TotalPax'] ?? null;
                $Products =  $extractedData['Products'] ?? null;
                $ProductsA =  $extractedDataA['Products'] ?? null;
                $issue_date =  $extractedDataA['issue_date'] ?? null;
                $Expirationdate =  $extractedDataA['Expirationdate'] ?? null;
                $Selectdata = $DataProduct['type_Proposal'];
                $fullName = null;
                $Contact_Name = null;
                $Name = null;
                if ($Selectdata == 'Guest') {
                    if ($Company_ID) {
                        $Data = Guest::where('Profile_ID',$Company_ID)->first();
                        $prename = $Data->preface;
                        $First_name = $Data->First_name;
                        $Last_name = $Data->Last_name;
                        $prefix = master_document::where('id',$prename)->where('Category','Mprename')->where('status',1)->first();
                        $name = $prefix->name_th;
                        $fullName = $name.$First_name.' '.$Last_name;
                    }
                }else{
                    if ($Company_ID) {
                        $Company = companys::where('Profile_ID',$Company_ID)->first();
                        $Company_type = $Company->Company_type;
                        $Compannyname = $Company->Company_Name;
                        $comtype = master_document::where('id', $Company_type)->where('Category', 'Mcompany_type')->first();
                        if ($comtype) {
                            if ($comtype->name_th == "บริษัทจำกัด") {
                                $Name = "บริษัท " . $Compannyname . " จำกัด";
                            } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                                $Name = "บริษัท " . $Compannyname . " จำกัด (มหาชน)";
                            } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                                $Name = "ห้างหุ้นส่วนจำกัด " . $Compannyname;
                            }else{
                                $Name = $comtype->name_th . $Compannyname;
                            }
                        }
                        $representative = representative::where('Company_ID',$Company_ID)->first();
                        $prename = $representative->prefix;
                        $prefix = master_document::where('id', $prename)->where('Category', 'Mprename')->first();
                        $name = $prefix->name_th;
                        $Contact_Name = 'ตัวแทน : '.$name.$representative->First_name.' '.$representative->Last_name;
                        $fullName = $Name.'+'.$Contact_Name;
                    }
                }
                $Checkin =null;
                if ($checkin || $checkout) {
                    $Checkin = 'Check in date : '.$checkin;
                    if ($checkin&&$checkout) {
                        $Checkin = 'Check in date : '.$checkin.' '.'Check out date : '.$checkout;
                    }elseif ($checkout) {
                        $Checkin = 'Check out date : '.$checkout;
                    }
                }
                $DAY =null;
                if ($day || $night) {
                    $DAY = 'Day : '.$day;
                    if ($day&&$night) {
                        $DAY = 'Day : '.$day.' '.'Night : '.$night;
                    }elseif ($night) {
                        $DAY = 'Night : '.$night;
                    }
                }
                $people =null;
                if ($adult || $children) {
                    $people = 'Adult : '.$adult;
                    if ($adult&&$children) {
                        $people = 'Adult : '.$adult.' '.'Children : '.$children;
                    }elseif ($children) {
                        $people = 'Children : '.$children;
                    }
                }
                $Comment = null;
                if ($comment) {
                    $Comment = 'comment : '.$comment;
                }
                $nameevent = null;
                if ($eventformat) {
                    $Mevent = master_document::where('id',$eventformat)->where('status', '1')->where('Category','Mevent')->first();
                    $nameevent = 'ประเภท : '.$Mevent->name_th;
                }
                $namevat = null;
                if ($vat_type) {
                    $Mvat = master_document::where('id',$vat_type)->where('status', '1')->where('Category','Mvat')->first();
                    $namevat = 'ประเภท VAT : '.$Mvat->name_th;
                }
                $discount = null;
                if ($SpecialDiscountBath) {
                    $discount = 'ส่วนลด : '.$SpecialDiscountBath;
                }
                $Pax = null;
                if ($TotalPax) {
                    $Pax = 'รวมความจุของห้องพัก : '.$TotalPax;
                }
                $issue_date = null;
                if ($issue_date) {
                    $issue_date = 'วันเริ่มใช้งานเอกสาร : '.$issue_date;
                }
                $Expirationdate = null;
                if ($Expirationdate) {
                    $Expirationdate = 'วันหมดอายุเอกสาร : '.$Expirationdate;
                }
                // กำหนดค่าเริ่มต้นให้กับตัวแปร
                $formattedProductData = [];
                $formattedProductDataA = [];

                // หาก $Products มีค่า
                if ($Products) {
                    $productData = [];
                    foreach ($Products as $product) {
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
                                'Product_Unit' => $quantity_name,// หรือระบุฟิลด์ที่ต้องการจาก $productDetails
                            ];
                        }
                    }


                    // จัดรูปแบบข้อมูลของผลิตภัณฑ์
                    foreach ($productData as $product) {
                        $formattedPrice = number_format($product['netpriceproduct']).' '.'บาท';
                        $formattedProductData[] = ' + '.'ลบรายการ' . '+ ' .'Description : ' . $product['Product_Name'] . ' , ' . 'Quantity : ' . $product['Quantity'] . ' ' . $product['Product_Quantity'] . ' , '. 'Unit : '. $product['Unit']. ' ' . $product['Product_Unit'] . ' , ' . 'Price Product : ' . $formattedPrice;
                    }
                }

                // หาก $ProductsA มีค่า
                if ($ProductsA) {
                    $productDataA = [];
                    foreach ($ProductsA as $product) {
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
                            $productDataA[] = [
                                'Product_ID' => $productID,
                                'Quantity' => $product['Quantity'],
                                'Unit' => $product['Unit'],
                                'netpriceproduct' => $product['totaldiscount'],
                                'Product_Name' => $ProductName,
                                'Product_Quantity' => $unitName,
                                'Product_Unit' => $quantity_name,// หรือระบุฟิลด์ที่ต้องการจาก $productDetails
                            ];
                        }
                    }

                    // จัดรูปแบบข้อมูลของผลิตภัณฑ์
                    foreach ($productDataA as $product) {
                        $formattedPrice = number_format($product['netpriceproduct']).' '.'บาท';
                        $formattedProductDataA[] = ' + '.'เพิ่มรายการ' . '+ ' .'Description : ' . $product['Product_Name'] . ' , ' . 'Quantity : ' . $product['Quantity'] . ' ' . $product['Product_Quantity'] . ' , '. 'Unit : '. $product['Unit']. ' ' . $product['Product_Unit'] . ' , ' . 'Price Product : ' . $formattedPrice;
                    }
                }

                $datacompany = '';
                // แปลง array ของ $formattedProductData เป็น string เดียวที่มีรายการทั้งหมด
                $formattedProductDataString = implode(' + ', $formattedProductData);
                $formattedProductDataStringA = implode(' + ', $formattedProductDataA);
                $variables = [$fullName,$issue_date, $Expirationdate, $Checkin, $DAY,$people,$nameevent,$namevat,$discount
                ,$Pax,$Comment];
                $variables[] = $formattedProductDataString;
                $variables[] = $formattedProductDataStringA;
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
                $save->Company_ID = $Quotation_ID;
                $save->type = 'Edit';
                $save->Category = 'Edit :: Dummy Proposal';
                $save->content =$datacompany;
                $save->save();

                $save = dummy_quotation::find($id);
                $save->Quotation_ID = $Quotation_ID;
                $save->DummyNo = $Quotation_ID;
                $save->Company_ID = $datarequest['Data_ID'];
                $save->company_contact = $datarequest['Data_ID'];
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
                $save->type_Proposal = $Selectdata;
                $save->issue_date = $request->IssueDate;
                $save->ComRateCode = $request->Company_Discount;
                $save->Expirationdate = $request->Expiration;
                $save->Operated_by = $userid;
                $save->comment = $request->comment;
                $save->SpecialDiscount = $SpecialDiscount;
                $save->additional_discount = $request->Add_discount;
                $save->status_document = 1;
                $save->save();
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

                $productold = document_dummy_quotation::where('Quotation_ID', $Quotation_ID)->delete();
                if ($Products !== null) {
                    foreach ($Products as $index => $ProductID) {
                        $saveProduct = new document_dummy_quotation();
                        $saveProduct->Quotation_ID = $Quotation_ID;
                        $saveProduct->Company_ID = $datarequest['Data_ID'];
                        $saveProduct->Product_ID = $ProductID;
                        $saveProduct->Issue_date = $request->IssueDate;
                        $paxValue = $pax[$index] ?? 0;
                        $saveProduct->pax = $paxValue;
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
                {
                    //-----------------------PDF---------------------------
                    $datarequestPDF = [
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
                        'CheckProduct' => $data['CheckProduct'] ?? null,
                        'Quantitymain' => $data['Quantitymain'] ?? null,
                        'ProductIDmain' => $data['ProductIDmain'] ?? null,
                        'pax' => $data['pax'] ?? null,
                        'priceproductmain' => $data['priceproductmain'] ?? null,
                        'discountmain' => $data['discountmain'] ?? null,
                        'comment' => $data['comment'] ?? null,
                        'PaxToTalall' => $data['PaxToTalall'] ?? null,
                        'Checkin' => $data['Checkin'] ?? null,
                        'Checkout' => $data['Checkout'] ?? null,
                        'Day' => $data['Day'] ?? null,
                        'Night' => $data['Night'] ?? null,
                        'Unitmain' => $data['Unitmain'] ?? null,
                    ];

                    $Products = $datarequestPDF['ProductIDmain'];
                    $Productslast = $datarequestPDF['CheckProduct'];
                    $pax=$datarequestPDF['pax'];
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
                    $quantities = $datarequestPDF['Quantitymain'] ?? [];
                    $discounts = $datarequestPDF['discountmain'] ?? [];
                    $priceUnits = $datarequestPDF['priceproductmain'] ?? [];
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

                                $discountedPrice = (($totalPrice * $discount) / 100);
                                $discountedPrices[] = $discountedPrice;

                                $discountedPriceTotal = $totalPrice - $discountedPrice;
                                $discountedPricestotal[] = $discountedPriceTotal;
                            }
                        }
                        $items = master_product_item::where('Product_ID', $productID)->get();
                        $QuotationVat= $datarequestPDF['Mvat'];
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

                        $SpecialDistext = $datarequestPDF['DiscountAmount'];
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
                    }

                    $Quotation = dummy_quotation::where('DummyNo',$Quotation_ID)->first();
                    $Quotation->AddTax = $AddTax;
                    $Quotation->Nettotal = $Nettotal;
                    $Quotation->save();
                    return redirect()->route('DummyQuotation.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
                }
            } catch (\Throwable $e) {
                return redirect()->route('DummyQuotation.index')->with('error', $e->getMessage());
            }
        }
    }
    //----------------------------------view-------------------
    public function view($id)
    {
        $settingCompany = Master_company::orderBy('id', 'desc')->first();
        $Quotation = dummy_quotation::where('id', $id)->first();
        $Quotation_ID = $Quotation->DummyNo;
        $Company = companys::select('Company_Name','id','Profile_ID')->get();
        $Guest = Guest::select('First_name','Last_name','id','Profile_ID')->get();
        $Mevent = master_document::select('name_th','id')->where('status', '1')->where('Category','Mevent')->get();
        $Mvat = master_document::select('name_th','id')->where('status', '1')->where('Category','Mvat')->get();
        $Freelancer_member = Freelancer_Member::select('First_name','id','Profile_ID','Last_name')->where('status', '1')->get();
        $selectproduct = document_dummy_quotation::where('Quotation_ID', $Quotation_ID)->get();
        $unit = master_unit::where('status',1)->get();
        $quantity = master_quantity::where('status',1)->get();
        return view('dummy_quotation.view',compact('settingCompany','Quotation','Quotation_ID','Company','Guest','Mevent','Mvat','Freelancer_member','selectproduct','unit','quantity'));
    }
    //-----------------------------send document---------------
    public function senddocuments(Request $request)
    {
        // Retrieve `ids` from the POST request body
        $idsArray = $request->input('ids');

        // Retrieve the authenticated user ID
        $userid = Auth::user()->id;

        // Fetch documents from the database using the array of IDs
        $documents = dummy_quotation::whereIn('id', $idsArray)->get();

        foreach ($documents as $document) {
            $DummyNo = $document->DummyNo;

            // Update document status
            $document->status_document = 2;
            $document->save();

            // Log the document action
            $log = new log_company();
            $log->Created_by = $userid;
            $log->Company_ID = $DummyNo;
            $log->type = 'Send documents';
            $log->Category = 'Send documents :: Dummy Proposal';
            $log->content = 'Send Document Dummy Proposal ID: ' . $DummyNo;
            $log->save();
        }

        return response()->json(['success' => true, 'message' => 'Documents updated successfully!']);
    }

    //-----------------------Generate-------------------------
    public function Generate(Request $request ,$id){
        try {
            $dummy = dummy_quotation::where('id', $id)->first();
            $dummy->status_document = 5;
            $dummy->save();
            $dummyID = $dummy->DummyNo;
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
            $newRunNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            $Quotation_ID = $ID.$year.$month.$newRunNumber;

            $userid = Auth::user()->id;
            $save = new log_company();
            $save->Created_by = $userid;
            $save->Company_ID = $dummyID;
            $save->type = 'Generate';
            $save->Category = 'Generate :: Dummy Proposal';
            $save->content = 'Generate to Proposal '.'+'.'Document Dummy Proposal ID :'.$dummyID.' to Proposal ID : '.$Quotation_ID;
            $save->save();

            $save = new Quotation();
            $save->DummyNo = $dummyID;
            $save->Quotation_ID = $Quotation_ID;
            $save->Company_ID = $dummy->Company_ID;
            $save->company_contact = $dummy->company_contact;
            $save->checkin = $dummy->checkin;
            $save->checkout = $dummy->checkout;
            $save->day = $dummy->day;
            $save->night = $dummy->night;
            $save->adult = $dummy->adult;
            $save->children = $dummy->children;
            $save->ComRateCode = $dummy->ComRateCode;
            $save->SpecialDiscount = $dummy->SpecialDiscount;
            $save->Nettotal = $dummy->Nettotal;
            $save->total = $dummy->Nettotal;
            $save->AddTax = $dummy->AddTax;
            $save->TotalPax = $dummy->TotalPax;
            $save->freelanceraiffiliate = $dummy->freelanceraiffiliate;
            $save->commissionratecode = $dummy->commissionratecode;
            $save->eventformat = $dummy->eventformat;
            $save->vat_type = $dummy->vat_type;
            $save->additional_discount = $dummy->additional_discount;
            $save->SpecialDiscountBath = $dummy->SpecialDiscountBath;
            $save->issue_date = $dummy->issue_date;
            $save->Date_type = $dummy->Date_type;
            $save->Expirationdate = $dummy->Expirationdate;
            $save->Document_issuer = $dummy->Document_issuer;
            $save->type_Proposal = $dummy->type_Proposal;
            $save->Operated_by = $dummy->Operated_by;
            $save->Confirm_by = $dummy->Confirm_by;
            $save->Approve_at = $dummy->Approve_at;
            $save->save();

            $document_dummy = document_dummy_quotation::where('Quotation_ID', $dummyID)->get();
            foreach ($document_dummy as $document) {
                $saveProduct = new document_quotation();
                $saveProduct->Quotation_ID = $Quotation_ID;
                $saveProduct->Company_ID = $document->Company_ID;
                $saveProduct->Product_ID = $document->Product_ID;
                $saveProduct->Issue_date = $document->Issue_date;
                $saveProduct->pax = $document->pax;
                $saveProduct->discount =$document->discount;
                $saveProduct->priceproduct =$document->priceproduct;
                $saveProduct->netpriceproduct =$document->netpriceproduct;
                $saveProduct->totaldiscount =$document->totaldiscount;
                $saveProduct->ExpirationDate = $document->ExpirationDate;
                $saveProduct->freelanceraiffiliate = $document->freelanceraiffiliate;
                $saveProduct->Quantity = $document->Quantity;
                $saveProduct->Unit = $document->Unit;
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
            $Quotation = Quotation::where('Quotation_ID', $Quotation_ID)->first();
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
                    }else{
                        $fullName = $comtype->name_th . $Compannyname;
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
                'SpecialDistext'=>$SpecialDistext,
            ];
            $view= $template->name;
            $pdf = FacadePdf::loadView('quotationpdf.'.$view,$data);
            $path = 'Log_PDF/proposal/';
            $pdf->save($path . $Quotation_ID . '.pdf');
            $ProposalData = Quotation::where('Quotation_ID',$Quotation_ID)->first();
            $ProposalID = $ProposalData->Quotation_ID;
            $ProposalIDS = $ProposalData->id;
            $ProposalProducts = document_quotation::where('Quotation_ID',$ProposalID)->get();
            $dataArray = $ProposalData->toArray();
            $datarequest = [
                'Proposal_ID' => $dataArray['Quotation_ID'] ?? null,
                'IssueDate' => $dataArray['issue_date'] ?? null,
                'Expiration' => $dataArray['Expirationdate'] ?? null,
                'Selectdata' => $dataArray['type_Proposal'] ?? null,
                'Data_ID' => $dataArray['Company_ID'] ?? null,
                'Adult' => $dataArray['adult'] ?? null,
                'Children' => $dataArray['children'] ?? null,
                'Mevent' => $dataArray['eventformat'] ?? null,
                'Mvat' => $dataArray['vat_type'] ?? null,
                'comment' => $dataArray['comment'] ?? null,
                'PaxToTalall' => $dataArray['TotalPax'] ?? null,
                'FreelancerMember' => $dataArray['freelanceraiffiliate'] ?? null,
                'Checkin' => $dataArray['checkin'] ?? null,
                'Checkout' => $dataArray['checkout'] ?? null,
                'Day' => $dataArray['day'] ?? null,
                'Night' => $dataArray['night'] ?? null,
            ];
            $productsArray = [];

            $productsArray['Products'] = $ProposalProducts->map(function($item) {
                // ปรับแต่ง $item ที่ได้จากแต่ละแถว
                unset($item['id'], $item['created_at'], $item['updated_at'], $item['SpecialDiscount']);
                return $item;
            })->toArray();

            if ($productsArray) {
                $productData = [];

                foreach ($productsArray['Products'] as $product) {
                    $productID = $product['Product_ID']; // ต้องใช้ $product เพื่อดึงข้อมูล

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
                            'netpriceproduct' => $product['netpriceproduct'],
                            'Product_Name' => $ProductName,
                            'Product_Quantity' => $unitName,
                            'Product_Unit' => $quantity_name,// หรือระบุฟิลด์ที่ต้องการจาก $productDetails
                        ];
                    }
                }
            }
            $Quotation_ID = $datarequest['Proposal_ID'];
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
            $Head = 'รายการ';
            $formattedProductData = [];

            foreach ($productData as $product) {
                $formattedPrice = number_format($product['netpriceproduct']).' '.'บาท';
                $formattedProductData[] = 'Description : ' . $product['Product_Name'] . ' , ' . 'Quantity : ' . $product['Quantity'] . ' ' . $product['Product_Quantity'] . ' , '. 'Unit : '. $product['Unit']. ' ' . $product['Product_Unit'] . ' , ' . 'Price Product : ' . $formattedPrice;
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
                    }else{
                        $fullName = $comtype->name_th . $Compannyname;
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

            $datacompany = '';

            $variables = [$QuotationID, $Issue_Date, $Expiration_Date, $fullName, $Contact_Name,$Time,$nameevent,$namevat,$Head];

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
            $save = new log_company();
            $save->Created_by = $userid;
            $save->Company_ID = $Quotation_ID;
            $save->type = 'Create';
            $save->Category = 'Create :: Proposal';
            $save->content =$datacompany;
            $save->save();
            return redirect()->route('Proposal.viewproposal', ['id' => $ProposalIDS])->with('success', 'บันทึกข้อมูลเรียบร้อยแล้ว');
        } catch (\Throwable $e) {
            return redirect()->route('DummyQuotation.index')->with('error', $e->getMessage());
        }
    }

    //---------------------------------table-----------------
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
                            $btn_status = '<span class="badge rounded-pill bg-success">Approved</span>';
                        } elseif ($value->status_document == 4) {
                            $btn_status = '<span class="badge rounded-pill " style="background-color:#1d4ed8">Reject</span>';
                        } elseif ($value->status_document == 5) {
                            $btn_status = '<span class="badge rounded-pill " style="background-color: #0ea5e9">Generate</span>';
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
                                if ($value->status_document !== 2 && $value->status_document !== 5) {
                                    if ($value->status_document == 3) {
                                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Generate(' . $value->id . ')">Generate</a></li>';
                                    }else{
                                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/edit/quotation/' . $value->id) . '">Edit</a></li>';
                                    }
                                    if ($value->status_document !== 0) {
                                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel(' . $value->id . ')">Cancel</a></li>';
                                    }else {
                                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Revice(' . $value->id . ')">Revice</a></li>';
                                    }
                                }
                            }
                        } elseif ($rolePermission == 2) {
                            if ($canViewProposal) {
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/view/' . $value->id) . '">View</a></li>';
                            }
                            if ($isOperatedByCreator) {
                                if ($canViewProposal) {
                                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/view/quotation/LOG/' . $value->id) . '">LOG</a></li>';
                                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" target="_blank" href="' . url('/Proposal/Quotation/cover/document/PDF/' . $value->id) . '">Export</a></li>';
                                }
                                if ($canEditProposal) {
                                    if ($value->status_document !== 2 && $value->status_document !== 5) {
                                        if ($value->status_document == 3) {
                                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Generate(' . $value->id . ')">Generate</a></li>';
                                        }else{
                                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/edit/quotation/' . $value->id) . '">Edit</a></li>';
                                        }
                                        if ($value->status_document !== 0) {
                                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel(' . $value->id . ')">Cancel</a></li>';
                                        }else {
                                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Revice(' . $value->id . ')">Revice</a></li>';
                                        }
                                    }
                                }
                            }
                        } elseif ($rolePermission == 3) {
                            if ($canViewProposal) {
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/view/' . $value->id) . '">View</a></li>';
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/view/quotation/LOG/' . $value->id) . '">LOG</a></li>';
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" target="_blank" href="' . url('/Dummy/Proposal/Quotation/cover/document/PDF/' . $value->id) . '">Export</a></li>';
                            }
                            if ($canEditProposal) {
                                if ($value->status_document !== 2 && $value->status_document !== 5) {
                                    if ($value->status_document == 3 ) {
                                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Generate(' . $value->id . ')">Generate</a></li>';
                                    }else{
                                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/edit/quotation/' . $value->id) . '">Edit</a></li>';
                                    }
                                    if ($value->status_document !== 0) {
                                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel(' . $value->id . ')">Cancel</a></li>';
                                    }else {
                                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Revice(' . $value->id . ')">Revice</a></li>';
                                    }
                                }
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
                        'Type'=>$value->Date_type ?? '-',
                        'CheckIn' => $value->checkin ?? '-',
                        'CheckOut'=> $value->checkout ?? '-',
                        'ExpirationDate' => $value->Expirationdate,
                        'DiscountP' => $value->additional_discount == 0 ? '-' : '<i class="bi bi-check-lg text-green"></i>',
                        'Operated' => $value->userOperated == null ? '-' : @$value->userOperated->name,
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
                            $btn_status = '<span class="badge rounded-pill bg-success">Approved</span>';
                        } elseif ($value->status_document == 4) {
                            $btn_status = '<span class="badge rounded-pill " style="background-color:#1d4ed8">Reject</span>';
                        } elseif ($value->status_document == 5) {
                            $btn_status = '<span class="badge rounded-pill " style="background-color: #0ea5e9">Generate</span>';
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
                                if ($value->status_document !== 2 && $value->status_document !== 5) {
                                    if ($value->status_document == 3) {
                                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Generate(' . $value->id . ')">Generate</a></li>';
                                    }else{
                                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/edit/quotation/' . $value->id) . '">Edit</a></li>';
                                    }
                                    if ($value->status_document !== 0) {
                                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel(' . $value->id . ')">Cancel</a></li>';
                                    }else {
                                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Revice(' . $value->id . ')">Revice</a></li>';
                                    }
                                }
                            }
                        } elseif ($rolePermission == 2) {
                            if ($canViewProposal) {
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/view/' . $value->id) . '">View</a></li>';
                            }
                            if ($isOperatedByCreator) {
                                if ($canViewProposal) {
                                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/view/quotation/LOG/' . $value->id) . '">LOG</a></li>';
                                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" target="_blank" href="' . url('/Proposal/Quotation/cover/document/PDF/' . $value->id) . '">Export</a></li>';
                                }
                                if ($canEditProposal) {
                                    if ($value->status_document !== 2 && $value->status_document !== 5) {
                                        if ($value->status_document == 3) {
                                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Generate(' . $value->id . ')">Generate</a></li>';
                                        }else{
                                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/edit/quotation/' . $value->id) . '">Edit</a></li>';
                                        }
                                        if ($value->status_document !== 0) {
                                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel(' . $value->id . ')">Cancel</a></li>';
                                        }else {
                                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Revice(' . $value->id . ')">Revice</a></li>';
                                        }
                                    }
                                }
                            }
                        } elseif ($rolePermission == 3) {
                            if ($canViewProposal) {
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/view/' . $value->id) . '">View</a></li>';
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/view/quotation/LOG/' . $value->id) . '">LOG</a></li>';
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" target="_blank" href="' . url('/Dummy/Proposal/Quotation/cover/document/PDF/' . $value->id) . '">Export</a></li>';
                            }
                            if ($canEditProposal) {
                                if ($value->status_document !== 2 && $value->status_document !== 5) {
                                    if ($value->status_document == 3 ) {
                                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Generate(' . $value->id . ')">Generate</a></li>';
                                    }else{
                                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/edit/quotation/' . $value->id) . '">Edit</a></li>';
                                    }
                                    if ($value->status_document !== 0) {
                                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel(' . $value->id . ')">Cancel</a></li>';
                                    }else {
                                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Revice(' . $value->id . ')">Revice</a></li>';
                                    }
                                }
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
                        'CheckIn' => $value->checkin ?? '-',
                        'CheckOut'=> $value->checkout ?? '-',
                        'Type'=>$value->Date_type,
                        'DiscountP' => $value->additional_discount == 0 ? '-' : '<i class="bi bi-check-lg text-green"></i>',
                        'Operated' => $value->userOperated == null ? '-' : @$value->userOperated->name,
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
     //------------------tablepending----------------------
    public function  paginate_pending_table_proposal(Request $request)
    {
        $perPage = (int)$request->perPage;
        $userid = Auth::user()->id;
        $data = [];
        $permissionid = Auth::user()->permission;

        if ($perPage == 10) {
            $data_query = dummy_quotation::query()->where('status_document',1)->limit($request->page.'0')
            ->get();
        } else {
            $data_query = dummy_quotation::query()->where('status_document',1)->paginate($perPage);
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
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/edit/quotation/' . $value->id) . '">Edit</a></li>';
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
                                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/edit/quotation/' . $value->id) . '">Edit</a></li>';
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
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/edit/quotation/' . $value->id) . '">Edit</a></li>';
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
                        'CheckIn' => $value->checkin ?? '-',
                        'CheckOut'=> $value->checkout ?? '-',
                        'Type'=>$value->Date_type,
                        'DiscountP' => $value->additional_discount == 0 ? '-' : '<i class="bi bi-check-lg text-green"></i>',
                        'Operated' => $value->userOperated == null ? '-' : @$value->userOperated->name,
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
    public function search_table_paginate_pending(Request $request)
    {
        $perPage = (int)$request->perPage;
        $search_value = $request->search_value;
        $guest_profile = $request->guest_profile;
        $userid = Auth::user()->id;
        $permissionid = Auth::user()->permission;
        if ($search_value) {
            $data_query = dummy_quotation::where('status_document',1)
            ->where('Quotation_ID', 'LIKE', '%'.$search_value.'%')
            ->orWhere('checkin', 'LIKE', '%'.$search_value.'%')
            ->orWhere('checkout', 'LIKE', '%'.$search_value.'%')
            ->orWhere('issue_date', 'LIKE', '%'.$search_value.'%')
            ->orWhere('Expirationdate', 'LIKE', '%'.$search_value.'%')
            ->where('Company_ID',$guest_profile)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
        }else{
            $perPageS = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
            $data_query = dummy_quotation::query()->where('status_document',1)->paginate($perPageS);
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
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/edit/quotation/' . $value->id) . '">Edit</a></li>';
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
                                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/edit/quotation/' . $value->id) . '">Edit</a></li>';
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
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/edit/quotation/' . $value->id) . '">Edit</a></li>';
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
                        'CheckIn' => $value->checkin ?? '-',
                        'CheckOut'=> $value->checkout ?? '-',
                        'Type'=>$value->Date_type,
                        'DiscountP' => $value->additional_discount == 0 ? '-' : '<i class="bi bi-check-lg text-green"></i>',
                        'Operated' => $value->userOperated == null ? '-' : @$value->userOperated->name,
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
    //----------------------tableAwaiting-----------------
    public function  paginate_awaiting_table_proposal(Request $request)
    {
        $perPage = (int)$request->perPage;
        $userid = Auth::user()->id;
        $data = [];
        $permissionid = Auth::user()->permission;
        if ($perPage == 10) {
            $data_query = dummy_quotation::query()->where('status_document',2)->limit($request->page.'0')
            ->get();
        } else {
            $data_query = dummy_quotation::query()->where('status_document',2)->paginate($perPage);
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

                    if ($rolePermission == 1 || $rolePermission == 2 || $rolePermission == 3) {
                        if ($canViewProposal == 1) {
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Quotation/view/' . $value->id) . '">View</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" target="_bank" href="' . url('/Quotation/Quotation/cover/document/PDF/' . $value->id) . '">Export</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Quotation/view/quotation/LOG/' . $value->id) . '">LOG</a></li>';
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
                        'CheckIn' => $value->checkin ?? '-',
                        'CheckOut'=> $value->checkout ?? '-',
                        'Type'=>$value->Date_type,
                        'DiscountP' => $value->additional_discount == 0 ? '-' : '<i class="bi bi-check-lg text-green"></i>',
                        'Operated' => $value->userOperated == null ? '-' : @$value->userOperated->name,
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
    public function search_table_paginate_awaiting(Request $request)
    {
        $perPage = (int)$request->perPage;
        $search_value = $request->search_value;
        $guest_profile = $request->guest_profile;
        $userid = Auth::user()->id;
        $permissionid = Auth::user()->permission;

        if ($search_value) {
            $data_query = dummy_quotation::where('status_document',2)
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
            $data_query =  dummy_quotation::query()->orderBy('created_at', 'desc')->where('status_document',2)->paginate($perPageS);
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
                        $btn_status = '<span class="badge rounded-pill bg-success" >Approved</span>';
                    } elseif ($value->status_document == 4) {
                        $btn_status = '<span class="badge rounded-pill " style="background-color:#1d4ed8">Reject</span>';
                    } elseif ($value->status_document == 5) {
                        $btn_status = '<span class="badge rounded-pill " style="background-color: #FF6633">Generate</span>';
                    }
                }
                $canViewProposal = Auth::user()->roleMenuView('Proposal', Auth::user()->id);
                $rolePermission = Auth::user()->rolePermissionData(Auth::user()->id);
                $btn_action = '<div class="dropdown">';
                $btn_action .= '<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>';
                $btn_action .= '<ul class="dropdown-menu border-0 shadow p-3">';

                if ($rolePermission == 1 || $rolePermission == 2 || $rolePermission == 3) {
                    if ($canViewProposal == 1) {
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Quotation/view/' . $value->id) . '">View</a></li>';
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" target="_bank" href="' . url('/Quotation/Quotation/cover/document/PDF/' . $value->id) . '">Export</a></li>';
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Quotation/view/quotation/LOG/' . $value->id) . '">LOG</a></li>';
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
                    'CheckIn' => $value->checkin,
                    'CheckOut'=> $value->checkout,
                    'Type'=>$value->Date_type,
                    'DiscountP' => $value->additional_discount == 0 ? '-' : '<i class="bi bi-check-lg text-green"></i>',
                    'Operated' => $value->userOperated == null ? '-' : @$value->userOperated->name,
                    'Approve' => $value->Confirm_by == null ? '-' : @$value->userConfirm->name,
                    'DocumentStatus' => $btn_status,
                    'btn_action' => $btn_action,
                ];
            }
        }
        return response()->json([
            'data' => $data,
        ]);
    }
    //----------------------tableApp-----------------
    public function  paginate_approved_table_proposal(Request $request)
    {
        $perPage = (int)$request->perPage;
        $userid = Auth::user()->id;
        $data = [];
        $permissionid = Auth::user()->permission;

        if ($perPage == 10) {
            $data_query =  dummy_quotation::query()->orderBy('created_at', 'desc')->where('status_document',3)->limit($request->page.'0')
            ->get();
        } else {
            $data_query =  dummy_quotation::query()->orderBy('created_at', 'desc')->where('status_document',3)->paginate($perPage);
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
                            $btn_status = '<span class="badge rounded-pill bg-success" >Approved</span>';
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
                    $btn_action = '<div class="btn-group">';
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
                                if ($value->status_document == 3) {
                                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Generate(' . $value->id . ')">Generate</a></li>';
                                }
                                if ($value->status_document !== 0) {
                                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel(' . $value->id . ')">Cancel</a></li>';
                                }else {
                                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel(' . $value->id . ')">Revice</a></li>';
                                }
                            }
                        } elseif ($rolePermission == 2) {
                            if ($canViewProposal) {
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/view/' . $value->id) . '">View</a></li>';
                            }
                            if ($isOperatedByCreator) {
                                if ($canViewProposal) {
                                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/view/quotation/LOG/' . $value->id) . '">LOG</a></li>';
                                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" target="_blank" href="' . url('/Proposal/Quotation/cover/document/PDF/' . $value->id) . '">Export</a></li>';
                                }
                                if ($canEditProposal) {
                                    if ($value->status_document == 3) {
                                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Generate(' . $value->id . ')">Generate</a></li>';
                                    }
                                    if ($value->status_document !== 0) {
                                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel(' . $value->id . ')">Cancel</a></li>';
                                    }else {
                                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel(' . $value->id . ')">Revice</a></li>';
                                    }
                                }
                            }
                        } elseif ($rolePermission == 3) {
                            if ($canViewProposal) {
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/view/' . $value->id) . '">View</a></li>';
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/view/quotation/LOG/' . $value->id) . '">LOG</a></li>';
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" target="_blank" href="' . url('/Dummy/Proposal/Quotation/cover/document/PDF/' . $value->id) . '">Export</a></li>';
                            }
                            if ($canEditProposal) {
                                if ($value->status_document == 3 ) {
                                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Generate(' . $value->id . ')">Generate</a></li>';
                                }
                                if ($value->status_document !== 0) {
                                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel(' . $value->id . ')">Cancel</a></li>';
                                }else {
                                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel(' . $value->id . ')">Revice</a></li>';
                                }
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
                        'CheckIn' => $value->checkin ?? '-',
                        'CheckOut'=> $value->checkout ?? '-',
                        'Type'=>$value->Date_type,
                        'DiscountP' => $value->additional_discount == 0 ? '-' : '<i class="bi bi-check-lg text-green"></i>',
                        'Operated' => $value->userOperated == null ? '-' : @$value->userOperated->name,
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
    public function search_table_paginate_approved(Request $request)
    {
        $perPage = (int)$request->perPage;
        $search_value = $request->search_value;
        $guest_profile = $request->guest_profile;
        $userid = Auth::user()->id;
        $permissionid = Auth::user()->permission;
        if ($search_value) {
            $data_query = dummy_quotation::where('status_document',3)
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
            $data_query =  dummy_quotation::query()->orderBy('created_at', 'desc')->where('status_document',3)->paginate($perPageS);
        }


        $data = [];
        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $btn_action = "";
                $btn_status = "";
                $name = "";
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
                        $btn_status = '<span class="badge rounded-pill bg-success" >Approved</span>';
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

                $btn_action = '<div class="btn-group">';
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
                            if ($value->status_document == 3) {
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Generate(' . $value->id . ')">Generate</a></li>';
                            }
                            if ($value->status_document !== 0) {
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel(' . $value->id . ')">Cancel</a></li>';
                            }else {
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel(' . $value->id . ')">Revice</a></li>';
                            }
                        }
                    } elseif ($rolePermission == 2) {
                        if ($canViewProposal) {
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/view/' . $value->id) . '">View</a></li>';
                        }
                        if ($isOperatedByCreator) {
                            if ($canViewProposal) {
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/view/quotation/LOG/' . $value->id) . '">LOG</a></li>';
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" target="_blank" href="' . url('/Proposal/Quotation/cover/document/PDF/' . $value->id) . '">Export</a></li>';
                            }
                            if ($canEditProposal) {
                                if ($value->status_document == 3) {
                                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Generate(' . $value->id . ')">Generate</a></li>';
                                }
                                if ($value->status_document !== 0) {
                                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel(' . $value->id . ')">Cancel</a></li>';
                                }else {
                                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel(' . $value->id . ')">Revice</a></li>';
                                }
                            }
                        }
                    } elseif ($rolePermission == 3) {
                        if ($canViewProposal) {
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/view/' . $value->id) . '">View</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/view/quotation/LOG/' . $value->id) . '">LOG</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" target="_blank" href="' . url('/Dummy/Proposal/Quotation/cover/document/PDF/' . $value->id) . '">Export</a></li>';
                        }
                        if ($canEditProposal) {
                            if ($value->status_document == 3 ) {
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Generate(' . $value->id . ')">Generate</a></li>';
                            }
                            if ($value->status_document !== 0) {
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel(' . $value->id . ')">Cancel</a></li>';
                            }else {
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel(' . $value->id . ')">Revice</a></li>';
                            }
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
                    'CheckIn' => $value->checkin,
                    'CheckOut'=> $value->checkout,
                    'Type'=>$value->Date_type,
                    'DiscountP' => $value->additional_discount == 0 ? '-' : '<i class="bi bi-check-lg text-green"></i>',
                    'Operated' => $value->userOperated == null ? '-' : @$value->userOperated->name,
                    'Approve' => $value->Confirm_by == null ? '-' : @$value->userConfirm->name,
                    'DocumentStatus' => $btn_status,
                    'btn_action' => $btn_action,
                ];
            }
        }
        return response()->json([
            'data' => $data,
        ]);
    }
    //----------------------tablegenerate-----------------
    public function  paginate_generate_table_proposal(Request $request)
    {
        $perPage = (int)$request->perPage;
        $userid = Auth::user()->id;
        $data = [];
        $permissionid = Auth::user()->permission;
        if ($perPage == 10) {
            $data_query = dummy_quotation::query()->where('status_document',5)->limit($request->page.'0')
            ->get();
        } else {
            $data_query = dummy_quotation::query()->where('status_document',5)->paginate($perPage);
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
                            $btn_status = '<span class="badge rounded-pill " style="background-color: #0ea5e9">Generate</span>';
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

                    if ($rolePermission == 1 || $rolePermission == 2 || $rolePermission == 3) {
                        if ($canViewProposal == 1) {
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Quotation/view/' . $value->id) . '">View</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" target="_bank" href="' . url('/Quotation/Quotation/cover/document/PDF/' . $value->id) . '">Export</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Quotation/view/quotation/LOG/' . $value->id) . '">LOG</a></li>';
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
                        'CheckIn' => $value->checkin ?? '-',
                        'CheckOut'=> $value->checkout ?? '-',
                        'Type'=>$value->Date_type,
                        'DiscountP' => $value->SpecialDiscount == 0 ? '-' : '<i class="bi bi-check-lg text-green"></i>',
                        'Operated' => $value->userOperated == null ? '-' : @$value->userOperated->name,
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
    public function search_table_paginate_generate(Request $request)
    {
        $perPage = (int)$request->perPage;
        $search_value = $request->search_value;
        $guest_profile = $request->guest_profile;
        $userid = Auth::user()->id;
        $permissionid = Auth::user()->permission;

        if ($search_value) {
            $data_query = dummy_quotation::where('status_document',5)
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
            $data_query =  dummy_quotation::query()->orderBy('created_at', 'desc')->where('status_document',5)->paginate($perPageS);
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
                        $btn_status = '<span class="badge rounded-pill bg-success" >Approved</span>';
                    } elseif ($value->status_document == 4) {
                        $btn_status = '<span class="badge rounded-pill " style="background-color:#1d4ed8">Reject</span>';
                    } elseif ($value->status_document == 5) {
                        $btn_status = '<span class="badge rounded-pill " style="background-color: #0ea5e9">Generate</span>';
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

                if ($rolePermission == 1 || $rolePermission == 2 || $rolePermission == 3) {
                    if ($canViewProposal == 1) {
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Quotation/view/' . $value->id) . '">View</a></li>';
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" target="_bank" href="' . url('/Quotation/Quotation/cover/document/PDF/' . $value->id) . '">Export</a></li>';
                        $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Quotation/view/quotation/LOG/' . $value->id) . '">LOG</a></li>';
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
                    'CheckIn' => $value->checkin,
                    'CheckOut'=> $value->checkout,
                    'Type'=>$value->Date_type,
                    'DiscountP' => $value->additional_discount == 0 ? '-' : '<i class="bi bi-check-lg text-green"></i>',
                    'Operated' => $value->userOperated == null ? '-' : @$value->userOperated->name,
                    'Approve' => $value->Confirm_by == null ? '-' : @$value->userConfirm->name,
                    'DocumentStatus' => $btn_status,
                    'btn_action' => $btn_action,
                ];
            }
        }
        return response()->json([
            'data' => $data,
        ]);
    }
    //----------------------tablereject-----------------
    public function  paginate_reject_table_proposal(Request $request)
    {
        $perPage = (int)$request->perPage;
        $userid = Auth::user()->id;
        $data = [];
        $permissionid = Auth::user()->permission;
        if ($perPage == 10) {
            $data_query =  dummy_quotation::query()->orderBy('created_at', 'desc')->where('status_document',4)->limit($request->page.'0')
            ->get();
        } else {
            $data_query =  dummy_quotation::query()->orderBy('created_at', 'desc')->where('status_document',4)->paginate($perPage);
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
                            $btn_status = '<span class="badge rounded-pill bg-success" >Approved</span>';
                        } elseif ($value->status_document == 4) {
                            $btn_status = '<span class="badge rounded-pill " style="background-color:#1d4ed8">Reject</span>';
                        } elseif ($value->status_document == 5) {
                            $btn_status = '<span class="badge rounded-pill " style="background-color: #0ea5e9">Generate</span>';
                        }
                    }
                    $rolePermission = Auth::user()->rolePermissionData(Auth::user()->id);
                    $canViewProposal = Auth::user()->roleMenuView('Proposal', Auth::user()->id);
                    $canEditProposal = Auth::user()->roleMenuEdit('Proposal', Auth::user()->id);
                    $CreateBy = Auth::user()->id;
                    $isOperatedByCreator = $value->Operated_by == $CreateBy;
                    $btn_action = '<div class="btn-group">';
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
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/edit/quotation/' . $value->id) . '">Edit</a></li>';
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
                                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/edit/quotation/' . $value->id) . '">Edit</a></li>';
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
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/edit/quotation/' . $value->id) . '">Edit</a></li>';
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
                        'CheckIn' => $value->checkin ?? '-',
                        'CheckOut'=> $value->checkout ?? '-',
                        'Type'=>$value->Date_type,
                        'DiscountP' => $value->additional_discount == 0 ? '-' : '<i class="bi bi-check-lg text-green"></i>',
                        'Operated' => $value->userOperated == null ? '-' : @$value->userOperated->name,
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
    public function search_table_paginate_reject(Request $request)
    {
        $perPage = (int)$request->perPage;
        $search_value = $request->search_value;
        $guest_profile = $request->guest_profile;
        $userid = Auth::user()->id;
        $permissionid = Auth::user()->permission;

        if ($search_value) {
            $data_query = dummy_quotation::where('status_document',4)
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
            $data_query =  dummy_quotation::query()->orderBy('created_at', 'desc')->where('status_document',4)->paginate($perPageS);
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
                        $btn_status = '<span class="badge rounded-pill bg-success" >Approved</span>';
                    } elseif ($value->status_document == 4) {
                        $btn_status = '<span class="badge rounded-pill " style="background-color:#1d4ed8">Reject</span>';
                    } elseif ($value->status_document == 5) {
                        $btn_status = '<span class="badge rounded-pill " style="background-color: #0ea5e9">Generate</span>';
                    }
                }
                $rolePermission = Auth::user()->rolePermissionData(Auth::user()->id);
                $canViewProposal = Auth::user()->roleMenuView('Proposal', Auth::user()->id);
                $canEditProposal = Auth::user()->roleMenuEdit('Proposal', Auth::user()->id);
                $CreateBy = Auth::user()->id;
                $isOperatedByCreator = $value->Operated_by == $CreateBy;

                $btn_action = '<div class="btn-group">';
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
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/edit/quotation/' . $value->id) . '">Edit</a></li>';
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
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/edit/quotation/' . $value->id) . '">Edit</a></li>';
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
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/edit/quotation/' . $value->id) . '">Edit</a></li>';
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
                    'CheckIn' => $value->checkin,
                    'CheckOut'=> $value->checkout,
                    'Type'=>$value->Date_type,
                    'DiscountP' => $value->additional_discount == 0 ? '-' : '<i class="bi bi-check-lg text-green"></i>',
                    'Operated' => $value->userOperated == null ? '-' : @$value->userOperated->name,
                    'Approve' => $value->Confirm_by == null ? '-' : @$value->userConfirm->name,
                    'DocumentStatus' => $btn_status,
                    'btn_action' => $btn_action,
                ];
            }
        }
        return response()->json([
            'data' => $data,
        ]);
    }
    public function  paginate_cancel_table_proposal(Request $request)
    {
        $perPage = (int)$request->perPage;
        $userid = Auth::user()->id;
        $data = [];
        $permissionid = Auth::user()->permission;

            if ($perPage == 10) {
                $data_query =  dummy_quotation::query()->orderBy('created_at', 'desc')->where('status_document',0)->limit($request->page.'0')
                ->get();
            } else {
                $data_query =  dummy_quotation::query()->orderBy('created_at', 'desc')->where('status_document',0)->paginate($perPage);
            }


        $page_1 = $request->page == 1 ? 1 : ($request->page - 1).'1';
        $page_2 = $request->page.'0';

        $perPage2 = $request->perPage > 10 ? $request->perPage : 10;

        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $btn_action = "";
                $btn_status = "";
                $name ="";
                $checkbox ="";
                // สร้าง dropdown สำหรับการทำรายการ
                if (($key + 1) >= (int)$page_1 && ($key + 1) <= (int)$page_2 || (int)$perPage > 10 && $key < (int)$perPage2) {

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
                            $btn_status = '<span class="badge rounded-pill bg-success" >Approved</span>';
                        } elseif ($value->status_document == 4) {
                            $btn_status = '<span class="badge rounded-pill " style="background-color:#1d4ed8">Reject</span>';
                        } elseif ($value->status_document == 5) {
                            $btn_status = '<span class="badge rounded-pill " style="background-color: #0ea5e9">Generate</span>';
                        }
                    }
                    $rolePermission = Auth::user()->rolePermissionData(Auth::user()->id);
                    $canViewProposal = Auth::user()->roleMenuView('Proposal', Auth::user()->id);
                    $canEditProposal = Auth::user()->roleMenuEdit('Proposal', Auth::user()->id);
                    $CreateBy = Auth::user()->id;
                    $isOperatedByCreator = $value->Operated_by == $CreateBy;

                    $btn_action = '<div class="btn-group">';
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
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/edit/quotation/' . $value->id) . '">Edit</a></li>';
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Revice(' . $value->id . ')">Revice</a></li>';
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
                                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/edit/quotation/' . $value->id) . '">Edit</a></li>';
                                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Revice(' . $value->id . ')">Revice</a></li>';
                                }
                            }
                        } elseif ($rolePermission == 3) {
                            if ($canViewProposal) {
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/view/' . $value->id) . '">View</a></li>';
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/view/quotation/LOG/' . $value->id) . '">LOG</a></li>';
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" target="_blank" href="' . url('/Dummy/Proposal/Quotation/cover/document/PDF/' . $value->id) . '">Export</a></li>';
                            }
                            if ($canEditProposal) {
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/edit/quotation/' . $value->id) . '">Edit</a></li>';
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Revice(' . $value->id . ')">Revice</a></li>';
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
                        'CheckIn' => $value->checkin ?? '-',
                        'CheckOut'=> $value->checkout ?? '-',
                        'Type'=>$value->Date_type,
                        'DiscountP' => $value->additional_discount == 0 ? '-' : '<i class="bi bi-check-lg text-green"></i>',
                        'Operated' => $value->userOperated == null ? '-' : @$value->userOperated->name,
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
    public function search_table_paginate_cancel(Request $request)
    {
        $perPage = (int)$request->perPage;
        $search_value = $request->search_value;
        $guest_profile = $request->guest_profile;
        $userid = Auth::user()->id;
        $permissionid = Auth::user()->permission;

            if ($search_value) {
                $data_query = dummy_quotation::where('status_document',0)
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
                $data_query =  dummy_quotation::query()->orderBy('created_at', 'desc')->where('status_document',0)->paginate($perPageS);
            }


        $data = [];
        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $btn_action = "";
                $btn_status = "";
                $name = "";
                $checkbox ="";
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
                        $btn_status = '<span class="badge rounded-pill bg-success" >Approved</span>';
                    } elseif ($value->status_document == 4) {
                        $btn_status = '<span class="badge rounded-pill " style="background-color:#1d4ed8">Reject</span>';
                    } elseif ($value->status_document == 5) {
                        $btn_status = '<span class="badge rounded-pill " style="background-color: #0ea5e9">Generate</span>';
                    }
                }
                $rolePermission = Auth::user()->rolePermissionData(Auth::user()->id);
                $canViewProposal = Auth::user()->roleMenuView('Proposal', Auth::user()->id);
                $canEditProposal = Auth::user()->roleMenuEdit('Proposal', Auth::user()->id);
                $CreateBy = Auth::user()->id;
                $isOperatedByCreator = $value->Operated_by == $CreateBy;

                $btn_action = '<div class="btn-group">';
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
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/edit/quotation/' . $value->id) . '">Edit</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Revice(' . $value->id . ')">Revice</a></li>';
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
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/edit/quotation/' . $value->id) . '">Edit</a></li>';
                                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Revice(' . $value->id . ')">Revice</a></li>';
                            }
                        }
                    } elseif ($rolePermission == 3) {
                        if ($canViewProposal) {
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/view/' . $value->id) . '">View</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/view/quotation/LOG/' . $value->id) . '">LOG</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" target="_blank" href="' . url('/Dummy/Proposal/Quotation/cover/document/PDF/' . $value->id) . '">Export</a></li>';
                        }
                        if ($canEditProposal) {
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Dummy/Proposal/edit/quotation/' . $value->id) . '">Edit</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Revice(' . $value->id . ')">Revice</a></li>';
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
                    'CheckIn' => $value->checkin,
                    'CheckOut'=> $value->checkout,
                    'Type'=>$value->Date_type,
                    'DiscountP' => $value->additional_discount == 0 ? '-' : '<i class="bi bi-check-lg text-green"></i>',
                    'Operated' => $value->userOperated == null ? '-' : @$value->userOperated->name,
                    'Approve' => $value->Confirm_by == null ? '-' : @$value->userConfirm->name,
                    'DocumentStatus' => $btn_status,
                    'btn_action' => $btn_action,
                ];
            }
        }
        return response()->json([
            'data' => $data,
        ]);
    }
     //----------------------------log---------------------------------
    public function LOG($id)
    {
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $Quotation = dummy_quotation::where('id', $id)->first();
        $QuotationID = $Quotation->DummyNo;
        $logproposal = log_company::where('Company_ID', $QuotationID)
            ->orderBy('updated_at', 'desc')
            ->paginate($perPage);
        return view('dummy_quotation.document',compact('logproposal','QuotationID'));
    }
    public function search_table_paginate_log_doc_dummyproposal (Request $request)
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
    public function  paginate_log_doc_table_dummyproposal (Request $request)
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
    public function sheetpdf(Request $request ,$id) {
        $Quotation = dummy_quotation::where('id', $id)->first();
        $Quotation_ID = $Quotation->DummyNo;
        $selectproduct = document_dummy_quotation::where('Quotation_ID', $Quotation_ID)->get();
        $datarequest = [
            'Proposal_ID' => $Quotation['DummyNo'] ?? null,
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
                }else{
                    $fullName = $comtype->name_th . $Compannyname;
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
        return $pdf->stream();
    }
    //-------------------------------------------------------------
    public function Cancel($id){
        $dummy = dummy_quotation::where('id', $id)->first();
        $DummyNo =$dummy->DummyNo;
        $dummystatus =$dummy->status_document;
        $dummy = dummy_quotation::find($id);
        if($dummystatus == 0){
            $dummy->status_document = 1;
            $userid = Auth::user()->id;
            $save = new log_company();
            $save->Created_by = $userid;
            $save->Company_ID = $DummyNo;
            $save->type = 'Revice';
            $save->Category = 'Revice Cancel :: Dummy Proposal';
            $save->content = 'Revice Cancel Document Dummy Proposal ID : '.$DummyNo;
            $save->save();
        }else{
            $dummy->status_document = 0;
            $userid = Auth::user()->id;
            $save = new log_company();
            $save->Created_by = $userid;
            $save->Company_ID = $DummyNo;
            $save->type = 'Cancel';
            $save->Category = 'Cancel :: Dummy Proposal';
            $save->content = 'Cancel Document Dummy Proposal ID : '.$DummyNo;
            $save->save();
        }
        $dummy->save();

        return redirect()->route('DummyQuotation.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }
    public function Revice($id){
        $Quotation = dummy_quotation::find($id);
        $Quotation->status_document = 1;
        $Quotation->save();
        $data = dummy_quotation::where('id',$id)->first();
        $Quotation_ID = $data->DummyNo;
        $userid = Auth::user()->id;
        $save = new log_company();
        $save->Created_by = $userid;
        $save->Company_ID = $Quotation_ID;
        $save->type = 'Revice';
        $save->Category = 'Revice Reject :: Dummy Proposal';
        $save->content = 'Revice Reject Document Dummy Proposal ID : '.$Quotation_ID;
        $save->save();
        return redirect()->route('DummyQuotation.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }
    public function addProduct($Quotation_ID) {
        $value = $Quotation_ID;
        if ($value == 'Room_Type') {

            $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->Leftjoin('master_quantities','master_product_items.quantity','master_quantities.id')->orderBy('master_product_items.Product_ID', 'asc')
            ->where('master_product_items.status',1)->where('master_product_items.Category','Room_Type')->select('master_product_items.*','master_units.name_th as unit_name','master_quantities.name_th as quantity_name')->get();

        }elseif ($value == 'Banquet') {
            $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->Leftjoin('master_quantities','master_product_items.quantity','master_quantities.id')->orderBy('master_product_items.Product_ID', 'asc')
            ->where('master_product_items.status',1)->where('master_product_items.Category','Banquet')->select('master_product_items.*','master_units.name_th as unit_name','master_quantities.name_th as quantity_name')->get();

        }elseif ($value == 'Meals') {
            $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->Leftjoin('master_quantities','master_product_items.quantity','master_quantities.id')->orderBy('master_product_items.Product_ID', 'asc')
            ->where('master_product_items.status',1)->where('master_product_items.Category','Meals')->select('master_product_items.*','master_units.name_th as unit_name','master_quantities.name_th as quantity_name')->get();

        }elseif ($value == 'Entertainment') {
            $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->Leftjoin('master_quantities','master_product_items.quantity','master_quantities.id')->orderBy('master_product_items.Product_ID', 'asc')
            ->where('master_product_items.status',1)->where('master_product_items.Category','Entertainment')->select('master_product_items.*','master_units.name_th as unit_name','master_quantities.name_th as quantity_name')->get();

        }
        elseif ($value == 'all'){
            $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->Leftjoin('master_quantities','master_product_items.quantity','master_quantities.id')->orderBy('master_product_items.type', 'asc')->orderBy('master_product_items.id', 'asc')
            ->where('master_product_items.status',1)->select('master_product_items.*','master_units.name_th as unit_name','master_quantities.name_th as quantity_name')->get();
        }
        return response()->json([
            'products' => $products,

        ]);
    }
    public function addProducttable($Quotation_ID) {

        $value = $Quotation_ID;
        if ($value == 'Room_Type') {

            $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->Leftjoin('master_quantities','master_product_items.quantity','master_quantities.id')->orderBy('master_product_items.Product_ID', 'asc')
            ->where('master_product_items.status',1)->where('master_product_items.Category','Room_Type')->select('master_product_items.*','master_units.name_th as unit_name','master_quantities.name_th as quantity_name')->get();

        }elseif ($value == 'Banquet') {
            $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->Leftjoin('master_quantities','master_product_items.quantity','master_quantities.id')->orderBy('master_product_items.Product_ID', 'asc')
            ->where('master_product_items.status',1)->where('master_product_items.Category','Banquet')->select('master_product_items.*','master_units.name_th as unit_name','master_quantities.name_th as quantity_name')->get();

        }elseif ($value == 'Meals') {
            $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->Leftjoin('master_quantities','master_product_items.quantity','master_quantities.id')->orderBy('master_product_items.Product_ID', 'asc')
            ->where('master_product_items.status',1)->where('master_product_items.Category','Meals')->select('master_product_items.*','master_units.name_th as unit_name','master_quantities.name_th as quantity_name')->get();

        }elseif ($value == 'Entertainment') {
            $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->Leftjoin('master_quantities','master_product_items.quantity','master_quantities.id')->orderBy('master_product_items.Product_ID', 'asc')
            ->where('master_product_items.status',1)->where('master_product_items.Category','Entertainment')->select('master_product_items.*','master_units.name_th as unit_name','master_quantities.name_th as quantity_name')->get();

        }
        elseif ($value == 'all'){
            $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->Leftjoin('master_quantities','master_product_items.quantity','master_quantities.id')->orderBy('master_product_items.type', 'asc')->orderBy('master_product_items.id', 'asc')
            ->where('master_product_items.status',1)->select('master_product_items.*','master_units.name_th as unit_name','master_quantities.name_th as quantity_name')->get();

        }
        return response()->json([
            'products' => $products,

        ]);
    }
    public function addProductselect($Quotation_ID) {
        $value = $Quotation_ID;
        $products = master_product_item::leftJoin('master_units', 'master_product_items.unit', '=', 'master_units.id')
        ->Leftjoin('master_quantities','master_product_items.quantity','master_quantities.id')
        ->orderBy('master_product_items.type', 'asc')->orderBy('master_product_items.id', 'asc')
        ->where('master_product_items.status', 1)
        ->where('master_product_items.id', $value)
        ->select('master_product_items.*', 'master_units.name_th as unit_name','master_quantities.name_th as quantity_name')
        ->get();

        return response()->json([
            'products' => $products,

        ]);
    }
    public function addProducttableselect($Quotation_ID) {
        $value = $Quotation_ID;
        $products = master_product_item::leftJoin('master_units', 'master_product_items.unit', '=', 'master_units.id')
        ->Leftjoin('master_quantities','master_product_items.quantity','master_quantities.id')
        ->orderBy('master_product_items.type', 'asc')->orderBy('master_product_items.id', 'asc')
        ->where('master_product_items.status', 1)
        ->where('master_product_items.id', $value)
        ->select('master_product_items.*', 'master_units.name_th as unit_name','master_quantities.name_th as quantity_name')
        ->get();

        return response()->json([
            'products' => $products,

        ]);
    }
    public function addProducttablemain($Quotation_ID) {
        $value = $Quotation_ID;
        $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->Leftjoin('master_quantities','master_product_items.quantity','master_quantities.id')->orderBy('master_product_items.type', 'asc')->orderBy('master_product_items.id', 'asc')
        ->where('master_product_items.status',1)->select('master_product_items.*','master_units.name_th as unit_name','master_quantities.name_th as quantity_name')->get();
        return response()->json([
            'products' => $products,

        ]);
    }
    public function addProducttablecreatemain($Quotation_ID) {
        $value = $Quotation_ID;
        $products = master_product_item::Leftjoin('master_units','master_product_items.unit','master_units.id')->Leftjoin('master_quantities','master_product_items.quantity','master_quantities.id')->orderBy('master_product_items.type', 'asc')->orderBy('master_product_items.id', 'asc')
        ->where('master_product_items.status',1)->select('master_product_items.*','master_units.name_th as unit_name','master_quantities.name_th as quantity_name')->get();
        return response()->json([
            'products' => $products,

        ]);
    }
}
