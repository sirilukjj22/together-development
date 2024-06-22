<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\master_template;
use App\Models\Quotation;
use App\Models\companys;
use App\Models\master_document;
use App\Models\province;
use App\Models\amphures;
use App\Models\districts;
use App\Models\company_fax;
use App\Models\company_phone;
use App\Models\representative;
use App\Models\representative_phone;
use App\Models\MasterEventFormate;
use App\Models\master_document_sheet;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class Master_TemplateController extends Controller
{
    public function TemplateA1()
    {
        $date = Carbon::now();
        $sheet = master_document_sheet::select('topic','name_th','id')->get();
        $Reservation_show = $sheet->where('topic', 'Reservation')->first();
        $Paymentterms = $sheet->where('topic', 'Paymentterms')->first();
        $note = $sheet->where('topic', 'note')->first();
        $Cancellations = $sheet->where('topic', 'Cancellations')->first();
        $Complimentary = $sheet->where('topic', 'Complimentary')->first();
        $All_rights_reserved = $sheet->where('topic', 'All_rights_reserved')->first();
        return view('master_template.templateA1',compact('date','Reservation_show','Paymentterms','note','Cancellations','Complimentary','All_rights_reserved'));
    }
    public function save(Request $request) {
        $data =$request->all();

        $Template = $request->Template;
        $name = "template".$Template;
        $save = new master_template();
        $save->CodeTemplate = $Template;
        $save->name = $name;
        $save->save();
        return redirect()->route('Template.TemplateA1');
    }
    public function savesheet(Request $request) {
        $data =$request->all();
        $Template = $request->Template;
        $data = [
            "Reservation" => $request->input('Reservation'),
            "Paymentterms" => $request->input('Paymentterms'),
            "note" => $request->input('note'),
            "Cancellations" => $request->input('Cancellations'),
            "Complimentary" => $request->input('Complimentary'),
            "All_rights_reserved" => $request->input('All_rights_reserved'),
        ];
        master_document_sheet::where('CodeTemplate', $Template)->delete();
        foreach ($data as $key => $value) {
            DB::table('master_document_sheet')->insert([
                'topic' => $key,
                'CodeTemplate'=>$Template,
                'name_th' => $value,
                'name_en' => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        return redirect()->back()->with('alert_', 'แก้ไข้ข้อมูลเรียบร้อย');
    }
}
