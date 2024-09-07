<?php

namespace App\Http\Controllers;

use App\Models\Masters;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($menu)
    {
        $masters = Masters::where('category', $menu)->where('status', 1)->orderBy('sort', 'asc')->paginate(10);

        $data_categorys = Masters::where('category', '')->whereNull('deleted_at')->where('status', 1)->paginate(10);

        $exp = explode('_', $menu);

        $menu_name = $menu;

        if (count($exp) > 1) {
            $search = $exp[1];
            $menu_name = $exp[0];

            if ($search == "all") {
                $masters = Masters::where('category', $menu_name)->whereNull('deleted_at')->orderBy('sort', 'asc')->paginate(10);
            }elseif ($search == 'ac') {
                $masters = Masters::where('category', $menu_name)->where('status', 1)->whereNull('deleted_at')->orderBy('sort', 'asc')->paginate(10);
            }else {
                $masters = Masters::where('category', $menu_name)->where('status', 0)->whereNull('deleted_at')->orderBy('sort', 'asc')->paginate(10);
            }
        }
        

        return view('master.'.$menu_name, compact('masters' , 'data_categorys', 'menu'));
    }

    public function search_table(Request $request)
    {
        $data = [];
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $search = $request->search_value;

        if (!empty($search)) {
            $data_query = Masters::where('category', $request->menu)->whereIn('status', $request->status)->whereNull('deleted_at')
                ->where(function($query) use ($search) {
                    $query->where('name_th', 'like', '%' . $search . '%')
                    ->orWhere('name_en', 'like', '%' . $search . '%');
                })
                ->paginate($perPage);

        } else {
            $data_query = Masters::where('category', $request->menu)->whereIn('status', $request->status)->whereNull('deleted_at')->paginate($perPage);
        }

        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {

                $image = '';
                $status_name = '';
                $btn_action = '';

                $image = '<div class="flex-jc p-left-4 center"><img class="img-bank" src="../upload/images/'.@$value->picture.'"></div>';

                // สถานะการใช้งาน
                if ($value->status == 0) { $status_name = '<button type="button" class="btn btn-light-success btn-sm btn-status" value="'.$value->id.'">Disabled</button>'; } 
                if ($value->status == 1) { $status_name = '<button type="button" class="btn btn-light-success btn-sm btn-status" value="'.$value->id.'">Active</button>'; } 

                if ($value->close_day == 0 || Auth::user()->edit_close_day == 1) {
                    $btn_action .='<div class="dropdown">';
                        $btn_action .='<button type="button" class="btn" style="background-color: #2C7F7A; color:white;" data-bs-toggle="dropdown" data-toggle="dropdown">
                                            Select <span class="caret"></span>
                                        </button>';
                        $btn_action .='<ul class="dropdown-menu">';
                            // if (User::roleMenuEdit('Users', Auth::user()->id) == 1) 
                            // {
                                $btn_action .='<li class="button-li" onclick="view_detail('.$value->id.')" data-bs-toggle="modal" data-bs-target="#exampleModalLongAddBank">Edit</li>';
                            // }
                        $btn_action .='</ul>';
                    $btn_action .='</div>';
                }

                $data[] = [
                    'id' => $key + 1,
                    'image' => $image,
                    'name_th' => $value->name_th,
                    'name_en' => $value->name_en,
                    'status_name' => $status_name,
                    'btn_action' => $btn_action,
                ];
            }
        }

        return response()->json([
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        // dd($request);
        if ($request->module_name == "create") {
            if ($request->hasFile('image')) {
                $path = 'upload/images';
                $file = $request->file('image'); // will get all files

                $file_name = $file->getClientOriginalName(); //Get file original name
                $file->move($path , $file_name); // move files to destination folder

                Masters::create([
                    'sort' => $request->sort ?? null,
                    'category' => $request->category,
                    'name_th' => $request->name_th ?? null,
                    'name_en' => $request->name_en ?? null,
                    'detail_th' => $request->detail_th ?? null,
                    'detail_en' => $request->detail_en ?? null,
                    'number_days' => $request->number_days ?? 0,
                    'account_name' => $request->account_name ?? null,
                    'account_number' => $request->account_number ?? null,
                    'type_name' => $request->type_name ?? null,
                    'entry_fields' => $request->entry_fields ?? null,
                    'picture' => $file_name ?? null,
                    'remark_th' => $request->remark_th ?? null,
                    'remark_en' => $request->remark_en ?? null,
                ]);
            }else{
                Masters::create($request->all());
            }
        
            return redirect(url('master/'.$request->category))->with('success', 'ระบบได้ทำการบันทึกรายการชื่อ '.$request->name_th.' ในระบบเรียบร้อยแล้ว');
        }else {

            $data = Masters::find($request->edit_id);
            $file_name = "";

            if ($request->image != '') {
                if (!empty($request->file('image'))) {
                    //ลบรูปเก่าเพื่ออัพโหลดรูปใหม่แทน
                    if (!empty($data->picture)) {
                        $path = 'upload/images/';
                        unlink($path.$data->picture);
                    }

                    $path = 'upload/images';
                    $file = $request->file('image'); // will get all files

                    $file_name = $file->getClientOriginalName(); //Get file original name
                    $file->move($path , $file_name); // move files to destination folder
                }

                Masters::where('id', $request->edit_id)->update([
                    'picture' => $file_name ?? null,
                ]);

            }
            // dd($request->number_days);
            Masters::where('id', $request->edit_id)->update([
                'sort' => $request->sort ?? null,
                'category' => $request->category,
                'name_th' => $request->name_th ?? null,
                'name_en' => $request->name_en ?? null,
                'detail_th' => $request->detail_th ?? null,
                'detail_en' => $request->detail_en ?? null,
                'number_days' => $request->number_days,
                'account_name' => $request->account_name ?? null,
                'account_number' => $request->account_number ?? null,
                'type_name' => $request->type_name ?? null,
                'entry_fields' => $request->entry_fields ?? null,
                'remark_th' => $request->remark_th ?? null,
                'remark_en' => $request->remark_en ?? null,
            ]);

            return redirect(url('master/'.$request->category))->with('success', 'ระบบได้ทำการแก้ไขรายการชื่อ '.$request->name_th.' ในระบบเรียบร้อยแล้ว');
        }
    }

    public function validate_field($category, $field, $datakey)
    {
        $cut_space = preg_replace('/[[:space:]]+/', '', trim($datakey));
        $txt_string = preg_replace('/[^A-Za-z0-9ก-ฮ]/', '', $cut_space);
        // return $txt_string;

        $data = Masters::check_field($category, $field, $txt_string);

       return response()->json([
        'data' => $data,
        ]);
    }

    public function validate_field_account_number($category, $field, $datakey)
    {
        $cut_space = preg_replace('/[[:space:]]+/', '', trim($datakey));
        $txt_string = preg_replace('/[^A-Za-z0-9ก-ฮ]/', '', $cut_space);
        // return $txt_string;

        $data = Masters::check_field_account_number($category, $field, $txt_string);

       return response()->json([
        'data' => $data,
        ]);
    }

    public function validate_field2($category, $field, $datakey, $type_name)
    {
        $cut_space = preg_replace('/[[:space:]]+/', '', trim($datakey));
        $txt_string = preg_replace('/[^A-Za-z0-9ก-ฮ]/', '', $cut_space);

        $data = Masters::check_field2($category, $field, $txt_string, $type_name);

       return response()->json([
        'data' => $data,
        ]);
    }

    public function validate_field_edit($id, $category, $field, $datakey)
    {
        $cut_space = preg_replace('/[[:space:]]+/', '', trim($datakey));
        $txt_string = preg_replace('/[^A-Za-z0-9ก-ฮ]/', '', $cut_space);

        $data = Masters::check_field_edit($id, $category, $field, $txt_string);

       return response()->json([
        'data' => $data,
        ]);
    }

    public function validate_field_account_number_edit($id, $category, $field, $datakey)
    {
        $cut_space = preg_replace('/[[:space:]]+/', '', trim($datakey));
        $txt_string = preg_replace('/[^A-Za-z0-9ก-ฮ]/', '', $cut_space);

        $data = Masters::check_field_account_number_edit($id, $category, $field, $txt_string);

       return response()->json([
        'data' => $data,
        ]);
    }

    public function validate_dupicate_name($category, $datakey, $type_name)
    {
        if ($type_name != 0) {
            $data = Masters::where('category', $category)->where('type_name', $type_name)
            ->select('id', 'name_th', 'type_name')->whereNull('deleted_at')->get();
        }else{
            $data = Masters::where('category', $category)
            ->select('id', 'name_th', 'type_name')->whereNull('deleted_at')->get();
        }

        $similarsV = [];
        foreach ($data as $key => $value) {
            $str = $datakey;
            $pattern = "/$value->name_th/i";
            if (preg_match($pattern, $str)) {
                if ($category == 'group') {
                    $similarsV[] = $value->name_th." (".$value->type_name.")";
                }else {
                    $similarsV[] = $value->name_th;
                }
            }
        }

        if ($type_name == 0) {
            foreach ($data as $key => $value) {
                $str = $value->name_th;
                $pattern = "/$datakey/i";
                if (preg_match($pattern, $str)) {
                    $similarsV[] = $value->name_th; 
                }
            }
        }

       return response()->json([
        'data' => $similarsV,
       ]);
    }

    public function validate_dupicate_account_number($category, $datakey, $type_name)
    {
        if ($type_name != 0) {
            $data = Masters::where('category', $category)->where('type_name', $type_name)
            ->select('id', 'account_number', 'type_name')->whereNull('deleted_at')->get();
        }else{
            $data = Masters::where('category', $category)
            ->select('id', 'account_number', 'type_name')->whereNull('deleted_at')->get();
        }

        $similarsV = [];
        foreach ($data as $key => $value) {
            $str = $datakey;
            $pattern = "/$value->account_number/i";
            if (preg_match($pattern, $str)) {
                if ($category == 'group') {
                    $similarsV[] = $value->account_number;
                }else {
                    $similarsV[] = $value->account_number;
                }
            }
        }

        if ($type_name == 0) {
            foreach ($data as $key => $value) {
                $str = $value->account_number;
                $pattern = "/$datakey/i";
                if (preg_match($pattern, $str)) {
                    $similarsV[] = $value->account_number; 
                }
            }
        }

       return response()->json([
        'data' => $similarsV,
       ]);
    }

    public function validate_dupicate_name_edit($id, $category, $datakey, $type_name)
    {
        if ($type_name != 0) {
            $data = Masters::where('category', $category)->whereNotIn('id', [$id])
            ->where('type_name', $type_name)->select('id', 'name_th', 'type_name')->whereNull('deleted_at')->get();
        }else{
            $data = Masters::where('category', $category)->whereNotIn('id', [$id])
            ->select('id', 'name_th', 'type_name')->whereNull('deleted_at')->get();
        }

        $cut_space = preg_replace('/[[:space:]]+/', '', trim($datakey));
        $txt_string = preg_replace('/[^A-Za-z0-9ก-ฮ]/', '', $cut_space);

        $similarsV = [];
        foreach ($data as $key => $value) {
            $str = $txt_string;
            $pattern = "/$value->name_th/i";
            if (preg_match($pattern, $str)) {
                if ($category == 'group') {
                    $similarsV[] = $value->name_th." (".$value->type_name.")";
                }else {
                    $similarsV[] = $value->name_th;
                }
            }
        }

        if ($type_name == 0) {
            foreach ($data as $key => $value) {
                $str = $value->name_th;
                $pattern = "/$txt_string/i";
                if (preg_match($pattern, $str)) {
                    $similarsV[] = $value->name_th; 
                }
            }
        }

       return response()->json([
        'data' => $similarsV,
       ]);
    }

    public function validate_dupicate_account_number_edit($id, $category, $datakey, $type_name)
    {
        if ($type_name != 0) {
            $data = Masters::where('category', $category)->whereNotIn('id', [$id])
            ->where('type_name', $type_name)->select('id', 'account_name', 'type_name')->whereNull('deleted_at')->get();
        }else{
            $data = Masters::where('category', $category)->whereNotIn('id', [$id])
            ->select('id', 'account_name', 'type_name')->whereNull('deleted_at')->get();
        }

        $cut_space = preg_replace('/[[:space:]]+/', '', trim($datakey));
        $txt_string = preg_replace('/[^A-Za-z0-9ก-ฮ]/', '', $cut_space);

        $similarsV = [];
        foreach ($data as $key => $value) {
            $str = $txt_string;
            $pattern = "/$value->account_name/i";
            if (preg_match($pattern, $str)) {
                if ($category == 'group') {
                    $similarsV[] = $value->account_name;
                }else {
                    $similarsV[] = $value->account_name;
                }
            }
        }

        if ($type_name == 0) {
            foreach ($data as $key => $value) {
                $str = $value->account_name;
                $pattern = "/$txt_string/i";
                if (preg_match($pattern, $str)) {
                    $similarsV[] = $value->account_name; 
                }
            }
        }

       return response()->json([
        'data' => $similarsV,
       ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Masters::find($id);

       return response()->json([
        'data' => $data,
        ]);
    }

    public function change_status($id)
    {
        $check_data = Masters::find($id);

        if ($check_data->status == 1) {
            Masters::where('id', $id)->update([
                'status' => 0,
            ]);
        }else{
            Masters::where('id', $id)->update([
                'status' => 1,
            ]);
        }
        
    }

    public function search_list($category, $name, $type_name)
    {
        if ($type_name != 0) {
            $masters = Masters::where('category', $category)->where('type_name', $type_name)
            // ->where('status', 1)
            ->whereNull('deleted_at')->where('name_th', 'LIKE', '%'.$name.'%')
            ->select('name_th', 'type_name')->limit(5)->get();
        }else {
            $masters = Masters::where('category', $category)
            // ->where('status', 1)
            ->whereNull('deleted_at')->where('name_th', 'LIKE', '%'.$name.'%')
            ->select('name_th', 'type_name')->limit(5)->get();
        }
            $output = '';

            if ($name == '') {

                $output .= '';
            } else {

                if (count($masters) > 0) {

                    foreach ($masters as $key => $item) {
                        if ($key + 1 < $masters->count()) {
                            if ($item->type_name != '') {
                                $output .= '<a href="#" class="text-warning similar_name" rel="'.$item->name_th.'">' . $item->name_th . ' ('.$item->type_name.')</a>, ';
                            }else {
                                $output .= '<a href="#" class="text-warning similar_name" rel="'.$item->name_th.'">' . $item->name_th . '</a>, ';
                            }
                        } else {
                            if ($item->type_name != '') {
                                $output .= '<a href="#" class="text-warning similar_name" rel="'.$item->name_th.'">' . $item->name_th . ' ('.$item->type_name.')</a> ';
                            }else {
                                $output .= '<a href="#" class="text-warning similar_name" rel="'.$item->name_th.'">' . $item->name_th . '</a> ';
                            }
                        }
                    }
                } else {
                    $output .= '<a href="#" class="text-warning">ไม่มีข้อมูล</a>';
                }
            }
            return $output;
    }

    public function search_account_number_list($category, $account_number, $type_name)
    {
        if ($type_name != 0) {
            $masters = Masters::where('category', $category)->where('type_name', $type_name)
            // ->where('status', 1)
            ->whereNull('deleted_at')->where('name_th', 'LIKE', '%'.$account_number.'%')
            ->select('name_th', 'type_name')->limit(5)->get();
        }else {
            $masters = Masters::where('category', $category)
            // ->where('status', 1)
            ->whereNull('deleted_at')->where('account_number', 'LIKE', '%'.$account_number.'%')
            ->select('account_number', 'type_name')->limit(5)->get();
        }
            $output = '';

            if ($account_number == '') {

                $output .= '';
            } else {

                if (count($masters) > 0) {

                    foreach ($masters as $key => $item) {
                        if ($key + 1 < $masters->count()) {
                            if ($item->type_name != '') {
                                $output .= '<a href="#" class="text-warning similar_name" rel="'.$item->account_number.'">' . $item->account_number .'</a>, ';
                            }else {
                                $output .= '<a href="#" class="text-warning similar_name" rel="'.$item->account_number.'">' . $item->account_number . '</a>, ';
                            }
                        } else {
                            if ($item->type_name != '') {
                                $output .= '<a href="#" class="text-warning similar_name" rel="'.$item->account_number.'">' . $item->account_number . '</a> ';
                            }else {
                                $output .= '<a href="#" class="text-warning similar_name" rel="'.$item->account_number.'">' . $item->account_number . '</a> ';
                            }
                        }
                    }
                } else {
                    $output .= '<a href="#" class="text-warning">ไม่มีข้อมูล</a>';
                }
            }
            return $output;
    }

    public function search_list2($category, $name, $type_name)
    {
            $masters = Masters::where('category', $category)
            ->whereNull('deleted_at')->where('type_name', $type_name)
            ->where('name_th', 'LIKE', '%' . $name . '%')
            ->select('name_th')->first();

            return $masters;
    }

    public function search_type($category, $name, $type_name)
    {
            $masters = Masters::where('category', $category)
            // ->where('status', 1)
            ->whereNull('deleted_at')->where('type_name', $type_name)
            ->where('name_th', 'LIKE', '%' . $name . '%')
            ->select('name_th')->first();

            return $masters;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if (isset($request->deleteID)) {
            Masters::where('id', $request->deleteID)->update([
                'status' => 0,
                'deleted_by' => Auth::user()->id,
                'deleted_at' => Carbon::now(),
            ]);
        } else {
            foreach ($request->radio_master_sub as $key => $value) {
                Masters::where('id', $value)->update([
                    'status' => 0,
                    'deleted_by' => Auth::user()->id,
                    'deleted_at' => Carbon::now(),
                ]);
            }
        }
    }
}
