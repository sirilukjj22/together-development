<?php

namespace App\Http\Controllers;

use App\Models\TB_permission_department_revenues;
use App\Models\TB_departments;
use App\Models\TB_permission_department_menus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserDepartmentsController extends Controller
{
    public function index()
    {
        $departments = TB_departments::get();

        $title = "Department";
        return view('user_department.index', compact('departments', 'title'));
    }

    public function create()
    {
        $tb_menu = DB::table('tb_menu')->orderBy('sort', 'asc')->get();

        $tb_revenue_type = [
            'Front Desk Revenue', 'Guest Deposit Revenue', 'All Outlet Revenue', 'Agoda Revenue', 'Credit Card Hotel Revenue', 'Elexa EGAT Revenue',
            'Water Park Revenue', 'Credit Card Water Park Revenue', 'Other Revenue', 'No Category', 'Transfer', 'Update Time', 'Split Revenue', 'Edit / Delete',
        ];

        $tb_revenue_type2 = [
            'front_desk', 'guest_deposit', 'all_outlet', 'agoda', 'credit_card_hotel', 'elexa',
            'water_park', 'credit_water_park', 'other_revenue', 'no_category', 'transfer', 'time', 'split', 'edit',
        ];
        
        return view('user_department.create', compact('tb_menu', 'tb_revenue_type', 'tb_revenue_type2'));
    }

    public function store(Request $request)
    {
        // dd($request);
        try {

            $data_id = TB_departments::create([
                'department' => $request->name,
                'close_day' => $request->close_day ?? 0,
            ])->id;

            $menu_name = DB::table('tb_menu')->get();
            foreach ($menu_name as $key => $value) {
                $menu_name2 = 'menu_'.$value->name2.'_main';
                $menu_name3 = 'menu_'.$value->name2;

            if ($request->$menu_name2 == 1 || $request->$menu_name3 == 1) {
                $add_data = 'menu_'.$value->name2.'_add';
                $edit_data = 'menu_'.$value->name2.'_edit';
                $delete_data = 'menu_'.$value->name2.'_delete';
                $view_data = 'menu_'.$value->name2.'_view';
                $discount = 'menu_'.$value->name2.'_discount';
                $special_discount = 'menu_'.$value->name2.'_special_discount';

                TB_permission_department_menus::create([
                    'department_id' => $data_id,
                    'menu_id' => $value->id,
                    'add_data' => $request->$add_data ?? 0,
                    'edit_data' => $request->$edit_data ?? 0,
                    'delete_data' => $request->$delete_data ?? 0,
                    'view_data' => $request->$view_data ?? 0,
                    'discount' => $request->$discount ?? 0,
                    'special_discount' => $request->$special_discount ?? 0,
                ]);
            }
          }

          TB_permission_department_revenues::create([
            'department_id' => $data_id,
            'front_desk' => $request->front_desk ?? 0,
            'guest_deposit' => $request->guest_deposit ?? 0,
            'all_outlet' => $request->all_outlet ?? 0,
            'agoda' => $request->agoda ?? 0,
            'credit_card_hotel' => $request->credit_card_hotel ?? 0,
            'elexa' => $request->elexa ?? 0,
            'no_category' => $request->no_category ?? 0,
            'water_park' => $request->water_park ?? 0,
            'credit_water_park' => $request->credit_water_park ?? 0,
            'other_revenue' => $request->other_revenue ?? 0,
            'transfer' => $request->transfer ?? 0,
            'time' => $request->time ?? 0,
            'split' => $request->split ?? 0,
            'edit' => $request->edit ?? 0,
            'select_revenue_all' => $request->select_revenue_all ?? 0,
          ]);
          
        } catch (\Throwable $th) {
            return redirect(url('user-department'))->with('error', $th->getMessage());
            // return $th->getMessage();
        }

        return redirect(url('user-department'))->with('success', 'ระบบได้ทำการเพิ่มชื่อ '.$request->name_th.' ในระบบเรียบร้อยแล้ว');
    }

    public function edit($id)
    {
        $department = TB_departments::where('id', $id)->first();
        $tb_menu = DB::table('tb_menu')->orderBy('sort', 'asc')->get();

        $tb_revenue_type = [
            'Front Desk Revenue', 'Guest Deposit Revenue', 'All Outlet Revenue', 'Agoda Revenue', 'Credit Card Hotel Revenue', 'Elexa EGAT Revenue',
            'Water Park Revenue', 'Credit Card Water Park Revenue', 'Other Revenue', 'No Category', 'Transfer', 'Update Time', 'Split Revenue', 'Edit / Delete',
        ];

        $tb_revenue_type2 = [
            'front_desk', 'guest_deposit', 'all_outlet', 'agoda', 'credit_card_hotel', 'elexa',
            'water_park', 'credit_water_park', 'other_revenue', 'no_category', 'transfer', 'time', 'split', 'edit',
        ];

        return view('user_department.edit', compact('department', 'tb_menu', 'tb_revenue_type', 'tb_revenue_type2'));
    }

    public function update(Request $request)
    {
        try {

            TB_departments::where('id', $request->id)->update([
                'department' => $request->name,
                'close_day' => $request->close_day ?? 0,
            ]);

            $menu_name = DB::table('tb_menu')->get();
            TB_permission_department_menus::where('department_id', $request->id)->delete();
            TB_permission_department_revenues::where('department_id', $request->id)->delete();

            foreach ($menu_name as $key => $value) {
                $add_data = 'menu_'.$value->name2.'_add';
                $edit_data = 'menu_'.$value->name2.'_edit';
                $delete_data = 'menu_'.$value->name2.'_delete';
                $view_data = 'menu_'.$value->name2.'_view';
                $discount = 'menu_'.$value->name2.'_discount';
                $special_discount = 'menu_'.$value->name2.'_special_discount';
                $menu_name2 = 'menu_'.$value->name2.'_main';
                $menu_name3 = 'menu_'.$value->name2;

            if ($request->$menu_name2 == 1 || $request->$menu_name3 == 1) {
                TB_permission_department_menus::create([
                    'department_id' => $request->id,
                    'menu_id' => $value->id,
                    'add_data' => $request->$add_data ?? 0,
                    'edit_data' => $request->$edit_data ?? 0,
                    'delete_data' => $request->$delete_data ?? 0,
                    'view_data' => $request->$view_data ?? 0,
                    'discount' => $request->$discount ?? 0,
                    'special_discount' => $request->$special_discount ?? 0,
                ]);
            }
          }

          TB_permission_department_revenues::create([
                'department_id' => $request->id,
                'front_desk' => $request->front_desk ?? 0,
                'guest_deposit' => $request->guest_deposit ?? 0,
                'all_outlet' => $request->all_outlet ?? 0,
                'agoda' => $request->agoda ?? 0,
                'credit_card_hotel' => $request->credit_card_hotel ?? 0,
                'elexa' => $request->elexa ?? 0,
                'no_category' => $request->no_category ?? 0,
                'water_park' => $request->water_park ?? 0,
                'credit_water_park' => $request->credit_water_park ?? 0,
                'other_revenue' => $request->other_revenue ?? 0,
                'transfer' => $request->transfer ?? 0,
                'time' => $request->time ?? 0,
                'split' => $request->split ?? 0,
                'edit' => $request->edit ?? 0,
                'select_revenue_all' => $request->select_revenue_all ?? 0,
          ]);
          
        } catch (\Throwable $th) {
            return redirect(url('user-department'))->with('error', $th->getMessage());
            // return $th->getMessage();
        }

        return redirect(url('user-department'))->with('success', 'ระบบได้ทำการแก้ไขชื่อ '.$request->name_th.' ในระบบเรียบร้อยแล้ว');
    }
}
