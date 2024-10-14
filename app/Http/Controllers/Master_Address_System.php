<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Master_company;
use App\Models\log_company;
use Carbon\Carbon;
use Auth;
use App\Models\User;
class Master_Address_System extends Controller
{
    public function index()
    {
        $address = Master_company::query()->first();
        return view('master_address_system.index',compact('address'));
    }
    public function edit(Request $request ,$id)
    {
        try {
            $userid = Auth::user()->id;
            $save = Master_company::find($id);
            $save->name = $request->name;
            $save->name_th = $request->name_th;
            $save->address = $request->address;
            $save->tel = $request->tel;
            $save->email = $request->email;
            $save->web = $request->web;
            $save->fax = $request->fax;
            $save->Hotal_ID = $request->Hotal_ID;
            $save->created_by = $userid;
            $save->save();
        } catch (\Throwable  $e) {
            return redirect()->route('System.index')->with('error', $e->getMessage());
        }
        try {
            $name = $request->name;
            $name_th = $request->name_th;
            $address = $request->address;
            $tel = $request->tel;
            $email = $request->email;
            $web = $request->web;
            $fax = $request->fax;
            $Hotal_ID = $request->Hotal_ID;
            $nameth = null;
            if ($name) {
                $nameth = 'ชื่อหลักบริษัท : '.$name;
            }
            $nameen = null;
            if ($name_th) {
                $nameen = 'ชื่อเรียกบริษัท : '.$name_th;
            }
            $Address = null;
            if ($address) {
                $Address = 'ที่อยู่บริษัท : '.$address;
            }
            $Tel = null;
            if ($tel) {
                $Tel = 'เบอร์ติดต่อ : '.$tel;
            }
            $Fax = null;
            if ($fax) {
                $Fax = 'แฟกซ์ : '.$fax;
            }
            $Email = null;
            if ($email) {
                $Email = 'อีเมล์ : '.$email;
            }
            $Web = null;
            if ($web) {
                $Web = 'เว็บไซต์ : '.$web;
            }
            $HotalID = null;
            if ($Hotal_ID) {
                $HotalID = 'เลขใบอนุญาติ : '.$Hotal_ID;
            }
            $datacompany = '';
            $variables = [$nameth, $nameen ,$Address,$Tel,$Fax,$Email,$Web,$HotalID];
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
            $save->Company_ID = 'Master System';
            $save->type = 'Edit';
            $save->Category = 'Edit :: Master System';
            $save->content =$datacompany;
            $save->save();

        } catch (\Throwable  $e) {
            return redirect()->route('System.index')->with('error', $e->getMessage());
        }
        return redirect()->route('System.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }

    public function log(){
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $log = log_company::where('Company_ID', 'Master System')
        ->orderBy('updated_at', 'desc')
        ->paginate($perPage);
        return view('master_address_system.log',compact('log'));
    }

    public function Msys_search_table_paginate_log(Request $request){
        $perPage = (int)$request->perPage;
        $search_value = $request->search_value;
        $guest_profile = $request->guest_profile;

        if ($search_value) {
            $data_query = log_company::where('created_at', 'LIKE', '%'.$search_value.'%')
                ->where('Company_ID','Master System')
                ->orderBy('updated_at', 'desc')
                ->paginate($perPage);
        }else{
            $perPageS = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
            $data_query = log_company::where('Company_ID', 'Master System')->orderBy('updated_at', 'desc')->paginate($perPageS);
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
    public function Msys_paginate_log_table(Request $request){
        $perPage = (int)$request->perPage;
        $guest_profile = $request->guest_profile;


        if ($perPage == 10) {
            $data_query = log_company::where('Company_ID', 'Master System')->orderBy('updated_at', 'desc')->limit($request->page.'0')->get();
        } else {
            $data_query = log_company::where('Company_ID', 'Master System')->orderBy('updated_at', 'desc')->paginate($perPage);
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
