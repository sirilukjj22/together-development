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
        $promotion = master_promotion::query()->paginate($perPage);
        $path = 'promotion/';
        $exp = explode('.', $menu);
        if (count($exp) > 1) {
            $search = $exp[1];
            if ($search == "all") {
                $promotion = master_promotion::query()->paginate($perPage);
            }elseif ($search == 'ac') {
                $promotion = master_promotion::query()->where('status',1)->paginate($perPage);
            }else {
                $promotion = master_promotion::query()->where('status',0)->paginate($perPage);
            }
        }
        return view('master_promotion.index',compact('promotion','path','menu'));
    }
    public function save(Request $request) {
        // $request->validate([
        //     'file.*' => 'required|mimes:png,jpg,pdf,mp4|max:1024000', // ขนาดสูงสุด 1000 MB
        // ]);
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
        return redirect()->route('Mpromotion','index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }
    public function delete($id)
    {

        $product = master_promotion::find($id);
        $product->delete();
        return redirect()->route('Mpromotion','index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }
    public function search_table(Request $request){

    }
    public function paginate_table(Request $request){
        $perPage = (int)$request->perPage;
        $userid = Auth::user()->id;
        $data = [];
        $permissionid = Auth::user()->permission;
        if ($perPage == 10) {
            $data_query = master_promotion::query()->limit($request->page.'0')
            ->get();
        } else {
            $data_query = master_promotion::query()->paginate($perPage);
        }


        $page_1 = $request->page == 1 ? 1 : ($request->page - 1).'1';
        $page_2 = $request->page.'0';

        $perPage2 = $request->perPage > 10 ? $request->perPage : 10;

        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $btn_action = "";
                $btn_status = "";
                $name ="";
                $issueDate = Carbon::parse($value->updated_at); // แปลงเป็น Carbon
                $daysPassed = $issueDate->diffInDays(now());
                // สร้าง dropdown สำหรับการทำรายการ
                if (($key + 1) >= (int)$page_1 && ($key + 1) <= (int)$page_2 || (int)$perPage > 10 && $key < (int)$perPage2) {
                    if ($value->status == 1) {
                        $btn_status = '<button type="button" class="btn btn-light-success btn-sm" value="'.$value->id.'" onclick="btnstatus('.$value->id.')">ใช้งาน</button>';
                    } else {
                        $btn_status = '<button type="button" class="btn btn-light-danger btn-sm" value="'.$value->id.'" onclick="btnstatus('.$value->id.')">ปิดใช้งาน</button>';
                    }

                    $path = 'promotion/';
                    $btn_action = '<div class="dropdown">';
                    $btn_action .= '<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>';
                    $btn_action .= '<ul class="dropdown-menu border-0 shadow p-3">';

                    $btn_action .= '<li><a href="' . asset($path . $value->name ) . '" type="button" class="dropdown-item py-2 rounded" target="_blank" data-toggle="tooltip" data-placement="top" title="พิมพ์เอกสาร">View</a></li>';
                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Delete(' . $value->id . ')">Delete</a></li>';
                    $btn_action .= '</ul>';
                    $btn_action .= '</div>';

                    $data[] = [
                        'number' => ($key + 1) ,
                        'name' => $value->name,
                        'status' => $btn_status,
                        'btn_action' => $btn_action,
                    ];
                }
            }
        }
        // dd($data);
        return response()->json([
            'data' => $data,
        ]);
    }
}
