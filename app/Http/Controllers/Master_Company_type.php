<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\master_document;
use App\Models\log_company;
use Carbon\Carbon;
use Auth;
use App\Models\User;
class Master_Company_type extends Controller
{
    public function index($menu)
    {
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $M_Company_type = master_document::query()->Where('Category','Mcompany_type')->get();
        $exp = explode('.', $menu);
        if (count($exp) > 1) {
            $search = $exp[1];
            if ($search == "all") {
                $M_Company_type = master_document::query()
                ->Where('Category','Mcompany_type')
                ->get();
            }elseif ($search == 'ac') {
                $M_Company_type = master_document::query()
                ->Where('Category','Mcompany_type')
                ->where('status', 1)
                ->get();
            }else {
                $M_Company_type = master_document::query()
                ->Where('Category','Mcompany_type')
                ->where('status', 0)
                ->get();
            }
        }
        return view('master_companyt.index',compact('M_Company_type','menu'));
    }
    public function changeStatus($id)
    {
        $Mcompany_type = master_document::find($id);
        if ($Mcompany_type->status == 1 ) {
            $status = 0;
            $Mcompany_type->status = $status;
        }elseif (($Mcompany_type->status == 0 )) {
            $status = 1;
            $Mcompany_type->status = $status;
        }
        $Mcompany_type->save();
    }
    public function save(Request $request)
    {
        try {
            $data = $request->all();
            $userid = Auth::user()->id;
            $Mcompany_type =  "Mcompany_type" ;
            $save = new master_document();
            $save->Category= $Mcompany_type;
            $save->name_th = $request->name_th;
            $save->name_en = $request->name_en;
            $save->created_by = $userid;
            $save->save();
        } catch (\Throwable  $e) {
            return redirect()->route('Mcomt','index')->with('error', $e->getMessage());
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
            $save->Company_ID = 'Master Company Type';
            $save->type = 'Create';
            $save->Category = 'Create :: Master Company Type';
            $save->content =$datacompany;
            $save->save();

        } catch (\Throwable  $e) {
            return redirect()->route('Mcomt','index')->with('error', $e->getMessage());
        }
        return redirect()->route('Mcomt','index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
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
            return redirect()->route('Mcomt','index')->with('error', $e->getMessage());
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
            $save->Company_ID = 'Master Company Type';
            $save->type = 'Edit';
            $save->Category = 'Edit :: Master Company Type';
            $save->content =$datacompany;
            $save->save();

        } catch (\Throwable  $e) {
            return redirect()->route('Mcomt','index')->with('error', $e->getMessage());
        }
        return redirect()->route('Mcomt','index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }

    public function edit($id)
    {
        $data = master_document::find($id);
        return response()->json(['data' => $data]);
    }
    public function  search($datakey)
    {
        $data = master_document::where('name_th',$datakey)->first();
        return response()->json($data);
    }
    public function  dupicate($id,$datakey)
    {
        $data = master_document::where('id',$id)->where('name_th',$datakey)->Where('Category','Mcompany_type')->first();
        return response()->json(['data' => $data]);
    }

    public function log(){
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $log = log_company::where('Company_ID', 'Master Company Type')
        ->orderBy('updated_at', 'desc')
        ->get();
        return view('master_companyt.log',compact('log'));
    }

}
