<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\master_document;
use App\Models\log_company;
use Carbon\Carbon;
use Auth;
use App\Models\User;
class Master_market extends Controller
{
    public function index($menu)
    {
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $Mmarket = master_document::query()->Where('Category','Mmarket')->get();
        $exp = explode('.', $menu);
        if (count($exp) > 1) {
            $search = $exp[1];
            if ($search == "all") {
                $Mmarket = master_document::query()
                ->Where('Category','Mmarket')
                ->get();
            }elseif ($search == 'ac') {
                $Mmarket = master_document::query()
                ->Where('Category','Mmarket')
                ->where('status', 1)
                ->get();
            }else {
                $Mmarket = master_document::query()
                ->Where('Category','Mmarket')
                ->where('status', 0)
                ->get();
            }
        }
        return view('master_market.index',compact('Mmarket','menu'));
    }
    public function save(Request $request)
    {
        try {
            $data = $request->all();
            $userid = Auth::user()->id;
            $Mbooking_channel =  "Mmarket" ;
            $save = new master_document();
            $save->Category= $Mbooking_channel;
            $save->code = $request->code;
            $save->name_th = $request->name_th;
            $save->name_en = $request->name_en;
            $save->created_by = $userid;
            $save->save();
        } catch (\Throwable  $e) {
            return redirect()->route('Mmarket','index')->with('error', $e->getMessage());
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
            $save->Company_ID = 'Master Market';
            $save->type = 'Create';
            $save->Category = 'Create :: Master Market';
            $save->content =$datacompany;
            $save->save();

        } catch (\Throwable  $e) {
            return redirect()->route('Mmarket','index')->with('error', $e->getMessage());
        }
        return redirect()->route('Mmarket','index')->with('success', 'บันทึกข้อมูลเรียบร้อย');

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
            return redirect()->route('Mmarket','index')->with('error', $e->getMessage());
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
            $save->Company_ID = 'Master Market';
            $save->type = 'Edit';
            $save->Category = 'Edit :: Master Market';
            $save->content =$datacompany;
            $save->save();

        } catch (\Throwable  $e) {
            return redirect()->route('Mmarket','index')->with('error', $e->getMessage());
        }
        return redirect()->route('Mmarket','index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }
    public function changeStatus($id)
    {
        $Mmarket = master_document::find($id);
        if ($Mmarket->status == 1 ) {
            $status = 0;
            $Mmarket->status = $status;
        }elseif (($Mmarket->status == 0 )) {
            $status = 1;
            $Mmarket->status = $status;
        }
        $Mmarket->save();
    }
    public function edit($id)
    {
        $data = master_document::find($id);
        return response()->json(['data' => $data]);
    }
    public function  search($datakey)
    {
        $data = master_document::where('name_th',$datakey)->Where('Category','Mmarket')->first();
        return response()->json($data);
    }
    public function  dupicate($id,$datakey)
    {
        $data = master_document::where('id',$id)->where('name_th',$datakey)->Where('Category','Mmarket')->first();
        return response()->json(['data' => $data]);
    }

    public function log(){
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $log = log_company::where('Company_ID', 'Master Market')
        ->orderBy('updated_at', 'desc')
        ->get();
        return view('master_market.log',compact('log'));
    }
}
