<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\master_promotion;
class Masterpromotion extends Controller
{
    public function index()
    {
        $promotion = master_promotion::query()->get();
        $path = 'promotion/';
        return view('master_promotion.index',compact('promotion','path'));
    }
    public function save(Request $request) {
        $request->validate([
            'file.*' => 'required|mimes:png,jpg,pdf|max:10240', // max size is 10240 KB which is 10 MB
        ]);
        $files = $request->file('file');
        foreach ($files as $file) {
            $originalName = $file->getClientOriginalName();
            $newName = $originalName;
            $path = 'promotion/';
            $file->move(public_path($path), $newName);
            $save = new master_promotion();
            $save->name = $newName;
            $save->save();
        }
        return redirect()->route('Mpromotion.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }
    public function ac(Request $request)
    {
        $ac = $request->value;
        if ($ac == 1 ) {
            $query = master_promotion::query();
            $promotion = $query->where('status', '1')->get();
        }
        return view('master_promotion.index',compact('promotion'));
    }
    public function no(Request $request)
    {
        $no = $request->value;
        if ($no == 0 ) {
            $query = master_promotion::query();
            $promotion = $query->where('status', '0')->get();
        }
        return view('master_promotion.index',compact('promotion'));
    }
    public function delete($id)
    {
        $product = master_promotion::find($id);
        $product->delete();
        return redirect()->route('Mpromotion.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }
}
