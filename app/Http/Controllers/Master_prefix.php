<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\master_document;
use Auth;
use App\Models\User;
class Master_prefix extends Controller
{
    public function index()
    {
        $prefix = master_document::query()->Where('Category','Mprename')->get();
        return view('master_prefix.index',compact('prefix'));
    }
    public function ac(Request $request)
    {
        $ac = $request->value;
        if ($ac == 1 ) {
            $query = master_document::query();
            $prefix = $query->where('status', '1')->Where('Category','Mprename')->get();
        }
        return view('master_prefix.index',compact('prefix'));
    }
    public function no(Request $request)
    {
        $no = $request->value;
        if ($no == 0 ) {
            $query = master_document::query();
            $prefix = $query->where('status', '0')->Where('Category','Mprename')->get();
        }
        return view('master_prefix.index',compact('prefix'));
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
        $data = $request->all();
        $Mprefix =  "Mprename" ;
        $name_th = $request->name_th;
        $name_en = $request->name_en;
        $userid = Auth::user()->id;
        $save = new master_document();
        $save->created_by = $userid;
        $save->Category= $Mprefix;
        $save->name_th = $name_th;
        $save->name_en = $name_en;
        $save->save();
        if ($save->save()) {
            return redirect()->route('Mprefix.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
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
            return redirect()->route('Mprefix.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
        } else {
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }

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
}
