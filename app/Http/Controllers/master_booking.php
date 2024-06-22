<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\master_document;
use Auth;
use App\Models\User;
class master_booking extends Controller
{
    public function Mbookingsave(Request $request)
    {

        $Mbooking_channel =  "Mbooking_channel" ;
        $userid = Auth::user()->id;
        $save = new master_document();
        $save->code = $request->code;
        $save->status= 1;
        $save->name_th = $request->name_th;
        $save->name_en = $request->name_en;
        $save->created_by = $userid;
        $save->Category= $Mbooking_channel;
        $save->save();
        if ($save->save()) {
            return redirect()->route('Mbooking.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
        } else {
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }

    }
    public function index()
    {
        $Mbooking = master_document::query()->Where('Category','Mbooking_channel')->get();
        return view('master_booking_channal.index',compact('Mbooking'));
    }
    public function ac(Request $request)
    {
        $ac = $request->value;
        if ($ac == 1 ) {
            $query = master_document::query();
            $Mbooking = $query->where('status', '1')->Where('Category','Mbooking_channel')->get();
        }
        return view('master_booking_channal.index',compact('Mbooking'));
    }
    public function no(Request $request)
    {
        $no = $request->value;
        if ($no == 0 ) {
            $query = master_document::query();
            $Mbooking = $query->where('status', '0')->Where('Category','Mbooking_channel')->get();
        }
        return view('master_booking_channal.index',compact('Mbooking'));
    }
    public function update($id,$datakey,$dataEN,$code) {
        $userid = Auth::user()->id;
        $Mbooking_channal = master_document::find($id);
        $Mbooking_channal->code = $code;
        $Mbooking_channal->name_th = $datakey;
        $Mbooking_channal->name_en = $dataEN;
        $Mbooking_channal->created_by = $userid;

        if ($Mbooking_channal->save()) {
            return redirect()->route('Mbooking.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
        } else {
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
    }
    public function changeStatus($id)
    {
        $Mbooking_channal = master_document::find($id);
        if ($Mbooking_channal->status == 1 ) {
            $status = 0;
            $Mbooking_channal->status = $status;
        }elseif (($Mbooking_channal->status == 0 )) {
            $status = 1;
            $Mbooking_channal->status = $status;
        }
        $Mbooking_channal->save();
    }
    public function edit($id)
    {
        $data = master_document::find($id);
        return response()->json(['data' => $data]);
    }
    public function  search($datakey)
    {
        $data = master_document::where('name_th',$datakey)->Where('Category','Mbooking_channel')->first();
        return response()->json($data);
    }
    public function  dupicate($id,$datakey)
    {
        $data = master_document::where('id',$id)->where('name_th',$datakey)->Where('Category','Mbooking_channel')->first();
        return response()->json(['data' => $data]);
    }
}
