<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\master_document;
use App\Models\log_company;
use Carbon\Carbon;
use Auth;
use App\Models\User;
class master_booking extends Controller
{
    public function index($menu)
    {
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $Mbooking = master_document::query()->Where('Category','Mbooking_channel')->paginate($perPage);
        $exp = explode('.', $menu);
        if (count($exp) > 1) {
            $search = $exp[1];
            if ($search == "all") {
                $Mbooking = master_document::query()
                ->Where('Category','Mbooking_channel')
                ->paginate($perPage);
            }elseif ($search == 'ac') {
                $Mbooking = master_document::query()
                ->Where('Category','Mbooking_channel')
                ->where('status', 1)
                ->paginate($perPage);
            }else {
                $Mbooking = master_document::query()
                ->Where('Category','Mbooking_channel')
                ->where('status', 0)
                ->paginate($perPage);
            }
        }
        return view('master_booking_channal.index',compact('Mbooking','menu'));
    }
    public function Mbookingsave(Request $request)
    {
        try {
            $data = $request->all();
            $userid = Auth::user()->id;
            $Mbooking_channel =  "Mbooking_channel" ;
            $save = new master_document();
            $save->Category= $Mbooking_channel;
            $save->code = $request->code;
            $save->name_th = $request->name_th;
            $save->name_en = $request->name_en;
            $save->created_by = $userid;
            $save->save();
        } catch (\Throwable  $e) {
            return redirect()->route('Mbooking','index')->with('error', $e->getMessage());
        }
        try {
            //log
            $code = 'รหัส : '.$request->code;
            $nameth = 'ชื่อภาษาไทย : '.$request->name_th;
            $nameen = 'ชื่อภาษาอังกฤษ : '.$request->name_en;
            $datacompany = '';
            $variables = [$code,$nameth, $nameen];
            // รวม $formattedProductDataString เข้าไปใน $variables
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
            $save->Company_ID = 'Master Booking';
            $save->type = 'Create';
            $save->Category = 'Create :: Master Booking';
            $save->content =$datacompany;
            $save->save();

        } catch (\Throwable  $e) {
            return redirect()->route('Mbooking','index')->with('error', $e->getMessage());
        }
        return redirect()->route('Mbooking','index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }

    public function update($id,$datakey,$dataEN,$code) {
        try {
            $userid = Auth::user()->id;
            $save = master_document::find($id);
            $save->code = $code;
            $save->name_th = $datakey;
            $save->name_en = $dataEN;
            $save->created_by = $userid;
            $save->save();
        } catch (\Throwable  $e) {
            return redirect()->route('Mbooking','index')->with('error', $e->getMessage());
        }
        try {
            $codeEN = null;
            if ($code) {
                $codeEN = 'รหัส : '.$code;
            }
            $nameth = null;
            if ($datakey) {
                $nameth = 'ชื่อภาษาไทย : '.$datakey;
            }
            $nameen = null;
            if ($datakey) {
                $nameen = 'ชื่อภาษาอังกฤษ : '.$dataEN;
            }
            $datacompany = '';
            $variables = [$codeEN,$nameth, $nameen];
            // รวม $formattedProductDataString เข้าไปใน $variables
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
            $save->Company_ID = 'Master Booking';
            $save->type = 'Edit';
            $save->Category = 'Edit :: Master Booking';
            $save->content =$datacompany;
            $save->save();

        } catch (\Throwable  $e) {
            return redirect()->route('Mbooking','index')->with('error', $e->getMessage());
        }
        return redirect()->route('Mbooking','index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }
    public function changeStatus($id)
    {
        $Mbooking_channal = master_document::find($id);
        if ($Mbooking_channal->status == 1 ) {
            $status = 0;
            $Mbooking_channal->status = $status;
        }elseif (($Mbooking_channal->status == 0 )) {
            $status = 1;
            $Mbooking_channal->status = $status;
        }
        $Mbooking_channal->save();
    }
    public function edit($id)
    {
        $data = master_document::find($id);
        return response()->json(['data' => $data]);
    }
    public function  search($datakey)
    {
        $data = master_document::where('name_th',$datakey)->Where('Category','Mbooking_channel')->first();
        return response()->json($data);
    }
    public function  dupicate($id,$datakey)
    {
        $data = master_document::where('id',$id)->where('name_th',$datakey)->Where('Category','Mbooking_channel')->first();
        return response()->json(['data' => $data]);
    }

    public function log(){
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $log = log_company::where('Company_ID', 'Master Booking')
        ->orderBy('updated_at', 'desc')
        ->paginate($perPage);
        return view('master_booking_channal.log',compact('log'));
    }

    public function book_search_table(Request $request){
        $perPage = (int)$request->perPage;
        $search_value = $request->search_value;
        $guest_profile = $request->guest_profile;
        $userid = Auth::user()->id;
        $permissionid = Auth::user()->permission;
        if ($search_value) {
            $data_query = master_document::where('Category', 'Mbooking_channel')
            ->where(function($query) use ($search_value) {
                $query->orWhere('name_th', 'LIKE', '%'.$search_value.'%')
                    ->orWhere('name_en', 'LIKE', '%'.$search_value.'%')
                    ->orWhere('code', 'LIKE', '%'.$search_value.'%');
            })
            ->paginate($perPage);
        }else{
            $perPageS = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
            $data_query = master_document::query()->where('Category','Mbooking_channel')->paginate($perPageS);
        }
        $data = [];
        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $btn_action = "";
                $btn_status = "";
                $name ="";
                $view ="";
                if ($value->status == 1) {
                    $btn_status = '<button type="button" class="btn btn-light-success btn-sm" value="'.$value->id.'" onclick="btnstatus('.$value->id.')">ใช้งาน</button>';
                } else {
                    $btn_status = '<button type="button" class="btn btn-light-danger btn-sm" value="'.$value->id.'" onclick="btnstatus('.$value->id.')">ปิดใช้งาน</button>';
                }

                $path = 'promotion/';
                $btn_action = '<div class="dropdown">';
                $btn_action .= '<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>';
                $btn_action .= '<ul class="dropdown-menu border-0 shadow p-3">';
                $btn_action .= '<li><a class="dropdown-item py-2 rounded" onclick="view_detail('.$value->id.')" data-bs-toggle="modal" data-bs-target="#MbookingCreate">View</a></li>';
                $btn_action .= '<li><a class="dropdown-item py-2 rounded" onclick="edit('.$value->id.')" data-bs-toggle="modal" data-bs-target="#MbookingCreate">Edit</a></li>';
                $btn_action .= '</ul>';
                $btn_action .= '</div>';

                $data[] = [
                    'number' => ($key + 1) ,
                    'code' => $value->code,
                    'nameth' => $value->name_th,
                    'nameen' => $value->name_en,
                    'status' => $btn_status,
                    'btn_action' => $btn_action,
                ];
            }
        }
        return response()->json([
            'data' => $data,
        ]);
    }
    public function book_paginate_table(Request $request){
        $perPage = (int)$request->perPage;
        $userid = Auth::user()->id;
        $data = [];
        $permissionid = Auth::user()->permission;
        if ($perPage == 10) {
            $data_query = master_document::query()->where('Category','Mbooking_channel')->limit($request->page.'0')
            ->get();
        } else {
            $data_query = master_document::query()->where('Category','Mbooking_channel')->paginate($perPage);
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
                    if ($value->status == 1) {
                        $btn_status = '<button type="button" class="btn btn-light-success btn-sm" value="'.$value->id.'" onclick="btnstatus('.$value->id.')">ใช้งาน</button>';
                    } else {
                        $btn_status = '<button type="button" class="btn btn-light-danger btn-sm" value="'.$value->id.'" onclick="btnstatus('.$value->id.')">ปิดใช้งาน</button>';
                    }

                    $path = 'promotion/';
                    $btn_action = '<div class="dropdown">';
                    $btn_action .= '<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>';
                    $btn_action .= '<ul class="dropdown-menu border-0 shadow p-3">';
                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" onclick="view_detail('.$value->id.')" data-bs-toggle="modal" data-bs-target="#MbookingCreate">View</a></li>';
                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" onclick="edit('.$value->id.')" data-bs-toggle="modal" data-bs-target="#MbookingCreate">Edit</a></li>';
                    $btn_action .= '</ul>';
                    $btn_action .= '</div>';

                    $data[] = [
                        'number' => ($key + 1) ,
                        'code' => $value->code,
                        'nameth' => $value->name_th,
                        'nameen' => $value->name_en,
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

    public function book_search_table_paginate_log(Request $request){
        $perPage = (int)$request->perPage;
        $search_value = $request->search_value;
        $guest_profile = $request->guest_profile;

        if ($search_value) {
            $data_query = log_company::where('created_at', 'LIKE', '%'.$search_value.'%')
                ->where('Company_ID','Master Booking')
                ->orderBy('updated_at', 'desc')
                ->paginate($perPage);
        }else{
            $perPageS = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
            $data_query = log_company::where('Company_ID', 'Master Booking')->orderBy('updated_at', 'desc')->paginate($perPageS);
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
    public function book_paginate_log_table(Request $request){
        $perPage = (int)$request->perPage;
        $guest_profile = $request->guest_profile;


        if ($perPage == 10) {
            $data_query = log_company::where('Company_ID', 'Master Booking')->orderBy('updated_at', 'desc')->limit($request->page.'0')->get();
        } else {
            $data_query = log_company::where('Company_ID', 'Master Booking')->orderBy('updated_at', 'desc')->paginate($perPage);
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
