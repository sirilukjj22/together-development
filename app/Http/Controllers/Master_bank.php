<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\master_document;
use Auth;
use App\Models\User;
class Master_bank extends Controller
{
    public function index()
    {
        $Mbank = master_document::query()->Where('Category','Mbank')->get();
        return view('master_bank.index',compact('Mbank'));
    }
    public function ac(Request $request)
    {
        $ac = $request->value;
        if ($ac == 1 ) {
            $query = master_document::query();
            $Mbank = $query->where('status', '1')->Where('Category','Mbank')->get();
        }
        return view('master_bank.index',compact('Mbank'));
    }
    public function no(Request $request)
    {
        $no = $request->value;
        if ($no == 0 ) {
            $query = master_document::query();
            $Mbank = $query->where('status', '0')->Where('Category','Mbank')->get();
        }
        return view('master_bank.index',compact('Mbank'));
    }

    public function changeStatus($id,$status)
    {
        $Mbank = master_document::find($id);
        if ($status == 1 ) {
            $status = 0;
            $Mbank->status = $status;
        }elseif (($status == 0 )) {
            $status = 1;
            $Mbank->status = $status;
        }
        $Mbank->save();
    }
    public function save(Request $request)
    {
        $userid = Auth::user()->id;
        $Mbank =  "Mbank" ;
        $code = $request->code;
        $swiftcode = $request->swiftcode;
        $name_th = $request->name_th;
        $name_en = $request->name_en;

        $save = new master_document();
        $save->code = $code;
        $save->swiftcode = $swiftcode;
        $save->name_th = $name_th;
        $save->name_en = $name_en;
        $save->Category= $Mbank;
        $save->created_by = $userid;
        $save->save();
        if ($save->save()) {
            return redirect()->route('Mbank.index')->with('alert_', 'บันทึกข้อมูลเรียบร้อย');
        } else {
            return redirect()->back()->with('error_', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
    }

    public function update($id,$datakey,$dataEN,$code,$swiftcode)
    {
        $code = $code;
        $swiftcode = $swiftcode;
        $name_th = $datakey;
        $name_en = $dataEN;
        $userid = Auth::user()->id;
        $save = master_document::find($id);
        $save->code = $code;
        $save->swiftcode = $swiftcode;
        $save->name_th = $name_th;
        $save->name_en = $name_en;
        $save->created_by = $userid;
        $save->save();
        if ($save->save()) {
            return redirect()->route('Mbank.index')->with('alert_', 'บันทึกข้อมูลเรียบร้อย');
        } else {
            return redirect()->back()->with('error_', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
    }

    public function edit($id)
    {
        $data = master_document::find($id);
        return response()->json(['data' => $data]);
    }
    public function  searchMbank($id,$datakey)
    {
        $data = master_document::where('id',$id)->where('name_th',$datakey)->Where('Category','Mbank')->first();
        return response()->json($data);
    }
    public function  dupicateMbank($id,$datakey)
    {
        $data = master_document::where('id',$id)->where('name_th',$datakey)->Where('Category','Mbank')->first();
        return response()->json(['data' => $data]);
    }
}
