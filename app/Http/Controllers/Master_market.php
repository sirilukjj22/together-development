<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\master_document;
class Master_market extends Controller
{
    public function index()
    {
        $Mmarket = master_document::query()->Where('Category','Mmarket')->get();
        return view('master_market.index',compact('Mmarket'));
    }
    public function ac(Request $request)
    {
        $ac = $request->value;
        if ($ac == 1 ) {
            $query = master_document::query();
            $Mmarket = $query->where('status', '1')->Where('Category','Mmarket')->get();
        }
        return view('master_market.index',compact('Mmarket'));
    }
    public function no(Request $request)
    {
        $no = $request->value;
        if ($no == 0 ) {
            $query = master_document::query();
            $Mmarket = $query->where('status', '0')->Where('Category','Mmarket')->get();
        }
        return view('master_market.index',compact('Mmarket'));
    }

    public function save(Request $request)
    {
        $data = $request->all();
        $Mmarket =  "Mmarket" ;
        $name_th = $request->name_th;
        $name_en = $request->name_en;
        $save = new master_document();
        $save->code = $request->code;
        $save->status= 1;
        $save->name_th = $name_th;
        $save->name_en = $name_en;
        $save->created_by = 5;
        $save->Category= $Mmarket;
        $save->save();
        if ($save->save()) {
            return redirect()->route('Mmarket.index')->with('alert_', 'บันทึกข้อมูลเรียบร้อย');
        } else {
            return redirect()->back()->with('error_', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }

    }
    public function update(Request $request) {

        $id = $request->id;
        $Mmarket = master_document::find($id);
        $Mmarket->code = $request->code;
        $Mmarket->name_th = $request->name_th;
        $Mmarket->name_en = $request->name_en;

        if ($Mmarket->save()) {
            return redirect()->route('Mmarket.index')->with('alert_', 'บันทึกข้อมูลเรียบร้อย');
        } else {
            return redirect()->back()->with('error_', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
    }
    public function changeStatus($id,$status)
    {
        $Mmarket = master_document::find($id);
        if ($status == 1 ) {
            $status = 0;
            $Mmarket->status = $status;
        }elseif (($status == 0 )) {
            $status = 1;
            $Mmarket->status = $status;
        }
        $Mmarket->save();
    }
}
