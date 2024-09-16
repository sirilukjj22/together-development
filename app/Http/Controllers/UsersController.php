<?php

namespace App\Http\Controllers;

use App\Models\Role_permission_menu;
use App\Models\Role_permission_menu_sub;
use App\Models\Role_permission_revenue;
use App\Models\TB_departments;
use App\Models\TB_permission_department_menus;
use App\Models\TB_permission_department_revenues;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($menu)
    {
        $users = User::where('status', 1)->paginate(10);

        $exp = explode('_', $menu);

        if (count($exp) > 1) {
            $search = $exp[1];

            if ($search == "all") {
                $users = User::paginate(10);
            }elseif ($search == 'ac') {
                $users = User::where('status', 1)->paginate(10);
            }else {
                $users = User::where('status', 0)->paginate(10);
            }
        }

        $title = "User";

        return view('users.index', compact('users', 'title', 'menu'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tb_menu = DB::table('tb_menu')->orderBy('sort', 'asc')->get();
        $departments = TB_departments::get();

        $tb_revenue_type = [
            'Front Desk Revenue', 'Guest Deposit Revenue', 'All Outlet Revenue', 'Agoda Revenue', 'Credit Card Hotel Revenue', 'Elexa EGAT Revenue',
            'Water Park Revenue', 'Credit Card Water Park Revenue', 'Other Revenue', 'No Category', 'Transfer', 'Update Time', 'Split Revenue', 'Edit / Delete',
        ];

        $tb_revenue_type2 = [
            'front_desk', 'guest_deposit', 'all_outlet', 'agoda', 'credit_card_hotel', 'elexa',
            'water_park', 'credit_water_park', 'other_revenue', 'no_category', 'transfer', 'time', 'split', 'edit',
        ];
        
        return view('users.create', compact('tb_menu', 'departments', 'tb_revenue_type', 'tb_revenue_type2'));
    }

    public function search_table(Request $request)
    {
        $data = [];
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $search = $request->search_value;

        if (!empty($search)) {
            $data_query = User::where('status', 1)->where('name', 'like', '%' . $search . '%')->paginate($perPage);

        } else {
            $data_query = User::where('status', 1)->paginate($perPage);
        }

        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {

                $permission_name = '';
                $status_name = '';
                $btn_action = '';

                // ประเภทรายได้
                if ($value->status == 0) { $status_name = '<button type="button" class="btn btn-light-success btn-sm btn-status" value="'.$value->id.'">Disabled</button>'; } 
                if ($value->status == 1) { $status_name = '<button type="button" class="btn btn-light-success btn-sm btn-status" value="'.$value->id.'">Active</button>'; } 

                if($value->permission == 0) { $permission_name = 'General'; } 
                if($value->permission == 1) { $permission_name = 'Admin'; } 
                if($value->permission == 2) { $permission_name = 'Developer'; } 

                if ($value->close_day == 0 || Auth::user()->edit_close_day == 1) {
                    $btn_action .='<div class="dropdown">';
                        $btn_action .='<button type="button" class="btn" style="background-color: #2C7F7A; color:white;" data-bs-toggle="dropdown" data-toggle="dropdown">
                                            Select <span class="caret"></span>
                                        </button>';
                        $btn_action .='<ul class="dropdown-menu">';
                            if (User::roleMenuEdit('Users', Auth::user()->id) == 1) 
                            {
                                $btn_action .='<li class="button-li" onclick="window.location.href=\'' . url('user-edit/' . $value->id) . '\'">Edit</li>';
                            }
                        $btn_action .='</ul>';
                    $btn_action .='</div>';
                }

                $data[] = [
                    'id' => $key + 1,
                    'username' => $value->name,
                    'permission_name' => $permission_name,
                    'status_name' => $status_name,
                    'btn_action' => $btn_action,
                ];
            }
        }

        return response()->json([
            'data' => $data,
        ]);
    }

    public function paginate_table(Request $request)
    {
        $perPage = (int)$request->perPage;

        $query_sms = User::query();

            if ($request->status != 0) { 
                $query_sms->where('status', $request->status); 
            }

        $query_sms->orderBy('id', 'asc');

        if ($perPage == 10) {
            $data_query = $query_sms->limit($request->page.'0')->get();
        } else {
            $data_query = $query_sms->paginate($perPage);
        }

        $data = [];

        $page_1 = $request->page == 1 ? 1 : ($request->page - 1).'1';
        $page_2 = $request->page.'0';

        $perPage2 = $request->perPage > 10 ? $request->perPage : 10;

        if (isset($data_query) && count($data_query) > 0) {
            foreach ($data_query as $key => $value) {
                if (($key + 1) >= (int)$page_1 && ($key + 1) <= (int)$page_2 || (int)$perPage > 10 && $key < (int)$perPage2) {

                    $permission_name = '';
                    $status_name = '';
                    $btn_action = '';

                    // ประเภทรายได้
                    if ($value->status == 0) { $status_name = '<button type="button" class="btn btn-light-success btn-sm btn-status" value="'.$value->id.'">Disabled</button>'; } 
                    if ($value->status == 1) { $status_name = '<button type="button" class="btn btn-light-success btn-sm btn-status" value="'.$value->id.'">Active</button>'; } 

                    if($value->permission == 0) { $permission_name = 'General'; } 
                    if($value->permission == 1) { $permission_name = 'Admin'; } 
                    if($value->permission == 2) { $permission_name = 'Developer'; } 

                    if ($value->close_day == 0 || Auth::user()->edit_close_day == 1) {
                        $btn_action .='<div class="dropdown">';
                            $btn_action .='<button type="button" class="btn" style="background-color: #2C7F7A; color:white;" data-bs-toggle="dropdown" data-toggle="dropdown">
                                                Select <span class="caret"></span>
                                            </button>';
                            $btn_action .='<ul class="dropdown-menu">';
                                if (User::roleMenuEdit('Users', Auth::user()->id) == 1) 
                                {
                                    $btn_action .='<li class="button-li" onclick="window.location.href=\'' . url('user-edit/' . $value->id) . '\'">Edit</li>';
                                }
                            $btn_action .='</ul>';
                        $btn_action .='</div>';
                    }

                    $data[] = [
                        'number' => $key + 1,
                        'username' => $value->name,
                        'permission_name' => $permission_name,
                        'status_name' => $status_name,
                        'btn_action' => $btn_action,
                    ];
                }
            }
        }

        return response()->json([
                'data' => $data,
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
        $user = User::where('id', $id)->first();
        $tb_menu = DB::table('tb_menu')->orderBy('sort', 'asc')->get();
        $departments = TB_departments::get();

        $tb_revenue_type = [
            'Front Desk Revenue', 'Guest Deposit Revenue', 'All Outlet Revenue', 'Agoda Revenue', 'Credit Card Hotel Revenue', 'Elexa EGAT Revenue',
            'Water Park Revenue', 'Credit Card Water Park Revenue', 'Other Revenue', 'No Category', 'Transfer', 'Update Time', 'Split Revenue', 'Edit / Delete',
        ];

        $tb_revenue_type2 = [
            'front_desk', 'guest_deposit', 'all_outlet', 'agoda', 'credit_card_hotel', 'elexa',
            'water_park', 'credit_water_park', 'other_revenue', 'no_category', 'transfer', 'time', 'split', 'edit',
        ];

        return view('users.edit', compact('user', 'tb_menu', 'departments', 'tb_revenue_type', 'tb_revenue_type2'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            User::where('id', $request->id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'discount' => $request->discount ?? 0,
                'permission' => $request->permission,
                'permission_edit' => $request->permission_edit ?? 0,
                'edit_close_day' => $request->close_day ?? 0,
            ]);
    
            if ($request->password != '') {
                User::where('id', $request->id)->update([
                    'password' => Hash::make($request->password),
                ]);
            }
    
            Role_permission_menu::where('user_id', $request->id)->update([
                'profile' => $request->menu_profile_main ?? 0,
                'company' => $request->menu_company ?? 0,
                'guest' => $request->menu_guest ?? 0,

                'freelancer' => $request->menu_freelancer_main ?? 0,
                'membership' => $request->menu_membership ?? 0,
                'message_inbox' => $request->menu_message_inbox ?? 0,
                'registration_request' => $request->menu_registration_request ?? 0,
                'message_request' => $request->menu_message_request ?? 0,

                'document' => $request->menu_document_main ?? 0,
                'dummy_proposal' => $request->menu_dummy_proposal ?? 0,
                'document_request' => $request->menu_document_request ?? 0,
                'banquet_event_order' => $request->menu_banquet_event_order ?? 0,
                'proposal' => $request->menu_proposal ?? 0,
                'hotel_contact_rate' => $request->menu_hotel_contact_rate ?? 0,
                'proforma_invoice' => $request->menu_proforma_invoice ?? 0,
                'receipt_payment' => $request->menu_receipt_payment ?? 0,
                'billing_folio' => $request->menu_billing_folio ?? 0,

                'debtor' => $request->menu_debtor_main ?? 0,
                'agoda' => $request->menu_agoda ?? 0,
                'elexa' => $request->menu_elexa ?? 0,

                'maintenance' => $request->menu_maintenance_main ?? 0,
                'request_repair' => $request->menu_request_repair ?? 0,
                'repair_job' => $request->menu_repair_job ?? 0,
                'preventive_maintenance' => $request->menu_preventive_maintenance ?? 0,

                'general_ledger' => $request->menu_general_ledger_main ?? 0,
                'sms_alert' => $request->menu_sms_alert ?? 0,
                'revenue' => $request->menu_revenue ?? 0,
                
                'setting' => $request->menu_setting_main ?? 0,
                'user' => $request->menu_user ?? 0,
                'department' => $request->menu_department ?? 0,
                'bank' => $request->menu_bank ?? 0,
                'product_item' => $request->menu_product_item_main ?? 0,
                'quantity' => $request->menu_quantity ?? 0,
                'unit' => $request->menu_unit ?? 0,
                'prefix' => $request->menu_prefix ?? 0,
                'bank_company' => $request->menu_bank_company ?? 0,
                'company_type' => $request->menu_company_type ?? 0,
                'company_market' => $request->menu_company_market ?? 0,
                'company_event' => $request->menu_company_event ?? 0,
                'booking' => $request->menu_booking ?? 0,
                'document_template_pdf' => $request->menu_document_template_pdf ?? 0,
                'report' => $request->menu_report_main ?? 0,

                'select_menu_all' => $request->select_menu_all ?? 0,
              ]);
        
              Role_permission_revenue::where('user_id', $request->id)->update([
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

              $menu_name = DB::table('tb_menu')->where('category_name', 2)->get();
              $check_menu = Role_permission_menu_sub::where('user_id', $request->id)->delete();

              if (isset($request->menu_product_item)) {
                $add_data = 'menu_product_item_add';
                $edit_data = 'menu_product_item_edit';
                $delete_data = 'menu_product_item_delete';
                $view_data = 'menu_product_item_view';

                Role_permission_menu_sub::create([
                    'user_id' => $request->id,
                    'menu_name' => "Product Item",
                    'add_data' => $request->$add_data ?? 0,
                    'edit_data' => $request->$edit_data ?? 0,
                    'delete_data' => $request->$delete_data ?? 0,
                    'view_data' => $request->$view_data ?? 0,
                ]);
              }
          foreach ($menu_name as $key => $value) {
                $add_data = 'menu_'.$value->name2.'_add';
                $edit_data = 'menu_'.$value->name2.'_edit';
                $delete_data = 'menu_'.$value->name2.'_delete';
                $view_data = 'menu_'.$value->name2.'_view';
                $discount = 'menu_'.$value->name2.'_discount';
                $special_discount = 'menu_'.$value->name2.'_special_discount';
                $menu_name2 = 'menu_'.$value->name2;

            if ($request->$menu_name2 == 1) {
                Role_permission_menu_sub::create([
                    'user_id' => $request->id,
                    'menu_name' => $value->name_en,
                    'add_data' => $request->$add_data ?? 0,
                    'edit_data' => $request->$edit_data ?? 0,
                    'delete_data' => $request->$delete_data ?? 0,
                    'view_data' => $request->$view_data ?? 0,
                    'discount' => $request->$discount ?? 0,
                    'special_discount' => $request->$special_discount ?? 0,
                ]);
            }
          }

          // Report
          if ($request->menu_report == 1) {
            Role_permission_menu_sub::create([
                'user_id' => $request->id,
                'menu_name' => "Report",
                'add_data' => $request->menu_report_add ?? 0,
                'edit_data' => $request->menu_report_edit ?? 0,
                'delete_data' => $request->menu_report_delete ?? 0,
                'view_data' => $request->menu_report_view ?? 0,
                'discount' => $request->menu_report_discount ?? 0,
                'special_discount' => $request->menu_report_special_discount ?? 0,
            ]);
          }
          
        } catch (\Throwable $th) {
            // return redirect(url('users', 'index'))->with('error', 'ระบบไม่สามารถทำการแก้ไขรายการชื่อ '.$request->name_th.' ได้');
            return $th->getMessage();
        }

        return redirect(url('users', 'index'))->with('success', 'ระบบได้ทำการแก้ไขรายการชื่อ '.$request->name_th.' ในระบบเรียบร้อยแล้ว');
    }

    public function change_status($id)
    {
        $check_data = User::find($id);

        if ($check_data->status == 1) {
            User::where('id', $id)->update([
                'status' => 0,
            ]);
        }else{
            User::where('id', $id)->update([
                'status' => 1,
            ]);
        }
        
    }

    public function delete(Request $request)
    {
        if (isset($request->deleteID)) {
            User::where('id', $request->deleteID)->delete();
        } else {
            foreach ($request->radio_master_sub as $key => $value) {
                User::where('id', $value)->delete();
            }
        }
    }

    public function search_department($id)
    {
        $data = TB_departments::find($id);
        $data_menu = TB_permission_department_menus::where('department_id', $id)->get();
        $data_revenue = TB_permission_department_revenues::where('department_id', $id)->first();

        return response()->json([
            'data' => $data,
            'data_menu' => $data_menu,
            'data_revenue' => $data_revenue
        ]);
        
    }
}
