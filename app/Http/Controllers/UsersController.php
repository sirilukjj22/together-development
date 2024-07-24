<?php

namespace App\Http\Controllers;

use App\Models\Role_permission_menu;
use App\Models\Role_permission_menu_sub;
use App\Models\Role_permission_revenue;
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
        $users = User::where('status', 1)->get();

        $exp = explode('_', $menu);

        if (count($exp) > 1) {
            $search = $exp[1];

            if ($search == "all") {
                $users = User::get();
            }elseif ($search == 'ac') {
                $users = User::where('status', 1)->get();
            }else {
                $users = User::where('status', 0)->get();
            }
        }

        $title = "User";

        return view('users.index', compact('users', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tb_menu = DB::table('tb_menu')->get();

        $tb_revenue_type = [
            'Front Desk Revenue', 'Guest Deposit Revenue', 'All Outlet Revenue', 'Agoda Revenue', 'Credit Card Hotel Revenue', 'Elexa EGAT Revenue',
            'Water Park Revenue', 'Credit Card Water Park Revenue', 'No Category', 'Transfer', 'Update Time', 'Split Revenue', 'Edit / Delete',
        ];

        $tb_revenue_type2 = [
            'front_desk', 'guest_deposit', 'all_outlet', 'agoda', 'credit_card_hotel', 'elexa',
            'water_park', 'credit_water_park', 'no_category', 'transfer', 'time', 'split', 'edit',
        ];
        
        return view('users.create', compact('tb_menu', 'tb_revenue_type', 'tb_revenue_type2'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $tb_menu = DB::table('tb_menu')->get();

        $tb_revenue_type = [
            'Front Desk Revenue', 'Guest Deposit Revenue', 'All Outlet Revenue', 'Agoda Revenue', 'Credit Card Hotel Revenue', 'Elexa EGAT Revenue',
            'Water Park Revenue', 'Credit Card Water Park Revenue', 'No Category', 'Transfer', 'Update Time', 'Split Revenue', 'Edit / Delete',
        ];

        $tb_revenue_type2 = [
            'front_desk', 'guest_deposit', 'all_outlet', 'agoda', 'credit_card_hotel', 'elexa',
            'water_park', 'credit_water_park', 'no_category', 'transfer', 'time', 'split', 'edit',
        ];

        return view('users.edit', compact('user', 'tb_menu', 'tb_revenue_type', 'tb_revenue_type2'));
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
            ]);
    
            if ($request->password != '') {
                User::where('id', $request->id)->update([
                    'password' => Hash::make($request->password),
                ]);
            }
    
            Role_permission_menu::where('user_id', $request->id)->update([
                'profile' => $request->menu_profile ?? 0,
                'company' => $request->menu_company ?? 0,
                'guest' => $request->menu_guest ?? 0,

                'freelancer' => $request->menu_freelancer ?? 0,
                'membership' => $request->menu_membership ?? 0,
                'message_inbox' => $request->menu_message_inbox ?? 0,
                'registration_request' => $request->menu_registration_request ?? 0,
                'message_request' => $request->menu_message_request ?? 0,

                'document' => $request->menu_document ?? 0,
                'banquet_event_order' => $request->menu_banquet_event_order ?? 0,
                'proposal' => $request->menu_proposal ?? 0,
                'hotel_contact_rate' => $request->menu_hotel_contact_rate ?? 0,
                'proforma_invoice' => $request->menu_proforma_invoice ?? 0,
                'billing_folio' => $request->menu_billing_folio ?? 0,

                'debtor' => $request->menu_debtor ?? 0,
                'agoda' => $request->menu_agoda ?? 0,
                'elexa' => $request->menu_elexa ?? 0,

                'maintenance' => $request->menu_maintenance ?? 0,
                'request_repair' => $request->menu_request_repair ?? 0,
                'repair_job' => $request->menu_repair_job ?? 0,
                'preventive_maintenance' => $request->menu_preventive_maintenance ?? 0,

                'general_ledger' => $request->menu_general_ledger ?? 0,
                'sms_alert' => $request->menu_sms_alert ?? 0,
                'revenue' => $request->menu_revenue ?? 0,

                // 'report' => $request->report ?? 0,
                
                'setting' => $request->menu_setting ?? 0,
                'user' => $request->menu_user ?? 0,
                'bank' => $request->menu_bank ?? 0,
                'product_item' => $request->menu_product_item ?? 0,
                'quantity' => $request->menu_quantity ?? 0,
                'unit' => $request->menu_unit ?? 0,
                'prefix' => $request->menu_prefix ?? 0,
                'bank_company' => $request->menu_bank_company ?? 0,
                'company_type' => $request->menu_company_type ?? 0,
                'company_market' => $request->menu_company_market ?? 0,
                'company_event' => $request->menu_company_event ?? 0,
                'booking' => $request->menu_booking ?? 0,
                'document_template_pdf' => $request->menu_template ?? 0,

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
                'transfer' => $request->transfer ?? 0,
                'time' => $request->time ?? 0,
                'split' => $request->split ?? 0,
                'edit' => $request->edit ?? 0,
                'select_revenue_all' => $request->select_revenue_all ?? 0,
              ]);

              $menu_name = DB::table('tb_menu')->where('category_name', 2)->get();
              $check_menu = Role_permission_menu_sub::where('user_id', $request->id)->delete();
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
}
