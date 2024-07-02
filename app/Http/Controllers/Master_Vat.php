<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\master_document;
use Auth;
use App\Models\User;
class Master_Vat extends Controller
{
    public function index()
    {
        $Mvat = master_document::where('Category','Mvat')->get();
        return view('master_vat.index',compact('Mvat'));
    }
    public function save(Request $request)
    {
        $Mevent =  "Mvat" ;
        $name_th = $request->name_th;
        $name_en = $request->name_en;
        $userid = Auth::user()->id;
        $save = new master_document();
        $save->name_th = $name_th;
        $save->name_en = $name_en;
        $save->created_by = $userid;
        $save->Category= $Mevent;
        $save->save();
        if ($save->save()) {
            return redirect()->route('Mvat.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
        } else {
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
    }

    public function update($id,$datakey,$dataEN)
    {
        $userid = Auth::user()->id;
        $save = master_document::find($id);
        $save->name_th = $datakey;
        $save->name_en = $dataEN;
        $save->created_by = $userid;
        $save->save();
        if ($save->save()) {
            return redirect()->back()->with('success', 'บันทึกข้อมูลเรียบร้อย');
        }else{
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาด');
        }
    }
    public function ac(Request $request)
    {
        $ac = $request->value;
        if ($ac == 1 ) {
            $query = master_document::query();
            $Mvat = $query->where('status', '1')->Where('Category','Mvat')->get();
        }
        return view('master_vat.index',compact('Mvat'));
    }
    public function no(Request $request)
    {
        $no = $request->value;
        if ($no == 0 ) {
            $query = master_document::query();
            $Mvat = $query->where('status', '0')->Where('Category','Mvat')->get();
        }
        return view('master_vat.index',compact('Mvat'));
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

}
