<?php

namespace App\Http\Controllers;

use App\Models\Role_permission_menu;
use App\Models\Role_permission_menu_sub;
use App\Models\Role_permission_revenue;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        //  phpinfo();
        // dd($request);
        $request->validate([
            'name' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('name', 'password');
        if (Auth::attempt($credentials)) {

            if (Auth::user()->permission == 3) { // แบ่งแยกหน้าเฉพาะของนิว
                return redirect()->intended('/Company/index')
                        ->withSuccess('You have Successfully loggedin');
            } else {
                return redirect()->intended('sms-alert')
                        ->withSuccess('You have Successfully loggedin');
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
            return redirect(url('users', 'index'))->with('error', 'ระบบไม่สามารถทำการบันทึกได้');
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

    public function menu_name()
    {
        $menu = [
            'Company / Agent', 'Guest', 'Membership',
            'Message Inbox', 'Registration Request', 'Message Request',
            'Dummy Proposal', 'Proposal Request', 'Proposal', 
            'Banquet Event Order', 'Hotel Contract Rate Agreement', 'Proforma Invoice',
            'Billing Folio', 'Product Item', 'Debtor Agoda',
            'Debtor Elexa', 'Request Repair', 'Repair Job',
            'Preventive Maintenance', 'Daily Bank Transaction Revenue', 'Hotel & Water Park Revenue',
            'User (Setting)', 'Bank (Setting)', 'Quantity (Setting)',
            'Unit (Setting)', 'Prename (Setting)', 'Company Type (Setting)',
            'Company Market (Setting)', 'Company Event (Setting)', 'Booking (Setting)',
            'Template (Setting)'
        ];

        return $menu;
    }

    public function create(array $data)
    {
        // dd($data['front_desk']);

      try {
        $user_id = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'discount' => $data['discount'] ?? 0,
            'permission' => $data['permission'],
            'status' => 1
          ])->id;

          Role_permission_menu::create([
            'user_id' => $user_id,

            'profile' => $data['menu_profile'] ?? 0,
            'company' => $data['menu_company'] ?? 0,
            'guest' => $data['menu_guest'] ?? 0,

            'freelancer' => $data['menu_freelancer'] ?? 0,
            'membership' => $data['menu_membership'] ?? 0,
            'message_inbox' => $data['menu_message_inbox'] ?? 0,
            'registration_request' => $data['menu_registration_request'] ?? 0,
            'message_request' => $data['menu_message_request'] ?? 0,

            'document' => $data['menu_document'] ?? 0,
            'banquet_event_order' => $data['menu_banquet_event_order'] ?? 0,
            'proposal' => $data['menu_proposal'] ?? 0,
            'hotel_contact_rate' => $data['menu_hotel_contact_rate'] ?? 0,
            'proforma_invoice' => $data['menu_proforma_invoice'] ?? 0,
            'billing_folio' => $data['menu_billing_folio'] ?? 0,

            'debtor' => $data['menu_debtor'] ?? 0,
            'agoda' => $data['menu_agoda'] ?? 0,
            'elexa' => $data['menu_elexa'] ?? 0,

            'maintenance' => $data['menu_maintenance'] ?? 0,
            'request_repair' => $data['menu_request_repair'] ?? 0,
            'repair_job' => $data['menu_repair_job'] ?? 0,
            'preventive_maintenance' => $data['menu_preventive_maintenance'] ?? 0,

            'general_ledger' => $data['menu_general_ledger'] ?? 0,
            'sms_alert' => $data['menu_sms_alert'] ?? 0,
            'revenue' => $data['menu_revenue'] ?? 0,
            
            'setting' => $data['menu_setting'] ?? 0,
            'user' => $data['menu_user'] ?? 0,
            'bank' => $data['menu_bank'] ?? 0,
            'product_item' => $data['menu_product_item'] ?? 0,
            'quantity' => $data['menu_quantity'] ?? 0,
            'unit' => $data['menu_unit'] ?? 0,
            'prefix' => $data['menu_prefix'] ?? 0,
            'bank_company' => $data['menu_bank_company'] ?? 0,
            'company_type' => $data['menu_company_type'] ?? 0,
            'company_market' => $data['menu_company_market'] ?? 0,
            'company_event' => $data['menu_company_event'] ?? 0,
            'booking' => $data['menu_booking'] ?? 0,
            'document_template_pdf' => $data['menu_template'] ?? 0,

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
            'transfer' => $data['transfer'] ?? 0,
            'time' => $data['time'] ?? 0,
            'split' => $data['split'] ?? 0,
            'edit' => $data['edit'] ?? 0,
            'select_revenue_all' => $data['select_revenue_all'] ?? 0,
          ]);

          $menu_name = $this->menu_name();

          foreach ($menu_name as $key => $value) {
            Role_permission_menu_sub::create([
                'user_id' => $user_id,
                'menu_name' => $value,
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
