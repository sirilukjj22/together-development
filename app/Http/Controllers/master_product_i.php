<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\master_product_item;
use App\Models\master_quantity;
use App\Models\master_unit;
class master_product_i extends Controller
{
    public function index()
    {
        $product = master_product_item::query()->get();
        return view('master_product.index',compact('product'));
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
        $image=$product->image_product;
        $quantity = master_quantity::query()->get();
        $unit = master_unit::query()->get();
        return view('master_product.edit',compact('product','quantity','unit'));
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
       if ($Category == 'Room_Type')
       {
            $lastProfile = master_product_item::where('Category', 'Room_Type')->count() + 1;
            $Profile_ID ="R-";
            $Product_ID = $Profile_ID.$lastProfile;
            $type = 'Room_Revenue';


       }else if ($Category == 'Banquet')
       {
            $lastProfile = master_product_item::where('Category', 'Banquet')->count() + 1;
            $Profile_ID ="B-";
            $Product_ID = $Profile_ID.$lastProfile;
            $type = 'Other_Revenue';

       }elseif ($Category == 'Meals')
       {
            $lastProfile = master_product_item::where('Category', 'Meals')->count() + 1;
            $Profile_ID ="M-";
            $Product_ID = $Profile_ID.$lastProfile;
            $type = 'F&B_Revenue';
       }elseif ($Category == 'Entertainment')
       {
            $lastProfile = master_product_item::where('Category', 'Entertainment')->count() + 1;
            $Profile_ID ="E-";
            $Product_ID = $Profile_ID.$lastProfile;
            $type = 'Other_Revenue';
       }else{
            return redirect()->back()->with('error_', 'Please enter the product type.');
       }
       $save = new master_product_item();
       $save->Product_ID = $Product_ID;
       $save->type = $type;
       $save->name_th = $name_th;
       $save->name_en = $name_en;
       $save->detail_th = $detail_th;
       $save->detail_en = $detail_en;
       $save->Category = $Category;
       $save->pax = $pax;
       $save->room_size = $room_size;
       $save->normal_price = $normal_price;
       $save->weekend_price = $weekend_price;
       $save->long_weekend_price = $long_weekend_price;
       $save->end_weekend_price = $end_weekend_price;
       $save->quantity = $Quantity;
       $save->unit = $Unit;
       $save->maximum_discount = $Maximum_Discount;
       $save->image_product = $full_path_image;
       $save->save();
        if ($save->save()) {
            $image->move($upload_location_image,$img_name1);
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
    public function changeStatus($id,$status)
    {

        $product = master_product_item::find($id);
        if ($status == 1 ) {
            $status = 0;
            $product->status = $status;
        }elseif (($status == 0 )) {
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
        $weekend_price = $request->weekend_price;
        $long_weekend_price = $request->long_weekend_price;
        $end_weekend_price = $request->end_weekend_price;
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

        $save = master_product_item::find($id);
        $save->name_th = $name_th;
        $save->name_en = $name_en;
        $save->detail_th = $detail_th;
        $save->detail_en = $detail_en;
        $save->pax = $pax;
        $save->room_size = $room_size;
        $save->normal_price = $normal_price;
        $save->weekend_price = $weekend_price;
        $save->long_weekend_price = $long_weekend_price;
        $save->end_weekend_price = $end_weekend_price;
        $save->quantity = $Quantity;
        $save->unit = $Unit;
        $save->maximum_discount = $Maximum_Discount;
        $save->image_product = $full_path_image ?? $save->image_product;
        $save->save();
         if ($save->save()) {
            if ($imageFile ?? false) {
                $imageFile->move($upload_location_image, $img_name1);
            }
             return redirect()->route('Mproduct.index')->with('alert_', 'บันทึกข้อมูลเรียบร้อย');
         } else {
             return redirect()->back()->with('error_', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
         }

    }
    //------------------------------------------------------------------------------------------------
    //-----------------------------------Quantity-----------------------------------------------------
    public function index_quantity()
    {
        $quantity = master_quantity::query()->get();
        return view('quantity.index',compact('quantity'));
    }
    public function save_quantity(Request $request)
    {
        $data = $request->all();
        $lastProfile = master_quantity::count() + 1;
        $save = new master_quantity();
        $save->Product_ID = $lastProfile;
        $save->name_th = $request->name_th;
        $save->name_en = $request->name_en;
        $save->save();
        if ($save->save()) {
            return redirect()->back()->with('alert_', 'บันทึกข้อมูลเรียบร้อย');
        }
        else {
            return redirect()->back()->with('error_', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
    }

    public function edit_quantity(Request $request)
    {
        $data = $request->all();
        $id = $request->id;
        $save = master_quantity::find($id);
        $save->name_th = $request->name_th;
        $save->name_en = $request->name_en;
        $save->save();
        if ($save->save()) {
            return redirect()->back()->with('alert_', 'บันทึกข้อมูลเรียบร้อย');
        }
        else {
            return redirect()->back()->with('error_', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
    }
    public function changeStatus_quantity($id,$status)
    {

        $quantity = master_quantity::find($id);
        if ($status == 1 ) {
            $status = 0;
            $quantity->status = $status;
        }elseif (($status == 0 )) {
            $status = 1;
            $quantity->status = $status;
        }
        $quantity->save();
    }
    public function ac_quantity(Request $request)
    {

        $ac = $request->value;
        if ($ac == 1 ) {
            $query = master_quantity::query();
            $quantity = $query->where('status', '1')->get();
        }
        return view('quantity.index',compact('quantity'));
    }
    public function no_quantity(Request $request)
    {
        $no = $request->value;
        if ($no == 0 ) {
            $query = master_unit::query();
            $quantity = $query->where('status', '0')->get();
        }
        return view('unit.index',compact('quantity'));
    }
    //------------------------Unit--------------------------------
    public function index_unit()
    {
        $unit = master_unit::query()->get();
        return view('unit.index',compact('unit'));
    }
    public function save_unit(Request $request)
    {
        $data = $request->all();
        $lastProfile = master_unit::count() + 1;
        $save = new master_unit();
        $save->Product_ID = $lastProfile;
        $save->name_th = $request->name_th;
        $save->name_en = $request->name_en;
        $save->save();
        if ($save->save()) {
            return redirect()->back()->with('alert_', 'บันทึกข้อมูลเรียบร้อย');
        }
        else {
            return redirect()->back()->with('error_', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
    }

    public function edit_unit(Request $request)
    {
        $data = $request->all();
        $id = $request->id;
        $save = master_unit::find($id);
        $save->name_th = $request->name_th;
        $save->name_en = $request->name_en;
        $save->save();
        if ($save->save()) {
            return redirect()->back()->with('alert_', 'บันทึกข้อมูลเรียบร้อย');
        }
        else {
            return redirect()->back()->with('error_', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
    }
    public function changeStatus_unit($id,$status)
    {

        $unit = master_unit::find($id);
        if ($status == 1 ) {
            $status = 0;
            $unit->status = $status;
        }elseif (($status == 0 )) {
            $status = 1;
            $unit->status = $status;
        }
        $unit->save();
    }
    public function ac_unit(Request $request)
    {

        $ac = $request->value;
        if ($ac == 1 ) {
            $query = master_unit::query();
            $unit = $query->where('status', '1')->get();
        }
        return view('unit.index',compact('unit'));
    }
    public function no_unit(Request $request)
    {
        $no = $request->value;
        if ($no == 0 ) {
            $query = master_unit::query();
            $unit = $query->where('status', '0')->get();
        }
        return view('unit.index',compact('unit'));
    }
}
