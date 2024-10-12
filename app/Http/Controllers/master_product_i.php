<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\master_product_item;
use App\Models\master_quantity;
use App\Models\master_unit;
use App\Models\master_product_image;
use App\Models\log_company;
use Carbon\Carbon;
use Auth;
use App\Models\User;
class master_product_i extends Controller
{
    public function index()
    {
        $product = master_product_item::query()->get();
        $Room_Revenue = master_product_item::where('Category','Room_Type')->count();
        $Banquet = master_product_item::where('Category','Banquet')->count();
        $Meals = master_product_item::where('Category','Meals')->count();
        $Entertainment = master_product_item::where('Category','Entertainment')->count();
        $productcount = master_product_item::query()->count();

        if ($productcount != 0) {
            $CountRoom = ($Room_Revenue * 100) / $productcount;
            $CountBanquet = ($Banquet * 100) / $productcount;
            $CountMeals = ($Meals * 100) / $productcount;
            $CountEntertainment = ($Entertainment * 100) / $productcount;
        }
        else {
            $CountRoom = 0;
            $CountBanquet = 0;
            $CountMeals = 0;
            $CountEntertainment = 0;
        }
        $productroom = master_product_item::where('Category','Room_Type')->get();
        $productBanquet = master_product_item::where('Category','Banquet')->get();
        $productMeals = master_product_item::where('Category','Meals')->get();
        $productEntertainment = master_product_item::where('Category','Entertainment')->get();
        return view('master_product.index',compact('product','Room_Revenue','Banquet','Meals','Entertainment','productcount'
        ,'CountRoom','CountBanquet','CountMeals','CountEntertainment','productroom','productBanquet','productMeals','productEntertainment'));
    }
    public function create()
    {
        $quantity = master_quantity::query()->get();
        $unit = master_unit::query()->get();
        return view('master_product.create',compact('quantity','unit'));
    }
    public function Category(Request $request)
    {
        $category = $request->input('category');
        if ($category == 'Room_Type')
       {
            $lastProfile = master_product_item::where('Category', 'Room_Type')->count() + 1;
            $Profile_ID ="R-";
            $Product_ID = $Profile_ID.$lastProfile;
       }else if ($category == 'Banquet')
       {
            $lastProfile = master_product_item::where('Category', 'Banquet')->count() + 1;
            $Profile_ID ="B-";
            $Product_ID = $Profile_ID.$lastProfile;
       }elseif ($category == 'Meals')
       {
            $lastProfile = master_product_item::where('Category', 'Meals')->count() + 1;
            $Profile_ID ="M-";
            $Product_ID = $Profile_ID.$lastProfile;
       }elseif ($category == 'Entertainment'){
            $lastProfile = master_product_item::where('Category', 'Entertainment')->count() + 1;
            $Profile_ID ="E-";
            $Product_ID = $Profile_ID.$lastProfile;
       }else{
            $Product_ID = "";
       }

        return response()->json([
            'data' => $Product_ID,
        ]);
    }

