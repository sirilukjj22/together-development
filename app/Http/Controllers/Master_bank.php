<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\master_document;

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
    public function create()
    {
        return view('master_bank.create');
    }
    public function save(Request $request)
    {
        $data = $request->all();
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
        $save->created_by = 2;
        $save->save();
        if ($save->save()) {
            return redirect()->route('Mbank.index')->with('alert_', 'บันทึกข้อมูลเรียบร้อย');
        } else {
            return redirect()->back()->with('error_', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
    }
    public function edit($id)
    {
        $Mbank = master_document::find($id);
        return view('master_bank.edit',compact('Mbank'));
    }

    public function update(Request $request,$id)
    {
        $data = $request->all();
        $code = $request->code;
        $swiftcode = $request->swiftcode;
        $name_th = $request->name_th;
        $name_en = $request->name_en;

        $save = master_document::find($id);
        $save->code = $code;
        $save->swiftcode = $swiftcode;
        $save->name_th = $name_th;
        $save->name_en = $name_en;
        $save->save();
        if ($save->save()) {
            return redirect()->route('Mbank.index')->with('alert_', 'บันทึกข้อมูลเรียบร้อย');
        } else {
            return redirect()->back()->with('error_', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
    }
}
