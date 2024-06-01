<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\master_document;
class Master_prefix extends Controller
{
    public function index()
    {
        $M_prefix = master_document::query()->Where('Category','Mprefix')->get();
        return view('master_prefix.index',compact('M_prefix'));
    }
    public function create()
    {
        return view('master_prefix.create');
    }
    public function ac(Request $request)
    {
        $ac = $request->value;
        if ($ac == 1 ) {
            $query = master_document::query();
            $M_prefix = $query->where('status', '1')->Where('Category','Mprefix')->get();
        }
        return view('master_prefix.index',compact('M_prefix'));
    }
    public function no(Request $request)
    {
        $no = $request->value;
        if ($no == 0 ) {
            $query = master_document::query();
            $M_prefix = $query->where('status', '0')->Where('Category','Mprefix')->get();
        }
        return view('master_prefix.index',compact('M_prefix'));
    }
    public function changeStatus($id,$status)
    {
        $M_prefix = master_document::find($id);
        if ($status == 1 ) {
            $status = 0;
            $M_prefix->status = $status;
        }elseif (($status == 0 )) {
            $status = 1;
            $M_prefix->status = $status;
        }
        $M_prefix->save();
    }
    public function edit($id)
    {
        $M_prefix = master_document::find($id);
        return view('master_prefix.edit',compact('M_prefix'));
    }
    public function save(Request $request)
    {
        $data = $request->all();
        $Mprefix =  "Mprefix" ;
        $name_th = $request->name_th;
        $name_en = $request->name_en;

        $save = new master_document();
        $save->created_by = 4;
        $save->Category= $Mprefix;
        $save->name_th = $name_th;
        $save->name_en = $name_en;
        $save->save();
        if ($save->save()) {
            return redirect()->route('Mprefix.index')->with('alert_', 'บันทึกข้อมูลเรียบร้อย');
        } else {
            return redirect()->back()->with('error_', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
    }
    public function update(Request $request,$id)
    {
        $data = $request->all();
        $name_th = $request->name_th;
        $name_en = $request->name_en;

        $save = master_document::find($id);
        $save->name_th = $name_th;
        $save->name_en = $name_en;
        $save->save();
        if ($save->save()) {
            return redirect()->route('Mprefix.index')->with('alert_', 'บันทึกข้อมูลเรียบร้อย');
        } else {
            return redirect()->back()->with('error_', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
    }
}
