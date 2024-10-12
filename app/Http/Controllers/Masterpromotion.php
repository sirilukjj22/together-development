<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\master_promotion;
use App\Models\Quotation;
use Carbon\Carbon;
use App\Models\Guest;
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
use App\Models\document_quotation;
use App\Models\log;
use App\Models\Master_company;
use App\Models\phone_guest;
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
class Masterpromotion extends Controller
{
    public function index($menu)
    {
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $promotion = master_promotion::query()->paginate($perPage);
        $path = 'promotion/';
        $exp = explode('.', $menu);
        if (count($exp) > 1) {
            $search = $exp[1];
            if ($search == "all") {
                $promotion = master_promotion::query()->paginate($perPage);
            }elseif ($search == 'ac') {
                $promotion = master_promotion::query()->where('status',1)->paginate($perPage);
            }else {
                $promotion = master_promotion::query()->where('status',0)->paginate($perPage);
            }
        }
        return view('master_promotion.index',compact('promotion','path','menu'));
    }
    public function save(Request $request) {

        $type = $request->Filter;
        $link = $request->Link;
        $files = $request->file('file');
        $image = $request->image;
        try {
            if ($type == 'Link') {
                $request->validate([
                    'image' => 'required|mimes:png,jpg,pdf|max:10240', // ขนาดสูงสุด 1000 MB
                ]);
                $originalName = $image->getClientOriginalName();
                $newName = $originalName;
                $path = 'promotion/';
                $image->move(public_path($path), $newName);
                $save = new master_promotion();
                $save->name = $link;
                $save->type = $type;
                $save->image = $newName;
                $save->save();
            }else{
                $request->validate([
                    'file.*' => 'required|mimes:png,jpg,pdf|max:10240', // ขนาดสูงสุด 1000 MB
                ]);
                foreach ($files as $file) {
                    $originalName = $file->getClientOriginalName();
                    $newName = $originalName;
                    $path = 'promotion/';
                    $file->move(public_path($path), $newName);
                    $save = new master_promotion();
                    $save->name = $newName;
                    $save->image = $newName;
                    $save->type = $type;
                    $save->save();
                }
            }
        } catch (\Throwable $e) {
            return redirect()->route('Mpromotion','index')->with('error', $e->getMessage());
        }
        try {
                {
                    //log
                    $typelog = 'ประเภท : '.$type;

                    $linklog=null;
                    if ($link) {
                        $linklog = 'Link : '.$link;
                    }

                    $filelog=[];
                    if ($files) {
                        foreach ($files as $file) {
                            $originalName = $file->getClientOriginalName();
                            $newName = $originalName;
                            $filelog[] = 'File : '.$newName;
                        }
                    }
                    $datacompany = '';

                    $variables = [$typelog, $linklog];
                    $filelogs = implode(' + ', $filelog);

                    // รวม $formattedProductDataString เข้าไปใน $variables
                    $variables[] = $filelogs;
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
                    $save->Company_ID = 'Master Promotion';
                    $save->type = 'Create';
                    $save->Category = 'Create :: Master Promotion';
                    $save->content =$datacompany;
                    $save->save();
                }
        } catch (\Throwable $e) {
            return redirect()->route('Mpromotion','index')->with('error', $e->getMessage());
        }
        return redirect()->route('Mpromotion','index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }
    public function delete($id)
    {
        $promotion =master_promotion::where('id',$id)->first();
        $name = $promotion->name;
        $datacompany = 'ชื่อ : '.$name;
        $product = master_promotion::find($id);
        $product->delete();
        $userid = Auth::user()->id;
        $save = new log_company();
        $save->Created_by = $userid;
        $save->Company_ID = 'Master Promotion';
        $save->type = 'Delete';
        $save->Category = 'Delete :: Master Promotion';
        $save->content =$datacompany;
        $save->save();
        return redirect()->route('Mpromotion','index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }
    public function search_table(Request $request){
        $perPage = (int)$request->perPage;
        $search_value = $request->search_value;
        $guest_profile = $request->guest_profile;
        $userid = Auth::user()->id;
        $permissionid = Auth::user()->permission;
        if ($search_value) {
            $data_query = master_promotion::where('name', 'LIKE', '%'.$search_value.'%')
            ->paginate($perPage);
        }else{
            $perPageS = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
            $data_query = master_promotion::query()->orderBy('created_at', 'desc')->paginate($perPageS);
        }
        $data = [];
        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $btn_action = "";
                $btn_status = "";
                $name ="";
                $view ="";
                $issueDate = Carbon::parse($value->updated_at); // แปลงเป็น Carbon
                $daysPassed = $issueDate->diffInDays(now());
                // สร้าง dropdown สำหรับการทำรายการ
                if ($value->status == 1) {
                    $btn_status = '<button type="button" class="btn btn-light-success btn-sm" value="'.$value->id.'" onclick="btnstatus('.$value->id.')">ใช้งาน</button>';
                } else {
                    $btn_status = '<button type="button" class="btn btn-light-danger btn-sm" value="'.$value->id.'" onclick="btnstatus('.$value->id.')">ปิดใช้งาน</button>';
                }

                $path = 'promotion/';
                $btn_action = '<div class="dropdown">';
                $btn_action .= '<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>';
                $btn_action .= '<ul class="dropdown-menu border-0 shadow p-3">';
                if ($value->type == 'Link') {
                    $btn_action .= '<li><a href="' . asset($value->name ) . '" type="button" class="dropdown-item py-2 rounded" target="_blank" data-toggle="tooltip" data-placement="top">View</a></li>';
                } else {
                    $btn_action .= '<li><a href="' . asset($path . $value->name ) . '" type="button" class="dropdown-item py-2 rounded" target="_blank" data-toggle="tooltip" data-placement="top" title="พิมพ์เอกสาร">View</a></li>';
                }

                $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Delete(' . $value->id . ')">Delete</a></li>';
                $btn_action .= '</ul>';
                $btn_action .= '</div>';

                $data[] = [
                    'number' => ($key + 1) ,
                    'image'=> '<img src="'.asset($path . $value->image).'" alt="Together Resort Logo" class="logo" id="logoImage" />',
                    'name' => $value->name,
                    'status' => $btn_status,
                    'btn_action' => $btn_action,
                ];
            }
        }
        return response()->json([
            'data' => $data,
        ]);
    }
    public function paginate_table(Request $request){
        $perPage = (int)$request->perPage;
        $userid = Auth::user()->id;
        $data = [];
        $permissionid = Auth::user()->permission;
        if ($perPage == 10) {
            $data_query = master_promotion::query()->limit($request->page.'0')
            ->get();
        } else {
            $data_query = master_promotion::query()->paginate($perPage);
        }


        $page_1 = $request->page == 1 ? 1 : ($request->page - 1).'1';
        $page_2 = $request->page.'0';

        $perPage2 = $request->perPage > 10 ? $request->perPage : 10;

        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $btn_action = "";
                $btn_status = "";
                $name ="";
                $issueDate = Carbon::parse($value->updated_at); // แปลงเป็น Carbon
                $daysPassed = $issueDate->diffInDays(now());
                // สร้าง dropdown สำหรับการทำรายการ
                if (($key + 1) >= (int)$page_1 && ($key + 1) <= (int)$page_2 || (int)$perPage > 10 && $key < (int)$perPage2) {
                    if ($value->status == 1) {
                        $btn_status = '<button type="button" class="btn btn-light-success btn-sm" value="'.$value->id.'" onclick="btnstatus('.$value->id.')">ใช้งาน</button>';
                    } else {
                        $btn_status = '<button type="button" class="btn btn-light-danger btn-sm" value="'.$value->id.'" onclick="btnstatus('.$value->id.')">ปิดใช้งาน</button>';
                    }

                    $path = 'promotion/';
                    $btn_action = '<div class="dropdown">';
                    $btn_action .= '<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>';
                    $btn_action .= '<ul class="dropdown-menu border-0 shadow p-3">';

                    if ($value->type == 'Link') {
                        $btn_action .= '<li><a href="' . asset($value->name ) . '" type="button" class="dropdown-item py-2 rounded" target="_blank" data-toggle="tooltip" data-placement="top">View</a></li>';
                    } else {
                        $btn_action .= '<li><a href="' . asset($path . $value->name ) . '" type="button" class="dropdown-item py-2 rounded" target="_blank" data-toggle="tooltip" data-placement="top" title="พิมพ์เอกสาร">View</a></li>';
                    }
                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Delete(' . $value->id . ')">Delete</a></li>';
                    $btn_action .= '</ul>';
                    $btn_action .= '</div>';

                    $data[] = [
                        'number' => ($key + 1) ,
                        'image'=> '<img src="'.asset($path . $value->image).'" alt="Together Resort Logo" class="logo" id="logoImage"/>',
                        'name' => $value->name,
                        'status' => $btn_status,
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

    public function log(){
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $log = log_company::where('Company_ID', 'Master Promotion')
        ->orderBy('updated_at', 'desc')
        ->paginate($perPage);
        return view('master_promotion.log',compact('log'));
    }
    public function search_table_paginate_log(Request $request){
        $perPage = (int)$request->perPage;
        $search_value = $request->search_value;
        $guest_profile = $request->guest_profile;

        if ($search_value) {
            $data_query = log_company::where('created_at', 'LIKE', '%'.$search_value.'%')
                ->where('Company_ID','Master Promotion')
                ->orderBy('updated_at', 'desc')
                ->paginate($perPage);
        }else{
            $perPageS = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
            $data_query = log_company::where('Company_ID', 'Master Promotion')->orderBy('updated_at', 'desc')->paginate($perPageS);
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
    public function paginate_log_table(Request $request){
        $perPage = (int)$request->perPage;
        $guest_profile = $request->guest_profile;


        if ($perPage == 10) {
            $data_query = log_company::where('Company_ID', 'Master Promotion')->orderBy('updated_at', 'desc')->limit($request->page.'0')->get();
        } else {
            $data_query = log_company::where('Company_ID', 'Master Promotion')->orderBy('updated_at', 'desc')->paginate($perPage);
        }
        $page_1 = $request->page == 1 ? 1 : ($request->page - 1).'1';
        $page_2 = $request->page.'0';

        $perPage2 = $request->perPage > 10 ? $request->perPage : 10;
        $data = [];
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
        return response()->json([
            'data' => $data,
        ]);

    }


}