    public function edit($id)
    {
        $product = master_product_item::find($id);
        $productID = $product->Product_ID;
        $image =master_product_image::where('Product_ID',$productID)->get();
        $imagePaths = $image->pluck('image_other')->toArray();
        $quantity = master_quantity::query()->get();
        $unit = master_unit::query()->get();
        return view('master_product.edit',compact('product','quantity','unit','imagePaths'));
    }
    public function view($id)
    {
        $product = master_product_item::find($id);
        $productID = $product->Product_ID;
        $image =master_product_image::where('Product_ID',$productID)->get();
        $imagePaths = $image->pluck('image_other')->toArray();
        $quantity = master_quantity::query()->get();
        $unit = master_unit::query()->get();
        return view('master_product.view',compact('product','quantity','unit','imagePaths'));
    }
    public function save(Request $request)
    {
        $data = $request->all();
        $Category = $request->category;
        $name_th = $request->name_th;
        $name_en = $request->name_en;
        $detail_th =$request->detail_th;
        $detail_en =$request->detail_en;
        $pax =$request->pax;
        $room_size = $request->room_size;
        $normal_price = $request->normal_price;
        $weekend_price = $request->weekend_price;
        $long_weekend_price = $request->long_weekend_price;
        $end_weekend_price = $request->end_weekend_price;
        $Quantity = $request->quantity;
        $Unit = $request->unit;
        $Maximum_Discount = $request->Maximum_Discount;
        if ($request->hasFile('imageFile')) {
            $image = $request->file('imageFile');
            $image_name_gen = hexdec(uniqid());
            $img_ext = strtolower($image->getClientOriginalExtension());
            $img_name1 = $image_name_gen . '.' . $img_ext;
            $upload_location_image = 'image/product/image-product/';
            if (!file_exists($upload_location_image)) {
                // สร้างโฟลเดอร์ถ้ายังไม่มี
                mkdir($upload_location_image, 0777, true);
            }

            if (!is_writable($upload_location_image)) {
                // ให้สิทธิ์ในการเขียนไฟล์
                chmod($upload_location_image, 0777);
            }
            $full_path_image = $upload_location_image . $img_name1;
            $image->move($upload_location_image,$img_name1);
        }
        if ($Category == 'Room_Type')
        {
                $lastProfile = master_product_item::where('Category', 'Room_Type')->count() + 1;
                $Profile_ID ="R-";
                $Product_ID = $Profile_ID.$lastProfile;
                $type = '1';


        }else if ($Category == 'Banquet')
        {
                $lastProfile = master_product_item::where('Category', 'Banquet')->count() + 1;
                $Profile_ID ="B-";
                $Product_ID = $Profile_ID.$lastProfile;
                $type = '2';

        }elseif ($Category == 'Meals')
        {
                $lastProfile = master_product_item::where('Category', 'Meals')->count() + 1;
                $Profile_ID ="M-";
                $Product_ID = $Profile_ID.$lastProfile;
                $type = '3';
        }elseif ($Category == 'Entertainment')
        {
                $lastProfile = master_product_item::where('Category', 'Entertainment')->count() + 1;
                $Profile_ID ="E-";
                $Product_ID = $Profile_ID.$lastProfile;
                $type = '4';
        }else{
                return redirect()->back()->with('error_', 'Please enter the product type.');
        }
            $userid = Auth::user()->id;
            $save = new master_product_item();
            $save->Product_ID = $Product_ID;
            $save->created_by = $userid;
            $save->type = $type;
            $save->name_th = $name_th;
            $save->name_en = $name_en;
            $save->detail_th = $detail_th;
            $save->detail_en = $detail_en;
            $save->Category = $Category;
            $save->pax = $pax;
            $save->room_size = $room_size;
            $save->normal_price = $normal_price;
            $save->quantity = $Quantity;
            $save->unit = $Unit;
            $save->maximum_discount = $Maximum_Discount;
            if ($request->hasFile('imageFile')) {
                $save->image_product = $full_path_image;
                $save->save();
            }else{
                $save->save();
            }
            if ($request->hasFile('image_other')) {
                $imageother = $request->file('image_other');
                $upload_location_image2 = 'image/product/image-orther/';

                if (!file_exists($upload_location_image2)) {
                    // สร้างโฟลเดอร์ถ้ายังไม่มี
                    mkdir($upload_location_image2, 0777, true);
                }

                if (!is_writable($upload_location_image2)) {
                    // ให้สิทธิ์ในการเขียนไฟล์
                    chmod($upload_location_image2, 0777);
                }

                foreach ($imageother as $file) {
                    $image_name_gen = hexdec(uniqid());
                    $img_ext = strtolower($file->getClientOriginalExtension());
                    $img_name2 = $image_name_gen . '.' . $img_ext;
                    $fullimageother = $upload_location_image2 . $img_name2;

                    // ย้ายไฟล์ไปยังตำแหน่งที่กำหนด
                    $file->move($upload_location_image2, $img_name2);
                    $saveimage = new master_product_image();
                    $saveimage->Product_ID = $Product_ID;
                    $saveimage->image_other	=$fullimageother;
                    $saveimage->save();
                }
            }

        if ($save->save()) {

            return redirect()->route('Mproduct.index')->with('alert_', 'บันทึกข้อมูลเรียบร้อย');
        } else {
            return redirect()->back()->with('error_', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
    }
    public function ac(Request $request)
    {

        $ac = $request->value;
        if ($ac == 1 ) {
            $query = master_product_item::query();
            $product = $query->where('status', '1')->get();
        }
        return view('master_product.index',compact('product'));
    }
    public function no(Request $request)
    {
        $no = $request->value;
        if ($no == 0 ) {
            $query = master_product_item::query();
            $product = $query->where('status', '0')->get();
        }
        return view('master_product.index',compact('product'));
    }
    public function changeStatus($id)
    {

        $product = master_product_item::find($id);
        if ($product->status == 1 ) {
            $status = 0;
            $product->status = $status;
        }elseif (($product->status == 0 )) {
            $status = 1;
            $product->status = $status;
        }
        $product->save();
    }
    public function Room_Type(Request $request)
    {

        $Room_Type = $request->value;
        if ($Room_Type == 'Room_Type' ) {
            $query = master_product_item::query();
            $product = $query->where('Category', 'Room_Type')->get();
        }
        return view('master_product.index',compact('product'));
    }
    public function Banquet(Request $request)
    {
        $Banquet = $request->value;
        if ($Banquet == 'Banquet' ) {
            $query = master_product_item::query();
            $product = $query->where('Category', 'Banquet')->get();
        }
        return view('master_product.index',compact('product'));
    }
    public function Meals(Request $request)
    {
        $Meals = $request->value;
        if ($Meals == 'Meals' ) {
            $query = master_product_item::query();
            $product = $query->where('Category', 'Meals')->get();
        }
        return view('master_product.index',compact('product'));
    }
    public function Entertainment(Request $request)
    {
        $Entertainment = $request->value;
        if ($Entertainment == 'Entertainment' ) {
            $query = master_product_item::query();
            $product = $query->where('Category', 'Entertainment')->get();
        }
        return view('master_product.index',compact('product'));
    }
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $name_th = $request->name_th;
        $name_en = $request->name_en;
        $detail_th =$request->detail_th;
        $detail_en =$request->detail_en;
        $pax =$request->pax;
        $room_size = $request->room_size;
        $normal_price = $request->normal_price;
        $Quantity = $request->quantity;
        $Unit = $request->unit;
        $Maximum_Discount = $request->Maximum_Discount;
        if ($request->hasFile('imageFile')) {
            $image = master_product_item::find($id);
            $filePath = public_path($image->image_product);
            if (file_exists($filePath)) {
                unlink($filePath);
                // ลบไฟล์จากระบบไฟล์
            }
            $imageFile = $request->file('imageFile');
            $image_name_gen = hexdec(uniqid());
            $img_ext = strtolower($imageFile->getClientOriginalExtension());
            $img_name1 = $image_name_gen . '.' . $img_ext;
            $upload_location_image = 'image/product/image-product/';
            if (!file_exists($upload_location_image)) {
                // สร้างโฟลเดอร์ถ้ายังไม่มี
                mkdir($upload_location_image, 0777, true);
            }

            if (!is_writable($upload_location_image)) {
                // ให้สิทธิ์ในการเขียนไฟล์
                chmod($upload_location_image, 0777);
            }
            $full_path_image = $upload_location_image . $img_name1;
        }
        $userid = Auth::user()->id;
        $save = master_product_item::find($id);
        $save->name_th = $name_th;
        $save->name_en = $name_en;
        $save->detail_th = $detail_th;
        $save->detail_en = $detail_en;
        $save->pax = $pax;
        $save->room_size = $room_size;
        $save->normal_price = $normal_price;
        $save->quantity = $Quantity;
        $save->unit = $Unit;
        $save->created_by = $userid;
        $save->maximum_discount = $Maximum_Discount;
        $save->image_product = $full_path_image ?? $save->image_product;
        $save->save();
        if ($request->hasFile('image_other')) {
            $image = master_product_image::find($id);
                $filePath = public_path($image->image_other);
                if (file_exists($filePath)) {
                    unlink($filePath);
                    // ลบไฟล์จากระบบไฟล์
                }
            $imageother = $request->file('image_other');
            $upload_location_image2 = 'image/product/image-orther/';

            if (!file_exists($upload_location_image2)) {
                // สร้างโฟลเดอร์ถ้ายังไม่มี
                mkdir($upload_location_image2, 0777, true);
            }

            if (!is_writable($upload_location_image2)) {
                // ให้สิทธิ์ในการเขียนไฟล์
                chmod($upload_location_image2, 0777);
            }
            $Product_ID= master_product_item::where('id',$id)->first();
            $idProduct = $Product_ID->Product_ID;
        foreach ($imageother as $file) {
            $image_name_gen = hexdec(uniqid());
            $img_ext = strtolower($file->getClientOriginalExtension());
            $img_name2 = $image_name_gen . '.' . $img_ext;
            $fullimageother = $upload_location_image2 . $img_name2;

            // ย้ายไฟล์ไปยังตำแหน่งที่กำหนด
            $file->move($upload_location_image2, $img_name2);
            $saveimage = new master_product_image();
            $saveimage->Product_ID = $idProduct;
            $saveimage->image_other	=$fullimageother;
            $saveimage->save();
        }
        }
        if ($save->save()) {
        if ($imageFile ?? false) {
            $imageFile->move($upload_location_image, $img_name1);
        }
            return redirect()->route('Mproduct.index')->with('alert_', 'บันทึกข้อมูลเรียบร้อย');
        } else {
            return redirect()->back()->with('error_', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }

    }
    public function delete($id)
    {
        $product = master_product_item::find($id);
        $product->delete();
        return redirect()->route('Mproduct.index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }
    //------------------------------------------------------------------------------------------------
    //-----------------------------------Quantity-----------------------------------------------------
    public function index_quantity($menu)
    {
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $quantity = master_quantity::query()->paginate($perPage);
        $exp = explode('.', $menu);
        if (count($exp) > 1) {
            $search = $exp[1];
            if ($search == "all") {
                $quantity = master_quantity::query()
                ->paginate($perPage);
            }elseif ($search == 'ac') {
                $quantity = master_quantity::query()
                ->where('status', 1)
                ->paginate($perPage);
            }else {
                $quantity = master_quantity::query()
                ->where('status', 0)
                ->paginate($perPage);
            }
        }
        return view('master_quantity.index',compact('quantity','menu'));
    }
    public function save_quantity(Request $request)
    {
        try {
            $data = $request->all();
            $userid = Auth::user()->id;
            $lastProfile = master_quantity::count() + 1;
            $save = new master_quantity();
            $save->Product_ID = $lastProfile;
            $save->name_th = $request->name_th;
            $save->name_en = $request->name_en;
            $save->create_by = $userid;
            $save->save();
        } catch (\Throwable  $e) {
            return redirect()->route('Quantity','index')->with('error', $e->getMessage());
        }
        try {
            //log
            $nameth = 'ชื่อภาษาไทย : '.$request->name_th;
            $nameen = 'ชื่อภาษาอังกฤษ : '.$request->name_en;
            $datacompany = '';
            $variables = [$nameth, $nameen];
            // รวม $formattedProductDataString เข้าไปใน $variables
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
            $save->Company_ID = 'Master Quantity';
            $save->type = 'Create';
            $save->Category = 'Create :: Master Quantity';
            $save->content =$datacompany;
            $save->save();

        } catch (\Throwable  $e) {
            return redirect()->route('Quantity','index')->with('error', $e->getMessage());
        }
        return redirect()->route('Quantity','index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }

    public function edit_quantity($id)
    {
        $data = master_quantity::find($id);
        return response()->json(['data' => $data]);
    }
    public function changeStatus_quantity($id)
    {

        $quantity = master_quantity::find($id);
        if ($quantity->status == 1 ) {
            $status = 0;
            $quantity->status = $status;
        }elseif (($quantity->status == 0 )) {
            $status = 1;
            $quantity->status = $status;
        }
        $quantity->save();
    }


    public function  searchquantity($datakey)
    {
        $data = master_quantity::where('name_th',$datakey)->first();
        return response()->json($data);
    }
    public function  dupicatequantity($id,$datakey)
    {
        $data = master_quantity::where('id',$id)->where('name_th',$datakey)->first();
        return response()->json(['data' => $data]);
    }

    public function  update_quantity($id,$datakey,$dataEN)
    {
        try {
            $userid = Auth::user()->id;
            $save = master_quantity::find($id);
            $save->name_th = $datakey;
            $save->name_en = $dataEN;
            $save->create_by = $userid;
            $save->save();
        } catch (\Throwable  $e) {
            return redirect()->route('Quantity','index')->with('error', $e->getMessage());
        }
        try {
            $nameth = null;
            if ($datakey) {
                $nameth = 'ชื่อภาษาไทย : '.$datakey;
            }
            $nameen = null;
            if ($datakey) {
                $nameen = 'ชื่อภาษาอังกฤษ : '.$dataEN;
            }
            $datacompany = '';
            $variables = [$nameth, $nameen];
            // รวม $formattedProductDataString เข้าไปใน $variables
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
            $save->Company_ID = 'Master Quantity';
            $save->type = 'Edit';
            $save->Category = 'Edit :: Master Quantity';
            $save->content =$datacompany;
            $save->save();

        } catch (\Throwable  $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        return redirect()->route('Quantity','index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }
    public function quantity_log(){
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $log = log_company::where('Company_ID', 'Master Quantity')
        ->orderBy('updated_at', 'desc')
        ->paginate($perPage);
        return view('master_quantity.log',compact('log'));
    }

    public function quantity_search_table(Request $request){
        $perPage = (int)$request->perPage;
        $search_value = $request->search_value;
        $guest_profile = $request->guest_profile;
        $userid = Auth::user()->id;
        $permissionid = Auth::user()->permission;
        if ($search_value) {
            $data_query = master_quantity::where('name_th', 'LIKE', '%'.$search_value.'%')
            ->orWhere('name_en', 'LIKE', '%'.$search_value.'%')
            ->paginate($perPage);
        }else{
            $perPageS = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
            $data_query = master_quantity::query()->paginate($perPageS);
        }
        $data = [];
        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $btn_action = "";
                $btn_status = "";
                $name ="";
                $view ="";
                if ($value->status == 1) {
                    $btn_status = '<button type="button" class="btn btn-light-success btn-sm" value="'.$value->id.'" onclick="btnstatus('.$value->id.')">ใช้งาน</button>';
                } else {
                    $btn_status = '<button type="button" class="btn btn-light-danger btn-sm" value="'.$value->id.'" onclick="btnstatus('.$value->id.')">ปิดใช้งาน</button>';
                }

                $path = 'promotion/';
                $btn_action = '<div class="dropdown">';
                $btn_action .= '<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>';
                $btn_action .= '<ul class="dropdown-menu border-0 shadow p-3">';
                $btn_action .= '<li><a class="dropdown-item py-2 rounded" onclick="view_detail('.$value->id.')" data-bs-toggle="modal" data-bs-target="#QuantityCreate">View</a></li>';
                $btn_action .= '<li><a class="dropdown-item py-2 rounded" onclick="edit('.$value->id.')" data-bs-toggle="modal" data-bs-target="#QuantityCreate">Edit</a></li>';
                $btn_action .= '</ul>';
                $btn_action .= '</div>';

                $data[] = [
                    'number' => ($key + 1) ,
                    'nameth' => $value->name_th,
                    'nameen' => $value->name_en,
                    'status' => $btn_status,
                    'btn_action' => $btn_action,
                ];
            }
        }
        return response()->json([
            'data' => $data,
        ]);
    }
    public function quantity_paginate_table(Request $request){
        $perPage = (int)$request->perPage;
        $userid = Auth::user()->id;
        $data = [];
        $permissionid = Auth::user()->permission;
        if ($perPage == 10) {
            $data_query = master_quantity::query()->limit($request->page.'0')
            ->get();
        } else {
            $data_query = master_quantity::query()->paginate($perPage);
        }


        $page_1 = $request->page == 1 ? 1 : ($request->page - 1).'1';
        $page_2 = $request->page.'0';

        $perPage2 = $request->perPage > 10 ? $request->perPage : 10;

        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $btn_action = "";
                $btn_status = "";
                $name ="";

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
                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" onclick="view_detail('.$value->id.')" data-bs-toggle="modal" data-bs-target="#QuantityCreate">View</a></li>';
                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" onclick="edit('.$value->id.')" data-bs-toggle="modal" data-bs-target="#QuantityCreate">Edit</a></li>';
                    $btn_action .= '</ul>';
                    $btn_action .= '</div>';

                    $data[] = [
                        'number' => ($key + 1) ,
                        'nameth' => $value->name_th,
                        'nameen' => $value->name_en,
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

    public function quantity_search_table_paginate_log(Request $request){
        $perPage = (int)$request->perPage;
        $search_value = $request->search_value;
        $guest_profile = $request->guest_profile;

        if ($search_value) {
            $data_query = log_company::where('created_at', 'LIKE', '%'.$search_value.'%')
                ->where('Company_ID','Master Quantity')
                ->orderBy('updated_at', 'desc')
                ->paginate($perPage);
        }else{
            $perPageS = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
            $data_query = log_company::where('Company_ID', 'Master Quantity')->orderBy('updated_at', 'desc')->paginate($perPageS);
        }
        $data = [];
        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $contentArray = explode('+', $value->content);
                $content = implode('</br>', $contentArray);
                $Category = '<b style="color:#0000FF ">' . $value->Category . '</b>';
                $name = $Category.'</br>'.$content;
                $data[] = [
                    'number' => $key + 1,
                    'Category'=>$value->Category,
                    'type'=>$value->type,
                    'Created_by'=>@$value->userOperated->name,
                    'created_at' => \Carbon\Carbon::parse($value->created_at)->format('d/m/Y'),
                    'Content' => $name,
                ];
            }
        }
        return response()->json([
            'data' => $data,
        ]);
    }
    public function quantity_paginate_log_table(Request $request){
        $perPage = (int)$request->perPage;
        $guest_profile = $request->guest_profile;


        if ($perPage == 10) {
            $data_query = log_company::where('Company_ID', 'Master Quantity')->orderBy('updated_at', 'desc')->limit($request->page.'0')->get();
        } else {
            $data_query = log_company::where('Company_ID', 'Master Quantity')->orderBy('updated_at', 'desc')->paginate($perPage);
        }
        $page_1 = $request->page == 1 ? 1 : ($request->page - 1).'1';
        $page_2 = $request->page.'0';

        $perPage2 = $request->perPage > 10 ? $request->perPage : 10;
        $data = [];
        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $contentArray = explode('+', $value->content);
                $content = implode('</br>', $contentArray);
                $Category = '<b style="color:#0000FF ">' . $value->Category . '</b>';
                $name = $Category.'</br>'.$content;
                if (($key + 1) >= (int)$page_1 && ($key + 1) <= (int)$page_2 || (int)$perPage > 10 && $key < (int)$perPage2) {
                    $data[] = [
                        'number' => $key + 1,
                        'Category'=>$value->Category,
                        'type'=>$value->type,
                        'Created_by'=>@$value->userOperated->name,
                        'created_at' => \Carbon\Carbon::parse($value->created_at)->format('d/m/Y'),
                        'Content' => $name,
                    ];
                }
            }
        }
        return response()->json([
            'data' => $data,
        ]);

    }
    //------------------------------------------------------------
    //------------------------Unit--------------------------------
    public function index_unit($menu)
    {
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $unit = master_unit::query()->paginate($perPage);
        $exp = explode('.', $menu);
        if (count($exp) > 1) {
            $search = $exp[1];
            if ($search == "all") {
                $unit = master_unit::query()
                ->paginate($perPage);
            }elseif ($search == 'ac') {
                $unit = master_unit::query()
                ->where('status', 1)
                ->paginate($perPage);
            }else {
                $unit = master_unit::query()
                ->where('status', 0)
                ->paginate($perPage);
            }
        }
        return view('master_unit.index',compact('unit','menu'));
    }

    public function  search($datakey)
    {
        $data = master_unit::where('name_th',$datakey)->first();
        return response()->json($data);
    }
    public function  dupicate($id,$datakey)
    {
        $data = master_unit::where('id',$id)->where('name_th',$datakey)->first();
        return response()->json(['data' => $data]);
    }
    public function  update_unit($id,$datakey,$dataEN)
    {
        try {
            $userid = Auth::user()->id;
            $save = master_unit::find($id);
            $save->name_th = $datakey;
            $save->name_en = $dataEN;
            $save->create_by = $userid;
            $save->save();
        } catch (\Throwable  $e) {
            return redirect()->route('Unit','index')->with('error', $e->getMessage());
        }
        try {
            $nameth = null;
            if ($datakey) {
                $nameth = 'ชื่อภาษาไทย : '.$datakey;
            }
            $nameen = null;
            if ($datakey) {
                $nameen = 'ชื่อภาษาอังกฤษ : '.$dataEN;
            }
            $datacompany = '';
            $variables = [$nameth, $nameen];
            // รวม $formattedProductDataString เข้าไปใน $variables
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
            $save->Company_ID = 'Master Unit';
            $save->type = 'Edit';
            $save->Category = 'Edit :: Master Unit';
            $save->content =$datacompany;
            $save->save();

        } catch (\Throwable  $e) {
            return redirect()->route('Unit','index')->with('error', $e->getMessage());
        }
        return redirect()->route('Unit','index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }
    public function save_unit(Request $request)
    {
        try {
            $data = $request->all();
            $userid = Auth::user()->id;
            $lastProfile = master_unit::count() + 1;
            $save = new master_unit();
            $save->Product_ID = $lastProfile;
            $save->name_th = $request->name_th;
            $save->name_en = $request->name_en;
            $save->create_by = $userid;
            $save->save();
        } catch (\Throwable  $e) {
            return redirect()->route('Unit','index')->with('error', $e->getMessage());
        }
        try {
            //log
            $nameth = 'ชื่อภาษาไทย : '.$request->name_th;
            $nameen = 'ชื่อภาษาอังกฤษ : '.$request->name_en;
            $datacompany = '';
            $variables = [$nameth, $nameen];
            // รวม $formattedProductDataString เข้าไปใน $variables
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
            $save->Company_ID = 'Master Unit';
            $save->type = 'Create';
            $save->Category = 'Create :: Master Unit';
            $save->content =$datacompany;
            $save->save();

        } catch (\Throwable  $e) {
            return redirect()->route('Unit','index')->with('error', $e->getMessage());
        }
        return redirect()->route('Unit','index')->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }

    public function edit_unit($id)
    {

        $data = master_unit::find($id);
        return response()->json(['data' => $data]);
    }
    public function changeStatus_unit($id)
    {

        $unit = master_unit::find($id);
        if ($unit->status == 1 ) {
            $status = 0;
            $unit->status = $status;
        }elseif (($unit->status == 0 )) {
            $status = 1;
            $unit->status = $status;
        }
        $unit->save();
    }
    public function unit_log(){
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $log = log_company::where('Company_ID', 'Master Unit')
        ->orderBy('updated_at', 'desc')
        ->paginate($perPage);
        return view('master_unit.log',compact('log'));
    }

    public function unit_search_table(Request $request){
        $perPage = (int)$request->perPage;
        $search_value = $request->search_value;
        $guest_profile = $request->guest_profile;
        $userid = Auth::user()->id;
        $permissionid = Auth::user()->permission;
        if ($search_value) {
            $data_query = master_unit::where('name_th', 'LIKE', '%'.$search_value.'%')
            ->orWhere('name_en', 'LIKE', '%'.$search_value.'%')
            ->paginate($perPage);
        }else{
            $perPageS = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
            $data_query = master_unit::query()->paginate($perPageS);
        }
        $data = [];
        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $btn_action = "";
                $btn_status = "";
                $name ="";
                $view ="";
                if ($value->status == 1) {
                    $btn_status = '<button type="button" class="btn btn-light-success btn-sm" value="'.$value->id.'" onclick="btnstatus('.$value->id.')">ใช้งาน</button>';
                } else {
                    $btn_status = '<button type="button" class="btn btn-light-danger btn-sm" value="'.$value->id.'" onclick="btnstatus('.$value->id.')">ปิดใช้งาน</button>';
                }

                $path = 'promotion/';
                $btn_action = '<div class="dropdown">';
                $btn_action .= '<button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>';
                $btn_action .= '<ul class="dropdown-menu border-0 shadow p-3">';
                $btn_action .= '<li><a class="dropdown-item py-2 rounded" onclick="view_detail('.$value->id.')" data-bs-toggle="modal" data-bs-target="#QuantityCreate">View</a></li>';
                $btn_action .= '<li><a class="dropdown-item py-2 rounded" onclick="edit('.$value->id.')" data-bs-toggle="modal" data-bs-target="#QuantityCreate">Edit</a></li>';
                $btn_action .= '</ul>';
                $btn_action .= '</div>';

                $data[] = [
                    'number' => ($key + 1) ,
                    'nameth' => $value->name_th,
                    'nameen' => $value->name_en,
                    'status' => $btn_status,
                    'btn_action' => $btn_action,
                ];
            }
        }
        return response()->json([
            'data' => $data,
        ]);
    }
    public function unit_paginate_table(Request $request){
        $perPage = (int)$request->perPage;
        $userid = Auth::user()->id;
        $data = [];
        $permissionid = Auth::user()->permission;
        if ($perPage == 10) {
            $data_query = master_unit::query()->limit($request->page.'0')
            ->get();
        } else {
            $data_query = master_unit::query()->paginate($perPage);
        }


        $page_1 = $request->page == 1 ? 1 : ($request->page - 1).'1';
        $page_2 = $request->page.'0';

        $perPage2 = $request->perPage > 10 ? $request->perPage : 10;

        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $btn_action = "";
                $btn_status = "";
                $name ="";

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
                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" onclick="view_detail('.$value->id.')" data-bs-toggle="modal" data-bs-target="#QuantityCreate">View</a></li>';
                    $btn_action .= '<li><a class="dropdown-item py-2 rounded" onclick="edit('.$value->id.')" data-bs-toggle="modal" data-bs-target="#QuantityCreate">Edit</a></li>';
                    $btn_action .= '</ul>';
                    $btn_action .= '</div>';

                    $data[] = [
                        'number' => ($key + 1) ,
                        'nameth' => $value->name_th,
                        'nameen' => $value->name_en,
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

    public function unit_search_table_paginate_log(Request $request){
        $perPage = (int)$request->perPage;
        $search_value = $request->search_value;
        $guest_profile = $request->guest_profile;

        if ($search_value) {
            $data_query = log_company::where('created_at', 'LIKE', '%'.$search_value.'%')
                ->where('Company_ID','Master Quantity')
                ->orderBy('updated_at', 'desc')
                ->paginate($perPage);
        }else{
            $perPageS = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
            $data_query = log_company::where('Company_ID', 'Master Quantity')->orderBy('updated_at', 'desc')->paginate($perPageS);
        }
        $data = [];
        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $contentArray = explode('+', $value->content);
                $content = implode('</br>', $contentArray);
                $Category = '<b style="color:#0000FF ">' . $value->Category . '</b>';
                $name = $Category.'</br>'.$content;
                $data[] = [
                    'number' => $key + 1,
                    'Category'=>$value->Category,
                    'type'=>$value->type,
                    'Created_by'=>@$value->userOperated->name,
                    'created_at' => \Carbon\Carbon::parse($value->created_at)->format('d/m/Y'),
                    'Content' => $name,
                ];
            }
        }
        return response()->json([
            'data' => $data,
        ]);
    }
    public function unit_paginate_log_table(Request $request){
        $perPage = (int)$request->perPage;
        $guest_profile = $request->guest_profile;


        if ($perPage == 10) {
            $data_query = log_company::where('Company_ID', 'Master Quantity')->orderBy('updated_at', 'desc')->limit($request->page.'0')->get();
        } else {
            $data_query = log_company::where('Company_ID', 'Master Quantity')->orderBy('updated_at', 'desc')->paginate($perPage);
        }
        $page_1 = $request->page == 1 ? 1 : ($request->page - 1).'1';
        $page_2 = $request->page.'0';

        $perPage2 = $request->perPage > 10 ? $request->perPage : 10;
        $data = [];
        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                $contentArray = explode('+', $value->content);
                $content = implode('</br>', $contentArray);
                $Category = '<b style="color:#0000FF ">' . $value->Category . '</b>';
                $name = $Category.'</br>'.$content;
                if (($key + 1) >= (int)$page_1 && ($key + 1) <= (int)$page_2 || (int)$perPage > 10 && $key < (int)$perPage2) {
                    $data[] = [
                        'number' => $key + 1,
                        'Category'=>$value->Category,
                        'type'=>$value->type,
                        'Created_by'=>@$value->userOperated->name,
                        'created_at' => \Carbon\Carbon::parse($value->created_at)->format('d/m/Y'),
                        'Content' => $name,
                    ];
                }
            }
        }
        return response()->json([
            'data' => $data,
        ]);

    }
}
