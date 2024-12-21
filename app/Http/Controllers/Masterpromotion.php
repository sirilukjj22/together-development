<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\master_promotion;
use App\Models\Quotation;
use Carbon\Carbon;
use App\Models\Guest;
use App\Models\companys;
use App\Models\representative;
use App\Models\representative_phone;
use App\Models\company_fax;
use App\Models\company_phone;
use App\Models\Freelancer_Member;
use App\Models\province;
use App\Models\amphures;
use App\Models\districts;
use App\Models\master_document;
use App\Models\master_product_item;
use App\Models\master_quantity;
use App\Models\master_unit;
use App\Models\document_quotation;
use App\Models\log;
use App\Models\Master_company;
use App\Models\phone_guest;
use Auth;
use App\Models\User;
use PDF;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use App\Models\master_document_sheet;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\DB;
use App\Models\master_template;
use Illuminate\Support\Arr;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Mail\QuotationEmail;
use Illuminate\Support\Facades\Mail;
use App\Models\master_document_email;
use App\Models\log_company;
class Masterpromotion extends Controller
{
    public function index($menu)
    {
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $promotion = master_promotion::query()->get();
        $path = 'promotion/';
        $exp = explode('.', $menu);
        if (count($exp) > 1) {
            $search = $exp[1];
            if ($search == "all") {
                $promotion = master_promotion::query()->get();
            }elseif ($search == 'ac') {
                $promotion = master_promotion::query()->where('status',1)->get();
            }else {
                $promotion = master_promotion::query()->where('status',0)->get();
            }
        }
        return view('master_promotion.index',compact('promotion','path','menu'));
    }
    public function save(Request $request) {

        $type = $request->Filter;
        $link = $request->Link;
        $files = $request->file('file');
        $image = $request->image;
        try {
            if ($type == 'Link') {
                $request->validate([
                    'image' => 'required|mimes:png,jpg,pdf|max:10240', // ขนาดสูงสุด 1000 MB
                ]);
                $originalName = $image->getClientOriginalName();
                $newName = $originalName;
                $path = 'promotion/';
                $image->move(public_path($path), $newName);
                $save = new master_promotion();
                $save->name = $link;
                $save->type = $type;
                $save->image = $newName;
                $save->save();
            }else{
                $request->validate([
                    'file.*' => 'required|mimes:png,jpg,pdf|max:10240', // ขนาดสูงสุด 1000 MB
                ]);
                foreach ($files as $file) {
                    $originalName = $file->getClientOriginalName();
                    $newName = $originalName;
                    $path = 'promotion/';
                    $file->move(public_path($path), $newName);
                    $save = new master_promotion();
                    $save->name = $newName;
                    $save->image = $newName;
                    $save->type = $type;
                    $save->save();
                }
            }
        } catch (\Throwable $e) {
            return redirect()->route('Mpromotion','index')->with('error', $e->getMessage());
        }
        try {
                {
                    //log
                    $typelog = 'ประเภท : '.$type;

                    $linklog=null;
                    if ($link) {
                        $linklog = 'Link : '.$link;
                    }

                    $filelog=[];
                    if ($files) {
                        foreach ($files as $file) {
                            $originalName = $file->getClientOriginalName();
                            $newName = $originalName;
                            $filelog[] = 'File : '.$newName;
                        }
                    }
                    $datacompany = '';

                    $variables = [$typelog, $linklog];
                    $filelogs = implode(' + ', $filelog);

                    // รวม $formattedProductDataString เข้าไปใน $variables
                    $variables[] = $filelogs;
                    foreach ($variables as $variable) {
                        if (!empty($variable)) {
                            if (!empty($datacompany)) {
                                $datacompany .= ' + ';
                            }
                            $datacompany .= $variable;
                        }
                    }

                    $userid = Auth::user()->id;
                    $save = new log_company();
                    $save->Created_by = $userid;
                    $save->Company_ID = 'Master Promotion';
                    $save->type = 'Create';
                    $save->Category = 'Create :: Master Promotion';
                    $save->content =$datacompany;
                    $save->save();
                }
        } catch (\Throwable $e) {
            return redirect()->route('Mpromotion','index')->with('error', $e->getMessage());
        }
        return redirect()->route('Mpromotion','index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }
    public function delete($id)
    {
        $promotion =master_promotion::where('id',$id)->first();
        $name = $promotion->name;
        $datacompany = 'ชื่อ : '.$name;
        $product = master_promotion::find($id);
        $product->delete();
        $userid = Auth::user()->id;
        $save = new log_company();
        $save->Created_by = $userid;
        $save->Company_ID = 'Master Promotion';
        $save->type = 'Delete';
        $save->Category = 'Delete :: Master Promotion';
        $save->content =$datacompany;
        $save->save();
        return redirect()->route('Mpromotion','index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }

    public function log(){
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $log = log_company::where('Company_ID', 'Master Promotion')
        ->orderBy('updated_at', 'desc')
        ->get();
        return view('master_promotion.log',compact('log'));
    }
    public function status($id)
    {
        $event = master_promotion::find($id);
        if ($event->status == 1 ) {
            $status = 0;
            $event->status = $status;
        }elseif (($event->status == 0 )) {
            $status = 1;
            $event->status = $status;
        }
        $event->save();
    }


}
