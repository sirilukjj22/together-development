<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quotation;
use Carbon\Carbon;
use App\Models\companys;
use App\Models\representative;
use App\Models\representative_phone;
use App\Models\company_fax;
use App\Models\company_phone;
use App\Models\MasterEventFormate;
use App\Models\Freelancer_Member;
use App\Models\province;
use App\Models\amphures;
use App\Models\districts;
use App\Models\master_document;
class QuotationController extends Controller
{
    public function index()
    {
        $Quotation = Quotation::query()->get();
        return view('quotation.index',compact('Quotation'));
    }
    public function create()
    {
        $currentDate = Carbon::now();
        $ID = 'Q';
        $formattedDate = Carbon::parse($currentDate);       // วันที่
        $month = $formattedDate->format('m'); // เดือน
        $year = $formattedDate->format('y');
        $lastRun = Quotation::latest()->first();
        $nextNumber = 1;
        if ($lastRun) {
            $lastNumber = intval($lastRun->number);
            $nextNumber = $lastNumber + 1;
        }
        $Issue_date = Carbon::parse($currentDate)->translatedFormat('d/m/Y');
        $Valid_Until = Carbon::parse($currentDate)->addDays(7)->translatedFormat('d/m/Y');
        $newRunNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        $Quotation_ID = $ID.$year.$month.$newRunNumber;
        $Mevent = MasterEventFormate::select('name_th','id')->where('status', '1')->get();
        $Freelancer_member = Freelancer_Member::select('First_name','id','Profile_ID','Last_name')->where('status', '1')->get();
        $Company = companys::select('Company_Name','id','Profile_ID')->get();
        return view('quotation.create',compact('Quotation_ID','Company','Mevent','Freelancer_member','Issue_date','Valid_Until'));
    }
    public function Contact($companyID)
    {
        $Contact_name = representative::where('Company_ID',$companyID)->select('First_name','Last_name','Profile_ID','id')->orderby('id','desc')->get();
        return response()->json([
            'data' => $Contact_name,

        ]);
    }

    public function save(Request $request){
        $data = $request->all();
        $Quotation_ID = $request->Quotation_ID;
        return redirect()->to(route('Quotation.SelectProduct',['id' => $Quotation_ID]))->with('alert_', 'บันทึกข้อมูลเรียบร้อย');
    }
    public function selectProduct($id)
    {
        $currentDate = Carbon::now();
        $Issue_date = Carbon::parse($currentDate)->translatedFormat('d/m/Y');
        $Valid_Until = Carbon::parse($currentDate)->addDays(7)->translatedFormat('d/m/Y');

        // ดึงข้อมูล Quotation ที่ตรงกับ ID
        $Quotation = Quotation::where('Quotation_ID', $id)->first();

        // ส่งตัวแปรไปยัง view
        return response()->json([
            'Valid_Until' => $id,

        ]);
    }
}
