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
        $newRunNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        $Quotation_ID = $ID.$year.$month.$newRunNumber;
        // $Company = companys::select('Company_Name','id')->where('status', '1')->get();
        $Company = companys::select('Company_Name','id','Profile_ID')->get();
        return view('quotation.create',compact('Quotation_ID','Company'));
    }
    public function Contact($companyID)
    {
        $Contact_name = representative::where('Company_ID',$companyID)->select('First_name','Last_name','Profile_ID','id')->orderby('id','desc')->get();
        return response()->json([
            'data' => $Contact_name,

        ]);
    }
    public function create_view($companyID)
    {
        $Company = companys::where('Profile_ID',$companyID)->first();
        $company_fax = company_fax::where('Profile_ID',$companyID)->where('Sequence','main')->first();
        $company_phone = company_phone::where('Profile_ID',$companyID)->where('Sequence','main')->first();
        $Contact_name = representative::where('Company_ID',$companyID)->where('status',1)->first();
        $Contact_phone = representative_phone::where('Company_ID',$companyID)->where('Sequence','main')->first();
        return response()->json([
            'Company' => $Company,
            'Contact_name'=>$Contact_name,
            'Contact_phone'=>$Contact_phone,
            'company_fax'=>$company_fax,
            'company_phone'=>$company_phone,


        ]);
    }
}
