<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterEventFormate;
class MasterEventFormatController extends Controller
{
    public function index()
    {
        $event = MasterEventFormate::query()->get();
        return view('master_event_format.index',compact('event'));
    }
    public function save(Request $request)
    {
        $data = $request->all();
        $count = MasterEventFormate::count()+1;
        $save = new MasterEventFormate();
        $save->code = $count;
        $save->name_th = $request->name_th;
        $save->name_en = $request->name_en;
        $save->detail_th = $request->detail_th;
        $save->detail_en = $request->detail_en;
        $save->save();
        if ($save->save()) {
            return redirect()->back()->with('success', 'บันทึกข้อมูลเรียบร้อย');
        }else{
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาด');
        }
    }

    public function update(Request $request)
    {
        $data = $request->all();
        $id = $request->id;
        $save = MasterEventFormate::find($id);
        $save->name_th = $request->name_th;
        $save->name_en = $request->name_en;
        $save->detail_th = $request->detail_th;
        $save->detail_en = $request->detail_en;
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
            $query = MasterEventFormate::query();
            $event = $query->where('status', '1')->get();
        }
        return view('master_event_format.index',compact('event'));
    }
    public function no(Request $request)
    {
        $no = $request->value;
        if ($no == 0 ) {
            $query = MasterEventFormate::query();
            $event = $query->where('status', '0')->get();
        }
        return view('master_event_format.index',compact('event'));
    }
    public function changeStatus($id,$status)
    {
        $event = MasterEventFormate::find($id);
        if ($status == 1 ) {
            $status = 0;
            $event->status = $status;
        }elseif (($status == 0 )) {
            $status = 1;
            $event->status = $status;
        }
        $event->save();
    }
}
