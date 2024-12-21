<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\master_document;
use App\Models\log_company;
use Carbon\Carbon;
use Auth;
use App\Models\User;
class Master_prefix extends Controller
{
    public function index($menu)
    {
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $prefix = master_document::query()->Where('Category','Mprename')->get();
        $exp = explode('.', $menu);
        if (count($exp) > 1) {
            $search = $exp[1];
            if ($search == "all") {
                $prefix = master_document::query()
                ->Where('Category','Mprename')
                ->get();
            }elseif ($search == 'ac') {
                $prefix = master_document::query()
                ->Where('Category','Mprename')
                ->where('status', 1)
                ->get();
            }else {
                $prefix = master_document::query()
                ->Where('Category','Mprename')
                ->where('status', 0)
                ->get();
            }
        }
        return view('master_prefix.index',compact('prefix','menu'));
    }
    public function changeStatus($id)
    {
        $M_prefix = master_document::find($id);
        if ($M_prefix->status == 1 ) {
            $status = 0;
            $M_prefix->status = $status;
        }elseif (($M_prefix->status == 0 )) {
            $status = 1;
            $M_prefix->status = $status;
        }
        $M_prefix->save();
    }

    public function save(Request $request)
    {
        try {
            $data = $request->all();
            $userid = Auth::user()->id;
            $Mprefix =  "Mprename" ;
            $save = new master_document();
            $save->Category= $Mprefix;
            $save->name_th = $request->name_th;
            $save->name_en = $request->name_en;
            $save->created_by = $userid;
            $save->save();
        } catch (\Throwable  $e) {
            return redirect()->route('Mprefix','index')->with('error', $e->getMessage());
        }
        try {
            //log
            $nameth = 'ชื่อภาษาไทย : '.$request->name_th;
            $nameen = 'ชื่อภาษาอังกฤษ : '.$request->name_en;
            $datacompany = '';
            $variables = [$nameth, $nameen];
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
            $save->Company_ID = 'Master Prename';
            $save->type = 'Create';
            $save->Category = 'Create :: Master Prename';
            $save->content =$datacompany;
            $save->save();

        } catch (\Throwable  $e) {
            return redirect()->route('Mprefix','index')->with('error', $e->getMessage());
        }
        return redirect()->route('Mprefix','index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }
    public function update($id,$datakey,$dataEN)
    {
        try {
            $userid = Auth::user()->id;
            $save = master_document::find($id);
            $save->name_th = $datakey;
            $save->name_en = $dataEN;
            $save->created_by = $userid;
            $save->save();
        } catch (\Throwable  $e) {
            return redirect()->route('Mprefix','index')->with('error', $e->getMessage());
        }
        try {
            $nameth = null;
            if ($datakey) {
                $nameth = 'ชื่อภาษาไทย : '.$datakey;
            }
            $nameen = null;
            if ($datakey) {
                $nameen = 'ชื่อภาษาอังกฤษ : '.$dataEN;
            }
            $datacompany = '';
            $variables = [$nameth, $nameen];
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
            $save->Company_ID = 'Master Prename';
            $save->type = 'Edit';
            $save->Category = 'Edit :: Master Prename';
            $save->content =$datacompany;
            $save->save();

        } catch (\Throwable  $e) {
            return redirect()->route('Mprefix','index')->with('error', $e->getMessage());
        }
        return redirect()->route('Mprefix','index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }
    public function edit($id)
    {
        $data = master_document::find($id);
        return response()->json(['data' => $data]);
    }
    public function  searchMprename($datakey)
    {
        $data = master_document::where('name_th',$datakey)->first();
        return response()->json($data);
    }
    public function  dupicateMprename($id,$datakey)
    {
        $data = master_document::where('id',$id)->where('name_th',$datakey)->Where('Category','Mprename')->first();
        return response()->json(['data' => $data]);
    }

    public function log(){
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $log = log_company::where('Company_ID', 'Master Prename')
        ->orderBy('updated_at', 'desc')
        ->get();
        return view('master_prefix.log',compact('log'));
    }
}
