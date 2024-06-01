<?php

namespace App\Http\Controllers;

use App\Models\Role_permission_menu;
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
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function create(array $data)
    {
        // dd($data['front_desk']);

      try {
        $user_id = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'permission' => $data['permission'],
            'status' => 1
          ])->id;
    
          Role_permission_menu::create([
            'user_id' => $user_id,
            'sms_alert' => $data['menu_sms_alert'] ?? 0,
            'revenue' => $data['menu_revenue'] ?? 0,
            'debtor' => $data['menu_debtor'] ?? 0,
            'agoda' => $data['menu_agoda'] ?? 0,
            'elexa' => $data['menu_elexa'] ?? 0,
            'profile' => $data['menu_profile'] ?? 0,
            'company' => $data['menu_company'] ?? 0,
            'guest' => $data['menu_guest'] ?? 0,
            'user' => $data['menu_user'] ?? 0,
            'bank' => $data['menu_bank'] ?? 0,
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
