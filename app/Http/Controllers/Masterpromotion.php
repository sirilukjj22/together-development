<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\master_promotion;
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
            $data_query = Quotation::query()->orderBy('created_at', 'desc')
            ->limit($request->page.'0')
            ->get();
        } else {
            $data_query = Quotation::query()->orderBy('created_at', 'desc')->paginate($perPage);
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

                    if ($value->type_Proposal == 'Company') {
                        $name = '<td>' .@$value->company->Company_Name. '</td>';
                    }else {
                        $name = '<td>' . @$value->guest->First_name . ' ' . @$value->guest->Last_name . '</td>';
                    }
                    // สร้างสถานะการใช้งาน
                    if ($value->status_guest == 1 &&$value->status_document !== 0) {
                        $btn_status = '<span class="badge rounded-pill bg-success">Approved</span>';
                    } else {
                        if ($value->status_document == 0) {
                            $btn_status = '<span class="badge rounded-pill bg-danger">Cancel</span>';
                        } elseif ($value->status_document == 1) {
                            $btn_status = '<span class="badge rounded-pill " style="background-color: #FF6633">Pending</span>';
                        } elseif ($value->status_document == 2) {
                            $btn_status = '<span class="badge rounded-pill bg-warning">Awaiting Approval</span>';
                        } elseif ($value->status_document == 3) {
                            $btn_status = '<span class="badge rounded-pill " style="background-color: #FF6633">Pending</span>';
                        } elseif ($value->status_document == 4) {
                            $btn_status = '<span class="badge rounded-pill " style="background-color:#1d4ed8">Reject</span>';
                        } elseif ($value->status_document == 6) {
                            $btn_status = '<span class="badge rounded-pill " style="background-color: #FF6633">Pending</span>';
                        }
                    }


                    $btn_action = '<div class="dropdown">';
                    $btn_action .= '<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>';
                    $btn_action .= '<ul class="dropdown-menu border-0 shadow p-3">';


                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Proposal/view/' . $value->id) . '">View</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" target="_blank" href="' . url('/Proposal/Quotation/cover/document/PDF/' . $value->id) . '">Export</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="' . url('/Proposal/view/quotation/LOG/' . $value->id) . '">LOG</a></li>';
                            $btn_action .= '<li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Approved(' . $value->id . ')">Approved</a></li>';

                    $btn_action .= '</ul>';
                    $btn_action .= '</div>';

                    $data[] = [
                        'number' => ($key + 1) . '<input type="hidden" id="update_date" value="' . $value->updated_at . '">',

                        'Operated' => @$value->userOperated->name,
                        'DocumentStatus' => $btn_status,
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
