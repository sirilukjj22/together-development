<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\master_document;
use Auth;
use App\Models\User;
use App\Models\log_company;
use Carbon\Carbon;
class Master_Vat extends Controller
{
    public function index($menu)
    {
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $Mvat = master_document::query()->Where('Category','Mvat')->get();
        $exp = explode('.', $menu);
        if (count($exp) > 1) {
            $search = $exp[1];
            if ($search == "all") {
                $Mvat = master_document::query()
                ->Where('Category','Mvat')
                ->get();
            }elseif ($search == 'ac') {
                $Mvat = master_document::query()
                ->Where('Category','Mvat')
                ->where('status', 1)
                ->get();
            }else {
                $Mvat = master_document::query()
                ->Where('Category','Mvat')
                ->where('status', 0)
                ->get();
            }
        }
        return view('master_vat.index',compact('Mvat','menu'));
    }
    public function save(Request $request)
    {
        try {
            $data = $request->all();
            $userid = Auth::user()->id;
            $Mprefix =  "Mvat" ;
            $save = new master_document();
            $save->Category= $Mprefix;
            $save->name_th = $request->name_th;
            $save->name_en = $request->name_en;
            $save->created_by = $userid;
            $save->save();
        } catch (\Throwable  $e) {
            return redirect()->route('Mvat','index')->with('error', $e->getMessage());
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
            $save->Company_ID = 'Master Vat';
            $save->type = 'Create';
            $save->Category = 'Create :: Master Vat';
            $save->content =$datacompany;
            $save->save();

        } catch (\Throwable  $e) {
            return redirect()->route('Mvat','index')->with('error', $e->getMessage());
        }
        return redirect()->route('Mvat','index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
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
            return redirect()->route('Mvat','index')->with('error', $e->getMessage());
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
            $save->Company_ID = 'Master Vat';
            $save->type = 'Edit';
            $save->Category = 'Edit :: Master Vat';
            $save->content =$datacompany;
            $save->save();

        } catch (\Throwable  $e) {
            return redirect()->route('Mvat','index')->with('error', $e->getMessage());
        }
        return redirect()->route('Mvat','index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }
    public function changeStatus($id)
    {
        $event = master_document::find($id);
        if ($event->status == 1 ) {
            $status = 0;
            $event->status = $status;
        }elseif (($event->status == 0 )) {
            $status = 1;
            $event->status = $status;
        }
        $event->save();
    }
    public function edit($id)
    {
        $data = master_document::find($id);
        return response()->json(['data' => $data]);
    }
    public function  search($datakey)
    {
        $data = master_document::where('name_th',$datakey)->Where('Category','Mvat')->first();
        return response()->json($data);
    }
    public function  dupicate($id,$datakey)
    {
        $data = master_document::where('id',$id)->where('name_th',$datakey)->Where('Category','Mvat')->first();
        return response()->json(['data' => $data]);
    }
    public function log(){
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $log = log_company::where('Company_ID', 'Master Vat')
        ->orderBy('updated_at', 'desc')
        ->get();
        return view('master_vat.log',compact('log'));
    }

}
