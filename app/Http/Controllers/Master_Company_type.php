<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\master_document;
class Master_Company_type extends Controller
{
    public function index()
    {
        $M_Company_type = master_document::query()->Where('Category','Mcompany_type')->get();
        return view('master_companyt.index',compact('M_Company_type'));
    }
    public function create()
    {
        return view('master_companyt.create');
    }
    public function ac(Request $request)
    {
        $ac = $request->value;
        if ($ac == 1 ) {
            $query = master_document::query();
            $M_Company_type = $query->where('status', '1')->Where('Category','Mcompany_type')->get();
        }
        return view('master_companyt.index',compact('M_Company_type'));
    }
    public function no(Request $request)
    {
        $no = $request->value;
        if ($no == 0 ) {
            $query = master_document::query();
            $M_Company_type = $query->where('status', '0')->Where('Category','Mcompany_type')->get();
        }
        return view('master_companyt.index',compact('M_Company_type'));
    }
    public function changeStatus($id,$status)
    {
        $Mcompany_type = master_document::find($id);
        if ($status == 1 ) {
            $status = 0;
            $Mcompany_type->status = $status;
        }elseif (($status == 0 )) {
            $status = 1;
            $Mcompany_type->status = $status;
        }
        $Mcompany_type->save();
    }
    public function edit($id)
    {
        $M_Company_type = master_document::find($id);
        return view('master_companyt.edit',compact('M_Company_type'));
    }
    public function save(Request $request)
    {
        $data = $request->all();
        $Mcompany_type =  "Mcompany_type" ;
        $name_th = $request->name_th;
        $name_en = $request->name_en;

        $save = new master_document();
        $save->created_by = 3;
        $save->Category= $Mcompany_type;
        $save->name_th = $name_th;
        $save->name_en = $name_en;
        $save->save();
        if ($save->save()) {
            return redirect()->route('Mcomt.index')->with('alert_', 'บันทึกข้อมูลเรียบร้อย');
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
            return redirect()->route('Mcomt.index')->with('alert_', 'บันทึกข้อมูลเรียบร้อย');
        } else {
            return redirect()->back()->with('error_', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
    }
}
