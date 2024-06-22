<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\master_document;
use Auth;
use App\Models\User;
class Master_Company_type extends Controller
{
    public function index()
    {
        $M_Company_type = master_document::query()->Where('Category','Mcompany_type')->get();
        return view('master_companyt.index',compact('M_Company_type'));
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
        $data = $request->all();
        $Mcompany_type =  "Mcompany_type" ;
        $name_th = $request->name_th;
        $name_en = $request->name_en;
        $userid = Auth::user()->id;
        $save = new master_document();
        $save->created_by = $userid;
        $save->Category= $Mcompany_type;
        $save->name_th = $name_th;
        $save->name_en = $name_en;
        $save->save();
        if ($save->save()) {
            return redirect()->route('Mcomt.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
        } else {
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
    }
    public function update($id,$datakey,$dataEN)
    {
        $name_th = $datakey;
        $name_en = $dataEN;
        $userid = Auth::user()->id;
        $save = master_document::find($id);
        $save->name_th = $name_th;
        $save->name_en = $name_en;
        $save->created_by = $userid;
        $save->save();
        if ($save->save()) {
            return redirect()->route('Mcomt.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
        } else {
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
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
}
