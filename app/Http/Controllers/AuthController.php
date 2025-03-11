<?php

namespace App\Http\Controllers;

use App\Models\Role_permission_menu;
use App\Models\Role_permission_menu_sub;
use App\Models\Role_permission_revenue;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function registration()
    {
        return view('auth.registration');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function postLogin(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('name', 'password');
        if (Auth::attempt($credentials)) {

            if (Auth::user()->status == 1) {

                if (Auth::user()->permission == 3) { // แบ่งแยกหน้าเฉพาะของนิว
                    return redirect()->intended('/Company/index')->withSuccess('You have Successfully loggedin');
                } else {
                    User::where('id', Auth::user()->id)->update([
                        'current_branch' => 0
                    ]);
                    
                    if (Auth::user()->permission_branch == 3) {
                        return redirect()->intended('select-branch')->withSuccess('You have Successfully loggedin');
                    } elseif (Auth::user()->permission_branch == 2) {
                        User::where('id', Auth::user()->id)->update([
                            'current_branch' => 2
                        ]);
                        return redirect()->intended('harmony-sms-alert')->withSuccess('You have Successfully loggedin');
                    } elseif (Auth::user()->permission_branch == 1) {
                        User::where('id', Auth::user()->id)->update([
                            'current_branch' => 1
                        ]);
                        return redirect()->intended('sms-alert')->withSuccess('You have Successfully loggedin');
                    }
                }
            } else {
                Auth::logout();
                return redirect("login")->withSuccess('ไม่มีสิทธิ์ในการเข้าใช้งานระบบ กรุณาติดต่อผู้ดูแล !');
            }
            

        }

        return redirect("login")->withSuccess('Email Address หรือ Password ไม่ต้อง !');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function postRegistration(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $data = $request->all();
        $check = $this->create($data);

        if ($check == "Success") {
            return redirect(url('users', 'index'))->with('success', 'ระบบได้ทำการบันทึกเรียบร้อยแล้ว');
        } else {
            return redirect(url('users', 'index'))->with('error', $check);
        }

    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function dashboard()
    {
        if(Auth::check()){
            return view('dashboard');
        }

        return redirect("login")->withSuccess('Opps! You do not have access');
    }

    public function create(array $data)
    {
      try {
        $image_name = '';
        if (!empty($data['signature']) && $data['signature'] instanceof \Illuminate\Http\UploadedFile) {
            $path = 'upload/signature/';
            
            $file_name = 'signature-' . time() . '-' . $data['signature']->getClientOriginalName();
            $data['signature']->move($path, $file_name); // Move file to destination folder
            
            $image_name = $file_name; 
        }

        $user_id = User::create([
            'name' => $data['name'],
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'tel' => $data['telephone'],
            'signature' => $image_name,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'discount' => $data['discount'] ?? 0,
            'additional_discount' => $data['additional_discount'] ?? 0,
            'permission' => $data['permission'],
            'permission_edit' => $data['permission_edit'] ?? 0,
            'edit_close_day' => $data['close_day'] ?? 0,
            'status' => 1
          ])->id;

          Role_permission_menu::create([
            'user_id' => $user_id,

            'profile' => $data['menu_profile_main'] ?? 0,
            'company' => isset($data['menu_company']) ? $data['menu_company'] : 0,
            'guest' => $data['menu_guest'] ?? 0,

            'freelancer' => $data['menu_freelancer_main'] ?? 0,
            'membership' => $data['menu_membership'] ?? 0,
            'message_inbox' => $data['menu_message_inbox'] ?? 0,
            'registration_request' => $data['menu_registration_request'] ?? 0,
            'message_request' => $data['menu_message_request'] ?? 0,

            'document' => $data['menu_document_main'] ?? 0,
            'dummy_proposal' => $data['menu_dummy_proposal'] ?? 0,
            'document_request' => $data['menu_document_request'] ?? 0,
            'banquet_event_order' => $data['menu_banquet_event_order'] ?? 0,
            'proposal' => $data['menu_proposal'] ?? 0,
            'deposit_revenue' => $data['menu_deposit_revenue'] ?? 0,
            'hotel_contact_rate' => $data['menu_hotel_contact_rate'] ?? 0,
            'proforma_invoice' => $data['menu_proforma_invoice'] ?? 0,
            'receipt_payment' => $data['menu_receipt_payment'] ?? 0,
            'billing_folio' => $data['menu_billing_folio'] ?? 0,
            'additional' => $data['menu_additional'] ?? 0,
            'receipt_cheque' => $data['menu_receipt_cheque'] ?? 0,

            'debtor' => $data['menu_debtor_main'] ?? 0,
            'agoda' => $data['menu_agoda'] ?? 0,
            'elexa' => $data['menu_elexa'] ?? 0,

            'maintenance' => $data['menu_maintenance_main'] ?? 0,
            'request_repair' => $data['menu_request_repair'] ?? 0,
            'repair_job' => $data['menu_repair_job'] ?? 0,
            'preventive_maintenance' => $data['menu_preventive_maintenance'] ?? 0,

            'general_ledger' => $data['menu_general_ledger_main'] ?? 0,
            'sms_alert' => $data['menu_sms_alert'] ?? 0,
            'revenue' => $data['menu_revenue'] ?? 0,

            'setting' => $data['menu_setting_main'] ?? 0,
            'user' => $data['menu_user'] ?? 0,
            'department' => $data['menu_department'] ?? 0,
            'bank' => $data['menu_bank'] ?? 0,
            'product_item' => $data['menu_product_item_main'] ?? 0,
            'quantity' => $data['menu_quantity'] ?? 0,
            'unit' => $data['menu_unit'] ?? 0,
            'prefix' => $data['menu_prefix'] ?? 0,
            'bank_company' => $data['menu_bank_company'] ?? 0,
            'company_type' => $data['menu_company_type'] ?? 0,
            'company_market' => $data['menu_company_market'] ?? 0,
            'company_event' => $data['menu_company_event'] ?? 0,
            'booking' => $data['menu_booking'] ?? 0,
            'document_template_pdf' => $data['menu_document_template_pdf'] ?? 0,

            'report' => $data['menu_report_main'] ?? 0,
            'audit_hotel_water_park_revenue' => $data['menu_audit_hotel_water_park_revenue'] ?? 0,
            'report_hotel_water_park_revenue' => $data['menu_report_hotel_water_park_revenue'] ?? 0,
            'report_hotel_manual_charge' => $data['menu_report_hotel_manual_charge'] ?? 0,
            'agoda_revenue_report' => $data['menu_agoda_revenue_report'] ?? 0,
            'agoda_outstanding_report' => $data['menu_agoda_outstanding_report'] ?? 0,
            'agoda_account_receivable_report' => $data['menu_agoda_account_receivable_report'] ?? 0,
            'agoda_paid_revenue_report' => $data['menu_agoda_paid_revenue_report'] ?? 0,
            'elexa_revenue_report' => $data['menu_elexa_revenue_report'] ?? 0,
            'elexa_outstanding_report' => $data['menu_elexa_outstanding_report'] ?? 0,
            'elexa_account_receivable_report' => $data['menu_elexa_account_receivable_report'] ?? 0,
            'elexa_paid_revenue_report' => $data['menu_elexa_paid_revenue_report'] ?? 0,

            'select_menu_all' => $data['select_menu_all'] ?? 0,
          ]);

          Role_permission_revenue::create([
            'user_id' => $user_id,
            'front_desk' => $data['front_desk'] ?? 0,
            'guest_deposit' => $data['guest_deposit'] ?? 0,
            'all_outlet' => $data['all_outlet'] ?? 0,
            'agoda' => $data['agoda'] ?? 0,
            'credit_card_hotel' => $data['credit_card_hotel'] ?? 0,
            'elexa' => $data['elexa'] ?? 0,
            'no_category' => $data['no_category'] ?? 0,
            'water_park' => $data['water_park'] ?? 0,
            'credit_water_park' => $data['credit_water_park'] ?? 0,
            'other_revenue' => $data['other_revenue'] ?? 0,
            'transfer' => $data['transfer'] ?? 0,
            'time' => $data['time'] ?? 0,
            'split' => $data['split'] ?? 0,
            'edit' => $data['edit'] ?? 0,
            'select_revenue_all' => $data['select_revenue_all'] ?? 0,
          ]);

          $menu_name = DB::table('tb_menu')->where('category_name', 2)->get();

          if (isset($data['menu_product_item'])) {
            Role_permission_menu_sub::create([
                'user_id' => $user_id,
                'menu_name' => "Product Item",
                'add_data' => $data['menu_product_item_add'] ?? 0,
                'edit_data' => $data['menu_product_item_edit'] ?? 0,
                'delete_data' => $data['menu_product_item_delete'] ?? 0,
                'view_data' => $data['menu_product_item_view'] ?? 0,
            ]);
          }

          if (isset($data['menu_report'])) {
            Role_permission_menu_sub::create([
                'user_id' => $user_id,
                'menu_name' => "Product Item",
                'add_data' => $data['menu_report_add'] ?? 0,
                'edit_data' => $data['menu_report_edit'] ?? 0,
                'delete_data' => $data['menu_report_delete'] ?? 0,
                'view_data' => $data['menu_report_view'] ?? 0,
            ]);
          }

          foreach ($menu_name as $key => $value) {
            if (isset($data['menu_'.$value->name2]) && $data['menu_'.$value->name2] == 1) {
                Role_permission_menu_sub::create([
                    'user_id' => $user_id,
                    'menu_name' => $value->name_en,
                    'add_data' => $data['menu_'.$value->name2.'_add'] ?? 0,
                    'edit_data' => $data['menu_'.$value->name2.'_edit'] ?? 0,
                    'delete_data' => $data['menu_'.$value->name2.'_delete'] ?? 0,
                    'view_data' => $data['menu_'.$value->name2.'_view'] ?? 0,
                    'discount' => $data['menu_'.$value->name2.'_discount'] ?? 0,
                    'special_discount' => $data['menu_'.$value->name2.'_special_discount'] ?? 0,
                ]);
            }
          }

          // Report
          if (isset($data['menu_report']) && $data['menu_report'] == 1) {
            Role_permission_menu_sub::create([
                'user_id' => $user_id,
                'menu_name' => "Report",
                'add_data' => $data['menu_report_add'] ?? 0,
                'edit_data' => $data['menu_report_edit'] ?? 0,
                'delete_data' => $data['menu_report_delete'] ?? 0,
                'view_data' => $data['menu_report_view'] ?? 0,
                'discount' => $data['menu_report_discount'] ?? 0,
                'special_discount' => $data['menu_report_special_discount'] ?? 0,
            ]);
        }

      } catch (\Throwable $th) {
        return $th->getMessage();
      }

      return "Success";
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function logout() {
        Session::flush();
        Auth::logout();

        return Redirect('login');
    }
}
