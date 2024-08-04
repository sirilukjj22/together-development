<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\master_document;
use Auth;
use App\Models\User;
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
        $Mmarket =  "Mmarket" ;
        $name_th = $request->name_th;
        $name_en = $request->name_en;
        $userid = Auth::user()->id;
        $save = new master_document();
        $save->code = $request->code;
        $save->name_th = $name_th;
        $save->name_en = $name_en;
        $save->created_by = $userid;
        $save->Category= $Mmarket;
        $save->save();
        if ($save->save()) {
            return redirect()->route('Mmarket.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
        } else {
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }

    }
    public function update(Request $request) {
        try {
            $id = $request->input('id');
            $datakey = $request->input('datakey');
            $dataEN = $request->input('dataEN');
            $code = $request->input('code');

            $userid = Auth::user()->id;
            $Mmarket = master_document::find($id);
            $Mmarket->code = $code;
            $Mmarket->name_th = $datakey;
            $Mmarket->name_en = $dataEN;
            $Mmarket->created_by = $userid;
            $Mmarket->save();
            return redirect()->route('Mmarket.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
    }
    public function changeStatus($id)
    {
        $Mmarket = master_document::find($id);
        if ($Mmarket->status == 1 ) {
            $status = 0;
            $Mmarket->status = $status;
        }elseif (($Mmarket->status == 0 )) {
            $status = 1;
            $Mmarket->status = $status;
        }
        $Mmarket->save();
    }
    public function edit($id)
    {
        $data = master_document::find($id);
        return response()->json(['data' => $data]);
    }
    public function  search($id,$datakey)
    {
        $data = master_document::where('id',$id)->where('name_th',$datakey)->Where('Category','Mmarket')->first();
        return response()->json($data);
    }
    public function  dupicate($id,$datakey)
    {
        $data = master_document::where('id',$id)->where('name_th',$datakey)->Where('Category','Mmarket')->first();
        return response()->json(['data' => $data]);
    }
}
