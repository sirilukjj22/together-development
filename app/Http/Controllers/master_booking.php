<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\master_document;

class master_booking extends Controller
{
    public function Mbookingsave(Request $request)
    {
        $data = $request->all();

        $Mbooking_channel =  "Mbooking_channel" ;
        $sort = $request->sort;
        $name_th = $request->name_th;
        $name_en = $request->name_en;
        $save = new master_document();
        $save->sort = $sort;
        $save->code = $request->code;
        $save->status= 1;
        $save->name_th = $name_th;
        $save->name_en = $name_en;
        $save->created_by = 1;
        $save->Category= $Mbooking_channel;
        $save->save();
        if ($save->save()) {
            return redirect()->route('Mbooking.index')->with('alert_', 'บันทึกข้อมูลเรียบร้อย');
        } else {
            return redirect()->back()->with('error_', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
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
    public function users_no(Request $request)
    {
        $users_no = $request->value;
        if ($users_no == 0 ) {
            $query = master_document::query();
            $Mbooking = $query->where('status', '0')->Where('Category','Mbooking_channel')->get();
        }
        return view('master_booking_channal.index',compact('Mbooking'));
    }
    public function Mbooking_update(Request $request) {
        $id = $request->id;
        $Mbooking_channal = master_document::find($id);
        $Mbooking_channal->sort = $request->sort;
        $Mbooking_channal->code = $request->code;
        $Mbooking_channal->status = 1;
        $Mbooking_channal->name_th = $request->name_th;
        $Mbooking_channal->name_en = $request->name_en;
        $Mbooking_channal->created_by = 1;

        if ($Mbooking_channal->save()) {
            return redirect()->route('Mbooking.index')->with('alert_', 'บันทึกข้อมูลเรียบร้อย');
        } else {
            return redirect()->back()->with('error_', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
    }
    public function changeStatus($id,$status)
    {
        $Mbooking_channal = master_document::find($id);
        if ($status == 1 ) {
            $status = 0;
            $Mbooking_channal->status = $status;
        }elseif (($status == 0 )) {
            $status = 1;
            $Mbooking_channal->status = $status;
        }
        $Mbooking_channal->save();
    }
}
